<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin / Bendahara HIMSU
        User::create([
            'nama_lengkap' => 'Bendahara HIMSU', 
            'username'     => 'admin_himsu',
            'email'        => 'admin@himsu.com',
            'password'     => Hash::make('password'), // Password: password
            'role'         => 'admin',
            'no_hp'        => '081234567890',
            'alamat'       => 'Sekretariat HIMSU Bengkalis',
        ]);

        // 2. Akun Anggota HIMSU
        User::create([
            'nama_lengkap' => 'Darmawanti Sihombing',
            'username'     => 'anggota_darma',
            'email'        => 'anggota@himsu.com',
            'password'     => Hash::make('password'), // Password: password
            'role'         => 'anggota',
            'no_hp'        => '089876543210',
            'alamat'       => 'Jl. Kelapapati Laut, Bengkalis',
        ]);
    }
}