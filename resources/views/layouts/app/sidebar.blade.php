<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        <flux:sidebar.nav>
            {{-- Dashboard --}}
            <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                wire:navigate>
                Dashboard
            </flux:sidebar.item>

            {{-- Master Data (admin only) --}}
            @if(auth()->user()->isAdmin())
                <flux:separator class="my-3" />
                <flux:sidebar.group :heading="__('Master Data')">
                    <flux:sidebar.item icon="map-pin" :href="route('wilayah.index')"
                        :current="request()->routeIs('wilayah.*')" wire:navigate>
                        Data Wilayah
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="users" :href="route('peserta-kb.index')"
                        :current="request()->routeIs('peserta-kb.*')" wire:navigate>
                        Data Peserta KB
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="archive-box" :href="route('alokon.index')"
                        :current="request()->routeIs('alokon.*')" wire:navigate>
                        Inventaris Alokon
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="cog-6-tooth" :href="route('pengguna.index')"
                        :current="request()->routeIs('pengguna.*')" wire:navigate>
                        Manajemen Pengguna
                    </flux:sidebar.item>
                </flux:sidebar.group>
            @endif

            {{-- Data Peserta (bidan - tanpa group, langsung item) --}}
            @if(auth()->user()->isBidan())
                <flux:separator class="my-3" />
                <flux:sidebar.item icon="users" :href="route('peserta-kb.index')"
                    :current="request()->routeIs('peserta-kb.*')" wire:navigate>
                    Data Peserta KB
                </flux:sidebar.item>
            @endif

            {{-- Pelayanan (admin & bidan) --}}
            @if(auth()->user()->isAdmin() || auth()->user()->isBidan())
                <flux:separator class="my-3" />
                <flux:sidebar.group :heading="__('Pelayanan')">
                    <flux:sidebar.item icon="clipboard-document-check" :href="route('pelayanan.index')"
                        :current="request()->routeIs('pelayanan.*')" wire:navigate>
                        Pelayanan KB
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="heart" :href="route('skrining-medis.index')"
                        :current="request()->routeIs('skrining-medis.*')" wire:navigate>
                        Skrining Medis
                    </flux:sidebar.item>
                    <flux:sidebar.item icon="document-check" :href="route('informed-consent.index')"
                        :current="request()->routeIs('informed-consent.*')" wire:navigate>
                        Informed Consent
                    </flux:sidebar.item>
                </flux:sidebar.group>
            @endif

            {{-- Laporan & Peta (semua role) --}}
            <flux:separator class="my-3 " />
            <flux:sidebar.group :heading="__('Analisis')">
                <flux:sidebar.item icon="chart-bar" :href="route('laporan.index')"
                    :current="request()->routeIs('laporan.*')" wire:navigate>
                    Cetak Laporan
                </flux:sidebar.item>
                <flux:sidebar.item icon="map" :href="route('peta-sebaran.index')"
                    :current="request()->routeIs('peta-sebaran.*')" wire:navigate>
                    Peta Sebaran
                </flux:sidebar.item>
            </flux:sidebar.group>
        </flux:sidebar.nav>

        <flux:spacer />

        {{-- User menu (desktop) --}}
        <x-desktop-user-menu :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->labelLevelAkses() }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer">
                        {{ __('Keluar') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
    <flux:toast.group position="top end">
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>