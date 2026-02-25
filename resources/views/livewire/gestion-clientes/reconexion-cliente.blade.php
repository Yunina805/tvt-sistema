<div class="max-w-6xl mx-auto">
    <div class="mb-8 flex justify-between items-center border-b pb-4">
        <h2 class="text-2xl font-semibold text-gray-800">Reconexión de Cliente</h2>
        <div class="flex space-x-2 text-xs font-bold uppercase text-gray-400">
            <span class="{{ $paso == 1 ? 'text-indigo-600' : '' }}">1. Tipo</span>
            <span>/</span>
            <span class="{{ $paso == 2 ? 'text-indigo-600' : '' }}">2. Búsqueda</span>
            <span>/</span>
            <span class="{{ $paso == 3 ? 'text-indigo-600' : '' }}">3. Saldos</span>
            <span>/</span>
            <span class="{{ $paso >= 4 ? 'text-indigo-600' : '' }}">4. Cierre</span>
        </div>
    </div>

    @if($paso == 1)
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <button wire:click="seleccionarTipo('mismo')" class="p-8 bg-white border-2 border-gray-200 rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition group text-center">
                <i class="ri-refresh-line text-4xl text-gray-400 group-hover:text-indigo-600 mb-4 block"></i>
                <span class="text-lg font-bold text-gray-700 uppercase">Reconexión con mismo Servicio</span>
            </button>
            <button wire:click="seleccionarTipo('otro')" class="p-8 bg-white border-2 border-gray-200 rounded-2xl hover:border-orange-500 hover:bg-orange-50 transition group text-center">
                <i class="ri-arrow-left-right-line text-4xl text-gray-400 group-hover:text-orange-600 mb-4 block"></i>
                <span class="text-lg font-bold text-gray-700 uppercase">Reconexión con Otro Servicio</span>
            </button>
        </div>
    @endif

    @if($paso == 2)
        <div class="bg-white p-8 border rounded-xl shadow-sm max-w-2xl mx-auto">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Identificar Cliente</h3>
            <div class="flex gap-2">
                <input type="text" wire:model="busqueda" placeholder="Nombre o # de Cliente..." class="flex-1 rounded-md border-gray-300">
                <button wire:click="buscarCliente" class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-bold">Buscar</button>
            </div>
            <button wire:click="irAPaso(1)" class="mt-4 text-sm text-gray-500 font-bold hover:underline">← Regresar</button>
        </div>
    @endif

    @if($paso == 3)
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-7 space-y-6">
                <div class="bg-white p-6 border rounded-xl shadow-sm">
                    <h3 class="text-sm font-bold text-gray-500 uppercase mb-4 border-b pb-2">Expediente de Suspensión</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div class="col-span-2"><p class="text-xs text-gray-400 uppercase font-bold">Cliente:</p><p class="font-bold text-gray-900">{{ $cliente['nombre'] }}</p></div>
                        <div><p class="text-xs text-gray-400 font-bold">Último Servicio:</p><p>{{ $cliente['ultimo_servicio'] }}</p></div>
                        <div><p class="text-xs text-gray-400 font-bold">Fecha Suspensión:</p><p class="text-red-600 font-bold">{{ $cliente['fecha_suspension'] }}</p></div>
                        <div class="col-span-2 border-t pt-2"><p class="text-xs text-gray-400 font-bold uppercase">Equipo Anterior:</p><p class="font-mono">{{ $cliente['equipo'] }}</p></div>
                    </div>
                </div>

                <div class="bg-white p-6 border rounded-xl shadow-sm">
                    <h3 class="text-sm font-bold text-gray-500 uppercase mb-3 italic">Últimos Pagos Registrados</h3>
                    <table class="w-full text-xs">
                        <tr class="text-left text-gray-400 uppercase"><th>Fecha</th><th>Importe</th><th>Concepto</th></tr>
                        @foreach($cliente['pagos'] as $pago)
                            <tr class="border-t">
                                <td class="py-2">{{ $pago['fecha'] }}</td>
                                <td class="py-2 font-bold">${{ $pago['monto'] }}</td>
                                <td class="py-2">{{ $pago['concepto'] }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="lg:col-span-5 space-y-4">
                <div class="bg-red-50 border border-red-100 p-6 rounded-xl shadow-sm">
                    <h3 class="text-red-800 font-black text-center uppercase mb-4">Saldo Pendiente: ${{ number_format($cliente['saldo_pendiente'], 2) }}</h3>
                    
                    @if(!$adeudoPagado)
                        <div class="space-y-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model.live="aplicarDescuento" class="rounded text-red-600">
                                <span class="text-xs font-bold text-red-700 uppercase tracking-tighter">Aplicar Descuento Autorizado</span>
                            </label>

                            @if($aplicarDescuento)
                                <div class="p-3 bg-white border border-red-200 rounded-lg space-y-2">
                                    <input type="number" wire:model="montoDescuento" placeholder="Monto a descontar" class="w-full text-sm rounded-md border-red-100">
                                    <input type="password" wire:model="passwordAuth" placeholder="Contraseña Admin" class="w-full text-sm rounded-md border-red-100">
                                    @error('passwordAuth') <span class="text-[10px] text-red-500 font-bold uppercase">{{ $message }}</span> @enderror
                                </div>
                            @endif

                            <button wire:click="procesarPagoAdeudo" class="w-full py-3 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700 transition shadow-md">Pagar Saldo Pendiente</button>
                        </div>
                    @else
                        <div class="bg-white p-4 rounded-lg border border-green-200 text-center">
                            <i class="ri-checkbox-circle-fill text-green-500 text-2xl"></i>
                            <p class="text-xs font-bold text-green-700 uppercase">Adeudo Liquidado correctamente</p>
                        </div>
                        <button wire:click="irAPaso(4)" class="w-full mt-4 py-3 bg-indigo-600 text-white font-bold rounded-lg uppercase">Siguiente: Cobro Días de Uso →</button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($paso == 4)
        <div class="max-w-3xl mx-auto space-y-6">
            @if($tipoReconexion == 'otro')
                <div class="bg-white p-6 border rounded-xl shadow-sm">
                    <h3 class="text-lg font-medium text-gray-900 mb-4 uppercase text-center border-b pb-2 tracking-tighter">Selección de Nueva Tarifa</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($paquetes as $key => $p)
                            <div wire:click="$set('servicioSeleccionado', '{{ $key }}')" class="cursor-pointer border-2 p-4 rounded-xl text-center {{ $servicioSeleccionado == $key ? 'border-orange-500 bg-orange-50' : '' }}">
                                <p class="font-bold uppercase text-xs">{{ $p['nombre'] }}</p>
                                <p class="text-xl font-black text-gray-900">${{ $p['mensualidad'] }}</p>
                                <p class="text-[10px] text-gray-500">Instalación: ${{ $p['instalacion'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="bg-white p-8 border rounded-xl shadow-sm">
                <h3 class="text-lg font-medium text-indigo-900 mb-6 text-center border-b pb-4 uppercase italic">Liquidación de Reconexión</h3>
                <div class="space-y-4 mb-8">
                    @if($tipoReconexion == 'otro' && $servicioSeleccionado)
                        <div class="flex justify-between text-sm"><span>Nueva Instalación</span><span class="font-bold">${{ number_format($paquetes[$servicioSeleccionado]['instalacion'], 2) }}</span></div>
                    @endif
                    <div class="flex justify-between text-sm">
                        <div><span>Días de Uso Proporcionales</span><p class="text-[10px] text-gray-400">{{ $diasUso }} días hasta el día 30</p></div>
                        <span class="font-bold">${{ number_format($costoProrrateo, 2) }}</span>
                    </div>
                    <div class="pt-4 border-t flex justify-between items-center text-xl font-black text-indigo-900">
                        <span>TOTAL A PAGAR</span>
                        <span>${{ number_format(($tipoReconexion == 'otro' && $servicioSeleccionado ? $paquetes[$servicioSeleccionado]['instalacion'] : 0) + $costoProrrateo, 2) }}</span>
                    </div>
                </div>

                <div class="flex gap-4">
                    <button wire:click="irAPaso(3)" class="px-6 py-2 bg-gray-100 text-gray-600 font-bold rounded-lg uppercase text-xs">Atrás</button>
                    <button wire:click="irAPaso(5)" class="flex-1 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition shadow-lg uppercase text-xs tracking-widest">Confirmar Ingreso en Caja</button>
                </div>
            </div>
        </div>
    @endif

    @if($paso == 5)
        <div class="bg-white p-8 border rounded-xl shadow-sm max-w-3xl mx-auto">
            <h3 class="text-lg font-bold text-gray-900 mb-6 border-b pb-2 uppercase tracking-tighter">Asignación y Comodato</h3>
            
            <div class="mb-8">
                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Técnico para Reconexión Física</label>
                <select wire:model="tecnicoAsignado" class="w-full rounded-md border-gray-300 shadow-sm text-sm uppercase font-bold">
                    <option value="">Seleccionar Técnico...</option>
                    <option value="Roberto">Téc. Roberto Gómez</option>
                </select>
                <p class="text-[10px] text-indigo-500 mt-2 font-bold uppercase tracking-widest italic">Se enviará SMS automático al técnico al guardar [cite: 1138]</p>
            </div>

            <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl h-48 mb-6 flex flex-col items-center justify-center text-gray-400">
                <i class="ri-pen-nib-line text-4xl mb-2"></i>
                <span class="text-xs font-bold uppercase tracking-widest">Nueva Firma de Comodato Digital [cite: 1051]</span>
            </div>

            <label class="flex items-center gap-3 cursor-pointer mb-8">
                <input type="checkbox" wire:model.live="aceptaTerminos" class="h-5 w-5 rounded text-indigo-600">
                <span class="text-xs font-bold text-gray-700 uppercase">Cliente confirma que mantiene el equipo original y acepta términos</span>
            </label>

            <button wire:click="finalizar" @if(!$tecnicoAsignado || !$aceptaTerminos) disabled @endif class="w-full py-4 bg-indigo-900 text-white font-black rounded-xl hover:bg-black transition shadow-xl uppercase tracking-widest disabled:opacity-50">Generar Reporte y Finalizar</button>
        </div>
    @endif
</div>