<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================
         ENCABEZADO
    ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-red-400"></i>
                <span>Gestión al Suscriptor</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-gray-500">Cobro de Servicios</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-emerald-600">Pago de Mensualidad</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Recepción de Pagos</h2>
            <p class="text-xs text-gray-400 mt-0.5">Suscriptor → Cobro → Recibo · Registro automático en caja y estado de cuenta</p>
        </div>
        <div class="flex items-center gap-2 self-start">
            <span class="text-[9px] font-mono font-black text-gray-400 bg-gray-100 border border-gray-200 px-2.5 py-1.5 rounded-md uppercase tracking-widest">
                {{ $folioRecibo }}
            </span>
            @if($paso === 2)
            <button x-data
                    @click="$confirm('¿Cancelar el cobro en curso? No se guardará ningún registro.', () => $wire.limpiarCliente(), { title: 'Cancelar cobro', confirmText: 'Sí, cancelar', icon: 'warning' })"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-white border border-red-200 text-red-500 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-red-50 transition-all">
                <i class="ri-close-line"></i> Cancelar
            </button>
            @endif
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all">
                <i class="ri-arrow-left-line"></i> Panel Principal
            </a>
        </div>
    </div>

    {{-- STEPPER --}}
    @if($paso < 3)
    <div class="mb-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex">
            @php
                $stepDefs = [
                    1 => ['label' => 'Suscriptor', 'icon' => 'ri-user-search-line'],
                    2 => ['label' => 'Cobro',      'icon' => 'ri-hand-coin-line'],
                ];
            @endphp
            @foreach($stepDefs as $num => $info)
            <div class="flex-1 flex flex-col items-center py-3 px-1 {{ !$loop->last ? 'border-r border-gray-200' : '' }}
                {{ $paso >= $num ? 'bg-emerald-50' : 'bg-gray-50' }}">
                <div class="w-7 h-7 rounded-full flex items-center justify-center mb-1
                    {{ $paso > $num ? 'bg-emerald-600 text-white' : ($paso == $num ? 'bg-emerald-600 text-white' : 'bg-gray-200 text-gray-400') }}">
                    @if($paso > $num)
                        <i class="ri-check-line text-xs"></i>
                    @else
                        <i class="{{ $info['icon'] }} text-xs"></i>
                    @endif
                </div>
                <span class="text-[9px] font-bold uppercase tracking-wider hidden sm:block
                    {{ $paso >= $num ? 'text-emerald-700' : 'text-gray-400' }}">
                    {{ $info['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 1 — BÚSQUEDA DE SUSCRIPTOR
    ================================================================ --}}
    @if($paso === 1)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

            <div class="bg-gray-50/50 border-b border-gray-100 px-6 py-4 flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center flex-shrink-0">
                    <i class="ri-user-search-line text-emerald-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-black text-gray-800 uppercase tracking-widest">Consultar Suscriptor</p>
                    <p class="text-[10px] font-bold text-gray-400 mt-0.5 uppercase tracking-wider">Busque por nombre, teléfono, ID o dirección</p>
                </div>
            </div>

            <div class="p-6">
                {{-- Campo de búsqueda --}}
                <div class="relative mb-4">
                    <i class="ri-search-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                    <input type="text"
                        wire:model.live.debounce.350ms="busqueda"
                        wire:keyup="buscarCliente"
                        placeholder="Ej: Juan Pérez, 9511234567, 01-0001234, Calle Morelos..."
                        autofocus
                        class="w-full pl-11 pr-10 py-3 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-800
                                focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 transition-all placeholder:text-gray-300 shadow-sm">
                    <div wire:loading wire:target="busqueda" class="absolute right-4 top-1/2 -translate-y-1/2">
                        <i class="ri-loader-4-line animate-spin text-emerald-500 text-base"></i>
                    </div>
                </div>

                {{-- Estado inicial --}}
                @if(strlen($busqueda) < 3)
                <div class="text-center py-10 bg-gray-50/50 rounded-xl border border-dashed border-gray-200">
                    <i class="ri-keyboard-line text-2xl text-gray-300 block mb-2"></i>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Ingrese al menos 3 caracteres para buscar</p>
                </div>
                @endif

                {{-- Sin resultados --}}
                @if(strlen($busqueda) >= 3 && count($resultados) === 0)
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <i class="ri-user-unfollow-line text-amber-500 text-xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Sin resultados</p>
                        <p class="text-[10px] text-amber-600 mt-0.5">No se encontró "<strong>{{ $busqueda }}</strong>" · Verifique ortografía o intente con teléfono</p>
                    </div>
                </div>
                @endif

                {{-- Resultados --}}
                @if(count($resultados) > 0)
                <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                    <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 border-b border-gray-100">
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-400">
                            {{ count($resultados) }} suscriptor(es) encontrado(s)
                        </span>
                    </div>
                    <div class="max-h-72 overflow-y-auto divide-y divide-gray-100">
                        @foreach($resultados as $cliente)
                        <div class="flex items-center justify-between p-3.5 hover:bg-emerald-50/50 transition-colors group">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-emerald-100 flex items-center justify-center flex-shrink-0 transition-colors">
                                    <i class="ri-user-3-line text-gray-400 group-hover:text-emerald-600 transition-colors text-sm"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <p class="text-xs font-black text-gray-900 uppercase tracking-tight truncate">{{ $cliente['nombre'] }}</p>
                                        <span class="text-[8px] font-black px-1.5 py-0.5 rounded uppercase flex-shrink-0
                                            {{ $cliente['estado'] === 'ACTIVO' ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-red-50 text-red-600 border border-red-100' }}">
                                            {{ $cliente['estado'] }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-2 text-[9px] font-bold text-gray-500 uppercase flex-wrap">
                                        <span class="font-mono bg-gray-100 px-1 py-0.5 rounded text-gray-600">{{ $cliente['id'] }}</span>
                                        <span class="text-gray-300">·</span>
                                        <span class="truncate max-w-[140px]">{{ $cliente['direccion'] }}</span>
                                        <span class="text-gray-300">·</span>
                                        <span class="{{ $cliente['saldo'] > 0 ? 'text-red-500' : 'text-emerald-500' }} font-black">
                                            Saldo: ${{ number_format($cliente['saldo'], 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <button wire:click="seleccionarCliente({{ json_encode($cliente) }})"
                                    class="flex-shrink-0 ml-3 px-3 py-1.5 bg-white border border-gray-200 text-gray-600 hover:text-emerald-700 hover:border-emerald-300 hover:bg-emerald-50 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all shadow-sm">
                                Seleccionar
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
         PASO 2 — FORMULARIO DE COBRO
    ================================================================ --}}
    @if($paso === 2)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ── COLUMNA IZQ — Expediente del suscriptor ── --}}
        <div class="lg:col-span-5 space-y-4">

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                {{-- Header oscuro --}}
                <div class="bg-gray-900 px-5 py-5">
                    <p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-1">Suscriptor Seleccionado</p>
                    <h3 class="text-base font-black text-white uppercase tracking-tight leading-tight">
                        {{ $clienteSeleccionado['nombre'] }}
                    </h3>
                    <p class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $clienteSeleccionado['id'] }}</p>
                    <p class="flex items-center gap-1.5 text-[10px] text-gray-400 font-bold uppercase mt-2 leading-tight">
                        <i class="ri-map-pin-line text-orange-400 flex-shrink-0"></i>
                        <span>{{ $clienteSeleccionado['direccion'] }}</span>
                    </p>
                    <p class="flex items-center gap-1.5 text-[10px] text-gray-400 font-bold mt-1">
                        <i class="ri-phone-line text-emerald-400"></i> {{ $clienteSeleccionado['telefono'] }}
                    </p>
                    <span class="inline-flex items-center gap-1.5 mt-3 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest
                        {{ $clienteSeleccionado['estado'] === 'ACTIVO' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                        <i class="ri-circle-fill text-[5px]"></i>
                        {{ $clienteSeleccionado['estado'] }} · {{ $clienteSeleccionado['sucursal'] }}
                    </span>
                </div>

                {{-- Servicio y tarifa --}}
                <div class="grid grid-cols-2 divide-x divide-gray-100">
                    <div class="p-4 flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-indigo-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-router-line text-indigo-600"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Servicio Activo</p>
                            <p class="text-[10px] font-black text-gray-800 uppercase leading-tight mt-0.5">
                                {{ $clienteSeleccionado['servicio'] }}
                            </p>
                        </div>
                    </div>
                    <div class="p-4 flex items-center gap-2.5">
                        <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-price-tag-3-line text-emerald-600"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Tarifa Mensual</p>
                            <p class="text-xl font-black text-emerald-600">${{ number_format($clienteSeleccionado['tarifa'], 2) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Saldo pendiente --}}
                <div class="border-t border-gray-100 p-4 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ri-money-dollar-circle-line text-red-500 text-lg"></i>
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Saldo Pendiente</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-black {{ $clienteSeleccionado['saldo'] > 0 ? 'text-red-600' : 'text-emerald-600' }}">
                            ${{ number_format($clienteSeleccionado['saldo'], 2) }}
                        </p>
                        @if($clienteSeleccionado['meses_adeudo'] > 1)
                        <p class="text-[9px] text-amber-600 font-black uppercase flex items-center gap-1 justify-end mt-0.5">
                            <i class="ri-alert-line"></i> {{ $clienteSeleccionado['meses_adeudo'] }} meses de adeudo
                        </p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ── COLUMNA DER — Formulario de cobro ── --}}
        <div class="lg:col-span-7 space-y-4">

            {{-- Concepto de cobro --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i class="ri-file-list-3-line text-emerald-600"></i>
                    </div>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Concepto y Período</p>
                </div>
                <div class="p-5 space-y-3">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            Concepto de Cobro *
                        </label>
                        <select wire:model.live="concepto"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase
                                       py-2.5 px-4 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 transition-colors">
                            <option value="">— Seleccione concepto —</option>
                            @foreach($conceptos as $key => $etiqueta)
                            <option value="{{ $key }}">{{ $etiqueta }}</option>
                            @endforeach
                        </select>
                        @error('concepto')
                        <p class="text-[10px] text-red-500 font-bold flex items-center gap-1">
                            <i class="ri-error-warning-line"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                    {{-- Tarifa vigente + período --}}
                    @if($concepto)
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-indigo-50 border border-indigo-100 rounded-lg px-3 py-2.5">
                            <p class="text-[9px] font-black text-indigo-600 uppercase tracking-widest mb-0.5">Tarifa Vigente</p>
                            <p class="text-sm font-black text-indigo-800">${{ number_format($tarifaMonto, 2) }}/mes</p>
                        </div>
                        <div class="bg-emerald-50 border border-emerald-100 rounded-lg px-3 py-2.5">
                            <p class="text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-0.5">Período que se Paga</p>
                            <p class="text-[10px] font-black text-emerald-800 uppercase leading-tight">{{ $periodoLabel ?: '—' }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Monto a recibir --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-emerald-50 border-b border-emerald-100 px-5 py-3.5 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <i class="ri-hand-coin-line text-emerald-600"></i>
                        </div>
                        <p class="text-[11px] font-black text-emerald-800 uppercase tracking-widest">Monto a Recibir</p>
                    </div>
                    {{-- Toggle ajuste manual --}}
                    <label class="flex items-center gap-2 cursor-pointer">
                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Ajuste Manual</span>
                        <div class="relative">
                            <input type="checkbox" wire:model.live="modificarMonto" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-300 peer-checked:bg-amber-500 rounded-full transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                        </div>
                    </label>
                </div>

                <div class="p-5">
                    @if(!$modificarMonto)
                    <div class="text-center py-2">
                        <p class="text-5xl font-black text-emerald-700 tracking-tight">
                            ${{ number_format($montoCobro, 2) }}
                        </p>
                        <p class="text-[9px] text-emerald-500 font-bold uppercase tracking-widest mt-2">
                            Calculado automáticamente según tarifa del contrato
                        </p>
                    </div>
                    @else
                    <div class="space-y-3">
                        <div class="bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 flex items-center gap-2">
                            <i class="ri-alert-line text-amber-500 flex-shrink-0"></i>
                            <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">
                                Ajuste manual requiere autorización gerencial
                            </p>
                        </div>
                        {{-- Monto manual --}}
                        <div>
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Nuevo Monto *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-black text-2xl">$</span>
                                <input type="number" wire:model.live="montoManual" min="1" step="0.01"
                                       class="w-full pl-10 pr-4 py-4 text-3xl font-black bg-gray-50 border border-gray-200
                                              rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 text-amber-700">
                            </div>
                            @error('montoManual')
                            <p class="text-[10px] text-red-500 font-bold flex items-center gap-1 mt-1">
                                <i class="ri-error-warning-line"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>
                        {{-- Motivo del ajuste --}}
                        <div>
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">
                                Motivo del Ajuste *
                            </label>
                            <input type="text" wire:model="motivoAjuste"
                                   class="w-full px-3 py-2.5 text-xs border border-gray-200 rounded-lg
                                          focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400"
                                   placeholder="Ej: Cortesía por falla de servicio, descuento promocional...">
                            @error('motivoAjuste')
                            <p class="text-[10px] text-red-500 font-bold flex items-center gap-1 mt-1">
                                <i class="ri-error-warning-line"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>
                        {{-- Contraseña gerencial --}}
                        <div>
                            <label class="block text-[9px] font-black text-red-500 uppercase tracking-widest mb-1">
                                Contraseña Gerencial *
                            </label>
                            <input type="password" wire:model="passwordAutorizacion"
                                   class="w-full px-3 py-2.5 text-sm border border-gray-200 rounded-lg
                                          focus:ring-2 focus:ring-red-500/20 focus:border-red-400"
                                   placeholder="••••••••">
                            @error('passwordAutorizacion')
                            <p class="text-[10px] text-red-500 font-bold flex items-center gap-1 mt-1">
                                <i class="ri-error-warning-line"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Método de pago --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-secure-payment-line text-emerald-500"></i> Método de Pago *
                </h3>
                <div class="space-y-2">
                    @foreach([
                        'efectivo'      => ['icon' => 'ri-money-dollar-box-line', 'label' => 'Efectivo'],
                        'transferencia' => ['icon' => 'ri-bank-line',             'label' => 'Transferencia Bancaria'],
                        'tarjeta'       => ['icon' => 'ri-bank-card-line',        'label' => 'Tarjeta Débito / Crédito'],
                        'deposito'      => ['icon' => 'ri-building-2-line',       'label' => 'Depósito Bancario'],
                    ] as $val => $mp)
                    <button type="button"
                            wire:click="$set('formaPago', '{{ $val }}')"
                            class="w-full flex items-center gap-3 p-3 border rounded-xl transition-all text-left
                                {{ $formaPago === $val
                                    ? 'border-emerald-500 bg-emerald-50 shadow-sm'
                                    : 'border-gray-200 hover:border-emerald-300 hover:bg-emerald-50/50' }}">
                        <i class="{{ $mp['icon'] }} text-lg {{ $formaPago === $val ? 'text-emerald-600' : 'text-gray-400' }}"></i>
                        <span class="text-xs font-bold flex-1 {{ $formaPago === $val ? 'text-emerald-700' : 'text-gray-700' }}">
                            {{ $mp['label'] }}
                        </span>
                        <i class="{{ $formaPago === $val ? 'ri-checkbox-circle-fill text-emerald-500' : 'ri-checkbox-blank-circle-line text-gray-300' }}"></i>
                    </button>
                    @endforeach
                </div>
                @error('formaPago')
                <p class="text-[10px] text-red-500 font-bold flex items-center gap-1 mt-2">
                    <i class="ri-error-warning-line"></i> {{ $message }}
                </p>
                @enderror
            </div>

            {{-- Factura CFDI --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <label class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 transition-colors
                    {{ $requiereFactura ? 'border-b border-gray-200' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg {{ $requiereFactura ? 'bg-indigo-100' : 'bg-gray-100' }} flex items-center justify-center transition-colors">
                            <i class="ri-bill-line {{ $requiereFactura ? 'text-indigo-600' : 'text-gray-500' }} transition-colors"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Solicitar Factura (CFDI)</p>
                            <p class="text-[10px] text-gray-400">Timbrado automático vía API Facturama</p>
                        </div>
                    </div>
                    <div class="relative flex-shrink-0">
                        <input type="checkbox" wire:model.live="requiereFactura" class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 peer-checked:bg-indigo-600 rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                </label>

                @if($requiereFactura)
                <div class="p-5 grid grid-cols-2 gap-3">
                    <div class="col-span-2 space-y-1">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Nombre o Razón Social *</label>
                        <input type="text" wire:model="datosFactura.nombre"
                               class="w-full px-3 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-lg uppercase font-bold
                                      focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                        @error('datosFactura.nombre')
                        <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">RFC *</label>
                        <input type="text" wire:model="datosFactura.rfc" maxlength="13"
                               class="w-full px-3 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-lg uppercase font-black
                                      focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                               placeholder="XAXX010101000">
                        @error('datosFactura.rfc')
                        <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">C.P. Fiscal *</label>
                        <input type="text" wire:model="datosFactura.cp" maxlength="5"
                               class="w-full px-3 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-lg font-bold
                                      focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                               placeholder="68000">
                        @error('datosFactura.cp')
                        <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2 space-y-1">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Uso CFDI *</label>
                        <select wire:model="datosFactura.uso_cfdi"
                                class="w-full px-3 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-lg font-bold
                                       focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                            @foreach($usoCfdiOpciones as $key => $lbl)
                            <option value="{{ $key }}">{{ $lbl }}</option>
                            @endforeach
                        </select>
                        @error('datosFactura.uso_cfdi')
                        <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="col-span-2 space-y-1">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Correo Electrónico *</label>
                        <input type="email" wire:model="datosFactura.correo"
                               class="w-full px-3 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-lg font-bold
                                      focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                               placeholder="cliente@correo.com">
                        @error('datosFactura.correo')
                        <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                @endif
            </div>

            {{-- Envío WhatsApp toggle --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm">
                <label class="flex items-center gap-3 cursor-pointer p-4 hover:bg-gray-50 transition-colors rounded-xl">
                    <div class="relative flex-shrink-0">
                        <input type="checkbox" wire:model.live="enviarWhatsapp" class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 peer-checked:bg-[#25D366] rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                    <div class="flex items-center gap-2.5">
                        <i class="ri-whatsapp-line text-[#25D366] text-xl"></i>
                        <div>
                            <span class="block text-[11px] font-black text-gray-700 uppercase tracking-widest">
                                Enviar recibo digital por WhatsApp
                            </span>
                            <span class="text-[10px] text-gray-400">
                                API de Meta · Tel. registrado: {{ $clienteSeleccionado['telefono'] }}
                            </span>
                        </div>
                    </div>
                </label>
            </div>

            {{-- Barra total + botón confirmar --}}
            <div class="flex items-center justify-between gap-4 bg-gray-900 rounded-xl px-6 py-5">
                <div>
                    <p class="text-[9px] font-black text-emerald-400 uppercase tracking-widest mb-1">Total a Recibir</p>
                    <p class="text-3xl font-black text-white tracking-tight">
                        ${{ number_format($modificarMonto ? $montoManual : $montoCobro, 2) }}
                    </p>
                    @if($periodoLabel)
                    <p class="text-[9px] text-gray-500 font-bold uppercase mt-1">{{ $periodoLabel }}</p>
                    @endif
                </div>
                <button x-data
                        @click="$confirm(
                            '¿Confirmar pago de ${{ number_format($modificarMonto ? $montoManual : $montoCobro, 2) }} para {{ addslashes($clienteSeleccionado['nombre']) }}?\n\nConcepto: {{ $conceptos[$concepto] ?? $concepto }}\nForma de pago: {{ $formaPago }}',
                            () => $wire.procesarPago(),
                            { confirmText: 'Sí, registrar pago', title: 'Confirmar Pago' }
                        )"
                        wire:loading.attr="disabled"
                        class="inline-flex items-center gap-2 px-7 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-lg shadow-emerald-900/20 transition-all active:scale-95 disabled:opacity-60">
                    <span wire:loading.remove wire:target="procesarPago">
                        <i class="ri-shield-check-line text-base"></i> Confirmar Pago
                    </span>
                    <span wire:loading wire:target="procesarPago" class="flex items-center gap-2">
                        <i class="ri-loader-4-line animate-spin text-base"></i> Procesando...
                    </span>
                </button>
            </div>

        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 3 — RECIBO Y CONFIRMACIÓN WHATSAPP
    ================================================================ --}}
    @if($paso === 3)
    <div class="max-w-2xl mx-auto space-y-5">

        {{-- Banner de éxito --}}
        <div class="bg-emerald-600 rounded-xl px-6 py-8 text-center text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10"
                 style="background-image: radial-gradient(circle, white 1px, transparent 0); background-size: 24px 24px;"></div>
            <div class="relative z-10">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <i class="ri-checkbox-circle-fill text-3xl"></i>
                </div>
                <h2 class="text-xl font-black uppercase tracking-tight">¡Pago Registrado!</h2>
                <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest mt-1">
                    Ingreso contabilizado · Saldo actualizado · Caja afectada · Estado de cuenta registrado
                </p>
                <span class="inline-block mt-3 font-mono text-sm font-black text-white bg-white/20 px-4 py-1.5 rounded-lg">
                    {{ $folioRecibo }}
                </span>
            </div>
        </div>

        {{-- RECIBO DIGITAL --}}
        <div class="bg-white border-2 border-gray-200 rounded-xl shadow-sm overflow-hidden" id="recibo-imprimible">

            {{-- Header del recibo --}}
            <div class="bg-gray-900 px-6 py-5 flex items-center justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <div class="w-6 h-6 bg-emerald-600 rounded-md flex items-center justify-center">
                            <i class="ri-tv-line text-white text-xs"></i>
                        </div>
                        <span class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Tu Visión Telecable</span>
                    </div>
                    <p class="text-lg font-black text-white uppercase tracking-tight">Recibo de Pago</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">{{ $fechaPago }}</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">ID de Pago</p>
                    <p class="font-mono text-sm font-black text-emerald-400">{{ $folioRecibo }}</p>
                    @if($montoAjustado)
                    <span class="inline-block mt-1 text-[8px] font-black text-amber-400 bg-amber-900/30 px-1.5 py-0.5 rounded uppercase tracking-widest">
                        Monto ajustado
                    </span>
                    @endif
                </div>
            </div>

            <div class="p-6 space-y-5">

                {{-- Datos del suscriptor --}}
                <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-xs">
                    <div class="col-span-2">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Nombre del Suscriptor</p>
                        <p class="text-sm font-black text-gray-900 uppercase">{{ $clienteSeleccionado['nombre'] }}</p>
                        <p class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $clienteSeleccionado['id'] }} · {{ $clienteSeleccionado['sucursal'] }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Dirección</p>
                        <p class="text-[10px] font-bold text-gray-700 uppercase leading-tight">{{ $clienteSeleccionado['direccion'] }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Servicio</p>
                        <p class="text-[10px] font-black text-gray-800 uppercase">{{ $clienteSeleccionado['servicio'] }}</p>
                    </div>
                </div>

                <hr class="border-dashed border-gray-200">

                {{-- Desglose del pago --}}
                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-gray-50 border-b border-gray-200 px-5 py-3">
                        <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Desglose del Cobro</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="flex items-center justify-between px-5 py-3.5">
                            <div>
                                <p class="text-[10px] font-black text-gray-700 uppercase">
                                    {{ $conceptos[$concepto] ?? $concepto }}
                                </p>
                                @if($periodoLabel)
                                <p class="text-[9px] text-emerald-600 font-bold uppercase mt-0.5">
                                    <i class="ri-calendar-line"></i> {{ $periodoLabel }}
                                </p>
                                @endif
                                <p class="text-[9px] text-gray-400 font-medium mt-0.5 uppercase">
                                    {{ $formasPago[$formaPago] ?? $formaPago }}
                                </p>
                            </div>
                            <p class="text-sm font-black text-gray-700">${{ number_format($subtotalRecibo, 2) }}</p>
                        </div>
                        <div class="flex items-center justify-between px-5 py-3.5">
                            <p class="text-[10px] font-bold text-gray-500 uppercase">IVA (16%)</p>
                            <p class="text-sm font-bold text-gray-600">${{ number_format($ivaRecibo, 2) }}</p>
                        </div>
                        @if($montoAjustado)
                        <div class="flex items-center justify-between px-5 py-3 bg-amber-50">
                            <p class="text-[10px] font-black text-amber-700 uppercase flex items-center gap-1">
                                <i class="ri-edit-line"></i> Monto ajustado con autorización gerencial
                            </p>
                        </div>
                        @endif
                        <div class="flex items-center justify-between px-5 py-4 bg-gray-900">
                            <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest">Total Recibido</p>
                            <p class="text-2xl font-black text-white">${{ number_format($totalRecibo, 2) }}</p>
                        </div>
                    </div>
                </div>

                @if($requiereFactura)
                <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-3 flex items-start gap-2.5">
                    <i class="ri-bill-line text-indigo-600 text-lg flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-[9px] font-black text-indigo-700 uppercase tracking-widest">Factura CFDI en proceso de timbrado</p>
                        <p class="text-[10px] text-indigo-500 mt-0.5">RFC: {{ $datosFactura['rfc'] }} · CFDI: {{ $datosFactura['uso_cfdi'] }}</p>
                        <p class="text-[10px] text-indigo-500">Envío a: {{ $datosFactura['correo'] }}</p>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- Botón imprimir --}}
        <button onclick="window.print()"
                class="w-full py-3 bg-white border border-gray-200 text-gray-700 font-black text-xs uppercase
                       tracking-widest rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center gap-2 shadow-sm">
            <i class="ri-printer-line text-base text-emerald-500"></i> Imprimir Recibo
        </button>

        {{-- CONFIRMACIÓN WHATSAPP --}}
        @if($enviarWhatsapp)
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-[#25D366]/20 flex items-center justify-center">
                    <i class="ri-whatsapp-line text-[#25D366] text-lg"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Envío por WhatsApp</p>
                    <p class="text-[10px] text-gray-400">Confirme el número antes de enviar — API de Meta</p>
                </div>
            </div>

            <div class="p-5">
                @if(!$whatsappEnviado)
                <div class="space-y-3">
                    <div class="space-y-1.5">
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">
                            Número de WhatsApp *
                        </label>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-2.5 bg-gray-100 border border-gray-200 rounded-lg text-xs font-black
                                         text-gray-500 flex items-center gap-1.5 flex-shrink-0">
                                <i class="ri-flag-line text-gray-400"></i> +52
                            </span>
                            <input type="tel" wire:model="telefonoWhatsapp" maxlength="10"
                                   class="flex-1 px-3 py-2.5 text-sm font-bold border border-gray-200 rounded-lg
                                          focus:ring-2 focus:ring-[#25D366]/30 focus:border-[#25D366] transition-colors"
                                   placeholder="9511234567">
                        </div>
                        @error('telefonoWhatsapp')
                        <p class="text-[10px] text-red-500 font-bold flex items-center gap-1">
                            <i class="ri-error-warning-line"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>
                    <button wire:click="enviarWhatsappRecibo"
                            wire:loading.attr="disabled"
                            class="w-full py-3 bg-[#25D366] text-white font-black text-xs uppercase tracking-widest
                                   rounded-xl hover:brightness-110 transition-all flex items-center justify-center gap-2
                                   shadow-md shadow-green-200 disabled:opacity-60">
                        <span wire:loading.remove wire:target="enviarWhatsappRecibo">
                            <i class="ri-whatsapp-line text-base"></i> Enviar Recibo por WhatsApp
                        </span>
                        <span wire:loading wire:target="enviarWhatsappRecibo" class="flex items-center gap-2">
                            <i class="ri-loader-4-line animate-spin text-base"></i> Enviando...
                        </span>
                    </button>
                </div>
                @else
                <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                    <i class="ri-checkbox-circle-fill text-emerald-500 text-2xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">¡Recibo enviado!</p>
                        <p class="text-[10px] text-emerald-600 mt-0.5">Enviado al +52 {{ $telefonoWhatsapp }} vía API de Meta</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Acciones finales --}}
        <div class="flex items-center justify-between gap-3 bg-white border border-gray-200 rounded-xl px-5 py-4">
            <button wire:click="nuevoPago"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 text-gray-700 font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-gray-200 transition-all">
                <i class="ri-add-circle-line"></i> Nuevo Pago
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
