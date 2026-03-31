<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendAutoTagihan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wa:send-tagihan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim otomatis broadcast tagihan WA kepada anggota yang belum lunas sesuai tanggal setting.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $waSetting = \App\Models\WaSetting::first();

        // Cek apakah fitur aktif dan tanggal otomatis di-set
        if (!$waSetting || !$waSetting->is_active || empty($waSetting->fonnte_token) || empty($waSetting->template_tagihan) || empty($waSetting->tgl_tagihan_otomatis)) {
            $this->info('Layanan WA belum aktif, token kosong, atau tanggal otomatis belum diatur.');
            return;
        }

        // Cek apakah hari ini sama dengan tanggal tagihan otomatis
        $hariIni = (int) date('d');
        if ($hariIni !== (int) $waSetting->tgl_tagihan_otomatis) {
            $this->info("Hari ini tanggal {$hariIni}, bukan jadwal kirim otomatis (Tanggal {$waSetting->tgl_tagihan_otomatis}).");
            return;
        }

        // Ambil periode kas yang aktif di bulan dan tahun saat ini
        $bulanIni = (int) date('n');
        $tahunIni = (int) date('Y');

        $periode = \App\Models\PeriodeKas::where('bulan', $bulanIni)
            ->where('tahun', $tahunIni)
            ->where('status', 'aktif')
            ->first();

        if (!$periode) {
            $this->info("Tidak ada periode kas yang aktif untuk bulan {$bulanIni} tahun {$tahunIni}.");
            return;
        }

        // Ambil semua anggota yang nomor HP-nya ada
        $anggotas = \App\Models\User::where('role', 'anggota')
            ->whereNotNull('no_hp')
            ->where('no_hp', '!=', '')
            ->get();

        $successCount = 0;
        $failCount = 0;

        $namaBulan = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April', 
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus', 
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        foreach ($anggotas as $anggota) {
            // Cek sudah bayar atau belum
            $sudahBayar = \App\Models\PembayaranKas::where('anggota_id', $anggota->id)
                ->where('periode_id', $periode->id)
                ->where('status', 'diterima')
                ->exists();

            if (!$sudahBayar) {
                $message = \App\Services\WhatsAppService::buildMessage($waSetting->template_tagihan, [
                    'nama' => $anggota->nama_lengkap,
                    'bulan' => $namaBulan[$periode->bulan],
                    'tahun' => $periode->tahun,
                    'nominal' => number_format($periode->nominal_wajib, 0, ',', '.'),
                    'status' => 'BELUM LUNAS',
                ]);

                $send = \App\Services\WhatsAppService::sendMessage($anggota->no_hp, $message);
                if ($send) {
                    $successCount++;
                } else {
                    $failCount++;
                }

                // Beri jeda 1 detik agar tidak dianggap spam/overload oleh Fonnte
                sleep(1);
            }
        }

        $this->info("Broadcast tagihan otomatis selesai. Berhasil: {$successCount}, Gagal: {$failCount}.");
    }
}
