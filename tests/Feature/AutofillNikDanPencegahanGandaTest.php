<?php

use App\Livewire\Pelayanan\Create as PelayananCreate;
use App\Livewire\PesertaKb\Create as PesertaKbCreate;
use App\Livewire\RegistrasiMandiri;
use App\Models\Alokon;
use App\Models\AntrianJadwal;
use App\Models\Instansi;
use App\Models\JadwalPelayanan;
use App\Models\PesertaKb;
use App\Models\SkriningMedis;
use App\Models\User;
use App\Models\Wilayah;
use Livewire\Livewire;

beforeEach(function () {
    $this->instansi = Instansi::create([
        'nama_instansi' => 'Puskesmas Wundulako',
        'kode_faskes' => 'PKM-01',
    ]);

    $this->bidan = User::create([
        'instansi_id' => $this->instansi->id,
        'name' => 'Bidan Siti, S.Tr.Keb',
        'nip' => '199205162015032004',
        'username' => 'bidansiti',
        'password' => 'password',
        'level_akses' => 'bidan',
    ]);

    $this->wilayah = Wilayah::create([
        'nama_desa_kelurahan' => 'Desa Wundulako',
    ]);

    $this->peserta = PesertaKb::create([
        'wilayah_id' => $this->wilayah->id,
        'nik' => '7401012345678901',
        'nama_lengkap' => 'Ibu Rahmawati',
        'nama_suami_istri' => 'Bapak Budi Santoso',
        'tanggal_lahir_istri' => '1995-05-15',
        'alamat_lengkap' => 'Jl. Poros Wundulako No. 10',
        'pendidikan_istri' => 'Tamat SLTA',
        'pendidikan_suami' => 'Tamat PT/Akademi',
        'pekerjaan_istri' => 'Wiraswasta',
        'pekerjaan_suami' => 'PNS/TNI/Polri',
        'jumlah_anak_laki' => 1,
        'jumlah_anak_perempuan' => 1,
        'status_kepesertaan' => 'ulangan',
        'kb_terakhir' => 'Suntikan 3 Bulan Progestin',
        'penggunaan_asuransi' => 'bpjs',
        'status' => 'terverifikasi',
    ]);

    $this->skriningLama = SkriningMedis::create([
        'peserta_kb_id' => $this->peserta->id,
        'tanggal_skrining' => now()->subMonths(3)->toDateString(),
        'gravida_partus_abortus' => 'G2P2A0',
        'posisi_rahim' => 'antaflexi',
        'rwyt_sakit_kuning' => false,
        'rwyt_pendarahan' => false,
        'rwyt_keputihan' => false,
        'rwyt_tumor' => false,
        'alat_kontrasepsi_boleh_digunakan' => ['Suntikan 3 Bulan Progestin', 'Pil Progestin'],
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

    $this->antrian = AntrianJadwal::create([
        'jadwal_pelayanan_id' => $this->jadwal->id,
        'peserta_kb_id' => $this->peserta->id,
        'nomor_antrian' => 1,
        'status' => 'terdaftar',
    ]);
});

test('pada registrasi mandiri nik otomatis terisi dari hasil cek nik tanpa perlu diisi 2 kali', function () {
    Livewire::test(RegistrasiMandiri::class)
        ->set('cekNik', '7401998877665544') // NIK belum terdaftar
        ->call('cekNikAction')
        ->assertSet('nikStatus', 'not_found')
        ->assertSet('nik', '7401998877665544')
        ->call('lanjutRegistrasi')
        ->assertSet('step', 'registrasi')
        ->assertSet('nik', '7401998877665544'); // Terisi otomatis di step registrasi
});

test('pada pelayanan create peserta dan nik langsung otomatis terisi dari antrian id', function () {
    Livewire::actingAs($this->bidan)
        ->withQueryParams(['antrian_id' => $this->antrian->id])
        ->test(PelayananCreate::class)
        ->assertSet('peserta_kb_id', $this->peserta->id)
        ->assertSet('nik', '7401012345678901')
        ->assertSet('pendidikan_istri', 'Tamat SLTA')
        ->assertSet('pendidikan_suami', 'Tamat PT/Akademi')
        ->assertSet('pekerjaan_istri', 'Wiraswasta')
        ->assertSet('pekerjaan_suami', 'PNS/TNI/Polri')
        ->assertSet('jumlah_anak_laki', 1)
        ->assertSet('jumlah_anak_perempuan', 1)
        ->assertSet('status_kepesertaan', 'ulangan')
        ->assertSet('kb_terakhir', 'Suntikan 3 Bulan Progestin')
        // Data rekam medis sebelumnya terisi otomatis
        ->assertSet('gravida_partus_abortus', 'G2P2A0')
        ->assertSet('posisi_rahim', 'antaflexi');
});

test('pada pelayanan create nik query parameter otomatis memilih peserta dan mengisi data', function () {
    Livewire::actingAs($this->bidan)
        ->withQueryParams(['nik' => '7401012345678901'])
        ->test(PelayananCreate::class)
        ->assertSet('peserta_kb_id', $this->peserta->id)
        ->assertSet('nik', '7401012345678901');
});
