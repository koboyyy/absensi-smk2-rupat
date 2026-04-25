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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id('absensi_id');
            // FK ditambahkan di migrasi terpisah agar urutan aman
            $table->unsignedBigInteger('siswa_id')->index();
            $table->unsignedBigInteger('jadwal_id')->index();
            $table->date('tanggal')->index();
            $table->enum('status', ['H', 'I', 'S', 'A'])->index();
            $table->string('foto_bukti')->nullable();
            $table->boolean('status_kirim')->default(false);
            $table->boolean('wali_validasi')->default(false);
            $table->timestamps();

            $table->unique(['siswa_id', 'jadwal_id', 'tanggal'], 'absensi_unique_per_pertemuan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
