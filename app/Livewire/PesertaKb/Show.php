<?php

namespace App\Livewire\PesertaKb;

use App\Models\PesertaKb;
use Livewire\Component;

class Show extends Component
{
    public PesertaKb $pesertaKb;

    public function mount(PesertaKb $pesertaKb)
    {
        $this->pesertaKb = $pesertaKb->load(['wilayah', 'user', 'skriningMedis.informedConsent', 'pelayanans.alokon']);
    }

    public function render()
    {
        return view('livewire.peserta-kb.show')
            ->layout('layouts.app', ['title' => 'Detail Peserta KB: ' . $this->pesertaKb->nama_lengkap]);
    }

    /**
     * Action to verify a peserta
     */
    public function verifikasi()
    {
        if (!auth()->user()->isAdmin()) {
            $this->dispatch('toast-show', slots: ['text' => 'Hanya admin yang dapat memverifikasi peserta.'], dataset: ['variant' => 'danger']);
            return;
        }

        $this->pesertaKb->verifikasi();
        $this->dispatch('toast-show', slots: ['text' => 'Peserta berhasil diverifikasi.'], dataset: ['variant' => 'success']);
    }
}
