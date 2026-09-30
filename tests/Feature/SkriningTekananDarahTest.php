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
        'nama_instansi' => 'Puskesmas Wundulako',
        'kode_faskes' => 'PKM-01',
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

    $this->alokon = Alokon::create([
        'instansi_id' => $this->instansi->id,
        'nama_alokon' => 'Suntik 3 Bulan',
        'jenis' => 'suntik',
        'stok' => 50,
        'satuan' => 'vial',
    ]);
});

test('tekanan darah terlalu tinggi memblokir proses ke langkah berikutnya dan memunculkan error', function () {
    Livewire::actingAs($this->user)
        ->test(Create::class)
        ->set('peserta_kb_id', $this->peserta->id)
        ->set('tanggal_skrining', now()->toDateString())
        ->set('pendidikan_istri', 'Tamat SLTA')
        ->set('pendidikan_suami', 'Tamat SLTA')
        ->set('pekerjaan_istri', 'Wiraswasta')
        ->set('pekerjaan_suami', 'Petani')
        ->set('jumlah_anak_laki', 1)
        ->set('jumlah_anak_perempuan', 1)
        ->set('status_kepesertaan', 'baru')
        ->set('gravida_partus_abortus', 'G2P2A0')
        ->set('hamil_diduga_hamil', false)
        ->set('status_menyusui', false)
        ->set('fisik_keadaan_umum', 'baik')
        ->set('fisik_berat_badan', 55)
        ->set('fisik_tekanan_darah', '150/100') // Tekanan darah tinggi (Hipertensi)
        ->set('pemeriksaan_dalam_radang', false)
        ->set('pemeriksaan_dalam_tumor', false)
        ->set('posisi_rahim', 'normal')
        ->set('pemeriksaan_tambahan_diabetes', false)
        ->set('pemeriksaan_tambahan_pembekuan_darah', false)
        ->set('pemeriksaan_tambahan_orchitis', false)
        ->set('pemeriksaan_tambahan_tumor', false)
        ->set('alat_kontrasepsi_boleh_digunakan', ['Suntikan 3 Bulan Progestin'])
        ->call('nextStep')
        ->assertHasErrors(['fisik_tekanan_darah'])
        ->assertSet('tekananDarahTinggi', true)
        ->assertSet('isLayak', false)
        ->assertSet('currentStep', 1); // Tidak berpindah ke step 2
});

test('lupa mengisi pertanyaan atau bagian wajib memunculkan error validasi di bawah field', function () {
    Livewire::actingAs($this->user)
        ->test(Create::class)
        ->set('tanggal_skrining', null) // Dikosongkan untuk menguji jika user lupa/menghapus
        ->call('nextStep')
        ->assertHasErrors([
            'peserta_kb_id',
            'tanggal_skrining',
            'pendidikan_istri',
            'pendidikan_suami',
            'pekerjaan_istri',
            'pekerjaan_suami',
            'gravida_partus_abortus',
            'fisik_berat_badan',
            'fisik_tekanan_darah',
            'alat_kontrasepsi_boleh_digunakan',
        ])
        ->assertSet('currentStep', 1);
});

test('tekanan darah normal dan data lengkap berhasil melanjutkan ke langkah 2', function () {
    Livewire::actingAs($this->user)
        ->test(Create::class)
        ->set('peserta_kb_id', $this->peserta->id)
        ->set('tanggal_skrining', now()->toDateString())
        ->set('pendidikan_istri', 'Tamat SLTA')
        ->set('pendidikan_suami', 'Tamat SLTA')
        ->set('pekerjaan_istri', 'Wiraswasta')
        ->set('pekerjaan_suami', 'Petani')
        ->set('jumlah_anak_laki', 1)
        ->set('jumlah_anak_perempuan', 1)
        ->set('status_kepesertaan', 'baru')
        ->set('gravida_partus_abortus', 'G2P2A0')
        ->set('hamil_diduga_hamil', false)
        ->set('status_menyusui', false)
        ->set('fisik_keadaan_umum', 'baik')
        ->set('fisik_berat_badan', 55)
        ->set('fisik_tekanan_darah', '120/80') // Tekanan darah normal
        ->set('pemeriksaan_dalam_radang', false)
        ->set('pemeriksaan_dalam_tumor', false)
        ->set('posisi_rahim', 'normal')
        ->set('pemeriksaan_tambahan_diabetes', false)
        ->set('pemeriksaan_tambahan_pembekuan_darah', false)
        ->set('pemeriksaan_tambahan_orchitis', false)
        ->set('pemeriksaan_tambahan_tumor', false)
        ->set('alat_kontrasepsi_boleh_digunakan', ['Suntikan 3 Bulan Progestin'])
        ->call('nextStep')
        ->assertHasNoErrors()
        ->assertSet('tekananDarahTinggi', false)
        ->assertSet('isLayak', true)
        ->assertSet('currentStep', 2); // Berhasil lanjut ke langkah 2
});

test('lupa mencentang persetujuan di langkah 2 memunculkan error validasi', function () {
    Livewire::actingAs($this->user)
        ->test(Create::class)
        ->set('currentStep', 2)
        ->set('persetujuan_klien', false)
        ->set('persetujuan_pasangan', false)
        ->call('nextStep')
        ->assertHasErrors(['persetujuan_klien', 'persetujuan_pasangan'])
        ->assertSet('currentStep', 2);
});

test('lupa mengisi alokon atau tanggal pelayanan di langkah 3 memunculkan error validasi', function () {
    Livewire::actingAs($this->user)
        ->test(Create::class)
        ->set('currentStep', 3)
        ->set('alokon_id', null)
        ->set('tanggal_kunjungan_ulang', null)
        ->call('simpan')
        ->assertHasErrors(['alokon_id', 'tanggal_kunjungan_ulang']);
});
