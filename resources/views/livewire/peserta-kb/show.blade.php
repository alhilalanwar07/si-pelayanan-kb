<div class="flex-1 space-y-6 p-6">
    <!-- Header -->
    <div class="flex items-center justify-between border-b border-zinc-200 pb-4 dark:border-zinc-700">
        <div class="flex items-center gap-3">
            <flux:button variant="outline" icon="arrow-left" href="{{ route('peserta-kb.index') }}" wire:navigate />
            <div>
                <flux:heading size="xl" level="1">Detail Peserta KB</flux:heading>
                <flux:text size="sm">Informasi profil dan riwayat medis peserta</flux:text>
            </div>
        </div>
        <div class="flex items-center gap-2">
            @if(auth()->user()->isAdmin() && !$pesertaKb->isTerverifikasi())
                <flux:button variant="primary" wire:click="verifikasi" wire:loading.attr="disabled">
                    Verifikasi Peserta
                </flux:button>
            @endif
        </div>
    </div>

    <!-- Main Grid -->
    <div class="grid gap-6 lg:grid-cols-3">
        <!-- Profile Card (Col Span 1) -->
        <flux:card class="space-y-6">
            <div class="flex flex-col items-center text-center space-y-3 pb-4 border-b border-zinc-150 dark:border-zinc-800">
                <div class="flex size-16 items-center justify-center rounded-full bg-blue-100 text-blue-700 text-xl font-bold dark:bg-blue-950 dark:text-blue-400">
                    {{ strtoupper(substr($pesertaKb->nama_lengkap, 0, 2)) }}
                </div>
                <div>
                    <flux:heading size="lg">{{ $pesertaKb->nama_lengkap }}</flux:heading>
                    <flux:text size="xs" class="font-mono">NIK: {{ $pesertaKb->nik }}</flux:text>
                </div>
                <div>
                    @if($pesertaKb->isTerverifikasi())
                        <flux:badge color="green" size="sm">Terverifikasi</flux:badge>
                    @else
                        <flux:badge color="amber" size="sm">Menunggu Verifikasi</flux:badge>
                    @endif
                </div>
            </div>

            <!-- Detail List -->
            <div class="space-y-4 text-sm">
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Nama Suami / Istri</span>
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $pesertaKb->nama_suami_istri }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Nomor WhatsApp</span>
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-zinc-900 dark:text-white font-mono">{{ $pesertaKb->nomor_hp ?? '-' }}</span>
                        @if($pesertaKb->nomor_hp)
                            <a href="{{ $pesertaKb->whatsapp_link }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400 font-semibold hover:underline">
                                <flux:icon name="chat-bubble-left-right" class="size-4 shrink-0" />
                                <span>Kirim Jadwal (WA)</span>
                            </a>
                        @endif
                    </div>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Tanggal Lahir Istri</span>
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $pesertaKb->tanggal_lahir_istri->translatedFormat('d F Y') }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Wilayah</span>
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $pesertaKb->wilayah->nama_desa_kelurahan }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Alamat Lengkap</span>
                    <span class="font-medium text-zinc-900 dark:text-white">{{ $pesertaKb->alamat_lengkap }}</span>
                </div>
                <div>
                    <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Penggunaan Asuransi</span>
                    <span class="font-medium uppercase text-zinc-900 dark:text-white">{{ $pesertaKb->penggunaan_asuransi ?? '-' }}</span>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Anak Hidup</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $pesertaKb->jumlah_anak_hidup }} anak</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-zinc-500">Umur Anak Terakhir</span>
                        <span class="font-medium text-zinc-900 dark:text-white">{{ $pesertaKb->umur_anak_terakhir ? $pesertaKb->umur_anak_terakhir . ' bulan' : '-' }}</span>
                    </div>
                </div>
            </div>
        </flux:card>

        <!-- Medical History (Col Span 2) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Pelayanan KB Card -->
            <flux:card>
                <div class="border-b border-zinc-200 pb-3 dark:border-zinc-700">
                    <flux:heading size="lg">📋 Riwayat Pelayanan Kontrasepsi</flux:heading>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Tanggal</flux:table.column>
                            <flux:table.column>Jenis Alokon</flux:table.column>
                            <flux:table.column>Pemeriksaan</flux:table.column>
                            <flux:table.column>Tindakan</flux:table.column>
                            <flux:table.column>Keterangan</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @forelse($pesertaKb->pelayanans as $pelayanan)
                                <flux:table.row :key="$pelayanan->id">
                                    <flux:table.cell class="font-medium">{{ $pelayanan->tanggal_pelayanan->translatedFormat('d M Y') }}</flux:table.cell>
                                    <flux:table.cell>
                                        <div class="flex flex-col">
                                            <span class="font-semibold text-zinc-950 dark:text-white">{{ $pelayanan->alokon->nama_alokon }}</span>
                                            <span class="text-xs text-zinc-500">Faskes: {{ $pelayanan->alokon->instansi->nama_instansi }}</span>
                                        </div>
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        @if($pelayanan->skriningMedis)
                                            <div class="text-xs space-y-0.5">
                                                <div>TD: {{ $pelayanan->skriningMedis->fisik_tekanan_darah ?? '-' }}</div>
                                                <div>BB: {{ $pelayanan->skriningMedis->fisik_berat_badan ?? '-' }} kg</div>
                                            </div>
                                        @else
                                            <span class="text-xs text-zinc-400">-</span>
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        @if($pelayanan->skriningMedis && $pelayanan->skriningMedis->informedConsent)
                                            <span class="capitalize text-xs font-semibold">{{ $pelayanan->skriningMedis->informedConsent->jenis_tindakan_medis }}</span>
                                        @else
                                            <span class="text-xs text-zinc-400">-</span>
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <span class="text-xs text-zinc-600 dark:text-zinc-400">{{ $pelayanan->keterangan ?? '-' }}</span>
                                    </flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="5" class="text-center text-zinc-500 py-6">
                                        Belum ada riwayat pelayanan untuk peserta ini.
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </div>
            </flux:card>

            <!-- Skrining Medis Card -->
            <flux:card>
                <div class="border-b border-zinc-200 pb-3 dark:border-zinc-700">
                    <flux:heading size="lg">🩺 Riwayat Skrining Medis</flux:heading>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <flux:table>
                        <flux:table.columns>
                            <flux:table.column>Tanggal Skrining</flux:table.column>
                            <flux:table.column>Haid Terakhir</flux:table.column>
                            <flux:table.column>GPA</flux:table.column>
                            <flux:table.column>Menyusui</flux:table.column>
                            <flux:table.column>Riwayat Sakit</flux:table.column>
                            <flux:table.column>Pemeriksaan Fisik</flux:table.column>
                        </flux:table.columns>

                        <flux:table.rows>
                            @forelse($pesertaKb->skriningMedis as $skrining)
                                <flux:table.row :key="$skrining->id">
                                    <flux:table.cell class="font-medium">{{ $skrining->tanggal_skrining->translatedFormat('d M Y') }}</flux:table.cell>
                                    <flux:table.cell>{{ $skrining->haid_terakhir ? $skrining->haid_terakhir->translatedFormat('d/m/y') : '-' }}</flux:table.cell>
                                    <flux:table.cell>{{ $skrining->gravida_partus_abortus ?? '-' }}</flux:table.cell>
                                    <flux:table.cell>
                                        @if($skrining->status_menyusui)
                                            <flux:badge color="blue" size="xs">Ya</flux:badge>
                                        @else
                                            <flux:badge color="zinc" size="xs">Tidak</flux:badge>
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        @if($skrining->adaRiwayatPenyakit())
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($skrining->riwayatPenyakitList() as $sakit)
                                                    <flux:badge color="rose" size="2xs">{{ $sakit }}</flux:badge>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">Sehat / Normal</span>
                                        @endif
                                    </flux:table.cell>
                                    <flux:table.cell>
                                        <div class="text-xs space-y-0.5">
                                            <div>Keadaan: {{ $skrining->fisik_keadaan_umum ?? '-' }}</div>
                                            <div>BB: {{ $skrining->fisik_berat_badan ?? '-' }} kg</div>
                                            <div>TD: {{ $skrining->fisik_tekanan_darah ?? '-' }}</div>
                                            <div>Rahim: {{ $skrining->posisi_rahim ?? '-' }}</div>
                                        </div>
                                    </flux:table.cell>
                                </flux:table.row>
                            @empty
                                <flux:table.row>
                                    <flux:table.cell colspan="6" class="text-center text-zinc-500 py-6">
                                        Belum ada riwayat skrining medis untuk peserta ini.
                                    </flux:table.cell>
                                </flux:table.row>
                            @endforelse
                        </flux:table.rows>
                    </flux:table>
                </div>
            </flux:card>
        </div>
    </div>
</div>
