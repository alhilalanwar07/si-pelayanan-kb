<?php

namespace App\Livewire\PetaSebaran;

use App\Models\PesertaKb;
use App\Models\Wilayah;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        $totalPeserta = PesertaKb::count();

        // Get participants count by wilayah
        $wilayahs = Wilayah::withCount('pesertaKbs')
            ->orderBy('peserta_kbs_count', 'desc')
            ->get();

        // Map colors based on density
        $wilayahs = $wilayahs->map(function ($w) use ($totalPeserta) {
            $w->persentase = $totalPeserta > 0 
                ? round(($w->peserta_kbs_count / $totalPeserta) * 100, 1) 
                : 0;

            // Density color (Tailwind color classes)
            if ($w->peserta_kbs_count >= 4) {
                $w->color = 'fill-blue-600 hover:fill-blue-700 dark:fill-blue-500';
                $w->bg_class = 'bg-blue-600';
            } elseif ($w->peserta_kbs_count >= 3) {
                $w->color = 'fill-blue-400 hover:fill-blue-500 dark:fill-blue-400';
                $w->bg_class = 'bg-blue-400';
            } elseif ($w->peserta_kbs_count >= 2) {
                $w->color = 'fill-blue-300 hover:fill-blue-450 dark:fill-blue-300';
                $w->bg_class = 'bg-blue-300';
            } else {
                $w->color = 'fill-blue-100 hover:fill-blue-200 dark:fill-blue-200';
                $w->bg_class = 'bg-blue-100';
            }

            return $w;
        });

        return view('livewire.peta-sebaran.index', [
            'wilayahs' => $wilayahs,
            'totalPeserta' => $totalPeserta,
        ])->layout('layouts.app', ['title' => 'Peta Sebaran Peserta KB']);
    }
}
