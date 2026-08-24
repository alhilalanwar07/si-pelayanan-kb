<div class="min-h-screen bg-slate-100/70 dark:bg-zinc-950 flex flex-col justify-between font-sans selection:bg-blue-600 selection:text-white">
    <!-- ============ PRINT ONLY STYLES ============ -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #tiket-antrian-card, #tiket-antrian-card * {
                visibility: visible;
            }
            #tiket-antrian-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 24px;
                border: 2px solid #2563eb !important;
                box-shadow: none !important;
                background: white !important;
                color: black !important;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>

    <!-- html2canvas & jsPDF for Direct PDF/Screenshot Download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb5hBydBmjaUGFBi13TLChqxUGa5I9vwcxlicILOwtW59RChNf04CL+Zo5wf3VgMB3n22BQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznubP9wIIqxDaVJxPX49P4944noKmKE5WubqF5109i2E64po0oSmqOzybi+7/ZAE6TE8Ksw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <!-- ============ TOP NAVBAR ============ -->
    <header class="bg-white/90 dark:bg-zinc-900/90 backdrop-blur-md border-b border-slate-200/80 dark:border-zinc-800/80 px-3 sm:px-6 md:px-8 py-2.5 sm:py-3.5 flex items-center justify-between sticky top-0 z-40 no-print gap-2">
        <a href="{{ route('home') }}" class="flex items-center gap-2 sm:gap-3 group min-w-0 flex-1 sm:flex-initial" wire:navigate>
            <img src="{{ asset('img-logo-login.webp') }}" alt="Logo SI Pelayanan KB" class="size-8 sm:size-10 object-contain group-hover:scale-105 transition-transform shrink-0" />
            <div class="min-w-0">
                <div class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white leading-tight truncate">SI Pelayanan KB</div>
                <div class="text-3xs sm:text-2xs text-slate-500 dark:text-zinc-400 font-medium truncate">Puskesmas Wundulako</div>
            </div>
        </a>
        <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
            <flux:button variant="outline" size="sm" href="{{ route('home') }}" icon="home" wire:navigate class="rounded-xl border-slate-300 dark:border-zinc-700 px-2 sm:px-3 text-xs h-8 sm:h-9">
                <span class="hidden sm:inline">Beranda</span>
            </flux:button>
            <flux:button variant="primary" size="sm" href="{{ route('login') }}" icon="arrow-right-end-on-rectangle" wire:navigate class="rounded-xl shadow-md shadow-blue-600/20 px-2 sm:px-3 text-xs h-8 sm:h-9">
                <span class="hidden sm:inline">Masuk Petugas</span>
                <span class="sm:hidden">Masuk</span>
            </flux:button>
        </div>
    </header>

    <!-- ============ MAIN CONTENT ============ -->
    <main class="flex-1 flex justify-center items-center py-4 sm:py-8 md:py-12 px-3 sm:px-6">
        <div class="w-full max-w-3xl space-y-4 sm:space-y-6">

            <!-- Stepper Progress Bar -->
            <div class="rounded-2xl bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 p-2 sm:p-3.5 shadow-sm no-print">
                <div class="grid grid-cols-3 gap-1.5 sm:gap-2 text-center">
                    <!-- Step 1 -->
                    <div class="flex items-center gap-1.5 sm:gap-2 justify-center py-1.5 px-1 rounded-xl transition-colors {{ in_array($step, ['cek_nik', 'registrasi', 'pilih_jadwal', 'selesai']) ? 'text-blue-600 bg-blue-50/80 dark:bg-blue-950/60 dark:text-blue-400 font-bold' : 'text-slate-400 font-medium' }}">
                        <span class="size-4 sm:size-5 rounded-full bg-blue-600 text-white text-3xs sm:text-2xs flex items-center justify-center font-black shrink-0">1</span>
                        <span class="text-2xs sm:text-xs truncate">Cek NIK</span>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex items-center gap-1.5 sm:gap-2 justify-center py-1.5 px-1 rounded-xl transition-colors {{ in_array($step, ['registrasi', 'pilih_jadwal', 'selesai']) ? 'text-blue-600 bg-blue-50/80 dark:bg-blue-950/60 dark:text-blue-400 font-bold' : 'text-slate-400 font-medium' }}">
                        <span class="size-4 sm:size-5 rounded-full {{ in_array($step, ['registrasi', 'pilih_jadwal', 'selesai']) ? 'bg-blue-600 text-white' : 'bg-slate-200 text-slate-500 dark:bg-zinc-800' }} text-3xs sm:text-2xs flex items-center justify-center font-black shrink-0">2</span>
                        <span class="text-2xs sm:text-xs truncate">{{ $step === 'registrasi' ? 'Daftar & Jadwal' : 'Pilih Jadwal' }}</span>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex items-center gap-1.5 sm:gap-2 justify-center py-1.5 px-1 rounded-xl transition-colors {{ $step === 'selesai' ? 'text-emerald-600 bg-emerald-50/80 dark:bg-emerald-950/60 dark:text-emerald-400 font-bold' : 'text-slate-400 font-medium' }}">
                        <span class="size-4 sm:size-5 rounded-full {{ $step === 'selesai' ? 'bg-emerald-600 text-white' : 'bg-slate-200 text-slate-500 dark:bg-zinc-800' }} text-3xs sm:text-2xs flex items-center justify-center font-black shrink-0">3</span>
                        <span class="text-2xs sm:text-xs truncate">Tiket Antrian</span>
                    </div>
                </div>
            </div>

            <!-- Main Card Container -->
            <div class="rounded-2xl sm:rounded-3xl bg-white dark:bg-zinc-900 border border-slate-200/80 dark:border-zinc-800 shadow-xl shadow-slate-200/40 dark:shadow-none p-4 sm:p-7 md:p-10 space-y-6 sm:space-y-8">

                {{-- ==================== STEP 1: CEK NIK ==================== --}}
                @if($step === 'cek_nik')
                    <div class="space-y-5 sm:space-y-6">
                        <!-- Heading -->
                        <div class="text-center space-y-2 max-w-md mx-auto">
                            <div class="inline-flex size-12 sm:size-14 items-center justify-center rounded-2xl bg-blue-50 dark:bg-blue-950/60 text-blue-600 dark:text-blue-400 shadow-inner">
                                <flux:icon name="identification" class="size-6 sm:size-7" />
                            </div>
                            <h2 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                                Pengecekan NIK Peserta
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 dark:text-zinc-400 leading-relaxed">
                                Masukkan 16 digit Nomor Induk Kependudukan (NIK) pada KTP Anda untuk memulai.
                            </p>
                        </div>

                        <!-- Input Box -->
                        <div class="space-y-3 max-w-lg mx-auto w-full">
                            <div class="space-y-1.5">
                                <flux:label class="font-bold text-xs">Nomor Induk Kependudukan (NIK)</flux:label>
                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-2.5">
                                    <div class="relative flex-1 w-full">
                                        <flux:input type="text" maxlength="16" placeholder="Contoh: 7401012305900001"
                                            wire:model="cekNik" wire:keydown.enter="cekNikAction"
                                            class="rounded-xl h-11 text-sm sm:text-base tracking-wider font-mono w-full" />
                                    </div>
                                    <flux:button variant="primary" wire:click="cekNikAction" icon="magnifying-glass"
                                        class="h-11 px-5 sm:px-6 rounded-xl font-bold bg-blue-600 hover:bg-blue-500 shadow-md shadow-blue-600/20 text-xs sm:text-sm shrink-0 justify-center">
                                        Cari Data
                                    </flux:button>
                                </div>
                                <flux:error name="cekNik" />
                            </div>
                        </div>

                        <!-- Status Result Cards -->
                        @if($nikStatus === 'not_found')
                            <div class="rounded-2xl bg-blue-50 dark:bg-blue-950/40 border border-blue-200 dark:border-blue-900 p-4 sm:p-5 space-y-4">
                                <div class="flex items-start gap-3">
                                    <div class="size-9 sm:size-10 rounded-xl bg-blue-100 dark:bg-blue-900/60 text-blue-700 dark:text-blue-300 flex items-center justify-center shrink-0">
                                        <flux:icon name="user-plus" class="size-4 sm:size-5" />
                                    </div>
                                    <div class="space-y-1 min-w-0">
                                        <h4 class="font-bold text-xs sm:text-sm text-blue-900 dark:text-blue-200">
                                            NIK Belum Terdaftar
                                        </h4>
                                        <p class="text-2xs sm:text-xs text-blue-800/90 dark:text-blue-300/90 leading-relaxed">
                                            NIK <strong>{{ $cekNik }}</strong> belum tercatat di sistem. Silakan isi formulir pendaftaran dan <strong>langsung pilih jadwal</strong> untuk mendapatkan nomor antrian resmi Anda.
                                        </p>
                                    </div>
                                </div>

                                <flux:button variant="primary" wire:click="lanjutRegistrasi"
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 font-bold py-3 rounded-xl shadow-md text-xs sm:text-sm justify-center">
                                    Lanjut Isi Data Diri & Pilih Jadwal
                                </flux:button>
                            </div>
                        @endif

                        <!-- Helpful Information -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 pt-4 border-t border-slate-100 dark:border-zinc-800 text-2xs sm:text-xs text-slate-500 dark:text-zinc-400">
                            <div class="flex items-start gap-2">
                                <flux:icon name="shield-check" class="size-4 text-emerald-500 shrink-0 mt-0.5" />
                                <span>Data privasi KTP Anda aman & terlindungi sesuai standar medis faskes.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <flux:icon name="clock" class="size-4 text-blue-500 shrink-0 mt-0.5" />
                                <span>Pelayanan antrian buka setiap hari kerja pukul 08:00 - 14:00 WITA.</span>
                            </div>
                        </div>
                    </div>

                {{-- ==================== STEP 2A: FORM REGISTRASI (PESERTA BARU + PILIH JADWAL) ==================== --}}
                @elseif($step === 'registrasi')
                    <form wire:submit="daftar" class="space-y-6 sm:space-y-8">
                        @csrf

                        <!-- Header with Back Button -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-zinc-800 pb-4">
                            <div>
                                <h3 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white tracking-tight">Formulir Pendaftaran & Pemilihan Jadwal</h3>
                                <p class="text-2xs sm:text-xs text-slate-500 dark:text-zinc-400">Lengkapi data diri dan pilih jadwal kunjungan untuk mendapatkan tiket antrian.</p>
                            </div>
                            <flux:button variant="ghost" size="sm" wire:click="kembaliCekNik" icon="arrow-left" class="rounded-xl font-semibold self-start sm:self-auto text-xs">
                                Ganti NIK
                            </flux:button>
                        </div>

                        <!-- Section 1: Identitas Diri -->
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 font-bold text-xs sm:text-sm">
                                <flux:icon name="user" class="size-4 shrink-0" />
                                1. Identitas Pasien & Pasangan
                            </div>

                            <div class="grid gap-3.5 sm:gap-4 grid-cols-1 sm:grid-cols-2">
                                <flux:field>
                                    <flux:label>Nomor Induk Kependudukan (NIK)</flux:label>
                                    <flux:input type="text" maxlength="16" placeholder="16 digit NIK" wire:model="nik" class="rounded-xl font-mono text-sm w-full" />
                                    <flux:error name="nik" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Nomor WhatsApp Aktif</flux:label>
                                    <flux:input type="text" placeholder="Contoh: 081234567890" wire:model="nomor_hp" class="rounded-xl text-sm w-full" />
                                    <flux:error name="nomor_hp" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Nama Lengkap Pasien (Istri)</flux:label>
                                    <flux:input type="text" placeholder="Nama lengkap sesuai KTP" wire:model="nama_lengkap" class="rounded-xl text-sm w-full" />
                                    <flux:error name="nama_lengkap" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Nama Suami / Pasangan</flux:label>
                                    <flux:input type="text" placeholder="Nama suami / pasangan" wire:model="nama_suami_istri" class="rounded-xl text-sm w-full" />
                                    <flux:error name="nama_suami_istri" />
                                </flux:field>

                                <flux:field class="sm:col-span-2">
                                    <flux:label>Tanggal Lahir Pasien (Istri)</flux:label>
                                    <flux:input type="date" wire:model="tanggal_lahir_istri" class="rounded-xl text-sm w-full" />
                                    <flux:error name="tanggal_lahir_istri" />
                                </flux:field>
                            </div>
                        </div>

                        <!-- Section 2: Wilayah & Asuransi -->
                        <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-bold text-xs sm:text-sm">
                                <flux:icon name="map-pin" class="size-4 shrink-0" />
                                2. Domisili & Jaminan Kesehatan
                            </div>

                            <div class="grid gap-3.5 sm:gap-4 grid-cols-1 sm:grid-cols-2">
                                <flux:field>
                                    <flux:label>Desa / Kelurahan</flux:label>
                                    <flux:select wire:model="wilayah_id" class="rounded-xl text-sm w-full">
                                        <option value="">Pilih Desa / Kelurahan</option>
                                        @foreach($wilayahs as $wilayah)
                                            <option value="{{ $wilayah->id }}">{{ $wilayah->nama_desa_kelurahan }}</option>
                                        @endforeach
                                    </flux:select>
                                    <flux:error name="wilayah_id" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Penggunaan Asuransi</flux:label>
                                    <flux:select wire:model="penggunaan_asuransi" class="rounded-xl text-sm w-full">
                                        <option value="bpjs">BPJS Kesehatan</option>
                                        <option value="kis">Kartu Indonesia Sehat (KIS)</option>
                                        <option value="umum">Umum / Mandiri</option>
                                        <option value="lainnya">Lainnya</option>
                                    </flux:select>
                                    <flux:error name="penggunaan_asuransi" />
                                </flux:field>

                                <flux:field class="sm:col-span-2">
                                    <flux:label>Alamat Lengkap KTP</flux:label>
                                    <flux:textarea rows="2" placeholder="Nama jalan, RT/RW, Dusun..." wire:model="alamat_lengkap" class="rounded-xl text-sm w-full" />
                                    <flux:error name="alamat_lengkap" />
                                </flux:field>
                            </div>
                        </div>

                        <!-- Section 3: Data Keluarga -->
                        <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center gap-2 text-purple-600 dark:text-purple-400 font-bold text-xs sm:text-sm">
                                <flux:icon name="users" class="size-4 shrink-0" />
                                3. Data Anak & Keluarga
                            </div>

                            <div class="grid gap-3.5 sm:gap-4 grid-cols-1 sm:grid-cols-2">
                                <flux:field>
                                    <flux:label>Jumlah Anak Hidup</flux:label>
                                    <flux:input type="number" min="0" placeholder="0" wire:model="jumlah_anak_hidup" class="rounded-xl text-sm w-full" />
                                    <flux:error name="jumlah_anak_hidup" />
                                </flux:field>

                                <flux:field>
                                    <flux:label>Umur Anak Terakhir (Bulan)</flux:label>
                                    <flux:input type="number" min="0" placeholder="Kosongkan jika belum memiliki anak" wire:model="umur_anak_terakhir" class="rounded-xl text-sm w-full" />
                                    <flux:error name="umur_anak_terakhir" />
                                </flux:field>
                            </div>
                        </div>

                        <!-- Section 4: PILIH JADWAL PELAYANAN (UNTUK PENDAFTAR BARU) -->
                        <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 font-bold text-xs sm:text-sm">
                                    <flux:icon name="calendar-days" class="size-4 shrink-0" />
                                    4. Pilih Jadwal Pelayanan KB
                                </div>
                                <span class="text-3xs sm:text-2xs text-slate-400 font-semibold">*Wajib dipilih untuk mendapatkan antrian</span>
                            </div>

                            <flux:error name="selectedJadwalId" />

                            @if($jadwalTersedia->count() > 0)
                                <div class="space-y-3">
                                    @foreach($jadwalTersedia as $jadwal)
                                        @php
                                            $sisa = max(0, $jadwal->kuota - $jadwal->antrians_count);
                                            $isFull = $sisa <= 0;
                                            $isSelected = $selectedJadwalId === $jadwal->id;
                                        @endphp
                                        <label class="block cursor-pointer {{ $isFull ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                            <input type="radio" wire:model.live="selectedJadwalId" value="{{ $jadwal->id }}"
                                                class="peer hidden" {{ $isFull ? 'disabled' : '' }}>
                                            <div class="rounded-2xl border-2 p-4 sm:p-5 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 sm:gap-4
                                                {{ $isSelected ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/30 dark:border-blue-500 shadow-md ring-2 ring-blue-500/20' : 'border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-slate-300 dark:hover:border-zinc-700' }}
                                            ">
                                                <!-- Schedule Info -->
                                                <div class="space-y-1.5 min-w-0">
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <span class="font-black text-sm sm:text-base text-slate-900 dark:text-white">
                                                            {{ $jadwal->tanggal->translatedFormat('l, d F Y') }}
                                                        </span>
                                                        @if($isFull)
                                                            <span class="px-2 py-0.5 rounded-full text-3xs sm:text-2xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-400">Penuh</span>
                                                        @else
                                                            <span class="px-2 py-0.5 rounded-full text-3xs sm:text-2xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">Tersedia</span>
                                                        @endif
                                                    </div>

                                                    <div class="flex flex-wrap items-center gap-2 text-2xs sm:text-xs text-slate-600 dark:text-zinc-400">
                                                        <span class="flex items-center gap-1 font-semibold text-blue-600 dark:text-blue-400 shrink-0">
                                                            <flux:icon name="clock" class="size-3.5" />
                                                            {{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }} WITA
                                                        </span>
                                                        @if($jadwal->keterangan)
                                                            <span class="truncate">• {{ $jadwal->keterangan }}</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Quota & Check Indicator -->
                                                <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100 dark:border-zinc-800">
                                                    <div class="text-left sm:text-right">
                                                        <div class="text-3xs sm:text-2xs uppercase tracking-wider text-slate-400 font-bold">Sisa Kuota</div>
                                                        <div class="text-xs sm:text-sm font-black {{ $sisa > 5 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600' }}">
                                                            {{ $sisa }} / {{ $jadwal->kuota }} Kursi
                                                        </div>
                                                    </div>

                                                    <div class="size-6 rounded-full border-2 flex items-center justify-center transition-colors shrink-0
                                                        {{ $isSelected ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 dark:border-zinc-700' }}">
                                                        @if($isSelected)
                                                            <flux:icon name="check" class="size-3.5 stroke-[3]" />
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-8 px-4 rounded-2xl bg-slate-50 dark:bg-zinc-800/40 border border-dashed border-slate-300 dark:border-zinc-700 space-y-2">
                                    <flux:icon name="calendar" class="size-8 text-slate-400 mx-auto" />
                                    <div class="text-xs font-semibold text-slate-700 dark:text-zinc-300">Belum ada jadwal pelayanan aktif saat ini.</div>
                                </div>
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-4 border-t border-slate-100 dark:border-zinc-800">
                            <flux:button type="submit" variant="primary" icon="ticket"
                                class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold shadow-lg shadow-blue-600/25 text-xs sm:text-sm justify-center">
                                Daftar & Ambil Nomor Antrian
                            </flux:button>
                        </div>
                    </form>

                {{-- ==================== STEP 2B: DASHBOARD PASIEN (ANTRIAN AKTIF, BATALKAN, RIWAYAT, & PILIH JADWAL) ==================== --}}
                @elseif($step === 'pilih_jadwal')
                    <div class="space-y-6 sm:space-y-8">
                        <!-- Top Header -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-slate-100 dark:border-zinc-800 pb-4">
                            <div>
                                <h3 class="text-lg sm:text-xl font-black text-slate-900 dark:text-white tracking-tight">Dashboard Pasien & Pemilihan Jadwal</h3>
                                <p class="text-2xs sm:text-xs text-slate-500 dark:text-zinc-400">Pantau tiket antrian aktif, riwayat pelayanan, atau pilih jadwal kunjungan baru.</p>
                            </div>
                            <flux:button variant="ghost" size="sm" wire:click="kembaliCekNik" icon="arrow-left" class="rounded-xl font-semibold self-start sm:self-auto text-xs">
                                Ganti NIK
                            </flux:button>
                        </div>

                        <!-- Flash Alert Pembatalan / Notifikasi -->
                        @if(session()->has('success_pembatalan'))
                            <div class="rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-200 dark:border-emerald-900 p-4 flex items-start gap-3">
                                <div class="size-8 rounded-xl bg-emerald-100 dark:bg-emerald-900 text-emerald-700 dark:text-emerald-300 flex items-center justify-center shrink-0">
                                    <flux:icon name="check-circle" class="size-5" />
                                </div>
                                <div class="text-xs text-emerald-900 dark:text-emerald-200">
                                    <div class="font-bold">Pembatalan Berhasil</div>
                                    <p class="mt-0.5 text-2xs sm:text-xs leading-relaxed text-emerald-800/90 dark:text-emerald-300/90">
                                        {{ session('success_pembatalan') }}
                                    </p>
                                </div>
                            </div>
                        @endif

                        <!-- Patient Profile Card -->
                        @if($foundPeserta)
                            <div class="rounded-2xl sm:rounded-3xl bg-gradient-to-r from-blue-500/10 via-indigo-500/10 to-teal-500/10 border border-blue-200/80 dark:border-blue-900/60 p-4 sm:p-5 flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-3.5 min-w-0">
                                    <div class="size-11 sm:size-13 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-base shadow-md shadow-blue-500/20 shrink-0">
                                        {{ strtoupper(substr($foundPeserta->nama_lengkap, 0, 2)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                                            <h4 class="font-black text-sm sm:text-base text-slate-900 dark:text-white truncate">{{ $foundPeserta->nama_lengkap }}</h4>
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-3xs sm:text-2xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-950 dark:text-blue-300 shrink-0">
                                                <flux:icon name="check-badge" class="size-3" />
                                                Pasien Terdaftar
                                            </span>
                                        </div>
                                        <p class="text-2xs sm:text-xs text-slate-600 dark:text-zinc-400 font-mono mt-0.5 truncate">
                                            NIK: {{ $foundPeserta->nik }} • {{ $foundPeserta->wilayah->nama_desa_kelurahan ?? 'Kec. Wundulako' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex flex-wrap items-center gap-2 text-2xs sm:text-xs text-slate-600 dark:text-zinc-400 pt-2 md:pt-0 border-t md:border-t-0 border-slate-200/60 dark:border-zinc-800">
                                    <span class="px-2.5 py-1 rounded-xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 font-semibold">
                                        Asuransi: <strong class="text-slate-900 dark:text-white">{{ strtoupper($foundPeserta->penggunaan_asuransi ?? 'UMUM') }}</strong>
                                    </span>
                                    <span class="px-2.5 py-1 rounded-xl bg-white dark:bg-zinc-800 border border-slate-200 dark:border-zinc-700 font-semibold">
                                        Anak: <strong class="text-slate-900 dark:text-white">{{ $foundPeserta->jumlah_anak_hidup ?? 0 }} Orang</strong>
                                    </span>
                                </div>
                            </div>
                        @endif

                        {{-- ==================== 1. JADWAL / ANTRIAN AKTIF (BELUM DILAKUKAN) ==================== --}}
                        @if($antrianAktif)
                            <div class="rounded-2xl sm:rounded-3xl border-2 border-blue-500/40 bg-gradient-to-b from-blue-50/80 via-white to-white dark:from-blue-950/30 dark:via-zinc-900 dark:to-zinc-900 p-5 sm:p-6 space-y-4 shadow-lg shadow-blue-500/5">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-blue-200/60 dark:border-zinc-800 pb-3">
                                    <div class="flex items-center gap-2">
                                        <span class="relative flex size-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full size-3 bg-blue-600"></span>
                                        </span>
                                        <h4 class="font-extrabold text-sm sm:text-base text-slate-900 dark:text-white">
                                            Jadwal Antrian Aktif Anda
                                        </h4>
                                    </div>
                                    <span class="px-2.5 py-0.5 rounded-full text-3xs sm:text-2xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 border border-amber-200 dark:border-amber-800 self-start sm:self-auto">
                                        Menunggu Kehadiran
                                    </span>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-center">
                                    <!-- Nomor Antrian Box -->
                                    <div class="sm:col-span-4 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-2xl p-4 text-center text-white shadow-md shadow-blue-600/25 space-y-1">
                                        <div class="text-3xs uppercase tracking-widest font-extrabold text-blue-200">Nomor Antrian</div>
                                        <div class="text-4xl sm:text-5xl font-black tracking-tight">
                                            {{ str_pad($antrianAktif->nomor_antrian, 3, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div class="text-3xs text-blue-100 font-medium">Tiket Terverifikasi</div>
                                    </div>

                                    <!-- Schedule Details -->
                                    <div class="sm:col-span-8 space-y-2.5 text-xs">
                                        <div class="space-y-1">
                                            <span class="text-slate-400 font-semibold text-2xs">Waktu & Sesi Pelayanan:</span>
                                            <div class="font-extrabold text-slate-900 dark:text-white text-sm sm:text-base flex items-center gap-1.5 text-blue-600 dark:text-blue-400">
                                                <flux:icon name="calendar-days" class="size-4 shrink-0" />
                                                <span>{{ $antrianAktif->jadwalPelayanan->tanggal->translatedFormat('l, d F Y') }}</span>
                                            </div>
                                            <div class="text-2xs sm:text-xs text-slate-600 dark:text-zinc-400 flex items-center gap-1.5 font-medium">
                                                <flux:icon name="clock" class="size-3.5 text-slate-400" />
                                                <span>Pukul {{ substr($antrianAktif->jadwalPelayanan->waktu_mulai, 0, 5) }} - {{ substr($antrianAktif->jadwalPelayanan->waktu_selesai, 0, 5) }} WITA</span>
                                                @if($antrianAktif->jadwalPelayanan->keterangan)
                                                    <span class="truncate">• {{ $antrianAktif->jadwalPelayanan->keterangan }}</span>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="text-2xs text-slate-500 dark:text-zinc-400 bg-white dark:bg-zinc-800/60 p-2.5 rounded-xl border border-slate-200/70 dark:border-zinc-800">
                                            📍 Lokasi: Loket Pelayanan KB UPTD Puskesmas Wundulako. Harap hadir 15 menit sebelum sesi dimulai.
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons for Active Queue -->
                                <div class="pt-2 border-t border-slate-100 dark:border-zinc-800 flex flex-col sm:flex-row items-center justify-between gap-2.5">
                                    <div class="flex flex-wrap items-center gap-2 w-full sm:w-auto">
                                        <flux:button variant="primary" size="sm" wire:click="lihatTiket({{ $antrianAktif->id }})" icon="ticket" class="rounded-xl text-xs font-bold w-full sm:w-auto justify-center">
                                            Buka Kartu Tiket
                                        </flux:button>
                                        <a href="{{ route('tiket.pdf', $antrianAktif->id) }}" target="_blank"
                                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-sm transition-all w-full sm:w-auto">
                                            <flux:icon name="arrow-down-tray" class="size-3.5 text-rose-400" />
                                            Unduh PDF Tiket
                                        </a>
                                    </div>

                                    <!-- Tombol Batalkan Antrian -->
                                    <flux:button variant="danger" size="sm" wire:click="batalkanAntrian({{ $antrianAktif->id }})"
                                        wire:confirm="Apakah Anda yakin ingin membatalkan jadwal antrian ini? Nomor antrian dan kuota akan dibatalkan sehingga Anda dapat memilih jadwal pelayanan yang baru."
                                        icon="x-mark" class="rounded-xl text-xs font-bold w-full sm:w-auto justify-center">
                                        Batalkan / Ubah Jadwal
                                    </flux:button>
                                </div>
                            </div>

                        {{-- ==================== 2. PILIH JADWAL BARU (JIKA TIDAK ADA ANTRIAN AKTIF) ==================== --}}
                        @else
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2 text-blue-600 dark:text-blue-400 font-bold text-xs sm:text-sm">
                                        <flux:icon name="calendar-days" class="size-4 shrink-0" />
                                        Pilih Jadwal Pelayanan yang Tersedia
                                    </div>
                                    <span class="text-3xs sm:text-2xs text-slate-400 font-semibold">*Pilih salah satu jadwal</span>
                                </div>

                                <flux:error name="selectedJadwalId" />

                                @if($jadwalTersedia->count() > 0)
                                    <div class="space-y-3">
                                        @foreach($jadwalTersedia as $jadwal)
                                            @php
                                                $sisa = max(0, $jadwal->kuota - $jadwal->antrians_count);
                                                $isFull = $sisa <= 0;
                                                $isSelected = $selectedJadwalId === $jadwal->id;
                                            @endphp
                                            <label class="block cursor-pointer {{ $isFull ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
                                                <input type="radio" wire:model.live="selectedJadwalId" value="{{ $jadwal->id }}"
                                                    class="peer hidden" {{ $isFull ? 'disabled' : '' }}>
                                                <div class="rounded-2xl border-2 p-4 sm:p-5 transition-all duration-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 sm:gap-4
                                                    {{ $isSelected ? 'border-blue-600 bg-blue-50/70 dark:bg-blue-950/30 dark:border-blue-500 shadow-md ring-2 ring-blue-500/20' : 'border-slate-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 hover:border-slate-300 dark:hover:border-zinc-700' }}
                                                ">
                                                    <!-- Schedule Info -->
                                                    <div class="space-y-1.5 min-w-0">
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <span class="font-black text-sm sm:text-base text-slate-900 dark:text-white">
                                                                {{ $jadwal->tanggal->translatedFormat('l, d F Y') }}
                                                            </span>
                                                            @if($isFull)
                                                                <span class="px-2 py-0.5 rounded-full text-3xs sm:text-2xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-400">Penuh</span>
                                                            @else
                                                                <span class="px-2 py-0.5 rounded-full text-3xs sm:text-2xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">Tersedia</span>
                                                            @endif
                                                        </div>

                                                        <div class="flex flex-wrap items-center gap-2 text-2xs sm:text-xs text-slate-600 dark:text-zinc-400">
                                                            <span class="flex items-center gap-1 font-semibold text-blue-600 dark:text-blue-400 shrink-0">
                                                                <flux:icon name="clock" class="size-3.5" />
                                                                {{ substr($jadwal->waktu_mulai, 0, 5) }} - {{ substr($jadwal->waktu_selesai, 0, 5) }} WITA
                                                            </span>
                                                            @if($jadwal->keterangan)
                                                                <span class="truncate">• {{ $jadwal->keterangan }}</span>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <!-- Quota & Check Indicator -->
                                                    <div class="flex items-center justify-between sm:justify-end gap-3 shrink-0 pt-2 sm:pt-0 border-t sm:border-t-0 border-slate-100 dark:border-zinc-800">
                                                        <div class="text-left sm:text-right">
                                                            <div class="text-3xs sm:text-2xs uppercase tracking-wider text-slate-400 font-bold">Sisa Kuota</div>
                                                            <div class="text-xs sm:text-sm font-black {{ $sisa > 5 ? 'text-emerald-600 dark:text-emerald-400' : 'text-amber-600' }}">
                                                                {{ $sisa }} / {{ $jadwal->kuota }} Kursi
                                                            </div>
                                                        </div>

                                                        <div class="size-6 rounded-full border-2 flex items-center justify-center transition-colors shrink-0
                                                            {{ $isSelected ? 'border-blue-600 bg-blue-600 text-white' : 'border-slate-300 dark:border-zinc-700' }}">
                                                            @if($isSelected)
                                                                <flux:icon name="check" class="size-3.5 stroke-[3]" />
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                                    <div class="pt-2">
                                        <flux:button variant="primary" wire:click="pilihJadwal" :disabled="!$selectedJadwalId" icon="ticket"
                                            class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold shadow-lg shadow-blue-600/25 text-xs sm:text-sm justify-center">
                                            Konfirmasi & Ambil Nomor Antrian
                                        </flux:button>
                                    </div>
                                @else
                                    <div class="text-center py-10 px-6 rounded-3xl bg-slate-50 dark:bg-zinc-800/40 border border-dashed border-slate-300 dark:border-zinc-700 space-y-2">
                                        <flux:icon name="calendar" class="size-8 text-slate-400 mx-auto" />
                                        <h4 class="font-bold text-sm text-slate-800 dark:text-zinc-200">Belum Ada Jadwal Pelayanan Aktif</h4>
                                        <p class="text-xs text-slate-500 dark:text-zinc-400">Silakan cek kembali dalam waktu dekat atau hubungi petugas puskesmas.</p>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- ==================== 3. RIWAYAT PELAYANAN MEDIS KB YANG SUDAH DIJALANI ==================== --}}
                        <div class="space-y-4 pt-4 border-t border-slate-100 dark:border-zinc-800">
                            <div class="flex items-center gap-2 text-slate-900 dark:text-white font-bold text-xs sm:text-sm">
                                <flux:icon name="clipboard-document-check" class="size-4 text-emerald-600 dark:text-emerald-400 shrink-0" />
                                Riwayat Pelayanan KB yang Telah Dijalani
                            </div>

                            @if($riwayatPelayanan->count() > 0)
                                <div class="space-y-3">
                                    @foreach($riwayatPelayanan as $pelayanan)
                                        <div class="rounded-2xl bg-slate-50 dark:bg-zinc-800/50 border border-slate-200/80 dark:border-zinc-700/80 p-4 space-y-2.5">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1.5 border-b border-slate-200/60 dark:border-zinc-700/60 pb-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="size-2 rounded-full bg-emerald-500"></span>
                                                    <span class="font-extrabold text-xs sm:text-sm text-slate-900 dark:text-white">
                                                        {{ $pelayanan->alokon->nama_alokon ?? 'Pelayanan KB' }}
                                                    </span>
                                                    <span class="px-2 py-0.5 rounded-full text-3xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                                        Selesai Dilayani ✓
                                                    </span>
                                                </div>
                                                <span class="text-2xs font-semibold text-slate-500 dark:text-zinc-400">
                                                    {{ $pelayanan->tanggal_pelayanan ? $pelayanan->tanggal_pelayanan->translatedFormat('d F Y') : '-' }}
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 text-2xs text-slate-600 dark:text-zinc-400">
                                                <div>
                                                    <span class="text-slate-400 font-medium">Bidan Pemeriksa:</span>
                                                    <div class="font-bold text-slate-800 dark:text-zinc-200">{{ $pelayanan->user->name ?? 'Bidan Puskesmas' }}</div>
                                                </div>
                                                <div>
                                                    <span class="text-slate-400 font-medium">Jenis Akseptor:</span>
                                                    <div class="font-bold text-slate-800 dark:text-zinc-200">{{ ucfirst($pelayanan->jenis_peserta ?? 'Peserta Aktif') }}</div>
                                                </div>
                                                <div>
                                                    <span class="text-slate-400 font-medium">Jadwal Kontrol Ulang:</span>
                                                    <div class="font-bold text-blue-600 dark:text-blue-400">
                                                        {{ $pelayanan->tanggal_kembali_peserta ? $pelayanan->tanggal_kembali_peserta->translatedFormat('d F Y') : 'Sesuai Kebutuhan' }}
                                                    </div>
                                                </div>
                                            </div>

                                            @if($pelayanan->keluhan_efek_samping || $pelayanan->tindakan_diberikan)
                                                <div class="text-3xs sm:text-2xs text-slate-500 dark:text-zinc-400 pt-1.5 border-t border-slate-200/50 dark:border-zinc-700/50">
                                                    Catatan / Tindakan: {{ $pelayanan->tindakan_diberikan ?? $pelayanan->keluhan_efek_samping ?? '-' }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-6 px-4 rounded-2xl bg-slate-50/60 dark:bg-zinc-800/30 border border-slate-200/60 dark:border-zinc-800 text-slate-500 dark:text-zinc-400 text-2xs sm:text-xs">
                                    Belum ada catatan rekam pelayanan medis sebelumnya di Puskesmas Wundulako.
                                </div>
                            @endif
                        </div>

                        {{-- ==================== 4. RIWAYAT ANTRIAN SEBELUMNYA ==================== --}}
                        @if($riwayatAntrian->count() > 0)
                            <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-zinc-800">
                                <div class="flex items-center gap-2 text-slate-700 dark:text-zinc-300 font-bold text-xs">
                                    <flux:icon name="clock" class="size-3.5 text-slate-400 shrink-0" />
                                    Riwayat Pemesanan Antrian Sebelumnya
                                </div>

                                <div class="space-y-2">
                                    @foreach($riwayatAntrian as $antrian)
                                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50/70 dark:bg-zinc-800/40 border border-slate-200/60 dark:border-zinc-800 text-2xs">
                                            <div class="flex items-center gap-2 min-w-0">
                                                <span class="font-mono font-bold text-slate-700 dark:text-zinc-300 shrink-0">
                                                    #{{ str_pad($antrian->nomor_antrian, 3, '0', STR_PAD_LEFT) }}
                                                </span>
                                                <span class="truncate text-slate-600 dark:text-zinc-400">
                                                    {{ $antrian->jadwalPelayanan ? $antrian->jadwalPelayanan->tanggal->translatedFormat('d M Y') : '-' }}
                                                </span>
                                            </div>

                                            @if($antrian->status === 'hadir')
                                                <span class="px-2 py-0.5 rounded-full text-3xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300">
                                                    Selesai Hadir
                                                </span>
                                            @elseif($antrian->status === 'tidak_hadir')
                                                <span class="px-2 py-0.5 rounded-full text-3xs font-bold bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-400">
                                                    Tidak Hadir / Lewat Jadwal
                                                </span>
                                            @elseif($antrian->status === 'batal')
                                                <span class="px-2 py-0.5 rounded-full text-3xs font-bold bg-slate-100 text-slate-700 dark:bg-zinc-800 dark:text-zinc-400">
                                                    Dibatalkan
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>

                {{-- ==================== STEP 3: TIKET ANTRIAN DIGITAL & DOWNLOAD/PRINT ==================== --}}
                @elseif($step === 'selesai')
                    <div class="space-y-5 sm:space-y-6">

                        <!-- Digital Boarding Pass / Ticket Card -->
                        <div id="tiket-antrian-card" class="rounded-2xl sm:rounded-3xl border-2 border-dashed border-blue-400 dark:border-blue-800 bg-gradient-to-b from-blue-50/60 via-white to-white dark:from-zinc-900 dark:via-zinc-900 dark:to-zinc-900 p-5 sm:p-7 md:p-8 space-y-5 sm:space-y-6 shadow-xl shadow-blue-500/10">

                            <!-- Header Tiket -->
                            <div class="flex items-center justify-between border-b border-slate-200 dark:border-zinc-800 pb-4 gap-2">
                                <div class="flex items-center gap-2.5 sm:gap-3 min-w-0">
                                    <div class="size-10 sm:size-11 rounded-xl bg-blue-600 text-white font-black text-sm flex items-center justify-center shadow-md shrink-0">KB</div>
                                    <div class="min-w-0">
                                        <h3 class="font-black text-sm sm:text-base text-slate-900 dark:text-white leading-tight truncate">Puskesmas Wundulako</h3>
                                        <p class="text-3xs sm:text-2xs text-slate-500 dark:text-zinc-400 truncate">Tiket Antrian Resmi Pelayanan KB</p>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full text-3xs sm:text-2xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 border border-emerald-200 shrink-0">
                                    Terkonfirmasi ✓
                                </span>
                            </div>

                            @if($nomorAntrian)
                                <!-- Main Big Queue Number -->
                                <div class="text-center py-6 sm:py-7 bg-gradient-to-br from-blue-600 via-indigo-600 to-blue-700 rounded-2xl sm:rounded-3xl text-white shadow-xl shadow-blue-600/30 space-y-1">
                                    <div class="text-3xs sm:text-2xs uppercase tracking-widest font-extrabold text-blue-200">Nomor Antrian Anda</div>
                                    <div class="text-5xl sm:text-7xl font-black tracking-tight drop-shadow-md">
                                        {{ str_pad($nomorAntrian, 3, '0', STR_PAD_LEFT) }}
                                    </div>
                                    <div class="text-2xs sm:text-xs text-blue-100 font-medium px-2">Harap simpan file PDF atau cetak tiket ini</div>
                                </div>

                                <!-- Detail Grid -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 sm:gap-4 bg-slate-50 dark:bg-zinc-800/60 p-4 sm:p-5 rounded-2xl border border-slate-100 dark:border-zinc-800 text-xs">
                                    <div class="space-y-1 min-w-0">
                                        <span class="text-slate-400 font-semibold text-2xs sm:text-xs">Nama Pasien:</span>
                                        <div class="font-bold text-slate-900 dark:text-white text-xs sm:text-sm truncate">
                                            {{ $foundPeserta->nama_lengkap ?? $nama_lengkap }}
                                        </div>
                                    </div>
                                    <div class="space-y-1 min-w-0">
                                        <span class="text-slate-400 font-semibold text-2xs sm:text-xs">Nomor NIK:</span>
                                        <div class="font-mono font-bold text-slate-900 dark:text-white text-xs sm:text-sm truncate">
                                            {{ $foundPeserta->nik ?? $nik }}
                                        </div>
                                    </div>
                                    <div class="sm:col-span-2 pt-2 border-t border-slate-200 dark:border-zinc-700 space-y-1 min-w-0">
                                        <span class="text-slate-400 font-semibold text-2xs sm:text-xs">Jadwal Kehadiran:</span>
                                        <div class="font-bold text-blue-600 dark:text-blue-400 text-xs sm:text-sm flex items-center gap-1.5">
                                            <flux:icon name="calendar-days" class="size-4 shrink-0" />
                                            <span class="truncate">{{ $jadwalInfo }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Instructions -->
                            <div class="space-y-2 text-2xs sm:text-xs text-slate-500 dark:text-zinc-400 bg-slate-50 dark:bg-zinc-800/30 p-3.5 sm:p-4 rounded-xl">
                                <div class="font-bold text-slate-700 dark:text-zinc-300 flex items-center gap-1.5">
                                    <flux:icon name="information-circle" class="size-3.5 sm:size-4 text-blue-500 shrink-0" />
                                    Petunjuk Kehadiran di Puskesmas:
                                </div>
                                <ul class="list-disc list-inside space-y-1 pl-1">
                                    <li>Bawa KTP Asli dan Kartu BPJS/KIS (jika ada).</li>
                                    <li>Tunjukkan lembar tiket PDF atau nomor antrian ini ke loket pelayanan KB.</li>
                                    <li>Hadir 15 menit sebelum waktu pelayanan dimulai.</li>
                                </ul>
                            </div>

                            <!-- Footer Card Watermark & Security Code -->
                            <div class="pt-2 border-t border-slate-100 dark:border-zinc-800 flex items-center justify-between text-3xs text-slate-400">
                                <span>Kecamatan Wundulako, Kab. Kolaka</span>
                                <span>Verifikasi Digital Terintegrasi</span>
                            </div>
                        </div>

                        <!-- Action Buttons: Direct DomPDF Download & Print -->
                        <div class="space-y-3 no-print">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 sm:gap-3">
                                <!-- Tombol Unduh PDF Langsung via DomPDF -->
                                @if($antrianId)
                                    <a href="{{ route('tiket.pdf', $antrianId) }}" target="_blank"
                                        class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm shadow-md transition-all cursor-pointer">
                                        <svg class="size-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                        Unduh Tiket (PDF)
                                    </a>
                                @else
                                    <button type="button" wire:click="unduhPdf"
                                        class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm shadow-md transition-all cursor-pointer">
                                        <span wire:loading.remove wire:target="unduhPdf" class="flex items-center gap-2">
                                            <svg class="size-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                            </svg>
                                            Unduh Tiket (PDF)
                                        </span>
                                        <span wire:loading wire:target="unduhPdf" class="flex items-center gap-2">
                                            <svg class="animate-spin size-4 text-rose-400" viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                            Menyiapkan PDF...
                                        </span>
                                    </button>
                                @endif

                                <!-- Tombol Cetak Langsung -->
                                <button type="button" onclick="window.print()"
                                    class="w-full flex items-center justify-center gap-2 py-3 px-4 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold text-xs sm:text-sm shadow-md shadow-blue-600/20 transition-all cursor-pointer">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.656" />
                                    </svg>
                                    Cetak Tiket / Print
                                </button>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-2.5 justify-center pt-2">
                                <flux:button variant="outline" wire:click="resetForm" icon="arrow-path" class="w-full sm:w-auto rounded-xl font-semibold text-xs sm:text-sm justify-center">
                                    Pengecekan NIK Lain
                                </flux:button>
                                <flux:button variant="ghost" href="{{ route('home') }}" icon="home" wire:navigate class="w-full sm:w-auto rounded-xl font-semibold text-xs sm:text-sm justify-center text-slate-500">
                                    Kembali ke Beranda
                                </flux:button>
                            </div>
                        </div>

                    </div>
                @endif

            </div>
        </div>
    </main>

    <!-- ============ FOOTER ============ -->
    <footer class="py-5 sm:py-6 text-center text-3xs sm:text-xs text-slate-400 dark:text-zinc-600 border-t border-slate-200/80 dark:border-zinc-900 px-4 no-print">
        &copy; {{ now()->year }} SI Pelayanan KB Puskesmas Wundulako, Kab. Kolaka. All rights reserved.
    </footer>
</div>
