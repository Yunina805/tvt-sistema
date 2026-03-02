<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================
         ENCABEZADO
    ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-indigo-400">Cobro de Servicios</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-emerald-600">Pago de Mensualidad</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Recepción de Pagos</h2>
            <div class="mt-1">
                <span class="text-[9px] font-mono font-black text-gray-400 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-md uppercase tracking-widest">
                    ID TRANSACCIÓN: {{ $folioRecibo }}
                </span>
            </div>
        </div>
        <div class="flex items-center gap-2 self-start">
            @if($paso === 2)
            <button wire:click="limpiarCliente"
                    class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white border border-gray-200 text-red-500 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-red-50 transition-all">
                <i class="ri-close-line"></i> Cancelar
            </button>
            @endif
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group">
                <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
            </a>
        </div>
    </div>

    {{-- INDICADOR DE PASOS --}}
    <div class="flex items-center mb-8">
        @foreach([1 => 'Buscar Cliente', 2 => 'Configurar Cobro', 3 => 'Recibo'] as $n => $etiqueta)
        <div class="flex items-center {{ $n < 3 ? 'flex-1' : '' }}">
            <div class="flex items-center gap-2 flex-shrink-0">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-black border-2 transition-all
                    {{ $paso > $n ? 'bg-emerald-600 border-emerald-600 text-white' : ($paso === $n ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-gray-200 text-gray-400') }}">
                    @if($paso > $n)
                        <i class="ri-check-line"></i>
                    @else
                        {{ $n }}
                    @endif
                </div>
                <span class="text-[10px] font-black uppercase tracking-widest hidden sm:block
                    {{ $paso >= $n ? 'text-emerald-600' : 'text-gray-400' }}">
                    {{ $etiqueta }}
                </span>
            </div>
            @if($n < 3)
            <div class="flex-1 h-px mx-3 {{ $paso > $n ? 'bg-emerald-400' : 'bg-gray-200' }}"></div>
            @endif
        </div>
        @endforeach
    </div>

    {{-- ================================================================
         PASO 1 — BÚSQUEDA DE CLIENTE
    ================================================================ --}}
    @if($paso === 1)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <i class="ri-user-search-line text-indigo-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Consultar Suscriptor</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Busque por nombre, teléfono, ID de cliente o dirección</p>
                </div>
            </div>

            <div class="p-6 space-y-4">
                {{-- Campo de búsqueda --}}
                <div class="relative">
                    <i class="ri-search-line absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-lg"></i>
                    <input type="text"
                           wire:model.live.debounce.350ms="busqueda"
                           wire:keyup="buscarCliente"
                           placeholder="Ej: Juan Pérez, 9511234567, 01-0001234, Independencia..."
                           autofocus
                           class="w-full pl-10 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium
                                  focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors placeholder:text-gray-300">
                </div>

                {{-- Estado inicial --}}
                @if(strlen($busqueda) < 3)
                <div class="text-center py-8">
                    <i class="ri-search-2-line text-5xl text-gray-200 block mb-2"></i>
                    <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Ingrese al menos 3 caracteres</p>
                </div>
                @endif

                {{-- Sin resultados --}}
                @if(strlen($busqueda) >= 3 && count($resultados) === 0)
                <div class="text-center py-8">
                    <i class="ri-user-unfollow-line text-4xl text-gray-200 block mb-2"></i>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Sin resultados para "{{ $busqueda }}"</p>
                    <p class="text-[10px] text-gray-300 mt-1">Intente con nombre, teléfono o ID del cliente</p>
                </div>
                @endif

                {{-- Resultados --}}
                @if(count($resultados) > 0)
                <div class="border border-gray-200 rounded-xl overflow-hidden divide-y divide-gray-100">
                    @foreach($resultados as $cliente)
                    <button wire:click="seleccionarCliente({{ json_encode($cliente) }})"
                            class="w-full px-5 py-4 text-left hover:bg-indigo-50 transition-colors group flex items-center justify-between gap-3">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight group-hover:text-indigo-700">
                                    {{ $cliente['nombre'] }}
                                </p>
                                <span class="text-[9px] font-mono font-bold text-gray-400 bg-gray-100 px-1.5 py-0.5 rounded">
                                    {{ $cliente['id'] }}
                                </span>
                                <span class="text-[9px] font-black px-1.5 py-0.5 rounded uppercase
                                    {{ $cliente['estado'] === 'Activo' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                                    {{ $cliente['estado'] }}
                                </span>
                            </div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase flex items-center gap-1 truncate">
                                <i class="ri-map-pin-2-line text-orange-400 flex-shrink-0"></i> {{ $cliente['direccion'] }}
                            </p>
                            <div class="flex items-center gap-4 mt-1">
                                <p class="text-[10px] text-gray-500 font-bold uppercase flex items-center gap-1">
                                    <i class="ri-router-line text-indigo-400"></i> {{ $cliente['servicio'] }}
                                </p>
                                <p class="text-[10px] font-black text-red-500 flex items-center gap-1 flex-shrink-0">
                                    <i class="ri-money-dollar-circle-line"></i> ${{ number_format($cliente['saldo'], 2) }}
                                </p>
                            </div>
                        </div>
                        <i class="ri-arrow-right-circle-line text-gray-300 text-2xl group-hover:text-indigo-400 transition-colors flex-shrink-0"></i>
                    </button>
                    @endforeach
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

        {{-- ── COLUMNA IZQ — Expediente del suscriptor ─────────────── --}}
        <div class="lg:col-span-5 space-y-4">

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                {{-- Header oscuro --}}
                <div class="bg-gray-900 px-5 py-5">
                    <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1">Suscriptor Seleccionado</p>
                    <h3 class="text-lg font-black text-white uppercase tracking-tight leading-tight">
                        {{ $clienteSeleccionado['nombre'] }}
                    </h3>
                    <p class="text-[10px] font-mono text-gray-400 mt-0.5">{{ $clienteSeleccionado['id'] }}</p>
                    <p class="flex items-center gap-1.5 text-[10px] text-gray-400 font-bold uppercase mt-2">
                        <i class="ri-map-pin-line text-orange-400"></i> {{ $clienteSeleccionado['direccion'] }}
                    </p>
                    <p class="flex items-center gap-1.5 text-[10px] text-gray-400 font-bold mt-1">
                        <i class="ri-phone-line text-emerald-400"></i> {{ $clienteSeleccionado['telefono'] }}
                    </p>
                    <span class="inline-flex items-center gap-1.5 mt-3 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest
                        {{ $clienteSeleccionado['estado'] === 'Activo' ? 'bg-emerald-500/20 text-emerald-400' : 'bg-red-500/20 text-red-400' }}">
                        <i class="ri-circle-fill text-[5px]"></i> {{ $clienteSeleccionado['estado'] }} · {{ $clienteSeleccionado['sucursal'] }}
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
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Tarifa Base</p>
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
                        <p class="text-2xl font-black text-red-600">${{ number_format($clienteSeleccionado['saldo'], 2) }}</p>
                        @if($clienteSeleccionado['meses_adeudo'] > 1)
                        <p class="text-[9px] text-amber-600 font-black uppercase flex items-center gap-1 justify-end mt-0.5">
                            <i class="ri-alert-line"></i> {{ $clienteSeleccionado['meses_adeudo'] }} meses de adeudo
                        </p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- ── COLUMNA DER — Formulario de cobro ───────────────────── --}}
        <div class="lg:col-span-7 space-y-4">

            {{-- Concepto y forma de pago --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <i class="ri-file-list-3-line text-indigo-600"></i>
                    </div>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Detalle del Cobro</p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- Concepto --}}
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            Concepto de Cobro *
                        </label>
                        <select wire:model.live="concepto"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase
                                       py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
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
                        @if($concepto && $tarifaMonto > 0)
                        <p class="text-[9px] text-indigo-600 font-bold uppercase tracking-widest flex items-center gap-1">
                            <i class="ri-price-tag-3-line"></i> Tarifa base del contrato: ${{ number_format($tarifaMonto, 2) }}
                        </p>
                        @endif
                    </div>

                    {{-- Forma de pago --}}
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            Forma de Pago *
                        </label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($formasPago as $val => $lbl)
                            <label class="flex items-center gap-2 p-3 border rounded-lg cursor-pointer transition-all
                                {{ $formaPago === $val
                                    ? 'border-indigo-400 bg-indigo-50'
                                    : 'border-gray-200 hover:border-indigo-200 hover:bg-indigo-50/30' }}">
                                <input type="radio" wire:model.live="formaPago" value="{{ $val }}" class="sr-only">
                                <i class="text-sm {{ $iconoFormaPago[$val] }}
                                    {{ $formaPago === $val ? 'text-indigo-600' : 'text-gray-400' }}"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest
                                    {{ $formaPago === $val ? 'text-indigo-700' : 'text-gray-500' }}">
                                    {{ $lbl }}
                                </span>
                            </label>
                            @endforeach
                        </div>
                        @error('formaPago')
                        <p class="text-[10px] text-red-500 font-bold flex items-center gap-1">
                            <i class="ri-error-warning-line"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

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
                    {{-- Monto automático --}}
                    <div class="text-center py-2">
                        <p class="text-5xl font-black text-emerald-700 tracking-tight">
                            ${{ number_format($montoCobro, 2) }}
                        </p>
                        <p class="text-[9px] text-emerald-500 font-bold uppercase tracking-widest mt-2">
                            Calculado automáticamente · relacionado con tarifa del contrato
                        </p>
                    </div>
                    @else
                    {{-- Ajuste manual con contraseña --}}
                    <div class="space-y-3">
                        <div class="bg-amber-50 border border-amber-200 rounded-lg px-3 py-2 flex items-center gap-2">
                            <i class="ri-alert-line text-amber-500 flex-shrink-0"></i>
                            <p class="text-[10px] font-black text-amber-700 uppercase tracking-widest">
                                Ajuste manual requiere autorización gerencial
                            </p>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-black text-2xl">$</span>
                            <input type="number" wire:model.live="montoManual" min="1" step="0.01"
                                   class="w-full pl-10 pr-4 py-4 text-3xl font-black bg-gray-50 border border-gray-200
                                          rounded-xl focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400 text-amber-700">
                        </div>
                        @error('montoManual')
                        <p class="text-[10px] text-red-500 font-bold flex items-center gap-1">
                            <i class="ri-error-warning-line"></i> {{ $message }}
                        </p>
                        @enderror
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-red-500 uppercase tracking-widest">
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

            {{-- Factura CFDI --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <label class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 transition-colors
                    {{ $requiereFactura ? 'border-b border-gray-200' : '' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gray-200 flex items-center justify-center">
                            <i class="ri-bill-line text-gray-600"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Solicitar Factura (CFDI)</p>
                            <p class="text-[10px] text-gray-400">Timbrado automático vía API Facturama</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="checkbox" wire:model.live="requiereFactura" class="sr-only peer">
                        <div class="w-10 h-5 bg-gray-200 peer-checked:bg-indigo-600 rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </div>
                </label>

                @if($requiereFactura)
                <div class="p-5 grid grid-cols-2 gap-3">
                    {{-- Razón social --}}
                    <div class="col-span-2 space-y-1">
                        <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Razón Social *</label>
                        <input type="text" wire:model="datosFactura.nombre"
                               class="w-full px-3 py-2.5 text-xs bg-gray-50 border border-gray-200 rounded-lg uppercase font-bold
                                      focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                        @error('datosFactura.nombre')
                        <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                    {{-- RFC --}}
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
                    {{-- C.P. --}}
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
                    {{-- Uso CFDI --}}
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
                    {{-- Correo --}}
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

            {{-- Envío WhatsApp --}}
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

            {{-- Botón confirmar --}}
            <button wire:click="procesarPago"
                    wire:loading.attr="disabled"
                    class="w-full py-4 bg-emerald-600 text-white font-black text-sm uppercase tracking-widest
                           rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all
                           active:scale-95 disabled:opacity-60 flex items-center justify-center gap-2">
                <span wire:loading.remove wire:target="procesarPago">
                    <i class="ri-shield-check-line text-lg"></i> Confirmar y Registrar Pago
                </span>
                <span wire:loading wire:target="procesarPago" class="flex items-center gap-2">
                    <i class="ri-loader-4-line animate-spin text-lg"></i> Procesando...
                </span>
            </button>

        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 3 — RECIBO Y CONFIRMACIÓN WHATSAPP
    ================================================================ --}}
    @if($paso === 3)
    <div class="max-w-xl mx-auto space-y-5">

        {{-- Banner de éxito --}}
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                <i class="ri-checkbox-circle-fill text-emerald-600 text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-black text-emerald-800 uppercase tracking-widest">¡Pago Registrado Exitosamente!</p>
                <p class="text-xs text-emerald-600 mt-0.5">
                    Ingreso contabilizado · Saldo actualizado · Estado de cuenta afectado · Caja afectada
                </p>
            </div>
        </div>

        {{-- ── RECIBO ── --}}
        <div class="bg-white border-2 border-gray-200 rounded-xl shadow-sm overflow-hidden print:shadow-none print:border"
             id="recibo-imprimible">

            {{-- Encabezado del recibo --}}
            <div class="bg-gray-900 px-6 py-5 text-center">
                <div class="flex items-center justify-center gap-2 mb-1">
                    <div class="w-7 h-7 bg-indigo-600 rounded-lg flex items-center justify-center">
                        <i class="ri-tv-line text-white text-sm"></i>
                    </div>
                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Tu Visión Telecable</span>
                </div>
                <h3 class="text-xl font-black text-white uppercase tracking-tight mt-1">Recibo de Pago</h3>
                <p class="text-sm font-mono font-bold text-gray-300 mt-1">{{ $folioRecibo }}</p>
            </div>

            <div class="p-6 space-y-5">

                {{-- Datos del cliente --}}
                <div class="grid grid-cols-2 gap-x-6 gap-y-3">
                    <div class="col-span-2">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Cliente</p>
                        <p class="text-sm font-black text-gray-900 uppercase">{{ $clienteSeleccionado['nombre'] }}</p>
                        <p class="text-[10px] font-mono text-gray-400">{{ $clienteSeleccionado['id'] }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Dirección</p>
                        <p class="text-[10px] font-bold text-gray-700 uppercase leading-tight">
                            {{ $clienteSeleccionado['direccion'] }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Servicio</p>
                        <p class="text-[10px] font-black text-gray-800 uppercase">{{ $clienteSeleccionado['servicio'] }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Fecha</p>
                        <p class="text-xs font-black text-gray-800">{{ $fechaPago }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Sucursal</p>
                        <p class="text-xs font-black text-gray-800 uppercase">{{ $clienteSeleccionado['sucursal'] }}</p>
                    </div>
                </div>

                <hr class="border-dashed border-gray-200">

                {{-- Detalle del pago --}}
                <div class="space-y-2.5">
                    <div class="flex justify-between items-center">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Concepto</p>
                        <p class="text-xs font-black text-gray-800 uppercase">{{ $conceptos[$concepto] ?? $concepto }}</p>
                    </div>
                    <div class="flex justify-between items-center">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Forma de Pago</p>
                        <p class="text-xs font-black text-gray-800 uppercase">{{ $formasPago[$formaPago] ?? $formaPago }}</p>
                    </div>
                    @if($modificarMonto)
                    <div class="flex justify-between items-center">
                        <p class="text-[10px] font-black text-amber-600 uppercase tracking-widest">
                            <i class="ri-edit-line"></i> Monto ajustado manualmente
                        </p>
                        <p class="text-[10px] font-black text-amber-600">Autorización gerencial aplicada</p>
                    </div>
                    @endif
                </div>

                <hr class="border-dashed border-gray-200">

                {{-- Total --}}
                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 flex items-center justify-between">
                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Total Recibido</p>
                    <p class="text-4xl font-black text-emerald-700">${{ number_format($montoFinal, 2) }}</p>
                </div>

                @if($requiereFactura)
                <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-3 flex items-start gap-2.5">
                    <i class="ri-bill-line text-indigo-600 text-lg flex-shrink-0 mt-0.5"></i>
                    <div>
                        <p class="text-[9px] font-black text-indigo-700 uppercase tracking-widest">
                            Factura CFDI en proceso de timbrado
                        </p>
                        <p class="text-[10px] text-indigo-500 mt-0.5">
                            RFC: {{ $datosFactura['rfc'] }} · Uso: {{ $datosFactura['uso_cfdi'] }}
                        </p>
                        <p class="text-[10px] text-indigo-500">
                            Se enviará a: {{ $datosFactura['correo'] }}
                        </p>
                    </div>
                </div>
                @endif

            </div>
        </div>

        {{-- Botón imprimir --}}
        <button onclick="window.print()"
                class="w-full py-3 bg-white border border-gray-200 text-gray-700 font-black text-xs uppercase
                       tracking-widest rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center gap-2 shadow-sm">
            <i class="ri-printer-line text-base text-indigo-400"></i> Imprimir Recibo
        </button>

        {{-- ── CONFIRMACIÓN WHATSAPP ── --}}
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
                            Número de WhatsApp del cliente *
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
                        <p class="text-[10px] text-emerald-600 mt-0.5">
                            Enviado al +52 {{ $telefonoWhatsapp }} vía API de Meta
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Acciones finales --}}
        <div class="grid grid-cols-2 gap-3 pb-4">
            <a href="{{ route('dashboard') }}"
               class="py-3 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase
                      tracking-widest rounded-xl hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
                <i class="ri-home-4-line"></i> Panel Principal
            </a>
            <button wire:click="nuevoPago"
                    class="py-3 bg-indigo-600 text-white font-black text-[10px] uppercase tracking-widest
                           rounded-xl hover:bg-indigo-700 transition-all flex items-center justify-center gap-2
                           shadow-md shadow-indigo-200">
                <i class="ri-add-circle-line"></i> Nuevo Pago
            </button>
        </div>

    </div>
    @endif

</div>
