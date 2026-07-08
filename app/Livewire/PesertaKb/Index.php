<?php

namespace App\Livewire\PesertaKb;

use App\Models\PesertaKb;
use App\Models\Wilayah;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $filterWilayah = '';
    public $filterStatus = '';
    public $showDeleteModal = false;
    public $confirmingId = null;
    public $confirmingName = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'filterWilayah' => ['except' => ''],
        'filterStatus' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingFilterWilayah()
    {
        $this->resetPage();
    }

    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = PesertaKb::with(['wilayah', 'user']);

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('nama_lengkap', 'like', '%' . $this->search . '%')
                  ->orWhere('nik', 'like', '%' . $this->search . '%')
                  ->orWhere('nama_suami_istri', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->filterWilayah)) {
            $query->where('wilayah_id', $this->filterWilayah);
        }

        if (!empty($this->filterStatus)) {
            $query->where('status', $this->filterStatus);
        }

        $pesertas = $query->latest()->paginate(10);
        $wilayahs = Wilayah::orderBy('nama_desa_kelurahan')->get();

        return view('livewire.peserta-kb.index', [
            'pesertas' => $pesertas,
            'wilayahs' => $wilayahs,
        ])->layout('layouts.app', ['title' => 'Daftar Peserta KB']);
    }

    /**
     * Action to verify a peserta
     */
    public function verifikasi($id)
    {
        if (!auth()->user()->isAdmin()) {
            $this->dispatch('toast-show', slots: ['text' => 'Hanya admin yang dapat memverifikasi peserta.'], dataset: ['variant' => 'danger']);
            return;
        }

        $peserta = PesertaKb::find($id);
        if ($peserta) {
            $peserta->verifikasi();
            $this->dispatch('toast-show', slots: ['text' => "Peserta {$peserta->nama_lengkap} berhasil diverifikasi."], dataset: ['variant' => 'success']);
        }
    }

    public function startDelete($id)
    {
        if (!auth()->user()->isAdmin()) {
            $this->dispatch('toast-show', slots: ['text' => 'Hanya admin yang dapat menghapus peserta.'], dataset: ['variant' => 'danger']);
            return;
        }

        $peserta = PesertaKb::find($id);
        if ($peserta) {
            $this->confirmingId = $id;
            $this->confirmingName = $peserta->nama_lengkap;
            $this->showDeleteModal = true;
        }
    }

    public function hapus()
    {
        if (!auth()->user()->isAdmin()) {
            $this->dispatch('toast-show', slots: ['text' => 'Hanya admin yang dapat menghapus peserta.'], dataset: ['variant' => 'danger']);
            return;
        }

        if ($this->confirmingId) {
            $peserta = PesertaKb::find($this->confirmingId);
            if ($peserta) {
                $nama = $peserta->nama_lengkap;
                $peserta->delete();
                $this->dispatch('toast-show', slots: ['text' => "Peserta {$nama} berhasil dihapus."], dataset: ['variant' => 'success']);
            }
        }
        $this->showDeleteModal = false;
    }
}
