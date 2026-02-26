<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ENCABEZADO --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-red-600">Cancelación Definitiva</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Baja de Servicio y <span class="text-red-600">Activos</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Cierre de expediente, recuperación de equipos y liberación de red</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>

    {{-- ================================================================
         PASO 1 — BUSCAR SUSCRIPTOR
    ================================================================ --}}
    @if($paso == 1)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-red-100 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-red-50 border-b border-red-100 px-6 py-6 text-center">
                <div class="w-14 h-14 bg-white border border-red-100 rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-3">
                    <i class="ri-user-unfollow-line text-red-500 text-2xl"></i>
                </div>
                <p class="text-[11px] font-black text-red-800 uppercase tracking-widest">Proceso de Baja Definitiva</p>
                <p class="text-[10px] text-red-400 mt-1 font-medium">Localizar suscriptor para cancelación</p>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-search-eye-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" wire:model="busqueda"
                               placeholder="Nombre, ID o teléfono..."
                               wire:keydown.enter="buscarCliente"
                               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-colors placeholder:text-gray-300">
                    </div>
                    <button wire:click="buscarCliente"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm">
                        Consultar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 2 — DATOS DEL SUSCRIPTOR + VALIDACIÓN DE SALDO
    ================================================================ --}}
    @if($paso == 2)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Expediente del cliente --}}
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                    <i class="ri-file-user-line text-gray-500"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Información del Suscriptor</p>
                </div>
                <div class="p-6 space-y-5">
                    <div>
                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">Titular del Contrato</p>
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">{{ $cliente['nombre'] }}</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Servicio Actual</p>
                            <p class="text-xs font-black text-indigo-600 uppercase">{{ $cliente['servicio_actual'] }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Estatus Operativo</p>
                            <p class="text-xs font-black text-emerald-600 uppercase">{{ $cliente['estado_actual'] }}</p>
                        </div>
                    </div>
                    {{-- Saldo con color semántico --}}
                    <div class="rounded-xl overflow-hidden shadow-sm {{ $cliente['saldo'] > 0 ? 'bg-red-600' : 'bg-emerald-600' }}">
                        <div class="px-6 py-5 text-white relative overflow-hidden">
                            <div class="absolute -right-4 -bottom-4 opacity-10 font-black italic text-7xl">CASH</div>
                            <p class="text-[9px] font-black uppercase tracking-widest mb-1 opacity-70">Saldo deudor para cierre</p>
                            <p class="text-3xl font-black tracking-tight">${{ number_format($cliente['saldo'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Acción según saldo --}}
        <div class="lg:col-span-5">

            @if($cliente['saldo'] > 0)
            {{-- Bloqueado por adeudo --}}
            <div class="bg-white border-2 border-red-100 rounded-xl text-center shadow-sm overflow-hidden">
                <div class="bg-red-50 px-6 py-8">
                    <div class="w-14 h-14 bg-white border border-red-100 rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4">
                        <i class="ri-error-warning-fill text-red-500 text-2xl"></i>
                    </div>
                    <p class="text-base font-black text-red-800 uppercase tracking-tight">Baja Bloqueada por Adeudo</p>
                </div>
                <div class="px-6 pb-6 space-y-4">
                    <p class="text-xs text-red-600 font-bold uppercase tracking-tight leading-relaxed">
                        El suscriptor debe tener saldo $0.00 para proceder. Liquide el adeudo antes de continuar.
                    </p>
                    <a href="{{ route('pago.mensualidad') }}"
                       class="block w-full py-3.5 bg-red-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-md shadow-red-200 transition-all active:scale-95 text-center">
                        <i class="ri-coin-line mr-1"></i> Ir a Cobro de Mensualidad
                    </a>
                    <button wire:click="$set('paso', 1)"
                            class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center justify-center gap-1.5 w-full">
                        <i class="ri-arrow-left-line"></i> Elegir otro cliente
                    </button>
                </div>
            </div>

            @else
            {{-- Libre de adeudo — asignar técnico --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-emerald-50 border-b border-emerald-100 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-checkbox-circle-fill text-emerald-500"></i>
                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Saldo $0.00 — Puede proceder</p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Técnico Asignado para Retiro *</label>
                        <div class="relative">
                            <i class="ri-truck-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                            <select wire:model.live="tecnicoAsignado"
                                    class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-xs font-black uppercase focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-colors">
                                <option value="">— Seleccione responsable —</option>
                                <option value="Roberto">ING. ROBERTO GÓMEZ</option>
                                <option value="Brigada 1">BRIGADA 1 (OAXACA CENTRO)</option>
                            </select>
                        </div>
                    </div>

                    <button wire:click="generarReporteBaja"
                            @if(!$tecnicoAsignado) disabled @endif
                            class="w-full py-3.5 bg-gray-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black shadow-md transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <i class="ri-file-warning-line text-base"></i> Generar Reporte de Baja
                    </button>

                    <button wire:click="$set('paso', 1)"
                            class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center justify-center gap-1.5 w-full">
                        <i class="ri-arrow-left-line"></i> Elegir otro cliente
                    </button>
                </div>
            </div>
            @endif

        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 3 — FORMULARIO DE CANCELACIÓN TÉCNICA
    ================================================================ --}}
    @if($paso == 3)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Panel izquierdo: datos previos --}}
        <div class="lg:col-span-4 space-y-4">

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-900 px-5 py-3.5 flex items-center justify-between">
                    <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Expediente de Cancelación</p>
                    <span class="font-mono text-xs font-black text-white">{{ $numeroReporte }}</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @php
                        $datosBaja = [
                            ['icon' => 'ri-calendar-event-line', 'label' => 'Fecha Apertura',   'value' => $fechaReporte, 'mono' => true],
                            ['icon' => 'ri-user-line',           'label' => 'Titular Legal',    'value' => $cliente['nombre'], 'bold' => true],
                            ['icon' => 'ri-map-pin-line',        'label' => 'Domicilio',         'value' => $cliente['domicilio'], 'italic' => true],
                            ['icon' => 'ri-user-star-line',      'label' => 'Técnico Asignado',  'value' => $tecnicoAsignado, 'badge' => 'indigo'],
                        ];
                    @endphp
                    @foreach($datosBaja as $d)
                    <div class="flex items-start gap-3 px-4 py-3">
                        <i class="{{ $d['icon'] }} text-gray-400 text-sm flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $d['label'] }}</p>
                            @if(isset($d['bold']))
                                <p class="text-xs font-black text-gray-900 uppercase mt-0.5">{{ $d['value'] }}</p>
                            @elseif(isset($d['italic']))
                                <p class="text-xs text-gray-500 italic leading-relaxed mt-0.5">{{ $d['value'] }}</p>
                            @elseif(isset($d['mono']))
                                <p class="font-mono text-xs font-black text-gray-800 mt-0.5">{{ $d['value'] }}</p>
                            @elseif(isset($d['badge']))
                                <span class="text-[10px] font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md uppercase">{{ $d['value'] }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    {{-- NAP --}}
                    <div class="px-4 py-3 bg-indigo-50/60">
                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">Ubicación de Red</p>
                        <p class="text-xs font-black text-gray-800 uppercase">{{ $cliente['nap'] }}</p>
                        <p class="text-[10px] text-gray-500 italic mt-0.5">{{ $cliente['direccion_nap'] }}</p>
                    </div>

                    {{-- Equipo --}}
                    <div class="px-4 py-3 bg-red-50/60">
                        <p class="text-[9px] font-black text-red-500 uppercase tracking-widest mb-1">Activo a Recuperar</p>
                        <p class="text-xs font-black text-gray-900 uppercase">{{ $cliente['equipo_asignado'] }}</p>
                        <p class="font-mono text-[10px] text-indigo-600 font-black tracking-widest mt-0.5">{{ $cliente['serie_registrada'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Panel derecho: formulario técnico --}}
        <div class="lg:col-span-8 space-y-4">

            {{-- SECCIÓN A: Recuperación física del equipo --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-router-line text-red-500 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Estado Físico de Recuperación de Equipos</p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="recuperaEquipo" value="si" class="peer sr-only">
                            <div class="text-center border-2 rounded-xl p-4 transition-all
                                        peer-checked:border-emerald-500 peer-checked:bg-emerald-50
                                        border-gray-200 hover:border-emerald-200 cursor-pointer">
                                <i class="ri-checkbox-circle-line block text-2xl mb-1.5 text-gray-300 peer-checked:text-emerald-500"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 peer-checked:text-emerald-700">
                                    SÍ, Equipo Recuperado
                                </p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="recuperaEquipo" value="no" class="peer sr-only">
                            <div class="text-center border-2 rounded-xl p-4 transition-all
                                        peer-checked:border-red-500 peer-checked:bg-red-50
                                        border-gray-200 hover:border-red-200 cursor-pointer">
                                <i class="ri-close-circle-line block text-2xl mb-1.5 text-gray-300 peer-checked:text-red-500"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 peer-checked:text-red-700">
                                    NO Recuperado
                                </p>
                            </div>
                        </label>
                    </div>

                    @if($recuperaEquipo == 'si')
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-indigo-600 uppercase tracking-widest">Validar Serie Recuperada (escaneo o manual)</label>
                        <input type="text" wire:model="serieConfirmada"
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg font-mono text-sm uppercase py-2.5 px-4 font-black tracking-widest text-indigo-700 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                               placeholder="SERIE DEL EQUIPO RECUPERADO...">
                    </div>
                    @endif

                    @if($recuperaEquipo == 'no')
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                        <label class="flex items-start gap-3 cursor-pointer">
                            <input type="checkbox" wire:model.live="pagoPerdida"
                                   class="mt-0.5 h-5 w-5 text-red-600 rounded border-red-300 focus:ring-0">
                            <div>
                                <p class="text-[11px] font-black text-red-900 uppercase tracking-widest mb-1">Registro de Pérdida Liquidada</p>
                                <p class="text-[10px] text-red-700 leading-relaxed font-medium">
                                    Al marcar, se certifica que el cliente pagó el valor del equipo no recuperado. El activo será liberado del inventario del suscriptor.
                                </p>
                            </div>
                        </label>
                    </div>
                    @endif
                </div>
            </div>

            {{-- SECCIÓN B + C: Gestión Software + Campo --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5">
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Checklist de Desactivación</p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x divide-gray-100">

                    {{-- Software --}}
                    <div class="p-5 space-y-3">
                        <p class="text-[10px] font-black text-indigo-600 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                            <i class="ri-computer-line"></i> A. Gestión Software (Sucursal)
                        </p>
                        @foreach([
                            ['label' => 'Baja nombre en Winbox',     'key' => 'bajaWinbox'],
                            ['label' => 'Baja plan datos en Winbox', 'key' => 'bajaPlanWinbox'],
                            ['label' => 'Baja de datos en OLT',      'key' => 'bajaOLT'],
                        ] as $check)
                        <label class="flex items-center gap-3 text-[10px] font-black text-gray-700 uppercase cursor-pointer hover:text-indigo-600 transition-colors group">
                            <input type="checkbox" class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-0 group-hover:border-indigo-400">
                            {{ $check['label'] }}
                        </label>
                        @endforeach
                    </div>

                    {{-- Campo --}}
                    <div class="p-5 space-y-3">
                        <p class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-1 flex items-center gap-1.5">
                            <i class="ri-tools-line"></i> B. Gestión Campo (Técnico)
                        </p>
                        <label class="flex items-center gap-3 text-[10px] font-black text-red-700 uppercase cursor-pointer hover:text-red-800 transition-colors group">
                            <input type="checkbox" wire:model="desconexionFisica"
                                   class="h-5 w-5 text-red-600 rounded border-red-300 focus:ring-0">
                            Confirmar desconexión en NAP
                        </label>
                        <div class="space-y-1.5">
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">Puerto NAP Liberado</label>
                            <input type="text"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs py-2.5 px-4 font-black uppercase focus:ring-2 focus:ring-red-500/20 focus:border-red-400 placeholder:text-gray-300"
                                   placeholder="Ej. Salida 4">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECCIÓN D: Calificación + Horas + Cierre --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5">
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Evaluación y Cierre</p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Calificación del Servicio</label>
                            <select wire:model="calificacion"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-black uppercase py-2.5 px-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="Excelente">Excelente</option>
                                <option value="Bueno">Bueno</option>
                                <option value="Malo">Malo</option>
                            </select>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-xl px-4 py-3 flex flex-col justify-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Tiempo de Respuesta</p>
                            <p class="text-lg font-black text-gray-800 font-mono">{{ $horasAtencion ?? '0.5' }}h</p>
                            <p class="text-[9px] text-gray-400 font-medium">Cálculo automático</p>
                        </div>
                    </div>

                    {{-- Botones de cierre --}}
                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                        <button class="flex-1 py-3 bg-white border-2 border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i class="ri-save-2-line"></i> Guardar Precierre
                        </button>
                        <button wire:click="finalizarCancelacion"
                                class="flex-1 py-3 bg-red-600 text-white font-black text-[10px] uppercase tracking-widest rounded-xl shadow-md shadow-red-200 hover:bg-red-700 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i class="ri-checkbox-circle-line text-base"></i> Cierre Total y Liberación NAP
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
    @endif

</div>