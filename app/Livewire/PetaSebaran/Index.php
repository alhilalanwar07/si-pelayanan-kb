<?php

namespace App\Livewire\PetaSebaran;

use App\Models\Pelayanan;
use App\Models\PesertaKb;
use App\Models\Wilayah;
use Livewire\Component;

class Index extends Component
{
    public ?int $selectedWilayahId = null;
    public string $search = '';
    public string $densityFilter = 'all'; // all, high, medium, low
    public string $viewMode = 'vector'; // 'vector' (Choropleth SVG default) or 'map' (Leaflet interactive GIS)

    protected $queryString = [
        'selectedWilayahId' => ['except' => null, 'as' => 'wilayah'],
        'densityFilter' => ['except' => 'all'],
        'viewMode' => ['except' => ''],
    ];

    public function mount(): void
    {
        if (!request()->has('viewMode') && !app()->runningUnitTests()) {
            $this->redirect(route('peta-sebaran.index', array_merge(request()->query(), ['viewMode' => 'vector'])), navigate: true);
            return;
        }

        $this->viewMode = request('viewMode', 'vector');

        if (request()->has('wilayah')) {
            $this->selectedWilayahId = (int) request('wilayah');
        }
    }

    public function selectWilayah(?int $id): void
    {
        if ($this->selectedWilayahId === $id) {
            $this->selectedWilayahId = null;
        } else {
            $this->selectedWilayahId = $id;
        }

        $this->dispatch('wilayah-selected', id: $this->selectedWilayahId);
    }

    public function resetSelection(): void
    {
        $this->selectedWilayahId = null;
        $this->search = '';
        $this->densityFilter = 'all';
        $this->dispatch('wilayah-selected', id: null);
    }

    public function setViewMode(string $mode): void
    {
        $this->viewMode = in_array($mode, ['map', 'vector']) ? $mode : 'map';
    }

    public function setDensityFilter(string $filter): void
    {
        $this->densityFilter = $filter;
    }

