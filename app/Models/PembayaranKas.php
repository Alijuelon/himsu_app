<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembayaranKas extends Model
{
    use HasFactory;

    protected $table = 'pembayaran_kas';

    protected $fillable = [
        'periode_id',
        'anggota_id',
        'verifikator_id',
        'tanggal_bayar',
        'jumlah_bayar',
        'bukti_transfer',
        'status',
        'keterangan',
    ];

    // Relasi: Pembayaran ini milik periode tagihan bulan apa?
    public function periode()
    {
        return $this->belongsTo(PeriodeKas::class, 'periode_id');
    }

    // Relasi: Pembayaran ini dilakukan oleh anggota siapa?
    public function anggota()
    {
        return $this->belongsTo(User::class, 'anggota_id');
    }

    // Relasi: Pembayaran ini diverifikasi oleh admin siapa?
    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }
}