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
                <flux:heading size="lg">📊 Pelayanan per Bulan ({{ now()->year }})</flux:heading>
            </div>

            <!-- CSS Bar Chart (Clean & Premium without external JS first) -->
            <div class="mt-6 flex h-48 items-end justify-around gap-2 px-2">
                @foreach($chartData as $index => $total)
                    <div class="group flex flex-1 flex-col items-center gap-2">
                        <div class="relative w-full flex justify-center items-end h-36">
                            <!-- Tooltip on hover -->
                            <div class="absolute -top-6 hidden rounded bg-zinc-800 px-1.5 py-0.5 text-3xs text-white group-hover:block dark:bg-zinc-700">
                                {{ $total }}
                            </div>
                            
                            @php
                                $maxTotal = max(1, ...$chartData);
                                $heightPercentage = ($total / $maxTotal) * 100;
                                $isCurrentMonth = ($index + 1) === now()->month;
                                $barColor = $isCurrentMonth ? 'bg-gradient-to-t from-cyan-500 to-cyan-400' : 'bg-gradient-to-t from-blue-600 to-blue-500';
                            @endphp
                            <div class="w-full max-w-[32px] rounded-t-md {{ $barColor }} transition-all duration-500 hover:opacity-80" 
                                 style="height: {{ max(4, $heightPercentage) }}%">
                            </div>
                        </div>
                        <span class="text-2xs font-semibold text-zinc-500 dark:text-zinc-400">{{ $chartLabels[$index] }}</span>
                    </div>
                @endforeach
            </div>
        </flux:card>

        <!-- Sebaran Wilayah -->
        <flux:card>
            <div class="flex items-center justify-between border-b border-zinc-200 pb-3 dark:border-zinc-700">
                <flux:heading size="lg">🗺️ Peserta per Wilayah</flux:heading>
                <flux:button size="sm" variant="outline" href="{{ route('peta-sebaran.index') }}" wire:navigate>Lihat Peta</flux:button>
            </div>

            <div class="mt-4 divide-y divide-zinc-100 dark:divide-zinc-850">
                @forelse($wilayahRank as $index => $wilayah)
                    <div class="flex items-center gap-3 py-2.5">
                        <div class="flex size-7 items-center justify-center rounded-lg text-xs font-bold 
                            {{ $index === 0 ? 'bg-blue-50 text-blue-600 dark:bg-blue-950 dark:text-blue-400' : '' }}
                            {{ $index === 1 ? 'bg-emerald-50 text-emerald-600 dark:bg-emerald-950 dark:text-emerald-400' : '' }}
                            {{ $index === 2 ? 'bg-amber-50 text-amber-600 dark:bg-amber-950 dark:text-amber-400' : '' }}
                            {{ $index > 2 ? 'bg-zinc-50 text-zinc-500 dark:bg-zinc-800 dark:text-zinc-400' : '' }}
                        ">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1">
                            <flux:heading size="sm" class="font-semibold">{{ $wilayah->nama_desa_kelurahan }}</flux:heading>
                            <flux:text size="xs">{{ $wilayah->persentase }}% dari total peserta</flux:text>
                        </div>
                        <div class="text-right">
                            <span class="font-extrabold text-zinc-900 dark:text-white">{{ $wilayah->peserta_kbs_count }}</span>
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
