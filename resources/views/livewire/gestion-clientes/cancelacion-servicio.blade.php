<div class="w-full max-w-6xl mx-auto py-6 px-4 sm:px-6">
    <div class="mb-6 border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Cancelación del Servicio</h2>
        <p class="text-sm text-gray-500 font-medium">Baja definitiva y control de inventario de activos.</p>
    </div>

    @if($paso == 1)
        <div wire:key="paso-1" class="bg-white p-8 border rounded-xl shadow-sm max-w-2xl mx-auto text-center animate-in fade-in duration-500">
            <div class="w-16 h-16 bg-gray-50 text-gray-300 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="ri-user-unfollow-line text-4xl"></i>
            </div>
            <label class="block text-sm font-bold text-gray-700 mb-4 uppercase tracking-wider">Identificar Cliente para la Baja</label>
            <div class="flex flex-col sm:flex-row gap-2">
                <input type="text" wire:model="busqueda" placeholder="Ej: Nombre, ID o Teléfono..." class="flex-1 rounded-lg border-gray-300 shadow-sm focus:ring-red-500 focus:border-red-500 w-full font-medium">
                <button wire:click="buscarCliente" class="bg-gray-900 text-white px-8 py-2 rounded-lg font-bold hover:bg-black transition uppercase text-xs w-full sm:w-auto">Consultar</button>
            </div>
        </div>
    @endif

    @if($paso == 2)
        <div wire:key="paso-2" class="grid grid-cols-1 md:grid-cols-12 gap-8 animate-in slide-in-from-bottom-4 duration-500">
            <div class="md:col-span-7 space-y-6">
                <div class="bg-white p-6 border rounded-xl shadow-sm">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase mb-4 border-b pb-2 tracking-[0.2em]">Expediente del Cliente</h3>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase">Titular del Servicio:</p>
                            <p class="font-bold text-gray-800 text-lg uppercase break-words">{{ $cliente['nombre'] }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase">Servicio:</p><p class="font-bold text-indigo-600 uppercase">{{ $cliente['servicio_actual'] }}</p></div>
                            <div><p class="text-[10px] font-bold text-gray-400 uppercase">Estatus:</p><p class="font-bold text-green-600 uppercase">{{ $cliente['estado_actual'] }}</p></div>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-[10px] font-bold text-gray-400 uppercase mb-1">Saldo deudor actual:</p>
                            <p class="text-3xl font-black {{ $cliente['saldo'] > 0 ? 'text-red-600' : 'text-green-600' }} tracking-tighter">
                                ${{ number_format($cliente['saldo'], 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-5">
                @if($cliente['saldo'] > 0)
                    <div class="bg-red-50 border border-red-200 p-6 rounded-2xl text-center shadow-sm">
                        <i class="ri-error-warning-fill text-red-500 text-5xl mb-3 block"></i>
                        <p class="text-sm font-bold text-red-800 uppercase mb-2">Baja Bloqueada por Adeudo</p>
                        <p class="text-xs text-red-700 leading-relaxed">El cliente debe estar al corriente para proceder. Liquide el adeudo en el módulo de cobro.</p>
                        <a href="{{ route('pago.mensualidad') }}" class="mt-6 inline-block w-full py-3 bg-red-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-red-700 transition shadow-lg shadow-red-200">Ir a Cobro de Mensualidad</a>
                    </div>
                @else
                    <div class="bg-white p-6 border rounded-xl shadow-sm space-y-6">
                        <div>
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-2">Asignar Técnico Responsable</label>
                            <select wire:model="tecnicoAsignado" class="w-full rounded-lg border-gray-200 bg-gray-50 text-sm font-bold py-3">
                                <option value="">Seleccione Técnico...</option>
                                <option value="Roberto">Téc. Roberto Gómez</option>
                                <option value="Brigada 1">Brigada 1 (Fibra)</option>
                            </select>
                        </div>
                        <button wire:click="generarReporteBaja" @if(!$tecnicoAsignado) @endif 
                            class="w-full py-4 bg-gray-900 text-white font-black uppercase text-xs tracking-[0.2em] rounded-xl hover:bg-black transition disabled:opacity-40 disabled:cursor-not-allowed shadow-xl">
                            Generar Reporte de Baja
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($paso == 3)
        <div wire:key="paso-3" class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-in fade-in duration-700">
            
            <div class="lg:col-span-4 space-y-4">
                <div class="bg-gray-800 text-white rounded-2xl p-6 shadow-xl relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 opacity-10"><i class="ri-file-list-3-line text-9xl"></i></div>
                    <h4 class="text-[10px] font-black text-indigo-400 uppercase tracking-[0.2em] mb-4 border-b border-gray-700 pb-2">Información del Reporte</h4>
                    <div class="space-y-3 text-[11px] font-medium leading-tight">
                        <p><span class="text-gray-400 uppercase tracking-tighter">A. No. Reporte:</span> <span class="font-mono text-indigo-300 ml-1">{{ $numeroReporte }}</span></p>
                        <p><span class="text-gray-400 uppercase tracking-tighter">B. Fecha Apertura:</span> <span class="ml-1">{{ $fechaReporte }}</span></p>
                        <p><span class="text-gray-400 uppercase tracking-tighter">C. Sucursal:</span> <span class="ml-1 uppercase">{{ $cliente['sucursal'] }}</span></p>
                        <p><span class="text-gray-400 uppercase tracking-tighter">D. Cliente:</span> <span class="ml-1 uppercase font-bold text-white">{{ $cliente['nombre'] }}</span></p>
                        <p><span class="text-gray-400 uppercase tracking-tighter">E. Domicilio:</span> <span class="ml-1 italic text-gray-300">{{ $cliente['domicilio'] }}</span></p>
                        <p><span class="text-gray-400 uppercase tracking-tighter">F. Servicio:</span> <span class="ml-1 font-black text-red-500 uppercase">CANCELACIÓN</span></p>
                        <p><span class="text-gray-400 uppercase tracking-tighter">H. Técnico:</span> <span class="ml-1 uppercase text-indigo-300">{{ $tecnicoAsignado }}</span></p>
                    </div>

                    <div class="mt-6 pt-4 border-t border-gray-700 space-y-2 text-[10px]">
                        <p class="font-black text-indigo-400 uppercase">G. Datos de Red (Actuales):</p>
                        <p><span class="text-gray-400">NAP:</span> <span class="ml-1">{{ $cliente['nap'] }}</span></p>
                        <p><span class="text-gray-400 italic">{{ $cliente['direccion_nap'] }}</span></p>
                        <p><span class="text-gray-400">IP:</span> <span class="ml-1 font-mono tracking-tighter">{{ $cliente['ip_asignada'] }}</span></p>
                    </div>
                </div>

                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 font-mono">
                    <p class="text-[9px] font-black text-indigo-800 uppercase mb-2">I. Equipo Registrado Originalmente:</p>
                    <p class="text-[11px] text-indigo-900">{{ $cliente['equipo_asignado'] }}</p>
                    <p class="text-[12px] font-black text-indigo-600 mt-1 uppercase">{{ $cliente['serie_registrada'] }}</p>
                </div>
            </div>

            <div class="lg:col-span-8 bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-10 space-y-8">
                
                <section class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                    <h3 class="text-sm font-black text-gray-800 uppercase mb-4 flex items-center gap-2 italic">
                        <i class="ri-router-line text-indigo-600 text-lg"></i> Recuperación de Equipos (SI/NO)
                    </h3>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <label class="cursor-pointer group">
                            <input type="radio" wire:model.live="recuperaEquipo" value="si" class="peer sr-only">
                            <div class="p-4 text-center border-2 rounded-xl peer-checked:border-indigo-600 peer-checked:bg-white font-bold text-xs uppercase text-gray-400 peer-checked:text-indigo-900 transition-all shadow-sm">SÍ, RECUPERADO</div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" wire:model.live="recuperaEquipo" value="no" class="peer sr-only">
                            <div class="p-4 text-center border-2 rounded-xl peer-checked:border-red-600 peer-checked:bg-white font-bold text-xs uppercase text-gray-400 peer-checked:text-red-900 transition-all shadow-sm">NO RECUPERADO</div>
                        </label>
                    </div>

                    @if($recuperaEquipo == 'si')
                        <div class="space-y-3 animate-in fade-in">
                            <label class="block text-[10px] font-black text-indigo-700 uppercase tracking-widest">Confirmar Serie Recuperada (Validar en Sistema)</label>
                            <input type="text" wire:model="serieConfirmada" class="w-full rounded-xl border-gray-200 bg-white font-mono text-sm uppercase p-3" placeholder="Escanee o escriba la serie del equipo físico...">
                            <p class="text-[9px] text-indigo-400 italic">Si la serie es diferente a la original, el sistema la re-vinculará antes de marcarla como recuperada.</p>
                        </div>
                    @else
                        <div class="p-4 bg-red-100 rounded-xl border border-red-200 animate-in fade-in">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model.live="pagoPerdida" class="h-5 w-5 text-red-600 rounded mt-1">
                                <div class="text-xs text-red-800 leading-relaxed">
                                    <span class="block font-black uppercase mb-1">Registrar como: EQUIPO PAGADO POR PÉRDIDA</span>
                                    Al marcar esta opción, se debe registrar el cobro en el módulo de ingresos y se liberará el activo del inventario del cliente.
                                </div>
                            </label>
                        </div>
                    @endif
                </section>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 border-t pt-8">
                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b pb-1">Validación Sucursal (Software)</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 text-xs font-bold text-gray-700 cursor-pointer">
                                <input type="checkbox" wire:model="bajaWinboxNombre" class="rounded text-indigo-600 w-5 h-5"> Baja Nombre en Winbox
                            </label>
                            <label class="flex items-center gap-3 text-xs font-bold text-gray-700 cursor-pointer">
                                <input type="checkbox" wire:model="bajaWinboxPlan" class="rounded text-indigo-600 w-5 h-5"> Baja Plan Datos en Winbox
                            </label>
                            <label class="flex items-center gap-3 text-xs font-bold text-gray-700 cursor-pointer">
                                <input type="checkbox" wire:model="bajaOLT" class="rounded text-indigo-600 w-5 h-5"> Baja de Datos en OLT
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest border-b pb-1">Validación Técnico (Físico)</h4>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 text-xs font-bold text-red-600 uppercase cursor-pointer">
                                <input type="checkbox" wire:model="desconexionFisica" class="rounded text-red-600 w-5 h-5"> Confirmar Desconexión en NAP
                            </label>
                            <div>
                                <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Puerto NAP Liberado (Ej. Salida 4)</label>
                                <input type="text" class="w-full rounded-lg border-gray-300 bg-gray-50 text-xs py-2 font-bold" placeholder="Escriba la salida que quedó disponible">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-t pt-8 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase mb-1">Calificación del Usuario (Default: Excelente)</label>
                        <select wire:model="calificacion" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-bold">
                            <option value="Excelente">Excelente</option>
                            <option value="Bueno">Bueno</option>
                            <option value="Malo">Malo</option>
                        </select>
                    </div>
                    <div class="flex flex-col justify-end">
                        <p class="text-[9px] text-gray-400 font-bold text-center mb-1 uppercase tracking-widest">Horas Transcurridas: 0.5 Hrs (Auto)</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-4">
                    <button class="w-full sm:flex-1 py-4 bg-white border-2 border-gray-200 text-gray-600 font-black rounded-2xl text-[10px] uppercase hover:bg-gray-50 transition shadow-sm tracking-widest">
                        Guardar Precierre (Pendiente)
                    </button>
                    <button wire:click="finalizarCancelacion" class="w-full sm:flex-1 py-4 bg-red-600 text-white font-black rounded-2xl text-[10px] uppercase shadow-xl hover:bg-red-700 transition transform active:scale-95 tracking-widest">
                        Cierre Total y Liberación NAP
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>