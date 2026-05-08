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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status_verifikasi', ['pending', 'verified', 'rejected'])->default('pending')->after('role');
            $table->enum('tipe_anggota', ['anggota', 'bukan_anggota'])->nullable()->after('status_verifikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status_verifikasi', 'tipe_anggota']);
        });
    }
};
