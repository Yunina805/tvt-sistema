
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8" style="overflow-anchor: none;">

    {{-- ================================================================ ENCABEZADO ================================================================ --}}
    @php
        $tipoBadges = [
            'INSTALACION'           => ['label' => 'Instalación Nueva',   'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'icon' => 'ri-install-line'],
            'FALLA_TV'              => ['label' => 'Falla TV',            'class' => 'bg-violet-100 text-violet-700 border-violet-200',    'icon' => 'ri-tv-2-line'],
            'FALLA_INTERNET'        => ['label' => 'Falla Internet',      'class' => 'bg-sky-100 text-sky-700 border-sky-200',             'icon' => 'ri-wifi-off-line'],
            'FALLA_TV_INTERNET'     => ['label' => 'Falla TV+Internet',   'class' => 'bg-blue-100 text-blue-700 border-blue-200',          'icon' => 'ri-router-line'],
            'CAMBIO_DOMICILIO'      => ['label' => 'Cambio Domicilio',    'class' => 'bg-orange-100 text-orange-700 border-orange-200',    'icon' => 'ri-map-pin-line'],
            'SUSPENSION'            => ['label' => 'Suspensión',          'class' => 'bg-red-100 text-red-700 border-red-200',             'icon' => 'ri-pause-circle-line'],
            'CANCELACION'           => ['label' => 'Cancelación',         'class' => 'bg-gray-200 text-gray-700 border-gray-300',          'icon' => 'ri-close-circle-line'],
            'RECUPERACION'          => ['label' => 'Rec. Equipo',         'class' => 'bg-amber-100 text-amber-700 border-amber-200',       'icon' => 'ri-arrow-down-circle-line'],
            'SERVICIO_ADICIONAL_TV' => ['label' => 'Serv. Adicional TV',  'class' => 'bg-purple-100 text-purple-700 border-purple-200',    'icon' => 'ri-tv-add-line'],
            'AUMENTO_VELOCIDAD'     => ['label' => 'Aumento Velocidad',   'class' => 'bg-teal-100 text-teal-700 border-teal-200',          'icon' => 'ri-speed-up-line'],
            'CAMBIO_SERVICIO'       => ['label' => 'Cambio de Servicio',  'class' => 'bg-cyan-100 text-cyan-700 border-cyan-200',           'icon' => 'ri-swap-line'],
            'RECONEXION'            => ['label' => 'Reconexión',           'class' => 'bg-green-100 text-green-700 border-green-200',         'icon' => 'ri-plug-line'],
            'RECONEXION_CAMBIO'     => ['label' => 'Reconex. + Cambio',    'class' => 'bg-lime-100 text-lime-700 border-lime-200',             'icon' => 'ri-refresh-line'],
        ];
        $tb = $tipoBadges[$reporte['tipo_reporte']] ?? ['label' => $reporte['tipo_reporte'], 'class' => 'bg-gray-100 text-gray-600 border-gray-200', 'icon' => 'ri-file-list-line'];
    @endphp

    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <a href="{{ route('reportes.servicio') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
                    <i class="ri-arrow-left-line"></i> Bandeja de Reportes
                </a>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-indigo-600">Atención Técnica</span>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                    Reporte <span class="text-indigo-600 font-mono">{{ $reporte['folio'] }}</span>
                </h2>
                <span class="inline-flex items-center gap-1.5 px-3 py-1 {{ $tb['class'] }} border rounded-lg text-[10px] font-black uppercase tracking-tight">
                    <i class="{{ $tb['icon'] }}"></i> {{ $tb['label'] }}
                </span>
                @if(isset($reporte['falla_reportada']))
                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $reporte['falla_reportada'] }}</span>
                @endif
            </div>
        </div>
        <div class="flex gap-2 self-start">
            <button wire:click="$toggle('mostrarCambioTecnico')"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-amber-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-amber-50 hover:border-amber-200 transition-all">
                <i class="ri-user-received-line text-base"></i> Cambiar Técnico
            </button>
            <button wire:click="exportarPDF"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-red-500 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-red-50 hover:border-red-200 transition-all">
                <i class="ri-file-pdf-line text-base"></i> PDF
            </button>
        </div>
    </div>

    {{-- Flash --}}
    @if(session()->has('exito'))
    <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
        <i class="ri-checkbox-circle-fill text-emerald-500 text-xl flex-shrink-0"></i>
        <p class="text-[11px] font-black text-emerald-800 uppercase tracking-widest">{{ session('exito') }}</p>
    </div>
    @endif
    @if(session()->has('info'))
    <div class="mb-5 bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center gap-3">
        <i class="ri-information-line text-amber-500 text-xl flex-shrink-0"></i>
        <p class="text-[11px] font-black text-amber-700 uppercase tracking-widest">{{ session('info') }}</p>
    </div>
    @endif

    {{-- ================================================================ CAMBIO DE TÉCNICO (MODAL INLINE) ================================================================ --}}
    @if($mostrarCambioTecnico)
    <div class="mb-5 bg-white border border-amber-300 rounded-xl shadow-md overflow-hidden">
        <div class="bg-amber-50 border-b border-amber-200 px-5 py-3.5 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i class="ri-user-received-line text-amber-600 text-base"></i>
                <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Reasignación de Técnico — Requiere Autorización</p>
            </div>
            <button wire:click="$toggle('mostrarCambioTecnico')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>
        <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="space-y-1.5">
                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">Nuevo Técnico *</label>
                <select wire:model="nuevoTecnico"
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-black uppercase py-2.5 px-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400">
                    <option value="">— Seleccionar —</option>
                    @foreach($catalogoTecnicos as $t)
                    <option value="{{ $t['id'] }}">{{ $t['nombre'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="space-y-1.5">
                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">Motivo del Cambio *</label>
                <select wire:model="motivoCambio"
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-black uppercase py-2.5 px-3 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400">
                    <option value="">— Seleccionar motivo —</option>
                    <option value="Permiso">Permiso</option>
                    <option value="Ausencia">Ausencia</option>
                    <option value="Sobrecarga de Trabajo">Sobrecarga de Trabajo</option>
                    <option value="Conocimiento Técnico">Conocimiento Técnico</option>
                </select>
            </div>
            <div class="space-y-1.5">
                <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">Contraseña de Autorización *</label>
                <input type="password" wire:model="passwordCambio"
                       class="w-full bg-gray-50 border border-gray-200 rounded-lg text-sm py-2.5 px-4 focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400"
                       placeholder="Contraseña sucursal / admin">
            </div>
        </div>
        <div class="bg-gray-50 border-t border-gray-200 px-5 py-3 flex justify-end">
            <button wire:click="cambiarTecnico"
                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-amber-600 text-white font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-amber-700 shadow-sm transition-all active:scale-95">
                <i class="ri-save-line"></i> Guardar Reasignación y Notificar SMS
            </button>
        </div>
    </div>
    @endif

    {{-- ================================================================ LAYOUT PRINCIPAL 2 COLUMNAS ================================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ============================================================ COLUMNA IZQUIERDA — DATOS PREVIOS ============================================================ --}}
        <div class="lg:col-span-4 space-y-4">

            {{-- Panel datos del reporte (todos automáticos) --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-900 px-5 py-3.5 flex items-center justify-between">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Datos Previos del Reporte</p>
                    <span class="font-mono text-[10px] font-black text-gray-400">{{ $reporte['folio'] }}</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @php
                        $datosBase = [
                            ['icon' => 'ri-calendar-event-line', 'label' => 'Fecha de apertura',  'value' => $reporte['fecha'],           'mono' => true],
                            ['icon' => 'ri-store-2-line',         'label' => 'Sucursal',           'value' => $reporte['sucursal']],
                            ['icon' => 'ri-hashtag',              'label' => 'ID Suscriptor',      'value' => $reporte['id_cliente'] ?? '—', 'mono' => true],
                            ['icon' => 'ri-user-line',            'label' => 'Titular',            'value' => $reporte['cliente'],         'bold' => true],
                            ['icon' => 'ri-phone-line',           'label' => 'Teléfono',           'value' => $reporte['telefono'] ?? '—', 'mono' => true],
                            ['icon' => 'ri-map-pin-line',         'label' => 'Domicilio',          'value' => $reporte['domicilio'],       'italic' => true],
                            ['icon' => 'ri-service-line',         'label' => 'Servicio',           'value' => $reporte['servicio'],        'badge' => 'indigo'],
                            ['icon' => 'ri-user-heart-line',      'label' => 'Estado del cliente', 'value' => $reporte['estado_cliente'],  'badge' => 'amber'],
                            ['icon' => 'ri-shield-user-line',     'label' => 'Técnico asignado',   'value' => $reporte['tecnico'],         'badge' => 'indigo'],
                            ['icon' => 'ri-group-line',           'label' => 'Reportado por',      'value' => $reporte['quien_reporto'] ?? 'Sistema'],
                        ];
                    @endphp
                    @foreach($datosBase as $d)
                    <div class="flex items-start gap-3 px-4 py-2.5">
                        <i class="{{ $d['icon'] }} text-gray-300 text-sm flex-shrink-0 mt-0.5"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">{{ $d['label'] }}</p>
                            @if(isset($d['badge']) && $d['badge']==='indigo')
                                <span class="inline-block text-[10px] font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md uppercase mt-0.5 leading-tight">{{ $d['value'] }}</span>
                            @elseif(isset($d['badge']) && $d['badge']==='amber')
                                <span class="inline-block text-[10px] font-black text-amber-700 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded-md uppercase mt-0.5 leading-tight">{{ $d['value'] }}</span>
                            @elseif(!empty($d['mono']))
                                <p class="font-mono text-[10px] font-black text-gray-800 mt-0.5">{{ $d['value'] }}</p>
                            @elseif(!empty($d['italic']))
                                <p class="text-[10px] text-gray-600 italic mt-0.5 leading-relaxed">{{ $d['value'] }}</p>
                            @elseif(!empty($d['bold']))
                                <p class="text-xs font-black text-gray-900 uppercase mt-0.5 truncate">{{ $d['value'] }}</p>
                            @else
                                <p class="text-[10px] font-semibold text-gray-700 uppercase mt-0.5">{{ $d['value'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- NAP y equipo asignado --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
                    <i class="ri-router-line text-indigo-500 text-sm"></i>
                    <p class="text-[10px] font-black text-gray-700 uppercase tracking-widest">Red y Equipo Asignado</p>
                </div>
                <div class="p-4 space-y-3">

                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-3">
                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">NAP Vinculada</p>
                        <p class="text-sm font-black text-gray-900 uppercase">{{ $reporte['nap'] }}</p>
                        <p class="text-[10px] text-gray-500 italic mt-0.5">{{ $reporte['dir_nap'] }}</p>
                    </div>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">
                            {{ $tieneTV && !$tieneInternet ? 'Mininodo Asignado' : 'ONU Asignada' }}
                        </p>
                        <p class="font-mono text-[10px] font-black text-gray-800">{{ $reporte['info_equipo'] }}</p>
                    </div>

                    {{-- Configuración ONU: solo si tiene Internet --}}
                    @if($tieneInternet)
                    <div class="bg-sky-50 border border-sky-100 rounded-lg p-3">
                        <p class="text-[9px] font-black text-sky-600 uppercase tracking-widest mb-2">Configuración ONU</p>
                        @php
                            $onuData = [
                                ['l'=>'IP Asignada',    'v'=>$reporte['ip'],               'mono'=>true],
                                ['l'=>'WIFI',           'v'=>$reporte['wifi'],             'mono'=>true],
                                ['l'=>'Contraseña',     'v'=>$reporte['password_wifi'],    'mono'=>true],
                                ['l'=>'OLT',            'v'=>$reporte['olt']],
                                ['l'=>'PON',            'v'=>$reporte['pon'],              'mono'=>true],
                                ['l'=>'VLAN',           'v'=>$reporte['vlan']],
                                ['l'=>'Encaps.',        'v'=>$reporte['encapsulamiento']],
                            ];
                        @endphp
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1.5">
                            @foreach($onuData as $od)
                            <div>
                                <p class="text-[8px] font-bold text-gray-400 uppercase">{{ $od['l'] }}</p>
                                <p class="text-[10px] font-black text-gray-800 {{ !empty($od['mono']) ? 'font-mono' : '' }}">{{ $od['v'] ?? '—' }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Últimas potencias (no aplica para instalaciones completamente nuevas) --}}
                    @if(!$esInstalacion)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 border-b border-gray-200 px-3 py-2">
                            <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Últimas Potencias Registradas</p>
                        </div>
                        <div class="p-3 grid grid-cols-2 gap-3">
                            <div class="text-center bg-red-50 border border-red-100 rounded-lg p-2">
                                <p class="text-[8px] font-bold text-gray-400 uppercase leading-tight mb-1">Salida NAP</p>
                                <p class="text-base font-black text-red-600 font-mono">{{ $reporte['ultima_potencia_nap'] }}</p>
                                <p class="text-[9px] text-gray-400">dBm</p>
                            </div>
                            <div class="text-center bg-red-50 border border-red-100 rounded-lg p-2">
                                <p class="text-[8px] font-bold text-gray-400 uppercase leading-tight mb-1">Antes Equipo</p>
                                <p class="text-base font-black text-red-600 font-mono">{{ $reporte['ultima_potencia_equipo'] }}</p>
                                <p class="text-[9px] text-gray-400">dBm</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Saldo (solo suspensión/recuperación) --}}
                    @if($esSuspension || $esRecuperacion)
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-center">
                        <p class="text-[9px] font-black text-red-500 uppercase tracking-widest mb-1">Saldo Pendiente</p>
                        <p class="text-2xl font-black text-red-700">${{ number_format($reporte['saldo_pendiente'] ?? 0, 2) }}</p>
                        <p class="text-[9px] text-red-500 font-bold uppercase mt-0.5">{{ $reporte['dias_suspension'] ?? 0 }} días de adeudo</p>
                    </div>
                    @endif

                </div>
            </div>

            {{-- Horas automáticas + calificación --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3">
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Seguimiento del Reporte</p>
                </div>
                <div class="p-4 grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-center">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Horas Transcurridas</p>
                        <p class="text-2xl font-black text-gray-900 font-mono">{{ $horasAtencion ?? '—' }}</p>
                        <p class="text-[9px] text-gray-400 font-medium">automático</p>
                    </div>
                    <div class="border border-gray-200 rounded-xl p-3">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Calificación</p>
                        <select wire:model="calificacion"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-2 text-xs font-black uppercase focus:ring-2 focus:ring-indigo-500/20">
                            <option value="Excelente">⭐ Excelente</option>
                            <option value="Bueno">👍 Bueno</option>
                            <option value="Malo">👎 Malo</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============================================================ COLUMNA DERECHA — ATENCIÓN / LLENADO ============================================================ --}}
        <div class="lg:col-span-8 space-y-5">

            {{-- ═══════════════════════════════════════════════════════
                 ASIGNACION DE EQUIPO
                 i. Información de la ONU asignada (WIFI / Contraseña / VLAN / Encapsulamiento)
                 — Instalación / Servicio Adicional / Cambio Servicio / Falla con cambio de equipo
            ═══════════════════════════════════════════════════════ --}}
            @if($esFalla || $esInstalacion || $esCambioDomicilio || $esServicioAdicional || $esCambioServicio || $esReconexion || $esReconexionCambio)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5">
                    @if($esInstalacion || $esServicioAdicional || $esCambioServicio)
                    <div class="flex items-center gap-2">
                        <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">
                            Asignacion de Equipo —
                            {{ ($tieneTV && !$tieneInternet) ? 'Mininodo Asignado' : 'ONU Asignada' }}
                        </p>
                        <span class="ml-auto text-[9px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-1.5 py-0.5 rounded uppercase">Sucursal</span>
                    </div>
                    @elseif($esReconexion)
                    <div class="flex items-center gap-2">
                        <i class="ri-plug-line text-green-600 text-sm"></i>
                        <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Validación de Equipo — Reconexión</p>
                        <span class="ml-auto text-[9px] font-bold text-green-600 bg-green-50 border border-green-100 px-1.5 py-0.5 rounded uppercase">Técnico</span>
                    </div>
                    @elseif($esReconexionCambio)
                    <div class="flex items-center gap-2">
                        <i class="ri-refresh-line text-lime-600 text-sm"></i>
                        <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Validación de Equipo — Reconexión con Cambio</p>
                        <span class="ml-auto text-[9px] font-bold text-lime-700 bg-lime-50 border border-lime-200 px-1.5 py-0.5 rounded uppercase">Técnico</span>
                    </div>
                    @else
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="cambioEquipo"
                               class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <div>
                            <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">¿Requiere Cambio de Equipo?</p>
                            <p class="text-[10px] text-gray-400">Al cambiar: se devuelve el dañado y se asigna uno nuevo del almacén</p>
                        </div>
                        <span class="ml-auto text-[9px] font-black text-gray-400 bg-gray-100 border border-gray-200 px-2 py-0.5 rounded uppercase">Opcional</span>
                    </label>
                    @endif
                </div>

                @if($cambioEquipo || $esInstalacion || $esServicioAdicional || $esCambioServicio)
                @php
                    // Filtrar catálogo según tipo de servicio
                    // TV-only → solo mininodos | Internet-only → solo ONUs | Combo → todos
                    $equiposFiltrados = collect($catalogoEquipos)->filter(function($eq) use ($tieneTV, $tieneInternet) {
                        if ($tieneTV && !$tieneInternet) return str_starts_with($eq['id'], 'min-');
                        if ($tieneInternet && !$tieneTV)  return str_starts_with($eq['id'], 'onu-');
                        return true;
                    })->values()->all();
                @endphp
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                @if($esInstalacion || $esServicioAdicional || $esCambioServicio)
                                    {{ ($tieneTV && !$tieneInternet) ? 'Mininodo Asignado — Del Catálogo (Sucursal)' : 'ONU Asignada — Del Catálogo (Sucursal)' }}
                                @else
                                    {{ ($tieneTV && !$tieneInternet) ? 'Nuevo Mininodo del Almacén *' : 'Nueva ONU del Almacén *' }}
                                @endif
                            </label>
                            <select wire:model.live="equipoNuevo"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-black uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="">— Seleccione equipo disponible —</option>
                                @foreach($equiposFiltrados as $eq)
                                <option value="{{ $eq['id'] }}">{{ $eq['label'] }}</option>
                                @endforeach
                            </select>
                            @error('equipoNuevo')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                        </div>

                        @if($tieneInternet)
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Nombre del WIFI</label>
                            <input type="text" wire:model="wifiNuevo"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                   placeholder="TuVision_001">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Contraseña</label>
                            <input type="text" wire:model="passwordNuevo"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-bold font-mono focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">VLAN (viene de catálogo)</label>
                            <select wire:model="vlanNueva"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-black uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="">— Seleccione VLAN —</option>
                                <option value="100">VLAN 100</option>
                                <option value="200">VLAN 200</option>
                                <option value="300">VLAN 300</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Encapsulamiento (viene de catálogo)</label>
                            <select wire:model="encapsulamientoNuevo"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-black uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="">— Seleccione —</option>
                                <option value="IPoE">IPoE</option>
                                <option value="PPPoE">PPPoE</option>
                            </select>
                        </div>
                        @endif
                    </div>

                    @if($esFalla)
                    <div class="flex items-start gap-3 bg-indigo-50 border border-indigo-100 rounded-lg p-3.5">
                        <i class="ri-information-line text-indigo-500 text-base flex-shrink-0 mt-0.5"></i>
                        <p class="text-[10px] font-medium text-indigo-700 leading-relaxed">
                            Al guardar: se generará <strong>comodato automático</strong>, se registrará la devolución del equipo dañado en inventario, y se enviará <strong>SMS al responsable de sucursal</strong>. Una vez listo el equipo, el técnico recibirá SMS de confirmación.
                        </p>
                    </div>
                    <button wire:click="guardarCambioEquipo"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition-all active:scale-95">
                        <i class="ri-save-line"></i> Guardar Asignación de {{ ($tieneTV && !$tieneInternet) ? 'Mininodo' : 'Equipo' }}
                    </button>
                    @elseif($esServicioAdicional)
                    <div class="flex items-start gap-3 bg-purple-50 border border-purple-100 rounded-lg p-3.5">
                        <i class="ri-information-line text-purple-500 text-base flex-shrink-0 mt-0.5"></i>
                        <p class="text-[10px] font-medium text-purple-700 leading-relaxed">
                            Al guardar: se generará <strong>comodato automático</strong> para el mininodo de TV, se registrará la asignación en inventario, y se notificará al responsable de sucursal.
                        </p>
                    </div>
                    <button wire:click="guardarCambioEquipo"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-purple-700 shadow-sm transition-all active:scale-95">
                        <i class="ri-save-line"></i> Guardar Asignación de Mininodo
                    </button>
                    @endif
                </div>
                @endif

                {{-- ─── RECONEXION: validación de equipo (conserva o nuevo) ─── --}}
                @if($esReconexion)
                <div class="p-5" x-data="{ conserva: '{{ $conservaEquipo }}' }">
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-3">¿El suscriptor conserva el equipo?</p>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <label class="cursor-pointer" @click="conserva = 'si'">
                            <div class="text-center border-2 rounded-xl p-3 transition-all cursor-pointer"
                                 :class="conserva === 'si' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-emerald-200'">
                                <i class="ri-checkbox-circle-line block text-xl mb-1"
                                   :class="conserva === 'si' ? 'text-emerald-500' : 'text-gray-300'"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest"
                                   :class="conserva === 'si' ? 'text-emerald-700' : 'text-gray-400'">Conserva el Equipo</p>
                            </div>
                        </label>
                        <label class="cursor-pointer" @click="conserva = 'no'">
                            <div class="text-center border-2 rounded-xl p-3 transition-all cursor-pointer"
                                 :class="conserva === 'no' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-200'">
                                <i class="ri-swap-box-line block text-xl mb-1"
                                   :class="conserva === 'no' ? 'text-amber-500' : 'text-gray-300'"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest"
                                   :class="conserva === 'no' ? 'text-amber-700' : 'text-gray-400'">Equipo Nuevo</p>
                            </div>
                        </label>
                    </div>

                    {{-- SI conserva: confirmar serie --}}
                    <div x-show="conserva === 'si'" x-cloak class="space-y-3">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 space-y-3">
                            <p class="text-[9px] font-black text-emerald-700 uppercase tracking-widest">Confirmar serie del equipo del suscriptor</p>
                            <p class="font-mono text-sm font-black text-indigo-700 bg-white border border-indigo-100 px-4 py-2 rounded-lg">
                                {{ $reporte['info_equipo'] ?? '—' }}
                            </p>
                            <div class="space-y-1.5">
                                <label class="block text-[9px] font-black text-gray-600 uppercase tracking-widest">Serie física confirmada *</label>
                                <input type="text" wire:model.live="serieConservada"
                                       class="w-full bg-white border border-emerald-300 rounded-lg py-2.5 px-4 text-sm font-black font-mono uppercase tracking-widest text-indigo-700 focus:ring-2 focus:ring-emerald-500/20"
                                       placeholder="ESCANEAR O ESCRIBIR SERIE...">
                            </div>
                            @if($serieConservada)
                                @php $serieOkRec = str_contains($reporte['info_equipo'] ?? '', $serieConservada); @endphp
                                @if($serieOkRec)
                                <div class="flex items-center gap-2 text-[10px] font-black text-emerald-700">
                                    <i class="ri-checkbox-circle-fill text-emerald-500"></i> Serie confirmada — el equipo se reasignará al inventario activo ✓
                                </div>
                                @else
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                    <p class="text-[10px] font-black text-amber-800 flex items-center gap-1.5">
                                        <i class="ri-alert-line text-amber-600"></i> Serie diferente — el sistema actualizará el registro con la serie física.
                                    </p>
                                </div>
                                @endif
                            @endif
                            <p class="text-[9px] text-gray-400">Al confirmar: el equipo queda registrado como activo en inventario de sucursal.</p>
                        </div>
                        <button wire:click="guardarCambioEquipo"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-emerald-700 shadow-sm transition-all active:scale-95">
                            <i class="ri-save-line"></i> Confirmar — Mismo Equipo
                        </button>
                    </div>

                    {{-- NO conserva: asignar equipo nuevo del catálogo --}}
                    <div x-show="conserva === 'no'" x-cloak class="space-y-3">
                        @php
                            $equiposRec = collect($catalogoEquipos)->filter(function($eq) use ($tieneTV, $tieneInternet) {
                                if ($tieneTV && !$tieneInternet) return str_starts_with($eq['id'], 'min-');
                                if ($tieneInternet && !$tieneTV)  return str_starts_with($eq['id'], 'onu-');
                                return true;
                            })->values()->all();
                        @endphp
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                {{ ($tieneTV && !$tieneInternet) ? 'Nuevo Mininodo del Almacén *' : 'Nueva ONU del Almacén *' }}
                            </label>
                            <select wire:model.live="equipoNuevo"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-black uppercase focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400">
                                <option value="">— Seleccione equipo disponible —</option>
                                @foreach($equiposRec as $eq)
                                <option value="{{ $eq['id'] }}">{{ $eq['label'] }}</option>
                                @endforeach
                            </select>
                            @error('equipoNuevo')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-lg p-3.5">
                            <i class="ri-information-line text-amber-500 text-base flex-shrink-0 mt-0.5"></i>
                            <p class="text-[10px] font-medium text-amber-700 leading-relaxed">
                                Al guardar: se generará <strong>comodato automático</strong> para el nuevo equipo y se registrará la baja del equipo anterior en inventario.
                            </p>
                        </div>
                        <button wire:click="guardarCambioEquipo"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-amber-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-amber-700 shadow-sm transition-all active:scale-95">
                            <i class="ri-save-line"></i> Asignar Nuevo {{ ($tieneTV && !$tieneInternet) ? 'Mininodo' : 'Equipo' }}
                        </button>
                    </div>
                </div>
                @endif

                {{-- ─── RECONEXION_CAMBIO: validación de equipo (conserva o nuevo) ─── --}}
                @if($esReconexionCambio)
                <div class="p-5" x-data="{ conserva: '{{ $conservaEquipo }}' }">
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-3">¿El suscriptor conserva el equipo anterior?</p>
                    <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 mb-3">
                        <p class="text-[9px] font-black text-amber-700 uppercase tracking-widest mb-1">Equipo Previo Registrado</p>
                        <p class="font-mono text-[10px] font-black text-gray-800">{{ $reporte['equipo_anterior'] ?? '—' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-4">
                        <label class="cursor-pointer" @click="conserva = 'si'">
                            <div class="text-center border-2 rounded-xl p-3 transition-all cursor-pointer"
                                 :class="conserva === 'si' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-emerald-200'">
                                <i class="ri-checkbox-circle-line block text-xl mb-1"
                                   :class="conserva === 'si' ? 'text-emerald-500' : 'text-gray-300'"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest"
                                   :class="conserva === 'si' ? 'text-emerald-700' : 'text-gray-400'">Conserva el Equipo</p>
                            </div>
                        </label>
                        <label class="cursor-pointer" @click="conserva = 'no'">
                            <div class="text-center border-2 rounded-xl p-3 transition-all cursor-pointer"
                                 :class="conserva === 'no' ? 'border-amber-500 bg-amber-50' : 'border-gray-200 hover:border-amber-200'">
                                <i class="ri-swap-box-line block text-xl mb-1"
                                   :class="conserva === 'no' ? 'text-amber-500' : 'text-gray-300'"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest"
                                   :class="conserva === 'no' ? 'text-amber-700' : 'text-gray-400'">Equipo Nuevo</p>
                            </div>
                        </label>
                    </div>

                    {{-- SI conserva: confirmar serie --}}
                    <div x-show="conserva === 'si'" x-cloak class="space-y-3">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-4 space-y-3">
                            <p class="text-[9px] font-black text-emerald-700 uppercase tracking-widest">Confirmar serie del equipo conservado</p>
                            <div class="space-y-1.5">
                                <label class="block text-[9px] font-black text-gray-600 uppercase tracking-widest">Serie física confirmada *</label>
                                <input type="text" wire:model.live="serieConservada"
                                       class="w-full bg-white border border-emerald-300 rounded-lg py-2.5 px-4 text-sm font-black font-mono uppercase tracking-widest text-indigo-700 focus:ring-2 focus:ring-emerald-500/20"
                                       placeholder="ESCANEAR O ESCRIBIR SERIE...">
                            </div>
                            @if($serieConservada)
                                @php $serieOkRc = str_contains($reporte['equipo_anterior'] ?? '', $serieConservada); @endphp
                                @if($serieOkRc)
                                <div class="flex items-center gap-2 text-[10px] font-black text-emerald-700">
                                    <i class="ri-checkbox-circle-fill text-emerald-500"></i> Serie confirmada — equipo reactivado en inventario activo ✓
                                </div>
                                @else
                                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                    <p class="text-[10px] font-black text-amber-800 flex items-center gap-1.5">
                                        <i class="ri-alert-line text-amber-600"></i> Serie diferente — el sistema actualizará el registro con la serie física.
                                    </p>
                                </div>
                                @endif
                            @endif
                            <p class="text-[9px] text-gray-400">Nota: Aunque se conserva el equipo, se generará nuevo comodato por el cambio de servicio.</p>
                        </div>
                        <button wire:click="guardarCambioEquipo"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-emerald-700 shadow-sm transition-all active:scale-95">
                            <i class="ri-save-line"></i> Confirmar — Mismo Equipo
                        </button>
                    </div>

                    {{-- NO conserva: asignar equipo nuevo del catálogo --}}
                    <div x-show="conserva === 'no'" x-cloak class="space-y-3">
                        @php
                            $equiposRc = collect($catalogoEquipos)->filter(function($eq) use ($tieneTV, $tieneInternet) {
                                if ($tieneTV && !$tieneInternet) return str_starts_with($eq['id'], 'min-');
                                if ($tieneInternet && !$tieneTV)  return str_starts_with($eq['id'], 'onu-');
                                return true;
                            })->values()->all();
                        @endphp
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                {{ ($tieneTV && !$tieneInternet) ? 'Nuevo Mininodo del Almacén *' : 'Nueva ONU del Almacén *' }}
                            </label>
                            <select wire:model.live="equipoNuevo"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-black uppercase focus:ring-2 focus:ring-lime-500/20 focus:border-lime-400">
                                <option value="">— Seleccione equipo disponible —</option>
                                @foreach($equiposRc as $eq)
                                <option value="{{ $eq['id'] }}">{{ $eq['label'] }}</option>
                                @endforeach
                            </select>
                            @error('equipoNuevo')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                        </div>
                        <div class="flex items-start gap-3 bg-lime-50 border border-lime-100 rounded-lg p-3.5">
                            <i class="ri-information-line text-lime-600 text-base flex-shrink-0 mt-0.5"></i>
                            <p class="text-[10px] font-medium text-lime-800 leading-relaxed">
                                Al guardar: se dará de baja el equipo anterior en inventario, se asignará el nuevo y se generará <strong>comodato automático</strong>.
                            </p>
                        </div>
                        <button wire:click="guardarCambioEquipo"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-lime-700 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-lime-800 shadow-sm transition-all active:scale-95">
                            <i class="ri-save-line"></i> Asignar Nuevo {{ ($tieneTV && !$tieneInternet) ? 'Mininodo' : 'Equipo' }}
                        </button>
                    </div>
                </div>
                @endif
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 [RECONEXION_CAMBIO] Infraestructura Histórica — Servicio Anterior
            ═══════════════════════════════════════════════════════ --}}
            @if($esReconexionCambio)
            <div class="bg-white border border-amber-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-amber-600 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-history-line text-white text-base"></i>
                    <p class="text-[11px] font-black text-white uppercase tracking-widest">Infraestructura Previa — Servicio Anterior</p>
                    <span class="ml-auto text-[9px] font-black bg-amber-100 text-amber-800 px-2 py-0.5 rounded uppercase">Solo Lectura</span>
                </div>
                <div class="p-5 space-y-4">

                    {{-- Datos del servicio anterior --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Servicio Anterior</p>
                            <p class="text-xs font-black text-gray-800 uppercase">{{ $reporte['servicio_anterior'] ?? '—' }}</p>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-1">Nuevo Servicio</p>
                            <p class="text-xs font-black text-gray-800 uppercase">{{ $reporte['servicio'] ?? '—' }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Tarifa Anterior</p>
                            <p class="text-xs font-black text-gray-700 font-mono">{{ $reporte['tarifa_anterior'] ?? '—' }}</p>
                        </div>
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <p class="text-[9px] font-black text-amber-600 uppercase tracking-widest mb-1">Nueva Tarifa</p>
                            <p class="text-xs font-black text-gray-800 font-mono">{{ $reporte['tarifa_nueva'] ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- Infraestructura previa --}}
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 border-b border-gray-200 px-4 py-2.5">
                            <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Red y NAP Anterior</p>
                        </div>
                        <div class="p-4 grid grid-cols-2 gap-x-6 gap-y-2">
                            @php
                                $infraHistorica = [
                                    ['l' => 'NAP Anterior',           'v' => $reporte['nap_anterior'] ?? '—',              'mono' => true],
                                    ['l' => 'Dirección NAP',          'v' => $reporte['dir_nap_anterior'] ?? '—'],
                                    ['l' => 'Salida NAP',             'v' => $reporte['salida_nap_anterior'] ?? '—',       'mono' => true],
                                    ['l' => 'Metros Acometida',       'v' => ($reporte['metros_acometida_anterior'] ?? '—') . ' m', 'mono' => true],
                                    ['l' => 'Potencia Salida NAP',    'v' => ($reporte['ultima_potencia_nap'] ?? '—') . ' dBm',    'mono' => true],
                                    ['l' => 'Potencia Antes Equipo',  'v' => ($reporte['ultima_potencia_equipo'] ?? '—') . ' dBm', 'mono' => true],
                                ];
                            @endphp
                            @foreach($infraHistorica as $ih)
                            <div>
                                <p class="text-[8px] font-bold text-gray-400 uppercase">{{ $ih['l'] }}</p>
                                <p class="text-[10px] font-black text-gray-800 {{ !empty($ih['mono']) ? 'font-mono' : '' }}">{{ $ih['v'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 INSTALACION DEL SERVICIO
                 x. Registro de la NAP donde se conectara el servicio (del catálogo / técnico)
                    i. Dirección de la NAP (del catálogo — automático)
                   ii. Seleccionar el # de Salida del NAP (del catálogo — técnico)
                  iii. Afectar las salidas de inventario de la NAP (automático)
            ═══════════════════════════════════════════════════════ --}}
            @if($esInstalacion || $esServicioAdicional || $esCambioServicio || $esReconexion || $esReconexionCambio)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Registro de la NAP donde se Conectara el Servicio</p>
                    <span class="ml-auto text-[9px] font-bold text-gray-400 uppercase">Del Catálogo / Técnico</span>
                    <span class="text-[9px] font-bold text-red-500 bg-red-50 border border-red-100 px-1.5 py-0.5 rounded uppercase">Requerido</span>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">NAP de Conexión * (Del catálogo)</label>
                        <select wire:model.live="napSeleccionada"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-black uppercase py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                            <option value="">— Seleccione del catálogo —</option>
                            @foreach($catalogoNaps as $nap)
                            <option value="{{ $nap['id'] }}">{{ $nap['nombre'] }} — {{ $nap['dir'] }}</option>
                            @endforeach
                        </select>
                        @error('napSeleccionada')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                        @if($napSeleccionada)
                        <p class="text-[9px] text-indigo-500 font-bold uppercase flex items-center gap-1">
                            <i class="ri-map-pin-line"></i> Dirección de la NAP cargada automáticamente desde catálogo
                        </p>
                        @endif
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest"># de Salida del NAP * (Salidas disponibles)</label>
                        <select wire:model="salidaNap"
                                @if(!$napSeleccionada) disabled @endif
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-black uppercase py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 disabled:opacity-40">
                            <option value="">— Salidas libres —</option>
                            @foreach($reporte['salidas_nap_disponibles'] ?? [] as $s)
                            <option value="{{ $s }}">Salida #{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('salidaNap')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                        <p class="text-[9px] text-gray-400 font-medium">Al cerrar el reporte, la salida se marcará como ocupada en inventario (automático)</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 [CAMBIO DOMICILIO] Cobro + nueva dirección
            ═══════════════════════════════════════════════════════ --}}
            @if($esCambioDomicilio)
            <div class="bg-amber-50 border border-amber-200 rounded-xl overflow-hidden">
                <div class="bg-amber-100 border-b border-amber-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-money-dollar-circle-line text-amber-600 text-lg"></i>
                    <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Cobro por Cambio de Domicilio</p>
                    <span class="ml-auto text-lg font-black text-amber-800">${{ number_format($reporte['costo_cambio_dom'] ?? 300, 2) }}</span>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[9px] font-black text-amber-700 uppercase tracking-widest">Método de Pago</label>
                        <select wire:model="metodoPagoCambioDom"
                                class="w-full bg-white border border-amber-200 rounded-lg py-2.5 px-3 text-xs font-black uppercase focus:ring-2 focus:ring-amber-400/30">
                            <option value="efectivo">Efectivo</option>
                            <option value="tarjeta">Tarjeta</option>
                            <option value="transferencia">Transferencia</option>
                        </select>
                    </div>
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-[9px] font-black text-amber-700 uppercase tracking-widest">Nueva Dirección del Domicilio</label>
                        <input type="text" wire:model="nuevaDireccion"
                               class="w-full bg-white border border-amber-200 rounded-lg py-2.5 px-4 text-sm font-medium focus:ring-2 focus:ring-amber-400/30"
                               placeholder="Calle, número exterior, número interior...">
                    </div>
                    <div class="sm:col-span-3 space-y-1.5">
                        <label class="block text-[9px] font-black text-amber-700 uppercase tracking-widest">Referencias del nuevo domicilio</label>
                        <input type="text" wire:model="nuevasDirReferencias"
                               class="w-full bg-white border border-amber-200 rounded-lg py-2.5 px-4 text-sm font-medium focus:ring-2 focus:ring-amber-400/30"
                               placeholder="Portón café, frente al parque, casa con tejado rojo...">
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 y. Registro de la Potencia óptica de la salida del divisor en la NAP (técnico)
                 z. Anotar cuantos Metros de Acometida son de la NAP al Domicilio (técnico)
                 aa. Potencia Óptica de llegada al domicilio antes de la ONU (técnico)
            ═══════════════════════════════════════════════════════ --}}
            @if($esFalla || $esInstalacion || $esCambioDomicilio || $esServicioAdicional || $esCambioServicio || $esReconexion || $esReconexionCambio)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-line-chart-line text-indigo-500 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">
                        Lecturas Técnicas — Potencias Ópticas NUEVAS
                    </p>
                    <span class="ml-auto text-[9px] font-bold text-gray-400 uppercase">Técnico</span>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            Potencia óptica de la salida del divisor en la NAP *
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="potenciaNap"
                                   class="w-full pl-4 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-black font-mono focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                   placeholder="-18.5">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">dBm</span>
                        </div>
                        @error('potenciaNap')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            Metros de Acometida (NAP → Domicilio)
                        </label>
                        <div class="relative">
                            <input type="number" wire:model="metrosAcometida"
                                   class="w-full pl-4 pr-8 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-black focus:ring-2 focus:ring-indigo-500/20"
                                   placeholder="0">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">m</span>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            Potencia óptica antes {{ $tieneTV && !$tieneInternet ? 'Mininodo' : 'ONU' }} *
                        </label>
                        <div class="relative">
                            <input type="text" wire:model="potenciaEquipo"
                                   class="w-full pl-4 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-black font-mono focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                   placeholder="-20.1">
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">dBm</span>
                        </div>
                        @error('potenciaEquipo')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 bb. Confirmación de Prueba de Canales (técnico)
                 cc. Registro de la Cantidad de canales digitales detectados (técnico)
                 — En INSTALACION siempre (spec Internet la incluye); en otros tipos solo si tiene TV
            ═══════════════════════════════════════════════════════ --}}
            @if($esInstalacion || $esCambioServicio || $esReconexion || $esReconexionCambio || ($tieneTV && ($esFalla || $esCambioDomicilio || $esServicioAdicional)))
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-violet-50 border-b border-violet-100 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-tv-2-line text-violet-600 text-sm"></i>
                    <p class="text-[11px] font-black text-violet-800 uppercase tracking-widest">
                        Verificación de Televisión — Mininodo
                    </p>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            Confirmación de Prueba de Canales
                        </label>
                        <label class="flex items-center gap-2.5 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 cursor-pointer hover:bg-violet-50 hover:border-violet-200 transition-colors">
                            <input type="checkbox" wire:model="pruebaCanalesOk" class="h-4 w-4 text-violet-600 rounded focus:ring-0">
                            <span class="text-[10px] font-black text-gray-700 uppercase tracking-widest">Prueba superada ✓</span>
                        </label>
                    </div>
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            Registro de la Cantidad de Canales Digitales Detectados
                        </label>
                        <div class="flex items-center gap-3">
                            <input type="number" wire:model="cantidadCanales"
                                   class="w-28 py-2.5 px-4 text-sm font-black text-center bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500/20"
                                   placeholder="0">
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">canales</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 j. Conexión de ONU:
                    i. Confirmar si encendió la ONU al Conectar
                   ii. Confirmar que el PON esté en verde
                  iii. Confirmar que puede Conectarse a la Red WIFI
                   iv. Confirmar los megas en la prueba de Velocidad
                 k. Registrar la Asignación de OLT (catálogo / SUCURSAL)
                 l. Registrar la Asignación de PON (catálogo / SUCURSAL)
                 m. Registrar la Dirección IP asignada (sucursal)
                 n. Confirmar actualización del nombre del cliente en Winbox
                 o. Confirmar asignación del plan de datos en Winbox (sucursal)
                 p. Confirmar actualización de datos del cliente en la OLT (sucursal)
                 — Solo si tiene Internet
            ═══════════════════════════════════════════════════════ --}}
            @if($tieneInternet && ($esFalla || $esInstalacion || $esCambioDomicilio || $esCambioServicio || $esReconexion || $esReconexionCambio))
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-sky-50 border-b border-sky-100 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-wifi-line text-sky-600 text-sm"></i>
                    <p class="text-[11px] font-black text-sky-800 uppercase tracking-widest">
                        Verificacion de ONU / Internet
                    </p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- j. Conexión de ONU — 4 confirmaciones del técnico --}}
                    <div>
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">
                            Conexion de ONU — Confirmaciones del Técnico
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach([
                                ['model' => 'onuEncendio',    'label' => 'ONU Encendió al Conectar',  'icon' => 'ri-power-line'],
                                ['model' => 'ponVerde',       'label' => 'PON en Verde',             'icon' => 'ri-signal-wifi-3-line'],
                                ['model' => 'wifiConecta',    'label' => 'Conecta a la Red WIFI',   'icon' => 'ri-wifi-line'],
                                ['model' => 'accesoInternet', 'label' => 'Prueba de Velocidad OK',   'icon' => 'ri-global-line'],
                            ] as $ck)
                            <label class="flex flex-col items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl p-3 cursor-pointer hover:bg-sky-50 hover:border-sky-200 transition-colors group text-center">
                                <input type="checkbox" wire:model="{{ $ck['model'] }}" class="h-5 w-5 text-sky-600 rounded focus:ring-0">
                                <i class="{{ $ck['icon'] }} text-gray-300 group-hover:text-sky-400 transition-colors"></i>
                                <span class="text-[9px] font-black text-gray-600 uppercase tracking-widest leading-tight">{{ $ck['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                        {{-- iv. Megas en la prueba de velocidad --}}
                        <div class="mt-3 space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                Megas Registrados en la Prueba de Velocidad
                            </label>
                            <div class="relative w-48">
                                <input type="number" wire:model="velocidadRegistrada"
                                       class="w-full pl-4 pr-14 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-black font-mono focus:ring-2 focus:ring-sky-500/20"
                                       placeholder="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">Mbps</span>
                            </div>
                        </div>
                    </div>

                    {{-- Falla: verifica red WiFi --}}
                    @if($esFalla)
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 border-t border-gray-100 pt-4">
                        <label class="flex items-center gap-2.5 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 cursor-pointer hover:bg-sky-50 transition-colors">
                            <input type="checkbox" wire:model="detectoWifiOriginal" class="h-4 w-4 text-sky-600 rounded focus:ring-0">
                            <span class="text-[10px] font-black text-gray-600 uppercase leading-tight">Detectó WIFI original</span>
                        </label>
                        <label class="flex items-center gap-2.5 bg-gray-50 border border-gray-200 rounded-lg px-3 py-2.5 cursor-pointer hover:bg-sky-50 transition-colors">
                            <input type="checkbox" wire:model="configuroWifiDefault" class="h-4 w-4 text-sky-600 rounded focus:ring-0">
                            <span class="text-[10px] font-black text-gray-600 uppercase leading-tight">Configuró WIFI default</span>
                        </label>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">Nueva contraseña asignada</label>
                            <input type="text" wire:model="asignoNuevaPass"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-xs font-bold font-mono focus:ring-2 focus:ring-sky-500/20"
                                   placeholder="Opcional">
                        </div>
                    </div>
                    @endif

                    {{-- k · l · m. OLT / PON / IP — Sucursal --}}
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-3">
                            Asignaciones de Red — Sucursal
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                    Asignación de OLT (catálogo)
                                </label>
                                <select wire:model="oltAsignada"
                                        class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-3 text-xs font-black uppercase focus:ring-2 focus:ring-sky-500/20">
                                    <option value="">— Catálogo —</option>
                                    @foreach($catalogoOlts as $olt)
                                    <option value="{{ $olt['id'] }}">{{ $olt['nombre'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                    Asignación de PON
                                </label>
                                <input type="text" wire:model="ponAsignado"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-3 font-mono text-xs font-black uppercase focus:ring-2 focus:ring-sky-500/20"
                                       placeholder="PON/0/1">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                    Dirección IP Asignada (sucursal)
                                </label>
                                <input type="text" wire:model="ipAsignada"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-3 font-mono text-xs font-black focus:ring-2 focus:ring-sky-500/20"
                                       placeholder="192.168.x.x">
                            </div>
                        </div>

                        {{-- n · o · p. Confirmaciones Winbox / OLT --}}
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mt-3">
                            @foreach([
                                ['model' => 'actualizoWinbox',  'label' => 'Actualización del nombre del cliente en Winbox'],
                                ['model' => 'asignoPlanWinbox', 'label' => 'Asignación del plan de datos en Winbox (sucursal)'],
                                ['model' => 'actualizoOLT',     'label' => 'Actualización de datos del cliente en la OLT (sucursal)'],
                            ] as $sc)
                            <label class="flex items-center gap-2.5 bg-indigo-50 border border-indigo-100 rounded-lg px-3 py-2.5 cursor-pointer hover:bg-indigo-100 transition-colors">
                                <input type="checkbox" wire:model="{{ $sc['model'] }}" class="h-4 w-4 text-indigo-600 rounded focus:ring-0 flex-shrink-0">
                                <span class="text-[9px] font-black text-indigo-700 uppercase tracking-widest leading-tight">{{ $sc['label'] }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 [SUSPENSIÓN] Ejecución de la desconexión
                 Reglas:
                  · Solo TV (mininodo)      → siempre FÍSICA en NAP
                  · Solo Internet (ONU)     → siempre LÓGICA (Winbox + OLT)
                  · TV+Internet remoto=true → LÓGICA (sucursal corta por OLT)
                  · TV+Internet remoto=false→ FÍSICA en NAP (técnico en campo)
            ═══════════════════════════════════════════════════════ --}}
            @if($esSuspension)
            @php
                $soloTV        = $tieneTV && !$tieneInternet;
                $soloInternet  = $tieneInternet && !$tieneTV;
                $comboRemoto   = $tieneTV && $tieneInternet && ($reporte['soporta_remoto'] ?? false);
                $comboFisico   = $tieneTV && $tieneInternet && !($reporte['soporta_remoto'] ?? false);
                $usaLogica     = $soloInternet || $comboRemoto;
                $usaFisica     = $soloTV || $comboFisico;
            @endphp

            {{-- ─────────────────────────────────────────────────────────
                 BLOQUE A: Datos de la suspensión (automáticos)
            ───────────────────────────────────────────────────────── --}}
            <div class="bg-white border border-red-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-red-600 px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ri-alarm-warning-line text-white text-base"></i>
                        <p class="text-[11px] font-black text-white uppercase tracking-widest">Suspensión por Falta de Pago</p>
                    </div>
                    <span class="text-[9px] font-black px-2.5 py-1 rounded uppercase
                        {{ $usaLogica && !$usaFisica ? 'bg-blue-100 text-blue-800' : ($usaFisica && !$usaLogica ? 'bg-amber-100 text-amber-800' : 'bg-violet-100 text-violet-800') }}">
                        {{ $usaLogica && !$usaFisica ? 'Corte Lógico — Sucursal' : ($usaFisica && !$usaLogica ? 'Corte Físico — Técnico en Campo' : 'Lógico + Físico') }}
                    </span>
                </div>
                <div class="p-5 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-red-50 border border-red-100 rounded-lg p-3 text-center">
                        <p class="text-[8px] font-bold text-red-400 uppercase tracking-widest mb-1">Estado del Suscriptor</p>
                        <p class="text-xs font-black text-red-700 uppercase">{{ $reporte['estado_cliente'] }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-100 rounded-lg p-3 text-center">
                        <p class="text-[8px] font-bold text-red-400 uppercase tracking-widest mb-1">Días en Mora</p>
                        <p class="text-xl font-black text-red-700">{{ $reporte['dias_suspension'] ?? '—' }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-100 rounded-lg p-3 text-center">
                        <p class="text-[8px] font-bold text-red-400 uppercase tracking-widest mb-1">Saldo Pendiente</p>
                        <p class="text-sm font-black text-red-700">${{ number_format($reporte['saldo_pendiente'] ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-center">
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Técnico Asignado</p>
                        <p class="text-[10px] font-black text-gray-700 uppercase">{{ $reporte['tecnico'] }}</p>
                    </div>
                </div>

                {{-- Infraestructura NAP --}}
                <div class="border-t border-red-100 px-5 py-3 flex items-center gap-3">
                    <i class="ri-signal-tower-line text-red-400 text-base flex-shrink-0"></i>
                    <div>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">NAP — Infraestructura</p>
                        <p class="text-xs font-black text-gray-800 uppercase">{{ $reporte['nap'] }}
                            <span class="text-gray-400 font-normal normal-case ml-1">— {{ $reporte['dir_nap'] }}</span>
                        </p>
                    </div>
                    <div class="ml-auto text-right">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Equipo Asignado</p>
                        <p class="text-[10px] font-black text-gray-800">{{ $reporte['info_equipo'] }}</p>
                    </div>
                </div>
            </div>

            {{-- ─────────────────────────────────────────────────────────
                 BLOQUE B: Cierre Técnico — Acciones del técnico
            ───────────────────────────────────────────────────────── --}}
            <div class="bg-white border {{ $tecnicoCompletado ? 'border-emerald-300' : 'border-gray-200' }} rounded-xl shadow-sm overflow-hidden">
                <div class="{{ $tecnicoCompletado ? 'bg-emerald-50 border-b border-emerald-200' : 'bg-gray-50 border-b border-gray-200' }} px-5 py-3.5 flex items-center gap-2">
                    @if($tecnicoCompletado)
                    <i class="ri-checkbox-circle-fill text-emerald-600 text-base"></i>
                    <p class="text-[11px] font-black text-emerald-800 uppercase tracking-widest">Cierre Técnico — Completado</p>
                    @else
                    <i class="ri-tools-line text-gray-600 text-base"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Cierre Técnico — Acciones del Técnico</p>
                    @endif
                </div>
                <div class="p-5 space-y-4 {{ $tecnicoCompletado ? 'opacity-60 pointer-events-none' : '' }}">

                    {{-- Desconexión LÓGICA: Solo Internet o TV+Internet con remoto --}}
                    @if($usaLogica)
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-4">
                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest mb-1">Desconexión Lógica — Sucursal</p>
                        <p class="text-[9px] text-blue-500 font-medium mb-3">
                            {{ $comboRemoto ? 'ONU soporta corte remoto. La sucursal corta el servicio desde el sistema.' : 'Servicio de Internet — corte lógico vía Winbox y OLT.' }}
                        </p>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-2.5 bg-white border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                                <input type="checkbox" wire:model="confirmacionWinbox" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <div>
                                    <p class="text-xs font-black text-gray-800 uppercase">Desconexión confirmada en Winbox</p>
                                    <p class="text-[10px] text-gray-500">Corte lógico en el gestor de red MikroTik</p>
                                </div>
                            </label>
                            @error('confirmacionWinbox')<p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p>@enderror
                            <label class="flex items-center gap-3 p-2.5 bg-white border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                                <input type="checkbox" wire:model="confirmacionOLT" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <div>
                                    <p class="text-xs font-black text-gray-800 uppercase">Puerto bloqueado en OLT</p>
                                    <p class="text-[10px] text-gray-500">Sesión detenida en la OLT asignada</p>
                                </div>
                            </label>
                            @error('confirmacionOLT')<p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    @endif

                    {{-- Desconexión FÍSICA: Solo TV o TV+Internet sin remoto --}}
                    @if($usaFisica)
                    <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                        <p class="text-[9px] font-black text-red-600 uppercase tracking-widest mb-1">Desconexión Física en NAP — Técnico en Campo</p>
                        <p class="text-[9px] text-red-500 font-medium mb-3">
                            {{ $soloTV ? 'Servicio de TV (mininodo) — desconexión física obligatoria en NAP.' : 'ONU sin función remota — técnico debe desconectar físicamente en la NAP.' }}
                        </p>
                        <label class="flex items-center gap-3 p-2.5 bg-white border border-red-200 rounded-lg cursor-pointer hover:bg-red-50 transition-colors mb-3">
                            <input type="checkbox" wire:model="confirmacionDesconexionFisica" class="h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                            <div>
                                <p class="text-xs font-black text-gray-800 uppercase">Desconexión física confirmada en NAP</p>
                                <p class="text-[10px] text-gray-500">Técnico retiró el conector de la salida NAP</p>
                            </div>
                        </label>
                        @error('confirmacionDesconexionFisica')<p class="text-[10px] text-red-500 font-bold ml-1 -mt-2 mb-2">{{ $message }}</p>@enderror
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-red-600 uppercase tracking-widest">Confirmar salida NAP liberada</label>
                            <input type="text" wire:model="salidaNapLibre"
                                   class="w-full bg-white border border-red-200 rounded-lg py-2.5 px-4 text-sm font-black uppercase focus:ring-2 focus:ring-red-400/30"
                                   placeholder="Ej: Salida #4">
                            <p class="text-[9px] text-gray-400">El inventario de salidas NAP se actualizará al confirmar el cierre</p>
                        </div>
                    </div>
                    @endif

                    {{-- Horas transcurridas (automático) --}}
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-3.5 flex items-center gap-3">
                        <i class="ri-time-line text-gray-400 text-base flex-shrink-0"></i>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Horas transcurridas desde la apertura</p>
                            <p class="text-sm font-black text-gray-800">{{ $horasAtencion }} <span class="text-[10px] font-medium text-gray-400 normal-case">hrs. desde la generación del reporte</span></p>
                        </div>
                    </div>

                    {{-- Calificación del Suscriptor --}}
                    <div class="space-y-1.5">
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">Calificación del Suscriptor</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border cursor-pointer transition-all
                                {{ $calificacion === 'Excelente' ? 'bg-emerald-50 border-emerald-400 text-emerald-700' : 'bg-white border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                <input type="radio" wire:model="calificacion" value="Excelente" class="sr-only">
                                <i class="ri-emotion-happy-line text-sm"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider">Excelente</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border cursor-pointer transition-all
                                {{ $calificacion === 'Bueno' ? 'bg-blue-50 border-blue-400 text-blue-700' : 'bg-white border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                <input type="radio" wire:model="calificacion" value="Bueno" class="sr-only">
                                <i class="ri-emotion-normal-line text-sm"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider">Bueno</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border cursor-pointer transition-all
                                {{ $calificacion === 'Malo' ? 'bg-red-50 border-red-400 text-red-700' : 'bg-white border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                <input type="radio" wire:model="calificacion" value="Malo" class="sr-only">
                                <i class="ri-emotion-unhappy-line text-sm"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider">Malo</span>
                            </label>
                        </div>
                    </div>

                    {{-- Notas técnicas --}}
                    <div class="space-y-1.5">
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">Notas del técnico (opcional)</label>
                        <textarea wire:model="notasSuspension" rows="2"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-medium resize-none focus:ring-2 focus:ring-red-500/20 focus:border-red-400"
                                  placeholder="Observaciones de la desconexión..."></textarea>
                    </div>

                </div>

                {{-- Botón: Guardar Avance Técnico --}}
                @if(!$tecnicoCompletado)
                <div class="border-t border-gray-200 px-5 py-4">
                    @error('tecnicoCompletado')
                    <p class="text-[10px] text-red-500 font-black mb-3 flex items-center gap-1.5">
                        <i class="ri-error-warning-line"></i> {{ $message }}
                    </p>
                    @enderror
                    <button wire:click="guardarAvanceSuspension"
                            class="w-full py-3 bg-gray-800 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-gray-900 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-save-3-line text-base"></i> Guardar Avance Técnico
                    </button>
                    <p class="text-center text-[9px] text-gray-400 font-medium mt-2">
                        Esto habilitará el Cierre Administrativo. Las acciones técnicas quedarán registradas.
                    </p>
                </div>
                @else
                <div class="border-t border-emerald-200 px-5 py-3 bg-emerald-50 flex items-center gap-2">
                    <i class="ri-checkbox-circle-fill text-emerald-600 text-base"></i>
                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Acciones técnicas registradas correctamente</p>
                </div>
                @endif
            </div>

            {{-- ─────────────────────────────────────────────────────────
                 BLOQUE C: Cierre Administrativo (requiere técnico completado)
            ───────────────────────────────────────────────────────── --}}
            <div class="bg-white border {{ $tecnicoCompletado ? 'border-red-300' : 'border-gray-200 opacity-50' }} rounded-xl shadow-sm overflow-hidden">
                <div class="{{ $tecnicoCompletado ? 'bg-red-600' : 'bg-gray-200' }} px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-shield-check-line {{ $tecnicoCompletado ? 'text-white' : 'text-gray-500' }} text-base"></i>
                    <p class="text-[11px] font-black {{ $tecnicoCompletado ? 'text-white' : 'text-gray-500' }} uppercase tracking-widest">
                        Cierre Administrativo — Sucursal
                    </p>
                    @if(!$tecnicoCompletado)
                    <span class="ml-auto text-[9px] font-black bg-gray-300 text-gray-600 px-2 py-0.5 rounded uppercase">
                        Requiere cierre técnico primero
                    </span>
                    @endif
                </div>

                @if($tecnicoCompletado)
                <div class="p-5 space-y-4">
                    {{-- Resumen de acciones del sistema --}}
                    <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                        <p class="text-[9px] font-black text-red-700 uppercase tracking-widest mb-3">Acciones del sistema al confirmar</p>
                        <ul class="space-y-1.5">
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-user-forbid-line text-red-500"></i>
                                Cambiar estado del suscriptor → <span class="text-red-600 font-black">SUSPENDIDO</span>
                            </li>
                            @if($usaFisica && $salidaNapLibre)
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-signal-tower-line text-red-500"></i>
                                Liberar salida <span class="font-mono font-black text-gray-900">{{ $salidaNapLibre }}</span> en {{ $reporte['nap'] }}
                            </li>
                            @elseif($usaFisica)
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-signal-tower-line text-red-500"></i>
                                Actualizar inventario de salidas en {{ $reporte['nap'] }}
                            </li>
                            @endif
                            @if($usaLogica)
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-router-line text-red-500"></i>
                                Liberar puertos y sesiones lógicas de red
                            </li>
                            @endif
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-pause-circle-line text-amber-500"></i>
                                Pausar ciclo de facturación — no generar cargos mientras esté suspendido
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-message-2-line text-indigo-500"></i>
                                Enviar SMS de notificación al suscriptor
                            </li>
                        </ul>
                    </div>

                    {{-- Resumen cierre técnico --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-center">
                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Calificación del Suscriptor</p>
                            <p class="text-xs font-black
                                {{ $calificacion === 'Excelente' ? 'text-emerald-700' : ($calificacion === 'Bueno' ? 'text-blue-700' : 'text-red-700') }}">
                                {{ $calificacion }}
                            </p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-center">
                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tiempo de Atención</p>
                            <p class="text-xs font-black text-gray-700">{{ $horasAtencion }} hrs.</p>
                        </div>
                    </div>

                    {{-- Regla de negocio: facturación --}}
                    <div class="flex items-start gap-3 bg-amber-50 border border-amber-100 rounded-lg p-3.5">
                        <i class="ri-information-line text-amber-600 text-base flex-shrink-0 mt-0.5"></i>
                        <p class="text-[10px] font-medium text-amber-800 leading-relaxed">
                            <strong>Regla de negocio:</strong> Los periodos suspendidos <strong>no generan cargos de mensualidad</strong>. La facturación se reactiva automáticamente al generar el reporte de reconexión y liquidar el adeudo.
                        </p>
                    </div>

                    <button @click="$confirm('¿Confirmar cierre administrativo? El suscriptor quedará SUSPENDIDO y se liberarán los recursos de red.', () => $wire.cerrarSuspension(), { confirmText: 'Sí, aplicar suspensión', title: 'Cierre Administrativo', icon: 'warning' })"
                            class="w-full py-3.5 bg-red-600 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-red-700 shadow-md shadow-red-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-pause-circle-line text-base"></i> Aplicar Suspensión — Cierre Total
                    </button>
                </div>
                @else
                <div class="p-6 text-center">
                    <i class="ri-lock-line text-3xl text-gray-300"></i>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mt-2">
                        Complete y guarde las acciones técnicas para habilitar el cierre administrativo
                    </p>
                </div>
                @endif
            </div>

            {{-- Enlace volver --}}
            <div class="flex justify-start">
                <a href="{{ route('reportes.servicio') }}"
                   class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Volver a bandeja
                </a>
            </div>

            @endif

            {{-- ═══════════════════════════════════════════════════════
                 [CANCELACIÓN] Recuperación del equipo
            ═══════════════════════════════════════════════════════ --}}
            @if($esCancelacion)
            {{-- ── Todo el bloque de cancelación usa Alpine local — CERO wire:model.live / $wire.set ── --}}
            <div x-init="equipoInfo = @json($reporte['info_equipo'] ?? '')" x-data="{
                recupera: '',
                serie: '',
                equipoInfo: '',
                get serieMatch() { return this.serie.length > 0 && this.equipoInfo.toUpperCase().includes(this.serie.toUpperCase()); },
                get serieDiff()  { return this.serie.length > 0 && !this.equipoInfo.toUpperCase().includes(this.serie.toUpperCase()); },
                pagoPerdida: false,
                desconFisica: false,
                bdWinbox: false,
                bdOLT: false,
                calificacion: 'Excelente',
                precierreMotivo: '',
                precierreError: ''
            }" class="space-y-4">

            {{-- ─── 1. Datos e Infraestructura (read-only) ─── --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-900 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-close-circle-line text-red-400 text-base"></i>
                    <p class="text-[11px] font-black text-gray-200 uppercase tracking-widest">Cancelación de Servicio — Datos de la Instalación</p>
                </div>
                <div class="p-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center">
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Servicio</p>
                        <p class="text-[10px] font-black text-gray-800 uppercase leading-tight">{{ $reporte['servicio'] ?? '—' }}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center">
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">NAP Conectada</p>
                        <p class="text-[10px] font-black text-gray-800 uppercase">{{ $reporte['nap'] }}</p>
                        <p class="text-[9px] text-gray-400 italic mt-0.5 leading-tight">{{ $reporte['dir_nap'] }}</p>
                    </div>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center sm:col-span-2">
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Equipo Asignado</p>
                        <p class="font-mono text-[10px] font-black text-gray-800">{{ $reporte['info_equipo'] }}</p>
                    </div>
                </div>
                @if($tieneInternet)
                <div class="border-t border-gray-100 px-4 py-3 flex flex-wrap gap-x-6 gap-y-1">
                    <div>
                        <p class="text-[8px] font-bold text-gray-400 uppercase">IP Asignada</p>
                        <p class="font-mono text-[10px] font-black text-gray-700">{{ $reporte['ip'] ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[8px] font-bold text-gray-400 uppercase">OLT / PON</p>
                        <p class="font-mono text-[10px] font-black text-gray-700">{{ ($reporte['olt'] ?? '—') }} / {{ ($reporte['pon'] ?? '—') }}</p>
                    </div>
                </div>
                @endif
            </div>

            {{-- ─── 2. Recuperación del Equipo en Comodato ─── --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-router-line text-gray-600 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Recuperación del Equipo en Comodato</p>
                    <span class="ml-auto text-[9px] font-bold text-red-600 bg-red-50 border border-red-100 px-1.5 py-0.5 rounded uppercase">Requerido para Cierre</span>
                </div>
                <div class="p-5 space-y-4">

                    {{-- Radio SI / NO — Alpine local, sin $wire.set --}}
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="recupera = 'si'; pagoPerdida = false">
                            <div class="text-center border-2 rounded-xl p-3.5 transition-all cursor-pointer"
                                 :class="recupera === 'si' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-emerald-200'">
                                <i class="ri-checkbox-circle-line block text-2xl mb-1"
                                   :class="recupera === 'si' ? 'text-emerald-500' : 'text-gray-300'"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest"
                                   :class="recupera === 'si' ? 'text-emerald-700' : 'text-gray-400'">SÍ — Recuperar Equipo</p>
                                <p class="text-[9px] mt-0.5"
                                   :class="recupera === 'si' ? 'text-emerald-600' : 'text-gray-300'">Regresa al inventario</p>
                            </div>
                        </button>
                        <button type="button" @click="recupera = 'no'; serie = ''">
                            <div class="text-center border-2 rounded-xl p-3.5 transition-all cursor-pointer"
                                 :class="recupera === 'no' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-200'">
                                <i class="ri-close-circle-line block text-2xl mb-1"
                                   :class="recupera === 'no' ? 'text-red-500' : 'text-gray-300'"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest"
                                   :class="recupera === 'no' ? 'text-red-700' : 'text-gray-400'">NO — No Entrega</p>
                                <p class="text-[9px] mt-0.5"
                                   :class="recupera === 'no' ? 'text-red-600' : 'text-gray-300'">Pago por pérdida</p>
                            </div>
                        </button>
                    </div>
                    @error('cerrarCancelacion')
                    <p class="text-[10px] text-red-500 font-black flex items-center gap-1"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                    @enderror

                    {{-- CASO: SÍ recupera → confirmar serie --}}
                    <div x-show="recupera === 'si'" x-cloak class="space-y-3">
                        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 space-y-3">
                            <p class="text-[9px] font-black text-emerald-700 uppercase tracking-widest">Validar serie del equipo recuperado</p>
                            <p class="font-mono text-sm font-black text-indigo-700 bg-white border border-indigo-100 px-4 py-2 rounded-lg">
                                {{ $reporte['info_equipo'] }}
                            </p>
                            <div class="space-y-1.5">
                                <label class="block text-[9px] font-black text-gray-600 uppercase tracking-widest">Serie física recuperada *</label>
                                <input type="text" x-model="serie"
                                       class="w-full bg-white border border-emerald-300 rounded-lg py-2.5 px-4 text-sm font-black font-mono uppercase tracking-widest text-indigo-700 focus:ring-2 focus:ring-emerald-500/20"
                                       placeholder="ESCANEAR O ESCRIBIR SERIE...">
                            </div>
                            <div x-show="serieMatch" x-cloak class="flex items-center gap-2 text-[10px] font-black text-emerald-700">
                                <i class="ri-checkbox-circle-fill text-emerald-500"></i> Serie confirmada — coincide con el registro ✓
                            </div>
                            <div x-show="serieDiff" x-cloak class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                <p class="text-[10px] font-black text-amber-800 flex items-center gap-1.5">
                                    <i class="ri-alert-line text-amber-600"></i> Serie diferente — se buscará en el sistema y se reasignará al suscriptor antes de registrar la recuperación.
                                </p>
                            </div>
                            <p class="text-[9px] text-gray-400">Al confirmar: el equipo ingresa al inventario de sucursal con validación.</p>
                        </div>

                        <label class="flex items-start gap-3 cursor-pointer p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                            <input type="checkbox" x-model="desconFisica" class="mt-0.5 h-5 w-5 text-red-600 rounded focus:ring-red-500 flex-shrink-0">
                            <div>
                                <p class="text-[10px] font-black text-red-900 uppercase">Desconexión física confirmada en NAP</p>
                                <p class="text-[9px] text-red-600 mt-0.5">El servicio fue físicamente desconectado. La salida NAP quedará disponible al cerrar.</p>
                            </div>
                        </label>
                    </div>

                    {{-- CASO: NO recupera → pago por pérdida --}}
                    <div x-show="recupera === 'no'" x-cloak class="space-y-3">
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 space-y-3">
                            <p class="text-[9px] font-black text-red-700 uppercase tracking-widest">Equipo no entregado — resolución requerida</p>
                            <label class="flex items-start gap-3 cursor-pointer p-3 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                <input type="checkbox" x-model="pagoPerdida" class="mt-0.5 h-5 w-5 text-red-600 rounded focus:ring-red-500 flex-shrink-0">
                                <div>
                                    <p class="text-[10px] font-black text-red-900 uppercase">Pago por pérdida del equipo confirmado</p>
                                    <p class="text-[9px] text-red-600 mt-0.5">El suscriptor pagó el valor del equipo en comodato. Ingreso registrado en caja.</p>
                                </div>
                            </label>
                            <div x-show="!pagoPerdida"
                                 class="flex items-start gap-2 bg-amber-50 border border-amber-100 rounded-lg p-3">
                                <i class="ri-information-line text-amber-600 text-sm flex-shrink-0"></i>
                                <p class="text-[10px] text-amber-700 font-bold">
                                    Si el suscriptor no entrega ni paga, use <strong>Guardar Precierre</strong> con el motivo correspondiente. El reporte no podrá cerrarse hasta resolver la situación del equipo.
                                </p>
                            </div>
                        </div>

                        <label class="flex items-start gap-3 cursor-pointer p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                            <input type="checkbox" x-model="desconFisica" class="mt-0.5 h-5 w-5 text-red-600 rounded focus:ring-red-500 flex-shrink-0">
                            <div>
                                <p class="text-[10px] font-black text-red-900 uppercase">Desconexión física confirmada en NAP</p>
                                <p class="text-[9px] text-red-600 mt-0.5">La acometida y el punto de conexión en NAP fueron liberados.</p>
                            </div>
                        </label>
                    </div>

                </div>
            </div>

            {{-- ─── 3. Baja de Red (solo si tenía Internet) ─── --}}
            @if($tieneInternet)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-indigo-50 border-b border-indigo-100 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-server-line text-indigo-600 text-sm"></i>
                    <p class="text-[11px] font-black text-indigo-800 uppercase tracking-widest">Baja de Red — Sucursal</p>
                </div>
                <div class="p-5 space-y-2.5">
                    <label class="flex items-start gap-3 cursor-pointer p-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-200 transition-colors">
                        <input type="checkbox" x-model="bdWinbox" class="mt-0.5 h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 flex-shrink-0">
                        <div>
                            <p class="text-[10px] font-black text-gray-800 uppercase">Baja del nombre del suscriptor en Winbox</p>
                            <p class="text-[9px] text-gray-500 mt-0.5">Perfil y plan de datos eliminados del gestor de red</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 cursor-pointer p-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-indigo-50 hover:border-indigo-200 transition-colors">
                        <input type="checkbox" x-model="bdOLT" class="mt-0.5 h-5 w-5 text-indigo-600 rounded focus:ring-indigo-500 flex-shrink-0">
                        <div>
                            <p class="text-[10px] font-black text-gray-800 uppercase">Baja del suscriptor en OLT</p>
                            <p class="text-[9px] text-gray-500 mt-0.5">Puerto y configuración eliminados de la OLT</p>
                        </div>
                    </label>
                </div>
            </div>
            @endif

            {{-- ─── 4. Calificación + Precierre ─── --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-star-line text-amber-500 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Métricas y Cierre</p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- Calificación — Alpine local, sin wire:model.live --}}
                    <div class="grid grid-cols-3 gap-2">
                        @foreach(['Excelente','Bueno','Malo'] as $cal)
                        <button type="button" @click="calificacion = '{{ $cal }}'">
                            <div :class="calificacion === '{{ $cal }}' ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200 hover:border-indigo-200'"
                                 class="text-center border-2 rounded-xl p-2.5 transition-all">
                                <p :class="calificacion === '{{ $cal }}' ? 'text-indigo-700' : 'text-gray-400'"
                                   class="text-[10px] font-black uppercase tracking-widest">{{ $cal }}</p>
                            </div>
                        </button>
                        @endforeach
                    </div>

                    {{-- Precierre — motivos específicos de cancelación --}}
                    <div class="border border-amber-200 bg-amber-50 rounded-lg overflow-hidden">
                        <div class="bg-amber-100 border-b border-amber-200 px-4 py-2.5 flex items-center gap-2">
                            <i class="ri-save-3-line text-amber-700 text-sm"></i>
                            <p class="text-[10px] font-black text-amber-800 uppercase tracking-widest">Precierre — No se Puede Cerrar Aún</p>
                        </div>
                        <div class="p-4 space-y-2.5">
                            <select x-model="precierreMotivo" @change="precierreError = ''"
                                    class="w-full bg-white border border-amber-300 rounded-lg py-2.5 px-3 text-xs font-black uppercase focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400">
                                <option value="">— Seleccione motivo —</option>
                                <option value="NO_ENTREGA_EQUIPO">Suscriptor no entrega el equipo</option>
                                <option value="NO_PAGA_PERDIDA">Suscriptor no paga el equipo perdido</option>
                                <option value="PENDIENTE_FIRMA">Pendiente de firma de cancelación</option>
                                <option value="OTRO">Otro — ver observaciones</option>
                            </select>
                            <p x-show="precierreError" x-text="precierreError" x-cloak
                               class="text-[10px] text-red-500 font-bold"></p>
                            @error('precierreCancel')
                            <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                            @enderror
                            <button type="button"
                                    @click="if(!precierreMotivo){ precierreError='Seleccione el motivo del precierre.'; return; } $wire.guardarPrecierreCancel(precierreMotivo)"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-amber-600 text-white rounded-lg font-black text-[10px] uppercase tracking-widest hover:bg-amber-700 transition-all active:scale-95">
                                <i class="ri-save-3-line text-sm"></i> Guardar Precierre
                            </button>
                        </div>
                    </div>

                    {{-- Cierre Administrativo --}}
                    <div class="border border-gray-900 rounded-lg overflow-hidden">
                        <div class="bg-gray-900 px-4 py-2.5 flex items-center gap-2">
                            <i class="ri-close-circle-line text-red-400 text-sm"></i>
                            <p class="text-[10px] font-black text-gray-200 uppercase tracking-widest">Cierre Administrativo — Cancelación</p>
                        </div>
                        <div class="p-4 space-y-3">
                            <div class="space-y-1.5 text-[10px] text-gray-600">
                                <p class="flex items-center gap-2"><i class="ri-checkbox-circle-line text-emerald-500"></i> Equipo recuperado físicamente en sucursal, <strong>o</strong></p>
                                <p class="flex items-center gap-2"><i class="ri-checkbox-circle-line text-emerald-500"></i> Pago por pérdida del equipo confirmado</p>
                            </div>
                            <button type="button"
                                    @click="$confirm('¿Confirmar la cancelación definitiva del servicio?', () => $wire.cerrarCancelacion(recupera, serie, pagoPerdida, desconFisica, bdWinbox, bdOLT, calificacion), { confirmText: 'Sí, cancelar servicio', icon: 'warning' })"
                                    class="w-full py-3 bg-gray-900 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-black shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                                <i class="ri-close-circle-line text-base"></i> Confirmar Cancelación del Servicio
                            </button>
                            <p class="text-center text-[9px] text-gray-400 font-medium uppercase tracking-widest">
                                Estado → CANCELADO · NAP liberada · Inventario actualizado · SMS al suscriptor
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            </div>{{-- /x-data cancelacion --}}
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 [RECUPERACIÓN DE EQUIPO] Por adeudo >61 días
            ═══════════════════════════════════════════════════════ --}}
            @if($esRecuperacion)

            {{-- ─── Datos de la recuperación (automáticos) ─────────────── --}}
            <div class="bg-white border border-amber-300 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-amber-600 px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ri-arrow-down-circle-line text-white text-base"></i>
                        <p class="text-[11px] font-black text-white uppercase tracking-widest">Recuperación de Equipo por Morosidad</p>
                    </div>
                    <span class="text-[9px] font-black bg-red-100 text-red-800 px-2.5 py-1 rounded uppercase">
                        {{ $reporte['dias_suspension'] ?? '—' }} días de adeudo
                    </span>
                </div>
                <div class="p-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <div class="bg-amber-50 border border-amber-100 rounded-lg p-3 text-center">
                        <p class="text-[8px] font-bold text-amber-500 uppercase tracking-widest mb-1">Estado del Suscriptor</p>
                        <p class="text-[10px] font-black text-amber-800 uppercase leading-tight">{{ $reporte['estado_cliente'] }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-100 rounded-lg p-3 text-center">
                        <p class="text-[8px] font-bold text-red-400 uppercase tracking-widest mb-1">Saldo Adeudo</p>
                        <p class="text-sm font-black text-red-700">${{ number_format($reporte['saldo_pendiente'] ?? 0, 2) }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-100 rounded-lg p-3 text-center">
                        <p class="text-[8px] font-bold text-red-400 uppercase tracking-widest mb-1">Último Pago</p>
                        <p class="text-[10px] font-black text-red-700">
                            {{ isset($reporte['fecha_ultimo_pago']) ? \Carbon\Carbon::parse($reporte['fecha_ultimo_pago'])->format('d/m/Y') : '—' }}
                        </p>
                    </div>
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-center">
                        <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Días en Mora</p>
                        <p class="text-xl font-black text-red-700">{{ $reporte['dias_suspension'] ?? '—' }}</p>
                    </div>
                </div>
                <div class="border-t border-amber-100 px-4 py-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex items-center gap-3">
                        <i class="ri-signal-tower-line text-amber-400 text-base flex-shrink-0"></i>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">NAP — Infraestructura</p>
                            <p class="text-xs font-black text-gray-800 uppercase">{{ $reporte['nap'] }}
                                <span class="text-gray-400 font-normal normal-case ml-1">— {{ $reporte['dir_nap'] }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <i class="ri-router-line text-gray-400 text-base flex-shrink-0"></i>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Equipo Asignado</p>
                            <p class="text-[10px] font-black text-gray-800">{{ $reporte['info_equipo'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ─── A. DESCONEXIÓN FÍSICA ───────────────────────────────── --}}
            <div class="bg-white border {{ $tecnicoCompletadoRec ? 'border-emerald-300' : 'border-gray-200' }} rounded-xl shadow-sm">
                <div class="{{ $tecnicoCompletadoRec ? 'bg-emerald-50 border-b border-emerald-200' : 'bg-gray-50 border-b border-gray-200' }} px-5 py-3.5 flex items-center gap-2">
                    @if($tecnicoCompletadoRec)
                    <i class="ri-checkbox-circle-fill text-emerald-600 text-base"></i>
                    <p class="text-[11px] font-black text-emerald-800 uppercase tracking-widest">A. Desconexión Física — Completada</p>
                    @else
                    <i class="ri-tools-line text-gray-600 text-base"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">A. Desconexión Física en Campo</p>
                    @endif
                </div>
                <div class="p-5 space-y-5 {{ $tecnicoCompletadoRec ? 'opacity-60 pointer-events-none' : '' }}">

                    {{-- A.1 Desconexión Física en NAP --}}
                    <div class="space-y-3">
                        <p class="text-[9px] font-black text-red-600 uppercase tracking-widest flex items-center gap-1.5">
                            <i class="ri-tools-line"></i> A. Desconexión Física en Campo
                        </p>
                        <label class="flex items-center gap-3 cursor-pointer p-3 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors">
                            <input type="checkbox" wire:model.live="desconexionFisicaRec" class="h-5 w-5 text-red-600 rounded focus:ring-red-500 flex-shrink-0">
                            <div>
                                <p class="text-xs font-black text-gray-800 uppercase">Corte físico del servicio confirmado en NAP</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">El técnico desconectó el servicio en la infraestructura</p>
                            </div>
                        </label>
                        @error('desconexionFisicaRec')
                        <p class="text-[10px] text-red-500 font-black flex items-center gap-1"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                        @enderror
                        <label class="flex items-center gap-3 cursor-pointer p-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-red-50 hover:border-red-200 transition-colors">
                            <input type="checkbox" wire:model="acometidaLiberada" class="h-5 w-5 text-red-600 rounded focus:ring-red-500 flex-shrink-0">
                            <div>
                                <p class="text-xs font-black text-gray-800 uppercase">Liberación de acometida</p>
                                <p class="text-[10px] text-gray-500 mt-0.5">Cable de acometida retirado del domicilio</p>
                            </div>
                        </label>
                        <div class="space-y-1">
                            <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">Puerto NAP liberado</label>
                            <input type="text" wire:model="salidaNapLibreRec"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-black uppercase focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400"
                                   placeholder="Ej: Salida #4">
                            <p class="text-[9px] text-gray-400">El inventario de salidas NAP se actualizará al confirmar el cierre</p>
                        </div>
                    </div>

                    {{-- A.3 Bajas de red (solo Internet) --}}
                    @if($tieneInternet)
                    <div class="border-t border-gray-100 pt-4 space-y-3">
                        <p class="text-[9px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-1.5">
                            <i class="ri-computer-line"></i> C. Acciones Administrativas de Red — Internet
                        </p>
                        <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 flex items-center gap-3 mb-2">
                            <i class="ri-global-line text-blue-500 text-lg flex-shrink-0"></i>
                            <div>
                                <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest">IP · OLT asignados al suscriptor</p>
                                <p class="font-mono text-xs font-black text-gray-700">{{ $reporte['ip'] ?? '—' }} · {{ $reporte['olt'] ?? '—' }} · {{ $reporte['pon'] ?? '—' }}</p>
                                <p class="text-[9px] text-gray-400 font-bold">VLAN {{ $reporte['vlan'] ?? '—' }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            @foreach([
                                ['model' => 'desconexionWinboxRec', 'label' => 'Baja lógica del suscriptor en Winbox',    'sub' => 'Usuario eliminado del gestor MikroTik'],
                                ['model' => 'desconexionOLTRec',    'label' => 'Baja lógica del suscriptor en OLT',       'sub' => 'Puerto liberado en la OLT correspondiente'],
                                ['model' => 'ipLiberada',            'label' => 'Liberación de IP asignada',              'sub' => 'La IP queda disponible en el pool de red'],
                                ['model' => 'vlanLiberada',          'label' => 'Liberación de VLAN',                     'sub' => 'VLAN desvinculada del perfil del suscriptor'],
                                ['model' => 'sesionLiberada',        'label' => 'Liberación de sesión activa',             'sub' => 'Sesión PPPoE/IPoE terminada correctamente'],
                            ] as $item)
                            <label class="flex items-center gap-3 p-2.5 bg-white border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                                <input type="checkbox" wire:model.live="{{ $item['model'] }}" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 flex-shrink-0">
                                <div>
                                    <p class="text-xs font-black text-gray-800 uppercase">{{ $item['label'] }}</p>
                                    <p class="text-[10px] text-gray-500">{{ $item['sub'] }}</p>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('desconexionOLTRec')
                        <p class="text-[10px] text-red-500 font-black flex items-center gap-1"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                </div>
            </div>

            {{-- ─── B. RECUPERACIÓN DEL EQUIPO — todo Alpine local, CERO wire:model.live ── --}}
            <div x-init="equipoInfoRec = @json($reporte['info_equipo'] ?? '')" x-data="{
                equipoRec: '{{ $recuperaEquipoRec }}',
                serie: '',
                equipoInfoRec: '',
                get serieMatch() { return this.serie.length > 0 && this.equipoInfoRec.toUpperCase().includes(this.serie.toUpperCase()); },
                get serieDiff()  { return this.serie.length > 0 && !this.equipoInfoRec.toUpperCase().includes(this.serie.toUpperCase()); },
                pagoDano: false,
                pagoPerdida: false,
                equipoEntregado: false
            }" class="bg-white border {{ $tecnicoCompletadoRec ? 'border-emerald-300' : 'border-amber-200' }} rounded-xl shadow-sm">
                <div class="{{ $tecnicoCompletadoRec ? 'bg-emerald-50 border-b border-emerald-200' : 'bg-amber-50 border-b border-amber-200' }} px-5 py-3.5 flex items-center gap-2">
                    @if($tecnicoCompletadoRec)
                    <i class="ri-checkbox-circle-fill text-emerald-600 text-base"></i>
                    <p class="text-[11px] font-black text-emerald-800 uppercase tracking-widest">B. Recuperación del Equipo — Completada</p>
                    @else
                    <i class="ri-router-line text-amber-600 text-base"></i>
                    <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">B. Recuperación del Equipo en Comodato</p>
                    @endif
                </div>
                <div class="p-5 space-y-4 {{ $tecnicoCompletadoRec ? 'opacity-60 pointer-events-none' : '' }}">

                    {{-- Botones Sí / No — <button type="button"> igual que cancelación --}}
                    <div class="grid grid-cols-2 gap-3">
                        <button type="button" @click="equipoRec = 'si'; pagoDano = false; pagoPerdida = false">
                            <div class="text-center border-2 rounded-xl p-3.5 transition-all cursor-pointer"
                                 :class="equipoRec === 'si' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-emerald-200'">
                                <i class="ri-checkbox-circle-line block text-2xl mb-1"
                                   :class="equipoRec === 'si' ? 'text-emerald-500' : 'text-gray-300'"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest"
                                   :class="equipoRec === 'si' ? 'text-emerald-700' : 'text-gray-400'">SÍ — Recuperado</p>
                                <p class="text-[9px] mt-0.5"
                                   :class="equipoRec === 'si' ? 'text-emerald-600' : 'text-gray-300'">Regresa al inventario</p>
                            </div>
                        </button>
                        <button type="button" @click="equipoRec = 'no'; serie = ''">
                            <div class="text-center border-2 rounded-xl p-3.5 transition-all cursor-pointer"
                                 :class="equipoRec === 'no' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-200'">
                                <i class="ri-close-circle-line block text-2xl mb-1"
                                   :class="equipoRec === 'no' ? 'text-red-500' : 'text-gray-300'"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest"
                                   :class="equipoRec === 'no' ? 'text-red-700' : 'text-gray-400'">NO — No Recuperado</p>
                                <p class="text-[9px] mt-0.5"
                                   :class="equipoRec === 'no' ? 'text-red-600' : 'text-gray-300'">Requiere resolución</p>
                            </div>
                        </button>
                    </div>
                    @error('recuperaEquipoRec')
                    <p class="text-[10px] text-red-500 font-black flex items-center gap-1"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                    @enderror

                    {{-- CASO: Sí recuperado —  x-show simple sin transiciones (igual que cancelación) --}}
                    <div x-show="equipoRec === 'si'" x-cloak class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 space-y-3">
                        <p class="text-[9px] font-black text-emerald-700 uppercase tracking-widest">Validar serie del equipo recuperado</p>
                        <p class="font-mono text-sm font-black text-indigo-700 bg-white border border-indigo-100 px-4 py-2 rounded-lg">
                            {{ $reporte['info_equipo'] }}
                        </p>
                        <div class="space-y-1.5">
                            <label class="block text-[9px] font-black text-gray-600 uppercase tracking-widest">Serie física recuperada *</label>
                            <input type="text" x-model="serie"
                                   class="w-full bg-white border border-emerald-300 rounded-lg py-2.5 px-4 text-sm font-black font-mono uppercase tracking-widest text-indigo-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400"
                                   placeholder="ESCANEAR O ESCRIBIR SERIE...">
                        </div>
                        <div x-show="serieMatch" class="flex items-center gap-2 text-[10px] font-black text-emerald-700">
                            <i class="ri-checkbox-circle-fill text-emerald-500"></i> Serie confirmada — coincide con el registro ✓
                        </div>
                        <div x-show="serieDiff" class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                            <p class="text-[10px] font-black text-amber-800 flex items-center gap-1.5 mb-1">
                                <i class="ri-alert-line text-amber-600"></i> Serie diferente detectada
                            </p>
                            <p class="text-[10px] text-amber-700 font-medium">
                                No coincide con el registro. El sistema buscará esta serie, la reasignará al historial del suscriptor y registrará la recuperación correcta.
                            </p>
                        </div>
                        <label class="flex items-center gap-3 cursor-pointer p-3 bg-white border border-emerald-200 rounded-lg hover:bg-emerald-50 transition-colors">
                            <input type="checkbox" x-model="equipoEntregado" class="h-5 w-5 text-emerald-600 rounded focus:ring-emerald-500 flex-shrink-0">
                            <div>
                                <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Equipo ingresado a inventario de sucursal ✓</p>
                                <p class="text-[9px] text-emerald-600 font-medium">La sucursal acepta el ingreso del equipo al almacén</p>
                            </div>
                        </label>
                        @error('equipoEntregado')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                    </div>

                    {{-- CASO: No recuperado — x-show simple sin transiciones --}}
                    <div x-show="equipoRec === 'no'" x-cloak class="bg-red-50 border border-red-200 rounded-xl p-4 space-y-3">
                        <p class="text-[9px] font-black text-red-700 uppercase tracking-widest">Equipo no recuperado — seleccione forma de resolución</p>
                        <p class="text-[10px] text-red-600 font-medium">
                            Para cerrar el reporte el suscriptor debe liquidar el valor del equipo por pérdida o daño.
                            Si no paga, use Guardar Precierre.
                        </p>
                        <label class="flex items-start gap-3 cursor-pointer p-3 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                            <input type="checkbox" x-model="pagoDano" class="mt-0.5 h-5 w-5 text-red-600 rounded focus:ring-red-500 flex-shrink-0">
                            <div>
                                <p class="text-[10px] font-black text-red-900 uppercase tracking-widest">A) Pago por daño del equipo confirmado</p>
                                <p class="text-[9px] text-red-600 font-medium mt-0.5">El equipo fue dañado. El suscriptor pagó el costo de reparación/reposición.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer p-3 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                            <input type="checkbox" x-model="pagoPerdida" class="mt-0.5 h-5 w-5 text-red-600 rounded focus:ring-red-500 flex-shrink-0">
                            <div>
                                <p class="text-[10px] font-black text-red-900 uppercase tracking-widest">B) Pago por pérdida del equipo confirmado</p>
                                <p class="text-[9px] text-red-600 font-medium mt-0.5">El equipo no existe. El suscriptor pagó el valor total. Estatus → PAGADO POR PÉRDIDA.</p>
                            </div>
                        </label>
                        @error('pagoPerdidaRec')
                        <p class="text-[10px] text-red-600 font-black flex items-center gap-1"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                        @enderror
                        <div x-show="!pagoPerdida && !pagoDano"
                             class="flex items-start gap-2 bg-amber-50 border border-amber-100 rounded-lg p-3">
                            <i class="ri-information-line text-amber-600 text-sm flex-shrink-0"></i>
                            <p class="text-[10px] text-amber-700 font-bold">
                                Si el suscriptor no paga, use <strong>Guardar Precierre</strong> con motivo correspondiente.
                            </p>
                        </div>
                    </div>

                </div>

                {{-- Horas transcurridas + Calificación --}}
                <div class="border-t border-gray-100 px-5 pt-4 pb-2 space-y-4">
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-3.5 flex items-center gap-3">
                        <i class="ri-time-line text-gray-400 text-base flex-shrink-0"></i>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Horas transcurridas desde la apertura</p>
                            <p class="text-sm font-black text-gray-800">{{ $horasAtencion }} <span class="text-[10px] font-medium text-gray-400 normal-case">hrs. desde la generación del reporte</span></p>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">Calificación del Suscriptor</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border cursor-pointer transition-all
                                {{ $calificacion === 'Excelente' ? 'bg-emerald-50 border-emerald-400 text-emerald-700' : 'bg-white border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                <input type="radio" wire:model="calificacion" value="Excelente" class="sr-only">
                                <i class="ri-emotion-happy-line text-sm"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider">Excelente</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border cursor-pointer transition-all
                                {{ $calificacion === 'Bueno' ? 'bg-blue-50 border-blue-400 text-blue-700' : 'bg-white border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                <input type="radio" wire:model="calificacion" value="Bueno" class="sr-only">
                                <i class="ri-emotion-normal-line text-sm"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider">Bueno</span>
                            </label>
                            <label class="flex items-center justify-center gap-2 p-2.5 rounded-lg border cursor-pointer transition-all
                                {{ $calificacion === 'Malo' ? 'bg-red-50 border-red-400 text-red-700' : 'bg-white border-gray-200 text-gray-500 hover:border-gray-300' }}">
                                <input type="radio" wire:model="calificacion" value="Malo" class="sr-only">
                                <i class="ri-emotion-unhappy-line text-sm"></i>
                                <span class="text-[10px] font-black uppercase tracking-wider">Malo</span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Botón Guardar Cierre Técnico --}}
                @if(!$tecnicoCompletadoRec)
                <div class="border-t border-gray-200 px-5 py-4">
                    @error('tecnicoCompletadoRec')
                    <p class="text-[10px] text-red-500 font-black mb-3 flex items-center gap-1.5"><i class="ri-error-warning-line"></i> {{ $message }}</p>
                    @enderror
                    <button @click="$wire.guardarAvanceRecuperacion(equipoRec, serie, pagoDano, pagoPerdida)"
                            class="w-full py-3 bg-gray-800 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-gray-900 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-save-3-line text-base"></i> Guardar Cierre Técnico
                    </button>
                    <p class="text-center text-[9px] text-gray-400 font-medium mt-2">Habilita el Cierre Administrativo</p>
                </div>
                @else
                <div class="border-t border-emerald-200 px-5 py-3 bg-emerald-50 flex items-center gap-2">
                    <i class="ri-checkbox-circle-fill text-emerald-600"></i>
                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Acciones técnicas registradas correctamente</p>
                </div>
                @endif
            </div>

            {{-- ─── D. PRECIERRE ────────────────────────────────────────── --}}
            <div class="bg-white border border-amber-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-amber-50 border-b border-amber-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-save-3-line text-amber-600 text-sm"></i>
                    <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Precierre — Guardar Avance sin Cerrar</p>
                </div>
                <div class="p-5 space-y-3">
                    <select wire:model="motivoPrecierreRec"
                            class="w-full bg-white border border-amber-300 rounded-lg py-2.5 px-3 text-xs font-black uppercase focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400">
                        <option value="">— Seleccione motivo —</option>
                        <option value="NO_ENTREGA_EQUIPO">Suscriptor no entrega el equipo</option>
                        <option value="NO_PAGA_PERDIDA">No paga por pérdida/daño del equipo</option>
                        <option value="SUSCRIPTOR_AUSENTE">Suscriptor ausente en domicilio</option>
                        <option value="OTRO">Otro — ver notas del técnico</option>
                    </select>
                    @error('motivoPrecierreRec')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                    <button wire:click="guardarPrecierreRecuperacion"
                            class="w-full py-2.5 bg-white border border-amber-300 text-amber-700 font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-amber-50 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-save-line"></i> Guardar Precierre
                    </button>
                </div>
            </div>

            {{-- ─── E. CIERRE ADMINISTRATIVO ────────────────────────────── --}}
            <div class="bg-white border {{ $tecnicoCompletadoRec ? 'border-amber-400' : 'border-gray-200 opacity-50' }} rounded-xl shadow-sm overflow-hidden">
                <div class="{{ $tecnicoCompletadoRec ? 'bg-amber-600' : 'bg-gray-200' }} px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-shield-check-line {{ $tecnicoCompletadoRec ? 'text-white' : 'text-gray-500' }} text-base"></i>
                    <p class="text-[11px] font-black {{ $tecnicoCompletadoRec ? 'text-white' : 'text-gray-500' }} uppercase tracking-widest">
                        Cierre Administrativo — Sucursal
                    </p>
                    @if(!$tecnicoCompletadoRec)
                    <span class="ml-auto text-[9px] font-black bg-gray-300 text-gray-600 px-2 py-0.5 rounded uppercase">Requiere cierre técnico</span>
                    @endif
                </div>

                @if($tecnicoCompletadoRec)
                <div class="p-5 space-y-4">
                    <div class="bg-amber-50 border border-amber-100 rounded-lg p-4">
                        <p class="text-[9px] font-black text-amber-700 uppercase tracking-widest mb-3">Acciones del sistema al confirmar</p>
                        <ul class="space-y-1.5">
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-user-forbid-line text-red-500"></i>
                                Cambiar estado → <span class="text-red-600 font-black uppercase">Cancelado por Morosidad</span>
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-close-circle-line text-red-500"></i>
                                Cancelar servicio activo · bloquear reactivación automática
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-pause-circle-line text-amber-500"></i>
                                Detener generación de mensualidades · cancelar ciclos futuros
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-signal-tower-line text-indigo-500"></i>
                                Liberar salida NAP · puertos · recursos lógicos
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-store-2-line text-emerald-500"></i>
                                {{ $recuperaEquipoRec === 'si' ? 'Ingresar equipo recuperado a almacén' : 'Registrar baja por pérdida/daño en inventario' }}
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-history-line text-gray-500"></i>
                                Guardar evento permanente en historial del suscriptor
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-message-2-line text-indigo-500"></i>
                                Enviar SMS de confirmación al suscriptor
                            </li>
                        </ul>
                    </div>
                    {{-- Resumen calificación / horas --}}
                    <div class="grid grid-cols-2 gap-2">
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-center">
                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Calificación del Suscriptor</p>
                            <p class="text-xs font-black
                                {{ $calificacion === 'Excelente' ? 'text-emerald-700' : ($calificacion === 'Bueno' ? 'text-blue-700' : 'text-red-700') }}">
                                {{ $calificacion }}
                            </p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-center">
                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest mb-1">Tiempo de Atención</p>
                            <p class="text-xs font-black text-gray-700">{{ $horasAtencion }} hrs.</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-3 bg-red-50 border border-red-100 rounded-lg p-3.5">
                        <i class="ri-information-line text-red-500 text-base flex-shrink-0 mt-0.5"></i>
                        <p class="text-[10px] font-medium text-red-700 leading-relaxed">
                            <strong>Regla de negocio:</strong> Los periodos suspendidos y en recuperación
                            <strong>no generan mensualidades</strong>. La facturación queda cancelada permanentemente.
                        </p>
                    </div>
                    <button @click="$confirm(
                                '¿Confirmar cierre administrativo? El suscriptor quedará CANCELADO POR MOROSIDAD y se liberarán todos los recursos asignados.',
                                () => $wire.cerrarRecuperacion(),
                                { confirmText: 'Sí, aplicar cierre', title: 'Cierre Administrativo Final', icon: 'warning' }
                            )"
                            class="w-full py-3.5 bg-amber-600 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-amber-700 shadow-md shadow-amber-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-arrow-down-circle-line text-base"></i> Aplicar Recuperación — Cierre Total
                    </button>
                </div>
                @else
                <div class="p-6 text-center">
                    <i class="ri-lock-line text-3xl text-gray-300"></i>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest mt-2">
                        Complete el cierre técnico para habilitar el cierre administrativo
                    </p>
                </div>
                @endif
            </div>

            {{-- Enlace volver --}}
            <div class="flex justify-start">
                <a href="{{ route('reportes.servicio') }}"
                   class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Volver a bandeja
                </a>
            </div>

            @endif

            {{-- ═══════════════════════════════════════════════════════
                 [AUMENTO DE VELOCIDAD] Cambio de plan en Winbox + OLT
            ═══════════════════════════════════════════════════════ --}}
            @if($esAumentoVelocidad)
            <div class="bg-white border border-teal-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-teal-50 border-b border-teal-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-speed-up-line text-teal-600 text-base"></i>
                    <p class="text-[11px] font-black text-teal-800 uppercase tracking-widest">Cambio de Plan — Operación de Sucursal</p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- Información del cambio --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Plan Anterior</p>
                            <p class="text-lg font-black text-gray-500 font-mono">{{ $reporte['plan_anterior'] ?? '—' }}</p>
                        </div>
                        <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 text-center">
                            <p class="text-[9px] font-black text-teal-600 uppercase tracking-widest mb-1">Plan Nuevo</p>
                            <p class="text-lg font-black text-teal-700 font-mono">{{ $reporte['plan_nuevo'] ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- Confirmaciones en sistema --}}
                    <div>
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Confirmar cambio en sistemas de red</p>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 p-2.5 bg-indigo-50 border border-indigo-100 rounded-lg cursor-pointer hover:bg-indigo-100 transition-colors">
                                <input type="checkbox" wire:model="confirmaCambioWinbox" class="h-5 w-5 text-indigo-600 rounded focus:ring-0 flex-shrink-0">
                                <div>
                                    <p class="text-xs font-black text-gray-800 uppercase">Plan actualizado en Winbox</p>
                                    <p class="text-[10px] text-gray-500">Límite de velocidad del cliente ajustado en el gestor de red</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 bg-indigo-50 border border-indigo-100 rounded-lg cursor-pointer hover:bg-indigo-100 transition-colors">
                                <input type="checkbox" wire:model="confirmaCambioOLT" class="h-5 w-5 text-indigo-600 rounded focus:ring-0 flex-shrink-0">
                                <div>
                                    <p class="text-xs font-black text-gray-800 uppercase">Plan actualizado en OLT</p>
                                    <p class="text-[10px] text-gray-500">Perfil de velocidad ajustado en la OLT correspondiente</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Prueba de velocidad --}}
                    <div class="border-t border-gray-100 pt-4">
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Prueba de velocidad post-cambio</p>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Velocidad registrada</label>
                            <div class="relative w-56">
                                <input type="number" wire:model="velocidadNueva"
                                       class="w-full pl-4 pr-16 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-black font-mono focus:ring-2 focus:ring-teal-500/20"
                                       placeholder="0">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">Mbps</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 CIERRE DEL REPORTE
                 dd. El sistema permite una opción de Precierre (motivo que impide terminar)
                     el reporte se mantiene en Proceso y no cambia el estado del cliente.
                 ee. Guardar avance o Cierre total del servicio del lado del técnico
                 ff. Número de Horas desde la fecha del reporte hasta el cierre (automático — panel izquierdo)
                 gg. Calificación otorgada por el usuario EXCELENTE/BUENO/MALO — default Excelente (panel izquierdo)
                 hh. Cierre de Reporte Final por la sucursal:
                     i. Se puede hacer hasta contar con el comodato firmado
                    ii. Afectar el estado del Cliente a ACTIVO / TARIFA / SERVICIO
                 jj. Conectar al API de SMS y enviar mensaje de bienvenida al cliente (automático)
            ═══════════════════════════════════════════════════════ --}}
            {{-- ═══════════════════════════════════════════════════════
                 [CAMBIO SERVICIO] Recuperación del equipo anterior
            ═══════════════════════════════════════════════════════ --}}
            @if($esCambioServicio)
            <div class="bg-white border border-cyan-200 rounded-xl shadow-sm overflow-hidden"
                 x-data="{ equipoCambio: '{{ $recuperoEquipoCambio }}' }">
                <div class="bg-cyan-600 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-swap-box-line text-white text-base"></i>
                    <p class="text-[11px] font-black text-white uppercase tracking-widest">Recuperación de Equipo Anterior</p>
                    <span class="ml-auto text-[9px] font-black bg-cyan-100 text-cyan-800 px-2 py-0.5 rounded uppercase">
                        Técnico en Campo
                    </span>
                </div>

                {{-- Servicio anterior info --}}
                <div class="px-5 py-3.5 border-b border-cyan-100 bg-cyan-50 grid grid-cols-2 gap-3">
                    <div>
                        <p class="text-[9px] font-black text-cyan-600 uppercase tracking-widest mb-0.5">Servicio Anterior</p>
                        <p class="text-xs font-black text-gray-800 uppercase">{{ $reporte['servicio_anterior'] ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-cyan-600 uppercase tracking-widest mb-0.5">Equipo a Recuperar</p>
                        <p class="font-mono text-[10px] font-black text-gray-800">{{ $reporte['equipo_anterior'] ?? '—' }}</p>
                    </div>
                </div>

                <div class="p-5 space-y-4">
                    {{-- ¿Se recuperó el equipo? --}}
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer" @click="equipoCambio = 'si'">
                            <input type="radio" name="equipoCambioRec" value="si" class="sr-only">
                            <div class="text-center border-2 rounded-xl p-3.5 transition-all cursor-pointer"
                                 :class="equipoCambio === 'si' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-emerald-200'">
                                <i class="ri-checkbox-circle-line block text-xl mb-1"
                                   :class="equipoCambio === 'si' ? 'text-emerald-500' : 'text-gray-300'"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest"
                                   :class="equipoCambio === 'si' ? 'text-emerald-700' : 'text-gray-400'">Equipo Recuperado</p>
                            </div>
                        </label>
                        <label class="cursor-pointer" @click="equipoCambio = 'no'">
                            <input type="radio" name="equipoCambioRec" value="no" class="sr-only">
                            <div class="text-center border-2 rounded-xl p-3.5 transition-all cursor-pointer"
                                 :class="equipoCambio === 'no' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-200'">
                                <i class="ri-close-circle-line block text-xl mb-1"
                                   :class="equipoCambio === 'no' ? 'text-red-500' : 'text-gray-300'"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest"
                                   :class="equipoCambio === 'no' ? 'text-red-700' : 'text-gray-400'">No Recuperado</p>
                            </div>
                        </label>
                    </div>

                    {{-- CASO: Recuperado — confirmar serie --}}
                    <div x-show="equipoCambio === 'si'" x-cloak class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 space-y-3">
                        <p class="text-[9px] font-black text-emerald-700 uppercase tracking-widest">Confirmar serie del equipo recuperado</p>
                        <p class="font-mono text-sm font-black text-indigo-700 bg-white border border-indigo-100 px-4 py-2 rounded-lg">
                            {{ $reporte['equipo_anterior'] ?? '—' }}
                        </p>
                        <div class="space-y-1.5">
                            <label class="block text-[9px] font-black text-gray-600 uppercase tracking-widest">Serie física recuperada *</label>
                            <input type="text" wire:model.live="serieCambioRecuperada"
                                   class="w-full bg-white border border-emerald-300 rounded-lg py-2.5 px-4 text-sm font-black font-mono uppercase tracking-widest text-indigo-700 focus:ring-2 focus:ring-emerald-500/20"
                                   placeholder="ESCANEAR O ESCRIBIR SERIE...">
                        </div>
                        @if($serieCambioRecuperada)
                            @php $serieOkCambio = str_contains($reporte['equipo_anterior'] ?? '', $serieCambioRecuperada); @endphp
                            @if($serieOkCambio)
                            <div class="flex items-center gap-2 text-[10px] font-black text-emerald-700">
                                <i class="ri-checkbox-circle-fill text-emerald-500"></i> Serie confirmada — coincide con el registro ✓
                            </div>
                            @else
                            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                                <p class="text-[10px] font-black text-amber-800 flex items-center gap-1.5 mb-1">
                                    <i class="ri-alert-line text-amber-600"></i> Serie diferente detectada
                                </p>
                                <p class="text-[10px] text-amber-700">El sistema actualizará el registro con la serie física recuperada.</p>
                            </div>
                            @endif
                        @endif
                        <p class="text-[9px] text-gray-400">Al confirmar: el equipo anterior ingresa al inventario de sucursal.</p>
                    </div>

                    {{-- CASO: No recuperado — cobrar pérdida/daño --}}
                    <div x-show="equipoCambio === 'no'" x-cloak class="bg-red-50 border border-red-200 rounded-xl p-4 space-y-3">
                        <p class="text-[9px] font-black text-red-700 uppercase tracking-widest">Equipo no recuperado — resolución requerida</p>
                        <label class="flex items-start gap-3 cursor-pointer p-3 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                            <input type="checkbox" wire:model.live="pagoPerdidaCambio" class="mt-0.5 h-5 w-5 text-red-600 rounded focus:ring-red-500 flex-shrink-0">
                            <div>
                                <p class="text-[10px] font-black text-red-900 uppercase">Pago por pérdida del equipo confirmado</p>
                                <p class="text-[9px] text-red-600 mt-0.5">El suscriptor pagó el valor del equipo anterior.</p>
                            </div>
                        </label>
                        <label class="flex items-start gap-3 cursor-pointer p-3 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                            <input type="checkbox" wire:model.live="pagoDanoCambio" class="mt-0.5 h-5 w-5 text-red-600 rounded focus:ring-red-500 flex-shrink-0">
                            <div>
                                <p class="text-[10px] font-black text-red-900 uppercase">Pago por daño del equipo confirmado</p>
                                <p class="text-[9px] text-red-600 mt-0.5">El equipo fue dañado. El suscriptor pagó el costo de reposición.</p>
                            </div>
                        </label>
                        <div x-show="!$wire.pagoPerdidaCambio && !$wire.pagoDanoCambio"
                             class="flex items-start gap-2 bg-amber-50 border border-amber-100 rounded-lg p-3">
                            <i class="ri-information-line text-amber-600 text-sm flex-shrink-0"></i>
                            <p class="text-[10px] text-amber-700 font-bold">
                                Si el suscriptor no paga, use <strong>Guardar Precierre</strong> con motivo correspondiente.
                            </p>
                        </div>
                    </div>

                    {{-- Saldo a favor (si aplica) --}}
                    @if(($reporte['saldo_favor'] ?? 0) > 0)
                    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-lg p-3">
                        <i class="ri-money-dollar-circle-line text-emerald-600 text-base flex-shrink-0"></i>
                        <div>
                            <p class="text-[9px] font-black text-emerald-700 uppercase tracking-widest">Saldo a Favor del Suscriptor</p>
                            <p class="text-sm font-black text-emerald-800">${{ number_format($reporte['saldo_favor'], 2) }}</p>
                            <p class="text-[9px] text-emerald-600 font-medium">Se aplicará automáticamente al calcular el primer pago del nuevo servicio.</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 [RECONEXION_CAMBIO] Evidencia fotográfica de instalación (stubs)
            ═══════════════════════════════════════════════════════ --}}
            @if($esReconexionCambio)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-camera-line text-gray-500 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Evidencia Fotográfica de la Instalación</p>
                    <span class="ml-auto text-[9px] font-bold text-gray-400 bg-gray-100 border border-gray-200 px-1.5 py-0.5 rounded uppercase">Pendiente: carga de fotos</span>
                </div>
                <div class="p-5 grid grid-cols-2 gap-3">
                    @php
                        $evidencias = [
                            ['icon' => 'ri-router-line',      'label' => 'Equipo instalado',          'color' => 'blue'],
                            ['icon' => 'ri-node-tree',        'label' => 'Conexión en NAP',           'color' => 'indigo'],
                            ['icon' => 'ri-layout-bottom-line','label' => 'Acometida en domicilio',   'color' => 'violet'],
                            ['icon' => 'ri-speed-up-line',    'label' => 'Pantalla prueba velocidad', 'color' => 'teal'],
                        ];
                    @endphp
                    @foreach($evidencias as $ev)
                    <div class="border-2 border-dashed border-gray-200 rounded-xl p-4 flex flex-col items-center gap-2 text-center hover:border-gray-300 transition-colors cursor-pointer">
                        <i class="ri-image-add-line text-2xl text-gray-300"></i>
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $ev['label'] }}</p>
                        <span class="text-[8px] text-gray-300 font-bold">Toque para agregar</span>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 pb-5">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Observaciones del técnico</label>
                        <textarea wire:model="descripcionSolucion" rows="2"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs text-gray-700 focus:ring-2 focus:ring-gray-300/30 resize-none"
                                  placeholder="Observaciones, incidencias o notas de la instalación..."></textarea>
                    </div>
                </div>
            </div>
            @endif

            @if($esFalla || $esInstalacion || $esCambioDomicilio || $esServicioAdicional || $esAumentoVelocidad || $esCambioServicio || $esReconexion || $esReconexionCambio)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-medal-line text-emerald-500 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">
                        @if($esInstalacion) Cierre del Reporte
                        @elseif($esCambioDomicilio) Cierre de Cambio de Domicilio
                        @elseif($esServicioAdicional) Cierre — Servicio Adicional TV
                        @elseif($esAumentoVelocidad) Cierre — Aumento de Velocidad
                        @elseif($esCambioServicio) Cierre — Cambio de Servicio
                        @elseif($esReconexion) Cierre — Reconexión de Servicio
                        @elseif($esReconexionCambio) Cierre — Reconexión con Cambio de Servicio
                        @else Solución y Cierre del Reporte
                        @endif
                    </p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- dd. Opción de Precierre — Motivo que impide la conclusión --}}
                    <div class="border border-amber-200 bg-amber-50 rounded-lg overflow-hidden">
                        <div class="bg-amber-100 border-b border-amber-200 px-4 py-2.5 flex items-center gap-2">
                            <i class="ri-save-3-line text-amber-700 text-sm"></i>
                            <p class="text-[10px] font-black text-amber-800 uppercase tracking-widest">Precierre — Motivo que Impide la Conclusión del Servicio</p>
                        </div>
                        <div class="p-4 space-y-2">
                            <p class="text-[9px] text-amber-700 font-medium">El reporte se mantiene en Proceso y el estado del cliente no cambia, pues el servicio no está concluido.</p>
                            <select wire:model="motivoPrecierre"
                                    class="w-full bg-white border border-amber-300 rounded-lg py-2.5 px-3 text-xs font-black uppercase focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400">
                                <option value="">— Seleccione motivo de precierre —</option>
                                <option value="BAJOS_NIVELES">Bajos Niveles de Potencia Óptica</option>
                                <option value="MININODO_DANADO">Mininodo Dañado</option>
                                <option value="DANO_NAP">Daño en la NAP</option>
                                <option value="OTRO">Otro — ver notas del técnico</option>
                            </select>
                            @error('motivoPrecierre')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- hh. i. Comodato requerido para INSTALACION, SERVICIO_ADICIONAL_TV, CAMBIO_SERVICIO, RECONEXION y RECONEXION_CAMBIO --}}
                    @if($esInstalacion || $esServicioAdicional || $esCambioServicio || $esReconexion || $esReconexionCambio)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-900 px-4 py-2.5 flex items-center gap-2">
                            <i class="ri-file-text-line text-indigo-400 text-sm"></i>
                            <p class="text-[10px] font-black text-gray-200 uppercase tracking-widest">Comodato — Requerido para Cierre Total por Sucursal</p>
                        </div>
                        <div class="p-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" wire:model="comodatoFirmado" class="h-5 w-5 text-emerald-600 rounded border-gray-300 focus:ring-emerald-500 mt-0.5 flex-shrink-0">
                                <div>
                                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Comodato firmado por el cliente ✓</p>
                                    <p class="text-[10px] text-gray-500 mt-0.5">El cliente firmó el contrato de comodato del equipo instalado. Sin esta confirmación el reporte no puede cerrarse.</p>
                                </div>
                            </label>
                            @error('comodatoFirmado')
                            <p class="text-[10px] text-red-500 font-black mt-2 flex items-center gap-1">
                                <i class="ri-error-warning-line"></i> {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>
                    @endif

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Resolución aplicada *</label>
                        <select wire:model="solucionOpcion"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-black uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                            <option value="">— Seleccione resolución —</option>
                            @foreach($solucionesOpciones as $op)
                            <option value="{{ $op }}">{{ $op }}</option>
                            @endforeach
                        </select>
                        @error('solucionOpcion')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Notas adicionales del técnico</label>
                        <textarea wire:model="descripcionSolucion" rows="3"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-medium resize-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                  placeholder="Detalles de la atención, materiales usados, observaciones..."></textarea>
                    </div>

                    @if($esInstalacion)
                    <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-100 rounded-lg p-3.5">
                        <i class="ri-smartphone-line text-emerald-600 text-base flex-shrink-0 mt-0.5"></i>
                        <p class="text-[10px] font-medium text-emerald-800 leading-relaxed">
                            Al realizar el Cierre Total: el estado del cliente cambia a <strong>ACTIVO / TARIFA / SERVICIO</strong> y el sistema enviará automáticamente un <strong>SMS de bienvenida</strong> al cliente vía API.
                        </p>
                    </div>
                    @endif
                </div>

                {{-- ee. Botones: Guardar Precierre / Cierre Total --}}
                <div class="bg-gray-50 border-t border-gray-200 px-5 py-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <a href="{{ route('reportes.servicio') }}"
                       class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                        <i class="ri-arrow-left-line"></i> Volver a bandeja
                    </a>
                    <div class="flex gap-3">
                        <button wire:click="guardarPrecierre"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-amber-300 text-amber-700 font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-amber-50 shadow-sm transition-all active:scale-95">
                            <i class="ri-save-line"></i> Guardar Precierre
                        </button>
                        <button wire:click="cerrarReporte"
                                class="inline-flex items-center gap-2 px-7 py-2.5 bg-emerald-600 text-white font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-all active:scale-95">
                            <i class="ri-check-double-line"></i> Cierre Total
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- Botón volver para tipos sin cierre propio --}}
            @if(!$esFalla && !$esInstalacion && !$esCambioDomicilio && !$esSuspension && !$esCancelacion && !$esRecuperacion && !$esServicioAdicional && !$esAumentoVelocidad)
            <div class="flex justify-start">
                <a href="{{ route('reportes.servicio') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-gray-50 shadow-sm transition-all">
                    <i class="ri-arrow-left-line"></i> Volver a Bandeja
                </a>
            </div>
            @endif

        </div>{{-- /columna derecha --}}
    </div>{{-- /grid --}}

</div>
