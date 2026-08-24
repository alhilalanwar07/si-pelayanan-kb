<x-layouts::auth :title="__('Masuk ke Sistem - SI Pelayanan KB')">
    <div class="flex flex-col gap-6">
        <!-- Header Section -->
        <div class="space-y-2 text-left">
            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-2xs font-bold bg-blue-500/10 text-blue-400 border border-blue-500/20">
                <flux:icon name="lock-closed" class="size-3" />
                <span>Portal Petugas & Bidan</span>
            </div>
            <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight">
                Selamat Datang 👋
            </h2>
            <p class="text-xs text-slate-400 leading-relaxed">
                Silakan masukkan username dan kata sandi Anda untuk mengakses dashboard pelayanan.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Username Input -->
            <flux:field>
                <flux:label class="text-xs font-semibold text-slate-300">Username Petugas</flux:label>
                <flux:input
                    name="username"
                    :value="old('username')"
                    type="text"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="Contoh: admin atau bidan"
                    icon="user"
                    class="rounded-xl bg-slate-800/80 border-slate-700 text-white placeholder-slate-500 text-sm focus:border-blue-500 focus:ring-blue-500/20"
                />
                <flux:error name="username" />
            </flux:field>

            <!-- Password Input -->
            <flux:field>
                <flux:label class="text-xs font-semibold text-slate-300">Kata Sandi</flux:label>
                <flux:input
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    icon="key"
                    viewable
                    class="rounded-xl bg-slate-800/80 border-slate-700 text-white placeholder-slate-500 text-sm focus:border-blue-500 focus:ring-blue-500/20"
                />
                <flux:error name="password" />
            </flux:field>

            <!-- Remember Me -->
            <div class="flex items-center justify-between pt-1">
                <flux:checkbox name="remember" :label="__('Ingat sesi saya')" :checked="old('remember')" class="text-xs text-slate-300" />
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <flux:button variant="primary" type="submit" icon:trailing="arrow-right" class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold shadow-lg shadow-blue-600/30 text-sm">
                    Masuk ke Sistem
                </flux:button>
            </div>
        </form>

        <!-- Divider & Quick Link for Patients -->
        <div class="pt-4 border-t border-slate-800/80">
            <div class="p-3.5 rounded-2xl bg-blue-950/40 border border-blue-900/50 flex items-center justify-between gap-3">
                <div class="space-y-0.5">
                    <div class="text-2xs font-bold text-slate-200">Pasien / Akseptor KB?</div>
                    <div class="text-3xs text-slate-400">Pesan nomor antrian secara mandiri</div>
                </div>
                <a href="{{ route('registrasi') }}" class="px-3 py-1.5 rounded-xl bg-blue-600 hover:bg-blue-500 text-white text-2xs font-bold shadow-md shadow-blue-600/20 transition-all shrink-0" wire:navigate>
                    Daftar Antrian
                </a>
            </div>
        </div>

    </div>
</x-layouts::auth>
