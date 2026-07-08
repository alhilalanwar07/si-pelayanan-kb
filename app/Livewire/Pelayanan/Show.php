<?php

namespace App\Livewire\Pelayanan;

use App\Models\Pelayanan;
use Livewire\Component;

class Show extends Component
{
    public Pelayanan $pelayanan;

    public function mount(Pelayanan $pelayanan)
    {
        $this->pelayanan = $pelayanan->load(['pesertaKb.wilayah', 'alokon.instansi', 'skriningMedis.informedConsent']);
    }

    public function render()
    {
        return view('livewire.pelayanan.show')
            ->layout('layouts.app', ['title' => 'Detail Pelayanan KB: ' . $this->pelayanan->pesertaKb->nama_lengkap]);
    }
}
