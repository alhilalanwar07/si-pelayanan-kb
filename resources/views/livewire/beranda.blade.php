<div class="min-h-screen bg-slate-50 dark:bg-zinc-950 font-sans text-slate-800 dark:text-zinc-100 selection:bg-blue-600 selection:text-white">
    <!-- ============ TOP NAVBAR ============ -->
    <header class="sticky top-0 z-50 bg-white/85 dark:bg-zinc-900/85 backdrop-blur-md border-b border-slate-200/80 dark:border-zinc-800/80 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group" wire:navigate>
                <div class="flex size-10 sm:size-11 items-center justify-center rounded-2xl bg-gradient-to-tr from-blue-600 to-indigo-500 text-white font-black text-base shadow-lg shadow-blue-500/25 group-hover:scale-105 transition-transform">
                    KB
                </div>
                <div>
                    <div class="font-extrabold text-base tracking-tight text-slate-900 dark:text-white flex items-center gap-1.5">
                        SI Pelayanan KB
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-2xs font-semibold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                            Wundulako
                        </span>
                    </div>
                    <div class="text-xs text-slate-500 dark:text-zinc-400 font-medium">Puskesmas Wundulako, Kolaka</div>
                </div>
            </a>

            <!-- Navigation Actions -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <a href="#jadwal" class="hidden sm:inline-flex text-xs font-semibold text-slate-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-white px-3 py-2 transition-colors">
                    Jadwal Layanan
                </a>
                <a href="#metode" class="hidden md:inline-flex text-xs font-semibold text-slate-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-white px-3 py-2 transition-colors">
                    Metode KB
                </a>
                <a href="#faq" class="hidden md:inline-flex text-xs font-semibold text-slate-600 hover:text-blue-600 dark:text-zinc-400 dark:hover:text-white px-3 py-2 transition-colors">
                    FAQ
                </a>

                @auth
                    <flux:button variant="primary" size="sm" href="{{ route('dashboard') }}" icon="squares-2x2" wire:navigate class="rounded-xl shadow-md shadow-blue-600/20">
                        Dashboard
                    </flux:button>
                @else
                    <flux:button variant="outline" size="sm" href="{{ route('registrasi') }}" icon="user-plus" wire:navigate class="rounded-xl font-semibold border-slate-300 dark:border-zinc-700">
                        Daftar Antrian
                    </flux:button>
                    <flux:button variant="primary" size="sm" href="{{ route('login') }}" icon="arrow-right-end-on-rectangle" wire:navigate class="rounded-xl font-semibold shadow-md shadow-blue-600/20">
                        Masuk
                    </flux:button>
                @endauth
            </div>
        </div>
    </header>

    <!-- ============ HERO SECTION ============ -->
    <section class="relative overflow-hidden bg-gradient-to-b from-slate-900 via-blue-950 to-slate-900 text-white pt-12 pb-24 sm:pt-20 sm:pb-32">
        <!-- Glow Orbs & Grid Decoration -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 size-[600px] bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-10 right-10 size-[350px] bg-indigo-500/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:24px_24px] opacity-10 pointer-events-none"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                <!-- Left Hero Content -->
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <!-- Live Status Badge -->
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/10 backdrop-blur-md border border-white/20 px-4 py-1.5 text-xs font-semibold text-blue-200 shadow-inner">
                        <span class="relative flex size-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full size-2 bg-emerald-400"></span>
                        </span>
                        Pelayanan KB Resmi Puskesmas Wundulako
                    </div>

                    <!-- Main Headline -->
                    <h1 class="text-3xl sm:text-5xl lg:text-6xl font-black tracking-tight leading-[1.15]">
                        Layanan KB Terpadu, <br>
                        <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-300 via-sky-200 to-indigo-200">
                            Cepat, Nyaman & Akurat
                        </span>
                    </h1>

                    <!-- Subtitle -->
                    <p class="text-sm sm:text-lg text-slate-300 leading-relaxed max-w-2xl mx-auto lg:mx-0 font-normal">
                        Daftar peserta KB baru atau ambil nomor antrian pelayanan secara online tanpa perlu berdesakan di puskesmas. Pantau jadwal aktif faskes secara real-time.
                    </p>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5 pt-2">
                        <flux:button variant="primary" href="{{ route('registrasi') }}" wire:navigate
                            class="w-full sm:w-auto bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold px-8 py-3.5 rounded-2xl shadow-xl shadow-blue-500/30 hover:scale-[1.02] active:scale-[0.98] transition-all text-sm">
                            <flux:icon name="sparkles" class="size-4 mr-2" />
                            Daftar / Ambil Antrian
                        </flux:button>

                        <a href="#jadwal" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-2xl bg-white/10 hover:bg-white/15 border border-white/20 text-sm font-semibold text-white backdrop-blur-md transition-all hover:scale-[1.02] active:scale-[0.98]">
                            <flux:icon name="calendar-days" class="size-4 text-blue-300" />
                            Lihat Jadwal Pelayanan
                        </a>
                    </div>

                    <!-- Highlight Features -->
                    <div class="pt-6 grid grid-cols-3 gap-4 border-t border-white/10 max-w-lg mx-auto lg:mx-0 text-left">
                        <div class="space-y-1">
                            <div class="flex items-center gap-1.5 text-emerald-400 font-bold text-xs">
                                <flux:icon name="check-circle" class="size-4 shrink-0" />
                                100% Gratis
                            </div>
                            <div class="text-2xs text-slate-400">Peserta BPJS & KIS</div>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-1.5 text-blue-400 font-bold text-xs">
                                <flux:icon name="check-circle" class="size-4 shrink-0" />
                                Bidan Resmi
                            </div>
                            <div class="text-2xs text-slate-400">Tenaga bersertifikasi</div>
                        </div>
                        <div class="space-y-1">
                            <div class="flex items-center gap-1.5 text-indigo-400 font-bold text-xs">
                                <flux:icon name="check-circle" class="size-4 shrink-0" />
                                Tanpa Antri
                            </div>
                            <div class="text-2xs text-slate-400">Antrian digital pasti</div>
                        </div>
                    </div>
                </div>

                <!-- Right Hero Card: Quick Widget -->
                <div class="lg:col-span-5">
                    <div class="relative rounded-3xl bg-white/10 dark:bg-zinc-900/60 p-6 sm:p-8 backdrop-blur-xl border border-white/20 shadow-2xl shadow-black/40 space-y-6">
                        <!-- Card Header -->
                        <div class="flex items-center justify-between border-b border-white/10 pb-4">
                            <div>
                                <div class="text-2xs uppercase tracking-wider font-bold text-blue-300">Akses Cepat Masyarakat</div>
                                <h3 class="text-lg font-bold text-white">Cek Antrian & Jadwal Hari Ini</h3>
                            </div>
                            <div class="size-10 rounded-2xl bg-blue-500/20 border border-blue-400/30 flex items-center justify-center text-blue-300">
                                <flux:icon name="ticket" class="size-5" />
                            </div>
                        </div>

                        <!-- Info Card Body -->
                        <div class="space-y-3">
                            <div class="rounded-2xl bg-slate-900/60 p-4 border border-white/10 space-y-2">
                                <div class="flex items-center justify-between text-xs text-slate-300">
                                    <span class="font-medium">Jadwal Aktif Terdekat:</span>
                                    @if($jadwalMendatang->first())
                                        <span class="font-bold text-emerald-400 flex items-center gap-1">
                                            <span class="size-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            Tersedia
                                        </span>
                                    @else
                                        <span class="text-amber-400">Belum Ada</span>
                                    @endif
                                </div>
                                @if($jadwalMendatang->first())
                                    @php $nextJadwal = $jadwalMendatang->first(); @endphp
                                    <div class="font-bold text-white text-base">
                                        {{ $nextJadwal->tanggal->translatedFormat('l, d F Y') }}
                                    </div>
                                    <div class="flex items-center justify-between text-xs text-slate-300 pt-1 border-t border-white/5">
                                        <span>⏰ {{ substr($nextJadwal->waktu_mulai, 0, 5) }} - {{ substr($nextJadwal->waktu_selesai, 0, 5) }} WITA</span>
                                        <span class="text-blue-300 font-semibold">Sisa Kuota: {{ $nextJadwal->kuota - $nextJadwal->antrians_count }}</span>
                                    </div>
                                @else
                                    <div class="text-xs text-slate-400">Belum ada jadwal pelayanan aktif dalam waktu dekat.</div>
                                @endif
                            </div>

                            <!-- Steps Preview -->
                            <div class="rounded-2xl bg-white/5 p-4 border border-white/5 space-y-2.5 text-xs text-slate-300">
                                <div class="font-semibold text-white flex items-center gap-1.5">
                                    <flux:icon name="information-circle" class="size-4 text-blue-400" />
                                    Sudah pernah mendaftar?
                                </div>
                                <p class="text-2xs text-slate-300 leading-relaxed">
                                    Cukup masukkan NIK Anda pada menu Registrasi untuk langsung memilih jadwal dan mendapatkan tiket antrian.
                                </p>
                            </div>
                        </div>

                        <!-- Widget Button -->
                        <flux:button variant="primary" href="{{ route('registrasi') }}" wire:navigate
                            class="w-full bg-blue-600 hover:bg-blue-500 font-bold py-3 rounded-xl shadow-lg shadow-blue-600/30 text-sm">
                            Mulai Sekarang
                        </flux:button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============ STATS SECTION (OVERLAPPING) ============ -->
    <div class="relative -mt-12 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 z-20">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-6">
            <!-- Stat 1 -->
            <div class="rounded-3xl bg-white dark:bg-zinc-900 p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200/80 dark:border-zinc-800 flex items-center gap-4 hover:-translate-y-1 transition-transform">
                <div class="size-14 rounded-2xl bg-blue-50 dark:bg-blue-950/60 border border-blue-100 dark:border-blue-900 flex items-center justify-center text-blue-600 dark:text-blue-400 shrink-0">
                    <flux:icon name="users" class="size-7" />
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ number_format($totalPesertaTerdaftar) }}</div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-zinc-400 mt-0.5">Peserta KB Terdaftar</div>
                </div>
            </div>

            <!-- Stat 2 -->
            <div class="rounded-3xl bg-white dark:bg-zinc-900 p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200/80 dark:border-zinc-800 flex items-center gap-4 hover:-translate-y-1 transition-transform">
                <div class="size-14 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-100 dark:border-emerald-900 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shrink-0">
                    <flux:icon name="heart" class="size-7" />
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ number_format($totalPesertaTerlayani) }}</div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-zinc-400 mt-0.5">Pelayanan Selesai</div>
                </div>
            </div>

            <!-- Stat 3 -->
            <div class="rounded-3xl bg-white dark:bg-zinc-900 p-6 shadow-xl shadow-slate-200/50 dark:shadow-none border border-slate-200/80 dark:border-zinc-800 flex items-center gap-4 hover:-translate-y-1 transition-transform">
                <div class="size-14 rounded-2xl bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-100 dark:border-indigo-900 flex items-center justify-center text-indigo-600 dark:text-indigo-400 shrink-0">
                    <flux:icon name="calendar-days" class="size-7" />
                </div>
                <div>
                    <div class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $jadwalMendatang->count() }}</div>
                    <div class="text-xs font-semibold text-slate-500 dark:text-zinc-400 mt-0.5">Jadwal Aktif Mendatang</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ============ JADWAL MENDATANG SECTION ============ -->
    <section id="jadwal" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-3 max-w-2xl mx-auto">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300">
                <flux:icon name="calendar-days" class="size-3.5" />
                Jadwal Bidan & Dokter
            </div>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                Jadwal Pelayanan KB Aktif
            </h2>
            <p class="text-sm sm:text-base text-slate-600 dark:text-zinc-400">
                Pilih waktu yang tepat untuk datang ke puskesmas. Kuota dibatasi demi kenyamanan dan ketepatan waktu pelayanan Anda.
            </p>
        </div>

        @if($jadwalMendatang->count() > 0)
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($jadwalMendatang as $jadwal)
                    @php 
                        $sisa = max(0, $jadwal->kuota - $jadwal->antrians_count);
                        $isPenuh = $sisa <= 0;
                        $persenTerisi = min(100, round(($jadwal->antrians_count / max(1, $jadwal->kuota)) * 100));
                    @endphp
                    <div class="group relative rounded-3xl bg-white dark:bg-zinc-900 p-6 border border-slate-200/80 dark:border-zinc-800 shadow-md hover:shadow-xl hover:border-blue-300 dark:hover:border-blue-800 transition-all duration-300 flex flex-col justify-between">
                        <div class="space-y-4">
                            <!-- Top Date & Badge -->
                            <div class="flex items-start justify-between gap-3">
                                <div class="space-y-1">
                                    <div class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-600 dark:text-blue-400">
                                        <flux:icon name="calendar" class="size-4" />
                                        {{ $jadwal->tanggal->translatedFormat('l') }}
                                    </div>
                                    <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">
                                        {{ $jadwal->tanggal->translatedFormat('d F Y') }}
                                    </h3>
                                </div>

                                @if($isPenuh)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-2xs font-bold bg-rose-50 text-rose-700 dark:bg-rose-950/60 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                                        Penuh
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-2xs font-bold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">
                                        <span class="size-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Tersedia
                                    </span>
                                @endif
                            </div>

                            <!-- Jam Pelayanan -->
                            <div class="flex items-center gap-2 text-sm font-semibold text-slate-700 dark:text-zinc-300 bg-slate-50 dark:bg-zinc-800/60 px-3.5 py-2.5 rounded-xl border border-slate-100 dark:border-zinc-800">
                                <flux:icon name="clock" class="size-4 text-blue-500 shrink-0" />
                                <span>{{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }} WITA</span>
                            </div>

                            <!-- Keterangan -->
                            @if($jadwal->keterangan)
                                <p class="text-xs text-slate-600 dark:text-zinc-400 line-clamp-2">
                                    {{ $jadwal->keterangan }}
                                </p>
                            @endif
                        </div>

                        <!-- Bottom Quota & Action -->
                        <div class="mt-6 pt-4 border-t border-slate-100 dark:border-zinc-800 space-y-3">
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs font-semibold">
                                    <span class="text-slate-500 dark:text-zinc-400">Kuota Terisi</span>
                                    <span class="{{ $isPenuh ? 'text-rose-600' : 'text-slate-900 dark:text-white' }}">
                                        {{ $jadwal->antrians_count }} / {{ $jadwal->kuota }} (Sisa {{ $sisa }})
                                    </span>
                                </div>
                                <div class="w-full h-2 rounded-full bg-slate-100 dark:bg-zinc-800 overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500 {{ $isPenuh ? 'bg-rose-500' : ($persenTerisi > 75 ? 'bg-amber-500' : 'bg-emerald-500') }}"
                                         style="width: {{ $persenTerisi }}%"></div>
                                </div>
                            </div>

                            <flux:button variant="{{ $isPenuh ? 'outline' : 'primary' }}" href="{{ route('registrasi') }}" wire:navigate
                                :disabled="$isPenuh"
                                class="w-full justify-center rounded-xl font-bold text-xs py-2.5">
                                {{ $isPenuh ? 'Kuota Sudah Habis' : 'Pilih Jadwal Ini' }}
                            </flux:button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-16 px-6 rounded-3xl bg-white dark:bg-zinc-900 border border-dashed border-slate-300 dark:border-zinc-800 max-w-xl mx-auto space-y-3">
                <div class="size-16 rounded-full bg-slate-100 dark:bg-zinc-800 text-slate-400 dark:text-zinc-500 flex items-center justify-center mx-auto">
                    <flux:icon name="calendar" class="size-8" />
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-zinc-200">Belum Ada Jadwal Pelayanan</h3>
                <p class="text-xs text-slate-500 dark:text-zinc-400 max-w-sm mx-auto">
                    Admin faskes sedang memperbarui jadwal layanan. Silakan kembali beberapa saat lagi.
                </p>
            </div>
        @endif
    </section>

    <!-- ============ ALUR PELAYANAN SECTION ============ -->
    <section class="py-20 bg-gradient-to-b from-slate-100/70 to-slate-50 dark:from-zinc-900/60 dark:to-zinc-950 border-y border-slate-200/60 dark:border-zinc-800/60">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            <div class="text-center space-y-3 max-w-2xl mx-auto">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-indigo-100 text-indigo-800 dark:bg-indigo-950 dark:text-indigo-300">
                    <flux:icon name="arrow-path" class="size-3.5" />
                    Prosedur Mudah
                </div>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    5 Langkah Pelayanan KB
                </h2>
                <p class="text-sm sm:text-base text-slate-600 dark:text-zinc-400">
                    Panduan praktis mulai dari pendaftaran daring hingga Anda menerima pelayanan di Puskesmas.
                </p>
            </div>

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-5 relative">
                @php
                    $steps = [
                        [
                            'step' => '01',
                            'icon' => 'clipboard-document-check',
                            'title' => 'Isi Data NIK',
                            'desc' => 'Masukkan NIK untuk pengecekan atau isi form data diri bagi peserta baru.',
                            'gradient' => 'from-blue-500 to-indigo-600'
                        ],
                        [
                            'step' => '02',
                            'icon' => 'shield-check',
                            'title' => 'Verifikasi',
                            'desc' => 'Admin memverifikasi kecocokan identitas dengan domisili wilayah faskes.',
                            'gradient' => 'from-indigo-500 to-purple-600'
                        ],
                        [
                            'step' => '03',
                            'icon' => 'calendar-days',
                            'title' => 'Pilih Jadwal',
                            'desc' => 'Pilih tanggal kunjungan dan langsung dapatkan nomor antrian digital resmi.',
                            'gradient' => 'from-purple-500 to-pink-600'
                        ],
                        [
                            'step' => '04',
                            'icon' => 'building-office-2',
                            'title' => 'Datang ke Faskes',
                            'desc' => 'Hadir sesuai jadwal dengan membawa KTP & kartu jaminan kesehatan (jika ada).',
                            'gradient' => 'from-pink-500 to-rose-600'
                        ],
                        [
                            'step' => '05',
                            'icon' => 'heart',
                            'title' => 'Pelayanan KB',
                            'desc' => 'Skrining medis oleh bidan, informed consent, dan tindakan kontrasepsi.',
                            'gradient' => 'from-emerald-500 to-teal-600'
                        ],
                    ];
                @endphp

                @foreach($steps as $s)
                    <div class="relative rounded-3xl bg-white dark:bg-zinc-900 p-6 border border-slate-200/80 dark:border-zinc-800 shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between space-y-4 group">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <div class="size-12 rounded-2xl bg-gradient-to-tr {{ $s['gradient'] }} text-white flex items-center justify-center shadow-md shadow-black/10 group-hover:scale-110 transition-transform">
                                    <flux:icon name="{{ $s['icon'] }}" class="size-6" />
                                </div>
                                <span class="text-2xl font-black text-slate-200 dark:text-zinc-800 group-hover:text-blue-500/20 transition-colors">
                                    {{ $s['step'] }}
                                </span>
                            </div>
                            <h3 class="font-extrabold text-base text-slate-900 dark:text-white tracking-tight">
                                {{ $s['title'] }}
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-zinc-400 leading-relaxed font-normal">
                                {{ $s['desc'] }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============ METODE KONTRASEPSI SECTION ============ -->
    <section id="metode" class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
        <div class="text-center space-y-3 max-w-2xl mx-auto">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-pink-100 text-pink-800 dark:bg-pink-950 dark:text-pink-300">
                <flux:icon name="sparkles" class="size-3.5" />
                Edukasi & Konseling
            </div>
            <h2 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                Pilihan Metode Kontrasepsi
            </h2>
            <p class="text-sm sm:text-base text-slate-600 dark:text-zinc-400">
                Konsultasikan dengan bidan kami untuk menentukan alat kontrasepsi yang paling aman dan sesuai dengan kondisi medis Anda.
            </p>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @php
                $metodeList = [
                    [
                        'nama' => 'Suntik KB 1 & 3 Bulan',
                        'kategori' => 'Hormonal',
                        'tag' => 'Favorit Ibu Menyusui',
                        'icon' => '💉',
                        'efektivitas' => '99%',
                        'desc' => 'Diberikan secara berkala tiap 1 atau 3 bulan. Sangat praktis, efektif mencegah kehamilan, dan versi progestin aman untuk ibu menyusui.'
                    ],
                    [
                        'nama' => 'Pil KB Kombinasi & Progestin',
                        'kategori' => 'Oral',
                        'tag' => 'Kembali Subur Cepat',
                        'icon' => '💊',
                        'efektivitas' => '92-99%',
                        'desc' => 'Diminum setiap hari pada jam yang sama. Kesuburan cepat kembali setelah berhenti mengonsumsi, siklus haid menjadi lebih teratur.'
                    ],
                    [
                        'nama' => 'Implan / Susuk KB',
                        'kategori' => 'Jangka Panjang (MKJP)',
                        'tag' => 'Perlindungan 3 Tahun',
                        'icon' => '🔬',
                        'efektivitas' => '99.5%',
                        'desc' => 'Tabung elastis kecil dipasang di bawah kulit lengan atas. Memberikan perlindungan efektif tanpa perlu repot mengingat jadwal harian/bulanan.'
                    ],
                    [
                        'nama' => 'IUD / AKDR (Spiral)',
                        'kategori' => 'Non-Hormonal / MKJP',
                        'tag' => 'Perlindungan 5-10 Tahun',
                        'icon' => '🛡️',
                        'efektivitas' => '99.4%',
                        'desc' => 'Alat kecil berbahan tembaga dipasang ke dalam rongga rahim oleh bidan/dokter terlatih. Sangat efektif dan bebas hormon.'
                    ],
                    [
                        'nama' => 'Kondom Pria',
                        'kategori' => 'Penghalang',
                        'tag' => 'Cegah Penyakit Menular',
                        'icon' => '🩹',
                        'efektivitas' => '88-98%',
                        'desc' => 'Metode sederhana tanpa efek samping hormonal, sekaligus mencegah penularan infeksi menular seksual (IMS/HIV).'
                    ],
                    [
                        'nama' => 'Tubektomi (MOW)',
                        'kategori' => 'Kontrasepsi Mantap',
                        'tag' => 'Bersifat Permanen',
                        'icon' => '⚕️',
                        'efektivitas' => '99.9%',
                        'desc' => 'Tindakan medis pengikatan/pemotongan saluran tuba bagi pasangan yang telah mantap tidak ingin menambah keturunan lagi.'
                    ],
                ];
            @endphp

            @foreach($metodeList as $m)
                <div class="rounded-3xl bg-white dark:bg-zinc-900 p-6 border border-slate-200/80 dark:border-zinc-800 shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-3xl p-2 rounded-2xl bg-slate-100 dark:bg-zinc-800">{{ $m['icon'] }}</span>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-2xs font-bold bg-blue-50 text-blue-700 dark:bg-blue-950/60 dark:text-blue-300 border border-blue-200 dark:border-blue-800">
                                {{ $m['kategori'] }}
                            </span>
                        </div>
                        <div>
                            <div class="text-2xs font-bold uppercase tracking-wider text-emerald-600 dark:text-emerald-400">{{ $m['tag'] }}</div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mt-0.5">{{ $m['nama'] }}</h3>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-zinc-400 leading-relaxed">
                            {{ $m['desc'] }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-between text-xs">
                        <span class="text-slate-500 dark:text-zinc-400">Tingkat Efektivitas</span>
                        <span class="font-extrabold text-emerald-600 dark:text-emerald-400">{{ $m['efektivitas'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- ============ FAQ SECTION ============ -->
    <section id="faq" class="py-20 bg-slate-100/60 dark:bg-zinc-900/40 border-t border-slate-200/60 dark:border-zinc-800/60">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
            <div class="text-center space-y-3">
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-slate-200 text-slate-800 dark:bg-zinc-800 dark:text-zinc-300">
                    <flux:icon name="question-mark-circle" class="size-3.5" />
                    Pusat Bantuan
                </div>
                <h2 class="text-2xl sm:text-4xl font-black text-slate-900 dark:text-white tracking-tight">
                    Pertanyaan yang Sering Diajukan
                </h2>
                <p class="text-sm sm:text-base text-slate-600 dark:text-zinc-400">
                    Informasi penting seputar prosedur pendaftaran, persyaratan, dan biaya pelayanan.
                </p>
            </div>

            <div x-data="{ openFaq: null }" class="space-y-3.5">
                @php
                    $faqItems = [
                        [
                            'q' => 'Apakah pendaftaran pelayanan KB ini dipungut biaya?',
                            'a' => 'Tidak. Pendaftaran daring ini 100% gratis. Untuk tindakan pelayanan di puskesmas gratis bagi pemegang kartu BPJS Kesehatan / KIS aktif yang faskes tingkat 1 nya terdaftar di Puskesmas Wundulako.'
                        ],
                        [
                            'q' => 'Saya sudah pernah KB di puskesmas, apakah perlu mendaftar baru?',
                            'a' => 'Cukup masukkan NIK Anda pada menu Registrasi. Sistem akan langsung mengenali identitas Anda dan Anda bisa langsung memilih jadwal kunjungan ulangan tanpa mengisi form dari awal.'
                        ],
                        [
                            'q' => 'Apa saja dokumen yang wajib dibawa saat hari pelayanan?',
                            'a' => 'Wajib membawa KTP asli, Kartu BPJS/KIS (bila ada), dan Buku KIA/KB (bila ada). Pastikan hadir 15 menit sebelum jam pelayanan dimulai.'
                        ],
                        [
                            'q' => 'Bagaimana jika saya berhalangan hadir pada jadwal yang telah dipilih?',
                            'a' => 'Anda dapat melakukan pemilihan ulang jadwal pada website ini dengan memasukkan NIK Anda kembali setelah jadwal sebelumnya terlewati.'
                        ],
                    ];
                @endphp

                @foreach($faqItems as $index => $item)
                    <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 overflow-hidden shadow-xs">
                        <button @click="openFaq === {{ $index }} ? openFaq = null : openFaq = {{ $index }}"
                            class="w-full flex items-center justify-between p-5 text-left text-sm sm:text-base font-bold text-slate-900 dark:text-white hover:bg-slate-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <span>{{ $item['q'] }}</span>
                            <div class="size-7 rounded-xl bg-slate-100 dark:bg-zinc-800 flex items-center justify-center shrink-0 ml-4 transition-transform duration-200"
                                :class="openFaq === {{ $index }} ? 'rotate-180 bg-blue-100 text-blue-600 dark:bg-blue-950 dark:text-blue-300' : ''">
                                <flux:icon name="chevron-down" class="size-4" />
                            </div>
                        </button>
                        <div x-show="openFaq === {{ $index }}" x-transition class="px-5 pb-5 pt-1 text-xs sm:text-sm text-slate-600 dark:text-zinc-400 leading-relaxed border-t border-slate-100 dark:border-zinc-800">
                            {{ $item['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ============ CTA BANNER ============ -->
    <section class="py-16 bg-gradient-to-r from-blue-600 via-indigo-600 to-blue-700 text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:20px_20px] opacity-10 pointer-events-none"></div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <h2 class="text-2xl sm:text-4xl font-black tracking-tight">
                Wujudkan Keluarga Sehat & Berencana Hari Ini
            </h2>
            <p class="text-sm sm:text-base text-blue-100 max-w-2xl mx-auto font-normal">
                Daftarkan diri Anda sekarang atau pilih jadwal pelayanan terdekat untuk mendapatkan nomor antrian digital tanpa antri lama.
            </p>
            <div class="flex flex-wrap justify-center gap-4 pt-2">
                <flux:button variant="primary" href="{{ route('registrasi') }}" wire:navigate
                    class="bg-white! text-blue-700! hover:bg-blue-50! font-bold text-sm px-8 py-3.5 rounded-2xl shadow-xl shadow-black/20">
                    <flux:icon name="sparkles" class="size-4 mr-2" />
                    Ambil Nomor Antrian Sekarang
                </flux:button>
            </div>
        </div>
    </section>

    <!-- ============ FOOTER ============ -->
    <footer class="bg-slate-900 dark:bg-black text-slate-400 text-xs border-t border-slate-800 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Col 1 -->
            <div class="space-y-3">
                <div class="flex items-center gap-2.5">
                    <div class="flex size-9 items-center justify-center rounded-xl bg-blue-600 text-white font-extrabold text-xs shadow-md">KB</div>
                    <span class="font-bold text-white text-sm">SI Pelayanan KB</span>
                </div>
                <p class="text-slate-400 text-xs leading-relaxed">
                    Sistem digitalisasi layanan Keluarga Berencana Kecamatan Wundulako, Kabupaten Kolaka, Sulawesi Tenggara.
                </p>
            </div>

            <!-- Col 2 -->
            <div class="space-y-3">
                <h4 class="font-bold text-white text-xs uppercase tracking-wider">Navigasi Cepat</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors" wire:navigate>Beranda</a></li>
                    <li><a href="{{ route('registrasi') }}" class="hover:text-white transition-colors" wire:navigate>Registrasi & Antrian</a></li>
                    <li><a href="#jadwal" class="hover:text-white transition-colors">Jadwal Pelayanan</a></li>
                    <li><a href="{{ route('login') }}" class="hover:text-white transition-colors" wire:navigate>Portal Petugas</a></li>
                </ul>
            </div>

            <!-- Col 3 -->
            <div class="space-y-3">
                <h4 class="font-bold text-white text-xs uppercase tracking-wider">Kontak & Lokasi</h4>
                <ul class="space-y-2">
                    <li class="flex items-start gap-2">
                        <flux:icon name="map-pin" class="size-4 text-blue-400 shrink-0 mt-0.5" />
                        <span>Puskesmas Wundulako, Jl. Poros Kolaka-Pomalaa, Kec. Wundulako</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <flux:icon name="phone" class="size-4 text-blue-400 shrink-0" />
                        <span>Pelayanan: 08:00 - 14:00 WITA</span>
                    </li>
                </ul>
            </div>

            <!-- Col 4 -->
            <div class="space-y-3">
                <h4 class="font-bold text-white text-xs uppercase tracking-wider">Hak Cipta</h4>
                <p class="text-slate-500 leading-relaxed">
                    &copy; {{ now()->year }} DPPKB & Puskesmas Wundulako. Hak cipta dilindungi undang-undang.
                </p>
            </div>
        </div>
    </footer>
</div>