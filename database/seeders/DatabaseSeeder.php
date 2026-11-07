<?php

namespace Database\Seeders;

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
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ──── 1. Instansi ────
        $instansi = Instansi::create([
            'nama_instansi' => 'DPPKB Kecamatan Wundulako',
            'kode_faskes' => 'KB-WDK-001',
        ]);

        // ──── 2. Wilayah (5 desa/kelurahan di Kec. Wundulako lengkap dengan Koordinat & Link Google Maps) ────
        $wilayahs = collect([
            [
                'nama_desa_kelurahan' => 'Kelurahan Wundulako',
                'latitude' => -4.0814230,
                'longitude' => 121.5105193,
                'link_gmaps' => 'https://www.google.com/maps/place/Balai+penyuluh+kkbpk+kec,wundulako/@-4.081423,121.5105193,12z',
            ],
            [
                'nama_desa_kelurahan' => 'Desa Bende',
                'latitude' => -4.1150000,
                'longitude' => 121.6120000,
                'link_gmaps' => 'https://www.google.com/maps?q=-4.1150,121.6120',
            ],
            [
                'nama_desa_kelurahan' => 'Desa Kowioha',
                'latitude' => -4.1320000,
                'longitude' => 121.6450000,
                'link_gmaps' => 'https://www.google.com/maps?q=-4.1320,121.6450',
            ],
            [
                'nama_desa_kelurahan' => 'Desa Lamokuni',
                'latitude' => -4.1580000,
                'longitude' => 121.6250000,
                'link_gmaps' => 'https://www.google.com/maps?q=-4.1580,121.6250',
            ],
            [
                'nama_desa_kelurahan' => 'Desa Watalara',
                'latitude' => -4.1480000,
                'longitude' => 121.5950000,
                'link_gmaps' => 'https://www.google.com/maps?q=-4.1480,121.5950',
            ],
        ])->map(fn (array $item) => Wilayah::create($item));

        // ──── 3. Users (3 role) ────
        $admin = User::create([
            'instansi_id' => $instansi->id,
            'name' => 'Admin Instansi',
            'nip' => '198801122011011002',
            'username' => 'admin',
            'password' => 'password',
            'level_akses' => 'admin',
        ]);

        $bidan = User::create([
            'instansi_id' => $instansi->id,
            'name' => 'Bidan Pelaksana, S.Tr.Keb',
            'nip' => '199205162015032004',
            'username' => 'bidan',
            'password' => 'password',
            'level_akses' => 'bidan',
        ]);

        User::create([
            'instansi_id' => $instansi->id,
            'name' => 'Pimpinan DPPKB',
            'nip' => '197508201998031001',
            'username' => 'pimpinan',
            'password' => 'password',
            'level_akses' => 'pimpinan',
        ]);

        // ──── 4. Alokon (5 jenis kontrasepsi; Implan & IUD stok < 10 untuk demo Peringatan Stok) ────
        $alokonData = [
            ['nama_alokon' => 'Suntik 3 Bulan (Depo Progestin)', 'stok' => 45],
            ['nama_alokon' => 'Pil KB Kombinasi', 'stok' => 120],
            ['nama_alokon' => 'Implan / Susuk', 'stok' => 5],
            ['nama_alokon' => 'IUD / AKDR', 'stok' => 8],
            ['nama_alokon' => 'Kondom', 'stok' => 200],
        ];

        $alokons = collect();
        foreach ($alokonData as $item) {
            $alokons->push(Alokon::create([
                'instansi_id' => $instansi->id,
                ...$item,
            ]));
        }

        // Index Alokon:
        // 0 = Suntik 3 Bulan (Depo Progestin)
        // 1 = Pil KB Kombinasi
        // 2 = Implan / Susuk
        // 3 = IUD / AKDR
        // 4 = Kondom

        // ──── 5. Data 25 Peserta KB di 5 Desa/Kelurahan (Semua Pasien Terverifikasi & Dilayani) ────
        $pesertaData = [
            // Kelurahan Wundulako (wilayah_index: 0) — 6 peserta
            [
                'nik' => '7401012305900001', 'nomor_hp' => '081234567801',
                'nama_lengkap' => 'Siti Aminah', 'nama_suami_istri' => 'Ahmad Yusuf',
                'tanggal_lahir_istri' => '1990-05-23', 'alamat_lengkap' => 'Jl. Merdeka No. 12, RT 01/RW 02, Kel. Wundulako',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'S1',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Wiraswasta',
                'jumlah_anak_laki' => 2, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 18,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 0, 'alokon_index' => 0, 'visits_months_ago' => [3, 0],
            ],
            [
                'nik' => '7401011504930002', 'nomor_hp' => '081234567802',
                'nama_lengkap' => 'Ramlah Sari', 'nama_suami_istri' => 'Hendra Wijaya',
                'tanggal_lahir_istri' => '1993-04-15', 'alamat_lengkap' => 'Jl. Poros Wundulako No. 45, RT 02/RW 01, Kel. Wundulako',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'D3', 'pendidikan_suami' => 'S1',
                'pekerjaan_istri' => 'Pegawai Swasta', 'pekerjaan_suami' => 'PNS / ASN',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 24,
                'status_kepesertaan' => 'ganti_cara', 'kb_terakhir' => 'Pil KB',
                'wilayah_index' => 0, 'alokon_index' => 3, 'visits_months_ago' => [3],
            ],
            [
                'nik' => '7401010811960003', 'nomor_hp' => '081234567803',
                'nama_lengkap' => 'Fitriani Hasan', 'nama_suami_istri' => 'Syahrul Ramadhan',
                'tanggal_lahir_istri' => '1996-11-08', 'alamat_lengkap' => 'Lingkungan II, RT 03/RW 02, Kel. Wundulako',
                'penggunaan_asuransi' => 'kis', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Petani / Pekebun',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 0, 'umur_anak_terakhir' => 10,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Pil KB Kombinasi',
                'wilayah_index' => 0, 'alokon_index' => 1, 'visits_months_ago' => [3, 2, 1],
            ],
            [
                'nik' => '7401012901910004', 'nomor_hp' => '081234567804',
                'nama_lengkap' => 'Hj. Darmawati', 'nama_suami_istri' => 'H. Burhanuddin',
                'tanggal_lahir_istri' => '1991-01-29', 'alamat_lengkap' => 'Jl. Pendidikan No. 8, RT 01/RW 01, Kel. Wundulako',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'S1', 'pendidikan_suami' => 'S1',
                'pekerjaan_istri' => 'PNS / ASN', 'pekerjaan_suami' => 'Wiraswasta',
                'jumlah_anak_laki' => 2, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 30,
                'status_kepesertaan' => 'baru', 'kb_terakhir' => null,
                'wilayah_index' => 0, 'alokon_index' => 2, 'visits_months_ago' => [2],
            ],
            [
                'nik' => '7401011907970005', 'nomor_hp' => '081234567805',
                'nama_lengkap' => 'Mega Mustika', 'nama_suami_istri' => 'Rizky Pratama',
                'tanggal_lahir_istri' => '1997-07-19', 'alamat_lengkap' => 'Lingkungan III, RT 04/RW 02, Kel. Wundulako',
                'penggunaan_asuransi' => 'umum', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'D3',
                'pekerjaan_istri' => 'Pedagang', 'pekerjaan_suami' => 'Karyawan Swasta',
                'jumlah_anak_laki' => 0, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 14,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 0, 'alokon_index' => 0, 'visits_months_ago' => [2],
            ],
            [
                'nik' => '7401010403940006', 'nomor_hp' => '081234567806',
                'nama_lengkap' => 'Andi Tenri Uleng', 'nama_suami_istri' => 'Andi Mappanyukki',
                'tanggal_lahir_istri' => '1994-03-04', 'alamat_lengkap' => 'Jl. Pahlawan No. 19, RT 02/RW 02, Kel. Wundulako',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'S1', 'pendidikan_suami' => 'S1',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'PNS / ASN',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 20,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Kondom',
                'wilayah_index' => 0, 'alokon_index' => 4, 'visits_months_ago' => [1, 0],
            ],

            // Desa Bende (wilayah_index: 1) — 5 peserta
            [
                'nik' => '7401021408920003', 'nomor_hp' => '085298765401',
                'nama_lengkap' => 'Nuraeni', 'nama_suami_istri' => 'Muhamad Ridwan',
                'tanggal_lahir_istri' => '1992-08-14', 'alamat_lengkap' => 'Dusun 1, RT 03/RW 01, Desa Bende',
                'penggunaan_asuransi' => 'kis', 'pendidikan_istri' => 'SMP', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Petani / Pekebun',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 24,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 1, 'alokon_index' => 0, 'visits_months_ago' => [3, 0],
            ],
            [
                'nik' => '7401022106890007', 'nomor_hp' => '085298765402',
                'nama_lengkap' => 'Salmawati', 'nama_suami_istri' => 'Kaharuddin',
                'tanggal_lahir_istri' => '1989-06-21', 'alamat_lengkap' => 'Dusun 2, RT 01/RW 01, Desa Bende',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Wiraswasta',
                'jumlah_anak_laki' => 2, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 36,
                'status_kepesertaan' => 'ganti_cara', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 1, 'alokon_index' => 2, 'visits_months_ago' => [3],
            ],
            [
                'nik' => '7401021112950008', 'nomor_hp' => '085298765403',
                'nama_lengkap' => 'Rosdiana', 'nama_suami_istri' => 'Amirullah',
                'tanggal_lahir_istri' => '1995-12-11', 'alamat_lengkap' => 'Dusun 1, RT 02/RW 01, Desa Bende',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Pedagang', 'pekerjaan_suami' => 'Petani / Pekebun',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 0, 'umur_anak_terakhir' => 12,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Pil KB Kombinasi',
                'wilayah_index' => 1, 'alokon_index' => 1, 'visits_months_ago' => [2, 1, 0],
            ],
            [
                'nik' => '7401020309930009', 'nomor_hp' => '085298765404',
                'nama_lengkap' => 'Kartini', 'nama_suami_istri' => 'Ruslan',
                'tanggal_lahir_istri' => '1993-09-03', 'alamat_lengkap' => 'Dusun 3, RT 01/RW 02, Desa Bende',
                'penggunaan_asuransi' => 'kis', 'pendidikan_istri' => 'SMP', 'pendidikan_suami' => 'SMP',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Buruh Harian',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 16,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 1, 'alokon_index' => 0, 'visits_months_ago' => [2],
            ],
            [
                'nik' => '7401022704980010', 'nomor_hp' => '085298765405',
                'nama_lengkap' => 'Sri Wahyuni', 'nama_suami_istri' => 'Fajar Sidiq',
                'tanggal_lahir_istri' => '1998-04-27', 'alamat_lengkap' => 'Dusun 2, RT 02/RW 02, Desa Bende',
                'penggunaan_asuransi' => 'umum', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Wiraswasta',
                'jumlah_anak_laki' => 0, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 8,
                'status_kepesertaan' => 'baru', 'kb_terakhir' => null,
                'wilayah_index' => 1, 'alokon_index' => 0, 'visits_months_ago' => [1],
            ],

            // Desa Kowioha (wilayah_index: 2) — 5 peserta
            [
                'nik' => '7401030507880005', 'nomor_hp' => '082188776601',
                'nama_lengkap' => 'Wa Ode Rahmawati', 'nama_suami_istri' => 'La Ode Saiful',
                'tanggal_lahir_istri' => '1988-07-05', 'alamat_lengkap' => 'Dusun 2, RT 02/RW 01, Desa Kowioha',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'S1',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'PNS / ASN',
                'jumlah_anak_laki' => 2, 'jumlah_anak_perempuan' => 2, 'umur_anak_terakhir' => 12,
                'status_kepesertaan' => 'ganti_cara', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 2, 'alokon_index' => 3, 'visits_months_ago' => [3],
            ],
            [
                'nik' => '7401031602920011', 'nomor_hp' => '082188776602',
                'nama_lengkap' => 'Murniati', 'nama_suami_istri' => 'Supriadi',
                'tanggal_lahir_istri' => '1992-02-16', 'alamat_lengkap' => 'Dusun 1, RT 01/RW 01, Desa Kowioha',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Petani / Pekebun',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 19,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 2, 'alokon_index' => 0, 'visits_months_ago' => [3, 0],
            ],
            [
                'nik' => '7401032210940012', 'nomor_hp' => '082188776603',
                'nama_lengkap' => 'Jumiati', 'nama_suami_istri' => 'Nasrullah',
                'tanggal_lahir_istri' => '1994-10-22', 'alamat_lengkap' => 'Dusun 3, RT 02/RW 02, Desa Kowioha',
                'penggunaan_asuransi' => 'kis', 'pendidikan_istri' => 'SMP', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Petani / Pekebun',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 0, 'umur_anak_terakhir' => 15,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Pil KB Kombinasi',
                'wilayah_index' => 2, 'alokon_index' => 1, 'visits_months_ago' => [3, 2, 1],
            ],
            [
                'nik' => '7401030905960013', 'nomor_hp' => '082188776604',
                'nama_lengkap' => 'Hernawati', 'nama_suami_istri' => 'Zulkifli',
                'tanggal_lahir_istri' => '1996-05-09', 'alamat_lengkap' => 'Dusun 1, RT 03/RW 01, Desa Kowioha',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'D3', 'pendidikan_suami' => 'S1',
                'pekerjaan_istri' => 'Pegawai Honorer', 'pekerjaan_suami' => 'Wiraswasta',
                'jumlah_anak_laki' => 0, 'jumlah_anak_perempuan' => 2, 'umur_anak_terakhir' => 22,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Kondom',
                'wilayah_index' => 2, 'alokon_index' => 4, 'visits_months_ago' => [2, 1],
            ],
            [
                'nik' => '7401033008910014', 'nomor_hp' => '082188776605',
                'nama_lengkap' => 'Fatmawati', 'nama_suami_istri' => 'Sudirman',
                'tanggal_lahir_istri' => '1991-08-30', 'alamat_lengkap' => 'Dusun 2, RT 01/RW 02, Desa Kowioha',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Petani / Pekebun',
                'jumlah_anak_laki' => 2, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 28,
                'status_kepesertaan' => 'ganti_cara', 'kb_terakhir' => 'Pil KB',
                'wilayah_index' => 2, 'alokon_index' => 2, 'visits_months_ago' => [0],
            ],

            // Desa Lamokuni (wilayah_index: 3) — 5 peserta
            [
                'nik' => '7401041202950002', 'nomor_hp' => '081399887701',
                'nama_lengkap' => 'Hasnawati', 'nama_suami_istri' => 'Arif Rahman',
                'tanggal_lahir_istri' => '1995-02-12', 'alamat_lengkap' => 'Dusun 3, RT 01/RW 02, Desa Lamokuni',
                'penggunaan_asuransi' => 'umum', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Wiraswasta',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 0, 'umur_anak_terakhir' => 36,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 3, 'alokon_index' => 0, 'visits_months_ago' => [3, 0],
            ],
            [
                'nik' => '7401042506900015', 'nomor_hp' => '081399887702',
                'nama_lengkap' => 'Surianti', 'nama_suami_istri' => 'Baharuddin',
                'tanggal_lahir_istri' => '1990-06-25', 'alamat_lengkap' => 'Dusun 1, RT 01/RW 01, Desa Lamokuni',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Petani / Pekebun',
                'jumlah_anak_laki' => 2, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 24,
                'status_kepesertaan' => 'ganti_cara', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 3, 'alokon_index' => 3, 'visits_months_ago' => [2],
            ],
            [
                'nik' => '7401040711930016', 'nomor_hp' => '081399887703',
                'nama_lengkap' => 'Rahmatia', 'nama_suami_istri' => 'Lukman Hakim',
                'tanggal_lahir_istri' => '1993-11-07', 'alamat_lengkap' => 'Dusun 2, RT 02/RW 01, Desa Lamokuni',
                'penggunaan_asuransi' => 'kis', 'pendidikan_istri' => 'SMP', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Petani / Pekebun',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 14,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Pil KB Kombinasi',
                'wilayah_index' => 3, 'alokon_index' => 1, 'visits_months_ago' => [3, 2, 1, 0],
            ],
            [
                'nik' => '7401041804970017', 'nomor_hp' => '081399887704',
                'nama_lengkap' => 'Irmawati', 'nama_suami_istri' => 'Dedi Kurniawan',
                'tanggal_lahir_istri' => '1997-04-18', 'alamat_lengkap' => 'Dusun 1, RT 03/RW 01, Desa Lamokuni',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'D3',
                'pekerjaan_istri' => 'Pedagang', 'pekerjaan_suami' => 'Karyawan Swasta',
                'jumlah_anak_laki' => 0, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 9,
                'status_kepesertaan' => 'baru', 'kb_terakhir' => null,
                'wilayah_index' => 3, 'alokon_index' => 0, 'visits_months_ago' => [1],
            ],
            [
                'nik' => '7401040209920018', 'nomor_hp' => '081399887705',
                'nama_lengkap' => 'Asmawati', 'nama_suami_istri' => 'Syamsuddin',
                'tanggal_lahir_istri' => '1992-09-02', 'alamat_lengkap' => 'Dusun 3, RT 02/RW 02, Desa Lamokuni',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Wiraswasta',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 2, 'umur_anak_terakhir' => 21,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Kondom',
                'wilayah_index' => 3, 'alokon_index' => 4, 'visits_months_ago' => [2, 0],
            ],

            // Desa Watalara (wilayah_index: 4) — 4 peserta
            [
                'nik' => '7401050809910004', 'nomor_hp' => '081122334401',
                'nama_lengkap' => 'Nurhalisa', 'nama_suami_istri' => 'Irfan Hidayat',
                'tanggal_lahir_istri' => '1991-09-08', 'alamat_lengkap' => 'Dusun 1, RT 02/RW 01, Desa Watalara',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'S1',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Wiraswasta',
                'jumlah_anak_laki' => 1, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 8,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 4, 'alokon_index' => 0, 'visits_months_ago' => [3, 0],
            ],
            [
                'nik' => '7401051401940019', 'nomor_hp' => '081122334402',
                'nama_lengkap' => 'Marwah', 'nama_suami_istri' => 'Hamzah',
                'tanggal_lahir_istri' => '1994-01-14', 'alamat_lengkap' => 'Dusun 2, RT 01/RW 01, Desa Watalara',
                'penggunaan_asuransi' => 'kis', 'pendidikan_istri' => 'SMP', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Petani / Pekebun',
                'jumlah_anak_laki' => 2, 'jumlah_anak_perempuan' => 0, 'umur_anak_terakhir' => 16,
                'status_kepesertaan' => 'ulangan', 'kb_terakhir' => 'Pil KB Kombinasi',
                'wilayah_index' => 4, 'alokon_index' => 1, 'visits_months_ago' => [2, 1, 0],
            ],
            [
                'nik' => '7401052807890020', 'nomor_hp' => '081122334403',
                'nama_lengkap' => 'Kasmawati', 'nama_suami_istri' => 'Mansyur',
                'tanggal_lahir_istri' => '1989-07-28', 'alamat_lengkap' => 'Dusun 1, RT 03/RW 01, Desa Watalara',
                'penggunaan_asuransi' => 'bpjs', 'pendidikan_istri' => 'SMA', 'pendidikan_suami' => 'SMA',
                'pekerjaan_istri' => 'Ibu Rumah Tangga (IRT)', 'pekerjaan_suami' => 'Petani / Pekebun',
                'jumlah_anak_laki' => 2, 'jumlah_anak_perempuan' => 2, 'umur_anak_terakhir' => 32,
                'status_kepesertaan' => 'ganti_cara', 'kb_terakhir' => 'Suntik 3 Bulan',
                'wilayah_index' => 4, 'alokon_index' => 2, 'visits_months_ago' => [1],
            ],
            [
                'nik' => '7401050612960021', 'nomor_hp' => '081122334404',
                'nama_lengkap' => 'Yuliana', 'nama_suami_istri' => 'Agus Salim',
                'tanggal_lahir_istri' => '1996-12-06', 'alamat_lengkap' => 'Dusun 2, RT 02/RW 02, Desa Watalara',
                'penggunaan_asuransi' => 'umum', 'pendidikan_istri' => 'D3', 'pendidikan_suami' => 'S1',
                'pekerjaan_istri' => 'Pegawai Swasta', 'pekerjaan_suami' => 'Wiraswasta',
                'jumlah_anak_laki' => 0, 'jumlah_anak_perempuan' => 1, 'umur_anak_terakhir' => 11,
                'status_kepesertaan' => 'baru', 'kb_terakhir' => null,
                'wilayah_index' => 4, 'alokon_index' => 0, 'visits_months_ago' => [1],
            ],
        ];

        $tekananDarahSamples = ['110/70', '120/80', '115/75', '125/80', '118/78'];
        $beratBadanSamples = [52.5, 56.0, 58.5, 61.0, 54.0, 63.5, 49.5];
        $posisiRahimSamples = ['normal', 'retroflexi', 'antaflexi'];

        // Buat jadwal pelayanan historis untuk 3 bulan lalu, 2 bulan lalu, 1 bulan lalu, dan bulan ini
        $historicalJadwals = [];
        foreach ([3, 2, 1, 0] as $mAgo) {
            $tglJadwal = Carbon::now()->subMonths($mAgo)->startOfMonth()->addDays(9)->toDateString();
            if ($mAgo === 0 && Carbon::parse($tglJadwal)->isFuture()) {
                $tglJadwal = Carbon::now()->subDays(2)->toDateString();
            }

            $historicalJadwals[$mAgo] = JadwalPelayanan::create([
                'instansi_id' => $instansi->id,
                'tanggal' => $tglJadwal,
                'waktu_mulai' => '08:00',
                'waktu_selesai' => '13:00',
                'keterangan' => 'Pelayanan KB Reguler Bulanan Periode '.Carbon::parse($tglJadwal)->translatedFormat('F Y'),
                'kuota' => 30,
                'is_aktif' => true,
                'created_by' => $admin->id,
            ]);
        }

        $antrianCounters = [3 => 1, 2 => 1, 1 => 1, 0 => 1];

        foreach ($pesertaData as $idx => $data) {
            $wilayahIndex = $data['wilayah_index'];
            $alokonIndex = $data['alokon_index'];
            $visitsMonthsAgo = $data['visits_months_ago'];
            unset($data['wilayah_index'], $data['alokon_index'], $data['visits_months_ago']);

            $earliestMonthAgo = max($visitsMonthsAgo);
            $createdAtDate = Carbon::now()->subMonths($earliestMonthAgo)->startOfMonth()->addDays(($idx % 8) + 1);

            $peserta = PesertaKb::create([
                'user_id' => $admin->id,
                'wilayah_id' => $wilayahs[$wilayahIndex]->id,
                'jumlah_anak_hidup' => $data['jumlah_anak_laki'] + $data['jumlah_anak_perempuan'],
                'status' => 'terverifikasi',
                ...$data,
            ]);

            // Set created_at sesuai bulan pertama terdaftar
            $peserta->timestamps = false;
            $peserta->created_at = $createdAtDate;
            $peserta->updated_at = $createdAtDate;
            $peserta->save();

            $alokon = $alokons[$alokonIndex];
            $totalAnak = $peserta->jumlah_anak_hidup;
            $gpa = 'G'.$totalAnak.'P'.$totalAnak.'A0';

            $jenisTindakan = match ($alokonIndex) {
                0 => 'penyuntikan',
                2, 3 => 'pemasangan',
                default => 'pemasangan',
            };

            $bolehAlokon = match ($alokonIndex) {
                0 => ['Suntikan 3 Bulan Progestin', 'Pil Kombinasi', 'Kondom'],
                1 => ['Pil Kombinasi', 'Pil Progestin', 'Kondom'],
                2 => ['Implan 1 Batang', 'Implan 2 Batang', 'IUD', 'Kondom'],
                3 => ['IUD', 'Implan 2 Batang', 'Kondom'],
                default => ['Kondom', 'Pil Kombinasi', 'Suntikan 3 Bulan Progestin'],
            };

            foreach ($visitsMonthsAgo as $mAgo) {
                $jadwal = $historicalJadwals[$mAgo];
                $tglPelayanan = Carbon::parse($jadwal->tanggal)->addDays($idx % 5);
                if ($tglPelayanan->isFuture()) {
                    $tglPelayanan = Carbon::now()->subDays(1);
                }
                $tglString = $tglPelayanan->toDateString();

                $kunjunganUlang = match ($alokonIndex) {
                    0 => $tglPelayanan->copy()->addMonths(3)->toDateString(),
                    1, 4 => $tglPelayanan->copy()->addMonth()->toDateString(),
                    2, 3 => $tglPelayanan->copy()->addMonths(6)->toDateString(),
                    default => $tglPelayanan->copy()->addMonths(3)->toDateString(),
                };

                // Langkah 1: Skrining Medis
                $skrining = SkriningMedis::create([
                    'peserta_kb_id' => $peserta->id,
                    'tanggal_skrining' => $tglString,
                    'haid_terakhir' => $tglPelayanan->copy()->subDays(14)->toDateString(),
                    'gravida_partus_abortus' => $gpa,
                    'status_menyusui' => ($peserta->umur_anak_terakhir ?? 24) <= 24,
                    'rwyt_sakit_kuning' => false,
                    'rwyt_pendarahan' => false,
                    'rwyt_keputihan' => false,
                    'rwyt_tumor' => false,
                    'fisik_keadaan_umum' => 'baik',
                    'fisik_berat_badan' => $beratBadanSamples[$idx % count($beratBadanSamples)],
                    'fisik_tekanan_darah' => $tekananDarahSamples[$idx % count($tekananDarahSamples)],
                    'posisi_rahim' => $posisiRahimSamples[$idx % count($posisiRahimSamples)],
                    'hamil_diduga_hamil' => false,
                    'pemeriksaan_dalam_radang' => false,
                    'pemeriksaan_dalam_tumor' => false,
                    'pemeriksaan_tambahan_diabetes' => false,
                    'pemeriksaan_tambahan_pembekuan_darah' => false,
                    'pemeriksaan_tambahan_orchitis' => false,
                    'pemeriksaan_tambahan_tumor' => false,
                    'alat_kontrasepsi_boleh_digunakan' => $bolehAlokon,
                ]);

                // Langkah 2: Informed Consent
                InformedConsent::create([
                    'skrining_medis_id' => $skrining->id,
                    'persetujuan_klien' => true,
                    'persetujuan_pasangan' => true,
                    'jenis_tindakan_medis' => $jenisTindakan,
                    'tanggal_persetujuan' => $tglString,
                ]);

                // Langkah 3: Pencatatan Pelayanan & Pemberian Alokon
                Pelayanan::create([
                    'peserta_kb_id' => $peserta->id,
                    'alokon_id' => $alokon->id,
                    'skrining_medis_id' => $skrining->id,
                    'tanggal_pelayanan' => $tglString,
                    'tanggal_kunjungan_ulang' => $kunjunganUlang,
                    'tanggal_dicabut' => null,
                    'penanggung_jawab_nama' => $bidan->name,
                    'penanggung_jawab_nip' => $bidan->nip,
                    'penanggung_jawab_jabatan' => 'bidan',
                    'keterangan' => "Pelayanan {$alokon->nama_alokon} berjalan lancar, kondisi pasien sehat dan edukasi jadwal kontrol telah diberikan.",
                ]);

                // Catat Antrian Hadir pada periode tersebut
                $noAntrian = $antrianCounters[$mAgo]++;
                AntrianJadwal::create([
                    'jadwal_pelayanan_id' => $jadwal->id,
                    'peserta_kb_id' => $peserta->id,
                    'nomor_antrian' => $noAntrian,
                    'jenis_pendaftaran' => ($idx % 3 === 0) ? 'walkin' : 'online',
                    'kode_antrian' => ($idx % 3 === 0) ? ('W-'.str_pad($noAntrian, 3, '0', STR_PAD_LEFT)) : null,
                    'status' => 'hadir',
                ]);
            }
        }

        // ──── 6. Jadwal Pelayanan Aktif (Hari Ini & Mendatang) ────
        $jadwalData = [
            ['hari_offset' => 0, 'waktu_mulai' => '08:00', 'waktu_selesai' => '12:00', 'keterangan' => 'Pelayanan KB Reguler & Konseling Hari Ini', 'kuota' => 25],
            ['hari_offset' => 3, 'waktu_mulai' => '08:00', 'waktu_selesai' => '12:00', 'keterangan' => 'Pelayanan Suntik & Pil KB', 'kuota' => 20],
            ['hari_offset' => 5, 'waktu_mulai' => '08:00', 'waktu_selesai' => '11:00', 'keterangan' => 'Pelayanan Implan & IUD', 'kuota' => 10],
            ['hari_offset' => 7, 'waktu_mulai' => '09:00', 'waktu_selesai' => '13:00', 'keterangan' => 'Pelayanan KB Umum', 'kuota' => 25],
            ['hari_offset' => 10, 'waktu_mulai' => '08:00', 'waktu_selesai' => '12:00', 'keterangan' => 'Konseling & Pelayanan KB', 'kuota' => 15],
            ['hari_offset' => 14, 'waktu_mulai' => '08:30', 'waktu_selesai' => '11:30', 'keterangan' => 'Pelayanan KB Pasca Persalinan', 'kuota' => 12],
        ];

        $createdJadwals = [];
        foreach ($jadwalData as $jd) {
            $createdJadwals[] = JadwalPelayanan::create([
                'instansi_id' => $instansi->id,
                'tanggal' => now()->addDays($jd['hari_offset'])->toDateString(),
                'waktu_mulai' => $jd['waktu_mulai'],
                'waktu_selesai' => $jd['waktu_selesai'],
                'keterangan' => $jd['keterangan'],
                'kuota' => $jd['kuota'],
                'is_aktif' => true,
                'created_by' => $admin->id,
            ]);
        }

        // ──── 7. Sample Antrian Menunggu pada Jadwal Mendatang (H+3) ────
        $pesertaTerverifikasi = PesertaKb::terverifikasi()->get();
        if ($pesertaTerverifikasi->count() >= 3 && isset($createdJadwals[1])) {
            $upcomingJadwal = $createdJadwals[1];
            $antrianNum = 1;
            foreach ($pesertaTerverifikasi->take(3) as $p) {
                AntrianJadwal::create([
                    'jadwal_pelayanan_id' => $upcomingJadwal->id,
                    'peserta_kb_id' => $p->id,
                    'nomor_antrian' => $antrianNum++,
                    'jenis_pendaftaran' => 'online',
                    'status' => 'terdaftar',
                ]);
            }
        }
    }
}
