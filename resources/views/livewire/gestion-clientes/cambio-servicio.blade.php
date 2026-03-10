<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ENCABEZADO --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-red-400"></i>
                <span>Gestión al Suscriptor</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-red-600">Cambio de Servicio</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Cambio de Servicio — Cliente Activo</h2>
            <p class="text-xs text-gray-400 mt-0.5">Servicio → Suscriptor → Cobro → Recibo → Contrato → Equipo → Reporte</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 font-bold text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all self-start">
            <i class="ri-arrow-left-line"></i> Panel Principal
        </a>
    </div>

    {{-- STEPPER --}}
    @if($paso < 7)
    <div class="mb-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex">
            @php
                $pasos = [
                    1 => ['label' => 'Servicio',    'icon' => 'ri-exchange-line'],
                    2 => ['label' => 'Suscriptor',  'icon' => 'ri-user-search-line'],
                    3 => ['label' => 'Cobro',        'icon' => 'ri-secure-payment-line'],
                    4 => ['label' => 'Recibo',       'icon' => 'ri-file-text-line'],
                    5 => ['label' => 'Contrato',     'icon' => 'ri-pen-nib-line'],
                    6 => ['label' => 'Equipo',       'icon' => 'ri-tools-line'],
                ];
            @endphp
            @foreach($pasos as $num => $info)
            <div class="flex-1 flex flex-col items-center py-3 px-1 {{ !$loop->last ? 'border-r border-gray-200' : '' }}
                {{ $paso >= $num ? 'bg-red-50' : 'bg-gray-50' }}">
                <div class="w-7 h-7 rounded-full flex items-center justify-center mb-1
                    {{ $paso > $num ? 'bg-red-600 text-white' : ($paso == $num ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-400') }}">
                    @if($paso > $num)
                        <i class="ri-check-line text-xs"></i>
                    @else
                        <i class="{{ $info['icon'] }} text-xs"></i>
                    @endif
                </div>
                <span class="text-[9px] font-bold uppercase tracking-wider hidden sm:block
                    {{ $paso >= $num ? 'text-red-700' : 'text-gray-400' }}">
                    {{ $info['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 1 — IDENTIFICACIÓN + SELECCIÓN DE NUEVO SERVICIO
    ================================================================ --}}
    @if($paso === 1)
    <div class="space-y-5">

        {{-- Identificación --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-id-card-line text-red-500"></i> Validación de Identidad del Suscriptor
            </h3>
            <p class="text-xs text-gray-500 mb-4">Solicita la credencial de elector o identificación oficial vigente y carga la fotografía del documento.</p>
            <div class="flex flex-col sm:flex-row gap-4 items-start">
                <label class="flex flex-col items-center justify-center w-full sm:w-48 h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all overflow-hidden">
                    @if($fotoIdentificacion)
                        <img src="{{ $fotoIdentificacion->temporaryUrl() }}" class="h-full w-full object-cover" alt="ID">
                    @else
                        <i class="ri-upload-cloud-2-line text-2xl text-gray-400 mb-1"></i>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Cargar foto de ID</span>
                        <span class="text-[9px] text-gray-300 mt-0.5">JPG, PNG · máx 5 MB</span>
                    @endif
                    <input type="file" class="hidden" wire:model="fotoIdentificacion" accept="image/*">
                </label>
                <div class="flex-1 text-xs text-gray-500 space-y-1 pt-2">
                    <p class="flex items-center gap-1.5"><i class="ri-checkbox-circle-line text-emerald-500"></i> Credencial de Elector (IFE/INE)</p>
                    <p class="flex items-center gap-1.5"><i class="ri-checkbox-circle-line text-emerald-500"></i> Pasaporte vigente</p>
                    <p class="flex items-center gap-1.5"><i class="ri-checkbox-circle-line text-emerald-500"></i> Cédula profesional</p>
                    <p class="flex items-center gap-1.5 text-amber-600 mt-2"><i class="ri-information-line"></i> La identificación debe ser legible y vigente.</p>
                </div>
            </div>
        </div>

        {{-- Selección del Nuevo Servicio --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-exchange-line text-red-500"></i> Selección del Nuevo Servicio
            </h3>

            @if(empty($tarifas))
                <div class="text-center py-10 text-gray-400">
                    <i class="ri-price-tag-line text-3xl mb-2 block"></i>
                    <p class="text-xs">No hay tarifas disponibles para contratar.</p>
                    <p class="text-[10px] text-gray-300 mt-1">Agrega tarifas con estado "VIGENTE_CONTRATAR" en el catálogo Financiero.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($tarifas as $tarifa)
                    @php
                        $tipo = $this->inferirTipoServicio($tarifa['nombre_comercial'] ?? '');
                        $colorBadge = match($tipo) {
                            'TV+INTERNET' => 'bg-purple-100 text-purple-700',
                            'INTERNET'    => 'bg-blue-100 text-blue-700',
                            default       => 'bg-orange-100 text-orange-700',
                        };
                        $iconoBadge = match($tipo) {
                            'TV+INTERNET' => 'ri-broadband-line',
                            'INTERNET'    => 'ri-wifi-line',
                            default       => 'ri-tv-line',
                        };
                    @endphp
                    <button wire:click="seleccionarTarifa({{ $tarifa['id'] }})"
                            class="text-left p-4 rounded-xl border-2 transition-all
                                {{ $tarifaId == $tarifa['id']
                                    ? 'border-red-500 bg-red-50 shadow-md'
                                    : 'border-gray-200 hover:border-red-300 hover:bg-red-50' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center gap-1 text-[9px] font-black px-2 py-0.5 rounded-full {{ $colorBadge }}">
                                <i class="{{ $iconoBadge }}"></i> {{ $tipo }}
                            </span>
                            @if($tarifaId == $tarifa['id'])
                                <i class="ri-checkbox-circle-fill text-red-500"></i>
                            @else
                                <i class="ri-checkbox-blank-circle-line text-gray-300"></i>
                            @endif
                        </div>
                        <p class="text-sm font-black text-gray-900 leading-tight mb-1">{{ $tarifa['nombre_comercial'] }}</p>
                        @if($tarifa['descripcion'])
                            <p class="text-[10px] text-gray-500 mb-3 leading-relaxed">{{ $tarifa['descripcion'] }}</p>
                        @endif
                        <div class="space-y-1 border-t border-gray-100 pt-2">
                            <div class="flex justify-between text-[10px]">
                                <span class="text-gray-500">Instalación</span>
                                <span class="font-black text-gray-800">${{ number_format($tarifa['precio_instalacion'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-[10px]">
                                <span class="text-gray-500">Mensualidad</span>
                                <span class="font-black text-gray-800">${{ number_format($tarifa['precio_mensualidad'], 2) }}</span>
                            </div>
                        </div>
                    </button>
                    @endforeach
                </div>
            @endif

            {{-- Estimado del primer pago (sin suscriptor aún) --}}
            @if($tarifaId)
            <div class="mt-5 bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-calculator-line text-red-500"></i> Estimado de Primer Pago
                    <span class="text-[9px] font-bold text-amber-600 bg-amber-50 border border-amber-100 px-2 py-0.5 rounded ml-auto">
                        Se ajusta al identificar al suscriptor
                    </span>
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                        <p class="text-[9px] text-gray-400 uppercase tracking-wider mb-1">Días restantes</p>
                        <p class="text-xl font-black text-red-600">{{ $diasRestantes }}</p>
                        <p class="text-[9px] text-gray-400">día {{ now()->day }} al 30</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                        <p class="text-[9px] text-gray-400 uppercase tracking-wider mb-1">Instalación</p>
                        <p class="text-xl font-black text-gray-800">${{ number_format($tarifaSeleccionada['precio_instalacion'], 2) }}</p>
                        <p class="text-[9px] text-gray-400">cargo único</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                        <p class="text-[9px] text-gray-400 uppercase tracking-wider mb-1">Días de uso</p>
                        <p class="text-xl font-black text-gray-800">${{ number_format($costoDiasNueva, 2) }}</p>
                        <p class="text-[9px] text-gray-400">{{ $diasRestantes }} días proporcional</p>
                    </div>
                    <div class="bg-red-600 text-white rounded-lg p-3">
                        <p class="text-[9px] uppercase tracking-wider mb-1 opacity-80">Total Estimado</p>
                        <p class="text-xl font-black">${{ number_format($totalPagar, 2) }}</p>
                        <p class="text-[9px] opacity-70">incluye IVA ${{ number_format($iva, 2) }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="flex justify-end">
            <button wire:click="irPaso2"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                Identificar Suscriptor <i class="ri-arrow-right-line"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 2 — IDENTIFICAR SUSCRIPTOR ACTIVO
    ================================================================ --}}
    @if($paso === 2)
    <div class="space-y-4">

        {{-- Tarifa seleccionada como contexto --}}
        @if(!empty($tarifaSeleccionada))
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="ri-exchange-line text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-[9px] text-gray-400 uppercase tracking-wider">Nuevo servicio seleccionado</p>
                        <p class="text-sm font-black text-gray-900">{{ $tarifaSeleccionada['nombre_comercial'] }}</p>
                        <p class="text-[10px] text-gray-500">
                            Instalación: ${{ number_format($tarifaSeleccionada['precio_instalacion'], 2) }} ·
                            Mensualidad: ${{ number_format($tarifaSeleccionada['precio_mensualidad'], 2) }}
                        </p>
                    </div>
                </div>
                <button x-data
                        @click="$confirm('¿Deseas regresar a cambiar el servicio?', () => $wire.set('paso', 1), { title: 'Cambiar servicio', confirmText: 'Sí, cambiar', icon: 'question' })"
                        class="text-[10px] text-red-600 font-bold cursor-pointer flex items-center gap-1 hover:underline flex-shrink-0">
                    <i class="ri-refresh-line"></i> Cambiar
                </button>
            </div>
        </div>
        @endif

        {{-- Buscador de suscriptor --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                <i class="ri-user-search-line text-red-500"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Buscar Suscriptor Activo</p>
                <span class="ml-auto text-[9px] font-bold text-gray-400 bg-white border border-gray-200 px-2 py-1 rounded-md uppercase">Solo suscriptores activos</span>
            </div>
            <div class="p-5 space-y-4">

                @if(empty($suscriptor))
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" wire:model="busqueda"
                               wire:keydown.enter="buscar"
                               placeholder="Nombre, teléfono o ID del suscriptor..."
                               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-colors placeholder:text-gray-300">
                    </div>
                    <button wire:click="buscar"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm">
                        Buscar
                    </button>
                </div>
                @endif

                {{-- Resultados --}}
                @if(!empty($resultados))
                <div class="space-y-2">
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">
                        {{ count($resultados) }} resultado(s) — selecciona al suscriptor
                    </p>
                    @foreach($resultados as $r)
                    @php
                        $estadoClass = match($r['estado'] ?? 'ACTIVO') {
                            'ACTIVO'     => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'SUSPENDIDO' => 'bg-amber-100 text-amber-700 border-amber-200',
                            default      => 'bg-gray-100 text-gray-600 border-gray-200',
                        };
                    @endphp
                    <button wire:click="seleccionarSuscriptor({{ $r['id'] }})"
                            class="w-full text-left p-4 rounded-xl border-2 border-gray-200 hover:border-red-400 hover:bg-red-50/50 transition-all">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex-1 min-w-0">
                                <p class="font-black text-gray-900 text-sm uppercase">{{ $r['nombre'] }}</p>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1.5">
                                    <span class="flex items-center gap-1 text-[10px] font-bold text-gray-400 uppercase">
                                        <i class="ri-hashtag text-gray-300"></i> {{ $r['clave'] }}
                                    </span>
                                    <span class="flex items-center gap-1 text-[10px] font-bold text-gray-400 uppercase">
                                        <i class="ri-phone-line text-gray-300"></i> {{ $r['telefono'] }}
                                    </span>
                                    <span class="flex items-center gap-1 text-[10px] font-bold text-gray-400 uppercase">
                                        <i class="ri-map-pin-line text-orange-400"></i> {{ $r['domicilio'] }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-1.5 flex-shrink-0">
                                <span class="text-[9px] font-black uppercase tracking-widest border px-2 py-0.5 rounded-md {{ $estadoClass }}">
                                    {{ $r['estado'] }}
                                </span>
                                <span class="text-[9px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md uppercase">
                                    {{ $r['tarifa_actual'] }}
                                </span>
                                <span class="text-[9px] font-bold text-gray-400 uppercase">{{ $r['tipo_servicio'] }}</span>
                            </div>
                        </div>
                    </button>
                    @endforeach
                </div>
                @elseif($busquedaRealizada && empty($suscriptor))
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <i class="ri-search-line text-amber-500 text-xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Sin resultados</p>
                        <p class="text-[10px] text-amber-600 mt-0.5">No se encontró ningún suscriptor activo con ese criterio.</p>
                    </div>
                </div>
                @endif

                {{-- Suscriptor confirmado --}}
                @if(!empty($suscriptor))
                <div class="border-2 border-red-300 bg-red-50/30 rounded-xl p-5 space-y-3">
                    <div class="flex items-start justify-between gap-3">
                        <span class="text-[9px] font-black text-red-600 bg-red-100 border border-red-200 px-2 py-1 rounded-md uppercase tracking-widest">
                            Suscriptor identificado
                        </span>
                        <span class="font-mono text-xs font-black text-gray-600 bg-white border border-gray-200 px-2.5 py-1 rounded-md">
                            {{ $suscriptor['clave'] }}
                        </span>
                    </div>
                    <div>
                        <p class="text-lg font-black text-gray-900 uppercase tracking-tight">{{ $suscriptor['nombre'] }}</p>
                        <div class="flex flex-wrap gap-x-5 gap-y-1.5 mt-2">
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 uppercase">
                                <i class="ri-phone-line text-gray-400"></i> {{ $suscriptor['telefono'] }}
                            </span>
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 uppercase">
                                <i class="ri-map-pin-line text-orange-400"></i> {{ $suscriptor['domicilio'] }}
                            </span>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                        <div class="bg-white border border-gray-100 rounded-lg p-2.5">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Tarifa Actual</p>
                            <p class="text-xs font-black text-gray-800">{{ $suscriptor['tarifa_actual'] }}</p>
                            <p class="text-[9px] text-gray-500">${{ number_format($suscriptor['tarifa_actual_precio'] ?? 0, 2) }}/mes</p>
                        </div>
                        <div class="bg-white border border-gray-100 rounded-lg p-2.5">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Pago Mes Actual</p>
                            @if($suscriptor['pagado_mes'] ?? false)
                                <p class="text-xs font-black text-emerald-600 flex items-center gap-1"><i class="ri-checkbox-circle-fill"></i> Pagado</p>
                                <p class="text-[9px] text-emerald-500">Aplica saldo a favor</p>
                            @else
                                <p class="text-xs font-black text-amber-600 flex items-center gap-1"><i class="ri-time-line"></i> Pendiente</p>
                                <p class="text-[9px] text-amber-500">Paga proporcional normal</p>
                            @endif
                        </div>
                        <div class="bg-white border border-gray-100 rounded-lg p-2.5">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Equipo Activo</p>
                            <p class="text-[10px] font-bold text-gray-700">{{ $suscriptor['equipo_activo'] ?? '—' }}</p>
                        </div>
                    </div>

                    {{-- Saldo calculado --}}
                    @if($saldoFavorCliente > 0)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 flex items-start gap-2">
                        <i class="ri-coins-line text-emerald-500 text-base mt-0.5 flex-shrink-0"></i>
                        <div>
                            <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Saldo a Favor del Suscriptor</p>
                            <p class="text-[10px] text-emerald-700 mt-0.5">
                                Tiene <strong>${{ number_format($saldoFavorCliente, 2) }}</strong> de crédito por {{ $diasRestantes }} días ya pagados
                                en su tarifa actual (${{ number_format($suscriptor['tarifa_actual_precio'] ?? 0, 2) }}/mes).
                                @if($diferenciaDias > 0)
                                    La tarifa nueva cuesta <strong>${{ number_format($diferenciaDias, 2) }}</strong> más por estos días.
                                @else
                                    El saldo a favor cubre los días de la nueva tarifa — sin cobro proporcional.
                                @endif
                            </p>
                        </div>
                    </div>
                    @endif

                    <button wire:click="limpiarSuscriptor"
                            class="text-[10px] font-black text-gray-400 hover:text-gray-600 uppercase tracking-widest transition-colors flex items-center gap-1">
                        <i class="ri-close-circle-line"></i> Cambiar suscriptor
                    </button>
                </div>
                @endif
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-5 py-3.5 flex items-center justify-between">
                <button wire:click="$set('paso', 1)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Cambiar servicio
                </button>
                <button wire:click="irPaso3"
                        @if(empty($suscriptor)) disabled @endif
                        class="inline-flex items-center gap-2 px-6 py-2.5 bg-red-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-sm transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed">
                    Confirmar y Continuar <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </div>

    </div>
    @endif

    {{-- ================================================================
         PASO 3 — CONFIRMACIÓN DE PAGO
    ================================================================ --}}
    @if($paso === 3)
    <div class="space-y-5">

        {{-- Resumen del suscriptor --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-user-check-line text-red-500"></i> Suscriptor Identificado
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Nombre</p>
                    <p class="font-black text-gray-900">{{ $suscriptor['nombre'] ?? '' }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Clave</p>
                    <p class="font-mono font-black text-gray-700">{{ $suscriptor['clave'] ?? '' }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Teléfono</p>
                    <p class="font-bold text-gray-700">{{ $suscriptor['telefono'] ?? '' }}</p>
                </div>
                <div class="sm:col-span-2">
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Domicilio del Servicio</p>
                    <p class="font-bold text-gray-700">{{ $suscriptor['domicilio'] ?? '' }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Sucursal</p>
                    <p class="font-bold text-gray-700">{{ $suscriptor['sucursal'] ?? '' }}</p>
                </div>
            </div>
        </div>

        {{-- Servicio + Método de pago --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Desglose de cobro --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-exchange-line text-red-500"></i> Cambio de Servicio
                </h3>

                {{-- De → A --}}
                <div class="flex items-center gap-3 mb-4 p-3 bg-gray-50 border border-gray-100 rounded-xl">
                    <div class="flex-1 text-center">
                        <p class="text-[9px] text-gray-400 uppercase tracking-widest mb-0.5">Servicio Actual</p>
                        <p class="text-xs font-black text-gray-600">{{ $suscriptor['tarifa_actual'] ?? '—' }}</p>
                        <p class="text-[10px] text-gray-500">${{ number_format($suscriptor['tarifa_actual_precio'] ?? 0, 2) }}/mes</p>
                    </div>
                    <i class="ri-arrow-right-line text-red-400 text-lg flex-shrink-0"></i>
                    <div class="flex-1 text-center">
                        <p class="text-[9px] text-red-500 uppercase tracking-widest mb-0.5">Servicio Nuevo</p>
                        <p class="text-xs font-black text-red-700">{{ $tarifaSeleccionada['nombre_comercial'] ?? '—' }}</p>
                        <p class="text-[10px] text-red-500">${{ number_format($tarifaSeleccionada['precio_mensualidad'] ?? 0, 2) }}/mes</p>
                    </div>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Cargo de instalación</span>
                        <span class="font-bold">${{ number_format($instalacion, 2) }}</span>
                    </div>

                    @if($saldoFavorCliente > 0)
                    {{-- Saldo favor / en contra --}}
                    <div class="border-t border-dashed border-gray-200 pt-2">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1.5">Ajuste por Días de Uso ({{ $diasRestantes }} días)</p>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Costo proporcional nueva tarifa</span>
                            <span class="font-bold">${{ number_format($costoDiasNueva, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-emerald-600">
                            <span class="font-bold">Saldo a favor (tarifa anterior)</span>
                            <span class="font-bold">— ${{ number_format($saldoFavorCliente, 2) }}</span>
                        </div>
                        @if($diferenciaDias > 0)
                        <div class="flex justify-between font-bold text-amber-700 bg-amber-50 -mx-1 px-1 py-0.5 rounded mt-0.5">
                            <span>Diferencia a cobrar</span>
                            <span>${{ number_format($diferenciaDias, 2) }}</span>
                        </div>
                        @else
                        <div class="flex justify-between font-bold text-emerald-700 bg-emerald-50 -mx-1 px-1 py-0.5 rounded mt-0.5">
                            <span>Saldo a favor (cubre días nuevos)</span>
                            <span>$0.00</span>
                        </div>
                        @endif
                    </div>
                    @else
                    <div class="flex justify-between">
                        <span class="text-gray-500">Proporcional {{ $diasRestantes }} días</span>
                        <span class="font-bold">${{ number_format($costoDiasNueva, 2) }}</span>
                    </div>
                    @endif

                    <div class="border-t border-gray-100 pt-1 flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-bold">${{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($descuentoAplicado)
                    <div class="flex justify-between text-emerald-600">
                        <span class="font-bold">Descuento aplicado</span>
                        <span class="font-bold">— ${{ number_format($montoDescuento, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal con descuento</span>
                        <span class="font-bold">${{ number_format($subtotalDescuento, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">IVA (16%)</span>
                        <span class="font-bold">${{ number_format($ivaDescuento, 2) }}</span>
                    </div>
                    @else
                    <div class="flex justify-between">
                        <span class="text-gray-500">IVA (16%)</span>
                        <span class="font-bold">${{ number_format($iva, 2) }}</span>
                    </div>
                    @endif

                    <div class="border-t-2 border-red-200 pt-2 flex justify-between">
                        <span class="font-black text-gray-900">Total a Cobrar</span>
                        <span class="font-black text-red-600 text-base">
                            ${{ $descuentoAplicado ? number_format($totalConDescuento, 2) : number_format($totalPagar, 2) }}
                        </span>
                    </div>
                </div>

                {{-- Descuento supervisor --}}
                <div class="mt-4 border-t border-gray-100 pt-3" x-data="{ open: {{ $mostrarDescuento ? 'true' : 'false' }} }">
                    @if($descuentoAplicado)
                        <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-lg p-2.5">
                            <span class="text-[10px] text-emerald-700 font-bold flex items-center gap-1.5">
                                <i class="ri-price-tag-3-line"></i> Descuento de ${{ number_format($montoDescuento, 2) }} aplicado
                            </span>
                            <button wire:click="quitarDescuento" class="text-[10px] text-red-500 font-bold hover:underline">
                                Quitar
                            </button>
                        </div>
                    @else
                        <button @click="open = !open"
                                class="text-[10px] text-gray-500 font-bold flex items-center gap-1.5 hover:text-red-600 transition-colors">
                            <i class="ri-percent-line"></i> Aplicar descuento de supervisor
                            <i class="ri-arrow-down-s-line transition-transform" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="mt-2 space-y-2">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-1">Monto ($)</label>
                                    <div class="relative">
                                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold">$</span>
                                        <input type="number" wire:model="montoDescuentoInput" min="0.01" step="0.01"
                                               class="w-full border border-gray-300 rounded-lg pl-6 pr-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                                               placeholder="0.00">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-1">Contraseña supervisor</label>
                                    <input type="password" wire:model="passwordDescuento"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                                           placeholder="••••••••"
                                           wire:keydown.enter="aplicarDescuento">
                                </div>
                            </div>
                            <button wire:click="aplicarDescuento"
                                    class="w-full py-1.5 bg-gray-900 hover:bg-gray-700 text-white text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">
                                Aplicar Descuento
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Cambiar servicio --}}
                <div class="mt-3 border-t border-gray-100 pt-3">
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider mb-1.5">Cambiar nuevo servicio antes del pago</p>
                    <select wire:change="cambiarServicio($event.target.value)"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300 text-gray-600">
                        @foreach($tarifas as $t)
                            <option value="{{ $t['id'] }}" {{ $tarifaId == $t['id'] ? 'selected' : '' }}>
                                {{ $t['nombre_comercial'] }} — ${{ number_format($t['precio_mensualidad'], 2) }}/mes
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Método de pago --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-secure-payment-line text-red-500"></i> Método de Pago
                </h3>
                <div class="space-y-2">
                    @foreach([
                        'efectivo'      => ['icon' => 'ri-money-dollar-circle-line', 'label' => 'Efectivo'],
                        'transferencia' => ['icon' => 'ri-bank-line',                'label' => 'Transferencia Bancaria'],
                        'tarjeta'       => ['icon' => 'ri-bank-card-line',           'label' => 'Tarjeta'],
                    ] as $val => $mp)
                    <button wire:click="seleccionarMetodoPago('{{ $val }}')"
                            class="w-full flex items-center gap-3 p-3 border rounded-xl transition-all text-left
                                {{ $metodoPago === $val
                                    ? 'border-red-500 bg-red-50 shadow-sm'
                                    : 'border-gray-200 hover:border-red-300 hover:bg-red-50' }}">
                        <i class="{{ $mp['icon'] }} text-lg {{ $metodoPago === $val ? 'text-red-600' : 'text-gray-400' }}"></i>
                        <span class="text-xs font-bold flex-1 {{ $metodoPago === $val ? 'text-red-700' : 'text-gray-700' }}">
                            {{ $mp['label'] }}
                        </span>
                        <i class="{{ $metodoPago === $val ? 'ri-checkbox-circle-fill text-red-500' : 'ri-checkbox-blank-circle-line text-gray-300' }}"></i>
                    </button>
                    @endforeach
                </div>

                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-3">
                    <p class="text-[10px] text-amber-700 font-bold flex items-center gap-1.5 mb-1.5">
                        <i class="ri-alert-line"></i> Al confirmar el ingreso el sistema:
                    </p>
                    <ul class="text-[10px] text-amber-600 space-y-0.5 list-disc list-inside">
                        <li>Actualiza la tarifa del suscriptor</li>
                        <li>Registra el ingreso en caja de la sucursal</li>
                        <li>Genera el recibo digital</li>
                        <li>Cambia el estado a Pendiente de Instalación</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button wire:click="$set('paso', 2)"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                <i class="ri-arrow-left-line"></i> Regresar
            </button>
            <div class="flex items-center gap-3">
                <button x-data
                        @click="$confirm('¿Cancelar el cambio de servicio? No se guardará ningún registro.', () => $wire.set('paso', 1), { title: 'Cancelar proceso', confirmText: 'Sí, cancelar', icon: 'warning' })"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-red-300 text-red-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-50 transition-all">
                    <i class="ri-close-line"></i> Cancelar
                </button>
                <button wire:click="confirmarIngreso"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                    <i class="ri-check-double-line"></i> Confirmar Cambio
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 4 — RECIBO DIGITAL
    ================================================================ --}}
    @if($paso === 4)
    <div class="space-y-5">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            <div class="bg-gray-900 text-white p-5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Recibo Digital de Pago</p>
                    <p class="text-lg font-black tracking-tight">TU VISIÓN TELECABLE</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">RFC: XAXX010101000 · Av. Principal #123, Veracruz, México</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">No. Recibo</p>
                    <p class="text-sm font-black text-red-400">{{ $folioRecibo }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">{{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="p-5 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-2">Suscriptor</p>
                        <p class="text-sm font-black text-gray-900">{{ $suscriptor['nombre'] ?? '' }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">Tel: {{ $suscriptor['telefono'] ?? '' }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">{{ $suscriptor['domicilio'] ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-2">No. de Suscriptor</p>
                        <div class="inline-flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-2">
                            <i class="ri-user-star-line text-red-500"></i>
                            <span class="text-base font-black text-red-700 tracking-widest">{{ $suscriptor['clave'] ?? '' }}</span>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">
                            Estado: <span class="font-bold text-amber-600">PENDIENTE DE INSTALACIÓN</span>
                        </p>
                        <p class="text-[10px] text-gray-400 mt-0.5">
                            Forma de pago: <span class="font-bold text-gray-600 uppercase">{{ $metodoPago }}</span>
                        </p>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2 text-[9px] font-black uppercase tracking-widest text-gray-500">Concepto</th>
                                <th class="text-right px-4 py-2 text-[9px] font-black uppercase tracking-widest text-gray-500">Importe</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-4 py-2.5 text-gray-700">Cargo de instalación — {{ $tarifaSeleccionada['nombre_comercial'] ?? '' }}</td>
                                <td class="px-4 py-2.5 text-right font-bold">${{ number_format($instalacion, 2) }}</td>
                            </tr>
                            @if($saldoFavorCliente > 0)
                            <tr>
                                <td class="px-4 py-2.5 text-gray-700">
                                    Días de uso nueva tarifa ({{ $diasRestantes }} días)
                                </td>
                                <td class="px-4 py-2.5 text-right font-bold">${{ number_format($costoDiasNueva, 2) }}</td>
                            </tr>
                            <tr class="text-emerald-600">
                                <td class="px-4 py-2.5 font-bold">Saldo a favor — tarifa anterior ({{ $diasRestantes }} días)</td>
                                <td class="px-4 py-2.5 text-right font-bold">— ${{ number_format($saldoFavorCliente, 2) }}</td>
                            </tr>
                            @else
                            <tr>
                                <td class="px-4 py-2.5 text-gray-700">
                                    Prorrateo mensualidad ({{ $diasRestantes }} días — {{ now()->format('d/m') }} al 30/{{ now()->format('m') }})
                                </td>
                                <td class="px-4 py-2.5 text-right font-bold">${{ number_format($costoDiasNueva, 2) }}</td>
                            </tr>
                            @endif
                            @if($descuentoAplicado)
                            <tr class="text-emerald-600">
                                <td class="px-4 py-2.5 font-bold">Descuento aplicado</td>
                                <td class="px-4 py-2.5 text-right font-bold">— ${{ number_format($montoDescuento, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-gray-50 text-xs">
                            <tr>
                                <td class="px-4 py-2 text-gray-500">Subtotal (sin IVA)</td>
                                <td class="px-4 py-2 text-right font-bold">
                                    ${{ $descuentoAplicado ? number_format($subtotalDescuento, 2) : number_format($subtotal, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-gray-500">IVA (16%)</td>
                                <td class="px-4 py-2 text-right font-bold">
                                    ${{ $descuentoAplicado ? number_format($ivaDescuento, 2) : number_format($iva, 2) }}
                                </td>
                            </tr>
                            <tr class="bg-gray-900 text-white">
                                <td class="px-4 py-3 font-black uppercase text-sm">Total Cobrado</td>
                                <td class="px-4 py-3 text-right font-black text-base">
                                    ${{ $descuentoAplicado ? number_format($totalConDescuento, 2) : number_format($totalPagar, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="border-t border-gray-200 px-5 py-4 flex items-center justify-between bg-gray-50">
                <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                    <i class="ri-printer-line"></i> Imprimir Recibo
                </button>
                <button wire:click="$set('paso', 5)"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                    Continuar — Firma del Contrato <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 5 — FIRMA DIGITAL DEL CONTRATO
    ================================================================ --}}
    @if($paso === 5)
    <div class="space-y-5">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden"
             x-data="{
                 drawing: false, vacio: true,
                 canvas: null, ctx: null, lastX: 0, lastY: 0,
                 init() {
                     this.canvas = this.$refs.firmaCanvas;
                     this.ctx = this.canvas.getContext('2d');
                     this.ctx.strokeStyle = '#1e293b';
                     this.ctx.lineWidth = 2;
                     this.ctx.lineCap = 'round';
                     this.ctx.lineJoin = 'round';
                 },
                 getPos(e) {
                     const rect = this.canvas.getBoundingClientRect();
                     const src = e.touches ? e.touches[0] : e;
                     return {
                         x: (src.clientX - rect.left) * (this.canvas.width / rect.width),
                         y: (src.clientY - rect.top)  * (this.canvas.height / rect.height),
                     };
                 },
                 startDraw(e) { e.preventDefault(); this.drawing = true; const p = this.getPos(e); this.lastX = p.x; this.lastY = p.y; },
                 draw(e) {
                     if (!this.drawing) return; e.preventDefault();
                     const p = this.getPos(e);
                     this.ctx.beginPath(); this.ctx.moveTo(this.lastX, this.lastY);
                     this.ctx.lineTo(p.x, p.y); this.ctx.stroke();
                     this.lastX = p.x; this.lastY = p.y; this.vacio = false;
                 },
                 stopDraw() { this.drawing = false; },
                 limpiar() { this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height); this.vacio = true; },
                 confirmar() {
                     if (this.vacio) { alert('Por favor dibuja tu firma para continuar.'); return; }
                     $wire.guardarFirma(this.canvas.toDataURL('image/png'));
                 }
             }">

            <div class="p-5 border-b border-gray-200">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-file-list-3-line text-red-500"></i> Contrato de Cambio de Servicio
                </h3>
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-xs text-gray-700 space-y-2 max-h-48 overflow-y-auto leading-relaxed">
                    <p class="font-black uppercase">Tu Visión Telecable — Modificación de Contrato de Prestación de Servicios</p>
                    <p>Por medio del presente instrumento, <strong>Tu Visión Telecable</strong> y
                    <strong>{{ $suscriptor['nombre'] ?? '' }}</strong> (Suscriptor: <strong>{{ $suscriptor['clave'] ?? '' }}</strong>)
                    acuerdan la modificación del servicio contratado de
                    <strong>{{ $suscriptor['tarifa_actual'] ?? '' }}</strong>
                    al servicio <strong>{{ $tarifaSeleccionada['nombre_comercial'] ?? '' }}</strong>
                    en el domicilio: <strong>{{ $suscriptor['domicilio'] ?? '' }}</strong>.</p>
                    <p>Nueva mensualidad: <strong>${{ number_format($tarifaSeleccionada['precio_mensualidad'] ?? 0, 2) }} MXN</strong> más IVA.
                    Fecha de modificación: <strong>{{ now()->format('d/m/Y') }}</strong>.</p>
                    <p>El suscriptor acepta los términos y condiciones del servicio. Tu Visión Telecable se compromete a realizar
                    la instalación del nuevo servicio en el domicilio indicado.</p>
                    <p class="text-[9px] text-gray-400 border-t border-gray-200 pt-2">
                        Folio de recibo: {{ $folioRecibo }} · Fecha: {{ now()->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <div class="p-5">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2 flex items-center gap-2">
                    <i class="ri-pen-nib-line text-red-500"></i> Firma Digital del Suscriptor
                    <span class="text-[9px] font-bold text-red-500 bg-red-50 border border-red-100 px-1.5 py-0.5 rounded ml-auto">Obligatoria</span>
                </h4>
                <div class="border-2 border-gray-200 rounded-xl overflow-hidden bg-white cursor-crosshair"
                     @mousedown="startDraw($event)"
                     @mousemove="draw($event)"
                     @mouseup="stopDraw()"
                     @mouseleave="stopDraw()"
                     @touchstart="startDraw($event)"
                     @touchmove="draw($event)"
                     @touchend="stopDraw()">
                    <canvas x-ref="firmaCanvas" width="800" height="160" class="w-full h-40"></canvas>
                </div>
                <p class="text-[9px] text-gray-400 mt-1.5 flex items-center gap-1">
                    <i class="ri-information-line"></i> Dibuja tu firma en el recuadro de arriba
                </p>
            </div>

            <div class="border-t border-gray-200 px-5 py-4 flex items-center justify-between bg-gray-50">
                <button @click="limpiar()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                    <i class="ri-eraser-line"></i> Limpiar
                </button>
                <button @click="confirmar()"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                    <i class="ri-check-line"></i> Confirmar Firma y Continuar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 6 — ASIGNACIÓN DE EQUIPO + TÉCNICO
    ================================================================ --}}
    @if($paso === 6)
    <div class="space-y-5">

        {{-- Resumen dark --}}
        <div class="bg-gray-900 rounded-xl p-5 flex items-center justify-between gap-4">
            <div class="min-w-0">
                <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">Cambio de Servicio — Asignación de Equipo</p>
                <p class="text-sm font-black text-white uppercase truncate">{{ $suscriptor['nombre'] ?? '' }}</p>
                <div class="flex items-center gap-2 mt-1.5 flex-wrap">
                    <span class="text-[10px] font-bold text-gray-400 uppercase">{{ $suscriptor['tarifa_actual'] ?? '' }}</span>
                    <i class="ri-arrow-right-line text-red-400 text-xs"></i>
                    <span class="text-[10px] font-black text-red-300 uppercase">{{ $tarifaSeleccionada['nombre_comercial'] ?? '' }}</span>
                </div>
            </div>
            <div class="text-right flex-shrink-0">
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Cobro registrado</p>
                <p class="text-lg font-black text-emerald-400">
                    ${{ number_format($descuentoAplicado ? $totalConDescuento : $totalPagar, 2) }}
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            {{-- Equipo nuevo --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                    <i class="ri-router-line text-indigo-500"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Asignación de Equipo Nuevo</p>
                    <span class="ml-auto text-[9px] font-bold text-red-600 bg-red-50 border border-red-100 px-2 py-1 rounded-md uppercase">Requerido</span>
                </div>
                <div class="p-5 space-y-3">
                    @if(empty($equipos))
                    <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4">
                        <i class="ri-inbox-line text-amber-500 text-xl flex-shrink-0"></i>
                        <p class="text-[10px] text-amber-700 font-bold uppercase tracking-widest">Sin equipos disponibles en inventario</p>
                    </div>
                    @else
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Seleccionar del catálogo de inventario</label>
                        <div class="space-y-2 max-h-44 overflow-y-auto pr-1">
                            @foreach($equipos as $eq)
                            <button wire:click="$set('equipoNuevoId', '{{ $eq['id'] }}')"
                                    class="w-full text-left p-3 rounded-xl border-2 transition-all
                                        {{ $equipoNuevoId === $eq['id']
                                            ? 'border-indigo-500 bg-indigo-50'
                                            : 'border-gray-200 hover:border-indigo-300 hover:bg-indigo-50/40' }}">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg {{ $equipoNuevoId === $eq['id'] ? 'bg-indigo-100' : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
                                        <i class="{{ $eq['tipo'] === 'ONU' ? 'ri-router-line' : 'ri-broadcast-line' }} {{ $equipoNuevoId === $eq['id'] ? 'text-indigo-600' : 'text-gray-400' }} text-sm"></i>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] font-black text-gray-800 uppercase truncate">{{ $eq['descripcion'] }}</p>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase">{{ $eq['tipo'] }}</p>
                                    </div>
                                    @if($equipoNuevoId === $eq['id'])
                                    <i class="ri-checkbox-circle-fill text-indigo-500 flex-shrink-0"></i>
                                    @endif
                                </div>
                            </button>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Recuperación de equipo anterior --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                    <i class="ri-arrow-go-back-line text-amber-500"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Recuperación de Equipo Anterior</p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- Equipo a recuperar --}}
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5">
                        <p class="text-[9px] font-black text-amber-700 uppercase tracking-widest mb-1">Equipo activo del suscriptor</p>
                        <p class="text-xs font-black text-gray-800">{{ $suscriptor['equipo_activo'] ?? '—' }}</p>
                    </div>

                    {{-- Opción recuperar o cobrar --}}
                    <div class="space-y-2">
                        <button wire:click="$set('recuperarEquipo', true)"
                                class="w-full text-left p-3 rounded-xl border-2 transition-all flex items-center gap-3
                                    {{ $recuperarEquipo ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-emerald-300' }}">
                            <i class="ri-checkbox-circle-{{ $recuperarEquipo ? 'fill text-emerald-500' : 'line text-gray-300' }} text-xl flex-shrink-0"></i>
                            <div>
                                <p class="text-xs font-black text-gray-800 uppercase">Recuperar equipo</p>
                                <p class="text-[10px] text-gray-500">El técnico lo recupera en campo · Afecta inventario automáticamente</p>
                            </div>
                        </button>
                        <button wire:click="$set('recuperarEquipo', false)"
                                class="w-full text-left p-3 rounded-xl border-2 transition-all flex items-center gap-3
                                    {{ !$recuperarEquipo ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-300' }}">
                            <i class="ri-checkbox-circle-{{ !$recuperarEquipo ? 'fill text-red-500' : 'line text-gray-300' }} text-xl flex-shrink-0"></i>
                            <div>
                                <p class="text-xs font-black text-gray-800 uppercase">Cobrar valor del equipo</p>
                                <p class="text-[10px] text-gray-500">No se pudo recuperar · Se cobra el costo del equipo al suscriptor</p>
                            </div>
                        </button>
                    </div>

                    @if(!$recuperarEquipo)
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Nota de no recuperación</label>
                        <textarea wire:model="notaRecuperacion" rows="2"
                                  placeholder="Indica el motivo por el que no se pudo recuperar el equipo..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300 resize-none"></textarea>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Técnico --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                <i class="ri-tools-line text-orange-500"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Asignación de Técnico Responsable</p>
                <span class="ml-auto text-[9px] font-bold text-red-600 bg-red-50 border border-red-100 px-2 py-1 rounded-md uppercase">Requerido</span>
            </div>
            <div class="p-5 space-y-4">

                @if(empty($tecnicos))
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <i class="ri-user-unfollow-line text-amber-500 text-xl flex-shrink-0"></i>
                    <p class="text-[10px] text-amber-700 font-bold uppercase tracking-widest">Sin técnicos disponibles · Registra empleados en el módulo RRHH</p>
                </div>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                    @foreach($tecnicos as $t)
                    <button wire:click="$set('tecnicoId', {{ $t['id'] }})"
                            class="text-left p-3.5 rounded-xl border-2 transition-all
                                {{ $tecnicoId == $t['id']
                                    ? 'border-red-500 bg-red-50 shadow-sm'
                                    : 'border-gray-200 hover:border-red-300 hover:bg-red-50/40' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full {{ $tecnicoId == $t['id'] ? 'bg-red-100' : 'bg-gray-100' }} flex items-center justify-center flex-shrink-0">
                                <i class="ri-user-star-line {{ $tecnicoId == $t['id'] ? 'text-red-600' : 'text-gray-400' }} text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-[11px] font-black text-gray-900 uppercase truncate">{{ $t['nombre'] }}</p>
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $t['clave'] }}</p>
                            </div>
                            @if($tecnicoId == $t['id'])
                            <i class="ri-checkbox-circle-fill text-red-500 ml-auto flex-shrink-0"></i>
                            @endif
                        </div>
                    </button>
                    @endforeach
                </div>
                @endif

                {{-- SMS --}}
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center gap-4">
                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                        <input type="checkbox" wire:model="notificarSms"
                               class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <div>
                            <p class="text-[11px] font-black text-indigo-800 uppercase tracking-widest">Notificar al técnico por SMS</p>
                            <p class="text-[10px] text-indigo-500 mt-0.5">Se enviará aviso automático al generar el reporte</p>
                        </div>
                    </label>
                    <i class="ri-message-3-line text-2xl text-indigo-300 flex-shrink-0"></i>
                </div>

                <button wire:click="generarReporte"
                        @if(!$tecnicoId || !$equipoNuevoId) disabled @endif
                        class="w-full py-3.5 bg-red-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-md shadow-red-200 transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <i class="ri-file-text-fill text-base"></i> Generar Reporte de Servicio
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 7 — ÉXITO
    ================================================================ --}}
    @if($paso === 7)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Banner --}}
            <div class="bg-emerald-600 px-8 py-10 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 opacity-10"
                     style="background-image: radial-gradient(circle, white 1px, transparent 0); background-size: 24px 24px;"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="ri-checkbox-circle-fill text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-black uppercase tracking-tight">Cambio de Servicio Completado</h2>
                    <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest mt-1">
                        Cobro registrado · Contrato firmado · Equipo asignado · Reporte generado
                    </p>
                </div>
            </div>

            {{-- Resumen --}}
            <div class="p-6 space-y-4">
                <div class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-white border-b border-gray-100 px-4 py-3">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Resumen de la Operación</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @php
                            $tecnicoSel = collect($tecnicos)->firstWhere('id', $tecnicoId);
                            $equipoSel  = collect($equipos)->firstWhere('id', $equipoNuevoId);
                            $filas = [
                                ['icon' => 'ri-file-text-line',     'label' => 'Folio Reporte',     'value' => $folioReporte,                               'mono' => true, 'color' => 'text-red-600'],
                                ['icon' => 'ri-user-line',          'label' => 'Suscriptor',         'value' => $suscriptor['nombre'] ?? '—'],
                                ['icon' => 'ri-hashtag',            'label' => 'Clave',              'value' => $suscriptor['clave'] ?? '—'],
                                ['icon' => 'ri-exchange-line',      'label' => 'Cambio',             'value' => ($suscriptor['tarifa_actual'] ?? '—').' → '.($tarifaSeleccionada['nombre_comercial'] ?? '—'), 'badge' => 'red'],
                                ['icon' => 'ri-router-line',        'label' => 'Equipo Asignado',    'value' => $equipoSel['descripcion'] ?? '—'],
                                ['icon' => 'ri-user-star-line',     'label' => 'Técnico',            'value' => $tecnicoSel['nombre'] ?? '—'],
                                ['icon' => 'ri-file-text-line',     'label' => 'Recibo',             'value' => $folioRecibo, 'mono' => true, 'color' => 'text-gray-700'],
                                ['icon' => 'ri-coins-line',         'label' => 'Total Cobrado',      'value' => '$'.number_format($descuentoAplicado ? $totalConDescuento : $totalPagar, 2), 'badge' => 'emerald'],
                            ];
                        @endphp
                        @foreach($filas as $f)
                        <div class="flex items-center gap-4 px-4 py-3">
                            <i class="{{ $f['icon'] }} text-gray-400 text-base flex-shrink-0"></i>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest w-28 flex-shrink-0">{{ $f['label'] }}</span>
                            @if(isset($f['badge']) && $f['badge'] === 'red')
                                <span class="text-[10px] font-black text-red-700 bg-red-50 border border-red-100 px-2 py-0.5 rounded-md uppercase">{{ $f['value'] }}</span>
                            @elseif(isset($f['badge']) && $f['badge'] === 'emerald')
                                <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md">{{ $f['value'] }}</span>
                            @elseif(isset($f['mono']))
                                <span class="font-mono font-black {{ $f['color'] ?? 'text-gray-800' }} text-sm tracking-wider">{{ $f['value'] }}</span>
                            @else
                                <span class="text-xs font-bold text-gray-800 uppercase">{{ $f['value'] }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                @if($notificarSms)
                <div class="flex items-center gap-3 bg-indigo-50 border border-indigo-100 rounded-lg px-4 py-3">
                    <i class="ri-message-3-line text-indigo-500 text-base flex-shrink-0"></i>
                    <p class="text-[10px] font-bold text-indigo-700 uppercase tracking-widest">SMS enviado al técnico correctamente</p>
                </div>
                @endif

                <div class="grid grid-cols-2 gap-3">
                    <button wire:click="$set('paso', 1)"
                            class="py-3.5 bg-gray-100 text-gray-700 rounded-xl font-black text-xs uppercase tracking-widest hover:bg-gray-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-add-circle-line"></i> Nuevo Cambio
                    </button>
                    <button wire:click="finalizar"
                            class="py-3.5 bg-gray-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm flex items-center justify-center gap-2">
                        <i class="ri-arrow-right-line"></i> Ver Reportes
                    </button>
                </div>
            </div>

        </div>
    </div>
    @endif

</div>
