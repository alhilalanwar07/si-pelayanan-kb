<?php

namespace App\Livewire\Wilayah;

use App\Models\Wilayah;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editId = null;
    public $confirmingId = null;
    public $confirmingName = '';
    public $nama_desa_kelurahan = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'nama_desa_kelurahan' => ['required', 'string', 'max:255', 'unique:wilayahs,nama_desa_kelurahan'],
    ];

    protected $validationAttributes = [
        'nama_desa_kelurahan' => 'Nama Desa/Kelurahan',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->editId = null;
        $this->nama_desa_kelurahan = '';
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->editId = $id;
        $wilayah = Wilayah::find($id);
        $this->nama_desa_kelurahan = $wilayah->nama_desa_kelurahan;
        $this->showModal = true;
    }

    public function simpan()
    {
        // For unique validation ignore current edit id
        $rules = $this->rules;
        if ($this->editId) {
            $rules['nama_desa_kelurahan'] = ['required', 'string', 'max:255', 'unique:wilayahs,nama_desa_kelurahan,' . $this->editId];
        }

        $this->validate($rules);

        if ($this->editId) {
            $wilayah = Wilayah::find($this->editId);
            $wilayah->update([
                'nama_desa_kelurahan' => $this->nama_desa_kelurahan,
            ]);
            $this->dispatch('toast-show', slots: ['text' => 'Wilayah berhasil diperbarui!'], dataset: ['variant' => 'success']);
        } else {
            $wilayah = Wilayah::create([
                'nama_desa_kelurahan' => $this->nama_desa_kelurahan,
            ]);
            $this->dispatch('toast-show', slots: ['text' => 'Wilayah baru berhasil ditambahkan!'], dataset: ['variant' => 'success']);
        }

        $this->showModal = false;
    }

    public function startDelete($id)
    {
        $wilayah = Wilayah::find($id);
        if ($wilayah) {
            $this->confirmingId = $id;
            $this->confirmingName = $wilayah->nama_desa_kelurahan;
            $this->showDeleteModal = true;
        }
    }

    public function hapus()
    {
        if ($this->confirmingId) {
            $wilayah = Wilayah::find($this->confirmingId);
            if ($wilayah) {
                $nama = $wilayah->nama_desa_kelurahan;
                
                // Check if there are participants in this subdistrict
                if ($wilayah->pesertaKbs()->exists()) {
                    $this->dispatch('toast-show', slots: ['text' => "Wilayah {$nama} tidak dapat dihapus karena memiliki data peserta KB."], dataset: ['variant' => 'danger']);
                    $this->showDeleteModal = false;
                    return;
                }

                $wilayah->delete();
                $this->dispatch('toast-show', slots: ['text' => "Wilayah {$nama} berhasil dihapus."], dataset: ['variant' => 'success']);
            }
        }
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $query = Wilayah::withCount('pesertaKbs');

        if (!empty($this->search)) {
            $query->where('nama_desa_kelurahan', 'like', '%' . $this->search . '%');
        }

        $wilayahs = $query->orderBy('nama_desa_kelurahan')->paginate(10);

        return view('livewire.wilayah.index', [
            'wilayahs' => $wilayahs,
        ])->layout('layouts.app', ['title' => 'Kelola Data Wilayah']);
    }
}
