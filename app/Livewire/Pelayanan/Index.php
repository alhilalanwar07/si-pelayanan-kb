<?php

namespace App\Livewire\Pelayanan;

use App\Models\Alokon;
use App\Models\AntrianJadwal;
use App\Models\JadwalPelayanan;
use App\Models\Pelayanan;
use App\Models\PesertaKb;
use App\Models\Wilayah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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

    // Modal Walk-in State
    public bool $showWalkinModal = false;
    public string $walkinType = 'terdaftar'; // 'terdaftar' | 'baru'
    public ?int $walkinPesertaId = null;
    public ?int $walkinJadwalId = null;

    // Form Pasien Baru Walk-in
    public string $walkinNik = '';
    public string $walkinNamaLengkap = '';
    public string $walkinNomorHp = '';
    public ?int $walkinWilayahId = null;
    public string $walkinAlamatLengkap = '';
    public string $walkinPenggunaanAsuransi = 'umum';
    public string $walkinNamaSuamiIstri = '';
    public string $walkinTanggalLahirIstri = '';

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

    public function panggilPeserta(int $antrianId)
    {
        $antrian = AntrianJadwal::findOrFail($antrianId);

        // Reset antrian lain yang sedang dilayani pada jadwal ini jika ada
        AntrianJadwal::where('jadwal_pelayanan_id', $antrian->jadwal_pelayanan_id)
            ->where('status', 'sedang_dilayani')
            ->where('id', '!=', $antrianId)
            ->update(['status' => 'terdaftar']);

        $antrian->update(['status' => 'sedang_dilayani']);

        $this->dispatch('toast-show', slots: ['text' => "Nomor antrian #{$antrian->nomor_antrian} sekarang berstatus sedang dilayani."], dataset: ['variant' => 'success']);
    }

    public function batalPanggil(int $antrianId)
    {
        $antrian = AntrianJadwal::findOrFail($antrianId);
        $antrian->update(['status' => 'terdaftar']);

        $this->dispatch('toast-show', slots: ['text' => "Nomor antrian #{$antrian->nomor_antrian} dikembalikan ke antrian menunggu."], dataset: ['variant' => 'info']);
    }

    public function openWalkinModal()
    {
        $this->walkinJadwalId = $this->selectedJadwalId;
        $this->walkinType = 'terdaftar';
        $this->walkinPesertaId = null;
        $this->resetWalkinForm();
        $this->showWalkinModal = true;
    }

    public function closeWalkinModal()
    {
        $this->showWalkinModal = false;
        $this->resetWalkinForm();
    }

    public function resetWalkinForm()
    {
        $this->walkinNik = '';
        $this->walkinNamaLengkap = '';
        $this->walkinNomorHp = '';
        $this->walkinWilayahId = null;
        $this->walkinAlamatLengkap = '';
        $this->walkinPenggunaanAsuransi = 'umum';
        $this->walkinNamaSuamiIstri = '';
        $this->walkinTanggalLahirIstri = '';
        $this->resetErrorBag();
    }

    public function updatedWalkinJadwalId()
    {
        $this->walkinPesertaId = null;
        $this->resetErrorBag('walkinPesertaId');
    }

    public function submitWalkin(bool $langsungLayani = false)
    {
        if ($langsungLayani && !auth()->user()->isBidan()) {
            $langsungLayani = false;
        }

        if (!$this->walkinJadwalId) {
            $this->addError('walkinJadwalId', 'Pilih sesi jadwal pelayanan.');
            return;
        }

        $jadwal = JadwalPelayanan::findOrFail($this->walkinJadwalId);

        $peserta = null;
        if ($this->walkinType === 'terdaftar') {
            $this->validate([
                'walkinPesertaId' => 'required|exists:peserta_kbs,id',
            ], [
                'walkinPesertaId.required' => 'Silakan pilih peserta yang sudah terdaftar.',
            ]);

            $peserta = PesertaKb::findOrFail($this->walkinPesertaId);

            // Cek apakah sudah terdaftar di jadwal ini
            $existing = AntrianJadwal::where('jadwal_pelayanan_id', $jadwal->id)
                ->where('peserta_kb_id', $peserta->id)
                ->first();

            // Cek apakah peserta sudah selesai dilayani pada sesi jadwal ini
            $sudahDilayani = ($existing && $existing->status === 'hadir')
                || Pelayanan::where('peserta_kb_id', $peserta->id)
                    ->whereDate('tanggal_pelayanan', $jadwal->tanggal)
                    ->exists();

            if ($sudahDilayani) {
                $this->addError('walkinPesertaId', "Peserta {$peserta->nama_lengkap} sudah selesai dilayani pada sesi jadwal ini dan tidak boleh dilayani 2x dalam jadwal yang sama.");
                return;
            }

            if ($existing) {
                if ($langsungLayani) {
                    $this->closeWalkinModal();
                    return $this->layaniPeserta($peserta->id, $existing->id);
                }
                $this->addError('walkinPesertaId', "Peserta ini sudah memiliki nomor antrian ({$existing->kode_display}) pada sesi jadwal ini.");
                return;
            }
        } else {
            // Form Peserta Baru Walk-in
            $this->validate([
                'walkinNik' => ['required', 'string', 'size:16', 'unique:peserta_kbs,nik'],
                'walkinNamaLengkap' => ['required', 'string', 'max:255'],
                'walkinNomorHp' => ['required', 'string', 'min:10', 'max:15'],
                'walkinWilayahId' => ['required', 'exists:wilayahs,id'],
                'walkinAlamatLengkap' => ['required', 'string'],
                'walkinPenggunaanAsuransi' => ['required', 'string', 'in:bpjs,kis,umum,lainnya'],
                'walkinTanggalLahirIstri' => ['nullable', 'date', 'before:today'],
            ], [], [
                'walkinNik' => 'NIK',
                'walkinNamaLengkap' => 'Nama Lengkap',
                'walkinNomorHp' => 'Nomor HP/WA',
                'walkinWilayahId' => 'Desa/Kelurahan',
                'walkinAlamatLengkap' => 'Alamat Lengkap',
                'walkinPenggunaanAsuransi' => 'Penggunaan Asuransi',
                'walkinTanggalLahirIstri' => 'Tanggal Lahir',
            ]);

            // Cek jika NIK sudah ada dan sudah dilayani di jadwal ini
            $pesertaExisting = PesertaKb::where('nik', $this->walkinNik)->first();
            if ($pesertaExisting) {
                $existingAntrian = AntrianJadwal::where('jadwal_pelayanan_id', $jadwal->id)
                    ->where('peserta_kb_id', $pesertaExisting->id)
                    ->first();

                $sudahDilayani = ($existingAntrian && $existingAntrian->status === 'hadir')
                    || Pelayanan::where('peserta_kb_id', $pesertaExisting->id)
                        ->whereDate('tanggal_pelayanan', $jadwal->tanggal)
                        ->exists();

                if ($sudahDilayani) {
                    $this->addError('walkinNik', "Pasien dengan NIK ini sudah selesai dilayani pada sesi jadwal ini dan tidak boleh dilayani 2x dalam jadwal yang sama.");
                    return;
                }
            }

            $peserta = PesertaKb::create([
                'user_id' => null,
                'wilayah_id' => $this->walkinWilayahId,
                'nik' => $this->walkinNik,
                'nomor_hp' => $this->walkinNomorHp,
                'nama_lengkap' => $this->walkinNamaLengkap,
                'nama_suami_istri' => $this->walkinNamaSuamiIstri ?: '-',
                'tanggal_lahir_istri' => $this->walkinTanggalLahirIstri ?: '1995-01-01',
                'alamat_lengkap' => $this->walkinAlamatLengkap,
                'penggunaan_asuransi' => $this->walkinPenggunaanAsuransi,
                'jumlah_anak_hidup' => 0,
                'status' => 'terverifikasi',
            ]);
        }

        // Generate antrian dengan DB Transaction & Lock
        $antrian = DB::transaction(function () use ($jadwal, $peserta, $langsungLayani) {
            $lastNomor = AntrianJadwal::where('jadwal_pelayanan_id', $jadwal->id)
                ->lockForUpdate()
                ->max('nomor_antrian') ?? 0;

            $nomorAntrian = $lastNomor + 1;
            $kodeAntrian = 'W-' . str_pad($nomorAntrian, 3, '0', STR_PAD_LEFT);
            $status = $langsungLayani ? 'sedang_dilayani' : 'terdaftar';

            if ($langsungLayani) {
                AntrianJadwal::where('jadwal_pelayanan_id', $jadwal->id)
                    ->where('status', 'sedang_dilayani')
                    ->update(['status' => 'terdaftar']);
            }

            return AntrianJadwal::create([
                'jadwal_pelayanan_id' => $jadwal->id,
                'peserta_kb_id' => $peserta->id,
                'nomor_antrian' => $nomorAntrian,
                'jenis_pendaftaran' => 'walkin',
                'kode_antrian' => $kodeAntrian,
                'status' => $status,
            ]);
        });

        $this->closeWalkinModal();

        if ($langsungLayani) {
            return $this->redirectRoute('pelayanan.create', [
                'peserta_id' => $peserta->id,
                'antrian_id' => $antrian->id,
            ], navigate: true);
        }

        $this->dispatch('toast-show', slots: ['text' => "Antrian Walk-in berhasil dibuat: {$antrian->kode_display} ({$peserta->nama_lengkap}). Pasien menunggu panggilan."], dataset: ['variant' => 'success']);
    }

    public function layaniPeserta(int $pesertaId, int $antrianId)
    {
        if (!auth()->user()->isBidan()) {
            $this->dispatch('toast-show', slots: ['text' => 'Hanya bidan yang berwenang mencatat pelayanan medis.'], dataset: ['variant' => 'danger']);
            return;
        }

        $antrian = AntrianJadwal::with('jadwalPelayanan')->find($antrianId);
        if (!$antrian) {
            $this->dispatch('toast-show', slots: ['text' => 'Data antrian tidak ditemukan.'], dataset: ['variant' => 'danger']);
            return;
        }

        if ($antrian->status === 'hadir') {
            $this->dispatch('toast-show', slots: ['text' => 'Peserta ini sudah selesai dilayani pada sesi jadwal ini dan tidak boleh dilayani 2x dalam jadwal yang sama.'], dataset: ['variant' => 'warning']);
            return;
        }

        if ($antrian->jadwalPelayanan) {
            $sudahAdaPelayanan = Pelayanan::where('peserta_kb_id', $pesertaId)
                ->whereDate('tanggal_pelayanan', $antrian->jadwalPelayanan->tanggal)
                ->exists();

            if ($sudahAdaPelayanan) {
                $antrian->update(['status' => 'hadir']);
                $this->dispatch('toast-show', slots: ['text' => 'Peserta ini sudah memiliki catatan pelayanan medis pada jadwal ini dan tidak boleh dilayani 2x.'], dataset: ['variant' => 'warning']);
                return;
            }
        }

        if (in_array($antrian->status, ['terdaftar', 'sedang_dilayani'])) {
            AntrianJadwal::where('jadwal_pelayanan_id', $antrian->jadwal_pelayanan_id)
                ->where('status', 'sedang_dilayani')
                ->where('id', '!=', $antrianId)
                ->update(['status' => 'terdaftar']);

            $antrian->update(['status' => 'sedang_dilayani']);
        }

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
            $antrianMenunggu = $currentJadwal->antrians()->whereIn('status', ['terdaftar', 'sedang_dilayani'])->count();
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
        $availablePesertas = PesertaKb::terverifikasi()->with('wilayah')->orderBy('nama_lengkap')->get();
        $wilayahs = Wilayah::orderBy('nama_desa_kelurahan')->get();

        // Peserta yang sudah dilayani / sudah antri pada jadwal walkin yang dipilih
        $servedPesertaIds = [];
        $queuedPesertaIds = [];
        $targetWalkinJadwalId = $this->walkinJadwalId ?: $this->selectedJadwalId;
        if ($targetWalkinJadwalId) {
            $walkinJadwalTarget = JadwalPelayanan::find($targetWalkinJadwalId);
            if ($walkinJadwalTarget) {
                $hadirIds = AntrianJadwal::where('jadwal_pelayanan_id', $walkinJadwalTarget->id)
                    ->where('status', 'hadir')
                    ->pluck('peserta_kb_id')
                    ->toArray();

                $pelayananIds = Pelayanan::whereDate('tanggal_pelayanan', $walkinJadwalTarget->tanggal)
                    ->pluck('peserta_kb_id')
                    ->toArray();

                $servedPesertaIds = array_unique(array_merge($hadirIds, $pelayananIds));

                $queuedPesertaIds = AntrianJadwal::where('jadwal_pelayanan_id', $walkinJadwalTarget->id)
                    ->whereIn('status', ['terdaftar', 'sedang_dilayani'])
                    ->pluck('peserta_kb_id')
                    ->toArray();
            }
        }

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
            'availablePesertas' => $availablePesertas,
            'wilayahs' => $wilayahs,
            'servedPesertaIds' => $servedPesertaIds,
            'queuedPesertaIds' => $queuedPesertaIds,
        ])->layout('layouts.app', ['title' => 'Pusat Pelayanan & Antrian KB']);
    }
}
