@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="{{ config('app.name', 'SI Pelayanan KB') }}" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-transparent">
            <x-app-logo-icon class="size-7 object-contain" />
        </x-slot>
    </flux:sidebar.brand>
@else   
    <flux:brand name="{{ config('app.name', 'SI Pelayanan KB') }}" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center rounded-md bg-transparent">
            <x-app-logo-icon class="size-7 object-contain" />
        </x-slot>
    </flux:brand>
@endif

