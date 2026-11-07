<?php

use App\Models\Alokon;
use App\Models\Instansi;
use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('dashboard explicitly displays low stock alokon names and quantities', function () {
    $instansi = Instansi::create([
        'nama_instansi' => 'DPPKB Kecamatan Wundulako',
        'kode_faskes' => 'KB-WDK-001',
    ]);

    $user = User::factory()->create([
        'instansi_id' => $instansi->id,
        'level_akses' => 'admin',
    ]);

    Alokon::create([
        'instansi_id' => $instansi->id,
        'nama_alokon' => 'IUD / AKDR',
        'stok' => 8,
    ]);

    Alokon::create([
        'instansi_id' => $instansi->id,
        'nama_alokon' => 'Implan / Susuk',
        'stok' => 5,
    ]);

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Peringatan Stok Alokon Menipis / Kurang')
        ->assertSee('IUD / AKDR')
        ->assertSee('Implan / Susuk')
        ->assertSee('tersisa 8 unit')
        ->assertSee('tersisa 5 unit');
});
