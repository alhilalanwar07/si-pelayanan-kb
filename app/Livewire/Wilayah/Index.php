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

    public $link_gmaps = '';

    public $latitude = '';

    public $longitude = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected $rules = [
        'nama_desa_kelurahan' => ['required', 'string', 'max:255', 'unique:wilayahs,nama_desa_kelurahan'],
        'latitude' => ['nullable', 'numeric', 'between:-90,90'],
        'longitude' => ['nullable', 'numeric', 'between:-180,180'],
        'link_gmaps' => ['nullable', 'string', 'max:500'],
    ];

    protected $validationAttributes = [
        'nama_desa_kelurahan' => 'Nama Desa/Kelurahan',
        'latitude' => 'Latitude (Garis Lintang)',
        'longitude' => 'Longitude (Garis Bujur)',
        'link_gmaps' => 'Link Google Maps',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    /**
     * Saat user menempelkan Link Google Maps atau "lat, lng" pada kolom link_gmaps,
     * otomatis ekstrak nilai Latitude & Longitude.
     */
    public function updatedLinkGmaps($value): void
    {
        $coords = Wilayah::extractCoordinatesFromInput($value);
        if ($coords) {
            $this->latitude = (string) $coords['latitude'];
            $this->longitude = (string) $coords['longitude'];
        }
    }

    /**
     * Jika user menempelkan pasangan "-4.1385, 121.6178" langsung di kolom Latitude,
     * otomatis pisahkan ke Latitude dan Longitude.
     */
    public function updatedLatitude($value): void
    {
        if (is_string($value) && str_contains($value, ',')) {
            $coords = Wilayah::extractCoordinatesFromInput($value);
            if ($coords) {
                $this->latitude = (string) $coords['latitude'];
                $this->longitude = (string) $coords['longitude'];
            }
        }
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->editId = null;
        $this->nama_desa_kelurahan = '';
        $this->link_gmaps = '';
        $this->latitude = '';
        $this->longitude = '';
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->editId = $id;
        $wilayah = Wilayah::find($id);
        $this->nama_desa_kelurahan = $wilayah->nama_desa_kelurahan;
        $this->latitude = $wilayah->latitude !== null ? (string) $wilayah->latitude : '';
        $this->longitude = $wilayah->longitude !== null ? (string) $wilayah->longitude : '';
        $this->link_gmaps = $wilayah->link_gmaps ?? '';
        $this->showModal = true;
    }

    public function simpan()
    {
        // Ekstrak koordinat otomatis jika user mengisi link_gmaps tetapi belum mengisi lat/lng
        if (! empty($this->link_gmaps) && ($this->latitude === '' || $this->longitude === '')) {
            $coords = Wilayah::extractCoordinatesFromInput($this->link_gmaps);
            if ($coords) {
                $this->latitude = (string) $coords['latitude'];
                $this->longitude = (string) $coords['longitude'];
            }
        }

        // For unique validation ignore current edit id
        $rules = $this->rules;
        if ($this->editId) {
            $rules['nama_desa_kelurahan'] = ['required', 'string', 'max:255', 'unique:wilayahs,nama_desa_kelurahan,'.$this->editId];
        }

        $this->validate($rules);

        $lat = $this->latitude !== '' && $this->latitude !== null ? (float) $this->latitude : null;
        $lng = $this->longitude !== '' && $this->longitude !== null ? (float) $this->longitude : null;
        $gmaps = trim((string) $this->link_gmaps);

        // Jika koordinat ada tetapi link_gmaps kosong (atau hanya berisi angka koordinat), buatkan URL Google Maps standar
        if ($lat !== null && $lng !== null && ($gmaps === '' || ! str_starts_with($gmaps, 'http'))) {
            $gmaps = "https://www.google.com/maps?q={$lat},{$lng}";
        }

        $payload = [
            'nama_desa_kelurahan' => $this->nama_desa_kelurahan,
            'latitude' => $lat,
            'longitude' => $lng,
            'link_gmaps' => $gmaps !== '' ? $gmaps : null,
        ];

        if ($this->editId) {
            $wilayah = Wilayah::find($this->editId);
            $wilayah->update($payload);
            $this->dispatch('toast-show', slots: ['text' => 'Wilayah dan titik koordinat berhasil diperbarui!'], dataset: ['variant' => 'success']);
        } else {
            $wilayah = Wilayah::create($payload);
            $this->dispatch('toast-show', slots: ['text' => 'Wilayah baru beserta titik koordinat berhasil ditambahkan!'], dataset: ['variant' => 'success']);
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

        if (! empty($this->search)) {
            $query->where('nama_desa_kelurahan', 'like', '%'.$this->search.'%');
        }

        $wilayahs = $query->orderBy('nama_desa_kelurahan')->paginate(10);

        return view('livewire.wilayah.index', [
            'wilayahs' => $wilayahs,
        ])->layout('layouts.app', ['title' => 'Kelola Data Wilayah']);
    }
}
