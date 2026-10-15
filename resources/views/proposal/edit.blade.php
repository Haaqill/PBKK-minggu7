@extends('layouts.app')

@section('title', 'Ubah Proposal')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Ubah Proposal</h1>

    <form action="{{ route('mahasiswa.proposal.update', $proposal) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="judul" class="form-label">Judul Rencana Proyek</label>
            <input
                id="judul"
                name="judul"
                type="text"
                class="form-control"
                value="{{ old('judul', $proposal->judul) }}"
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
            >{{ old('deskripsi', $proposal->deskripsi) }}</textarea>
            @error('deskripsi')
                <p class="mt-1 text-danger">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('mahasiswa.proposal.index') }}" class="btn btn-link">Kembali</a>
    </form>
</div>
@endsection
