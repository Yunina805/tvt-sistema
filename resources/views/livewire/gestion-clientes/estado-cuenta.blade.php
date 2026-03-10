<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================
         ENCABEZADO
    ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-red-400"></i>
                <span>Gestión al Suscriptor</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-violet-600">Estados de Cuenta</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Estado de Cuenta del Suscriptor</h2>
            <p class="text-xs text-gray-400 mt-0.5">Consulta histórica de cargos, pagos y saldos · 5 tipos de movimiento · Exportable</p>
        </div>
        <div class="flex items-center gap-2 self-start">
            @if($suscriptor)
            <button x-data
                    @click="$confirm('¿Cambiar de suscriptor?', () => $wire.limpiarSuscriptor(), { confirmText: 'Sí, cambiar', title: 'Cambiar Suscriptor', icon: 'question' })"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-violet-200 text-violet-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-violet-50 transition-all">
                <i class="ri-user-search-line"></i> Cambiar
            </button>
            <button onclick="window.print()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all">
                <i class="ri-printer-line text-violet-500"></i> Imprimir
            </button>
            @endif
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all">
                <i class="ri-arrow-left-line"></i> Panel Principal
            </a>
        </div>
    </div>

    {{-- ================================================================
         BÚSQUEDA (siempre visible si no hay suscriptor seleccionado)
    ================================================================ --}}
    @if(!$suscriptor)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-violet-50/60 border-b border-violet-100 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center flex-shrink-0">
                    <i class="ri-account-box-line text-violet-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Consultar Suscriptor</p>
                    <p class="text-[10px] font-bold text-violet-500 mt-0.5 uppercase tracking-wider">Búsqueda por nombre, teléfono, ID o dirección</p>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-search-line absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" wire:model="busqueda"
                               placeholder="Ej: Juan Pérez, 9511234567, 01-0001234..."
                               wire:keydown.enter="buscarCliente"
                               autofocus
                               class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium
                                      focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400 transition-colors placeholder:text-gray-300">
                        <div wire:loading wire:target="buscarCliente" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                            <i class="ri-loader-4-line animate-spin text-violet-400 text-base"></i>
                        </div>
                    </div>
                    <button wire:click="buscarCliente"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm">
                        Consultar
                    </button>
                </div>

                {{-- Estado inicial --}}
                @if(!$busquedaHecha && count($resultados) === 0)
                <div class="text-center py-10 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                    <i class="ri-file-chart-line text-2xl text-gray-300 block mb-2"></i>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ingrese nombre o ID para consultar el estado de cuenta</p>
                </div>
                @endif

                {{-- Sin resultados --}}
                @if($busquedaHecha && count($resultados) === 0)
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <i class="ri-user-unfollow-line text-amber-500 text-xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Sin resultados</p>
                        <p class="text-[10px] text-amber-600 mt-0.5">No se encontró "<strong>{{ $busqueda }}</strong>"</p>
                    </div>
                </div>
                @endif

                {{-- Resultados --}}
                @if(count($resultados) > 0)
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="px-4 py-2.5 bg-gray-50 border-b border-gray-100">
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">
                            {{ count($resultados) }} suscriptor(es) encontrado(s)
                        </span>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach($resultados as $r)
                        <div class="flex items-center justify-between p-3.5 hover:bg-violet-50/40 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-violet-100 flex items-center justify-center flex-shrink-0 transition-colors">
                                    <i class="ri-user-3-line text-gray-400 group-hover:text-violet-500 transition-colors text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <p class="text-xs font-black text-gray-900 uppercase tracking-tight truncate">{{ $r['nombre'] }}</p>
                                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded uppercase flex-shrink-0
                                            {{ $r['estado'] === 'ACTIVO' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                                            {{ $r['estado'] }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[9px] font-bold text-gray-500 uppercase flex-wrap">
                                        <span class="font-mono bg-gray-100 px-1 py-0.5 rounded text-gray-600">{{ $r['id'] }}</span>
                                        <span class="text-gray-300">·</span>
                                        <span class="truncate max-w-[140px]">{{ $r['direccion'] }}</span>
                                        <span class="text-gray-300">·</span>
                                        <span class="{{ $r['saldo_actual'] > 0 ? 'text-red-500' : 'text-emerald-500' }} font-black">
                                            Saldo: ${{ number_format($r['saldo_actual'], 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button wire:click="seleccionarSuscriptor({{ json_encode($r) }})"
                                    class="flex-shrink-0 ml-3 px-3 py-1.5 bg-white border border-gray-200 text-gray-600 hover:text-violet-700 hover:border-violet-300 hover:bg-violet-50 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all shadow-sm">
                                Ver Cuenta
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         VISTA DE ESTADO DE CUENTA (cuando hay suscriptor)
    ================================================================ --}}
    @if($suscriptor)

    {{-- ── Panel expediente del suscriptor ── --}}
    <div class="bg-gray-900 rounded-xl p-5 mb-6 text-white relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 opacity-5 font-black text-[100px] leading-none uppercase italic">CUENTA</div>
        <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-4 relative z-10">
            <div class="sm:col-span-2">
                <p class="text-[9px] font-black text-violet-400 uppercase tracking-widest mb-1">Suscriptor</p>
                <h3 class="text-base font-black uppercase tracking-tight leading-tight">{{ $suscriptor['nombre'] }}</h3>
                <p class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $suscriptor['id'] }}</p>
                <p class="flex items-center gap-1.5 text-[10px] text-gray-400 font-bold uppercase mt-1.5 leading-tight">
                    <i class="ri-map-pin-line text-orange-400 flex-shrink-0"></i>
                    <span>{{ $suscriptor['direccion'] }}</span>
                </p>
            </div>
            <div>
                <p class="text-[9px] font-black text-violet-400 uppercase tracking-widest mb-1">Servicio(s) Activo(s)</p>
                @foreach($suscriptor['servicios'] as $srv)
                <span class="inline-block text-[9px] font-black bg-violet-600 text-white px-2 py-0.5 rounded-md uppercase mb-0.5">{{ $srv }}</span>
                @endforeach
                <p class="text-[10px] text-gray-400 mt-1 font-bold">
                    <i class="ri-building-2-line mr-0.5"></i> {{ $suscriptor['sucursal'] }}
                </p>
            </div>
            <div>
                <p class="text-[9px] font-black text-violet-400 uppercase tracking-widest mb-1">Estado</p>
                <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase
                    {{ $suscriptor['estado'] === 'ACTIVO' ? 'text-emerald-400' : 'text-red-400' }}">
                    <span class="w-1.5 h-1.5 rounded-full {{ $suscriptor['estado'] === 'ACTIVO' ? 'bg-emerald-400 animate-pulse' : 'bg-red-400' }}"></span>
                    {{ $suscriptor['estado'] }}
                </span>
                @if($suscriptor['meses_adeudo'] > 0)
                <p class="text-[9px] text-amber-400 font-black uppercase mt-1 flex items-center gap-1">
                    <i class="ri-alert-line"></i> {{ $suscriptor['meses_adeudo'] }} mes(es) de adeudo
                </p>
                @endif
            </div>
            <div class="text-right">
                <p class="text-[9px] font-black text-violet-400 uppercase tracking-widest mb-1">Saldo Actual</p>
                <p class="text-3xl font-black {{ $suscriptor['saldo_actual'] > 0 ? 'text-red-400' : 'text-emerald-400' }} tracking-tight">
                    ${{ number_format($suscriptor['saldo_actual'], 2) }}
                </p>
                <p class="text-[9px] text-gray-500 uppercase font-bold mt-0.5">
                    Tarifa: ${{ number_format($suscriptor['tarifa'], 2) }}/mes
                </p>
            </div>
        </div>
    </div>

    {{-- ── Filtro de período ── --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 mb-5">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="flex items-center gap-1 flex-shrink-0">
                <i class="ri-calendar-line text-violet-500 text-sm"></i>
                <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Período:</span>
            </div>
            {{-- Toggle tipo período --}}
            <div class="flex items-center gap-1 bg-gray-100 rounded-lg p-1">
                <button type="button"
                        wire:click="$set('tipoPeriodo', 'mensual')"
                        class="px-3 py-1.5 rounded-md text-[10px] font-black uppercase tracking-widest transition-all
                               {{ $tipoPeriodo === 'mensual' ? 'bg-white text-violet-700 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                    Vista Mensual
                </button>
                <button type="button"
                        wire:click="$set('tipoPeriodo', 'personalizado')"
                        class="px-3 py-1.5 rounded-md text-[10px] font-black uppercase tracking-widest transition-all
                               {{ $tipoPeriodo === 'personalizado' ? 'bg-white text-violet-700 shadow-sm' : 'text-gray-400 hover:text-gray-600' }}">
                    Rango Personalizado
                </button>
            </div>
            {{-- Selectores --}}
            @if($tipoPeriodo === 'mensual')
            <input type="month" wire:model.live="periodoMes"
                   class="bg-gray-50 border border-gray-200 rounded-lg text-xs font-black text-gray-700 py-2 px-3
                          focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400">
            @else
            <div class="flex items-center gap-2">
                <input type="date" wire:model.live="fechaDesde"
                       class="bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 py-2 px-3
                              focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400">
                <span class="text-[10px] font-black text-gray-400">—</span>
                <input type="date" wire:model.live="fechaHasta"
                       class="bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold text-gray-700 py-2 px-3
                              focus:ring-2 focus:ring-violet-500/20 focus:border-violet-400">
            </div>
            @endif
            <div wire:loading wire:target="cargarMovimientos" class="flex items-center gap-1.5 text-[10px] font-black text-violet-500 uppercase">
                <i class="ri-loader-4-line animate-spin text-sm"></i> Actualizando...
            </div>
        </div>
    </div>

    {{-- ── Tabs + Tablas ── --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-5">

        {{-- Tab headers (scrollable en mobile) --}}
        <div class="border-b border-gray-200 overflow-x-auto">
            <div class="flex min-w-max">
                @php
                    $tabs = [
                        'mensualidades'         => ['icon' => 'ri-calendar-check-line',  'label' => 'Corte de Mensualidades',     'color' => 'violet'],
                        'dias_uso'              => ['icon' => 'ri-calendar-2-line',       'label' => 'Días de Uso',               'color' => 'indigo'],
                        'contratacion_nueva'    => ['icon' => 'ri-file-add-line',         'label' => 'Contratación Nueva',        'color' => 'blue'],
                        'servicios_adicionales' => ['icon' => 'ri-add-box-line',          'label' => 'Servicios Adicionales',     'color' => 'orange'],
                        'reconexiones'          => ['icon' => 'ri-refresh-line',          'label' => 'Reconexión',                'color' => 'emerald'],
                    ];
                @endphp
                @foreach($tabs as $key => $t)
                @php
                    $count = count($movimientos[$key] ?? []);
                    $isActive = $tabActual === $key;
                    $activeClasses = match($t['color']) {
                        'violet'  => 'border-violet-600 text-violet-700 bg-violet-50/40',
                        'indigo'  => 'border-indigo-600 text-indigo-700 bg-indigo-50/40',
                        'blue'    => 'border-blue-600 text-blue-700 bg-blue-50/40',
                        'orange'  => 'border-orange-500 text-orange-700 bg-orange-50/40',
                        'emerald' => 'border-emerald-600 text-emerald-700 bg-emerald-50/40',
                        default   => 'border-gray-600 text-gray-700',
                    };
                    $badgeClasses = match($t['color']) {
                        'violet'  => 'bg-violet-100 text-violet-700',
                        'indigo'  => 'bg-indigo-100 text-indigo-700',
                        'blue'    => 'bg-blue-100 text-blue-700',
                        'orange'  => 'bg-orange-100 text-orange-700',
                        'emerald' => 'bg-emerald-100 text-emerald-700',
                        default   => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <button wire:click="$set('tabActual', '{{ $key }}')"
                        class="flex items-center gap-2 px-5 py-3.5 text-[10px] font-black uppercase tracking-widest transition-colors border-b-2 -mb-px whitespace-nowrap
                               {{ $isActive ? $activeClasses : 'border-transparent text-gray-400 hover:text-gray-600 hover:bg-gray-50' }}">
                    <i class="{{ $t['icon'] }} text-sm"></i>
                    <span>{{ $t['label'] }}</span>
                    @if($count > 0)
                    <span class="text-[9px] font-black px-1.5 py-0.5 rounded-full {{ $isActive ? $badgeClasses : 'bg-gray-100 text-gray-500' }}">
                        {{ $count }}
                    </span>
                    @endif
                </button>
                @endforeach
            </div>
        </div>

        {{-- ──────────────────────────────────────────────────────────────────
             TAB 1 — CORTE DE MENSUALIDADES (8 columnas)
        ─────────────────────────────────────────────────────────────────── --}}
        @if($tabActual === 'mensualidades')
        @if(count($movimientos['mensualidades']) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-xs">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest whitespace-nowrap"># Movimiento</th>
                        <th class="px-4 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Concepto (Corte)</th>
                        <th class="px-4 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Servicio</th>
                        <th class="px-4 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Fecha</th>
                        <th class="px-4 py-3.5 text-right text-[9px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Importe a Cobrar</th>
                        <th class="px-4 py-3.5 text-right text-[9px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Saldo Anterior</th>
                        <th class="px-4 py-3.5 text-right text-[9px] font-black text-red-500 uppercase tracking-widest whitespace-nowrap">Saldo a Pagar (Corte)</th>
                        <th class="px-4 py-3.5 text-right text-[9px] font-black text-emerald-600 uppercase tracking-widest whitespace-nowrap">Pago por Mensualidad</th>
                        <th class="px-4 py-3.5 text-right text-[9px] font-black text-violet-700 uppercase tracking-widest whitespace-nowrap underline">Saldo del Período</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($movimientos['mensualidades'] as $m)
                    <tr class="hover:bg-violet-50/20 transition-colors">
                        <td class="px-4 py-3.5 font-mono text-[9px] font-black text-violet-500 whitespace-nowrap">{{ $m['movimiento'] }}</td>
                        <td class="px-4 py-3.5">
                            <p class="font-black text-gray-800 uppercase tracking-tight">{{ $m['concepto'] }}</p>
                        </td>
                        <td class="px-4 py-3.5 text-[10px] font-bold text-gray-600 uppercase whitespace-nowrap">{{ $m['servicio'] }}</td>
                        <td class="px-4 py-3.5 font-medium text-gray-500 whitespace-nowrap">{{ $m['fecha'] }}</td>
                        <td class="px-4 py-3.5 text-right font-black text-gray-900 whitespace-nowrap">${{ number_format($m['importe_cobrar'], 2) }}</td>
                        <td class="px-4 py-3.5 text-right text-gray-400 font-medium whitespace-nowrap">${{ number_format($m['saldo_anterior'], 2) }}</td>
                        <td class="px-4 py-3.5 text-right font-black text-red-600 whitespace-nowrap">${{ number_format($m['saldo_pagar_corte'], 2) }}</td>
                        <td class="px-4 py-3.5 text-right font-black text-emerald-600 whitespace-nowrap">${{ number_format($m['pago_mensualidad'], 2) }}</td>
                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                            <span class="font-black text-sm {{ $m['saldo_periodo'] > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                                ${{ number_format($m['saldo_periodo'], 2) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-900">
                        <td colspan="4" class="px-4 py-3.5">
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Totales del Período</p>
                        </td>
                        <td class="px-4 py-3.5 text-right font-black text-white whitespace-nowrap">
                            ${{ number_format(collect($movimientos['mensualidades'])->sum('importe_cobrar'), 2) }}
                        </td>
                        <td class="px-4 py-3.5"></td>
                        <td class="px-4 py-3.5 text-right font-black text-red-400 whitespace-nowrap">
                            ${{ number_format(collect($movimientos['mensualidades'])->sum('saldo_pagar_corte'), 2) }}
                        </td>
                        <td class="px-4 py-3.5 text-right font-black text-emerald-400 whitespace-nowrap">
                            ${{ number_format(collect($movimientos['mensualidades'])->sum('pago_mensualidad'), 2) }}
                        </td>
                        <td class="px-4 py-3.5 text-right whitespace-nowrap">
                            @php $saldoFinal = collect($movimientos['mensualidades'])->last()['saldo_periodo'] ?? 0; @endphp
                            <span class="font-black text-lg {{ $saldoFinal > 0 ? 'text-red-400' : 'text-emerald-400' }}">
                                ${{ number_format($saldoFinal, 2) }}
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="py-16 text-center">
            <i class="ri-calendar-check-line text-3xl text-gray-200 block mb-2"></i>
            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Sin cortes de mensualidad en este período</p>
        </div>
        @endif
        @endif

        {{-- ──────────────────────────────────────────────────────────────────
             TABS 2-5 — Patrón común (4 columnas)
        ─────────────────────────────────────────────────────────────────── --}}
        @php
            $tabsSimples = [
                'dias_uso'              => ['color' => 'indigo',  'colorClass' => 'text-indigo-700',  'icon' => 'ri-calendar-2-line',  'label' => 'Cobro de Días de Uso',               'desc' => 'Pagos proporcionales por días de servicio · Altas, reconexiones y cambios'],
                'contratacion_nueva'    => ['color' => 'blue',    'colorClass' => 'text-blue-700',    'icon' => 'ri-file-add-line',     'label' => 'Contratación Nueva',                 'desc' => 'Cargos de instalación y pagos iniciales del servicio'],
                'servicios_adicionales' => ['color' => 'orange',  'colorClass' => 'text-orange-700',  'icon' => 'ri-add-box-line',      'label' => 'Contratación de Servicios Adicionales','desc' => 'Instalación de servicios extra y activaciones adicionales'],
                'reconexiones'          => ['color' => 'emerald', 'colorClass' => 'text-emerald-700', 'icon' => 'ri-refresh-line',      'label' => 'Reconexión',                         'desc' => 'Adeudos anteriores · Días de uso por reactivación · Cargos de reconexión'],
            ];
            $tabInfo = $tabsSimples[$tabActual] ?? null;
        @endphp
        @if($tabInfo)
        @php $filas = $movimientos[$tabActual] ?? []; @endphp
        <div class="border-b border-gray-100 px-5 py-3 flex items-center gap-3 bg-gray-50/60">
            <div class="w-7 h-7 rounded-lg flex items-center justify-center
                {{ $tabInfo['color'] === 'indigo'  ? 'bg-indigo-100' : '' }}
                {{ $tabInfo['color'] === 'blue'    ? 'bg-blue-100'   : '' }}
                {{ $tabInfo['color'] === 'orange'  ? 'bg-orange-100' : '' }}
                {{ $tabInfo['color'] === 'emerald' ? 'bg-emerald-100': '' }}">
                <i class="{{ $tabInfo['icon'] }} text-sm {{ $tabInfo['colorClass'] }}"></i>
            </div>
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest {{ $tabInfo['colorClass'] }}">{{ $tabInfo['label'] }}</p>
                <p class="text-[9px] text-gray-400 font-medium mt-0.5">{{ $tabInfo['desc'] }}</p>
            </div>
            <span class="ml-auto text-[9px] font-bold text-gray-400 bg-white border border-gray-200 px-2 py-0.5 rounded-md">
                {{ count($filas) }} registro(s)
            </span>
        </div>
        @if(count($filas) > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 text-xs">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest whitespace-nowrap"># Movimiento</th>
                        <th class="px-5 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Concepto</th>
                        <th class="px-5 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Servicio Asociado</th>
                        <th class="px-5 py-3.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Fecha</th>
                        <th class="px-5 py-3.5 text-right text-[9px] font-black text-gray-500 uppercase tracking-widest whitespace-nowrap">Importe Pagado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($filas as $row)
                    <tr class="hover:bg-gray-50/60 transition-colors">
                        <td class="px-5 py-3.5 font-mono text-[9px] font-black {{ $tabInfo['colorClass'] }} whitespace-nowrap">{{ $row['movimiento'] }}</td>
                        <td class="px-5 py-3.5 font-bold text-gray-800 uppercase">{{ $row['concepto'] }}</td>
                        <td class="px-5 py-3.5 text-[10px] font-medium text-gray-500 uppercase whitespace-nowrap">{{ $row['servicio'] }}</td>
                        <td class="px-5 py-3.5 font-medium text-gray-500 whitespace-nowrap">{{ $row['fecha'] }}</td>
                        <td class="px-5 py-3.5 text-right font-black text-gray-900 whitespace-nowrap">${{ number_format($row['importe'], 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 border-t border-gray-200">
                        <td colspan="4" class="px-5 py-3 text-[9px] font-black text-gray-400 uppercase tracking-widest">
                            Subtotal — {{ $tabInfo['label'] }}
                        </td>
                        <td class="px-5 py-3 text-right font-black text-gray-900 text-sm whitespace-nowrap">
                            ${{ number_format(collect($filas)->sum('importe'), 2) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @else
        <div class="py-16 text-center">
            <i class="{{ $tabInfo['icon'] }} text-3xl text-gray-200 block mb-2"></i>
            <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Sin movimientos de {{ $tabInfo['label'] }} en este período</p>
        </div>
        @endif
        @endif

    </div>

    {{-- ── Resumen General del Período ── --}}
    @php
        $totalMensualidades  = collect($movimientos['mensualidades'])->sum('pago_mensualidad');
        $totalDiasUso        = collect($movimientos['dias_uso'])->sum('importe');
        $totalContNueva      = collect($movimientos['contratacion_nueva'])->sum('importe');
        $totalServAdicionales= collect($movimientos['servicios_adicionales'])->sum('importe');
        $totalReconexiones   = collect($movimientos['reconexiones'])->sum('importe');
        $totalGeneral        = $totalMensualidades + $totalDiasUso + $totalContNueva + $totalServAdicionales + $totalReconexiones;
    @endphp
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
            <i class="ri-bar-chart-grouped-line text-violet-500 text-sm"></i>
            <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Resumen General del Período</p>
            <span class="ml-auto text-[9px] font-bold text-gray-400 uppercase">
                @if($tipoPeriodo === 'mensual')
                    {{ \Carbon\Carbon::createFromFormat('Y-m', $periodoMes)->translatedFormat('F Y') }}
                @else
                    {{ $fechaDesde }} — {{ $fechaHasta }}
                @endif
            </span>
        </div>
        <div class="p-5">
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
                @foreach([
                    ['label' => 'Mensualidades',       'value' => $totalMensualidades,   'color' => 'violet'],
                    ['label' => 'Días de Uso',          'value' => $totalDiasUso,         'color' => 'indigo'],
                    ['label' => 'Contrat. Nueva',       'value' => $totalContNueva,       'color' => 'blue'],
                    ['label' => 'Serv. Adicionales',    'value' => $totalServAdicionales, 'color' => 'orange'],
                    ['label' => 'Reconexiones',         'value' => $totalReconexiones,    'color' => 'emerald'],
                ] as $r)
                <div class="rounded-xl p-3 border
                    {{ $r['color'] === 'violet'  ? 'bg-violet-50 border-violet-100'   : '' }}
                    {{ $r['color'] === 'indigo'  ? 'bg-indigo-50 border-indigo-100'   : '' }}
                    {{ $r['color'] === 'blue'    ? 'bg-blue-50 border-blue-100'       : '' }}
                    {{ $r['color'] === 'orange'  ? 'bg-orange-50 border-orange-100'   : '' }}
                    {{ $r['color'] === 'emerald' ? 'bg-emerald-50 border-emerald-100' : '' }}">
                    <p class="text-[9px] font-black uppercase tracking-widest mb-1
                        {{ $r['color'] === 'violet'  ? 'text-violet-600'  : '' }}
                        {{ $r['color'] === 'indigo'  ? 'text-indigo-600'  : '' }}
                        {{ $r['color'] === 'blue'    ? 'text-blue-600'    : '' }}
                        {{ $r['color'] === 'orange'  ? 'text-orange-600'  : '' }}
                        {{ $r['color'] === 'emerald' ? 'text-emerald-600' : '' }}">
                        {{ $r['label'] }}
                    </p>
                    <p class="text-base font-black text-gray-900">${{ number_format($r['value'], 2) }}</p>
                </div>
                @endforeach
                {{-- Total General --}}
                <div class="rounded-xl p-3 bg-gray-900 border border-gray-800">
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-1">Total General</p>
                    <p class="text-base font-black text-white">${{ number_format($totalGeneral, 2) }}</p>
                </div>
            </div>

            {{-- Acciones --}}
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-3 border-t border-gray-100">
                <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                    <i class="ri-information-line text-violet-400 mr-0.5"></i>
                    Total incluye pagos registrados — cortes generados por el sistema
                </p>
                <div class="flex items-center gap-2">
                    <button onclick="window.print()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-gray-50 shadow-sm transition-all">
                        <i class="ri-printer-line text-violet-500"></i> Imprimir
                    </button>
                    <button onclick="window.print()"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 text-white font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-violet-700 shadow-sm shadow-violet-200 transition-all">
                        <i class="ri-file-pdf-line"></i> Exportar PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    @endif {{-- / $suscriptor --}}

</div>
