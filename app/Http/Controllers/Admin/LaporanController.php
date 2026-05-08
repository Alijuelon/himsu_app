<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BukuKas;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanController extends Controller
{
    // Menampilkan halaman laporan dengan filter tanggal
    public function index(Request $request)
    {
        $query = BukuKas::query();

        // Jika ada filter tanggal mulai dan akhir
        if ($request->has('tgl_mulai') && $request->has('tgl_akhir') && $request->tgl_mulai != '' && $request->tgl_akhir != '') {
            $query->whereBetween('tanggal', [$request->tgl_mulai, $request->tgl_akhir]);
        }

        // Ambil semua data (tanpa pagination agar semua masuk laporan) diurutkan dari yang terlama ke terbaru
        $laporan = $query->orderBy('tanggal', 'asc')->get();

        // Hitung Total untuk Summary
        $totalPemasukan = $laporan->where('jenis_transaksi', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $laporan->where('jenis_transaksi', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        return view('admin.laporan.index', compact('laporan', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir'));
    }

    // Fungsi untuk meng-generate dan mendownload PDF
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

        // Load view khusus PDF (Kita akan buat file view-nya terpisah agar format cetaknya rapi)
        $pdf = Pdf::loadView('admin.laporan.pdf', compact('laporan', 'totalPemasukan', 'totalPengeluaran', 'saldoAkhir', 'periode'));
        
        // Atur ukuran kertas ke A4 Portrait
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('Laporan_Keuangan_HIMSU.pdf');
    }

    public function labaRugi(Request $request)
    {
        $year = $request->input('year', Carbon::now()->year);

        // Ambil pemasukan dan pengeluaran per bulan pada tahun yang dipilih
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

        // Siapkan array data 1-12 bulan
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

        return view('admin.laporan.laba-rugi', compact('year', 'labaRugiData', 'totalPemasukanTahun', 'totalPengeluaranTahun', 'totalLabaRugi'));
    }
}