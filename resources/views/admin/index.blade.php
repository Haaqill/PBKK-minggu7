@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
<div class="container py-5">
    <h1>Dashboard Admin</h1>
    <p>Halaman ini hanya dapat diakses dosen dan asdos.</p>
    <a href="{{ route('admin.proposal.index') }}" class="btn btn-primary">
        Lihat dan nilai proposal
    </a>

    @can('input-nilai')
        <a href="{{ route('admin.nilai') }}" class="btn btn-outline-primary">
            Input Nilai Dosen
        </a>
    @endcan
</div>
@endsection