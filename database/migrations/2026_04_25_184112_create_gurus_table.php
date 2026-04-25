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
        Schema::create('gurus', function (Blueprint $table) {
            $table->id('guru_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_guru');
            $table->string('nip')->nullable()->index();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_hp', 30)->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gurus');
    }
};
