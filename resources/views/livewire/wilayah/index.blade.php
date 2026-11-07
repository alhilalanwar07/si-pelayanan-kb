<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Data Wilayah</flux:heading>
            <flux:text size="sm">Kelola daftar desa / kelurahan di wilayah pelayanan</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">
            Tambah Wilayah
        </flux:button>
    </div>

    <!-- Search & List -->
    <flux:card class="space-y-4">
        <div class="flex items-center justify-between gap-4">
            <div class="w-full max-w-sm">
                <flux:input type="text" placeholder="Cari nama desa/kelurahan..." wire:model.live="search" icon="magnifying-glass" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nama Desa / Kelurahan</flux:table.column>
                    <flux:table.column>Titik Koordinat (Lat, Long) &amp; Maps</flux:table.column>
                    <flux:table.column>Total Peserta KB</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($wilayahs as $wilayah)
                        <flux:table.row :key="$wilayah->id">
                            <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">
                                {{ $wilayah->nama_desa_kelurahan }}
                            </flux:table.cell>
                            <flux:table.cell>
                                @if($wilayah->latitude !== null && $wilayah->longitude !== null)
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="inline-flex items-center gap-1 rounded-md bg-zinc-100 px-2 py-0.5 font-mono text-xs text-zinc-700 dark:bg-zinc-800 dark:text-zinc-300 border border-zinc-200 dark:border-zinc-700">
                                            📍 {{ number_format($wilayah->latitude, 4) }}, {{ number_format($wilayah->longitude, 4) }}
                                        </span>
                                        <a href="{{ $wilayah->link_gmaps ?: 'https://www.google.com/maps?q=' . $wilayah->latitude . ',' . $wilayah->longitude }}"
                                           target="_blank"
                                           rel="noopener noreferrer"
                                           class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline dark:text-blue-400">
                                            <span>Buka Google Maps</span>
                                            <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                                        </a>
                                    </div>
                                @elseif($wilayah->link_gmaps)
                                    <a href="{{ $wilayah->link_gmaps }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="inline-flex items-center gap-1 text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline dark:text-blue-400">
                                        <span>Buka Link Google Maps</span>
                                    </a>
                                @else
                                    <span class="text-xs text-zinc-400 italic">Koordinat default</span>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                <flux:badge color="blue" size="sm">{{ $wilayah->peserta_kbs_count }} peserta</flux:badge>
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <flux:button size="xs" variant="outline" wire:click="openEditModal({{ $wilayah->id }})">
                                        Edit
                                    </flux:button>
                                    <flux:button size="xs" variant="danger" wire:click="startDelete({{ $wilayah->id }})">
                                        Hapus
                                    </flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="4" class="text-center text-zinc-500 py-6">
                                Belum ada data wilayah.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-4">
            {{ $wilayahs->links() }}
        </div>
    </flux:card>

    <!-- Modal Form (Create / Edit) -->
    <flux:modal wire:model="showModal" class="md:w-[34rem] space-y-6">
        <div>
            <flux:heading size="lg">{{ $editId ? 'Edit Wilayah & Koordinat Peta' : 'Tambah Wilayah Baru' }}</flux:heading>
            <flux:text size="sm">Masukkan nama desa/kelurahan beserta Link Google Maps atau titik koordinat (Latitude &amp; Longitude).</flux:text>
        </div>

        <form wire:submit.prevent="simpan" class="space-y-4">
            <flux:field>
                <flux:label>Nama Desa / Kelurahan <span class="text-red-500">*</span></flux:label>
                <flux:input type="text" placeholder="Contoh: Desa Bende" wire:model="nama_desa_kelurahan" autofocus />
                <flux:error name="nama_desa_kelurahan" />
            </flux:field>

            <flux:field>
                <flux:label>Link Google Maps / Tempel Koordinat</flux:label>
                <flux:input type="text"
                            placeholder="Tempel URL Google Maps atau ketik: -4.1150, 121.6120"
                            wire:model.live.debounce.300ms="link_gmaps"
                            icon="map-pin" />
                <flux:description class="text-xs text-zinc-500">
                    Jika Anda menempelkan URL Google Maps (contoh: <code>https://www.google.com/maps/place/.../@-4.1150,121.6120,15z</code>) atau angka <code>-4.1150, 121.6120</code>, kolom Latitude &amp; Longitude di bawah akan terisi otomatis.
                </flux:description>
                <flux:error name="link_gmaps" />
            </flux:field>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <flux:field>
                    <flux:label>Latitude (Garis Lintang)</flux:label>
                    <flux:input type="text"
                                placeholder="Contoh: -4.1150"
                                wire:model.live.debounce.300ms="latitude" />
                    <flux:error name="latitude" />
                </flux:field>

                <flux:field>
                    <flux:label>Longitude (Garis Bujur)</flux:label>
                    <flux:input type="text"
                                placeholder="Contoh: 121.6120"
                                wire:model.live.debounce.300ms="longitude" />
                    <flux:error name="longitude" />
                </flux:field>
            </div>

            @if($latitude !== '' && $longitude !== '' && is_numeric($latitude) && is_numeric($longitude))
                <div class="rounded-xl bg-blue-50/80 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 p-3 flex items-center justify-between gap-2 text-xs">
                    <div class="flex items-center gap-2 text-blue-900 dark:text-blue-200">
                        <span class="text-base">📍</span>
                        <div>
                            <span class="font-bold block">Titik Koordinat Siap Dipetakan</span>
                            <span class="font-mono text-[11px] text-blue-700 dark:text-blue-300">Lat: {{ $latitude }} | Long: {{ $longitude }}</span>
                        </div>
                    </div>
                    <a href="https://www.google.com/maps?q={{ $latitude }},{{ $longitude }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="shrink-0 rounded-lg bg-white dark:bg-zinc-800 px-2.5 py-1 font-bold text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-700 hover:bg-blue-50">
                        Cek di Maps ↗
                    </a>
                </div>
            @endif

            <div class="flex justify-end gap-3 mt-6 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                <flux:button variant="outline" wire:click="$set('showModal', false)">Batal</flux:button>
                <flux:button type="submit" variant="primary">Simpan Wilayah</flux:button>
            </div>
        </form>
    </flux:modal>

    <!-- Modal Konfirmasi Hapus -->
    <flux:modal wire:model="showDeleteModal" class="md:w-[26rem] space-y-6">
        <div class="text-center space-y-3">
            <div class="inline-flex size-12 items-center justify-center rounded-full bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400">
                <flux:icon name="exclamation-triangle" class="size-6" />
            </div>
            <div>
                <flux:heading size="lg">Konfirmasi Hapus</flux:heading>
                <flux:text size="sm">Apakah Anda yakin ingin menghapus wilayah <strong>{{ $confirmingName }}</strong>? Tindakan ini tidak dapat dibatalkan.</flux:text>
            </div>
        </div>

        <div class="flex justify-center gap-3">
            <flux:button variant="outline" wire:click="$set('showDeleteModal', false)">Batal</flux:button>
            <flux:button variant="danger" wire:click="hapus">Ya, Hapus Wilayah</flux:button>
        </div>
    </flux:modal>
</div>
