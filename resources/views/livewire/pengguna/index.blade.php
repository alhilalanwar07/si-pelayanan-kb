<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Manajemen Pengguna</flux:heading>
            <flux:text size="sm">Kelola data akun pengguna internal sistem</flux:text>
        </div>
        <flux:button variant="primary" icon="plus" wire:click="openCreateModal">
            Tambah Pengguna
        </flux:button>
    </div>

    <!-- Search & List -->
    <flux:card class="space-y-4">
        <div class="flex items-center justify-between gap-4">
            <div class="w-full max-w-sm">
                <flux:input type="text" placeholder="Cari nama atau username..." wire:model.live="search" icon="magnifying-glass" />
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nama Lengkap</flux:table.column>
                    <flux:table.column>Username</flux:table.column>
                    <flux:table.column>Faskes / Instansi</flux:table.column>
                    <flux:table.column>Level Akses</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($users as $user)
                        <flux:table.row :key="$user->id">
                            <!-- Nama -->
                            <flux:table.cell class="font-semibold text-zinc-900 dark:text-white">
                                {{ $user->name }}
                                @if(auth()->id() == $user->id)
                                    <flux:badge color="zinc" size="sm" class="ml-2">Anda</flux:badge>
                                @endif
                            </flux:table.cell>

                            <!-- Username -->
                            <flux:table.cell class="font-mono text-xs">
                                {{ $user->username }}
                            </flux:table.cell>

                            <!-- Instansi -->
                            <flux:table.cell>
                                {{ $user->instansi->nama_instansi ?? '-' }}
                            </flux:table.cell>

                            <!-- Level Akses -->
                            <flux:table.cell>
                                @if($user->isAdmin())
                                    <flux:badge color="blue" size="sm">Admin</flux:badge>
                                @elseif($user->isBidan())
                                    <flux:badge color="emerald" size="sm">Bidan</flux:badge>
                                @else
                                    <flux:badge color="purple" size="sm">Pimpinan</flux:badge>
                                @endif
                            </flux:table.cell>

                            <!-- Aksi -->
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <flux:button size="xs" variant="outline" wire:click="openEditModal({{ $user->id }})">
                                        Edit
                                    </flux:button>
                                    @if(auth()->id() != $user->id)
                                        <flux:button size="xs" variant="danger" wire:click="startDelete({{ $user->id }})">
                                            Hapus
                                        </flux:button>
                                    @endif
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="5" class="text-center text-zinc-500 py-6">
                                Belum ada data pengguna.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </flux:card>

    <!-- Modal Form (Create / Edit) -->
    <flux:modal wire:model="showModal" class="md:w-[28rem] space-y-6">
        <div>
            <flux:heading size="lg">{{ $editId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}</flux:heading>
            <flux:text size="sm">Masukkan detail akun untuk pengguna sistem</flux:text>
        </div>

        <form wire:submit.prevent="simpan" class="space-y-4">
            <!-- Nama Lengkap -->
            <flux:field>
                <flux:label>Nama Lengkap</flux:label>
                <flux:input type="text" placeholder="Masukkan nama lengkap" wire:model="name" />
                <flux:error name="name" />
            </flux:field>

            <!-- Username -->
            <flux:field>
                <flux:label>Username</flux:label>
                <flux:input type="text" placeholder="Masukkan username" wire:model="username" />
                <flux:error name="username" />
            </flux:field>

            <!-- Password -->
            <flux:field>
                <flux:label>Password {{ $editId ? '(Kosongkan jika tidak diganti)' : '' }}</flux:label>
                <flux:input type="password" placeholder="Minimal 6 karakter" wire:model="password" viewable />
                <flux:error name="password" />
            </flux:field>

            <!-- Level Akses -->
            <flux:field>
                <flux:label>Level Akses</flux:label>
                <flux:select wire:model="level_akses">
                    <option value="admin">Admin / Operator Kecamatan</option>
                    <option value="bidan">Bidan / Petugas Medis</option>
                    <option value="pimpinan">Pimpinan DPPKB</option>
                </flux:select>
                <flux:error name="level_akses" />
            </flux:field>

            <!-- Instansi -->
            <flux:field>
                <flux:label>Faskes / Instansi</flux:label>
                <flux:select wire:model="instansi_id">
                    @foreach($instansis as $instansi)
                        <option value="{{ $instansi->id }}">{{ $instansi->nama_instansi }}</option>
                    @endforeach
                </flux:select>
                <flux:error name="instansi_id" />
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
                <flux:heading size="lg">Konfirmasi Hapus Akun</flux:heading>
                <flux:text size="sm">Apakah Anda yakin ingin menghapus akun pengguna bernama <strong>{{ $confirmingName }}</strong>? Pengguna ini tidak akan bisa login lagi.</flux:text>
            </div>
        </div>

        <div class="flex justify-center gap-3">
            <flux:button variant="outline" wire:click="$set('showDeleteModal', false)">Batal</flux:button>
            <flux:button variant="danger" wire:click="hapus">Ya, Hapus Pengguna</flux:button>
        </div>
    </flux:modal>
</div>
