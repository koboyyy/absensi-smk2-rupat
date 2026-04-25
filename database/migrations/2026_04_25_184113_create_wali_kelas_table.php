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
        Schema::create('wali_kelas', function (Blueprint $table) {
            $table->id('wali_kelas_id');
            $table->foreignId('kelas_id')->constrained('kelas', 'kelas_id')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('gurus', 'guru_id')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['kelas_id'], 'wali_kelas_unique_per_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wali_kelas');
    }
};
