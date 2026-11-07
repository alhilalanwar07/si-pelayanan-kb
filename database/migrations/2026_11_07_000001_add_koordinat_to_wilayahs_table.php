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
        Schema::table('wilayahs', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('nama_desa_kelurahan');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('link_gmaps', 500)->nullable()->after('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wilayahs', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'link_gmaps']);
        });
    }
};
