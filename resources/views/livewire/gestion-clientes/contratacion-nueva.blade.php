<div class="max-w-6xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between border-b border-gray-200 pb-4">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 md:mb-0">Contratación de Cliente Nuevo</h2>
        <div class="flex space-x-2 text-sm font-medium overflow-x-auto pb-2 md:pb-0">
            <span class="{{ $paso >= 1 ? 'text-indigo-600' : 'text-gray-400' }}">1. Servicio</span>
            <span class="text-gray-300">/</span>
            <span class="{{ $paso >= 2 ? 'text-indigo-600' : 'text-gray-400' }}">2. Datos</span>
            <span class="text-gray-300">/</span>
            <span class="{{ $paso >= 3 ? 'text-indigo-600' : 'text-gray-400' }}">3. Caja</span>
            <span class="text-gray-300">/</span>
            <span class="{{ $paso >= 4 ? 'text-indigo-600' : 'text-gray-400' }}">4. Contrato</span>
            <span class="text-gray-300">/</span>
            <span class="{{ $paso >= 5 ? 'text-green-600' : 'text-gray-400' }}">5. Técnico</span>
        </div>
    </div>

    @if($paso == 1)
        <div class="bg-white p-6 border rounded-xl shadow-sm mb-6">
            <div class="mb-6 border-b pb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Paso Previo: Identificación Oficial (Foto)</label>
                <input type="file" wire:model="identificacion" class="block w-full md:w-1/2 text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
            </div>

            <div class="flex justify-between items-end mb-4">
                <h3 class="text-lg font-medium text-gray-900">Seleccione el Paquete</h3>
                <span class="text-sm text-gray-500">Tarifas Vigentes</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($paquetes as $key => $paquete)
                    <div class="bg-white border {{ $servicioSeleccionado === $key ? 'border-indigo-500 ring-1 ring-indigo-500 shadow-md' : 'border-gray-200 hover:shadow-lg' }} rounded-xl overflow-hidden transition-all">
                        <div class="bg-gray-50 p-4 border-b text-center">
                            <h4 class="font-bold text-gray-800 text-lg uppercase">{{ $paquete['nombre'] }}</h4>
                        </div>
                        <div class="p-6 text-center">
                            <p class="text-3xl font-extrabold text-indigo-600">${{ number_format($paquete['mensualidad'], 2) }}</p>
                            <p class="text-xs text-gray-500 uppercase mt-1">Mensualidad</p>
                            
                            <div class="flex justify-center space-x-4 text-sm text-gray-600 mt-4">
                                @if($paquete['canales'] > 0)
                                    <span class="flex items-center"><i class="ri-tv-2-line mr-1"></i> {{ $paquete['canales'] }} Canales</span>
                                @endif
                                @if($paquete['internet'])
                                    <span class="flex items-center"><i class="ri-wifi-line mr-1"></i> Internet</span>
                                @endif
                            </div>
                        </div>
                        <div class="bg-gray-50 p-4 border-t text-sm flex flex-col justify-center items-center gap-3">
                            <span class="text-gray-600">Instalación Única: <strong class="text-gray-900">${{ number_format($paquete['instalacion'], 2) }}</strong></span>
                            <button wire:click="seleccionarServicio('{{ $key }}')" class="w-full bg-{{ $servicioSeleccionado === $key ? 'indigo-600' : 'white border border-gray-300 text-gray-700' }} {{ $servicioSeleccionado === $key ? 'text-white' : 'hover:bg-gray-50' }} px-4 py-2 rounded-lg font-medium transition">
                                {{ $servicioSeleccionado === $key ? 'Seleccionado' : 'Elegir' }}
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            @if($servicioSeleccionado)
                <div class="mt-8 flex justify-end">
                    <button wire:click="irAPaso(2)" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Capturar Datos del Cliente <i class="ri-arrow-right-line align-middle"></i></button>
                </div>
            @endif
        </div>
    @endif

    @if($paso == 2)
        <div class="bg-white p-8 border rounded-xl shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-6 border-b pb-2">Información del Cliente</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nombre(s) *</label>
                    <input type="text" wire:model="nombre" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Apellidos *</label>
                    <input type="text" wire:model="apellidos" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Teléfono (10 dígitos) *</label>
                    <input type="text" wire:model="telefono" maxlength="10" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">CURP</label>
                    <input type="text" wire:model="curp" maxlength="18" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 uppercase">
                </div>

                <div class="md:col-span-2 mt-4">
                    <h4 class="font-medium text-gray-800 mb-3 border-b pb-2">Dirección de Instalación</h4>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Calle (Del Catálogo)</label>
                            <select wire:model="calle" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Seleccione...</option>
                                <option value="Independencia">Av. Independencia</option>
                                <option value="Reforma">Calle Reforma</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Números (Ext / Int)</label>
                            <div class="flex space-x-2 mt-1">
                                <input type="text" wire:model="num_ext" placeholder="Ext" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <input type="text" wire:model="num_int" placeholder="Int" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Referencias para el Técnico</label>
                            <textarea wire:model="referencias" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2 mt-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="requiereFactura" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 h-5 w-5">
                        <span class="text-gray-900 font-medium">¿El cliente requiere Factura (CFDI)?</span>
                    </label>

                    @if($requiereFactura)
                        <div class="mt-4 grid grid-cols-2 gap-4">
                            <div><label class="block text-xs font-medium text-gray-700">RFC</label><input type="text" wire:model="rfc" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm uppercase text-sm"></div>
                            <div><label class="block text-xs font-medium text-gray-700">Código Postal</label><input type="text" wire:model="cp_fiscal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm"></div>
                            <div><label class="block text-xs font-medium text-gray-700">Correo Electrónico</label><input type="email" wire:model="correo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm"></div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Uso CFDI</label>
                                <select wire:model="uso_cfdi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm"><option value="G03">G03 - Gastos en general</option></select>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-8 flex justify-between">
                <button wire:click="irAPaso(1)" class="text-gray-600 hover:text-gray-900 font-medium">Atrás</button>
                <button wire:click="irAPaso(3)" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition font-medium">Proceder a Caja</button>
            </div>
        </div>
    @endif

    @if($paso == 3)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="bg-white p-6 border rounded-xl shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Desglose de Cobro</h3>
                <p class="text-xs text-gray-500 font-mono mb-4">Folio: {{ $folioPago }}</p>
                
                <div class="space-y-3">
                    <div class="flex justify-between text-gray-700">
                        <span>Instalación</span>
                        <span class="font-medium">${{ number_format($paquetes[$servicioSeleccionado]['instalacion'], 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-700 pb-3 border-b">
                        <div>
                            <span>Mensualidad Proporcional</span>
                            <p class="text-xs text-gray-400">{{ $diasUso }} días calculados de uso</p>
                        </div>
                        <span class="font-medium">${{ number_format($costoProrrateo, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between text-xs text-gray-500 pt-2">
                        <span>Subtotal</span><span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-gray-500">
                        <span>IVA (16%)</span><span>${{ number_format($iva, 2) }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center pt-4 mt-2 border-t text-xl font-bold text-gray-900">
                        <span>Total a Cobrar</span>
                        <span>${{ number_format($totalPagar, 2) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 border rounded-xl shadow-sm">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ingreso en Caja</h3>
                
                <label class="block text-sm font-medium text-gray-700 mb-2">Método de Pago</label>
                <select wire:model="metodo_pago" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mb-6">
                    <option value="efectivo">Efectivo</option>
                    <option value="tarjeta">Tarjeta (Terminal)</option>
                    <option value="transferencia">Transferencia</option>
                </select>

                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
                    <label class="flex items-start space-x-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="confirmacionCaja" class="mt-1 h-5 w-5 text-green-600 rounded border-gray-300">
                        <span class="text-sm text-gray-800">
                            <strong>Confirmar Recepción del Pago</strong><br>
                            Certifico que el monto fue ingresado a caja. Esto generará el ID del Cliente.
                        </span>
                    </label>
                </div>

                <div class="flex justify-between">
                    <button wire:click="irAPaso(2)" class="text-gray-600 hover:text-gray-900 font-medium">Atrás</button>
                    <button wire:click="confirmarCobro" @if(!$confirmacionCaja) disabled @endif class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition font-medium disabled:opacity-50">Confirmar Ingreso</button>
                </div>
            </div>
        </div>
    @endif

    @if($paso == 4)
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
            <div class="md:col-span-4 space-y-6">
                <div class="bg-white p-6 border rounded-xl shadow-sm">
                    <h3 class="text-sm font-bold text-gray-600 uppercase mb-4 tracking-wide">Resumen de Contratación</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Titular:</p>
                            <p class="text-sm font-bold text-gray-800 uppercase">{{ $nombre . ' ' . $apellidos }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Servicio Seleccionado:</p>
                            <p class="text-sm font-bold text-indigo-700 uppercase">{{ $paquetes[$servicioSeleccionado]['nombre'] ?? '--' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase">Domicilio de Instalación:</p>
                            <p class="text-xs text-gray-700">{{ $calle }} {{ $num_ext }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl flex gap-3 text-blue-700">
                    <i class="ri-information-fill text-xl shrink-0"></i>
                    <p class="text-xs leading-relaxed">Al firmar, el cliente acepta los términos y condiciones del contrato de adhesión de <strong>Tu Visión Telecable</strong>.</p>
                </div>
            </div>

            <div class="md:col-span-8 bg-white p-8 border rounded-xl shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="text-sm font-bold text-gray-800 uppercase mb-4 flex items-center gap-2"><i class="ri-pen-nib-line"></i> Área de Firma Digital del Cliente</h3>
                    
                    <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl h-64 mb-4 relative flex items-center justify-center">
                        <span class="absolute top-4 left-4 text-xs font-bold text-gray-400 uppercase tracking-widest">Área de Captura</span>
                        <div class="w-3/4 border-b-2 border-gray-300 flex justify-end pb-2">
                            <i class="ri-pencil-line text-3xl text-gray-300"></i>
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-8">
                        <button class="text-xs font-bold text-red-500 hover:text-red-700 flex items-center gap-1 transition-colors"><i class="ri-delete-bin-line"></i> Borrar y Repetir Firma</button>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" wire:model.live="aceptaTerminos" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-4 w-4">
                            <span class="text-xs font-medium text-gray-700">Cliente confirma que los datos son correctos</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                    <button wire:click="irAPaso(3)" class="text-sm font-medium text-gray-500 hover:text-gray-800 flex items-center gap-1"><i class="ri-arrow-left-line"></i> Regresar a Caja</button>
                    <button wire:click="firmarContrato" @if(!$aceptaTerminos) disabled @endif class="bg-indigo-900 text-white px-6 py-3 rounded-lg hover:bg-indigo-800 transition font-medium flex items-center gap-2 disabled:opacity-50">Guardar Firma y Enviar Contrato <i class="ri-arrow-right-line"></i></button>
                </div>
            </div>
        </div>
    @endif

    @if($paso == 5)
        <div class="bg-white p-8 border rounded-xl shadow-sm max-w-3xl mx-auto">
            <div class="flex justify-between items-center bg-gray-900 text-white px-6 py-4 rounded-t-lg -mt-8 -mx-8 mb-6">
                <h3 class="font-medium flex items-center gap-2"><i class="ri-tools-line text-orange-400"></i> Logística de Instalación</h3>
                <span class="text-xs bg-white/20 px-2 py-1 rounded">Paso Final</span>
            </div>

            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Asignar Técnico Responsable</label>
                <div class="relative">
                    <i class="ri-user-search-line absolute left-3 top-2.5 text-gray-400"></i>
                    <select wire:model.live="tecnicoAsignado" class="w-full pl-9 pr-3 py-2 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium">
                        <option value="">Seleccionar Técnico...</option>
                        <option value="Juan Pérez">Juan Pérez (Zona Centro)</option>
                        <option value="Cuadrilla 2">Cuadrilla 2 (Zona Norte)</option>
                    </select>
                </div>
            </div>

            <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-6 mb-6">
                <h4 class="text-sm font-bold text-indigo-900 uppercase mb-4 flex items-center gap-2"><i class="ri-file-list-3-line"></i> Datos Previos del Reporte de Servicio</h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                    <div>
                        <p class="text-xs font-bold text-indigo-800 uppercase mb-1">A. Número de Reporte:</p>
                        <span class="bg-white border border-blue-200 px-2 py-1 rounded font-mono text-gray-700">{{ $reporteGeneradoId }}</span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-indigo-800 uppercase mb-1">B. Fecha del Reporte:</p>
                        <p class="text-gray-700">{{ date('d de F, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-indigo-800 uppercase mb-1">C. Sucursal:</p>
                        <p class="text-gray-700">{{ $sucursal }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-indigo-800 uppercase mb-1">G. Estado del Cliente:</p>
                        <span class="bg-green-100 text-green-800 text-[10px] font-bold px-2 py-0.5 rounded-full">ACTIVO (PENDIENTE INSTALACIÓN)</span>
                    </div>
                    <div class="sm:col-span-2 border-t border-blue-100 pt-3">
                        <p class="text-xs font-bold text-indigo-800 uppercase mb-1">D. Datos del Cliente:</p>
                        <p class="text-gray-800 font-medium">{{ strtoupper($nombre . ' ' . $apellidos) }}</p>
                    </div>
                    <div class="sm:col-span-2 border-t border-blue-100 pt-3">
                        <p class="text-xs font-bold text-indigo-800 uppercase mb-1">E. Domicilio y Referencias:</p>
                        <p class="text-gray-600 italic text-sm">{{ $calle }} {{ $num_ext }}. {{ $referencias }}</p>
                    </div>
                    <div class="border-t border-blue-100 pt-3">
                        <p class="text-xs font-bold text-indigo-800 uppercase mb-1">F. Servicio Contratado:</p>
                        <p class="text-gray-800 font-medium">{{ $paquetes[$servicioSeleccionado]['nombre'] ?? '--' }}</p>
                    </div>
                    <div class="border-t border-blue-100 pt-3">
                        <p class="text-xs font-bold text-indigo-800 uppercase mb-1">I. Información de Equipo:</p>
                        <p class="text-gray-500 text-xs mt-1">Pendiente de asignación física en campo.</p>
                    </div>
                    
                    <div class="sm:col-span-2 bg-blue-100/50 p-3 rounded-lg border border-blue-200 mt-2">
                        <p class="text-xs font-bold text-indigo-900 uppercase mb-1">H. Datos del Técnico Asignado:</p>
                        <p class="text-indigo-700 font-medium flex items-center gap-2">
                            <i class="ri-shield-user-line"></i> {{ $tecnicoAsignado ?: 'Seleccione un técnico arriba para vincular...' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-3 mb-8">
                <input type="checkbox" wire:model="notificarSms" class="rounded border-gray-300 text-indigo-600 h-4 w-4 focus:ring-indigo-500">
                <span class="text-sm text-gray-700">Notificar al técnico por SMS automáticamente</span>
            </div>

            <button wire:click="finalizarProceso" @if(!$tecnicoAsignado) disabled @endif class="w-full py-3 bg-green-600 text-white font-medium rounded-lg hover:bg-green-700 transition flex items-center justify-center gap-2 disabled:opacity-50">
                <i class="ri-send-plane-fill"></i> Guardar y cerrar
            </button>
        </div>
    @endif
</div>