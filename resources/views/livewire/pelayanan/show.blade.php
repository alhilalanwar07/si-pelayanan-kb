<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div class="flex items-center gap-3">
            <flux:button variant="outline" icon="arrow-left" href="{{ route('pelayanan.index') }}" wire:navigate />
            <div>
                <flux:heading size="xl" level="1">Detail Catatan Pelayanan KB</flux:heading>
                <flux:text size="sm">Informasi lengkap skrining medis, persetujuan tindakan, dan kontrasepsi</flux:text>
            </div>
        </div>
        <flux:button variant="primary" icon="printer" href="{{ route('pelayanan.cetak', $pelayanan->id) }}" target="_blank">
            Cetak Formulir (PDF)
        </flux:button>
    </div>

    <!-- Main Grid -->
    <div class="grid gap-6 md:grid-cols-3">
        <!-- Sidebar Participant Profile -->
        <flux:card class="space-y-4">
            <div class="flex items-center gap-3 border-b border-zinc-150 pb-4 dark:border-zinc-800">
                <div class="flex size-12 items-center justify-center rounded-full bg-blue-50 text-blue-600 font-bold dark:bg-blue-950 dark:text-blue-400">
                    {{ strtoupper(substr($pelayanan->pesertaKb->nama_lengkap, 0, 2)) }}
                </div>
                <div>
                    <flux:heading size="md" class="font-bold">{{ $pelayanan->pesertaKb->nama_lengkap }}</flux:heading>
                    <flux:text size="xs" class="font-mono">NIK: {{ $pelayanan->pesertaKb->nik }}</flux:text>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Nama Suami / Istri</span>
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $pelayanan->pesertaKb->nama_suami_istri }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Tanggal Lahir Istri</span>
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $pelayanan->pesertaKb->tanggal_lahir_istri->translatedFormat('d F Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Wilayah / Kelurahan</span>
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $pelayanan->pesertaKb->wilayah->nama_desa_kelurahan }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Alamat Lengkap</span>
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $pelayanan->pesertaKb->alamat_lengkap }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Asuransi Kesehatan</span>
                    <span class="font-medium uppercase text-zinc-900 dark:text-white">{{ $pelayanan->pesertaKb->penggunaan_asuransi ?? '-' }}</span>
                </div>
            </div>
        </flux:card>

        <!-- Details Area (Col Span 2) -->
        <div class="md:col-span-2 space-y-6">
            <!-- Section 1: Skrining Medis -->
            <flux:card class="space-y-4">
                <div class="flex items-center gap-2 border-b border-zinc-150 pb-2 dark:border-zinc-800">
                    <flux:icon name="heart" class="size-5 text-blue-500" />
                    <flux:heading size="lg">1. Hasil Skrining Medis</flux:heading>
                </div>

                @if($pelayanan->skriningMedis)
                    <div class="grid gap-4 sm:grid-cols-3 text-sm">
                        <div>
                            <span class="block text-xs text-zinc-500">Tanggal Skrining</span>
                            <span class="font-medium text-zinc-950 dark:text-white">{{ $pelayanan->skriningMedis->tanggal_skrining->translatedFormat('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-500">Haid Terakhir</span>
                            <span class="font-medium text-zinc-950 dark:text-white">{{ $pelayanan->skriningMedis->haid_terakhir ? $pelayanan->skriningMedis->haid_terakhir->translatedFormat('d M Y') : '-' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-500">GPA (Gravida/Partus/Abortus)</span>
                            <span class="font-medium text-zinc-950 dark:text-white">{{ $pelayanan->skriningMedis->gravida_partus_abortus ?? '-' }}</span>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2 text-sm pt-2">
                        <div>
                            <span class="block text-xs text-zinc-500">Sedang Menyusui</span>
                            <span class="font-medium text-zinc-950 dark:text-white">{{ $pelayanan->skriningMedis->status_menyusui ? 'Ya' : 'Tidak' }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-500">Kondisi / Riwayat Penyakit</span>
                            <div class="flex flex-wrap gap-1 mt-1">
                                @if($pelayanan->skriningMedis->adaRiwayatPenyakit())
                                    @foreach($pelayanan->skriningMedis->riwayatPenyakitList() as $sakit)
                                        <flux:badge color="rose" size="2xs">{{ $sakit }}</flux:badge>
                                    @endforeach
                                @else
                                    <flux:badge color="green" size="2xs">Sehat / Normal</flux:badge>
                                @endif
                            </div>
                        </div>
                    </div>

                    <flux:separator />

                    <div class="space-y-2">
                        <flux:label class="font-semibold block">Pemeriksaan Fisik</flux:label>
                        <div class="grid gap-4 sm:grid-cols-4 text-sm">
                            <div>
                                <span class="block text-xs text-zinc-500">Keadaan Umum</span>
                                <span class="font-medium capitalize text-zinc-950 dark:text-white">{{ $pelayanan->skriningMedis->fisik_keadaan_umum ?? '-' }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-zinc-500">Berat Badan</span>
                                <span class="font-medium text-zinc-950 dark:text-white">{{ $pelayanan->skriningMedis->fisik_berat_badan ?? '-' }} kg</span>
                            </div>
                            <div>
                                <span class="block text-xs text-zinc-500">Tekanan Darah</span>
                                <span class="font-medium text-zinc-950 dark:text-white">{{ $pelayanan->skriningMedis->fisik_tekanan_darah ?? '-' }} mmHg</span>
                            </div>
                            <div>
                                <span class="block text-xs text-zinc-500">Posisi Rahim</span>
                                <span class="font-medium capitalize text-zinc-950 dark:text-white">{{ $pelayanan->skriningMedis->posisi_rahim ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <flux:text class="text-zinc-500 text-center py-4">Data skrining medis tidak tersedia.</flux:text>
                @endif
            </flux:card>

            <!-- Section 2: Informed Consent -->
            <flux:card class="space-y-4">
                <div class="flex items-center gap-2 border-b border-zinc-150 pb-2 dark:border-zinc-800">
                    <flux:icon name="document-check" class="size-5 text-emerald-500" />
                    <flux:heading size="lg">2. Persetujuan Tindakan (Informed Consent)</flux:heading>
                </div>

                @if($pelayanan->skriningMedis && $pelayanan->skriningMedis->informedConsent)
                    <div class="grid gap-4 sm:grid-cols-3 text-sm">
                        <div>
                            <span class="block text-xs text-zinc-500">Jenis Tindakan</span>
                            <span class="font-semibold text-zinc-950 dark:text-white capitalize">{{ $pelayanan->skriningMedis->informedConsent->jenis_tindakan_medis }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-500">Tanggal Persetujuan</span>
                            <span class="font-medium text-zinc-950 dark:text-white">{{ $pelayanan->skriningMedis->informedConsent->tanggal_persetujuan->translatedFormat('d M Y') }}</span>
                        </div>
                        <div>
                            <span class="block text-xs text-zinc-500">Status Persetujuan</span>
                            <div class="flex items-center gap-1 mt-1 text-emerald-600 dark:text-emerald-400 font-semibold text-xs">
                                <flux:icon name="check-circle" class="size-4" />
                                <span>Klien & Pasangan Menyetujui</span>
                            </div>
                        </div>
                    </div>
                @else
                    <flux:text class="text-zinc-500 text-center py-4">Data persetujuan tidak tersedia.</flux:text>
                @endif
            </flux:card>

            <!-- Section 3: Pemberian Alokon -->
            <flux:card class="space-y-4">
                <div class="flex items-center gap-2 border-b border-zinc-150 pb-2 dark:border-zinc-800">
                    <flux:icon name="clipboard-document-check" class="size-5 text-indigo-500" />
                    <flux:heading size="lg">3. Pemberian Alokon & Pelayanan</flux:heading>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 text-sm">
                    <div>
                        <span class="block text-xs text-zinc-500">Alat/Obat Kontrasepsi</span>
                        <span class="font-semibold text-zinc-950 dark:text-white text-base">{{ $pelayanan->alokon->nama_alokon }}</span>
                    </div>
                    <div>
                        <span class="block text-xs text-zinc-500">Tanggal Pemberian</span>
                        <span class="font-medium text-zinc-950 dark:text-white text-base">{{ $pelayanan->tanggal_pelayanan->translatedFormat('d F Y') }}</span>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 text-sm pt-2">
                    <div>
                        <span class="block text-xs text-zinc-500">Faskes Pelaksana</span>
                        <span class="font-medium text-zinc-950 dark:text-white">{{ $pelayanan->alokon->instansi->nama_instansi }}</span>
                    </div>
                </div>

                <flux:separator />

                <div>
                    <span class="block text-xs text-zinc-500">Catatan Bidan / Keterangan</span>
                    <p class="mt-1 p-3 rounded-lg bg-zinc-50 dark:bg-zinc-800 text-sm text-zinc-800 dark:text-zinc-200 border border-zinc-150 dark:border-zinc-750">
                        {{ $pelayanan->keterangan ?? 'Tidak ada catatan tambahan.' }}
                    </p>
                </div>
            </flux:card>
        </div>
    </div>
</div>
