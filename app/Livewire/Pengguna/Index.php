<?php

namespace App\Livewire\Pengguna;

use App\Models\Instansi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
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
    public $name = '';
    public $username = '';
    public $password = '';
    public $level_akses = 'bidan';
    public $instansi_id = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    protected function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:100', 'unique:users,username,' . $this->editId],
            'password' => $this->editId ? ['nullable', 'string', 'min:6'] : ['required', 'string', 'min:6'],
            'level_akses' => ['required', 'string', 'in:admin,bidan,pimpinan'],
            'instansi_id' => ['required', 'exists:instansis,id'],
        ];
    }

    protected $validationAttributes = [
        'name' => 'Nama Lengkap',
        'username' => 'Username',
        'password' => 'Password',
        'level_akses' => 'Hak Akses',
        'instansi_id' => 'Faskes / Instansi',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openCreateModal()
    {
        $this->resetValidation();
        $this->editId = null;
        $this->name = '';
        $this->username = '';
        $this->password = '';
        $this->level_akses = 'bidan';
        
        $firstInstansi = Instansi::first();
        $this->instansi_id = $firstInstansi ? $firstInstansi->id : '';
        
        $this->showModal = true;
    }

    public function openEditModal($id)
    {
        $this->resetValidation();
        $this->editId = $id;
        $user = User::find($id);
        $this->name = $user->name;
        $this->username = $user->username;
        $this->password = '';
        $this->level_akses = $user->level_akses;
        $this->instansi_id = $user->instansi_id;
        $this->showModal = true;
    }

    public function simpan()
    {
        $this->validate();

        if ($this->editId) {
            $user = User::find($this->editId);
            $data = [
                'name' => $this->name,
                'username' => $this->username,
                'level_akses' => $this->level_akses,
                'instansi_id' => $this->instansi_id,
            ];
            
            if (!empty($this->password)) {
                $data['password'] = Hash::make($this->password);
            }
            
            $user->update($data);
            $this->dispatch('toast-show', slots: ['text' => 'Data pengguna berhasil diperbarui!'], dataset: ['variant' => 'success']);
        } else {
            User::create([
                'name' => $this->name,
                'username' => $this->username,
                'password' => Hash::make($this->password),
                'level_akses' => $this->level_akses,
                'instansi_id' => $this->instansi_id,
            ]);
            $this->dispatch('toast-show', slots: ['text' => 'Pengguna baru berhasil didaftarkan!'], dataset: ['variant' => 'success']);
        }

        $this->showModal = false;
    }

    public function startDelete($id)
    {
        // Don't allow self-deletion
        if (auth()->id() == $id) {
            $this->dispatch('toast-show', slots: ['text' => 'Anda tidak dapat menghapus akun Anda sendiri.'], dataset: ['variant' => 'danger']);
            return;
        }

        $user = User::find($id);
        if ($user) {
            $this->confirmingId = $id;
            $this->confirmingName = $user->name;
            $this->showDeleteModal = true;
        }
    }

    public function hapus()
    {
        if ($this->confirmingId) {
            // Don't allow self-deletion
            if (auth()->id() == $this->confirmingId) {
                $this->dispatch('toast-show', slots: ['text' => 'Anda tidak dapat menghapus akun Anda sendiri.'], dataset: ['variant' => 'danger']);
                $this->showDeleteModal = false;
                return;
            }

            $user = User::find($this->confirmingId);
            if ($user) {
                $nama = $user->name;
                $user->delete();
                $this->dispatch('toast-show', slots: ['text' => "Pengguna {$nama} berhasil dihapus."], dataset: ['variant' => 'success']);
            }
        }
        $this->showDeleteModal = false;
    }

    public function render()
    {
        $query = User::with('instansi');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('username', 'like', '%' . $this->search . '%');
            });
        }

        $users = $query->latest()->paginate(10);
        $instansis = Instansi::orderBy('nama_instansi')->get();

        return view('livewire.pengguna.index', [
            'users' => $users,
            'instansis' => $instansis,
        ])->layout('layouts.app', ['title' => 'Manajemen Pengguna']);
    }
}
