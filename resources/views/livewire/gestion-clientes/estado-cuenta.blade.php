
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ENCABEZADO --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-indigo-600">Estado de Cuenta</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Estado de Cuenta del Cliente
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Consulta histórica de cargos, pagos y saldos por periodo mensual</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>

    {{-- BUSCADOR + FILTRO PERIODO --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-6">
        <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
            <i class="ri-user-search-line text-indigo-500 text-sm"></i>
            <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Consultar Suscriptor</p>
        </div>
        <div class="p-4 flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                <input type="text" wire:model="search"
                       placeholder="Nombre, teléfono, ID o dirección..."
                       class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors placeholder:text-gray-300">
            </div>
            <div class="sm:w-48">
                <input type="month" wire:model="periodo"
                       class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-black text-gray-600 py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
            </div>
            <button wire:click="buscarCliente"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-sm shadow-indigo-200 transition-all active:scale-95">
                <i class="ri-search-line"></i> Generar Consulta
            </button>
        </div>
    </div>

    @if($cliente)

    {{-- PANEL EXPEDIENTE --}}
    <div class="bg-gray-900 rounded-xl p-6 mb-6 text-white relative overflow-hidden">
        <div class="absolute -right-8 -bottom-8 opacity-10 font-black italic text-[120px] leading-none uppercase">CUENTA</div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 relative z-10">
            <div>
                <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1">Suscriptor</p>
                <h3 class="text-xl font-black uppercase tracking-tight">{{ $cliente['nombre'] }}</h3>
                <p class="flex items-center gap-1.5 text-xs text-gray-400 font-bold uppercase mt-1.5">
                    <i class="ri-map-pin-line text-orange-400"></i> {{ $cliente['direccion'] }}
                </p>
            </div>
            <div class="flex flex-col sm:items-end justify-center gap-2">
                <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest">Estatus de Servicio</p>
                <span class="inline-flex items-center gap-1.5 bg-indigo-600 text-white px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest self-start sm:self-auto">
                    <span class="w-1.5 h-1.5 bg-white rounded-full"></span>
                    {{ $cliente['servicio_activo'] }}
                </span>
            </div>
        </div>
    </div>

    {{-- TABS --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

        {{-- Tab headers --}}
        <div class="border-b border-gray-200 flex">
            <button wire:click="$set('tabActual', 'mensualidades')"
                    class="flex-1 flex items-center justify-center gap-2 px-5 py-3.5 text-[10px] font-black uppercase tracking-widest transition-colors border-b-2 -mb-px
                           {{ $tabActual == 'mensualidades'
                               ? 'border-indigo-600 text-indigo-600 bg-indigo-50/40'
                               : 'border-transparent text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <i class="ri-calendar-check-line text-base"></i>
                <span class="hidden sm:inline">Corte de Mensualidades</span>
                <span class="sm:hidden">Mensualidades</span>
            </button>
            <button wire:click="$set('tabActual', 'otros')"
                    class="flex-1 flex items-center justify-center gap-2 px-5 py-3.5 text-[10px] font-black uppercase tracking-widest transition-colors border-b-2 -mb-px
                           {{ $tabActual == 'otros'
                               ? 'border-indigo-600 text-indigo-600 bg-indigo-50/40'
                               : 'border-transparent text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                <i class="ri-list-settings-line text-base"></i>
                <span class="hidden sm:inline">Otros Movimientos (4 Conceptos)</span>
                <span class="sm:hidden">Otros Movimientos</span>
            </button>
        </div>

        {{-- TAB: MENSUALIDADES --}}
        @if($tabActual == 'mensualidades')
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Concepto / Mov</th>
                        <th class="px-4 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Servicio</th>
                        <th class="px-4 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Fecha</th>
                        <th class="px-4 py-3.5 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Importe</th>
                        <th class="px-4 py-3.5 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Saldo Ant.</th>
                        <th class="px-4 py-3.5 text-right text-[9px] font-black text-red-500 uppercase tracking-widest">Saldo a Pagar</th>
                        <th class="px-4 py-3.5 text-right text-[9px] font-black text-emerald-600 uppercase tracking-widest">Pago</th>
                        <th class="px-4 py-3.5 text-right text-[9px] font-black text-gray-900 uppercase tracking-widest underline">Saldo Periodo</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($cliente['mensualidades'] as $m)
                    <tr class="hover:bg-indigo-50/20 transition-colors">
                        <td class="px-4 py-4">
                            <p class="text-xs font-black text-gray-800 uppercase tracking-tight">{{ $m['concepto'] }}</p>
                            <span class="font-mono text-[9px] text-indigo-500 font-bold">{{ $m['movimiento'] }}</span>
                        </td>
                        <td class="px-4 py-4 text-[10px] font-bold text-gray-600 uppercase">{{ $m['servicio'] }}</td>
                        <td class="px-4 py-4 text-xs font-medium text-gray-500 italic">{{ $m['fecha'] }}</td>
                        <td class="px-4 py-4 text-right font-black text-gray-900">${{ number_format($m['importe_cobrar'], 2) }}</td>
                        <td class="px-4 py-4 text-right text-gray-400 text-xs font-medium">${{ number_format($m['saldo_anterior'], 2) }}</td>
                        <td class="px-4 py-4 text-right font-black text-red-600">${{ number_format($m['saldo_pagar_corte'], 2) }}</td>
                        <td class="px-4 py-4 text-right font-black text-emerald-600">${{ number_format($m['pago_mensualidad'], 2) }}</td>
                        <td class="px-4 py-4 text-right font-black text-gray-900 text-base tracking-tight">${{ number_format($m['saldo_periodo'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                {{-- Totales --}}
                <tfoot>
                    <tr class="bg-gray-900">
                        <td colspan="3" class="px-4 py-3.5">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Totales del Periodo</p>
                        </td>
                        <td class="px-4 py-3.5 text-right font-black text-white">${{ number_format(collect($cliente['mensualidades'])->sum('importe_cobrar'), 2) }}</td>
                        <td class="px-4 py-3.5"></td>
                        <td class="px-4 py-3.5 text-right font-black text-red-400">${{ number_format(collect($cliente['mensualidades'])->sum('saldo_pagar_corte'), 2) }}</td>
                        <td class="px-4 py-3.5 text-right font-black text-emerald-400">${{ number_format(collect($cliente['mensualidades'])->sum('pago_mensualidad'), 2) }}</td>
                        <td class="px-4 py-3.5 text-right">
                            @php $saldoFinal = collect($cliente['mensualidades'])->last()['saldo_periodo'] ?? 0; @endphp
                            <span class="font-black text-lg {{ $saldoFinal > 0 ? 'text-red-400' : 'text-emerald-400' }}">
                                ${{ number_format($saldoFinal, 2) }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        {{-- TAB: OTROS MOVIMIENTOS --}}
        @if($tabActual == 'otros')
        <div class="p-5 space-y-6">
            @php
                $otrosConceptos = [
                    ['label' => 'Cobro de Días de Uso',                    'color' => 'indigo',  'icon' => 'ri-calendar-2-line',      'data' => $cliente['dias_uso']],
                    ['label' => 'Contratación Nueva',                      'color' => 'blue',    'icon' => 'ri-file-add-line',         'data' => $cliente['contratacion_nueva']],
                    ['label' => 'Contratación Servicios Adicionales',      'color' => 'orange',  'icon' => 'ri-add-box-line',          'data' => $cliente['servicios_adicionales']],
                    ['label' => 'Reconexión',                              'color' => 'emerald', 'icon' => 'ri-refresh-line',          'data' => $cliente['reconexiones']],
                ];
            @endphp

            @foreach($otrosConceptos as $c)
            <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                {{-- Header del concepto --}}
                <div class="flex items-center gap-3 px-5 py-3 border-b
                            {{ $c['color'] === 'indigo'  ? 'bg-indigo-50 border-indigo-100' : '' }}
                            {{ $c['color'] === 'blue'    ? 'bg-blue-50 border-blue-100'     : '' }}
                            {{ $c['color'] === 'orange'  ? 'bg-orange-50 border-orange-100' : '' }}
                            {{ $c['color'] === 'emerald' ? 'bg-emerald-50 border-emerald-100': '' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center
                                {{ $c['color'] === 'indigo'  ? 'bg-indigo-100' : '' }}
                                {{ $c['color'] === 'blue'    ? 'bg-blue-100'   : '' }}
                                {{ $c['color'] === 'orange'  ? 'bg-orange-100' : '' }}
                                {{ $c['color'] === 'emerald' ? 'bg-emerald-100': '' }}">
                        <i class="{{ $c['icon'] }} text-sm
                                  {{ $c['color'] === 'indigo'  ? 'text-indigo-600' : '' }}
                                  {{ $c['color'] === 'blue'    ? 'text-blue-600'   : '' }}
                                  {{ $c['color'] === 'orange'  ? 'text-orange-600' : '' }}
                                  {{ $c['color'] === 'emerald' ? 'text-emerald-600': '' }}"></i>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest
                              {{ $c['color'] === 'indigo'  ? 'text-indigo-700' : '' }}
                              {{ $c['color'] === 'blue'    ? 'text-blue-700'   : '' }}
                              {{ $c['color'] === 'orange'  ? 'text-orange-700' : '' }}
                              {{ $c['color'] === 'emerald' ? 'text-emerald-700': '' }}">
                        {{ $c['label'] }}
                    </p>
                    <span class="ml-auto text-[9px] font-bold text-gray-400 bg-white border border-gray-200 px-2 py-0.5 rounded-md">
                        {{ count($c['data']) }} registros
                    </span>
                </div>

                @if(count($c['data']) > 0)
                <table class="min-w-full divide-y divide-gray-100 text-xs">
                    <thead>
                        <tr class="bg-gray-50">
                            <th class="px-5 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Concepto</th>
                            <th class="px-5 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Servicio</th>
                            <th class="px-5 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Fecha</th>
                            <th class="px-5 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Importe</th>
                            <th class="px-5 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest"># Mov</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($c['data'] as $row)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-bold text-gray-700 uppercase">{{ $row['concepto'] }}</td>
                            <td class="px-5 py-3 text-gray-500 uppercase font-medium text-[10px]">{{ $row['servicio'] }}</td>
                            <td class="px-5 py-3 text-gray-400 italic font-medium">{{ $row['fecha'] }}</td>
                            <td class="px-5 py-3 text-right font-black text-gray-900">${{ number_format($row['importe'], 2) }}</td>
                            <td class="px-5 py-3 text-center font-mono text-[9px] font-black text-indigo-500">{{ $row['mov'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    {{-- Subtotal --}}
                    <tfoot>
                        <tr class="bg-gray-50 border-t border-gray-200">
                            <td colspan="3" class="px-5 py-2.5 text-[9px] font-black text-gray-400 uppercase tracking-widest">Subtotal</td>
                            <td class="px-5 py-2.5 text-right font-black text-gray-900 text-sm">
                                ${{ number_format(collect($c['data'])->sum('importe'), 2) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>

                @else
                <div class="py-8 text-center">
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Sin registros en este periodo</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        @endif

    </div>

    @else
    {{-- EMPTY STATE --}}
    <div class="bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl py-24 text-center">
        <div class="w-16 h-16 bg-white rounded-xl shadow-sm border border-gray-100 flex items-center justify-center mx-auto mb-4">
            <i class="ri-user-search-line text-3xl text-gray-200"></i>
        </div>
        <p class="text-xs font-black text-gray-300 uppercase tracking-widest">Realice una búsqueda para generar el historial de movimientos</p>
    </div>
    @endif

</div>