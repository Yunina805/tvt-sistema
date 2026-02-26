<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================
         ENCABEZADO
    ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-indigo-600">Servicios Adicionales</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Contratación de Servicios Adicionales
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Ampliación de red, bocas adicionales y servicios premium</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>

    {{-- ================================================================
         STEPPER
    ================================================================ --}}
    <div class="mb-8 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex">
            @php
                $pasos = [
                    1 => ['label' => 'Servicio',  'sub' => 'Selección de extra',    'icon' => 'ri-list-check'],
                    2 => ['label' => 'Cliente',   'sub' => 'Identificar suscriptor','icon' => 'ri-user-search-line'],
                    3 => ['label' => 'Caja',      'sub' => 'Cobro del servicio',    'icon' => 'ri-money-dollar-circle-line'],
                    4 => ['label' => 'Técnico',   'sub' => 'Orden de servicio',     'icon' => 'ri-tools-line'],
                    5 => ['label' => 'Listo',     'sub' => 'Proceso completado',    'icon' => 'ri-checkbox-circle-line'],
                ];
            @endphp

            @foreach($pasos as $num => $p)
                @php
                    $isActive    = $paso === $num;
                    $isCompleted = $paso > $num;
                    $isLast      = $num === count($pasos);
                @endphp
                <div class="flex-1 relative
                    {{ $isActive    ? 'bg-indigo-600' : '' }}
                    {{ $isCompleted ? 'bg-indigo-50'  : '' }}
                    {{ !$isActive && !$isCompleted ? 'bg-white' : '' }}
                    {{ !$isLast ? 'border-r border-gray-200' : '' }}">

                    @if(!$isLast)
                        <div class="absolute right-0 top-0 bottom-0 w-3 z-10 flex items-center justify-end overflow-hidden">
                            <svg viewBox="0 0 12 48" class="h-full w-3 {{ $isActive ? 'text-indigo-600' : ($isCompleted ? 'text-indigo-50' : 'text-white') }}" preserveAspectRatio="none">
                                <path d="M0,0 L8,24 L0,48 L12,48 L12,0 Z" fill="currentColor"/>
                            </svg>
                        </div>
                    @endif

                    <div class="flex items-center gap-2.5 px-4 py-3.5 pr-6">
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center
                            {{ $isActive    ? 'bg-white/20' : '' }}
                            {{ $isCompleted ? 'bg-indigo-100' : '' }}
                            {{ !$isActive && !$isCompleted ? 'bg-gray-100' : '' }}">
                            @if($isCompleted)
                                <i class="ri-check-line text-indigo-600 text-base"></i>
                            @else
                                <i class="{{ $p['icon'] }} text-base {{ $isActive ? 'text-white' : 'text-gray-400' }}"></i>
                            @endif
                        </div>
                        <div class="hidden sm:block min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-widest leading-none
                                {{ $isActive ? 'text-white' : ($isCompleted ? 'text-indigo-600' : 'text-gray-400') }}">
                                {{ $p['label'] }}
                            </p>
                            <p class="text-[9px] mt-0.5 truncate
                                {{ $isActive ? 'text-indigo-200' : ($isCompleted ? 'text-indigo-400' : 'text-gray-300') }}">
                                {{ $p['sub'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Mensaje sesión --}}
    @if(session()->has('mensaje'))
        <div class="mb-5 p-3.5 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200 text-[11px] font-bold uppercase tracking-widest flex items-center gap-2">
            <i class="ri-checkbox-circle-fill text-base text-emerald-500"></i>
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- ================================================================
         PASO 1 — SELECCIÓN DE SERVICIO ADICIONAL
    ================================================================ --}}
    @if($paso == 1)
    <div class="space-y-5">

        <div class="flex items-center justify-between">
            <p class="text-[11px] font-black text-gray-500 uppercase tracking-widest">Catálogo de Servicios Disponibles</p>
            <span class="text-[9px] font-bold text-indigo-500 bg-indigo-50 border border-indigo-100 px-2 py-1 rounded-md uppercase tracking-widest">
                {{ count($serviciosAdicionales) }} disponibles
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($serviciosAdicionales as $key => $s)
            <div wire:click="seleccionarServicio('{{ $key }}')"
                 class="relative bg-white border-2 rounded-xl p-6 transition-all cursor-pointer group
                        {{ isset($servicioKey) && $servicioKey === $key
                            ? 'border-indigo-500 shadow-lg shadow-indigo-100 ring-1 ring-indigo-500/30'
                            : 'border-gray-200 hover:border-indigo-300 hover:shadow-md' }}">

                {{-- Indicador selección --}}
                <div class="absolute top-4 right-4 w-5 h-5 rounded-full border-2 flex items-center justify-center
                            {{ isset($servicioKey) && $servicioKey === $key ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                    @if(isset($servicioKey) && $servicioKey === $key)
                        <i class="ri-check-line text-white text-[10px]"></i>
                    @endif
                </div>

                {{-- Ícono del servicio --}}
                <div class="w-12 h-12 rounded-xl bg-indigo-100 flex items-center justify-center mb-4 group-hover:bg-indigo-200 transition-colors">
                    <i class="{{ $s['icon'] ?? 'ri-add-box-line' }} text-indigo-600 text-2xl"></i>
                </div>

                <h4 class="font-black text-gray-800 text-base uppercase tracking-tight mb-1 pr-6">{{ $s['nombre'] }}</h4>

                @if(isset($s['descripcion']))
                    <p class="text-xs text-gray-500 mb-4">{{ $s['descripcion'] }}</p>
                @endif

                {{-- Precios --}}
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <div class="bg-gray-50 border border-gray-100 rounded-lg px-4 py-3">
                        <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest block mb-0.5">Instalación</span>
                        <p class="text-lg font-black text-gray-900 tracking-tight">${{ number_format($s['instalacion'], 2) }}</p>
                        <p class="text-[9px] text-gray-400 font-medium">pago único</p>
                    </div>
                    <div class="bg-indigo-50 border border-indigo-100 rounded-lg px-4 py-3">
                        <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest block mb-0.5">Mensualidad</span>
                        <p class="text-lg font-black text-indigo-600 tracking-tight">+${{ number_format($s['mensualidad'], 2) }}</p>
                        <p class="text-[9px] text-indigo-400 font-medium">ajuste mensual</p>
                    </div>
                </div>

                {{-- Tags del servicio si existen --}}
                @if(isset($s['tags']))
                <div class="flex flex-wrap gap-1.5 mt-3">
                    @foreach($s['tags'] as $tag)
                        <span class="text-[9px] font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded-md uppercase tracking-widest">{{ $tag }}</span>
                    @endforeach
                </div>
                @endif
            </div>
            @endforeach
        </div>

        {{-- CTA --}}
        @if(isset($servicioKey) && $servicioKey)
        <div class="flex justify-end pt-2">
            <button wire:click="irAPaso(2)"
                    class="inline-flex items-center gap-2 px-8 py-3.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95">
                Identificar Cliente <i class="ri-arrow-right-line"></i>
            </button>
        </div>
        @endif
    </div>
    @endif

    {{-- ================================================================
         PASO 2 — IDENTIFICAR CLIENTE
    ================================================================ --}}
    @if($paso == 2)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Header con resumen del servicio elegido --}}
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <i class="ri-user-search-line text-indigo-600"></i>
                </div>
                <div class="flex-1">
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Identificar Suscriptor</p>
                    <p class="text-[10px] text-gray-400">Busque por nombre, teléfono o ID del cliente</p>
                </div>
                {{-- Servicio elegido como contexto --}}
                @if(isset($servicioSeleccionado) && $servicioSeleccionado)
                <div class="flex items-center gap-1.5 bg-indigo-50 border border-indigo-100 rounded-lg px-3 py-2">
                    <i class="ri-add-box-line text-indigo-500 text-sm"></i>
                    <span class="text-[10px] font-black text-indigo-700 uppercase tracking-wider">{{ $servicioSeleccionado['nombre'] }}</span>
                </div>
                @endif
            </div>

            <div class="p-6 space-y-5">

                {{-- Buscador --}}
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-user-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" wire:model="busquedaCliente"
                               placeholder="Nombre, teléfono o ID..."
                               wire:keydown.enter="buscarCliente"
                               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors placeholder:text-gray-300">
                    </div>
                    <button wire:click="buscarCliente"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm">
                        Buscar
                    </button>
                </div>

                {{-- Resultado de búsqueda --}}
                @if($clienteEncontrado)
                <div class="border-2 border-indigo-200 bg-indigo-50/40 rounded-xl p-5 space-y-4">
                    <div class="flex items-start justify-between">
                        <span class="text-[9px] font-black text-indigo-500 uppercase tracking-widest bg-indigo-100 border border-indigo-200 px-2 py-1 rounded-md">
                            Expediente encontrado
                        </span>
                        <span class="font-mono text-xs font-black text-gray-600 bg-white border border-gray-200 px-2.5 py-1 rounded-md">
                            #{{ $clienteEncontrado['id'] ?? 'S/N' }}
                        </span>
                    </div>

                    <div>
                        <p class="text-lg font-black text-gray-900 uppercase tracking-tight">{{ $clienteEncontrado['nombre'] }}</p>
                        <div class="flex flex-wrap gap-x-5 gap-y-1.5 mt-2">
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 uppercase">
                                <i class="ri-broadcast-line text-indigo-400"></i> {{ $clienteEncontrado['servicio_actual'] }}
                            </span>
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 uppercase">
                                <i class="ri-map-pin-line text-orange-400"></i> {{ $clienteEncontrado['domicilio'] }}
                            </span>
                            @if(isset($clienteEncontrado['sucursal']))
                            <span class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 uppercase">
                                <i class="ri-store-2-line text-gray-400"></i> {{ $clienteEncontrado['sucursal'] }}
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Estado del cliente --}}
                    <div class="flex items-center gap-2">
                        @php
                            $estadoCliente = $clienteEncontrado['estado'] ?? 'Activo';
                            $estadoClass   = match($estadoCliente) {
                                'Activo'     => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'Suspendido' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'Cancelado'  => 'bg-red-100 text-red-700 border-red-200',
                                default      => 'bg-gray-100 text-gray-600 border-gray-200',
                            };
                        @endphp
                        <span class="text-[9px] font-black uppercase tracking-widest {{ $estadoClass }} border px-2.5 py-1 rounded-lg">
                            Estado: {{ $estadoCliente }}
                        </span>
                        @if(isset($clienteEncontrado['adeudo']) && $clienteEncontrado['adeudo'] > 0)
                        <span class="text-[9px] font-black text-red-600 bg-red-50 border border-red-200 px-2.5 py-1 rounded-lg uppercase tracking-widest">
                            <i class="ri-alert-line"></i> Adeudo: ${{ number_format($clienteEncontrado['adeudo'], 2) }}
                        </span>
                        @endif
                    </div>

                    <button wire:click="irAPaso(3)"
                            class="w-full py-3 bg-indigo-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        Confirmar Cliente y Continuar <i class="ri-arrow-right-line"></i>
                    </button>
                </div>

                @elseif(isset($busquedaRealizada) && $busquedaRealizada)
                <div class="flex items-center gap-3 bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <i class="ri-search-line text-amber-500 text-xl flex-shrink-0"></i>
                    <div>
                        <p class="text-[11px] font-black text-amber-800 uppercase tracking-widest">Sin resultados</p>
                        <p class="text-[10px] text-amber-600 mt-0.5">No se encontró ningún cliente con ese criterio de búsqueda</p>
                    </div>
                </div>
                @endif

            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-3.5 flex items-center justify-between">
                <button wire:click="irAPaso(1)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Cambiar servicio
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 3 — CAJA / COBRO
    ================================================================ --}}
    @if($paso == 3)
    <div class="max-w-xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i class="ri-receipt-line text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Liquidación del Servicio</p>
                    <p class="text-[10px] text-gray-400">{{ $clienteEncontrado['nombre'] ?? '' }}</p>
                </div>
            </div>

            {{-- Desglose --}}
            <div class="p-6 space-y-1">

                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Servicio</span>
                    <span class="text-xs font-black text-indigo-600 uppercase">{{ $servicioSeleccionado['nombre'] }}</span>
                </div>

                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-xs font-bold text-gray-600 uppercase">Instalación (único)</span>
                    <span class="text-sm font-black text-gray-900">${{ number_format($servicioSeleccionado['instalacion'], 2) }}</span>
                </div>

                <div class="flex justify-between items-start py-3 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold text-gray-600 uppercase">Ajuste mensualidad</span>
                        <p class="text-[9px] text-gray-400 mt-0.5 uppercase font-medium">Se suma a la renta mensual del cliente</p>
                    </div>
                    <span class="text-sm font-black text-gray-900">+${{ number_format($servicioSeleccionado['mensualidad'], 2) }}</span>
                </div>

                {{-- Total --}}
                <div class="flex justify-between items-center mt-3 bg-gray-900 rounded-xl px-5 py-4">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total a Cobrar Hoy</span>
                    <span class="text-2xl font-black text-white tracking-tight">
                        ${{ number_format($servicioSeleccionado['instalacion'] + $servicioSeleccionado['mensualidad'], 2) }}
                    </span>
                </div>
            </div>

            {{-- Validación cajero --}}
            <div class="px-6 pb-6 space-y-4">

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Método de pago</label>
                    <select wire:model="metodoPago"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
                        <option value="efectivo">Efectivo en ventanilla</option>
                        <option value="tarjeta">Tarjeta — Terminal bancaria</option>
                        <option value="transferencia">Transferencia / Depósito</option>
                    </select>
                </div>

                <div class="border-2 border-dashed border-indigo-200 rounded-xl p-4 bg-indigo-50/50">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="confirmacionCaja"
                               class="mt-0.5 h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <div>
                            <p class="text-[11px] font-black text-indigo-900 uppercase tracking-widest mb-1">Validación de Cajero</p>
                            <p class="text-[10px] text-indigo-600 leading-relaxed font-medium">
                                Certifico que el pago fue recibido e ingresado a caja física. Esta acción afectará el historial del suscriptor.
                            </p>
                        </div>
                    </label>
                </div>

                @if(!$confirmacionCaja)
                <div class="flex items-center gap-2 text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2.5">
                    <i class="ri-alert-line text-base flex-shrink-0"></i>
                    Confirme la recepción del pago para continuar
                </div>
                @endif

                <button wire:click="confirmarCobro"
                        @if(!$confirmacionCaja) disabled @endif
                        class="w-full py-3.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed">
                    <i class="ri-check-double-line mr-2"></i> Confirmar Pago y Registrar
                </button>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-3.5">
                <button wire:click="irAPaso(2)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Cambiar cliente
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 4 — ASIGNACIÓN TÉCNICA
    ================================================================ --}}
    @if($paso == 4)
    <div class="max-w-2xl mx-auto space-y-5">

        {{-- Resumen del expediente --}}
        <div class="bg-gray-900 rounded-xl p-5 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Orden de Servicio</p>
                <p class="text-xs font-bold text-gray-300 uppercase">{{ $clienteEncontrado['nombre'] ?? '' }}</p>
                <span class="inline-flex items-center gap-1 text-[10px] font-black text-indigo-300 bg-white/10 px-2 py-0.5 rounded-md mt-1 uppercase">
                    <i class="ri-add-box-line text-xs"></i> {{ $servicioSeleccionado['nombre'] }}
                </span>
            </div>
            <div class="text-right">
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Cobro registrado</p>
                <p class="text-base font-black text-emerald-400">${{ number_format($servicioSeleccionado['instalacion'] + $servicioSeleccionado['mensualidad'], 2) }}</p>
            </div>
        </div>

        {{-- Asignación --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-2">
                <i class="ri-tools-line text-orange-500"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Asignación de Personal Técnico</p>
                <span class="ml-auto text-[9px] font-bold text-red-500 bg-red-50 border border-red-100 px-2 py-1 rounded-md uppercase">Requerido</span>
            </div>
            <div class="p-5 space-y-4">

                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Responsable de Instalación</label>
                    <div class="relative">
                        <i class="ri-user-star-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <select wire:model.live="tecnicoAsignado"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
                            <option value="">— Seleccione técnico o cuadrilla —</option>
                            <option value="Roberto">TÉC. ROBERTO GÓMEZ</option>
                            <option value="Cuadrilla_A">CUADRILLA A (FIBRA ÓPTICA)</option>
                            <option value="Cuadrilla_B">CUADRILLA B (INSTALACIONES)</option>
                        </select>
                    </div>
                </div>

                @if($tecnicoAsignado)
                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
                    <i class="ri-checkbox-circle-fill text-emerald-500 text-lg"></i>
                    <div>
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Técnico seleccionado</p>
                        <p class="text-xs font-bold text-emerald-700 uppercase">{{ $tecnicoAsignado }}</p>
                    </div>
                </div>
                @endif

                {{-- SMS --}}
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-center gap-4">
                    <label class="flex items-center gap-3 cursor-pointer flex-1">
                        <input type="checkbox" wire:model="notificarSms"
                               class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <div>
                            <p class="text-[11px] font-black text-indigo-800 uppercase tracking-widest">Notificar por SMS automáticamente</p>
                            <p class="text-[10px] text-indigo-500 mt-0.5">Se enviará aviso al técnico al generar la orden</p>
                        </div>
                    </label>
                    <i class="ri-message-3-line text-2xl text-indigo-300 flex-shrink-0"></i>
                </div>

                <button wire:click="generarReporte"
                        @if(!$tecnicoAsignado) disabled @endif
                        class="w-full py-3.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                    <i class="ri-tools-fill text-base"></i> Generar Orden de Servicio
                </button>
            </div>
        </div>

    </div>
    @endif

    {{-- ================================================================
         PASO 5 — ÉXITO
    ================================================================ --}}
    @if($paso == 5)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            {{-- Banner éxito --}}
            <div class="bg-emerald-600 px-8 py-10 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 opacity-10"
                     style="background-image: radial-gradient(circle, white 1px, transparent 0); background-size: 24px 24px;"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="ri-checkbox-circle-fill text-4xl"></i>
                    </div>
                    <h2 class="text-2xl font-black uppercase tracking-tight">Servicio Procesado</h2>
                    <p class="text-emerald-100 text-[10px] font-bold uppercase tracking-widest mt-1">Orden de servicio generada con éxito</p>
                </div>
            </div>

            {{-- Resumen del proceso --}}
            <div class="p-6 space-y-4">

                {{-- Datos del expediente --}}
                <div class="bg-gray-50 border border-gray-200 rounded-xl overflow-hidden">
                    <div class="bg-white border-b border-gray-100 px-4 py-3">
                        <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Resumen del Proceso</p>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @php
                            $resumen = [
                                ['icon' => 'ri-file-text-line',   'label' => 'Folio de Reporte',     'value' => 'REQ-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT), 'mono' => true, 'color' => 'text-indigo-600'],
                                ['icon' => 'ri-user-line',        'label' => 'Suscriptor',           'value' => $clienteEncontrado['nombre'] ?? '—'],
                                ['icon' => 'ri-store-2-line',     'label' => 'Sucursal',             'value' => $clienteEncontrado['sucursal'] ?? '—'],
                                ['icon' => 'ri-add-box-line',     'label' => 'Servicio Adicional',   'value' => $servicioSeleccionado['nombre'] ?? '—', 'badge' => 'indigo'],
                                ['icon' => 'ri-user-star-line',   'label' => 'Técnico Asignado',     'value' => $tecnicoAsignado ?? '—'],
                                ['icon' => 'ri-coins-line',       'label' => 'Total Cobrado',        'value' => '$' . number_format(($servicioSeleccionado['instalacion'] ?? 0) + ($servicioSeleccionado['mensualidad'] ?? 0), 2), 'badge' => 'emerald'],
                            ];
                        @endphp
                        @foreach($resumen as $r)
                        <div class="flex items-center gap-4 px-4 py-3">
                            <i class="{{ $r['icon'] }} text-gray-400 text-base flex-shrink-0"></i>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest w-32 flex-shrink-0">{{ $r['label'] }}</span>
                            @if(isset($r['badge']) && $r['badge'] === 'indigo')
                                <span class="text-[10px] font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md uppercase">{{ $r['value'] }}</span>
                            @elseif(isset($r['badge']) && $r['badge'] === 'emerald')
                                <span class="text-[10px] font-black text-emerald-700 bg-emerald-50 border border-emerald-100 px-2 py-0.5 rounded-md">{{ $r['value'] }}</span>
                            @elseif(isset($r['mono']))
                                <span class="font-mono font-black {{ $r['color'] ?? 'text-gray-800' }} text-sm tracking-wider">{{ $r['value'] }}</span>
                            @else
                                <span class="text-xs font-bold text-gray-800 uppercase">{{ $r['value'] }}</span>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Aviso SMS si fue activado --}}
                @if(isset($notificarSms) && $notificarSms)
                <div class="flex items-center gap-3 bg-indigo-50 border border-indigo-100 rounded-lg px-4 py-3">
                    <i class="ri-message-3-line text-indigo-500 text-base"></i>
                    <p class="text-[10px] font-bold text-indigo-700 uppercase tracking-widest">SMS enviado al técnico correctamente</p>
                </div>
                @endif

                <button wire:click="finalizar"
                        class="w-full py-3.5 bg-gray-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm flex items-center justify-center gap-2">
                    <i class="ri-arrow-left-line"></i> Volver a Bandeja de Gestión
                </button>
            </div>
        </div>
    </div>
    @endif

</div>