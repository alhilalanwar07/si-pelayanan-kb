<div class="flex-1 space-y-6 p-6" x-data="gisSebaranApp({
    allWilayahs: @js($gisJsonData),
    selectedId: @entangle('selectedWilayahId'),
    viewMode: @entangle('viewMode')
})">
    <!-- Leaflet CSS & JS Assets -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Custom CSS for GIS Pulse and Animations -->
    <style>
        .leaflet-container {
            font-family: inherit;
            border-radius: 1rem;
            z-index: 10;
        }
        .pulse-marker-high {
            box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
            animation: pulse-blue 1.8s infinite cubic-bezier(0.66, 0, 0, 1);
        }
        .pulse-marker-med {
            box-shadow: 0 0 0 0 rgba(14, 165, 233, 0.7);
            animation: pulse-sky 2s infinite cubic-bezier(0.66, 0, 0, 1);
        }
        .pulse-marker-puskesmas {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7);
            animation: pulse-red 1.6s infinite cubic-bezier(0.66, 0, 0, 1);
        }
        @keyframes pulse-blue {
            to { box-shadow: 0 0 0 16px rgba(37, 99, 235, 0); }
        }
        @keyframes pulse-sky {
            to { box-shadow: 0 0 0 14px rgba(14, 165, 233, 0); }
        }
        @keyframes pulse-red {
            to { box-shadow: 0 0 0 18px rgba(239, 68, 68, 0); }
        }
        .custom-popup .leaflet-popup-content-wrapper {
            background: #ffffff;
            border-radius: 12px;
            padding: 4px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .dark .custom-popup .leaflet-popup-content-wrapper {
            background: #18181b;
            color: #ffffff;
            border-color: #27272a;
        }
        .custom-popup .leaflet-popup-tip {
            background: #ffffff;
        }
        .dark .custom-popup .leaflet-popup-tip {
            background: #18181b;
        }
    </style>

    <!-- Page Header & Mode Switcher -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <div class="flex items-center gap-2.5">
                <div class="p-2 rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 6.75V15m6-6v8.25m.503 3.346l1.982-1.077a1.5 1.5 0 00.765-1.319V6.442a1.5 1.5 0 00-.765-1.32l-1.982-1.076a1.5 1.5 0 00-1.5 0L10.5 5.122a1.5 1.5 0 01-1.5 0L7.018 4.045a1.5 1.5 0 00-1.5 0L3.536 5.122a1.5 1.5 0 00-.765 1.32v10.162a1.5 1.5 0 00.765 1.319l1.982 1.077a1.5 1.5 0 001.5 0L9 17.878a1.5 1.5 0 011.5 0l1.982 1.077a1.5 1.5 0 001.5 0z" />
                    </svg>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <flux:heading size="xl" level="1">Peta Sebaran Geografis Peserta KB</flux:heading>
                        <flux:badge size="sm" color="blue" class="font-medium">GIS Spasial</flux:badge>
                    </div>
                    <flux:text size="sm">Pemetaan spasial & analisis densitas akseptor KB di seluruh wilayah Kecamatan Wundulako</flux:text>
                </div>
            </div>
        </div>

        <!-- Mode Switcher & Reset Button -->
        <div class="flex items-center gap-2">
            @if($selectedWilayahId)
                <flux:button variant="subtle" size="sm" icon="x-mark" wire:click="resetSelection" class="text-zinc-600 dark:text-zinc-300">
                    Batal Pilih
                </flux:button>
            @endif

            <div class="inline-flex rounded-xl bg-zinc-100 p-1 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                <button type="button" disabled
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg opacity-50 cursor-not-allowed text-zinc-400 dark:text-zinc-500">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span>Peta GIS Satelit</span>
                </button>
                <button type="button" 
                        wire:click="setViewMode('vector')"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $viewMode === 'vector' ? 'bg-white text-blue-600 shadow-xs dark:bg-zinc-700 dark:text-white' : 'text-zinc-600 hover:text-zinc-900 dark:text-zinc-400 dark:hover:text-white' }}">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
                    </svg>
                    <span>Peta Tematik SVG</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Top KPI Highlights (4 Summary Cards) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Metric 1: Total Wilayah -->
        <flux:card class="p-4 relative overflow-hidden bg-gradient-to-br from-blue-500/10 via-transparent to-transparent border-blue-500/20">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Total Wilayah</span>
                <span class="p-1.5 rounded-lg bg-blue-50 text-blue-600 dark:bg-blue-900/30 dark:text-blue-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /></svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white">{{ $allWilayahs->count() }}</span>
                <span class="text-xs text-zinc-500">Desa/Kelurahan</span>
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">100% Tercakup Faskes</span>
        </flux:card>

        <!-- Metric 2: Total Peserta Terpetakan -->
        <flux:card class="p-4 relative overflow-hidden bg-gradient-to-br from-emerald-500/10 via-transparent to-transparent border-emerald-500/20">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Peserta Terpetakan</span>
                <span class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </span>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white">{{ $totalPeserta }}</span>
                <span class="text-xs text-zinc-500">Akseptor Aktif</span>
            </div>
            <span class="text-[11px] text-emerald-600 dark:text-emerald-400 mt-1 block font-medium">Spasial terverifikasi</span>
        </flux:card>

        <!-- Metric 3: Wilayah Kerapatan Tertinggi -->
        <flux:card class="p-4 relative overflow-hidden bg-gradient-to-br from-indigo-500/10 via-transparent to-transparent border-indigo-500/20">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Kerapatan Tertinggi</span>
                <span class="p-1.5 rounded-lg bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                </span>
            </div>
            <div class="mt-2 truncate">
                <span class="text-lg font-bold text-zinc-900 dark:text-white truncate block">{{ $topWilayah->nama_desa_kelurahan ?? '-' }}</span>
            </div>
            <span class="text-[11px] text-indigo-600 dark:text-indigo-400 mt-1 block font-medium">
                {{ $topWilayah->peserta_kbs_count ?? 0 }} Peserta ({{ $topWilayah->persentase ?? 0 }}%)
            </span>
        </flux:card>

        <!-- Metric 4: Faskes Penyangga -->
        <flux:card class="p-4 relative overflow-hidden bg-gradient-to-br from-rose-500/10 via-transparent to-transparent border-rose-500/20">
            <div class="flex items-center justify-between">
                <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Pusat Layanan</span>
                <span class="p-1.5 rounded-lg bg-rose-50 text-rose-600 dark:bg-rose-900/30 dark:text-rose-400">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                </span>
            </div>
            <div class="mt-2">
                <span class="text-sm font-bold text-zinc-900 dark:text-white block">UPTD Puskesmas Wundulako</span>
            </div>
            <span class="text-[11px] text-zinc-500 mt-1 block">5 Poskesdes & Pustu Jejaring</span>
        </flux:card>
    </div>

    <!-- Main Content Area: Split Sidebar & Map View -->
    <div class="grid gap-6 lg:grid-cols-12">
        
        <!-- Left Sidebar: Wilayah Navigator & Density Filters (4 Cols) -->
        <div class="lg:col-span-4 space-y-4">
            <flux:card class="space-y-4 p-4">
                <!-- Search & Header -->
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <flux:heading size="md">Navigasi Wilayah</flux:heading>
                        <flux:badge size="sm" color="zinc">{{ $wilayahs->count() }} Desa</flux:badge>
                    </div>
                    
                    <div class="relative">
                        <flux:input wire:model.live.debounce.300ms="search" placeholder="Cari desa / kelurahan..." icon="magnifying-glass" clearable size="sm" />
                    </div>
                </div>

                <!-- Density Filter Pills -->
                <div class="space-y-1.5">
                    <span class="text-xs font-semibold text-zinc-500 dark:text-zinc-400">Filter Tingkat Kerapatan:</span>
                    <div class="grid grid-cols-4 gap-1.5 text-center">
                        <button type="button" 
                                wire:click="setDensityFilter('all')"
                                class="px-2 py-1 text-[11px] font-semibold rounded-lg transition-all {{ $densityFilter === 'all' ? 'bg-blue-600 text-white shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300' }}">
                            Semua
                        </button>
                        <button type="button" 
                                wire:click="setDensityFilter('high')"
                                class="px-2 py-1 text-[11px] font-semibold rounded-lg transition-all {{ $densityFilter === 'high' ? 'bg-blue-600 text-white shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300' }}">
                            Tinggi
                        </button>
                        <button type="button" 
                                wire:click="setDensityFilter('medium')"
                                class="px-2 py-1 text-[11px] font-semibold rounded-lg transition-all {{ $densityFilter === 'medium' ? 'bg-sky-500 text-white shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300' }}">
                            Sedang
                        </button>
                        <button type="button" 
                                wire:click="setDensityFilter('low')"
                                class="px-2 py-1 text-[11px] font-semibold rounded-lg transition-all {{ $densityFilter === 'low' ? 'bg-slate-500 text-white shadow-xs' : 'bg-zinc-100 text-zinc-600 hover:bg-zinc-200 dark:bg-zinc-800 dark:text-zinc-300' }}">
                            Rendah
                        </button>
                    </div>
                </div>

                <flux:separator />

                <!-- Wilayah Interactive List -->
                <div class="space-y-2 max-h-[380px] overflow-y-auto pr-1">
                    @forelse($wilayahs as $w)
                        <div wire:click="selectWilayah({{ $w->id }})"
                             @click="flyToWilayah({{ $w->id }}, {{ $w->lat }}, {{ $w->lng }})"
                             class="group cursor-pointer p-3 rounded-xl border transition-all duration-200 {{ $selectedWilayahId === $w->id ? 'bg-blue-50 border-blue-500 dark:bg-blue-950/40 dark:border-blue-500 shadow-xs' : 'bg-white border-zinc-200 hover:border-blue-300 hover:bg-zinc-50/80 dark:bg-zinc-800/80 dark:border-zinc-700 dark:hover:bg-zinc-700/60' }}">
                            
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="size-3.5 rounded-full shrink-0 {{ $w->bg_class }} ring-2 ring-white dark:ring-zinc-900"></div>
                                    <div class="truncate">
                                        <div class="flex items-center gap-1.5">
                                            <span class="font-bold text-sm text-zinc-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                                {{ $w->nama_desa_kelurahan }}
                                            </span>
                                            @if($w->is_puskesmas)
                                                <span class="px-1.5 py-0.5 text-[9px] font-bold rounded bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">Puskesmas</span>
                                            @endif
                                        </div>
                                        <span class="text-[11px] text-zinc-500 dark:text-zinc-400 block truncate">{{ $w->faskes }}</span>
                                    </div>
                                </div>

                                <div class="text-right shrink-0">
                                    <div class="flex items-baseline justify-end gap-1">
                                        <span class="font-extrabold text-base text-zinc-900 dark:text-white">{{ $w->peserta_kbs_count }}</span>
                                        <span class="text-[10px] text-zinc-500">peserta</span>
                                    </div>
                                    <span class="text-[11px] font-semibold {{ $w->text_class }}">
                                        {{ $w->persentase }}%
                                    </span>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="mt-2.5 w-full bg-zinc-100 rounded-full h-1.5 dark:bg-zinc-700 overflow-hidden">
                                <div class="h-1.5 rounded-full {{ $w->bg_class }} transition-all duration-500" style="width: {{ max($w->persentase, 4) }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-zinc-500 text-sm">
                            Tidak ada wilayah yang cocok dengan filter pencarian.
                        </div>
                    @endforelse
                </div>

                <flux:separator />

                <!-- Legenda Kerapatan -->
                <div class="bg-zinc-50 dark:bg-zinc-900/60 p-3 rounded-xl border border-zinc-200/80 dark:border-zinc-700/80 space-y-2">
                    <span class="text-xs font-bold text-zinc-700 dark:text-zinc-300 block">Keterangan Gradasi Warna:</span>
                    <div class="grid grid-cols-3 gap-2 text-center text-[10px] font-semibold">
                        <div class="flex items-center gap-1.5">
                            <div class="size-3 rounded bg-blue-600 shrink-0"></div>
                            <span class="text-zinc-700 dark:text-zinc-300">Tinggi (≥4)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="size-3 rounded bg-sky-500 shrink-0"></div>
                            <span class="text-zinc-700 dark:text-zinc-300">Sedang (2-3)</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <div class="size-3 rounded bg-slate-400 shrink-0"></div>
                            <span class="text-zinc-700 dark:text-zinc-300">Rendah (<2)</span>
                        </div>
                    </div>
                </div>
            </flux:card>
        </div>

        <!-- Right Column: GIS Leaflet / SVG Map + Selected Details (8 Cols) -->
        <div class="lg:col-span-8 space-y-6">
            
            <!-- Map Container Card -->
            <flux:card class="p-0 overflow-hidden relative shadow-md border border-zinc-200 dark:border-zinc-700">
                <!-- Map Header Overlay Bar -->
                <div class="p-4 bg-white/90 dark:bg-zinc-900/90 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-700 flex flex-wrap items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="relative flex size-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full size-3 bg-blue-600"></span>
                        </span>
                        <flux:heading size="md">
                            {{ $viewMode === 'map' ? 'Peta Satelit GIS & Titik Wilayah' : 'Visualisasi Poligon Tematik' }}
                        </flux:heading>
                    </div>

                    <!-- Map Actions Controls -->
                    <div class="flex items-center gap-2">
                        @if($viewMode === 'map')
                            <button type="button" 
                                    @click="recenterMap()"
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-lg bg-zinc-100 hover:bg-zinc-200 text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 dark:hover:bg-zinc-700 transition-colors">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
                                <span>Pusatkan Peta</span>
                            </button>
                        @endif
                        <span class="text-xs text-zinc-500">Kecamatan Wundulako (Kolaka)</span>
                    </div>
                </div>

                <!-- 1. Interactive Leaflet GIS Map View -->
                <div x-show="viewMode === 'map'" class="relative w-full h-[460px] bg-zinc-100 dark:bg-zinc-950">
                    <div id="leaflet-map" wire:ignore class="w-full h-full"></div>
                </div>

                <!-- 2. Interactive SVG Vector Map View -->
                <div x-show="viewMode === 'vector'" class="p-6 flex flex-col items-center justify-center bg-zinc-50/50 dark:bg-zinc-950/50 min-h-[460px]">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 400" class="w-full max-w-[550px] h-auto drop-shadow-xl select-none">
                        <defs>
                            <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                                <feGaussianBlur stdDeviation="4" result="blur" />
                                <feComposite in="SourceGraphic" in2="blur" operator="over" />
                            </filter>
                        </defs>

                        <!-- Outer Border / Background Canvas -->
                        <rect x="0" y="0" width="600" height="400" rx="16" fill="transparent" class="stroke-zinc-300 dark:stroke-zinc-800" stroke-width="1.5" stroke-dasharray="6 6" />

                        <!-- Kelurahan Wundulako (Center) -->
                        @php $w0 = $allWilayahs->firstWhere('nama_desa_kelurahan', 'Kelurahan Wundulako'); @endphp
                        <g wire:click="selectWilayah({{ $w0?->id }})" class="cursor-pointer group">
                            <polygon points="200,150 350,130 380,220 280,250 180,200" 
                                     class="{{ $w0 ? $w0->color : 'fill-zinc-200' }} {{ $selectedWilayahId === $w0?->id ? 'stroke-amber-400 stroke-[4] filter-[url(#glow)]' : 'stroke-white dark:stroke-zinc-900 stroke-[3]' }} transition-all duration-300 group-hover:opacity-90" />
                            <circle cx="275" cy="190" r="5" fill="#ef4444" stroke="#ffffff" stroke-width="2" />
                            <text x="275" y="175" text-anchor="middle" class="fill-zinc-900 dark:fill-white font-extrabold text-[11px] pointer-events-none drop-shadow-sm">Kel. Wundulako</text>
                            <text x="275" y="210" text-anchor="middle" class="fill-zinc-700 dark:fill-zinc-300 font-semibold text-[9px] pointer-events-none">({{ $w0?->peserta_kbs_count ?? 0 }} Peserta)</text>
                        </g>

                        <!-- Desa Bende (North) -->
                        @php $w1 = $allWilayahs->firstWhere('nama_desa_kelurahan', 'Desa Bende'); @endphp
                        <g wire:click="selectWilayah({{ $w1?->id }})" class="cursor-pointer group">
                            <polygon points="150,50 320,30 350,130 200,150 120,110" 
                                     class="{{ $w1 ? $w1->color : 'fill-zinc-200' }} {{ $selectedWilayahId === $w1?->id ? 'stroke-amber-400 stroke-[4] filter-[url(#glow)]' : 'stroke-white dark:stroke-zinc-900 stroke-[3]' }} transition-all duration-300 group-hover:opacity-90" />
                            <circle cx="235" cy="90" r="4" fill="#2563eb" stroke="#ffffff" stroke-width="2" />
                            <text x="235" y="80" text-anchor="middle" class="fill-zinc-900 dark:fill-white font-extrabold text-[11px] pointer-events-none drop-shadow-sm">Desa Bende</text>
                            <text x="235" y="105" text-anchor="middle" class="fill-zinc-700 dark:fill-zinc-300 font-semibold text-[9px] pointer-events-none">({{ $w1?->peserta_kbs_count ?? 0 }} Peserta)</text>
                        </g>

                        <!-- Desa Kowioha (East) -->
                        @php $w2 = $allWilayahs->firstWhere('nama_desa_kelurahan', 'Desa Kowioha'); @endphp
                        <g wire:click="selectWilayah({{ $w2?->id }})" class="cursor-pointer group">
                            <polygon points="350,130 480,100 520,200 380,220" 
                                     class="{{ $w2 ? $w2->color : 'fill-zinc-200' }} {{ $selectedWilayahId === $w2?->id ? 'stroke-amber-400 stroke-[4] filter-[url(#glow)]' : 'stroke-white dark:stroke-zinc-900 stroke-[3]' }} transition-all duration-300 group-hover:opacity-90" />
                            <circle cx="430" cy="160" r="4" fill="#2563eb" stroke="#ffffff" stroke-width="2" />
                            <text x="430" y="150" text-anchor="middle" class="fill-zinc-900 dark:fill-white font-extrabold text-[11px] pointer-events-none drop-shadow-sm">Desa Kowioha</text>
                            <text x="430" y="175" text-anchor="middle" class="fill-zinc-700 dark:fill-zinc-300 font-semibold text-[9px] pointer-events-none">({{ $w2?->peserta_kbs_count ?? 0 }} Peserta)</text>
                        </g>

                        <!-- Desa Lamokuni (South) -->
                        @php $w3 = $allWilayahs->firstWhere('nama_desa_kelurahan', 'Desa Lamokuni'); @endphp
                        <g wire:click="selectWilayah({{ $w3?->id }})" class="cursor-pointer group">
                            <polygon points="280,250 380,220 420,340 300,350 240,300" 
                                     class="{{ $w3 ? $w3->color : 'fill-zinc-200' }} {{ $selectedWilayahId === $w3?->id ? 'stroke-amber-400 stroke-[4] filter-[url(#glow)]' : 'stroke-white dark:stroke-zinc-900 stroke-[3]' }} transition-all duration-300 group-hover:opacity-90" />
                            <circle cx="325" cy="290" r="4" fill="#2563eb" stroke="#ffffff" stroke-width="2" />
                            <text x="325" y="280" text-anchor="middle" class="fill-zinc-900 dark:fill-white font-extrabold text-[11px] pointer-events-none drop-shadow-sm">Desa Lamokuni</text>
                            <text x="325" y="305" text-anchor="middle" class="fill-zinc-700 dark:fill-zinc-300 font-semibold text-[9px] pointer-events-none">({{ $w3?->peserta_kbs_count ?? 0 }} Peserta)</text>
                        </g>

                        <!-- Desa Watalara (West) -->
                        @php $w4 = $allWilayahs->firstWhere('nama_desa_kelurahan', 'Desa Watalara'); @endphp
                        <g wire:click="selectWilayah({{ $w4?->id }})" class="cursor-pointer group">
                            <polygon points="80,180 180,200 280,250 240,300 120,320 60,260" 
                                     class="{{ $w4 ? $w4->color : 'fill-zinc-200' }} {{ $selectedWilayahId === $w4?->id ? 'stroke-amber-400 stroke-[4] filter-[url(#glow)]' : 'stroke-white dark:stroke-zinc-900 stroke-[3]' }} transition-all duration-300 group-hover:opacity-90" />
                            <circle cx="155" cy="250" r="4" fill="#2563eb" stroke="#ffffff" stroke-width="2" />
                            <text x="155" y="240" text-anchor="middle" class="fill-zinc-900 dark:fill-white font-extrabold text-[11px] pointer-events-none drop-shadow-sm">Desa Watalara</text>
                            <text x="155" y="265" text-anchor="middle" class="fill-zinc-700 dark:fill-zinc-300 font-semibold text-[9px] pointer-events-none">({{ $w4?->peserta_kbs_count ?? 0 }} Peserta)</text>
                        </g>
                    </svg>
                    <span class="text-xs text-zinc-500 mt-2 font-medium">Klik pada bidang wilayah di peta untuk meninjau data rincian</span>
                </div>
            </flux:card>

            <!-- Selected Wilayah Deep Analysis Panel -->
            @if($selectedWilayah)
                <flux:card class="p-6 space-y-6 border-2 border-blue-500/40 bg-gradient-to-b from-blue-50/40 to-transparent dark:from-blue-950/20 dark:to-transparent animate-in fade-in duration-300">
                    <!-- Panel Header -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-zinc-200 pb-4 dark:border-zinc-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2.5 rounded-xl {{ $selectedWilayah->bg_class }} text-white shadow-sm">
                                <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <flux:heading size="lg">{{ $selectedWilayah->nama_desa_kelurahan }}</flux:heading>
                                    <flux:badge size="sm" :color="$selectedWilayah->badge_variant">
                                        {{ $selectedWilayah->density_label }}
                                    </flux:badge>
                                </div>
                                <flux:text size="sm">Fasilitas Kesehatan Penyangga: <b>{{ $selectedWilayah->faskes }}</b></flux:text>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <flux:button variant="primary" size="sm" icon="users" href="{{ route('peserta-kb.index') }}" wire:navigate class="bg-blue-600 hover:bg-blue-700 text-white font-semibold">
                                Lihat Data Peserta
                            </flux:button>
                            <flux:button variant="ghost" size="sm" icon="x-mark" wire:click="resetSelection" />
                        </div>
                    </div>

                    <!-- 3-Card Summary Grid for Selected Village -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-4 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                            <span class="text-xs text-zinc-500 font-medium">Total Akseptor KB</span>
                            <div class="mt-1 flex items-baseline gap-2">
                                <span class="text-2xl font-black text-blue-600 dark:text-blue-400">{{ $selectedWilayah->peserta_kbs_count }}</span>
                                <span class="text-xs text-zinc-500">orang</span>
                            </div>
                            <span class="text-[11px] text-zinc-500 mt-1 block">Kontribusi {{ $selectedWilayah->persentase }}% se-kecamatan</span>
                        </div>

                        <div class="p-4 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                            <span class="text-xs text-zinc-500 font-medium">Total Layanan Tercatat</span>
                            <div class="mt-1 flex items-baseline gap-2">
                                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $selectedWilayah->pelayanans_count }}</span>
                                <span class="text-xs text-zinc-500">tindakan</span>
                            </div>
                            <span class="text-[11px] text-zinc-500 mt-1 block">Rekam medis terverifikasi</span>
                        </div>

                        <div class="p-4 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                            <span class="text-xs text-zinc-500 font-medium">Kategori Kerapatan</span>
                            <div class="mt-1">
                                <span class="text-lg font-black {{ $selectedWilayah->text_class }}">{{ $selectedWilayah->density_label }}</span>
                            </div>
                            <span class="text-[11px] text-zinc-500 mt-1 block">Tingkat capaian program KB</span>
                        </div>
                    </div>

                    <!-- Bottom Breakdown: Contraceptive Methods & Recent Participants -->
                    <div class="grid gap-6 md:grid-cols-2">
                        <!-- Left: Contraceptive Methods Distribution -->
                        <div class="p-4 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">Sebaran Metode Kontrasepsi</span>
                                <span class="text-xs text-zinc-500">{{ count($selectedAlokonBreakdown) }} Metode</span>
                            </div>

                            @if(count($selectedAlokonBreakdown) > 0)
                                <div class="space-y-2.5">
                                    @foreach($selectedAlokonBreakdown as $methodName => $methodData)
                                        <div class="space-y-1">
                                            <div class="flex items-center justify-between text-xs">
                                                <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $methodName }}</span>
                                                <span class="font-bold text-zinc-900 dark:text-white">{{ $methodData['count'] }} orang ({{ $methodData['percentage'] }}%)</span>
                                            </div>
                                            <div class="w-full bg-zinc-100 rounded-full h-1.5 dark:bg-zinc-700 overflow-hidden">
                                                <div class="h-1.5 rounded-full bg-blue-600" style="width: {{ $methodData['percentage'] }}%"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 text-xs text-zinc-500">
                                    Belum ada rekam tindakan pelayanan di wilayah ini.
                                </div>
                            @endif
                        </div>

                        <!-- Right: Recent Registered Participants -->
                        <div class="p-4 rounded-xl bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold text-zinc-900 dark:text-white uppercase tracking-wider">Peserta KB Terdaftar</span>
                                <span class="text-xs text-zinc-500">Terbaru</span>
                            </div>

                            @if($selectedRecentPeserta->count() > 0)
                                <div class="divide-y divide-zinc-100 dark:divide-zinc-700/60 max-h-[180px] overflow-y-auto pr-1">
                                    @foreach($selectedRecentPeserta as $peserta)
                                        <div class="py-2 flex items-center justify-between text-xs">
                                            <div>
                                                <span class="font-bold text-zinc-900 dark:text-white block">{{ $peserta->nama_lengkap }}</span>
                                                <span class="text-[10px] text-zinc-500 font-mono">NIK: {{ $peserta->nik }} • Pasangan: {{ $peserta->nama_suami_istri }}</span>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold {{ $peserta->status === 'terverifikasi' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300' : 'bg-amber-50 text-amber-700 dark:bg-amber-950/50 dark:text-amber-300' }}">
                                                    {{ ucfirst($peserta->status) }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 text-xs text-zinc-500">
                                    Belum ada data peserta terdaftar di desa ini.
                                </div>
                            @endif
                        </div>
                    </div>
                </flux:card>
            @endif

        </div>
    </div>
</div>

<!-- Alpine.js GIS Mapping Script -->
<script>
    function gisSebaranApp(config) {
        return {
            map: null,
            markers: {},
            polygons: {},
            centerCoords: [-4.1333, 121.6167],
            allWilayahs: config.allWilayahs || [],
            selectedId: config.selectedId,
            viewMode: config.viewMode,

            init() {
                this.$nextTick(() => {
                    this.initLeafletMap();
                });

                // Listen for Livewire selection changes
                this.$watch('selectedId', (newId) => {
                    if (newId) {
                        const target = this.allWilayahs.find(w => w.id === newId);
                        if (target && this.map) {
                            this.flyToWilayah(target.id, target.lat, target.lng);
                        }
                    }
                });

                // Re-invalidate map size when view mode switches
                this.$watch('viewMode', (mode) => {
                    if (mode === 'map' && this.map) {
                        setTimeout(() => {
                            this.map.invalidateSize();
                        }, 200);
                    }
                });
            },

            initLeafletMap() {
                const mapEl = document.getElementById('leaflet-map');
                if (!mapEl || this.map) return;

                // Create Map centered in Kecamatan Wundulako
                this.map = L.map('leaflet-map', {
                    center: this.centerCoords,
                    zoom: 13,
                    zoomControl: false
                });

                // Add zoom control at top right
                L.control.zoom({ position: 'topright' }).addTo(this.map);

                // CartoDB Positron Tile Layer (Clean modern styling)
                const isDark = document.documentElement.classList.contains('dark');
                const tileUrl = isDark 
                    ? 'https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png'
                    : 'https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png';

                L.tileLayer(tileUrl, {
                    attribution: '&copy; <a href="https://carto.com/">CARTO</a> &copy; OpenStreetMap',
                    maxZoom: 18
                }).addTo(this.map);

                // Add Puskesmas Wundulako Primary Landmark
                const puskesmasIcon = L.divIcon({
                    className: 'custom-puskesmas-marker',
                    html: `<div class="size-8 rounded-full bg-rose-600 border-2 border-white text-white flex items-center justify-center font-bold text-sm shadow-lg pulse-marker-puskesmas">
                                🏥
                           </div>`,
                    iconSize: [32, 32],
                    iconAnchor: [16, 16]
                });

                L.marker([-4.1385, 121.6178], { icon: puskesmasIcon })
                    .addTo(this.map)
                    .bindPopup(`
                        <div class="p-2 text-center">
                            <span class="inline-block px-2 py-0.5 rounded bg-rose-100 text-rose-700 text-[10px] font-bold">FASKES UTAMA</span>
                            <h4 class="font-bold text-sm text-zinc-900 mt-1">UPTD Puskesmas Wundulako</h4>
                            <p class="text-xs text-zinc-600 mt-0.5">Pusat Pelayanan & Distribusi Alokon KB</p>
                        </div>
                    `, { className: 'custom-popup' });

                // Render each Village Marker and Polygon
                this.allWilayahs.forEach(w => {
                    const pulseClass = w.density_tier === 'high' ? 'pulse-marker-high' : 'pulse-marker-med';
                    const markerColor = w.hex_color || '#2563eb';

                    // Custom Circle Icon with participant count
                    const customIcon = L.divIcon({
                        className: 'custom-village-marker',
                        html: `<div class="flex items-center justify-center size-9 rounded-full text-white font-extrabold text-xs shadow-lg border-2 border-white ${pulseClass}" style="background-color: ${markerColor}">
                                    ${w.peserta_kbs_count}
                               </div>`,
                        iconSize: [36, 36],
                        iconAnchor: [18, 18]
                    });

                    const marker = L.marker([w.lat, w.lng], { icon: customIcon })
                        .addTo(this.map)
                        .bindPopup(`
                            <div class="p-3 text-left space-y-1.5 min-w-[200px]">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-black text-sm text-zinc-900">${w.nama_desa_kelurahan}</span>
                                    <span class="px-1.5 py-0.5 rounded text-[10px] font-bold text-white" style="background-color: ${markerColor}">${w.code}</span>
                                </div>
                                <div class="text-xs text-zinc-600">
                                    Faskes: <b>${w.faskes}</b>
                                </div>
                                <div class="flex items-baseline gap-1.5 pt-1 border-t border-zinc-200">
                                    <span class="text-base font-black text-blue-600">${w.peserta_kbs_count}</span>
                                    <span class="text-xs text-zinc-500">Akseptor (${w.persentase}%)</span>
                                </div>
                            </div>
                        `, { className: 'custom-popup' });

                    marker.on('click', () => {
                        this.$wire.selectWilayah(w.id);
                    });

                    this.markers[w.id] = marker;

                    // Add Polygon if available
                    if (w.polygon && w.polygon.length > 0) {
                        const poly = L.polygon(w.polygon, {
                            color: markerColor,
                            fillColor: markerColor,
                            fillOpacity: 0.25,
                            weight: 2
                        }).addTo(this.map);

                        poly.on('click', () => {
                            this.$wire.selectWilayah(w.id);
                            marker.openPopup();
                        });

                        this.polygons[w.id] = poly;
                    }
                });
            },

            flyToWilayah(id, lat, lng) {
                if (this.map) {
                    this.map.flyTo([lat, lng], 15, { duration: 1.2 });
                    if (this.markers[id]) {
                        setTimeout(() => {
                            this.markers[id].openPopup();
                        }, 1200);
                    }
                }
            },

            recenterMap() {
                if (this.map) {
                    this.map.flyTo(this.centerCoords, 13, { duration: 1 });
                    this.$wire.selectWilayah(null);
                }
            }
        };
    }
</script>
