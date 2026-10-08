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
    public bool $tekananDarahTinggi = false;
    public ?int $sistolik = null;
    public ?int $diastolik = null;

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

    public const DAFTAR_ALOKON_BOLEH = [
        'Suntikan 1 Bulan',
        'Suntikan 3 Bulan Kombinasi',
        'Suntikan 3 Bulan Progestin',
        'Pil Kombinasi',
        'Pil Progestin',
        'Kondom',
        'Implan 1 Batang',
        'Implan 2 Batang',
        'IUD',
        'Tubektomi',
        'Vasektomi',
    ];

    public $alat_kontrasepsi_boleh_digunakan = [];

    public function selectSemuaAlokon(): void
    {
        $this->alat_kontrasepsi_boleh_digunakan = self::DAFTAR_ALOKON_BOLEH;
    }

    public function uncheckSemuaAlokon(): void
    {
        $this->alat_kontrasepsi_boleh_digunakan = [];
    }

    public function toggleSemuaAlokon(): void
    {
        if (count($this->alat_kontrasepsi_boleh_digunakan) === count(self::DAFTAR_ALOKON_BOLEH)) {
            $this->alat_kontrasepsi_boleh_digunakan = [];
        } else {
            $this->alat_kontrasepsi_boleh_digunakan = self::DAFTAR_ALOKON_BOLEH;
        }
    }

    public function toggleAlokon(string $alokon): void
    {
        if (!is_array($this->alat_kontrasepsi_boleh_digunakan)) {
            $this->alat_kontrasepsi_boleh_digunakan = [];
        }

        if (in_array($alokon, $this->alat_kontrasepsi_boleh_digunakan)) {
            $this->alat_kontrasepsi_boleh_digunakan = array_values(array_diff($this->alat_kontrasepsi_boleh_digunakan, [$alokon]));
        } else {
            $this->alat_kontrasepsi_boleh_digunakan[] = $alokon;
        }
    }

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

    // Antrian Reference (if dispatched from queue)
    public $antrian_id = null;
    public ?\App\Models\AntrianJadwal $antrian = null;

    public function mount()
    {
        $this->tanggal_skrining = now()->toDateString();
        $this->tanggal_persetujuan = now()->toDateString();
        $this->tanggal_pelayanan = now()->toDateString();

        // Default penanggung jawab otomatis dari user yang login
        if (auth()->check()) {
            $user = auth()->user();
            $this->penanggung_jawab_nama = $user->name ?? '';
            $this->penanggung_jawab_nip = $user->nip ?? '';
            $this->penanggung_jawab_jabatan = $user->isBidan() ? 'bidan' : ($user->isAdmin() ? 'bidan' : 'perawat');
        }

        // Auto-select patient from query string (e.g. from queue action or URL)
        if (request()->has('peserta_id')) {
            $this->peserta_kb_id = (int) request()->query('peserta_id');
            $this->updatedPesertaKbId($this->peserta_kb_id);
        }

        if (request()->has('nik')) {
            $peserta = PesertaKb::where('nik', request()->query('nik'))->first();
            if ($peserta) {
                $this->peserta_kb_id = $peserta->id;
                $this->updatedPesertaKbId($this->peserta_kb_id);
            }
        }

        if (request()->has('antrian_id')) {
            $this->antrian_id = (int) request()->query('antrian_id');
            $this->antrian = \App\Models\AntrianJadwal::with('jadwalPelayanan')->find($this->antrian_id);
            if ($this->antrian) {
                if ($this->antrian->status === 'hadir') {
                    session()->flash('warning', 'Peserta ini sudah selesai dilayani pada sesi jadwal ini dan tidak boleh dilayani 2x dalam jadwal yang sama.');
                    return redirect()->route('pelayanan.index');
                }
                if (in_array($this->antrian->status, ['terdaftar', 'sedang_dilayani'])) {
                    $this->antrian->update(['status' => 'sedang_dilayani']);
                }
                if (!$this->peserta_kb_id && $this->antrian->peserta_kb_id) {
                    $this->peserta_kb_id = $this->antrian->peserta_kb_id;
                    $this->updatedPesertaKbId($this->peserta_kb_id);
                }
            }
        }
    }

    public function updatedPesertaKbId($value)
    {
        $peserta = PesertaKb::with(['skriningMedis' => function ($q) {
            $q->latest('tanggal_skrining');
        }])->find($value);

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

            // Otomatis tarik data rekam medis terakhir jika peserta ulangan / pernah periksa
            $lastSkrining = $peserta->skriningMedis->first();
            if ($lastSkrining) {
                if (empty($this->gravida_partus_abortus) && $lastSkrining->gravida_partus_abortus) {
                    $this->gravida_partus_abortus = $lastSkrining->gravida_partus_abortus;
                }
                $this->posisi_rahim = $lastSkrining->posisi_rahim ?? 'normal';
                $this->rwyt_sakit_kuning = (bool) $lastSkrining->rwyt_sakit_kuning;
                $this->rwyt_pendarahan = (bool) $lastSkrining->rwyt_pendarahan;
                $this->rwyt_keputihan = (bool) $lastSkrining->rwyt_keputihan;
                $this->rwyt_tumor = (bool) $lastSkrining->rwyt_tumor;
                if (!empty($lastSkrining->alat_kontrasepsi_boleh_digunakan) && empty($this->alat_kontrasepsi_boleh_digunakan)) {
                    $savedBoleh = $lastSkrining->alat_kontrasepsi_boleh_digunakan;
                    if (is_string($savedBoleh)) {
                        $savedBoleh = json_decode($savedBoleh, true) ?? [];
                    }
                    $this->alat_kontrasepsi_boleh_digunakan = is_array($savedBoleh) ? $savedBoleh : [];
                }
            }
        }
    }

    /**
     * Check if the participant is medically fit to proceed
     */
    public function checkKelayakanMedis()
    {
        $warnings = [];

        // 1. Cek Tekanan Darah (Hipertensi: Sistolik >= 140 atau Diastolik >= 90)
        $this->tekananDarahTinggi = false;
        $this->sistolik = null;
        $this->diastolik = null;

        if (!empty($this->fisik_tekanan_darah)) {
            if (preg_match('/^(\d{2,3})\s*[\/\-]\s*(\d{2,3})$/', trim($this->fisik_tekanan_darah), $matches)) {
                $this->sistolik = (int) $matches[1];
                $this->diastolik = (int) $matches[2];

                // Standar Medis BKKBN / Kemenkes / WHO:
                if ($this->sistolik >= 140 || $this->diastolik >= 90) {
                    $this->tekananDarahTinggi = true;
                    $warnings[] = "Tekanan darah pasien terlalu tinggi ({$this->sistolik}/{$this->diastolik} mmHg - Hipertensi). Pelayanan KB tidak dapat dilanjutkan.";
                    $this->addError('fisik_tekanan_darah', "Tekanan darah terlalu tinggi ({$this->sistolik}/{$this->diastolik} mmHg). Pasien tidak dapat melanjutkan tindakan pelayanan KB.");
                } else {
                    $errors = $this->getErrorBag();
                    if ($errors->has('fisik_tekanan_darah')) {
                        $msg = $errors->first('fisik_tekanan_darah');
                        if (str_contains($msg, 'terlalu tinggi')) {
                            $this->resetErrorBag('fisik_tekanan_darah');
                        }
                    }
                }
            }
        }

        // 2. Kontraindikasi Medis Lainnya
        if ($this->hamil_diduga_hamil) {
            $warnings[] = 'Pasien terindikasi hamil atau diduga hamil.';
        }
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
            $warnings[] = 'Kondisi fisik keadaan umum pasien lemah / sakit.';
        }

        if (count($warnings) > 0) {
            $this->isLayak = false;
            $this->medicalWarningMessage = implode(' ', $warnings);
        } else {
            $this->isLayak = true;
            $this->medicalWarningMessage = '';
        }
    }

    public function updated($propertyName)
    {
        // Re-evaluate eligibility if any relevant field changes in Step 1
        if (in_array($propertyName, ['rwyt_tumor', 'rwyt_pendarahan', 'rwyt_sakit_kuning', 'fisik_keadaan_umum', 'fisik_tekanan_darah', 'hamil_diduga_hamil'])) {
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
                'gravida_partus_abortus' => ['required', 'string', 'max:20'],
                'status_menyusui' => ['required', 'boolean'],
                'fisik_keadaan_umum' => ['required', 'string', 'in:baik,sedang,kurang,lemah'],
                'fisik_berat_badan' => ['required', 'numeric', 'min:20', 'max:250'],
                'fisik_tekanan_darah' => ['required', 'string', 'regex:/^\d{2,3}\s*[\/\-]\s*\d{2,3}$/'],
                'pemeriksaan_dalam_radang' => ['required', 'boolean'],
                'pemeriksaan_dalam_tumor' => ['required', 'boolean'],
                'posisi_rahim' => ['required', 'string', 'in:retroflexi,antaflexi,normal'],
                'pemeriksaan_tambahan_diabetes' => ['required', 'boolean'],
                'pemeriksaan_tambahan_pembekuan_darah' => ['required', 'boolean'],
                'pemeriksaan_tambahan_orchitis' => ['required', 'boolean'],
                'pemeriksaan_tambahan_tumor' => ['required', 'boolean'],
                'alat_kontrasepsi_boleh_digunakan' => ['required', 'array', 'min:1'],
            ], [
                'peserta_kb_id.required' => 'Pilih peserta KB terlebih dahulu.',
                'tanggal_skrining.required' => 'Tanggal skrining wajib diisi.',
                'pendidikan_istri.required' => 'Pendidikan terakhir istri wajib dipilih.',
                'pendidikan_suami.required' => 'Pendidikan terakhir suami wajib dipilih.',
                'pekerjaan_istri.required' => 'Pekerjaan istri wajib dipilih.',
                'pekerjaan_suami.required' => 'Pekerjaan suami wajib dipilih.',
                'jumlah_anak_laki.required' => 'Jumlah anak laki-laki wajib diisi.',
                'jumlah_anak_perempuan.required' => 'Jumlah anak perempuan wajib diisi.',
                'status_kepesertaan.required' => 'Status kepesertaan KB wajib dipilih.',
                'gravida_partus_abortus.required' => 'Data GPA (Gravida/Partus/Abortus) wajib diisi (contoh: G2P1A0).',
                'fisik_keadaan_umum.required' => 'Keadaan umum fisik pasien wajib dipilih.',
                'fisik_berat_badan.required' => 'Berat badan pasien wajib diisi dalam satuan kg.',
                'fisik_berat_badan.numeric' => 'Berat badan harus berupa angka.',
                'fisik_tekanan_darah.required' => 'Tekanan darah pasien wajib diisi (contoh: 120/80).',
                'fisik_tekanan_darah.regex' => 'Format tekanan darah tidak valid. Gunakan format sistolik/diastolik (contoh: 120/80).',
                'posisi_rahim.required' => 'Posisi rahim wajib dipilih.',
                'alat_kontrasepsi_boleh_digunakan.required' => 'Centang minimal satu alat kontrasepsi yang boleh dipergunakan oleh pasien.',
                'alat_kontrasepsi_boleh_digunakan.min' => 'Centang minimal satu alat kontrasepsi yang boleh dipergunakan oleh pasien.',
            ], [
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
                'gravida_partus_abortus' => 'GPA (Gravida/Partus/Abortus)',
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
                'alat_kontrasepsi_boleh_digunakan' => 'Alat Kontrasepsi yang Boleh Dipergunakan',
            ]);

            $this->checkKelayakanMedis();

            if (!$this->isLayak) {
                if ($this->tekananDarahTinggi) {
                    $this->addError('fisik_tekanan_darah', "Tekanan darah terlalu tinggi ({$this->sistolik}/{$this->diastolik} mmHg). Pasien tidak dapat melanjutkan tindakan pelayanan KB.");
                }
                $this->dispatch('toast-show', slots: ['text' => 'Pasien tidak lolos skrining medis. Tindakan tidak dapat dilanjutkan.'], dataset: ['variant' => 'danger']);
                return;
            }

            $this->currentStep = 2;
        } elseif ($this->currentStep === 2) {
            $this->validate([
                'persetujuan_klien' => ['accepted'],
                'persetujuan_pasangan' => ['accepted'],
                'jenis_tindakan_medis' => ['required', 'string', 'in:pemasangan,pencabutan,penggantian,penyuntikan'],
                'tanggal_persetujuan' => ['required', 'date'],
            ], [
                'persetujuan_klien.accepted' => 'Persetujuan klien wajib dicentang untuk dapat melanjutkan tindakan medis.',
                'persetujuan_pasangan.accepted' => 'Persetujuan suami / pasangan wajib dicentang untuk dapat melanjutkan tindakan medis.',
                'jenis_tindakan_medis.required' => 'Jenis tindakan medis wajib dipilih.',
                'tanggal_persetujuan.required' => 'Tanggal persetujuan tindakan wajib diisi.',
            ], [
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
            'tanggal_kunjungan_ulang' => ['required', 'date', 'after_or_equal:tanggal_pelayanan'],
            'tanggal_dicabut' => ['nullable', 'date'],
            'penanggung_jawab_nama' => ['required', 'string', 'max:255'],
            'penanggung_jawab_nip' => ['nullable', 'string', 'max:50'],
            'penanggung_jawab_jabatan' => ['required', 'string', 'in:dokter,bidan,perawat'],
        ], [
            'alokon_id.required' => 'Alat atau obat kontrasepsi (Alokon) wajib dipilih.',
            'tanggal_pelayanan.required' => 'Tanggal pelayanan wajib diisi.',
            'tanggal_kunjungan_ulang.required' => 'Tanggal kontrol / kunjungan ulang wajib diisi.',
            'tanggal_kunjungan_ulang.after_or_equal' => 'Tanggal kunjungan ulang tidak boleh mendahului tanggal pelayanan.',
            'penanggung_jawab_nama.required' => 'Nama petugas penanggung jawab wajib diisi.',
            'penanggung_jawab_jabatan.required' => 'Jabatan petugas penanggung jawab wajib dipilih.',
        ], [
            'alokon_id' => 'Alokon',
            'tanggal_pelayanan' => 'Tanggal Pelayanan',
            'tanggal_kunjungan_ulang' => 'Tanggal Kunjungan Ulang',
            'tanggal_dicabut' => 'Tanggal Dicabut',
            'penanggung_jawab_nama' => 'Nama Petugas',
            'penanggung_jawab_nip' => 'NIP Petugas',
            'penanggung_jawab_jabatan' => 'Jabatan Petugas',
        ]);

        $alokon = Alokon::find($this->alokon_id);
        if (!$alokon->isStokTersedia(1)) {
            $this->dispatch('toast-show', slots: ['text' => "Stok {$alokon->nama_alokon} tidak mencukupi!"], dataset: ['variant' => 'danger']);
            return;
        }

        if ($this->antrian_id) {
            $antrianCheck = \App\Models\AntrianJadwal::find($this->antrian_id);
            if ($antrianCheck && $antrianCheck->status === 'hadir') {
                $this->dispatch('toast-show', slots: ['text' => 'Nomor antrian ini sudah selesai dilayani dan tidak boleh dilayani 2x.'], dataset: ['variant' => 'danger']);
                return;
            }
        }

        $sudahAdaPelayanan = Pelayanan::where('peserta_kb_id', $this->peserta_kb_id)
            ->whereDate('tanggal_pelayanan', $this->tanggal_pelayanan)
            ->exists();

        if ($sudahAdaPelayanan) {
            $this->dispatch('toast-show', slots: ['text' => 'Peserta ini sudah tercatat mendapatkan pelayanan KB pada tanggal ini dan tidak boleh dilayani 2x dalam jadwal yang sama.'], dataset: ['variant' => 'danger']);
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
                'alat_kontrasepsi_boleh_digunakan' => is_array($this->alat_kontrasepsi_boleh_digunakan) ? $this->alat_kontrasepsi_boleh_digunakan : [],
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

            // 5. Mark queue status as hadir if associated, or record walk-in queue for today
            if ($this->antrian_id) {
                \App\Models\AntrianJadwal::where('id', $this->antrian_id)->update(['status' => 'hadir']);
            } else {
                $todayJadwal = \App\Models\JadwalPelayanan::where('instansi_id', auth()->user()->instansi_id)
                    ->whereDate('tanggal', $this->tanggal_pelayanan)
                    ->where('is_aktif', true)
                    ->first();

                if ($todayJadwal) {
                    $existing = \App\Models\AntrianJadwal::where('jadwal_pelayanan_id', $todayJadwal->id)
                        ->where('peserta_kb_id', $this->peserta_kb_id)
                        ->first();

                    if ($existing) {
                        $existing->update(['status' => 'hadir']);
                    } else {
                        $lastNomor = \App\Models\AntrianJadwal::where('jadwal_pelayanan_id', $todayJadwal->id)
                            ->lockForUpdate()
                            ->max('nomor_antrian') ?? 0;
                        $nomor = $lastNomor + 1;
                        \App\Models\AntrianJadwal::create([
                            'jadwal_pelayanan_id' => $todayJadwal->id,
                            'peserta_kb_id' => $this->peserta_kb_id,
                            'nomor_antrian' => $nomor,
                            'jenis_pendaftaran' => 'walkin',
                            'kode_antrian' => 'W-' . str_pad($nomor, 3, '0', STR_PAD_LEFT),
                            'status' => 'hadir',
                        ]);
                    }
                }
            }
        });

        $this->dispatch('toast-show', slots: ['text' => 'Pencatatan pelayanan KB berhasil disimpan dan antrian diperbarui!'], dataset: ['variant' => 'success']);
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
