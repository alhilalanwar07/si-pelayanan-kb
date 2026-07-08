<?php

namespace App\Livewire\InformedConsent;

use App\Models\InformedConsent;
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
        $query = InformedConsent::with(['skriningMedis.pesertaKb.wilayah']);

        if (!empty($this->search)) {
            $query->whereHas('skriningMedis.pesertaKb', function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }

        $consents = $query->latest('tanggal_persetujuan')->paginate(10);

        return view('livewire.informed-consent.index', [
            'consents' => $consents,
        ])->layout('layouts.app', ['title' => 'Persetujuan Tindakan (Informed Consent)']);
    }
}
