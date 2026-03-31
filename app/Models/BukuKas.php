<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BukuKas extends Model
{
    use HasFactory;

    protected $table = 'buku_kas';

    protected $fillable = [
        'jenis_transaksi',
        'kategori',
        'tanggal',
        'nominal',
        'keterangan',
        'bukti_nota',
        'user_id',
    ];

    // Relasi: Transaksi ini dicatat oleh admin siapa?
    public function pencatat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}