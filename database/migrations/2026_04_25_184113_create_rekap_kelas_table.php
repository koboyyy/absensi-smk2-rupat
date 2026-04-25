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
        Schema::create('rekap_kelas', function (Blueprint $table) {
            $table->id('rekap_kelas_id');
            $table->foreignId('kelas_id')->constrained('kelas', 'kelas_id')->cascadeOnDelete();
            $table->unsignedTinyInteger('bulan'); // 1-12
            $table->unsignedSmallInteger('tahun');
            $table->unsignedInteger('hadir')->default(0);
            $table->unsignedInteger('izin')->default(0);
            $table->unsignedInteger('sakit')->default(0);
            $table->unsignedInteger('alfa')->default(0);
            $table->timestamps();

            $table->unique(['kelas_id', 'bulan', 'tahun'], 'rekap_kelas_unique_periode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekap_kelas');
    }
};
