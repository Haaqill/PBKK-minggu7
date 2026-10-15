@extends('layouts.app')

@section('title', 'Proposal')

@section('content')
<div class="container py-5">

        <div class="mb-6 d-flex align-items-center justify-content-between">
            <h1 class="text-2xl font-bold">
                {{ auth()->user()->role === 'mahasiswa' ? 'Proposal Saya' : 'Daftar Proposal' }}
            </h1>

            @if (auth()->user()->role === 'mahasiswa')
                <a href="{{ route('mahasiswa.proposal.create') }}" class="btn btn-primary">
                    Buat Proposal
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="mb-4 rounded border border-green-300 bg-green-50 p-3 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($proposals as $proposal)
            <div class="mb-4 rounded-lg border p-4">

                <h2 class="text-lg font-semibold">
                    {{ $proposal->judul }}
                </h2>

                <p class="mt-2 text-gray-600">
                    {{ $proposal->deskripsi }}
                </p>

                <div class="mt-3 text-sm">
                    @if (auth()->user()->role !== 'mahasiswa')
                        <span>Pengaju: {{ $proposal->mahasiswa->name }}</span>
                    @endif
                    <span class="ml-4">
                        Status: {{ $proposal->status }}
                    </span>
                </div>

                <a
                    href="{{ route('proposal.show', $proposal) }}"
                    class="mt-3 inline-block text-blue-600 hover:underline"
                >
                    Lihat detail
                </a>

                @can('update', $proposal)
                    <a
                        href="{{ route('mahasiswa.proposal.edit', $proposal) }}"
                        class="ml-3 mt-3 inline-block text-blue-600 hover:underline"
                    >
                        Ubah
                    </a>
                @endcan

                @can('delete', $proposal)
                    <form
                        method="POST"
                        action="{{ route('mahasiswa.proposal.destroy', $proposal) }}"
                        class="mt-3"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline">
                            Hapus
                        </button>
                    </form>
                @endcan

            </div>
        @empty
            <p>{{ auth()->user()->role === 'mahasiswa' ? 'Anda belum memiliki proposal.' : 'Belum ada proposal yang diajukan.' }}</p>
        @endforelse

    </div>
@endsection