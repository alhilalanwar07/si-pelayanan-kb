<div wire:poll.10s class="w-full">
    @if($jadwalHariIni)
        {{-- ==================== STATE 1: ADA JADWAL HARI INI ==================== --}}
        <div class="relative rounded-2xl sm:rounded-3xl bg-slate-900/85 backdrop-blur-xl border border-slate-700/70 p-4 sm:p-5 shadow-2xl shadow-slate-950/60 overflow-hidden">
            <!-- Glow Accent -->
            <div class="absolute -top-10 -right-10 size-32 bg-blue-500/15 rounded-full blur-2xl pointer-events-none"></div>
            <div class="absolute -bottom-10 -left-10 size-32 bg-emerald-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Top Header & Live Status -->
            <div class="relative z-10 flex items-center justify-between gap-2 pb-3 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    @if($statusLoket === 'melayani')
                        <span class="relative flex size-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full size-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-2xs font-extrabold uppercase tracking-wider text-emerald-400">
                            Loket Aktif Melayani
                        </span>
                    @elseif($statusLoket === 'menunggu_panggilan')
                        <span class="relative flex size-2.5">
                            <span class="relative inline-flex rounded-full size-2.5 bg-amber-400 animate-pulse"></span>
                        </span>
                        <span class="text-2xs font-extrabold uppercase tracking-wider text-amber-300">
                            Loket Siap • Menunggu Panggilan
                        </span>
                    @elseif($statusLoket === 'persiapan')
                        <span class="relative flex size-2.5">
                            <span class="relative inline-flex rounded-full size-2.5 bg-blue-400 animate-pulse"></span>
                        </span>
                        <span class="text-2xs font-extrabold uppercase tracking-wider text-blue-300">
                            Persiapan Pelayanan
                        </span>
                    @elseif($statusLoket === 'selesai')
                        <span class="size-2.5 rounded-full bg-emerald-400"></span>
                        <span class="text-2xs font-extrabold uppercase tracking-wider text-emerald-300">
                            Pelayanan Hari Ini Selesai
                        </span>
                    @else
                        <span class="size-2.5 rounded-full bg-slate-500"></span>
                        <span class="text-2xs font-extrabold uppercase tracking-wider text-slate-400">
                            Loket Belum Dibuka
                        </span>
                    @endif
                </div>

                <!-- Clock & Refresh Indicator -->
                <div class="flex items-center gap-2">
                    <div class="flex items-center gap-1 text-2xs text-slate-400 font-mono">
                        <flux:icon name="clock" class="size-3 text-slate-500" />
                        <span>{{ substr($jadwalHariIni->waktu_mulai, 0, 5) }} - {{ substr($jadwalHariIni->waktu_selesai, 0, 5) }} WITA</span>
                    </div>
                    <button type="button" wire:click="$refresh" title="Perbarui antrian real-time"
                        class="size-6 rounded-lg bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-400 hover:text-white flex items-center justify-center transition-all cursor-pointer">
                        <svg wire:loading.class="animate-spin text-blue-400" class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Central Digital Queue Display Screen -->
            <div class="relative z-10 my-3 rounded-2xl bg-slate-950/90 border border-slate-800/80 p-4 sm:p-5 text-center shadow-inner overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-transparent via-blue-500/60 to-transparent"></div>

                <div class="text-3xs uppercase tracking-widest font-extrabold mb-1">
                    @if($antrianBerjalan)
                        <span class="text-blue-400">Nomor Antrian Sedang Dilayani</span>
                    @elseif($statusLoket === 'selesai')
                        <span class="text-emerald-400">Semua Antrian Selesai</span>
                    @elseif($antrianBerikutnya)
                        <span class="text-amber-400">Antrian Siap Dipanggil</span>
                    @else
                        <span class="text-slate-400">Status Antrian Berjalan</span>
                    @endif
                </div>

                <!-- Big Number -->
                <div class="py-1">
                    @if($antrianBerjalan)
                        <div class="text-4xl sm:text-5xl lg:text-6xl font-black font-mono tracking-tight text-white drop-shadow-[0_2px_20px_rgba(59,130,246,0.6)]">
                            {{ $antrianBerjalanDisplay ?? ('#' . str_pad($antrianBerjalan, 3, '0', STR_PAD_LEFT)) }}
                        </div>
                    @elseif($statusLoket === 'selesai')
                        <div class="text-3xl sm:text-4xl lg:text-5xl font-black font-mono tracking-tight text-emerald-400 drop-shadow-[0_2px_15px_rgba(16,185,129,0.4)]">
                            SELESAI
                        </div>
                        <div class="text-2xs text-emerald-300/80 mt-1">Total {{ $totalSelesai }} peserta telah selesai</div>
                    @elseif($antrianBerikutnya)
                        <div class="text-4xl sm:text-5xl lg:text-6xl font-black font-mono tracking-tight text-amber-300 drop-shadow-[0_2px_15px_rgba(251,191,36,0.4)]">
                            {{ $antrianBerikutnyaDisplay ?? ('#' . str_pad($antrianBerikutnya, 3, '0', STR_PAD_LEFT)) }}
                        </div>
                        <div class="text-2xs text-amber-300/80 mt-1">Siap dipanggil oleh petugas</div>
                    @else
                        <div class="text-3xl sm:text-4xl font-black font-mono text-slate-500">
                            000
                        </div>
                        <div class="text-2xs text-slate-400 mt-1">Belum ada antrian dipanggil</div>
                    @endif
                </div>

                <!-- Sub-info: Panggilan Berikutnya & Terakhir Selesai -->
                <div class="mt-2 flex flex-wrap items-center justify-center gap-2">
                    @if($antrianBerjalan && $antrianBerikutnya)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900 border border-slate-700/70 text-2xs text-slate-300">
                            <span class="text-slate-400">Panggilan Berikutnya:</span>
                            <strong class="text-amber-300 font-mono font-bold">{{ $antrianBerikutnyaDisplay ?? ('#' . str_pad($antrianBerikutnya, 3, '0', STR_PAD_LEFT)) }}</strong>
                        </div>
                    @endif

                    @if($nomorTerakhirSelesai)
                        <div class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-950/60 border border-emerald-800/40 text-3xs text-emerald-300 font-mono">
                            <flux:icon name="check" class="size-3 text-emerald-400" />
                            <span>Terakhir Selesai: <strong>{{ $nomorTerakhirSelesaiDisplay ?? ('#' . str_pad($nomorTerakhirSelesai, 3, '0', STR_PAD_LEFT)) }}</strong></span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Mini Stats 3-Grid -->
            <div class="relative z-10 grid grid-cols-3 gap-2 text-center">
                <div class="p-2 sm:p-2.5 rounded-xl bg-slate-800/60 border border-slate-700/50">
                    <div class="text-3xs text-slate-400 uppercase font-semibold">Terdaftar</div>
                    <div class="text-sm sm:text-base font-black text-white font-mono mt-0.5">{{ $totalAntrianHariIni }}</div>
                </div>

                <div class="p-2 sm:p-2.5 rounded-xl bg-emerald-950/30 border border-emerald-800/40">
                    <div class="text-3xs text-emerald-400 uppercase font-semibold">Selesai</div>
                    <div class="text-sm sm:text-base font-black text-emerald-400 font-mono mt-0.5">{{ $totalSelesai }}</div>
                </div>

                <div class="p-2 sm:p-2.5 rounded-xl bg-amber-950/30 border border-amber-800/40">
                    <div class="text-3xs text-amber-300 uppercase font-semibold">Sisa Menunggu</div>
                    <div class="text-sm sm:text-base font-black text-amber-300 font-mono mt-0.5">{{ $sisaAntrian }}</div>
                </div>
            </div>

            <!-- Bottom Action Link -->
            <div class="relative z-10 mt-3 pt-2.5 border-t border-slate-800 flex items-center justify-between gap-2">
                <span class="text-3xs text-slate-400">Punya tiket antrian?</span>
                <a href="{{ route('registrasi') }}" class="inline-flex items-center gap-1 text-2xs font-bold text-blue-400 hover:text-blue-300 transition-colors" wire:navigate>
                    <span>Cek Status / Daftar Antrian</span>
                    <flux:icon name="arrow-right" class="size-3" />
                </a>
            </div>
        </div>

    @else
        {{-- ==================== STATE 2: TIDAK ADA JADWAL HARI INI ==================== --}}
        <div class="relative rounded-2xl sm:rounded-3xl bg-slate-900/85 backdrop-blur-xl border border-slate-700/70 p-4 sm:p-5 shadow-2xl shadow-slate-950/60 overflow-hidden">
            <!-- Glow Accent -->
            <div class="absolute -top-10 -right-10 size-32 bg-amber-500/10 rounded-full blur-2xl pointer-events-none"></div>

            <!-- Top Header & Status -->
            <div class="relative z-10 flex items-center justify-between gap-2 pb-3 border-b border-slate-800">
                <div class="flex items-center gap-2">
                    <span class="relative flex size-2.5">
                        <span class="relative inline-flex rounded-full size-2.5 bg-rose-500"></span>
                    </span>
                    <span class="text-2xs font-extrabold uppercase tracking-wider text-rose-400">
                        Loket Pelayanan Tutup
                    </span>
                </div>
                <div class="text-3xs text-slate-400 font-medium">
                    Hari Ini Tidak Ada Sesi
                </div>
            </div>

            <!-- Central Digital Display Screen (LED 000 Blinking) -->
            <div class="relative z-10 my-3 rounded-2xl bg-slate-950/90 border border-slate-800/80 p-4 sm:p-5 text-center shadow-inner overflow-hidden">
                <div class="absolute inset-x-0 top-0 h-0.5 bg-gradient-to-r from-transparent via-amber-500/50 to-transparent"></div>

                <div class="text-3xs uppercase tracking-widest font-extrabold text-slate-400 mb-1">
                    Status Nomor Antrian
                </div>

                <!-- Digital LED Blinking 000 -->
                <div class="py-1">
                    <div class="animate-blink-led text-4xl sm:text-5xl lg:text-6xl font-black font-mono tracking-widest text-amber-400 drop-shadow-[0_0_20px_rgba(251,191,36,0.6)]">
                        000
                    </div>
                </div>

                <div class="mt-1 text-xs font-bold text-slate-300">
                    Tidak ada pelayanan hari ini
                </div>
                <p class="text-3xs text-slate-400 mt-0.5">
                    Pelayanan KB tatap muka di puskesmas dibuka sesuai jadwal berkala.
                </p>
            </div>

            <!-- Next Available Schedule Card -->
            @if($jadwalBerikutnya)
                <div class="relative z-10 p-3 rounded-xl bg-blue-950/40 border border-blue-900/50 flex items-center justify-between gap-3 text-left">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <div class="size-8 rounded-lg bg-blue-500/20 text-blue-400 flex items-center justify-center shrink-0">
                            <flux:icon name="calendar-days" class="size-4" />
                        </div>
                        <div class="min-w-0">
                            <div class="text-3xs font-bold uppercase tracking-wider text-blue-300">Jadwal Pelayanan Berikutnya:</div>
                            <div class="text-xs font-bold text-white truncate">
                                {{ $jadwalBerikutnya->tanggal->translatedFormat('l, d F Y') }}
                            </div>
                            <div class="text-3xs text-slate-400 font-mono">
                                Pukul {{ substr($jadwalBerikutnya->waktu_mulai, 0, 5) }} - {{ substr($jadwalBerikutnya->waktu_selesai, 0, 5) }} WITA
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="relative z-10 p-2.5 rounded-xl bg-slate-800/40 border border-slate-700/50 text-center text-2xs text-slate-400">
                    Belum ada jadwal pelayanan terdekat yang dibuka.
                </div>
            @endif

            <!-- Bottom Action Link -->
            <div class="relative z-10 mt-3 pt-2.5 border-t border-slate-800 flex items-center justify-between gap-2">
                <span class="text-3xs text-slate-400">Ingin daftar jadwal mendatang?</span>
                <a href="{{ route('registrasi') }}" class="inline-flex items-center gap-1 text-2xs font-bold text-blue-400 hover:text-blue-300 transition-colors" wire:navigate>
                    <span>Daftar Antrian Online</span>
                    <flux:icon name="arrow-right" class="size-3" />
                </a>
            </div>
        </div>
    @endif
</div>
