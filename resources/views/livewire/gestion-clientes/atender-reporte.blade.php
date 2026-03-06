
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

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
                            ['icon' => 'ri-hashtag',              'label' => 'ID Cliente',         'value' => $reporte['id_cliente'] ?? '—', 'mono' => true],
                            ['icon' => 'ri-user-line',            'label' => 'Titular',            'value' => $reporte['cliente'],         'bold' => true],
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

                    {{-- Últimas potencias (no aplica para instalaciones nuevas ni servicio adicional) --}}
                    @if(!$esInstalacion && !$esServicioAdicional)
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
                 — Instalación / Servicio Adicional / Falla con cambio de equipo
            ═══════════════════════════════════════════════════════ --}}
            @if($esFalla || $esInstalacion || $esCambioDomicilio || $esServicioAdicional)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5">
                    @if($esInstalacion || $esServicioAdicional)
                    <div class="flex items-center gap-2">
                        <div class="w-6 h-6 bg-emerald-100 rounded-md flex items-center justify-center text-[10px] font-black text-emerald-700">i</div>
                        <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Asignacion de Equipo — ONU Asignada</p>
                        <span class="ml-auto text-[9px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-1.5 py-0.5 rounded uppercase">Sucursal</span>
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

                @if($cambioEquipo || $esInstalacion || $esServicioAdicional)
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                @if($esInstalacion || $esServicioAdicional) Equipo Asignado — Del Catálogo (Sucursal) @else Nuevo Equipo del Almacén *
                                @endif
                            </label>
                            <select wire:model.live="equipoNuevo"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-black uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="">— Seleccione equipo disponible —</option>
                                @foreach($catalogoEquipos as $eq)
                                <option value="{{ $eq['id'] }}">{{ $eq['label'] }}</option>
                                @endforeach
                            </select>
                            @error('equipoNuevo')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                        </div>

                        @if($tieneInternet)
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">i. Nombre del WIFI</label>
                            <input type="text" wire:model="wifiNuevo"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                   placeholder="TuVision_001">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">ii. Contraseña</label>
                            <input type="text" wire:model="passwordNuevo"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-bold font-mono focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">iii. VLAN (viene de catálogo)</label>
                            <select wire:model="vlanNueva"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-black uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="">— Seleccione VLAN —</option>
                                <option value="100">VLAN 100</option>
                                <option value="200">VLAN 200</option>
                                <option value="300">VLAN 300</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">iv. Encapsulamiento (viene de catálogo)</label>
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
                        <i class="ri-save-line"></i> Guardar Asignación de Equipo
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
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 INSTALACION DEL SERVICIO
                 x. Registro de la NAP donde se conectara el servicio (del catálogo / técnico)
                    i. Dirección de la NAP (del catálogo — automático)
                   ii. Seleccionar el # de Salida del NAP (del catálogo — técnico)
                  iii. Afectar las salidas de inventario de la NAP (automático)
            ═══════════════════════════════════════════════════════ --}}
            @if($esInstalacion || $esServicioAdicional)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <div class="w-6 h-6 bg-indigo-100 rounded-md flex items-center justify-center text-[10px] font-black text-indigo-600">x</div>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Registro de la NAP donde se Conectara el Servicio</p>
                    <span class="ml-auto text-[9px] font-bold text-gray-400 uppercase">Del Catálogo / Técnico</span>
                    <span class="text-[9px] font-bold text-red-500 bg-red-50 border border-red-100 px-1.5 py-0.5 rounded uppercase">Requerido</span>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">i. NAP de Conexión * (Del catálogo)</label>
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
                            <i class="ri-map-pin-line"></i> i. Dirección de la NAP cargada automáticamente desde catálogo
                        </p>
                        @endif
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">ii. # de Salida del NAP * (Salidas disponibles)</label>
                        <select wire:model="salidaNap"
                                @if(!$napSeleccionada) disabled @endif
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-black uppercase py-2.5 px-4 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 disabled:opacity-40">
                            <option value="">— Salidas libres —</option>
                            @foreach($reporte['salidas_nap_disponibles'] ?? [] as $s)
                            <option value="{{ $s }}">Salida #{{ $s }}</option>
                            @endforeach
                        </select>
                        @error('salidaNap')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                        <p class="text-[9px] text-gray-400 font-medium">iii. Al cerrar el reporte, la salida se marcará como ocupada en inventario (automático)</p>
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
            @if($esFalla || $esInstalacion || $esCambioDomicilio || $esServicioAdicional)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-line-chart-line text-indigo-500 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">
                        @if($esInstalacion) y · z · aa. @endif
                        Lecturas Técnicas — Potencias Ópticas
                    </p>
                    <span class="ml-auto text-[9px] font-bold text-gray-400 uppercase">Técnico</span>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            @if($esInstalacion) y. @endif
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
                            @if($esInstalacion) z. @endif
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
                            @if($esInstalacion) aa. @endif
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
                 — Solo si tiene TV
            ═══════════════════════════════════════════════════════ --}}
            @if($tieneTV && ($esFalla || $esInstalacion || $esCambioDomicilio || $esServicioAdicional))
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-violet-50 border-b border-violet-100 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-tv-2-line text-violet-600 text-sm"></i>
                    <p class="text-[11px] font-black text-violet-800 uppercase tracking-widest">
                        @if($esInstalacion) bb · cc. @endif
                        Verificación de Televisión — Mininodo
                    </p>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            @if($esInstalacion) bb. @endif
                            Confirmación de Prueba de Canales
                        </label>
                        <label class="flex items-center gap-2.5 bg-gray-50 border border-gray-200 rounded-lg px-4 py-2.5 cursor-pointer hover:bg-violet-50 hover:border-violet-200 transition-colors">
                            <input type="checkbox" wire:model="pruebaCanalesOk" class="h-4 w-4 text-violet-600 rounded focus:ring-0">
                            <span class="text-[10px] font-black text-gray-700 uppercase tracking-widest">Prueba superada ✓</span>
                        </label>
                    </div>
                    <div class="sm:col-span-2 space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                            @if($esInstalacion) cc. @endif
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
            @if($tieneInternet && ($esFalla || $esInstalacion || $esCambioDomicilio))
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-sky-50 border-b border-sky-100 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-wifi-line text-sky-600 text-sm"></i>
                    <p class="text-[11px] font-black text-sky-800 uppercase tracking-widest">
                        @if($esInstalacion) j · k · l · m · n · o · p. @endif
                        Verificacion de ONU / Internet
                    </p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- j. Conexión de ONU — 4 confirmaciones del técnico --}}
                    <div>
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">
                            @if($esInstalacion) j. @endif
                            Conexion de ONU — Confirmaciones del Técnico
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            @foreach([
                                ['model' => 'onuEncendio',    'label' => ($esInstalacion ? 'i. ' : '') . 'ONU Encendió al Conectar',  'icon' => 'ri-power-line'],
                                ['model' => 'ponVerde',       'label' => ($esInstalacion ? 'ii. ' : '') . 'PON en Verde',             'icon' => 'ri-signal-wifi-3-line'],
                                ['model' => 'wifiConecta',    'label' => ($esInstalacion ? 'iii. ' : '') . 'Conecta a la Red WIFI',   'icon' => 'ri-wifi-line'],
                                ['model' => 'accesoInternet', 'label' => ($esInstalacion ? 'iv. ' : '') . 'Prueba de Velocidad OK',   'icon' => 'ri-global-line'],
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
                                @if($esInstalacion) iv. @endif
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
                            @if($esInstalacion) k · l · m. @endif
                            Asignaciones de Red — Sucursal
                        </p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                    @if($esInstalacion) k. @endif
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
                                    @if($esInstalacion) l. @endif
                                    Asignación de PON
                                </label>
                                <input type="text" wire:model="ponAsignado"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-3 font-mono text-xs font-black uppercase focus:ring-2 focus:ring-sky-500/20"
                                       placeholder="PON/0/1">
                            </div>
                            <div class="space-y-1.5">
                                <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">
                                    @if($esInstalacion) m. @endif
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
                                ['model' => 'actualizoWinbox',  'label' => ($esInstalacion ? 'n. ' : '') . 'Actualización del nombre del cliente en Winbox'],
                                ['model' => 'asignoPlanWinbox', 'label' => ($esInstalacion ? 'o. ' : '') . 'Asignación del plan de datos en Winbox (sucursal)'],
                                ['model' => 'actualizoOLT',     'label' => ($esInstalacion ? 'p. ' : '') . 'Actualización de datos del cliente en la OLT (sucursal)'],
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
            <div class="bg-white border border-red-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-red-50 border-b border-red-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-indeterminate-circle-line text-red-600 text-lg"></i>
                    <p class="text-[11px] font-black text-red-800 uppercase tracking-widest">Ejecución de Suspensión por Falta de Pago</p>
                    <span class="ml-auto text-[9px] font-black px-2 py-0.5 rounded uppercase
                        {{ $usaLogica && !$usaFisica ? 'bg-blue-100 text-blue-700' : ($usaFisica && !$usaLogica ? 'bg-red-100 text-red-700' : 'bg-violet-100 text-violet-700') }}">
                        {{ $usaLogica && !$usaFisica ? 'Corte Lógico' : ($usaFisica && !$usaLogica ? 'Corte Físico' : 'Lógico + Físico') }}
                    </span>
                </div>
                <div class="p-5 space-y-3">

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
                                    <p class="text-xs font-black text-gray-800 uppercase">Desconexión en Winbox</p>
                                    <p class="text-[10px] text-gray-500">Corte lógico confirmado en el gestor de red</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 p-2.5 bg-white border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                                <input type="checkbox" wire:model="confirmacionOLT" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <div>
                                    <p class="text-xs font-black text-gray-800 uppercase">Desconexión en OLT</p>
                                    <p class="text-[10px] text-gray-500">Puerto bloqueado en la OLT</p>
                                </div>
                            </label>
                        </div>
                    </div>
                    @endif

                    {{-- Desconexión FÍSICA: Solo TV o TV+Internet sin remoto --}}
                    @if($usaFisica)
                    <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                        <p class="text-[9px] font-black text-red-600 uppercase tracking-widest mb-1">Desconexión Física en NAP — Técnico en Campo</p>
                        <p class="text-[9px] text-red-500 font-medium mb-3">
                            {{ $soloTV ? 'Servicio de TV (mininodo) — siempre requiere desconexión física en NAP.' : 'ONU no soporta corte remoto — técnico debe desconectar físicamente en la NAP.' }}
                        </p>
                        <label class="flex items-center gap-3 p-2.5 bg-white border border-red-200 rounded-lg cursor-pointer hover:bg-red-50 transition-colors mb-3">
                            <input type="checkbox" wire:model="confirmacionDesconexionFisica" class="h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                            <div>
                                <p class="text-xs font-black text-gray-800 uppercase">Desconexión física confirmada en NAP</p>
                                <p class="text-[10px] text-gray-500">Técnico retiró el conector de la NAP</p>
                            </div>
                        </label>
                        <div class="space-y-1.5">
                            <label class="block text-[9px] font-black text-red-600 uppercase tracking-widest">Puerto NAP que queda libre</label>
                            <input type="text" wire:model="salidaNapLibre"
                                   class="w-full bg-white border border-red-200 rounded-lg py-2.5 px-4 text-sm font-black uppercase focus:ring-2 focus:ring-red-400/30"
                                   placeholder="Salida #4">
                            <p class="text-[9px] text-gray-400">Se actualizará el inventario de salidas de la NAP al confirmar</p>
                        </div>
                    </div>
                    @endif

                    <button wire:click="cerrarSuspension"
                            class="w-full py-3.5 bg-red-600 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-red-700 shadow-md shadow-red-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-pause-circle-line text-base"></i> Confirmar y Finalizar Suspensión
                    </button>
                    <p class="text-center text-[9px] text-gray-400 font-medium uppercase tracking-widest">
                        Al confirmar: estado cliente → SUSPENDIDO · actualiza inventario NAP · envía SMS al cliente
                    </p>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 [CANCELACIÓN] Recuperación del equipo
            ═══════════════════════════════════════════════════════ --}}
            @if($esCancelacion)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-close-circle-line text-gray-600 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Recuperación del Equipo en Comodato</p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- Opción: recuperar equipo --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="recuperacion" wire:model.live="equipoRecuperado" value="1" class="peer sr-only">
                            <div class="border-2 border-gray-200 rounded-xl p-4 text-center hover:border-emerald-300 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                <i class="ri-checkbox-circle-fill text-2xl text-gray-200 peer-checked:text-emerald-500 block mb-1.5 transition-colors"></i>
                                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">SÍ — Recuperar Equipo</p>
                                <p class="text-[9px] text-gray-400 mt-1">El equipo regresa al inventario</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="recuperacion" wire:model.live="equipoPerdido" value="1" class="peer sr-only">
                            <div class="border-2 border-gray-200 rounded-xl p-4 text-center hover:border-red-300 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all">
                                <i class="ri-close-circle-fill text-2xl text-gray-200 peer-checked:text-red-500 block mb-1.5 transition-colors"></i>
                                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">NO — Pago por Pérdida</p>
                                <p class="text-[9px] text-gray-400 mt-1">Se registra como pago por daño/pérdida</p>
                            </div>
                        </label>
                    </div>

                    @if($equipoRecuperado)
                    <div class="space-y-3">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Confirmar Serie del Equipo Recuperado</label>
                            <div class="flex gap-3">
                                <input type="text" wire:model="serieConfirmada"
                                       class="flex-1 bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-black font-mono uppercase focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400"
                                       placeholder="{{ $reporte['info_equipo'] }}">
                            </div>
                            <p class="text-[9px] text-gray-400 font-medium">Si la serie no coincide, busque en sistema y actualice el registro</p>
                        </div>

                        <label class="flex items-center gap-3 cursor-pointer p-3 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors">
                            <input type="checkbox" wire:model="desconexionFisica" class="h-5 w-5 text-emerald-600 rounded focus:ring-0 flex-shrink-0">
                            <span class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Confirmación de desconexión física del servicio en NAP</span>
                        </label>
                    </div>
                    @endif

                    @if($tieneInternet)
                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Baja Lógica de Sistema — Sucursal</p>
                        <label class="flex items-center gap-3 cursor-pointer p-2.5 bg-gray-50 border border-gray-200 rounded-lg hover:bg-indigo-50 transition-colors">
                            <input type="checkbox" wire:model="bajaWinbox" class="h-4 w-4 text-indigo-600 rounded focus:ring-0 flex-shrink-0">
                            <span class="text-[10px] font-black text-gray-700 uppercase">Baja del nombre del cliente en Winbox</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer p-2.5 bg-gray-50 border border-gray-200 rounded-lg hover:bg-indigo-50 transition-colors">
                            <input type="checkbox" wire:model="bajaOLT" class="h-4 w-4 text-indigo-600 rounded focus:ring-0 flex-shrink-0">
                            <span class="text-[10px] font-black text-gray-700 uppercase">Baja de datos del cliente en OLT</span>
                        </label>
                    </div>
                    @endif

                    <button wire:click="cerrarCancelacion"
                            class="w-full py-3.5 bg-gray-900 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-black shadow-md transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-checkbox-circle-line text-base"></i> Confirmar Cancelación del Servicio
                    </button>
                    <p class="text-center text-[9px] text-gray-400 font-medium uppercase tracking-widest">
                        Al confirmar: estado → CANCELADO · equipo → inventario · NAP → salida libre · SMS al cliente
                    </p>
                </div>
            </div>
            @endif

            {{-- ═══════════════════════════════════════════════════════
                 [RECUPERACIÓN DE EQUIPO] Por adeudo >61 días
            ═══════════════════════════════════════════════════════ --}}
            @if($esRecuperacion)
            <div class="bg-white border border-amber-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-amber-50 border-b border-amber-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-arrow-down-circle-line text-amber-600 text-base"></i>
                    <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Recuperación de Equipo — Adeudo Mayor a 61 Días</p>
                </div>
                <div class="p-5 space-y-4">

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Confirmación de Desconexión Física</label>
                        <label class="flex items-center gap-3 cursor-pointer p-3 bg-gray-50 border border-gray-200 rounded-lg hover:bg-amber-50 hover:border-amber-200 transition-colors">
                            <input type="checkbox" wire:model="desconexionFisicaRec" class="h-5 w-5 text-amber-600 rounded focus:ring-0 flex-shrink-0">
                            <span class="text-[10px] font-black text-gray-700 uppercase tracking-widest">Equipo desconectado físicamente de la NAP</span>
                        </label>
                    </div>

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Confirmar Serie del Equipo Recuperado *</label>
                        <input type="text" wire:model="serieRecuperada"
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-black font-mono uppercase tracking-widest focus:ring-2 focus:ring-amber-500/20 focus:border-amber-400"
                               placeholder="{{ $reporte['info_equipo'] }}">
                        @error('serieRecuperada')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror
                        <p class="text-[9px] text-amber-600 font-bold uppercase">Si no confirma la serie el reporte NO puede cerrarse</p>
                    </div>

                    <label class="flex items-center gap-3 cursor-pointer p-3 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors">
                        <input type="checkbox" wire:model="equipoEntregado" class="h-5 w-5 text-emerald-600 rounded focus:ring-0 flex-shrink-0">
                        <div>
                            <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Equipo ingresado a inventario de sucursal ✓</p>
                            <p class="text-[9px] text-emerald-600 font-medium">La sucursal acepta el ingreso del equipo</p>
                        </div>
                    </label>
                    @error('equipoEntregado')<p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>@enderror

                    @if($tieneInternet)
                    <div class="border-t border-gray-100 pt-4 space-y-2">
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Baja Lógica — Sucursal</p>
                        <label class="flex items-center gap-2.5 cursor-pointer p-2.5 bg-gray-50 border border-gray-200 rounded-lg hover:bg-indigo-50 transition-colors">
                            <input type="checkbox" wire:model="desconexionWinboxRec" class="h-4 w-4 text-indigo-600 rounded focus:ring-0 flex-shrink-0">
                            <span class="text-[10px] font-black text-gray-700 uppercase">Desconexión lógica en Winbox</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer p-2.5 bg-gray-50 border border-gray-200 rounded-lg hover:bg-indigo-50 transition-colors">
                            <input type="checkbox" wire:model="desconexionOLTRec" class="h-4 w-4 text-indigo-600 rounded focus:ring-0 flex-shrink-0">
                            <span class="text-[10px] font-black text-gray-700 uppercase">Desconexión lógica en OLT</span>
                        </label>
                    </div>
                    @endif

                    <button wire:click="cerrarRecuperacion"
                            class="w-full py-3.5 bg-amber-600 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-amber-700 shadow-md shadow-amber-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-arrow-down-circle-line text-base"></i> Confirmar Recuperación y Cerrar Reporte
                    </button>
                </div>
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
            @if($esFalla || $esInstalacion || $esCambioDomicilio || $esServicioAdicional || $esAumentoVelocidad)
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-medal-line text-emerald-500 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">
                        @if($esInstalacion) Cierre del Reporte
                        @elseif($esCambioDomicilio) Cierre de Cambio de Domicilio
                        @elseif($esServicioAdicional) Cierre — Servicio Adicional TV
                        @elseif($esAumentoVelocidad) Cierre — Aumento de Velocidad
                        @else Solución y Cierre del Reporte
                        @endif
                    </p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- dd. Opción de Precierre — Motivo que impide la conclusión --}}
                    <div class="border border-amber-200 bg-amber-50 rounded-lg overflow-hidden">
                        <div class="bg-amber-100 border-b border-amber-200 px-4 py-2.5 flex items-center gap-2">
                            <i class="ri-save-3-line text-amber-700 text-sm"></i>
                            <p class="text-[10px] font-black text-amber-800 uppercase tracking-widest">dd. Precierre — Motivo que Impide la Conclusión del Servicio</p>
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

                    {{-- hh. i. Comodato requerido para INSTALACION y SERVICIO_ADICIONAL_TV --}}
                    @if($esInstalacion || $esServicioAdicional)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-900 px-4 py-2.5 flex items-center gap-2">
                            <i class="ri-file-text-line text-indigo-400 text-sm"></i>
                            <p class="text-[10px] font-black text-gray-200 uppercase tracking-widest">hh. i. Comodato — Requerido para Cierre Total por Sucursal</p>
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
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">ee. Notas adicionales del técnico</label>
                        <textarea wire:model="descripcionSolucion" rows="3"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-medium resize-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                  placeholder="Detalles de la atención, materiales usados, observaciones..."></textarea>
                    </div>

                    @if($esInstalacion)
                    <div class="flex items-start gap-3 bg-emerald-50 border border-emerald-100 rounded-lg p-3.5">
                        <i class="ri-smartphone-line text-emerald-600 text-base flex-shrink-0 mt-0.5"></i>
                        <p class="text-[10px] font-medium text-emerald-800 leading-relaxed">
                            <strong>jj.</strong> Al realizar el Cierre Total: el estado del cliente cambia a <strong>ACTIVO / TARIFA / SERVICIO</strong> y el sistema enviará automáticamente un <strong>SMS de bienvenida</strong> al cliente vía API.
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
                            <i class="ri-save-line"></i> dd. Guardar Precierre
                        </button>
                        <button wire:click="cerrarReporte"
                                class="inline-flex items-center gap-2 px-7 py-2.5 bg-emerald-600 text-white font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-all active:scale-95">
                            <i class="ri-check-double-line"></i> ee. Cierre Total
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