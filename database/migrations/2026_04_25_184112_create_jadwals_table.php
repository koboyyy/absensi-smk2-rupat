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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id('jadwal_id');
            // FK ditambahkan di migrasi terpisah agar urutan aman
            $table->unsignedBigInteger('kelas_id')->index();
            $table->unsignedBigInteger('guru_id')->index();
            $table->unsignedBigInteger('mapel_id')->index();
            $table->string('hari', 15)->index();
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
