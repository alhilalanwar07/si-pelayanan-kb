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
        Schema::create('pelayanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_kb_id')->constrained('peserta_kbs')->cascadeOnDelete();
            $table->foreignId('alokon_id')->constrained('alokons')->cascadeOnDelete();
            $table->foreignId('skrining_medis_id')->nullable()->constrained('skrining_medis')->nullOnDelete();
            $table->date('tanggal_pelayanan');
            $table->text('keterangan')->nullable();
            $table->date('tanggal_kunjungan_ulang')->nullable();
            $table->date('tanggal_dicabut')->nullable();
            $table->string('penanggung_jawab_nama', 255)->nullable();
            $table->string('penanggung_jawab_nip', 50)->nullable();
            $table->string('penanggung_jawab_jabatan', 50)->nullable(); // dokter, bidan, perawat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelayanans');
    }
};
