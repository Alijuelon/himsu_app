<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WaSetting extends Model
{
    protected $fillable = [
        'fonnte_token',
        'template_tagihan',
        'template_pembayaran_diterima',
        'template_pembayaran_ditolak',
        'is_active',
        'tgl_tagihan_otomatis',
    ];
}
