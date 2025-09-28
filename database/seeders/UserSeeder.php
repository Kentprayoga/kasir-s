<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User; // Import model User

class UserSeeder extends Seeder
{
    public function run()
    {
        // Membuat 1 user dengan email dan password default
        User::create([
            'name' => 'SASALERO',
            'email' => 'sasalero@kasir.com',
            'password' => Hash::make('sasalero'), // Pastikan password di-hash
        ]);

        // Opsional: Menambahkan lebih banyak user menggunakan factory atau manual
        // User::create([
        //     'name' => 'Jane Smith',
        //     'email' => 'janesmith@example.com',
        //     'password' => Hash::make('password123'),
        // ]);
    }
}