@extends('layouts.app')

@section('title', $project->judul)

@section('content')
<div class="container py-5">
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <h1>{{ $project->judul }}</h1>
    <dl class="row mt-4">
        <dt class="col-sm-3">Tema Agent</dt>
        <dd class="col-sm-9">{{ $project->tema_agent }}</dd>

        <dt class="col-sm-3">Deskripsi</dt>
        <dd class="col-sm-9">{{ $project->deskripsi ?: 'Tidak ada deskripsi.' }}</dd>

        @if (in_array(auth()->user()->role, ['dosen', 'asdos'], true))
            <dt class="col-sm-3">Pemilik</dt>
            <dd class="col-sm-9">{{ $project->user->name }}</dd>
        @endif

        <dt class="col-sm-3">API Key</dt>
        <dd class="col-sm-9">
            {{ $project->api_key_secure ? 'Tersimpan secara terenkripsi' : 'Belum diatur' }}
        </dd>
    </dl>

    <a href="{{ route('project.edit', $project) }}" class="btn btn-primary">Ubah</a>
    <form
        method="POST"
        action="{{ route('project.destroy', $project) }}"
        class="d-inline"
    >
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Pindahkan ke Recycle Bin</button>
    </form>
    <a href="{{ route('project.index') }}" class="btn btn-link">Kembali</a>
</div>
@endsection
