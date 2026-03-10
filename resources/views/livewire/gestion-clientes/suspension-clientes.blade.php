
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================ ENCABEZADO ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-red-600">Suspensión por Adeudo</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Clientes con Adeudo <span class="text-red-600">+31 Días</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Vista automática nocturna · Listado de cortes pendientes por falta de pago</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>


    {{-- ================================================================ LEYENDA DE TIPOS DE SUSPENSIÓN ================================================================ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
        {{-- TV: siempre físico --}}
        <div class="bg-violet-50 border border-violet-200 rounded-xl p-4 flex items-start gap-3">
            <div class="w-8 h-8 bg-violet-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="ri-tv-2-line text-violet-600 text-sm"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-violet-800 uppercase tracking-widest">Servicio de TV</p>
                <p class="text-[10px] text-violet-600 font-medium mt-0.5">
                    <i class="ri-tools-line"></i> Siempre requiere <strong>técnico en campo</strong>. Desconexión física en la NAP.
                </p>
            </div>
        </div>
        {{-- Internet: siempre remoto --}}
        <div class="bg-sky-50 border border-sky-200 rounded-xl p-4 flex items-start gap-3">
            <div class="w-8 h-8 bg-sky-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="ri-wifi-off-line text-sky-600 text-sm"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-sky-800 uppercase tracking-widest">Internet</p>
                <p class="text-[10px] text-sky-600 font-medium mt-0.5">
                    <i class="ri-computer-line"></i> <strong>Corte lógico</strong> desde sucursal. Winbox + OLT. Sin técnico.
                </p>
            </div>
        </div>
        {{-- TV+Internet: depende de ONU --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-start gap-3">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="ri-router-line text-blue-600 text-sm"></i>
            </div>
            <div>
                <p class="text-[10px] font-black text-blue-800 uppercase tracking-widest">TV + Internet</p>
                <p class="text-[10px] text-blue-600 font-medium mt-0.5">
                    <i class="ri-question-line"></i> Depende de la ONU:
                    <span class="text-emerald-600 font-black">Con remoto</span> → lógico.
                    <span class="text-red-600 font-black">Sin remoto</span> → físico.
                </p>
            </div>
        </div>
    </div>

    {{-- ================================================================ KPI CARDS ================================================================ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        @php
            $kpis = [
                ['label' => 'Total Deudores',  'value' => $totalDeudores,                   'icon' => 'ri-user-forbid-line',        'bg' => 'bg-red-100',    'tc' => 'text-red-600'],
                ['label' => 'Monto Total',      'value' => '$'.number_format($montoTotal,2), 'icon' => 'ri-money-dollar-circle-line','bg' => 'bg-red-100',    'tc' => 'text-red-600'],
                ['label' => 'Suspendidos Hoy', 'value' => $suspendidosHoy,                  'icon' => 'ri-pause-circle-line',       'bg' => 'bg-amber-100',  'tc' => 'text-amber-600'],
                ['label' => 'Pendientes',       'value' => $pendientesSuspension,            'icon' => 'ri-time-line',               'bg' => 'bg-indigo-100', 'tc' => 'text-indigo-600'],
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
            <i class="ri-filter-3-line text-red-500 text-sm"></i>
            <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Filtros</p>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">

            <div class="xl:col-span-2 space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Buscar cliente</label>
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Nombre, ID o teléfono..."
                           class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-colors placeholder:text-gray-300">
                </div>
            </div>

            <div class="space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Sucursal</label>
                <select wire:model.live="filterSucursal"
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
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
                            class="flex-1 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
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
                        <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest"># Suscriptor · Nombre · Sucursal</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Servicio · Equipo · NAP</th>
                        <th class="px-4 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Saldo Actual</th>
                        <th class="px-4 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Últ. Pago · Días Mora · Estatus</th>
                        <th class="px-4 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Tipo de Corte</th>
                        <th class="px-4 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Generar Reporte</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($filtrados as $cliente)
                    @php
                        $dias = Carbon\Carbon::parse($cliente['ultimo_pago'])->diffInDays(now());

                        if ($cliente['tipo_servicio'] === 'TV') {
                            $tipoCorte = 'fisico';
                        } elseif ($cliente['tipo_servicio'] === 'INTERNET') {
                            $tipoCorte = 'logico';
                        } else {
                            $tipoCorte = $cliente['soporta_remoto'] ? 'logico' : 'fisico';
                        }
                    @endphp
                    <tr class="hover:bg-red-50/20 transition-colors">

                        {{-- # Suscriptor · Nombre · Sucursal --}}
                        <td class="px-4 py-3">
                            <span class="font-mono text-[10px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md">{{ $cliente['id'] }}</span>
                            <p class="text-xs font-black text-gray-800 uppercase tracking-tight mt-1 leading-tight">{{ $cliente['nombre'] }}</p>
                            <p class="flex items-center gap-1 text-[9px] font-bold text-gray-400 uppercase mt-0.5">
                                <i class="ri-map-pin-2-line text-indigo-300 text-[10px]"></i> {{ $cliente['sucursal'] }}
                            </p>
                        </td>

                        {{-- Servicio · Equipo · NAP --}}
                        <td class="px-4 py-3">
                            <p class="text-[10px] font-black text-gray-800 uppercase">{{ $cliente['servicio'] }}</p>
                            <p class="font-mono text-[9px] text-gray-400 font-bold mt-0.5">{{ $cliente['equipo'] }}</p>
                            <div class="flex items-center gap-1 mt-0.5">
                                <i class="ri-signal-tower-line text-indigo-400 text-[9px]"></i>
                                <span class="text-[9px] font-bold text-indigo-600 uppercase">{{ $cliente['nap'] }}</span>
                                <span class="text-[9px] text-gray-400 hidden xl:inline">— {{ $cliente['dir_nap'] }}</span>
                            </div>
                        </td>

                        {{-- Saldo Actual --}}
                        <td class="px-4 py-3 whitespace-nowrap text-right">
                            <p class="text-base font-black text-red-600 tracking-tight">${{ number_format($cliente['saldo'], 2) }}</p>
                        </td>

                        {{-- Últ. Pago · Días Mora · Estatus --}}
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <span class="inline-flex items-center gap-1 text-[10px] font-black text-red-600 bg-red-50 border border-red-100 px-2 py-0.5 rounded-md">
                                <i class="ri-alarm-warning-line"></i> {{ $dias }} días
                            </span>
                            <p class="text-[9px] text-gray-400 font-bold mt-1">{{ Carbon\Carbon::parse($cliente['ultimo_pago'])->format('d/m/Y') }}</p>
                            <div class="flex items-center justify-center gap-1 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                <span class="text-[9px] font-black text-amber-700 uppercase tracking-widest">{{ $cliente['estatus'] }}</span>
                            </div>
                        </td>

                        {{-- Tipo de Corte --}}
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            @if($tipoCorte === 'fisico')
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 text-red-700 border border-red-200 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                <i class="ri-tools-line"></i> Físico
                            </span>
                            <p class="text-[9px] text-gray-400 font-bold mt-1">Técnico en campo</p>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-sky-100 text-sky-700 border border-sky-200 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                <i class="ri-computer-line"></i> Lógico
                            </span>
                            <p class="text-[9px] text-gray-400 font-bold mt-1">Winbox + OLT</p>
                            @endif
                        </td>

                        {{-- Generar Reporte --}}
                        <td class="px-4 py-3 whitespace-nowrap text-center">
                            <button @click="$confirm(
                                        '¿Generar reporte de suspensión para {{ addslashes($cliente['nombre']) }}? Se enviará SMS al cliente{{ $tipoCorte === 'fisico' ? ' y al técnico asignado' : '' }}.',
                                        () => $wire.generarReporteSuspension('{{ $cliente['id'] }}'),
                                        { confirmText: 'Sí, generar reporte', title: 'Generar Reporte de Suspensión', icon: 'warning' }
                                    )"
                                    class="inline-flex items-center gap-1.5 px-3 py-2 bg-red-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm shadow-red-200 hover:bg-red-700 transition-all active:scale-95">
                                <i class="ri-file-warning-line"></i> Generar
                            </button>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-12 h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                    <i class="ri-checkbox-circle-line text-xl text-emerald-500"></i>
                                </div>
                                <p class="text-xs font-black text-gray-300 uppercase tracking-widest">No hay suscriptores con adeudos críticos mayores a 31 días</p>
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