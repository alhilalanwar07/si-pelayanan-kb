<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Data Peserta KB</flux:heading>
            <flux:text size="sm">Kelola seluruh data peserta pelayanan kontrasepsi</flux:text>
        </div>
        @if(auth()->user()->isAdmin())
            <flux:button variant="primary" icon="plus" href="{{ route('peserta-kb.create') }}" wire:navigate>
                Tambah Peserta
            </flux:button>
        @endif
    </div>

    <!-- Filter & Search Bar -->
    <flux:card class="space-y-4">
        <div class="grid gap-4 sm:grid-cols-3">
            <!-- Search -->
            <flux:field>
                <flux:label>Pencarian</flux:label>
                <flux:input type="text" placeholder="Cari nama, NIK..." wire:model.live="search" icon="magnifying-glass" />
            </flux:field>

            <!-- Filter Wilayah -->
            <flux:field>
                <flux:label>Wilayah (Desa/Kelurahan)</flux:label>
                <flux:select wire:model.live="filterWilayah">
                    <option value="">Semua Wilayah</option>
                    @foreach($wilayahs as $wilayah)
                        <option value="{{ $wilayah->id }}">{{ $wilayah->nama_desa_kelurahan }}</option>
                    @endforeach
                </flux:select>
            </flux:field>

            <!-- Filter Status -->
            <flux:field>
                <flux:label>Status Verifikasi</flux:label>
                <flux:select wire:model.live="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="terverifikasi">Terverifikasi</option>
                    <option value="menunggu">Menunggu Verifikasi</option>
                </flux:select>
            </flux:field>
        </div>
    </flux:card>

    <!-- Data Table -->
    <flux:card>
        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Nama Lengkap / NIK</flux:table.column>
                    <flux:table.column>Nama Pasangan</flux:table.column>
                    <flux:table.column>Wilayah</flux:table.column>
                    <flux:table.column>Asuransi</flux:table.column>
                    <flux:table.column>Anak (Terakhir)</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column>Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($pesertas as $peserta)
                        <flux:table.row :key="$peserta->id">
                            <!-- Nama / NIK -->
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $peserta->nama_lengkap }}</span>
                                    <div class="flex items-center gap-1.5 text-xs text-zinc-500 font-mono">
                                        <span>NIK: {{ $peserta->nik }}</span>
                                        @if($peserta->nomor_hp)
                                            <span>•</span>
                                            <a href="{{ $peserta->whatsapp_link }}" target="_blank" class="text-emerald-600 dark:text-emerald-400 font-medium hover:underline flex items-center gap-0.5">
                                                <span>{{ $peserta->nomor_hp }}</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </flux:table.cell>
                            
                            <!-- Nama Pasangan -->
                            <flux:table.cell>{{ $peserta->nama_suami_istri }}</flux:table.cell>
                            
                            <!-- Wilayah -->
                            <flux:table.cell>{{ $peserta->wilayah->nama_desa_kelurahan }}</flux:table.cell>
                            
                            <!-- Asuransi -->
                            <flux:table.cell>
                                <span class="uppercase text-xs font-semibold">{{ $peserta->penggunaan_asuransi ?? '-' }}</span>
                            </flux:table.cell>
                            
                            <!-- Jumlah Anak & Umur Terakhir -->
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span>{{ $peserta->jumlah_anak_hidup }} anak</span>
                                    @if($peserta->umur_anak_terakhir)
                                        <span class="text-xs text-zinc-500">{{ $peserta->umur_anak_terakhir }} bln</span>
                                    @endif
                                </div>
                            </flux:table.cell>
                            
                            <!-- Status -->
                            <flux:table.cell>
                                @if($peserta->isTerverifikasi())
                                    <flux:badge color="green" size="sm">Terverifikasi</flux:badge>
                                @else
                                    <flux:badge color="amber" size="sm">Menunggu</flux:badge>
                                @endif
                            </flux:table.cell>
                            
                            <!-- Actions -->
                            <flux:table.cell>
                                <div class="flex items-center gap-2">
                                    <!-- Detail Button -->
                                    <flux:button size="xs" variant="outline" href="{{ route('peserta-kb.show', $peserta->id) }}" wire:navigate>
                                        Detail
                                    </flux:button>

                                    <!-- Verifikasi Button -->
                                    @if(auth()->user()->isAdmin() && !$peserta->isTerverifikasi())
                                        <flux:button size="xs" variant="primary" wire:click="verifikasi({{ $peserta->id }})">
                                            Verifikasi
                                        </flux:button>
                                    @elseif(auth()->user()->isAdmin() && $peserta->isTerverifikasi() && $peserta->nomor_hp)
                                        <flux:button size="xs" variant="outline" href="{{ $peserta->whatsapp_link }}" target="_blank" icon="chat-bubble-left-right" class="text-emerald-600 dark:text-emerald-400">
                                            Kirim Jadwal (WA)
                                        </flux:button>
                                    @endif

                                    <!-- Hapus Button -->
                                    @if(auth()->user()->isAdmin())
                                        <flux:button size="xs" variant="danger" wire:click="startDelete({{ $peserta->id }})">
                                            Hapus
                                        </flux:button>
                                    @endif
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="7" class="text-center text-zinc-500 py-6">
                                Tidak ada data peserta yang cocok dengan kriteria.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $pesertas->links() }}
        </div>
    </flux:card>

    <!-- Modal Konfirmasi Hapus -->
    <flux:modal wire:model="showDeleteModal" class="md:w-[28rem] space-y-6">
        <div class="text-center space-y-3">
            <div class="inline-flex size-12 items-center justify-center rounded-full bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400">
                <flux:icon name="exclamation-triangle" class="size-6" />
            </div>
            <div>
                <flux:heading size="lg">Konfirmasi Hapus Peserta</flux:heading>
                <flux:text size="sm">Apakah Anda yakin ingin menghapus peserta bernama <strong>{{ $confirmingName }}</strong>? Semua data riwayat skrining medis dan pelayanan KB miliknya juga akan ikut terhapus permanen.</flux:text>
            </div>
        </div>

        <div class="flex justify-center gap-3">
            <flux:button variant="outline" wire:click="$set('showDeleteModal', false)">Batal</flux:button>
            <flux:button variant="danger" wire:click="hapus">Ya, Hapus Permanen</flux:button>
        </div>
    </flux:modal>
</div>
