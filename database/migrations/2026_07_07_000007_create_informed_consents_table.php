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
        Schema::create('informed_consents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('skrining_medis_id')->constrained('skrining_medis')->cascadeOnDelete();
            $table->boolean('persetujuan_klien')->default(false);
            $table->boolean('persetujuan_pasangan')->default(false);
            $table->string('jenis_tindakan_medis', 50);
            $table->date('tanggal_persetujuan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informed_consents');
    }
};
