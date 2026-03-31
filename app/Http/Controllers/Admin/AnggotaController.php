<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
        public function index(Request $request)
    {
        $query = User::where('role', 'anggota');

        // Logika Pencarian (Search)
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }

        // Pagination: Menampilkan 10 data per halaman (withQueryString agar saat pindah halaman, parameter search tidak hilang)
        $anggota = $query->latest()->paginate(10)->withQueryString();

        return view('admin.anggota.index', compact('anggota'));
    }

    // Menampilkan form tambah anggota
    public function create()
    {
        return view('admin.anggota.create');
    }

    // Menyimpan data anggota baru ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email'        => 'required|string|email|max:255|unique:users',
            'username'     => 'nullable|string|max:50|unique:users',
            'password'     => 'required|string|min:8',
            'no_hp'        => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
        ]);

        User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'email'        => $request->email,
            'username'     => $request->username,
            'password'     => Hash::make($request->password),
            'role'         => 'anggota',
            'no_hp'        => $request->no_hp,
            'alamat'       => $request->alamat,
        ]);

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil ditambahkan.');
    }

    // Menampilkan form edit anggota
    public function edit($id)
    {
        $anggota = User::findOrFail($id);
        return view('admin.anggota.edit', compact('anggota'));
    }

    // Menyimpan perubahan data anggota
    public function update(Request $request, $id)
    {
        $anggota = User::findOrFail($id);

        $request->validate([
            'nama_lengkap' => 'required|string|max:100',
            'email'        => 'required|string|email|max:255|unique:users,email,' . $anggota->id,
            'username'     => 'nullable|string|max:50|unique:users,username,' . $anggota->id,
            'no_hp'        => 'nullable|string|max:20',
            'alamat'       => 'nullable|string',
        ]);

        $anggota->nama_lengkap = $request->nama_lengkap;
        $anggota->email = $request->email;
        $anggota->username = $request->username;
        $anggota->no_hp = $request->no_hp;
        $anggota->alamat = $request->alamat;

        // Jika password diisi, maka update password (jika kosong, biarkan password lama)
        if ($request->filled('password')) {
            $anggota->password = Hash::make($request->password);
        }

        $anggota->save();

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil diperbarui.');
    }

    // Menghapus data anggota
    public function destroy($id)
    {
        $anggota = User::findOrFail($id);
        $anggota->delete();

        return redirect()->route('admin.anggota.index')->with('success', 'Data anggota berhasil dihapus.');
    }
}