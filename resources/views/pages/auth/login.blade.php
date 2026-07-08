<x-layouts::auth :title="__('Masuk ke Sistem')">
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Selamat Datang 👋')" :description="__('Silakan masuk ke akun Anda untuk mengakses Sistem Informasi Pelayanan KB.')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-6">
            @csrf

            <!-- Username -->
            <flux:input
                name="username"
                :label="__('Username')"
                :value="old('username')"
                type="text"
                required
                autofocus
                autocomplete="username"
                placeholder="Masukkan username Anda"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Password')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('••••••••')"
                viewable
            />

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Ingat sesi saya')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full">
                    {{ __('Masuk ke Sistem') }}
                </flux:button>
            </div>
        </form>

    </div>
</x-layouts::auth>
