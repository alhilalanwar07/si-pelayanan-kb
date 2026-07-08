<?php

namespace App\Livewire\Pelayanan;

use App\Models\Pelayanan;
use Livewire\Component;

class Cetak extends Component
{
    public Pelayanan $pelayanan;

    public function mount(Pelayanan $pelayanan)
    {
        $this->pelayanan = $pelayanan->load(['pesertaKb.wilayah', 'skriningMedis', 'alokon']);
    }

    public function render()
    {
        return view('livewire.pelayanan.cetak')
            ->layout('layouts.plain', ['title' => 'Cetak Formulir Pelayanan KB']);
    }
}
