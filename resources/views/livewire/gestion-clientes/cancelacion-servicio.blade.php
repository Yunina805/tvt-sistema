
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================ ENCABEZADO ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-red-600">Cancelación de Servicio</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Baja Definitiva de <span class="text-red-600">Servicio</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Cierre de expediente · Recuperación de equipos · Liberación de red</p>
        </div>
        <div class="flex items-center gap-3 self-start">
            {{-- Indicador de paso --}}
            <div class="hidden sm:flex items-center gap-1.5">
                @foreach([1 => 'Buscar', 2 => 'Validar', 3 => 'Reporte'] as $n => $label)
                <div class="flex items-center gap-1.5">
                    <div class="flex items-center gap-1 {{ $paso >= $n ? 'text-red-600' : 'text-gray-300' }}">
                        <span class="w-5 h-5 rounded-full text-[9px] font-black flex items-center justify-center border-2
                            {{ $paso > $n ? 'bg-red-600 border-red-600 text-white' : ($paso == $n ? 'border-red-600 text-red-600' : 'border-gray-300 text-gray-300') }}">
                            {{ $paso > $n ? '✓' : $n }}
                        </span>
                        <span class="text-[9px] font-black uppercase tracking-widest">{{ $label }}</span>
                    </div>
                    @if($n < 3)<i class="ri-arrow-right-s-line text-gray-300 text-xs"></i>@endif
                </div>
                @endforeach
            </div>
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group">
                <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel
            </a>
        </div>
    </div>

    {{-- ================================================================
         PASO 1 — BUSCAR SUSCRIPTOR
    ================================================================ --}}
    @if($paso == 1)
    <div class="max-w-2xl mx-auto space-y-4">

        {{-- Buscador --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-red-600 px-6 py-5 text-center">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="ri-user-unfollow-line text-white text-2xl"></i>
                </div>
                <p class="text-[11px] font-black text-white uppercase tracking-widest">Proceso de Baja Definitiva</p>
                <p class="text-[10px] text-red-200 mt-1 font-medium">Localizar suscriptor para iniciar la cancelación</p>
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
                <p class="text-[10px] text-gray-400 font-medium text-center">El sistema validará automáticamente estado de cuenta e historial técnico</p>
            </div>
        </div>

        {{-- Resultados --}}
        @if(count($resultados))
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex items-center gap-2">
                <i class="ri-list-check text-indigo-500 text-sm"></i>
                <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">{{ count($resultados) }} resultado(s) encontrado(s)</p>
            </div>
            <div class="divide-y divide-gray-100">
                @foreach($resultados as $r)
                <button wire:click="seleccionarCliente({{ json_encode($r) }})"
                        class="w-full text-left px-5 py-4 hover:bg-red-50/40 transition-colors group">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="ri-user-line text-red-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-black text-gray-800 uppercase tracking-tight">{{ $r['nombre'] }}</p>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="font-mono text-[9px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded">{{ $r['id'] }}</span>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase">{{ $r['servicio_actual'] }}</span>
                                    <span class="text-[9px] font-bold text-gray-400">· {{ $r['sucursal'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($r['saldo'] > 0)
                            <span class="text-[10px] font-black text-red-600 bg-red-50 border border-red-100 px-2 py-0.5 rounded-md">
                                Adeudo ${{ number_format($r['saldo'], 2) }}
                            </span>
                            @else
                            <span class="text-[10px] font-black text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md">
                                Sin adeudo ✓
                            </span>
                            @endif
                            <p class="text-[9px] text-gray-300 font-bold mt-1 group-hover:text-red-400 transition-colors">Seleccionar →</p>
                        </div>
                    </div>
                </button>
                @endforeach
            </div>
        </div>
        @endif

    </div>
    @endif

    {{-- ================================================================
         PASO 2 — VALIDACIÓN DE SALDO + ASIGNACIÓN DE TÉCNICO
    ================================================================ --}}
    @if($paso == 2 && $cliente)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Panel izquierdo: datos del suscriptor --}}
        <div class="lg:col-span-7 space-y-4">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-900 px-5 py-3.5 flex items-center justify-between">
                    <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Solicitud de Cancelación</p>
                    <span class="text-[9px] font-black text-gray-400 uppercase">Paso 2 de 3 — Validación</span>
                </div>
                <div class="p-5 space-y-4">

                    <div>
                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">Titular del Contrato</p>
                        <h3 class="text-xl font-black text-gray-800 uppercase tracking-tight">{{ $cliente['nombre'] }}</h3>
                        <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $cliente['sucursal'] }} · {{ $cliente['id'] }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Servicio Activo</p>
                            <p class="text-xs font-black text-indigo-600 uppercase">{{ $cliente['servicio_actual'] }}</p>
                        </div>
                        <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Estado Operativo</p>
                            <div class="flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                                <p class="text-xs font-black text-emerald-600 uppercase">{{ $cliente['estado_actual'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Domicilio del Servicio</p>
                        <p class="text-xs text-gray-700 font-medium">{{ $cliente['domicilio'] }}</p>
                        <p class="text-[10px] text-gray-400 italic mt-0.5">{{ $cliente['referencias'] }}</p>
                    </div>

                    {{-- Saldo con semáforo --}}
                    <div class="rounded-xl overflow-hidden border-2 {{ $cliente['saldo'] > 0 ? 'border-red-200 bg-red-600' : 'border-emerald-200 bg-emerald-600' }}">
                        <div class="px-5 py-4 text-white relative overflow-hidden">
                            <div class="absolute -right-3 -bottom-3 opacity-10 font-black italic text-7xl leading-none">$</div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-[9px] font-black uppercase tracking-widest opacity-70 mb-1">
                                        {{ $cliente['saldo'] > 0 ? '⚠ Saldo deudor pendiente' : '✅ Saldo liquidado' }}
                                    </p>
                                    <p class="text-3xl font-black tracking-tight">${{ number_format($cliente['saldo'], 2) }}</p>
                                </div>
                                <i class="{{ $cliente['saldo'] > 0 ? 'ri-error-warning-fill' : 'ri-checkbox-circle-fill' }} text-4xl opacity-30"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Panel derecho: acción --}}
        <div class="lg:col-span-5">
            @if($cliente['saldo'] > 0)
            {{-- BLOQUEADO por adeudo --}}
            <div class="bg-white border-2 border-red-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-red-50 px-6 py-7 text-center">
                    <div class="w-14 h-14 bg-white border border-red-100 rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-4">
                        <i class="ri-lock-line text-red-500 text-2xl"></i>
                    </div>
                    <p class="text-base font-black text-red-800 uppercase tracking-tight">Cancelación Bloqueada</p>
                    <p class="text-[10px] text-red-500 font-bold uppercase tracking-widest mt-1">Adeudo pendiente</p>
                </div>
                <div class="px-6 pb-6 space-y-4">
                    <div class="bg-red-50 border border-red-100 rounded-lg p-3">
                        <p class="text-[10px] text-red-700 font-bold leading-relaxed">
                            El suscriptor debe tener saldo <strong>$0.00</strong> para proceder con la baja.
                            Liquide el adeudo en el módulo de pagos antes de continuar.
                        </p>
                    </div>
                    <a href="{{ route('pago.mensualidad') }}"
                       class="flex items-center justify-center gap-2 w-full py-3.5 bg-red-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-red-700 shadow-md shadow-red-200 transition-all active:scale-95">
                        <i class="ri-coin-line text-base"></i> Ir a Cobro de Mensualidad
                    </a>
                    <button wire:click="$set('paso', 1)"
                            class="flex items-center justify-center gap-1.5 w-full text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors">
                        <i class="ri-arrow-left-line"></i> Elegir otro suscriptor
                    </button>
                </div>
            </div>

            @else
            {{-- SIN adeudo — asignar técnico y continuar --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-emerald-50 border-b border-emerald-100 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-checkbox-circle-fill text-emerald-500"></i>
                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Sin Adeudo — Puede Proceder</p>
                </div>
                <div class="p-5 space-y-5">

                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Técnico Responsable del Retiro *</label>
                        <div class="relative">
                            <i class="ri-user-star-line absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                            <select wire:model.live="tecnicoAsignado"
                                    class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-xs font-black uppercase focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-colors">
                                <option value="">— Seleccione responsable —</option>
                                @foreach($catalogoTecnicos as $t)
                                <option value="{{ $t['id'] }}">{{ $t['nombre'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($tecnicoAsignado)
                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-3 flex items-center gap-2">
                        <i class="ri-message-2-line text-indigo-500 text-sm flex-shrink-0"></i>
                        <p class="text-[10px] font-bold text-indigo-700">
                            Al generar el reporte se enviará <strong>SMS automático</strong> al técnico con datos del suscriptor y domicilio.
                        </p>
                    </div>
                    @endif

                    <button wire:click="generarReporteBaja"
                            @if(!$tecnicoAsignado) disabled @endif
                            class="w-full py-3.5 bg-gray-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black shadow-md transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <i class="ri-file-warning-line text-base"></i> Generar Reporte de Cancelación
                    </button>

                    <button wire:click="$set('paso', 1)"
                            class="flex items-center justify-center gap-1.5 w-full text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors">
                        <i class="ri-arrow-left-line"></i> Elegir otro suscriptor
                    </button>
                </div>
            </div>
            @endif
        </div>

    </div>
    @endif

    {{-- ================================================================
         PASO 3 — FORMULARIO DEL REPORTE DE CANCELACIÓN
    ================================================================ --}}
    @if($paso == 3 && $cliente)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ============================================================ COLUMNA IZQUIERDA — Expediente ============================================================ --}}
        <div class="lg:col-span-4 space-y-4">

            {{-- Datos del reporte --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-900 px-5 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ri-file-warning-line text-red-400 text-sm"></i>
                        <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Reporte de Cancelación</p>
                    </div>
                    <span class="font-mono text-xs font-black text-white">{{ $folioReporte }}</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @php
                        $filas = [
                            ['icon' => 'ri-calendar-event-line', 'label' => 'Fecha / Hora',      'value' => $fechaReporte,              'mono' => true],
                            ['icon' => 'ri-building-2-line',     'label' => 'Sucursal',           'value' => $cliente['sucursal'],       'bold' => true],
                            ['icon' => 'ri-user-line',           'label' => 'Titular',            'value' => $cliente['nombre'],         'bold' => true],
                            ['icon' => 'ri-map-pin-line',        'label' => 'Domicilio',          'value' => $cliente['domicilio'],      'italic' => true],
                            ['icon' => 'ri-map-pin-2-line',      'label' => 'Referencias',        'value' => $cliente['referencias'],   'italic' => true],
                            ['icon' => 'ri-wifi-line',           'label' => 'Servicio',           'value' => $cliente['servicio_actual'],'bold' => true],
                            ['icon' => 'ri-user-star-line',      'label' => 'Técnico Asignado',   'value' => $tecnicoAsignado,           'badge' => 'indigo'],
                        ];
                    @endphp
                    @foreach($filas as $f)
                    <div class="flex items-start gap-3 px-4 py-2.5">
                        <i class="{{ $f['icon'] }} text-gray-300 text-sm flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-[8px] font-bold text-gray-400 uppercase tracking-widest">{{ $f['label'] }}</p>
                            @if(isset($f['badge']))
                                <span class="text-[10px] font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md uppercase">{{ $f['value'] }}</span>
                            @elseif(isset($f['mono']))
                                <p class="font-mono text-[10px] font-black text-gray-700 mt-0.5">{{ $f['value'] }}</p>
                            @elseif(isset($f['bold']))
                                <p class="text-xs font-black text-gray-900 uppercase mt-0.5">{{ $f['value'] }}</p>
                            @else
                                <p class="text-[10px] text-gray-500 italic leading-relaxed mt-0.5">{{ $f['value'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Infraestructura + Equipo --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-4 py-3">
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Infraestructura y Activos</p>
                </div>
                <div class="divide-y divide-gray-100">
                    <div class="px-4 py-3">
                        <p class="text-[8px] font-black text-indigo-500 uppercase tracking-widest mb-1">NAP de Conexión</p>
                        <p class="text-xs font-black text-gray-800 uppercase">{{ $cliente['nap'] }}</p>
                        <p class="text-[10px] text-gray-400 italic mt-0.5">{{ $cliente['dir_nap'] }}</p>
                    </div>
                    <div class="px-4 py-3">
                        <p class="text-[8px] font-black text-red-500 uppercase tracking-widest mb-1">Activo a Recuperar</p>
                        <p class="text-xs font-black text-gray-900 uppercase">{{ $cliente['equipo_asignado'] }}</p>
                        <p class="font-mono text-[10px] text-indigo-600 font-black mt-0.5">{{ $cliente['serie_registrada'] }}</p>
                    </div>
                    @if($tieneInternet && $cliente['ip_asignada'] !== '—')
                    <div class="px-4 py-3 bg-blue-50/50">
                        <p class="text-[8px] font-black text-blue-500 uppercase tracking-widest mb-1">Red — Internet</p>
                        <div class="space-y-0.5">
                            <p class="font-mono text-[10px] font-black text-gray-700">IP: {{ $cliente['ip_asignada'] }}</p>
                            <p class="text-[9px] text-gray-400 font-bold">OLT: {{ $cliente['olt'] }} · {{ $cliente['pon'] }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Seguimiento del reporte --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-4 py-3">
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Seguimiento</p>
                </div>
                <div class="p-4 grid grid-cols-2 gap-3">
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-center">
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-1">Horas Transcurridas</p>
                        <p class="text-2xl font-black text-gray-900 font-mono">{{ $horasAtencion }}</p>
                        <p class="text-[9px] text-gray-400 font-medium">automático</p>
                    </div>
                    <div class="border border-gray-200 rounded-xl p-3">
                        <p class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-2">Calificación</p>
                        <select wire:model="calificacion"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg py-1.5 px-2 text-xs font-black uppercase focus:ring-2 focus:ring-indigo-500/20">
                            <option value="Excelente">⭐ Excelente</option>
                            <option value="Bueno">👍 Bueno</option>
                            <option value="Malo">👎 Malo</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        {{-- ============================================================ COLUMNA DERECHA — Formulario técnico ============================================================ --}}
        <div class="lg:col-span-8 space-y-5">

            {{-- ─── A. RECUPERACIÓN DE EQUIPOS ─────────────────────────────────────────────── --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-router-line text-red-500 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">A. Recuperación de Equipo en Comodato</p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- Radio: Sí / No --}}
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="recuperaEquipo" value="si" class="sr-only">
                            <div class="text-center border-2 rounded-xl p-4 transition-all cursor-pointer
                                        {{ $recuperaEquipo === 'si' ? 'border-emerald-500 bg-emerald-50' : 'border-gray-200 hover:border-emerald-200' }}">
                                <i class="ri-checkbox-circle-line block text-2xl mb-1.5 {{ $recuperaEquipo === 'si' ? 'text-emerald-500' : 'text-gray-300' }}"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest {{ $recuperaEquipo === 'si' ? 'text-emerald-700' : 'text-gray-400' }}">
                                    SÍ — Equipo Recuperado
                                </p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" wire:model.live="recuperaEquipo" value="no" class="sr-only">
                            <div class="text-center border-2 rounded-xl p-4 transition-all cursor-pointer
                                        {{ $recuperaEquipo === 'no' ? 'border-red-500 bg-red-50' : 'border-gray-200 hover:border-red-200' }}">
                                <i class="ri-close-circle-line block text-2xl mb-1.5 {{ $recuperaEquipo === 'no' ? 'text-red-500' : 'text-gray-300' }}"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest {{ $recuperaEquipo === 'no' ? 'text-red-700' : 'text-gray-400' }}">
                                    NO — No Recuperado
                                </p>
                            </div>
                        </label>
                    </div>

                    @error('recuperaEquipo')
                    <p class="text-[10px] text-red-500 font-black flex items-center gap-1">
                        <i class="ri-error-warning-line"></i> {{ $message }}
                    </p>
                    @enderror

                    {{-- CASO A: Sí recuperado — validar serie --}}
                    @if($recuperaEquipo === 'si')
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 space-y-3">
                        <p class="text-[9px] font-black text-emerald-700 uppercase tracking-widest">Caso A — Validación de Serie del Equipo</p>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest">Serie registrada en sistema</label>
                            <p class="font-mono text-sm font-black text-indigo-700 bg-white border border-indigo-100 px-4 py-2 rounded-lg">
                                {{ $cliente['serie_registrada'] }}
                            </p>
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-600 uppercase tracking-widest">Serie física recuperada (escaneo o manual) *</label>
                            <input type="text" wire:model="serieConfirmada"
                                   class="w-full bg-white border border-emerald-300 rounded-lg font-mono text-sm uppercase py-2.5 px-4 font-black tracking-widest text-indigo-700 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-400"
                                   placeholder="SERIE DEL EQUIPO FÍSICO...">
                        </div>
                        @if($serieConfirmada && $serieConfirmada !== $cliente['serie_registrada'])
                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 flex items-start gap-2">
                            <i class="ri-alert-line text-amber-600 text-sm flex-shrink-0 mt-0.5"></i>
                            <div>
                                <p class="text-[10px] font-black text-amber-800 uppercase">Serie diferente detectada</p>
                                <p class="text-[10px] text-amber-700 font-medium mt-0.5">
                                    La serie no coincide con el registro. El sistema buscará la nueva serie y reasignará el historial al suscriptor antes del cierre.
                                </p>
                            </div>
                        </div>
                        @elseif($serieConfirmada && $serieConfirmada === $cliente['serie_registrada'])
                        <div class="flex items-center gap-2 text-[10px] font-black text-emerald-700">
                            <i class="ri-checkbox-circle-fill text-emerald-500"></i> Serie confirmada — coincide con el registro
                        </div>
                        @endif
                    </div>
                    @endif

                    {{-- CASO B: No recuperado — pago por pérdida --}}
                    @if($recuperaEquipo === 'no')
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 space-y-3">
                        <p class="text-[9px] font-black text-red-700 uppercase tracking-widest">Caso B — Equipo No Recuperado</p>
                        <p class="text-[10px] text-red-600 font-medium leading-relaxed">
                            Para continuar, el suscriptor debe pagar el valor del equipo perdido.
                            Si no paga, el reporte se mantiene en proceso mediante Precierre.
                        </p>
                        <label class="flex items-start gap-3 cursor-pointer bg-white border border-red-200 rounded-lg p-3.5">
                            <input type="checkbox" wire:model.live="pagoPerdida"
                                   class="mt-0.5 h-5 w-5 text-red-600 rounded border-red-300 focus:ring-0 flex-shrink-0">
                            <div>
                                <p class="text-[11px] font-black text-red-900 uppercase tracking-widest">
                                    Pago de equipo por pérdida confirmado ✓
                                </p>
                                <p class="text-[10px] text-red-600 font-medium mt-0.5">
                                    El suscriptor pagó el valor del equipo. El activo será liberado del inventario
                                    con estado <strong>PAGADO POR PÉRDIDA</strong>.
                                </p>
                            </div>
                        </label>
                        @error('pagoPerdida')
                        <p class="text-[10px] text-red-600 font-black flex items-center gap-1">
                            <i class="ri-error-warning-line"></i> {{ $message }}
                        </p>
                        @enderror
                        @if(!$pagoPerdida)
                        <div class="flex items-start gap-2 bg-amber-50 border border-amber-100 rounded-lg p-3">
                            <i class="ri-information-line text-amber-600 text-sm flex-shrink-0"></i>
                            <p class="text-[10px] text-amber-700 font-bold">
                                Si el suscriptor no paga, use <strong>Guardar Precierre</strong> con motivo "No paga equipo perdido".
                                El reporte quedará en proceso hasta resolver.
                            </p>
                        </div>
                        @endif
                    </div>
                    @endif

                </div>
            </div>

            {{-- ─── B. INFRAESTRUCTURA NAP + DESCONEXIÓN FÍSICA ─────────────────────── --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-signal-tower-line text-indigo-500 text-sm"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">B. Infraestructura NAP — Desconexión Física</p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- Info NAP (automático) --}}
                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg p-3 flex items-center gap-3">
                        <i class="ri-signal-tower-line text-indigo-500 text-xl flex-shrink-0"></i>
                        <div>
                            <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest">NAP del Servicio — Dirección Automática</p>
                            <p class="text-sm font-black text-gray-800 uppercase">{{ $cliente['nap'] }}</p>
                            <p class="text-[10px] text-gray-500 font-medium italic">{{ $cliente['dir_nap'] }}</p>
                        </div>
                    </div>

                    {{-- Confirmar desconexión --}}
                    <label class="flex items-center gap-3 p-3.5 bg-white border border-red-200 rounded-lg cursor-pointer hover:bg-red-50 transition-colors">
                        <input type="checkbox" wire:model.live="desconexionFisica" class="h-5 w-5 text-red-600 rounded border-gray-300 focus:ring-red-500">
                        <div>
                            <p class="text-xs font-black text-gray-800 uppercase">Confirmar desconexión física del servicio en NAP</p>
                            <p class="text-[10px] text-gray-500 mt-0.5">El técnico retiró el conector de la salida asignada</p>
                        </div>
                    </label>
                    @error('desconexionFisica')
                    <p class="text-[10px] text-red-500 font-black flex items-center gap-1">
                        <i class="ri-error-warning-line"></i> {{ $message }}
                    </p>
                    @enderror

                    {{-- Puerto NAP liberado --}}
                    <div class="space-y-1.5">
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">
                            Confirmar salida NAP liberada
                        </label>
                        <input type="text" wire:model="salidaNapLibre"
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg text-sm py-2.5 px-4 font-black uppercase focus:ring-2 focus:ring-red-500/20 focus:border-red-400 placeholder:text-gray-300"
                               placeholder="Ej: Salida #4">
                        <p class="text-[9px] text-gray-400">El inventario de salidas NAP se actualizará al confirmar el cierre</p>
                    </div>

                </div>
            </div>

            {{-- ─── C. RED (solo si tiene Internet) ─────────────────────────────────── --}}
            @if($tieneInternet)
            <div class="bg-white border border-blue-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-blue-50 border-b border-blue-200 px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-computer-line text-blue-600 text-sm"></i>
                    <p class="text-[11px] font-black text-blue-800 uppercase tracking-widest">C. Acciones Administrativas de Red — Internet</p>
                </div>
                <div class="p-5 space-y-4">

                    {{-- IP info --}}
                    <div class="bg-blue-50 border border-blue-100 rounded-lg p-3 flex items-center gap-3">
                        <i class="ri-global-line text-blue-500 text-xl flex-shrink-0"></i>
                        <div>
                            <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest">IP Asignada al Suscriptor</p>
                            <p class="font-mono text-sm font-black text-gray-800">{{ $cliente['ip_asignada'] }}</p>
                            <p class="text-[9px] text-gray-400 font-bold mt-0.5">{{ $cliente['olt'] }} · {{ $cliente['pon'] }}</p>
                        </div>
                    </div>

                    {{-- Checklist Winbox + OLT --}}
                    <div class="space-y-2">
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1">Confirmar bajas en sistema de red</p>

                        <label class="flex items-center gap-3 p-3 bg-white border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                            <input type="checkbox" wire:model.live="bajaWinboxNombre" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                            <div>
                                <p class="text-xs font-black text-gray-800 uppercase">Baja de nombre del suscriptor en Winbox</p>
                                <p class="text-[10px] text-gray-500">Usuario eliminado del gestor de red MikroTik</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 bg-white border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                            <input type="checkbox" wire:model.live="bajaWinboxPlan" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                            <div>
                                <p class="text-xs font-black text-gray-800 uppercase">Baja de plan de datos en Winbox</p>
                                <p class="text-[10px] text-gray-500">Plan de velocidad desvinculado del perfil</p>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-3 bg-white border border-blue-200 rounded-lg cursor-pointer hover:bg-blue-50 transition-colors">
                            <input type="checkbox" wire:model.live="bajaOLT" class="h-5 w-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                            <div>
                                <p class="text-xs font-black text-gray-800 uppercase">Baja de datos del suscriptor en OLT</p>
                                <p class="text-[10px] text-gray-500">Puerto liberado en la OLT · sesión terminada</p>
                            </div>
                        </label>

                        @error('bajaOLT')
                        <p class="text-[10px] text-red-500 font-black flex items-center gap-1">
                            <i class="ri-error-warning-line"></i> {{ $message }}
                        </p>
                        @enderror
                    </div>

                </div>
            </div>
            @endif

            {{-- ─── D. PRECIERRE — Motivo ────────────────────────────────────────────── --}}
            <div class="bg-white border border-amber-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-amber-50 border-b border-amber-200 px-5 py-3.5 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ri-save-3-line text-amber-600 text-sm"></i>
                        <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">D. Precierre — Estado: En Proceso</p>
                    </div>
                </div>
                <div class="p-5 space-y-3">
                    <p class="text-[10px] text-amber-700 font-medium">
                        Guarda el avance sin cerrar el reporte. Use cuando no sea posible concluir en esta visita.
                    </p>
                    <div class="space-y-1.5">
                        <label class="block text-[9px] font-black text-gray-500 uppercase tracking-widest">Motivo del precierre</label>
                        <select wire:model="motivoPrecierre"
                                class="w-full bg-white border border-amber-300 rounded-lg py-2.5 px-3 text-xs font-black uppercase focus:ring-2 focus:ring-amber-400/30 focus:border-amber-400">
                            <option value="">— Seleccione motivo —</option>
                            <option value="NO_ENTREGA_EQUIPO">No entrega el equipo</option>
                            <option value="NO_PAGA_EQUIPO_PERDIDO">No paga el equipo perdido</option>
                            <option value="SUSCRIPTOR_AUSENTE">Suscriptor ausente en domicilio</option>
                            <option value="OTRO">Otro — ver notas del técnico</option>
                        </select>
                        @error('motivoPrecierre')
                        <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p>
                        @enderror
                    </div>
                    <button wire:click="guardarPrecierre"
                            class="w-full py-2.5 bg-white border border-amber-300 text-amber-700 font-black text-[10px] uppercase tracking-widest rounded-lg hover:bg-amber-50 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-save-line"></i> Guardar Precierre
                    </button>
                </div>
            </div>

            {{-- ─── E. CIERRE TÉCNICO ────────────────────────────────────────────────── --}}
            <div class="bg-white border {{ $tecnicoCompletado ? 'border-emerald-300' : 'border-gray-200' }} rounded-xl shadow-sm overflow-hidden">
                <div class="{{ $tecnicoCompletado ? 'bg-emerald-50 border-b border-emerald-200' : 'bg-gray-50 border-b border-gray-200' }} px-5 py-3.5 flex items-center gap-2">
                    @if($tecnicoCompletado)
                    <i class="ri-checkbox-circle-fill text-emerald-600 text-base"></i>
                    <p class="text-[11px] font-black text-emerald-800 uppercase tracking-widest">E. Cierre Técnico — Completado</p>
                    @else
                    <i class="ri-tools-line text-gray-600 text-base"></i>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">E. Cierre Técnico</p>
                    @endif
                </div>

                @if(!$tecnicoCompletado)
                <div class="p-5 space-y-3">
                    <p class="text-[10px] text-gray-500 font-medium leading-relaxed">
                        Al confirmar el cierre técnico se registrarán todas las acciones: equipo, NAP, y red (si aplica).
                        El cierre administrativo se habilitará una vez completado este paso.
                    </p>
                    @error('tecnicoCompletado')
                    <p class="text-[10px] text-red-500 font-black flex items-center gap-1.5">
                        <i class="ri-error-warning-line"></i> {{ $message }}
                    </p>
                    @enderror
                    <button wire:click="confirmarCierreTecnico"
                            class="w-full py-3 bg-gray-800 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-gray-900 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-save-3-line text-base"></i> Guardar Cierre Técnico
                    </button>
                </div>
                @else
                <div class="px-5 py-3 bg-emerald-50 flex items-center gap-2">
                    <i class="ri-checkbox-circle-fill text-emerald-600 text-base"></i>
                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Acciones técnicas registradas correctamente</p>
                </div>
                @endif
            </div>

            {{-- ─── F. CIERRE ADMINISTRATIVO ─────────────────────────────────────────── --}}
            <div class="bg-white border {{ $tecnicoCompletado ? 'border-red-300' : 'border-gray-200 opacity-50' }} rounded-xl shadow-sm overflow-hidden">
                <div class="{{ $tecnicoCompletado ? 'bg-red-600' : 'bg-gray-200' }} px-5 py-3.5 flex items-center gap-2">
                    <i class="ri-shield-check-line {{ $tecnicoCompletado ? 'text-white' : 'text-gray-500' }} text-base"></i>
                    <p class="text-[11px] font-black {{ $tecnicoCompletado ? 'text-white' : 'text-gray-500' }} uppercase tracking-widest">
                        F. Cierre Administrativo — Sucursal
                    </p>
                    @if(!$tecnicoCompletado)
                    <span class="ml-auto text-[9px] font-black bg-gray-300 text-gray-600 px-2 py-0.5 rounded uppercase">
                        Requiere cierre técnico primero
                    </span>
                    @endif
                </div>

                @if($tecnicoCompletado)
                <div class="p-5 space-y-4">

                    {{-- Condición obligatoria --}}
                    <div class="bg-gray-50 border border-gray-100 rounded-lg p-3">
                        <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-2">Condición para el cierre</p>
                        @if($recuperaEquipo === 'si')
                        <div class="flex items-center gap-2 text-[10px] font-black text-emerald-700">
                            <i class="ri-checkbox-circle-fill text-emerald-500"></i>
                            Equipo recuperado físicamente en sucursal ✓
                        </div>
                        @elseif($pagoPerdida)
                        <div class="flex items-center gap-2 text-[10px] font-black text-emerald-700">
                            <i class="ri-checkbox-circle-fill text-emerald-500"></i>
                            Pago de equipo por pérdida confirmado ✓
                        </div>
                        @endif
                    </div>

                    {{-- Acciones del sistema --}}
                    <div class="bg-red-50 border border-red-100 rounded-lg p-4">
                        <p class="text-[9px] font-black text-red-700 uppercase tracking-widest mb-3">Acciones del sistema al confirmar</p>
                        <ul class="space-y-1.5">
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-user-forbid-line text-red-500"></i>
                                Cambiar estado del suscriptor → <span class="text-red-600 font-black">CANCELADO</span>
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-signal-tower-line text-red-500"></i>
                                Liberar salida NAP → marcar como disponible en inventario
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-router-line text-red-500"></i>
                                Actualizar estatus del equipo en inventario
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-pause-circle-line text-amber-500"></i>
                                Cerrar ciclo de facturación del suscriptor
                            </li>
                            <li class="flex items-center gap-2 text-[10px] font-bold text-gray-700">
                                <i class="ri-message-2-line text-indigo-500"></i>
                                Enviar SMS de confirmación de cancelación al suscriptor
                            </li>
                        </ul>
                    </div>

                    <button @click="$confirm(
                                '¿Confirmar cierre administrativo? El suscriptor quedará CANCELADO y se liberarán todos los recursos asignados.',
                                () => $wire.finalizarCancelacion(),
                                { confirmText: 'Sí, aplicar cancelación', title: 'Cierre Administrativo', icon: 'warning' }
                            )"
                            class="w-full py-3.5 bg-red-600 text-white font-black text-xs uppercase tracking-widest rounded-xl hover:bg-red-700 shadow-md shadow-red-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <i class="ri-close-circle-line text-base"></i> Aplicar Cancelación — Cierre Total
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
                    <i class="ri-arrow-left-line"></i> Volver a bandeja de reportes
                </a>
            </div>

        </div>
    </div>
    @endif

</div>
