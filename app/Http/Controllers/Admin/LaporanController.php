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
}