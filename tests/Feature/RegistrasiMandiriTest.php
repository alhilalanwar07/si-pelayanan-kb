<?php

use App\Livewire\Pelayanan\Index;
use App\Livewire\RegistrasiMandiri;
use App\Models\Alokon;
use App\Models\AntrianJadwal;
use App\Models\InformedConsent;
use App\Models\Instansi;
use App\Models\JadwalPelayanan;
use App\Models\Pelayanan;
use App\Models\PesertaKb;
use App\Models\SkriningMedis;
use App\Models\User;
use App\Models\Wilayah;
use Livewire\Livewire;

test('peserta yang sudah daftar dan sudah dilayani tidak error saat cek nik ulang', function () {
    $instansi = Instansi::create([
        'nama_instansi' => 'DPPKB Kecamatan Wundulako',
        'kode_faskes' => 'KB-01',
    ]);

    $bidan = User::create([
        'instansi_id' => $instansi->id,
        'name' => 'Bidan Siti',
        'username' => 'bidansiti',
        'password' => 'password',
        'level_akses' => 'petugas',
    ]);

    $wilayah = Wilayah::create([
        'nama_desa_kelurahan' => 'Desa Wundulako',
    ]);

    $alokon = Alokon::create([
        'instansi_id' => $instansi->id,
        'nama_alokon' => 'Suntik 3 Bulan',
        'jenis' => 'suntik',
        'stok' => 50,
        'satuan' => 'vial',
    ]);

    $jadwal = JadwalPelayanan::create([
        'instansi_id' => $instansi->id,
        'tanggal' => now()->toDateString(),
        'waktu_mulai' => '08:00',
        'waktu_selesai' => '12:00',
        'kuota' => 20,
        'is_aktif' => true,
        'created_by' => $bidan->id,
    ]);

    $peserta = PesertaKb::create([
        'wilayah_id' => $wilayah->id,
        'nik' => '7401012345678901',
        'nama_lengkap' => 'Ibu Rahma',
        'nama_suami_istri' => 'Bapak Budi',
        'tanggal_lahir_istri' => '1995-05-15',
        'alamat_lengkap' => 'Jl. Poros Wundulako',
        'penggunaan_asuransi' => 'bpjs',
        'status' => 'terverifikasi',
    ]);

    // Antrian sudah berstatus hadir (sudah dilayani)
    $antrian = AntrianJadwal::create([
        'jadwal_pelayanan_id' => $jadwal->id,
        'peserta_kb_id' => $peserta->id,
        'nomor_antrian' => 1,
        'status' => 'hadir',
    ]);

    // Pelayanan sudah dicatat oleh bidan
    $pelayanan = Pelayanan::create([
        'peserta_kb_id' => $peserta->id,
        'alokon_id' => $alokon->id,
        'tanggal_pelayanan' => now()->toDateString(),
        'tanggal_kunjungan_ulang' => now()->addMonths(3)->toDateString(),
        'penanggung_jawab_nama' => 'Bidan Siti, S.Tr.Keb',
        'penanggung_jawab_nip' => '198501012010012001',
        'penanggung_jawab_jabatan' => 'bidan',
        'keterangan' => 'Pelayanan KB suntik 3 bulan berjalan lancar tanpa efek samping.',
    ]);

    // Tes 1: Pasien cek NIK ulang setelah dilayani
    $component = Livewire::test(RegistrasiMandiri::class)
        ->set('cekNik', '7401012345678901')
        ->call('cekNikAction')
        ->assertSet('step', 'pilih_jadwal')
        ->assertSee('Ibu Rahma')
        ->assertSee('7401012345678901')
        ->assertSee('Suntik 3 Bulan')
        ->assertSee('Bidan Siti, S.Tr.Keb')
        ->assertSee('Selesai Hadir');

    // Tes 2: Pasien bisa melihat tiket antrian riwayatnya
    $component->call('lihatTiket', $antrian->id)
        ->assertSet('step', 'selesai')
        ->assertSet('nomorAntrian', 1)
        ->assertSee('Tiket Antrian Resmi Pelayanan KB');
});

