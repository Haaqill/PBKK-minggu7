<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', 'Profil Akademis')
    </title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="{{ request()->query('mode') === 'dark' ? 'page-dark' : 'page-light' }}">

    <nav class="navbar navbar-expand-lg border-bottom">
        <div class="container py-2">

            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                Profil Akademis
            </a>

            
            <div class="d-flex gap-2">

                <a href="{{ route('home') }}" class="btn {{ ($darkMode ?? false) ? 'btn-outline-light' : 'btn-outline-dark' }}">
                    Home
                </a>

                <a href="{{ route('hitung.ipk', ['ip1' => '3.40', 'ip2' => '3.75']) }}" class="btn {{ ($darkMode ?? false) ? 'btn-outline-light' : 'btn-outline-dark' }}">
                    Kalkulator IPK
                </a>

                <a href="{{ route('agent', ['tema' => 'ByeByeCleaner']) }}" class="btn {{ ($darkMode ?? false) ? 'btn-outline-light' : 'btn-outline-dark' }}">
                    Project Agent
                </a>

                @auth
                    <a href="{{ route('project.index') }}" class="btn {{ ($darkMode ?? false) ? 'btn-outline-light' : 'btn-outline-dark' }}">
                        Proyek AI
                    </a>
                @endauth

                @php
                    $isDark = request()->query('mode') === 'dark';
                @endphp

                <a
                    href="{{ $isDark
                        ? request()->fullUrlWithoutQuery('mode')
                        : request()->fullUrlWithQuery(['mode' => 'dark'])
                    }}"
                    class="nav-link mode-button"
                >
                    {{ $isDark ? 'Light Mode' : 'Dark Mode' }}
                </a>

                @can('input-nilai')
                    <a class="nav-link" href="{{ route('admin.nilai') }}">
                        Input Nilai Dosen
                    </a>
                @endcan

                @auth
                <a class="nav-link" href="{{ route(Auth::user()->dashboardRouteName()) }}">
                    {{ Auth::user()->name }}
                    <small>({{ ucfirst(Auth::user()->role) }})</small>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit" class="nav-link navbar-auth-button">
                        Logout
                    </button>
                </form>
            @else
                <a
                    href="{{ route('login') }}"
                    class="nav-link navbar-auth-button"
                >
                    Login
                </a>

                <a
                    href="{{ route('register') }}"
                    class="nav-link navbar-auth-button"
                >
                    Register
                </a>
            @endauth

            </div>

        </div>
    </nav>


    <main>
        @yield('content')
    </main>

    <footer class="border-top py-4 mt-5">

        <div class="container text-center">

            <small>
                Institut Teknologi Sepuluh Nopember
            </small>

        </div>

    </footer>

</body>
</html>