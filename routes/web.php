<?php

use Illuminate\Support\Facades\Route;

// ──── Public Routes ────
Route::view('/', 'welcome')->name('home');
Route::get('/registrasi', \App\Livewire\RegistrasiMandiri::class)->name('registrasi');

// ──── Authenticated Routes ────
Route::middleware(['auth'])->group(function () {

    // Dashboard (semua role)
    Route::get('dashboard', \App\Livewire\Dashboard::class)->name('dashboard');

    // Data Wilayah (admin only)
    Route::get('wilayah', \App\Livewire\Wilayah\Index::class)->name('wilayah.index')->middleware('level:admin');

    // Data Peserta KB (admin, bidan)
    Route::get('peserta-kb', \App\Livewire\PesertaKb\Index::class)->name('peserta-kb.index')->middleware('level:admin,bidan');
    Route::get('peserta-kb/create', \App\Livewire\PesertaKb\Create::class)->name('peserta-kb.create')->middleware('level:admin');
    Route::get('peserta-kb/{pesertaKb}', \App\Livewire\PesertaKb\Show::class)->name('peserta-kb.show')->middleware('level:admin,bidan');

    // Inventaris Alokon (admin only)
    Route::get('alokon', \App\Livewire\Alokon\Index::class)->name('alokon.index')->middleware('level:admin');

    // Pelayanan KB — Wizard + Daftar (bidan, admin)
    Route::get('pelayanan', \App\Livewire\Pelayanan\Index::class)->name('pelayanan.index')->middleware('level:admin,bidan');
    Route::get('pelayanan/create', \App\Livewire\Pelayanan\Create::class)->name('pelayanan.create')->middleware('level:bidan');
    Route::get('pelayanan/{pelayanan}', \App\Livewire\Pelayanan\Show::class)->name('pelayanan.show')->middleware('level:admin,bidan');
    Route::get('pelayanan/{pelayanan}/cetak', \App\Livewire\Pelayanan\Cetak::class)->name('pelayanan.cetak')->middleware('level:admin,bidan');

    // Skrining Medis — Daftar (readonly, data via wizard)
    Route::get('skrining-medis', \App\Livewire\SkriningMedis\Index::class)->name('skrining-medis.index')->middleware('level:admin,bidan');

    // Informed Consent — Daftar (readonly, data via wizard)
    Route::get('informed-consent', \App\Livewire\InformedConsent\Index::class)->name('informed-consent.index')->middleware('level:admin,bidan');

    // Laporan (semua role internal)
    Route::get('laporan', \App\Livewire\Laporan\Index::class)->name('laporan.index');

    // Peta Sebaran (semua role internal)
    Route::get('peta-sebaran', \App\Livewire\PetaSebaran\Index::class)->name('peta-sebaran.index');

    // Manajemen Pengguna (admin only)
    Route::get('pengguna', \App\Livewire\Pengguna\Index::class)->name('pengguna.index')->middleware('level:admin');
});

require __DIR__.'/settings.php';
