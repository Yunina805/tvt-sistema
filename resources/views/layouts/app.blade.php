<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{
        sidebarOpen: true,
        mobileSidebarOpen: false,
        darkMode: localStorage.getItem('tvtDarkMode') === '1',
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('tvtDarkMode', this.darkMode ? '1' : '0');
        }
    }" :class="{ dark: darkMode }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Anti-FOUC: aplica dark class antes de que Alpine cargue --}}
    <script>if (localStorage.getItem('tvtDarkMode') === '1') document.documentElement.classList.add('dark');</script>

    <title>{{ config('app.name', 'TVT Sistema') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Scrollbar delgado sidebar */
        .sidebar-scroll::-webkit-scrollbar {
            width: 3px;
        }

        .sidebar-scroll::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #c7d2fe;
            border-radius: 4px;
        }

        /* Link activo */
        .nav-active {
            background: linear-gradient(90deg, #4338ca 0%, #6366f1 100%);
            color: #fff !important;
            box-shadow: 0 2px 8px rgba(99, 102, 241, .3);
        }

        .nav-active i {
            color: #fff !important;
        }

        /* Sidebar colapsado — solo íconos */
        .sidebar-collapsed {
            width: 64px !important;
        }

        .sidebar-collapsed .hide-collapsed {
            display: none !important;
        }

        .sidebar-collapsed .nav-item {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }

        .sidebar-collapsed .sidebar-logo {
            justify-content: center !important;
        }

        .sidebar-collapsed .section-label {
            display: none !important;
        }

        /* ══════════════════════════════════════════════════════════════
               DARK MODE — overrides globales (cubre vistas sin dark:clases)
               Paleta: slate-950 (#0f172a) · slate-800 (#1e293b) · slate-700 (#334155)
               ══════════════════════════════════════════════════════════════ */

        /* Fondos principales */
        .dark .bg-white {
            background-color: #1e293b;
        }

        .dark .bg-gray-50 {
            background-color: #0f172a;
        }

        .dark .bg-gray-100 {
            background-color: #273444;
        }

        /* Bordes */
        .dark .border-gray-50 {
            border-color: #1e293b;
        }

        .dark .border-gray-100 {
            border-color: #273444;
        }

        .dark .border-gray-200 {
            border-color: #334155;
        }

        .dark .border-slate-200 {
            border-color: #334155;
        }

        .dark .border-red-100 {
            border-color: rgba(127, 29, 29, .35);
        }

        /* Textos oscuros → versión legible en dark */
        .dark .text-gray-900 {
            color: #f1f5f9;
        }

        .dark .text-gray-800 {
            color: #e2e8f0;
        }

        .dark .text-gray-700 {
            color: #cbd5e1;
        }

        .dark .text-gray-600 {
            color: #94a3b8;
        }

        /* ── Tablas: divisores y hover ── */
        .dark .divide-y>*+* {
            border-color: #273444;
        }

        .dark .divide-gray-50>*+* {
            border-color: #1e293b;
        }

        .dark .divide-gray-100>*+* {
            border-color: #273444;
        }

        .dark .hover\:bg-gray-50:hover {
            background-color: #273444 !important;
        }

        .dark .hover\:bg-gray-100:hover {
            background-color: #334155 !important;
        }

        /* ── Formularios (inputs, select, textarea) ── */
        .dark input:not([type="checkbox"]):not([type="radio"]),
        .dark select,
        .dark textarea {
            background-color: #1e293b;
            color: #e2e8f0;
            border-color: #475569;
        }

        .dark input::placeholder,
        .dark textarea::placeholder {
            color: #64748b;
        }

        .dark input:disabled,
        .dark select:disabled,
        .dark textarea:disabled {
            background-color: #0f172a !important;
            color: #475569 !important;
        }

        /* ── Sidebar: agrupaciones y pills de navegación ── */
        .dark aside nav .bg-slate-100 {
            background-color: #273444 !important;
        }

        .dark aside nav .hover\:bg-slate-100:hover {
            background-color: #273444 !important;
        }

        .dark aside nav .text-slate-700 {
            color: #94a3b8 !important;
        }

        .dark aside nav .text-gray-600 {
            color: #94a3b8 !important;
        }

        .dark aside nav .text-gray-500 {
            color: #64748b !important;
        }

        /* Scrollbar sidebar en dark */
        .dark .sidebar-scroll::-webkit-scrollbar-thumb {
            background: #475569;
        }

        /* ── Badges con fondo gris en tablas ── */
        .dark .bg-gray-100.text-gray-700 {
            background-color: #334155;
            color: #94a3b8;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900 dark:text-slate-100 bg-gray-50 dark:bg-slate-950">

    {{-- Overlay mobile --}}
    <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false"
        class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden transition-opacity duration-300"></div>

    <div class="flex h-screen overflow-hidden">

        {{-- ================================================================
        SIDEBAR
        ================================================================ --}}
        @php
            $gcActive = request()->routeIs([
                'contrataciones.nuevas',
                'servicios.adicionales',
                'contratacion.promocion',
                'pago.mensualidad',
                'estado.cuenta',
                'suspension.clientes',
                'reconexion.cliente',
                'cancelacion.servicio',
                'recuperacion.equipos',
                'reportes.servicio',
                'reportes.atender',
            ]);
            $infraActive = request()->routeIs('infraestructura.*', 'energia.ctc');
            $rrhhActive = request()->routeIs('rrhh.*');
            $planActive = request()->routeIs('plan.*');
            $finActive = request()->routeIs('financiero.*');
            $clientesActive = request()->routeIs('clientes.*');
            $svcActive = request()->routeIs('cat.servicios.*');
            $actActive = request()->routeIs('cat.actividades.*');
            $plantaActive = request()->routeIs('planta.*', 'energia.fibra');
            $tvActive = request()->routeIs('tv.*');
            $redActive = request()->routeIs('red.*');
            $upsActive = request()->routeIs('energia.ups');
            $fibraActive = request()->routeIs('energia.fibra');
            $ctcActive = request()->routeIs('energia.ctc');
            $regulatorioActive = request()->routeIs('regulatorio.*');

            // ── Control de acceso por módulo ─────────────────────────────────
            $_acc = auth()->user()?->accesoSistema;
            $_admin = $_acc?->rol === 'ADMINISTRADOR';
            $_mods = $_acc?->modulos ?? [];

            // ¿Tiene acceso al módulo completo?
            $_canMod = fn($mod) => $_admin || array_key_exists($mod, $_mods);

            // ¿Tiene acceso a un submodulo específico dentro de un módulo?
            $_canSub = function ($mod, $sub) use ($_admin, $_mods) {
                if ($_admin)
                    return true;
                if (!array_key_exists($mod, $_mods))
                    return false;
                $subs = $_mods[$mod] ?? [];
                return empty($subs) || in_array($sub, $subs);
            };
        @endphp

        <aside :class="[
                'flex flex-col bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 transition-all duration-300 ease-in-out z-30 flex-shrink-0',
                sidebarOpen ? 'w-64' : 'sidebar-collapsed w-16',
                mobileSidebarOpen ? 'fixed inset-y-0 left-0 !w-64 shadow-2xl' : 'hidden lg:flex'
            ]">
            {{-- Logo Corporativo --}}
            <div
                class="flex items-center h-16 px-4 border-b border-gray-100 dark:border-slate-700 flex-shrink-0 sidebar-logo bg-gray-50/50 dark:bg-slate-800/80">
                <div class="flex items-center gap-2 overflow-hidden">
                    <div
                        class="flex-shrink-0 w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center shadow-lg shadow-red-200">
                        <i class="ri-tv-2-fill text-white text-lg"></i>
                    </div>
                    <span
                        class="hide-collapsed font-black text-gray-900 dark:text-slate-100 text-sm uppercase tracking-tighter whitespace-nowrap">
                        Tu Visión <span class="text-red-600">Telecable</span>
                    </span>
                </div>
            </div>

            {{-- Navegación --}}
            <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-2 space-y-1">

                {{-- DASHBOARD --}}
                <a href="{{ route('dashboard') }}"
                    class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all
                          {{ request()->routeIs('dashboard') ? 'nav-active' : 'text-gray-500 hover:bg-gray-100 hover:text-indigo-600' }}">
                    <i class="ri-dashboard-3-line text-lg flex-shrink-0"></i>
                    <span class="hide-collapsed">Centro de Operaciones</span>
                </a>

                <div class="my-3 border-t border-gray-100 dark:border-slate-700 mx-2"></div>

                {{-- ═════════════════════════════════════════════════════════════
                ═ CATÁLOGOS PARA CONFIGURACIÓN
                ═════════════════════════════════════════════════════════════ --}}
                {{-- ═════════════════════════════════════════════════════════════
                ═ CATÁLOGOS DE CONFIGURACIÓN (wrapper único)
                ═════════════════════════════════════════════════════════════ --}}
                @php
                    $adminTvtActive = request()->routeIs([
                        'rrhh.*',
                        'infraestructura.*',
                        'energia.ctc',
                        'plan.*',
                        'financiero.*',
                        'cat.servicios.*',
                        'cat.actividades.*',
                    ]);
                    $clientesGestActive = $clientesGestActive ?? request()->routeIs('clientes.*');
                    $redesGestActive = $redesGestActive ?? request()->routeIs(['tv.*', 'red.*', 'energia.ups', 'planta.*', 'energia.fibra']);
                    $catalogsActive = $adminTvtActive || $clientesGestActive || $redesGestActive || $regulatorioActive;
                @endphp
                <div x-data="{ open: {{ $catalogsActive ? 'true' : 'false' }} }"
                    @close-nav-top.window="if ($event.detail !== 'catalogos') open = false">
                    <button @click="open = !open; if (open) $dispatch('close-nav-top', 'catalogos')"
                        class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $catalogsActive ? 'bg-slate-100 text-slate-700' : 'text-gray-600 hover:bg-slate-100 hover:text-slate-700' }}">
                        <i
                            class="ri-settings-4-line text-lg {{ $catalogsActive ? 'text-slate-600' : 'text-slate-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Catálogos de
                            Configuración</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200"
                            :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-1 ml-2 pb-1">

                        {{-- 1. ADMINISTRATIVA DE TVT --}}
                        @if($_canMod('RRHH') || $_canMod('Infraestructura') || $_canMod('Financiero') || $_canMod('Servicios'))
                            <div x-data="{ open: {{ $adminTvtActive ? 'true' : 'false' }} }"
                                @close-nav-cat.window="if ($event.detail !== 'adminTvt') open = false">
                                <button @click="open = !open; if (open) $dispatch('close-nav-cat', 'adminTvt')"
                                    class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                           {{ $adminTvtActive ? 'bg-slate-100 text-slate-700' : 'text-gray-600 hover:bg-slate-100 hover:text-slate-700' }}">
                                    <i
                                        class="ri-settings-3-line text-lg {{ $adminTvtActive ? 'text-slate-600' : 'text-slate-400' }}"></i>
                                    <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Administrativa
                                        de TVT</span>
                                    <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200"
                                        :class="open && 'rotate-180'"></i>
                                </button>
                                <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-slate-200 pb-1">

                                    {{-- Recursos Humanos --}}
                                    @if($_canMod('RRHH'))
                                        <div x-data="{ open: {{ $rrhhActive ? 'true' : 'false' }} }"
                                            @close-nav-admin.window="if ($event.detail !== 'rrhh') open = false">
                                            <button @click="open = !open; if (open) $dispatch('close-nav-admin', 'rrhh')"
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-blue-600 hover:bg-blue-50/50 rounded-r-lg transition-colors">
                                                <i class="ri-team-line text-sm opacity-70"></i>
                                                <span>Recursos Humanos</span>
                                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                    :class="open && 'rotate-180'"></i>
                                            </button>
                                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                                @if($_canSub('RRHH', 'rrhh.empleados'))
                                                    <a href="{{ route('rrhh.empleados') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 rounded transition-colors">
                                                        <i class="ri-user-add-line text-xs opacity-70"></i>
                                                        <span>Empleados</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('RRHH', 'rrhh.vacaciones'))
                                                    <a href="{{ route('rrhh.vacaciones') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 rounded transition-colors">
                                                        <i class="ri-sun-line text-xs opacity-70"></i>
                                                        <span>Vacaciones</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('RRHH', 'rrhh.descanso'))
                                                    <a href="{{ route('rrhh.descanso') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 rounded transition-colors">
                                                        <i class="ri-calendar-event-line text-xs opacity-70"></i>
                                                        <span>Descansos Mensuales</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('RRHH', 'rrhh.permisos'))
                                                    <a href="{{ route('rrhh.permisos') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 rounded transition-colors">
                                                        <i class="ri-pass-valid-line text-xs opacity-70"></i>
                                                        <span>Permisos</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('RRHH', 'rrhh.accesos'))
                                                    <a href="{{ route('rrhh.accesos') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 rounded transition-colors">
                                                        <i class="ri-key-2-line text-xs opacity-70"></i>
                                                        <span>Accesos Sistema</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Sucursales (Infraestructura) --}}
                                    @if($_canMod('Infraestructura'))
                                        <div x-data="{ open: {{ $infraActive ? 'true' : 'false' }} }"
                                            @close-nav-admin.window="if ($event.detail !== 'infra') open = false">
                                            <button @click="open = !open; if (open) $dispatch('close-nav-admin', 'infra')"
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-pink-600 hover:bg-pink-50/50 rounded-r-lg transition-colors">
                                                <i class="ri-map-pin-range-line text-sm opacity-70"></i>
                                                <span>Sucursales</span>
                                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                    :class="open && 'rotate-180'"></i>
                                            </button>
                                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                                @if($_canSub('Infraestructura', 'infraestructura.geografia'))
                                                    <a href="{{ route('infraestructura.geografia') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-pink-600 hover:bg-pink-50/50 rounded transition-colors">
                                                        <i class="ri-map-2-line text-xs opacity-70"></i>
                                                        <span>Geografía (INEGI)</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Infraestructura', 'infraestructura.sucursales'))
                                                    <a href="{{ route('infraestructura.sucursales') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-pink-600 hover:bg-pink-50/50 rounded transition-colors">
                                                        <i class="ri-store-3-line text-xs opacity-70"></i>
                                                        <span>Sucursales</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Infraestructura', 'infraestructura.calles'))
                                                    <a href="{{ route('infraestructura.calles') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-pink-600 hover:bg-pink-50/50 rounded transition-colors">
                                                        <i class="ri-road-map-line text-xs opacity-70"></i>
                                                        <span>Calles</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Infraestructura', 'infraestructura.postes'))
                                                    <a href="{{ route('infraestructura.postes') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-pink-600 hover:bg-pink-50/50 rounded transition-colors">
                                                        <i class="ri-pushpin-2-line text-xs opacity-70"></i>
                                                        <span>Postes</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Infraestructura', 'infraestructura.ctc'))
                                                    <a href="{{ route('energia.ctc') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-pink-600 hover:bg-pink-50/50 rounded transition-colors">
                                                        <i class="ri-building-4-line text-xs opacity-70"></i>
                                                        <span>CTC</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Plan de Trabajo --}}
                                    @if($_canMod('PlanTrabajo'))
                                        <div x-data="{ open: {{ $planActive ? 'true' : 'false' }} }"
                                            @close-nav-admin.window="if ($event.detail !== 'plan') open = false">
                                            <button @click="open = !open; if (open) $dispatch('close-nav-admin', 'plan')"
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-violet-600 hover:bg-violet-50/50 rounded-r-lg transition-colors">
                                                <i class="ri-task-line text-sm opacity-70"></i>
                                                <span>Plan de Trabajo</span>
                                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                    :class="open && 'rotate-180'"></i>
                                            </button>
                                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                                @if($_canSub('PlanTrabajo', 'plan.actividades'))
                                                    <a href="{{ route('plan.actividades') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-violet-600 hover:bg-violet-50/50 rounded transition-colors">
                                                        <i class="ri-list-check-3 text-xs opacity-70"></i>
                                                        <span>Actividades</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('PlanTrabajo', 'plan.trabajo'))
                                                    <a href="{{ route('plan.trabajo') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-violet-600 hover:bg-violet-50/50 rounded transition-colors">
                                                        <i class="ri-calendar-schedule-line text-xs opacity-70"></i>
                                                        <span>Asignación Plan</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Cuentas por Pagar --}}
                                    @if($_canMod('Financiero'))
                                        @php $cxpActive = request()->routeIs('financiero.proveedores', 'financiero.bancos', 'financiero.facturas'); @endphp
                                        <div x-data="{ open: {{ $cxpActive ? 'true' : 'false' }} }"
                                            @close-nav-admin.window="if ($event.detail !== 'cxp') open = false">
                                            <button @click="open = !open; if (open) $dispatch('close-nav-admin', 'cxp')"
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-emerald-600 hover:bg-emerald-50/50 rounded-r-lg transition-colors">
                                                <i class="ri-bill-line text-sm opacity-70"></i>
                                                <span>Cuentas por Pagar</span>
                                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                    :class="open && 'rotate-180'"></i>
                                            </button>
                                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                                @if($_canSub('Financiero', 'financiero.proveedores'))
                                                    <a href="{{ route('financiero.proveedores') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                                        <i class="ri-truck-line text-xs opacity-70"></i>
                                                        <span>Proveedores</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Financiero', 'financiero.bancos'))
                                                    <a href="{{ route('financiero.bancos') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                                        <i class="ri-bank-line text-xs opacity-70"></i>
                                                        <span>Bancos</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Financiero', 'financiero.facturas'))
                                                    <a href="{{ route('financiero.facturas') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                                        <i class="ri-file-text-line text-xs opacity-70"></i>
                                                        <span>Facturas</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Ingresos / Egresos --}}
                                        @php $ingEgActive = request()->routeIs('financiero.ingresos.egresos', 'financiero.motivos-traspaso'); @endphp
                                        <div x-data="{ open: {{ $ingEgActive ? 'true' : 'false' }} }"
                                            @close-nav-admin.window="if ($event.detail !== 'ingEg') open = false">
                                            <button @click="open = !open; if (open) $dispatch('close-nav-admin', 'ingEg')"
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-emerald-600 hover:bg-emerald-50/50 rounded-r-lg transition-colors">
                                                <i class="ri-arrow-up-down-line text-sm opacity-70"></i>
                                                <span>Ingresos / Egresos</span>
                                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                    :class="open && 'rotate-180'"></i>
                                            </button>
                                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                                @if($_canSub('Financiero', 'financiero.ingresos.egresos'))
                                                    <a href="{{ route('financiero.ingresos.egresos') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                                        <i class="ri-arrow-up-down-line text-xs opacity-70"></i>
                                                        <span>Tipos de Ingresos/Egresos</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Financiero', 'financiero.motivos-traspaso'))
                                                    <a href="{{ route('financiero.motivos-traspaso') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                                        <i class="ri-shuffle-line text-xs opacity-70"></i>
                                                        <span>Motivos de Traspaso</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Tarifas y Promociones --}}
                                        @php $tarifasActive = request()->routeIs('financiero.tarifas.*', 'financiero.promociones', 'financiero.descuentos'); @endphp
                                        <div x-data="{ open: {{ $tarifasActive ? 'true' : 'false' }} }"
                                            @close-nav-admin.window="if ($event.detail !== 'tarifas') open = false">
                                            <button @click="open = !open; if (open) $dispatch('close-nav-admin', 'tarifas')"
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-emerald-600 hover:bg-emerald-50/50 rounded-r-lg transition-colors">
                                                <i class="ri-price-tag-3-line text-sm opacity-70"></i>
                                                <span>Tarifas y Promociones</span>
                                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                    :class="open && 'rotate-180'"></i>
                                            </button>
                                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                                @if($_canSub('Financiero', 'financiero.tarifas.principales'))
                                                    <a href="{{ route('financiero.tarifas.principales') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                                        <i class="ri-price-tag-3-line text-xs opacity-70"></i>
                                                        <span>Tarifas Principales</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Financiero', 'financiero.tarifas.adicionales'))
                                                    <a href="{{ route('financiero.tarifas.adicionales') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                                        <i class="ri-coins-line text-xs opacity-70"></i>
                                                        <span>Tarifas Adicionales</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Financiero', 'financiero.promociones'))
                                                    <a href="{{ route('financiero.promociones') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                                        <i class="ri-discount-percent-line text-xs opacity-70"></i>
                                                        <span>Promociones Estacionales</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Financiero', 'financiero.descuentos'))
                                                    <a href="{{ route('financiero.descuentos') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                                        <i class="ri-coupon-3-line text-xs opacity-70"></i>
                                                        <span>Descuentos</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Servicios --}}
                                    @if($_canMod('Servicios'))
                                        <div x-data="{ open: {{ $svcActive ? 'true' : 'false' }} }"
                                            @close-nav-admin.window="if ($event.detail !== 'svc') open = false">
                                            <button @click="open = !open; if (open) $dispatch('close-nav-admin', 'svc')"
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 rounded-r-lg transition-colors">
                                                <i class="ri-settings-5-line text-sm opacity-70"></i>
                                                <span>Servicios</span>
                                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                    :class="open && 'rotate-180'"></i>
                                            </button>
                                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                                @if($_canSub('Servicios', 'cat.servicios.tarifas-principales'))
                                                    <a href="{{ route('cat.servicios.tarifas-principales') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50 rounded transition-colors">
                                                        <i class="ri-service-line text-xs opacity-70"></i>
                                                        <span>Servicios Tar. Principales</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Servicios', 'cat.servicios.tarifas-adicionales'))
                                                    <a href="{{ route('cat.servicios.tarifas-adicionales') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50 rounded transition-colors">
                                                        <i class="ri-service-line text-xs opacity-70"></i>
                                                        <span>Servicios Tar. Adicionales</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Servicios', 'cat.servicios.fallas'))
                                                    <a href="{{ route('cat.servicios.fallas') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50 rounded transition-colors">
                                                        <i class="ri-tools-line text-xs opacity-70"></i>
                                                        <span>Servicios de Fallas</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Servicios', 'cat.servicios.personal'))
                                                    <a href="{{ route('cat.servicios.personal') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50 rounded transition-colors">
                                                        <i class="ri-user-settings-line text-xs opacity-70"></i>
                                                        <span>Servicios al Personal</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Servicios', 'cat.servicios.clientes'))
                                                    <a href="{{ route('cat.servicios.clientes') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50 rounded transition-colors">
                                                        <i class="ri-customer-service-line text-xs opacity-70"></i>
                                                        <span>Servicios al Cliente</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Actividades (Reportes de Servicio) --}}
                                        <div x-data="{ open: {{ $actActive ? 'true' : 'false' }} }"
                                            @close-nav-admin.window="if ($event.detail !== 'act') open = false">
                                            <button @click="open = !open; if (open) $dispatch('close-nav-admin', 'act')"
                                                class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 rounded-r-lg transition-colors">
                                                <i class="ri-task-line text-sm opacity-70"></i>
                                                <span>Actividades</span>
                                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                    :class="open && 'rotate-180'"></i>
                                            </button>
                                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                                @if($_canSub('Servicios', 'cat.actividades.tarifas-principales'))
                                                    <a href="{{ route('cat.actividades.tarifas-principales') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50 rounded transition-colors">
                                                        <i class="ri-list-check text-xs opacity-70"></i>
                                                        <span>Activ. Tar. Principales</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Servicios', 'cat.actividades.tarifas-adicionales'))
                                                    <a href="{{ route('cat.actividades.tarifas-adicionales') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50 rounded transition-colors">
                                                        <i class="ri-list-check text-xs opacity-70"></i>
                                                        <span>Activ. Tar. Adicionales</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Servicios', 'cat.actividades.fallas'))
                                                    <a href="{{ route('cat.actividades.fallas') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50 rounded transition-colors">
                                                        <i class="ri-tools-line text-xs opacity-70"></i>
                                                        <span>Activ. de Fallas</span>
                                                    </a>
                                                @endif
                                                @if($_canSub('Servicios', 'cat.actividades.personal'))
                                                    <a href="{{ route('cat.actividades.personal') }}"
                                                        class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50 rounded transition-colors">
                                                        <i class="ri-user-settings-line text-xs opacity-70"></i>
                                                        <span>Activ. al Personal</span>
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endif

                        {{-- 2. GESTIÓN AL CLIENTE (Catálogos) --}}
                        @php $clientesGestActive = request()->routeIs('clientes.*'); @endphp
                        @if($_canMod('Clientes'))
                            <div x-data="{ open: {{ $clientesGestActive ? 'true' : 'false' }} }"
                                @close-nav-cat.window="if ($event.detail !== 'clientes') open = false">
                                <button @click="open = !open; if (open) $dispatch('close-nav-cat', 'clientes')"
                                    class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                           {{ $clientesGestActive ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-teal-50 hover:text-teal-700' }}">
                                    <i
                                        class="ri-user-star-line text-lg {{ $clientesGestActive ? 'text-teal-600' : 'text-teal-400' }}"></i>
                                    <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Gestión
                                        Clientes</span>
                                    <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200"
                                        :class="open && 'rotate-180'"></i>
                                </button>
                                <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-teal-100 pb-1">
                                    @if($_canSub('Clientes', 'clientes.registro'))
                                        <a href="{{ route('clientes.registro') }}"
                                            class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('clientes.registro') ? 'text-teal-700 bg-teal-50' : 'text-gray-500 hover:text-teal-600 hover:bg-teal-50/50' }}">
                                            <i class="ri-user-add-line text-sm opacity-70"></i>
                                            <span>Registro de Clientes</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- 3. GESTIÓN DE REDES --}}
                        @php
                            $redesGestActive = request()->routeIs([
                                'tv.*',
                                'red.*',
                                'energia.ups',
                                'planta.*',
                                'energia.fibra',
                            ]);
                        @endphp
                        <div x-data="{ open: {{ $redesGestActive ? 'true' : 'false' }} }"
                            @close-nav-cat.window="if ($event.detail !== 'redesGest') open = false">
                            <button @click="open = !open; if (open) $dispatch('close-nav-cat', 'redesGest')"
                                class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $redesGestActive ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                                <i
                                    class="ri-router-line text-lg {{ $redesGestActive ? 'text-blue-600' : 'text-blue-400' }}"></i>
                                <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Gestión de
                                    Redes</span>
                                <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200"
                                    :class="open && 'rotate-180'"></i>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-blue-100 pb-1">

                                {{-- Televisión --}}
                                @if($_canMod('Television'))
                                    <div x-data="{ open: {{ $tvActive ? 'true' : 'false' }} }"
                                        @close-nav-redes.window="if ($event.detail !== 'tv') open = false">
                                        <button @click="open = !open; if (open) $dispatch('close-nav-redes', 'tv')"
                                            class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-orange-600 hover:bg-orange-50/50 rounded-r-lg transition-colors">
                                            <i class="ri-broadcast-line text-sm opacity-70"></i>
                                            <span>Servicios Televisión</span>
                                            <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                :class="open && 'rotate-180'"></i>
                                        </button>
                                        <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                            @if($_canSub('Television', 'tv.mininodos'))
                                                <a href="{{ route('tv.mininodos') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-radar-line text-xs opacity-70"></i>
                                                    <span>Mininodos</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.antenas'))
                                                <a href="{{ route('tv.antenas') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-wifi-line text-xs opacity-70"></i>
                                                    <span>Antenas</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.satelites'))
                                                <a href="{{ route('tv.satelites') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-planet-line text-xs opacity-70"></i>
                                                    <span>Satélites</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.proveedores-senal'))
                                                <a href="{{ route('tv.proveedores-senal') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-broadcast-line text-xs opacity-70"></i>
                                                    <span>Proveedores de Señal</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.receptores'))
                                                <a href="{{ route('tv.receptores') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-tv-2-line text-xs opacity-70"></i>
                                                    <span>Receptores</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.canales'))
                                                <a href="{{ route('tv.canales') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-satellite-line text-xs opacity-70"></i>
                                                    <span>Canales</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.moduladores'))
                                                <a href="{{ route('tv.moduladores') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-equalizer-line text-xs opacity-70"></i>
                                                    <span>Moduladores Análogos</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.moduladores-digitales'))
                                                <a href="{{ route('tv.moduladores-digitales') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-equalizer-2-line text-xs opacity-70"></i>
                                                    <span>Moduladores Digitales</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.transmisores'))
                                                <a href="{{ route('tv.transmisores') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-broadcast-line text-xs opacity-70"></i>
                                                    <span>Transmisores 1310/1550/EDFA</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.pon-edfa'))
                                                <a href="{{ route('tv.pon-edfa') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-rfid-line text-xs opacity-70"></i>
                                                    <span>PON EDFA</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.divisores'))
                                                <a href="{{ route('tv.divisores') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-git-branch-line text-xs opacity-70"></i>
                                                    <span>Divisores de Señal</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.amplificadores'))
                                                <a href="{{ route('planta.amplificadores') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-pulse-line text-xs opacity-70"></i>
                                                    <span>Amplificadores RF</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Television', 'tv.nodos-opticos'))
                                                <a href="{{ route('planta.nodos-opticos') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                                    <i class="ri-share-line text-xs opacity-70"></i>
                                                    <span>Nodos Ópticos</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Internet --}}
                                @if($_canMod('Red'))
                                    <div x-data="{ open: {{ $redActive ? 'true' : 'false' }} }"
                                        @close-nav-redes.window="if ($event.detail !== 'red') open = false">
                                        <button @click="open = !open; if (open) $dispatch('close-nav-redes', 'red')"
                                            class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-purple-600 hover:bg-purple-50/50 rounded-r-lg transition-colors">
                                            <i class="ri-router-line text-sm opacity-70"></i>
                                            <span>Servicios Internet</span>
                                            <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                :class="open && 'rotate-180'"></i>
                                        </button>
                                        <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                            @if($_canSub('Red', 'red.onus'))
                                                <a href="{{ route('red.onus') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                                    <i class="ri-modem-line text-xs opacity-70"></i>
                                                    <span>ONUs</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Red', 'red.olt'))
                                                <a href="{{ route('red.olt') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                                    <i class="ri-server-line text-xs opacity-70"></i>
                                                    <span>OLT Externa</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Red', 'red.olt-interna'))
                                                <a href="{{ route('red.olt-interna') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                                    <i class="ri-server-2-line text-xs opacity-70"></i>
                                                    <span>OLT Interna</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Red', 'red.starlinks'))
                                                <a href="{{ route('red.starlinks') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                                    <i class="ri-base-station-line text-xs opacity-70"></i>
                                                    <span>Starlinks</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Red', 'red.isp-telmex'))
                                                <a href="{{ route('red.isp-telmex') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                                    <i class="ri-global-line text-xs opacity-70"></i>
                                                    <span>ISP Telmex</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Red', 'red.ccr'))
                                                <a href="{{ route('red.ccr') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                                    <i class="ri-exchange-box-line text-xs opacity-70"></i>
                                                    <span>CCR 0</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Red', 'red.ccr1'))
                                                <a href="{{ route('red.ccr1') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                                    <i class="ri-exchange-box-line text-xs opacity-70"></i>
                                                    <span>CCR 1</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Red', 'red.switches'))
                                                <a href="{{ route('red.switches') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                                    <i class="ri-switch-line text-xs opacity-70"></i>
                                                    <span>Switches</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Red', 'red.vlans'))
                                                <a href="{{ route('red.vlans') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                                    <i class="ri-git-merge-line text-xs opacity-70"></i>
                                                    <span>Winbox / VLANs</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Red', 'red.encapsulamiento'))
                                                <a href="{{ route('red.encapsulamiento') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                                    <i class="ri-stack-line text-xs opacity-70"></i>
                                                    <span>Encapsulamiento</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Energía --}}
                                @if($_canMod('Energia'))
                                    <div x-data="{ open: {{ ($upsActive || $fibraActive || $ctcActive) ? 'true' : 'false' }} }"
                                        @close-nav-redes.window="if ($event.detail !== 'energia') open = false">
                                        <button @click="open = !open; if (open) $dispatch('close-nav-redes', 'energia')"
                                            class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-yellow-600 hover:bg-yellow-50/50 rounded-r-lg transition-colors">
                                            <i class="ri-flashlight-line text-sm opacity-70"></i>
                                            <span>Servicios Energía</span>
                                            <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                :class="open && 'rotate-180'"></i>
                                        </button>
                                        <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                            @if($_canSub('Energia', 'energia.ups'))
                                                <a href="{{ route('energia.ups') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-yellow-600 hover:bg-yellow-50/50 rounded transition-colors">
                                                    <i class="ri-battery-2-charge-line text-xs opacity-70"></i>
                                                    <span>UPS</span>
                                                </a>
                                            @endif
                                            @if($_canSub('Energia', 'energia.plantas'))
                                                <a href="{{ route('energia.plantas') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-yellow-600 hover:bg-yellow-50/50 rounded transition-colors">
                                                    <i class="ri-flashlight-line text-xs opacity-70"></i>
                                                    <span>Plantas de Emergencia</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                {{-- Planta Externa --}}
                                @if($_canMod('PlantaExterna'))
                                    <div x-data="{ open: {{ $plantaActive ? 'true' : 'false' }} }"
                                        @close-nav-redes.window="if ($event.detail !== 'planta') open = false">
                                        <button @click="open = !open; if (open) $dispatch('close-nav-redes', 'planta')"
                                            class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-slate-600 hover:bg-slate-100/50 rounded-r-lg transition-colors">
                                            <i class="ri-node-tree text-sm opacity-70"></i>
                                            <span>Planta Externa</span>
                                            <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform"
                                                :class="open && 'rotate-180'"></i>
                                        </button>
                                        <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                            @if($_canSub('PlantaExterna', 'planta.naps'))
                                                <a href="{{ route('red.naps') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-slate-600 hover:bg-slate-100/50 rounded transition-colors">
                                                    <i class="ri-node-tree text-xs opacity-70"></i>
                                                    <span>NAPs</span>
                                                </a>
                                            @endif
                                            @if($_canSub('PlantaExterna', 'planta.dfo'))
                                                <a href="{{ route('planta.dfo') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-slate-600 hover:bg-slate-100/50 rounded transition-colors">
                                                    <i class="ri-git-fork-line text-xs opacity-70"></i>
                                                    <span>DFO</span>
                                                </a>
                                            @endif
                                            @if($_canSub('PlantaExterna', 'planta.tipo-fibra'))
                                                <a href="{{ route('planta.tipo-fibra') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-slate-600 hover:bg-slate-100/50 rounded transition-colors">
                                                    <i class="ri-code-s-slash-line text-xs opacity-70"></i>
                                                    <span>Tipos de Fibra</span>
                                                </a>
                                            @endif
                                            @if($_canSub('PlantaExterna', 'planta.enlaces'))
                                                <a href="{{ route('energia.fibra') }}"
                                                    class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-slate-600 hover:bg-slate-100/50 rounded transition-colors">
                                                    <i class="ri-links-line text-xs opacity-70"></i>
                                                    <span>Enlaces</span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>

                        {{-- ═════════════════════════════════════════════════════════════
                        ═ CATÁLOGOS REGULATORIOS
                        ═════════════════════════════════════════════════════════════ --}}
                        @if($_canMod('Regulatorio'))
                            <div x-data="{ open: {{ $regulatorioActive ? 'true' : 'false' }} }"
                                @close-nav-cat.window="if ($event.detail !== 'regulatorio') open = false">
                                <button @click="open = !open; if (open) $dispatch('close-nav-cat', 'regulatorio')"
                                    class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                           {{ $regulatorioActive ? 'bg-rose-50 text-rose-700' : 'text-gray-600 hover:bg-rose-50 hover:text-rose-700' }}">
                                    <i
                                        class="ri-government-line text-lg {{ $regulatorioActive ? 'text-rose-600' : 'text-rose-400' }}"></i>
                                    <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Catálogos
                                        Regulatorios</span>
                                    <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200"
                                        :class="open && 'rotate-180'"></i>
                                </button>
                                <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-rose-100 pb-1">
                                    @if($_canSub('Regulatorio', 'regulatorio.entidades'))
                                        <a href="{{ route('regulatorio.entidades') }}"
                                            class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-500 hover:text-rose-600 hover:bg-rose-50/50 rounded-r-lg transition-colors">
                                            <i class="ri-building-line text-sm opacity-70"></i>
                                            <span>Entidades Regulatorias</span>
                                        </a>
                                    @endif
                                    @if($_canSub('Regulatorio', 'regulatorio.documentos'))
                                        <a href="{{ route('regulatorio.documentos') }}"
                                            class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-500 hover:text-rose-600 hover:bg-rose-50/50 rounded-r-lg transition-colors">
                                            <i class="ri-file-paper-2-line text-sm opacity-70"></i>
                                            <span>Documentos</span>
                                        </a>
                                    @endif
                                    @if($_canSub('Regulatorio', 'regulatorio.obligaciones'))
                                        <a href="{{ route('regulatorio.obligaciones') }}"
                                            class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-500 hover:text-rose-600 hover:bg-rose-50/50 rounded-r-lg transition-colors">
                                            <i class="ri-send-plane-line text-sm opacity-70"></i>
                                            <span>Envío de Obligaciones</span>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif

                    </div>{{-- /catalogs-inner --}}
                </div>{{-- /catalogs-wrapper --}}

                <div class="my-2 border-t border-gray-100 dark:border-slate-700 mx-2"></div>

                {{-- ═════════════════════════════════════════════════════════════
                ═ MODELOS DE OPERACIÓN
                ═════════════════════════════════════════════════════════════ --}}
                <p
                    class="hide-collapsed section-label px-3 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5">
                    Modelos de Operación</p>

                {{-- ── GESTIÓN AL CLIENTE (OPERACIONALES) ── --}}
                @if($_canMod('GestionClientes'))
                    <div x-data="{ open: {{ $gcActive ? 'true' : 'false' }} }"
                        @close-nav-top.window="if ($event.detail !== 'gc') open = false">
                        <button @click="open = !open; if (open) $dispatch('close-nav-top', 'gc')"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                           {{ $gcActive ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-red-50 hover:text-red-700' }}">
                            <i
                                class="ri-customer-service-2-line text-lg {{ $gcActive ? 'text-red-600' : 'text-red-400' }}"></i>
                            <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Gestión al
                                Cliente</span>
                            <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200"
                                :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-red-100 pb-1">
                            @if($_canSub('GestionClientes', 'contrataciones-nuevas'))
                                <a href="{{ route('contrataciones.nuevas') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('contrataciones.nuevas') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-file-add-line text-sm opacity-70"></i>
                                    <span>Contratos Nuevos</span>
                                </a>
                            @endif
                            @if($_canSub('GestionClientes', 'servicios-adicionales'))
                                <a href="{{ route('servicios.adicionales') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('servicios.adicionales') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-add-box-line text-sm opacity-70"></i>
                                    <span>Servicios Adicionales</span>
                                </a>
                            @endif
                            @if($_canSub('GestionClientes', 'contratacion-promocion'))
                                <a href="{{ route('contratacion.promocion') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('contratacion.promocion') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-discount-percent-line text-sm opacity-70"></i>
                                    <span>Pago en Promoción</span>
                                </a>
                            @endif
                            @if($_canSub('GestionClientes', 'cambio-servicio'))
                                <a href="{{ route('cambio.servicio') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('cambio.servicio') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-exchange-line text-sm opacity-70"></i>
                                    <span>Cambio de Servicio</span>
                                </a>
                            @endif
                            @if($_canSub('GestionClientes', 'pago-mensualidad'))
                                <a href="{{ route('pago.mensualidad') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('pago.mensualidad') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-calendar-check-line text-sm opacity-70"></i>
                                    <span>Pago de Mensualidad</span>
                                </a>
                            @endif
                            @if($_canSub('GestionClientes', 'estado-cuenta'))
                                <a href="{{ route('estado.cuenta') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('estado.cuenta') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-file-list-3-line text-sm opacity-70"></i>
                                    <span>Estado de Cuenta</span>
                                </a>
                            @endif
                            @if($_canSub('GestionClientes', 'suspension-falta-pago'))
                                <a href="{{ route('suspension.clientes') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('suspension.clientes') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-user-unfollow-line text-sm opacity-70"></i>
                                    <span>Suspensión Falta Pago</span>
                                </a>
                            @endif
                            @if($_canSub('GestionClientes', 'reconexion-cliente'))
                                <a href="{{ route('reconexion.cliente') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('reconexion.cliente') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-plug-line text-sm opacity-70"></i>
                                    <span>Reconexión de Cliente</span>
                                </a>
                            @endif
                            @if($_canSub('GestionClientes', 'cancelacion-servicio'))
                                <a href="{{ route('cancelacion.servicio') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('cancelacion.servicio') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-close-circle-line text-sm opacity-70"></i>
                                    <span>Cancelación de Servicio</span>
                                </a>
                            @endif
                            @if($_canSub('GestionClientes', 'recuperacion-equipos'))
                                <a href="{{ route('recuperacion.equipos') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('recuperacion.equipos') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-router-line text-sm opacity-70"></i>
                                    <span>Recuperación de Equipos</span>
                                </a>
                            @endif
                            @if($_canSub('GestionClientes', 'reportes-servicio'))
                                <a href="{{ route('reportes.servicio') }}"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                                  {{ request()->routeIs('reportes.servicio', 'reportes.atender') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                                    <i class="ri-inbox-archive-line text-sm opacity-70"></i>
                                    <span>Bandeja de Reportes</span>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif

            </nav>

            {{-- Footer Usuario --}}
            <div class="flex-shrink-0 border-t border-gray-100 p-3 bg-gray-50/50">
                <div class="flex items-center gap-3">
                    <div
                        class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black text-xs shadow-md shadow-indigo-100">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="hide-collapsed overflow-hidden flex-1 min-w-0">
                        <p class="text-[10px] font-black text-gray-900 uppercase truncate leading-none">
                            {{ Auth::user()->name }}
                        </p>
                        <p class="text-[9px] font-bold text-gray-400 truncate mt-1 italic uppercase tracking-tighter">
                            ADMINISTRADOR</p>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ================================================================
        CONTENIDO PRINCIPAL
        ================================================================ --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden relative bg-gray-50 dark:bg-slate-950">

            {{-- TOP BAR (MEJORADO) --}}
            <header class="flex flex-col bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] flex-shrink-0 z-20">
                
                {{-- LÍNEA 1: Controles + Búsqueda + Info --}}
                <div class="flex items-center h-16 px-4 md:px-6 gap-2 md:gap-4">
                    {{-- ZONA 1: Togles del sidebar (Izquierda) --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        {{-- Toggle mobile --}}
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                            class="lg:hidden p-2.5 text-gray-500 dark:text-slate-400 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 shadow-sm hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 dark:hover:text-indigo-400 dark:hover:bg-slate-600 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            <i class="ri-menu-3-line text-lg leading-none"></i>
                        </button>

                        {{-- Toggle desktop --}}
                        <button @click="sidebarOpen = !sidebarOpen"
                            class="hidden lg:flex p-2.5 text-gray-500 dark:text-slate-400 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 shadow-sm hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 dark:hover:text-indigo-400 dark:hover:bg-slate-600 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            <i class="ri-menu-fold-line text-lg leading-none transition-transform duration-300"
                                :class="!sidebarOpen && 'rotate-180'"></i>
                        </button>
                    </div>

                    {{-- ZONA 2: Búsqueda global (Centro - crece) --}}
                    <div class="flex-1 hidden md:flex md:items-center min-w-0">
                        <livewire:layout.search-global />
                    </div>

                    {{-- ZONA 3: Información (Reloj) + Separador --}}
                    <div class="hidden lg:flex items-center gap-4 flex-shrink-0 pl-4 border-l border-gray-200 dark:border-slate-700">
                        <livewire:layout.clock />
                    </div>

                    {{-- ZONA 4: Controles del Sistema (Agrupo oscuro + estado + acciones) --}}
                    <div class="flex items-center gap-2 flex-shrink-0">
                        {{-- Toggle modo oscuro --}}
                        <button @click="toggleDark()" :title="darkMode ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'"
                            class="p-2.5 text-gray-500 dark:text-slate-400 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 shadow-sm hover:text-amber-500 hover:bg-amber-50 hover:border-amber-200 dark:hover:text-amber-400 dark:hover:bg-slate-600 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-amber-100">
                            <i class="text-lg leading-none" :class="darkMode ? 'ri-sun-line' : 'ri-moon-line'"></i>
                        </button>

                        {{-- Estado del sistema --}}
                        <livewire:layout.system-status />

                        {{-- Acciones rápidas --}}
                        <livewire:layout.quick-actions />
                    </div>

                    {{-- ZONA 5: Notificaciones + Usuario (Derecha) --}}
                    <div class="flex items-center gap-2 flex-shrink-0 pl-2 border-l border-gray-200 dark:border-slate-700">
                        {{-- Notificaciones --}}
                        <livewire:layout.notificaciones-top-bar />

                        {{-- Navegación/Usuario --}}
                        <livewire:layout.navigation />
                    </div>
                </div>

                {{-- LÍNEA 2: Breadcrumbs --}}
                <div class="flex items-center h-12 px-4 md:px-6 bg-gray-50/50 dark:bg-slate-900/50 border-t border-gray-100 dark:border-slate-700/50">
                    <livewire:layout.breadcrumbs />
                </div>

            </header>

            {{-- MAIN --}}
            <main class="flex-1 overflow-y-auto p-4 md:p-8 z-10">
                {{ $slot }}
            </main>

            {{-- FOOTER --}}
            <footer
                class="flex-shrink-0 px-6 py-4 bg-transparent border-t border-gray-200/60 dark:border-slate-700/60 text-[11px] text-gray-400 dark:text-slate-500 flex items-center justify-between">
                <span class="font-medium text-gray-500 dark:text-slate-500">TVT Sistema &copy; {{ date('Y') }} — Tu
                    Visión Telecable</span>
                <span class="font-bold tracking-widest text-gray-400 dark:text-slate-600 uppercase">v1.0.0</span>
            </footer>

        </div>
    </div>

</body>

</html>