<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Pelayanan Kontrasepsi</flux:heading>
            <flux:text size="sm">Pencatatan riwayat pemberian pelayanan KB</flux:text>
        </div>
        @if(auth()->user()->isBidan())
            <flux:button variant="primary" icon="plus" href="{{ route('pelayanan.create') }}" wire:navigate>
                Pelayanan Baru (Wizard)
            </flux:button>
        @endif
    </div>

    <!-- Search & Filter Card -->
    <flux:card class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-4">
            <!-- Search -->
            <flux:field class="sm:col-span-2">
                <flux:label>Pencarian Peserta</flux:label>
                <flux:input type="text" placeholder="Cari nama peserta, NIK..." wire:model.live="search" icon="magnifying-glass" />
            </flux:field>

            <!-- Filter Alokon -->
            <flux:field>
                <flux:label>Jenis Alokon</flux:label>
                <flux:select wire:model.live="filterAlokon">
                    <option value="">Semua Alokon</option>
                    @foreach($alokons as $alokon)
                        <option value="{{ $alokon->id }}">{{ $alokon->nama_alokon }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <!-- Filter Bulan -->
            <flux:field>
                <flux:label>Bulan</flux:label>
                <flux:select wire:model.live="filterBulan">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}">{{ Carbon\Carbon::create(null, $m)->translatedFormat('F') }}</option>
                    @endfor
                </flux:select>
            </flux:field>
        </div>
    </flux:card>

    <!-- Table -->
    <flux:card>
        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Tanggal</flux:table.column>
                    <flux:table.column>Nama Peserta</flux:table.column>
                    <flux:table.column>Alokon</flux:table.column>
                    <flux:table.column>Tindakan (Consent)</flux:table.column>
                    <flux:table.column>Faskes</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($pelayanans as $pelayanan)
                        <flux:table.row :key="$pelayanan->id">
                            <!-- Tanggal -->
                            <flux:table.cell class="font-medium">
                                {{ $pelayanan->tanggal_pelayanan->translatedFormat('d M Y') }}
                            </flux:table.cell>

                            <!-- Peserta -->
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $pelayanan->pesertaKb->nama_lengkap }}</span>
                                    <span class="text-xs text-zinc-500 font-mono">NIK: {{ $pelayanan->pesertaKb->nik }}</span>
                                </div>
                            </flux:table.cell>

                            <!-- Alokon -->
                            <flux:table.cell>{{ $pelayanan->alokon->nama_alokon }}</flux:table.cell>

                            <!-- Tindakan / Consent -->
                            <flux:table.cell>
                                @if($pelayanan->skriningMedis && $pelayanan->skriningMedis->informedConsent)
                                    <span class="capitalize font-semibold text-zinc-900 dark:text-white">
                                        {{ $pelayanan->skriningMedis->informedConsent->jenis_tindakan_medis }}
                                    </span>
                                @else
                                    <span class="text-zinc-400">-</span>
                                @endif
                            </flux:table.cell>

                            <!-- Faskes -->
                            <flux:table.cell>
                                <span class="text-xs">{{ $pelayanan->alokon->instansi->nama_instansi }}</span>
                            </flux:table.cell>

                            <!-- Aksi -->
                            <flux:table.cell>
                                <flux:button size="xs" variant="outline" href="{{ route('pelayanan.show', $pelayanan->id) }}" wire:navigate>
                                    Detail
                                </flux:button>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="text-center text-zinc-500 py-6">
                                Belum ada riwayat pelayanan KB yang tercatat.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-4">
            {{ $pelayanans->links() }}
        </div>
    </flux:card>
</div>
