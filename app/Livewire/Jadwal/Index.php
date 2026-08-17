<?php

namespace App\Livewire\Jadwal;

use App\Models\AntrianJadwal;
use App\Models\JadwalPelayanan;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public int $year;
    public int $month;

    // Form tambah jadwal
    public bool $showFormModal = false;
    public string $formTanggal = '';
    public string $formWaktuMulai = '08:00';
    public string $formWaktuSelesai = '12:00';
    public string $formKeterangan = '';
    public int $formKuota = 20;

    // Detail jadwal per tanggal
    public bool $showDetailModal = false;
    public string $detailTanggal = '';
    public array $detailJadwals = [];

    // Konfirmasi hapus
    public bool $showDeleteModal = false;
    public ?int $deletingId = null;
    public string $deletingInfo = '';

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->subMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->year, $this->month, 1)->addMonth();
        $this->year = $date->year;
        $this->month = $date->month;
    }

    public function goToToday()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    /**
     * Klik tanggal pada kalender
     */
    public function clickDate(string $date)
    {
        $jadwals = JadwalPelayanan::where('instansi_id', auth()->user()->instansi_id)
            ->where('tanggal', $date)
            ->withCount('antrians')
            ->orderBy('waktu_mulai')
            ->get();

        if ($jadwals->isEmpty()) {
            // Buka form tambah jadwal
            $this->formTanggal = $date;
            $this->formWaktuMulai = '08:00';
            $this->formWaktuSelesai = '12:00';
            $this->formKeterangan = '';
            $this->formKuota = 20;
            $this->showFormModal = true;
        } else {
            // Buka detail jadwal
            $this->detailTanggal = $date;
            $this->detailJadwals = $jadwals->map(function ($j) {
                return [
                    'id' => $j->id,
                    'waktu_mulai' => $j->waktu_mulai,
                    'waktu_selesai' => $j->waktu_selesai,
                    'keterangan' => $j->keterangan,
                    'kuota' => $j->kuota,
                    'terisi' => $j->antrians_count,
                    'is_aktif' => $j->is_aktif,
                ];
            })->toArray();
            $this->showDetailModal = true;
        }
    }

    /**
     * Tambah jadwal baru dari form modal
     */
    public function simpanJadwal()
    {
        $this->validate([
            'formTanggal' => ['required', 'date'],
            'formWaktuMulai' => ['required', 'date_format:H:i'],
            'formWaktuSelesai' => ['required', 'date_format:H:i', 'after:formWaktuMulai'],
            'formKeterangan' => ['nullable', 'string', 'max:255'],
            'formKuota' => ['required', 'integer', 'min:1', 'max:100'],
        ], [
            'formWaktuSelesai.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        JadwalPelayanan::create([
            'instansi_id' => auth()->user()->instansi_id,
            'tanggal' => $this->formTanggal,
            'waktu_mulai' => $this->formWaktuMulai,
            'waktu_selesai' => $this->formWaktuSelesai,
            'keterangan' => $this->formKeterangan ?: null,
            'kuota' => $this->formKuota,
            'is_aktif' => true,
            'created_by' => auth()->id(),
        ]);

        $this->showFormModal = false;
        $this->dispatch('toast-show', slots: ['text' => 'Jadwal berhasil ditambahkan!'], dataset: ['variant' => 'success']);
    }

    /**
     * Tambah jadwal baru dari detail modal (tanggal sudah ada jadwal lain)
     */
    public function tambahJadwalDariDetail()
    {
        $this->formTanggal = $this->detailTanggal;
        $this->formWaktuMulai = '08:00';
        $this->formWaktuSelesai = '12:00';
        $this->formKeterangan = '';
        $this->formKuota = 20;
        $this->showDetailModal = false;
        $this->showFormModal = true;
    }

    /**
     * Toggle aktif/nonaktif jadwal
     */
    public function toggleAktif(int $id)
    {
        $jadwal = JadwalPelayanan::where('instansi_id', auth()->user()->instansi_id)->findOrFail($id);
        $jadwal->update(['is_aktif' => !$jadwal->is_aktif]);

        // Refresh detail modal
        $this->clickDate($this->detailTanggal);
        
        $status = $jadwal->fresh()->is_aktif ? 'diaktifkan' : 'dinonaktifkan';
        $this->dispatch('toast-show', slots: ['text' => "Jadwal berhasil {$status}!"], dataset: ['variant' => 'success']);
    }

    /**
     * Mulai hapus jadwal (konfirmasi)
     */
    public function startDelete(int $id)
    {
        $jadwal = JadwalPelayanan::where('instansi_id', auth()->user()->instansi_id)->findOrFail($id);
        $this->deletingId = $id;
        $this->deletingInfo = Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') . ' (' . $jadwal->waktu_mulai . ' - ' . $jadwal->waktu_selesai . ')';
        $this->showDetailModal = false;
        $this->showDeleteModal = true;
    }

    /**
     * Konfirmasi hapus jadwal
     */
    public function hapusJadwal()
    {
        if ($this->deletingId) {
            JadwalPelayanan::where('instansi_id', auth()->user()->instansi_id)
                ->where('id', $this->deletingId)
                ->delete();
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('toast-show', slots: ['text' => 'Jadwal berhasil dihapus!'], dataset: ['variant' => 'success']);
    }

    public function render()
    {
        $startOfMonth = Carbon::create($this->year, $this->month, 1);
        $endOfMonth = $startOfMonth->copy()->endOfMonth();
        
        $monthName = $startOfMonth->translatedFormat('F Y');

        // Get all jadwals for this month
        $jadwals = JadwalPelayanan::where('instansi_id', auth()->user()->instansi_id)
            ->whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->withCount('antrians')
            ->get()
            ->groupBy(fn ($j) => $j->tanggal->toDateString());

        // Build calendar grid data
        $calendarDays = [];
        
        // Day of week for first day (0=Mon ... 6=Sun in ISO)
        $firstDayOfWeek = $startOfMonth->dayOfWeekIso - 1; // 0-indexed, Monday=0
        
        // Fill empty days before month start
        for ($i = 0; $i < $firstDayOfWeek; $i++) {
            $calendarDays[] = null;
        }
        
        // Fill month days
        for ($day = 1; $day <= $endOfMonth->day; $day++) {
            $date = Carbon::create($this->year, $this->month, $day)->toDateString();
            $dayJadwals = $jadwals->get($date, collect());
            
            $calendarDays[] = [
                'date' => $date,
                'day' => $day,
                'isToday' => $date === now()->toDateString(),
                'isPast' => Carbon::parse($date)->lt(now()->startOfDay()),
                'jadwalCount' => $dayJadwals->count(),
                'hasAktif' => $dayJadwals->where('is_aktif', true)->count() > 0,
                'hasNonAktif' => $dayJadwals->where('is_aktif', false)->count() > 0,
                'totalAntrian' => $dayJadwals->sum('antrians_count'),
            ];
        }

        // Stats
        $totalJadwalBulanIni = JadwalPelayanan::where('instansi_id', auth()->user()->instansi_id)
            ->whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()])
            ->count();

        $jadwalAktifMendatang = JadwalPelayanan::where('instansi_id', auth()->user()->instansi_id)
            ->aktif()->mendatang()->count();

        $totalAntrianBulanIni = AntrianJadwal::whereHas('jadwalPelayanan', function ($q) use ($startOfMonth, $endOfMonth) {
            $q->where('instansi_id', auth()->user()->instansi_id)
              ->whereBetween('tanggal', [$startOfMonth->toDateString(), $endOfMonth->toDateString()]);
        })->count();

        return view('livewire.jadwal.index', [
            'monthName' => $monthName,
            'calendarDays' => $calendarDays,
            'totalJadwalBulanIni' => $totalJadwalBulanIni,
            'jadwalAktifMendatang' => $jadwalAktifMendatang,
            'totalAntrianBulanIni' => $totalAntrianBulanIni,
        ])->layout('layouts.app', ['title' => 'Jadwal Pelayanan KB']);
    }
}
