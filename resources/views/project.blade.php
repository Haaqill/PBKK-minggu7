@extends('layouts.app')

@section('title', 'Project Agent')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="profile-card">

                <p class="text-uppercase text-secondary fw-semibold mb-2">
                    Project Idea
                </p>

                <h1 class="fw-bold mb-4">
                    {{ ucwords($tema) }}
                </h1>

                <p class="text-secondary lh-lg">
                    ByeByeCleaner adalah asisten AI otonom berbasis lokal yang mengelola dan merapikan tumpukan file usang di perangkat 
                    pengguna. Berbeda dari pembersih biasa yang hanya menghapus file besar, agen ini memahami konteks file untuk 
                    memberikan rekomendasi tindakan yang relevan—apakah sebuah file sebaiknya dibuang ke Trash, diarsipkan, 
                    atau di-backup ke cloud.
                </p>


                <div class="mt-4">

                    <h5 class="fw-bold mb-3">
                        Fitur Utama
                    </h5>

                    <ul class="text-secondary lh-lg">

                        <li>
                            Semantic Grouping: Mengelompokkan file berdasarkan topik 
                            (misal: memaketkan dokumen Word, PDF, dan gambar dari proyek renovasi tahun lalu ke dalam satu folder arsip).
                        </li>

                        <li>
                            Smart Deduplication: Mengidentifikasi lima versi revisi dari dokumen yang sama dan menyarankan pengguna 
                            untuk hanya menyimpan versi paling akhir.
                        </li>

                        <li>
                            Auto-Tagging: Membuat tag otomatis seperti #Dokumen_Pajak atau #Foto_Kenangan tanpa membebani pengguna dengan penginputan tag manual.
                        </li>

                    </ul>

                </div>


                

                <div class="text-center">

                    <a
                        href="{{ route('home') }}"
                        class="btn btn-dark px-4"
                    >
                        Kembali ke Home
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection