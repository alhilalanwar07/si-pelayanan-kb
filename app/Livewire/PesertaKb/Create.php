<?php

namespace App\Livewire\PesertaKb;

use App\Models\PesertaKb;
use App\Models\Wilayah;
use Livewire\Component;

class Create extends Component
{
    public $nik = '';
    public $nomor_hp = '';
    public $nama_lengkap = '';
    public $nama_suami_istri = '';
    public $tanggal_lahir_istri = '';
    public $alamat_lengkap = '';
    public $wilayah_id = '';
    public $penggunaan_asuransi = 'umum';
    public $jumlah_anak_hidup = 0;
    public $umur_anak_terakhir = '';

    protected function rules()
    {
        return [
            'nik' => ['required', 'string', 'size:16', 'unique:peserta_kbs,nik'],
            'nomor_hp' => ['required', 'string', 'min:10', 'max:15', 'regex:/^[0-9]+$/'],
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'nama_suami_istri' => ['required', 'string', 'max:255'],
            'tanggal_lahir_istri' => ['required', 'date', 'before:today'],
            'alamat_lengkap' => ['required', 'string'],
            'wilayah_id' => ['required', 'exists:wilayahs,id'],
            'penggunaan_asuransi' => ['required', 'string', 'in:bpjs,kis,umum,lainnya'],
            'jumlah_anak_hidup' => ['required', 'integer', 'min:0'],
            'umur_anak_terakhir' => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected $validationAttributes = [
        'nik' => 'NIK',
        'nomor_hp' => 'Nomor WhatsApp Aktif',
        'nama_lengkap' => 'Nama Lengkap',
        'nama_suami_istri' => 'Nama Suami/Istri',
        'tanggal_lahir_istri' => 'Tanggal Lahir Istri',
        'alamat_lengkap' => 'Alamat Lengkap',
        'wilayah_id' => 'Desa/Kelurahan',
        'penggunaan_asuransi' => 'Penggunaan Asuransi',
        'jumlah_anak_hidup' => 'Jumlah Anak Hidup',
        'umur_anak_terakhir' => 'Umur Anak Terakhir (Bulan)',
    ];

    public function simpan()
    {
        $this->validate();

        $peserta = PesertaKb::create([
            'user_id' => auth()->id(),
            'wilayah_id' => $this->wilayah_id,
            'nik' => $this->nik,
            'nomor_hp' => $this->nomor_hp,
            'nama_lengkap' => $this->nama_lengkap,
            'nama_suami_istri' => $this->nama_suami_istri,
            'tanggal_lahir_istri' => $this->tanggal_lahir_istri,
            'alamat_lengkap' => $this->alamat_lengkap,
            'penggunaan_asuransi' => $this->penggunaan_asuransi,
            'jumlah_anak_hidup' => $this->jumlah_anak_hidup,
            'umur_anak_terakhir' => empty($this->umur_anak_terakhir) ? null : $this->umur_anak_terakhir,
            'status' => 'terverifikasi', // Admin registration is automatically verified
        ]);

        $this->dispatch('toast-show', slots: ['text' => "Peserta {$peserta->nama_lengkap} berhasil ditambahkan!"], dataset: ['variant' => 'success']);
        
        return $this->redirectRoute('peserta-kb.index', navigate: true);
    }

    public function render()
    {
        $wilayahs = Wilayah::orderBy('nama_desa_kelurahan')->get();

        return view('livewire.peserta-kb.create', [
            'wilayahs' => $wilayahs,
        ])->layout('layouts.app', ['title' => 'Tambah Peserta KB']);
    }
}
