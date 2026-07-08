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

    public function render()
    {
        $wilayahs = Wilayah::orderBy('nama_desa_kelurahan')->get();
        $alokons = Alokon::orderBy('nama_alokon')->get();

        // 1. Pelayanan Query
        $pelayananQuery = Pelayanan::with(['pesertaKb.wilayah', 'alokon.instansi', 'skriningMedis.informedConsent'])
            ->whereBetween('tanggal_pelayanan', [$this->dariTanggal, $this->sampaiTanggal]);

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
        $pesertaQuery = PesertaKb::with('wilayah')
            ->whereBetween('created_at', [
                Carbon::parse($this->dariTanggal)->startOfDay(),
                Carbon::parse($this->sampaiTanggal)->endOfDay()
            ]);

        if (!empty($this->wilayahId)) {
            $pesertaQuery->where('wilayah_id', $this->wilayahId);
        }

        $pesertas = $pesertaQuery->latest()->get();

        // 3. Alokon Query
        $alokonQuery = Alokon::with('instansi');
        if (!empty($this->alokonId)) {
            $alokonQuery->where('id', $this->alokonId);
        }
        $inventory = $alokonQuery->orderBy('nama_alokon')->get();

        // Summaries
        $totalPelayanan = $pelayanans->count();
        $totalPesertaDilayani = $pelayanans->pluck('peserta_kb_id')->unique()->count();
        $totalAlokonTerdistribusi = $pelayanans->count(); // 1 unit per pelayanan
        $totalWilayahTercakup = $pelayanans->map(fn($p) => $p->pesertaKb->wilayah_id)->unique()->count();

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
        ])->layout('layouts.app', ['title' => 'Laporan Rekapitulasi']);
    }

    /**
     * Export to CSV action
     */
    public function exportCsv()
    {
        $fileName = 'Laporan_SI_Pelayanan_KB_' . $this->activeTab . '_' . date('Y-m-d') . '.csv';
        
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            if ($this->activeTab === 'pelayanan') {
                fputcsv($file, ['No', 'Tanggal Pelayanan', 'Nama Peserta', 'NIK', 'Wilayah', 'Alokon', 'Tindakan', 'Keterangan']);
                
                $pelayananQuery = Pelayanan::with(['pesertaKb.wilayah', 'alokon'])
                    ->whereBetween('tanggal_pelayanan', [$this->dariTanggal, $this->sampaiTanggal]);

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
                    fputcsv($file, [
                        $index + 1,
                        $row->tanggal_pelayanan->format('Y-m-d'),
                        $row->pesertaKb->nama_lengkap,
                        "'" . $row->pesertaKb->nik, // single quote to prevent scientific notation in Excel
                        $row->pesertaKb->wilayah->nama_desa_kelurahan,
                        $row->alokon->nama_alokon,
                        $row->skriningMedis?->informedConsent?->jenis_tindakan_medis ?? '-',
                        $row->keterangan ?? '-'
                    ]);
                }
            } elseif ($this->activeTab === 'peserta') {
                fputcsv($file, ['No', 'NIK', 'Nama Lengkap', 'Nama Pasangan', 'Tanggal Lahir', 'Wilayah', 'Asuransi', 'Status']);
                
                $pesertaQuery = PesertaKb::with('wilayah')
                    ->whereBetween('created_at', [
                        Carbon::parse($this->dariTanggal)->startOfDay(),
                        Carbon::parse($this->sampaiTanggal)->endOfDay()
                    ]);

                if (!empty($this->wilayahId)) {
                    $pesertaQuery->where('wilayah_id', $this->wilayahId);
                }

                $data = $pesertaQuery->latest()->get();
                foreach ($data as $index => $row) {
                    fputcsv($file, [
                        $index + 1,
                        "'" . $row->nik,
                        $row->nama_lengkap,
                        $row->nama_suami_istri,
                        $row->tanggal_lahir_istri->format('Y-m-d'),
                        $row->wilayah->nama_desa_kelurahan,
                        strtoupper($row->penggunaan_asuransi ?? '-'),
                        ucfirst($row->status)
                    ]);
                }
            } else {
                fputcsv($file, ['No', 'Nama Alokon', 'Faskes / Instansi', 'Stok Tersedia', 'Status Stok']);
                
                $alokonQuery = Alokon::with('instansi');
                if (!empty($this->alokonId)) {
                    $alokonQuery->where('id', $this->alokonId);
                }

                $data = $alokonQuery->orderBy('nama_alokon')->get();
                foreach ($data as $index => $row) {
                    fputcsv($file, [
                        $index + 1,
                        $row->nama_alokon,
                        $row->instansi->nama_instansi,
                        $row->stok,
                        $row->stok < 5 ? 'Kritis' : ($row->stok < 10 ? 'Rendah' : 'Aman')
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
