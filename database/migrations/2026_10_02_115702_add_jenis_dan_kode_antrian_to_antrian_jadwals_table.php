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
        Schema::table('antrian_jadwals', function (Blueprint $table) {
            $table->string('jenis_pendaftaran', 20)->default('online')->after('nomor_antrian');
            $table->string('kode_antrian', 30)->nullable()->after('jenis_pendaftaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrian_jadwals', function (Blueprint $table) {
            $table->dropColumn(['jenis_pendaftaran', 'kode_antrian']);
        });
    }
};
