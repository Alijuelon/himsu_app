<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeKas extends Model
{
    use HasFactory;

    protected $table = 'periode_kas';

    protected $fillable = [
        'bulan',
        'tahun',
        'nominal_wajib',
        'status',
    ];

    // Relasi: Satu periode tagihan memiliki banyak data setoran/pembayaran anggota
    public function pembayaran()
    {
        return $this->hasMany(PembayaranKas::class, 'periode_id');
    }
}