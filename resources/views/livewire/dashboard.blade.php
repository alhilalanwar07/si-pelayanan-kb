<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Dashboard</flux:heading>
            <flux:text size="sm">Ringkasan data pelayanan dan inventaris terkini</flux:text>
        </div>
        <div class="text-right">
            <flux:heading size="md" level="2" class="font-semibold">{{ now()->translatedFormat('l, j F Y') }}</flux:heading>
            <flux:text size="xs">Semester {{ now()->month <= 6 ? '1' : '2' }} — Tahun {{ now()->year }}</flux:text>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Card 1: Total Peserta KB -->
        <flux:card class="relative overflow-hidden border-t-4 border-t-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <flux:text size="xs" class="font-semibold uppercase tracking-wider text-zinc-500">Total Peserta KB</flux:text>
                    <flux:heading size="xl" class="mt-2 text-blue-600 dark:text-blue-400 font-extrabold">{{ number_format($totalPeserta, 0, ',', '.') }}</flux:heading>
                </div>
                <div class="rounded-lg bg-blue-50 p-3 text-blue-500 dark:bg-blue-950/50">
                    <flux:icon name="users" class="size-6" />
                </div>
            </div>
            <div class="mt-4 flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400 font-medium">
                <flux:icon name="arrow-trending-up" class="size-4" />
                <span>+{{ $pesertaBulanIni }} peserta baru bulan ini</span>
            </div>
        </flux:card>

        <!-- Card 2: Pelayanan Bulan Ini -->
        <flux:card class="relative overflow-hidden border-t-4 border-t-emerald-500">
            <div class="flex items-center justify-between">
                <div>
                    <flux:text size="xs" class="font-semibold uppercase tracking-wider text-zinc-500">Pelayanan Bulan Ini</flux:text>
                    <flux:heading size="xl" class="mt-2 text-emerald-600 dark:text-emerald-400 font-extrabold">{{ $pelayananBulanIni }}</flux:heading>
                </div>
                <div class="rounded-lg bg-emerald-50 p-3 text-emerald-500 dark:bg-emerald-950/50">
                    <flux:icon name="check-circle" class="size-6" />
                </div>
            </div>
            <div class="mt-4 text-xs text-zinc-500">
                <span>Periode {{ now()->translatedFormat('F Y') }}</span>
            </div>
        </flux:card>

        <!-- Card 3: Menunggu Verifikasi -->
        <flux:card class="relative overflow-hidden border-t-4 border-t-amber-500">
            <div class="flex items-center justify-between">
                <div>
                    <flux:text size="xs" class="font-semibold uppercase tracking-wider text-zinc-500">Menunggu Verifikasi</flux:text>
                    <flux:heading size="xl" class="mt-2 text-amber-600 dark:text-amber-400 font-extrabold">{{ $menungguVerifikasi }}</flux:heading>
                </div>
                <div class="rounded-lg bg-amber-50 p-3 text-amber-500 dark:bg-amber-950/50">
                    <flux:icon name="clipboard-document-check" class="size-6" />
                </div>
            </div>
            <div class="mt-4 text-xs {{ $menungguVerifikasi > 0 ? 'text-amber-600 dark:text-amber-400 font-medium' : 'text-zinc-500' }}">
                <span>{{ $menungguVerifikasi > 0 ? 'Perlu tindakan verifikasi' : 'Semua peserta terverifikasi' }}</span>
            </div>
        </flux:card>

        <!-- Card 4: Peringatan Stok -->
        <flux:card class="relative overflow-hidden border-t-4 border-t-rose-500">
            <div class="flex items-center justify-between">
                <div>
                    <flux:text size="xs" class="font-semibold uppercase tracking-wider text-zinc-500">Peringatan Stok</flux:text>
                    <flux:heading size="xl" class="mt-2 text-rose-600 dark:text-rose-400 font-extrabold">{{ $peringatanStok }}</flux:heading>
                </div>
                <div class="rounded-lg bg-rose-50 p-3 text-rose-500 dark:bg-rose-950/50">
                    <flux:icon name="exclamation-triangle" class="size-6" />
                </div>
            </div>
            <div class="mt-4 text-xs {{ $peringatanStok > 0 ? 'text-rose-600 dark:text-rose-400 font-medium' : 'text-zinc-500' }}">
                <span>{{ $peringatanStok > 0 ? 'Alokon di bawah threshold stok' : 'Stok alokon aman' }}</span>
            </div>
        </flux:card>
    </div>

    <!-- Content Grid (Recent Registrations & Alokon Stock) -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Registrasi Peserta Terbaru (Col span 2) -->
        <flux:card class="lg:col-span-2">
            <div class="flex items-center justify-between border-b border-zinc-200 pb-3 dark:border-zinc-700">
                <flux:heading size="lg">📋 Registrasi Peserta Terbaru</flux:heading>
                <div class="flex items-center gap-2">
                    <flux:button size="sm" variant="outline" href="{{ route('peserta-kb.index') }}" wire:navigate>Lihat Semua</flux:button>
                </div>
            </div>

            <div class="mt-4 overflow-x-auto">
                <flux:table>
                    <flux:table.columns>
                        <flux:table.column>Informasi Peserta</flux:table.column>
                        <flux:table.column>Wilayah</flux:table.column>
                        <flux:table.column>Tgl Daftar</flux:table.column>
                        <flux:table.column>Status</flux:table.column>
                        @if(auth()->user()->isAdmin())
                            <flux:table.column>Aksi</flux:table.column>
                        @endif
                    </flux:table.columns>

                    <flux:table.rows>
                        @forelse($pesertaTerbaru as $peserta)
                            <flux:table.row :key="$peserta->id">
                                <flux:table.cell>
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-zinc-900 dark:text-white">{{ $peserta->nama_lengkap }}</span>
                                        <span class="text-xs text-zinc-500 font-mono">NIK: {{ $peserta->nik }}</span>
                                    </div>
                                </flux:table.cell>
                                <flux:table.cell>{{ $peserta->wilayah->nama_desa_kelurahan }}</flux:table.cell>
                                <flux:table.cell>{{ $peserta->created_at->translatedFormat('d M Y') }}</flux:table.cell>
                                <flux:table.cell>
                                    @if($peserta->isTerverifikasi())
                                        <flux:badge color="green" size="sm">Terverifikasi</flux:badge>
                                    @else
                                        <flux:badge color="amber" size="sm">Menunggu</flux:badge>
                                    @endif
                                </flux:table.cell>
                                @if(auth()->user()->isAdmin())
                                    <flux:table.cell>
                                        @if(!$peserta->isTerverifikasi())
                                            <flux:button size="xs" variant="primary" wire:click="verifikasiPeserta({{ $peserta->id }})" wire:loading.attr="disabled">
                                                Verifikasi
                                            </flux:button>
                                        @else
                                            @if($peserta->nomor_hp)
                                                <flux:button size="xs" variant="outline" href="{{ $peserta->whatsapp_link }}" target="_blank" icon="chat-bubble-left-right" class="text-emerald-600 dark:text-emerald-400">
                                                    Kirim Jadwal (WA)
                                                </flux:button>
                                            @else
                                                <span class="text-xs text-zinc-400">Terverifikasi</span>
                                            @endif
                                        @endif
                                    </flux:table.cell>
                                @endif
                            </flux:table.row>
                        @empty
                            <flux:table.row>
                                <flux:table.cell colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="text-center text-zinc-500">
                                    Belum ada peserta terdaftar.
                                </flux:table.cell>
                            </flux:table.row>
                        @endforelse
                    </flux:table.rows>
                </flux:table>
            </div>
        </flux:card>

        <!-- Stok Alokon -->
        <flux:card>
            <div class="flex items-center justify-between border-b border-zinc-200 pb-3 dark:border-zinc-700">
                <flux:heading size="lg">📦 Stok Alokon</flux:heading>
                @if(auth()->user()->isAdmin())
                    <flux:button size="sm" variant="outline" href="{{ route('alokon.index') }}" wire:navigate>Detail</flux:button>
                @endif
            </div>

            <div class="mt-4 space-y-4">
                @forelse($alokons as $alokon)
                    <div class="flex flex-col space-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <flux:heading size="sm" class="font-semibold">{{ $alokon->nama_alokon }}</flux:heading>
                                <span class="text-xs text-zinc-500">{{ $alokon->instansi->nama_instansi }}</span>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold {{ $alokon->stok < 5 ? 'text-red-500' : ($alokon->stok < 10 ? 'text-amber-500' : 'text-emerald-500') }}">
                                    {{ $alokon->stok }}
                                </span>
                                <span class="text-xs text-zinc-400"> unit</span>
                            </div>
                        </div>
                        
                        <!-- Progress bar -->
                        @php
                            $percentage = min(100, max(0, ($alokon->stok / 150) * 100));
                            $colorClass = $alokon->stok < 5 ? 'bg-red-500' : ($alokon->stok < 10 ? 'bg-amber-500' : 'bg-emerald-500');
                        @endphp
                        <div class="h-2 w-full rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                            <div class="h-full rounded-full {{ $colorClass }}" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-zinc-500 py-4">
                        Belum ada data alokon.
                    </div>
                @endforelse
            </div>
        </flux:card>
    </div>

    <!-- Bottom Grid (Chart Pelayanan & Wilayah Rankings) -->
    <div class="grid gap-6 md:grid-cols-2">
        <!-- Chart Pelayanan -->
        <flux:card>
            <div class="flex items-center justify-between border-b border-zinc-200 pb-3 dark:border-zinc-700">
                <div>
                    <flux:heading size="lg">📊 Pelayanan per Bulan ({{ now()->year }})</flux:heading>
                    <flux:text size="xs">Total {{ array_sum($chartData) }} pelayanan terlaksana tahun ini</flux:text>
                </div>
                <flux:badge size="sm" color="blue">{{ array_sum($chartData) }} Total</flux:badge>
            </div>

            <!-- CSS Bar Chart -->
            <div class="mt-6 flex h-48 items-end justify-around gap-2 px-2 pb-2">
                @php
                    $maxTotal = max(1, ...$chartData);
                @endphp
                @foreach($chartData as $index => $total)
                    <div class="group relative flex flex-1 flex-col items-center gap-2">
                        <div class="relative w-full flex justify-center items-end h-36">
                            <!-- Tooltip on hover -->
                            <div class="absolute -top-7 z-10 hidden whitespace-nowrap rounded bg-zinc-800 px-2 py-0.5 text-[10px] font-medium text-white shadow-md group-hover:block dark:bg-zinc-700">
                                {{ $total }} pelayanan
                            </div>
                            
                            @php
                                $heightPercentage = $total > 0 ? ($total / $maxTotal) * 100 : 0;
                                $isCurrentMonth = ($index + 1) === now()->month;
                                $barColor = $isCurrentMonth 
                                    ? 'bg-gradient-to-t from-cyan-500 to-cyan-400 shadow-sm shadow-cyan-500/20' 
                                    : 'bg-gradient-to-t from-blue-600 to-blue-500';
                            @endphp

                            @if($total > 0)
                                <div class="w-full max-w-[28px] rounded-t-md {{ $barColor }} transition-all duration-300 hover:opacity-85" 
                                     style="height: {{ max(10, $heightPercentage) }}%">
                                </div>
                            @else
                                <div class="h-1.5 w-full max-w-[20px] rounded-full bg-zinc-200 dark:bg-zinc-700/60 transition-all duration-300 group-hover:bg-zinc-300 dark:group-hover:bg-zinc-600"></div>
                            @endif
                        </div>

                        <!-- Month label -->
                        <span class="text-xs font-semibold {{ $isCurrentMonth ? 'text-cyan-600 dark:text-cyan-400 font-bold' : 'text-zinc-500 dark:text-zinc-400' }}">
                            {{ $chartLabels[$index] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </flux:card>

        <!-- Sebaran Wilayah -->
        <flux:card>
            <div class="flex items-center justify-between border-b border-zinc-200 pb-3 dark:border-zinc-700">
                <div>
                    <flux:heading size="lg">🗺️ Peserta per Wilayah</flux:heading>
                    <flux:text size="xs">{{ $wilayahRank->count() }} desa/kelurahan terdaftar</flux:text>
                </div>
                <flux:button size="sm" variant="outline" href="{{ route('peta-sebaran.index') }}" wire:navigate>Lihat Peta</flux:button>
            </div>

            <div class="mt-4 divide-y divide-zinc-100 dark:divide-zinc-800 max-h-56 overflow-y-auto pr-1">
                @forelse($wilayahRank as $index => $wilayah)
                    <div class="flex items-center gap-3 py-2.5">
                        <div class="flex size-7 shrink-0 items-center justify-center rounded-lg text-xs font-bold 
                            {{ $index === 0 ? 'bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-400' : '' }}
                            {{ $index === 1 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' : '' }}
                            {{ $index === 2 ? 'bg-amber-50 text-amber-600 dark:bg-amber-950 dark:text-amber-400' : '' }}
                            {{ $index > 2 ? 'bg-zinc-100 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400' : '' }}
                        ">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between gap-2">
                                <flux:heading size="sm" class="font-semibold truncate">{{ $wilayah->nama_desa_kelurahan }}</flux:heading>
                                <span class="text-xs font-bold text-zinc-900 dark:text-white shrink-0">
                                    {{ $wilayah->peserta_kbs_count }} <span class="font-normal text-zinc-500">peserta</span>
                                </span>
                            </div>
                            
                            <!-- Progress bar percentage -->
                            <div class="mt-1.5 flex items-center gap-2">
                                <div class="h-1.5 w-full rounded-full bg-zinc-100 dark:bg-zinc-800 overflow-hidden">
                                    <div class="h-full rounded-full {{ $index === 0 ? 'bg-blue-500' : ($index === 1 ? 'bg-emerald-500' : ($index === 2 ? 'bg-amber-500' : 'bg-zinc-400 dark:bg-zinc-500')) }}" 
                                         style="width: {{ $wilayah->persentase }}%">
                                    </div>
                                </div>
                                <span class="text-3xs text-zinc-400 font-medium shrink-0">{{ $wilayah->persentase }}%</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-zinc-500 py-4">
                        Belum ada data wilayah.
                    </div>
                @endforelse
            </div>
        </flux:card>
    </div>
</div>
