<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Inventaris Alokon</flux:heading>
            <flux:text size="sm">Kelola data stok Alat Obat Kontrasepsi (Alokon)</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">
            Tambah Alokon
        </flux:button>
    </div>

    <!-- Search & Filters -->
    <flux:card class="space-y-4">
        <div class="flex items-center justify-between gap-4">
            <div class="w-full max-w-sm">
                <flux:input type="text" placeholder="Cari nama alokon..." wire:model.live="search" icon="magnifying-glass" />
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Alat / Obat Kontrasepsi</flux:table.column>
                    <flux:table.column>Faskes / Instansi</flux:table.column>
                    <flux:table.column>Stok Tersedia</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($alokons as $alokon)
                        <flux:table.row :key="$alokon->id">
                            <!-- Nama Alokon -->
                            <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">
                                {{ $alokon->nama_alokon }}
                            </flux:table.cell>
                            
                            <!-- Instansi -->
                            <flux:table.cell>
                                {{ $alokon->instansi->nama_instansi }}
                            </flux:table.cell>

                            <!-- Stok -->
                            <flux:table.cell class="font-mono font-bold">
                                {{ $alokon->stok }} unit
                            </flux:table.cell>

                            <!-- Status -->
                            <flux:table.cell>
                                @if($alokon->stok < 5)
                                    <flux:badge color="red" size="sm">Kritis / Sangat Rendah</flux:badge>
                                @elseif($alokon->stok < 10)
                                    <flux:badge color="amber" size="sm">Rendah / Restock</flux:badge>
                                @else
                                    <flux:badge color="green" size="sm">Aman / Cukup</flux:badge>
                                @endif
                            </flux:table.cell>

                            <!-- Aksi -->
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <flux:button size="xs" variant="outline" wire:click="openEditModal({{ $alokon->id }})">
                                        Edit
                                    </flux:button>
                                    <flux:button size="xs" variant="danger" wire:click="startDelete({{ $alokon->id }})">
                                        Hapus
                                    </flux:button>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-zinc-500 py-6">
                                Belum ada data alokon.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-4">
            {{ $alokons->links() }}
        </div>
    </flux:card>

    <!-- Modal Form (Create / Edit) -->
    <flux:modal wire:model="showModal" class="md:w-[28rem] space-y-6">
        <div>
            <flux:heading size="lg">{{ $editId ? 'Edit Alokon' : 'Tambah Alokon Baru' }}</flux:heading>
            <flux:text size="sm">Masukkan detail alat/obat kontrasepsi beserta stok awal</flux:text>
        </div>

        <form wire:submit.prevent="simpan" class="space-y-4">
            <!-- Instansi -->
            <flux:field>
                <flux:label>Faskes / Instansi</flux:label>
                <flux:select wire:model="instansi_id">
                    @foreach($instansis as $instansi)
                        <option value="{{ $instansi->id }}">{{ $instansi->nama_instansi }} ({{ $instansi->kode_faskes }})</option>
                    @endforeach
                </flux:select>
                <flux:error name="instansi_id" />
            </flux:field>

            <!-- Nama Alokon -->
            <flux:field>
                <flux:label>Nama Alat/Obat Kontrasepsi</flux:label>
                <flux:input type="text" placeholder="Contoh: Pil KB Kombinasi" wire:model="nama_alokon" />
                <flux:error name="nama_alokon" />
            </flux:field>

            <!-- Stok -->
            <flux:field>
                <flux:label>Jumlah Stok Awal</flux:label>
                <flux:input type="number" min="0" placeholder="0" wire:model="stok" />
                <flux:error name="stok" />
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
                <flux:text size="sm">Apakah Anda yakin ingin menghapus alokon <strong>{{ $confirmingName }}</strong>? Tindakan ini tidak dapat dibatalkan.</flux:text>
            </div>
        </div>

        <div class="flex justify-center gap-3">
            <flux:button variant="outline" wire:click="$set('showDeleteModal', false)">Batal</flux:button>
            <flux:button variant="danger" wire:click="hapus">Ya, Hapus Alokon</flux:button>
        </div>
    </flux:modal>
</div>
