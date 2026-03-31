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

        if ($request->has('search') && $request->search != '') {
            $query->where('tahun', 'like', '%' . $request->search . '%')
                  ->orWhere('bulan', 'like', '%' . $request->search . '%');
        }

        $periode = $query->orderBy('tahun', 'desc')->orderBy('bulan', 'desc')->paginate(10)->withQueryString();
        
        // Array pembantu untuk nama bulan
        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        return view('admin.periode.index', compact('periode', 'namaBulan'));
    }

    public function create()
    {
        return view('admin.periode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
            'nominal_wajib' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,tutup',
        ]);

        // Cek agar tidak ada periode bulan dan tahun yang dobel
        $cekDobel = PeriodeKas::where('bulan', $request->bulan)
                              ->where('tahun', $request->tahun)
                              ->first();
        if ($cekDobel) {
            return redirect()->back()->withInput()->with('error', 'Periode untuk bulan dan tahun tersebut sudah ada!');
        }

        PeriodeKas::create($request->all());

        return redirect()->route('admin.periode.index')->with('success', 'Periode kas baru berhasil ditambahkan.');
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
            'bulan' => 'required|integer|between:1,12',
            'tahun' => 'required|integer|min:2020',
            'nominal_wajib' => 'required|numeric|min:0',
            'status' => 'required|in:aktif,tutup',
        ]);

        $cekDobel = PeriodeKas::where('bulan', $request->bulan)
                              ->where('tahun', $request->tahun)
                              ->where('id', '!=', $id)
                              ->first();
        if ($cekDobel) {
            return redirect()->back()->withInput()->with('error', 'Periode untuk bulan dan tahun tersebut sudah digunakan!');
        }

        $periode->update($request->all());

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