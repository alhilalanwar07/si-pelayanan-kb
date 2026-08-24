<?php

use App\Livewire\PetaSebaran\Index as PetaSebaranIndex;
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
        ->assertSee('Peta Sebaran Geografis')
        ->set('viewMode', 'vector')
        ->assertSee('Visualisasi Poligon Tematik')
        ->set('densityFilter', 'high')
        ->set('densityFilter', 'all')
        ->call('selectWilayah', $wilayah ? $wilayah->id : null)
        ->assertStatus(200)
        ->call('resetSelection')
        ->assertStatus(200);
});
