<?php

use App\Models\Alokon;
use App\Models\Instansi;
use App\Models\Pelayanan;
use App\Models\PesertaKb;
use App\Models\SkriningMedis;
use App\Models\User;
use App\Models\Wilayah;

beforeEach(function () {
    $this->instansi = Instansi::create([
        'nama_instansi' => 'DPPKB Kecamatan Wundulako',
        'kode_faskes' => 'KB-WUN-01',
    ]);

    $this->bidan = User::create([
        'instansi_id' => $this->instansi->id,
        'name' => 'Bidan Siti, S.Tr.Keb',
        'username' => 'bidansiti',
        'password' => 'password',
        'level_akses' => 'bidan',
    ]);

    $this->wilayah = Wilayah::create([
        'nama_desa_kelurahan' => 'Desa Wundulako',
    ]);

    $this->peserta = PesertaKb::create([
        'wilayah_id' => $this->wilayah->id,
        'nik' => '7401011234560001',
        'nama_lengkap' => 'Ibu Rahmawati',
        'nama_suami_istri' => 'Bapak Budi',
        'tanggal_lahir_istri' => '1995-05-15',
        'alamat_lengkap' => 'Jl. Poros Kolaka',
        'penggunaan_asuransi' => 'bpjs',
        'status' => 'terverifikasi',
    ]);

    $this->alokon = Alokon::create([
        'instansi_id' => $this->instansi->id,
        'nama_alokon' => 'Suntikan 3 Bulan Progestin',
        'stok' => 10,
    ]);

    $this->skrining = SkriningMedis::create([
        'peserta_kb_id' => $this->peserta->id,
        'tanggal_skrining' => now()->toDateString(),
        'gravida_partus_abortus' => 'G2P1A0',
        'fisik_keadaan_umum' => 'baik',
        'fisik_berat_badan' => 55.5,
        'fisik_tekanan_darah' => '120/80',
        'posisi_rahim' => 'antaflexi',
        'alat_kontrasepsi_boleh_digunakan' => ['Suntikan 3 Bulan Progestin', 'Pil Kombinasi'],
    ]);

    $this->pelayanan = Pelayanan::create([
        'peserta_kb_id' => $this->peserta->id,
        'alokon_id' => $this->alokon->id,
        'skrining_medis_id' => $this->skrining->id,
        'tanggal_pelayanan' => now()->toDateString(),
        'tanggal_kunjungan_ulang' => now()->addMonths(3)->toDateString(),
        'penanggung_jawab_nama' => 'Bidan Siti',
        'penanggung_jawab_jabatan' => 'bidan',
    ]);
});

test('halaman cetak formulir pelayanan KB dapat diakses dan tidak error TypeError saat alat_kontrasepsi_boleh_digunakan berupa array', function () {
    $this->actingAs($this->bidan);

    $response = $this->get(route('pelayanan.cetak', $this->pelayanan->id));

    $response->assertOk();
    $response->assertSee('K/IV/KB/15');
    $response->assertSee('IBU RAHMAWATI');
    $response->assertSee('KARTU STATUS PESERTA KB');
});
