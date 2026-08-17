<?php

namespace App\Livewire;

use App\Models\JadwalPelayanan;
use App\Models\Pelayanan;
use App\Models\PesertaKb;
use Livewire\Component;

class Beranda extends Component
{
    public function render()
    {
        $totalPesertaTerlayani = Pelayanan::distinct('peserta_kb_id')->count('peserta_kb_id');
        $totalPesertaTerdaftar = PesertaKb::count();

        $jadwalMendatang = JadwalPelayanan::aktif()
            ->mendatang()
            ->withCount('antrians')
            ->orderBy('tanggal')
            ->orderBy('waktu_mulai')
            ->limit(6)
            ->get();

        return view('livewire.beranda', [
            'totalPesertaTerlayani' => $totalPesertaTerlayani,
            'totalPesertaTerdaftar' => $totalPesertaTerdaftar,
            'jadwalMendatang' => $jadwalMendatang,
        ])->layout('layouts.plain', ['title' => 'SI Pelayanan KB — Kecamatan Wundulako']);
    }
}
