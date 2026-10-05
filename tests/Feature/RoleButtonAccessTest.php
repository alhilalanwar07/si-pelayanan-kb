<?php

use App\Livewire\Dashboard as DashboardComponent;
use App\Livewire\Pelayanan\Index as PelayananIndex;
use App\Livewire\PesertaKb\Show as PesertaKbShow;
use App\Livewire\PetaSebaran\Index as PetaSebaranIndex;
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

    $this->admin = User::create([
        'instansi_id' => $this->instansi->id,
        'name' => 'Admin User',
        'username' => 'admin_test',
        'password' => 'password',
        'level_akses' => 'admin',
    ]);

    $this->bidan = User::create([
        'instansi_id' => $this->instansi->id,
        'name' => 'Bidan User',
        'username' => 'bidan_test',
        'password' => 'password',
        'level_akses' => 'bidan',
    ]);

    $this->pimpinan = User::create([
        'instansi_id' => $this->instansi->id,
        'name' => 'Pimpinan User',
        'username' => 'pimpinan_test',
        'password' => 'password',
        'level_akses' => 'pimpinan',
    ]);

    $this->wilayah = Wilayah::create([
        'nama_desa_kelurahan' => 'Desa Wundulako',
    ]);

    $this->peserta = PesertaKb::create([
        'wilayah_id' => $this->wilayah->id,
        'nik' => '7401011111110001',
        'nama_lengkap' => 'Ny. Aminah',
        'nama_suami_istri' => 'Tn. Ahmad',
        'tanggal_lahir_istri' => '1996-01-01',
        'alamat_lengkap' => 'Jl. Merdeka No. 1',
        'penggunaan_asuransi' => 'bpjs',
        'jumlah_anak_hidup' => 2,
        'status' => 'terverifikasi',
    ]);
});

test('pimpinan does not see Lihat Semua button on dashboard', function () {
    $this->actingAs($this->pimpinan);

    Livewire::test(DashboardComponent::class)
        ->assertDontSeeHtml(route('peserta-kb.index'));
});

test('admin and bidan see Lihat Semua button on dashboard', function () {
    $this->actingAs($this->admin);
    Livewire::test(DashboardComponent::class)
        ->assertSeeHtml(route('peserta-kb.index'));

    $this->actingAs($this->bidan);
    Livewire::test(DashboardComponent::class)
        ->assertSeeHtml(route('peserta-kb.index'));
});

test('pimpinan does not see Lihat Data Peserta button in peta sebaran when wilayah selected', function () {
    $this->actingAs($this->pimpinan);

    Livewire::test(PetaSebaranIndex::class)
        ->set('selectedWilayahId', $this->wilayah->id)
        ->assertDontSeeHtml(route('peserta-kb.index'));
});

test('admin and bidan see Lihat Data Peserta button in peta sebaran when wilayah selected', function () {
    $this->actingAs($this->admin);
    Livewire::test(PetaSebaranIndex::class)
        ->set('selectedWilayahId', $this->wilayah->id)
        ->assertSeeHtml(route('peserta-kb.index'));

    $this->actingAs($this->bidan);
    Livewire::test(PetaSebaranIndex::class)
        ->set('selectedWilayahId', $this->wilayah->id)
        ->assertSeeHtml(route('peserta-kb.index'));
});

test('admin does not see Catat Pelayanan KB button on peserta show page', function () {
    $this->actingAs($this->admin);

    Livewire::test(PesertaKbShow::class, ['pesertaKb' => $this->peserta])
        ->assertDontSeeHtml(route('pelayanan.create', ['peserta_id' => $this->peserta->id]));
});

test('bidan sees Catat Pelayanan KB button on verified peserta show page', function () {
    $this->actingAs($this->bidan);

    Livewire::test(PesertaKbShow::class, ['pesertaKb' => $this->peserta])
        ->assertSeeHtml(route('pelayanan.create', ['peserta_id' => $this->peserta->id]));
});

test('admin cannot trigger layaniPeserta and does not see layani button in antrian', function () {
    $jadwal = JadwalPelayanan::create([
        'instansi_id' => $this->instansi->id,
        'tanggal' => now()->toDateString(),
        'waktu_mulai' => '08:00',
        'waktu_selesai' => '12:00',
        'kuota' => 20,
        'is_aktif' => true,
        'created_by' => $this->admin->id,
    ]);

    $antrian = AntrianJadwal::create([
        'jadwal_pelayanan_id' => $jadwal->id,
        'peserta_kb_id' => $this->peserta->id,
        'nomor_antrian' => 1,
        'kode_antrian' => 'W-001',
        'jenis_pendaftaran' => 'walkin',
        'status' => 'terdaftar',
    ]);

    $this->actingAs($this->admin);

    Livewire::test(PelayananIndex::class)
        ->set('selectedJadwalId', $jadwal->id)
        ->assertDontSee('Layani Pasien')
        ->call('layaniPeserta', $this->peserta->id, $antrian->id)
        ->assertDispatched('toast-show');
});

test('bidan sees layani button in antrian and can call layaniPeserta', function () {
    $jadwal = JadwalPelayanan::create([
        'instansi_id' => $this->instansi->id,
        'tanggal' => now()->toDateString(),
        'waktu_mulai' => '08:00',
        'waktu_selesai' => '12:00',
        'kuota' => 20,
        'is_aktif' => true,
        'created_by' => $this->bidan->id,
    ]);

    $antrian = AntrianJadwal::create([
        'jadwal_pelayanan_id' => $jadwal->id,
        'peserta_kb_id' => $this->peserta->id,
        'nomor_antrian' => 1,
        'kode_antrian' => 'W-001',
        'jenis_pendaftaran' => 'walkin',
        'status' => 'terdaftar',
    ]);

    $this->actingAs($this->bidan);

    Livewire::test(PelayananIndex::class)
        ->set('selectedJadwalId', $jadwal->id)
        ->assertSee('Layani Pasien')
        ->call('layaniPeserta', $this->peserta->id, $antrian->id)
        ->assertRedirect(route('pelayanan.create', [
            'peserta_id' => $this->peserta->id,
            'antrian_id' => $antrian->id,
        ]));
});
