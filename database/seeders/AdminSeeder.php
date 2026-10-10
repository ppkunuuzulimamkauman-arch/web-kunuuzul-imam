<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Satu role saja: admin.
        User::firstOrCreate(['email' => 'admin@kunuuzul.id'], [
            'name' => 'Admin Pondok',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true,
        ]);

        // Normalisasi: akun lama (superadmin/operator) jadi admin agar tetap bisa login.
        User::where('role', '!=', 'admin')->update(['role' => 'admin']);
    }
}
