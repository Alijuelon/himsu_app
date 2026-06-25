<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PeriodeKas;
use App\Models\User;
use App\Models\WaSetting;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PeriodeKasController extends Controller
{
    public function index(Request $request)
    {
        $query = PeriodeKas::query();

        // Ambil tahun dari request atau gunakan tahun saat ini
        $tahunFilter = $request->input('tahun', date('Y'));
        
        $query->where('tahun', $tahunFilter);

        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('bulan', 'like', '%' . $request->search . '%');
            });
        }

        // Tampilkan semua bulan (12) dalam tahun tersebut
        $periode = $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->paginate(12)->withQueryString();
        
        // Ambil daftar tahun unik yang ada di tabel, tambahkan tahun ini jika kosong
        $tahuns = PeriodeKas::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        if ($tahuns->isEmpty() && !$tahuns->contains(date('Y'))) {
            $tahuns = collect([date('Y')]);
        }

        // Array pembantu untuk nama bulan
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('admin.periode.index', compact('periode', 'namaBulan', 'tahunFilter', 'tahuns'));
    }

    public function tagihanList(Request $request)
    {
        $tahunFilter = $request->input('tahun', date('Y'));
        $bulanFilter = $request->input('bulan', date('n'));
        
        // Cari periode kas berdasarkan filter tahun dan bulan
        $periode = PeriodeKas::where('tahun', $tahunFilter)
                             ->where('bulan', $bulanFilter)
                             ->first();

        $anggotas = User::where('role', 'anggota')->get();

        // Data tunggakan: anggota yang belum bayar atau belum diterima
        $tunggakan = collect();
        
        if ($periode) {
            $pembayarans = \App\Models\PembayaranKas::where('periode_id', $periode->id)
                ->get()
                ->keyBy('anggota_id');
                
            foreach ($anggotas as $anggota) {
                $pembayaran = $pembayarans->get($anggota->id);
                // Jika belum bayar, ditolak, atau pending -> masuk kategori tagihan/tunggakan
                if (!$pembayaran || $pembayaran->status != 'diterima') {
                    $tunggakan->push([
                        'anggota' => $anggota,
                        'status' => $pembayaran ? $pembayaran->status : 'belum_bayar'
                    ]);
                }
            }
        }

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        // Ambil daftar tahun yang ada di database periode_kas
        $tahuns = PeriodeKas::select('tahun')->distinct()->orderBy('tahun', 'desc')->pluck('tahun');
        if ($tahuns->isEmpty()) {
            $tahuns = collect([date('Y')]);
        }
        
        $waSetting = \App\Models\WaSetting::first();

        return view('admin.periode.tagihan_list', compact('tunggakan', 'periode', 'tahunFilter', 'bulanFilter', 'namaBulan', 'tahuns', 'waSetting'));
    }

    public function create()
    {
        return view('admin.periode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020',
            'nominal_wajib' => 'required|numeric|min:0',
        ]);

        // Create 12 months for the given year
        for ($i = 1; $i <= 12; $i++) {
            $cekDobel = PeriodeKas::where('bulan', $i)
                                  ->where('tahun', $request->tahun)
                                  ->first();
            
            if (!$cekDobel) {
                $data = [
                    'bulan' => $i,
                    'tahun' => $request->tahun,
                    'nominal_wajib' => $request->nominal_wajib,
                    'status' => 'aktif',
                ];
                
                if (\Illuminate\Support\Facades\Schema::hasColumn('periode_kas', 'deadline')) {
                    $data['deadline'] = date('Y-m-t', strtotime($request->tahun . '-' . sprintf('%02d', $i) . '-01'));
                }
                
                PeriodeKas::create($data);
            }
        }

        return redirect()->route('admin.periode.index')->with('success', 'Periode kas (12 bulan) berhasil ditambahkan untuk tahun ' . $request->tahun . '.');
    }

    public function show($id)
    {
        $periode = PeriodeKas::findOrFail($id);
        
        $anggotas = User::where('role', 'anggota')->get();
        
        // Ambil data pembayaran untuk periode ini, index berdasarkan anggota_id
        $pembayarans = \App\Models\PembayaranKas::where('periode_id', $id)
            ->get()
            ->keyBy('anggota_id');
            
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('admin.periode.show', compact('periode', 'anggotas', 'pembayarans', 'namaBulan'));
    }

    public function edit($id)
    {
        $periode = PeriodeKas::findOrFail($id);
        return view('admin.periode.edit', compact('periode'));
    }

    public function update(Request $request, $id)
    {
        $periode = PeriodeKas::findOrFail($id);

        $request->validate([
            'nominal_wajib' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,tutup',
        ]);

        $periode->update([
            'nominal_wajib' => $request->nominal_wajib,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.periode.index')->with('success', 'Data periode kas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $periode = PeriodeKas::findOrFail($id);
        $periode->delete();

        return redirect()->route('admin.periode.index')->with('success', 'Periode kas berhasil dihapus.');
    }

    public function sendTagihan(Request $request, $id)
    {
        $periode = PeriodeKas::findOrFail($id);
        $waSetting = WaSetting::first();

        if (!$waSetting || !$waSetting->is_active || empty($waSetting->fonnte_token) || empty($waSetting->template_tagihan)) {
            return redirect()->back()->with('error', 'Layanan WhatsApp belum aktif atau pengaturan template tagihan belum lengkap.');
        }

        // Ambil semua anggota yang nomor HP-nya tidak kosong
        $anggotas = User::where('role', 'anggota')
            ->whereNotNull('no_hp')
            ->where('no_hp', '!=', '')
            ->get();

        $successCount = 0;
        $failCount = 0;

        foreach ($anggotas as $anggota) {
            // Cek apakah anggota sudah pernah membayar dan diterima di periode ini
            $sudahBayar = \App\Models\PembayaranKas::where('anggota_id', $anggota->id)
                ->where('periode_id', $periode->id)
                ->where('status', 'diterima')
                ->exists();

            if (!$sudahBayar) {
                // Build message
                $namaBulan = [
                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                ];

                $message = WhatsAppService::buildMessage($waSetting->template_tagihan, [
                    'nama' => $anggota->nama_lengkap,
                    'bulan' => $namaBulan[$periode->bulan],
                    'tahun' => $periode->tahun,
                    'nominal' => number_format($periode->nominal_wajib, 0, ',', '.'),
                    'status' => 'BELUM LUNAS', // Default context for billing
                ]);

                // Send Message
                $send = WhatsAppService::sendMessage($anggota->no_hp, $message);
                if ($send) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            }
        }

        return redirect()->back()->with('success', "Broadcast tagihan selesai. Berhasil: {$successCount}, Gagal: {$failCount}.");
    }
}