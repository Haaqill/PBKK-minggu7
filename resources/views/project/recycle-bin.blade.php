@extends('layouts.app')

@section('title', 'Recycle Bin Proyek')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Recycle Bin Proyek</h1>
        <a href="{{ route('project.index') }}" class="btn btn-outline-secondary">
            Kembali ke Proyek
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th scope="col">Judul</th>
                    <th scope="col">Tema Agent</th>
                    @if (in_array(auth()->user()->role, ['dosen', 'asdos'], true))
                        <th scope="col">Pemilik</th>
                    @endif
                    <th scope="col">Dihapus</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($projects as $project)
                    <tr>
                        <td>{{ $project->judul }}</td>
                        <td>{{ $project->tema_agent }}</td>
                        @if (in_array(auth()->user()->role, ['dosen', 'asdos'], true))
                            <td>{{ $project->user->name }}</td>
                        @endif
                        <td>{{ $project->deleted_at->format('d-m-Y H:i') }}</td>
                        <td>
                            <form method="POST" action="{{ route('project.restore', $project) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    Pulihkan
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="{{ in_array(auth()->user()->role, ['dosen', 'asdos'], true) ? 5 : 4 }}"
                            class="text-center"
                        >
                            Recycle Bin kosong.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $projects->links() }}
</div>
@endsection
