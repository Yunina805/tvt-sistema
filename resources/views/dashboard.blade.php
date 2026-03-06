<x-app-layout>
<div x-data="{ activeTab: 'gestion_clientes' }" class="w-full space-y-0">

    {{-- ================================================================
         HEADER: Título + KPIs rápidos
    ================================================================ --}}
    <div class="mb-5 flex flex-col md:flex-row md:items-center justify-between gap-4 px-1">
        <div>
        <p class="text-xs font-semibold uppercase tracking-widest text-indigo-500 mb-0.5">Tu Visión Telecable</p>
        <h2 class="text-2xl font-black text-gray-900 tracking-tight uppercase">Centro de Operaciones</h2>
        <p class="text-sm text-gray-400 font-medium mt-0.5 capitalize">
            <i class="ri-calendar-line mr-1"></i>{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
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
     TABS DE NAVEGACIÓN (Diseño Píldora Dinámico)
    ================================================================ --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-1.5 mb-6 overflow-x-auto" style="scrollbar-width:none">
        <nav class="flex gap-1.5 min-w-max">
            @php
                // Se definen las clases exactas de Tailwind para que el compilador las detecte
                $tabs = [
                    ['id' => 'gestion_clientes', 'icon' => 'ri-group-line',               'label' => 'Gestión al Cliente', 'activeClasses' => 'bg-indigo-50 text-indigo-700 border-indigo-200 ring-1 ring-indigo-100 shadow-sm'],
                    ['id' => 'planta_externa',   'icon' => 'ri-node-tree',                'label' => 'Planta Externa',     'activeClasses' => 'bg-pink-50 text-pink-700 border-pink-200 ring-1 ring-pink-100 shadow-sm'],
                    ['id' => 'planta_interna',   'icon' => 'ri-router-line',              'label' => 'Planta Interna',     'activeClasses' => 'bg-violet-50 text-violet-700 border-violet-200 ring-1 ring-violet-100 shadow-sm'],
                    ['id' => 'financieros',      'icon' => 'ri-money-dollar-circle-line', 'label' => 'Financieros',        'activeClasses' => 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-1 ring-emerald-100 shadow-sm'],
                    ['id' => 'administrativos',  'icon' => 'ri-briefcase-line',           'label' => 'Administrativos',    'activeClasses' => 'bg-blue-50 text-blue-700 border-blue-200 ring-1 ring-blue-100 shadow-sm'],
                    ['id' => 'regulatorios',     'icon' => 'ri-shield-check-line',        'label' => 'Regulatorios',       'activeClasses' => 'bg-slate-50 text-slate-700 border-slate-200 ring-1 ring-slate-100 shadow-sm'],
                ];
            @endphp

            @foreach ($tabs as $tab)
                <button
                    @click="activeTab = '{{ $tab['id'] }}'"
                    :class="activeTab === '{{ $tab['id'] }}'
                        ? '{{ $tab['activeClasses'] }}'
                        : 'border-transparent text-gray-500 hover:text-gray-800 hover:bg-gray-100/60 opacity-80 hover:opacity-100'"
                    class="flex items-center justify-center gap-2 px-5 py-3 rounded-xl border border-transparent font-black text-[10px] uppercase tracking-[0.15em] transition-all duration-300 outline-none">
                    <i class="{{ $tab['icon'] }} text-base transition-transform duration-300" :class="activeTab === '{{ $tab['id'] }}' ? 'scale-110' : ''"></i>
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

        <div class="mb-6 pb-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-black text-gray-900 uppercase tracking-tight">Gestión al Cliente</h3>
                <p class="text-xs text-gray-400 mt-0.5">Operación directa con suscriptores</p>
            </div>
        </div>

        {{-- Grid de acciones agrupadas --}}
        <div class="space-y-8">

            {{-- Grupo: Altas --}}
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400"></span> Altas y Contratos
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">

                    <a href="{{ route('contrataciones.nuevas') }}"
                       class="group relative flex flex-col justify-between p-5 bg-indigo-50 border border-indigo-100 rounded-2xl hover:bg-indigo-100 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-100/50 hover:-translate-y-1 transition-all duration-300 min-h-[120px]">
                        <div class="flex justify-between items-start">
                            <i class="ri-user-add-line text-2xl text-indigo-600 transition-colors"></i>
                            <i class="ri-arrow-right-up-line text-indigo-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        </div>
                        <div class="mt-4">
                            <span class="block text-xs font-black text-indigo-950 uppercase tracking-tight transition-colors">Contratación<br>Nueva</span>
                        </div>
                    </a>

                    <a href="{{ route('servicios.adicionales') }}"
                       class="group relative flex flex-col justify-between p-5 bg-indigo-50 border border-indigo-100 rounded-2xl hover:bg-indigo-100 hover:border-indigo-200 hover:shadow-lg hover:shadow-indigo-100/50 hover:-translate-y-1 transition-all duration-300 min-h-[120px]">
                        <div class="flex justify-between items-start">
                            <i class="ri-add-box-line text-2xl text-indigo-500 transition-colors"></i>
                            <i class="ri-arrow-right-up-line text-indigo-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        </div>
                        <div class="mt-4">
                            <span class="block text-xs font-black text-indigo-950 uppercase tracking-tight transition-colors">Servicios<br>Adicionales</span>
                        </div>
                    </a>

                    <a href="{{ route('contratacion.promocion') }}"
                       class="group relative flex flex-col justify-between p-5 bg-orange-50 border border-orange-100 rounded-2xl hover:bg-orange-100 hover:border-orange-200 hover:shadow-lg hover:shadow-orange-100/50 hover:-translate-y-1 transition-all duration-300 min-h-[120px]">
                        <div class="flex justify-between items-start">
                            <i class="ri-price-tag-3-line text-2xl text-orange-500 transition-colors"></i>
                            <i class="ri-arrow-right-up-line text-orange-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        </div>
                        <div class="mt-4">
                            <span class="block text-xs font-black text-orange-950 uppercase tracking-tight transition-colors">Contrat.<br>Promociones</span>
                        </div>
                    </a>

                </div>
            </div>

            {{-- Grupo: Cobros --}}
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Cobros y Cuentas
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">

                    <a href="{{ route('pago.mensualidad') }}"
                       class="group relative flex flex-col justify-between p-5 bg-emerald-50 border border-emerald-100 rounded-2xl hover:bg-emerald-100 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-100/50 hover:-translate-y-1 transition-all duration-300 min-h-[120px]">
                        <div class="flex justify-between items-start">
                            <i class="ri-money-dollar-circle-line text-2xl text-emerald-600 transition-colors"></i>
                            <i class="ri-arrow-right-up-line text-emerald-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        </div>
                        <div class="mt-4">
                            <span class="block text-xs font-black text-emerald-950 uppercase tracking-tight transition-colors">Pago<br>Mensualidad</span>
                        </div>
                    </a>

                    <a href="{{ route('estado.cuenta') }}"
                       class="group relative flex flex-col justify-between p-5 bg-emerald-50 border border-emerald-100 rounded-2xl hover:bg-emerald-100 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-100/50 hover:-translate-y-1 transition-all duration-300 min-h-[120px]">
                        <div class="flex justify-between items-start">
                            <i class="ri-file-list-3-line text-2xl text-emerald-500 transition-colors"></i>
                            <i class="ri-arrow-right-up-line text-emerald-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        </div>
                        <div class="mt-4">
                            <span class="block text-xs font-black text-emerald-950 uppercase tracking-tight transition-colors">Estado<br>de Cuenta</span>
                        </div>
                    </a>

                </div>
            </div>

            {{-- Grupo: Operativa --}}
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-sky-400"></span> Operativa de Estatus
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">

                    <a href="{{ route('reconexion.cliente') }}"
                       class="group relative flex flex-col justify-between p-5 bg-sky-50 border border-sky-100 rounded-2xl hover:bg-sky-100 hover:border-sky-200 hover:shadow-lg hover:shadow-sky-100/50 hover:-translate-y-1 transition-all duration-300 min-h-[120px]">
                        <div class="flex justify-between items-start">
                            <i class="ri-plug-line text-2xl text-sky-500 transition-colors"></i>
                            <i class="ri-arrow-right-up-line text-sky-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        </div>
                        <div class="mt-4">
                            <span class="block text-xs font-black text-sky-950 uppercase tracking-tight transition-colors">Reconexión<br>de Cliente</span>
                        </div>
                    </a>

                    <a href="{{ route('suspension.clientes') }}"
                       class="group relative flex flex-col justify-between p-5 bg-amber-50 border border-amber-100 rounded-2xl hover:bg-amber-100 hover:border-amber-200 hover:shadow-lg hover:shadow-amber-100/50 hover:-translate-y-1 transition-all duration-300 min-h-[120px]">
                        <div class="flex justify-between items-start">
                            <i class="ri-pause-circle-line text-2xl text-amber-500 transition-colors"></i>
                            <i class="ri-arrow-right-up-line text-amber-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        </div>
                        <div class="mt-4">
                            <span class="block text-xs font-black text-amber-950 uppercase tracking-tight transition-colors">Suspensión<br>por Adeudo</span>
                        </div>
                    </a>

                    <a href="{{ route('cancelacion.servicio') }}"
                       class="group relative flex flex-col justify-between p-5 bg-red-50 border border-red-100 rounded-2xl hover:bg-red-100 hover:border-red-200 hover:shadow-lg hover:shadow-red-100/50 hover:-translate-y-1 transition-all duration-300 min-h-[120px]">
                        <div class="flex justify-between items-start">
                            <i class="ri-close-circle-line text-2xl text-red-500 transition-colors"></i>
                            <i class="ri-arrow-right-up-line text-red-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        </div>
                        <div class="mt-4">
                            <span class="block text-xs font-black text-red-950 uppercase tracking-tight transition-colors">Cancelación<br>de Servicio</span>
                        </div>
                    </a>

                    <a href="{{ route('recuperacion.equipos') }}"
                       class="group relative flex flex-col justify-between p-5 bg-red-50 border border-red-100 rounded-2xl hover:bg-red-100 hover:border-red-200 hover:shadow-lg hover:shadow-red-100/50 hover:-translate-y-1 transition-all duration-300 min-h-[120px]">
                        <div class="flex justify-between items-start">
                            <i class="ri-router-line text-2xl text-red-400 transition-colors"></i>
                            <i class="ri-arrow-right-up-line text-red-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        </div>
                        <div class="mt-4">
                            <span class="block text-xs font-black text-red-950 uppercase tracking-tight transition-colors">Recuperación<br>de Equipo</span>
                        </div>
                    </a>

                </div>
            </div>

            {{-- Grupo: Reportes --}}
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                    <span class="w-1.5 h-1.5 rounded-full bg-violet-400"></span> Reportes Técnicos
                </p>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">

                    <a href="{{ route('reportes.servicio') }}"
                       class="group relative flex flex-col justify-between p-5 bg-violet-50 border border-violet-100 rounded-2xl hover:bg-violet-100 hover:border-violet-200 hover:shadow-lg hover:shadow-violet-100/50 hover:-translate-y-1 transition-all duration-300 min-h-[120px]">
                        <div class="flex justify-between items-start">
                            <i class="ri-tools-line text-2xl text-violet-600 transition-colors"></i>
                            <i class="ri-arrow-right-up-line text-violet-400 opacity-0 group-hover:opacity-100 transition-all transform group-hover:translate-x-1 group-hover:-translate-y-1"></i>
                        </div>
                        <div class="mt-4">
                            <span class="block text-xs font-black text-violet-950 uppercase tracking-tight transition-colors">Reportes<br>de Servicio</span>
                        </div>
                    </a>

                </div>
            </div>

        </div>
    </div>
</div>
</x-app-layout>