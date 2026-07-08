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
                            <flux:table.cell colspan="3" class="text-center text-zinc-500 py-6">
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
    <flux:modal wire:model="showModal" class="md:w-[28rem] space-y-6">
        <div>
            <flux:heading size="lg">{{ $editId ? 'Edit Wilayah' : 'Tambah Wilayah Baru' }}</flux:heading>
            <flux:text size="sm">Masukkan nama desa / kelurahan di Kecamatan Wundulako</flux:text>
        </div>

        <form wire:submit.prevent="simpan" class="space-y-4">
            <flux:field>
                <flux:label>Nama Desa / Kelurahan</flux:label>
                <flux:input type="text" placeholder="Contoh: Desa Bende" wire:model="nama_desa_kelurahan" autofocus />
                <flux:error name="nama_desa_kelurahan" />
            </flux:field>

            <div class="flex justify-end gap-3 mt-6">
                <flux:button variant="outline" wire:click="$set('showModal', false)">Batal</flux:button>
                <flux:button type="submit" variant="primary">Simpan</flux:button>
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
