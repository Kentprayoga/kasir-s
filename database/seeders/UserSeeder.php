<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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
        // Membuat 10 user tambahan menggunakan factory
        User::factory(10)->create();
    }
}