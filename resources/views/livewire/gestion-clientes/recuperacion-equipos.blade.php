<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ENCABEZADO --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-red-600">Recuperación de Equipos</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Clientes con Adeudo Crítico <span class="text-red-600">+61 Días</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Gestión de activos y órdenes de cancelación física por falta de pago</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>

    {{-- ================================================================
         PASO 1 — LISTADO DE DEUDORES CRÍTICOS
    ================================================================ --}}
    @if($paso == 1)

    {{-- Filtros --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-5 overflow-hidden">
        <div class="bg-red-50 border-b border-red-100 px-5 py-3 flex items-center gap-2">
            <i class="ri-alarm-warning-line text-red-500"></i>
            <p class="text-[10px] font-black text-red-700 uppercase tracking-widest">Filtros — Solo deudores críticos (+61 días)</p>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
            <div class="xl:col-span-2 space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Buscar deudor</label>
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Nombre, ID, teléfono o dirección..."
                           class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-colors placeholder:text-gray-300">
                </div>
            </div>
            <div class="space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Sucursal</label>
                <select wire:model.live="filtroSucursal"
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                    <option value="">Todas</option>
                    <option value="oaxaca">Oaxaca Centro</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Estatus</label>
                <div class="flex gap-2">
                    <select wire:model.live="filtroEstatus"
                            class="flex-1 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                        <option value="">Solo Críticos</option>
                        <option value="suspendido">Suspendidos</option>
                    </select>
                    <button wire:click="resetFilters"
                            class="px-3 bg-white border border-gray-200 text-gray-400 rounded-lg hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all text-xs font-black">
                        <i class="ri-filter-off-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest"># ID</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Titular / Sucursal</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Adeudo</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Servicio / Estatus</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Último Pago</th>
                        <th class="px-5 py-3.5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($clientes as $cliente)
                    <tr class="hover:bg-red-50/20 transition-colors">
                        <td class="px-5 py-4">
                            <span class="font-mono text-xs font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-lg">{{ $cliente['id'] }}</span>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-sm font-black text-gray-800 uppercase tracking-tight">{{ $cliente['nombre'] }}</p>
                            <p class="flex items-center gap-1 text-[10px] font-bold text-gray-400 mt-0.5 uppercase">
                                <i class="ri-map-pin-line text-red-400"></i> {{ $cliente['sucursal'] }}
                            </p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-lg font-black text-red-600 tracking-tight">${{ number_format($cliente['saldo'], 2) }}</p>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-xs font-bold text-gray-700 uppercase">{{ $cliente['servicio'] }}</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                <span class="text-[9px] font-black text-red-700 uppercase tracking-widest">{{ $cliente['estatus'] }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-4">
                            <p class="text-xs font-bold text-gray-500">{{ $cliente['ultimo_pago'] }}</p>
                        </td>
                        <td class="px-5 py-4 text-right">
                            <button wire:click="seleccionarCliente({{ json_encode($cliente) }})"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm shadow-red-200 hover:bg-red-700 transition-all active:scale-95">
                                <i class="ri-file-warning-line text-sm"></i> Generar Orden
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                    <i class="ri-inbox-archive-line text-2xl text-emerald-400"></i>
                                </div>
                                <p class="text-xs font-black text-gray-300 uppercase tracking-widest">No se detectaron clientes para recuperación física</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 2 — ORDEN DE RECUPERACIÓN
    ================================================================ --}}
    @if($paso == 2)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Panel lateral izquierdo —— datos del reporte --}}
        <div class="lg:col-span-4 space-y-4">

            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-900 px-5 py-3.5 flex items-center justify-between">
                    <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Datos Previos del Reporte</p>
                    <span class="font-mono text-xs font-black text-white">{{ $folioReporte }}</span>
                </div>
                <div class="divide-y divide-gray-100">
                    @php
                        $datosRec = [
                            ['icon' => 'ri-calendar-event-line','label' => 'Fecha Emisión',  'value' => now()->format('d/m/Y — H:i'), 'mono' => true],
                            ['icon' => 'ri-user-line',           'label' => 'Titular',        'value' => $clienteSeleccionado['nombre'], 'bold' => true],
                            ['icon' => 'ri-map-pin-line',        'label' => 'Domicilio',      'value' => $clienteSeleccionado['domicilio'] ?? 'No especificado', 'italic' => true],
                            ['icon' => 'ri-money-dollar-circle-line','label' => 'Adeudo',     'value' => '$'.number_format($clienteSeleccionado['saldo'],2), 'badge' => 'red'],
                        ];
                    @endphp
                    @foreach($datosRec as $d)
                    <div class="flex items-start gap-3 px-4 py-3">
                        <i class="{{ $d['icon'] }} text-gray-400 text-base flex-shrink-0 mt-0.5"></i>
                        <div>
                            <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ $d['label'] }}</p>
                            @if(isset($d['bold']))
                                <p class="text-xs font-black text-gray-900 uppercase mt-0.5">{{ $d['value'] }}</p>
                            @elseif(isset($d['italic']))
                                <p class="text-xs text-gray-600 italic mt-0.5">{{ $d['value'] }}</p>
                            @elseif(isset($d['mono']))
                                <p class="font-mono text-xs font-black text-gray-800 mt-0.5">{{ $d['value'] }}</p>
                            @elseif(isset($d['badge']) && $d['badge'] === 'red')
                                <span class="inline-flex text-sm font-black text-red-600 mt-0.5">{{ $d['value'] }}</span>
                            @else
                                <p class="text-xs font-semibold text-gray-700 mt-0.5">{{ $d['value'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach

                    {{-- NAP --}}
                    <div class="px-4 py-3 bg-indigo-50/60">
                        <p class="text-[9px] font-black text-indigo-500 uppercase tracking-widest mb-1">NAP Asignada</p>
                        <p class="text-xs font-black text-gray-800 uppercase">{{ $clienteSeleccionado['nap'] }}</p>
                        <p class="text-[10px] text-gray-500 italic mt-0.5">{{ $clienteSeleccionado['direccion_nap'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Equipo a recuperar --}}
            <div class="bg-white border border-red-200 rounded-xl p-4 shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <i class="ri-router-line text-red-500 text-base"></i>
                    <p class="text-[10px] font-black text-red-700 uppercase tracking-widest">Equipo a Recuperar</p>
                </div>
                <p class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ $clienteSeleccionado['equipo'] }}</p>
            </div>
        </div>

        {{-- Panel principal —— asignación técnica --}}
        <div class="lg:col-span-8 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center">
                    <i class="ri-user-settings-line text-red-600"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Orden de Cancelación Física</p>
                    <p class="text-[10px] text-gray-400">Asignación de cuadrilla técnica para retiro de equipo</p>
                </div>
            </div>

            <div class="p-6 space-y-5">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Personal Técnico Asignado *</label>
                    <div class="relative">
                        <i class="ri-user-star-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <select wire:model.live="tecnicoAsignado"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold uppercase focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-colors">
                            <option value="">— Seleccione cuadrilla o técnico —</option>
                            <option value="Roberto">ING. ROBERTO GÓMEZ</option>
                            <option value="Cuadrilla 2">CUADRILLA 2 (OAXACA SUR)</option>
                        </select>
                    </div>
                </div>

                @if($tecnicoAsignado)
                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
                    <i class="ri-checkbox-circle-fill text-emerald-500 text-lg"></i>
                    <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">{{ $tecnicoAsignado }} asignado</p>
                </div>
                @endif

                {{-- Aviso SMS --}}
                <div class="bg-indigo-50 border border-indigo-100 rounded-xl p-4 flex items-start gap-4">
                    <label class="flex items-start gap-3 cursor-pointer flex-1">
                        <input type="checkbox" wire:model="notificarSms"
                               class="mt-0.5 h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <div>
                            <p class="text-[11px] font-black text-indigo-800 uppercase tracking-widest">Notificación automática SMS / API Meta</p>
                            <p class="text-[10px] text-indigo-500 mt-0.5 leading-relaxed">
                                Al confirmar, el técnico recibe la orden de trabajo y el cliente recibe aviso de <strong>RECUPERACIÓN DE ACTIVO</strong> iniciando el proceso legal de cobro.
                            </p>
                        </div>
                    </label>
                    <i class="ri-message-3-line text-2xl text-indigo-300 flex-shrink-0 mt-0.5"></i>
                </div>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-between">
                <button wire:click="$set('paso', 1)"
                        class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5">
                    <i class="ri-arrow-left-line"></i> Volver al listado
                </button>
                <button wire:click="generarReporte"
                        @if(!$tecnicoAsignado) disabled @endif
                        class="inline-flex items-center gap-2 px-7 py-3 bg-red-600 text-white rounded-lg font-black text-xs uppercase tracking-widest shadow-sm shadow-red-200 hover:bg-red-700 transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed">
                    <i class="ri-file-warning-line"></i> Confirmar y Notificar Orden
                </button>
            </div>
        </div>

    </div>
    @endif

</div>