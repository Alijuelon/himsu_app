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

        $anggotaList = \App\Models\User::where('status_verifikasi', 'verified')->orderBy('nama_lengkap', 'asc')->get();
        $periodeList = \App\Models\PeriodeKas::orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->get();

        return view('admin.pembayaran.index', compact('pembayaran', 'namaBulan', 'anggotaList', 'periodeList'));
    }

    // Memproses Tambah Pembayaran Manual (Cash)
    public function storeManual(Request $request)
    {
        $request->validate([
            'anggota_id' => 'required|exists:users,id',
            'periode_id' => 'required|exists:periode_kas,id',
            'jumlah_bayar' => 'required|numeric|min:1',
            'keterangan' => 'nullable|string'
        ]);

        // Cek apakah sudah pernah bayar untuk periode ini
        $exists = PembayaranKas::where('anggota_id', $request->anggota_id)
            ->where('periode_id', $request->periode_id)
            ->whereIn('status', ['pending', 'diterima'])
            ->first();

        if ($exists) {
            return redirect()->back()->with('error', 'Pembayaran untuk periode ini sudah ada (Status: ' . $exists->status . ').');
        }

        DB::beginTransaction();

        try {
            $pembayaran = PembayaranKas::create([
                'anggota_id' => $request->anggota_id,
                'periode_id' => $request->periode_id,
                'tanggal_bayar' => now()->toDateString(),
                'jumlah_bayar' => $request->jumlah_bayar,
                'bukti_transfer' => 'manual', // Penanda bahwa ini bayar cash
                'status' => 'diterima',
                'verifikator_id' => Auth::id(),
                'keterangan' => $request->keterangan ?? 'Pembayaran tunai (Cash) ke admin'
            ]);

            // Langsung catat ke Buku Kas sebagai Pemasukan
            BukuKas::create([
                'jenis_transaksi' => 'pemasukan',
                'kategori'      => 'Iuran Kas',
                'tanggal'       => now(), 
                'nominal'       => $pembayaran->jumlah_bayar,
                'keterangan'    => 'Setoran kas periode ' . $pembayaran->periode->bulan . '/' . $pembayaran->periode->tahun . ' dari ' . $pembayaran->anggota->nama_lengkap . ' (Cash)',
                'user_id'       => Auth::id(),
            ]);

            DB::commit();

            // Kirim Notifikasi WA setelah commit
            try {
                $waSetting = WaSetting::first();
                if ($waSetting && $waSetting->is_active && $pembayaran->anggota && $pembayaran->anggota->no_hp) {
                    $template = $waSetting->template_pembayaran_diterima;
                    if (!empty($template)) {
                        $namaBulanArr = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        
                        $message = WhatsAppService::buildMessage($template, [
                            'nama' => $pembayaran->anggota->nama_lengkap,
                            'bulan' => $namaBulanArr[$pembayaran->periode->bulan] ?? $pembayaran->periode->bulan,
                            'tahun' => $pembayaran->periode->tahun,
                            'nominal' => number_format($pembayaran->jumlah_bayar, 0, ',', '.'),
                            'status' => 'DITERIMA',
                        ]);
                        WhatsAppService::sendMessage($pembayaran->anggota->no_hp, $message);
                    }
                }
            } catch (\Exception $waEx) {
                \Illuminate\Support\Facades\Log::error('Gagal kirim Notif WA bayar manual: ' . $waEx->getMessage());
            }

            return redirect()->back()->with('success', 'Pembayaran manual berhasil ditambahkan dan dicatat ke Buku Kas.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
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
                        $namaBulanArr = [
                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                        ];
                        
                        $message = WhatsAppService::buildMessage($template, [
                            'nama' => $pembayaran->anggota->nama_lengkap,
                            'bulan' => $namaBulanArr[$pembayaran->periode->bulan] ?? $pembayaran->periode->bulan,
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

    // Mengirim Ulang Notifikasi WA untuk Pembayaran yang sudah diverifikasi
    public function resendNotif(Request $request, $id)
    {
        try {
            $pembayaran = PembayaranKas::with(['anggota', 'periode'])->findOrFail($id);

            if ($pembayaran->status === 'pending') {
                return redirect()->back()->with('error', 'Pembayaran belum diverifikasi.');
            }

            $waSetting = WaSetting::first();
            if ($waSetting && $waSetting->is_active && $pembayaran->anggota && $pembayaran->anggota->no_hp) {
                $template = $pembayaran->status === 'diterima' ? $waSetting->template_pembayaran_diterima : $waSetting->template_pembayaran_ditolak;
                
                if (!empty($template)) {
                    $namaBulanArr = [
                        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                    ];
                    
                    $message = WhatsAppService::buildMessage($template, [
                        'nama' => $pembayaran->anggota->nama_lengkap,
                        'bulan' => $namaBulanArr[$pembayaran->periode->bulan] ?? $pembayaran->periode->bulan,
                        'tahun' => $pembayaran->periode->tahun,
                        'nominal' => number_format($pembayaran->jumlah_bayar, 0, ',', '.'),
                        'status' => strtoupper($pembayaran->status),
                    ]);
                    
                    $response = WhatsAppService::sendMessage($pembayaran->anggota->no_hp, $message);
                    
                    if ($response && isset($response['status']) && $response['status']) {
                        return redirect()->back()->with('success', 'Notifikasi WhatsApp berhasil dikirim ulang ke anggota.');
                    } else {
                        $reason = $response['reason'] ?? 'Gagal dari server Fonnte';
                        return redirect()->back()->with('error', 'Gagal mengirim WA: ' . $reason);
                    }
                } else {
                    return redirect()->back()->with('error', 'Template pesan WA belum diatur.');
                }
            } else {
                return redirect()->back()->with('error', 'WA Gateway belum aktif atau anggota tidak memiliki nomor HP valid.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}