<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerifikasiAkunController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['anggota', 'ketua'])->orderBy('created_at', 'desc')->get();
        return view('admin.verifikasi-akun', compact('users'));
    }

    public function verify(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:pending,verified,rejected',
            'tipe_anggota' => 'nullable|in:anggota,bukan_anggota',
        ]);

        $user = User::findOrFail($id);
        $user->status_verifikasi = $request->status_verifikasi;
        $user->tipe_anggota = $request->tipe_anggota;
        $user->save();

        return back()->with('success', 'Status verifikasi akun berhasil diperbarui.');
    }
}
