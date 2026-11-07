<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Wilayah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class HostingMigrationController extends Controller
{
    /**
     * Jalankan migrasi database di hosting secara otomatis via browser (tanpa menghapus data).
     */
    public function migrate(Request $request)
    {
        try {
            // 1. Jalankan artisan migrate dengan opsi --force untuk production
            $exitCode = Artisan::call('migrate', [
                '--force' => true,
            ]);

            $output = Artisan::output();

            // 2. Verifikasi apakah kolom 'nip' sudah ada di tabel 'users'
            $hasNipColumn = Schema::hasTable('users') && Schema::hasColumn('users', 'nip');

            // 3. Jika kolom nip ada, inisialisasi NIP akun bawaan jika belum terisi
            $updatedUsers = [];
            if ($hasNipColumn) {
                $usersToUpdate = [
                    'admin' => '198801122011011002',
                    'bidan' => '199205162015032004',
                    'pimpinan' => '197508201998031001',
                ];

                foreach ($usersToUpdate as $username => $nip) {
                    $user = User::where('username', $username)->first();
                    if ($user && empty($user->nip)) {
                        $user->update(['nip' => $nip]);
                        $updatedUsers[] = "User '{$username}' diisi NIP: {$nip}";
                    }
                }
            }

            // 4. Verifikasi apakah kolom koordinat (latitude, longitude, link_gmaps) sudah ada di tabel 'wilayahs'
            $hasWilayahKoordinatColumns = Schema::hasTable('wilayahs')
                && Schema::hasColumns('wilayahs', ['latitude', 'longitude', 'link_gmaps']);

            // 5. Inisialisasi koordinat & link Google Maps default untuk 5 Desa/Kelurahan Kecamatan Wundulako jika masih kosong
            $updatedWilayahs = [];
            if ($hasWilayahKoordinatColumns) {
                $defaultKoordinatWilayah = [
                    'Kelurahan Wundulako' => [
                        'latitude' => -4.0814230,
                        'longitude' => 121.5105193,
                        'link_gmaps' => 'https://www.google.com/maps/place/Balai+penyuluh+kkbpk+kec,wundulako/@-4.081423,121.5105193,12z',
                    ],
                    'Desa Bende' => [
                        'latitude' => -4.1150000,
                        'longitude' => 121.6120000,
                        'link_gmaps' => 'https://www.google.com/maps?q=-4.1150,121.6120',
                    ],
                    'Desa Kowioha' => [
                        'latitude' => -4.1320000,
                        'longitude' => 121.6450000,
                        'link_gmaps' => 'https://www.google.com/maps?q=-4.1320,121.6450',
                    ],
                    'Desa Lamokuni' => [
                        'latitude' => -4.1580000,
                        'longitude' => 121.6250000,
                        'link_gmaps' => 'https://www.google.com/maps?q=-4.1580,121.6250',
                    ],
                    'Desa Watalara' => [
                        'latitude' => -4.1480000,
                        'longitude' => 121.5950000,
                        'link_gmaps' => 'https://www.google.com/maps?q=-4.1480,121.5950',
                    ],
                ];

                foreach ($defaultKoordinatWilayah as $namaDesa => $koordinat) {
                    $wilayah = Wilayah::where('nama_desa_kelurahan', $namaDesa)->first();
                    $needsWundulakoSync = $namaDesa === 'Kelurahan Wundulako'
                        && $wilayah
                        && (float) $wilayah->latitude === -4.1385;

                    if ($wilayah && ($wilayah->latitude === null || $wilayah->longitude === null || $needsWundulakoSync)) {
                        $wilayah->update($koordinat);
                        $updatedWilayahs[] = "{$namaDesa} diisi koordinat ({$koordinat['latitude']}, {$koordinat['longitude']})";
                    }
                }
            }

            // 6. Bersihkan cache optimize & views
            Artisan::call('optimize:clear');
            $cacheOutput = Artisan::output();

            return response()->view('pages.hosting-migrate-result', [
                'success' => $exitCode === 0,
                'mode' => 'migrate',
                'exitCode' => $exitCode,
                'output' => $output,
                'hasNipColumn' => $hasNipColumn,
                'hasWilayahKoordinatColumns' => $hasWilayahKoordinatColumns,
                'updatedUsers' => $updatedUsers,
                'updatedWilayahs' => $updatedWilayahs,
                'cacheOutput' => $cacheOutput,
            ]);
        } catch (\Throwable $e) {
            return response()->view('pages.hosting-migrate-result', [
                'success' => false,
                'mode' => 'migrate',
                'exitCode' => 1,
                'output' => $e->getMessage(),
                'hasNipColumn' => Schema::hasTable('users') && Schema::hasColumn('users', 'nip'),
                'hasWilayahKoordinatColumns' => Schema::hasTable('wilayahs') && Schema::hasColumns('wilayahs', ['latitude', 'longitude', 'link_gmaps']),
                'updatedUsers' => [],
                'updatedWilayahs' => [],
                'cacheOutput' => '',
                'error' => $e,
            ], 500);
        }
    }

    /**
     * Jalankan migrate:fresh --seed di hosting untuk memuat data revisi 4 bulan (25 peserta, 40 pelayanan, stok kritis).
     */
    public function migrateFreshSeed(Request $request)
    {
        try {
            $exitCode = Artisan::call('migrate:fresh', [
                '--seed' => true,
                '--force' => true,
            ]);

            $output = Artisan::output();

            $hasNipColumn = Schema::hasTable('users') && Schema::hasColumn('users', 'nip');
            $hasWilayahKoordinatColumns = Schema::hasTable('wilayahs')
                && Schema::hasColumns('wilayahs', ['latitude', 'longitude', 'link_gmaps']);

            Artisan::call('optimize:clear');
            $cacheOutput = Artisan::output();

            return response()->view('pages.hosting-migrate-result', [
                'success' => $exitCode === 0,
                'mode' => 'seed',
                'exitCode' => $exitCode,
                'output' => $output,
                'hasNipColumn' => $hasNipColumn,
                'hasWilayahKoordinatColumns' => $hasWilayahKoordinatColumns,
                'updatedUsers' => ['Seluruh akun bawaan (admin, bidan, pimpinan) berhasil di-seed ulang beserta NIP.'],
                'updatedWilayahs' => ['5 Desa/Kelurahan Kecamatan Wundulako berhasil di-seed beserta koordinat GPS & Link Google Maps, 25 peserta KB, dan 40 riwayat pelayanan (4 bulan).'],
                'cacheOutput' => $cacheOutput,
            ]);
        } catch (\Throwable $e) {
            return response()->view('pages.hosting-migrate-result', [
                'success' => false,
                'mode' => 'seed',
                'exitCode' => 1,
                'output' => $e->getMessage(),
                'hasNipColumn' => Schema::hasTable('users') && Schema::hasColumn('users', 'nip'),
                'hasWilayahKoordinatColumns' => Schema::hasTable('wilayahs') && Schema::hasColumns('wilayahs', ['latitude', 'longitude', 'link_gmaps']),
                'updatedUsers' => [],
                'updatedWilayahs' => [],
                'cacheOutput' => '',
                'error' => $e,
            ], 500);
        }
    }
}
