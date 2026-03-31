<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PembayaranKas;
use App\Models\BukuKas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Services\WhatsAppService;
use App\Models\WaSetting;

class PembayaranKasController extends Controller
{
    // Menampilkan daftar pembayaran dari anggota
    public function index(Request $request)
    {
        $query = PembayaranKas::with(['anggota', 'periode', 'verifikator']);

        // Filter Pencarian berdasarkan nama anggota
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('anggota', function($q) use ($search) {
                $q->where('nama_lengkap', 'like', '%' . $search . '%');
            });
        }

        // Filter berdasarkan Status (pending, diterima, ditolak)
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $pembayaran = $query->latest()->paginate(10)->withQueryString();

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('admin.pembayaran.index', compact('pembayaran', 'namaBulan'));
    }

    // Memproses Verifikasi (Terima / Tolak)
    public function verifikasi(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:diterima,ditolak',
            'keterangan' => 'nullable|string|max:255'
        ]);

        // Gunakan DB Transaction agar jika ada error, data tidak tersimpan setengah-setengah
        DB::beginTransaction();

        try {
            $pembayaran = PembayaranKas::with(['anggota', 'periode'])->findOrFail($id);

            // Cegah verifikasi ulang jika sudah diproses
            if ($pembayaran->status !== 'pending') {
                return redirect()->back()->with('error', 'Pembayaran ini sudah pernah diverifikasi sebelumnya.');
            }

            // Update status pembayaran
            $pembayaran->status = $request->status;
            $pembayaran->keterangan = $request->keterangan;
            $pembayaran->verifikator_id = Auth::id(); // Admin yang memverifikasi
            $pembayaran->save();

            // Jika DITERIMA, otomatis catat ke Buku Kas sebagai Pemasukan
            if ($request->status === 'diterima') {
                BukuKas::create([
                    'jenis_transaksi' => 'pemasukan',
                    'kategori'      => 'Iuran Kas',
                    'tanggal'       => now(), 
                    'nominal'       => $pembayaran->jumlah_bayar,
                    'keterangan'    => 'Setoran kas periode ' . $pembayaran->periode->bulan . '/' . $pembayaran->periode->tahun . ' dari ' . $pembayaran->anggota->nama_lengkap,
                    'user_id'       => Auth::id(),
                ]);
            }

            DB::commit();

            // Kirim Notifikasi WA setelah commit
            try {
                $waSetting = WaSetting::first();
                if ($waSetting && $waSetting->is_active && $pembayaran->anggota && $pembayaran->anggota->no_hp) {
                    $template = $request->status === 'diterima' ? $waSetting->template_pembayaran_diterima : $waSetting->template_pembayaran_ditolak;
                    if (!empty($template)) {
                        $message = WhatsAppService::buildMessage($template, [
                            'nama' => $pembayaran->anggota->nama_lengkap,
                            'bulan' => $pembayaran->periode->bulan,
                            'tahun' => $pembayaran->periode->tahun,
                            'nominal' => number_format($pembayaran->jumlah_bayar, 0, ',', '.'),
                            'status' => strtoupper($request->status),
                        ]);
                        WhatsAppService::sendMessage($pembayaran->anggota->no_hp, $message);
                    }
                }
            } catch (\Exception $waEx) {
                // Jangan gagalkan flow verifikasi hanya karena WA gagal
                \Illuminate\Support\Facades\Log::error('Gagal kirim Notif WA setelah verifikasi: ' . $waEx->getMessage());
            }

            $pesan = $request->status === 'diterima' ? 'Pembayaran berhasil diterima dan dicatat ke Buku Kas.' : 'Pembayaran telah ditolak.';
            return redirect()->back()->with('success', $pesan);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}