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
            SIDEBAR: CATÁLOGOS MAESTROS ERP
        ================================================================ --}}
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

            {{-- Navegación Maestra --}}
            <nav class="flex-1 overflow-y-auto sidebar-scroll py-4 px-2 space-y-1">

                {{-- ── OPERACIÓN DIARIA ────────────────────────────────── --}}
                <a href="{{ route('dashboard') }}"
                class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-black uppercase tracking-widest transition-all
                        {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200' : 'text-gray-500 hover:bg-gray-100' }}">
                    <i class="ri-dashboard-3-line text-lg flex-shrink-0"></i>
                    <span class="hide-collapsed">Centro de Operaciones</span>
                </a>

                <div class="my-4 border-t border-gray-100 mx-2"></div>

                {{-- ── SECCIÓN: CATÁLOGOS ERP ────────────────────────────── --}}
                <p class="hide-collapsed px-3 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2">Módulos de Configuración</p>

                <div x-data="{ open: false }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-pink-50 hover:text-pink-700 transition-all">
                        <i class="ri-map-pin-range-line text-lg text-pink-500"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Sedes e Infraestructura</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-1 ml-4 border-l-2 border-pink-100">
                        <a href="#" class="block px-4 py-1.5 text-[10px] font-bold text-gray-500 hover:text-pink-600 uppercase">Geografía (INEGI)</a>
                        <a href="#" class="block px-4 py-1.5 text-[10px] font-bold text-gray-500 hover:text-pink-600 uppercase">Crear Sucursal</a>
                        <a href="#" class="block px-4 py-1.5 text-[10px] font-bold text-gray-500 hover:text-pink-600 uppercase">Registro de Calles</a>
                        <a href="#" class="block px-4 py-1.5 text-[10px] font-bold text-gray-500 hover:text-pink-600 uppercase">Inventario Postes</a>
                    </div>
                </div>

                <div x-data="{ open: false }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-700 transition-all">
                        <i class="ri-team-line text-lg text-blue-500"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Recursos Humanos</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-1 ml-4 border-l-2 border-blue-100 uppercase text-[10px] font-bold text-gray-500">
                        <a href="#" class="block px-4 py-1.5 hover:text-blue-600">Registro Empleados</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-blue-600">Vacaciones</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-blue-600">Descanso Mensual</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-blue-600">Permisos</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-blue-600">Accesos Sistema</a>
                    </div>
                </div>

                <div x-data="{ open: false }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 transition-all">
                        <i class="ri-money-dollar-box-line text-lg text-emerald-500"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Financieros</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-1 ml-4 border-l-2 border-emerald-100 uppercase text-[10px] font-bold text-gray-500">
                        <a href="#" class="block px-4 py-1.5 hover:text-emerald-600">Tarifas Principales</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-emerald-600">Tarifas Adicionales</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-emerald-600">Promociones</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-emerald-600">Descuentos</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-emerald-600">Ingresos / Egresos</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-emerald-600">Proveedores y Bancos</a>
                    </div>
                </div>

                <div x-data="{ open: false }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-purple-50 hover:text-purple-700 transition-all">
                        <i class="ri-router-line text-lg text-purple-500"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Red e Internet</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-1 ml-4 border-l-2 border-purple-100 uppercase text-[10px] font-bold text-gray-500">
                        <a href="#" class="block px-4 py-1.5 hover:text-purple-600">Administrar NAPs</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-purple-600">OLT (Ext/Int)</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-purple-600">Administración ONUs</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-purple-600">Winbox / VLANs</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-purple-600">CCR / Switches</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-purple-600">Starlinks / ISP Telmex</a>
                    </div>
                </div>

                <div x-data="{ open: false }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-orange-50 hover:text-orange-700 transition-all">
                        <i class="ri-broadcast-line text-lg text-orange-500"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Catálogo TV</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-1 ml-4 border-l-2 border-orange-100 uppercase text-[10px] font-bold text-gray-500">
                        <a href="#" class="block px-4 py-1.5 hover:text-orange-600">Canales y Satélites</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-orange-600">Moduladores (An/Dig)</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-orange-600">Transmisores (Todos)</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-orange-600">PON EDFA</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-orange-600">Mininodos y Antenas</a>
                    </div>
                </div>

                <div x-data="{ open: false }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-gray-100 transition-all">
                        <i class="ri-settings-5-line text-lg text-gray-500"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Servicios / Tareas</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-1 ml-4 border-l-2 border-gray-200 uppercase text-[10px] font-bold text-gray-500">
                        <a href="#" class="block px-4 py-1.5 hover:text-indigo-600">Registro de Servicios</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-indigo-600">Matriz de Actividades</a>
                    </div>
                </div>

                <div class="my-4 border-t border-gray-100 mx-2"></div>

                <div x-data="{ open: false }">
                    <button @click="open = !open"
                            class="nav-item w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-xs font-bold text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 transition-all">
                        <i class="ri-flashlight-line text-lg text-yellow-500"></i>
                        <span class="hide-collapsed flex-1 text-left uppercase tracking-tighter">Energía y Enlaces</span>
                        <i class="hide-collapsed ri-arrow-down-s-line transition-transform duration-200" :class="open && 'rotate-180'"></i>
                    </button>
                    <div x-show="open" x-cloak class="mt-1 space-y-1 ml-4 border-l-2 border-yellow-100 uppercase text-[10px] font-bold text-gray-500">
                        <a href="#" class="block px-4 py-1.5 hover:text-yellow-600">Enlaces Fibra</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-yellow-600">Catálogo CTC</a>
                        <a href="#" class="block px-4 py-1.5 hover:text-yellow-600">UPS / Plantas</a>
                    </div>
                </div>

            </nav>

            {{-- Footer Usuario (Mantenido de tu vista original) --}}
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