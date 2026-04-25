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
        Schema::create('surat_izins', function (Blueprint $table) {
            $table->id('surat_id');
            $table->foreignId('siswa_id')->constrained('siswas', 'siswa_id')->cascadeOnDelete();
            $table->foreignId('ortu_id')->constrained('orang_tuas', 'ortu_id')->cascadeOnDelete();
            $table->foreignId('jadwal_id')->constrained('jadwals', 'jadwal_id')->cascadeOnDelete();
            $table->date('tanggal')->index();
            $table->text('keterangan')->nullable();
            $table->string('file_bukti')->nullable();
            $table->enum('status', ['pending', 'diterima', 'ditolak'])->default('pending')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_izins');
    }
};
