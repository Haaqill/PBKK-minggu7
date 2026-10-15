<?php

namespace App\Http\Controllers;

use App\Http\Requests\BulkDeleteProjectsRequest;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;

class ProjectController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Project::class);

        $projects = Project::query()
            ->with('user:id,name')
            ->when(
                ! in_array(auth()->user()->role, ['dosen', 'asdos'], true),
                fn ($query) => $query->whereBelongsTo(auth()->user())
            )
            ->latest()
            ->paginate(15);

        return view('project.index', compact('projects'));
    }

    public function create(): View
    {
        Gate::authorize('create', Project::class);

        return view('project.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        $project = $this->transaction(function () use ($request): Project {
            $project = $request->user()->projects()->create($request->validated());
            $project->activityLogs()->create([
                'user_id' => $request->user()->id,
                'action' => 'created',
                'metadata' => ['judul' => $project->judul],
            ]);

            return $project;
        });

        return redirect()
            ->route('project.show', $project)
            ->with('success', 'Proyek berhasil dibuat.');
    }

    public function show(Project $project): View
    {
        Gate::authorize('view', $project);

        return view('project.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        Gate::authorize('update', $project);

        return view('project.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $this->transaction(function () use ($request, $project): void {
            $data = $request->validated();
            if (blank($data['api_key_secure'] ?? null)) {
                unset($data['api_key_secure']);
            }

            $project->update($data);
            $project->activityLogs()->create([
                'user_id' => $request->user()->id,
                'action' => 'updated',
                'metadata' => ['judul' => $project->judul],
            ]);
        });

        return redirect()
            ->route('project.show', $project)
            ->with('success', 'Proyek berhasil diperbarui.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);

        $this->transaction(function () use ($project): void {
            $project->delete();
            $project->activityLogs()->create([
                'user_id' => auth()->id(),
                'action' => 'deleted',
                'metadata' => ['judul' => $project->judul],
            ]);
        });

        return redirect()
            ->route('project.index')
            ->with('success', 'Proyek dipindahkan ke Recycle Bin.');
    }

    public function recycleBin(): View
    {
        Gate::authorize('viewAny', Project::class);

        $projects = Project::onlyTrashed()
            ->with('user:id,name')
            ->when(
                ! in_array(auth()->user()->role, ['dosen', 'asdos'], true),
                fn ($query) => $query->whereBelongsTo(auth()->user())
            )
            ->latest('deleted_at')
            ->paginate(15);

        return view('project.recycle-bin', compact('projects'));
    }

    public function restore(Project $project): RedirectResponse
    {
        abort_unless($project->trashed(), 404);
        Gate::authorize('restore', $project);

        $this->transaction(function () use ($project): void {
            $project->restore();
            $project->activityLogs()->create([
                'user_id' => auth()->id(),
                'action' => 'restored',
                'metadata' => ['judul' => $project->judul],
            ]);
        });

        return redirect()
            ->route('project.recycle-bin')
            ->with('success', 'Proyek berhasil dipulihkan.');
    }

    public function bulkDestroy(BulkDeleteProjectsRequest $request): RedirectResponse
    {
        $this->transaction(function () use ($request): void {
            $projects = Project::query()
                ->whereKey($request->validated('project_ids'))
                ->get();

            foreach ($projects as $project) {
                $project->delete();
                $project->activityLogs()->create([
                    'user_id' => $request->user()->id,
                    'action' => 'bulk_deleted',
                    'metadata' => ['judul' => $project->judul],
                ]);
            }
        });

        return redirect()
            ->route('project.index')
            ->with('success', 'Proyek terpilih dipindahkan ke Recycle Bin.');
    }

    private function transaction(callable $operation): mixed
    {
        DB::beginTransaction();

        try {
            $result = $operation();
            DB::commit();

            return $result;
        } catch (Throwable $exception) {
            DB::rollBack();

            throw $exception;
        }
    }
}
