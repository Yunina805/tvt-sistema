<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ENCABEZADO --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-red-400"></i>
                <span>Gestión al Suscriptor</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-orange-600">Contratación de Promociones</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Pago Anticipado con Beneficios</h2>
            <p class="text-xs text-gray-400 mt-0.5">Suscriptor → Promoción → Pago → Confirmación · Calcúla días de uso e inicia el día 1 del mes</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 font-bold text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all self-start">
            <i class="ri-arrow-left-line"></i> Panel Principal
        </a>
    </div>

    {{-- STEPPER --}}
    @if($paso < 4)
    <div class="mb-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex">
            @php
                $pasos = [
                    1 => ['label' => 'Suscriptor', 'icon' => 'ri-user-search-line'],
                    2 => ['label' => 'Promoción',  'icon' => 'ri-gift-line'],
                    3 => ['label' => 'Pago',        'icon' => 'ri-secure-payment-line'],
                ];
            @endphp
            @foreach($pasos as $num => $info)
            <div class="flex-1 flex flex-col items-center py-3 px-1 {{ !$loop->last ? 'border-r border-gray-200' : '' }}
                {{ $paso >= $num ? 'bg-orange-50' : 'bg-gray-50' }}">
                <div class="w-7 h-7 rounded-full flex items-center justify-center mb-1
                    {{ $paso > $num ? 'bg-orange-500 text-white' : ($paso == $num ? 'bg-orange-500 text-white' : 'bg-gray-200 text-gray-400') }}">
                    @if($paso > $num)
                        <i class="ri-check-line text-xs"></i>
                    @else
                        <i class="{{ $info['icon'] }} text-xs"></i>
                    @endif
                </div>
                <span class="text-[9px] font-bold uppercase tracking-wider hidden sm:block
                    {{ $paso >= $num ? 'text-orange-600' : 'text-gray-400' }}">
                    {{ $info['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 1 — IDENTIFICAR SUSCRIPTOR
    ================================================================ --}}
    @if($paso == 1)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            <div class="bg-orange-50/60 border-b border-orange-100 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                    <i class="ri-star-smile-line text-orange-500 text-base"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Consultar Suscriptor</p>
                    <p class="text-[10px] font-bold text-orange-500 mt-0.5 uppercase tracking-wider">Solo suscriptores activos aplican</p>
                </div>
            </div>

            <div class="p-6 space-y-4">

                {{-- Buscador --}}
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-user-search-line absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" wire:model="search"
                               placeholder="Nombre o ID del suscriptor..."
                               wire:keydown.enter="buscarCliente"
                               class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 transition-colors placeholder:text-gray-300">
                        <div wire:loading wire:target="buscarCliente" class="absolute right-3.5 top-1/2 -translate-y-1/2">
                            <i class="ri-loader-4-line animate-spin text-orange-400 text-base"></i>
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
                    <i class="ri-keyboard-line text-2xl text-gray-300 block mb-2"></i>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ingrese nombre o ID y presione consultar</p>
                </div>
                @endif

                {{-- Sin resultados --}}
                @if($busquedaHecha && count($resultados) === 0)
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <i class="ri-search-line text-amber-500 text-xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Sin resultados</p>
                        <p class="text-[10px] text-amber-600 mt-0.5">No se encontró ningún suscriptor activo con "<strong>{{ $search }}</strong>"</p>
                    </div>
                </div>
                @endif

                {{-- Resultados --}}
                @if(count($resultados) > 0)
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 border-b border-gray-100">
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">
                            {{ count($resultados) }} resultado(s)
                        </span>
                    </div>
                    <div class="max-h-72 overflow-y-auto divide-y divide-gray-100">
                        @foreach($resultados as $r)
                        <div class="flex items-center justify-between p-3.5 hover:bg-orange-50/50 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-orange-100 flex items-center justify-center flex-shrink-0 transition-colors">
                                    <i class="ri-user-3-line text-gray-400 group-hover:text-orange-500 transition-colors"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <p class="text-xs font-black text-gray-900 uppercase tracking-tight truncate">{{ $r['nombre'] }}</p>
                                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded uppercase bg-emerald-50 text-emerald-600 border border-emerald-100 flex-shrink-0">
                                            {{ $r['estado'] }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[9px] font-bold text-gray-500 uppercase">
                                        <span class="font-mono bg-gray-100 px-1 py-0.5 rounded text-gray-600">{{ $r['id'] }}</span>
                                        <span class="text-gray-300">·</span>
                                        <span class="text-orange-600 truncate max-w-[140px]"><i class="ri-router-line mr-0.5"></i>{{ $r['servicio'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 flex-shrink-0 ml-3">
                                <div class="hidden sm:block text-right">
                                    <p class="text-sm font-black text-gray-900 leading-none">${{ number_format($r['tarifa'], 2) }}</p>
                                    <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">Mensualidad</p>
                                </div>
                                <button wire:click="seleccionarCliente({{ json_encode($r) }})"
                                        class="px-3 py-1.5 bg-white border border-gray-200 text-gray-600 hover:text-orange-600 hover:border-orange-300 hover:bg-orange-50 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all shadow-sm">
                                    Seleccionar
                                </button>
                            </div>
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
         PASO 2 — SELECCIONAR PROMOCIÓN + DESGLOSE
    ================================================================ --}}
    @if($paso == 2)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ── Columna izquierda: ficha + selector de promo ── --}}
        <div class="lg:col-span-4 space-y-4">

            {{-- Ficha del suscriptor --}}
            <div class="bg-gray-900 rounded-xl p-5 text-white shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-5">
                    <i class="ri-star-smile-line text-9xl"></i>
                </div>
                <p class="text-[9px] font-black text-orange-400 uppercase tracking-widest mb-3 border-b border-gray-800 pb-2">
                    Expediente del Suscriptor
                </p>
                <div class="space-y-3 relative z-10">
                    <div>
                        <p class="text-[8px] text-gray-500 uppercase font-bold mb-0.5">Titular</p>
                        <p class="font-black text-white uppercase tracking-tight text-sm">{{ $cliente['nombre'] }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <p class="text-[8px] text-gray-500 uppercase font-bold mb-0.5">ID Suscriptor</p>
                            <p class="font-mono text-[10px] font-black text-orange-400">{{ $cliente['id'] }}</p>
                        </div>
                        <div>
                            <p class="text-[8px] text-gray-500 uppercase font-bold mb-0.5">Sucursal</p>
                            <p class="text-[10px] font-bold text-gray-300">{{ $cliente['sucursal'] }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[8px] text-gray-500 uppercase font-bold mb-0.5">Servicio Contratado</p>
                        <p class="font-bold text-indigo-300 uppercase text-xs">{{ $cliente['servicio'] }}</p>
                    </div>
                    <div class="flex items-center justify-between pt-1">
                        <div class="flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">{{ $cliente['estado'] }}</span>
                        </div>
                        <span class="text-base font-black text-white">
                            ${{ number_format($cliente['tarifa'], 2) }}<span class="text-[9px] text-gray-400 font-bold">/mes</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- Selector de promoción --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex items-center gap-2">
                    <i class="ri-gift-line text-orange-500 text-sm"></i>
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Promociones Disponibles</p>
                    <span class="ml-auto text-[9px] font-black text-orange-600 bg-orange-50 border border-orange-100 px-1.5 py-0.5 rounded uppercase">
                        {{ $cliente['tipo_servicio'] }}
                    </span>
                </div>
                <div class="p-3 space-y-2">
                    @forelse($promociones as $p)
                    @php $mesesGratis = $p['meses_beneficio'] - $p['meses_pago']; @endphp
                    <button wire:click="seleccionarPromo({{ $p['id'] }})"
                            class="w-full p-4 rounded-xl border-2 transition-all text-left group
                                   {{ $promoSeleccionada && $promoSeleccionada['id'] == $p['id']
                                       ? 'border-orange-500 bg-orange-50 shadow-sm'
                                       : 'border-gray-100 hover:border-orange-200 hover:bg-orange-50/30' }}">
                        <div class="flex justify-between items-start gap-2">
                            <div class="flex-1 min-w-0">
                                <p class="text-[11px] font-black uppercase leading-tight
                                    {{ $promoSeleccionada && $promoSeleccionada['id'] == $p['id'] ? 'text-orange-700' : 'text-gray-700 group-hover:text-orange-600' }}">
                                    {{ $p['nombre'] }}
                                </p>
                                <p class="text-[9px] text-gray-400 font-bold uppercase mt-0.5">
                                    Paga <strong>{{ $p['meses_pago'] }}</strong> · Recibe <strong>{{ $p['meses_beneficio'] }}</strong> meses
                                </p>
                                <span class="inline-block mt-1.5 text-[9px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100 px-1.5 py-0.5 rounded-md uppercase">
                                    +{{ $mesesGratis }} {{ $mesesGratis == 1 ? 'mes' : 'meses' }} gratis
                                </span>
                            </div>
                            <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                        {{ $promoSeleccionada && $promoSeleccionada['id'] == $p['id'] ? 'border-orange-500 bg-orange-500' : 'border-gray-300' }}">
                                @if($promoSeleccionada && $promoSeleccionada['id'] == $p['id'])
                                    <i class="ri-check-line text-white text-[10px]"></i>
                                @endif
                            </div>
                        </div>
                    </button>
                    @empty
                    <div class="py-8 text-center text-[10px] font-black text-gray-300 uppercase tracking-widest">
                        Sin promociones para este tipo de servicio
                    </div>
                    @endforelse
                </div>
            </div>

            <button wire:click="$set('paso', 1)"
                    class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5 px-1">
                <i class="ri-arrow-left-line"></i> Cambiar suscriptor
            </button>
        </div>

        {{-- ── Columna derecha: desglose de importes ── --}}
        <div class="lg:col-span-8">

            @if(!$promoSeleccionada)
            <div class="bg-white border-2 border-dashed border-gray-200 rounded-xl flex items-center justify-center py-32">
                <div class="text-center space-y-3">
                    <div class="w-16 h-16 bg-orange-50 border border-orange-100 rounded-2xl flex items-center justify-center mx-auto">
                        <i class="ri-calculator-line text-2xl text-orange-300"></i>
                    </div>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Selecciona una promoción para ver el desglose</p>
                </div>
            </div>
            @else

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                {{-- Header desglose --}}
                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Desglose de Importes</p>
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

                    {{-- Cards desglose --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Días de uso --}}
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Días de Uso</p>
                                    <p class="text-[9px] text-gray-400 font-medium mt-0.5">Prorrateo hasta el día 1 del próx. mes</p>
                                </div>
                                <span class="text-[9px] font-black text-indigo-700 bg-indigo-50 border border-indigo-200 px-2 py-1 rounded-lg uppercase flex-shrink-0">
                                    {{ $calculos['dias_uso'] }} días
                                </span>
                            </div>
                            <p class="text-[9px] text-gray-400 font-medium mb-1">
                                ${{ number_format($calculos['costo_dia'] ?? 0, 2) }}/día × {{ $calculos['dias_uso'] }} días
                            </p>
                            <p class="text-2xl font-black text-gray-900 tracking-tight">${{ number_format($calculos['importe_dias'], 2) }}</p>
                        </div>

                        {{-- Importe promo --}}
                        <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
                            <div class="mb-3">
                                <p class="text-[10px] font-black text-orange-700 uppercase tracking-widest">Importe de la Promoción</p>
                                <p class="text-[9px] text-orange-400 font-medium mt-0.5">
                                    ${{ number_format($cliente['tarifa'], 2) }}/mes × {{ $promoSeleccionada['meses_pago'] }} meses
                                </p>
                            </div>
                            <p class="text-2xl font-black text-orange-600 tracking-tight">${{ number_format($calculos['importe_promo'], 2) }}</p>
                            @php $gratis = $promoSeleccionada['meses_beneficio'] - $promoSeleccionada['meses_pago']; @endphp
                            <span class="inline-block mt-2 text-[9px] font-black text-emerald-700 bg-emerald-50 border border-emerald-200 px-1.5 py-0.5 rounded-md uppercase">
                                +{{ $gratis }} {{ $gratis == 1 ? 'mes' : 'meses' }} de bonificación
                            </span>
                        </div>
                    </div>

                    {{-- Vigencia proyectada --}}
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
                            <i class="ri-calendar-check-line text-orange-500 text-sm"></i>
                            <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Vigencia de la Promoción</p>
                            <span class="ml-auto text-[9px] font-bold text-gray-400 uppercase">
                                {{ $promoSeleccionada['meses_beneficio'] }} × 30 días = {{ $promoSeleccionada['meses_beneficio'] * 30 }} días de beneficio
                            </span>
                        </div>
                        <div class="grid grid-cols-3 divide-x divide-gray-100">
                            <div class="px-4 py-4">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Fecha de Aplicación</p>
                                <p class="text-[11px] font-black text-emerald-700 uppercase">{{ $calculos['fecha_inicio'] }}</p>
                                <p class="text-[9px] text-gray-400 mt-0.5">Día 1 del mes siguiente</p>
                            </div>
                            <div class="px-4 py-4">
                                <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Fecha de Término</p>
                                <p class="text-[11px] font-black text-red-600 uppercase">{{ $calculos['fecha_termino'] }}</p>
                                <p class="text-[9px] text-gray-400 mt-0.5">Fin del beneficio</p>
                            </div>
                            <div class="px-4 py-4 bg-indigo-50/40">
                                <p class="text-[8px] font-black text-indigo-500 uppercase tracking-widest mb-1">Próximo Pago</p>
                                <p class="text-[11px] font-black text-indigo-700 uppercase">{{ $calculos['proximo_pago'] }}</p>
                                <p class="text-[9px] text-indigo-400 mt-0.5">Al vencer la promo</p>
                            </div>
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="flex items-center justify-between pt-1">
                        <button wire:click="$set('paso', 1)"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                            <i class="ri-arrow-left-line"></i> Regresar
                        </button>
                        <button wire:click="irPaso3"
                                class="inline-flex items-center gap-2 px-7 py-3 bg-orange-500 hover:bg-orange-600 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-orange-900/20 transition-all active:scale-95">
                            <i class="ri-secure-payment-line text-base"></i> Continuar al Pago
                        </button>
                    </div>

                </div>
            </div>
            @endif

        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 3 — CONFIRMACIÓN DE PAGO
    ================================================================ --}}
    @if($paso == 3)
    <div class="space-y-5">

        {{-- Resumen del suscriptor + promo --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-user-check-line text-orange-500"></i> Resumen del Suscriptor
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-xs">
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Titular</p>
                    <p class="font-black text-gray-900">{{ $cliente['nombre'] }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">ID Suscriptor</p>
                    <p class="font-mono font-bold text-orange-600">{{ $cliente['id'] }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Servicio</p>
                    <p class="font-bold text-gray-700">{{ $cliente['servicio'] }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Sucursal</p>
                    <p class="font-bold text-gray-700">{{ $cliente['sucursal'] }}</p>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100 flex items-center gap-3">
                <span class="text-[9px] font-black text-orange-700 bg-orange-50 border border-orange-200 px-2.5 py-1 rounded-lg uppercase tracking-widest">
                    <i class="ri-gift-line mr-1"></i>{{ $promoSeleccionada['nombre'] }}
                </span>
                <span class="text-[9px] font-bold text-gray-400 uppercase">
                    {{ $promoSeleccionada['meses_pago'] }} meses pagados · {{ $promoSeleccionada['meses_beneficio'] }} meses de vigencia ·
                    Inicia {{ $calculos['fecha_inicio'] }}
                </span>
            </div>
        </div>

        {{-- Desglose + Método de pago --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Desglose de importes --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-calculator-line text-orange-500"></i> Desglose del Cobro
                </h3>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Días de uso ({{ $calculos['dias_uso'] }} días prorrateo)</span>
                        <span class="font-bold">${{ number_format($calculos['importe_dias'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Promo {{ $promoSeleccionada['nombre'] }} ({{ $promoSeleccionada['meses_pago'] }} meses)</span>
                        <span class="font-bold">${{ number_format($calculos['importe_promo'], 2) }}</span>
                    </div>
                    <div class="border-t border-gray-100 pt-1 flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-bold">${{ number_format($calculos['total'], 2) }}</span>
                    </div>

                    @if($descuentoAplicado)
                    <div class="flex justify-between text-emerald-600">
                        <span class="font-bold">Descuento aplicado</span>
                        <span class="font-bold">— ${{ number_format($montoDescuento, 2) }}</span>
                    </div>
                    @endif

                    <div class="border-t-2 border-orange-200 pt-2 flex justify-between">
                        <span class="font-black text-gray-900">Total a Cobrar</span>
                        <span class="font-black text-orange-600 text-base">
                            ${{ $descuentoAplicado ? number_format($totalConDescuento, 2) : number_format($calculos['total'], 2) }}
                        </span>
                    </div>
                </div>

                {{-- Descuento supervisor --}}
                <div class="mt-4 border-t border-gray-100 pt-3" x-data="{ open: {{ $mostrarDescuento ? 'true' : 'false' }} }">
                    @if($descuentoAplicado)
                        <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-lg p-2.5">
                            <span class="text-[10px] text-emerald-700 font-bold flex items-center gap-1.5">
                                <i class="ri-price-tag-3-line"></i> Descuento de ${{ number_format($montoDescuento, 2) }} aplicado
                            </span>
                            <button wire:click="quitarDescuento" class="text-[10px] text-red-500 font-bold hover:underline">
                                Quitar
                            </button>
                        </div>
                    @else
                        <button @click="open = !open"
                                class="text-[10px] text-gray-500 font-bold flex items-center gap-1.5 hover:text-orange-600 transition-colors">
                            <i class="ri-percent-line"></i> Aplicar descuento de supervisor
                            <i class="ri-arrow-down-s-line transition-transform" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="mt-2 space-y-2">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-1">Monto de descuento ($)</label>
                                    <div class="relative">
                                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold">$</span>
                                        <input type="number" wire:model="montoDescuentoInput" min="0.01" step="0.01"
                                               class="w-full border border-gray-300 rounded-lg pl-6 pr-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300"
                                               placeholder="0.00">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-1">Contraseña supervisor</label>
                                    <input type="password" wire:model="passwordDescuento"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-orange-300"
                                           placeholder="••••••••"
                                           wire:keydown.enter="aplicarDescuento">
                                </div>
                            </div>
                            <button wire:click="aplicarDescuento"
                                    class="w-full py-1.5 bg-gray-900 hover:bg-gray-700 text-white text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">
                                Aplicar Descuento
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Método de pago --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-secure-payment-line text-orange-500"></i> Método de Pago
                </h3>
                <div class="space-y-2">
                    @foreach([
                        'efectivo'      => ['icon' => 'ri-money-dollar-circle-line', 'label' => 'Efectivo'],
                        'transferencia' => ['icon' => 'ri-bank-line',                'label' => 'Transferencia Bancaria'],
                        'tarjeta'       => ['icon' => 'ri-bank-card-line',           'label' => 'Tarjeta'],
                    ] as $val => $mp)
                    <button type="button"
                            wire:click="$set('formaPago', '{{ $val }}')"
                            class="w-full flex items-center gap-3 p-3 border rounded-xl transition-all text-left
                                {{ $formaPago === $val
                                    ? 'border-orange-500 bg-orange-50 shadow-sm'
                                    : 'border-gray-200 hover:border-orange-300 hover:bg-orange-50' }}">
                        <i class="{{ $mp['icon'] }} text-lg {{ $formaPago === $val ? 'text-orange-600' : 'text-gray-400' }}"></i>
                        <span class="text-xs font-bold flex-1 {{ $formaPago === $val ? 'text-orange-700' : 'text-gray-700' }}">
                            {{ $mp['label'] }}
                        </span>
                        <i class="{{ $formaPago === $val ? 'ri-checkbox-circle-fill text-orange-500' : 'ri-checkbox-blank-circle-line text-gray-300' }}"></i>
                    </button>
                    @endforeach
                </div>

                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-3">
                    <p class="text-[10px] text-amber-700 font-bold flex items-center gap-1.5 mb-1.5">
                        <i class="ri-alert-line"></i> Al confirmar el sistema:
                    </p>
                    <ul class="text-[10px] text-amber-600 space-y-0.5 list-disc list-inside">
                        <li>Activa la promoción en el suscriptor</li>
                        <li>Registra el ingreso en caja de la sucursal</li>
                        <li>Genera el comprobante digital</li>
                        <li>Envía SMS de confirmación al suscriptor</li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Barra de total + confirmar --}}
        <div class="flex items-center justify-between gap-5 bg-gray-900 rounded-xl px-6 py-5">
            <div>
                <p class="text-[9px] font-black text-orange-400 uppercase tracking-widest mb-1">Total a Liquidar</p>
                <p class="text-3xl font-black text-white tracking-tight">
                    ${{ $descuentoAplicado ? number_format($totalConDescuento, 2) : number_format($calculos['total'], 2) }}
                </p>
                <p class="text-[9px] text-gray-500 font-medium mt-1 uppercase">
                    {{ $formaPago }} · {{ $promoSeleccionada['meses_pago'] }} meses anticipados + prorrateo
                </p>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="$set('paso', 2)"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-800 hover:bg-gray-700 border border-gray-700 text-gray-300 font-bold text-xs uppercase tracking-widest rounded-xl transition-all">
                    <i class="ri-arrow-left-line"></i> Regresar
                </button>
                <button x-data
                        @click="$confirm(
                            '¿Confirmar promoción {{ addslashes($promoSeleccionada['nombre']) }} para {{ addslashes($cliente['nombre']) }}?\n\nTotal a cobrar: ${{ $descuentoAplicado ? number_format($totalConDescuento, 2) : number_format($calculos['total'], 2) }}',
                            () => $wire.confirmarContratacion(),
                            { confirmText: 'Sí, activar promoción', title: 'Confirmar Pago' }
                        )"
                        class="inline-flex items-center gap-2 px-7 py-3 bg-orange-500 hover:bg-orange-600 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-orange-900/30 transition-all active:scale-95">
                    <i class="ri-shield-check-line text-base"></i> Confirmar y Activar
                </button>
            </div>
        </div>

    </div>
    @endif

    {{-- ================================================================
         PASO 4 — RECIBO + FICHA DE ESTADO
    ================================================================ --}}
    @if($paso == 4)
    <div class="max-w-3xl mx-auto space-y-5">

        {{-- Banner éxito --}}
        <div class="bg-emerald-600 rounded-xl px-6 py-8 text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle, white 1px, transparent 0); background-size: 24px 24px;"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="ri-star-smile-fill text-3xl"></i>
                </div>
                <h2 class="text-xl font-black uppercase tracking-tight">¡Promoción Activada!</h2>
                <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest mt-1">
                    Cobro registrado · SMS enviado · Beneficio activado
                </p>
                <span class="inline-block mt-3 font-mono text-sm font-black text-white bg-white/20 px-4 py-1.5 rounded-lg">
                    {{ $resultado['folio'] ?? '' }}
                </span>
            </div>
        </div>

        {{-- FICHA DE IMPACTO EN ESTADO DEL SUSCRIPTOR --}}
        <div class="bg-white border-2 border-orange-200 rounded-xl overflow-hidden shadow-sm">
            <div class="bg-orange-500 px-5 py-3.5 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 bg-white/20 rounded-lg flex items-center justify-center">
                        <i class="ri-star-fill text-white text-sm"></i>
                    </div>
                    <p class="text-[11px] font-black text-white uppercase tracking-widest">Estado del Suscriptor</p>
                </div>
                <span class="text-[9px] font-black text-orange-100 bg-white/20 px-2.5 py-1 rounded-lg uppercase tracking-widest">
                    {{ $resultado['cliente']['id'] ?? '' }}
                </span>
            </div>
            <div class="p-5">
                <div class="flex items-center justify-between gap-3 mb-4">
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Titular</p>
                        <p class="text-base font-black text-gray-900 uppercase">{{ $resultado['cliente']['nombre'] ?? '' }}</p>
                        <p class="text-[10px] font-bold text-gray-500 mt-0.5">{{ $resultado['cliente']['servicio'] ?? '' }} · ${{ number_format($resultado['cliente']['tarifa'] ?? 0, 2) }}/mes</p>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <span class="inline-flex items-center gap-1.5 bg-orange-100 border border-orange-300 text-orange-800 text-[9px] font-black uppercase tracking-widest px-3 py-1.5 rounded-lg">
                            <span class="w-1.5 h-1.5 rounded-full bg-orange-500 animate-pulse"></span>
                            PROMOCIÓN ACTIVA
                        </span>
                        <p class="text-[9px] font-bold text-orange-500 mt-1.5 uppercase">
                            {{ $resultado['promo']['nombre'] ?? '' }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-3">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-3.5 text-center">
                        <i class="ri-calendar-check-line text-emerald-500 text-lg mb-1 block"></i>
                        <p class="text-[8px] font-black text-emerald-700 uppercase tracking-widest mb-1">Fecha de Inicio</p>
                        <p class="text-sm font-black text-emerald-800">{{ $resultado['calculos']['fecha_inicio'] ?? '' }}</p>
                        <p class="text-[9px] text-emerald-500 mt-0.5">Día 1 del mes siguiente</p>
                    </div>
                    <div class="bg-red-50 border border-red-200 rounded-xl p-3.5 text-center">
                        <i class="ri-calendar-close-line text-red-400 text-lg mb-1 block"></i>
                        <p class="text-[8px] font-black text-red-600 uppercase tracking-widest mb-1">Fecha de Término</p>
                        <p class="text-sm font-black text-red-700">{{ $resultado['calculos']['fecha_termino'] ?? '' }}</p>
                        <p class="text-[9px] text-red-400 mt-0.5">{{ ($resultado['promo']['meses_beneficio'] ?? 0) }} × 30 días</p>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-200 rounded-xl p-3.5 text-center">
                        <i class="ri-calendar-2-line text-indigo-500 text-lg mb-1 block"></i>
                        <p class="text-[8px] font-black text-indigo-600 uppercase tracking-widest mb-1">Próximo Pago</p>
                        <p class="text-sm font-black text-indigo-700">{{ $resultado['calculos']['proximo_pago'] ?? '' }}</p>
                        <p class="text-[9px] text-indigo-400 mt-0.5">Al vencer la promoción</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recibo imprimible --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            <div class="bg-gray-900 px-6 py-5 flex items-center justify-between">
                <div>
                    <p class="text-[9px] font-black text-orange-400 uppercase tracking-widest mb-1">Tu Visión Telecable — Comprobante de Promoción</p>
                    <p class="text-lg font-black text-white uppercase tracking-tight">{{ $resultado['promo']['nombre'] ?? '' }}</p>
                    <p class="text-[10px] font-bold text-gray-400 mt-0.5">{{ $resultado['fecha_registro'] ?? '' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[8px] font-black text-gray-500 uppercase tracking-widest mb-1">Folio</p>
                    <p class="font-mono text-sm font-black text-orange-400">{{ $resultado['folio'] ?? '' }}</p>
                    <p class="text-[9px] font-bold text-gray-500 uppercase mt-1">{{ $resultado['forma_pago'] ?? '' }}</p>
                </div>
            </div>

            <div class="p-6 space-y-4">
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-5 py-3">
                        <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Desglose del Cobro</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="flex items-center justify-between px-5 py-3.5">
                            <div>
                                <p class="text-[10px] font-black text-gray-700 uppercase">Días de Uso (Prorrateo)</p>
                                <p class="text-[9px] text-gray-400 font-medium mt-0.5">
                                    {{ $resultado['calculos']['dias_uso'] ?? 0 }} días ·
                                    ${{ number_format($resultado['calculos']['costo_dia'] ?? 0, 2) }}/día ·
                                    Para iniciar el día 1 del mes
                                </p>
                            </div>
                            <p class="text-sm font-black text-gray-800">${{ number_format($resultado['calculos']['importe_dias'] ?? 0, 2) }}</p>
                        </div>
                        <div class="flex items-center justify-between px-5 py-3.5">
                            <div>
                                <p class="text-[10px] font-black text-gray-700 uppercase">Importe de la Promoción</p>
                                <p class="text-[9px] text-gray-400 font-medium mt-0.5">
                                    {{ $resultado['promo']['meses_pago'] ?? 0 }} meses pagados ·
                                    {{ $resultado['promo']['meses_beneficio'] ?? 0 }} meses de vigencia
                                </p>
                            </div>
                            <p class="text-sm font-black text-orange-600">${{ number_format($resultado['calculos']['importe_promo'] ?? 0, 2) }}</p>
                        </div>
                        @if(($resultado['descuento'] ?? 0) > 0)
                        <div class="flex items-center justify-between px-5 py-3.5 bg-emerald-50">
                            <p class="text-[10px] font-black text-emerald-700 uppercase">Descuento Aplicado</p>
                            <p class="text-sm font-black text-emerald-600">— ${{ number_format($resultado['descuento'], 2) }}</p>
                        </div>
                        @endif
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-900">
                            <div>
                                <p class="text-[10px] font-black text-orange-400 uppercase tracking-widest">Total Cobrado</p>
                                <p class="text-[9px] text-gray-500 font-medium uppercase">{{ $resultado['forma_pago'] ?? '' }}</p>
                            </div>
                            <p class="text-xl font-black text-white">${{ number_format($resultado['total_cobrado'] ?? 0, 2) }}</p>
                        </div>
                    </div>
                </div>

                <p class="text-[9px] text-gray-400 text-center font-bold uppercase tracking-widest leading-relaxed">
                    Vigencia calculada en bloques de 30 días × {{ $resultado['promo']['meses_beneficio'] ?? 0 }} meses de beneficio ·
                    El suscriptor fue notificado por SMS
                </p>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 bg-white border border-gray-200 rounded-xl px-5 py-4">
            <button wire:click="nuevaContratacion"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-gray-200 transition-all">
                <i class="ri-add-circle-line"></i> Nueva Contratación
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
