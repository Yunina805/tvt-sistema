
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
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest"># ID</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Titular / Sucursal</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Servicio / Equipo / NAP</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipo de Corte</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Adeudo / Días</th>
                        <th class="px-5 py-3.5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($filtrados as $cliente)
                    @php
                        $dias = Carbon\Carbon::parse($cliente['ultimo_pago'])->diffInDays(now());

                        // Determinar tipo de corte según especificaciones:
                        // TV          → siempre FÍSICO (técnico en campo)
                        // INTERNET    → siempre LÓGICO (Winbox + OLT)
                        // TV+INTERNET → depende de soporta_remoto
                        if ($cliente['tipo_servicio'] === 'TV') {
                            $tipoCorte = 'fisico';
                        } elseif ($cliente['tipo_servicio'] === 'INTERNET') {
                            $tipoCorte = 'logico';
                        } else {
                            // TV+INTERNET
                            $tipoCorte = $cliente['soporta_remoto'] ? 'logico' : 'fisico';
                        }
                    @endphp
                    <tr class="hover:bg-red-50/20 transition-colors group">

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
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                <span class="text-[9px] font-black text-amber-700 uppercase tracking-widest">{{ $cliente['estatus'] }}</span>
                            </div>
                        </td>

                        {{-- Servicio + Equipo + NAP --}}
                        <td class="px-5 py-4">
                            <p class="text-xs font-black text-gray-800 uppercase">{{ $cliente['servicio'] }}</p>
                            <p class="font-mono text-[9px] text-gray-400 font-bold mt-0.5">{{ $cliente['equipo'] }} · {{ $cliente['serie_equipo'] }}</p>
                            <div class="flex items-center gap-1 mt-1">
                                <i class="ri-signal-tower-line text-indigo-400 text-[10px]"></i>
                                <span class="text-[9px] font-bold text-indigo-600 uppercase">{{ $cliente['nap'] }}</span>
                                <span class="text-[9px] text-gray-400 italic">— {{ $cliente['dir_nap'] }}</span>
                            </div>
                        </td>

                        {{-- Tipo de corte —— COLUMNA CLAVE de la especificación --}}
                        <td class="px-5 py-4">
                            @if($tipoCorte === 'fisico')
                            <div class="flex flex-col gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-red-100 text-red-700 border border-red-200 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                    <i class="ri-tools-line text-xs"></i> Físico — Técnico en campo
                                </span>
                                @if($cliente['tipo_servicio'] === 'TV+INTERNET')
                                <span class="text-[9px] text-gray-400 font-bold">ONU sin función remota</span>
                                @else
                                <span class="text-[9px] text-gray-400 font-bold">TV: desconexión en NAP</span>
                                @endif
                                <span class="text-[9px] text-indigo-500 font-bold flex items-center gap-1">
                                    <i class="ri-message-2-line"></i> SMS al técnico al generar
                                </span>
                            </div>
                            @else
                            <div class="flex flex-col gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-sky-100 text-sky-700 border border-sky-200 rounded-lg text-[9px] font-black uppercase tracking-widest">
                                    <i class="ri-computer-line text-xs"></i> Lógico — Sucursal
                                </span>
                                <span class="text-[9px] text-gray-400 font-bold">Winbox + OLT · Sin técnico</span>
                                @if($cliente['tipo_servicio'] === 'TV+INTERNET')
                                <span class="text-[9px] text-emerald-600 font-bold">ONU con función remota ✓</span>
                                @endif
                            </div>
                            @endif
                        </td>

                        {{-- Adeudo --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <p class="text-xl font-black text-red-600 tracking-tight">${{ number_format($cliente['saldo'], 2) }}</p>
                            <span class="inline-flex items-center gap-1 text-[9px] font-black text-red-500 bg-red-50 border border-red-100 px-1.5 py-0.5 rounded-md mt-1">
                                <i class="ri-alarm-warning-line"></i> {{ $dias }} días sin pago
                            </span>
                            <p class="text-[9px] text-gray-400 font-bold mt-1">Últ. pago: {{ Carbon\Carbon::parse($cliente['ultimo_pago'])->format('d/m/Y') }}</p>
                        </td>

                        {{-- Acción --}}
                        <td class="px-5 py-4 whitespace-nowrap text-right">
                            <button @click="$confirm('¿Suspender a {{ $cliente['nombre'] }}? Se generará el reporte y se notificará al cliente.', () => $wire.generarReporteSuspension('{{ $cliente['id'] }}'), { confirmText: 'Sí, suspender' })"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm shadow-red-200 hover:bg-red-700 transition-all active:scale-95 whitespace-nowrap">
                                <i class="ri-pause-circle-line text-sm"></i> Suspender
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
                                <p class="text-xs font-black text-gray-300 uppercase tracking-widest">No hay clientes con adeudos críticos mayores a 31 días</p>
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