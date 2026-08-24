<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-slate-900 text-slate-100 antialiased font-sans selection:bg-blue-600 selection:text-white">
        <div class="relative min-h-screen grid lg:grid-cols-12 overflow-hidden">

            <!-- ==================== LEFT HERO / BRAND SHOWCASE (Desktop & Tablet) ==================== -->
            <div class="relative hidden lg:flex lg:col-span-7 xl:col-span-7 flex-col justify-between p-10 xl:p-14 bg-slate-950 overflow-hidden border-r border-slate-800/80">
                <!-- Background Image DPPKB with Enhanced Gradient Overlay -->
                <div class="absolute inset-0 bg-cover bg-center scale-105 transition-transform duration-1000 ease-out" style="background-image: url('{{ asset('DPPKB KECAMATAN WUNDULAKO.webp') }}')"></div>
                <div class="absolute inset-0 bg-gradient-to-tr from-slate-950 via-slate-950/90 to-blue-950/80"></div>
                <div class="absolute inset-0 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:24px_24px] opacity-15 pointer-events-none"></div>

                <!-- Glowing Ambient Lights -->
                <div class="absolute -top-24 -left-24 size-96 bg-blue-500/25 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute bottom-10 right-10 size-80 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Header Branding -->
                <div class="relative z-20 flex items-center justify-between">
                    <a href="{{ route('home') }}" class="flex items-center gap-3.5 group" wire:navigate>
                        <div class="size-11 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 p-2 flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform">
                            <img src="{{ asset('img-logo-login.webp') }}" alt="Logo" class="size-full object-contain" />
                        </div>
                        <div>
                            <div class="font-extrabold text-base tracking-tight text-white flex items-center gap-2">
                                <span>SI Pelayanan KB</span>
                                <span class="px-2 py-0.5 rounded-full text-3xs font-bold bg-blue-500/20 text-blue-300 border border-blue-400/30">
                                    Puskesmas Wundulako
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 font-medium">Kabupaten Kolaka, Sulawesi Tenggara</p>
                        </div>
                    </a>
                </div>

                <!-- Center Feature Pitch & Highlights -->
                <div class="relative z-20 my-auto py-12 max-w-xl space-y-8">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 rounded-full bg-blue-500/10 border border-blue-400/25 px-3.5 py-1 text-xs font-bold text-blue-300 backdrop-blur-md">
                            <span class="size-2 rounded-full bg-blue-400 animate-pulse"></span>
                            Portal Manajemen & Rekam Medis Petugas
                        </div>
                        <h1 class="text-3xl xl:text-4xl font-black text-white tracking-tight leading-tight">
                            Digitalisasi Pelayanan KB yang <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-sky-300 to-teal-300">Aman, Terintegrasi & Cepat</span>
                        </h1>
                        <p class="text-sm text-slate-300/90 leading-relaxed">
                            Mendukung tenaga medis dan pengelola program KB dalam pencatatan akseptor, skrining medis kelayakan alokon, dan pelaporan akurat secara real-time.
                        </p>
                    </div>

                    <!-- Glassmorphism Highlight Cards -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5 pt-2">
                        <div class="p-3.5 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 space-y-1.5 hover:bg-white/10 transition-colors">
                            <div class="size-8 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-sm">
                                <flux:icon name="clipboard-document-check" class="size-4" />
                            </div>
                            <div class="font-bold text-xs text-white">Skrining & Informed Consent</div>
                            <p class="text-3xs text-slate-400 leading-normal">Pemeriksaan medis terstandar BKKBN dan persetujuan tindakan medis digital.</p>
                        </div>

                        <div class="p-3.5 rounded-2xl bg-white/5 backdrop-blur-md border border-white/10 space-y-1.5 hover:bg-white/10 transition-colors">
                            <div class="size-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm">
                                <flux:icon name="queue-list" class="size-4" />
                            </div>
                            <div class="font-bold text-xs text-white">Antrian Terhubung Langsung</div>
                            <p class="text-3xs text-slate-400 leading-normal">Pendaftaran mandiri pasien terhubung langsung ke meja pelayanan bidan.</p>
                        </div>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="relative z-20 pt-6 border-t border-white/10 flex items-center justify-between text-2xs text-slate-400">
                    <div>
                        © {{ date('Y') }} UPTD Puskesmas Wundulako • DPPKB Kab. Kolaka
                    </div>
                    <div class="flex items-center gap-1 text-slate-400">
                        <flux:icon name="shield-check" class="size-3.5 text-emerald-400" />
                        <span>Sistem Terenkripsi & Terlindungi</span>
                    </div>
                </div>
            </div>

            <!-- ==================== RIGHT AUTH FORM SECTION ==================== -->
            <div class="lg:col-span-5 xl:col-span-5 flex flex-col justify-between p-6 sm:p-10 xl:p-12 bg-slate-900 dark:bg-zinc-950 relative min-h-screen overflow-y-auto">
                <!-- Top Navigation -->
                <div class="flex items-center justify-between w-full">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-400 hover:text-white transition-colors" wire:navigate>
                        <flux:icon name="arrow-left" class="size-4" />
                        <span>Kembali ke Beranda</span>
                    </a>

                    <a href="{{ route('registrasi') }}" class="inline-flex items-center gap-1 text-2xs font-bold text-blue-400 hover:text-blue-300 px-2.5 py-1 rounded-xl bg-blue-950/60 border border-blue-800/60 transition-colors" wire:navigate>
                        <flux:icon name="ticket" class="size-3" />
                        <span>Daftar Antrian Pasien</span>
                    </a>
                </div>

                <!-- Center Auth Form Slot -->
                <div class="my-auto py-8 w-full max-w-sm mx-auto">
                    <!-- Mobile Logo (Shown on screens < lg) -->
                    <div class="flex lg:hidden flex-col items-center text-center gap-2 mb-6">
                        <div class="size-12 rounded-2xl bg-blue-600/20 border border-blue-500/30 p-2 flex items-center justify-center shadow-lg">
                            <img src="{{ asset('img-logo-login.webp') }}" alt="Logo" class="size-full object-contain" />
                        </div>
                        <h2 class="font-extrabold text-base text-white">SI Pelayanan KB</h2>
                        <span class="text-2xs text-slate-400">UPTD Puskesmas Wundulako</span>
                    </div>

                    {{ $slot }}
                </div>

                <!-- Bottom Helper Text -->
                <div class="text-center pt-6 border-t border-slate-800/80 text-2xs text-slate-500">
                    Butuh bantuan akses akun? Hubungi Administrator Puskesmas Wundulako.
                </div>
            </div>

        </div>

        @persist('toast')
            <flux:toast.group position="top end">
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
