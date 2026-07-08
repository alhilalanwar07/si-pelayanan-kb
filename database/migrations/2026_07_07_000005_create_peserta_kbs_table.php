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
        Schema::create('peserta_kbs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('wilayah_id')->constrained('wilayahs')->cascadeOnDelete();
            $table->string('nik', 16)->unique();
            $table->string('nomor_hp', 20)->nullable();
            $table->string('nama_lengkap');
            $table->string('nama_suami_istri');
            $table->date('tanggal_lahir_istri');
            $table->text('alamat_lengkap');
            $table->string('penggunaan_asuransi', 50)->nullable();
            $table->string('pendidikan_istri', 50)->nullable();
            $table->string('pendidikan_suami', 50)->nullable();
            $table->string('pekerjaan_istri', 50)->nullable();
            $table->string('pekerjaan_suami', 50)->nullable();
            $table->integer('jumlah_anak_hidup')->default(0);
            $table->integer('jumlah_anak_laki')->default(0);
            $table->integer('jumlah_anak_perempuan')->default(0);
            $table->integer('umur_anak_terakhir')->nullable();
            $table->string('status_kepesertaan', 50)->nullable(); // baru, ganti_cara, ulangan
            $table->string('kb_terakhir', 100)->nullable();
            $table->string('status', 20)->default('menunggu'); // menunggu, terverifikasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_kbs');
    }
};
