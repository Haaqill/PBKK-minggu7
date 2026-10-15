<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Dosen',
                'email' => 'dosen@gmail.com',
                'role' => 'dosen',
            ],
            [
                'name' => 'Asdos',
                'email' => 'asdos@gmail.com',
                'role' => 'asdos',
            ],
            [
                'name' => 'Hanif',
                'email' => 'hanif@gmail.com',
                'role' => 'mahasiswa',
            ],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'role' => $data['role'],
                    'password' => Hash::make(
                        'password'
                    ),
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}