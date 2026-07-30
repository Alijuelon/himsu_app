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

            $pendingAccounts = User::whereIn('role', ['anggota', 'ketua'])->where('status_verifikasi', 'pending')->count();

            return view('admin.dashboard', compact('totalPemasukan', 'totalPengeluaran', 'saldoKas', 'jumlahAnggota', 'transaksiTerbaru', 'chartData', 'currentYear', 'pendingAccounts'));
            
        } 
        // JIKA YANG LOGIN ADALAH KETUA UMUM
        elseif (Auth::user()->role === 'ketua') {
            return $this->ketuaDashboard();
        }
        // JIKA YANG LOGIN ADALAH ANGGOTA
        else {
            return $this->anggotaDashboard();
        }
    }

    /**
     * Dashboard untuk Ketua Umum.
     * Menampilkan ringkasan keuangan organisasi (read-only)
     * DAN info pembayaran kas pribadi sebagai anggota.
     */
    private function ketuaDashboard()
    {
        $userId = Auth::id();

        // === DATA SEBAGAI KETUA (Monitoring) ===
        $totalPemasukan = BukuKas::where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $totalPengeluaran = BukuKas::where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldoKas = $totalPemasukan - $totalPengeluaran;
        $jumlahAnggota = User::where('role', 'anggota')->count();
        $transaksiTerbaru = BukuKas::orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->take(5)->get();

        // Chart Data
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



        return view('ketua.dashboard', compact(
            'totalPemasukan', 'totalPengeluaran', 'saldoKas', 'jumlahAnggota',
            'transaksiTerbaru', 'chartData', 'currentYear'
        ));
    }

    /**
     * Dashboard untuk Anggota biasa.
     */
    private function anggotaDashboard()
    {
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

        // 4. Hitung jumlah bulan tunggakan (periode aktif yang BELUM dibayar oleh anggota ini)
        $periodeAktifIds = PeriodeKas::where('status', 'aktif')->pluck('id');

        $periodeSudahBayar = PembayaranKas::where('anggota_id', $userId)
            ->whereIn('status', ['diterima', 'pending'])
            ->whereIn('periode_id', $periodeAktifIds)
            ->pluck('periode_id');

        $tagihanAktif = $periodeAktifIds->diff($periodeSudahBayar)->count();

        // Lempar ke view anggota
        return view('anggota.dashboard', compact('totalDibayar', 'menungguVerifikasi', 'riwayatTerbaru', 'tagihanAktif'));
    }
}