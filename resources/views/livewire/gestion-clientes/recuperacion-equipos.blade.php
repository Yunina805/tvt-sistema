<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================ ENCABEZADO ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-amber-600">Recuperación de Equipos</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Recuperación de Equipos — Adeudo <span class="text-amber-600">+61 Días</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Vista automática nocturna · La sucursal genera los reportes del día</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>


    {{-- Aviso automático nocturno --}}
    <div class="mb-5 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-start gap-3">
        <i class="ri-time-line text-amber-500 text-xl flex-shrink-0 mt-0.5"></i>
        <div>
            <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Vista actualizada automáticamente cada noche</p>
            <p class="text-[10px] text-amber-600 font-medium mt-0.5">
                Solo muestra cuentas con adeudo superior a 61 días desde su último corte. Al generar el reporte se notifica por SMS al cliente y al técnico asignado.
            </p>
        </div>
    </div>

    {{-- ================================================================ KPI CARDS ================================================================ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        @php
            $kpis = [
                ['label' => 'Total Deudores',  'value' => $totalDeudores,                              'icon' => 'ri-user-forbid-line',        'bg' => 'bg-amber-100',  'tc' => 'text-amber-600'],
                ['label' => 'Monto Total',     'value' => '$'.number_format($montoTotal, 2),            'icon' => 'ri-money-dollar-circle-line', 'bg' => 'bg-red-100',    'tc' => 'text-red-600'],
                ['label' => 'Con Internet',    'value' => $conInternet,                                 'icon' => 'ri-router-line',              'bg' => 'bg-sky-100',    'tc' => 'text-sky-600'],
                ['label' => 'Solo TV',         'value' => $soloTV,                                      'icon' => 'ri-tv-2-line',               'bg' => 'bg-violet-100', 'tc' => 'text-violet-600'],
            ];
        @endphp
        @foreach($kpis as $k)
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 {{ $k['bg'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="{{ $k['icon'] }} {{ $k['tc'] }} text-lg"></i>
            </div>
            <div>
                <p class="text-lg font-black text-gray-900 leading-none">{{ $k['value'] }}</p>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $k['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ================================================================ FILTROS ================================================================ --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-5 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
            <i class="ri-filter-3-line text-amber-500 text-sm"></i>
            <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Filtros</p>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
            <div class="xl:col-span-2 space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Buscar cliente</label>
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Nombre, ID o teléfono..."
                           class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 transition-colors placeholder:text-gray-300">
                </div>
            </div>
            <div class="space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Sucursal</label>
                <select wire:model.live="filterSucursal"
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400">
                    <option value="">Todas</option>
                    <option value="Oaxaca Centro">Oaxaca Centro</option>
                    <option value="San Pedro Amuzgos">San Pedro Amuzgos</option>
                    <option value="Oaxaca Norte">Oaxaca Norte</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipo de Servicio</label>
                <div class="flex gap-2">
                    <select wire:model.live="filterServicio"
                            class="flex-1 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400">
                        <option value="">Todos</option>
                        <option value="TV">Solo TV</option>
                        <option value="INTERNET">Solo Internet</option>
                        <option value="TV+INTERNET">TV + Internet</option>
                    </select>
                    <button wire:click="resetFilters"
                            class="px-3 bg-white border border-gray-200 text-gray-400 rounded-lg hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all text-xs font-black" title="Limpiar filtros">
                        <i class="ri-filter-off-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ================================================================ TABLA ================================================================ --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest"># ID</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Titular / Sucursal</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Servicio / Equipo</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Saldo Total</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Último Pago</th>
                        <th class="px-5 py-3.5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($filtrados as $cliente)
                    @php
                        $dias = \Carbon\Carbon::parse($cliente['ultimo_pago'])->diffInDays(now());
                    @endphp
                    <tr class="hover:bg-amber-50/20 transition-colors group">

                        {{-- ID --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <span class="font-mono text-xs font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-lg">{{ $cliente['id'] }}</span>
                        </td>

                        {{-- Titular --}}
                        <td class="px-5 py-4">
                            <p class="text-sm font-black text-gray-800 uppercase tracking-tight">{{ $cliente['nombre'] }}</p>
                            <p class="flex items-center gap-1 text-[10px] font-bold text-gray-400 mt-0.5 uppercase">
                                <i class="ri-map-pin-2-line text-indigo-400 text-xs"></i> {{ $cliente['sucursal'] }}
                            </p>
                        </td>

                        {{-- Servicio + Equipo --}}
                        <td class="px-5 py-4">
                            <p class="text-xs font-black text-gray-700 uppercase">{{ $cliente['servicio'] }}</p>
                            <div class="mt-1 flex flex-wrap items-center gap-1.5">
                                {{-- badge tipo servicio --}}
                                @if($cliente['tipo_servicio'] === 'TV')
                                <span class="text-[9px] font-black text-violet-700 bg-violet-50 border border-violet-100 px-1.5 py-0.5 rounded-md uppercase">
                                    <i class="ri-tv-2-line"></i> TV
                                </span>
                                @elseif($cliente['tipo_servicio'] === 'INTERNET')
                                <span class="text-[9px] font-black text-sky-700 bg-sky-50 border border-sky-100 px-1.5 py-0.5 rounded-md uppercase">
                                    <i class="ri-wifi-line"></i> Internet
                                </span>
                                @else
                                <span class="text-[9px] font-black text-blue-700 bg-blue-50 border border-blue-100 px-1.5 py-0.5 rounded-md uppercase">
                                    <i class="ri-router-line"></i> TV+Internet
                                </span>
                                @endif
                                {{-- estado --}}
                                <span class="flex items-center gap-1 text-[9px] font-black text-red-700 bg-red-50 border border-red-100 px-1.5 py-0.5 rounded-md uppercase">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                    {{ $cliente['estatus'] }}
                                </span>
                            </div>
                            <p class="font-mono text-[9px] text-gray-400 font-bold mt-1">
                                {{ $cliente['equipo'] }} · {{ $cliente['serie_equipo'] }}
                            </p>
                        </td>

                        {{-- Saldo --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <p class="text-xl font-black text-red-600 tracking-tight">${{ number_format($cliente['saldo'], 2) }}</p>
                            <span class="inline-flex items-center gap-1 text-[9px] font-black text-amber-700 bg-amber-50 border border-amber-200 px-1.5 py-0.5 rounded-md mt-1">
                                <i class="ri-alarm-warning-line"></i> {{ $dias }} días sin pago
                            </span>
                        </td>

                        {{-- Último pago --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <p class="text-xs font-bold text-gray-600">{{ \Carbon\Carbon::parse($cliente['ultimo_pago'])->format('d/m/Y') }}</p>
                        </td>

                        {{-- Acción --}}
                        <td class="px-5 py-4 whitespace-nowrap text-right">
                            <button @click="$confirm('¿Recuperar equipo de {{ $cliente['nombre'] }}? Se notificará al cliente y al técnico asignado.', () => $wire.generarReporteRecuperacion('{{ $cliente['id'] }}'), { confirmText: 'Sí, recuperar' })"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-amber-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm shadow-amber-200 hover:bg-amber-700 transition-all active:scale-95">
                                <i class="ri-arrow-down-circle-line text-sm"></i> Recuperar Equipo
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                    <i class="ri-checkbox-circle-line text-2xl text-emerald-500"></i>
                                </div>
                                <p class="text-xs font-black text-gray-300 uppercase tracking-widest">No hay clientes con adeudos críticos mayores a 61 días</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Nota legal --}}
    <p class="mt-4 text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest">
        ⚠ Nota: Los periodos suspendidos no generan cargo en el corte mensual del cliente.
    </p>

</div>