<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    @if (app()->environment('local'))
        <div class="mt-6 border-t border-gray-200 pt-6">
            <h2 class="text-lg font-semibold text-gray-800">
                Auto-Login Test Accounts
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Pilih peran untuk menguji hak akses aplikasi.
            </p>

            <div class="mt-4 space-y-3">

                <form method="POST" action="{{ route('local.demo-login') }}">
                    @csrf
                    <input type="hidden" name="role" value="dosen">

                    <button
                        type="submit"
                        class="w-full rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
                    >
                        Login sebagai Dosen
                    </button>
                </form>

                <form method="POST" action="{{ route('local.demo-login') }}">
                    @csrf
                    <input type="hidden" name="role" value="asdos">

                    <button
                        type="submit"
                        class="w-full rounded-md bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-700"
                    >
                        Login sebagai Asdos
                    </button>
                </form>

                <form method="POST" action="{{ route('local.demo-login') }}">
                    @csrf
                    <input type="hidden" name="role" value="mahasiswa">

                    <button
                        type="submit"
                        class="w-full rounded-md bg-emerald-600 px-4 py-2 text-white hover:bg-emerald-700"
                    >
                        Login sebagai Mahasiswa
                    </button>
                </form>

            </div>
        </div>
    @endif
</x-guest-layout>
