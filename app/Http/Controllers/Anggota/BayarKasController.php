<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\PeriodeKas;
use App\Models\PembayaranKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BayarKasController extends Controller
{
    // Menampilkan Form Pembayaran
    public function create()
    {
        // Ambil data periode kas yang statusnya masih 'aktif'
        $periodeAktif = PeriodeKas::where('status', 'aktif')->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();
        
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('anggota.bayar.create', compact('periodeAktif', 'namaBulan'));
    }

    // Memproses Data Pembayaran & Upload Bukti Transfer
    public function store(Request $request)
    {
        $request->validate([
            'periode_id'     => 'required|exists:periode_kas,id',
            'jumlah_bayar'   => 'required|numeric|min:1000',
            // Hapus 'image' dan tambahkan 'pdf' pada mimes
            'bukti_transfer' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048', 
        ]);

        $userId = Auth::id();

        // Cek apakah anggota ini sudah pernah membayar atau sedang pending di periode yang sama
        $cekPembayaran = PembayaranKas::where('anggota_id', $userId)
                                      ->where('periode_id', $request->periode_id)
                                      ->whereIn('status', ['pending', 'diterima'])
                                      ->first();

        if ($cekPembayaran) {
            $status = $cekPembayaran->status === 'diterima' ? 'sudah lunas.' : 'sedang menunggu verifikasi admin.';
            return redirect()->back()->with('error', 'Tagihan untuk periode ini ' . $status);
        }

        // Proses Upload Gambar ke storage/app/public/bukti_transfer
        $pathBukti = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $fileName = time() . '_' . $userId . '.' . $file->getClientOriginalExtension();
            // Simpan ke folder 'bukti_transfer' di dalam disk 'public'
            $pathBukti = $file->storeAs('bukti_transfer', $fileName, 'public');
        }

        // Simpan data ke database dengan status 'pending'
        PembayaranKas::create([
            'periode_id'     => $request->periode_id,
            'anggota_id'     => $userId,
            'tanggal_bayar'  => now(),
            'jumlah_bayar'   => $request->jumlah_bayar,
            'bukti_transfer' => $pathBukti,
            'status'         => 'pending',
        ]);

        return redirect()->route('anggota.riwayat.index')->with('success', 'Bukti pembayaran berhasil diunggah dan sedang menunggu verifikasi admin.');
    }
}