<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Peta Sebaran Peserta KB</flux:heading>
            <flux:text size="sm">Visualisasi geografis sebaran peserta KB di Kecamatan Wundulako</flux:text>
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Sidebar: Legend & Details -->
        <flux:card class="space-y-6">
            <div>
                <flux:heading size="lg">Keterangan Sebaran</flux:heading>
                <flux:text size="sm">Gradasi warna menunjukkan kerapatan jumlah peserta KB aktif</flux:text>
            </div>

            <!-- Legend Items -->
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="size-4 rounded bg-blue-600"></div>
                    <span class="text-sm font-semibold">Tinggi (4+ peserta)</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="size-4 rounded bg-blue-400"></div>
                    <span class="text-sm font-semibold">Sedang-Tinggi (3 peserta)</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="size-4 rounded bg-blue-300"></div>
                    <span class="text-sm font-semibold">Sedang (2 peserta)</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="size-4 rounded bg-blue-100 dark:bg-blue-200"></div>
                    <span class="text-sm font-semibold">Rendah (< 2 peserta)</span>
                </div>
            </div>

            <flux:separator />

            <!-- Wilayah Ranking Table -->
            <div class="space-y-4">
                <flux:heading size="md">Tabel Kerapatan Wilayah</flux:heading>
                <div class="space-y-3">
                    @foreach($wilayahs as $w)
                        <div class="flex items-center justify-between text-sm">
                            <div class="flex items-center gap-2">
                                <div class="size-3 rounded-full {{ $w->bg_class }}"></div>
                                <span class="font-medium text-zinc-950 dark:text-white">{{ $w->nama_desa_kelurahan }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold">{{ $w->peserta_kbs_count }}</span>
                                <span class="text-xs text-zinc-500"> ({{ $w->persentase }}%)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </flux:card>

        <!-- SVG Map Container (Col Span 2) -->
        <flux:card class="lg:col-span-2 flex flex-col items-center justify-center p-6 space-y-4 min-h-[400px]">
            <flux:heading size="lg">Peta Choropleth Kecamatan Wundulako</flux:heading>
            
            <!-- Beautiful Interactive SVG Map -->
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 600 400" class="w-full max-w-[500px] h-auto drop-shadow-md">
                <!-- Outer Border / Background -->
                <rect x="0" y="0" width="600" height="400" rx="15" fill="none" class="stroke-zinc-200 dark:stroke-zinc-800" stroke-width="1" />

                <!-- Kelurahan Wundulako (Center) -->
                @php $w0 = $wilayahs->firstWhere('nama_desa_kelurahan', 'Kelurahan Wundulako'); @endphp
                <g class="cursor-pointer group">
                    <polygon points="200,150 350,130 380,220 280,250 180,200" 
                             class="{{ $w0 ? $w0->color : 'fill-zinc-100' }} stroke-white dark:stroke-zinc-900 transition-all duration-300" stroke-width="3" />
                    <text x="270" y="190" class="fill-white dark:fill-zinc-900 font-bold text-xs pointer-events-none text-center">Wundulako</text>
                    <!-- Tooltip simulated by SVG title -->
                    <title>Kelurahan Wundulako: {{ $w0 ? $w0->peserta_kbs_count : 0 }} peserta ({{ $w0 ? $w0->persentase : 0 }}%)</title>
                </g>

                <!-- Desa Bende (North) -->
                @php $w1 = $wilayahs->firstWhere('nama_desa_kelurahan', 'Desa Bende'); @endphp
                <g class="cursor-pointer group">
                    <polygon points="150,50 320,30 350,130 200,150 120,110" 
                             class="{{ $w1 ? $w1->color : 'fill-zinc-100' }} stroke-white dark:stroke-zinc-900 transition-all duration-300" stroke-width="3" />
                    <text x="220" y="100" class="fill-white dark:fill-zinc-900 font-bold text-xs pointer-events-none">Desa Bende</text>
                    <title>Desa Bende: {{ $w1 ? $w1->peserta_kbs_count : 0 }} peserta ({{ $w1 ? $w1->persentase : 0 }}%)</title>
                </g>

                <!-- Desa Kowioha (East) -->
                @php $w2 = $wilayahs->firstWhere('nama_desa_kelurahan', 'Desa Kowioha'); @endphp
                <g class="cursor-pointer group">
                    <polygon points="350,130 480,100 520,200 380,220" 
                             class="{{ $w2 ? $w2->color : 'fill-zinc-100' }} stroke-white dark:stroke-zinc-900 transition-all duration-300" stroke-width="3" />
                    <text x="410" y="160" class="fill-white dark:fill-zinc-900 font-bold text-xs pointer-events-none">Desa Kowioha</text>
                    <title>Desa Kowioha: {{ $w2 ? $w2->peserta_kbs_count : 0 }} peserta ({{ $w2 ? $w2->persentase : 0 }}%)</title>
                </g>

                <!-- Desa Lamokuni (South) -->
                @php $w3 = $wilayahs->firstWhere('nama_desa_kelurahan', 'Desa Lamokuni'); @endphp
                <g class="cursor-pointer group">
                    <polygon points="280,250 380,220 420,340 300,350 240,300" 
                             class="{{ $w3 ? $w3->color : 'fill-zinc-100' }} stroke-white dark:stroke-zinc-900 transition-all duration-300" stroke-width="3" />
                    <text x="310" y="290" class="fill-white dark:fill-zinc-900 font-bold text-xs pointer-events-none">Lamokuni</text>
                    <title>Desa Lamokuni: {{ $w3 ? $w3->peserta_kbs_count : 0 }} peserta ({{ $w3 ? $w3->persentase : 0 }}%)</title>
                </g>

                <!-- Desa Watalara (West) -->
                @php $w4 = $wilayahs->firstWhere('nama_desa_kelurahan', 'Desa Watalara'); @endphp
                <g class="cursor-pointer group">
                    <polygon points="80,180 180,200 280,250 240,300 120,320 60,260" 
                             class="{{ $w4 ? $w4->color : 'fill-zinc-100' }} stroke-white dark:stroke-zinc-900 transition-all duration-300" stroke-width="3" />
                    <text x="130" y="260" class="fill-white dark:fill-zinc-900 font-bold text-xs pointer-events-none">Watalara</text>
                    <title>Desa Watalara: {{ $w4 ? $w4->peserta_kbs_count : 0 }} peserta ({{ $w4 ? $w4->persentase : 0 }}%)</title>
                </g>
            </svg>

            <span class="text-xs text-zinc-500 dark:text-zinc-400">Arahkan kursor pada peta untuk melihat detail per wilayah</span>
        </flux:card>
    </div>
</div>
