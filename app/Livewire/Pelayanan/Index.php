<?php

namespace App\Livewire\Pelayanan;

use App\Models\Alokon;
use App\Models\Pelayanan;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterAlokon = '';
    public $filterBulan = '';
    public $filterTahun = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterAlokon' => ['except' => ''],
        'filterBulan' => ['except' => ''],
        'filterTahun' => ['except' => ''],
    ];

    public function mount()
    {
        $this->filterTahun = Carbon::now()->year;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterAlokon()
    {
        $this->resetPage();
    }

    public function updatingFilterBulan()
    {
        $this->resetPage();
    }

    public function updatingFilterTahun()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Pelayanan::with(['pesertaKb.wilayah', 'alokon.instansi', 'skriningMedis']);

        if (!empty($this->search)) {
            $query->whereHas('pesertaKb', function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->filterAlokon)) {
            $query->where('alokon_id', $this->filterAlokon);
        }

        if (!empty($this->filterBulan)) {
            $query->whereMonth('tanggal_pelayanan', $this->filterBulan);
        }

        if (!empty($this->filterTahun)) {
            $query->whereYear('tanggal_pelayanan', $this->filterTahun);
        }

        $pelayanans = $query->latest('tanggal_pelayanan')->paginate(10);
        $alokons = Alokon::orderBy('nama_alokon')->get();

        $tahunList = range(Carbon::now()->year, Carbon::now()->year - 5);

        return view('livewire.pelayanan.index', [
            'pelayanans' => $pelayanans,
            'alokons' => $alokons,
            'tahunList' => $tahunList,
        ])->layout('layouts.app', ['title' => 'Riwayat Pelayanan KB']);
    }
}
