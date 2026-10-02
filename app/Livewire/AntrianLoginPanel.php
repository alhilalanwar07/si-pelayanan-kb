<?php

namespace App\Livewire;

use App\Models\AntrianJadwal;
use App\Models\JadwalPelayanan;
use Carbon\Carbon;
use Livewire\Component;

class AntrianLoginPanel extends Component
{
    public function render()
    {
        $today = Carbon::today();

        // Cari jadwal pelayanan hari ini
        $jadwalHariIni = JadwalPelayanan::whereDate('tanggal', $today)
            ->where('is_aktif', true)
            ->first();

        $antrianBerjalan = null;
        $antrianBerikutnya = null;
        $nomorTerakhirSelesai = null;
        $antrianBerjalanDisplay = null;
        $antrianBerikutnyaDisplay = null;
        $nomorTerakhirSelesaiDisplay = null;
        $totalAntrianHariIni = 0;
        $totalSelesai = 0;
        $sisaAntrian = 0;
        $jadwalBerikutnya = null;
        $statusLoket = 'tutup'; // 'melayani', 'persiapan', 'selesai', 'tutup'

        if ($jadwalHariIni) {
            $antrians = AntrianJadwal::where('jadwal_pelayanan_id', $jadwalHariIni->id)
                ->where('status', '!=', 'batal')
                ->orderBy('nomor_antrian')
                ->get();

            $totalAntrianHariIni = $antrians->count();

            // Antrian yang SUDAH SELESAI dilayani (hadir)
            $sudahDilayani = $antrians->where('status', 'hadir');
            $totalSelesai = $sudahDilayani->count();

            // Nomor antrian yang terakhir selesai dilayani
            $terakhirSelesai = $sudahDilayani->sortByDesc('nomor_antrian')->first();
            $nomorTerakhirSelesai = $terakhirSelesai ? $terakhirSelesai->nomor_antrian : null;
            $nomorTerakhirSelesaiDisplay = $terakhirSelesai ? $terakhirSelesai->kode_display : null;

            // Antrian yang AKTIF sedang dilayani saat ini (status: sedang_dilayani)
            $sedangDilayani = $antrians->where('status', 'sedang_dilayani')->sortBy('nomor_antrian')->first();
            $antrianBerjalan = $sedangDilayani ? $sedangDilayani->nomor_antrian : null;
            $antrianBerjalanDisplay = $sedangDilayani ? $sedangDilayani->kode_display : null;

            // Antrian yang masih menunggu giliran (terdaftar)
            $sedangMenunggu = $antrians->where('status', 'terdaftar');
            $sisaMenunggu = $sedangMenunggu->count();
            $sisaAntrian = $sisaMenunggu + ($sedangDilayani ? 1 : 0);

            $berikutnya = $sedangMenunggu->sortBy('nomor_antrian')->first();
            $antrianBerikutnya = $berikutnya ? $berikutnya->nomor_antrian : null;
            $antrianBerikutnyaDisplay = $berikutnya ? $berikutnya->kode_display : null;

            // Tentukan status loket pelayanan
            if ($totalAntrianHariIni === 0) {
                $statusLoket = 'persiapan';
            } elseif ($sisaAntrian === 0 && $totalSelesai > 0) {
                $statusLoket = 'selesai';
            } elseif ($sisaAntrian > 0 || $totalSelesai > 0) {
                $statusLoket = 'melayani';
            } else {
                $statusLoket = 'persiapan';
            }
        } else {
            // Ambil jadwal terdekat mendatang jika hari ini tidak ada jadwal
            $jadwalBerikutnya = JadwalPelayanan::aktif()
                ->mendatang()
                ->orderBy('tanggal')
                ->orderBy('waktu_mulai')
                ->first();
        }

        return view('livewire.antrian-login-panel', [
            'jadwalHariIni' => $jadwalHariIni,
            'antrianBerjalan' => $antrianBerjalan,
            'antrianBerikutnya' => $antrianBerikutnya,
            'nomorTerakhirSelesai' => $nomorTerakhirSelesai,
            'antrianBerjalanDisplay' => $antrianBerjalanDisplay,
            'antrianBerikutnyaDisplay' => $antrianBerikutnyaDisplay,
            'nomorTerakhirSelesaiDisplay' => $nomorTerakhirSelesaiDisplay,
            'totalAntrianHariIni' => $totalAntrianHariIni,
            'totalSelesai' => $totalSelesai,
            'sisaAntrian' => $sisaAntrian,
            'jadwalBerikutnya' => $jadwalBerikutnya,
            'statusLoket' => $statusLoket,
        ]);
    }
}
