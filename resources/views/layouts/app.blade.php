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

            /* Línea vertical submenú */
            .sub-nav {
                border-left: 2px solid #e0e7ff;
                margin-left: 1.1rem;
                padding-left: .6rem;
            }
        </style>
    </head>

    <body class="font-sans antialiased text-gray-900 bg-gray-50">

        {{-- Overlay mobile --}}
        <div x-show="mobileSidebarOpen" x-cloak @click="mobileSidebarOpen = false"
             class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden"></div>

        <div class="flex h-screen overflow-hidden">

            {{-- ================================================================
                 SIDEBAR
            ================================================================ --}}
            <aside
                :class="[
                    'flex flex-col bg-white border-r border-gray-200 transition-all duration-300 ease-in-out z-30 flex-shrink-0',
                    sidebarOpen ? 'w-64' : 'sidebar-collapsed w-16',
                    mobileSidebarOpen ? 'fixed inset-y-0 left-0 !w-64 shadow-2xl' : 'hidden lg:flex'
                ]"
            >
                {{-- Logo --}}
                <div class="flex items-center h-16 px-4 border-b border-gray-100 flex-shrink-0 sidebar-logo">
                    <div class="flex items-center gap-2 overflow-hidden">
                        <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                            <i class="ri-tv-line text-white text-lg"></i>
                        </div>
                        <span class="hide-collapsed font-bold text-gray-900 text-lg whitespace-nowrap">
                            TVT <span class="text-indigo-600">Sistema</span>
                        </span>
                    </div>
                </div>

                {{-- Navegación --}}
                <nav class="flex-1 overflow-y-auto sidebar-scroll py-3 px-2 space-y-0.5">

                    {{-- Dashboard --}}
                    <a href="{{ route('dashboard') }}"
                       class="nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
                              {{ request()->routeIs('dashboard') ? 'nav-active' : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}">
                        <i class="ri-dashboard-line text-lg flex-shrink-0 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-indigo-400' }}"></i>
                        <span class="hide-collapsed">Inicio</span>
                    </a>

                    {{-- ── CLIENTES ──────────────────────────────────────────── --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                                class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <i class="ri-team-line text-lg flex-shrink-0 text-indigo-400"></i>
                            <span class="hide-collapsed flex-1 text-left">Clientes</span>
                            <i class="hide-collapsed ri-arrow-down-s-line text-base text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="sub-nav mt-0.5 space-y-0.5">
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-list-unordered text-sm text-gray-400"></i><span class="hide-collapsed">Lista de Clientes</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-user-add-line text-sm text-gray-400"></i><span class="hide-collapsed">Contratación Nueva</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-arrow-go-back-line text-sm text-gray-400"></i><span class="hide-collapsed">Reconexión</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-percent-line text-sm text-gray-400"></i><span class="hide-collapsed">Contrat. Promociones</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-file-text-line text-sm text-gray-400"></i><span class="hide-collapsed">Estado de Cuenta</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-secure-payment-line text-sm text-gray-400"></i><span class="hide-collapsed">Cobro Mensualidad</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-add-box-line text-sm text-gray-400"></i><span class="hide-collapsed">Servicios Adicionales</span>
                            </a>
                        </div>
                    </div>

                    {{-- ── REPORTES DE SERVICIO ──────────────────────────────── --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                                class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <i class="ri-file-list-3-line text-lg flex-shrink-0 text-indigo-400"></i>
                            <span class="hide-collapsed flex-1 text-left">Reportes de Servicio</span>
                            <i class="hide-collapsed ri-arrow-down-s-line text-base text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="sub-nav mt-0.5 space-y-0.5">
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-eye-line text-sm text-gray-400"></i><span class="hide-collapsed">Ver Reportes</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-forbid-2-line text-sm text-gray-400"></i><span class="hide-collapsed">Suspensión</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-close-circle-line text-sm text-gray-400"></i><span class="hide-collapsed">Cancelación</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-arrow-down-circle-line text-sm text-gray-400"></i><span class="hide-collapsed">Recuperación Equipo</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-error-warning-line text-sm text-gray-400"></i><span class="hide-collapsed">Fallas de Servicio</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-map-pin-line text-sm text-gray-400"></i><span class="hide-collapsed">Cambio de Domicilio</span>
                            </a>
                        </div>
                    </div>

                    {{-- ── PLANTA EXTERNA ────────────────────────────────────── --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                                class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <i class="ri-tools-line text-lg flex-shrink-0 text-indigo-400"></i>
                            <span class="hide-collapsed flex-1 text-left">Planta Externa</span>
                            <i class="hide-collapsed ri-arrow-down-s-line text-base text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="sub-nav mt-0.5 space-y-0.5">
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-signal-tower-line text-sm text-gray-400"></i><span class="hide-collapsed">Postes</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-node-tree text-sm text-gray-400"></i><span class="hide-collapsed">NAPs</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-router-line text-sm text-gray-400"></i><span class="hide-collapsed">ONUs</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-server-line text-sm text-gray-400"></i><span class="hide-collapsed">OLTs</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-share-line text-sm text-gray-400"></i><span class="hide-collapsed">Fibra Óptica</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-building-2-line text-sm text-gray-400"></i><span class="hide-collapsed">CTC</span>
                            </a>
                        </div>
                    </div>

                    {{-- ── TELEVISIÓN ────────────────────────────────────────── --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                                class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <i class="ri-tv-2-line text-lg flex-shrink-0 text-indigo-400"></i>
                            <span class="hide-collapsed flex-1 text-left">Televisión</span>
                            <i class="hide-collapsed ri-arrow-down-s-line text-base text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="sub-nav mt-0.5 space-y-0.5">
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-hard-drive-2-line text-sm text-gray-400"></i><span class="hide-collapsed">Mininodos</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-remote-control-line text-sm text-gray-400"></i><span class="hide-collapsed">Receptores</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-live-line text-sm text-gray-400"></i><span class="hide-collapsed">Canales</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-settings-4-line text-sm text-gray-400"></i><span class="hide-collapsed">Moduladores</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-broadcast-line text-sm text-gray-400"></i><span class="hide-collapsed">Transmisores</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-global-line text-sm text-gray-400"></i><span class="hide-collapsed">Satélites / Antenas</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-wifi-line text-sm text-gray-400"></i><span class="hide-collapsed">Prov. de Señal</span>
                            </a>
                        </div>
                    </div>

                    {{-- ── INTERNET ──────────────────────────────────────────── --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                                class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <i class="ri-wifi-line text-lg flex-shrink-0 text-indigo-400"></i>
                            <span class="hide-collapsed flex-1 text-left">Internet</span>
                            <i class="hide-collapsed ri-arrow-down-s-line text-base text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="sub-nav mt-0.5 space-y-0.5">
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-star-line text-sm text-gray-400"></i><span class="hide-collapsed">Starlinks</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-share-circle-line text-sm text-gray-400"></i><span class="hide-collapsed">CCR / Switches</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-links-line text-sm text-gray-400"></i><span class="hide-collapsed">VLANs</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-terminal-box-line text-sm text-gray-400"></i><span class="hide-collapsed">Winbox</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-cloud-line text-sm text-gray-400"></i><span class="hide-collapsed">ISP / Telmex</span>
                            </a>
                        </div>
                    </div>

                    {{-- ── FINANZAS ──────────────────────────────────────────── --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                                class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <i class="ri-money-dollar-circle-line text-lg flex-shrink-0 text-indigo-400"></i>
                            <span class="hide-collapsed flex-1 text-left">Finanzas</span>
                            <i class="hide-collapsed ri-arrow-down-s-line text-base text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="sub-nav mt-0.5 space-y-0.5">
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-safe-line text-sm text-gray-400"></i><span class="hide-collapsed">Caja</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-arrow-down-circle-line text-sm text-gray-400"></i><span class="hide-collapsed">Ingresos</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-arrow-up-circle-line text-sm text-gray-400"></i><span class="hide-collapsed">Egresos</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-bank-line text-sm text-gray-400"></i><span class="hide-collapsed">Depósitos</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-transfer-line text-sm text-gray-400"></i><span class="hide-collapsed">Traspasos</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-scissors-cut-line text-sm text-gray-400"></i><span class="hide-collapsed">Cortes Mensuales</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-price-tag-3-line text-sm text-gray-400"></i><span class="hide-collapsed">Tarifas y Promo.</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-file-pdf-line text-sm text-gray-400"></i><span class="hide-collapsed">Facturación</span>
                            </a>
                        </div>
                    </div>

                    {{-- ── CATÁLOGOS ─────────────────────────────────────────── --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                                class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <i class="ri-book-2-line text-lg flex-shrink-0 text-indigo-400"></i>
                            <span class="hide-collapsed flex-1 text-left">Catálogos</span>
                            <i class="hide-collapsed ri-arrow-down-s-line text-base text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="sub-nav mt-0.5 space-y-0.5">
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-store-line text-sm text-gray-400"></i><span class="hide-collapsed">Sucursales</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-map-2-line text-sm text-gray-400"></i><span class="hide-collapsed">Calles / Localidades</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-truck-line text-sm text-gray-400"></i><span class="hide-collapsed">Proveedores</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-bank-card-line text-sm text-gray-400"></i><span class="hide-collapsed">Bancos</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-service-line text-sm text-gray-400"></i><span class="hide-collapsed">Servicios</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-task-line text-sm text-gray-400"></i><span class="hide-collapsed">Actividades</span>
                            </a>
                        </div>
                    </div>

                    {{-- ── RECURSOS HUMANOS ──────────────────────────────────── --}}
                    <div x-data="{ open: false }">
                        <button @click="open = !open"
                                class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                            <i class="ri-group-line text-lg flex-shrink-0 text-indigo-400"></i>
                            <span class="hide-collapsed flex-1 text-left">Recursos Humanos</span>
                            <i class="hide-collapsed ri-arrow-down-s-line text-base text-gray-400 transition-transform duration-200" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="sub-nav mt-0.5 space-y-0.5">
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-user-3-line text-sm text-gray-400"></i><span class="hide-collapsed">Empleados</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-key-line text-sm text-gray-400"></i><span class="hide-collapsed">Accesos al Sistema</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-calendar-check-line text-sm text-gray-400"></i><span class="hide-collapsed">Vacaciones</span>
                            </a>
                            <a href="#" class="flex items-center gap-2 px-2 py-1.5 rounded-lg text-xs font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                                <i class="ri-calendar-event-line text-sm text-gray-400"></i><span class="hide-collapsed">Permisos y Descansos</span>
                            </a>
                        </div>
                    </div>

                    {{-- ── SECCIÓN: REPORTES ─────────────────────────────────── --}}
                    <div class="section-label pt-3 pb-1 px-3">
                        <span class="text-[10px] font-semibold uppercase tracking-widest text-gray-400">Reportes</span>
                    </div>

                    <a href="#"
                       class="nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                        <i class="ri-bar-chart-box-line text-lg flex-shrink-0 text-indigo-400"></i>
                        <span class="hide-collapsed">Reportes de Clientes</span>
                    </a>

                    <a href="#"
                       class="nav-item flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-600 hover:bg-indigo-50 hover:text-indigo-700 transition-colors">
                        <i class="ri-line-chart-line text-lg flex-shrink-0 text-indigo-400"></i>
                        <span class="hide-collapsed">Reportes Financieros</span>
                    </a>

                </nav>

                {{-- Footer usuario --}}
                <div class="flex-shrink-0 border-t border-gray-100 p-3">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-semibold text-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="hide-collapsed overflow-hidden flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <button @click="sidebarOpen = !sidebarOpen"
                                class="hide-collapsed p-1 text-gray-400 hover:text-gray-600 rounded transition-colors flex-shrink-0 hidden lg:flex">
                            <i :class="sidebarOpen ? 'ri-arrow-left-s-line' : 'ri-arrow-right-s-line'" class="text-xl"></i>
                        </button>
                    </div>
                </div>

            </aside>

            {{-- ================================================================
                 CONTENIDO PRINCIPAL
            ================================================================ --}}
            <div class="flex flex-col flex-1 min-w-0 overflow-hidden">

                {{-- TOP BAR --}}
                <header class="flex items-center justify-between h-16 px-4 md:px-6 bg-white border-b border-gray-200 flex-shrink-0">
                    <div class="flex items-center gap-2">

                        {{-- Toggle mobile --}}
                        <button @click="mobileSidebarOpen = !mobileSidebarOpen"
                                class="lg:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ri-menu-3-line text-xl"></i>
                        </button>

                        {{-- Toggle desktop --}}
                        <button @click="sidebarOpen = !sidebarOpen"
                                class="hidden lg:flex p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                            <i class="ri-menu-fold-line text-xl"></i>
                        </button>

                    </div>

                    <div class="flex items-center gap-2">
                        <livewire:layout.notificaciones-top-bar />
                        <livewire:layout.navigation />
                    </div>
                </header>

                {{-- MAIN --}}
                <main class="flex-1 overflow-y-auto bg-gray-50 p-4 md:p-6">
                    {{ $slot }}
                </main>

                {{-- FOOTER --}}
                <footer class="flex-shrink-0 px-6 py-2 bg-white border-t border-gray-100 text-xs text-gray-400 flex items-center justify-between">
                    <span>TVT Sistema &copy; {{ date('Y') }} — Tu Visión Telecable</span>
                    <span>v1.0.0</span>
                </footer>

            </div>
        </div>

    </body>
</html>