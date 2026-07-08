<?php

namespace App\Livewire\SkriningMedis;

use App\Models\SkriningMedis;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = SkriningMedis::with(['pesertaKb.wilayah', 'informedConsent']);

        if (!empty($this->search)) {
            $query->whereHas('pesertaKb', function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }

        $skrinings = $query->latest('tanggal_skrining')->paginate(10);

        return view('livewire.skrining-medis.index', [
            'skrinings' => $skrinings,
        ])->layout('layouts.app', ['title' => 'Riwayat Skrining Medis']);
    }
}
