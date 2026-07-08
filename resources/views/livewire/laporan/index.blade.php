<div class="flex-1 space-y-6 p-6 print:p-0 print:bg-white print:text-black">
    <!-- Header (Hidden on Print) -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700 print:hidden">
        <div>
            <flux:heading size="xl" level="1">Laporan Pelayanan KB</flux:heading>
            <flux:text size="sm">Cetak rekapitulasi pelayanan, peserta, dan stok alokon</flux:text>
        </div>
        <div class="flex items-center gap-2">
            <!-- Print Button -->
            <flux:button variant="outline" icon="printer" onclick="window.print()">
                Cetak Laporan
            </flux:button>
            
            <!-- Export Button -->
            <flux:button variant="primary" icon="arrow-down-tray" wire:click="exportCsv">
                Export CSV
            </flux:button>
        </div>
    </div>

    <!-- Printable Header (Visible ONLY on Print) -->
    <div class="hidden print:block text-center space-y-2 border-b-2 border-black pb-4 mb-6">
        <h1 class="text-xl font-bold uppercase">Sistem Informasi Pelayanan KB</h1>
        <h2 class="text-md font-semibold uppercase">Kecamatan Wundulako - Kabupaten Kolaka</h2>
        <p class="text-xs text-zinc-600">Laporan Rekapitulasi: {{ $activeTab === 'pelayanan' ? 'Pelayanan KB' : ($activeTab === 'peserta' ? 'Data Peserta' : 'Stok Alokon') }}</p>
        <p class="text-xs">Periode: {{ Carbon\Carbon::parse($dariTanggal)->translatedFormat('d F Y') }} s/d {{ Carbon\Carbon::parse($sampaiTanggal)->translatedFormat('d F Y') }}</p>
    </div>

    <!-- Filter Card (Hidden on Print) -->
    <flux:card class="space-y-4 print:hidden">
        <div class="grid gap-4 sm:grid-cols-4">
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
                <flux:label>Alokon</flux:label>
                <flux:select wire:model.live="alokonId">
                    <option value="">Semua Alokon</option>
                    @foreach($alokons as $a)
                        <option value="{{ $a->id }}">{{ $a->nama_alokon }}</option>
                    @endforeach
                </flux:select>
            </flux:field>
        </div>
    </flux:card>

    <!-- Summary Widgets (Hidden on Print) -->
    <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4 print:hidden">
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <span class="text-xs text-zinc-500 font-semibold uppercase tracking-wider">Total Pelayanan</span>
            <div class="mt-2 text-2xl font-extrabold text-blue-600 dark:text-blue-400">{{ $totalPelayanan }}</div>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <span class="text-xs text-zinc-500 font-semibold uppercase tracking-wider">Peserta Dilayani</span>
            <div class="mt-2 text-2xl font-extrabold text-emerald-600 dark:text-emerald-400">{{ $totalPesertaDilayani }}</div>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <span class="text-xs text-zinc-500 font-semibold uppercase tracking-wider">Alokon Terdistribusi</span>
            <div class="mt-2 text-2xl font-extrabold text-purple-600 dark:text-purple-400">{{ $totalAlokonTerdistribusi }}</div>
        </div>
        <div class="rounded-xl border border-zinc-200 bg-white p-4 dark:border-zinc-700 dark:bg-zinc-900">
            <span class="text-xs text-zinc-500 font-semibold uppercase tracking-wider">Wilayah Tercakup</span>
            <div class="mt-2 text-2xl font-extrabold text-amber-600 dark:text-amber-400">{{ $totalWilayahTercakup }}</div>
        </div>
    </div>

    <!-- Navigation Tabs (Hidden on Print) -->
    <div class="flex border-b border-zinc-200 dark:border-zinc-700 print:hidden">
        <button wire:click="setTab('pelayanan')" 
                class="px-4 py-2 border-b-2 font-medium text-sm transition-all duration-150 {{ $activeTab === 'pelayanan' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            Rekap Pelayanan
        </button>
        <button wire:click="setTab('peserta')" 
                class="px-4 py-2 border-b-2 font-medium text-sm transition-all duration-150 {{ $activeTab === 'peserta' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            Data Peserta
        </button>
        <button wire:click="setTab('alokon')" 
                class="px-4 py-2 border-b-2 font-medium text-sm transition-all duration-150 {{ $activeTab === 'alokon' ? 'border-blue-500 text-blue-600 dark:text-blue-400' : 'border-transparent text-zinc-500 hover:text-zinc-700' }}">
            Stok Alokon
        </button>
    </div>

    <!-- Main Data Card -->
    <flux:card class="print:border-none print:shadow-none print:p-0">
        <!-- Tab 1: Rekap Pelayanan -->
        @if($activeTab === 'pelayanan')
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse print:text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 print:bg-transparent">
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">No</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Tanggal</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Nama Peserta (NIK)</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Wilayah</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Alokon</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Tindakan</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Skrining</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Consent</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 print:divide-zinc-300">
                        @forelse($pelayanans as $index => $pelayanan)
                            <tr>
                                <td class="p-3">{{ $index + 1 }}</td>
                                <td class="p-3 font-medium">{{ $pelayanan->tanggal_pelayanan->translatedFormat('d M Y') }}</td>
                                <td class="p-3">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-zinc-900 dark:text-white print:text-black">{{ $pelayanan->pesertaKb->nama_lengkap }}</span>
                                        <span class="text-xs text-zinc-500 font-mono print:text-black">NIK: {{ $pelayanan->pesertaKb->nik }}</span>
                                    </div>
                                </td>
                                <td class="p-3">{{ $pelayanan->pesertaKb->wilayah->nama_desa_kelurahan }}</td>
                                <td class="p-3 font-semibold text-zinc-950 dark:text-white print:text-black">{{ $pelayanan->alokon->nama_alokon }}</td>
                                <td class="p-3 capitalize">{{ $pelayanan->skriningMedis?->informedConsent?->jenis_tindakan_medis ?? '-' }}</td>
                                <td class="p-3">
                                    @if($pelayanan->skriningMedis?->adaRiwayatPenyakit())
                                        <span class="text-xs text-rose-600 dark:text-rose-400 font-medium">Beresiko</span>
                                    @else
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">Lolos</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    @if($pelayanan->skriningMedis?->informedConsent?->isLengkap())
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold">Lengkap</span>
                                    @else
                                        <span class="text-xs text-rose-500 font-bold">Belum</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-zinc-500 print:text-zinc-500">
                                    Tidak ada data pelayanan untuk filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Tab 2: Data Peserta -->
        @if($activeTab === 'peserta')
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse print:text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 print:bg-transparent">
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">No</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">NIK</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Nama Lengkap</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Nama Pasangan</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Tanggal Lahir</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Wilayah</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Asuransi</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 print:divide-zinc-300">
                        @forelse($pesertas as $index => $peserta)
                            <tr>
                                <td class="p-3">{{ $index + 1 }}</td>
                                <td class="p-3 font-mono">{{ $peserta->nik }}</td>
                                <td class="p-3 font-bold text-zinc-900 dark:text-white print:text-black">{{ $peserta->nama_lengkap }}</td>
                                <td class="p-3">{{ $peserta->nama_suami_istri }}</td>
                                <td class="p-3">{{ $peserta->tanggal_lahir_istri->translatedFormat('d M Y') }}</td>
                                <td class="p-3">{{ $peserta->wilayah->nama_desa_kelurahan }}</td>
                                <td class="p-3 uppercase">{{ $peserta->penggunaan_asuransi ?? '-' }}</td>
                                <td class="p-3 capitalize">{{ $peserta->status }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-6 text-center text-zinc-500">
                                    Tidak ada data peserta baru untuk filter yang dipilih.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif

        <!-- Tab 3: Stok Alokon -->
        @if($activeTab === 'alokon')
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse print:text-xs">
                    <thead>
                        <tr class="border-b border-zinc-200 dark:border-zinc-700 bg-zinc-50 dark:bg-zinc-800/50 print:bg-transparent">
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">No</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Nama Alat / Obat Kontrasepsi</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Faskes / Instansi</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Sisa Stok</th>
                            <th class="p-3 font-semibold text-xs text-zinc-500 print:text-black">Status Stok</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 print:divide-zinc-300">
                        @forelse($inventory as $index => $alokon)
                            <tr>
                                <td class="p-3">{{ $index + 1 }}</td>
                                <td class="p-3 font-bold text-zinc-900 dark:text-white print:text-black">{{ $alokon->nama_alokon }}</td>
                                <td class="p-3">{{ $alokon->instansi->nama_instansi }}</td>
                                <td class="p-3 font-mono font-bold">{{ $alokon->stok }} unit</td>
                                <td class="p-3">
                                    @if($alokon->stok < 5)
                                        <span class="text-xs text-red-600 dark:text-red-400 font-bold uppercase">Kritis</span>
                                    @elseif($alokon->stok < 10)
                                        <span class="text-xs text-amber-600 dark:text-amber-400 font-bold uppercase">Rendah</span>
                                    @else
                                        <span class="text-xs text-emerald-600 dark:text-emerald-400 font-bold uppercase">Aman</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="p-6 text-center text-zinc-500">
                                    Tidak ada data inventaris alokon.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </flux:card>

    <!-- Printing CSS adjustments -->
    <style>
        @media print {
            body {
                background: white !important;
                color: black !important;
            }
            .sidebar, flux\:sidebar, .top-header, header, flux\:header {
                display: none !important;
            }
            main, flux\:main {
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
</div>
