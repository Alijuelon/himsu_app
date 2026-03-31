<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use App\Models\PembayaranKas;
use App\Models\BukuKas; // Pastikan Model BukuKas dipanggil
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    // 1. Fungsi untuk melihat riwayat pembayaran pribadi anggota
    public function index()
    {
        $riwayat = PembayaranKas::with(['periode', 'verifikator'])
                                ->where('anggota_id', Auth::id())
                                ->latest()
                                ->paginate(10);

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('anggota.riwayat.index', compact('riwayat', 'namaBulan'));
    }

    // 2. Fungsi untuk melihat transparansi Saldo Kas HIMSU
    public function saldo()
    {
        // Hitung total keseluruhan
        $totalPemasukan = BukuKas::where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $totalPengeluaran = BukuKas::where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Ambil riwayat arus kas (Buku Kas) untuk ditampilkan secara transparan
        $arusKas = BukuKas::orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->paginate(15);

        return view('anggota.saldo.index', compact('saldoAkhir', 'totalPemasukan', 'totalPengeluaran', 'arusKas'));
    }
}