<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wa_settings', function (Blueprint $table) {
            $table->id();
            $table->string('fonnte_token')->nullable();
            $table->text('template_tagihan')->nullable();
            $table->text('template_pembayaran_diterima')->nullable();
            $table->text('template_pembayaran_ditolak')->nullable();
            $table->boolean('is_active')->default(false);
            $table->integer('tgl_tagihan_otomatis')->nullable()->default(null)->comment('Tanggal otomatis kirim tagihan tiap bulan (1-28)');
            $table->time('waktu_tagihan_otomatis')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wa_settings');
    }
};
