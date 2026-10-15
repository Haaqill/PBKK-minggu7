@extends('layouts.app')

@section('title', $proposal->judul)

@section('content')
<div class="container py-5">
    <h1 class="text-2xl font-bold">
        {{ $proposal->judul }}
    </h1>

    <p class="mt-4">
        {{ $proposal->deskripsi }}
    </p>

    <div class="mt-6 space-y-2 text-sm text-gray-600">
        <p>Pengaju: {{ $proposal->mahasiswa->name }}</p>
        <p>Status: {{ $proposal->status }}</p>
    </div>

    <a href="{{ auth()->user()->role === 'mahasiswa' ? route('mahasiswa.proposal.index') : route('admin.proposal.index') }}"
        class="mt-6 inline-block text-blue-600 hover:underline">
        Kembali
    </a>
</div>
@endsection