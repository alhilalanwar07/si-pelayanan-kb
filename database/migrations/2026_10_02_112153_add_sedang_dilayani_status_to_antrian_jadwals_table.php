<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('antrian_jadwals', function (Blueprint $table) {
                $table->string('status', 30)->default('terdaftar')->change();
            });
        } else {
            DB::statement("ALTER TABLE antrian_jadwals MODIFY COLUMN status ENUM('terdaftar', 'sedang_dilayani', 'hadir', 'tidak_hadir') NOT NULL DEFAULT 'terdaftar'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::table('antrian_jadwals', function (Blueprint $table) {
                $table->string('status', 30)->default('terdaftar')->change();
            });
        } else {
            DB::statement("ALTER TABLE antrian_jadwals MODIFY COLUMN status ENUM('terdaftar', 'hadir', 'tidak_hadir') NOT NULL DEFAULT 'terdaftar'");
        }
    }
};
