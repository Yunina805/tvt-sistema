<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TVT Sistema') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet"/>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            [x-cloak] { display: none !important; }

            /* Scrollbar delgado sidebar */
            .sidebar-scroll::-webkit-scrollbar { width: 3px; }
            .sidebar-scroll::-webkit-scrollbar-track { background: transparent; }
            .sidebar-scroll::-webkit-scrollbar-thumb { background: #c7d2fe; border-radius: 4px; }

            /* Link activo */
            .nav-active {
                background: linear-gradient(90deg, #4338ca 0%, #6366f1 100%);
                color: #fff !important;
                box-shadow: 0 2px 8px rgba(99,102,241,.3);
            }
            .nav-active i { color: #fff !important; }

            /* Sidebar colapsado — solo íconos */
            .sidebar-collapsed { width: 64px !important; }
            .sidebar-collapsed .hide-collapsed { display: none !important; }
            .sidebar-collapsed .nav-item { justify-content: center !important; padding-left: 0 !important; padding-right: 0 !important; }
            .sidebar-collapsed .sidebar-logo { justify-content: center !important; }
            .sidebar-collapsed .section-label { display: none !important; }
        </style>
    </head>

    <body class="font-sans antialiased text-gray-900 bg-gray-50">

        {{-- Overlay mobile --}}
        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false"
             class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden transition-opacity duration-300"></div>

        <div class="flex h-screen overflow-hidden">

        {{-- ================================================================
            SIDEBAR
        ================================================================ --}}
        @php
            $gcActive       = request()->routeIs([
                'contrataciones.nuevas', 'servicios.adicionales', 'contratacion.promocion',
                'pago.mensualidad', 'estado.cuenta', 'suspension.clientes',
                'reconexion.cliente', 'cancelacion.servicio', 'recuperacion.equipos',
                'reportes.servicio', 'reportes.atender',
            ]);
            $infraActive    = request()->routeIs('infraestructura.*');
            $rrhhActive     = request()->routeIs('rrhh.*');
            $finActive      = request()->routeIs('financiero.*');
            $clientesActive = request()->routeIs('clientes.*');
            $svcActive      = request()->routeIs('cat.servicios.*');
            $reportesActive = request()->routeIs('cat.actividades.*');
            $plantaActive   = request()->routeIs('planta.*');
            $ctcActive      = request()->routeIs('energia.ctc');
            $tvActive       = request()->routeIs('tv.*');
            $redActive      = request()->routeIs('red.*');
            $upsActive      = request()->routeIs('energia.ups');
            $fibraActive    = request()->routeIs('energia.fibra');

            // ── Control de acceso por módulo ─────────────────────────────────
            $_acc    = auth()->user()?->accesoSistema;
            $_admin  = $_acc?->rol === 'ADMINISTRADOR';
            $_mods   = $_acc?->modulos ?? [];

            // ¿Tiene acceso al módulo completo?
            $_canMod = fn($mod) => $_admin || array_key_exists($mod, $_mods);

            // ¿Tiene acceso a un submodulo específico dentro de un módulo?
            $_canSub = function($mod, $sub) use ($_admin, $_mods) {
                if ($_admin) return true;
                if (!array_key_exists($mod, $_mods)) return false;
                $subs = $_mods[$mod] ?? [];
                return empty($subs) || in_array($sub, $subs);
            };
        @endphp

        <aside
            :class="[
                'flex flex-col bg-white border-r border-gray-200 transition-all duration-300 ease-in-out z-30 flex-shrink-0',
                sidebarOpen ? 'w-64' : 'sidebar-collapsed w-16',
                mobileSidebarOpen ? 'fixed inset-y-0 left-0 !w-64 shadow-2xl' : 'hidden lg:flex'
            ]"
        >
            {{-- Logo Corporativo --}}
            <div class="flex items-center h-16 px-4 border-b border-gray-100 flex-shrink-0 sidebar-logo bg-gray-50/50">
                <div class="flex items-center gap-2 overflow-hidden">
                    <div class="flex-shrink-0 w-8 h-8 bg-red-600 rounded-lg flex items-center justify-center shadow-lg shadow-red-200">
                        <i class="ri-tv-2-fill text-white text-lg"></i>
                    </div>
                    <span class="hide-collapsed font-black text-gray-900 text-sm uppercase tracking-tighter whitespace-nowrap">
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

                <div class="my-3 border-t border-gray-100 mx-2"></div>

                {{-- ═════════════════════════════════════════════════════════════
                    ═ CATÁLOGOS PARA CONFIGURACIÓN
                    ═════════════════════════════════════════════════════════════ --}}
                <p class="hide-collapsed section-label px-3 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5">Catálogos para Configuración</p>

                {{-- 1. ADMINISTRATIVA DE TVT --}}
                @php
                    $adminTvtActive = request()->routeIs([
                        'rrhh.*', 'infraestructura.*', 'financiero.*', 'cat.servicios.*'
                    ]);
                @endphp
                @if($_canMod('RRHH') || $_canMod('Infraestructura') || $_canMod('Financiero') || $_canMod('Servicios'))
                <div x-data="{ open: {{ $adminTvtActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $adminTvtActive ? 'bg-slate-100 text-slate-700' : 'text-gray-600 hover:bg-slate-100 hover:text-slate-700' }}">
                        <i class="ri-settings-3-line text-lg {{ $adminTvtActive ? 'text-slate-600' : 'text-slate-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Administrativa de TVT</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-slate-200 pb-1">

                        {{-- Recursos Humanos --}}
                        @if($_canMod('RRHH'))
                        <div x-data="{ open: {{ $rrhhActive ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-blue-600 hover:bg-blue-50/50 rounded-r-lg transition-colors">
                                <i class="ri-team-line text-sm opacity-70"></i>
                                <span>Recursos Humanos</span>
                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform" :class="open && 'rotate-180'"></i>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                @if($_canSub('RRHH','rrhh.empleados'))
                                <a href="{{ route('rrhh.empleados') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 rounded transition-colors">
                                    <i class="ri-user-add-line text-xs opacity-70"></i>
                                    <span>Empleados</span>
                                </a>
                                @endif
                                @if($_canSub('RRHH','rrhh.vacaciones'))
                                <a href="{{ route('rrhh.vacaciones') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 rounded transition-colors">
                                    <i class="ri-sun-line text-xs opacity-70"></i>
                                    <span>Vacaciones</span>
                                </a>
                                @endif
                                @if($_canSub('RRHH','rrhh.descanso'))
                                <a href="{{ route('rrhh.descanso') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 rounded transition-colors">
                                    <i class="ri-calendar-event-line text-xs opacity-70"></i>
                                    <span>Descansos Mensuales</span>
                                </a>
                                @endif
                                @if($_canSub('RRHH','rrhh.permisos'))
                                <a href="{{ route('rrhh.permisos') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-blue-600 hover:bg-blue-50/50 rounded transition-colors">
                                    <i class="ri-pass-valid-line text-xs opacity-70"></i>
                                    <span>Permisos</span>
                                </a>
                                @endif
                                @if($_canSub('RRHH','rrhh.accesos'))
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
                        <div x-data="{ open: {{ $infraActive ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-pink-600 hover:bg-pink-50/50 rounded-r-lg transition-colors">
                                <i class="ri-map-pin-range-line text-sm opacity-70"></i>
                                <span>Infraestructura</span>
                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform" :class="open && 'rotate-180'"></i>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                @if($_canSub('Infraestructura','infraestructura.geografia'))
                                <a href="{{ route('infraestructura.geografia') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-pink-600 hover:bg-pink-50/50 rounded transition-colors">
                                    <i class="ri-map-2-line text-xs opacity-70"></i>
                                    <span>Geografía (INEGI)</span>
                                </a>
                                @endif
                                @if($_canSub('Infraestructura','infraestructura.sucursales'))
                                <a href="{{ route('infraestructura.sucursales') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-pink-600 hover:bg-pink-50/50 rounded transition-colors">
                                    <i class="ri-store-3-line text-xs opacity-70"></i>
                                    <span>Sucursales</span>
                                </a>
                                @endif
                                @if($_canSub('Infraestructura','infraestructura.calles'))
                                <a href="{{ route('infraestructura.calles') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-pink-600 hover:bg-pink-50/50 rounded transition-colors">
                                    <i class="ri-road-map-line text-xs opacity-70"></i>
                                    <span>Calles</span>
                                </a>
                                @endif
                                @if($_canSub('Infraestructura','infraestructura.postes'))
                                <a href="{{ route('infraestructura.postes') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-pink-600 hover:bg-pink-50/50 rounded transition-colors">
                                    <i class="ri-pushpin-2-line text-xs opacity-70"></i>
                                    <span>Postes</span>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Financieros --}}
                        @if($_canMod('Financiero'))
                        <div x-data="{ open: {{ $finActive ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-emerald-600 hover:bg-emerald-50/50 rounded-r-lg transition-colors">
                                <i class="ri-money-dollar-box-line text-sm opacity-70"></i>
                                <span>Financiera</span>
                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform" :class="open && 'rotate-180'"></i>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                @if($_canSub('Financiero','financiero.tarifas.principales'))
                                <a href="{{ route('financiero.tarifas.principales') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                    <i class="ri-price-tag-3-line text-xs opacity-70"></i>
                                    <span>Tarifas Principales</span>
                                </a>
                                @endif
                                @if($_canSub('Financiero','financiero.tarifas.adicionales'))
                                <a href="{{ route('financiero.tarifas.adicionales') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                    <i class="ri-coins-line text-xs opacity-70"></i>
                                    <span>Tarifas Adicionales</span>
                                </a>
                                @endif
                                @if($_canSub('Financiero','financiero.promociones'))
                                <a href="{{ route('financiero.promociones') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                    <i class="ri-price-tag-3-line text-xs opacity-70"></i>
                                    <span>Promociones</span>
                                </a>
                                @endif
                                @if($_canSub('Financiero','financiero.descuentos'))
                                <a href="{{ route('financiero.descuentos') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                    <i class="ri-coupon-3-line text-xs opacity-70"></i>
                                    <span>Descuentos</span>
                                </a>
                                @endif
                                @if($_canSub('Financiero','financiero.ingresos.egresos'))
                                <a href="{{ route('financiero.ingresos.egresos') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                    <i class="ri-arrow-up-down-line text-xs opacity-70"></i>
                                    <span>Ingresos/Egresos</span>
                                </a>
                                @endif
                                @if($_canSub('Financiero','financiero.proveedores'))
                                <a href="{{ route('financiero.proveedores') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50 rounded transition-colors">
                                    <i class="ri-bank-line text-xs opacity-70"></i>
                                    <span>Proveedores</span>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Servicios --}}
                        @if($_canMod('Servicios'))
                        <a href="{{ route('cat.servicios.tarifas-principales') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-indigo-600 hover:bg-indigo-50/50 rounded-r-lg transition-colors">
                            <i class="ri-settings-5-line text-sm opacity-70"></i>
                            <span>Config. de Servicios</span>
                        </a>
                        @endif

                    </div>
                </div>
                @endif

                {{-- 2. GESTIÓN AL CLIENTE (Catálogos) --}}
                @php $clientesGestActive = request()->routeIs('clientes.*'); @endphp
                @if($_canMod('Clientes'))
                <div x-data="{ open: {{ $clientesGestActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $clientesGestActive ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-teal-50 hover:text-teal-700' }}">
                        <i class="ri-user-star-line text-lg {{ $clientesGestActive ? 'text-teal-600' : 'text-teal-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Gestión Clientes</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-teal-100 pb-1">
                        @if($_canSub('Clientes','clientes.registro'))
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
                        'tv.*', 'red.*', 'energia.ups', 'energia.fibra', 'planta.*'
                    ]);
                @endphp
                <div x-data="{ open: {{ $redesGestActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $redesGestActive ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                        <i class="ri-router-line text-lg {{ $redesGestActive ? 'text-blue-600' : 'text-blue-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Gestión de Redes</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-blue-100 pb-1">

                        {{-- Televisión --}}
                        @if($_canMod('Television'))
                        <div x-data="{ open: {{ $tvActive ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-orange-600 hover:bg-orange-50/50 rounded-r-lg transition-colors">
                                <i class="ri-broadcast-line text-sm opacity-70"></i>
                                <span>Servicios Televisión</span>
                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform" :class="open && 'rotate-180'"></i>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                @if($_canSub('Television','tv.mininodos'))
                                <a href="{{ route('tv.mininodos') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                    <i class="ri-radar-line text-xs opacity-70"></i>
                                    <span>Mininodos/Antenas</span>
                                </a>
                                @endif
                                @if($_canSub('Television','tv.canales'))
                                <a href="{{ route('tv.canales') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                    <i class="ri-satellite-line text-xs opacity-70"></i>
                                    <span>Canales/Satélites</span>
                                </a>
                                @endif
                                @if($_canSub('Television','tv.moduladores'))
                                <a href="{{ route('tv.moduladores') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                    <i class="ri-equalizer-line text-xs opacity-70"></i>
                                    <span>Moduladores</span>
                                </a>
                                @endif
                                @if($_canSub('Television','tv.transmisores'))
                                <a href="{{ route('tv.transmisores') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                    <i class="ri-broadcast-line text-xs opacity-70"></i>
                                    <span>Transmisores/EDFA</span>
                                </a>
                                @endif
                                @if($_canSub('Television','tv.pon-edfa'))
                                <a href="{{ route('tv.pon-edfa') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-orange-600 hover:bg-orange-50/50 rounded transition-colors">
                                    <i class="ri-rfid-line text-xs opacity-70"></i>
                                    <span>PON EDFA</span>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Internet --}}
                        @if($_canMod('Red'))
                        <div x-data="{ open: {{ $redActive ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-purple-600 hover:bg-purple-50/50 rounded-r-lg transition-colors">
                                <i class="ri-router-line text-sm opacity-70"></i>
                                <span>Servicios Internet</span>
                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform" :class="open && 'rotate-180'"></i>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                @if($_canSub('Red','red.onus'))
                                <a href="{{ route('red.onus') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                    <i class="ri-modem-line text-xs opacity-70"></i>
                                    <span>ONUs</span>
                                </a>
                                @endif
                                @if($_canSub('Red','red.naps'))
                                <a href="{{ route('red.naps') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                    <i class="ri-node-tree text-xs opacity-70"></i>
                                    <span>NAPs</span>
                                </a>
                                @endif
                                @if($_canSub('Red','red.olt'))
                                <a href="{{ route('red.olt') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                    <i class="ri-server-line text-xs opacity-70"></i>
                                    <span>OLT (Externa/Interna)</span>
                                </a>
                                @endif
                                @if($_canSub('Red','red.starlinks'))
                                <a href="{{ route('red.starlinks') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                    <i class="ri-base-station-line text-xs opacity-70"></i>
                                    <span>Starlinks/ISP</span>
                                </a>
                                @endif
                                @if($_canSub('Red','red.ccr'))
                                <a href="{{ route('red.ccr') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                    <i class="ri-exchange-box-line text-xs opacity-70"></i>
                                    <span>CCR/Switches</span>
                                </a>
                                @endif
                                @if($_canSub('Red','red.vlans'))
                                <a href="{{ route('red.vlans') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-purple-600 hover:bg-purple-50/50 rounded transition-colors">
                                    <i class="ri-global-line text-xs opacity-70"></i>
                                    <span>Winbox/VLANs</span>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Energía --}}
                        @if($_canMod('Energia'))
                        <div x-data="{ open: {{ ($upsActive || $fibraActive || $ctcActive) ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-yellow-600 hover:bg-yellow-50/50 rounded-r-lg transition-colors">
                                <i class="ri-flashlight-line text-sm opacity-70"></i>
                                <span>Servicios Energía</span>
                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform" :class="open && 'rotate-180'"></i>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                @if($_canSub('Energia','energia.ups'))
                                <a href="{{ route('energia.ups') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-yellow-600 hover:bg-yellow-50/50 rounded transition-colors">
                                    <i class="ri-battery-2-charge-line text-xs opacity-70"></i>
                                    <span>UPS/Emergencia</span>
                                </a>
                                @endif
                                @if($_canSub('Energia','energia.ctc'))
                                <a href="{{ route('energia.ctc') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-yellow-600 hover:bg-yellow-50/50 rounded transition-colors">
                                    <i class="ri-building-2-line text-xs opacity-70"></i>
                                    <span>CTC</span>
                                </a>
                                @endif
                                @if($_canSub('Energia','energia.fibra'))
                                <a href="{{ route('energia.fibra') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-yellow-600 hover:bg-yellow-50/50 rounded transition-colors">
                                    <i class="ri-links-line text-xs opacity-70"></i>
                                    <span>Fibra Óptica</span>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                        {{-- Planta Externa --}}
                        @if($_canMod('PlantaExterna'))
                        <div x-data="{ open: {{ $plantaActive ? 'true' : 'false' }} }">
                            <button @click="open = !open"
                                    class="w-full flex items-center gap-2.5 px-4 py-2 text-xs font-bold text-gray-600 hover:text-slate-600 hover:bg-slate-100/50 rounded-r-lg transition-colors">
                                <i class="ri-node-tree text-sm opacity-70"></i>
                                <span>Planta Externa</span>
                                <i class="ri-arrow-down-s-line text-xs ml-auto transition-transform" :class="open && 'rotate-180'"></i>
                            </button>
                            <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4">
                                @if($_canSub('PlantaExterna','planta.tipo-fibra'))
                                <a href="{{ route('planta.tipo-fibra') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-slate-600 hover:bg-slate-100/50 rounded transition-colors">
                                    <i class="ri-code-s-slash-line text-xs opacity-70"></i>
                                    <span>Tipos de Fibra</span>
                                </a>
                                @endif
                                @if($_canSub('PlantaExterna','planta.amplificadores'))
                                <a href="{{ route('planta.amplificadores') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-slate-600 hover:bg-slate-100/50 rounded transition-colors">
                                    <i class="ri-pulse-line text-xs opacity-70"></i>
                                    <span>Amplificadores</span>
                                </a>
                                @endif
                                @if($_canSub('PlantaExterna','planta.nodos-opticos'))
                                <a href="{{ route('planta.nodos-opticos') }}"
                                   class="flex items-center gap-2 px-4 py-1.5 text-xs font-bold text-gray-500 hover:text-slate-600 hover:bg-slate-100/50 rounded transition-colors">
                                    <i class="ri-share-line text-xs opacity-70"></i>
                                    <span>Nodos Ópticos</span>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif

                    </div>
                </div>

                <div class="my-2 border-t border-gray-100 mx-2"></div>

                {{-- ═════════════════════════════════════════════════════════════
                    ═ GESTIÓN AL CLIENTE (OPERRACIONALES)
                    ═════════════════════════════════════════════════════════════ --}}
                <p class="hide-collapsed section-label px-3 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5">Operacionales</p>

                {{-- ── GESTIÓN AL CLIENTE (OPERACIONALES) ── --}}
                @if($_canMod('GestionClientes'))
                <div x-data="{ open: {{ $gcActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $gcActive ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-red-50 hover:text-red-700' }}">
                        <i class="ri-customer-service-2-line text-lg {{ $gcActive ? 'text-red-600' : 'text-red-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Gestión al Cliente</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-red-100 pb-1">
                        @if($_canSub('GestionClientes','contrataciones-nuevas'))
                        <a href="{{ route('contrataciones.nuevas') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('contrataciones.nuevas') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-file-add-line text-sm opacity-70"></i>
                            <span>Contratos Nuevos</span>
                        </a>
                        @endif
                        @if($_canSub('GestionClientes','servicios-adicionales'))
                        <a href="{{ route('servicios.adicionales') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('servicios.adicionales') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-add-box-line text-sm opacity-70"></i>
                            <span>Servicios Adicionales</span>
                        </a>
                        @endif
                        @if($_canSub('GestionClientes','contratacion-promocion'))
                        <a href="{{ route('contratacion.promocion') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('contratacion.promocion') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-discount-percent-line text-sm opacity-70"></i>
                            <span>Pago en Promoción</span>
                        </a>
                        @endif
                        @if($_canSub('GestionClientes','pago-mensualidad'))
                        <a href="{{ route('pago.mensualidad') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('pago.mensualidad') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-calendar-check-line text-sm opacity-70"></i>
                            <span>Pago de Mensualidad</span>
                        </a>
                        @endif
                        @if($_canSub('GestionClientes','estado-cuenta'))
                        <a href="{{ route('estado.cuenta') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('estado.cuenta') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-file-list-3-line text-sm opacity-70"></i>
                            <span>Estado de Cuenta</span>
                        </a>
                        @endif
                        @if($_canSub('GestionClientes','suspension-falta-pago'))
                        <a href="{{ route('suspension.clientes') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('suspension.clientes') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-user-unfollow-line text-sm opacity-70"></i>
                            <span>Suspensión Falta Pago</span>
                        </a>
                        @endif
                        @if($_canSub('GestionClientes','reconexion-cliente'))
                        <a href="{{ route('reconexion.cliente') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('reconexion.cliente') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-plug-line text-sm opacity-70"></i>
                            <span>Reconexión de Cliente</span>
                        </a>
                        @endif
                        @if($_canSub('GestionClientes','cancelacion-servicio'))
                        <a href="{{ route('cancelacion.servicio') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('cancelacion.servicio') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-close-circle-line text-sm opacity-70"></i>
                            <span>Cancelación de Servicio</span>
                        </a>
                        @endif
                        @if($_canSub('GestionClientes','recuperacion-equipos'))
                        <a href="{{ route('recuperacion.equipos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('recuperacion.equipos') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-router-line text-sm opacity-70"></i>
                            <span>Recuperación de Equipos</span>
                        </a>
                        @endif
                        @if($_canSub('GestionClientes','reportes-servicio'))
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
                    <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-black text-xs shadow-md shadow-indigo-100">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="hide-collapsed overflow-hidden flex-1 min-w-0">
                        <p class="text-[10px] font-black text-gray-900 uppercase truncate leading-none">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] font-bold text-gray-400 truncate mt-1 italic uppercase tracking-tighter">ADMINISTRADOR</p>
                    </div>
                </div>
            </div>
        </aside>

            {{-- ================================================================
                 CONTENIDO PRINCIPAL
            ================================================================ --}}
            <div class="flex flex-col flex-1 min-w-0 overflow-hidden relative bg-gray-50">

                {{-- TOP BAR --}}
                <header class="flex items-center justify-between h-16 px-4 md:px-6 bg-white border-b border-gray-200 shadow-[0_4px_20px_-10px_rgba(0,0,0,0.05)] flex-shrink-0 z-20">
                    <div class="flex items-center gap-3">

                        {{-- Toggle mobile --}}
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                                class="lg:hidden p-2.5 text-gray-500 bg-white border border-gray-200 shadow-sm hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            <i class="ri-menu-3-line text-lg leading-none"></i>
                        </button>

                        {{-- Toggle desktop --}}
                        <button @click="sidebarOpen = !sidebarOpen"
                                class="hidden lg:flex p-2.5 text-gray-500 bg-white border border-gray-200 shadow-sm hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                            <i class="ri-menu-fold-line text-lg leading-none transition-transform duration-300" :class="!sidebarOpen && 'rotate-180'"></i>
                        </button>

                    </div>

                    <div class="flex items-center gap-4">
                        <livewire:layout.notificaciones-top-bar />
                        <livewire:layout.navigation />
                    </div>
                </header>

                {{-- MAIN --}}
                <main class="flex-1 overflow-y-auto p-4 md:p-8 z-10">
                    {{ $slot }}
                </main>

                {{-- FOOTER --}}
                <footer class="flex-shrink-0 px-6 py-4 bg-transparent border-t border-gray-200/60 text-[11px] text-gray-400 flex items-center justify-between">
                    <span class="font-medium text-gray-500">TVT Sistema &copy; {{ date('Y') }} — Tu Visión Telecable</span>
                    <span class="font-bold tracking-widest text-gray-400 uppercase">v1.0.0</span>
                </footer>

            </div>
        </div>

    </body>
</html>