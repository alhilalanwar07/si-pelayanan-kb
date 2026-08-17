<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb5hBydBmjaUGFBi13TLChqxUGa5I9vwcxlicILOwtW59RChNf04CL+Zo5wf3VgMB3n22BQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-qZvrmS2ekKPF2mSznubP9wIIqxDaVJxPX49P4944noKmKE5WubqF5109i2E64po0oSmqOzybi+7/ZAE6TE8Ksw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</head>
<body class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-100 antialiased">
    
    {{ $slot }}

    @persist('toast')
    <flux:toast.group position="top end">
        <flux:toast />
    </flux:toast.group>
    @endpersist

    @fluxScripts
</body>
</html>
