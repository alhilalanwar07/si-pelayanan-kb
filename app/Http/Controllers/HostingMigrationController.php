<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class HostingMigrationController extends Controller
{
    /**
     * Jalankan migrasi database di hosting secara otomatis via browser.
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

            // 4. Bersihkan cache optimize & views
            Artisan::call('optimize:clear');
            $cacheOutput = Artisan::output();

            return response()->view('pages.hosting-migrate-result', [
                'success' => $exitCode === 0,
                'exitCode' => $exitCode,
                'output' => $output,
                'hasNipColumn' => $hasNipColumn,
                'updatedUsers' => $updatedUsers,
                'cacheOutput' => $cacheOutput,
            ]);
        } catch (\Throwable $e) {
            return response()->view('pages.hosting-migrate-result', [
                'success' => false,
                'exitCode' => 1,
                'output' => $e->getMessage(),
                'hasNipColumn' => Schema::hasTable('users') && Schema::hasColumn('users', 'nip'),
                'updatedUsers' => [],
                'cacheOutput' => '',
                'error' => $e,
            ], 500);
        }
    }
}
