<?php

use App\Livewire\PetaSebaran\Index as PetaSebaranIndex;
use App\Livewire\Wilayah\Index;
use App\Models\User;
use App\Models\Wilayah;
use Livewire\Livewire;

test('authenticated users can access peta sebaran page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('peta-sebaran.index'));
    $response->assertOk();
});

test('peta sebaran component can filter, switch view mode, and select wilayah', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $wilayah = Wilayah::first();

    Livewire::test(PetaSebaranIndex::class)
        ->assertStatus(200)
        ->assertSet('viewMode', 'map')
        ->assertSee('Peta Sebaran Geografis')
        ->assertSee('Peta Lokasi Titik Desa/Kelurahan')
        ->set('viewMode', 'vector')
        ->assertSee('Visualisasi Grafik & Tabel Sebaran Wilayah')
        ->assertSee('Grafik Perbandingan Peserta KB')
        ->set('densityFilter', 'high')
        ->set('densityFilter', 'all')
        ->call('selectWilayah')
        ->assertStatus(200)
        ->assertSet('selectedWilayahId', null)
        ->call('selectWilayah', $wilayah ? $wilayah->id : null)
        ->assertStatus(200)
        ->call('resetSelection')
        ->assertStatus(200);
});

test('admin dapat menambah desa/kelurahan dengan link google maps atau latitude longitude', function () {
    $admin = User::factory()->create(['level_akses' => 'admin']);
    $this->actingAs($admin);

    // 1. Input via Link Google Maps -> otomatis ekstrak Latitude & Longitude
    Livewire::test(Index::class)
        ->call('openCreateModal')
        ->set('nama_desa_kelurahan', 'Desa Silea')
        ->set('link_gmaps', 'https://www.google.com/maps/place/Silea/@-4.1234,121.6321,15z')
        ->assertSet('latitude', '-4.1234')
        ->assertSet('longitude', '121.6321')
        ->call('simpan')
        ->assertHasNoErrors();

    $desaSilea = Wilayah::where('nama_desa_kelurahan', 'Desa Silea')->first();
    expect($desaSilea)->not->toBeNull()
        ->and($desaSilea->latitude)->toBe(-4.1234)
        ->and($desaSilea->longitude)->toBe(121.6321);

    // 2. Input via Latitude & Longitude langsung -> otomatis buat link Google Maps
    Livewire::test(Index::class)
        ->call('openCreateModal')
        ->set('nama_desa_kelurahan', 'Desa Tikonu')
        ->set('latitude', '-4.1412')
        ->set('longitude', '121.6215')
        ->call('simpan')
        ->assertHasNoErrors();

    $desaTikonu = Wilayah::where('nama_desa_kelurahan', 'Desa Tikonu')->first();
    expect($desaTikonu)->not->toBeNull()
        ->and($desaTikonu->latitude)->toBe(-4.1412)
        ->and($desaTikonu->longitude)->toBe(121.6215)
        ->and($desaTikonu->link_gmaps)->toContain('-4.1412,121.6215');
});

test('route run-migrate di hosting berhasil menjalankan migrasi dan mengisi koordinat default wilayah', function () {
    $wundulako = Wilayah::create([
        'nama_desa_kelurahan' => 'Kelurahan Wundulako',
        'latitude' => null,
        'longitude' => null,
        'link_gmaps' => null,
    ]);

    $response = $this->get(route('hosting.migrate'));

    $response->assertOk()
        ->assertSee('Migrasi Database Berhasil Dijalankan!')
        ->assertSee('Kolom Koordinat Wilayah')
        ->assertSee('Tersedia (latitude, longitude, link_gmaps)');

    $wundulako->refresh();
    expect($wundulako->latitude)->toBe(-4.1385)
        ->and($wundulako->longitude)->toBe(121.6178)
        ->and($wundulako->link_gmaps)->toContain('-4.1385,121.6178');
});
