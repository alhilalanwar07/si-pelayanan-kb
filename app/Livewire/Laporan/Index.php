<?php

namespace App\Livewire\Laporan;

use App\Models\Alokon;
use App\Models\Pelayanan;
use App\Models\PesertaKb;
use App\Models\Wilayah;
use Carbon\Carbon;
use Livewire\Component;

class Index extends Component
{
    public $activeTab = 'pelayanan'; // pelayanan, peserta, alokon

    // Filters
    public $dariTanggal = '';
    public $sampaiTanggal = '';
    public $wilayahId = '';
    public $alokonId = '';

    protected $queryString = [
        'activeTab' => ['except' => 'pelayanan'],
        'dariTanggal' => ['except' => ''],
        'sampaiTanggal' => ['except' => ''],
        'wilayahId' => ['except' => ''],
        'alokonId' => ['except' => ''],
    ];

    public function mount()
    {
        // Default filter range is current month
        $this->dariTanggal = Carbon::now()->startOfMonth()->toDateString();
        $this->sampaiTanggal = Carbon::now()->endOfMonth()->toDateString();
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function setFilterPreset($preset)
    {
        switch ($preset) {
            case 'bulan_ini':
                $this->dariTanggal = Carbon::now()->startOfMonth()->toDateString();
                $this->sampaiTanggal = Carbon::now()->endOfMonth()->toDateString();
                break;
            case 'bulan_lalu':
                $this->dariTanggal = Carbon::now()->subMonth()->startOfMonth()->toDateString();
                $this->sampaiTanggal = Carbon::now()->subMonth()->endOfMonth()->toDateString();
                break;
            case 'tahun_ini':
                $this->dariTanggal = Carbon::now()->startOfYear()->toDateString();
                $this->sampaiTanggal = Carbon::now()->endOfYear()->toDateString();
                break;
            case 'semua':
                $this->dariTanggal = '2020-01-01';
                $this->sampaiTanggal = Carbon::now()->addYear()->endOfYear()->toDateString();
                break;
        }
    }

    public function resetFilters()
    {
        $this->dariTanggal = Carbon::now()->startOfMonth()->toDateString();
        $this->sampaiTanggal = Carbon::now()->endOfMonth()->toDateString();
        $this->wilayahId = '';
        $this->alokonId = '';
    }

    public function render()
    {
        $wilayahs = Wilayah::orderBy('nama_desa_kelurahan')->get();
        $alokons = Alokon::orderBy('nama_alokon')->get();

        // 1. Pelayanan Query
        $pelayananQuery = Pelayanan::with([
            'pesertaKb.wilayah', 
            'alokon.instansi', 
            'skriningMedis.informedConsent'
        ]);

        if (!empty($this->dariTanggal) && !empty($this->sampaiTanggal)) {
            $pelayananQuery->whereBetween('tanggal_pelayanan', [$this->dariTanggal, $this->sampaiTanggal]);
        }

        if (!empty($this->wilayahId)) {
            $pelayananQuery->whereHas('pesertaKb', function ($q) {
                $q->where('wilayah_id', $this->wilayahId);
            });
        }

        if (!empty($this->alokonId)) {
            $pelayananQuery->where('alokon_id', $this->alokonId);
        }

        $pelayanans = $pelayananQuery->latest('tanggal_pelayanan')->get();

        // 2. Peserta Query
        $pesertaQuery = PesertaKb::with(['wilayah', 'pelayanans.alokon', 'skriningMedis']);

        if (!empty($this->dariTanggal) && !empty($this->sampaiTanggal)) {
            $pesertaQuery->whereBetween('created_at', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay()
            ]);
        }

        if (!empty($this->wilayahId)) {
            $pesertaQuery->where('wilayah_id', $this->wilayahId);
        }

        $pesertas = $pesertaQuery->latest()->get();

        // 3. Alokon Query
        $alokonQuery = Alokon::with('instansi')->withCount('pelayanans');
        if (!empty($this->alokonId)) {
            $alokonQuery->where('id', $this->alokonId);
        }
        $inventory = $alokonQuery->orderBy('nama_alokon')->get();

        // Summaries
        $totalPelayanan = $pelayanans->count();
        $totalPesertaDilayani = $pelayanans->pluck('peserta_kb_id')->unique()->count();
        $totalAlokonTerdistribusi = $pelayanans->count();
        $totalWilayahTercakup = $pelayanans->map(fn($p) => $p->pesertaKb?->wilayah_id)->filter()->unique()->count();
        
        $totalPesertaTerdaftar = $pesertas->count();
        $totalPesertaTerverifikasi = $pesertas->where('status', 'terverifikasi')->count();
        $totalStokTersedia = $inventory->sum('stok');

        return view('livewire.laporan.index', [
            'pelayanans' => $pelayanans,
            'pesertas' => $pesertas,
            'inventory' => $inventory,
            'wilayahs' => $wilayahs,
            'alokons' => $alokons,
            'totalPelayanan' => $totalPelayanan,
            'totalPesertaDilayani' => $totalPesertaDilayani,
            'totalAlokonTerdistribusi' => $totalAlokonTerdistribusi,
            'totalWilayahTercakup' => $totalWilayahTercakup,
            'totalPesertaTerdaftar' => $totalPesertaTerdaftar,
            'totalPesertaTerverifikasi' => $totalPesertaTerverifikasi,
            'totalStokTersedia' => $totalStokTersedia,
        ])->layout('layouts.app', ['title' => 'Laporan Rekapitulasi']);
    }

