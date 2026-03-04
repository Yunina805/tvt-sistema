<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>TVT Sistema — Acceso</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen font-sans antialiased">
    <div class="min-h-screen flex">

        {{-- Panel izquierdo: Branding --}}
        <div class="hidden lg:flex lg:w-[55%] bg-slate-900 flex-col justify-between p-12 relative overflow-hidden">
            {{-- Decoración de fondo --}}
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute -top-32 -left-32 w-96 h-96 bg-red-600 rounded-full opacity-10"></div>
                <div class="absolute -bottom-32 -right-32 w-96 h-96 bg-indigo-600 rounded-full opacity-10"></div>
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-slate-800 rounded-full opacity-30"></div>
            </div>

            {{-- Logo y nombre --}}
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-12">
                    <div class="w-14 h-14 bg-red-600 rounded-2xl flex items-center justify-center shadow-lg shadow-red-900/40">
                        <i class="ri-tv-2-line text-white text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-white text-xl font-black uppercase tracking-widest leading-none">TVT Sistema</p>
                        <p class="text-slate-400 text-xs font-medium tracking-[0.2em] mt-1">Tu Visión Telecable</p>
                    </div>
                </div>

                <h2 class="text-4xl font-black text-white leading-tight mb-4">
                    Sistema de<br>Gestión Empresarial
                </h2>
                <p class="text-slate-400 text-sm leading-relaxed max-w-sm">
                    Plataforma integrada para la gestión de clientes, infraestructura de red y operaciones del ISP de cable e internet.
                </p>
            </div>

            {{-- Módulos disponibles --}}
            <div class="relative z-10">
                <p class="text-slate-500 text-[9px] font-black uppercase tracking-[0.3em] mb-4">Módulos disponibles</p>
                <div class="grid grid-cols-2 gap-3">
                    @foreach([
                        ['ri-user-settings-line',        'Gestión de Clientes'],
                        ['ri-router-line',               'Control de Infraestructura'],
                        ['ri-team-line',                 'Recursos Humanos'],
                        ['ri-money-dollar-circle-line',  'Finanzas'],
                    ] as [$icon, $label])
                        <div class="flex items-center gap-3 p-3 bg-slate-800 rounded-xl border border-slate-700/50">
                            <i class="{{ $icon }} text-slate-400 text-base shrink-0"></i>
                            <span class="text-slate-300 text-xs font-medium">{{ $label }}</span>
                        </div>
                    @endforeach
                </div>

                <p class="text-slate-600 text-[9px] font-medium tracking-widest mt-8 uppercase">
                    v1.0 — Acceso Empresarial Exclusivo
                </p>
            </div>
        </div>

        {{-- Panel derecho: Formulario --}}
        <div class="flex-1 flex items-center justify-center bg-white px-8 py-12">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
