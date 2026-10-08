<?php

use App\Livewire\Pelayanan\Create;
use App\Models\Alokon;
use App\Models\Instansi;
use App\Models\PesertaKb;
use App\Models\User;
use App\Models\Wilayah;
use Livewire\Livewire;

beforeEach(function () {
    $this->instansi = Instansi::create([
        'nama_instansi' => 'DPPKB Kecamatan Wundulako',
        'kode_faskes' => 'KB-01',
    ]);

    $this->user = User::create([
        'instansi_id' => $this->instansi->id,
        'name' => 'Bidan Siti, S.Tr.Keb',
        'username' => 'bidansiti',
        'password' => 'password',
        'level_akses' => 'petugas',
    ]);

    $this->wilayah = Wilayah::create([
        'nama_desa_kelurahan' => 'Desa Wundulako',
    ]);

    $this->peserta = PesertaKb::create([
        'wilayah_id' => $this->wilayah->id,
        'nik' => '7401012345678901',
        'nama_lengkap' => 'Ibu Rahma',
        'nama_suami_istri' => 'Bapak Budi',
        'tanggal_lahir_istri' => '1995-05-15',
        'alamat_lengkap' => 'Jl. Poros Wundulako',
        'penggunaan_asuransi' => 'bpjs',
        'status' => 'terverifikasi',
    ]);
});

test('can set alat_kontrasepsi_boleh_digunakan array individually without affecting others', function () {
    Livewire::actingAs($this->user)
        ->test(Create::class)
        ->set('peserta_kb_id', $this->peserta->id)
        ->set('alat_kontrasepsi_boleh_digunakan', ['Suntikan 1 Bulan'])
        ->assertSet('alat_kontrasepsi_boleh_digunakan', ['Suntikan 1 Bulan'])
        ->call('toggleAlokon', 'IUD')
        ->assertSet('alat_kontrasepsi_boleh_digunakan', ['Suntikan 1 Bulan', 'IUD'])
        ->call('toggleAlokon', 'Suntikan 1 Bulan')
        ->assertSet('alat_kontrasepsi_boleh_digunakan', ['IUD']);
});

test('selectSemuaAlokon selects all 11 items and uncheckSemuaAlokon clears all', function () {
    Livewire::actingAs($this->user)
        ->test(Create::class)
        ->set('peserta_kb_id', $this->peserta->id)
        ->assertSet('alat_kontrasepsi_boleh_digunakan', [])
        ->call('selectSemuaAlokon')
        ->assertSet('alat_kontrasepsi_boleh_digunakan', Create::DAFTAR_ALOKON_BOLEH)
        ->call('uncheckSemuaAlokon')
        ->assertSet('alat_kontrasepsi_boleh_digunakan', [])
        ->call('toggleSemuaAlokon')
        ->assertSet('alat_kontrasepsi_boleh_digunakan', Create::DAFTAR_ALOKON_BOLEH)
        ->call('toggleSemuaAlokon')
        ->assertSet('alat_kontrasepsi_boleh_digunakan', []);
});
