<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Jadwal Pelayanan KB</flux:heading>
            <flux:text size="sm">Kelola jadwal pelayanan kontrasepsi melalui kalender interaktif</flux:text>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid gap-4 sm:grid-cols-3">
        <flux:card class="flex items-center gap-4 p-4">
            <div class="flex size-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/50 dark:text-blue-400">
                <flux:icon name="calendar-days" class="size-5" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-500">Jadwal Bulan Ini</flux:text>
                <flux:heading size="lg" class="font-black">{{ $totalJadwalBulanIni }}</flux:heading>
            </div>
        </flux:card>
        <flux:card class="flex items-center gap-4 p-4">
            <div class="flex size-10 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-950/50 dark:text-emerald-400">
                <flux:icon name="check-circle" class="size-5" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-500">Jadwal Aktif Mendatang</flux:text>
                <flux:heading size="lg" class="font-black">{{ $jadwalAktifMendatang }}</flux:heading>
            </div>
        </flux:card>
        <flux:card class="flex items-center gap-4 p-4">
            <div class="flex size-10 items-center justify-center rounded-xl bg-amber-50 text-amber-600 dark:bg-amber-950/50 dark:text-amber-400">
                <flux:icon name="users" class="size-5" />
            </div>
            <div>
                <flux:text size="sm" class="text-zinc-500">Antrian Bulan Ini</flux:text>
                <flux:heading size="lg" class="font-black">{{ $totalAntrianBulanIni }}</flux:heading>
            </div>
        </flux:card>
    </div>

    <!-- Calendar -->
    <flux:card class="p-0 overflow-hidden">
        <!-- Calendar Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-zinc-200 dark:border-zinc-700">
            <div class="flex items-center gap-3">
                <flux:heading size="lg" class="font-bold">{{ $monthName }}</flux:heading>
            </div>
            <div class="flex items-center gap-2">
                <flux:button variant="outline" size="sm" wire:click="goToToday">Hari Ini</flux:button>
                <flux:button variant="outline" size="sm" icon="chevron-left" wire:click="previousMonth" />
                <flux:button variant="outline" size="sm" icon="chevron-right" wire:click="nextMonth" />
            </div>
        </div>

        <!-- Day Headers -->
        <div class="grid grid-cols-7 border-b border-zinc-200 dark:border-zinc-700">
            @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $dayName)
                <div class="px-2 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-zinc-500 dark:text-zinc-400
                    {{ in_array($dayName, ['Sab', 'Min']) ? 'bg-zinc-50/50 dark:bg-zinc-800/30' : '' }}">
                    {{ $dayName }}
                </div>
            @endforeach
        </div>

        <!-- Calendar Grid -->
        <div class="grid grid-cols-7">
            @foreach($calendarDays as $dayData)
                @if($dayData === null)
                    <div class="min-h-24 border-b border-e border-zinc-100 dark:border-zinc-800 bg-zinc-50/30 dark:bg-zinc-900/30"></div>
                @else
                    <button
                        wire:click="clickDate('{{ $dayData['date'] }}')"
                        class="min-h-24 border-b border-e border-zinc-100 dark:border-zinc-800 p-2 text-left transition-colors
                            {{ $dayData['isToday'] ? 'bg-blue-50/50 dark:bg-blue-950/20 ring-1 ring-inset ring-blue-200 dark:ring-blue-800' : '' }}
                            {{ $dayData['isPast'] && !$dayData['isToday'] ? 'bg-zinc-50/50 dark:bg-zinc-900/30' : '' }}
                            hover:bg-zinc-100 dark:hover:bg-zinc-800/50 cursor-pointer"
                    >
                        <div class="flex items-start justify-between">
                            <span class="inline-flex items-center justify-center text-sm font-medium
                                {{ $dayData['isToday'] ? 'size-7 rounded-full bg-blue-600 text-white' : '' }}
                                {{ $dayData['isPast'] && !$dayData['isToday'] ? 'text-zinc-400 dark:text-zinc-600' : 'text-zinc-700 dark:text-zinc-300' }}
                            ">
                                {{ $dayData['day'] }}
                            </span>
                            @if($dayData['jadwalCount'] > 0)
                                <span class="inline-flex items-center gap-0.5">
                                    @if($dayData['hasAktif'])
                                        <span class="size-2 rounded-full bg-emerald-500"></span>
                                    @endif
                                    @if($dayData['hasNonAktif'])
                                        <span class="size-2 rounded-full bg-zinc-400"></span>
                                    @endif
                                </span>
                            @endif
                        </div>
                        @if($dayData['jadwalCount'] > 0)
                            <div class="mt-1.5 space-y-1">
                                <div class="text-2xs font-semibold {{ $dayData['hasAktif'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-zinc-400' }}">
                                    {{ $dayData['jadwalCount'] }} jadwal
                                </div>
                                @if($dayData['totalAntrian'] > 0)
                                    <div class="text-2xs text-zinc-500 dark:text-zinc-500">
                                        {{ $dayData['totalAntrian'] }} antrian
                                    </div>
                                @endif
                            </div>
                        @endif
                    </button>
                @endif
            @endforeach
        </div>

        <!-- Legend -->
        <div class="flex items-center gap-4 px-6 py-3 border-t border-zinc-200 dark:border-zinc-700 text-xs text-zinc-500">
            <div class="flex items-center gap-1.5">
                <span class="size-2.5 rounded-full bg-emerald-500"></span>
                <span>Jadwal Aktif</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="size-2.5 rounded-full bg-zinc-400"></span>
                <span>Jadwal Nonaktif</span>
            </div>
            <div class="flex items-center gap-1.5">
                <span class="size-4 rounded-full bg-blue-600 text-white text-2xs flex items-center justify-center font-bold">H</span>
                <span>Hari Ini</span>
            </div>
        </div>
    </flux:card>

    <!-- Modal: Tambah Jadwal -->
    <flux:modal wire:model="showFormModal" class="md:w-[28rem] space-y-6">
        <div>
            <flux:heading size="lg">Tambah Jadwal Baru</flux:heading>
            <flux:text size="sm">
                @if($formTanggal)
                    Tanggal: <strong>{{ \Carbon\Carbon::parse($formTanggal)->translatedFormat('l, d F Y') }}</strong>
                @endif
            </flux:text>
        </div>

        <div class="space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <flux:field>
                    <flux:label>Waktu Mulai</flux:label>
                    <flux:input type="time" wire:model="formWaktuMulai" />
                    <flux:error name="formWaktuMulai" />
                </flux:field>
                <flux:field>
                    <flux:label>Waktu Selesai</flux:label>
                    <flux:input type="time" wire:model="formWaktuSelesai" />
                    <flux:error name="formWaktuSelesai" />
                </flux:field>
            </div>

            <flux:field>
                <flux:label>Keterangan / Deskripsi</flux:label>
                <flux:input type="text" wire:model="formKeterangan" placeholder="Misal: Pelayanan Suntik & Pil KB" />
                <flux:error name="formKeterangan" />
            </flux:field>

            <flux:field>
                <flux:label>Kuota Peserta</flux:label>
                <flux:input type="number" min="1" max="100" wire:model="formKuota" />
                <flux:error name="formKuota" />
            </flux:field>
        </div>

        <div class="flex justify-end gap-3">
            <flux:button variant="outline" wire:click="$set('showFormModal', false)">Batal</flux:button>
            <flux:button variant="primary" wire:click="simpanJadwal">Simpan Jadwal</flux:button>
        </div>
    </flux:modal>

    <!-- Modal: Detail Jadwal -->
    <flux:modal wire:model="showDetailModal" class="md:w-[32rem] space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <flux:heading size="lg">Detail Jadwal</flux:heading>
                <flux:text size="sm">
                    @if($detailTanggal)
                        {{ \Carbon\Carbon::parse($detailTanggal)->translatedFormat('l, d F Y') }}
                    @endif
                </flux:text>
            </div>
            <flux:button variant="primary" size="sm" icon="plus" wire:click="tambahJadwalDariDetail">
                Tambah
            </flux:button>
        </div>

        <div class="space-y-3">
            @forelse($detailJadwals as $jadwal)
                <div class="rounded-lg border p-4 space-y-3
                    {{ $jadwal['is_aktif'] ? 'border-emerald-200 bg-emerald-50/50 dark:border-emerald-900 dark:bg-emerald-950/20' : 'border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-800/50' }}">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="flex items-center gap-2">
                                <flux:text class="font-semibold">
                                    {{ substr($jadwal['waktu_mulai'], 0, 5) }} - {{ substr($jadwal['waktu_selesai'], 0, 5) }}
                                </flux:text>
                                @if($jadwal['is_aktif'])
                                    <flux:badge color="green" size="sm">Aktif</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="sm">Nonaktif</flux:badge>
                                @endif
                            </div>
                            @if($jadwal['keterangan'])
                                <flux:text size="sm" class="text-zinc-500 mt-1">{{ $jadwal['keterangan'] }}</flux:text>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3 text-xs">
                            <span class="text-zinc-500">
                                Kuota: <strong class="text-zinc-700 dark:text-zinc-200">{{ $jadwal['terisi'] }}/{{ $jadwal['kuota'] }}</strong>
                            </span>
                            @php $sisa = $jadwal['kuota'] - $jadwal['terisi']; @endphp
                            @if($sisa > 0)
                                <flux:badge color="blue" size="sm">Sisa {{ $sisa }}</flux:badge>
                            @else
                                <flux:badge color="red" size="sm">Penuh</flux:badge>
                            @endif
                        </div>
                        <div class="flex items-center gap-1.5">
                            <flux:button size="xs" variant="{{ $jadwal['is_aktif'] ? 'outline' : 'primary' }}" wire:click="toggleAktif({{ $jadwal['id'] }})">
                                {{ $jadwal['is_aktif'] ? 'Nonaktifkan' : 'Aktifkan' }}
                            </flux:button>
                            <flux:button size="xs" variant="danger" wire:click="startDelete({{ $jadwal['id'] }})">
                                Hapus
                            </flux:button>
                        </div>
                    </div>
                </div>
            @empty
                <flux:text class="text-center py-4 text-zinc-400">Tidak ada jadwal pada tanggal ini.</flux:text>
            @endforelse
        </div>
    </flux:modal>

    <!-- Modal: Konfirmasi Hapus -->
    <flux:modal wire:model="showDeleteModal" class="md:w-[28rem] space-y-6">
        <div class="text-center space-y-3">
            <div class="inline-flex size-12 items-center justify-center rounded-full bg-rose-50 text-rose-600 dark:bg-rose-950/30 dark:text-rose-400">
                <flux:icon name="exclamation-triangle" class="size-6" />
            </div>
            <div>
                <flux:heading size="lg">Konfirmasi Hapus Jadwal</flux:heading>
                <flux:text size="sm">Apakah Anda yakin ingin menghapus jadwal <strong>{{ $deletingInfo }}</strong>? Semua data antrian pada jadwal ini juga akan terhapus.</flux:text>
            </div>
        </div>

        <div class="flex justify-center gap-3">
            <flux:button variant="outline" wire:click="$set('showDeleteModal', false)">Batal</flux:button>
            <flux:button variant="danger" wire:click="hapusJadwal">Ya, Hapus</flux:button>
        </div>
    </flux:modal>
</div>
