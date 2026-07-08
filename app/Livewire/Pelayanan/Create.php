<?php

namespace App\Livewire\Pelayanan;

use App\Models\Alokon;
use App\Models\InformedConsent;
use App\Models\Pelayanan;
use App\Models\PesertaKb;
use App\Models\SkriningMedis;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{
    public $currentStep = 1;
    public $isLayak = true;
    public $medicalWarningMessage = '';

    // Step 1: Skrining Medis & Profil Peserta
    public $peserta_kb_id = '';
    public $nik = '';
    public $tanggal_skrining = '';
    
    // Peserta Kb details to update
    public $pendidikan_istri = '';
    public $pendidikan_suami = '';
    public $pekerjaan_istri = '';
    public $pekerjaan_suami = '';
    public $jumlah_anak_laki = 0;
    public $jumlah_anak_perempuan = 0;
    public $status_kepesertaan = 'baru';
    public $kb_terakhir = '';

    // Anamnese
    public $haid_terakhir = '';
    public $hamil_diduga_hamil = false;
    public $gravida_partus_abortus = '';
    public $status_menyusui = false;
    
    // Penyakit
    public $rwyt_sakit_kuning = false;
    public $rwyt_pendarahan = false;
    public $rwyt_keputihan = false;
    public $rwyt_tumor = false;

    // Pemeriksaan Fisik
    public $fisik_keadaan_umum = 'baik';
    public $fisik_berat_badan = '';
    public $fisik_tekanan_darah = '';
    
    // Pemeriksaan Dalam
    public $pemeriksaan_dalam_radang = false;
    public $pemeriksaan_dalam_tumor = false;
    public $posisi_rahim = 'retroflexi'; // retroflexi, antaflexi, normal

    // Pemeriksaan Tambahan
    public $pemeriksaan_tambahan_diabetes = false;
    public $pemeriksaan_tambahan_pembekuan_darah = false;
    public $pemeriksaan_tambahan_orchitis = false;
    public $pemeriksaan_tambahan_tumor = false;

    public $alat_kontrasepsi_boleh_digunakan = [];

    // Step 2: Informed Consent
    public $persetujuan_klien = false;
    public $persetujuan_pasangan = false;
    public $jenis_tindakan_medis = 'pemasangan';
    public $tanggal_persetujuan = '';

    // Step 3: Pencatatan Pelayanan
    public $alokon_id = '';
    public $tanggal_pelayanan = '';
    public $keterangan = '';
    public $tanggal_kunjungan_ulang = '';
    public $tanggal_dicabut = '';
    
    // Penanggung Jawab Pelayanan
    public $penanggung_jawab_nama = '';
    public $penanggung_jawab_nip = '';
    public $penanggung_jawab_jabatan = 'bidan'; // dokter, bidan, perawat

    public function mount()
    {
        $this->tanggal_skrining = now()->toDateString();
        $this->tanggal_persetujuan = now()->toDateString();
        $this->tanggal_pelayanan = now()->toDateString();
    }

    public function updatedPesertaKbId($value)
    {
        $peserta = PesertaKb::find($value);
        $this->nik = $peserta ? $peserta->nik : '';
        
        if ($peserta) {
            $this->pendidikan_istri = $peserta->pendidikan_istri ?? '';
            $this->pendidikan_suami = $peserta->pendidikan_suami ?? '';
            $this->pekerjaan_istri = $peserta->pekerjaan_istri ?? '';
            $this->pekerjaan_suami = $peserta->pekerjaan_suami ?? '';
            $this->jumlah_anak_laki = $peserta->jumlah_anak_laki ?? 0;
            $this->jumlah_anak_perempuan = $peserta->jumlah_anak_perempuan ?? 0;
            $this->status_kepesertaan = $peserta->status_kepesertaan ?? 'baru';
            $this->kb_terakhir = $peserta->kb_terakhir ?? '';
        }
    }

    /**
     * Check if the participant is medically fit to proceed
     */
    public function checkKelayakanMedis()
    {
        $warnings = [];

        if ($this->rwyt_tumor) {
            $warnings[] = 'Adanya indikasi riwayat tumor/benjolan.';
        }
        if ($this->rwyt_pendarahan) {
            $warnings[] = 'Adanya indikasi riwayat pendarahan rahim yang tidak biasa.';
        }
        if ($this->rwyt_sakit_kuning) {
            $warnings[] = 'Adanya indikasi penyakit kuning (hepatitis/gangguan hati).';
        }
        if ($this->fisik_keadaan_umum === 'lemah') {
            $warnings[] = 'Kondisi fisik keadaan umum pasien lemah.';
        }

        if (count($warnings) > 0) {
            $this->isLayak = false;
            $this->medicalWarningMessage = 'Pasien dinyatakan TIDAK LAYAK untuk menerima tindakan kontrasepsi karena: ' . implode(' ', $warnings);
        } else {
            $this->isLayak = true;
            $this->medicalWarningMessage = '';
        }
    }

    public function updated($propertyName)
    {
        // Re-evaluate eligibility if any relevant field changes in Step 1
        if (in_array($propertyName, ['rwyt_tumor', 'rwyt_pendarahan', 'rwyt_sakit_kuning', 'fisik_keadaan_umum'])) {
            $this->checkKelayakanMedis();
        }
    }

    public function nextStep()
    {
        if ($this->currentStep === 1) {
            $this->validate([
                'peserta_kb_id' => ['required', 'exists:peserta_kbs,id'],
                'tanggal_skrining' => ['required', 'date'],
                'pendidikan_istri' => ['required', 'string'],
                'pendidikan_suami' => ['required', 'string'],
                'pekerjaan_istri' => ['required', 'string'],
                'pekerjaan_suami' => ['required', 'string'],
                'jumlah_anak_laki' => ['required', 'integer', 'min:0'],
                'jumlah_anak_perempuan' => ['required', 'integer', 'min:0'],
                'status_kepesertaan' => ['required', 'string', 'in:baru,ganti_cara,ulangan'],
                'kb_terakhir' => ['nullable', 'string'],
                'haid_terakhir' => ['nullable', 'date'],
                'hamil_diduga_hamil' => ['required', 'boolean'],
                'gravida_partus_abortus' => ['nullable', 'string', 'max:20'],
                'status_menyusui' => ['required', 'boolean'],
                'fisik_keadaan_umum' => ['required', 'string', 'in:baik,sedang,kurang,lemah'],
                'fisik_berat_badan' => ['nullable', 'numeric', 'min:0'],
                'fisik_tekanan_darah' => ['nullable', 'string', 'max:15'],
                'pemeriksaan_dalam_radang' => ['required', 'boolean'],
                'pemeriksaan_dalam_tumor' => ['required', 'boolean'],
                'posisi_rahim' => ['required', 'string', 'in:retroflexi,antaflexi,normal'],
                'pemeriksaan_tambahan_diabetes' => ['required', 'boolean'],
                'pemeriksaan_tambahan_pembekuan_darah' => ['required', 'boolean'],
                'pemeriksaan_tambahan_orchitis' => ['required', 'boolean'],
                'pemeriksaan_tambahan_tumor' => ['required', 'boolean'],
            ], [], [
                'peserta_kb_id' => 'Peserta KB',
                'tanggal_skrining' => 'Tanggal Skrining',
                'pendidikan_istri' => 'Pendidikan Istri',
                'pendidikan_suami' => 'Pendidikan Suami',
                'pekerjaan_istri' => 'Pekerjaan Istri',
                'pekerjaan_suami' => 'Pekerjaan Suami',
                'jumlah_anak_laki' => 'Jumlah Anak Laki-laki',
                'jumlah_anak_perempuan' => 'Jumlah Anak Perempuan',
                'status_kepesertaan' => 'Status Peserta KB',
                'kb_terakhir' => 'KB Terakhir',
                'haid_terakhir' => 'Tanggal Haid Terakhir',
                'hamil_diduga_hamil' => 'Hamil / Diduga Hamil',
                'status_menyusui' => 'Status Menyusui',
                'fisik_keadaan_umum' => 'Keadaan Umum',
                'fisik_berat_badan' => 'Berat Badan',
                'fisik_tekanan_darah' => 'Tekanan Darah',
                'pemeriksaan_dalam_radang' => 'Tanda-tanda Radang',
                'pemeriksaan_dalam_tumor' => 'Tumor Ginekologi',
                'posisi_rahim' => 'Posisi Rahim',
                'pemeriksaan_tambahan_diabetes' => 'Tanda-tanda Diabetes',
                'pemeriksaan_tambahan_pembekuan_darah' => 'Kelainan Pembekuan Darah',
                'pemeriksaan_tambahan_orchitis' => 'Radang Orchitis/Epididymitis',
                'pemeriksaan_tambahan_tumor' => 'Tumor Tambahan',
            ]);

            $this->checkKelayakanMedis();

            if (!$this->isLayak) {
                $this->dispatch('toast-show', slots: ['text' => 'Skrining medis tidak lolos. Langkah berikutnya dikunci.'], dataset: ['variant' => 'danger']);
                return;
            }

            $this->currentStep = 2;
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'persetujuan_klien' => ['accepted'],
                'persetujuan_pasangan' => ['accepted'],
                'jenis_tindakan_medis' => ['required', 'string', 'in:pemasangan,pencabutan,penggantian,penyuntikan'],
                'tanggal_persetujuan' => ['required', 'date'],
            ], [], [
                'persetujuan_klien' => 'Persetujuan Klien',
                'persetujuan_pasangan' => 'Persetujuan Pasangan/Suami',
                'jenis_tindakan_medis' => 'Jenis Tindakan Medis',
                'tanggal_persetujuan' => 'Tanggal Persetujuan',
            ]);

            $this->currentStep = 3;
        }
    }

    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function simpan()
    {
        $this->validate([
            'alokon_id' => ['required', 'exists:alokons,id'],
            'tanggal_pelayanan' => ['required', 'date'],
            'keterangan' => ['nullable', 'string'],
            'tanggal_kunjungan_ulang' => ['nullable', 'date'],
            'tanggal_dicabut' => ['nullable', 'date'],
            'penanggung_jawab_nama' => ['required', 'string', 'max:255'],
            'penanggung_jawab_nip' => ['nullable', 'string', 'max:50'],
            'penanggung_jawab_jabatan' => ['required', 'string', 'in:dokter,bidan,perawat'],
        ], [], [
            'alokon_id' => 'Alat Kontrasepsi (Alokon)',
            'tanggal_pelayanan' => 'Tanggal Pelayanan',
            'keterangan' => 'Keterangan',
            'tanggal_kunjungan_ulang' => 'Tanggal Kunjungan Ulang',
            'tanggal_dicabut' => 'Tanggal Dicabut',
            'penanggung_jawab_nama' => 'Nama Penanggung Jawab',
            'penanggung_jawab_nip' => 'NIP Penanggung Jawab',
            'penanggung_jawab_jabatan' => 'Jabatan Penanggung Jawab',
        ]);

        $alokon = Alokon::find($this->alokon_id);
        if (!$alokon->isStokTersedia(1)) {
            $this->dispatch('toast-show', slots: ['text' => "Stok {$alokon->nama_alokon} tidak mencukupi!"], dataset: ['variant' => 'danger']);
            return;
        }

        DB::transaction(function () use ($alokon) {
            // Update profile data in PesertaKb
            $peserta = PesertaKb::find($this->peserta_kb_id);
            $peserta->update([
                'pendidikan_istri' => $this->pendidikan_istri,
                'pendidikan_suami' => $this->pendidikan_suami,
                'pekerjaan_istri' => $this->pekerjaan_istri,
                'pekerjaan_suami' => $this->pekerjaan_suami,
                'jumlah_anak_laki' => $this->jumlah_anak_laki,
                'jumlah_anak_perempuan' => $this->jumlah_anak_perempuan,
                'jumlah_anak_hidup' => $this->jumlah_anak_laki + $this->jumlah_anak_perempuan,
                'status_kepesertaan' => $this->status_kepesertaan,
                'kb_terakhir' => $this->kb_terakhir,
            ]);

            // 1. Save Skrining Medis
            $skrining = SkriningMedis::create([
                'peserta_kb_id' => $this->peserta_kb_id,
                'tanggal_skrining' => $this->tanggal_skrining,
                'haid_terakhir' => empty($this->haid_terakhir) ? null : $this->haid_terakhir,
                'gravida_partus_abortus' => $this->gravida_partus_abortus,
                'status_menyusui' => $this->status_menyusui,
                'rwyt_sakit_kuning' => $this->rwyt_sakit_kuning,
                'rwyt_pendarahan' => $this->rwyt_pendarahan,
                'rwyt_keputihan' => $this->rwyt_keputihan,
                'rwyt_tumor' => $this->rwyt_tumor,
                'fisik_keadaan_umum' => $this->fisik_keadaan_umum,
                'fisik_berat_badan' => empty($this->fisik_berat_badan) ? null : $this->fisik_berat_badan,
                'fisik_tekanan_darah' => $this->fisik_tekanan_darah,
                'posisi_rahim' => $this->posisi_rahim,
                'hamil_diduga_hamil' => $this->hamil_diduga_hamil,
                'pemeriksaan_dalam_radang' => $this->pemeriksaan_dalam_radang,
                'pemeriksaan_dalam_tumor' => $this->pemeriksaan_dalam_tumor,
                'pemeriksaan_tambahan_diabetes' => $this->pemeriksaan_tambahan_diabetes,
                'pemeriksaan_tambahan_pembekuan_darah' => $this->pemeriksaan_tambahan_pembekuan_darah,
                'pemeriksaan_tambahan_orchitis' => $this->pemeriksaan_tambahan_orchitis,
                'pemeriksaan_tambahan_tumor' => $this->pemeriksaan_tambahan_tumor,
                'alat_kontrasepsi_boleh_digunakan' => json_encode($this->alat_kontrasepsi_boleh_digunakan),
            ]);

            // 2. Save Informed Consent
            InformedConsent::create([
                'skrining_medis_id' => $skrining->id,
                'persetujuan_klien' => $this->persetujuan_klien,
                'persetujuan_pasangan' => $this->persetujuan_pasangan,
                'jenis_tindakan_medis' => $this->jenis_tindakan_medis,
                'tanggal_persetujuan' => $this->tanggal_persetujuan,
            ]);

            // 3. Save Pelayanan
            Pelayanan::create([
                'peserta_kb_id' => $this->peserta_kb_id,
                'alokon_id' => $this->alokon_id,
                'skrining_medis_id' => $skrining->id,
                'tanggal_pelayanan' => $this->tanggal_pelayanan,
                'keterangan' => $this->keterangan,
                'tanggal_kunjungan_ulang' => empty($this->tanggal_kunjungan_ulang) ? null : $this->tanggal_kunjungan_ulang,
                'tanggal_dicabut' => empty($this->tanggal_dicabut) ? null : $this->tanggal_dicabut,
                'penanggung_jawab_nama' => $this->penanggung_jawab_nama,
                'penanggung_jawab_nip' => $this->penanggung_jawab_nip,
                'penanggung_jawab_jabatan' => $this->penanggung_jawab_jabatan,
            ]);

            // 4. Decrease stock
            $alokon->kurangiStok(1);
        });

        $this->dispatch('toast-show', slots: ['text' => 'Pencatatan pelayanan KB berhasil disimpan!'], dataset: ['variant' => 'success']);
        return $this->redirectRoute('pelayanan.index', navigate: true);
    }

    public function render()
    {
        // Only verified patients can receive services
        $pesertas = PesertaKb::terverifikasi()->orderBy('nama_lengkap')->get();
        
        // Show alokons owned by bidan's instansi
        $alokons = Alokon::where('instansi_id', auth()->user()->instansi_id)
            ->where('stok', '>', 0)
            ->get();

        return view('livewire.pelayanan.create', [
            'pesertas' => $pesertas,
            'alokons' => $alokons,
        ])->layout('layouts.app', ['title' => 'Pelayanan KB Baru (Wizard)']);
    }
}
