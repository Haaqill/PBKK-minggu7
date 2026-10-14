@extends('layouts.app')

@section('title', 'Kalkulator IPK')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-7">

            <div class="profile-card">

                <div class="text-center mb-5">

                    <p class="text-uppercase text-secondary fw-semibold">
                        Academic Calculator
                    </p>

                    <h1 class="fw-bold">
                        Kalkulator IPK
                    </h1>

                    <p class="text-secondary">
                        Masukkan IP dari dua semester.
                    </p>

                </div>


                <form
                    method="GET"
                    action=""
                    id="ipkForm"
                >

                    <div class="mb-4">

                        <label
                            for="ip1"
                            class="form-label fw-semibold"
                        >
                            IP Semester 1
                        </label>

                        <input
                            type="number"
                            id="ip1"
                            class="form-control form-control-lg"
                            min="0"
                            max="4"
                            step="0.01"
                            value="{{ $ip1 == 0 ? '' : $ip1 }}"
                            required
                        >

                    </div>


                    <div class="mb-4">

                        <label
                            for="ip2"
                            class="form-label fw-semibold"
                        >
                            IP Semester 2
                        </label>

                        <input
                            type="number"
                            id="ip2"
                            class="form-control form-control-lg"
                            min="0"
                            max="4"
                            step="0.01"
                            value="{{ $ip2 == 0 ? '' : $ip2 }}"
                            required
                        >

                    </div>


                    <button
                        type="submit"
                        class="btn btn-dark w-100 py-3"
                    >
                        Hitung IPK
                    </button>

                </form>


                @if($ip1 != 0 || $ip2 != 0)

                    <hr class="my-5">

                    <x-status-banner
                        message="Perhitungan IPK berhasil dilakukan."
                    />

                    <div class="text-center mt-4">

                        <p class="text-secondary">
                            IPK Rata-rata
                        </p>

                        <div
                            class="fw-bold"
                            style="font-size: 72px;"
                        >
                            {{ number_format($ipk, 2) }}
                        </div>

                    </div>

                @endif

            </div>

        </div>

    </div>

</div>


<script>

document
    .getElementById('ipkForm')
    .addEventListener('submit', function (event) {

        event.preventDefault();

        const ip1 = document.getElementById('ip1').value;
        const ip2 = document.getElementById('ip2').value;

        const nilaiIp1 = parseFloat(ip1);
        const nilaiIp2 = parseFloat(ip2);

        if (
            nilaiIp1 < 0 ||
            nilaiIp1 > 4 ||
            nilaiIp2 < 0 ||
            nilaiIp2 > 4
        ) {
            alert('Nilai IP harus berada antara 0 sampai 4.');
            return;
        }

        window.location.href =
            '/hitung-ipk/' + ip1 + '/' + ip2;

    });

</script>

@endsection