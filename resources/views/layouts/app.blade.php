<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TVT Sistema') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
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
            $gcActive = request()->routeIs([
                'contrataciones.nuevas', 'servicios.adicionales', 'contratacion.promocion',
                'pago.mensualidad', 'estado.cuenta', 'suspension.clientes',
                'reconexion.cliente', 'cancelacion.servicio', 'recuperacion.equipos',
                'reportes.servicio', 'reportes.atender',
            ]);
            $infraActive   = request()->routeIs('infraestructura.*');
            $rrhhActive    = request()->routeIs('rrhh.*');
            $finActive     = request()->routeIs('financiero.*');
            $redActive     = request()->routeIs('red.*');
            $tvActive      = request()->routeIs('tv.*');
            $svcActive     = request()->routeIs('cat.servicios.*');
            $energiaActive = request()->routeIs('energia.*');
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

                {{-- ── GESTIÓN AL CLIENTE ── --}}
                <p class="hide-collapsed section-label px-3 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5">Gestión al Cliente</p>

                <div x-data="{ open: {{ $gcActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $gcActive ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-red-50 hover:text-red-700' }}">
                        <i class="ri-customer-service-2-line text-lg {{ $gcActive ? 'text-red-600' : 'text-red-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Gestión al Cliente</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-red-100 pb-1">
                        <a href="{{ route('contrataciones.nuevas') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('contrataciones.nuevas') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-file-add-line text-sm opacity-70"></i>
                            <span>Contratos Nuevos</span>
                        </a>
                        <a href="{{ route('servicios.adicionales') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('servicios.adicionales') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-add-box-line text-sm opacity-70"></i>
                            <span>Servicios Adicionales</span>
                        </a>
                        <a href="{{ route('contratacion.promocion') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('contratacion.promocion') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-discount-percent-line text-sm opacity-70"></i>
                            <span>Pago en Promoción</span>
                        </a>
                        <a href="{{ route('pago.mensualidad') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('pago.mensualidad') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-calendar-check-line text-sm opacity-70"></i>
                            <span>Pago de Mensualidad</span>
                        </a>
                        <a href="{{ route('estado.cuenta') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('estado.cuenta') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-file-list-3-line text-sm opacity-70"></i>
                            <span>Estado de Cuenta</span>
                        </a>
                        <a href="{{ route('suspension.clientes') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('suspension.clientes') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-user-unfollow-line text-sm opacity-70"></i>
                            <span>Suspensión Falta Pago</span>
                        </a>
                        <a href="{{ route('reconexion.cliente') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('reconexion.cliente') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-plug-line text-sm opacity-70"></i>
                            <span>Reconexión de Cliente</span>
                        </a>
                        <a href="{{ route('cancelacion.servicio') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('cancelacion.servicio') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-close-circle-line text-sm opacity-70"></i>
                            <span>Cancelación de Servicio</span>
                        </a>
                        <a href="{{ route('recuperacion.equipos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('recuperacion.equipos') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-router-line text-sm opacity-70"></i>
                            <span>Recuperación de Equipos</span>
                        </a>
                        <a href="{{ route('reportes.servicio') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('reportes.servicio', 'reportes.atender') ? 'text-red-700 bg-red-50' : 'text-gray-500 hover:text-red-600 hover:bg-red-50/50' }}">
                            <i class="ri-inbox-archive-line text-sm opacity-70"></i>
                            <span>Bandeja de Reportes</span>
                        </a>
                    </div>
                </div>

                <div class="my-3 border-t border-gray-100 mx-2"></div>

                {{-- ── MÓDULOS DE CONFIGURACIÓN ── --}}
                <p class="hide-collapsed section-label px-3 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-1.5">Módulos de Configuración</p>

                {{-- Sedes e Infraestructura --}}
                <div x-data="{ open: {{ $infraActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $infraActive ? 'bg-pink-50 text-pink-700' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-700' }}">
                        <i class="ri-map-pin-range-line text-lg {{ $infraActive ? 'text-pink-600' : 'text-pink-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Sedes e Infraestructura</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-pink-100 pb-1">
                        <a href="{{ route('infraestructura.geografia') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('infraestructura.geografia') ? 'text-pink-700 bg-pink-50' : 'text-gray-500 hover:text-pink-600 hover:bg-pink-50/50' }}">
                            <i class="ri-map-2-line text-sm opacity-70"></i>
                            <span>Geografía (INEGI)</span>
                        </a>
                        <a href="{{ route('infraestructura.sucursales') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('infraestructura.sucursales') ? 'text-pink-700 bg-pink-50' : 'text-gray-500 hover:text-pink-600 hover:bg-pink-50/50' }}">
                            <i class="ri-store-3-line text-sm opacity-70"></i>
                            <span>Crear Sucursal</span>
                        </a>
                        <a href="{{ route('infraestructura.calles') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('infraestructura.calles') ? 'text-pink-700 bg-pink-50' : 'text-gray-500 hover:text-pink-600 hover:bg-pink-50/50' }}">
                            <i class="ri-road-map-line text-sm opacity-70"></i>
                            <span>Registro de Calles</span>
                        </a>
                        <a href="{{ route('infraestructura.postes') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('infraestructura.postes') ? 'text-pink-700 bg-pink-50' : 'text-gray-500 hover:text-pink-600 hover:bg-pink-50/50' }}">
                            <i class="ri-pushpin-2-line text-sm opacity-70"></i>
                            <span>Inventario de Postes</span>
                        </a>
                    </div>
                </div>

                {{-- Recursos Humanos --}}
                <div x-data="{ open: {{ $rrhhActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $rrhhActive ? 'bg-blue-50 text-blue-700' : 'text-gray-600 hover:bg-blue-50 hover:text-blue-700' }}">
                        <i class="ri-team-line text-lg {{ $rrhhActive ? 'text-blue-600' : 'text-blue-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Recursos Humanos</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-blue-100 pb-1">
                        <a href="{{ route('rrhh.empleados') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('rrhh.empleados') ? 'text-blue-700 bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }}">
                            <i class="ri-user-add-line text-sm opacity-70"></i>
                            <span>Registro Empleados</span>
                        </a>
                        <a href="{{ route('rrhh.vacaciones') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('rrhh.vacaciones') ? 'text-blue-700 bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }}">
                            <i class="ri-sun-line text-sm opacity-70"></i>
                            <span>Vacaciones</span>
                        </a>
                        <a href="{{ route('rrhh.descanso') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('rrhh.descanso') ? 'text-blue-700 bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }}">
                            <i class="ri-calendar-event-line text-sm opacity-70"></i>
                            <span>Descanso Mensual</span>
                        </a>
                        <a href="{{ route('rrhh.permisos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('rrhh.permisos') ? 'text-blue-700 bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }}">
                            <i class="ri-pass-valid-line text-sm opacity-70"></i>
                            <span>Permisos</span>
                        </a>
                        <a href="{{ route('rrhh.accesos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('rrhh.accesos') ? 'text-blue-700 bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }}">
                            <i class="ri-key-2-line text-sm opacity-70"></i>
                            <span>Accesos al Sistema</span>
                        </a>
                    </div>
                </div>

                {{-- Financieros --}}
                <div x-data="{ open: {{ $finActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $finActive ? 'bg-emerald-50 text-emerald-700' : 'text-gray-600 hover:bg-emerald-50 hover:text-emerald-700' }}">
                        <i class="ri-money-dollar-box-line text-lg {{ $finActive ? 'text-emerald-600' : 'text-emerald-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Financieros</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-emerald-100 pb-1">
                        <a href="{{ route('financiero.tarifas.principales') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('financiero.tarifas.principales') ? 'text-emerald-700 bg-emerald-50' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50' }}">
                            <i class="ri-money-dollar-circle-line text-sm opacity-70"></i>
                            <span>Tarifas Principales</span>
                        </a>
                        <a href="{{ route('financiero.tarifas.adicionales') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('financiero.tarifas.adicionales') ? 'text-emerald-700 bg-emerald-50' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50' }}">
                            <i class="ri-coins-line text-sm opacity-70"></i>
                            <span>Tarifas Adicionales</span>
                        </a>
                        <a href="{{ route('financiero.promociones') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('financiero.promociones') ? 'text-emerald-700 bg-emerald-50' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50' }}">
                            <i class="ri-price-tag-3-line text-sm opacity-70"></i>
                            <span>Promociones</span>
                        </a>
                        <a href="{{ route('financiero.descuentos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('financiero.descuentos') ? 'text-emerald-700 bg-emerald-50' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50' }}">
                            <i class="ri-coupon-3-line text-sm opacity-70"></i>
                            <span>Descuentos</span>
                        </a>
                        <a href="{{ route('financiero.ingresos.egresos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('financiero.ingresos.egresos') ? 'text-emerald-700 bg-emerald-50' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50' }}">
                            <i class="ri-arrow-up-down-line text-sm opacity-70"></i>
                            <span>Ingresos / Egresos</span>
                        </a>
                        <a href="{{ route('financiero.proveedores') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('financiero.proveedores') ? 'text-emerald-700 bg-emerald-50' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50' }}">
                            <i class="ri-bank-line text-sm opacity-70"></i>
                            <span>Proveedores y Bancos</span>
                        </a>
                    </div>
                </div>

                {{-- Red e Internet --}}
                <div x-data="{ open: {{ $redActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $redActive ? 'bg-purple-50 text-purple-700' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700' }}">
                        <i class="ri-router-line text-lg {{ $redActive ? 'text-purple-600' : 'text-purple-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Red e Internet</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-purple-100 pb-1">
                        <a href="{{ route('red.naps') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.naps') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-node-tree text-sm opacity-70"></i>
                            <span>Administrar NAPs</span>
                        </a>
                        <a href="{{ route('red.olt') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.olt') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-server-line text-sm opacity-70"></i>
                            <span>OLT (Ext / Int)</span>
                        </a>
                        <a href="{{ route('red.onus') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.onus') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-modem-line text-sm opacity-70"></i>
                            <span>Administración ONUs</span>
                        </a>
                        <a href="{{ route('red.vlans') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.vlans') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-global-line text-sm opacity-70"></i>
                            <span>Winbox / VLANs</span>
                        </a>
                        <a href="{{ route('red.ccr') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.ccr') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-exchange-box-line text-sm opacity-70"></i>
                            <span>CCR / Switches</span>
                        </a>
                        <a href="{{ route('red.starlinks') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.starlinks') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-base-station-line text-sm opacity-70"></i>
                            <span>Starlinks / ISP Telmex</span>
                        </a>
                    </div>
                </div>

                {{-- Catálogo TV --}}
                <div x-data="{ open: {{ $tvActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $tvActive ? 'bg-orange-50 text-orange-700' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-700' }}">
                        <i class="ri-broadcast-line text-lg {{ $tvActive ? 'text-orange-600' : 'text-orange-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Catálogo TV</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-orange-100 pb-1">
                        <a href="{{ route('tv.canales') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('tv.canales') ? 'text-orange-700 bg-orange-50' : 'text-gray-500 hover:text-orange-600 hover:bg-orange-50/50' }}">
                            <i class="ri-satellite-line text-sm opacity-70"></i>
                            <span>Canales y Satélites</span>
                        </a>
                        <a href="{{ route('tv.moduladores') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('tv.moduladores') ? 'text-orange-700 bg-orange-50' : 'text-gray-500 hover:text-orange-600 hover:bg-orange-50/50' }}">
                            <i class="ri-equalizer-line text-sm opacity-70"></i>
                            <span>Moduladores (An/Dig)</span>
                        </a>
                        <a href="{{ route('tv.transmisores') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('tv.transmisores') ? 'text-orange-700 bg-orange-50' : 'text-gray-500 hover:text-orange-600 hover:bg-orange-50/50' }}">
                            <i class="ri-broadcast-line text-sm opacity-70"></i>
                            <span>Transmisores</span>
                        </a>
                        <a href="{{ route('tv.pon-edfa') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('tv.pon-edfa') ? 'text-orange-700 bg-orange-50' : 'text-gray-500 hover:text-orange-600 hover:bg-orange-50/50' }}">
                            <i class="ri-rfid-line text-sm opacity-70"></i>
                            <span>PON EDFA</span>
                        </a>
                        <a href="{{ route('tv.mininodos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('tv.mininodos') ? 'text-orange-700 bg-orange-50' : 'text-gray-500 hover:text-orange-600 hover:bg-orange-50/50' }}">
                            <i class="ri-radar-line text-sm opacity-70"></i>
                            <span>Mininodos y Antenas</span>
                        </a>
                    </div>
                </div>

                {{-- Servicios / Tareas --}}
                <div x-data="{ open: {{ $svcActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $svcActive ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                        <i class="ri-settings-5-line text-lg {{ $svcActive ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Servicios / Tareas</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-indigo-100 pb-1">
                        <a href="{{ route('cat.servicios.registro') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('cat.servicios.registro') ? 'text-indigo-700 bg-indigo-50' : 'text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50' }}">
                            <i class="ri-tools-line text-sm opacity-70"></i>
                            <span>Registro de Servicios</span>
                        </a>
                        <a href="{{ route('cat.servicios.actividades') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('cat.servicios.actividades') ? 'text-indigo-700 bg-indigo-50' : 'text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50' }}">
                            <i class="ri-task-line text-sm opacity-70"></i>
                            <span>Matriz de Actividades</span>
                        </a>
                    </div>
                </div>

                <div class="my-3 border-t border-gray-100 mx-2"></div>

                {{-- Energía y Enlaces --}}
                <div x-data="{ open: {{ $energiaActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $energiaActive ? 'bg-yellow-50 text-yellow-700' : 'text-gray-600 hover:bg-yellow-50 hover:text-yellow-700' }}">
                        <i class="ri-flashlight-line text-lg {{ $energiaActive ? 'text-yellow-600' : 'text-yellow-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Energía y Enlaces</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-yellow-100 pb-1">
                        <a href="{{ route('energia.fibra') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('energia.fibra') ? 'text-yellow-700 bg-yellow-50' : 'text-gray-500 hover:text-yellow-600 hover:bg-yellow-50/50' }}">
                            <i class="ri-links-line text-sm opacity-70"></i>
                            <span>Enlaces Fibra</span>
                        </a>
                        <a href="{{ route('energia.ctc') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('energia.ctc') ? 'text-yellow-700 bg-yellow-50' : 'text-gray-500 hover:text-yellow-600 hover:bg-yellow-50/50' }}">
                            <i class="ri-list-settings-line text-sm opacity-70"></i>
                            <span>Catálogo CTC</span>
                        </a>
                        <a href="{{ route('energia.ups') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('energia.ups') ? 'text-yellow-700 bg-yellow-50' : 'text-gray-500 hover:text-yellow-600 hover:bg-yellow-50/50' }}">
                            <i class="ri-battery-2-charge-line text-sm opacity-70"></i>
                            <span>UPS / Plantas</span>
                        </a>
                    </div>
                </div>

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