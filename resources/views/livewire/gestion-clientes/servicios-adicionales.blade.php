<div class="max-w-6xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between border-b border-gray-200 pb-4">
        <h2 class="text-2xl font-semibold text-gray-800 mb-4 md:mb-0">Contratación de Servicios Adicionales</h2>
        <div class="flex space-x-2 text-sm font-medium overflow-x-auto pb-2 md:pb-0 text-gray-400">
            <span class="{{ $paso >= 1 ? 'text-indigo-600 font-bold' : '' }}">1. Selección</span>
            <span>/</span>
            <span class="{{ $paso >= 2 ? 'text-indigo-600 font-bold' : '' }}">2. Cliente</span>
            <span>/</span>
            <span class="{{ $paso >= 3 ? 'text-indigo-600 font-bold' : '' }}">3. Caja</span>
            <span>/</span>
            <span class="{{ $paso >= 4 ? 'text-indigo-600 font-bold' : '' }}">4. Técnico</span>
            <span>/</span>
            <span class="{{ $paso >= 5 ? 'text-green-600 font-bold' : '' }}">5. Éxito</span>
        </div>
    </div>

    @if($paso == 1)
        <div class="bg-white p-6 border rounded-xl shadow-sm mb-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6 border-b pb-2">Servicios Disponibles</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($serviciosAdicionales as $key => $s)
                    <div class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-md transition-shadow">
                        <h4 class="text-xl font-bold text-gray-800 mb-4">{{ $s['nombre'] }}</h4>
                        <div class="space-y-2 mb-6 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tarifa Instalación:</span>
                                <span class="font-bold text-gray-900">${{ number_format($s['instalacion'], 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Tarifa Mensualidad:</span>
                                <span class="font-bold text-indigo-600">${{ number_format($s['mensualidad'], 2) }}</span>
                            </div>
                        </div>
                        <button wire:click="seleccionarServicio('{{ $key }}')" class="w-full bg-white border border-indigo-600 text-indigo-600 py-2 rounded-lg font-bold hover:bg-indigo-50 transition">
                            Seleccionar Servicio
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    @if($paso == 2)
        <div class="bg-white p-8 border rounded-xl shadow-sm max-w-2xl mx-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Buscar Cliente</h3>
            <div class="flex gap-2 mb-6">
                <input type="text" wire:model="busquedaCliente" placeholder="Nombre, Teléfono o ID..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                <button wire:click="buscarCliente" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">Buscar</button>
            </div>

            @if($clienteEncontrado)
                <div class="bg-indigo-50 p-6 rounded-lg border border-indigo-100">
                    <p class="text-xs text-indigo-700 font-bold uppercase mb-2 italic">Cliente Encontrado</p>
                    <p class="font-bold text-gray-900 text-lg">{{ $clienteEncontrado['nombre'] }}</p>
                    <p class="text-sm text-gray-600">Servicio Actual: {{ $clienteEncontrado['servicio_actual'] }}</p>
                    <p class="text-sm text-gray-600 mt-2"><i class="ri-map-pin-line"></i> {{ $clienteEncontrado['domicilio'] }}</p>
                    
                    <button wire:click="irAPaso(3)" class="mt-6 w-full bg-indigo-600 text-white py-2 rounded-lg font-bold hover:bg-indigo-700 transition">
                        Confirmar Cliente y Continuar
                    </button>
                </div>
            @endif
        </div>
    @endif

    @if($paso == 3)
        <div class="max-w-xl mx-auto bg-white p-8 border rounded-xl shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-6 border-b pb-4">Proceso de Cobro</h3>
            <div class="space-y-4 mb-8 text-sm">
                <div class="flex justify-between items-center text-gray-600">
                    <span>Instalación ({{ $servicioSeleccionado['nombre'] }})</span>
                    <span class="font-bold text-gray-900">${{ number_format($servicioSeleccionado['instalacion'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-gray-600">
                    <span>Mensualidad Adicional</span>
                    <span class="font-bold text-gray-900">${{ number_format($servicioSeleccionado['mensualidad'], 2) }}</span>
                </div>
                <div class="flex justify-between items-center pt-4 border-t text-lg font-bold text-indigo-700">
                    <span>Total a Ingresar</span>
                    <span>${{ number_format($servicioSeleccionado['instalacion'] + $servicioSeleccionado['mensualidad'], 2) }}</span>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg mb-6">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" wire:model.live="confirmacionCaja" class="mt-1 h-5 w-5 text-green-600 rounded border-gray-300">
                    <span class="text-sm text-gray-800">
                        <strong>Confirmar Cobro en Caja</strong><br>
                        Al confirmar, se afectará el historial del cliente y los movimientos de sucursal.
                    </span>
                </label>
            </div>

            <button wire:click="confirmarCobro" @if(!$confirmacionCaja) disabled @endif class="w-full bg-green-600 text-white py-3 rounded-lg font-bold hover:bg-green-700 transition disabled:opacity-50">
                Confirmar Pago y Registrar Servicio
            </button>
        </div>
    @endif

    @if($paso == 4)
        <div class="max-w-2xl mx-auto bg-white p-8 border rounded-xl shadow-sm">
            <h3 class="text-lg font-medium text-gray-900 mb-6 border-b pb-4">Asignación de Técnico</h3>
            
            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-2">Técnico que realizará el trabajo</label>
                <select wire:model="tecnicoAsignado" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    <option value="">Seleccione Técnico...</option>
                    <option value="Roberto">Téc. Roberto Gómez</option>
                    <option value="Cuadrilla_A">Cuadrilla A (Fibra)</option>
                </select>
                <div class="mt-4 flex items-center gap-3">
                    <input type="checkbox" wire:model="notificarSms" class="h-4 w-4 text-indigo-600 border-gray-300 rounded shadow-sm">
                    <span class="text-xs text-gray-500 uppercase font-bold tracking-tight">Notificar al técnico por SMS automáticamente</span>
                </div>
            </div>

            <button wire:click="generarReporte" @if(!$tecnicoAsignado) disabled @endif class="w-full bg-indigo-600 text-white py-3 rounded-lg font-bold hover:bg-indigo-700 transition disabled:opacity-50">
                Generar Reporte de Servicio Automático
            </button>
        </div>
    @endif

    @if($paso == 5)
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-8">
                <div class="h-16 w-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4 italic font-bold">OK</div>
                <h2 class="text-2xl font-bold text-gray-900">Servicio Adicional Registrado</h2>
                <p class="text-gray-500 text-sm mt-1 uppercase tracking-tighter">Reporte generado exitosamente</p>
            </div>

            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex justify-between items-center font-bold text-gray-700 uppercase text-xs">
                    <span>Datos del Reporte Generado</span>
                    <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded">REQ-{{ rand(1000, 9999) }}</span>
                </div>
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                    <div><span class="font-bold">Sucursal:</span> {{ $clienteEncontrado['sucursal'] }}</div>
                    <div><span class="font-bold">Fecha:</span> {{ date('d/m/Y H:i') }}</div>
                    <div class="md:col-span-2 border-t pt-2"><span class="font-bold">Cliente:</span> {{ $clienteEncontrado['nombre'] }}</div>
                    <div class="md:col-span-2"><span class="font-bold">Servicio Contratado:</span> <span class="bg-blue-50 text-indigo-700 px-2 py-0.5 rounded font-bold uppercase">{{ $servicioSeleccionado['nombre'] }}</span></div>
                    <div><span class="font-bold">Estado:</span> <span class="text-yellow-600 font-bold uppercase">Pendiente de Instalación</span></div>
                    <div><span class="font-bold">Técnico Asignado:</span> {{ $tecnicoAsignado }}</div>
                </div>
            </div>

            <div class="mt-8 flex justify-center">
                <button wire:click="finalizar" class="bg-gray-800 text-white px-8 py-3 rounded-lg hover:bg-black transition font-bold uppercase text-xs tracking-widest">
                    Ir a Bandeja de Reportes
                </button>
            </div>
        </div>
    @endif
</div>