    /**
     * Download PDF action for Cetak Laporan
     */
    public function downloadPdf()
    {
        $wilayahs = Wilayah::orderBy('nama_desa_kelurahan')->get();
        $alokons = Alokon::orderBy('nama_alokon')->get();

        $wilayahSelected = !empty($this->wilayahId) ? $wilayahs->firstWhere('id', $this->wilayahId) : null;
        $alokonSelected = !empty($this->alokonId) ? $alokons->firstWhere('id', $this->alokonId) : null;

        // 1. Pelayanan Query
        $pelayananQuery = Pelayanan::with([
            'pesertaKb.wilayah', 
            'alokon.instansi', 
            'skriningMedis.informedConsent'
        ]);

        if (!empty($this->dariTanggal) && !empty($this->sampaiTanggal)) {
            $pelayananQuery->whereBetween('tanggal_pelayanan', [$this->dariTanggal, $this->sampaiTanggal]);
        }

        if (!empty($this->wilayahId)) {
            $pelayananQuery->whereHas('pesertaKb', function ($q) {
                $q->where('wilayah_id', $this->wilayahId);
            });
        }

        if (!empty($this->alokonId)) {
            $pelayananQuery->where('alokon_id', $this->alokonId);
        }

        $pelayanans = $pelayananQuery->latest('tanggal_pelayanan')->get();

        // 2. Peserta Query
        $pesertaQuery = PesertaKb::with(['wilayah', 'pelayanans.alokon', 'skriningMedis']);

        if (!empty($this->dariTanggal) && !empty($this->sampaiTanggal)) {
            $pesertaQuery->whereBetween('created_at', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay()
            ]);
        }

        if (!empty($this->wilayahId)) {
            $pesertaQuery->where('wilayah_id', $this->wilayahId);
        }

        $pesertas = $pesertaQuery->latest()->get();

        // 3. Alokon Query
        $alokonQuery = Alokon::with('instansi')->withCount('pelayanans');
        if (!empty($this->alokonId)) {
            $alokonQuery->where('id', $this->alokonId);
        }
        $inventory = $alokonQuery->orderBy('nama_alokon')->get();

        // Summaries
        $totalPelayanan = $pelayanans->count();
        $totalPesertaDilayani = $pelayanans->pluck('peserta_kb_id')->unique()->count();
        $totalAlokonTerdistribusi = $pelayanans->count();
        $totalWilayahTercakup = $pelayanans->map(fn($p) => $p->pesertaKb?->wilayah_id)->filter()->unique()->count();
        
