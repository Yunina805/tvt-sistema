<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================
         ENCABEZADO
    ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-red-400"></i>
                <span>Gestión al Suscriptor</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-red-600">Servicios Adicionales</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Contratación de Servicio Adicional</h2>
            <p class="text-xs text-gray-400 mt-0.5">Servicio → Suscriptor → Cobro → Técnico → Reporte generado</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 font-bold text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all self-start">
            <i class="ri-arrow-left-line"></i> Panel Principal
        </a>
    </div>

    {{-- ================================================================
         STEPPER
    ================================================================ --}}
    @if($paso < 5)
    <div class="mb-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex">
            @php
                $pasos = [
                    1 => ['label' => 'Servicio',    'icon' => 'ri-add-box-line'],
                    2 => ['label' => 'Suscriptor',  'icon' => 'ri-user-search-line'],
                    3 => ['label' => 'Cobro',        'icon' => 'ri-secure-payment-line'],
                    4 => ['label' => 'Técnico',      'icon' => 'ri-tools-line'],
                ];
            @endphp
            @foreach($pasos as $num => $info)
            <div class="flex-1 flex flex-col items-center py-3 px-1 {{ !$loop->last ? 'border-r border-gray-200' : '' }}
                {{ $paso >= $num ? 'bg-red-50' : 'bg-gray-50' }}">
                <div class="w-7 h-7 rounded-full flex items-center justify-center mb-1
                    {{ $paso > $num ? 'bg-red-600 text-white' : ($paso == $num ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-400') }}">
                    @if($paso > $num)
                        <i class="ri-check-line text-xs"></i>
                    @else
                        <i class="{{ $info['icon'] }} text-xs"></i>
                    @endif
                </div>
                <span class="text-[9px] font-bold uppercase tracking-wider hidden sm:block
                    {{ $paso >= $num ? 'text-red-700' : 'text-gray-400' }}">
                    {{ $info['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 1 — SELECCIÓN DE SERVICIO ADICIONAL
    ================================================================ --}}
    @if($paso === 1)
    <div class="space-y-5">

        <div class="flex items-center justify-between">
            <p class="text-[11px] font-black text-gray-500 uppercase tracking-widest flex items-center gap-2">
                <i class="ri-price-tag-3-line text-red-500"></i> Catálogo de Servicios Adicionales
            </p>
            <span class="text-[9px] font-bold text-red-600 bg-red-50 border border-red-100 px-2 py-1 rounded-md uppercase tracking-widest">
                {{ count($servicios) }} disponibles
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($servicios as $s)
            @php
                $sel     = $servicioId === $s['id'];
                $color   = $s['color'] ?? 'red';
                $iconBg  = "bg-{$color}-100";
                $iconTxt = "text-{$color}-600";
                $border  = $sel ? "border-red-500 shadow-lg ring-1 ring-red-500/20" : "border-gray-200 hover:border-red-300 hover:shadow-md";
                $bg      = $sel ? "bg-red-50/60" : "bg-white";
            @endphp
            <button wire:click="seleccionarServicio({{ $s['id'] }})"
                    class="relative text-left rounded-xl border-2 p-6 transition-all {{ $border }} {{ $bg }} group">

                {{-- Indicador de selección --}}
                <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 flex items-center justify-center
                            {{ $sel ? 'border-red-500 bg-red-500' : 'border-gray-300 group-hover:border-red-300' }}">
                    @if($sel)
                        <i class="ri-check-line text-white text-[10px]"></i>
                    @endif
                </div>

                {{-- Ícono --}}
                <div class="w-12 h-12 rounded-xl {{ $iconBg }} flex items-center justify-center mb-4 transition-colors">
                    <i class="{{ $s['icon'] }} {{ $iconTxt }} text-2xl"></i>
                </div>

                <h4 class="font-black text-gray-900 text-base uppercase tracking-tight mb-1 pr-6">{{ $s['nombre'] }}</h4>
                <p class="text-[10px] text-gray-400 font-medium mb-4 leading-relaxed">{{ $s['descripcion'] }}</p>

                {{-- Precios --}}
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">Instalación</span>
                        <p class="text-lg font-black text-gray-900 tracking-tight">
                            @if($s['instalacion'] == 0)
                                <span class="text-emerald-600">Sin costo</span>
                            @else
                                ${{ number_format($s['instalacion'], 2) }}
                            @endif
                        </p>
                        <p class="text-[9px] text-gray-400 font-medium">pago único</p>
                    </div>
                    <div class="bg-red-50 border border-red-100 rounded-lg px-4 py-3">
                        <span class="text-[9px] font-black text-red-500 uppercase tracking-widest block mb-0.5">Mensualidad</span>
                        <p class="text-lg font-black text-red-600 tracking-tight">+${{ number_format($s['mensualidad'], 2) }}</p>
                        <p class="text-[9px] text-red-400 font-medium">ajuste mensual</p>
                    </div>
                </div>

                {{-- Tags --}}
                @if(!empty($s['tags']))
                <div class="flex flex-wrap gap-1.5 mt-3">
                    @foreach($s['tags'] as $tag)
                        <span class="text-[9px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md uppercase tracking-widest">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
            </button>
            @endforeach
        </div>

        {{-- CTA --}}
        <div class="flex justify-end pt-2">
            <button wire:click="irPaso2"
                    @if(!$servicioId) disabled @endif
                    class="inline-flex items-center gap-2 px-8 py-3.5 bg-red-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-lg shadow-red-200 transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed">
                Identificar Suscriptor <i class="ri-arrow-right-line"></i>
            </button>
        </div>

    </div>
    @endif

    {{-- ================================================================
         PASO 2 — IDENTIFICAR SUSCRIPTOR
    ================================================================ --}}
    @if($paso === 2)
    <div class="max-w-2xl mx-auto space-y-4">

        {{-- Contexto del servicio elegido --}}
        @if(!empty($servicioSel))
        <div class="flex items-center gap-3 bg-white border border-gray-200 rounded-xl px-5 py-3.5 shadow-sm">
            <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                <i class="{{ $servicioSel['icon'] }} text-red-600 text-base"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Servicio Seleccionado</p>
                <p class="text-sm font-black text-gray-900 uppercase truncate">{{ $servicioSel['nombre'] }}</p>
            </div>
            <div class="flex gap-3 text-right flex-shrink-0">
                <div>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Instalación</p>
                    <p class="text-xs font-black text-gray-700">
                        {{ $servicioSel['instalacion'] == 0 ? 'Sin costo' : '$'.number_format($servicioSel['instalacion'],2) }}
                    </p>
                </div>
                <div>
                    <p class="text-[9px] font-bold text-red-400 uppercase tracking-widest">Mensualidad</p>
                    <p class="text-xs font-black text-red-600">+${{ number_format($servicioSel['mensualidad'],2) }}</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Búsqueda --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                <i class="ri-user-search-line text-red-500"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Identificar Suscriptor</p>
            </div>
            <div class="p-5 space-y-4">

                {{-- Buscador --}}
                @if(empty($suscriptor))
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" wire:model="busqueda"
                               wire:keydown.enter="buscar"
                               placeholder="Nombre, teléfono o ID del suscriptor..."
                               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-colors placeholder:text-gray-300">
                    </div>
                    <button wire:click="buscar"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm">
                        Buscar
                    </button>
                </div>
                @endif

                {{-- Resultados --}}
                @if(!empty($resultados))
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        {{ count($resultados) }} resultado(s) — haz clic para seleccionar
                    </p>
                    @foreach($resultados as $r)
                    @php
                        $estadoClass = match($r['estado'] ?? 'ACTIVO') {
                            'ACTIVO'     => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'SUSPENDIDO' => 'bg-amber-100 text-amber-700 border-amber-200',
                            'CANCELADO'  => 'bg-red-100 text-red-700 border-red-200',
                            default      => 'bg-gray-100 text-gray-600 border-gray-200',
                        };
                    @endphp
                    <button wire:click="seleccionarSuscriptor({{ $r['id'] }})"
                            class="w-full text-left p-4 rounded-xl border-2 border-gray-200 hover:border-red-400 hover:bg-red-50/50 transition-all group">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-gray-900 text-sm uppercase">{{ $r['nombre'] }}</p>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1.5">
                                    <span class="flex items-center gap-1 text-[10px] font-bold text-gray-400 uppercase">
                                        <i class="ri-hashtag text-gray-300"></i> {{ $r['clave'] }}
                                    </span>
                                    <span class="flex items-center gap-1 text-[10px] font-bold text-gray-400 uppercase">
                                        <i class="ri-phone-line text-gray-300"></i> {{ $r['telefono'] }}
                                    </span>
                                    <span class="flex items-center gap-1 text-[10px] font-bold text-gray-400 uppercase">
                                        <i class="ri-map-pin-line text-orange-400"></i> {{ $r['domicilio'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                                <span class="text-[9px] font-black uppercase tracking-widest border px-2 py-0.5 rounded-md {{ $estadoClass }}">
                                    {{ $r['estado'] }}
                                </span>
                                <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md uppercase">
                                    {{ $r['tarifa_actual'] }}
                                </span>
                            </div>
                        </div>
                    </button>
                    @endforeach
                </div>
                @elseif($busquedaRealizada && empty($suscriptor))
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <i class="ri-search-line text-amber-500 text-xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Sin resultados</p>
                        <p class="text-[10px] text-amber-600 mt-0.5">No se encontró ningún suscriptor con ese criterio.</p>
                    </div>
                </div>
                @endif

                {{-- Suscriptor seleccionado --}}
                @if(!empty($suscriptor))
                @php
                    $estadoClass = match($suscriptor['estado'] ?? 'ACTIVO') {
                        'ACTIVO'     => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                        'SUSPENDIDO' => 'bg-amber-100 text-amber-700 border-amber-200',
                        'CANCELADO'  => 'bg-red-100 text-red-700 border-red-200',
                        default      => 'bg-gray-100 text-gray-600 border-gray-200',
                    };
                @endphp
                <div class="border-2 border-red-300 bg-red-50/40 rounded-xl p-5 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <span class="text-[9px] font-black text-red-600 bg-red-100 border border-red-200 px-2 py-1 rounded-md uppercase tracking-widest">
                            Suscriptor confirmado
                        </span>
                        <span class="font-mono text-xs font-black text-gray-600 bg-white border border-gray-200 px-2.5 py-1 rounded-md">
                            {{ $suscriptor['clave'] }}
                        </span>
                    </div>
                    <div>
                        <p class="text-lg font-black text-gray-900 uppercase tracking-tight">{{ $suscriptor['nombre'] }}</p>
                        <div class="flex flex-wrap gap-x-5 gap-y-1.5 mt-2">
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 uppercase">
                                <i class="ri-phone-line text-gray-400"></i> {{ $suscriptor['telefono'] }}
                            </span>
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 uppercase">
                                <i class="ri-map-pin-line text-orange-400"></i> {{ $suscriptor['domicilio'] }}
                            </span>
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 uppercase">
                                <i class="ri-store-2-line text-gray-400"></i> {{ $suscriptor['sucursal'] }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 flex-wrap">
                        <span class="text-[9px] font-black uppercase tracking-widest border px-2.5 py-1 rounded-lg {{ $estadoClass }}">
                            Estado: {{ $suscriptor['estado'] }}
                        </span>
                        <span class="text-[9px] font-bold text-indigo-700 bg-indigo-50 border border-indigo-200 px-2.5 py-1 rounded-lg uppercase tracking-widest">
                            <i class="ri-broadcast-line"></i> {{ $suscriptor['tarifa_actual'] }}
                        </span>
                        <span class="text-[9px] font-bold text-gray-500 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-lg uppercase tracking-widest">
                            {{ $suscriptor['tipo_servicio'] }}
                        </span>
                    </div>
                    <button wire:click="limpiarSuscriptor"
                            class="text-[10px] font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest transition-colors flex items-center gap-1">
                        <i class="ri-close-circle-line"></i> Cambiar suscriptor
                    </button>
                </div>
                @endif

            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-5 py-3.5 flex items-center justify-between">
                <button wire:click="$set('paso', 1)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Cambiar servicio
                </button>
                <button wire:click="irPaso3"
                        @if(empty($suscriptor)) disabled @endif
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-sm transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed">
                    Confirmar y Continuar <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </div>

    </div>
    @endif

    {{-- ================================================================
         PASO 3 — COBRO
    ================================================================ --}}
    @if($paso === 3)
    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i class="ri-receipt-line text-emerald-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Liquidación del Servicio</p>
                    <p class="text-[10px] text-gray-400">{{ $suscriptor['nombre'] ?? '' }}</p>
                </div>
            </div>

            {{-- Desglose --}}
            <div class="p-6 space-y-1">
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Servicio adicional</span>
                    <span class="text-xs font-black text-red-600 uppercase">{{ $servicioSel['nombre'] ?? '' }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold text-gray-600 uppercase">Cargo por instalación</span>
                        <p class="text-[9px] text-gray-400 mt-0.5 uppercase font-medium">Pago único al contratar</p>
                    </div>
                    <span class="text-sm font-black text-gray-900">
                        @if(($servicioSel['instalacion'] ?? 0) == 0)
                            <span class="text-emerald-600 text-xs font-black uppercase">Sin costo</span>
                        @else
                            ${{ number_format($servicioSel['instalacion'], 2) }}
                        @endif
                    </span>
                </div>
                <div class="flex justify-between items-start py-3 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold text-gray-600 uppercase">Ajuste mensualidad</span>
                        <p class="text-[9px] text-gray-400 mt-0.5 uppercase font-medium">Se suma a la renta mensual del suscriptor</p>
                    </div>
                    <span class="text-sm font-black text-gray-900">+${{ number_format($servicioSel['mensualidad'] ?? 0, 2) }}</span>
                </div>

                {{-- Total --}}
                <div class="flex justify-between items-center mt-3 bg-gray-900 rounded-xl px-5 py-4">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total a Cobrar Hoy</span>
                    @php $total = ($servicioSel['instalacion'] ?? 0) + ($servicioSel['mensualidad'] ?? 0); @endphp
                    <span class="text-2xl font-black text-white tracking-tight">${{ number_format($total, 2) }}</span>
                </div>
            </div>

            {{-- Método de pago + Validación --}}
            <div class="px-6 pb-6 space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Método de pago</label>
                    <select wire:model="metodoPago"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-colors">
                        <option value="efectivo">Efectivo en ventanilla</option>
                        <option value="tarjeta">Tarjeta — Terminal bancaria</option>
                        <option value="transferencia">Transferencia / Depósito</option>
                    </select>
                </div>

                <div class="border-2 border-dashed border-red-200 rounded-xl p-4 bg-red-50/50">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="confirmacionCaja"
                               class="mt-0.5 h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                        <div>
                            <p class="text-[11px] font-black text-red-900 uppercase tracking-widest mb-1">Validación de Cajero</p>
                            <p class="text-[10px] text-red-600 leading-relaxed font-medium">
                                Certifico que el pago fue recibido e ingresado a caja física. Esta acción afectará el historial y la mensualidad del suscriptor.
                            </p>
                        </div>
                    </label>
                </div>

                @if(!$confirmacionCaja)
                <div class="flex items-center gap-2 text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2.5">
                    <i class="ri-alert-line text-base flex-shrink-0"></i>
                    Confirme la recepción del pago en caja para continuar
                </div>
                @endif

                <button wire:click="confirmarCobro"
                        @if(!$confirmacionCaja) disabled @endif
                        class="w-full py-3.5 bg-red-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-md shadow-red-200 transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <i class="ri-check-double-line text-base"></i> Confirmar Pago y Registrar
                </button>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-3.5">
                <button wire:click="$set('paso', 2)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Cambiar suscriptor
                </button>
            </div>

        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 4 — ASIGNACIÓN TÉCNICA
    ================================================================ --}}
    @if($paso === 4)
    <div class="max-w-2xl mx-auto space-y-5">

        {{-- Resumen de la orden --}}
        <div class="bg-gray-900 rounded-xl p-5 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">Orden de Servicio</p>
                <p class="text-sm font-black text-white uppercase truncate">{{ $suscriptor['nombre'] ?? '' }}</p>
                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    <span class="inline-flex items-center gap-1 text-[10px] font-black text-red-300 bg-white/10 px-2 py-0.5 rounded-md uppercase">
                        <i class="{{ $servicioSel['icon'] ?? 'ri-add-box-line' }} text-xs"></i>
                        {{ $servicioSel['nombre'] ?? '' }}
                    </span>
                    <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $suscriptor['sucursal'] ?? '' }}</span>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Cobro registrado</p>
                <p class="text-lg font-black text-emerald-400">
                    ${{ number_format(($servicioSel['instalacion'] ?? 0) + ($servicioSel['mensualidad'] ?? 0), 2) }}
                </p>
            </div>
        </div>

        {{-- Panel técnico --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                <i class="ri-tools-line text-orange-500"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Asignación de Personal Técnico</p>
                <span class="ml-auto text-[9px] font-bold text-red-600 bg-red-50 border border-red-100 px-2 py-1 rounded-md uppercase">Requerido</span>
            </div>

            <div class="p-5 space-y-4">

                @if(empty($tecnicos))
                {{-- Sin técnicos en DB --}}
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <i class="ri-user-unfollow-line text-amber-500 text-xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Sin técnicos disponibles</p>
                        <p class="text-[10px] text-amber-600 mt-0.5">Registra empleados con área TÉCNICO_CAMPO o TÉCNICO_INSTALACIONES en el módulo RRHH.</p>
                    </div>
                </div>
                @else
                {{-- Grid de tarjetas de técnicos --}}
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Técnico responsable</label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 max-h-56 overflow-y-auto pr-1">
                        @foreach($tecnicos as $t)
                        <button wire:click="$set('tecnicoId', {{ $t['id'] }})"
                                class="text-left p-3.5 rounded-xl border-2 transition-all
                                    {{ $tecnicoId == $t['id']
                                        ? 'border-red-500 bg-red-50 shadow-sm'
                                        : 'border-gray-200 hover:border-red-300 hover:bg-red-50/40' }}">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full {{ $tecnicoId == $t['id'] ? 'bg-red-100' : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
                                    <i class="ri-user-star-line {{ $tecnicoId == $t['id'] ? 'text-red-600' : 'text-gray-400' }} text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-black text-gray-900 uppercase truncate">{{ $t['nombre'] }}</p>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $t['clave'] }} · {{ str_replace('_', ' ', $t['area'] ?? '') }}</p>
                                </div>
                                @if($tecnicoId == $t['id'])
                                <i class="ri-checkbox-circle-fill text-red-500 ml-auto flex-shrink-0"></i>
                                @endif
                            </div>
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- SMS --}}
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center gap-4">
                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                        <input type="checkbox" wire:model="notificarSms"
                               class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <div>
                            <p class="text-[11px] font-black text-indigo-800 uppercase tracking-widest">Notificar al técnico por SMS</p>
                            <p class="text-[10px] text-indigo-500 mt-0.5">Se enviará aviso automático al generar la orden de servicio</p>
                        </div>
                    </label>
                    <i class="ri-message-3-line text-2xl text-indigo-300 flex-shrink-0"></i>
                </div>

                @if(!$tecnicoId && !empty($tecnicos))
                <div class="flex items-center gap-2 text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2.5">
                    <i class="ri-alert-line text-base flex-shrink-0"></i>
                    Selecciona un técnico para generar el reporte de servicio
                </div>
                @endif

                <button wire:click="generarReporte"
                        @if(!$tecnicoId) disabled @endif
                        class="w-full py-3.5 bg-red-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-md shadow-red-200 transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <i class="ri-tools-fill text-base"></i> Generar Reporte de Servicio
                </button>

            </div>
        </div>

    </div>
    @endif

    {{-- ================================================================
         PASO 5 — ÉXITO
    ================================================================ --}}
    @if($paso === 5)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Banner --}}
            <div class="bg-emerald-600 px-8 py-10 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 opacity-10"
                     style="background-image: radial-gradient(circle, white 1px, transparent 0); background-size: 24px 24px;"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="ri-checkbox-circle-fill text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-black uppercase tracking-tight">Servicio Adicional Procesado</h2>
                    <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest mt-1">Cobro registrado · Servicio asociado · Reporte generado</p>
                </div>
            </div>

            {{-- Resumen --}}
            <div class="p-6 space-y-4">
                <div class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-white border-b border-gray-100 px-4 py-3">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Resumen de la Operación</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @php
                            $tecnicoSel = collect($tecnicos)->firstWhere('id', $tecnicoId);
                            $filas = [
                                ['icon' => 'ri-file-text-line',   'label' => 'Folio Reporte',       'value' => $folioReporte,                                      'mono' => true, 'color' => 'text-red-600'],
                                ['icon' => 'ri-user-line',        'label' => 'Suscriptor',           'value' => $suscriptor['nombre'] ?? '—'],
                                ['icon' => 'ri-hashtag',          'label' => 'Clave',                'value' => $suscriptor['clave'] ?? '—'],
                                ['icon' => 'ri-store-2-line',     'label' => 'Sucursal',             'value' => $suscriptor['sucursal'] ?? '—'],
                                ['icon' => 'ri-add-box-line',     'label' => 'Servicio Adicional',   'value' => $servicioSel['nombre'] ?? '—',  'badge' => 'red'],
                                ['icon' => 'ri-user-star-line',   'label' => 'Técnico Asignado',     'value' => $tecnicoSel['nombre'] ?? '—'],
                                ['icon' => 'ri-coins-line',       'label' => 'Total Cobrado',        'value' => '$'.number_format(($servicioSel['instalacion']??0)+($servicioSel['mensualidad']??0),2), 'badge' => 'emerald'],
                            ];
                        @endphp
                        @foreach($filas as $f)
                        <div class="flex items-center gap-4 px-4 py-3">
                            <i class="{{ $f['icon'] }} text-gray-400 text-base flex-shrink-0"></i>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest w-32 flex-shrink-0">{{ $f['label'] }}</span>
                            @if(isset($f['badge']) && $f['badge'] === 'red')
                                <span class="text-[10px] font-black text-red-700 bg-red-50 border border-red-100 px-2 py-0.5 rounded-md uppercase">{{ $f['value'] }}</span>
                            @elseif(isset($f['badge']) && $f['badge'] === 'emerald')
                                <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md">{{ $f['value'] }}</span>
                            @elseif(isset($f['mono']))
                                <span class="font-mono font-black {{ $f['color'] ?? 'text-gray-800' }} text-sm tracking-wider">{{ $f['value'] }}</span>
                            @else
                                <span class="text-xs font-bold text-gray-800 uppercase">{{ $f['value'] }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                @if($notificarSms)
                <div class="flex items-center gap-3 bg-indigo-50 border border-indigo-100 rounded-lg px-4 py-3">
                    <i class="ri-message-3-line text-indigo-500 text-base flex-shrink-0"></i>
                    <p class="text-[10px] font-bold text-indigo-700 uppercase tracking-widest">SMS enviado al técnico correctamente</p>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <button wire:click="$set('paso', 1)"
                            class="py-3.5 bg-gray-100 text-gray-700 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-add-circle-line"></i> Nuevo Servicio
                    </button>
                    <button wire:click="finalizar"
                            class="py-3.5 bg-gray-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm flex items-center justify-center gap-2">
                        <i class="ri-arrow-right-line"></i> Ver Reportes
                    </button>
                </div>
            </div>

        </div>
    </div>
    @endif

</div>