test('masyarakat dapat melihat rekam medis 3 langkah dengan mengetik nik dan petugas dapat mencari nama lengkap di walkin', function () {
    $instansi = Instansi::create([
        'nama_instansi' => 'DPPKB Kecamatan Wundulako',
        'kode_faskes' => 'KB-01',
    ]);

    $bidan = User::create([
        'instansi_id' => $instansi->id,
        'name' => 'Bidan Pelaksana, S.Tr.Keb',
        'nip' => '199205162015032004',
        'username' => 'bidan',
        'password' => 'password',
        'level_akses' => 'bidan',
    ]);

    $wilayah = Wilayah::create([
        'nama_desa_kelurahan' => 'Kelurahan Wundulako',
    ]);

    $alokon = Alokon::create([
        'instansi_id' => $instansi->id,
        'nama_alokon' => 'IUD / AKDR',
        'stok' => 8,
    ]);

    $jadwal = JadwalPelayanan::create([
        'instansi_id' => $instansi->id,
        'tanggal' => now()->toDateString(),
        'waktu_mulai' => '08:00',
        'waktu_selesai' => '12:00',
        'kuota' => 20,
        'is_aktif' => true,
        'created_by' => $bidan->id,
    ]);

    $peserta = PesertaKb::create([
        'wilayah_id' => $wilayah->id,
        'nik' => '7401012305900001',
        'nama_lengkap' => 'Siti Aminah',
        'nama_suami_istri' => 'Ahmad Yusuf',
        'tanggal_lahir_istri' => '1990-05-23',
        'alamat_lengkap' => 'Jl. Merdeka No. 12',
        'penggunaan_asuransi' => 'bpjs',
        'status' => 'terverifikasi',
    ]);

    $skrining = SkriningMedis::create([
        'peserta_kb_id' => $peserta->id,
        'tanggal_skrining' => now()->toDateString(),
        'gravida_partus_abortus' => 'G3P3A0',
        'status_menyusui' => true,
        'fisik_keadaan_umum' => 'baik',
        'fisik_berat_badan' => 56.5,
        'fisik_tekanan_darah' => '120/80',
        'posisi_rahim' => 'normal',
        'alat_kontrasepsi_boleh_digunakan' => ['IUD', 'Kondom'],
    ]);

    InformedConsent::create([
        'skrining_medis_id' => $skrining->id,
        'persetujuan_klien' => true,
        'persetujuan_pasangan' => true,
        'jenis_tindakan_medis' => 'pemasangan',
        'tanggal_persetujuan' => now()->toDateString(),
    ]);

    Pelayanan::create([
        'peserta_kb_id' => $peserta->id,
        'alokon_id' => $alokon->id,
        'skrining_medis_id' => $skrining->id,
        'tanggal_pelayanan' => now()->subMonths(3)->toDateString(),
        'tanggal_kunjungan_ulang' => now()->addMonths(3)->toDateString(),
        'penanggung_jawab_nama' => $bidan->name,
        'penanggung_jawab_nip' => $bidan->nip,
        'penanggung_jawab_jabatan' => 'bidan',
        'keterangan' => 'Pemasangan IUD berjalan lancar.',
    ]);

    // 1. Cek rekam medis 3 langkah lewat NIK di /registrasi
    Livewire::test(RegistrasiMandiri::class)
        ->set('cekNik', '7401012305900001')
        ->call('cekNikAction')
        ->assertSet('step', 'pilih_jadwal')
        ->assertSee('Hasil Pelayanan & Rekam Medis KB (3 Langkah)')
        ->assertSee('Skrining Medis')
        ->assertSee('120/80 mmHg')
        ->assertSee('G3P3A0')
        ->assertSee('Informed Consent')
        ->assertSee('Pemasangan Kontrasepsi')
        ->assertSee('Tindakan & Alokon')
        ->assertSee('IUD / AKDR');

    // 2. Cek pencarian nama lengkap peserta pada Walk-In Pelayanan\Index
    $this->actingAs($bidan);
    Livewire::test(Index::class)
        ->call('openWalkinModal')
        ->set('walkinSearchPeserta', 'Siti Aminah')
        ->assertSet('walkinPesertaId', (string) $peserta->id)
        ->call('submitWalkin')
        ->assertHasNoErrors();
});