        $totalPesertaTerdaftar = $pesertas->count();
        $totalPesertaTerverifikasi = $pesertas->where('status', 'terverifikasi')->count();
        $totalStokTersedia = $inventory->sum('stok');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.laporan', [
            'activeTab' => $this->activeTab,
            'pelayanans' => $pelayanans,
            'pesertas' => $pesertas,
            'inventory' => $inventory,
            'wilayahSelected' => $wilayahSelected,
            'alokonSelected' => $alokonSelected,
            'dariTanggal' => $this->dariTanggal,
            'sampaiTanggal' => $this->sampaiTanggal,
            'totalPelayanan' => $totalPelayanan,
            'totalPesertaDilayani' => $totalPesertaDilayani,
            'totalAlokonTerdistribusi' => $totalAlokonTerdistribusi,
            'totalWilayahTercakup' => $totalWilayahTercakup,
            'totalPesertaTerdaftar' => $totalPesertaTerdaftar,
            'totalPesertaTerverifikasi' => $totalPesertaTerverifikasi,
            'totalStokTersedia' => $totalStokTersedia,
        ]);

        $pdf->setPaper('a4', 'landscape');

        $tabName = match ($this->activeTab) {
            'pelayanan' => 'Pelayanan_KB',
            'peserta' => 'Data_Peserta_KB',
            'alokon' => 'Inventaris_Alokon',
            default => 'Laporan',
        };

        $fileName = 'Laporan_' . $tabName . '_' . date('Y-m-d_His') . '.pdf';

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $fileName);
    }

    /**
     * Export to CSV action
     */
    public function exportCsv()
    {
        $tabName = match ($this->activeTab) {
            'pelayanan' => 'Pelayanan_KB',
            'peserta' => 'Data_Peserta_KB',
            'alokon' => 'Inventaris_Alokon',
            default => 'Laporan',
        };

        $fileName = 'Laporan_' . $tabName . '_' . date('Y-m-d_His') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel characters display
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            if ($this->activeTab === 'pelayanan') {
                fputcsv($file, [
                    'No',
                    'Tanggal Pelayanan',
                    'Nama Peserta',
                    'NIK',
                    'Nama Suami/Istri',
                    'No. HP / WA',
                    'Wilayah (Desa/Kelurahan)',
                    'Alamat Lengkap',
                    'Alokon / Kontrasepsi',
                    'Faskes / Instansi',
                    'Status Peserta KB',
                    'Tindakan Medis',
                    'Hasil Skrining',
                    'Informed Consent',
                    'Tanggal Kunjungan Ulang',
                    'Tanggal Dicabut',
                    'Penanggung Jawab',
                    'Keterangan'
                ]);
                
                $pelayananQuery = Pelayanan::with(['pesertaKb.wilayah', 'alokon.instansi', 'skriningMedis.informedConsent']);

                if (!empty($this->dariTanggal) && !empty($this->sampaiTanggal)) {
                    $pelayananQuery->whereBetween('tanggal_pelayanan', [$this->dariTanggal, $this->sampaiTanggal]);
                }

                if (!empty($this->wilayahId)) {
                    $pelayananQuery->whereHas('pesertaKb', function ($q) {
                        $q->where('wilayah_id', $this->wilayahId);
                    });
                }
                if (!empty($this->alokonId)) {
                    $pelayananQuery->where('alokon_id', $this->alokonId);
                }

                $data = $pelayananQuery->latest('tanggal_pelayanan')->get();

                foreach ($data as $index => $row) {
                    $peserta = $row->pesertaKb;
                    $skrining = $row->skriningMedis;
                    $consent = $skrining?->informedConsent;

                    fputcsv($file, [
                        $index + 1,
                        $row->tanggal_pelayanan ? $row->tanggal_pelayanan->format('Y-m-d') : '-',
                        $peserta?->nama_lengkap ?? '-',
                        $peserta ? "=\"{$peserta->nik}\"" : '-',
                        $peserta?->nama_suami_istri ?? '-',
                        $peserta?->nomor_hp ? "=\"{$peserta->nomor_hp}\"" : '-',
                        $peserta?->wilayah?->nama_desa_kelurahan ?? '-',
                        $peserta?->alamat_lengkap ?? '-',
                        $row->alokon?->nama_alokon ?? '-',
                        $row->alokon?->instansi?->nama_instansi ?? '-',
                        $peserta?->status_kepesertaan ? ucfirst(str_replace('_', ' ', $peserta->status_kepesertaan)) : '-',
                        $consent?->jenis_tindakan_medis ? ucfirst($consent->jenis_tindakan_medis) : '-',
                        $skrining ? ($skrining->adaRiwayatPenyakit() ? 'Beresiko' : 'Lolos Skrining') : 'Tidak Ada',
                        $consent ? ($consent->isLengkap() ? 'Lengkap Disetujui' : 'Belum Lengkap') : 'Tidak Ada',
                        $row->tanggal_kunjungan_ulang ? $row->tanggal_kunjungan_ulang->format('Y-m-d') : '-',
                        $row->tanggal_dicabut ? $row->tanggal_dicabut->format('Y-m-d') : '-',
                        $row->penanggung_jawab_nama ?: 'Petugas Faskes',
                        $row->keterangan ?? '-'
                    ]);
                }
            } elseif ($this->activeTab === 'peserta') {
                fputcsv($file, [
                    'No',
                    'NIK',
                    'Nama Lengkap',
                    'Nama Suami/Istri',
                    'No. HP / WA',
                    'Tanggal Lahir',
                    'Usia (Tahun)',
                    'Wilayah (Desa/Kelurahan)',
                    'Alamat Lengkap',
                    'Asuransi',
                    'Pendidikan Istri',
                    'Pendidikan Suami',
                    'Pekerjaan Istri',
                    'Pekerjaan Suami',
                    'Jumlah Anak Hidup',
                    'Anak Laki-laki',
                    'Anak Perempuan',
                    'Umur Anak Terakhir (Bulan)',
                    'Status Kepesertaan KB',
                    'KB Terakhir',
                    'Status Verifikasi',
                    'Tanggal Pendaftaran'
                ]);
                
                $pesertaQuery = PesertaKb::with('wilayah');

                if (!empty($this->dariTanggal) && !empty($this->sampaiTanggal)) {
                    $pesertaQuery->whereBetween('created_at', [
                        Carbon::parse($this->dariTanggal)->startOfDay(),
                        Carbon::parse($this->sampaiTanggal)->endOfDay()
                    ]);
                }

                if (!empty($this->wilayahId)) {
                    $pesertaQuery->where('wilayah_id', $this->wilayahId);
                }

                $data = $pesertaQuery->latest()->get();

                foreach ($data as $index => $row) {
                    $usia = $row->tanggal_lahir_istri ? $row->tanggal_lahir_istri->age : '-';

                    fputcsv($file, [
                        $index + 1,
                        "=\"{$row->nik}\"",
                        $row->nama_lengkap,
                        $row->nama_suami_istri,
                        $row->nomor_hp ? "=\"{$row->nomor_hp}\"" : '-',
                        $row->tanggal_lahir_istri ? $row->tanggal_lahir_istri->format('Y-m-d') : '-',
                        $usia,
                        $row->wilayah?->nama_desa_kelurahan ?? '-',
                        $row->alamat_lengkap,
                        strtoupper($row->penggunaan_asuransi ?? '-'),
                        $row->pendidikan_istri ?? '-',
                        $row->pendidikan_suami ?? '-',
                        $row->pekerjaan_istri ?? '-',
                        $row->pekerjaan_suami ?? '-',
                        $row->jumlah_anak_hidup ?? 0,
                        $row->jumlah_anak_laki ?? 0,
                        $row->jumlah_anak_perempuan ?? 0,
                        $row->umur_anak_terakhir ?? '-',
                        $row->status_kepesertaan ? ucfirst(str_replace('_', ' ', $row->status_kepesertaan)) : '-',
                        $row->kb_terakhir ?? '-',
                        ucfirst($row->status),
                        $row->created_at ? $row->created_at->format('Y-m-d H:i') : '-'
                    ]);
                }
            } else {
                fputcsv($file, [
                    'No',
                    'Nama Alat / Obat Kontrasepsi',
                    'Faskes / Instansi',
                    'Kode Faskes',
                    'Sisa Stok (Unit)',
                    'Total Terdistribusi (Pelayanan)',
                    'Status Stok'
                ]);
                
                $alokonQuery = Alokon::with('instansi')->withCount('pelayanans');
                if (!empty($this->alokonId)) {
                    $alokonQuery->where('id', $this->alokonId);
                }

                $data = $alokonQuery->orderBy('nama_alokon')->get();

                foreach ($data as $index => $row) {
                    $statusStok = $row->stok < 5 ? 'Kritis' : ($row->stok < 10 ? 'Rendah' : 'Aman');

                    fputcsv($file, [
                        $index + 1,
                        $row->nama_alokon,
                        $row->instansi?->nama_instansi ?? '-',
                        $row->instansi?->kode_faskes ?? '-',
                        $row->stok,
                        $row->pelayanans_count ?? 0,
                        $statusStok
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

