<?php

namespace Tests\Feature;

use App\Models\Proposal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProposalAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_mahasiswa_only_sees_and_opens_their_own_proposals(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $otherMahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $ownProposal = $mahasiswa->proposals()->create([
            'judul' => 'Proposal Saya',
            'deskripsi' => 'Deskripsi saya',
        ]);
        $otherProposal = $otherMahasiswa->proposals()->create([
            'judul' => 'Proposal Orang Lain',
            'deskripsi' => 'Deskripsi orang lain',
        ]);

        $this->actingAs($mahasiswa)
            ->get(route('mahasiswa.proposal.index'))
            ->assertOk()
            ->assertSee('Proposal Saya')
            ->assertDontSee('Proposal Orang Lain');

        $this->get(route('proposal.show', $ownProposal))->assertOk();
        $this->get(route('proposal.show', $otherProposal))->assertForbidden();
    }

    public function test_mahasiswa_can_create_update_and_delete_their_own_proposal(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($mahasiswa)
            ->post(route('mahasiswa.proposal.store'), [
                'judul' => 'Proposal Baru',
                'deskripsi' => 'Deskripsi awal',
            ])
            ->assertRedirect(route('mahasiswa.proposal.index'));

        $proposal = Proposal::query()->where('judul', 'Proposal Baru')->firstOrFail();
        $this->assertSame($mahasiswa->id, $proposal->user_id);

        $this->put(route('mahasiswa.proposal.update', $proposal), [
            'judul' => 'Proposal Diperbarui',
            'deskripsi' => 'Deskripsi diperbarui',
        ])
            ->assertRedirect(route('mahasiswa.proposal.index'));

        $this->assertDatabaseHas('proposals', [
            'id' => $proposal->id,
            'judul' => 'Proposal Diperbarui',
            'deskripsi' => 'Deskripsi diperbarui',
        ]);

        $this->delete(route('mahasiswa.proposal.destroy', $proposal))
            ->assertRedirect(route('mahasiswa.proposal.index'));

        $this->assertDatabaseMissing('proposals', ['id' => $proposal->id]);
    }

    public function test_mahasiswa_cannot_edit_or_delete_another_users_proposal(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $owner = User::factory()->create(['role' => 'mahasiswa']);
        $proposal = $owner->proposals()->create([
            'judul' => 'Proposal Pemilik',
            'deskripsi' => 'Deskripsi',
        ]);

        $this->actingAs($mahasiswa)
            ->get(route('mahasiswa.proposal.edit', $proposal))
            ->assertForbidden();
        $this->put(route('mahasiswa.proposal.update', $proposal), [
            'judul' => 'Diubah orang lain',
        ])->assertForbidden();
        $this->delete(route('mahasiswa.proposal.destroy', $proposal))
            ->assertForbidden();

        $this->assertDatabaseHas('proposals', [
            'id' => $proposal->id,
            'judul' => 'Proposal Pemilik',
        ]);
    }

    public function test_admin_pages_are_restricted_by_role(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $dosen = User::factory()->create(['role' => 'dosen']);
        $asdos = User::factory()->create(['role' => 'asdos']);

        $this->actingAs($mahasiswa)
            ->get(route('admin.index'))
            ->assertForbidden();
        $this->actingAs($dosen)->get(route('admin.index'))->assertOk();
        $this->actingAs($asdos)->get(route('admin.index'))->assertOk();
    }

    public function test_dosen_and_asdos_can_see_all_proposals_from_the_admin_page(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $asdos = User::factory()->create(['role' => 'asdos']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswa->proposals()->create([
            'judul' => 'Proposal untuk Ditinjau',
            'deskripsi' => 'Deskripsi proposal',
        ]);

        $this->actingAs($dosen)
            ->get(route('admin.proposal.index'))
            ->assertOk()
            ->assertSee('Proposal untuk Ditinjau')
            ->assertSee($mahasiswa->name);

        $this->actingAs($asdos)
            ->get(route('admin.proposal.index'))
            ->assertOk()
            ->assertSee('Proposal untuk Ditinjau');
    }

    public function test_only_dosen_can_access_input_nilai_and_see_its_link(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);
        $asdos = User::factory()->create(['role' => 'asdos']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($dosen)
            ->get(route('admin.nilai'))
            ->assertOk();
        $this->get('/')
            ->assertSee(route('admin.nilai'))
            ->assertSee('Input Nilai Dosen');

        $this->actingAs($asdos)
            ->get(route('admin.nilai'))
            ->assertForbidden();
        $this->get('/')->assertDontSee('Input Nilai Dosen');

        $this->actingAs($mahasiswa)
            ->get(route('admin.nilai'))
            ->assertForbidden();
        $this->get('/')->assertDontSee('Input Nilai Dosen');
    }

    public function test_dosen_and_asdos_are_sent_to_admin_after_login(): void
    {
        foreach (['dosen', 'asdos'] as $role) {
            $user = User::factory()->create(['role' => $role]);

            $this->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ])->assertRedirect(route('admin.index'));

            auth()->logout();
        }
    }

    public function test_logged_in_name_and_role_link_to_the_role_dashboard(): void
    {
        $dosen = User::factory()->create(['role' => 'dosen']);

        $this->actingAs($dosen)
            ->get('/')
            ->assertOk()
            ->assertSee('href="'.route('admin.index').'"', false)
            ->assertSee('Dosen')
            ->assertSee('(Dosen)');
    }
}
