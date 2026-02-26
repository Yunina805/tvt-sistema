<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ENCABEZADO --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-emerald-600">Cobro de Mensualidad</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Recepción de Pagos y Renta
            </h2>
            <div class="flex items-center gap-2 mt-1">
                <span class="text-[9px] font-mono font-black text-gray-400 bg-gray-100 border border-gray-200 px-2.5 py-1 rounded-md uppercase tracking-widest">
                    ID TRANSACCIÓN: {{ $pagoId }}
                </span>
            </div>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ============================================================
             COLUMNA IZQ — BÚSQUEDA Y EXPEDIENTE
        ============================================================ --}}
        <div class="lg:col-span-7 space-y-5">

            {{-- Buscador --}}
            @if(!$clienteSeleccionado)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                        <i class="ri-user-search-line text-indigo-600"></i>
                    </div>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Consultar Suscriptor</p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="relative">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" wire:model.live.debounce.300ms="busqueda"
                               wire:keyup="buscarCliente"
                               placeholder="Nombre, teléfono, ID o dirección..."
                               class="w-full pl-9 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors placeholder:text-gray-300">
                    </div>

                    {{-- Resultados de búsqueda --}}
                    @if(count($resultados) > 0)
                    <div class="border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                        @foreach($resultados as $cliente)
                        <button wire:click="seleccionarCliente({{ json_encode($cliente) }})"
                                class="w-full px-4 py-3.5 text-left hover:bg-indigo-50 border-b border-gray-100 last:border-0 flex justify-between items-center transition-colors group">
                            <div>
                                <p class="text-sm font-black text-gray-900 uppercase tracking-tight group-hover:text-indigo-700">{{ $cliente['nombre'] }}</p>
                                <p class="flex items-center gap-1 text-[10px] text-gray-400 font-bold uppercase mt-0.5">
                                    <i class="ri-map-pin-line text-orange-400"></i> {{ $cliente['direccion'] }}
                                </p>
                            </div>
                            <i class="ri-arrow-right-circle-line text-gray-300 text-xl group-hover:text-indigo-400 transition-colors"></i>
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- Expediente del cliente seleccionado --}}
            @if($clienteSeleccionado)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                {{-- Header oscuro --}}
                <div class="bg-gray-900 px-6 py-5 flex justify-between items-start">
                    <div>
                        <p class="text-[9px] font-black text-indigo-400 uppercase tracking-widest mb-1">Expediente del Suscriptor</p>
                        <h3 class="text-xl font-black text-white uppercase tracking-tight">{{ $clienteSeleccionado['nombre'] }}</h3>
                        <p class="flex items-center gap-1.5 text-xs text-gray-400 font-bold uppercase mt-1.5">
                            <i class="ri-map-pin-line text-orange-400"></i> {{ $clienteSeleccionado['direccion'] }}
                        </p>
                    </div>
                    <button wire:click="$set('clienteSeleccionado', null)"
                            class="w-8 h-8 bg-white/10 hover:bg-red-500 text-white rounded-lg transition-all flex items-center justify-center">
                        <i class="ri-close-line text-base"></i>
                    </button>
                </div>

                {{-- KPIs del cliente --}}
                <div class="grid grid-cols-2 divide-x divide-gray-100 border-t border-gray-800">
                    <div class="p-5 flex items-center gap-3">
                        <div class="w-10 h-10 bg-indigo-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-router-line text-indigo-600 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Servicio Vigente</p>
                            <p class="text-xs font-black text-gray-800 uppercase tracking-tight mt-0.5">{{ $clienteSeleccionado['servicio'] }}</p>
                        </div>
                    </div>
                    <div class="p-5 flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="ri-money-dollar-circle-line text-red-500 text-lg"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">Saldo Pendiente</p>
                            <p class="text-xl font-black text-red-600 tracking-tight mt-0.5">${{ number_format($clienteSeleccionado['saldo'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>

        {{-- ============================================================
             COLUMNA DER — FORMULARIO DE COBRO
        ============================================================ --}}
        <div class="lg:col-span-5">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden {{ !$clienteSeleccionado ? 'opacity-40 pointer-events-none grayscale select-none' : '' }}">

                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                        <i class="ri-hand-coin-line text-emerald-600"></i>
                    </div>
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Procesar Recepción de Pago</p>
                </div>

                <div class="p-5 space-y-4">

                    {{-- Concepto --}}
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Concepto de Cobro *</label>
                        <select wire:model="concepto"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-4 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 transition-colors">
                            <option value="">— Seleccione concepto —</option>
                            <option value="MENSUALIDAD">Mensualidad ordinaria</option>
                            <option value="ADEUDO">Liquidación de adeudo</option>
                            <option value="DIAS_USO">Proporcional / días de uso</option>
                        </select>
                        <p class="text-[9px] text-indigo-600 font-bold uppercase tracking-widest">
                            Tarifa base según contrato: ${{ number_format($tarifaMonto, 2) }}
                        </p>
                    </div>

                    {{-- Monto --}}
                    <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 space-y-3">
                        <div class="flex items-center justify-between">
                            <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Monto a Liquidar</p>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <span class="text-[9px] font-black text-emerald-600 uppercase tracking-widest">Ajuste Manual</span>
                                <div class="relative">
                                    <input type="checkbox" wire:model.live="modificarMonto" class="sr-only peer">
                                    <div class="w-8 h-5 bg-emerald-200 peer-checked:bg-emerald-600 rounded-full transition-colors"></div>
                                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-3"></div>
                                </div>
                            </label>
                        </div>

                        @if(!$modificarMonto)
                        <div class="text-center py-2">
                            <p class="text-4xl font-black text-emerald-800 tracking-tight">${{ number_format($montoCobro, 2) }}</p>
                            <p class="text-[9px] text-emerald-500 font-bold uppercase tracking-widest mt-1">Calculado por sistema</p>
                        </div>
                        @else
                        <div class="space-y-3">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-black text-xl">$</span>
                                <input type="number" wire:model="montoManual"
                                       class="w-full pl-8 pr-4 py-3 text-2xl font-black bg-white border border-emerald-200 rounded-lg focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400 text-emerald-700">
                            </div>
                            <div class="bg-white border border-red-200 rounded-lg p-3 space-y-1.5">
                                <label class="block text-[9px] font-black text-red-500 uppercase tracking-widest">Contraseña Gerencial *</label>
                                <input type="password" wire:model="passwordAutorizacion"
                                       class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-red-500/20 focus:border-red-400"
                                       placeholder="••••••••">
                                @error('passwordAutorizacion') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- Factura --}}
                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                        <label class="flex items-center justify-between p-3.5 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-lg bg-gray-200 flex items-center justify-center">
                                    <i class="ri-bill-line text-gray-600 text-sm"></i>
                                </div>
                                <div>
                                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Solicitar Factura (CFDI)</p>
                                    <p class="text-[10px] text-gray-400">Conexión API Facturama</p>
                                </div>
                            </div>
                            <div class="relative">
                                <input type="checkbox" wire:model.live="requiereFactura" class="sr-only peer">
                                <div class="w-9 h-5 bg-gray-200 peer-checked:bg-indigo-600 rounded-full transition-colors"></div>
                                <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                            </div>
                        </label>

                        @if($requiereFactura)
                        <div class="p-4 border-t border-gray-200 grid grid-cols-2 gap-3">
                            <div class="col-span-2 space-y-1">
                                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Razón Social</label>
                                <input type="text" wire:model="datosFactura.nombre"
                                       class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-lg uppercase font-bold focus:ring-2 focus:ring-indigo-500/20">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">RFC</label>
                                <input type="text" wire:model="datosFactura.rfc"
                                       class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-lg uppercase font-black focus:ring-2 focus:ring-indigo-500/20">
                            </div>
                            <div class="space-y-1">
                                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">C.P. Fiscal</label>
                                <input type="text" wire:model="datosFactura.cp"
                                       class="w-full px-3 py-2 text-xs bg-gray-50 border border-gray-200 rounded-lg font-bold focus:ring-2 focus:ring-indigo-500/20">
                            </div>
                        </div>
                        @endif
                    </div>

                    {{-- WhatsApp --}}
                    <label class="flex items-center gap-3 cursor-pointer p-3 hover:bg-gray-50 rounded-lg transition-colors">
                        <input type="checkbox" wire:model="enviarWhatsapp"
                               class="h-5 w-5 text-emerald-500 border-gray-300 rounded focus:ring-0">
                        <div class="flex items-center gap-2">
                            <i class="ri-whatsapp-line text-emerald-500 text-lg"></i>
                            <span class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Enviar recibo digital por WhatsApp</span>
                        </div>
                    </label>

                </div>

                <div class="bg-gray-50 border-t border-gray-200 p-5 space-y-2">
                    <button wire:click="procesarPago"
                            class="w-full py-3.5 bg-emerald-600 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow-md shadow-emerald-200 hover:bg-emerald-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-shield-check-line text-base"></i> Confirmar Ingreso a Caja
                    </button>
                    <p class="text-center text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                        Al confirmar se genera el registro contable y el folio fiscal automático
                    </p>
                </div>

            </div>
        </div>

    </div>
</div>