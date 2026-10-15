@extends('layouts.app')

@section('title', 'Buat Proposal')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Buat Proposal</h1>

    <form action="{{ route('mahasiswa.proposal.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="judul" class="form-label">Judul Rencana Proyek</label>
            <input
                id="judul"
                name="judul"
                type="text"
                class="form-control"
                value="{{ old('judul') }}"
                maxlength="255"
                required
            >
            @error('judul')
                <p class="mt-1 text-danger">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea
                id="deskripsi"
                name="deskripsi"
                class="form-control"
                rows="4"
            >{{ old('deskripsi') }}</textarea>
            @error('deskripsi')
                <p class="mt-1 text-danger">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Ajukan Proposal</button>
        <a href="{{ route('mahasiswa.proposal.index') }}" class="btn btn-link">Kembali</a>
    </form>
</div>
@endsection