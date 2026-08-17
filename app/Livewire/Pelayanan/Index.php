<?php

namespace App\Livewire\Pelayanan;

use App\Models\Alokon;
use App\Models\AntrianJadwal;
use App\Models\JadwalPelayanan;
use App\Models\Pelayanan;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    // Tab state: 'antrian' | 'riwayat'
    public string $tab = 'antrian';

    // Filters for Riwayat
    public $search = '';
    public $filterAlokon = '';
    public $filterBulan = '';
    public $filterTahun = '';

    // Filter for Antrian
    public $searchAntrian = '';
    public ?int $selectedJadwalId = null;

    protected $queryString = [
        'tab' => ['except' => 'antrian'],
        'search' => ['except' => ''],
        'filterAlokon' => ['except' => ''],
        'filterBulan' => ['except' => ''],
        'filterTahun' => ['except' => ''],
    ];

    public function mount()
    {
        $this->filterTahun = Carbon::now()->year;
        
        // Default selected jadwal: today or nearest upcoming
        $todayJadwal = JadwalPelayanan::where('instansi_id', auth()->user()->instansi_id)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        if ($todayJadwal) {
            $this->selectedJadwalId = $todayJadwal->id;
        } else {
            $nearest = JadwalPelayanan::where('instansi_id', auth()->user()->instansi_id)
                ->mendatang()
                ->orderBy('tanggal')
                ->first();
            $this->selectedJadwalId = $nearest?->id;
        }
    }

    public function switchTab(string $tabName)
    {
        $this->tab = $tabName;
        $this->resetPage();
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

    public function tandaiTidakHadir(int $antrianId)
    {
        $antrian = AntrianJadwal::findOrFail($antrianId);
        $antrian->update(['status' => 'tidak_hadir']);
        
        $this->dispatch('toast-show', slots: ['text' => 'Status antrian diubah menjadi Tidak Hadir.'], dataset: ['variant' => 'info']);
    }

    public function layaniPeserta(int $pesertaId, int $antrianId)
    {
        return $this->redirectRoute('pelayanan.create', [
            'peserta_id' => $pesertaId,
            'antrian_id' => $antrianId,
        ], navigate: true);
    }

    public function render()
    {
        $instansiId = auth()->user()->instansi_id;

        // ──── 1. STATISTIK ────
        $todayJadwal = JadwalPelayanan::where('instansi_id', $instansiId)
            ->whereDate('tanggal', now()->toDateString())
            ->first();

        $activeJadwals = JadwalPelayanan::where('instansi_id', $instansiId)
            ->mendatang()
            ->orderBy('tanggal')
            ->get();

        $currentJadwal = $this->selectedJadwalId 
            ? JadwalPelayanan::find($this->selectedJadwalId)
            : ($todayJadwal ?? $activeJadwals->first());

        $totalAntrianHariIni = 0;
        $antrianHadir = 0;
        $antrianMenunggu = 0;

        if ($currentJadwal) {
            $totalAntrianHariIni = $currentJadwal->antrians()->count();
            $antrianHadir = $currentJadwal->antrians()->where('status', 'hadir')->count();
            $antrianMenunggu = $currentJadwal->antrians()->where('status', 'terdaftar')->count();
        }

        $totalPelayananBulanIni = Pelayanan::whereMonth('tanggal_pelayanan', now()->month)
            ->whereYear('tanggal_pelayanan', now()->year)
            ->count();

        // ──── 2. QUERY DAFTAR ANTRIAN ────
        $antrians = collect();
        if ($currentJadwal) {
            $antrianQuery = $currentJadwal->antrians()->with(['pesertaKb.wilayah'])->orderBy('nomor_antrian');
            
            if (!empty($this->searchAntrian)) {
                $antrianQuery->whereHas('pesertaKb', function ($q) {
                    $q->where('nama_lengkap', 'like', '%' . $this->searchAntrian . '%')
                      ->orWhere('nik', 'like', '%' . $this->searchAntrian . '%');
                });
            }
            
            $antrians = $antrianQuery->get();
        }

        // ──── 3. QUERY RIWAYAT PELAYANAN ────
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
            'tab' => $this->tab,
            'todayJadwal' => $todayJadwal,
            'currentJadwal' => $currentJadwal,
            'activeJadwals' => $activeJadwals,
            'totalAntrianHariIni' => $totalAntrianHariIni,
            'antrianHadir' => $antrianHadir,
            'antrianMenunggu' => $antrianMenunggu,
            'totalPelayananBulanIni' => $totalPelayananBulanIni,
            'antrians' => $antrians,
            'pelayanans' => $pelayanans,
            'alokons' => $alokons,
            'tahunList' => $tahunList,
        ])->layout('layouts.app', ['title' => 'Pusat Pelayanan & Antrian KB']);
    }
}
