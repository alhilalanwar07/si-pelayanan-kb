<?php

namespace App\Livewire;

use App\Models\Alokon;
use App\Models\Pelayanan;
use App\Models\PesertaKb;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        // 1. Stats
        $totalPeserta = PesertaKb::count();
        
        $pesertaBulanIni = PesertaKb::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        $pelayananBulanIni = Pelayanan::whereMonth('tanggal_pelayanan', Carbon::now()->month)
            ->whereYear('tanggal_pelayanan', Carbon::now()->year)
            ->count();

        $menungguVerifikasi = PesertaKb::menunggu()->count();

        $peringatanStok = Alokon::stokRendah(10)->count();

        // 2. Registrasi Peserta Terbaru (limit 5)
        $pesertaTerbaru = PesertaKb::with('wilayah')
            ->latest()
            ->limit(5)
            ->get();

        // 3. Stok Alokon
        $alokons = Alokon::with('instansi')->get();

        // 4. Peserta per Wilayah (Ranking)
        $wilayahRank = Wilayah::withCount('pesertaKbs')
            ->orderBy('peserta_kbs_count', 'desc')
            ->get();

        // Calculate percentages for wilayah rank
        $wilayahRank = $wilayahRank->map(function ($wilayah) use ($totalPeserta) {
            $wilayah->persentase = $totalPeserta > 0 
                ? round(($wilayah->peserta_kbs_count / $totalPeserta) * 100) 
                : 0;
            return $wilayah;
        });

        // 5. Chart data: Pelayanan per Bulan (running year)
        $monthlyPelayanans = Pelayanan::select(
            DB::raw('count(id) as total'),
            DB::raw('MONTH(tanggal_pelayanan) as month')
        )
        ->whereYear('tanggal_pelayanan', Carbon::now()->year)
        ->groupBy('month')
        ->orderBy('month')
        ->get()
        ->pluck('total', 'month')
        ->toArray();

        $chartLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyPelayanans[$i] ?? 0;
        }

        return view('livewire.dashboard', [
            'totalPeserta' => $totalPeserta,
            'pesertaBulanIni' => $pesertaBulanIni,
            'pelayananBulanIni' => $pelayananBulanIni,
            'menungguVerifikasi' => $menungguVerifikasi,
            'peringatanStok' => $peringatanStok,
            'pesertaTerbaru' => $pesertaTerbaru,
            'alokons' => $alokons,
            'wilayahRank' => $wilayahRank,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ])->layout('layouts.app', ['title' => 'Dashboard']);
    }

    /**
     * Action to verify a peserta immediately from dashboard
     */
    public function verifikasiPeserta($pesertaId)
    {
        if (!auth()->user()->isAdmin()) {
            $this->dispatch('toast-show', slots: ['text' => 'Hanya admin yang dapat memverifikasi peserta.'], dataset: ['variant' => 'danger']);
            return;
        }

        $peserta = PesertaKb::find($pesertaId);
        if ($peserta) {
            $peserta->verifikasi();
            $this->dispatch('toast-show', slots: ['text' => "Peserta {$peserta->nama_lengkap} berhasil diverifikasi!"], dataset: ['variant' => 'success']);
        }
    }
}
