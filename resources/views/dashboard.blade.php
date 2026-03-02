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
</div>
</x-app-layout>