<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================
         ENCABEZADO
    ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <a href="{{ route('reportes.servicio') }}" class="hover:text-indigo-600 transition-colors flex items-center gap-1">
                    <i class="ri-arrow-left-line"></i> Bandeja de Reportes
                </a>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-indigo-600">Atención Técnica</span>
            </div>
            <div class="flex items-center gap-3">
                <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                    Reporte <span class="text-indigo-600">{{ $reporte['folio'] }}</span>
                </h2>
                {{-- Badge tipo de falla --}}
                @php
                    $tipoBadges = [
                        'FALLA_TV'          => ['label' => 'Falla TV',          'class' => 'bg-violet-100 text-violet-700 border-violet-200', 'icon' => 'ri-tv-2-line'],
                        'FALLA_INTERNET'    => ['label' => 'Falla Internet',    'class' => 'bg-sky-100 text-sky-700 border-sky-200',           'icon' => 'ri-wifi-off-line'],
                        'FALLA_TV_INTERNET' => ['label' => 'Falla TV+Internet', 'class' => 'bg-blue-100 text-blue-700 border-blue-200',         'icon' => 'ri-router-line'],
                        'CAMBIO_DOMICILIO'  => ['label' => 'Cambio Domicilio',  'class' => 'bg-orange-100 text-orange-700 border-orange-200',   'icon' => 'ri-map-pin-line'],
                        'INSTALACION'       => ['label' => 'Instalación',       'class' => 'bg-emerald-100 text-emerald-700 border-emerald-200','icon' => 'ri-install-line'],
                        'SUSPENSION'        => ['label' => 'Suspensión',        'class' => 'bg-red-100 text-red-700 border-red-200',            'icon' => 'ri-pause-circle-line'],
                        'CANCELACION'       => ['label' => 'Cancelación',       'class' => 'bg-gray-100 text-gray-700 border-gray-200',         'icon' => 'ri-close-circle-line'],
                        'RECUPERACION'      => ['label' => 'Rec. Equipo',       'class' => 'bg-amber-100 text-amber-700 border-amber-200',      'icon' => 'ri-arrow-down-circle-line'],
                    ];
                    $tb = $tipoBadges[$reporte['tipo_reporte']] ?? ['label' => $reporte['tipo_reporte'], 'class' => 'bg-gray-100 text-gray-600 border-gray-200', 'icon' => 'ri-file-list-line'];
                    $tieneInternet = in_array($reporte['tipo_servicio'], ['INTERNET', 'TV+INTERNET', 'CAMBIO_DOMICILIO']);
                    $tieneTV       = in_array($reporte['tipo_servicio'], ['TV', 'TV+INTERNET', 'CAMBIO_DOMICILIO']);
                    $esCambioDom   = $reporte['tipo_reporte'] === 'CAMBIO_DOMICILIO';
                    $esSuspension  = $reporte['tipo_reporte'] === 'SUSPENSION';
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1 {{ $tb['class'] }} border rounded-lg text-[10px] font-black uppercase tracking-tight">
                    <i class="{{ $tb['icon'] }}"></i> {{ $tb['label'] }}
                </span>
            </div>
        </div>
        <button wire:click="exportarIndividual('{{ $reporte['folio'] }}')"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-red-500 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-red-50 hover:border-red-200 transition-all self-start">
            <i class="ri-file-pdf-line text-base"></i> Exportar PDF
        </button>
    </div>

    {{-- ================================================================
         LAYOUT PRINCIPAL
    ================================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ============================================================
             COLUMNA IZQUIERDA — DATOS PREVIOS (solo lectura)
        ============================================================ --}}
        <div class="lg:col-span-4 space-y-4">

            {{-- Panel: Datos del reporte --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-900 px-5 py-3.5 flex items-center justify-between">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Datos Previos del Reporte</p>
                    <span class="font-mono text-xs font-black text-white">{{ $reporte['folio'] }}</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @php
                        $datosBase = [
                            ['icon' => 'ri-calendar-event-line', 'label' => 'Fecha apertura',    'value' => $reporte['fecha'],          'mono' => true],
                            ['icon' => 'ri-store-2-line',         'label' => 'Sucursal',          'value' => $reporte['sucursal']],
                            ['icon' => 'ri-user-line',            'label' => 'Cliente',           'value' => $reporte['cliente'],        'bold' => true],
                            ['icon' => 'ri-map-pin-line',         'label' => 'Domicilio',         'value' => $reporte['domicilio'],      'italic' => true],
                            ['icon' => 'ri-service-line',         'label' => 'Servicio',          'value' => $reporte['servicio'],       'badge' => 'indigo'],
                            ['icon' => 'ri-user-heart-line',      'label' => 'Estado cliente',    'value' => $reporte['estado_cliente'],'badge' => 'emerald'],
                            ['icon' => 'ri-group-line',           'label' => 'Quien reportó',     'value' => $reporte['quien_reporto']],
                            ['icon' => 'ri-shield-user-line',     'label' => 'Técnico asignado',  'value' => $reporte['tecnico'],        'badge' => 'indigo'],
                        ];
                    @endphp
                    @foreach($datosBase as $d)
                    <div class="flex items-start gap-3 px-4 py-3">
                        <i class="{{ $d['icon'] }} text-gray-400 text-base flex-shrink-0 mt-0.5"></i>
                        <div class="flex-1 min-w-0">
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $d['label'] }}</p>
                            @if(isset($d['badge']) && $d['badge'] === 'indigo')
                                <span class="inline-flex text-[10px] font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md uppercase mt-0.5">{{ $d['value'] }}</span>
                            @elseif(isset($d['badge']) && $d['badge'] === 'emerald')
                                <span class="inline-flex text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md uppercase mt-0.5">{{ $d['value'] }}</span>
                            @elseif(isset($d['mono']))
                                <p class="font-mono text-xs font-black text-gray-800 mt-0.5">{{ $d['value'] }}</p>
                            @elseif(isset($d['italic']))
                                <p class="text-xs text-gray-700 italic mt-0.5">{{ $d['value'] }}</p>
                            @elseif(isset($d['bold']))
                                <p class="text-xs font-black text-gray-900 uppercase mt-0.5">{{ $d['value'] }}</p>
                            @else
                                <p class="text-xs font-semibold text-gray-700 uppercase mt-0.5">{{ $d['value'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Panel: Red y Equipo --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
                    <i class="ri-router-line text-indigo-500"></i>
                    <p class="text-[10px] font-black text-gray-700 uppercase tracking-widest">Red y Equipo Asignado</p>
                </div>
                <div class="p-4 space-y-3">

                    {{-- NAP --}}
                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-3">
                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">NAP Asignada</p>
                        <p class="text-sm font-black text-gray-900 uppercase">{{ $reporte['nap'] }}</p>
                        <p class="text-[10px] text-gray-500 italic mt-0.5">{{ $reporte['dir_nap'] }}</p>
                    </div>

                    {{-- Equipo base (Mininodo/ONU) --}}
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Equipo Asignado</p>
                        <p class="font-mono text-xs font-black text-gray-800">{{ $reporte['info_equipo'] }}</p>
                    </div>

                    {{-- Datos ONU (solo Internet) --}}
                    @if($tieneInternet)
                    <div class="bg-sky-50 border border-sky-100 rounded-lg p-3 space-y-1.5">
                        <p class="text-[9px] font-black text-sky-600 uppercase tracking-widest mb-2">Configuración ONU</p>
                        @php
                            $onuDatos = [
                                ['l' => 'IP',       'v' => $reporte['ip']],
                                ['l' => 'WIFI',     'v' => $reporte['wifi']],
                                ['l' => 'Pass',     'v' => $reporte['password_wifi'] ?? '—'],
                                ['l' => 'OLT',      'v' => $reporte['olt']],
                                ['l' => 'PON',      'v' => $reporte['pon']],
                                ['l' => 'VLAN',     'v' => $reporte['vlan'] ?? '—'],
                                ['l' => 'Encaps.',  'v' => $reporte['encapsulamiento'] ?? '—'],
                            ];
                        @endphp
                        <div class="grid grid-cols-2 gap-x-4 gap-y-1.5">
                            @foreach($onuDatos as $od)
                            <div>
                                <p class="text-[9px] font-bold text-gray-400 uppercase">{{ $od['l'] }}</p>
                                <p class="text-[10px] font-black text-gray-800 font-mono">{{ $od['v'] }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Últimas potencias registradas --}}
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-3 py-2 border-b border-gray-200">
                            <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Últimas Potencias Registradas</p>
                        </div>
                        <div class="p-3 grid grid-cols-2 gap-3">
                            <div class="text-center bg-red-50 border border-red-100 rounded-lg p-2">
                                <p class="text-[9px] font-bold text-gray-400 uppercase">Salida NAP</p>
                                <p class="text-base font-black text-red-600 font-mono">{{ $reporte['ultima_potencia_nap'] }}</p>
                                <p class="text-[9px] text-gray-400">dBm</p>
                            </div>
                            <div class="text-center bg-red-50 border border-red-100 rounded-lg p-2">
                                <p class="text-[9px] font-bold text-gray-400 uppercase">Antes Equipo</p>
                                <p class="text-base font-black text-red-600 font-mono">{{ $reporte['ultima_potencia_equipo'] }}</p>
                                <p class="text-[9px] text-gray-400">dBm</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- ============================================================
             COLUMNA DERECHA — ATENCIÓN AL REPORTE (editable)
        ============================================================ --}}
        <div class="lg:col-span-8 space-y-5">

            {{-- ── CAMBIO DE DOMICILIO: Cobro ────────────────────────── --}}
            @if($esCambioDom)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <i class="ri-money-dollar-circle-line text-amber-600 text-xl"></i>
                    <div>
                        <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Cobro por Cambio de Domicilio</p>
                        <p class="text-[10px] text-amber-600">Este servicio tiene costo según tarifas registradas</p>
                    </div>
                    <span class="ml-auto text-lg font-black text-amber-800">{{ '$' . number_format($reporte['costo_cambio_dom'] ?? 0, 2) }}</span>
                </div>
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-amber-700 uppercase tracking-widest">Confirmar método de pago cobrado</label>
                    <select wire:model="metodoPagoCambioDom"
                            class="w-full bg-white border border-amber-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-amber-500/20">
                        <option value="efectivo">Efectivo</option>
                        <option value="tarjeta">Tarjeta</option>
                        <option value="transferencia">Transferencia</option>
                    </select>
                </div>
            </div>
            @endif

            {{-- ── CAMBIO DE EQUIPO ─────────────────────────────────── --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="cambioEquipo"
                               class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <div>
                            <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">¿Requiere Cambio de Equipo?</p>
                            <p class="text-[10px] text-gray-400">Mininodo, ONU u otro equipo del almacén</p>
                        </div>
                    </label>
                </div>

                @if($cambioEquipo)
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Nuevo Equipo del Almacén *</label>
                            <select wire:model.live="equipoNuevo"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="">— Seleccione equipo disponible —</option>
                                <option value="onu-1">ONU HUAWEI HG8310M (Serie: HW2024-001)</option>
                                <option value="onu-2">ONU ZTE F660 (Serie: ZTE2024-045)</option>
                                <option value="mini-1">MININODO ARRIS (Serie: ARR2024-012)</option>
                            </select>
                        </div>

                        @if($tieneInternet)
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Nombre WIFI *</label>
                            <input type="text" wire:model="wifiNuevo"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                   placeholder="TVT_CLIENTE_001">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Contraseña WIFI *</label>
                            <input type="text" wire:model="passwordNuevo"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">VLAN (del catálogo)</label>
                            <select wire:model="vlanNueva"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="">— Seleccione VLAN —</option>
                                <option value="100">VLAN 100</option>
                                <option value="200">VLAN 200</option>
                            </select>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Encapsulamiento (del catálogo)</label>
                            <select wire:model="encapsulamientoNuevo"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="">— Seleccione —</option>
                                <option value="IPoE">IPoE</option>
                                <option value="PPPoE">PPPoE</option>
                            </select>
                        </div>
                        @endif
                    </div>

                    {{-- Aviso comodato + SMS --}}
                    <div class="flex items-start gap-3 bg-indigo-50 border border-indigo-100 rounded-lg p-3.5">
                        <i class="ri-information-line text-indigo-500 text-base flex-shrink-0 mt-0.5"></i>
                        <p class="text-[10px] font-medium text-indigo-700 leading-relaxed">
                            Al guardar la asignación: se generará <strong>comodato automático</strong>, se actualizará el inventario con la devolución del equipo dañado, y se enviará <strong>SMS al responsable de sucursal</strong> para preparar el equipo. Una vez listo, el técnico recibirá SMS de confirmación.
                        </p>
                    </div>

                    <button wire:click="guardarCambioEquipo"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-sm transition-all active:scale-95">
                        <i class="ri-save-line"></i> Guardar Asignación de Equipo
                    </button>
                </div>
                @endif
            </div>

            {{-- ── LECTURAS TÉCNICAS ────────────────────────────────── --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-line-chart-line text-indigo-500"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Lecturas Técnicas</p>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        @if($esCambioDom)
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Metros de Acometida *</label>
                            <div class="relative">
                                <input type="number" wire:model="metrosAcometida"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 pr-12 text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                       placeholder="120">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">mts</span>
                            </div>
                            @error('metrosAcometida') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                        </div>
                        @endif

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Potencia Salida NAP *</label>
                            <div class="relative">
                                <input type="text" wire:model="potenciaNap"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 pr-12 text-sm font-bold font-mono focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                       placeholder="-18.5">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">dBm</span>
                            </div>
                            @error('potenciaNap') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Potencia Antes Equipo *</label>
                            <div class="relative">
                                <input type="text" wire:model="potenciaEquipo"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 pr-12 text-sm font-bold font-mono focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                       placeholder="-20.1">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">dBm</span>
                            </div>
                            @error('potenciaEquipo') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                        </div>

                        @if($tieneInternet)
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Velocidad Registrada</label>
                            <div class="relative">
                                <input type="text" wire:model="velocidadRegistrada"
                                       class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 pr-14 text-sm font-bold font-mono focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400"
                                       placeholder="100">
                                <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400">Mbps</span>
                            </div>
                        </div>
                        @endif

                    </div>
                </div>
            </div>

            {{-- ── CHECKLIST DE VERIFICACIÓN ────────────────────────── --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-checkbox-multiple-line text-indigo-500"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Checklist de Verificación</p>
                </div>
                <div class="p-5 space-y-2">

                    {{-- TV: canales --}}
                    @if($tieneTV)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-violet-50 border-b border-violet-100 px-4 py-2">
                            <p class="text-[9px] font-black text-violet-600 uppercase tracking-widest flex items-center gap-1.5"><i class="ri-tv-2-line"></i> Televisión</p>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                <input type="checkbox" wire:model="pruebaCanales"
                                       class="h-5 w-5 text-violet-600 rounded border-gray-300 focus:ring-violet-500">
                                <span class="text-xs font-semibold text-gray-700">Prueba de canales exitosa</span>
                            </label>
                            <div class="flex items-center gap-3 p-3">
                                <label class="text-xs font-semibold text-gray-600 flex-shrink-0">Canales detectados:</label>
                                <input type="number" wire:model="cantidadCanales"
                                       class="w-20 py-1.5 px-3 text-sm font-black text-center bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-violet-500/20"
                                       placeholder="0">
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Internet: checklist ONU --}}
                    @if($tieneInternet)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-sky-50 border-b border-sky-100 px-4 py-2">
                            <p class="text-[9px] font-black text-sky-600 uppercase tracking-widest flex items-center gap-1.5"><i class="ri-wifi-line"></i> Internet / ONU</p>
                        </div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-2">
                            @php
                                $checklistInternet = [
                                    ['model' => 'ledsVerdes',           'label' => 'LEDs en verde / PON encendido'],
                                    ['model' => 'detectoWifiOriginal',  'label' => 'Detectó red WIFI original'],
                                    ['model' => 'configuroWifiDefault', 'label' => 'Configuró WIFI por default'],
                                    ['model' => 'accesoInternet',       'label' => 'Confirmar acceso a internet'],
                                    ['model' => 'confirmoOlt',          'label' => 'Confirmó OLT asignada'],
                                    ['model' => 'confirmoPon',          'label' => 'Confirmó PON asignada'],
                                ];
                            @endphp
                            @foreach($checklistInternet as $item)
                            <label class="flex items-center gap-3 cursor-pointer p-2.5 rounded-lg hover:bg-gray-50 transition-colors">
                                <input type="checkbox" wire:model="{{ $item['model'] }}"
                                       class="h-5 w-5 text-sky-600 rounded border-gray-300 focus:ring-sky-500">
                                <span class="text-xs font-semibold text-gray-700">{{ $item['label'] }}</span>
                            </label>
                            @endforeach

                            {{-- Nueva contraseña --}}
                            <div class="md:col-span-2 border-t border-gray-100 pt-3 mt-1">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-1.5">
                                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Nueva contraseña asignada</label>
                                        <input type="text" wire:model="asignoNuevaPass"
                                               class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-sm font-bold focus:ring-2 focus:ring-sky-500/20"
                                               placeholder="Opcional">
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Velocidad registrada</label>
                                        <div class="relative">
                                            <input type="text" wire:model="velocidadRegistrada"
                                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 pr-14 text-sm font-bold font-mono focus:ring-2 focus:ring-sky-500/20"
                                                   placeholder="100">
                                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-[9px] font-black text-gray-400">Mbps</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Tiempo automático + calificación --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-center">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Horas de atención</p>
                            <p class="text-2xl font-black text-gray-900 font-mono">{{ $horasAtencion ?? '—' }}</p>
                            <p class="text-[9px] text-gray-400 font-medium">calculado automáticamente</p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-3">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">Calificación del servicio</p>
                            <select wire:model="calificacion"
                                    class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-3 text-xs font-black uppercase focus:ring-2 focus:ring-indigo-500/20">
                                <option value="Excelente">⭐ Excelente</option>
                                <option value="Bueno">👍 Bueno</option>
                                <option value="Malo">👎 Malo</option>
                            </select>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ── SUSPENSIÓN FALTA DE PAGO ─────────────────────────── --}}
            @if($esSuspension)
            <div class="bg-red-50 border border-red-200 rounded-xl overflow-hidden">
                <div class="bg-red-100 border-b border-red-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-indeterminate-circle-line text-red-600 text-lg"></i>
                    <p class="text-[11px] font-black text-red-800 uppercase tracking-widest">Ejecución de Suspensión</p>
                </div>
                <div class="p-5 space-y-3">
                    @if($tieneInternet)
                    <label class="flex items-center gap-3 p-3 bg-white border border-red-200 rounded-lg cursor-pointer hover:bg-red-50 transition-colors">
                        <input type="checkbox" wire:model="confirmacionWinbox"
                               class="h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                        <div>
                            <p class="text-xs font-black text-gray-800 uppercase">Desconexión lógica en Winbox</p>
                            <p class="text-[10px] text-gray-500">Confirmar corte en gestor de red</p>
                        </div>
                    </label>
                    <label class="flex items-center gap-3 p-3 bg-white border border-red-200 rounded-lg cursor-pointer hover:bg-red-50 transition-colors">
                        <input type="checkbox" wire:model="confirmacionOLT"
                               class="h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                        <div>
                            <p class="text-xs font-black text-gray-800 uppercase">Desconexión lógica en OLT</p>
                            <p class="text-[10px] text-gray-500">Confirmar bloqueo en OLT</p>
                        </div>
                    </label>
                    @endif
                    <label class="flex items-center gap-3 p-3 bg-white border border-red-200 rounded-lg cursor-pointer hover:bg-red-50 transition-colors">
                        <input type="checkbox" wire:model="confirmacionDesconexionFisica"
                               class="h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                        <div>
                            <p class="text-xs font-black text-gray-800 uppercase">Desconexión física en NAP</p>
                            <p class="text-[10px] text-gray-500">Técnico retira conector físicamente</p>
                        </div>
                    </label>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Puerto de NAP liberado</label>
                        <input type="text" wire:model="salidaNapLibre"
                               class="w-full bg-white border border-red-200 rounded-lg py-2.5 px-4 text-sm font-bold focus:ring-2 focus:ring-red-500/20 focus:border-red-400"
                               placeholder="Ej. Salida 4">
                    </div>
                    <button wire:click="cerrarSuspension"
                            class="w-full py-3.5 bg-red-600 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-red-700 shadow-sm transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-pause-circle-line text-base"></i> Finalizar Suspensión del Servicio
                    </button>
                </div>
            </div>
            @endif

            {{-- ── SOLUCIÓN Y CIERRE ────────────────────────────────── --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-medal-line text-emerald-500"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Solución y Cierre del Reporte</p>
                </div>
                <div class="p-5 space-y-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Resolución del problema *</label>
                        <select wire:model="solucionOpcion"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
                            <option value="">— Seleccione una opción —</option>
                            <option value="Conector Dañado">Cambio de conector dañado</option>
                            <option value="Fibra Rota">Reparación de fibra rota</option>
                            <option value="Cambio de Equipo">Cambio de equipo (daño eléctrico)</option>
                            <option value="Reconfiguracion">Reconfiguración de router / ONU</option>
                            <option value="Instalacion">Instalación exitosa</option>
                            <option value="Splitter">Cambio de splitter</option>
                            <option value="Puertos_NAP">Reasignación de puerto NAP</option>
                        </select>
                        @error('solucionOpcion') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Notas del técnico</label>
                        <textarea wire:model="descripcionSolucion" rows="3"
                                  class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-medium resize-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors"
                                  placeholder="Detalles adicionales de la atención..."></textarea>
                    </div>
                </div>

                {{-- Acciones --}}
                <div class="bg-gray-50 border-t border-gray-200 px-5 py-4 flex flex-col sm:flex-row items-center justify-between gap-3">
                    <a href="{{ route('reportes.servicio') }}"
                       class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                        <i class="ri-arrow-left-line"></i> Volver a bandeja
                    </a>
                    <div class="flex gap-3">
                        <button wire:click="guardarPrecierre"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-black text-xs uppercase tracking-widest rounded-lg hover:bg-gray-50 shadow-sm transition-all active:scale-95">
                            <i class="ri-save-line"></i> Guardar Avance
                        </button>
                        <button wire:click="cerrarReporte"
                                class="inline-flex items-center gap-2 px-7 py-2.5 bg-emerald-600 text-white font-black text-xs uppercase tracking-widest rounded-lg hover:bg-emerald-700 shadow-md shadow-emerald-200 transition-all active:scale-95">
                            <i class="ri-check-double-line"></i> Cierre Total
                        </button>
                    </div>
                </div>
            </div>

        </div>{{-- /columna derecha --}}
    </div>{{-- /grid --}}

</div>