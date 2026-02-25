<div class="w-full max-w-6xl mx-auto py-6 px-4">
    <div class="mb-6 border-b border-gray-200 pb-4">
        <h2 class="text-xl font-bold text-gray-800 uppercase tracking-tight">Recuperación de Equipos (> 61 Días)</h2>
        <p class="text-sm text-gray-500 font-medium">Gestión de cobro de activos y cancelación física por falta de pago.</p>
    </div>

    @if (session()->has('mensaje'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg border border-green-200 font-bold uppercase text-xs">
            {{ session('mensaje') }}
        </div>
    @endif

    @if($paso == 1)
        <div wire:key="paso-1" class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden animate-in fade-in duration-500">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-6 py-4 text-left"># Cliente</th>
                            <th class="px-6 py-4 text-left">Titular / Sucursal</th>
                            <th class="px-6 py-4 text-left">Saldo Actual</th>
                            <th class="px-6 py-4 text-left">Servicio / Estatus</th>
                            <th class="px-6 py-4 text-left">Último Pago</th>
                            <th class="px-6 py-4 text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm">
                        @foreach($clientes as $cliente)
                            <tr class="hover:bg-red-50/20 transition-colors">
                                <td class="px-6 py-4 font-mono font-bold text-indigo-600 tracking-tighter">{{ $cliente['id'] }}</td>
                                <td class="px-6 py-4 uppercase font-bold text-gray-800">
                                    {{ $cliente['nombre'] }}
                                    <span class="block text-[10px] font-normal text-gray-400 italic">{{ $cliente['sucursal'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-red-600 font-black">${{ number_format($cliente['saldo'], 2) }}</td>
                                <td class="px-6 py-4">
                                    <p class="font-medium text-gray-700 uppercase text-xs">{{ $cliente['servicio'] }}</p>
                                    <span class="px-2 py-0.5 rounded-full bg-red-100 text-red-700 text-[9px] font-black uppercase">{{ $cliente['estatus'] }}</span>
                                </td>
                                <td class="px-6 py-4 text-gray-500">{{ $cliente['ultimo_pago'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <button wire:click="seleccionarCliente({{ json_encode($cliente) }})" 
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-lg hover:bg-red-700 transition transform active:scale-95">
                                        Generar Reporte
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if($paso == 2)
        <div wire:key="paso-2" class="grid grid-cols-1 md:grid-cols-12 gap-8 animate-in slide-in-from-right-4 duration-500">
            
            <div class="md:col-span-4 space-y-4">
                <div class="bg-gray-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 opacity-10 text-9xl italic font-black">REC</div>
                    <h4 class="text-[10px] font-black text-red-400 uppercase tracking-[0.2em] mb-4 border-b border-gray-800 pb-2 italic">Datos Previos del Reporte</h4>
                    
                    <div class="space-y-4 text-[11px] leading-tight font-medium">
                        <div class="flex justify-between border-b border-gray-800 pb-2">
                            <span class="text-gray-500 uppercase tracking-tighter">A. No. Reporte:</span>
                            <span class="font-mono text-indigo-400 font-bold uppercase">{{ $folioReporte }}</span>
                        </div>
                        <p><span class="text-gray-500 uppercase tracking-tighter">B. Fecha Reporte:</span><br><span class="text-white">{{ date('d de F, Y') }}</span></p>
                        <p><span class="text-gray-500 uppercase tracking-tighter">D. Cliente:</span><br><span class="text-white font-bold uppercase text-xs">{{ $clienteSeleccionado['nombre'] }}</span></p>
                        <p><span class="text-gray-500 uppercase tracking-tighter">E. Domicilio:</span><br><span class="italic text-gray-300">{{ $clienteSeleccionado['domicilio'] ?? 'No especificado' }}</span></p>
                        <p><span class="text-gray-500 uppercase tracking-tighter">F. Servicio:</span><br><span class="text-red-500 font-black">RECUPERACION DE EQUIPO</span></p>
                        
                        <div class="pt-4 border-t border-gray-800 space-y-2">
                            <p class="font-black text-indigo-400 uppercase italic">G. Ubicación Técnica:</p>
                            <p>NAP: <span class="ml-2 font-bold">{{ $clienteSeleccionado['nap'] }}</span></p>
                            <p class="text-gray-400 text-[10px]">{{ $clienteSeleccionado['direccion_nap'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 font-mono">
                    <p class="text-[9px] font-black text-indigo-800 uppercase mb-2 italic">H. Equipo a Recuperar:</p>
                    <p class="text-xs font-black text-indigo-900 uppercase tracking-tighter break-words">{{ $clienteSeleccionado['equipo'] }}</p>
                </div>
            </div>

            <div class="md:col-span-8 bg-white border border-gray-200 rounded-2xl shadow-sm p-6 sm:p-10 space-y-8">
                <div class="flex items-center gap-4 border-b pb-6">
                    <div class="w-12 h-12 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center">
                        <i class="ri-user-settings-line text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 uppercase">Configurar Orden de Recuperación</h3>
                        <p class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">Responsable de Sucursal</p>
                    </div>
                </div>

                <div class="space-y-8">
                    <div>
                        <label class="block text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-2 italic">Asignar Técnico para Cancelación Física</label>
                        <select wire:model="tecnicoAsignado" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm font-bold py-3 px-4 focus:ring-red-500">
                            <option value="">Seleccione al técnico responsable...</option>
                            <option value="Roberto">Téc. Roberto Gómez</option>
                            <option value="Cuadrilla 2">Cuadrilla 2 (Zona Norte)</option>
                        </select>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                        <div class="flex items-start gap-4">
                            <div class="mt-1 flex-shrink-0">
                                <input type="checkbox" wire:model="notificarSms" class="w-5 h-5 text-indigo-600 rounded border-gray-300">
                            </div>
                            <div class="text-xs leading-relaxed text-blue-800">
                                <span class="block font-black uppercase mb-1">Automatización de SMS (API META)</span>
                                Al generar el reporte, se notificará al TÉCNICO y se enviará mensaje de <strong>RECUPERACIÓN DE EQUIPO POR FALTA DE PAGO</strong> al cliente.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-8 border-t border-gray-100 flex flex-col gap-4">
                    <button wire:click="generarReporte" @if(!$tecnicoAsignado) disabled @endif 
                        class="w-full py-4 bg-red-600 text-white font-black uppercase text-xs tracking-[0.3em] rounded-2xl shadow-xl hover:bg-red-700 transition transform active:scale-95 disabled:opacity-40">
                        Generar Reporte y Notificar SMS
                    </button>
                    <button wire:click="$set('paso', 1)" class="text-center text-[10px] text-gray-400 font-bold uppercase tracking-widest hover:text-indigo-600 transition">Regresar al listado de deudores</button>
                </div>
            </div>
        </div>
    @endif
</div>