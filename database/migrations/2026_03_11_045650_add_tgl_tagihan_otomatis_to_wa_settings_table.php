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
        Schema::table('wa_settings', function (Blueprint $table) {
            $table->integer('tgl_tagihan_otomatis')->nullable()->default(null)->after('is_active')->comment('Tanggal otomatis kirim tagihan tiap bulan (1-28)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wa_settings', function (Blueprint $table) {
            $table->dropColumn('tgl_tagihan_otomatis');
        });
    }
};
