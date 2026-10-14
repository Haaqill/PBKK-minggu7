@extends('layouts.app')

@section('title', '404 - Halaman Tidak Ditemukan')

@section('content')

<section class="error-page">

    <div class="container">

        <div class="error-code">
            404
        </div>


        <h1 class="error-title mt-4 mb-3">
            Halaman tidak ditemukan
        </h1>


        <p class="error-description mb-4">

            Maaf, halaman yang kamu cari tidak tersedia
            atau alamat yang kamu masukkan tidak benar.

        </p>


        <a
            href="{{ route('home') }}"
            class="btn btn-dark error-button"
        >
            ← Kembali ke Home
        </a>

    </div>

</section>

@endsection