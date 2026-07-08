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
        Schema::create('skrining_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('peserta_kb_id')->constrained('peserta_kbs')->cascadeOnDelete();
            $table->date('tanggal_skrining');
            $table->date('haid_terakhir')->nullable();
            $table->string('gravida_partus_abortus', 20)->nullable();
            $table->boolean('status_menyusui')->default(false);
            $table->boolean('rwyt_sakit_kuning')->default(false);
            $table->boolean('rwyt_pendarahan')->default(false);
            $table->boolean('rwyt_keputihan')->default(false);
            $table->boolean('rwyt_tumor')->default(false);
            $table->string('fisik_keadaan_umum', 20)->nullable();
            $table->decimal('fisik_berat_badan', 5, 2)->nullable();
            $table->string('fisik_tekanan_darah', 10)->nullable();
            $table->string('posisi_rahim', 30)->nullable();
            $table->boolean('hamil_diduga_hamil')->default(false);
            $table->boolean('pemeriksaan_dalam_radang')->default(false);
            $table->boolean('pemeriksaan_dalam_tumor')->default(false);
            $table->boolean('pemeriksaan_tambahan_diabetes')->default(false);
            $table->boolean('pemeriksaan_tambahan_pembekuan_darah')->default(false);
            $table->boolean('pemeriksaan_tambahan_orchitis')->default(false);
            $table->boolean('pemeriksaan_tambahan_tumor')->default(false);
            $table->text('alat_kontrasepsi_boleh_digunakan')->nullable(); // JSON or comma-separated
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skrining_medis');
    }
};
