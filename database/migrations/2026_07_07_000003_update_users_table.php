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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('instansi_id')->nullable()->after('id')->constrained('instansis')->cascadeOnDelete();
            $table->string('username', 100)->unique()->after('name');
            $table->string('level_akses', 50)->default('admin')->after('password'); // admin, bidan, pimpinan
        });

        // Drop email-related columns (sistem ini login pakai username)
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['email', 'email_verified_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('email')->unique()->after('name');
            $table->timestamp('email_verified_at')->nullable()->after('email');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn(['instansi_id', 'username', 'level_akses']);
        });
    }
};
