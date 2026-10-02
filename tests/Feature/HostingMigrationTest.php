<?php

use App\Models\User;
use Illuminate\Support\Facades\Schema;

test('route /run-migrate dapat diakses dan menjalankan migrasi', function () {
    $response = $this->get('/run-migrate');

    $response->assertOk();
    $response->assertSee('Pembaruan Skema Database Hosting');
    $response->assertSee('Kolom Baru (NIP Petugas)');

    expect(Schema::hasTable('users'))->toBeTrue();
    expect(Schema::hasColumn('users', 'nip'))->toBeTrue();
});

test('alias route /migrate juga dapat diakses', function () {
    $response = $this->get('/migrate');

    $response->assertOk();
    $response->assertSee('Pembaruan Skema Database Hosting');
});
