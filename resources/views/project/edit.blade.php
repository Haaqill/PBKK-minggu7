@extends('layouts.app')

@section('title', 'Ubah Proyek')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Ubah Proyek</h1>
    @include('project._form', ['project' => $project])
</div>
@endsection
