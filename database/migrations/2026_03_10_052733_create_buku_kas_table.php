<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('buku_kas', function (Blueprint $table) {
            $table->id();
            $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
            $table->string('kategori', 50); // Contoh: Iuran Kas, Donasi, Konsumsi, Beli ATK
            $table->date('tanggal');
            $table->decimal('nominal', 12, 2);
            $table->text('keterangan');
            $table->string('bukti_nota')->nullable(); // Foto struk/nota pengeluaran
            
            // Relasi ke admin yang mencatat
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('buku_kas');
    }
};