<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WaSetting;
use Illuminate\Http\Request;

class WaNotificationController extends Controller
{
    public function settings()
    {
        $setting = WaSetting::first();

        return view('admin.wa_notification.settings', compact('setting'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'fonnte_token' => 'nullable|string',
            'tgl_tagihan_otomatis' => 'nullable|integer|between:1,28',
            'template_tagihan' => 'nullable|string',
            'template_pembayaran_diterima' => 'nullable|string',
            'template_pembayaran_ditolak' => 'nullable|string',
        ]);

        $setting = WaSetting::first() ?? new WaSetting;
        $setting->fonnte_token = $request->fonnte_token;
        $setting->tgl_tagihan_otomatis = $request->tgl_tagihan_otomatis;
        $setting->template_tagihan = $request->template_tagihan;
        $setting->template_pembayaran_diterima = $request->template_pembayaran_diterima;
        $setting->template_pembayaran_ditolak = $request->template_pembayaran_ditolak;
        $setting->is_active = $request->has('is_active');
        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan Notifikasi WA berhasil disimpan.');
    }

    public function members(Request $request)
    {
        $query = User::where('role', 'anggota');
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('nama_lengkap', 'like', '%'.$request->search.'%')
                    ->orWhere('no_hp', 'like', '%'.$request->search.'%');
            });
        }
        $members = $query->paginate(15)->withQueryString();

        return view('admin.wa_notification.members', compact('members'));
    }

    public function updateMemberWa(Request $request, $id)
    {
        $request->validate([
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user = User::findOrFail($id);
        $user->no_hp = $request->no_hp;
        $user->save();

        return redirect()->back()->with('success', 'Nomor WA anggota berhasil diperbarui.');
    }

    /**
     * Fungsi Helper untuk mengirim WA via Fonnte
     */
    public static function sendWaMessage($no_hp, $message)
    {
        $setting = WaSetting::first();

        // Cek apakah fitur diaktifkan, token ada, dan no HP tidak kosong
        if (! $setting || ! $setting->is_active || empty($setting->fonnte_token) || empty($no_hp)) {
            return false;
        }

        // Sanitasi Nomor HP (Hapus spasi, strip, ubah 62/0 ke standar Fonnte)
        $no_hp = preg_replace('/[^0-9]/', '', $no_hp);

        // Eksekusi API Fonnte
        try {
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization' => $setting->fonnte_token, // Format Header Fonnte
            ])->post('https://api.fonnte.com/send', [
                'target' => $no_hp,
                'message' => $message,
                'countryCode' => '62',
            ]);

            return $response->successful();

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte API Error: '.$e->getMessage());

            return false;
        }
    }
}
