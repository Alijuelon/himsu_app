<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BukuKas;
use App\Models\User;
use App\Models\PembayaranKas;
use App\Models\PeriodeKas;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // JIKA YANG LOGIN ADALAH ADMIN
        if (Auth::user()->role === 'admin') {
            $totalPemasukan = BukuKas::where('jenis_transaksi', 'pemasukan')->sum('nominal');
            $totalPengeluaran = BukuKas::where('jenis_transaksi', 'pengeluaran')->sum('nominal');
            $saldoKas = $totalPemasukan - $totalPengeluaran;
            $jumlahAnggota = User::where('role', 'anggota')->count();
            $transaksiTerbaru = BukuKas::orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->take(5)->get();

            // Prepare Data for Chart.js (Monthly Cashflow for the current year)
            $currentYear = date('Y');
            $monthlyPemasukan = array_fill(1, 12, 0);
            $monthlyPengeluaran = array_fill(1, 12, 0);
            
            $kasBulanan = BukuKas::whereYear('tanggal', $currentYear)
                ->selectRaw('MONTH(tanggal) as bulan, jenis_transaksi, SUM(nominal) as total')
                ->groupBy('bulan', 'jenis_transaksi')
                ->get();

            foreach ($kasBulanan as $kas) {
                if ($kas->jenis_transaksi == 'pemasukan') {
                    $monthlyPemasukan[$kas->bulan] = $kas->total;
                } else {
                    $monthlyPengeluaran[$kas->bulan] = $kas->total;
                }
            }
            
            $chartData = [
                'pemasukan' => array_values($monthlyPemasukan),
                'pengeluaran' => array_values($monthlyPengeluaran),
            ];

            return view('admin.dashboard', compact('totalPemasukan', 'totalPengeluaran', 'saldoKas', 'jumlahAnggota', 'transaksiTerbaru', 'chartData', 'currentYear'));
            
        } 
        // JIKA YANG LOGIN ADALAH ANGGOTA
        else {
            $userId = Auth::id();

            // 1. Hitung total uang yang sudah divalidasi (diterima) milik anggota ini
            $totalDibayar = PembayaranKas::where('anggota_id', $userId)
                                         ->where('status', 'diterima')
                                         ->sum('jumlah_bayar');

            // 2. Hitung berapa transaksi yang masih pending
            $menungguVerifikasi = PembayaranKas::where('anggota_id', $userId)
                                               ->where('status', 'pending')
                                               ->count();

            // 3. Ambil 5 riwayat bayar terakhir
            $riwayatTerbaru = PembayaranKas::with('periode')
                                           ->where('anggota_id', $userId)
                                           ->latest()
                                           ->take(5)
                                           ->get();

            // 4. Cek apakah ada periode tagihan yang aktif bulan ini
            $tagihanAktif = PeriodeKas::where('status', 'aktif')->count();

            // Lempar ke view anggota
            return view('anggota.dashboard', compact('totalDibayar', 'menungguVerifikasi', 'riwayatTerbaru', 'tagihanAktif'));
        }
    }
}