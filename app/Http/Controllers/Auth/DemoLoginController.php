<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemoLoginController extends Controller
{
    public function login(Request $request)
    {
        abort_unless(app()->environment('local'), 404);

        $validated = $request->validate([
            'role' => ['required', 'in:dosen,asdos,mahasiswa'],
        ]);

        $user = User::query()
            ->where('email', match ($validated['role']) {
                'dosen' => 'dosen@gmail.com',
                'asdos' => 'asdos@gmail.com',
                'mahasiswa' => 'hanif@gmail.com',
            })
            ->where('role', $validated['role'])
            ->firstOrFail();

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route($user->dashboardRouteName());
    }
}