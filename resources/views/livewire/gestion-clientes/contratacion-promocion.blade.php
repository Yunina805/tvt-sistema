<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================ ENCABEZADO ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-orange-600">Contratación de Promociones</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Pago Anticipado con <span class="text-orange-600">Beneficios</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">6×7 · 12×14 · Meses de servicio gratis por pago adelantado</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>


    {{-- ================================================================ INDICADOR DE PASOS ================================================================ --}}
    <div class="mb-8">
        <div class="flex items-center justify-center gap-0">
            @php
                $stepLabels = ['Identificar Cliente', 'Seleccionar Promoción', 'Confirmación'];
            @endphp
            @foreach($stepLabels as $i => $label)
            @php $num = $i + 1; @endphp
            <div class="flex items-center {{ $num < count($stepLabels) ? 'flex-1' : '' }}">
                <div class="flex flex-col items-center gap-1 {{ $num < count($stepLabels) ? 'flex-shrink-0' : '' }}">
                    <div class="w-9 h-9 rounded-full border-2 flex items-center justify-center text-sm font-black transition-all
                        {{ $paso > $num ? 'bg-orange-500 border-orange-500 text-white' : ($paso == $num ? 'bg-white border-orange-500 text-orange-600' : 'bg-white border-gray-200 text-gray-300') }}">
                        @if($paso > $num)
                            <i class="ri-check-line text-sm"></i>
                        @else
                            {{ $num }}
                        @endif
                    </div>
                    <p class="text-[9px] font-black uppercase tracking-widest
                        {{ $paso >= $num ? 'text-orange-600' : 'text-gray-300' }}">{{ $label }}</p>
                </div>
                @if($num < count($stepLabels))
                <div class="flex-1 h-px mx-3 mt-[-18px]
                    {{ $paso > $num ? 'bg-orange-400' : 'bg-gray-200' }}"></div>
                @endif
            </div>
            @endforeach
        </div>
    </div>

    {{-- ================================================================
         PASO 1 — IDENTIFICAR CLIENTE
    ================================================================ --}}
    @if($paso == 1)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="bg-orange-50 border-b border-orange-100 px-6 py-5 text-center">
                <div class="w-14 h-14 bg-white border border-orange-100 rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-3">
                    <i class="ri-star-smile-line text-orange-500 text-2xl"></i>
                </div>
                <p class="text-[11px] font-black text-orange-800 uppercase tracking-widest">Identificar Cliente para Aplicar Promoción</p>
                <p class="text-[10px] text-orange-500 mt-1 font-medium">Solo clientes con estado Activo pueden contratar promociones</p>
            </div>

            {{-- Búsqueda --}}
            <div class="p-5 space-y-3">
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-user-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" wire:model="search"
                               placeholder="Nombre, ID o teléfono del cliente..."
                               wire:keydown.enter="buscarCliente"
                               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 transition-colors placeholder:text-gray-300">
                    </div>
                    <button wire:click="buscarCliente"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm">
                        Consultar
                    </button>
                </div>

                {{-- Resultados de búsqueda --}}
                @if(count($resultados) > 0)
                <div class="space-y-2">
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest px-1">
                        {{ count($resultados) }} resultado(s) — Seleccione un cliente
                    </p>
                    @foreach($resultados as $r)
                    <button wire:click="seleccionarCliente({{ json_encode($r) }})"
                            class="w-full p-4 bg-gray-50 border border-gray-200 rounded-xl hover:border-orange-300 hover:bg-orange-50/40 transition-all text-left group">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight group-hover:text-orange-700 transition-colors">{{ $r['nombre'] }}</p>
                                <div class="flex items-center gap-3 mt-1 flex-wrap">
                                    <span class="font-mono text-[10px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded">{{ $r['id'] }}</span>
                                    <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $r['servicio'] }}</span>
                                    <span class="text-[10px] font-bold text-gray-400">{{ $r['sucursal'] }}</span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1.5 flex-shrink-0 ml-3">
                                <span class="text-xs font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-md">{{ $r['estado'] }}</span>
                                <span class="text-sm font-black text-gray-800">${{ number_format($r['tarifa'], 2) }}<span class="text-[9px] text-gray-400 font-bold">/mes</span></span>
                            </div>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-[9px] font-black text-orange-500 opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="ri-arrow-right-circle-line"></i> Clic para seleccionar
                        </div>
                    </button>
                    @endforeach
                </div>
                @elseif(strlen($search) >= 2 && count($resultados) === 0)
                <div class="text-center py-8">
                    <i class="ri-user-unfollow-line text-3xl text-gray-200 block mb-2"></i>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Sin coincidencias para "{{ $search }}"</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 2 — SELECCIONAR PROMOCIÓN + LIQUIDACIÓN
    ================================================================ --}}
    @if($paso == 2)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ── Columna izquierda: expediente + selector de promo ── --}}
        <div class="lg:col-span-4 space-y-4">

            {{-- Expediente del cliente --}}
            <div class="bg-gray-900 rounded-xl p-5 text-white shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-5"><i class="ri-star-smile-line text-9xl"></i></div>
                <p class="text-[9px] font-black text-orange-400 uppercase tracking-widest mb-3 border-b border-gray-800 pb-2">Expediente del Cliente</p>
                <div class="space-y-3 relative z-10">
                    <div>
                        <p class="text-[8px] text-gray-500 uppercase font-bold mb-0.5">Titular</p>
                        <p class="font-black text-white uppercase tracking-tight text-sm">{{ $cliente['nombre'] }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-[8px] text-gray-500 uppercase font-bold mb-0.5">ID Cliente</p>
                            <p class="font-mono text-[10px] font-black text-indigo-400">{{ $cliente['id'] }}</p>
                        </div>
                        <div>
                            <p class="text-[8px] text-gray-500 uppercase font-bold mb-0.5">Sucursal</p>
                            <p class="text-[10px] font-bold text-gray-300">{{ $cliente['sucursal'] }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[8px] text-gray-500 uppercase font-bold mb-0.5">Servicio</p>
                        <p class="font-bold text-indigo-400 uppercase text-xs">{{ $cliente['servicio'] }}</p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">{{ $cliente['estado'] }}</span>
                        </div>
                        <span class="text-base font-black text-white">${{ number_format($cliente['tarifa'], 2) }}<span class="text-[9px] text-gray-400 font-bold">/mes</span></span>
                    </div>
                </div>
            </div>

            {{-- Selección de promoción --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex items-center gap-2">
                    <i class="ri-gift-line text-orange-500 text-sm"></i>
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Promociones Disponibles</p>
                    <span class="ml-auto text-[9px] font-black text-orange-600 bg-orange-50 border border-orange-100 px-1.5 py-0.5 rounded uppercase">{{ $cliente['tipo_servicio'] }}</span>
                </div>
                <div class="p-3 space-y-2">
                    @forelse($promociones as $p)
                    <button wire:click="seleccionarPromo({{ $p['id'] }})"
                            class="w-full p-4 rounded-xl border-2 transition-all text-left group
                                   {{ $promoSeleccionada && $promoSeleccionada['id'] == $p['id']
                                       ? 'border-orange-500 bg-orange-50 shadow-sm'
                                       : 'border-gray-100 hover:border-orange-200 hover:bg-orange-50/30' }}">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <p class="text-[11px] font-black uppercase leading-tight
                                    {{ $promoSeleccionada && $promoSeleccionada['id'] == $p['id'] ? 'text-orange-700' : 'text-gray-700 group-hover:text-orange-600' }}">
                                    {{ $p['nombre'] }}
                                </p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">
                                    Paga <strong>{{ $p['meses_pago'] }}</strong> mes{{ $p['meses_pago'] > 1 ? 'es' : '' }}
                                    · Recibe <strong>{{ $p['meses_beneficio'] }}</strong> mes{{ $p['meses_beneficio'] > 1 ? 'es' : '' }}
                                </p>
                                <span class="inline-block mt-2 text-[9px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100 px-1.5 py-0.5 rounded-md uppercase tracking-widest">
                                    +{{ $p['meses_beneficio'] - $p['meses_pago'] }} {{ ($p['meses_beneficio'] - $p['meses_pago']) == 1 ? 'mes' : 'meses' }} gratis
                                </span>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0 ml-3 mt-0.5
                                        {{ $promoSeleccionada && $promoSeleccionada['id'] == $p['id'] ? 'border-orange-500 bg-orange-500' : 'border-gray-300' }}">
                                @if($promoSeleccionada && $promoSeleccionada['id'] == $p['id'])
                                <i class="ri-check-line text-white text-[10px]"></i>
                                @endif
                            </div>
                        </div>
                    </button>
                    @empty
                    <div class="py-6 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                        Sin promociones disponibles para este servicio
                    </div>
                    @endforelse
                </div>
            </div>

            <button wire:click="$set('paso', 1)"
                    class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5 px-1">
                <i class="ri-arrow-left-line"></i> Cambiar cliente
            </button>
        </div>

        {{-- ── Columna derecha: desglose de liquidación ── --}}
        <div class="lg:col-span-8">

            @if(!$promoSeleccionada)
            {{-- Placeholder --}}
            <div class="bg-white border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center py-32">
                <div class="text-center space-y-3">
                    <div class="w-16 h-16 bg-orange-50 border border-orange-100 rounded-2xl flex items-center justify-center mx-auto">
                        <i class="ri-calculator-line text-2xl text-orange-300"></i>
                    </div>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Seleccione una promoción para ver el desglose</p>
                </div>
            </div>

            @else
            {{-- Desglose completo --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                {{-- Encabezado del desglose --}}
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Liquidación de Pago Anticipado</p>
                        <p class="text-[10px] text-orange-500 font-bold uppercase mt-0.5">{{ $promoSeleccionada['nombre'] }}</p>
                    </div>
                    <span class="text-sm font-black text-orange-700 bg-orange-100 border border-orange-200 px-3 py-1.5 rounded-lg uppercase tracking-widest">
                        {{ $promoSeleccionada['meses_pago'] }}×{{ $promoSeleccionada['meses_beneficio'] }}
                    </span>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Tarifa base --}}
                    <div class="flex items-center justify-between bg-indigo-50 border border-indigo-100 rounded-lg px-4 py-3">
                        <div class="flex items-center gap-2">
                            <i class="ri-price-tag-3-line text-indigo-500 text-sm"></i>
                            <p class="text-[10px] font-black text-indigo-700 uppercase tracking-widest">Tarifa Mensual del Servicio</p>
                        </div>
                        <p class="text-base font-black text-indigo-700">${{ number_format($cliente['tarifa'], 2) }}/mes</p>
                    </div>

                    {{-- Cards de desglose: 2 columnas --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Card: Días de Uso --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest leading-tight">Días de Uso</p>
                                    <p class="text-[9px] text-gray-400 font-medium mt-0.5">Prorrateo hasta día 1 del próx. mes</p>
                                </div>
                                <span class="text-[9px] font-black text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-1 rounded-lg uppercase">
                                    {{ $calculos['dias_uso'] }} días
                                </span>
                            </div>
                            <p class="text-[9px] text-gray-400 font-medium mb-1">
                                ${{ number_format($calculos['costo_dia'] ?? 0, 2) }}/día × {{ $calculos['dias_uso'] }} días
                            </p>
                            <p class="text-2xl font-black text-gray-900 tracking-tight">${{ number_format($calculos['importe_dias'], 2) }}</p>
                        </div>

                        {{-- Card: Importe Promo --}}
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
                            <div class="mb-3">
                                <p class="text-[10px] font-black text-orange-700 uppercase tracking-widest leading-tight">Importe de la Promoción</p>
                                <p class="text-[9px] text-orange-400 font-medium mt-0.5">
                                    ${{ number_format($cliente['tarifa'], 2) }}/mes × {{ $promoSeleccionada['meses_pago'] }} meses
                                </p>
                            </div>
                            <p class="text-2xl font-black text-orange-600 tracking-tight">${{ number_format($calculos['importe_promo'], 2) }}</p>
                            <span class="inline-block mt-2 text-[9px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded-md uppercase">
                                +{{ $promoSeleccionada['meses_beneficio'] - $promoSeleccionada['meses_pago'] }} mes{{ ($promoSeleccionada['meses_beneficio'] - $promoSeleccionada['meses_pago']) > 1 ? 'es' : '' }} de bonificación
                            </span>
                        </div>
                    </div>

                    {{-- Vigencia proyectada --}}
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
                            <i class="ri-calendar-check-line text-orange-500 text-sm"></i>
                            <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Vigencia de la Promoción</p>
                        </div>
                        <div class="grid grid-cols-3 divide-x divide-gray-100">
                            <div class="px-4 py-4">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Aplica desde</p>
                                <p class="text-[11px] font-black text-emerald-700 uppercase">{{ $calculos['fecha_inicio'] }}</p>
                                <p class="text-[9px] text-gray-400 mt-0.5">Día 1 del mes siguiente</p>
                            </div>
                            <div class="px-4 py-4">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Término promo</p>
                                <p class="text-[11px] font-black text-red-600 uppercase">{{ $calculos['fecha_termino'] }}</p>
                                <p class="text-[9px] text-gray-400 mt-0.5">{{ $promoSeleccionada['meses_beneficio'] }} × 30 días</p>
                            </div>
                            <div class="px-4 py-4 bg-indigo-50/40">
                                <p class="text-[8px] font-black text-indigo-500 uppercase tracking-widest mb-1">Próximo Pago</p>
                                <p class="text-[11px] font-black text-indigo-700 uppercase">{{ $calculos['proximo_pago'] }}</p>
                                <p class="text-[9px] text-indigo-400 mt-0.5">Al vencer la promo</p>
                            </div>
                        </div>
                    </div>

                    {{-- Forma de pago --}}
                    <div class="space-y-2">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Forma de Pago</p>
                        <div class="grid grid-cols-3 gap-3">
                            @foreach([
                                ['v' => 'efectivo',      'label' => 'Efectivo',       'icon' => 'ri-money-dollar-circle-line', 'c' => 'emerald'],
                                ['v' => 'tarjeta',       'label' => 'Tarjeta',        'icon' => 'ri-bank-card-line',          'c' => 'blue'],
                                ['v' => 'transferencia', 'label' => 'Transferencia',  'icon' => 'ri-swap-line',               'c' => 'violet'],
                            ] as $fp)
                            <label class="cursor-pointer">
                                <input type="radio" wire:model="formaPago" value="{{ $fp['v'] }}" class="sr-only peer">
                                <div class="border-2 rounded-xl p-3 text-center transition-all
                                    {{ $formaPago === $fp['v']
                                        ? 'border-orange-500 bg-orange-50'
                                        : 'border-gray-200 hover:border-orange-200 hover:bg-orange-50/20' }}">
                                    <i class="{{ $fp['icon'] }} text-xl block mb-1
                                        {{ $formaPago === $fp['v'] ? 'text-orange-600' : 'text-gray-300' }}"></i>
                                    <p class="text-[9px] font-black uppercase tracking-widest
                                        {{ $formaPago === $fp['v'] ? 'text-orange-700' : 'text-gray-500' }}">{{ $fp['label'] }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    {{-- Total + Confirmar --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-5 bg-gray-900 rounded-xl px-6 py-5">
                        <div>
                            <p class="text-[9px] font-black text-orange-400 uppercase tracking-widest mb-1">Total a Liquidar Hoy</p>
                            <p class="text-3xl font-black text-white tracking-tight">
                                ${{ number_format($calculos['total'], 2) }}
                            </p>
                            <p class="text-[9px] text-gray-500 font-medium mt-1">
                                Días de uso + {{ $promoSeleccionada['meses_pago'] }} meses anticipados
                            </p>
                        </div>
                        <button @click="$confirm('¿Confirmar promo {{ $promoSeleccionada['nombre'] }} para {{ $cliente['nombre'] }}? Cobro: ${{ number_format($calculos['total'], 2) }}.', () => $wire.confirmarContratacion(), { confirmText: 'Sí, confirmar', title: 'Confirmar Contratación' })"
                                class="w-full sm:w-auto px-8 py-3.5 bg-orange-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-orange-600 shadow-lg shadow-orange-900/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i class="ri-shield-check-line text-base"></i> Confirmar y Activar Promo
                        </button>
                    </div>

                    <p class="text-center text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                        Al confirmar: estado del cliente cambia a <span class="text-orange-500 font-black">PROMOCIÓN CONTRATADA</span> · SMS automático al cliente
                    </p>

                </div>
            </div>
            @endif

        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 3 — RECIBO / CONFIRMACIÓN
    ================================================================ --}}
    @if($paso == 3)
    <div class="max-w-3xl mx-auto space-y-5">

        {{-- Banner de éxito --}}
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="ri-checkbox-circle-fill text-emerald-500 text-2xl"></i>
            </div>
            <div class="flex-1">
                <p class="text-[11px] font-black text-emerald-800 uppercase tracking-widest">¡Promoción Activada Exitosamente!</p>
                <p class="text-[10px] text-emerald-600 font-medium mt-0.5">
                    Se ha enviado SMS al cliente con los detalles de la promoción contratada.
                </p>
            </div>
            <span class="font-mono text-[10px] font-black text-emerald-700 bg-emerald-100 border border-emerald-200 px-2.5 py-1.5 rounded-lg">
                {{ $resultado['folio'] ?? '' }}
            </span>
        </div>

        {{-- Recibo imprimible --}}
        <div id="recibo-imprimible" class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Encabezado oscuro del recibo --}}
            <div class="bg-gray-900 px-6 py-5 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-orange-400 uppercase tracking-widest mb-1">Tu Visión Telecable — Comprobante de Promoción</p>
                    <p class="text-lg font-black text-white uppercase tracking-tight">{{ $resultado['promo']['nombre'] ?? '' }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">{{ $resultado['fecha_registro'] ?? '' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1">Folio</p>
                    <p class="font-mono text-sm font-black text-orange-400">{{ $resultado['folio'] ?? '' }}</p>
                </div>
            </div>

            <div class="p-6 space-y-5">

                {{-- Estado del cliente --}}
                <div class="bg-orange-50 border border-orange-200 rounded-xl px-5 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-0.5">Nuevo estado del cliente</p>
                        <p class="text-[11px] font-black text-orange-800 uppercase tracking-widest">PROMOCIÓN CONTRATADA</p>
                    </div>
                    <span class="text-lg font-black text-orange-600"><i class="ri-star-smile-line"></i></span>
                </div>

                {{-- Datos del cliente --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Titular</p>
                        <p class="text-sm font-black text-gray-900 uppercase">{{ $resultado['cliente']['nombre'] ?? '' }}</p>
                        <p class="font-mono text-[10px] font-bold text-indigo-600 mt-0.5">{{ $resultado['cliente']['id'] ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Servicio</p>
                        <p class="text-[11px] font-bold text-gray-700 uppercase">{{ $resultado['cliente']['servicio'] ?? '' }}</p>
                        <p class="text-[10px] font-black text-gray-500 mt-0.5">${{ number_format($resultado['cliente']['tarifa'] ?? 0, 2) }}/mes</p>
                    </div>
                </div>

                {{-- Desglose del cobro --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-5 py-3">
                        <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Desglose del Cobro</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="flex items-center justify-between px-5 py-3">
                            <div>
                                <p class="text-[10px] font-black text-gray-700 uppercase">Días de Uso (Prorrateo)</p>
                                <p class="text-[9px] text-gray-400 font-medium">{{ $resultado['calculos']['dias_uso'] ?? 0 }} días — Para iniciar el día 1 del mes</p>
                            </div>
                            <p class="text-sm font-black text-gray-800">${{ number_format($resultado['calculos']['importe_dias'] ?? 0, 2) }}</p>
                        </div>
                        <div class="flex items-center justify-between px-5 py-3">
                            <div>
                                <p class="text-[10px] font-black text-gray-700 uppercase">Importe de la Promoción</p>
                                <p class="text-[9px] text-gray-400 font-medium">
                                    {{ $resultado['promo']['meses_pago'] ?? 0 }} meses pagados ·
                                    {{ $resultado['promo']['meses_beneficio'] ?? 0 }} meses de vigencia
                                </p>
                            </div>
                            <p class="text-sm font-black text-orange-600">${{ number_format($resultado['calculos']['importe_promo'] ?? 0, 2) }}</p>
                        </div>
                        <div class="flex items-center justify-between px-5 py-3 bg-gray-900">
                            <div>
                                <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Total Cobrado</p>
                                <p class="text-[9px] text-gray-500 font-medium uppercase">{{ $resultado['forma_pago'] ?? '' }}</p>
                            </div>
                            <p class="text-xl font-black text-white">${{ number_format($resultado['calculos']['total'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Vigencia --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
                        <i class="ri-calendar-check-line text-orange-500 text-sm"></i>
                        <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Vigencia de la Promoción</p>
                    </div>
                    <div class="grid grid-cols-3 divide-x divide-gray-100">
                        <div class="px-4 py-4">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Fecha de Aplicación</p>
                            <p class="text-sm font-black text-emerald-700 uppercase">{{ $resultado['calculos']['fecha_inicio'] ?? '' }}</p>
                        </div>
                        <div class="px-4 py-4">
                            <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Fecha de Término</p>
                            <p class="text-sm font-black text-red-600 uppercase">{{ $resultado['calculos']['fecha_termino'] ?? '' }}</p>
                        </div>
                        <div class="px-4 py-4 bg-indigo-50/60">
                            <p class="text-[8px] font-black text-indigo-500 uppercase tracking-widest mb-1">Próximo Pago</p>
                            <p class="text-sm font-black text-indigo-700 uppercase">{{ $resultado['calculos']['proximo_pago'] ?? '' }}</p>
                        </div>
                    </div>
                </div>

                {{-- Nota legal --}}
                <p class="text-[9px] text-gray-400 text-center font-bold uppercase tracking-widest leading-relaxed">
                    La vigencia se calcula en bloques de 30 días × {{ $resultado['promo']['meses_beneficio'] ?? 0 }} meses de beneficio.<br>
                    El cliente fue notificado por SMS. Guarde este comprobante para su registro.
                </p>

            </div>
        </div>

        {{-- Acciones post-confirmación --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white border border-gray-200 rounded-xl px-5 py-4">
            <button wire:click="nuevaContratacion"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-gray-50 shadow-sm transition-all">
                <i class="ri-user-add-line"></i> Nueva Contratación
            </button>
            <div class="flex items-center gap-3">
                <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-gray-50 shadow-sm transition-all">
                    <i class="ri-printer-line"></i> Imprimir
                </button>
                <a href="{{ route('dashboard') }}"
                   class="inline-flex items-center gap-2 px-6 py-2.5 bg-gray-900 text-white font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-black shadow-sm transition-all">
                    <i class="ri-home-4-line"></i> Panel Principal
                </a>
            </div>
        </div>

    </div>
    @endif

</div>
