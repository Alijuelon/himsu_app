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
            'nama_lengkap'      => 'Bendahara HIMSU', 
            'username'          => 'admin_himsu',
            'email'             => 'admin@himsu.com',
            'password'          => Hash::make('password'),
            'role'              => 'admin',
            'status_verifikasi' => 'verified',
            'tipe_anggota'      => 'anggota',
            'jabatan'           => 'bendahara',
            'no_hp'             => '081234567890',
            'provinsi'          => 'Sumatera Utara',
            'kabupaten'         => 'Kabupaten Tapanuli Utara',
            'kecamatan'         => 'Tarutung',
            'desa'              => 'Hutatoruan VII',
            'alamat'            => 'Sekretariat HIMSU Bengkalis',
        ]);

        // 2. Akun Anggota HIMSU
        User::create([
            'nama_lengkap'      => 'Darmawanti Sihombing',
            'username'          => 'anggota_darma',
            'email'             => 'anggota@himsu.com',
            'password'          => Hash::make('password'),
            'role'              => 'anggota',
            'status_verifikasi' => 'verified',
            'tipe_anggota'      => 'anggota',
            'jabatan'           => 'anggota devisi minat dan bakat',
            'no_hp'             => '089876543210',
            'provinsi'          => 'Sumatera Utara',
            'kabupaten'         => 'Kabupaten Samosir',
            'kecamatan'         => 'Pangururan',
            'desa'              => 'Pasar Pangururan',
            'alamat'            => 'Jl. Kelapapati Laut, Bengkalis',
        ]);
        // 3. Akun Ketua Umum HIMSU
        User::create([
            'nama_lengkap'      => 'Ketua Umum HIMSU',
            'username'          => 'ketua_himsu',
            'email'             => 'ketua@himsu.com',
            'password'          => Hash::make('password'),
            'role'              => 'ketua',
            'status_verifikasi' => 'verified',
            'tipe_anggota'      => 'anggota',
            'jabatan'           => 'Ketua Umum',
            'no_hp'             => '085311223344',
            'provinsi'          => 'Sumatera Utara',
            'kabupaten'         => 'Kabupaten Asahan',
            'kecamatan'         => 'Kisaran Timur',
            'desa'              => 'Mutiara',
            'alamat'            => 'Sekretariat HIMSU Bengkalis',
        ]);
    }
}