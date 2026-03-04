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
            $svcActive      = request()->routeIs('cat.servicios.registro');
            $reportesActive = request()->routeIs('cat.servicios.actividades');
            $plantaActive   = request()->routeIs('planta.*');
            $ctcActive      = request()->routeIs('energia.ctc');
            $tvActive       = request()->routeIs('tv.*');
            $redActive      = request()->routeIs('red.*');
            $upsActive      = request()->routeIs('energia.ups');
            $fibraActive    = request()->routeIs('energia.fibra');
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

                {{-- 1. Catálogo Sucursales --}}
                <div x-data="{ open: {{ $infraActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $infraActive ? 'bg-pink-50 text-pink-700' : 'text-gray-600 hover:bg-pink-50 hover:text-pink-700' }}">
                        <i class="ri-map-pin-range-line text-lg {{ $infraActive ? 'text-pink-600' : 'text-pink-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Sucursales</span>
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

                {{-- 2. Recursos Humanos --}}
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
                        <a href="{{ route('rrhh.accesos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('rrhh.accesos') ? 'text-blue-700 bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }}">
                            <i class="ri-key-2-line text-sm opacity-70"></i>
                            <span>Accesos al Sistema</span>
                        </a>
                        <a href="{{ route('rrhh.permisos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('rrhh.permisos') ? 'text-blue-700 bg-blue-50' : 'text-gray-500 hover:text-blue-600 hover:bg-blue-50/50' }}">
                            <i class="ri-pass-valid-line text-sm opacity-70"></i>
                            <span>Permisos</span>
                        </a>
                    </div>
                </div>

                {{-- 3. Financieros --}}
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
                            <span>Promociones Estacionales</span>
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
                            <span>Tipo Ingresos / Egresos</span>
                        </a>
                        <a href="{{ route('financiero.proveedores') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('financiero.proveedores') ? 'text-emerald-700 bg-emerald-50' : 'text-gray-500 hover:text-emerald-600 hover:bg-emerald-50/50' }}">
                            <i class="ri-bank-line text-sm opacity-70"></i>
                            <span>Proveedores / Bancos</span>
                        </a>
                    </div>
                </div>

                {{-- 4. Clientes --}}
                <div x-data="{ open: {{ $clientesActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $clientesActive ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-teal-50 hover:text-teal-700' }}">
                        <i class="ri-user-star-line text-lg {{ $clientesActive ? 'text-teal-600' : 'text-teal-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Clientes</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-teal-100 pb-1">
                        <a href="{{ route('clientes.registro') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('clientes.registro') ? 'text-teal-700 bg-teal-50' : 'text-gray-500 hover:text-teal-600 hover:bg-teal-50/50' }}">
                            <i class="ri-user-add-line text-sm opacity-70"></i>
                            <span>Registro de Clientes</span>
                        </a>
                    </div>
                </div>

                {{-- 5. Servicios --}}
                <div x-data="{ open: {{ $svcActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $svcActive ? 'bg-indigo-50 text-indigo-700' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                        <i class="ri-settings-5-line text-lg {{ $svcActive ? 'text-indigo-600' : 'text-indigo-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Servicios</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-indigo-100 pb-1">
                        <a href="{{ route('cat.servicios.registro') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('cat.servicios.registro') ? 'text-indigo-700 bg-indigo-50' : 'text-gray-500 hover:text-indigo-600 hover:bg-indigo-50/50' }}">
                            <i class="ri-tools-line text-sm opacity-70"></i>
                            <span>Registro de Servicios</span>
                        </a>
                    </div>
                </div>

                {{-- 6. Reportes de Servicio --}}
                <div x-data="{ open: {{ $reportesActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $reportesActive ? 'bg-violet-50 text-violet-700' : 'text-gray-600 hover:bg-violet-50 hover:text-violet-700' }}">
                        <i class="ri-task-line text-lg {{ $reportesActive ? 'text-violet-600' : 'text-violet-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Reportes de Servicio</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-violet-100 pb-1">
                        <a href="{{ route('cat.servicios.actividades') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('cat.servicios.actividades') ? 'text-violet-700 bg-violet-50' : 'text-gray-500 hover:text-violet-600 hover:bg-violet-50/50' }}">
                            <i class="ri-list-check-3 text-sm opacity-70"></i>
                            <span>Registro de Actividades</span>
                        </a>
                    </div>
                </div>

                {{-- 7. Planta Externa --}}
                <div x-data="{ open: {{ $plantaActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $plantaActive ? 'bg-slate-100 text-slate-700' : 'text-gray-600 hover:bg-slate-100 hover:text-slate-700' }}">
                        <i class="ri-node-tree text-lg {{ $plantaActive ? 'text-slate-600' : 'text-slate-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Planta Externa</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-slate-200 pb-1">
                        <a href="{{ route('planta.tipo-fibra') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('planta.tipo-fibra') ? 'text-slate-700 bg-slate-100' : 'text-gray-500 hover:text-slate-600 hover:bg-slate-100/60' }}">
                            <i class="ri-code-s-slash-line text-sm opacity-70"></i>
                            <span>Tipo de Fibra</span>
                        </a>
                        <a href="{{ route('planta.amplificadores') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('planta.amplificadores') ? 'text-slate-700 bg-slate-100' : 'text-gray-500 hover:text-slate-600 hover:bg-slate-100/60' }}">
                            <i class="ri-pulse-line text-sm opacity-70"></i>
                            <span>Amplificadores</span>
                        </a>
                        <a href="{{ route('planta.nodos-opticos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('planta.nodos-opticos') ? 'text-slate-700 bg-slate-100' : 'text-gray-500 hover:text-slate-600 hover:bg-slate-100/60' }}">
                            <i class="ri-share-line text-sm opacity-70"></i>
                            <span>Nodos Ópticos</span>
                        </a>
                    </div>
                </div>

                {{-- 8. Catálogo CTC --}}
                <div x-data="{ open: {{ $ctcActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $ctcActive ? 'bg-amber-50 text-amber-700' : 'text-gray-600 hover:bg-amber-50 hover:text-amber-700' }}">
                        <i class="ri-list-settings-line text-lg {{ $ctcActive ? 'text-amber-600' : 'text-amber-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">CTC</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-amber-100 pb-1">
                        <a href="{{ route('energia.ctc') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('energia.ctc') ? 'text-amber-700 bg-amber-50' : 'text-gray-500 hover:text-amber-600 hover:bg-amber-50/50' }}">
                            <i class="ri-building-2-line text-sm opacity-70"></i>
                            <span>Catálogo CTC</span>
                        </a>
                    </div>
                </div>

                {{-- 9. Servicio de Televisión --}}
                <div x-data="{ open: {{ $tvActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $tvActive ? 'bg-orange-50 text-orange-700' : 'text-gray-600 hover:bg-orange-50 hover:text-orange-700' }}">
                        <i class="ri-broadcast-line text-lg {{ $tvActive ? 'text-orange-600' : 'text-orange-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Televisión</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-orange-100 pb-1">
                        <a href="{{ route('tv.mininodos') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('tv.mininodos') ? 'text-orange-700 bg-orange-50' : 'text-gray-500 hover:text-orange-600 hover:bg-orange-50/50' }}">
                            <i class="ri-radar-line text-sm opacity-70"></i>
                            <span>Mininodos y Antenas</span>
                        </a>
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
                            <span>Transmisores / PON EDFA</span>
                        </a>
                        <a href="{{ route('tv.pon-edfa') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('tv.pon-edfa') ? 'text-orange-700 bg-orange-50' : 'text-gray-500 hover:text-orange-600 hover:bg-orange-50/50' }}">
                            <i class="ri-rfid-line text-sm opacity-70"></i>
                            <span>PON EDFA</span>
                        </a>
                    </div>
                </div>

                {{-- 10. Servicio de Internet --}}
                <div x-data="{ open: {{ $redActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $redActive ? 'bg-purple-50 text-purple-700' : 'text-gray-600 hover:bg-purple-50 hover:text-purple-700' }}">
                        <i class="ri-router-line text-lg {{ $redActive ? 'text-purple-600' : 'text-purple-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Internet</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-purple-100 pb-1">
                        <a href="{{ route('red.onus') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.onus') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-modem-line text-sm opacity-70"></i>
                            <span>ONUs</span>
                        </a>
                        <a href="{{ route('red.naps') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.naps') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-node-tree text-sm opacity-70"></i>
                            <span>NAPs</span>
                        </a>
                        <a href="{{ route('red.olt') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.olt') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-server-line text-sm opacity-70"></i>
                            <span>OLT (Ext / Int)</span>
                        </a>
                        <a href="{{ route('red.starlinks') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.starlinks') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-base-station-line text-sm opacity-70"></i>
                            <span>Starlinks / ISP Telmex</span>
                        </a>
                        <a href="{{ route('red.ccr') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.ccr') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-exchange-box-line text-sm opacity-70"></i>
                            <span>CCR / Switches</span>
                        </a>
                        <a href="{{ route('red.vlans') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('red.vlans') ? 'text-purple-700 bg-purple-50' : 'text-gray-500 hover:text-purple-600 hover:bg-purple-50/50' }}">
                            <i class="ri-global-line text-sm opacity-70"></i>
                            <span>Winbox / VLANs / Sistemas</span>
                        </a>
                    </div>
                </div>

                {{-- 11. Servicios de Energía --}}
                <div x-data="{ open: {{ $upsActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $upsActive ? 'bg-yellow-50 text-yellow-700' : 'text-gray-600 hover:bg-yellow-50 hover:text-yellow-700' }}">
                        <i class="ri-flashlight-line text-lg {{ $upsActive ? 'text-yellow-600' : 'text-yellow-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Energía</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-yellow-100 pb-1">
                        <a href="{{ route('energia.ups') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('energia.ups') ? 'text-yellow-700 bg-yellow-50' : 'text-gray-500 hover:text-yellow-600 hover:bg-yellow-50/50' }}">
                            <i class="ri-battery-2-charge-line text-sm opacity-70"></i>
                            <span>UPS / Plantas de Emergencia</span>
                        </a>
                    </div>
                </div>

                {{-- 12. Fibra Óptica --}}
                <div x-data="{ open: {{ $fibraActive ? 'true' : 'false' }} }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold transition-all
                                   {{ $fibraActive ? 'bg-cyan-50 text-cyan-700' : 'text-gray-600 hover:bg-cyan-50 hover:text-cyan-700' }}">
                        <i class="ri-links-line text-lg {{ $fibraActive ? 'text-cyan-600' : 'text-cyan-400' }}"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Fibra Óptica</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-0.5 ml-4 border-l-2 border-cyan-100 pb-1">
                        <a href="{{ route('energia.fibra') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-xs font-bold uppercase rounded-r-lg transition-colors
                                  {{ request()->routeIs('energia.fibra') ? 'text-cyan-700 bg-cyan-50' : 'text-gray-500 hover:text-cyan-600 hover:bg-cyan-50/50' }}">
                            <i class="ri-git-branch-line text-sm opacity-70"></i>
                            <span>Enlaces de Fibra</span>
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