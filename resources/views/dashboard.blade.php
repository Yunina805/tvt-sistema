<x-app-layout>
<div x-data="{ activeTab: 'gestion_clientes' }" class="w-full space-y-0">

    {{-- ================================================================
         HEADER: Título + KPIs rápidos
    ================================================================ --}}
    <div class="mb-5 flex flex-col md:flex-row md:items-center justify-between gap-4 px-1">
        <div>
            <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 mb-0.5">Tu Visión Telecable</p>
            <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Centro de Operaciones</h2>
            <p class="text-sm text-gray-400 font-medium mt-0.5">
                <i class="ri-calendar-line mr-1"></i>{{ now()->format('l, d \d\e F \d\e Y') }}
            </p>
        </div>

        {{-- KPI strip --}}
        <div class="flex items-center gap-3 flex-wrap">
            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i class="ri-user-heart-line text-emerald-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 leading-none">Activos</p>
                    <p class="text-lg font-black text-gray-900 leading-tight">—</p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm">
                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                    <i class="ri-time-line text-amber-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 leading-none">Reportes Abiertos</p>
                    <p class="text-lg font-black text-gray-900 leading-tight">—</p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm">
                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="ri-error-warning-line text-red-500 text-base"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 leading-none">Con Adeudo</p>
                    <p class="text-lg font-black text-gray-900 leading-tight">—</p>
                </div>
            </div>
            <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-xl px-4 py-2.5 shadow-sm">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <i class="ri-coins-line text-indigo-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-wider text-gray-400 leading-none">Cobros Hoy</p>
                    <p class="text-lg font-black text-gray-900 leading-tight">—</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================
         TABS
    ================================================================ --}}
    <div class="bg-white border border-gray-200 rounded-t-2xl shadow-sm overflow-x-auto" style="scrollbar-width:none">
        <nav class="flex -mb-px px-4 min-w-max">
            @php
                $tabs = [
                    ['id' => 'gestion_clientes', 'icon' => 'ri-group-line',              'label' => 'Gestión al Cliente', 'color' => 'indigo'],
                    ['id' => 'planta_externa',   'icon' => 'ri-node-tree',               'label' => 'Planta Externa',     'color' => 'pink'],
                    ['id' => 'planta_interna',   'icon' => 'ri-router-line',             'label' => 'Planta Interna',     'color' => 'violet'],
                    ['id' => 'financieros',      'icon' => 'ri-money-dollar-circle-line','label' => 'Financieros',        'color' => 'emerald'],
                    ['id' => 'administrativos',  'icon' => 'ri-briefcase-line',          'label' => 'Administrativos',    'color' => 'blue'],
                    ['id' => 'regulatorios',     'icon' => 'ri-shield-check-line',       'label' => 'Regulatorios',       'color' => 'slate'],
                ];
            @endphp

            @foreach ($tabs as $tab)
                <button
                    @click="activeTab = '{{ $tab['id'] }}'"
                    :class="activeTab === '{{ $tab['id'] }}'
                        ? 'border-indigo-500 text-indigo-600 bg-indigo-50/60'
                        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                    class="relative whitespace-nowrap py-4 px-5 border-b-2 font-bold text-[11px] flex items-center gap-2 transition-all duration-150 uppercase tracking-widest outline-none">
                    <i class="{{ $tab['icon'] }} text-base"></i>
                    {{ $tab['label'] }}
                </button>
            @endforeach
        </nav>
    </div>

    {{-- ================================================================
         TAB CONTENT
    ================================================================ --}}
    <div class="bg-white border border-t-0 border-gray-200 rounded-b-2xl shadow-sm p-6 min-h-[520px]">

        {{-- ── GESTIÓN AL CLIENTE ──────────────────────────────────── --}}
        <div x-show="activeTab === 'gestion_clientes'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="mb-5 pb-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Gestión al Cliente</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Operación directa con suscriptores</p>
                </div>
            </div>

            {{-- Grid de acciones agrupadas --}}
            <div class="space-y-6">

                {{-- Grupo: Altas --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Altas y Contratos
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">

                        <a href="{{ route('contrataciones.nuevas') }}"
                           class="group flex flex-col items-center justify-center gap-3 p-5 bg-indigo-50 border border-indigo-100 rounded-xl hover:bg-indigo-100 hover:border-indigo-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                            <div class="w-11 h-11 rounded-xl bg-indigo-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="ri-user-add-line text-white text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold text-indigo-800 uppercase tracking-tight leading-tight">Contratación<br>Nueva</span>
                        </a>

                        <a href="{{ route('servicios.adicionales') }}"
                           class="group flex flex-col items-center justify-center gap-3 p-5 bg-indigo-50 border border-indigo-100 rounded-xl hover:bg-indigo-100 hover:border-indigo-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                            <div class="w-11 h-11 rounded-xl bg-indigo-500 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="ri-add-box-line text-white text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold text-indigo-800 uppercase tracking-tight leading-tight">Servicios<br>Adicionales</span>
                        </a>

                        <a href="{{ route('contratacion.promocion') }}"
                           class="group flex flex-col items-center justify-center gap-3 p-5 bg-orange-50 border border-orange-100 rounded-xl hover:bg-orange-100 hover:border-orange-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                            <div class="w-11 h-11 rounded-xl bg-orange-500 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="ri-price-tag-3-line text-white text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold text-orange-800 uppercase tracking-tight leading-tight">Contrat.<br>Promociones</span>
                        </a>

                    </div>
                </div>

                {{-- Grupo: Cobros --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Cobros y Cuentas
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">

                        <a href="{{ route('pago.mensualidad') }}"
                           class="group flex flex-col items-center justify-center gap-3 p-5 bg-emerald-50 border border-emerald-100 rounded-xl hover:bg-emerald-100 hover:border-emerald-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                            <div class="w-11 h-11 rounded-xl bg-emerald-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="ri-money-dollar-circle-line text-white text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-tight leading-tight">Pago<br>Mensualidad</span>
                        </a>

                        <a href="{{ route('estado.cuenta') }}"
                           class="group flex flex-col items-center justify-center gap-3 p-5 bg-emerald-50 border border-emerald-100 rounded-xl hover:bg-emerald-100 hover:border-emerald-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                            <div class="w-11 h-11 rounded-xl bg-emerald-500 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="ri-file-list-3-line text-white text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold text-emerald-800 uppercase tracking-tight leading-tight">Estado<br>de Cuenta</span>
                        </a>

                    </div>
                </div>

                {{-- Grupo: Operativa --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Operativa de Estatus
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">

                        <a href="{{ route('reconexion.cliente') }}"
                           class="group flex flex-col items-center justify-center gap-3 p-5 bg-sky-50 border border-sky-100 rounded-xl hover:bg-sky-100 hover:border-sky-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                            <div class="w-11 h-11 rounded-xl bg-sky-500 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="ri-plug-line text-white text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold text-sky-800 uppercase tracking-tight leading-tight">Reconexión<br>de Cliente</span>
                        </a>

                        <a href="{{ route('suspension.clientes') }}"
                           class="group flex flex-col items-center justify-center gap-3 p-5 bg-amber-50 border border-amber-100 rounded-xl hover:bg-amber-100 hover:border-amber-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                            <div class="w-11 h-11 rounded-xl bg-amber-500 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="ri-pause-circle-line text-white text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold text-amber-800 uppercase tracking-tight leading-tight">Suspensión<br>por Adeudo</span>
                        </a>

                        <a href="{{ route('cancelacion.servicio') }}"
                           class="group flex flex-col items-center justify-center gap-3 p-5 bg-red-50 border border-red-100 rounded-xl hover:bg-red-100 hover:border-red-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                            <div class="w-11 h-11 rounded-xl bg-red-500 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="ri-close-circle-line text-white text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold text-red-800 uppercase tracking-tight leading-tight">Cancelación<br>de Servicio</span>
                        </a>

                        <a href="{{ route('recuperacion.equipos') }}"
                           class="group flex flex-col items-center justify-center gap-3 p-5 bg-red-50 border border-red-100 rounded-xl hover:bg-red-100 hover:border-red-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                            <div class="w-11 h-11 rounded-xl bg-red-400 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="ri-router-line text-white text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold text-red-800 uppercase tracking-tight leading-tight">Recuperación<br>de Equipo</span>
                        </a>

                    </div>
                </div>

                {{-- Grupo: Reportes --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Reportes Técnicos
                    </p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3">

                        <a href="{{ route('reportes.servicio') }}"
                           class="group flex flex-col items-center justify-center gap-3 p-5 bg-violet-50 border border-violet-100 rounded-xl hover:bg-violet-100 hover:border-violet-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                            <div class="w-11 h-11 rounded-xl bg-violet-600 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                <i class="ri-tools-line text-white text-xl"></i>
                            </div>
                            <span class="text-[11px] font-bold text-violet-800 uppercase tracking-tight leading-tight">Reportes<br>de Servicio</span>
                        </a>

                    </div>
                </div>

            </div>
        </div>

        {{-- ── PLANTA EXTERNA ──────────────────────────────────────── --}}
        <div x-show="activeTab === 'planta_externa'" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="mb-5 pb-4 border-b border-gray-100">
                <h3 class="text-base font-black text-pink-600 uppercase tracking-tight">Planta Externa</h3>
                <p class="text-xs text-gray-400 mt-0.5">Infraestructura física en campo</p>
            </div>

            @php
                $plantaExterna = [
                    ['icon' => 'ri-store-2-line',       'label' => 'Sucursales',        'color' => 'bg-pink-600'],
                    ['icon' => 'ri-node-tree',           'label' => 'NAPs',              'color' => 'bg-pink-500'],
                    ['icon' => 'ri-map-2-line',          'label' => 'Calles',            'color' => 'bg-pink-400'],
                    ['icon' => 'ri-signal-tower-line',   'label' => 'Postes',            'color' => 'bg-rose-500'],
                    ['icon' => 'ri-share-line',          'label' => 'Enlaces de Fibra',  'color' => 'bg-rose-600'],
                    ['icon' => 'ri-server-line',         'label' => 'OLT Externa',       'color' => 'bg-fuchsia-600'],
                    ['icon' => 'ri-broadcast-line',      'label' => 'PON EDFA Externo',  'color' => 'bg-fuchsia-500'],
                    ['icon' => 'ri-building-2-line',     'label' => 'C.T.C.',            'color' => 'bg-pink-700'],
                ];
            @endphp

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                @foreach($plantaExterna as $item)
                    <a href="#"
                       class="group flex flex-col items-center justify-center gap-3 p-5 bg-pink-50 border border-pink-100 rounded-xl hover:bg-pink-100 hover:border-pink-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                        <div class="w-11 h-11 rounded-xl {{ $item['color'] }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="{{ $item['icon'] }} text-white text-xl"></i>
                        </div>
                        <span class="text-[11px] font-bold text-pink-900 uppercase tracking-tight leading-tight">{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>
        </div>

        {{-- ── PLANTA INTERNA ──────────────────────────────────────── --}}
        <div x-show="activeTab === 'planta_interna'" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="mb-5 pb-4 border-b border-gray-100">
                <h3 class="text-base font-black text-violet-600 uppercase tracking-tight">Planta Interna</h3>
                <p class="text-xs text-gray-400 mt-0.5">Equipos y configuración en planta</p>
            </div>

            <div class="space-y-6">
                {{-- Televisión --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Servicios de Televisión
                    </p>
                    @php
                        $television = [
                            ['icon' => 'ri-tv-2-line',             'label' => 'Mininodos',       'color' => 'bg-violet-600'],
                            ['icon' => 'ri-remote-control-line',   'label' => 'Receptores',      'color' => 'bg-violet-500'],
                            ['icon' => 'ri-live-line',             'label' => 'Canales',         'color' => 'bg-violet-400'],
                            ['icon' => 'ri-settings-4-line',       'label' => 'Moduladores',     'color' => 'bg-purple-600'],
                            ['icon' => 'ri-broadcast-line',        'label' => 'Transmisores',    'color' => 'bg-purple-500'],
                            ['icon' => 'ri-global-line',           'label' => 'Satélites',       'color' => 'bg-purple-400'],
                            ['icon' => 'ri-wifi-line',             'label' => 'Prov. de Señal',  'color' => 'bg-indigo-500'],
                        ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                        @foreach($television as $item)
                            <a href="#" class="group flex flex-col items-center justify-center gap-3 p-5 bg-violet-50 border border-violet-100 rounded-xl hover:bg-violet-100 hover:border-violet-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                                <div class="w-11 h-11 rounded-xl {{ $item['color'] }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="{{ $item['icon'] }} text-white text-xl"></i>
                                </div>
                                <span class="text-[11px] font-bold text-violet-900 uppercase tracking-tight leading-tight">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Internet --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Servicios de Internet
                    </p>
                    @php
                        $internet = [
                            ['icon' => 'ri-router-line',           'label' => 'ONUs',            'color' => 'bg-sky-600'],
                            ['icon' => 'ri-server-line',           'label' => 'OLTs',            'color' => 'bg-sky-500'],
                            ['icon' => 'ri-star-line',             'label' => 'Starlinks',       'color' => 'bg-sky-400'],
                            ['icon' => 'ri-share-circle-line',     'label' => 'CCR / Switches',  'color' => 'bg-blue-600'],
                            ['icon' => 'ri-links-line',            'label' => 'VLANs',           'color' => 'bg-blue-500'],
                            ['icon' => 'ri-terminal-box-line',     'label' => 'Winbox',          'color' => 'bg-blue-400'],
                            ['icon' => 'ri-cloud-line',            'label' => 'ISP / Telmex',    'color' => 'bg-cyan-600'],
                        ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                        @foreach($internet as $item)
                            <a href="#" class="group flex flex-col items-center justify-center gap-3 p-5 bg-sky-50 border border-sky-100 rounded-xl hover:bg-sky-100 hover:border-sky-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                                <div class="w-11 h-11 rounded-xl {{ $item['color'] }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="{{ $item['icon'] }} text-white text-xl"></i>
                                </div>
                                <span class="text-[11px] font-bold text-sky-900 uppercase tracking-tight leading-tight">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Energía --}}
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Servicios de Energía
                    </p>
                    @php
                        $energia = [
                            ['icon' => 'ri-battery-charge-line', 'label' => 'UPS',                  'color' => 'bg-amber-600'],
                            ['icon' => 'ri-flashlight-line',     'label' => 'Plantas de Emergencia','color' => 'bg-amber-500'],
                        ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                        @foreach($energia as $item)
                            <a href="#" class="group flex flex-col items-center justify-center gap-3 p-5 bg-amber-50 border border-amber-100 rounded-xl hover:bg-amber-100 hover:border-amber-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                                <div class="w-11 h-11 rounded-xl {{ $item['color'] }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="{{ $item['icon'] }} text-white text-xl"></i>
                                </div>
                                <span class="text-[11px] font-bold text-amber-900 uppercase tracking-tight leading-tight">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ── FINANCIEROS ─────────────────────────────────────────── --}}
        <div x-show="activeTab === 'financieros'" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="mb-5 pb-4 border-b border-gray-100">
                <h3 class="text-base font-black text-emerald-700 uppercase tracking-tight">Módulo Financiero</h3>
                <p class="text-xs text-gray-400 mt-0.5">Control de caja, ingresos, egresos y cortes</p>
            </div>

            <div class="space-y-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Catálogos
                    </p>
                    @php
                        $financCatalogos = [
                            ['icon' => 'ri-price-tag-3-line',   'label' => 'Tarifas',       'color' => 'bg-emerald-600'],
                            ['icon' => 'ri-service-line',       'label' => 'Servicios',     'color' => 'bg-emerald-500'],
                            ['icon' => 'ri-percent-line',       'label' => 'Promociones',   'color' => 'bg-teal-600'],
                            ['icon' => 'ri-coupon-line',        'label' => 'Descuentos',    'color' => 'bg-teal-500'],
                            ['icon' => 'ri-arrow-up-circle-line','label' => 'Egresos',      'color' => 'bg-red-500'],
                            ['icon' => 'ri-arrow-down-circle-line','label' => 'Ingresos',   'color' => 'bg-emerald-400'],
                            ['icon' => 'ri-truck-line',         'label' => 'Proveedores',   'color' => 'bg-slate-500'],
                            ['icon' => 'ri-bank-card-line',     'label' => 'Bancos',        'color' => 'bg-slate-600'],
                        ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                        @foreach($financCatalogos as $item)
                            <a href="#" class="group flex flex-col items-center justify-center gap-3 p-5 bg-emerald-50 border border-emerald-100 rounded-xl hover:bg-emerald-100 hover:border-emerald-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                                <div class="w-11 h-11 rounded-xl {{ $item['color'] }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="{{ $item['icon'] }} text-white text-xl"></i>
                                </div>
                                <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-tight leading-tight">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Operación por Sucursal
                    </p>
                    @php
                        $financOp = [
                            ['icon' => 'ri-safe-line',          'label' => 'Caja',              'color' => 'bg-emerald-700'],
                            ['icon' => 'ri-bank-line',          'label' => 'Depósitos',         'color' => 'bg-emerald-600'],
                            ['icon' => 'ri-transfer-line',      'label' => 'Traspasos',         'color' => 'bg-cyan-600'],
                            ['icon' => 'ri-scissors-cut-line',  'label' => 'Cortes Mensuales',  'color' => 'bg-indigo-600'],
                            ['icon' => 'ri-file-pdf-line',      'label' => 'Facturación',       'color' => 'bg-red-600'],
                        ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                        @foreach($financOp as $item)
                            <a href="#" class="group flex flex-col items-center justify-center gap-3 p-5 bg-emerald-50 border border-emerald-100 rounded-xl hover:bg-emerald-100 hover:border-emerald-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                                <div class="w-11 h-11 rounded-xl {{ $item['color'] }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="{{ $item['icon'] }} text-white text-xl"></i>
                                </div>
                                <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-tight leading-tight">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ── ADMINISTRATIVOS ─────────────────────────────────────── --}}
        <div x-show="activeTab === 'administrativos'" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="mb-5 pb-4 border-b border-gray-100">
                <h3 class="text-base font-black text-blue-700 uppercase tracking-tight">Administrativos</h3>
                <p class="text-xs text-gray-400 mt-0.5">Recursos humanos, vehículos e inventarios</p>
            </div>

            <div class="space-y-6">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Recursos Humanos
                    </p>
                    @php
                        $rrhh = [
                            ['icon' => 'ri-user-3-line',         'label' => 'Empleados',         'color' => 'bg-blue-600'],
                            ['icon' => 'ri-key-line',            'label' => 'Accesos al Sistema','color' => 'bg-blue-500'],
                            ['icon' => 'ri-calendar-check-line', 'label' => 'Vacaciones',        'color' => 'bg-blue-400'],
                            ['icon' => 'ri-calendar-event-line', 'label' => 'Permisos',          'color' => 'bg-indigo-400'],
                            ['icon' => 'ri-calendar-2-line',     'label' => 'Plan de Trabajo',   'color' => 'bg-indigo-500'],
                        ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                        @foreach($rrhh as $item)
                            <a href="#" class="group flex flex-col items-center justify-center gap-3 p-5 bg-blue-50 border border-blue-100 rounded-xl hover:bg-blue-100 hover:border-blue-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                                <div class="w-11 h-11 rounded-xl {{ $item['color'] }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="{{ $item['icon'] }} text-white text-xl"></i>
                                </div>
                                <span class="text-[11px] font-bold text-blue-900 uppercase tracking-tight leading-tight">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
                        <span class="w-4 h-px bg-gray-300 inline-block"></span> Gastos Operativos
                    </p>
                    @php
                        $gastos = [
                            ['icon' => 'ri-car-line',            'label' => 'Autos',             'color' => 'bg-slate-600'],
                            ['icon' => 'ri-broadcast-line',      'label' => 'Pago de Señales',   'color' => 'bg-slate-500'],
                            ['icon' => 'ri-flashlight-line',     'label' => 'Pago de Luz',       'color' => 'bg-amber-500'],
                            ['icon' => 'ri-tools-line',          'label' => 'Pago Mecánicos',    'color' => 'bg-orange-500'],
                            ['icon' => 'ri-gas-station-line',    'label' => 'Combustible',       'color' => 'bg-orange-600'],
                            ['icon' => 'ri-archive-line',        'label' => 'Inventarios',       'color' => 'bg-slate-700'],
                        ];
                    @endphp
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 xl:grid-cols-5 gap-3">
                        @foreach($gastos as $item)
                            <a href="#" class="group flex flex-col items-center justify-center gap-3 p-5 bg-slate-50 border border-slate-100 rounded-xl hover:bg-slate-100 hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5 transition-all text-center">
                                <div class="w-11 h-11 rounded-xl {{ $item['color'] }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                                    <i class="{{ $item['icon'] }} text-white text-xl"></i>
                                </div>
                                <span class="text-[11px] font-bold text-slate-800 uppercase tracking-tight leading-tight">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ── REGULATORIOS ────────────────────────────────────────── --}}
        <div x-show="activeTab === 'regulatorios'" x-cloak
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="mb-5 pb-4 border-b border-gray-100">
                <h3 class="text-base font-black text-slate-700 uppercase tracking-tight">Regulatorios</h3>
                <p class="text-xs text-gray-400 mt-0.5">Documentos y obligaciones regulatorias</p>
            </div>

            @php
                $regulatorios = [
                    ['icon' => 'ri-government-line',    'label' => 'C.R.T.',                    'color' => 'bg-slate-700', 'desc' => 'Comisión Reguladora'],
                    ['icon' => 'ri-copyright-line',     'label' => 'IMPI',                      'color' => 'bg-slate-600', 'desc' => 'Propiedad Industrial'],
                    ['icon' => 'ri-id-card-line',       'label' => 'RENAPO',                    'color' => 'bg-slate-600', 'desc' => 'Reg. Nacional de Población'],
                    ['icon' => 'ri-scales-line',        'label' => 'PROFECO',                   'color' => 'bg-slate-500', 'desc' => 'Protección al Consumidor'],
                    ['icon' => 'ri-file-text-line',     'label' => 'Términos y Condiciones',    'color' => 'bg-gray-600',  'desc' => 'Contratos y Políticas'],
                    ['icon' => 'ri-pages-line',         'label' => 'Doctos del Footpage',       'color' => 'bg-gray-500',  'desc' => 'Pie de página legal'],
                ];
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                @foreach($regulatorios as $item)
                    <a href="#"
                       class="group flex items-center gap-4 p-4 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 hover:border-slate-300 hover:shadow-md hover:-translate-y-0.5 transition-all">
                        <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $item['color'] }} flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                            <i class="{{ $item['icon'] }} text-white text-xl"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-xs font-black text-slate-800 uppercase tracking-tight">{{ $item['label'] }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ $item['desc'] }}</p>
                        </div>
                        <i class="ri-arrow-right-s-line text-slate-300 group-hover:text-slate-500 ml-auto flex-shrink-0 transition-colors"></i>
                    </a>
                @endforeach
            </div>
        </div>

    </div>{{-- /tab content --}}

</div>
</x-app-layout>