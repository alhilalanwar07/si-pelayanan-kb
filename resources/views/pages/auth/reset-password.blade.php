<x-layouts::auth :title="__('Atur Ulang Kata Sandi - SI Pelayanan KB')">
    <div class="flex flex-col gap-6">
        <div class="space-y-2 text-left">
            <h2 class="text-2xl font-black text-white tracking-tight">
                Atur Ulang Kata Sandi 🔑
            </h2>
            <p class="text-xs text-slate-400 leading-relaxed">
                Silakan buat kata sandi baru untuk akun Anda.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-5">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <flux:field>
                <flux:label class="text-xs font-semibold text-slate-300">Alamat Email</flux:label>
                <flux:input
                    name="email"
                    value="{{ request('email') }}"
                    type="email"
                    required
                    autocomplete="email"
                    icon="envelope"
                    class="rounded-xl bg-slate-800/80 border-slate-700 text-white placeholder-slate-500 text-sm focus:border-blue-500 focus:ring-blue-500/20"
                />
                <flux:error name="email" />
            </flux:field>

            <!-- Password -->
            <flux:field>
                <flux:label class="text-xs font-semibold text-slate-300">Kata Sandi Baru</flux:label>
                <flux:input
                    name="password"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                    icon="key"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable
                    class="rounded-xl bg-slate-800/80 border-slate-700 text-white placeholder-slate-500 text-sm focus:border-blue-500 focus:ring-blue-500/20"
                />
                <flux:error name="password" />
            </flux:field>

            <!-- Confirm Password -->
            <flux:field>
                <flux:label class="text-xs font-semibold text-slate-300">Konfirmasi Kata Sandi Baru</flux:label>
                <flux:input
                    name="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="••••••••"
                    icon="key"
                    passwordrules="{{ \Illuminate\Validation\Rules\Password::defaults()->toPasswordRulesString() }}"
                    viewable
                    class="rounded-xl bg-slate-800/80 border-slate-700 text-white placeholder-slate-500 text-sm focus:border-blue-500 focus:ring-blue-500/20"
                />
                <flux:error name="password_confirmation" />
            </flux:field>

            <div class="pt-2">
                <flux:button type="submit" variant="primary" class="w-full py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold shadow-lg shadow-blue-600/30 text-sm" data-test="reset-password-button">
                    Simpan Kata Sandi Baru
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts::auth>
