<?php

namespace App\Http\Controllers\Ketua;

use App\Http\Controllers\Controller;
use App\Models\BukuKas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    /**
     * Menampilkan halaman laporan keuangan (read-only) untuk Ketua Umum.
     */
    public function index(Request $request)
    {
        $query = BukuKas::query();

        if ($request->has('tgl_mulai') && $request->has('tgl_akhir') && $request->tgl_mulai != '' && $request->tgl_akhir != '') {
            $query->whereBetween('tanggal', [$request->tgl_mulai, $request->tgl_akhir]);
        }

        $laporan = $query->orderBy('tanggal', 'asc')->get();

        $totalPemasukan = $laporan->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $laporan->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return view('ketua.laporan.index', compact('laporan', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
    }

    /**
     * Menampilkan laporan Laba Rugi untuk Ketua Umum.
     */
    public function labaRugi(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        $pemasukan = BukuKas::where('jenis_transaksi', 'pemasukan')
            ->whereYear('tanggal', $year)
            ->selectRaw('MONTH(tanggal) as bulan, SUM(nominal) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')->toArray();

        $pengeluaran = BukuKas::where('jenis_transaksi', 'pengeluaran')
            ->whereYear('tanggal', $year)
            ->selectRaw('MONTH(tanggal) as bulan, SUM(nominal) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')->toArray();

        $labaRugiData = [];
        $totalLabaRugi = 0;
        $totalPemasukanTahun = 0;
        $totalPengeluaranTahun = 0;

        for ($i = 1; $i <= 12; $i++) {
            $in = $pemasukan[$i] ?? 0;
            $out = $pengeluaran[$i] ?? 0;
            $laba = $in - $out;

            $labaRugiData[$i] = [
                'pemasukan' => $in,
                'pengeluaran' => $out,
                'laba' => $laba
            ];

            $totalPemasukanTahun += $in;
            $totalPengeluaranTahun += $out;
            $totalLabaRugi += $laba;
        }

        return view('ketua.laporan.laba-rugi', compact('year', 'labaRugiData', 'totalPemasukanTahun', 'totalPengeluaranTahun', 'totalLabaRugi'));
    }

    /**
     * Export PDF laporan keuangan untuk Ketua Umum.
     */
    public function exportPdf(Request $request)
    {
        $query = BukuKas::query();
        $periode = "Semua Waktu";

        if ($request->has('tgl_mulai') && $request->has('tgl_akhir') && $request->tgl_mulai != '' && $request->tgl_akhir != '') {
            $query->whereBetween('tanggal', [$request->tgl_mulai, $request->tgl_akhir]);
            $periode = Carbon::parse($request->tgl_mulai)->translatedFormat('d F Y') . ' s/d ' . Carbon::parse($request->tgl_akhir)->translatedFormat('d F Y');
        }

        $laporan = $query->orderBy('tanggal', 'asc')->get();
        $totalPemasukan = $laporan->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $laporan->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporan', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir', 'periode'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Keuangan_HIMSU.pdf');
    }
}
