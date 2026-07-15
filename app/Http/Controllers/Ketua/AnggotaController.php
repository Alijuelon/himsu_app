<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AnggotaController extends Controller
{
    /**
     * Menampilkan data anggota (read-only) untuk Ketua Umum.
     * Ketua TIDAK bisa menambah, mengedit, atau menghapus data anggota.
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'anggota');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('no_hp', 'like', '%' . $search . '%');
            });
        }

        $anggota = $query->latest()->paginate(10)->withQueryString();

        return view('ketua.anggota.index', compact('anggota'));
    }
}
