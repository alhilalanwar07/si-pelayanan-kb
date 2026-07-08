<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Informed Consent</flux:heading>
            <flux:text size="sm">Daftar dokumen persetujuan tindakan medis KB</flux:text>
        </div>
    </div>

    <!-- Search Bar -->
    <flux:card>
        <div class="w-full max-w-sm">
            <flux:input type="text" placeholder="Cari nama peserta, NIK..." wire:model.live="search" icon="magnifying-glass" />
        </div>
    </flux:card>

    <!-- Table -->
    <flux:card>
        <div class="overflow-x-auto">
            <flux:table>
                <flux:table.columns>
                    <flux:table.column>Tanggal Persetujuan</flux:table.column>
                    <flux:table.column>Nama Peserta</flux:table.column>
                    <flux:table.column>Jenis Tindakan</flux:table.column>
                    <flux:table.column>Persetujuan Klien</flux:table.column>
                    <flux:table.column>Persetujuan Pasangan</flux:table.column>
                    <flux:table.column>Status Dokumen</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($consents as $consent)
                        <flux:table.row :key="$consent->id">
                            <!-- Tanggal -->
                            <flux:table.cell class="font-medium">
                                {{ $consent->tanggal_persetujuan->translatedFormat('d M Y') }}
                            </flux:table.cell>

                            <!-- Peserta -->
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $consent->skriningMedis->pesertaKb->nama_lengkap }}</span>
                                    <span class="text-xs text-zinc-500 font-mono">NIK: {{ $consent->skriningMedis->pesertaKb->nik }}</span>
                                </div>
                            </flux:table.cell>

                            <!-- Jenis Tindakan -->
                            <flux:table.cell>
                                <span class="font-semibold text-zinc-900 dark:text-white capitalize">
                                    {{ $consent->labelTindakan() }}
                                </span>
                            </flux:table.cell>

                            <!-- Klien -->
                            <flux:table.cell>
                                @if($consent->persetujuan_klien)
                                    <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-semibold text-xs">
                                        <flux:icon name="check" class="size-4" /> Setuju
                                    </span>
                                @else
                                    <span class="text-red-500 font-semibold text-xs">Belum / Tolak</span>
                                @endif
                            </flux:table.cell>

                            <!-- Pasangan -->
                            <flux:table.cell>
                                @if($consent->persetujuan_pasangan)
                                    <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-semibold text-xs">
                                        <flux:icon name="check" class="size-4" /> Setuju
                                    </span>
                                @else
                                    <span class="text-red-500 font-semibold text-xs">Belum / Tolak</span>
                                @endif
                            </flux:table.cell>

                            <!-- Status Dokumen -->
                            <flux:table.cell>
                                @if($consent->isLengkap())
                                    <flux:badge color="green" size="sm">Lengkap (SAH)</flux:badge>
                                @else
                                    <flux:badge color="red" size="sm">Tidak Lengkap</flux:badge>
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="text-center text-zinc-500 py-6">
                                Belum ada dokumen informed consent yang tercatat.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-4">
            {{ $consents->links() }}
        </div>
    </flux:card>
</div>
