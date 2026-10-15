@extends('layouts.app')

@section('title', 'Proyek Agentic AI')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Proyek Agentic AI</h1>
        <div>
            <a href="{{ route('project.recycle-bin') }}" class="btn btn-outline-secondary">
                Recycle Bin
            </a>
            <a href="{{ route('project.create') }}" class="btn btn-primary">
                Buat Proyek
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @can('bulkDeleteAny', \App\Models\Project::class)
        <form method="POST" action="{{ route('project.bulk-destroy') }}">
            @csrf
            @error('project_ids')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
            @error('project_ids.*')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
    @endcan

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    @can('bulkDeleteAny', \App\Models\Project::class)
                        <th scope="col">Pilih</th>
                    @endcan
                    <th scope="col">Judul</th>
                    <th scope="col">Tema Agent</th>
                    @if (in_array(auth()->user()->role, ['dosen', 'asdos'], true))
                        <th scope="col">Pemilik</th>
                    @endif
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr>
                        @can('bulkDeleteAny', \App\Models\Project::class)
                            <td>
                                <input
                                    type="checkbox"
                                    name="project_ids[]"
                                    value="{{ $project->id }}"
                                    aria-label="Pilih {{ $project->judul }}"
                                >
                            </td>
                        @endcan
                        <td>{{ $project->judul }}</td>
                        <td>{{ $project->tema_agent }}</td>
                        @if (in_array(auth()->user()->role, ['dosen', 'asdos'], true))
                            <td>{{ $project->user->name }}</td>
                        @endif
                        <td>
                            <a href="{{ route('project.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                Detail
                            </a>
                            <a href="{{ route('project.edit', $project) }}" class="btn btn-sm btn-outline-secondary">
                                Ubah
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="{{ in_array(auth()->user()->role, ['dosen', 'asdos'], true) ? 5 : 3 }}"
                            class="text-center"
                        >
                            Belum ada proyek.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @can('bulkDeleteAny', \App\Models\Project::class)
        <button type="submit" class="btn btn-danger">
            Hapus Proyek Terpilih
        </button>
        </form>
    @endcan

    {{ $projects->links() }}
</div>
@endsection
