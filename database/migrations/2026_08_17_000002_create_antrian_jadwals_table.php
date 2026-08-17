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
        Schema::create('antrian_jadwals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jadwal_pelayanan_id')->constrained('jadwal_pelayanans')->cascadeOnDelete();
            $table->foreignId('peserta_kb_id')->constrained('peserta_kbs')->cascadeOnDelete();
            $table->unsignedInteger('nomor_antrian');
            $table->enum('status', ['terdaftar', 'hadir', 'tidak_hadir'])->default('terdaftar');
            $table->timestamps();

            $table->unique(['jadwal_pelayanan_id', 'peserta_kb_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('antrian_jadwals');
    }
};
