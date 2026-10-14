@extends('layouts.app')

@section('title', 'Home - Profil Akademis')

@section('content')

<section class="hero">

    <div class="hero-content">

        <x-status-banner
            message="Selamat datang, {{ $user }}!"
        />

        <p class="text-uppercase fw-semibold">
            Academic Profile
        </p>

        <h1 class="hero-title">
            Profil Akademis
        </h1>

        <p class="hero-subtitle">
            Selamat datang di aplikasi profil akademis mahasiswa ITS.
        </p>

    </div>

</section>


<section
    class="profile-section"
    id="profile"
>

    <div class="container">
        <div class="profile-header">
            <p class="text-uppercase mb-2">
                Student Profile
            </p>

            <h2>
                Profil Mahasiswa
            </h2>

        </div>


        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="profile-card">
                    <div class="d-flex align-items-center mb-5">
                        <div class="avatar me-4">
                            {{ strtoupper(
                                substr($mahasiswa['nama'], 0, 1)
                            ) }}
                        </div>

                        <div>
                            <h3 class="fw-bold mb-1">
                                {{ $mahasiswa['nama'] }}
                            </h3>


                            <p class="text-secondary mb-0">
                                {{ $mahasiswa['jurusan'] }}
                            </p>

                        </div>

                    </div>


                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="info-label">
                                NRP
                            </div>
                            <div class="info-value">
                                {{ $mahasiswa['nrp'] }}
                            </div>
                        </div>


                        <div class="col-md-6 mb-4">
                            <div class="info-label">
                                Fakultas
                            </div>
                            <div class="info-value">
                                {{ $mahasiswa['fakultas'] }}
                            </div>
                        </div>


                        <div class="col-md-6 mb-4">
                            <div class="info-label">
                                Angkatan
                            </div>
                            <div class="info-value">
                                {{ $mahasiswa['angkatan'] }}
                            </div>
                        </div>


                        <div class="col-md-6 mb-4">
                            <div class="info-label">
                                Status
                            </div>

                            <div class="info-value">
                                {{ $mahasiswa['status'] }}
                            </div>

                        </div>

                    </div>


                    <hr class="my-4">

                    <a
                        href="{{ route(
                            'mahasiswa.profil',
                            $mahasiswa['nrp']
                        ) }}"
                        class="btn btn-dark w-100 py-3"
                    >
                        Lihat Profil Mahasiswa
                    </a>


                </div>

            </div>

        </div>

    </div>

</section>

@endsection