    public function render()
    {
        $totalPeserta = PesertaKb::count();
        $totalPelayanan = Pelayanan::count();

        // Coordinates database for Kecamatan Wundulako
        $geoCoordinates = [
            'Kelurahan Wundulako' => [
                'lat' => -4.1385,
                'lng' => 121.6178,
                'code' => 'WND',
                'type' => 'Kelurahan',
                'faskes' => 'UPTD Puskesmas Wundulako (Pusat)',
                'is_puskesmas' => true,
                'polygon' => [
                    [-4.130, 121.610],
                    [-4.130, 121.625],
                    [-4.145, 121.628],
                    [-4.148, 121.614],
                    [-4.138, 121.608]
                ]
            ],
            'Desa Bende' => [
                'lat' => -4.1150,
                'lng' => 121.6120,
                'code' => 'BND',
                'type' => 'Desa',
                'faskes' => 'Poskesdes Bende',
                'is_puskesmas' => false,
                'polygon' => [
                    [-4.105, 121.605],
                    [-4.102, 121.622],
                    [-4.125, 121.625],
                    [-4.130, 121.610],
                    [-4.120, 121.600]
                ]
            ],
            'Desa Kowioha' => [
                'lat' => -4.1320,
                'lng' => 121.6450,
                'code' => 'KWH',
                'type' => 'Desa',
                'faskes' => 'Pustu Kowioha',
                'is_puskesmas' => false,
                'polygon' => [
                    [-4.125, 121.630],
                    [-4.120, 121.658],
                    [-4.142, 121.662],
                    [-4.145, 121.632]
                ]
            ],
            'Desa Lamokuni' => [
                'lat' => -4.1580,
                'lng' => 121.6250,
                'code' => 'LMK',
                'type' => 'Desa',
                'faskes' => 'Poskesdes Lamokuni',
                'is_puskesmas' => false,
                'polygon' => [
                    [-4.148, 121.615],
                    [-4.145, 121.635],
                    [-4.170, 121.640],
                    [-4.172, 121.620],
                    [-4.160, 121.610]
                ]
            ],
            'Desa Watalara' => [
                'lat' => -4.1480,
                'lng' => 121.5950,
                'code' => 'WTL',
                'type' => 'Desa',
                'faskes' => 'Pustu Watalara',
                'is_puskesmas' => false,
                'polygon' => [
                    [-4.135, 121.585],
                    [-4.138, 121.608],
                    [-4.158, 121.610],
                    [-4.165, 121.590],
                    [-4.150, 121.580]
                ]
            ],
        ];

        // Fetch all Wilayah with relations
        $allWilayahs = Wilayah::withCount(['pesertaKbs', 'pelayanans'])
            ->orderBy('peserta_kbs_count', 'desc')
            ->get();

        // Enrich with GIS metadata and tiering
        $enrichedWilayahs = $allWilayahs->map(function ($w, $index) use ($totalPeserta, $geoCoordinates) {
            $w->persentase = $totalPeserta > 0
                ? round(($w->peserta_kbs_count / $totalPeserta) * 100, 1)
                : 0;

            $geo = $geoCoordinates[$w->nama_desa_kelurahan] ?? [
                'lat' => -4.1333 + ($index * 0.015),
                'lng' => 121.6167 + ($index * 0.012),
                'code' => strtoupper(substr(str_replace(['Desa ', 'Kelurahan '], '', $w->nama_desa_kelurahan), 0, 3)),
                'type' => str_starts_with($w->nama_desa_kelurahan, 'Kelurahan') ? 'Kelurahan' : 'Desa',
                'faskes' => 'Poskesdes ' . str_replace(['Desa ', 'Kelurahan '], '', $w->nama_desa_kelurahan),
                'is_puskesmas' => false,
                'polygon' => []
            ];

            $w->lat = $geo['lat'];
            $w->lng = $geo['lng'];
            $w->code = $geo['code'];
            $w->type = $geo['type'];
            $w->faskes = $geo['faskes'];
            $w->is_puskesmas = $geo['is_puskesmas'];
            $w->polygon = $geo['polygon'];

            // Density classification
            if ($w->peserta_kbs_count >= 4) {
                $w->density_tier = 'high';
                $w->density_label = 'Kerapatan Tinggi';
                $w->color = 'fill-blue-600 hover:fill-blue-700 dark:fill-blue-500';
                $w->hex_color = '#2563eb';
                $w->bg_class = 'bg-blue-600';
                $w->text_class = 'text-blue-600 dark:text-blue-400';
                $w->badge_variant = 'blue';
            } elseif ($w->peserta_kbs_count >= 2) {
                $w->density_tier = 'medium';
                $w->density_label = 'Kerapatan Sedang';
                $w->color = 'fill-sky-500 hover:fill-sky-600 dark:fill-sky-400';
                $w->hex_color = '#0ea5e9';
                $w->bg_class = 'bg-sky-500';
                $w->text_class = 'text-sky-600 dark:text-sky-400';
                $w->badge_variant = 'sky';
            } else {
                $w->density_tier = 'low';
                $w->density_label = 'Kerapatan Rendah';
                $w->color = 'fill-slate-300 hover:fill-slate-400 dark:fill-slate-600';
                $w->hex_color = '#94a3b8';
                $w->bg_class = 'bg-slate-400';
                $w->text_class = 'text-slate-600 dark:text-slate-400';
                $w->badge_variant = 'zinc';
            }

            return $w;
        });

        // Filter for display in the sidebar list
        $filteredWilayahs = $enrichedWilayahs->filter(function ($w) {
            $matchSearch = empty($this->search) || str_contains(
                strtolower($w->nama_desa_kelurahan),
                strtolower(trim($this->search))
            );

            $matchDensity = match ($this->densityFilter) {
                'high' => $w->density_tier === 'high',
                'medium' => $w->density_tier === 'medium',
                'low' => $w->density_tier === 'low',
                default => true,
            };

            return $matchSearch && $matchDensity;
        });

        // Selected Wilayah detailed analytics
        $selectedWilayah = null;
        $selectedAlokonBreakdown = [];
        $selectedRecentPeserta = [];

        if ($this->selectedWilayahId) {
            $selectedWilayah = $enrichedWilayahs->firstWhere('id', $this->selectedWilayahId);

            if ($selectedWilayah) {
                // Alokon methods breakdown for this village
                $pelayanansInWilayah = Pelayanan::whereHas('pesertaKb', function ($q) {
                    $q->where('wilayah_id', $this->selectedWilayahId);
                })->with('alokon')->get();

                $selectedAlokonBreakdown = $pelayanansInWilayah
                    ->groupBy(fn ($p) => $p->alokon?->nama_alokon ?: 'Metode Lain')
                    ->map(fn ($group) => [
                        'count' => $group->count(),
                        'percentage' => $pelayanansInWilayah->count() > 0 
                            ? round(($group->count() / $pelayanansInWilayah->count()) * 100, 1) 
                            : 0
                    ])
                    ->sortByDesc('count');

                // Recent participants in this village
                $selectedRecentPeserta = PesertaKb::where('wilayah_id', $this->selectedWilayahId)
                    ->latest()
                    ->limit(6)
                    ->get();
            }
        }

        // Top ranked village
        $topWilayah = $enrichedWilayahs->first();

        return view('livewire.peta-sebaran.index', [
            'wilayahs' => $filteredWilayahs,
            'allWilayahs' => $enrichedWilayahs,
            'selectedWilayah' => $selectedWilayah,
            'selectedAlokonBreakdown' => $selectedAlokonBreakdown,
            'selectedRecentPeserta' => $selectedRecentPeserta,
            'totalPeserta' => $totalPeserta,
            'totalPelayanan' => $totalPelayanan,
            'topWilayah' => $topWilayah,
            'gisJsonData' => $enrichedWilayahs->values()->toArray(),
        ])->layout('layouts.app', ['title' => 'Peta Sebaran Peserta KB']);
    }
}
