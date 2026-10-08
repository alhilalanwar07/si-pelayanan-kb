<?php

use App\Livewire\Pelayanan\Index as PelayananIndex;
use App\Models\AntrianJadwal;
use App\Models\Instansi;
use App\Models\JadwalPelayanan;
use App\Models\PesertaKb;
use App\Models\User;
use App\Models\Wilayah;
use Livewire\Livewire;

beforeEach(function () {
    $this->instansi = Instansi::create([
        'nama_instansi' => 'DPPKB Kecamatan Wundulako',
        'kode_faskes' => 'KB-WUN-01',
    ]);

    $this->bidan = User::create([
        'instansi_id' => $this->instansi->id,
        'name' => 'Bidan Siti',
        'username' => 'bidansiti',
        'password' => 'password',
        'level_akses' => 'bidan',
    ]);

    $this->wilayah = Wilayah::create([
        'nama_desa_kelurahan' => 'Desa Wundulako',
    ]);

    $this->jadwal = JadwalPelayanan::create([
        'instansi_id' => $this->instansi->id,
        'tanggal' => now()->toDateString(),
        'waktu_mulai' => '08:00',
        'waktu_selesai' => '12:00',
        'kuota' => 20,
        'is_aktif' => true,
        'created_by' => $this->bidan->id,
    ]);

    $this->peserta = PesertaKb::create([
        'wilayah_id' => $this->wilayah->id,
        'nik' => '7401011234560001',
        'nama_lengkap' => 'Ibu Rahmawati',
        'nama_suami_istri' => 'Bapak Budi',
        'tanggal_lahir_istri' => '1995-05-15',
        'alamat_lengkap' => 'Jl. Poros Kolaka',
        'penggunaan_asuransi' => 'bpjs',
        'jumlah_anak_hidup' => 2,
        'status' => 'terverifikasi',
    ]);
});

test('bidan can open and close walk-in modal', function () {
    $this->actingAs($this->bidan);

    Livewire::test(PelayananIndex::class)
        ->assertSet('showWalkinModal', false)
        ->call('openWalkinModal')
        ->assertSet('showWalkinModal', true)
        ->assertSet('walkinJadwalId', $this->jadwal->id)
        ->call('closeWalkinModal')
        ->assertSet('showWalkinModal', false);
});

test('bidan can register walk-in queue for existing registered patient', function () {
    $this->actingAs($this->bidan);

    Livewire::test(PelayananIndex::class)
        ->call('openWalkinModal')
        ->set('walkinType', 'terdaftar')
        ->set('walkinPesertaId', $this->peserta->id)
        ->call('submitWalkin', false)
        ->assertSet('showWalkinModal', false);

    $antrian = AntrianJadwal::where('peserta_kb_id', $this->peserta->id)->first();
    expect($antrian)->not->toBeNull();
    expect($antrian->jenis_pendaftaran)->toBe('walkin');
    expect($antrian->kode_antrian)->toBe('W-001');
    expect($antrian->status)->toBe('terdaftar');
    expect($antrian->kode_display)->toBe('W-001');
});

test('bidan can register walk-in and serve immediately', function () {
    $this->actingAs($this->bidan);

    Livewire::test(PelayananIndex::class)
        ->call('openWalkinModal')
        ->set('walkinType', 'terdaftar')
        ->set('walkinPesertaId', $this->peserta->id)
        ->call('submitWalkin', true)
        ->assertRedirect(route('pelayanan.create', [
            'peserta_id' => $this->peserta->id,
            'antrian_id' => 1,
        ]));

    $antrian = AntrianJadwal::where('peserta_kb_id', $this->peserta->id)->first();
    expect($antrian->status)->toBe('sedang_dilayani');
    expect($antrian->kode_display)->toBe('W-001');
});

test('bidan can register walk-in for brand new unregistered patient', function () {
    $this->actingAs($this->bidan);

    Livewire::test(PelayananIndex::class)
        ->call('openWalkinModal')
        ->set('walkinType', 'baru')
        ->set('walkinNik', '7401019999990001')
        ->set('walkinNamaLengkap', 'Pasien Baru Walkin')
        ->set('walkinNomorHp', '081234567890')
        ->set('walkinWilayahId', $this->wilayah->id)
        ->set('walkinAlamatLengkap', 'Dusun 1 Wundulako')
        ->set('walkinPenggunaanAsuransi', 'umum')
        ->call('submitWalkin', false);

    $pesertaBaru = PesertaKb::where('nik', '7401019999990001')->first();
    expect($pesertaBaru)->not->toBeNull();
    expect($pesertaBaru->nama_lengkap)->toBe('Pasien Baru Walkin');

    $antrian = AntrianJadwal::where('peserta_kb_id', $pesertaBaru->id)->first();
    expect($antrian)->not->toBeNull();
    expect($antrian->jenis_pendaftaran)->toBe('walkin');
    expect($antrian->kode_antrian)->toBe('W-001');
    expect($antrian->status)->toBe('terdaftar');
});

test('patient cannot be served twice in the same schedule via walkin modal', function () {
    $this->actingAs($this->bidan);

    // Pasien sudah berstatus hadir / selesai dilayani di jadwal ini
    AntrianJadwal::create([
        'jadwal_pelayanan_id' => $this->jadwal->id,
        'peserta_kb_id' => $this->peserta->id,
        'nomor_antrian' => 1,
        'jenis_pendaftaran' => 'online',
        'status' => 'hadir',
    ]);

    Livewire::test(PelayananIndex::class)
        ->call('openWalkinModal')
        ->set('walkinType', 'terdaftar')
        ->set('walkinPesertaId', $this->peserta->id)
        ->call('submitWalkin', true)
        ->assertHasErrors(['walkinPesertaId'])
        ->assertNoRedirect();

    Livewire::test(PelayananIndex::class)
        ->call('openWalkinModal')
        ->set('walkinType', 'terdaftar')
        ->set('walkinPesertaId', $this->peserta->id)
        ->call('submitWalkin', false)
        ->assertHasErrors(['walkinPesertaId']);
});

test('layaniPeserta prevents serving an antrian that is already hadir', function () {
    $this->actingAs($this->bidan);

    $antrian = AntrianJadwal::create([
        'jadwal_pelayanan_id' => $this->jadwal->id,
        'peserta_kb_id' => $this->peserta->id,
        'nomor_antrian' => 1,
        'jenis_pendaftaran' => 'online',
        'status' => 'hadir',
    ]);

    Livewire::test(PelayananIndex::class)
        ->call('layaniPeserta', $this->peserta->id, $antrian->id)
        ->assertDispatched('toast-show')
        ->assertNoRedirect();
});
