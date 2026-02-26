<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ENCABEZADO --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-indigo-600">Reconexión de Servicios</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Proceso de Reconexión Técnica
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Restablecimiento de señal para cuentas suspendidas o canceladas</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>

    {{-- STEPPER --}}
    <div class="mb-8 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex">
            @php
                $pasos = [
                    1 => ['label' => 'Tipo',     'sub' => 'Selección de reconexión', 'icon' => 'ri-git-merge-line'],
                    2 => ['label' => 'Búsqueda', 'sub' => 'Localizar suscriptor',    'icon' => 'ri-user-search-line'],
                    3 => ['label' => 'Saldos',   'sub' => 'Liquidación de adeudo',   'icon' => 'ri-money-dollar-circle-line'],
                    4 => ['label' => 'Cobro',    'sub' => 'Pago proporcional',       'icon' => 'ri-receipt-line'],
                    5 => ['label' => 'Cierre',   'sub' => 'Técnico y comodato',      'icon' => 'ri-checkbox-circle-line'],
                ];
            @endphp
            @foreach($pasos as $num => $p)
                @php
                    $isActive    = $paso === $num;
                    $isCompleted = $paso > $num;
                    $isLast      = $num === count($pasos);
                @endphp
                <div class="flex-1 relative
                    {{ $isActive ? 'bg-indigo-600' : ($isCompleted ? 'bg-indigo-50' : 'bg-white') }}
                    {{ !$isLast ? 'border-r border-gray-200' : '' }}">
                    @if(!$isLast)
                    <div class="absolute right-0 top-0 bottom-0 w-3 z-10 overflow-hidden">
                        <svg viewBox="0 0 12 48" class="h-full w-3 {{ $isActive ? 'text-indigo-600' : ($isCompleted ? 'text-indigo-50' : 'text-white') }}" preserveAspectRatio="none">
                            <path d="M0,0 L8,24 L0,48 L12,48 L12,0 Z" fill="currentColor"/>
                        </svg>
                    </div>
                    @endif
                    <div class="flex items-center gap-2.5 px-4 py-3.5 pr-6">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center
                            {{ $isActive ? 'bg-white/20' : ($isCompleted ? 'bg-indigo-100' : 'bg-gray-100') }}">
                            @if($isCompleted)
                                <i class="ri-check-line text-indigo-600 text-base"></i>
                            @else
                                <i class="{{ $p['icon'] }} text-base {{ $isActive ? 'text-white' : 'text-gray-400' }}"></i>
                            @endif
                        </div>
                        <div class="hidden sm:block min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-widest leading-none {{ $isActive ? 'text-white' : ($isCompleted ? 'text-indigo-600' : 'text-gray-400') }}">{{ $p['label'] }}</p>
                            <p class="text-[9px] mt-0.5 truncate {{ $isActive ? 'text-indigo-200' : ($isCompleted ? 'text-indigo-400' : 'text-gray-300') }}">{{ $p['sub'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================================================================
         PASO 1 — TIPO DE RECONEXIÓN
    ================================================================ --}}
    @if($paso == 1)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-2xl mx-auto">

        <button wire:click="seleccionarTipo('mismo')"
                class="relative bg-white border-2 border-gray-200 rounded-xl p-8 hover:border-indigo-400 hover:shadow-lg transition-all group text-center overflow-hidden">
            <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-indigo-200 transition-colors">
                <i class="ri-refresh-line text-indigo-600 text-2xl"></i>
            </div>
            <p class="text-base font-black text-gray-800 uppercase tracking-tight">Reconexión Simple</p>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">Mantener mismo paquete y tarifa</p>
            <div class="mt-4 inline-flex items-center gap-1.5 text-[10px] font-black text-indigo-500 bg-indigo-50 border border-indigo-100 px-3 py-1.5 rounded-lg uppercase tracking-widest">
                <i class="ri-check-line"></i> Sin cambios en servicio
            </div>
        </button>

        <button wire:click="seleccionarTipo('otro')"
                class="relative bg-white border-2 border-gray-200 rounded-xl p-8 hover:border-orange-400 hover:shadow-lg transition-all group text-center overflow-hidden">
            <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center mx-auto mb-4 group-hover:bg-orange-200 transition-colors">
                <i class="ri-arrow-left-right-line text-orange-600 text-2xl"></i>
            </div>
            <p class="text-base font-black text-gray-800 uppercase tracking-tight">Reconexión con Cambio</p>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">Migrar a un nuevo paquete técnico</p>
            <div class="mt-4 inline-flex items-center gap-1.5 text-[10px] font-black text-orange-500 bg-orange-50 border border-orange-100 px-3 py-1.5 rounded-lg uppercase tracking-widest">
                <i class="ri-exchange-line"></i> Cambia tarifa
            </div>
        </button>

    </div>
    @endif

    {{-- ================================================================
         PASO 2 — BUSCAR SUSCRIPTOR
    ================================================================ --}}
    @if($paso == 2)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <i class="ri-user-search-line text-indigo-600"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Localizar Suscriptor Suspendido</p>
                    <p class="text-[10px] text-gray-400">Solo aparecerán cuentas en estado Suspendido o Cancelado</p>
                </div>
                {{-- Tipo elegido --}}
                <div class="ml-auto">
                    <span class="text-[9px] font-black uppercase tracking-widest px-2 py-1 rounded-md border
                        {{ $tipoReconexion === 'mismo' ? 'bg-indigo-50 text-indigo-600 border-indigo-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                        {{ $tipoReconexion === 'mismo' ? '↩ Simple' : '⇄ Con Cambio' }}
                    </span>
                </div>
            </div>

            <div class="p-5 space-y-4">
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-user-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" wire:model="busqueda"
                               placeholder="Nombre, ID o teléfono..."
                               wire:keydown.enter="buscarCliente"
                               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors placeholder:text-gray-300">
                    </div>
                    <button wire:click="buscarCliente"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm">
                        Buscar
                    </button>
                </div>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-5 py-3">
                <button wire:click="irAPaso(1)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Cambiar tipo de reconexión
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 3 — SALDOS Y LIQUIDACIÓN DE ADEUDO
    ================================================================ --}}
    @if($paso == 3)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Expediente de suspensión --}}
        <div class="lg:col-span-7 space-y-4">

            <div class="bg-gray-900 rounded-xl p-6 text-white shadow-sm relative overflow-hidden">
                <div class="absolute -right-8 -top-8 opacity-10 font-black italic text-8xl">OFF</div>
                <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-4 border-b border-gray-800 pb-2">Expediente de Suspensión</p>
                <div class="grid grid-cols-2 gap-4 text-xs relative z-10">
                    <div class="col-span-2">
                        <p class="text-[9px] text-gray-500 font-bold uppercase mb-0.5">Titular Legal</p>
                        <p class="text-base font-black uppercase tracking-tight">{{ $cliente['nombre'] }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] text-gray-500 font-bold uppercase mb-0.5">Último Servicio</p>
                        <p class="font-bold text-indigo-400">{{ $cliente['ultimo_servicio'] }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] text-gray-500 font-bold uppercase mb-0.5">Fecha de Corte</p>
                        <p class="font-black text-red-400">{{ $cliente['fecha_suspension'] }}</p>
                    </div>
                    <div class="col-span-2 bg-white/5 rounded-lg p-3 border border-white/10">
                        <p class="text-[9px] text-gray-500 font-bold uppercase mb-0.5">Equipo Vinculado</p>
                        <p class="font-mono text-xs text-gray-300">SN: {{ $cliente['equipo'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Últimos pagos --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
                    <i class="ri-history-line text-gray-400 text-sm"></i>
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Últimos Pagos Registrados</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-100 text-xs">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Fecha</th>
                                <th class="px-4 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Importe</th>
                                <th class="px-4 py-3 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Concepto</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($cliente['pagos'] as $pago)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-4 py-3 text-gray-500 font-medium italic">{{ $pago['fecha'] }}</td>
                                <td class="px-4 py-3 font-black text-gray-900">${{ $pago['monto'] }}</td>
                                <td class="px-4 py-3 text-[10px] font-bold text-gray-500 uppercase">{{ $pago['concepto'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Panel de liquidación --}}
        <div class="lg:col-span-5">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-red-600 px-5 py-4 text-center">
                    <p class="text-[9px] font-black text-red-200 uppercase tracking-widest mb-1">Saldo a Liquidar Obligatorio</p>
                    <p class="text-3xl font-black text-white tracking-tight">${{ number_format($cliente['saldo_pendiente'], 2) }}</p>
                </div>

                @if(!$adeudoPagado)
                <div class="p-5 space-y-4">

                    {{-- Descuento --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <label class="flex items-center justify-between p-4 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 rounded-lg bg-red-100 flex items-center justify-center">
                                    <i class="ri-percent-line text-red-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Aplicar Descuento</p>
                                    <p class="text-[10px] text-gray-400">Requiere contraseña gerencial</p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="checkbox" wire:model.live="aplicarDescuento" class="sr-only peer">
                                <div class="w-10 h-6 bg-gray-200 peer-checked:bg-red-500 rounded-full transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                            </div>
                        </label>

                        @if($aplicarDescuento)
                        <div class="p-4 border-t border-gray-200 space-y-3">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Monto del Descuento</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-black">$</span>
                                    <input type="number" wire:model="montoDescuento"
                                           class="w-full pl-7 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-black focus:ring-2 focus:ring-red-500/20 focus:border-red-400"
                                           placeholder="0.00">
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Contraseña de Autorización</label>
                                <input type="password" wire:model="passwordAuth"
                                       class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-red-500/20 focus:border-red-400"
                                       placeholder="••••••••">
                                @error('passwordAuth') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    <button wire:click="procesarPagoAdeudo"
                            class="w-full py-3.5 bg-red-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-md shadow-red-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-money-dollar-box-line text-base"></i> Liquidar Saldo Pendiente
                    </button>
                </div>

                @else
                <div class="p-5 space-y-4">
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 text-center">
                        <i class="ri-checkbox-circle-fill text-3xl text-emerald-500 mb-2 block"></i>
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Adeudo liquidado con éxito</p>
                    </div>
                    <button wire:click="irAPaso(4)"
                            class="w-full py-3.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        Continuar al Cobro Proporcional <i class="ri-arrow-right-line"></i>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 4 — COBRO PROPORCIONAL (+ CAMBIO DE PAQUETE)
    ================================================================ --}}
    @if($paso == 4)
    <div class="max-w-3xl mx-auto space-y-5">

        {{-- Cambio de paquete (solo si tipo = 'otro') --}}
        @if($tipoReconexion == 'otro')
        <div class="bg-white border border-orange-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-orange-50 border-b border-orange-100 px-5 py-3.5 flex items-center gap-2">
                <i class="ri-exchange-line text-orange-500"></i>
                <p class="text-[11px] font-black text-orange-800 uppercase tracking-widest">Selección de Nueva Tarifa Contractual</p>
            </div>
            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-3">
                @foreach($paquetes as $key => $p)
                <div wire:click="$set('servicioSeleccionado', '{{ $key }}')"
                     class="cursor-pointer border-2 rounded-xl p-4 transition-all
                            {{ $servicioSeleccionado == $key ? 'border-orange-500 bg-orange-50 shadow-md' : 'border-gray-200 hover:border-orange-300' }}">
                    <div class="flex items-start justify-between mb-2">
                        <p class="text-[11px] font-black text-gray-700 uppercase tracking-tight">{{ $p['nombre'] }}</p>
                        <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                    {{ $servicioSeleccionado == $key ? 'border-orange-500 bg-orange-500' : 'border-gray-300' }}">
                            @if($servicioSeleccionado == $key)
                                <i class="ri-check-line text-white text-[9px]"></i>
                            @endif
                        </div>
                    </div>
                    <p class="text-xl font-black text-gray-900 tracking-tight">${{ number_format($p['mensualidad'], 2) }}</p>
                    <p class="text-[10px] font-bold text-orange-600 mt-1">Instalación: ${{ $p['instalacion'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Liquidación proporcional --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i class="ri-receipt-line text-emerald-600"></i>
                </div>
                <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Liquidación Final de Reconexión</p>
            </div>
            <div class="p-5 space-y-2">

                @if($tipoReconexion == 'otro' && $servicioSeleccionado)
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-600 uppercase">Nueva cuota de instalación</span>
                    <span class="text-sm font-black text-gray-900">${{ number_format($paquetes[$servicioSeleccionado]['instalacion'], 2) }}</span>
                </div>
                @endif

                <div class="flex justify-between items-start py-3 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold text-gray-600 uppercase">Días proporcionales de uso</span>
                        <p class="text-[9px] text-indigo-500 font-bold uppercase mt-0.5">{{ $diasUso }} días hasta fin de mes</p>
                    </div>
                    <span class="text-sm font-black text-gray-900">${{ number_format($costoProrrateo, 2) }}</span>
                </div>

                <div class="flex justify-between items-center mt-2 bg-gray-900 rounded-xl px-5 py-4">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total Neto a Cobrar</span>
                    <span class="text-2xl font-black text-white tracking-tight">
                        ${{ number_format(($tipoReconexion == 'otro' && $servicioSeleccionado ? $paquetes[$servicioSeleccionado]['instalacion'] : 0) + $costoProrrateo, 2) }}
                    </span>
                </div>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-5 py-4 flex items-center justify-between">
                <button wire:click="irAPaso(3)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Volver
                </button>
                <button wire:click="irAPaso(5)"
                        class="inline-flex items-center gap-2 px-7 py-3 bg-emerald-600 text-white rounded-lg font-black text-xs uppercase tracking-widest shadow-md shadow-emerald-200 hover:bg-emerald-700 transition-all active:scale-95">
                    <i class="ri-shield-check-line"></i> Confirmar Ingreso en Caja
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 5 — TÉCNICO Y COMODATO
    ================================================================ --}}
    @if($paso == 5)
    <div class="max-w-2xl mx-auto space-y-5">

        {{-- Resumen --}}
        <div class="bg-gray-900 rounded-xl p-5 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Asignación Final</p>
                <p class="text-xs font-bold text-gray-300 uppercase">{{ $cliente['nombre'] ?? '' }}</p>
            </div>
            <span class="text-[9px] font-black text-emerald-400 bg-white/10 px-2 py-1 rounded-md uppercase tracking-widest">
                Cobro confirmado ✓
            </span>
        </div>

        {{-- Técnico --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                <i class="ri-tools-line text-orange-500"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Técnico para Reconexión Física</p>
                <span class="ml-auto text-[9px] font-bold text-red-500 bg-red-50 border border-red-100 px-2 py-1 rounded-md uppercase">Requerido</span>
            </div>
            <div class="p-5 space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Técnico o Cuadrilla Asignada</label>
                    <div class="relative">
                        <i class="ri-user-star-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <select wire:model.live="tecnicoAsignado"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
                            <option value="">— Seleccione técnico —</option>
                            <option value="Roberto">ING. ROBERTO GÓMEZ (FIBRA)</option>
                            <option value="Juan">ING. JUAN PÉREZ (COAXIAL)</option>
                        </select>
                    </div>
                </div>
                <p class="flex items-center gap-2 text-[10px] font-bold text-indigo-500 uppercase tracking-widest">
                    <i class="ri-message-3-line text-base"></i> Se enviará la orden de trabajo vía SMS al confirmar
                </p>
            </div>
        </div>

        {{-- Comodato --}}
        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                <i class="ri-file-text-line text-indigo-500"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Firma de Renovación de Comodato</p>
            </div>
            <div class="p-5 space-y-4">
                {{-- Área de firma --}}
                <div class="border-2 border-dashed border-gray-200 rounded-xl min-h-[160px] flex items-center justify-center bg-gray-50 hover:border-indigo-300 transition-colors relative group">
                    <div class="text-center">
                        <i class="ri-pen-nib-line text-4xl text-gray-200 group-hover:text-indigo-300 transition-colors block mb-2"></i>
                        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Área de captura biométrica</p>
                    </div>
                    <div class="absolute bottom-8 left-12 right-12 border-b border-gray-200"></div>
                    <p class="absolute bottom-4 left-12 text-[9px] text-gray-300 uppercase tracking-widest font-bold">Firma del titular</p>
                </div>

                <div class="border-2 border-dashed border-indigo-200 rounded-xl p-4 bg-indigo-50/50">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="aceptaTerminos"
                               class="mt-0.5 h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <div>
                            <p class="text-[11px] font-black text-indigo-900 uppercase tracking-widest mb-1">Declaratoria del Suscriptor</p>
                            <p class="text-[10px] text-indigo-600 leading-relaxed font-medium">
                                El cliente confirma que mantiene el equipo original en buen estado y acepta los términos del contrato de adhesión renovado de Tu Visión Telecable.
                            </p>
                        </div>
                    </label>
                </div>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-5 py-4 flex items-center justify-between">
                <button wire:click="irAPaso(4)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Atrás
                </button>
                <button wire:click="finalizar"
                        @if(!$tecnicoAsignado || !$aceptaTerminos) disabled @endif
                        class="inline-flex items-center gap-2 px-7 py-3 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest shadow-md hover:bg-black transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed">
                    <i class="ri-save-3-line"></i> Generar Reporte y Finalizar
                </button>
            </div>
        </div>
    </div>
    @endif

</div>