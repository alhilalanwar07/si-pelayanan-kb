<?php

namespace Database\Seeders;

use App\Models\Alokon;
use App\Models\Instansi;
use App\Models\PesertaKb;
use App\Models\User;
use App\Models\Wilayah;
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
            'nama_instansi' => 'Puskesmas Wundulako',
            'kode_faskes' => 'PKM-WDK-001',
        ]);

        // ──── 2. Wilayah (5 desa/kelurahan di Kec. Wundulako) ────
        $wilayahs = collect([
            'Kelurahan Wundulako',
            'Desa Bende',
            'Desa Kowioha',
            'Desa Lamokuni',
            'Desa Watalara',
        ])->map(fn ($nama) => Wilayah::create(['nama_desa_kelurahan' => $nama]));

        // ──── 3. Users (3 role) ────
        $admin = User::create([
            'instansi_id' => $instansi->id,
            'name' => 'Admin Instansi',
            'username' => 'admin',
            'password' => 'password',
            'level_akses' => 'admin',
        ]);

        User::create([
            'instansi_id' => $instansi->id,
            'name' => 'Bidan Pelaksana',
            'username' => 'bidan',
            'password' => 'password',
            'level_akses' => 'bidan',
        ]);

        User::create([
            'instansi_id' => $instansi->id,
            'name' => 'Pimpinan DPPKB',
            'username' => 'pimpinan',
            'password' => 'password',
            'level_akses' => 'pimpinan',
        ]);

        // ──── 4. Alokon (5 jenis kontrasepsi) ────
        $alokons = [
            ['nama_alokon' => 'Suntik 3 Bulan (Depo Progestin)', 'stok' => 45],
            ['nama_alokon' => 'Pil KB Kombinasi', 'stok' => 120],
            ['nama_alokon' => 'Implan / Susuk', 'stok' => 5],
            ['nama_alokon' => 'IUD / AKDR', 'stok' => 8],
            ['nama_alokon' => 'Kondom', 'stok' => 200],
        ];

        foreach ($alokons as $alokon) {
            Alokon::create([
                'instansi_id' => $instansi->id,
                ...$alokon,
            ]);
        }

        // ──── 5. Sampel Peserta KB (5 data) ────
        $pesertaData = [
            [
                'nik' => '7401012305900001',
                'nomor_hp' => '081234567890',
                'nama_lengkap' => 'Siti Aminah',
                'nama_suami_istri' => 'Ahmad Yusuf',
                'tanggal_lahir_istri' => '1990-05-23',
                'alamat_lengkap' => 'Jl. Merdeka No. 12, RT 01/RW 02, Kel. Wundulako',
                'penggunaan_asuransi' => 'bpjs',
                'jumlah_anak_hidup' => 3,
                'umur_anak_terakhir' => 18,
                'status' => 'terverifikasi',
                'wilayah_index' => 0,
            ],
            [
                'nik' => '7401021408920003',
                'nomor_hp' => '085298765432',
                'nama_lengkap' => 'Nuraeni',
                'nama_suami_istri' => 'Muhamad Ridwan',
                'tanggal_lahir_istri' => '1992-08-14',
                'alamat_lengkap' => 'Dusun 1, RT 03/RW 01, Desa Bende',
                'penggunaan_asuransi' => 'kis',
                'jumlah_anak_hidup' => 2,
                'umur_anak_terakhir' => 24,
                'status' => 'menunggu',
                'wilayah_index' => 1,
            ],
            [
                'nik' => '7401030507880005',
                'nomor_hp' => '082188776655',
                'nama_lengkap' => 'Wa Ode Rahmawati',
                'nama_suami_istri' => 'La Ode Saiful',
                'tanggal_lahir_istri' => '1988-07-05',
                'alamat_lengkap' => 'Dusun 2, RT 02/RW 01, Desa Kowioha',
                'penggunaan_asuransi' => 'bpjs',
                'jumlah_anak_hidup' => 4,
                'umur_anak_terakhir' => 12,
                'status' => 'terverifikasi',
                'wilayah_index' => 2,
            ],
            [
                'nik' => '7401041202950002',
                'nomor_hp' => '081399887766',
                'nama_lengkap' => 'Hasnawati',
                'nama_suami_istri' => 'Arif Rahman',
                'tanggal_lahir_istri' => '1995-02-12',
                'alamat_lengkap' => 'Dusun 3, RT 01/RW 02, Desa Lamokuni',
                'penggunaan_asuransi' => 'umum',
                'jumlah_anak_hidup' => 1,
                'umur_anak_terakhir' => 36,
                'status' => 'terverifikasi',
                'wilayah_index' => 3,
            ],
            [
                'nik' => '7401050809910004',
                'nomor_hp' => '081122334455',
                'nama_lengkap' => 'Nurhalisa',
                'nama_suami_istri' => 'Irfan Hidayat',
                'tanggal_lahir_istri' => '1991-09-08',
                'alamat_lengkap' => 'Dusun 1, RT 02/RW 01, Desa Watalara',
                'penggunaan_asuransi' => 'bpjs',
                'jumlah_anak_hidup' => 2,
                'umur_anak_terakhir' => 8,
                'status' => 'menunggu',
                'wilayah_index' => 4,
            ],
        ];

        foreach ($pesertaData as $data) {
            $wilayahIndex = $data['wilayah_index'];
            unset($data['wilayah_index']);

            PesertaKb::create([
                'user_id' => $data['status'] === 'terverifikasi' ? $admin->id : null,
                'wilayah_id' => $wilayahs[$wilayahIndex]->id,
                ...$data,
            ]);
        }
    }
}
