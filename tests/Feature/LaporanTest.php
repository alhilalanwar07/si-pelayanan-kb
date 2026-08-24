<?php

use App\Livewire\Laporan\Index as LaporanIndex;
use App\Models\User;
use Livewire\Livewire;

test('authenticated users can access laporan page', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('laporan.index'));
    $response->assertOk();
});

test('laporan component can switch tabs and export csv', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(LaporanIndex::class)
        ->assertStatus(200)
        ->set('activeTab', 'pelayanan')
        ->assertSee('Rekap Pelayanan')
        ->set('activeTab', 'peserta')
        ->assertSee('Data Peserta KB')
        ->set('activeTab', 'alokon')
        ->assertSee('Inventaris Alokon')
        ->call('exportCsv')
        ->assertFileDownloaded();
});

test('laporan component can download pdf report via cetak laporan', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    Livewire::test(LaporanIndex::class)
        ->assertStatus(200)
        ->set('activeTab', 'pelayanan')
        ->call('downloadPdf')
        ->assertFileDownloaded();
});
