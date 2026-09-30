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

            // Antrian yang sudah hadir / dilayani
            $sudahDilayani = $antrians->where('status', 'hadir');
            $totalSelesai = $sudahDilayani->count();

            // Nomor antrian yang sedang atau terakhir dilayani
            $terakhirDilayani = $sudahDilayani->sortByDesc('nomor_antrian')->first();
            $antrianBerjalan = $terakhirDilayani ? $terakhirDilayani->nomor_antrian : null;

            // Antrian yang masih menunggu giliran
            $sedangMenunggu = $antrians->where('status', 'terdaftar');
            $sisaAntrian = $sedangMenunggu->count();
            $berikutnya = $sedangMenunggu->sortBy('nomor_antrian')->first();
            $antrianBerikutnya = $berikutnya ? $berikutnya->nomor_antrian : null;

            // Tentukan status loket pelayanan
            if ($totalAntrianHariIni === 0) {
                $statusLoket = 'persiapan';
            } elseif ($sisaAntrian === 0 && $totalSelesai > 0) {
                $statusLoket = 'selesai';
            } elseif ($totalSelesai > 0 || $antrianBerikutnya !== null) {
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
            'totalAntrianHariIni' => $totalAntrianHariIni,
            'totalSelesai' => $totalSelesai,
            'sisaAntrian' => $sisaAntrian,
            'jadwalBerikutnya' => $jadwalBerikutnya,
            'statusLoket' => $statusLoket,
        ]);
    }
}
