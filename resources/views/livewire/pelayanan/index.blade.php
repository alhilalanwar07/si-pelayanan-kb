<div class="flex-1 space-y-6 p-4 sm:p-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 pb-4 dark:border-zinc-700">
        <div>
            <div class="flex items-center gap-2">
                <flux:heading size="xl" level="1" class="font-black text-slate-900 dark:text-white">Pusat Pelayanan & Antrian KB</flux:heading>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">
                    Live System
                </span>
            </div>
            <flux:text size="sm" class="text-slate-500 dark:text-zinc-400 mt-0.5">
                Manajemen antrian pasien dari jadwal aktif dan pencatatan riwayat pelayanan kontrasepsi
            </flux:text>
        </div>
        @if(auth()->user()->isBidan() || auth()->user()->isAdmin())
            <flux:button variant="primary" icon="plus" href="{{ route('pelayanan.create') }}" wire:navigate class="rounded-xl font-bold shadow-md shadow-blue-600/20">
                Pelayanan Baru (Walk-in)
            </flux:button>
        @endif
    </div>

    <!-- Quick Stats Cards -->
    <div class="grid gap-4 grid-cols-2 lg:grid-cols-4">
        <!-- Stat 1: Total Antrian Sesi Ini -->
        <div class="rounded-2xl bg-white dark:bg-zinc-900 p-4 sm:p-5 border border-slate-200/80 dark:border-zinc-800 shadow-sm flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 flex items-center justify-center shrink-0">
                <flux:icon name="ticket" class="size-6" />
            </div>
            <div class="min-w-0">
                <div class="text-2xs sm:text-xs font-semibold text-slate-500 dark:text-zinc-400 truncate">Total Antrian Sesi</div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $totalAntrianHariIni }}</div>
            </div>
        </div>

        <!-- Stat 2: Pasien Menunggu -->
        <div class="rounded-2xl bg-white dark:bg-zinc-900 p-4 sm:p-5 border border-slate-200/80 dark:border-zinc-800 shadow-sm flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0">
                <flux:icon name="clock" class="size-6" />
            </div>
            <div class="min-w-0">
                <div class="text-2xs sm:text-xs font-semibold text-slate-500 dark:text-zinc-400 truncate">Menunggu Dilayani</div>
                <div class="text-xl sm:text-2xl font-black text-amber-600 dark:text-amber-400 tracking-tight">{{ $antrianMenunggu }}</div>
            </div>
        </div>

        <!-- Stat 3: Pasien Selesai / Hadir -->
        <div class="rounded-2xl bg-white dark:bg-zinc-900 p-4 sm:p-5 border border-slate-200/80 dark:border-zinc-800 shadow-sm flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shrink-0">
                <flux:icon name="check-badge" class="size-6" />
            </div>
            <div class="min-w-0">
                <div class="text-2xs sm:text-xs font-semibold text-slate-500 dark:text-zinc-400 truncate">Selesai / Hadir</div>
                <div class="text-xl sm:text-2xl font-black text-emerald-600 dark:text-emerald-400 tracking-tight">{{ $antrianHadir }}</div>
            </div>
        </div>

        <!-- Stat 4: Pelayanan Bulan Ini -->
        <div class="rounded-2xl bg-white dark:bg-zinc-900 p-4 sm:p-5 border border-slate-200/80 dark:border-zinc-800 shadow-sm flex items-center gap-3.5">
            <div class="size-11 sm:size-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                <flux:icon name="clipboard-document-check" class="size-6" />
            </div>
            <div class="min-w-0">
                <div class="text-2xs sm:text-xs font-semibold text-slate-500 dark:text-zinc-400 truncate">Pelayanan Bulan Ini</div>
                <div class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">{{ $totalPelayananBulanIni }}</div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-zinc-800">
        <button wire:click="switchTab('antrian')"
            class="flex items-center gap-2 px-4 py-3 font-bold text-sm border-b-2 transition-all cursor-pointer {{ $tab === 'antrian' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-500' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}">
            <flux:icon name="ticket" class="size-4.5" />
            <span>Antrian Pasien</span>
            @if($antrianMenunggu > 0)
                <span class="px-2 py-0.5 rounded-full text-2xs font-extrabold bg-amber-500 text-white animate-pulse">
                    {{ $antrianMenunggu }}
                </span>
            @endif
        </button>

        <button wire:click="switchTab('riwayat')"
            class="flex items-center gap-2 px-4 py-3 font-bold text-sm border-b-2 transition-all cursor-pointer {{ $tab === 'riwayat' ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-500' : 'border-transparent text-slate-500 hover:text-slate-700 dark:text-zinc-400 dark:hover:text-zinc-200' }}">
            <flux:icon name="clock" class="size-4.5" />
            <span>Riwayat Rekam Pelayanan</span>
        </button>
    </div>

    {{-- ==================== TAB 1: ANTRIAN PASIEN ==================== --}}
    @if($tab === 'antrian')
        <div class="space-y-4">
            <!-- Schedule Selector Card -->
            <flux:card class="p-4 sm:p-5">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <div class="text-xs font-bold text-slate-700 dark:text-zinc-300 shrink-0">
                            Pilih Sesi Jadwal:
                        </div>
                        <flux:select wire:model.live="selectedJadwalId" class="rounded-xl text-xs sm:text-sm min-w-[280px]">
                            @forelse($activeJadwals as $aj)
                                <option value="{{ $aj->id }}">
                                    {{ $aj->tanggal->translatedFormat('d M Y') }} ({{ substr($aj->waktu_mulai, 0, 5) }} - {{ substr($aj->waktu_selesai, 0, 5) }}) {{ $aj->tanggal->isToday() ? '★ HARI INI' : '' }}
                                </option>
                            @empty
                                <option value="">Tidak ada jadwal aktif</option>
                            @endforelse
                        </flux:select>
                    </div>

                    <!-- Search within queue -->
                    <div class="w-full sm:w-72">
                        <flux:input type="text" placeholder="Cari nama pasien, NIK..." wire:model.live="searchAntrian" icon="magnifying-glass" class="rounded-xl text-xs sm:text-sm" />
                    </div>
                </div>

                @if($currentJadwal)
                    <div class="mt-3 pt-3 border-t border-slate-100 dark:border-zinc-800 flex flex-wrap items-center justify-between gap-2 text-xs text-slate-500 dark:text-zinc-400">
                        <div class="flex items-center gap-2">
                            <span class="font-bold text-slate-700 dark:text-zinc-300">
                                📅 {{ $currentJadwal->tanggal->translatedFormat('l, d F Y') }}
                            </span>
                            <span>•</span>
                            <span>⏰ {{ substr($currentJadwal->waktu_mulai, 0, 5) }} - {{ substr($currentJadwal->waktu_selesai, 0, 5) }} WITA</span>
                            @if($currentJadwal->keterangan)
                                <span>•</span>
                                <span class="italic">{{ $currentJadwal->keterangan }}</span>
                            @endif
                        </div>
                        <div class="font-semibold">
                            Kapasitas: <strong class="text-slate-800 dark:text-white">{{ $currentJadwal->antrians()->count() }} / {{ $currentJadwal->kuota }}</strong>
                        </div>
                    </div>
                @endif
            </flux:card>

            <!-- Queue Patients List -->
            <flux:card class="p-0 overflow-hidden">
                <div class="overflow-x-auto">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column class="w-24 text-center">No. Antrian</flux:table.column>
                            <flux:table.column>Identitas Pasien</flux:table.column>
                            <flux:table.column>Domisili & Asuransi</flux:table.column>
                            <flux:table.column>Status Kehadiran</flux:table.column>
                            <flux:table.column class="text-right">Tindakan Petugas</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @forelse($antrians as $antrian)
                                <flux:table.row :key="$antrian->id" class="{{ $antrian->status === 'hadir' ? 'bg-emerald-50/40 dark:bg-emerald-950/20' : '' }}">
                                    <!-- Nomor Antrian -->
                                    <flux:table.cell class="text-center font-black">
                                        <span class="inline-flex size-10 items-center justify-center rounded-2xl {{ $antrian->status === 'hadir' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300' : 'bg-blue-600 text-white shadow-md shadow-blue-500/20' }} text-sm">
                                            {{ str_pad($antrian->nomor_antrian, 3, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </flux:table.cell>

                                    <!-- Identitas Pasien -->
                                    <flux:table.cell>
                                        <div class="space-y-0.5">
                                            <div class="font-bold text-slate-900 dark:text-white text-sm">
                                                {{ $antrian->pesertaKb->nama_lengkap }}
                                            </div>
                                            <div class="text-xs text-slate-500 dark:text-zinc-400 font-mono">
                                                NIK: {{ $antrian->pesertaKb->nik }} • HP: {{ $antrian->pesertaKb->nomor_hp ?? '-' }}
                                            </div>
                                        </div>
                                    </flux:table.cell>

                                    <!-- Domisili & Asuransi -->
                                    <flux:table.cell>
                                        <div class="text-xs space-y-0.5">
                                            <div class="font-semibold text-slate-700 dark:text-zinc-300">
                                                {{ $antrian->pesertaKb->wilayah->nama_desa_kelurahan ?? '-' }}
                                            </div>
                                            <div class="uppercase text-slate-400 font-bold text-2xs">
                                                {{ $antrian->pesertaKb->penggunaan_asuransi ?? 'Umum' }}
                                            </div>
                                        </div>
                                    </flux:table.cell>

                                    <!-- Status -->
                                    <flux:table.cell>
                                        @if($antrian->status === 'hadir')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200">
                                                <flux:icon name="check-circle" class="size-3.5 text-emerald-600" />
                                                Selesai / Hadir
                                            </span>
                                        @elseif($antrian->status === 'tidak_hadir')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-400 border border-rose-200">
                                                <flux:icon name="x-circle" class="size-3.5 text-rose-600" />
                                                Tidak Hadir
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-200">
                                                <span class="size-2 rounded-full bg-amber-500 animate-pulse"></span>
                                                Menunggu Dipanggil
                                            </span>
                                        @endif
                                    </flux:table.cell>

                                    <!-- Action Buttons -->
                                    <flux:table.cell class="text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            @if($antrian->status !== 'hadir')
                                                <flux:button size="sm" variant="primary" wire:click="layaniPeserta({{ $antrian->pesertaKb->id }}, {{ $antrian->id }})"
                                                    class="rounded-xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 text-xs shadow-md">
                                                    <flux:icon name="sparkles" class="size-3.5 mr-1" />
                                                    Layani Pasien
                                                </flux:button>
                                                <flux:button size="sm" variant="outline" wire:click="tandaiTidakHadir({{ $antrian->id }})" class="rounded-xl text-xs text-rose-600 hover:bg-rose-50">
                                                    Absen
                                                </flux:button>
                                            @else
                                                <flux:button size="sm" variant="outline" href="{{ route('peserta-kb.show', $antrian->pesertaKb->id) }}" wire:navigate class="rounded-xl text-xs">
                                                    Profil
                                                </flux:button>
                                            @endif
                                        </div>
                                    </flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="5" class="text-center py-12 text-slate-400 dark:text-zinc-500 space-y-2">
                                        <flux:icon name="ticket" class="size-10 mx-auto text-slate-300 dark:text-zinc-700" />
                                        <div class="text-sm font-semibold">Belum ada pasien yang mendaftar pada sesi jadwal ini.</div>
                                        <div class="text-xs">Nomor antrian akan muncul otomatis saat peserta melakukan registrasi mandiri.</div>
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </div>
            </flux:card>
        </div>
    @endif

    {{-- ==================== TAB 2: RIWAYAT PELAYANAN ==================== --}}
    @if($tab === 'riwayat')
        <div class="space-y-4">
            <!-- Search & Filter Card -->
            <flux:card class="p-4 sm:p-5 space-y-4">
                <div class="grid gap-4 grid-cols-1 sm:grid-cols-4">
                    <!-- Search -->
                    <flux:field class="sm:col-span-2">
                        <flux:label>Pencarian Peserta</flux:label>
                        <flux:input type="text" placeholder="Cari nama peserta, NIK..." wire:model.live="search" icon="magnifying-glass" class="rounded-xl" />
                    </flux:field>

                    <!-- Filter Alokon -->
                    <flux:field>
                        <flux:label>Jenis Alokon</flux:label>
                        <flux:select wire:model.live="filterAlokon" class="rounded-xl">
                            <option value="">Semua Alokon</option>
                            @foreach($alokons as $alokon)
                                <option value="{{ $alokon->id }}">{{ $alokon->nama_alokon }}</option>
                            @endforeach
                        </flux:select>
                    </flux:field>

                    <!-- Filter Bulan -->
                    <flux:field>
                        <flux:label>Bulan</flux:label>
                        <flux:select wire:model.live="filterBulan" class="rounded-xl">
                            <option value="">Semua Bulan</option>
                            @for($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}">{{ Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                            @endfor
                        </flux:select>
                    </flux:field>
                </div>
            </flux:card>

            <!-- Table Riwayat -->
            <flux:card class="p-0 overflow-hidden">
                <div class="overflow-x-auto">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Tanggal</flux:table.column>
                            <flux:table.column>Nama Peserta</flux:table.column>
                            <flux:table.column>Alat Kontrasepsi</flux:table.column>
                            <flux:table.column>Tindakan (Consent)</flux:table.column>
                            <flux:table.column>Faskes</flux:table.column>
                            <flux:table.column class="text-right">Aksi</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @forelse($pelayanans as $pelayanan)
                                <flux:table.row :key="$pelayanan->id">
                                    <!-- Tanggal -->
                                    <flux:table.cell class="font-bold text-slate-800 dark:text-zinc-200">
                                        {{ $pelayanan->tanggal_pelayanan->translatedFormat('d M Y') }}
                                    </flux:table.cell>

                                    <!-- Peserta -->
                                    <flux:table.cell>
                                        <div class="flex flex-col">
                                            <span class="font-bold text-slate-900 dark:text-white">{{ $pelayanan->pesertaKb->nama_lengkap }}</span>
                                            <span class="text-xs text-slate-500 font-mono">NIK: {{ $pelayanan->pesertaKb->nik }}</span>
                                        </div>
                                    </flux:table.cell>

                                    <!-- Alokon -->
                                    <flux:table.cell>
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-50 text-blue-800 dark:bg-blue-950 dark:text-blue-300">
                                            {{ $pelayanan->alokon->nama_alokon }}
                                        </span>
                                    </flux:table.cell>

                                    <!-- Tindakan / Consent -->
                                    <flux:table.cell>
                                        @if($pelayanan->skriningMedis && $pelayanan->skriningMedis->informedConsent)
                                            <span class="capitalize font-semibold text-slate-800 dark:text-zinc-200">
                                                {{ $pelayanan->skriningMedis->informedConsent->jenis_tindakan_medis }}
                                            </span>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </flux:table.cell>

                                    <!-- Faskes -->
                                    <flux:table.cell>
                                        <span class="text-xs text-slate-600 dark:text-zinc-400">{{ $pelayanan->alokon->instansi->nama_instansi }}</span>
                                    </flux:table.cell>

                                    <!-- Aksi -->
                                    <flux:table.cell class="text-right">
                                        <div class="flex items-center justify-end gap-1.5">
                                            <flux:button size="xs" variant="outline" href="{{ route('pelayanan.show', $pelayanan->id) }}" wire:navigate class="rounded-lg">
                                                Detail
                                            </flux:button>
                                            <flux:button size="xs" variant="ghost" icon="printer" href="{{ route('pelayanan.cetak', $pelayanan->id) }}" target="_blank" class="rounded-lg text-slate-500" />
                                        </div>
                                    </flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="6" class="text-center text-slate-500 py-8">
                                        Belum ada riwayat pelayanan KB yang tercatat.
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </div>

                <div class="p-4 border-t border-slate-100 dark:border-zinc-800">
                    {{ $pelayanans->links() }}
                </div>
            </flux:card>
        </div>
    @endif
</div>
