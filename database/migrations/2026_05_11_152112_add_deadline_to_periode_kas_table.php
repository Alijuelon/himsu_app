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
        Schema::table('periode_kas', function (Blueprint $table) {
            $table->date('deadline')->nullable()->after('nominal_wajib')->comment('Tenggat waktu pembayaran kas');
        });
    }

    public function down(): void
    {
        Schema::table('periode_kas', function (Blueprint $table) {
            $table->dropColumn('deadline');
        });
    }
};
