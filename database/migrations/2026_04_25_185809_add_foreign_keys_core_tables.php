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
        Schema::table('jadwals', function (Blueprint $table) {
            $table->foreign('kelas_id')->references('kelas_id')->on('kelas')->cascadeOnDelete();
            $table->foreign('guru_id')->references('guru_id')->on('gurus')->cascadeOnDelete();
            $table->foreign('mapel_id')->references('mapel_id')->on('mapels')->cascadeOnDelete();
        });

        Schema::table('absensis', function (Blueprint $table) {
            $table->foreign('siswa_id')->references('siswa_id')->on('siswas')->cascadeOnDelete();
            $table->foreign('jadwal_id')->references('jadwal_id')->on('jadwals')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropForeign(['jadwal_id']);
        });

        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropForeign(['guru_id']);
            $table->dropForeign(['mapel_id']);
        });
    }
};
