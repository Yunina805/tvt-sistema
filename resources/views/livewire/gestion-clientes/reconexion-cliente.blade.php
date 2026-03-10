<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ── ENCABEZADO ─────────────────────────────────────────────────────── --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-orange-400"></i>
                <span>Gestión al <strong>Suscriptor</strong></span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-orange-600">Reconexión de Servicio</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Reconexión de Servicio</h2>
            <p class="text-xs text-gray-400 mt-0.5">Restablecimiento para cuentas suspendidas · Liquida adeudo y genera reporte técnico</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>

    {{-- ── STEPPER ──────────────────────────────────────────────────────────── --}}
    @if($paso < 6)
    @php
        $pasos = [
            1 => ['label' => 'Tipo',      'sub' => 'Seleccionar flujo',      'icon' => 'ri-git-merge-line'],
            2 => ['label' => 'Suscriptor','sub' => 'Localizar cuenta',       'icon' => 'ri-user-search-line'],
            3 => ['label' => 'Adeudo',    'sub' => 'Liquidar saldo',         'icon' => 'ri-money-dollar-circle-line'],
            4 => ['label' => 'Días Uso',  'sub' => 'Cobro proporcional',     'icon' => 'ri-calendar-check-line'],
            5 => ['label' => 'Cierre',    'sub' => 'Técnico y comodato',     'icon' => 'ri-checkbox-circle-line'],
        ];
    @endphp
    <div class="mb-8 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex">
            @foreach($pasos as $num => $p)
            @php
                $isActive    = $paso === $num;
                $isCompleted = $paso > $num;
                $isLast      = $num === count($pasos);
            @endphp
            <div class="flex-1 relative
                {{ $isActive ? 'bg-orange-600' : ($isCompleted ? 'bg-orange-50' : 'bg-white') }}
                {{ !$isLast ? 'border-r border-gray-200' : '' }}">
                @if(!$isLast)
                <div class="absolute right-0 top-0 bottom-0 w-3 z-10 overflow-hidden">
                    <svg viewBox="0 0 12 48" class="h-full w-3 {{ $isActive ? 'text-orange-600' : ($isCompleted ? 'text-orange-50' : 'text-white') }}" preserveAspectRatio="none">
                        <path d="M0,0 L8,24 L0,48 L12,48 L12,0 Z" fill="currentColor"/>
                    </svg>
                </div>
                @endif
                <div class="flex items-center gap-2.5 px-4 py-3.5 pr-6">
                    <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center
                        {{ $isActive ? 'bg-white/20' : ($isCompleted ? 'bg-orange-100' : 'bg-gray-100') }}">
                        @if($isCompleted)
                            <i class="ri-check-line text-orange-600 text-base"></i>
                        @else
                            <i class="{{ $p['icon'] }} text-base {{ $isActive ? 'text-white' : 'text-gray-400' }}"></i>
                        @endif
                    </div>
                    <div class="hidden sm:block min-w-0">
                        <p class="text-[10px] font-black uppercase tracking-widest leading-none {{ $isActive ? 'text-white' : ($isCompleted ? 'text-orange-600' : 'text-gray-400') }}">{{ $p['label'] }}</p>
                        <p class="text-[9px] mt-0.5 truncate {{ $isActive ? 'text-orange-200' : ($isCompleted ? 'text-orange-400' : 'text-gray-300') }}">{{ $p['sub'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 1 — TIPO DE RECONEXIÓN
    ================================================================ --}}
    @if($paso == 1)
    <div class="max-w-2xl mx-auto">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest text-center mb-6">¿Qué tipo de reconexión requiere el suscriptor?</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <button wire:click="seleccionarTipo('mismo')"
                    class="relative bg-white border-2 border-gray-200 rounded-xl p-8 hover:border-orange-400 hover:shadow-lg transition-all group text-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-orange-50/0 to-orange-50/0 group-hover:from-orange-50/60 transition-all rounded-xl"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-200 transition-colors">
                        <i class="ri-refresh-line text-orange-600 text-3xl"></i>
                    </div>
                    <p class="text-base font-black text-gray-800 uppercase tracking-tight mb-1.5">Mismo Servicio</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Mantiene paquete y tarifa anterior</p>
                    <div class="mt-4 inline-flex items-center gap-1.5 text-[10px] font-black text-orange-600 bg-orange-50 border border-orange-100 px-3 py-1.5 rounded-lg uppercase tracking-widest">
                        <i class="ri-check-line"></i> Sin cambios en contrato
                    </div>
                </div>
            </button>

            <button wire:click="seleccionarTipo('otro')"
                    class="relative bg-white border-2 border-gray-200 rounded-xl p-8 hover:border-amber-400 hover:shadow-lg transition-all group text-center overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-50/0 to-amber-50/0 group-hover:from-amber-50/60 transition-all rounded-xl"></div>
                <div class="relative">
                    <div class="w-16 h-16 bg-amber-100 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-200 transition-colors">
                        <i class="ri-arrow-left-right-line text-amber-600 text-3xl"></i>
                    </div>
                    <p class="text-base font-black text-gray-800 uppercase tracking-tight mb-1.5">Cambio de Servicio</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Migra a un nuevo paquete o tarifa</p>
                    <div class="mt-4 inline-flex items-center gap-1.5 text-[10px] font-black text-amber-600 bg-amber-50 border border-amber-100 px-3 py-1.5 rounded-lg uppercase tracking-widest">
                        <i class="ri-exchange-line"></i> Nuevo contrato
                    </div>
                </div>
            </button>

        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 2 — BUSCAR SUSCRIPTOR
    ================================================================ --}}
    @if($paso == 2)
    <div class="max-w-2xl mx-auto space-y-4">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                    <i class="ri-user-search-line text-orange-600 text-sm"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Localizar Suscriptor</p>
                    <p class="text-[10px] text-gray-400">Solo aparecerán cuentas con estado <strong>Suspendido</strong> o <strong>Cancelado</strong></p>
                </div>
                <span class="flex-shrink-0 text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-md border
                    {{ $tipoReconexion === 'mismo' ? 'bg-orange-50 text-orange-600 border-orange-100' : 'bg-amber-50 text-amber-600 border-amber-100' }}">
                    {{ $tipoReconexion === 'mismo' ? '↩ Mismo Servicio' : '⇄ Cambio Servicio' }}
                </span>
            </div>

            <div class="p-5">
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" wire:model="busqueda"
                               wire:keydown.enter="buscarCliente"
                               placeholder="Nombre, ID de suscriptor, teléfono o dirección..."
                               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 transition-colors placeholder:text-gray-300">
                    </div>
                    <button wire:click="buscarCliente"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm flex-shrink-0">
                        Buscar
                    </button>
                </div>
            </div>

            {{-- Resultados --}}
            @if($busquedaHecha && count($resultados) > 0)
            <div class="border-t border-gray-100">
                @foreach($resultados as $r)
                <div class="flex items-center gap-4 px-5 py-4 hover:bg-orange-50/50 transition-colors {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                        {{ $r['estado'] === 'CANCELADO' ? 'bg-gray-100' : 'bg-red-100' }}">
                        <i class="{{ $r['estado'] === 'CANCELADO' ? 'ri-close-circle-line text-gray-500' : 'ri-pause-circle-line text-red-500' }} text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-black text-gray-900 uppercase tracking-tight truncate">{{ $r['nombre'] }}</p>
                        <div class="flex items-center gap-3 mt-0.5 flex-wrap">
                            <span class="text-[10px] font-mono text-gray-500">{{ $r['id'] }}</span>
                            <span class="text-[10px] text-gray-400">{{ $r['telefono'] }}</span>
                            <span class="text-[9px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded
                                {{ $r['estado'] === 'CANCELADO' ? 'bg-gray-100 text-gray-500' : 'bg-red-100 text-red-600' }}">
                                {{ $r['estado'] }}
                            </span>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-0.5 truncate">{{ $r['ultimo_servicio'] }} · ${{ number_format($r['saldo_pendiente'], 2) }} pendiente</p>
                    </div>
                    <button wire:click="seleccionarSuscriptor({{ json_encode($r) }})"
                            class="flex-shrink-0 px-4 py-2 bg-orange-600 text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-orange-700 transition-all active:scale-95">
                        Seleccionar
                    </button>
                </div>
                @endforeach
            </div>
            @elseif($busquedaHecha && empty($resultados))
            <div class="border-t border-gray-100 px-5 py-8 text-center">
                <i class="ri-user-search-line text-3xl text-gray-200 mb-2 block"></i>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Sin resultados</p>
            </div>
            @endif

            <div class="bg-gray-50 border-t border-gray-200 px-5 py-3">
                <button wire:click="$set('paso', 1)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Cambiar tipo de reconexión
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 3 — LIQUIDAR ADEUDO
    ================================================================ --}}
    @if($paso == 3)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Columna izquierda: expediente --}}
        <div class="lg:col-span-7 space-y-4">

            {{-- Tarjeta expediente --}}
            <div class="bg-gray-900 rounded-xl p-5 text-white shadow-sm relative overflow-hidden">
                <div class="absolute -right-6 -top-6 opacity-[0.07] font-black text-9xl select-none">SUSP</div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Expediente de Suspensión</p>
                        <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-md
                            {{ $tipoReconexion === 'mismo' ? 'bg-orange-500/20 text-orange-300' : 'bg-amber-500/20 text-amber-300' }}">
                            {{ $tipoReconexion === 'mismo' ? '↩ Mismo servicio' : '⇄ Cambio servicio' }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4 text-xs">
                        <div class="col-span-2">
                            <p class="text-[9px] text-gray-500 font-bold uppercase mb-0.5">Titular</p>
                            <p class="text-base font-black uppercase tracking-tight leading-tight">{{ $suscriptor['nombre'] }}</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">{{ $suscriptor['id'] }} · {{ $suscriptor['sucursal'] }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] text-gray-500 font-bold uppercase mb-0.5">Último Servicio</p>
                            <p class="font-bold text-orange-400 text-[11px] uppercase">{{ $suscriptor['ultimo_servicio'] }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] text-gray-500 font-bold uppercase mb-0.5">Fecha de Corte</p>
                            <p class="font-black text-red-400 text-[11px]">{{ $suscriptor['fecha_suspension'] }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] text-gray-500 font-bold uppercase mb-0.5">NAP Asignado</p>
                            <p class="font-bold text-gray-300 text-[11px]">{{ $suscriptor['nap'] }}</p>
                            <p class="text-[9px] text-gray-500">{{ $suscriptor['dir_nap'] }}</p>
                        </div>
                        <div class="bg-white/5 rounded-lg p-3 border border-white/10">
                            <p class="text-[9px] text-gray-500 font-bold uppercase mb-1">Equipo Vinculado</p>
                            <p class="text-[11px] font-bold text-gray-200">{{ $suscriptor['equipo']['tipo'] }} {{ $suscriptor['equipo']['marca'] }}</p>
                            <p class="font-mono text-[10px] text-gray-500 mt-0.5">SN: {{ $suscriptor['equipo']['serie'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Últimos pagos --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
                    <i class="ri-history-line text-gray-400 text-sm"></i>
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Últimos 4 Pagos Registrados</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-xs">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Folio</th>
                                <th class="px-4 py-2.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Fecha</th>
                                <th class="px-4 py-2.5 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Concepto</th>
                                <th class="px-4 py-2.5 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest">Monto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($suscriptor['pagos'] as $pago)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 font-mono text-[10px] text-gray-400">{{ $pago['folio'] }}</td>
                                <td class="px-4 py-3 text-gray-500 font-medium italic text-[11px]">{{ $pago['fecha'] }}</td>
                                <td class="px-4 py-3 text-[10px] font-bold text-gray-600 uppercase tracking-wide">{{ $pago['concepto'] }}</td>
                                <td class="px-4 py-3 font-black text-gray-900 text-right">${{ number_format($pago['monto'], 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Columna derecha: panel de liquidación --}}
        <div class="lg:col-span-5">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden sticky top-4">

                {{-- Cabecera saldo --}}
                <div class="bg-red-600 px-5 py-5 text-center">
                    <p class="text-[9px] font-black text-red-200 uppercase tracking-widest mb-1">
                        Saldo a Liquidar · {{ $suscriptor['meses_adeudo'] }} {{ $suscriptor['meses_adeudo'] === 1 ? 'mes' : 'meses' }} de adeudo
                    </p>
                    <p class="text-4xl font-black text-white tracking-tight">${{ number_format($suscriptor['saldo_pendiente'], 2) }}</p>
                    @if($descuentoAplicado)
                    <div class="mt-3 flex items-center justify-between bg-white/10 rounded-lg px-3 py-2">
                        <span class="text-[10px] font-bold text-red-200 uppercase">Descuento aplicado</span>
                        <span class="text-sm font-black text-white">-${{ number_format($montoDescuento, 2) }}</span>
                    </div>
                    <div class="mt-1.5 flex items-center justify-between bg-white/20 rounded-lg px-3 py-2.5">
                        <span class="text-[10px] font-black text-red-100 uppercase tracking-widest">Total a Cobrar</span>
                        <span class="text-xl font-black text-white">${{ number_format($totalAdeudo, 2) }}</span>
                    </div>
                    @endif
                </div>

                <div class="p-5 space-y-4">

                    {{-- Descuento supervisor --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden" x-data>
                        <button type="button"
                                @click="$wire.set('mostrarDescuento', !$wire.mostrarDescuento)"
                                class="w-full flex items-center justify-between p-4 bg-gray-50 hover:bg-gray-100 transition-colors text-left">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center">
                                    <i class="ri-percent-line text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Descuento Supervisor</p>
                                    <p class="text-[10px] text-gray-400">Requiere contraseña gerencial</p>
                                </div>
                            </div>
                            @if($descuentoAplicado)
                            <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-md uppercase tracking-widest">Aplicado ✓</span>
                            @else
                            <i class="ri-arrow-{{ $mostrarDescuento ? 'up' : 'down' }}-s-line text-gray-400"></i>
                            @endif
                        </button>

                        @if($descuentoAplicado)
                        <div class="px-4 py-3 bg-emerald-50 border-t border-emerald-100 flex items-center justify-between">
                            <span class="text-[10px] font-bold text-emerald-700">Descuento: -${{ number_format($montoDescuento, 2) }}</span>
                            <button wire:click="quitarDescuento"
                                    class="text-[10px] font-black text-red-500 hover:text-red-700 uppercase tracking-widest transition-colors">
                                Quitar
                            </button>
                        </div>
                        @elseif($mostrarDescuento)
                        <div class="p-4 border-t border-gray-200 space-y-3">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Monto del Descuento ($)</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-black text-sm">$</span>
                                    <input type="number" wire:model="montoDescuentoInput" min="0" step="0.01"
                                           class="w-full pl-7 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-black focus:ring-2 focus:ring-red-500/20 focus:border-red-400"
                                           placeholder="0.00">
                                </div>
                                @error('montoDescuentoInput') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Contraseña Gerencial</label>
                                <input type="password" wire:model="passwordDescuento"
                                       wire:keydown.enter="aplicarDescuento"
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400"
                                       placeholder="••••••••">
                                @error('passwordDescuento') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                            </div>
                            <button wire:click="aplicarDescuento"
                                    class="w-full py-2.5 bg-red-600 text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-red-700 transition-all active:scale-95">
                                Aplicar Descuento
                            </button>
                        </div>
                        @endif
                    </div>

                    {{-- Forma de pago --}}
                    <div>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Forma de Pago del Adeudo</p>
                        <div class="space-y-2">
                            @php
                                $opcionesAdeudo = [
                                    'efectivo'      => ['icon' => 'ri-money-dollar-box-line',   'label' => 'Efectivo'],
                                    'transferencia' => ['icon' => 'ri-bank-line',                'label' => 'Transferencia / SPEI'],
                                    'tarjeta'       => ['icon' => 'ri-bank-card-line',           'label' => 'Tarjeta Débito / Crédito'],
                                    'deposito'      => ['icon' => 'ri-building-2-line',          'label' => 'Depósito Bancario'],
                                ];
                            @endphp
                            @foreach($opcionesAdeudo as $key => $op)
                            <button type="button" wire:click="$set('formaPagoAdeudo', '{{ $key }}')"
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border-2 font-bold text-sm transition-all text-left
                                        {{ $formaPagoAdeudo === $key
                                            ? 'border-orange-500 bg-orange-50 text-orange-800 shadow-sm'
                                            : 'border-gray-200 text-gray-600 hover:border-orange-300 hover:bg-orange-50/40' }}">
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0
                                    {{ $formaPagoAdeudo === $key ? 'bg-orange-100' : 'bg-gray-100' }}">
                                    <i class="{{ $op['icon'] }} text-sm {{ $formaPagoAdeudo === $key ? 'text-orange-600' : 'text-gray-400' }}"></i>
                                </div>
                                <span class="text-[11px] font-black uppercase tracking-widest">{{ $op['label'] }}</span>
                                @if($formaPagoAdeudo === $key)
                                <i class="ri-check-line text-orange-600 text-sm ml-auto"></i>
                                @endif
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Botón confirmar --}}
                    <div class="pt-1" x-data>
                        <button type="button"
                                @click="$confirm(
                                    'Registrar liquidación de ${{ number_format($totalAdeudo, 2) }} en caja — forma de pago: {{ strtoupper($formaPagoAdeudo ?: '---') }}',
                                    () => $wire.procesarPagoAdeudo(),
                                    { title: 'Confirmar Liquidación', confirmText: 'Sí, registrar', icon: 'warning' }
                                )"
                                class="w-full py-3.5 bg-orange-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-orange-700 shadow-md shadow-orange-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i class="ri-money-dollar-box-line text-base"></i> Liquidar Adeudo y Continuar
                        </button>
                    </div>
                </div>

                <div class="bg-gray-50 border-t border-gray-200 px-5 py-3">
                    <button wire:click="$set('paso', 2)"
                            class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                        <i class="ri-arrow-left-line"></i> Cambiar suscriptor
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 4 — COBRO DE DÍAS DE USO (+ CAMBIO DE SERVICIO)
    ================================================================ --}}
    @if($paso == 4)
    <div class="max-w-3xl mx-auto space-y-5">

        {{-- Resumen suscriptor --}}
        <div class="bg-gray-900 rounded-xl px-5 py-4 flex items-center justify-between">
            <div>
                <p class="text-[9px] font-black text-orange-400 uppercase tracking-widest mb-0.5">Suscriptor</p>
                <p class="text-sm font-black text-white uppercase tracking-tight">{{ $suscriptor['nombre'] }}</p>
                <p class="text-[10px] text-gray-500 mt-0.5">Adeudo liquidado · ${{ number_format($totalAdeudo, 2) }}</p>
            </div>
            <span class="text-[9px] font-black text-emerald-400 bg-white/10 px-2.5 py-1.5 rounded-lg uppercase tracking-widest flex items-center gap-1.5">
                <i class="ri-checkbox-circle-fill"></i> Adeudo OK
            </span>
        </div>

        {{-- Catálogo de servicios (solo si tipo = 'otro') --}}
        @if($tipoReconexion === 'otro')
        <div class="bg-white border border-amber-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-amber-50 border-b border-amber-100 px-5 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                    <i class="ri-exchange-line text-amber-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-amber-900 uppercase tracking-widest">Seleccionar Nuevo Servicio</p>
                    <p class="text-[10px] text-amber-600">Elige el paquete al que migrará el suscriptor</p>
                </div>
                @if($servicioSeleccionado)
                <span class="ml-auto text-[9px] font-black text-emerald-600 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-md uppercase">Seleccionado ✓</span>
                @else
                <span class="ml-auto text-[9px] font-black text-red-500 bg-red-50 border border-red-100 px-2 py-1 rounded-md uppercase">Requerido</span>
                @endif
            </div>
            <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @foreach($serviciosCatalogo as $s)
                <button type="button" wire:click="seleccionarServicio('{{ $s['key'] }}')"
                        class="text-left border-2 rounded-xl p-4 transition-all
                            {{ $servicioSeleccionado === $s['key']
                                ? 'border-amber-500 bg-amber-50 shadow-md'
                                : 'border-gray-200 hover:border-amber-300 hover:bg-amber-50/40' }}">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        @php
                            $tipoIcon = match($s['tipo']) {
                                'TV'          => ['ri-tv-line',       'text-orange-500',  'bg-orange-50'],
                                'INTERNET'    => ['ri-wifi-line',     'text-blue-500',    'bg-blue-50'],
                                'TV+INTERNET' => ['ri-router-line',   'text-purple-500',  'bg-purple-50'],
                                default       => ['ri-service-line',  'text-gray-500',    'bg-gray-50'],
                            };
                        @endphp
                        <div class="w-7 h-7 rounded-lg {{ $tipoIcon[2] }} flex items-center justify-center flex-shrink-0">
                            <i class="{{ $tipoIcon[0] }} text-sm {{ $tipoIcon[1] }}"></i>
                        </div>
                        @if($servicioSeleccionado === $s['key'])
                        <i class="ri-check-circle-fill text-amber-500 text-base flex-shrink-0"></i>
                        @endif
                    </div>
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-tight leading-snug">{{ $s['nombre'] }}</p>
                    <p class="text-lg font-black text-gray-900 mt-1.5 tracking-tight">${{ number_format($s['mensualidad'], 2) }}<span class="text-[10px] font-bold text-gray-400">/mes</span></p>
                    <p class="text-[10px] font-bold text-amber-600 mt-0.5">Instalación: ${{ number_format($s['instalacion'], 2) }}</p>
                </button>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Desglose días de uso --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center">
                    <i class="ri-calendar-check-line text-orange-600 text-sm"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Cobro Proporcional de Días de Uso</p>
                    <p class="text-[10px] text-gray-400">Desde hoy hasta el fin del mes en curso</p>
                </div>
            </div>
            <div class="p-5 space-y-3">
                @if($tipoReconexion === 'otro' && $servicioSeleccionado)
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <div>
                        <p class="text-xs font-bold text-gray-600 uppercase">Cargo de Instalación</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">
                            {{ collect($serviciosCatalogo)->firstWhere('key', $servicioSeleccionado)['nombre'] ?? '' }}
                        </p>
                    </div>
                    <span class="text-sm font-black text-gray-900">${{ number_format($cargoInstalacion, 2) }}</span>
                </div>
                @endif
                <div class="flex justify-between items-start py-3 border-b border-gray-100">
                    <div>
                        <p class="text-xs font-bold text-gray-600 uppercase">Días Proporcionales de Uso</p>
                        <p class="text-[10px] text-orange-500 font-bold uppercase mt-0.5">
                            {{ $diasUso }} días restantes del mes ·
                            ${{ number_format($costoDia, 2) }}/día
                            @if($tipoReconexion === 'mismo')
                            (tarifa ${{ number_format($suscriptor['tarifa'], 2) }}/30)
                            @elseif($servicioSeleccionado)
                            (tarifa ${{ number_format($costoNuevaMensual, 2) }}/30)
                            @endif
                        </p>
                    </div>
                    <span class="text-sm font-black text-gray-900">${{ number_format($costoProrrateo, 2) }}</span>
                </div>
                @php
                    $totalCobro = $costoProrrateo + ($tipoReconexion === 'otro' && $servicioSeleccionado ? $cargoInstalacion : 0);
                @endphp
                <div class="flex justify-between items-center bg-gray-900 rounded-xl px-5 py-4 mt-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total a Cobrar</span>
                    <span class="text-2xl font-black text-white tracking-tight">${{ number_format($totalCobro, 2) }}</span>
                </div>
            </div>

            {{-- Forma de pago --}}
            <div class="px-5 pb-5 space-y-2">
                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3 pt-2 border-t border-gray-100">Forma de Pago del Cargo Proporcional</p>
                @php
                    $opcionesDias = [
                        'efectivo'      => ['icon' => 'ri-money-dollar-box-line',  'label' => 'Efectivo'],
                        'transferencia' => ['icon' => 'ri-bank-line',               'label' => 'Transferencia / SPEI'],
                        'tarjeta'       => ['icon' => 'ri-bank-card-line',          'label' => 'Tarjeta Débito / Crédito'],
                        'deposito'      => ['icon' => 'ri-building-2-line',         'label' => 'Depósito Bancario'],
                    ];
                @endphp
                @foreach($opcionesDias as $key => $op)
                <button type="button" wire:click="$set('formaPagoDias', '{{ $key }}')"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl border-2 font-bold text-sm transition-all text-left
                            {{ $formaPagoDias === $key
                                ? 'border-orange-500 bg-orange-50 text-orange-800 shadow-sm'
                                : 'border-gray-200 text-gray-600 hover:border-orange-300 hover:bg-orange-50/40' }}">
                    <div class="w-7 h-7 rounded-lg flex items-center justify-center flex-shrink-0
                        {{ $formaPagoDias === $key ? 'bg-orange-100' : 'bg-gray-100' }}">
                        <i class="{{ $op['icon'] }} text-sm {{ $formaPagoDias === $key ? 'text-orange-600' : 'text-gray-400' }}"></i>
                    </div>
                    <span class="text-[11px] font-black uppercase tracking-widest">{{ $op['label'] }}</span>
                    @if($formaPagoDias === $key)
                    <i class="ri-check-line text-orange-600 text-sm ml-auto"></i>
                    @endif
                </button>
                @endforeach
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-5 py-4 flex items-center justify-between" x-data>
                <button wire:click="$set('paso', 3)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Atrás
                </button>
                <button type="button"
                        @click="$confirm(
                            'Registrar cobro de ${{ number_format($totalCobro, 2) }} — {{ $diasUso }} días proporcionales',
                            () => $wire.procesarCobroProporcional(),
                            { title: 'Confirmar Cobro Proporcional', confirmText: 'Sí, confirmar', icon: 'warning' }
                        )"
                        class="inline-flex items-center gap-2 px-7 py-3 bg-orange-600 text-white rounded-lg font-black text-xs uppercase tracking-widest shadow-md shadow-orange-200 hover:bg-orange-700 transition-all active:scale-95">
                    <i class="ri-receipt-line"></i> Confirmar Cobro y Continuar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 5 — CIERRE: TÉCNICO Y COMODATO
    ================================================================ --}}
    @if($paso == 5)
    <div class="max-w-2xl mx-auto space-y-5">

        {{-- Resumen --}}
        <div class="bg-gray-900 rounded-xl px-5 py-4 flex items-start justify-between gap-4">
            <div>
                <p class="text-[9px] font-black text-orange-400 uppercase tracking-widest mb-0.5">Asignación Final · {{ $tipoReconexion === 'mismo' ? 'Mismo Servicio' : 'Cambio de Servicio' }}</p>
                <p class="text-sm font-black text-white uppercase tracking-tight">{{ $suscriptor['nombre'] }}</p>
                <p class="text-[10px] text-gray-500 mt-0.5">
                    {{ $tipoReconexion === 'mismo' ? $suscriptor['ultimo_servicio'] : (collect($serviciosCatalogo)->firstWhere('key', $servicioSeleccionado)['nombre'] ?? '') }}
                </p>
            </div>
            <div class="text-right flex-shrink-0">
                <span class="text-[9px] font-black text-emerald-400 bg-white/10 px-2.5 py-1.5 rounded-lg uppercase tracking-widest block">
                    Cobros confirmados ✓
                </span>
                <p class="text-[10px] text-gray-500 mt-1.5">
                    Adeudo: ${{ number_format($totalAdeudo, 2) }}<br>
                    Proporcional: ${{ number_format($costoProrrateo + $cargoInstalacion, 2) }}
                </p>
            </div>
        </div>

        {{-- Técnico --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                <i class="ri-tools-line text-orange-500 text-sm"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Técnico Asignado a la Reconexión</p>
                <span class="ml-auto text-[9px] font-bold text-red-500 bg-red-50 border border-red-100 px-2 py-1 rounded-md uppercase">Requerido</span>
            </div>
            <div class="p-5 space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Técnico o Cuadrilla</label>
                    <div class="relative">
                        <i class="ri-user-star-line absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <select wire:model.live="tecnicoAsignado"
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold uppercase focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 transition-colors">
                            <option value="">— Seleccione técnico —</option>
                            <option value="ING. ROBERTO GÓMEZ">ING. ROBERTO GÓMEZ (FIBRA ÓPTICA)</option>
                            <option value="ING. JUAN PÉREZ">ING. JUAN PÉREZ (COAXIAL / TV)</option>
                            <option value="ING. MARIO SÁNCHEZ">ING. MARIO SÁNCHEZ (INSTALACIONES)</option>
                            <option value="CUADRILLA A">CUADRILLA A (ZONA CENTRO)</option>
                            <option value="CUADRILLA B">CUADRILLA B (ZONA NORTE)</option>
                        </select>
                    </div>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Notas para el Técnico <span class="text-gray-300 normal-case font-normal">(opcional)</span></label>
                    <textarea wire:model="notasTecnico" rows="2"
                              placeholder="Instrucciones especiales, ubicación exacta, equipo adicional..."
                              class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm resize-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 transition-colors placeholder:text-gray-300"></textarea>
                </div>
                <p class="flex items-center gap-2 text-[10px] font-bold text-orange-500 uppercase tracking-widest">
                    <i class="ri-message-3-line text-base"></i> La orden de trabajo se enviará vía SMS al confirmar
                </p>
            </div>
        </div>

        {{-- Comodato --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                <i class="ri-file-text-line text-orange-500 text-sm"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">
                    {{ $tipoReconexion === 'mismo' ? 'Renovación de Comodato' : 'Firma de Nuevo Contrato de Adhesión' }}
                </p>
            </div>
            <div class="p-5 space-y-4">
                {{-- Área de firma biométrica (placeholder) --}}
                <div class="border-2 border-dashed border-gray-200 rounded-xl min-h-[140px] flex items-center justify-center bg-gray-50 hover:border-orange-300 transition-colors relative group">
                    <div class="text-center">
                        <i class="ri-pen-nib-line text-4xl text-gray-200 group-hover:text-orange-300 transition-colors block mb-2"></i>
                        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Área de Captura de Firma</p>
                        <p class="text-[9px] text-gray-200 mt-0.5">(Integración con tablet / pad biométrico)</p>
                    </div>
                    <div class="absolute bottom-8 left-12 right-12 border-b border-gray-200"></div>
                    <p class="absolute bottom-4 left-12 text-[9px] text-gray-300 uppercase tracking-widest font-bold">Firma del Titular</p>
                </div>

                {{-- Declaratoria --}}
                <div class="border-2 border-dashed {{ $comodatoFirmado ? 'border-emerald-300 bg-emerald-50/50' : 'border-orange-200 bg-orange-50/30' }} rounded-xl p-4 transition-colors">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="comodatoFirmado"
                               class="mt-0.5 h-5 w-5 rounded border-gray-300 text-orange-600 focus:ring-orange-500 flex-shrink-0">
                        <div>
                            <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest mb-1">Declaratoria del Suscriptor</p>
                            <p class="text-[10px] text-gray-600 leading-relaxed font-medium">
                                @if($tipoReconexion === 'mismo')
                                El suscriptor confirma que el equipo vinculado (<strong>{{ $suscriptor['equipo']['tipo'] }} {{ $suscriptor['equipo']['marca'] }} — SN: {{ $suscriptor['equipo']['serie'] }}</strong>) se encuentra en su domicilio en buen estado y acepta los términos del contrato de adhesión renovado de <strong>Tu Visión Telecable</strong>.
                                @else
                                El suscriptor acepta el cambio al nuevo paquete de servicio y firma el contrato de adhesión actualizado de <strong>Tu Visión Telecable</strong>, comprometiéndose a conservar el equipo asignado en buen estado.
                                @endif
                            </p>
                        </div>
                    </label>
                </div>
                @error('comodatoFirmado') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-5 py-4 flex items-center justify-between" x-data>
                <button wire:click="$set('paso', 4)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Atrás
                </button>
                <button type="button"
                        @click="$confirm(
                            'Generar reporte técnico y cambiar estado del suscriptor a ACTIVO',
                            () => $wire.finalizarReconexion(),
                            { title: '¿Finalizar reconexión?', confirmText: 'Sí, generar reporte', icon: 'warning' }
                        )"
                        class="inline-flex items-center gap-2 px-7 py-3 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest shadow-md hover:bg-black transition-all active:scale-95">
                    <i class="ri-save-3-line"></i> Generar Reporte y Finalizar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 6 — RECIBO DE RECONEXIÓN
    ================================================================ --}}
    @if($paso == 6)
    <div class="max-w-2xl mx-auto space-y-5">

        {{-- Banner éxito --}}
        <div class="bg-emerald-600 rounded-xl px-6 py-5 text-center text-white shadow-lg shadow-emerald-200">
            <i class="ri-checkbox-circle-fill text-5xl mb-3 block opacity-90"></i>
            <p class="text-xl font-black uppercase tracking-tight mb-1">Reconexión Completada</p>
            <p class="text-emerald-200 text-sm font-bold">El suscriptor ha sido reactivado · Reporte técnico generado</p>
            <div class="mt-3 inline-block bg-white/10 px-4 py-2 rounded-lg">
                <span class="text-[10px] font-black text-emerald-200 uppercase tracking-widest">Folio: </span>
                <span class="font-mono text-base font-black text-white">{{ $resultado['folio'] ?? '' }}</span>
            </div>
        </div>

        {{-- Detalle del proceso --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                <i class="ri-receipt-line text-orange-500 text-sm"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Resumen de la Reconexión</p>
            </div>
            <div class="divide-y divide-gray-100">
                @php
                    $items = [
                        ['label' => 'Folio',          'value' => $resultado['folio'] ?? ''],
                        ['label' => 'Fecha y Hora',   'value' => $resultado['fecha'] ?? ''],
                        ['label' => 'Suscriptor',     'value' => $resultado['nombre'] ?? ''],
                        ['label' => 'ID Suscriptor',  'value' => $resultado['id_suscriptor'] ?? ''],
                        ['label' => 'Sucursal',       'value' => $resultado['sucursal'] ?? ''],
                        ['label' => 'Servicio',       'value' => $resultado['servicio'] ?? ''],
                        ['label' => 'Tipo',           'value' => ($resultado['tipo'] ?? '') === 'mismo' ? 'Reconexión Simple' : 'Cambio de Servicio'],
                        ['label' => 'Técnico',        'value' => $resultado['tecnico'] ?? ''],
                    ];
                @endphp
                @foreach($items as $item)
                <div class="flex items-center justify-between px-5 py-3">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ $item['label'] }}</span>
                    <span class="text-xs font-bold text-gray-900 text-right">{{ $item['value'] }}</span>
                </div>
                @endforeach
            </div>

            {{-- Desglose financiero --}}
            <div class="border-t border-gray-200 bg-gray-50">
                <p class="px-5 pt-4 pb-2 text-[9px] font-black text-gray-400 uppercase tracking-widest">Desglose de Cobros</p>
                <div class="divide-y divide-gray-100 text-sm">
                    <div class="flex justify-between px-5 py-3">
                        <span class="text-[11px] font-bold text-gray-600 uppercase">Adeudo Liquidado</span>
                        <span class="font-black text-gray-900">${{ number_format($resultado['saldo_liquidado'] ?? 0, 2) }}</span>
                    </div>
                    @if(($resultado['descuento'] ?? 0) > 0)
                    <div class="flex justify-between px-5 py-3">
                        <span class="text-[11px] font-bold text-emerald-600 uppercase">Descuento Aplicado</span>
                        <span class="font-black text-emerald-600">-${{ number_format($resultado['descuento'] ?? 0, 2) }}</span>
                    </div>
                    @endif
                    @if(($resultado['cargo_instalacion'] ?? 0) > 0)
                    <div class="flex justify-between px-5 py-3">
                        <span class="text-[11px] font-bold text-gray-600 uppercase">Cargo de Instalación</span>
                        <span class="font-black text-gray-900">${{ number_format($resultado['cargo_instalacion'] ?? 0, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between px-5 py-3">
                        <div>
                            <span class="text-[11px] font-bold text-gray-600 uppercase">Días Proporcionales de Uso</span>
                            <p class="text-[9px] text-gray-400 mt-0.5">{{ $resultado['dias_uso'] ?? 0 }} días restantes del mes</p>
                        </div>
                        <span class="font-black text-gray-900">${{ number_format($resultado['costo_prorrateo'] ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center px-5 py-4 bg-gray-900 rounded-b-xl">
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Cobrado</span>
                        <span class="text-2xl font-black text-white tracking-tight">
                            ${{ number_format(
                                ($resultado['saldo_liquidado'] ?? 0) +
                                ($resultado['costo_prorrateo'] ?? 0) +
                                ($resultado['cargo_instalacion'] ?? 0), 2) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="grid grid-cols-2 gap-3">
            <button onclick="window.print()"
                    class="flex items-center justify-center gap-2 py-3.5 bg-white border-2 border-gray-200 text-gray-700 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-50 hover:border-gray-300 transition-all">
                <i class="ri-printer-line text-base"></i> Imprimir Recibo
            </button>
            <button wire:click="reiniciar"
                    class="flex items-center justify-center gap-2 py-3.5 bg-orange-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-orange-700 shadow-md shadow-orange-200 transition-all active:scale-95">
                <i class="ri-add-circle-line text-base"></i> Nueva Reconexión
            </button>
        </div>

        <div class="text-center">
            <a href="{{ route('reportes.servicio') }}"
               class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors">
                <i class="ri-list-check text-sm"></i> Ver en Reportes de Servicio
            </a>
        </div>
    </div>
    @endif

</div>
