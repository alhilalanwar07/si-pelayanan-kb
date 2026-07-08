<?php

namespace App\Livewire\Alokon;

use App\Models\Alokon;
use App\Models\Instansi;
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
    
    // Form fields
    public $instansi_id = '';
    public $nama_alokon = '';
    public $stok = 0;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected function rules()
    {
        return [
            'instansi_id' => ['required', 'exists:instansis,id'],
            'nama_alokon' => ['required', 'string', 'max:255'],
            'stok' => ['required', 'integer', 'min:0'],
        ];
    }

    protected $validationAttributes = [
        'instansi_id' => 'Instansi Faskes',
        'nama_alokon' => 'Nama Alat/Obat Kontrasepsi',
        'stok' => 'Jumlah Stok',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->editId = null;
        $this->nama_alokon = '';
        $this->stok = 0;
        
        // Select first instansi by default if exists
        $firstInstansi = Instansi::first();
        $this->instansi_id = $firstInstansi ? $firstInstansi->id : '';
        
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->editId = $id;
        $alokon = Alokon::find($id);
        $this->instansi_id = $alokon->instansi_id;
        $this->nama_alokon = $alokon->nama_alokon;
        $this->stok = $alokon->stok;
        $this->showModal = true;
    }

    public function simpan()
    {
        $this->validate();

        if ($this->editId) {
            $alokon = Alokon::find($this->editId);
            $alokon->update([
                'instansi_id' => $this->instansi_id,
                'nama_alokon' => $this->nama_alokon,
                'stok' => $this->stok,
            ]);
            $this->dispatch('toast-show', slots: ['text' => 'Stok alokon berhasil diperbarui!'], dataset: ['variant' => 'success']);
        } else {
            Alokon::create([
                'instansi_id' => $this->instansi_id,
                'nama_alokon' => $this->nama_alokon,
                'stok' => $this->stok,
            ]);
            $this->dispatch('toast-show', slots: ['text' => 'Alokon baru berhasil ditambahkan!'], dataset: ['variant' => 'success']);
        }

        $this->showModal = false;
    }

    public function startDelete($id)
    {
        $alokon = Alokon::find($id);
        if ($alokon) {
            $this->confirmingId = $id;
            $this->confirmingName = $alokon->nama_alokon;
            $this->showDeleteModal = true;
        }
    }

    public function hapus()
    {
        if ($this->confirmingId) {
            $alokon = Alokon::find($this->confirmingId);
            if ($alokon) {
                $nama = $alokon->nama_alokon;
                
                // Check if there are services recorded with this alokon
                if ($alokon->pelayanans()->exists()) {
                    $this->dispatch('toast-show', slots: ['text' => "Alokon {$nama} tidak dapat dihapus karena memiliki riwayat pelayanan."], dataset: ['variant' => 'danger']);
                    $this->showDeleteModal = false;
                    return;
                }

                $alokon->delete();
                $this->dispatch('toast-show', slots: ['text' => "Alokon {$nama} berhasil dihapus."], dataset: ['variant' => 'success']);
            }
        }
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $query = Alokon::with('instansi');

        if (!empty($this->search)) {
            $query->where('nama_alokon', 'like', '%' . $this->search . '%');
        }

        $alokons = $query->latest()->paginate(10);
        $instansis = Instansi::orderBy('nama_instansi')->get();

        return view('livewire.alokon.index', [
            'alokons' => $alokons,
            'instansis' => $instansis,
        ])->layout('layouts.app', ['title' => 'Inventaris Alokon']);
    }
}
