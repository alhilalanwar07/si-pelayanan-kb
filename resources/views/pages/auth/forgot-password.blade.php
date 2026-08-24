<x-layouts::auth :title="__('Lupa Kata Sandi - SI Pelayanan KB')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2 text-left">
            <h2 class="text-2xl font-black text-white tracking-tight">
                Lupa Kata Sandi? 🔒
            </h2>
            <p class="text-xs text-slate-400 leading-relaxed">
                Masukkan email akun Anda untuk menerima tautan pemulihan kata sandi.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <flux:field>
                <flux:label class="text-xs font-semibold text-slate-300">Alamat Email</flux:label>
                <flux:input
                    name="email"
                    type="email"
                    required
                    autofocus
                    placeholder="nama@puskesmas.go.id"
                    icon="envelope"
                    class="rounded-xl bg-slate-800/80 border-slate-700 text-white placeholder-slate-500 text-sm focus:border-blue-500 focus:ring-blue-500/20"
                />
                <flux:error name="email" />
            </flux:field>

            <div class="pt-2">
                <flux:button variant="primary" type="submit" class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold shadow-lg shadow-blue-600/30 text-sm" data-test="email-password-reset-link-button">
                    Kirim Tautan Reset Kata Sandi
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 text-center text-xs text-slate-400 pt-2 border-t border-slate-800">
            <span>Kembali ke halaman</span>
            <flux:link :href="route('login')" wire:navigate class="text-blue-400 hover:underline font-semibold">Masuk Petugas</flux:link>
        </div>
    </div>
</x-layouts::auth>
