<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Akun bawaan = admin utama (superadmin).
        User::firstOrCreate(['email' => 'admin@kunuuzul.id'], [
            'name' => 'Admin Pondok',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'is_active' => true,
        ]);

        // Normalisasi: role lama 'admin' naik jadi superadmin agar tetap bisa login.
        User::where('role', 'admin')->update(['role' => 'superadmin']);
    }
}
