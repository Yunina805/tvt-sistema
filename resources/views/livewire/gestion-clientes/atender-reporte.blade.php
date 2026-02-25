<div class="max-w-7xl mx-auto py-6">
    
    <div class="mb-6 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-wider text-gray-500 mb-1">
                <a href="{{ route('reportes.servicio') }}" class="hover:text-indigo-600 transition-colors"><i class="ri-arrow-left-line"></i> Bandeja de Reportes</a>
                <i class="ri-arrow-right-s-line text-gray-300"></i>
                <span>Atención Técnica</span>
            </div>
            <h2 class="text-2xl font-semibold text-gray-800">Reporte {{ $reporte['folio'] }}</h2>
        </div>
        <div>
            <span class="bg-blue-100 text-blue-800 text-xs font-bold px-3 py-1 rounded-full uppercase">{{ $reporte['falla_reportada'] }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 shadow-sm text-sm">
                <h3 class="font-bold text-gray-700 uppercase mb-4 border-b border-gray-200 pb-2"><i class="ri-information-line mr-1"></i> Datos Previos</h3>
                
                <div class="space-y-3 text-gray-600">
                    <div><span class="font-bold text-gray-800">Fecha:</span> {{ $reporte['fecha'] }}</div>
                    <div><span class="font-bold text-gray-800">Sucursal:</span> {{ $reporte['sucursal'] }}</div>
                    <div><span class="font-bold text-gray-800">Cliente:</span> {{ $reporte['cliente'] }}</div>
                    <div><span class="font-bold text-gray-800">Domicilio:</span> <span class="italic">{{ $reporte['domicilio'] }}</span></div>
                    <div><span class="font-bold text-gray-800">Servicio:</span> {{ $reporte['servicio'] }}</div>
                    <div><span class="font-bold text-gray-800">Estado Cliente:</span> <span class="bg-green-100 text-green-700 px-1.5 rounded text-xs font-bold">{{ $reporte['estado_cliente'] }}</span></div>
                    <div><span class="font-bold text-gray-800">Quien Reportó:</span> {{ $reporte['quien_reporto'] }}</div>
                    <div><span class="font-bold text-gray-800">Técnico Asignado:</span> <i class="ri-shield-user-line text-indigo-500"></i> {{ $reporte['tecnico'] }}</div>
                </div>

                <h3 class="font-bold text-gray-700 uppercase mt-6 mb-3 border-b border-gray-200 pb-2"><i class="ri-router-line mr-1"></i> Red y Equipo</h3>
                <div class="space-y-3 text-gray-600">
                    <div><span class="font-bold text-gray-800">NAP:</span> {{ $reporte['nap'] }}</div>
                    <div><span class="font-bold text-gray-800">Dir. NAP:</span> {{ $reporte['dir_nap'] }}</div>
                    <div class="bg-white p-2 border border-gray-200 rounded mt-2">
                        <span class="font-bold text-indigo-700 block text-xs">EQUIPO ASIGNADO:</span>
                        <span class="font-mono text-xs">{{ $reporte['info_equipo'] }}</span>
                    </div>
                    
                    @if(in_array($reporte['tipo_servicio'], ['INTERNET', 'TV+INTERNET']))
                        <div class="grid grid-cols-2 gap-2 text-xs bg-gray-100 p-2 rounded">
                            <div><span class="font-bold">IP:</span> {{ $reporte['ip'] }}</div>
                            <div><span class="font-bold">WIFI:</span> {{ $reporte['wifi'] }}</div>
                            <div><span class="font-bold">OLT:</span> {{ $reporte['olt'] }}</div>
                            <div><span class="font-bold">PON:</span> {{ $reporte['pon'] }}</div>
                        </div>
                    @endif

                    <div class="text-xs space-y-1 mt-3">
                        <p><span class="font-bold text-gray-800">Última Potencia NAP:</span> <span class="text-red-500 font-bold">{{ $reporte['ultima_potencia_nap'] }}</span></p>
                        <p><span class="font-bold text-gray-800">Última Potencia Equipo:</span> <span class="text-red-500 font-bold">{{ $reporte['ultima_potencia_equipo'] }}</span></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-8">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                
                <div class="mb-8 border-b border-gray-200 pb-6">
                    <label class="flex items-center space-x-3 cursor-pointer mb-4">
                        <input type="checkbox" wire:model.live="cambioEquipo" class="rounded border-gray-300 text-indigo-600 h-5 w-5 focus:ring-indigo-500">
                        <span class="font-bold text-gray-800">¿Requiere Cambio de Equipo? (Mininodo / ONU)</span>
                    </label>

                    @if($cambioEquipo)
                        <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-indigo-900 uppercase mb-1">Nuevo Equipo del Almacén</label>
                                <select wire:model="equipoNuevo" class="w-full rounded-md border-gray-300 text-sm">
                                    <option value="">Seleccionar Equipo...</option>
                                    <option value="1">ONU Huawei (Serie Nueva)</option>
                                    <option value="2">Mininodo Arris (Serie Nueva)</option>
                                </select>
                            </div>
                            @if(in_array($reporte['tipo_servicio'], ['INTERNET', 'TV+INTERNET']))
                                <div><label class="block text-xs font-bold text-indigo-900 uppercase mb-1">Nombre WIFI</label><input type="text" class="w-full rounded-md border-gray-300 text-sm"></div>
                                <div><label class="block text-xs font-bold text-indigo-900 uppercase mb-1">Contraseña</label><input type="text" class="w-full rounded-md border-gray-300 text-sm"></div>
                            @endif
                            <div class="md:col-span-2 text-xs text-indigo-700 mt-1">
                                <i class="ri-information-line"></i> Al guardar, se generará comodato, se actualizará inventario y se notificará por SMS al responsable.
                            </div>
                        </div>
                    @endif
                </div>

                <h3 class="text-sm font-bold text-gray-700 uppercase mb-4">Lecturas Técnicas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    @if($reporte['tipo_servicio'] === 'CAMBIO_DOMICILIO')
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Metros de Acometida</label>
                            <input type="number" wire:model="metrosAcometida" class="w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="Ej. 120">
                        </div>
                    @endif
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Potencia Óptica (Salida NAP) *</label>
                        <input type="text" wire:model="potenciaNap" class="w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="dBm">
                        @error('potenciaNap') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Potencia Óptica (Antes del Equipo) *</label>
                        <input type="text" wire:model="potenciaEquipo" class="w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="dBm">
                        @error('potenciaEquipo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                </div>

                <h3 class="text-sm font-bold text-gray-700 uppercase mb-4 border-t border-gray-200 pt-6">Checklist de Verificación</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-3 gap-x-6 mb-8 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    
                    @if(in_array($reporte['tipo_servicio'], ['TV', 'TV+INTERNET', 'CAMBIO_DOMICILIO']))
                        <label class="flex items-center space-x-2">
                            <input type="checkbox" wire:model="pruebaCanales" class="rounded border-gray-300 text-indigo-600">
                            <span class="text-sm text-gray-700">Prueba de Canales Exitosa</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            <label class="text-sm text-gray-700 w-full">Cantidad de Canales Digitales:</label>
                            <input type="number" wire:model="cantidadCanales" class="w-20 py-1 px-2 text-sm rounded-md border-gray-300">
                        </div>
                    @endif

                    @if(in_array($reporte['tipo_servicio'], ['INTERNET', 'TV+INTERNET', 'CAMBIO_DOMICILIO']))
                        <label class="flex items-center space-x-2"><input type="checkbox" wire:model="ledsVerdes" class="rounded border-gray-300 text-indigo-600"><span class="text-sm text-gray-700">LEDs en color Verde / PON Encendido</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" wire:model="detectoWifiOriginal" class="rounded border-gray-300 text-indigo-600"><span class="text-sm text-gray-700">Detectó Red WIFI Original</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" wire:model="configuroWifiDefault" class="rounded border-gray-300 text-indigo-600"><span class="text-sm text-gray-700">Configuró WIFI por Default</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" wire:model="accesoInternet" class="rounded border-gray-300 text-indigo-600"><span class="text-sm text-gray-700">Confirmar acceso a Internet</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" wire:model="confirmoOlt" class="rounded border-gray-300 text-indigo-600"><span class="text-sm text-gray-700">Confirmar OLT asignada</span></label>
                        <label class="flex items-center space-x-2"><input type="checkbox" wire:model="confirmoPon" class="rounded border-gray-300 text-indigo-600"><span class="text-sm text-gray-700">Confirmar PON asignada</span></label>
                        
                        <div class="col-span-1 md:col-span-2 grid grid-cols-2 gap-4 mt-2">
                            <div><label class="block text-xs text-gray-500">Asignó Nueva Pass (Opcional):</label><input type="text" wire:model="asignoNuevaPass" class="w-full py-1 text-sm rounded border-gray-300"></div>
                            <div><label class="block text-xs text-gray-500">Velocidad Registrada (Megas):</label><input type="text" wire:model="velocidadRegistrada" class="w-full py-1 text-sm rounded border-gray-300"></div>
                        </div>
                    @endif
                </div>

                <h3 class="text-sm font-bold text-gray-700 uppercase mb-4 border-t border-gray-200 pt-6">Solución y Cierre</h3>
                <div class="space-y-4 mb-8">
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Resolución del Problema *</label>
                        <select wire:model="solucionOpcion" class="w-full rounded-md border-gray-300 shadow-sm text-sm">
                            <option value="">Seleccione una opción...</option>
                            <option value="Conector Dañado">Cambio de Conector Dañado</option>
                            <option value="Fibra Rota">Reparación de Fibra Rota</option>
                            <option value="Cambio de Equipo">Cambio de Equipo (Daño Eléctrico)</option>
                            <option value="Reconfiguracion">Reconfiguración de Router/ONU</option>
                            <option value="Instalacion">Instalación Exitosa</option>
                        </select>
                        @error('solucionOpcion') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Notas del Técnico</label>
                        <textarea wire:model="descripcionSolucion" rows="2" class="w-full rounded-md border-gray-300 shadow-sm text-sm" placeholder="Detalles adicionales..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-600 uppercase mb-1">Calificación del Usuario (Por defecto: Excelente)</label>
                        <select wire:model="calificacion" class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm text-sm">
                            <option value="Excelente">Excelente</option>
                            <option value="Bueno">Bueno</option>
                            <option value="Malo">Malo</option>
                        </select>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-4 pt-6 border-t border-gray-200">
                    <button wire:click="guardarPrecierre" class="px-6 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        Guardar Avance (Precierre)
                    </button>
                    <button wire:click="cerrarReporte" class="px-8 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors shadow-sm flex items-center justify-center gap-2">
                        <i class="ri-check-double-line"></i> Cierre Total del Reporte
                    </button>
                </div>

            </div>
        </div>
    </div>

    @if($reporte['falla_reportada'] == 'SUSPENSION FALTA DE PAGO')
    <div class="bg-red-50 p-6 rounded-xl border border-red-100 mb-6">
        <h4 class="text-sm font-bold text-red-800 uppercase mb-4 flex items-center gap-2">
            <i class="ri-indeterminate-circle-line"></i> Ejecución de Suspensión
        </h4>

        <div class="grid grid-cols-1 gap-4">
            @if(in_array($reporte['tipo_servicio'], ['INTERNET', 'TV+INTERNET']))
                <label class="flex items-center gap-3 p-3 bg-white border border-red-200 rounded-lg cursor-pointer">
                    <input type="checkbox" wire:model="confirmacionWinbox" class="h-5 w-5 text-red-600 rounded">
                    <span class="text-sm font-medium text-gray-700 uppercase">Confirmar Desconexión Lógica en Winbox</span>
                </label>
                <label class="flex items-center gap-3 p-3 bg-white border border-red-200 rounded-lg cursor-pointer">
                    <input type="checkbox" wire:model="confirmacionOLT" class="h-5 w-5 text-red-600 rounded">
                    <span class="text-sm font-medium text-gray-700 uppercase">Confirmar Desconexión Lógica en OLT</span>
                </label>
            @endif

            <label class="flex items-center gap-3 p-3 bg-white border border-red-200 rounded-lg cursor-pointer">
                <input type="checkbox" wire:model="confirmacionDesconexionFisica" class="h-5 w-5 text-red-600 rounded">
                <span class="text-sm font-medium text-gray-700 uppercase">Confirmar Desconexión Física en NAP</span>
            </label>
            
            <div>
                <label class="block text-[10px] font-bold text-gray-500 uppercase mb-1">Puerto de NAP liberado</label>
                <input type="text" wire:model="salidaNapLibre" class="w-full rounded-lg border-red-100 text-sm" placeholder="Ej. Salida 4">
            </div>
        </div>

        <button wire:click="cerrarSuspension" class="mt-6 w-full py-3 bg-red-600 text-white font-bold rounded-xl hover:bg-red-700 transition shadow-lg">
            Finalizar Suspensión del Servicio
        </button>
    </div>
@endif
</div>