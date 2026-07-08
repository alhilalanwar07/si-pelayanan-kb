<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div>
            <flux:heading size="xl" level="1">Riwayat Skrining Medis</flux:heading>
            <flux:text size="sm">Catatan pemeriksaan klinis/fisik awal pasien</flux:text>
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
                    <flux:table.column>Tanggal Skrining</flux:table.column>
                    <flux:table.column>Nama Peserta</flux:table.column>
                    <flux:table.column>GPA</flux:table.column>
                    <flux:table.column>Menyusui</flux:table.column>
                    <flux:table.column>Kondisi Kesehatan</flux:table.column>
                    <flux:table.column>Pemeriksaan Fisik</flux:table.column>
                    <flux:table.column>Consent</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse($skrinings as $skrining)
                        <flux:table.row :key="$skrining->id">
                            <!-- Tanggal -->
                            <flux:table.cell class="font-medium">
                                {{ $skrining->tanggal_skrining->translatedFormat('d M Y') }}
                            </flux:table.cell>

                            <!-- Peserta -->
                            <flux:table.cell>
                                <div class="flex flex-col">
                                    <span class="font-semibold text-zinc-900 dark:text-white">{{ $skrining->pesertaKb->nama_lengkap }}</span>
                                    <span class="text-xs text-zinc-500 font-mono">NIK: {{ $skrining->pesertaKb->nik }}</span>
                                </div>
                            </flux:table.cell>

                            <!-- GPA -->
                            <flux:table.cell>{{ $skrining->gravida_partus_abortus ?? '-' }}</flux:table.cell>

                            <!-- Menyusui -->
                            <flux:table.cell>
                                @if($skrining->status_menyusui)
                                    <flux:badge color="blue" size="xs">Ya</flux:badge>
                                @else
                                    <flux:badge color="zinc" size="xs">Tidak</flux:badge>
                                @endif
                            </flux:table.cell>

                            <!-- Riwayat Penyakit -->
                            <flux:table.cell>
                                @if($skrining->adaRiwayatPenyakit())
                                    <div class="flex flex-wrap gap-1 max-w-[150px]">
                                        @foreach($skrining->riwayatPenyakitList() as $sakit)
                                            <flux:badge color="rose" size="2xs">{{ $sakit }}</flux:badge>
                                        @endforeach
                                    </div>
                                @else
                                    <flux:badge color="green" size="2xs">Sehat / Normal</flux:badge>
                                @endif
                            </flux:table.cell>

                            <!-- Pemeriksaan Fisik -->
                            <flux:table.cell>
                                <div class="text-xs space-y-0.5">
                                    <div>Keadaan: {{ $skrining->fisik_keadaan_umum ?? '-' }}</div>
                                    <div>BB: {{ $skrining->fisik_berat_badan ?? '-' }} kg</div>
                                    <div>TD: {{ $skrining->fisik_tekanan_darah ?? '-' }}</div>
                                    <div>Rahim: {{ $skrining->posisi_rahim ?? '-' }}</div>
                                </div>
                            </flux:table.cell>

                            <!-- Informed Consent -->
                            <flux:table.cell>
                                @if($skrining->informedConsent)
                                    <flux:badge color="green" size="xs">Diberikan</flux:badge>
                                @else
                                    <flux:badge color="red" size="xs">Belum / Ditolak</flux:badge>
                                @endif
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="7" class="text-center text-zinc-500 py-6">
                                Belum ada riwayat skrining medis yang tercatat.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>

        <div class="mt-4">
            {{ $skrinings->links() }}
        </div>
    </flux:card>
</div>
