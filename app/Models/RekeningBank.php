<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekeningBank extends Model
{
    protected $fillable = ['nama_bank', 'no_rekening', 'atas_nama'];
}
