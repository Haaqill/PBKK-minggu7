<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;

Route::get('/', [PageController::class, 'home']) ->name('home');

Route::get('/profil-mahasiswa/{nrp}', [PageController::class, 'profil']) 
    ->where('nrp', '[0-9]{10}')
    ->name('mahasiswa.profil');
    
Route::get('/ide-agent/{tema?}', [PageController::class, 'agent']) ->name('agent');

Route::get('/hitung-ipk/{ip1}/{ip2}', [PageController::class, 'hitung']) 
    ->where([
        'ip1' => '[0-4]+(\.[0-9]+)?',
        'ip2' => '[0-4]+(\.[0-9]+)?'
    ])
    ->name('hitung.ipk');

Route::fallback(function () {
    return response()->view('404', [], 404);    
});