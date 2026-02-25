<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="mb-8 flex items-center justify-between border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Pago de Mensualidad</h2>
            <p class="text-xs font-mono text-indigo-600 uppercase tracking-widest mt-1">ID PAGO: {{ $pagoId }}</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-gray-500 hover:text-indigo-600 transition">
            <i class="ri-arrow-left-line"></i> Regresar
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-7 space-y-6">
            
            @if(!$clienteSeleccionado)
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-2 text-center">Consultar por Nombre, Teléfono, ID o Dirección</label>
                    <div class="relative">
                        <i class="ri-search-2-line absolute left-4 top-3 text-gray-400 text-xl"></i>
                        <input type="text" wire:model.live.debounce.300ms="busqueda" wire:keyup="buscarCliente"
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border-gray-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 text-lg font-medium" 
                            placeholder="Comience a escribir para buscar...">
                    </div>

                    @if(count($resultados) > 0)
                        <div class="mt-4 border border-gray-100 rounded-xl overflow-hidden shadow-lg animate-in fade-in slide-in-from-top-2">
                            @foreach($resultados as $cliente)
                                <button wire:click="seleccionarCliente({{ json_encode($cliente) }})" class="w-full p-4 text-left hover:bg-indigo-50 border-b flex justify-between items-center transition">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $cliente['nombre'] }}</p>
                                        <p class="text-xs text-gray-500 italic">{{ $cliente['direccion'] }}</p>
                                    </div>
                                    <i class="ri-arrow-right-s-line text-indigo-300"></i>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            @if($clienteSeleccionado)
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="bg-indigo-900 p-6 text-white flex justify-between items-start">
                        <div>
                            <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-1">Datos del Titular</p>
                            <h3 class="text-2xl font-black uppercase">{{ $clienteSeleccionado['nombre'] }}</h3>
                            <p class="text-sm text-indigo-100 mt-1"><i class="ri-map-pin-line mr-1"></i> {{ $clienteSeleccionado['direccion'] }}</p>
                        </div>
                        <button wire:click="$set('clienteSeleccionado', null)" class="text-white/50 hover:text-white transition"><i class="ri-close-circle-fill text-2xl"></i></button>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 bg-gray-50">
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Servicio Activo</p>
                            <p class="font-bold text-gray-800 text-lg mt-1"><i class="ri-router-line text-tvt-orange mr-2"></i> {{ $clienteSeleccionado['servicio'] }}</p>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Saldo Pendiente [cite: 1559]</p>
                            <p class="font-black text-2xl text-red-600 mt-1">${{ number_format($clienteSeleccionado['saldo'], 2) }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-5">
            <div class="bg-white p-6 rounded-2xl border border-gray-200 shadow-xl {{ !$clienteSeleccionado ? 'opacity-40 pointer-events-none' : '' }}">
                <h3 class="text-sm font-bold text-gray-900 uppercase border-b pb-4 mb-6">Procesar Agregar Pago [cite: 1562]</h3>

                <div class="space-y-5">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Concepto de Pago</label>
                        <select wire:model="concepto" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-bold py-3">
                            <option value="">Seleccione Concepto...</option>
                            <option value="MENSUALIDAD">Mensualidad Ordinaria</option>
                            <option value="ADEUDO">Pago de Adeudo</option>
                            <option value="DIAS_USO">Cobro Días de Uso</option>
                        </select>
                        <p class="mt-2 text-[10px] text-indigo-600 font-bold uppercase">Tarifa según contrato: ${{ number_format($tarifaMonto, 2) }}</p>
                    </div>

                    <div class="bg-indigo-50 p-5 rounded-2xl border border-indigo-100 relative overflow-hidden">
                        <div class="flex justify-between items-center mb-3">
                            <span class="text-xs font-bold text-indigo-900 uppercase">Importe a Cobrar</span>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <span class="text-[9px] font-bold text-indigo-400 uppercase">Monto Manual</span>
                                <input type="checkbox" wire:model.live="modificarMonto" class="rounded text-indigo-600 focus:ring-indigo-600 h-4 w-4">
                            </label>
                        </div>

                        @if(!$modificarMonto)
                            <div class="text-4xl font-black text-indigo-900">${{ number_format($montoCobro, 2) }}</div>
                            <p class="text-[9px] text-indigo-400 mt-1 italic font-medium">Importe calculado automáticamente por tarifa.</p>
                        @else
                            <div class="space-y-4 mt-2">
                                <div class="relative">
                                    <span class="absolute left-4 top-2 text-xl font-bold text-gray-400">$</span>
                                    <input type="number" wire:model="montoManual" class="w-full pl-9 pr-4 py-2 text-2xl font-black rounded-xl border-indigo-200 focus:ring-indigo-500">
                                </div>
                                <div class="p-3 bg-white rounded-xl border border-red-100">
                                    <label class="block text-[9px] font-bold text-red-500 uppercase mb-1">Contraseña de Autorización Requerida </label>
                                    <input type="password" wire:model="passwordAutorizacion" class="w-full px-3 py-2 text-sm border-gray-200 rounded-lg focus:ring-red-500 focus:border-red-500">
                                    @error('password') <span class="text-[10px] text-red-600 font-bold mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-gray-100">
                        <label class="flex items-center gap-3 p-3 border border-gray-100 rounded-xl cursor-pointer hover:bg-gray-50 transition mb-4">
                            <input type="checkbox" wire:model.live="requiereFactura" class="w-5 h-5 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <span class="text-sm font-bold text-gray-700 uppercase tracking-tight">Solicitar Factura (API Facturama)</span>
                        </label>

                        @if($requiereFactura)
                            <div class="grid grid-cols-2 gap-3 p-4 bg-gray-50 rounded-2xl border border-gray-200 animate-in fade-in duration-300">
                                <div class="col-span-2">
                                    <label class="text-[9px] font-bold text-gray-400 uppercase">Nombre / Razón Social</label>
                                    <input type="text" wire:model="datosFactura.nombre" class="w-full px-3 py-2 text-xs border-gray-200 rounded-lg uppercase font-bold">
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-gray-400 uppercase">RFC</label>
                                    <input type="text" wire:model="datosFactura.rfc" class="w-full px-3 py-2 text-xs border-gray-200 rounded-lg uppercase">
                                </div>
                                <div>
                                    <label class="text-[9px] font-bold text-gray-400 uppercase">Código Postal</label>
                                    <input type="text" wire:model="datosFactura.cp" class="w-full px-3 py-2 text-xs border-gray-200 rounded-lg">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-[9px] font-bold text-gray-400 uppercase">Correo Electrónico</label>
                                    <input type="email" wire:model="datosFactura.correo" class="w-full px-3 py-2 text-xs border-gray-200 rounded-lg">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-[9px] font-bold text-gray-400 uppercase">Uso CFDI</label>
                                    <select wire:model="datosFactura.uso_cfdi" class="w-full px-3 py-2 text-xs border-gray-200 rounded-lg font-bold">
                                        <option value="G03">G03 - GASTOS EN GENERAL</option>
                                        <option value="S01">S01 - SIN EFECTOS FISCALES</option>
                                    </select>
                                </div>
                            </div>
                        @endif
                    </div>

                    <label class="flex items-center gap-3 px-3 py-2 cursor-pointer group">
                        <input type="checkbox" wire:model="enviarWhatsapp" class="w-4 h-4 text-green-500 border-gray-300 rounded focus:ring-green-500">
                        <span class="text-xs font-bold text-gray-600 group-hover:text-green-600 transition"><i class="ri-whatsapp-line mr-1 text-green-500 text-lg align-middle"></i> Enviar recibo digital por WhatsApp</span>
                    </label>

                    <button wire:click="procesarPago" class="w-full py-4 bg-green-600 text-white font-black uppercase tracking-widest rounded-2xl shadow-lg hover:bg-green-700 hover:shadow-green-500/20 transition-all flex items-center justify-center gap-3 transform active:scale-95">
                        <i class="ri-shield-check-line text-xl"></i> Confirmar Ingreso a Caja
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>