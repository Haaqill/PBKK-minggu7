<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    private $mahasiswa = [
        '5025241111' => [
            'nrp' => '5025241111',
            'nama' => 'Hanif Aqil Janardana',
            'jurusan' => 'Teknik Informatika',
            'fakultas' => 'Fakultas Teknologi Elektro dan Informatika Cerdas',
            'angkatan' => '2024',
            'email' => 'hanifaqil360@gmail.com',
            'status' => 'Mahasiswa Aktif'
        ],
    ];

    public function home(Request $request){
        $user = $request->query('user', 'Hanif');

        $mahasiswa = $this->mahasiswa['5025241111'];
        return view('home', compact('mahasiswa', 'user'));
    }
        
    public function profil($nrp){
        $mahasiswa = $this->mahasiswa['5025241111'];
        return view('mahasiswa', compact('mahasiswa'));
    }

    public function agent($tema = null){
        if ($tema=='ByeByeCleaner'){
            return view('project', [
                    'tema' => $tema,
                ]);
        }

        return 'General Assistant Agent';
    }

    public function hitung($ip1, $ip2){
        $ip1 = (float) $ip1;
        $ip2 = (float) $ip2;
        $ipk = ($ip1 + $ip2) / 2;

        return view('ipk', [
            'ip1' => $ip1,
            'ip2' => $ip2,
            'ipk' => $ipk
        ]);
    }
}
