<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pembayaran_kas', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel periode_kas
            $table->foreignId('periode_id')->constrained('periode_kas')->onDelete('cascade');
            
            // Relasi ke tabel users (sebagai anggota yang membayar)
            $table->foreignId('anggota_id')->constrained('users')->onDelete('cascade');
            
            // Relasi ke tabel users (sebagai admin yang memverifikasi, nullable karena awal bayar belum diverifikasi)
            $table->foreignId('verifikator_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->date('tanggal_bayar');
            $table->decimal('jumlah_bayar', 10, 2);
            $table->string('bukti_transfer');
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembayaran_kas');
    }
};