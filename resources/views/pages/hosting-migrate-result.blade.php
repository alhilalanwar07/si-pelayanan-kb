<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Migrasi Database Hosting - SI Pelayanan KB</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        code, pre { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col justify-between p-4 sm:p-6 lg:p-8">
    <div class="max-w-3xl mx-auto w-full space-y-6 my-auto">
        <!-- Header -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-xs font-bold tracking-wide uppercase">
                Fasilitas Faskes & DPPKB
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Pembaruan Skema Database Hosting
            </h1>
            <p class="text-xs sm:text-sm text-slate-500">
                Layanan eksekusi migrasi tabel dan kolom baru pada server hosting
            </p>
        </div>

        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-xl shadow-slate-200/60 border {{ $success ? 'border-emerald-200' : 'border-rose-200' }} overflow-hidden">
            <!-- Banner Status -->
            <div class="{{ $success ? 'bg-emerald-600' : 'bg-rose-600' }} text-white p-5 sm:p-6 flex items-start gap-4">
                <div class="size-11 sm:size-12 rounded-xl bg-white/20 backdrop-blur-xs flex items-center justify-center shrink-0">
                    @if($success)
                        <svg class="size-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    @else
                        <svg class="size-7 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                        </svg>
                    @endif
                </div>
                <div>
                    <h2 class="text-lg sm:text-xl font-bold tracking-tight">
                        {{ $success ? 'Migrasi Database Berhasil Dijalankan!' : 'Terjadi Kendala Saat Menjalankan Migrasi' }}
                    </h2>
                    <p class="text-xs sm:text-sm text-white/90 mt-1 leading-relaxed">
                        {{ $success ? 'Perintah migrasi Laravel telah dieksekusi dengan opsi --force di hosting.' : 'Silakan periksa log error di bawah ini untuk melihat kendala koneksi atau skema database.' }}
                    </p>
                </div>
            </div>

            <!-- Content Details -->
            <div class="p-5 sm:p-7 space-y-6">
                <!-- Check Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- Check Kolom NIP -->
                    <div class="p-4 rounded-xl border {{ $hasNipColumn ? 'bg-emerald-50/50 border-emerald-200' : 'bg-rose-50/50 border-rose-200' }} flex items-start gap-3">
                        <div class="size-8 rounded-lg {{ $hasNipColumn ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} flex items-center justify-center shrink-0 mt-0.5">
                            @if($hasNipColumn)
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                            @else
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                            @endif
                        </div>
                        <div>
                            <span class="text-xs font-bold block text-slate-800">Kolom Baru (NIP Petugas)</span>
                            <span class="text-xs {{ $hasNipColumn ? 'text-emerald-700' : 'text-rose-700' }}">
                                {{ $hasNipColumn ? 'Tersedia di tabel users (users.nip)' : 'Belum ditemukan di tabel users' }}
                            </span>
                        </div>
                    </div>

                    <!-- Cache Status -->
                    <div class="p-4 rounded-xl border bg-blue-50/50 border-blue-200 flex items-start gap-3">
                        <div class="size-8 rounded-lg bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold block text-slate-800">Pembersihan Cache</span>
                            <span class="text-xs text-blue-700">
                                Cache optimize & view dibersihkan
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Updated Users List -->
                @if(count($updatedUsers) > 0)
                    <div class="space-y-2">
                        <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Inisialisasi Data NIP Akun Bawaan</span>
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-700 space-y-1">
                            @foreach($updatedUsers as $userMsg)
                                <div class="flex items-center gap-2">
                                    <svg class="size-3.5 text-emerald-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                    <span>{{ $userMsg }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Terminal / Artisan Output -->
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Log Output Artisan Migrate</span>
                    <pre class="bg-slate-900 text-slate-200 p-4 rounded-xl text-xs overflow-x-auto leading-relaxed max-h-56">{{ trim($output) ?: 'Tidak ada migrasi baru yang perlu dijalankan (Database sudah mutakhir).' }}</pre>
                </div>

                <!-- Action Navigation -->
                <div class="flex flex-col sm:flex-row gap-3 pt-3 border-t border-slate-100">
                    <a href="{{ route('login') }}" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl font-bold text-xs sm:text-sm transition-colors shadow-md shadow-blue-600/20">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                        <span>Menuju Halaman Login</span>
                    </a>
                    <a href="{{ url('/') }}" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold text-xs sm:text-sm transition-colors">
                        <span>Halaman Utama</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Security Note -->
        <p class="text-center text-2xs sm:text-xs text-slate-400">
            Sistem Informasi Pelayanan KB - Faskes DPPKB Wundulako
        </p>
    </div>
</body>
</html>
