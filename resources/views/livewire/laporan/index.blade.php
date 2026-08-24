<div class="flex-1 space-y-6 p-6 print:p-0 print:m-0 print:bg-white print:text-black">
    <!-- Header (Hidden on Print) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-zinc-200 pb-4 dark:border-zinc-700 print:hidden">
        <div>
            <div class="flex items-center gap-2">
                <flux:heading size="xl" level="1">Laporan & Rekapitulasi</flux:heading>
                <flux:badge size="sm" color="blue" class="font-medium">SI-KB Wundulako</flux:badge>
            </div>
            <flux:text size="sm">Cetak rekapitulasi resmi pelayanan KB, data peserta terdaftar, dan inventaris alokon</flux:text>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <!-- Cetak Laporan (Download PDF) Button -->
            <flux:button variant="outline" icon="printer" wire:click="downloadPdf" wire:loading.attr="disabled" class="hover:bg-zinc-100 dark:hover:bg-zinc-800 font-semibold shadow-xs">
                <span wire:loading.remove wire:target="downloadPdf">Cetak Laporan</span>
                <span wire:loading wire:target="downloadPdf" class="flex items-center gap-1.5">
                    <svg class="animate-spin size-4 text-zinc-600 dark:text-zinc-300" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyiapkan PDF...
                </span>
            </flux:button>
            
            <!-- Export CSV Button -->
            <flux:button variant="primary" icon="arrow-down-tray" wire:click="exportCsv" wire:loading.attr="disabled" class="bg-blue-600 hover:bg-blue-700 font-semibold text-white shadow-xs">
                <span wire:loading.remove wire:target="exportCsv">Export CSV</span>
                <span wire:loading wire:target="exportCsv" class="flex items-center gap-1.5">
                    <svg class="animate-spin size-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mengekspor...
                </span>
            </flux:button>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- ================== OFFICIAL KOP SURAT (PRINT ONLY) ====================== -->
    <!-- ========================================================================= -->
    <div class="hidden print:block mb-6">
        <!-- Kop Surat Header -->
        <div class="text-center pb-2 border-b-4 border-double border-black">
            <h3 class="text-xs font-bold uppercase tracking-wider text-black">PEMERINTAH KABUPATEN KOLAKA</h3>
            <h2 class="text-sm font-extrabold uppercase tracking-wide text-black mt-0.5">DINAS PENGENDALIAN PENDUDUK DAN KELUARGA BERENCANA</h2>
            <h1 class="text-base font-black uppercase text-black mt-0.5">UPTD PUSKESMAS KECAMATAN WUNDULAKO</h1>
            <p class="text-[10px] text-zinc-700 mt-1 leading-tight">
                Jl. Poros Kolaka - Pomalaa, Kec. Wundulako, Kab. Kolaka, Sulawesi Tenggara 93561<br>
                Email: pkm.wundulako@kolakakab.go.id | Aplikasi: Sistem Informasi Pelayanan KB
            </p>
        </div>

        <!-- Judul Laporan & Periode -->
        <div class="text-center mt-4 mb-4">
            <h2 class="text-sm font-black uppercase underline tracking-wider">
                @if($activeTab === 'pelayanan')
                    LAPORAN REKAPITULASI PELAYANAN KELUARGA BERENCANA (KB)
                @elseif($activeTab === 'peserta')
                    LAPORAN REKAPITULASI DATA PESERTA KB BARU & AKTIF
                @else
                    LAPORAN STATUS INVENTARIS ALAT & OBAT KONTRASEPSI (ALOKON)
                @endif
            </h2>
            <div class="flex justify-center items-center gap-6 mt-1.5 text-xs text-black">
                <div>
                    <b>Periode:</b> 
                    {{ Carbon\Carbon::parse($dariTanggal)->translatedFormat('d F Y') }} s/d {{ Carbon\Carbon::parse($sampaiTanggal)->translatedFormat('d F Y') }}
                </div>
                @if($wilayahId)
                    @php $wilayahSelected = $wilayahs->firstWhere('id', $wilayahId); @endphp
                    <div><b>Wilayah:</b> {{ $wilayahSelected?->nama_desa_kelurahan ?? '-' }}</div>
                @else
                    <div><b>Wilayah:</b> Semua Wilayah (Kec. Wundulako)</div>
                @endif
                @if($alokonId && $activeTab !== 'peserta')
                    @php $alokonSelected = $alokons->firstWhere('id', $alokonId); @endphp
                    <div><b>Alokon:</b> {{ $alokonSelected?->nama_alokon ?? '-' }}</div>
                @endif
            </div>
        </div>

        <!-- Ringkasan Statistik Cetak -->
        <div class="mb-4 border border-black p-2.5 text-xs">
            <div class="font-bold mb-1 uppercase text-[10px] tracking-wider text-zinc-800">Ringkasan Data Laporan:</div>
            <div class="grid grid-cols-4 gap-2 text-center text-xs">
                @if($activeTab === 'pelayanan')
                    <div class="border-r border-black last:border-r-0">
                        <span class="text-[10px] text-zinc-600 block">Total Pelayanan:</span>
                        <b class="text-sm">{{ $totalPelayanan }}</b> tindakan
                    </div>
                    <div class="border-r border-black last:border-r-0">
                        <span class="text-[10px] text-zinc-600 block">Peserta Dilayani:</span>
                        <b class="text-sm">{{ $totalPesertaDilayani }}</b> orang
                    </div>
                    <div class="border-r border-black last:border-r-0">
                        <span class="text-[10px] text-zinc-600 block">Alokon Diberikan:</span>
                        <b class="text-sm">{{ $totalAlokonTerdistribusi }}</b> unit
                    </div>
                    <div>
                        <span class="text-[10px] text-zinc-600 block">Cakupan Wilayah:</span>
                        <b class="text-sm">{{ $totalWilayahTercakup }}</b> desa/kel.
                    </div>
                @elseif($activeTab === 'peserta')
                    <div class="border-r border-black last:border-r-0">
                        <span class="text-[10px] text-zinc-600 block">Total Peserta:</span>
                        <b class="text-sm">{{ $totalPesertaTerdaftar }}</b> orang
                    </div>
                    <div class="border-r border-black last:border-r-0">
                        <span class="text-[10px] text-zinc-600 block">Terverifikasi:</span>
                        <b class="text-sm">{{ $totalPesertaTerverifikasi }}</b> orang
                    </div>
                    <div class="border-r border-black last:border-r-0">
                        <span class="text-[10px] text-zinc-600 block">Menunggu Verifikasi:</span>
                        <b class="text-sm">{{ $totalPesertaTerdaftar - $totalPesertaTerverifikasi }}</b> orang
                    </div>
                    <div>
                        <span class="text-[10px] text-zinc-600 block">Wilayah Asal:</span>
                        <b class="text-sm">{{ $pesertas->pluck('wilayah_id')->unique()->count() }}</b> desa/kel.
                    </div>
                @else
                    <div class="border-r border-black last:border-r-0">
                        <span class="text-[10px] text-zinc-600 block">Jenis Alokon:</span>
                        <b class="text-sm">{{ $inventory->count() }}</b> jenis
                    </div>
                    <div class="border-r border-black last:border-r-0">
                        <span class="text-[10px] text-zinc-600 block">Total Sisa Stok:</span>
                        <b class="text-sm">{{ $totalStokTersedia }}</b> unit
                    </div>
                    <div class="border-r border-black last:border-r-0">
                        <span class="text-[10px] text-zinc-600 block">Stok Kritis (<5):</span>
                        <b class="text-sm">{{ $inventory->where('stok', '<', 5)->count() }}</b> jenis
                    </div>
                    <div>
                        <span class="text-[10px] text-zinc-600 block">Stok Aman (>=10):</span>
                        <b class="text-sm">{{ $inventory->where('stok', '>=', 10)->count() }}</b> jenis
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- ========================================================================= -->
    <!-- ===================== SCREEN FILTERS & PRESETS ========================== -->
    <!-- ========================================================================= -->
    <flux:card class="space-y-4 print:hidden">
        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-zinc-100 pb-3 dark:border-zinc-800">
            <div class="flex items-center gap-1.5">
                <flux:icon name="funnel" class="size-4 text-zinc-500" />
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-700 dark:text-zinc-300">Filter Data Laporan</span>
            </div>
            
            <!-- Quick Date Presets -->
            <div class="flex flex-wrap items-center gap-1.5">
                <span class="text-xs text-zinc-500 mr-1">Preset:</span>
                <button type="button" wire:click="setFilterPreset('bulan_ini')" class="rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-medium hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition">
                    Bulan Ini
                </button>
                <button type="button" wire:click="setFilterPreset('bulan_lalu')" class="rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-medium hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition">
                    Bulan Lalu
                </button>
                <button type="button" wire:click="setFilterPreset('tahun_ini')" class="rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-medium hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition">
                    Tahun Ini
                </button>
                <button type="button" wire:click="setFilterPreset('semua')" class="rounded-md border border-zinc-200 bg-zinc-50 px-2.5 py-1 text-xs font-medium hover:bg-zinc-100 dark:border-zinc-700 dark:bg-zinc-800 dark:hover:bg-zinc-700 transition">
                    Semua
                </button>
                <button type="button" wire:click="resetFilters" class="rounded-md text-xs font-medium text-rose-600 hover:text-rose-700 dark:text-rose-400 px-2 py-1 transition">
                    Reset
                </button>
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Dari Tanggal -->
            <flux:field>
                <flux:label>Periode Mulai</flux:label>
                <flux:input type="date" wire:model.live="dariTanggal" />
            </flux:field>

            <!-- Sampai Tanggal -->
            <flux:field>
                <flux:label>Periode Akhir</flux:label>
                <flux:input type="date" wire:model.live="sampaiTanggal" />
            </flux:field>

            <!-- Wilayah -->
            <flux:field>
                <flux:label>Wilayah (Desa/Kel.)</flux:label>
                <flux:select wire:model.live="wilayahId">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahs as $w)
                        <option value="{{ $w->id }}">{{ $w->nama_desa_kelurahan }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <!-- Alokon -->
            <flux:field>
                <flux:label>Alat Kontrasepsi</flux:label>
                <flux:select wire:model.live="alokonId">
                    <option value="">Semua Alokon</option>
                    @foreach($alokons as $a)
                        <option value="{{ $a->id }}">{{ $a->nama_alokon }}</option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>
    </flux:card>

    <!-- ========================================================================= -->
    <!-- ==================== SCREEN STATS SUMMARY CARDS ========================= -->
    <!-- ========================================================================= -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 print:hidden">
        @if($activeTab === 'pelayanan')
            <flux:card class="border-l-4 border-l-blue-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Total Pelayanan</span>
                <div class="mt-2 text-2xl font-extrabold text-blue-600 dark:text-blue-400">{{ $totalPelayanan }}</div>
                <span class="mt-1 text-xs text-zinc-500">Tindakan dalam periode terpilih</span>
            </flux:card>
            <flux:card class="border-l-4 border-l-emerald-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Peserta Dilayani</span>
                <div class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $totalPesertaDilayani }}</div>
                <span class="mt-1 text-xs text-zinc-500">Peserta unik terlayani</span>
            </flux:card>
            <flux:card class="border-l-4 border-l-purple-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Alokon Terdistribusi</span>
                <div class="mt-2 text-2xl font-extrabold text-purple-600 dark:text-purple-400">{{ $totalAlokonTerdistribusi }}</div>
                <span class="mt-1 text-xs text-zinc-500">Unit alokon terpakai</span>
            </flux:card>
            <flux:card class="border-l-4 border-l-amber-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Wilayah Tercakup</span>
                <div class="mt-2 text-2xl font-extrabold text-amber-600 dark:text-amber-400">{{ $totalWilayahTercakup }}</div>
                <span class="mt-1 text-xs text-zinc-500">Desa/kelurahan terjangkau</span>
            </flux:card>
        @elseif($activeTab === 'peserta')
            <flux:card class="border-l-4 border-l-blue-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Total Peserta Baru</span>
                <div class="mt-2 text-2xl font-extrabold text-blue-600 dark:text-blue-400">{{ $totalPesertaTerdaftar }}</div>
                <span class="mt-1 text-xs text-zinc-500">Terdaftar dalam periode</span>
            </flux:card>
            <flux:card class="border-l-4 border-l-emerald-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Terverifikasi</span>
                <div class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $totalPesertaTerverifikasi }}</div>
                <span class="mt-1 text-xs text-zinc-500">Siap menerima pelayanan</span>
            </flux:card>
            <flux:card class="border-l-4 border-l-amber-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Menunggu Verifikasi</span>
                <div class="mt-2 text-2xl font-extrabold text-amber-600 dark:text-amber-400">{{ $totalPesertaTerdaftar - $totalPesertaTerverifikasi }}</div>
                <span class="mt-1 text-xs text-zinc-500">Perlu tindak lanjut admin</span>
            </flux:card>
            <flux:card class="border-l-4 border-l-cyan-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Cakupan Wilayah</span>
                <div class="mt-2 text-2xl font-extrabold text-cyan-600 dark:text-cyan-400">{{ $pesertas->pluck('wilayah_id')->unique()->count() }}</div>
                <span class="mt-1 text-xs text-zinc-500">Desa/kelurahan sebaran</span>
            </flux:card>
        @else
            <flux:card class="border-l-4 border-l-blue-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Jenis Alokon</span>
                <div class="mt-2 text-2xl font-extrabold text-blue-600 dark:text-blue-400">{{ $inventory->count() }}</div>
                <span class="mt-1 text-xs text-zinc-500">Varian kontrasepsi</span>
            </flux:card>
            <flux:card class="border-l-4 border-l-emerald-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Total Stok Tersedia</span>
                <div class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $totalStokTersedia }}</div>
                <span class="mt-1 text-xs text-zinc-500">Unit siap digunakan</span>
            </flux:card>
            <flux:card class="border-l-4 border-l-rose-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Stok Kritis (< 5)</span>
                <div class="mt-2 text-2xl font-extrabold text-rose-600 dark:text-rose-400">{{ $inventory->where('stok', '<', 5)->count() }}</div>
                <span class="mt-1 text-xs text-zinc-500">Perlu re-stok segera</span>
            </flux:card>
            <flux:card class="border-l-4 border-l-purple-500">
                <span class="text-xs font-semibold uppercase tracking-wider text-zinc-500">Total Distribusi</span>
                <div class="mt-2 text-2xl font-extrabold text-purple-600 dark:text-purple-400">{{ $inventory->sum('pelayanans_count') }}</div>
                <span class="mt-1 text-xs text-zinc-500">Unit keluar via pelayanan</span>
            </flux:card>
        @endif
    </div>

    <!-- ========================================================================= -->
    <!-- ===================== SCREEN NAVIGATION TABS ============================ -->
    <!-- ========================================================================= -->
    <div class="flex border-b border-zinc-200 dark:border-zinc-700 print:hidden">
        <button type="button" wire:click="setTab('pelayanan')" 
                class="flex items-center gap-2 px-5 py-3 border-b-2 font-semibold text-sm transition-all duration-150 {{ $activeTab === 'pelayanan' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
            <span>📊 Rekap Pelayanan</span>
            <span class="rounded-full bg-blue-100 dark:bg-blue-950 px-2 py-0.5 text-xs text-blue-700 dark:text-blue-300 font-bold">{{ $pelayanans->count() }}</span>
        </button>
        <button type="button" wire:click="setTab('peserta')" 
                class="flex items-center gap-2 px-5 py-3 border-b-2 font-semibold text-sm transition-all duration-150 {{ $activeTab === 'peserta' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
            <span>👥 Data Peserta KB</span>
            <span class="rounded-full bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 text-xs text-zinc-700 dark:text-zinc-300 font-bold">{{ $pesertas->count() }}</span>
        </button>
        <button type="button" wire:click="setTab('alokon')" 
                class="flex items-center gap-2 px-5 py-3 border-b-2 font-semibold text-sm transition-all duration-150 {{ $activeTab === 'alokon' ? 'border-blue-600 text-blue-600 dark:border-blue-400 dark:text-blue-400' : 'border-transparent text-zinc-500 hover:text-zinc-700 dark:hover:text-zinc-300' }}">
            <span>📦 Inventaris Alokon</span>
            <span class="rounded-full bg-zinc-100 dark:bg-zinc-800 px-2 py-0.5 text-xs text-zinc-700 dark:text-zinc-300 font-bold">{{ $inventory->count() }}</span>
        </button>
    </div>

    <!-- ========================================================================= -->
    <!-- ======================== MAIN DATA TABLE CARD =========================== -->
    <!-- ========================================================================= -->
    <flux:card class="print:border-none print:shadow-none print:p-0">
        
        <!-- ==================== TAB 1: REKAP PELAYANAN ==================== -->
        @if($activeTab === 'pelayanan')
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse print:text-[10px] print:border print:border-black">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 print:bg-zinc-100 print:border-b print:border-black">
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black text-center w-10">No</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Tanggal</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Peserta (NIK)</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Pasangan</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Wilayah</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Alokon / Faskes</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Tindakan</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Skrining</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Consent</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Kunj. Ulang</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Petugas</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 print:divide-zinc-400">
                        @forelse($pelayanans as $index => $pelayanan)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 print:hover:bg-transparent">
                                <td class="p-3 text-center text-xs text-zinc-500 print:p-1.5 print:border print:border-black">{{ $index + 1 }}</td>
                                <td class="p-3 font-semibold text-xs text-zinc-800 dark:text-zinc-200 print:p-1.5 print:border print:border-black print:text-black whitespace-nowrap">
                                    {{ $pelayanan->tanggal_pelayanan ? $pelayanan->tanggal_pelayanan->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="p-3 print:p-1.5 print:border print:border-black">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-sm text-zinc-900 dark:text-white print:text-black print:text-[10px]">
                                            {{ $pelayanan->pesertaKb?->nama_lengkap ?? '-' }}
                                        </span>
                                        <span class="text-xs text-zinc-500 font-mono print:text-black print:text-[9px]">
                                            NIK: {{ $pelayanan->pesertaKb?->nik ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-3 text-xs text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $pelayanan->pesertaKb?->nama_suami_istri ?? '-' }}
                                </td>
                                <td class="p-3 text-xs text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $pelayanan->pesertaKb?->wilayah?->nama_desa_kelurahan ?? '-' }}
                                </td>
                                <td class="p-3 print:p-1.5 print:border print:border-black">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-xs text-zinc-900 dark:text-white print:text-black print:text-[10px]">
                                            {{ $pelayanan->alokon?->nama_alokon ?? '-' }}
                                        </span>
                                        <span class="text-2xs text-zinc-500 print:text-[8px] print:text-zinc-700">
                                            {{ $pelayanan->alokon?->instansi?->nama_instansi ?? '-' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-3 text-xs capitalize text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $pelayanan->skriningMedis?->informedConsent?->jenis_tindakan_medis ?? '-' }}
                                </td>
                                <td class="p-3 print:p-1.5 print:border print:border-black text-center">
                                    @if($pelayanan->skriningMedis?->adaRiwayatPenyakit())
                                        <span class="inline-block rounded-sm bg-rose-100 dark:bg-rose-950/60 px-1.5 py-0.5 text-2xs font-bold text-rose-700 dark:text-rose-400 print:text-black print:bg-transparent">Beresiko</span>
                                    @else
                                        <span class="inline-block rounded-sm bg-emerald-100 dark:bg-emerald-950/60 px-1.5 py-0.5 text-2xs font-bold text-emerald-700 dark:text-emerald-400 print:text-black print:bg-transparent">Lolos</span>
                                    @endif
                                </td>
                                <td class="p-3 print:p-1.5 print:border print:border-black text-center">
                                    @if($pelayanan->skriningMedis?->informedConsent?->isLengkap())
                                        <span class="inline-block rounded-sm bg-emerald-100 dark:bg-emerald-950/60 px-1.5 py-0.5 text-2xs font-bold text-emerald-700 dark:text-emerald-400 print:text-black print:bg-transparent">Lengkap</span>
                                    @else
                                        <span class="inline-block rounded-sm bg-amber-100 dark:bg-amber-950/60 px-1.5 py-0.5 text-2xs font-bold text-amber-700 dark:text-amber-400 print:text-black print:bg-transparent">Belum</span>
                                    @endif
                                </td>
                                <td class="p-3 text-xs font-mono text-zinc-600 dark:text-zinc-400 print:p-1.5 print:border print:border-black print:text-black whitespace-nowrap">
                                    {{ $pelayanan->tanggal_kunjungan_ulang ? $pelayanan->tanggal_kunjungan_ulang->translatedFormat('d/m/Y') : '-' }}
                                </td>
                                <td class="p-3 text-xs text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $pelayanan->penanggung_jawab_nama ?: 'Petugas Faskes' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="p-8 text-center text-zinc-500 print:p-4 print:border print:border-black">
                                    Tidak ada data rekapitulasi pelayanan untuk filter periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- ==================== TAB 2: DATA PESERTA ==================== -->
        @if($activeTab === 'peserta')
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse print:text-[10px] print:border print:border-black">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 print:bg-zinc-100 print:border-b print:border-black">
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black text-center w-10">No</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Nama Peserta (NIK)</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Nama Pasangan</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">No. HP / WA</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Tgl Lahir / Usia</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Wilayah & Alamat</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Asuransi</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Status KB</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Anak</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 print:divide-zinc-400">
                        @forelse($pesertas as $index => $peserta)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 print:hover:bg-transparent">
                                <td class="p-3 text-center text-xs text-zinc-500 print:p-1.5 print:border print:border-black">{{ $index + 1 }}</td>
                                <td class="p-3 print:p-1.5 print:border print:border-black">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-sm text-zinc-900 dark:text-white print:text-black print:text-[10px]">
                                            {{ $peserta->nama_lengkap }}
                                        </span>
                                        <span class="text-xs text-zinc-500 font-mono print:text-black print:text-[9px]">
                                            NIK: {{ $peserta->nik }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-3 text-xs text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $peserta->nama_suami_istri }}
                                </td>
                                <td class="p-3 text-xs font-mono text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $peserta->nomor_hp ?: '-' }}
                                </td>
                                <td class="p-3 text-xs text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black whitespace-nowrap">
                                    {{ $peserta->tanggal_lahir_istri ? $peserta->tanggal_lahir_istri->translatedFormat('d M Y') : '-' }}
                                    <span class="text-zinc-400 font-semibold">({{ $peserta->tanggal_lahir_istri ? $peserta->tanggal_lahir_istri->age : 0 }} th)</span>
                                </td>
                                <td class="p-3 print:p-1.5 print:border print:border-black">
                                    <div class="flex flex-col">
                                        <span class="font-semibold text-xs text-zinc-900 dark:text-white print:text-black print:text-[10px]">
                                            {{ $peserta->wilayah?->nama_desa_kelurahan ?? '-' }}
                                        </span>
                                        <span class="text-2xs text-zinc-500 truncate max-w-xs print:text-[8px] print:text-zinc-700">
                                            {{ $peserta->alamat_lengkap }}
                                        </span>
                                    </div>
                                </td>
                                <td class="p-3 text-xs uppercase font-semibold text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $peserta->penggunaan_asuransi ?? '-' }}
                                </td>
                                <td class="p-3 text-xs capitalize text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $peserta->status_kepesertaan ? str_replace('_', ' ', $peserta->status_kepesertaan) : '-' }}
                                </td>
                                <td class="p-3 text-xs text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black text-center">
                                    <b>{{ $peserta->jumlah_anak_hidup ?? 0 }}</b> (L:{{ $peserta->jumlah_anak_laki ?? 0 }} P:{{ $peserta->jumlah_anak_perempuan ?? 0 }})
                                </td>
                                <td class="p-3 print:p-1.5 print:border print:border-black text-center">
                                    @if($peserta->isTerverifikasi())
                                        <flux:badge color="green" size="sm" class="print:text-black print:bg-transparent print:border-none">Terverifikasi</flux:badge>
                                    @else
                                        <flux:badge color="amber" size="sm" class="print:text-black print:bg-transparent print:border-none">Menunggu</flux:badge>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="p-8 text-center text-zinc-500 print:p-4 print:border print:border-black">
                                    Tidak ada data peserta baru untuk filter periode ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- ==================== TAB 3: STOK ALOKON ==================== -->
        @if($activeTab === 'alokon')
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse print:text-[10px] print:border print:border-black">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 print:bg-zinc-100 print:border-b print:border-black">
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black text-center w-10">No</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Nama Alat / Obat Kontrasepsi</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black">Faskes / Instansi</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black text-center">Kode Faskes</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black text-right">Sisa Stok</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black text-right">Terdistribusi (Pelayanan)</th>
                            <th class="p-3 font-bold text-xs text-zinc-600 dark:text-zinc-300 print:text-black print:p-1.5 print:border print:border-black text-center">Status Ketersediaan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 print:divide-zinc-400">
                        @forelse($inventory as $index => $alokon)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/40 print:hover:bg-transparent">
                                <td class="p-3 text-center text-xs text-zinc-500 print:p-1.5 print:border print:border-black">{{ $index + 1 }}</td>
                                <td class="p-3 font-bold text-sm text-zinc-900 dark:text-white print:text-black print:text-[10px] print:p-1.5 print:border print:border-black">
                                    {{ $alokon->nama_alokon }}
                                </td>
                                <td class="p-3 text-xs text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $alokon->instansi?->nama_instansi ?? '-' }}
                                </td>
                                <td class="p-3 text-xs font-mono text-center text-zinc-600 dark:text-zinc-400 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $alokon->instansi?->kode_faskes ?? '-' }}
                                </td>
                                <td class="p-3 text-right print:p-1.5 print:border print:border-black">
                                    <span class="font-mono font-bold text-sm {{ $alokon->stok < 5 ? 'text-rose-600 dark:text-rose-400' : ($alokon->stok < 10 ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400') }} print:text-black">
                                        {{ $alokon->stok }}
                                    </span>
                                    <span class="text-xs text-zinc-400"> unit</span>
                                </td>
                                <td class="p-3 text-right font-mono text-xs font-semibold text-zinc-700 dark:text-zinc-300 print:p-1.5 print:border print:border-black print:text-black">
                                    {{ $alokon->pelayanans_count ?? 0 }} unit
                                </td>
                                <td class="p-3 print:p-1.5 print:border print:border-black text-center">
                                    @if($alokon->stok < 5)
                                        <flux:badge color="rose" size="sm" class="uppercase font-bold print:text-black print:bg-transparent print:border-none">Kritis</flux:badge>
                                    @elseif($alokon->stok < 10)
                                        <flux:badge color="amber" size="sm" class="uppercase font-bold print:text-black print:bg-transparent print:border-none">Rendah</flux:badge>
                                    @else
                                        <flux:badge color="green" size="sm" class="uppercase font-bold print:text-black print:bg-transparent print:border-none">Aman</flux:badge>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-zinc-500 print:p-4 print:border print:border-black">
                                    Tidak ada data inventaris alat kontrasepsi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </flux:card>

    <!-- ========================================================================= -->
    <!-- ================== LEMBAR PENGESAHAN TANDA TANGAN (PRINT ONLY) ========== -->
    <!-- ========================================================================= -->
    <div class="hidden print:block mt-8 text-xs text-black leading-normal">
        <div class="flex justify-between items-start">
            <!-- Kolom Tanda Tangan Kiri: Kepala UPTD / Pimpinan -->
            <div class="text-center w-64">
                <div>Mengetahui,</div>
                <div class="font-bold">Kepala UPTD Puskesmas Wundulako</div>
                <div class="h-20"></div>
                <div class="font-bold underline uppercase">( .................................................... )</div>
                <div>NIP. ....................................................</div>
            </div>

            <!-- Kolom Tanda Tangan Kanan: Bidan Koordinator / Petugas -->
            <div class="text-center w-64">
                <div>Wundulako, {{ now()->translatedFormat('d F Y') }}</div>
                <div class="font-bold">Penanggung Jawab / Bidan Koordinator</div>
                <div class="h-20"></div>
                <div class="font-bold underline uppercase">{{ auth()->user()->name ?? '( .................................................... )' }}</div>
                <div>NIP. ....................................................</div>
            </div>
        </div>
        <div class="text-[9px] text-zinc-500 mt-6 border-t border-zinc-300 pt-1 text-right italic">
            Dicetak otomatis oleh Sistem Informasi Pelayanan KB Wundulako pada {{ now()->translatedFormat('l, d F Y H:i:s') }} WITA
        </div>
    </div>

    <!-- Printing CSS adjustments -->
    <style>
        @media print {
            @page {
                size: A4 portrait;
                margin: 12mm 10mm 12mm 10mm;
            }
            body {
                background: white !important;
                color: black !important;
                font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif !important;
                font-size: 10pt !important;
            }
            .sidebar, flux\:sidebar, .top-header, header, flux\:header, nav, [data-flux-sidebar] {
                display: none !important;
            }
            main, flux\:main {
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                page-break-inside: auto !important;
            }
            tr {
                page-break-inside: avoid !important;
                page-break-after: auto !important;
            }
            thead {
                display: table-header-group !important;
            }
            tfoot {
                display: table-footer-group !important;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</div>

