<?php

use App\Livewire\AntrianLoginPanel;
use App\Models\AntrianJadwal;
use App\Models\Instansi;
use App\Models\JadwalPelayanan;
use App\Models\PesertaKb;
use App\Models\User;
use App\Models\Wilayah;
use Livewire\Livewire;

test('login page renders antrian login panel', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
    $response->assertSeeLivewire('antrian-login-panel');
});

test('antrian panel shows no service when there is no schedule today', function () {
    Livewire::test(AntrianLoginPanel::class)
        ->assertSee('Tidak ada pelayanan hari ini')
        ->assertSee('000');
});

test('antrian panel shows running queue number when schedule exists today', function () {
    $instansi = Instansi::create([
        'nama_instansi' => 'Puskesmas Wundulako',
        'kode_faskes' => 'PKM-01',
    ]);

    $admin = User::create([
        'instansi_id' => $instansi->id,
        'name' => 'Admin Test',
        'username' => 'admintest',
        'password' => 'password',
        'level_akses' => 'admin',
    ]);

    $wilayah = Wilayah::create([
        'nama_desa_kelurahan' => 'Desa Test',
    ]);

    $jadwal = JadwalPelayanan::create([
        'instansi_id' => $instansi->id,
        'tanggal' => now()->toDateString(),
        'waktu_mulai' => '08:00',
        'waktu_selesai' => '12:00',
        'kuota' => 20,
        'is_aktif' => true,
        'created_by' => $admin->id,
    ]);

    $peserta1 = PesertaKb::create([
        'wilayah_id' => $wilayah->id,
        'nik' => '7401011111110001',
        'nama_lengkap' => 'Pasien Pertama',
        'nama_suami_istri' => 'Suami Pertama',
        'tanggal_lahir_istri' => '1995-01-01',
        'alamat_lengkap' => 'Alamat Pasien 1',
        'penggunaan_asuransi' => 'bpjs',
        'status' => 'terverifikasi',
    ]);

    $peserta2 = PesertaKb::create([
        'wilayah_id' => $wilayah->id,
        'nik' => '7401011111110002',
        'nama_lengkap' => 'Pasien Kedua',
        'nama_suami_istri' => 'Suami Kedua',
        'tanggal_lahir_istri' => '1996-02-02',
        'alamat_lengkap' => 'Alamat Pasien 2',
        'penggunaan_asuransi' => 'bpjs',
        'status' => 'terverifikasi',
    ]);

    // Antrian 1 sudah hadir/dilayani
    AntrianJadwal::create([
        'jadwal_pelayanan_id' => $jadwal->id,
        'peserta_kb_id' => $peserta1->id,
        'nomor_antrian' => 1,
        'status' => 'hadir',
    ]);

    // Antrian 2 masih menunggu
    AntrianJadwal::create([
        'jadwal_pelayanan_id' => $jadwal->id,
        'peserta_kb_id' => $peserta2->id,
        'nomor_antrian' => 2,
        'status' => 'terdaftar',
    ]);

    Livewire::test(AntrianLoginPanel::class)
        ->assertSee('Loket Aktif Melayani')
        ->assertSee('001')
        ->assertSee('002')
        ->assertSee('Terdaftar');
});
