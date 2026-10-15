<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_sees_only_owned_projects_and_can_create_one(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $otherStudent = User::factory()->create(['role' => 'mahasiswa']);
        $otherStudent->projects()->create(['judul' => 'Private Project']);

        $this->actingAs($student)
            ->get(route('project.index'))
            ->assertOk()
            ->assertDontSee('Private Project')
            ->assertSee(route('project.create'));

        $this->post(route('project.store'), [
            'judul' => 'Agentic AI Idea',
            'deskripsi' => 'A helpful research agent.',
            'api_key_secure' => 'secret-api-key',
            'user_id' => $otherStudent->id,
        ])->assertRedirect();

        $project = Project::query()->where('judul', 'Agentic AI Idea')->firstOrFail();
        $this->assertSame($student->id, $project->user_id);
        $this->assertSame('Ollama', $project->tema_agent);
        $this->assertSame('secret-api-key', $project->api_key_secure);
        $this->assertNotSame('secret-api-key', $project->getRawOriginal('api_key_secure'));
        $this->assertArrayNotHasKey('api_key_secure', $project->toArray());
        $this->assertDatabaseHas('project_activity_logs', [
            'project_id' => $project->id,
            'user_id' => $student->id,
            'action' => 'created',
        ]);
    }

    public function test_student_cannot_view_or_edit_another_users_project(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $owner = User::factory()->create(['role' => 'mahasiswa']);
        $project = $owner->projects()->create(['judul' => 'Private Project']);

        $this->actingAs($student)
            ->get(route('project.show', $project))
            ->assertForbidden();
        $this->get(route('project.edit', $project))->assertForbidden();
        $this->put(route('project.update', $project), [
            'judul' => 'Hijacked Project',
        ])->assertForbidden();
        $this->delete(route('project.destroy', $project))->assertForbidden();
    }

    public function test_project_detail_and_edit_forms_render_without_revealing_the_api_key(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $project = $student->projects()->create([
            'judul' => 'Visible project title',
            'api_key_secure' => 'private-api-key',
        ]);

        $this->actingAs($student)
            ->get(route('project.show', $project))
            ->assertOk()
            ->assertSee('Visible project title')
            ->assertSee('Tersimpan secara terenkripsi')
            ->assertDontSee('private-api-key');

        $this->get(route('project.edit', $project))
            ->assertOk()
            ->assertSee('value="Visible project title"', false)
            ->assertSee('name="_method" value="PUT"', false)
            ->assertDontSee('private-api-key');
    }

    public function test_dosen_and_asdos_can_view_projects_from_all_users(): void
    {
        $owner = User::factory()->create(['role' => 'mahasiswa']);
        $owner->projects()->create(['judul' => 'Student Project']);

        foreach (['dosen', 'asdos'] as $role) {
            $staff = User::factory()->create(['role' => $role]);

            $this->actingAs($staff)
                ->get(route('project.index'))
                ->assertOk()
                ->assertSee('Student Project');
        }
    }

    public function test_project_validation_errors_return_to_the_form_without_saving(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($student)
            ->from(route('project.create'))
            ->post(route('project.store'), [
                'judul' => 'Remember this title',
                'deskripsi' => str_repeat('x', 10001),
                'api_key_secure' => 'do-not-flash-this-secret',
            ])
            ->assertRedirect(route('project.create'))
            ->assertSessionHasErrors(['deskripsi']);

        $this->assertDatabaseCount('projects', 0);
        $this->assertDatabaseCount('project_activity_logs', 0);
        $this->assertSame(null, session()->getOldInput('api_key_secure'));
        $this->get(route('project.create'))
            ->assertOk()
            ->assertSee('value="Remember this title"', false);
    }

    public function test_update_validation_errors_leave_the_project_unchanged(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $project = $student->projects()->create(['judul' => 'Original title']);

        $this->actingAs($student)
            ->from(route('project.edit', $project))
            ->put(route('project.update', $project), [
                'judul' => '',
            ])
            ->assertRedirect(route('project.edit', $project))
            ->assertSessionHasErrors('judul');

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'judul' => 'Original title',
        ]);
        $this->assertDatabaseCount('project_activity_logs', 0);
    }

    public function test_project_creation_rolls_back_when_activity_logging_fails(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        DB::statement(
            "CREATE TRIGGER fail_project_activity_log BEFORE INSERT ON project_activity_logs
            BEGIN SELECT RAISE(ABORT, 'activity log unavailable'); END"
        );
        $this->withoutExceptionHandling();

        try {
            $this->actingAs($student)->post(route('project.store'), [
                'judul' => 'Must roll back',
            ]);
            $this->fail('The activity log failure should abort project creation.');
        } catch (QueryException $exception) {
            $this->assertStringContainsString('activity log unavailable', $exception->getMessage());
        }

        $this->assertDatabaseCount('projects', 0);
        $this->assertDatabaseCount('project_activity_logs', 0);
    }

    public function test_student_can_update_and_soft_delete_their_project_then_restore_it(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $project = $student->projects()->create([
            'judul' => 'Original title',
            'api_key_secure' => 'keep-this-key',
        ]);
        $otherStudent = User::factory()->create(['role' => 'mahasiswa']);
        $otherDeletedProject = $otherStudent->projects()->create([
            'judul' => 'Private deleted project',
        ]);
        $otherDeletedProject->delete();

        $this->actingAs($student)
            ->put(route('project.update', $project), [
                'judul' => 'Updated title',
                'api_key_secure' => '',
            ])
            ->assertRedirect(route('project.show', $project));

        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'judul' => 'Updated title',
        ]);
        $this->assertSame('keep-this-key', $project->fresh()->api_key_secure);

        $this->delete(route('project.destroy', $project))
            ->assertRedirect(route('project.index'));
        $this->assertSoftDeleted('projects', ['id' => $project->id]);

        $this->get(route('project.recycle-bin'))
            ->assertOk()
            ->assertSee('Updated title')
            ->assertDontSee('Private deleted project');

        $this->post(route('project.restore', $project->id))
            ->assertRedirect(route('project.recycle-bin'));
        $this->assertNotSoftDeleted('projects', ['id' => $project->id]);
        $this->assertDatabaseHas('project_activity_logs', [
            'project_id' => $project->id,
            'action' => 'restored',
        ]);
    }

    public function test_student_cannot_restore_another_users_deleted_project(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $owner = User::factory()->create(['role' => 'mahasiswa']);
        $project = $owner->projects()->create(['judul' => 'Deleted project']);
        $project->delete();

        $this->actingAs($student)
            ->post(route('project.restore', $project->id))
            ->assertForbidden();

        $this->assertSoftDeleted('projects', ['id' => $project->id]);
    }

    public function test_admin_can_soft_delete_more_than_ten_projects_at_once(): void
    {
        $admin = User::factory()->create(['role' => 'dosen']);
        $owner = User::factory()->create(['role' => 'mahasiswa']);
        $projects = collect(range(1, 12))
            ->map(fn (int $number) => $owner->projects()->create([
                'judul' => "Project {$number}",
            ]));

        $this->actingAs($admin)
            ->post(route('project.bulk-destroy'), [
                'project_ids' => $projects->pluck('id')->all(),
            ])
            ->assertRedirect(route('project.index'));

        $this->assertSame(12, Project::onlyTrashed()->count());
        $this->assertSame(12, $admin->projectActivityLogs()->where('action', 'bulk_deleted')->count());
    }

    public function test_student_cannot_bulk_delete_projects(): void
    {
        $student = User::factory()->create(['role' => 'mahasiswa']);
        $project = $student->projects()->create(['judul' => 'Project']);

        $this->actingAs($student)
            ->post(route('project.bulk-destroy'), [
                'project_ids' => [$project->id],
            ])
            ->assertForbidden();

        $this->assertNotSoftDeleted('projects', ['id' => $project->id]);
    }
}
