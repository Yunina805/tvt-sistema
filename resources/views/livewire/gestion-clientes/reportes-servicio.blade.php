<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================
         ENCABEZADO
    ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-indigo-600">Reportes de Servicio</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Reportes de Servicio en Proceso
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Gestión priorizada · los más antiguos aparecen primero</p>
        </div>

        <div class="flex items-center gap-2 self-start">
            <button wire:click="exportarTotal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-emerald-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-emerald-50 hover:border-emerald-200 transition-all">
                <i class="ri-file-excel-line text-base"></i> Exportar Total
            </button>
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group">
                <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
            </a>
        </div>
    </div>

    {{-- ================================================================
         CONTADORES RÁPIDOS
    ================================================================ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        @php
            $contadores = [
                ['label' => 'Total',      'value' => $totalReportes     ?? 0,  'icon' => 'ri-file-list-3-line',   'color' => 'indigo'],
                ['label' => 'Pendientes', 'value' => $totalPendientes   ?? 0,  'icon' => 'ri-time-line',          'color' => 'amber'],
                ['label' => 'En Proceso', 'value' => $totalEnProceso    ?? 0,  'icon' => 'ri-loader-4-line',      'color' => 'blue'],
                ['label' => 'Cerrados Hoy','value' => $totalCerradosHoy ?? 0,  'icon' => 'ri-checkbox-circle-line','color' => 'emerald'],
            ];
            $colores = [
                'indigo'  => ['bg' => 'bg-indigo-50',  'icon' => 'bg-indigo-100',  'text' => 'text-indigo-600'],
                'amber'   => ['bg' => 'bg-amber-50',   'icon' => 'bg-amber-100',   'text' => 'text-amber-600'],
                'blue'    => ['bg' => 'bg-blue-50',    'icon' => 'bg-blue-100',    'text' => 'text-blue-600'],
                'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'bg-emerald-100', 'text' => 'text-emerald-600'],
            ];
        @endphp
        @foreach($contadores as $c)
        @php $col = $colores[$c['color']]; @endphp
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 {{ $col['icon'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="{{ $c['icon'] }} {{ $col['text'] }} text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-black text-gray-900 leading-none">{{ $c['value'] }}</p>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $c['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ================================================================
         FILTROS
    ================================================================ --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-5 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
            <i class="ri-filter-3-line text-indigo-500 text-sm"></i>
            <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Filtros y Búsqueda</p>
        </div>
        <div class="p-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3">

                {{-- Buscador --}}
                <div class="xl:col-span-2 space-y-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Buscar cliente o folio</label>
                    <div class="relative">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" wire:model.live.debounce.300ms="busqueda"
                               placeholder="Nombre, ID o folio..."
                               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors placeholder:text-gray-300">
                    </div>
                </div>

                {{-- Sucursal --}}
                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Sucursal</label>
                    <select wire:model.live="filtroSucursal"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
                        <option value="">Todas</option>
                        <option value="oaxaca">Oaxaca Centro</option>
                    </select>
                </div>

                {{-- Estado --}}
                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Estado</label>
                    <select wire:model.live="filtroEstado"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
                        <option value="Todos">Todos</option>
                        <option value="Pendiente">Pendientes</option>
                        <option value="En Proceso">En Proceso</option>
                        <option value="Cerrado">Cerrados</option>
                    </select>
                </div>

                {{-- Periodo --}}
                <div class="space-y-1">
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Periodo</label>
                    <div class="flex gap-2">
                        <input type="month" wire:model.live="filtroPeriodo"
                               class="flex-1 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold py-2.5 px-3 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
                        <button wire:click="limpiarFiltros"
                                class="px-3 py-2.5 bg-white border border-gray-200 text-gray-400 rounded-lg hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all text-xs font-black uppercase tracking-widest" title="Limpiar filtros">
                            <i class="ri-filter-off-line"></i>
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ================================================================
         TABLA DE REPORTES
    ================================================================ --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Folio / Apertura</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Tipo de Reporte</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Cliente / Servicio</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Sucursal / Técnico</th>
                        <th class="px-5 py-3.5 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">Estado</th>
                        <th class="px-5 py-3.5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($reportes as $reporte)
                    <tr class="hover:bg-indigo-50/30 transition-colors group">

                        {{-- Folio --}}
                        <td class="px-5 py-4 whitespace-nowrap">
                            <p class="text-sm font-black text-indigo-600 tracking-tight">{{ $reporte['folio'] }}</p>
                            <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $reporte['fecha_apertura'] }}</p>
                            {{-- Indicador de antigüedad --}}
                            @php
                                $dias = \Carbon\Carbon::parse($reporte['fecha_apertura'])->diffInDays(now());
                            @endphp
                            @if($dias >= 2)
                                <span class="inline-flex items-center gap-1 text-[9px] font-black text-red-600 bg-red-50 px-1.5 py-0.5 rounded-md mt-1">
                                    <i class="ri-alarm-warning-line"></i> {{ $dias }}d
                                </span>
                            @endif
                        </td>

                        {{-- Tipo de reporte --}}
                        <td class="px-5 py-4">
                            @php
                                $tipoBadges = [
                                    'FALLA_TV'            => ['label' => 'Falla TV',         'class' => 'bg-violet-100 text-violet-700', 'icon' => 'ri-tv-2-line'],
                                    'FALLA_INTERNET'      => ['label' => 'Falla Internet',    'class' => 'bg-sky-100 text-sky-700',       'icon' => 'ri-wifi-off-line'],
                                    'FALLA_TV_INTERNET'   => ['label' => 'Falla TV+Internet', 'class' => 'bg-blue-100 text-blue-700',     'icon' => 'ri-router-line'],
                                    'CAMBIO_DOMICILIO'    => ['label' => 'Cambio Domicilio',  'class' => 'bg-orange-100 text-orange-700', 'icon' => 'ri-map-pin-line'],
                                    'INSTALACION'         => ['label' => 'Instalación',       'class' => 'bg-emerald-100 text-emerald-700','icon' => 'ri-install-line'],
                                    'SUSPENSION'          => ['label' => 'Suspensión',        'class' => 'bg-red-100 text-red-700',       'icon' => 'ri-pause-circle-line'],
                                    'CANCELACION'         => ['label' => 'Cancelación',       'class' => 'bg-gray-100 text-gray-700',     'icon' => 'ri-close-circle-line'],
                                    'RECUPERACION'        => ['label' => 'Rec. Equipo',       'class' => 'bg-amber-100 text-amber-700',   'icon' => 'ri-arrow-down-circle-line'],
                                ];
                                $tipo = $tipoBadges[$reporte['tipo_reporte']] ?? ['label' => $reporte['tipo_reporte'], 'class' => 'bg-gray-100 text-gray-600', 'icon' => 'ri-file-list-line'];
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $tipo['class'] }} rounded-lg text-[10px] font-black uppercase tracking-tight">
                                <i class="{{ $tipo['icon'] }} text-sm"></i> {{ $tipo['label'] }}
                            </span>
                            @if(isset($reporte['quien_reporto']))
                                <p class="text-[9px] text-gray-400 mt-1 uppercase font-medium">Por: {{ $reporte['quien_reporto'] }}</p>
                            @endif
                        </td>

                        {{-- Cliente / Servicio --}}
                        <td class="px-5 py-4">
                            <p class="text-sm font-black text-gray-800 uppercase tracking-tight">{{ $reporte['cliente'] }}</p>
                            <p class="text-[10px] text-indigo-500 font-bold uppercase mt-0.5">{{ $reporte['servicio_actual'] }}</p>
                            @if(isset($reporte['sucursal_cliente']))
                                <p class="text-[9px] text-gray-400 mt-0.5 uppercase">{{ $reporte['sucursal_cliente'] }}</p>
                            @endif
                        </td>

                        {{-- Sucursal / Técnico --}}
                        <td class="px-5 py-4">
                            <p class="text-[11px] font-black text-gray-700 uppercase">{{ $reporte['sucursal'] }}</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <div class="w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                    <i class="ri-user-settings-line text-indigo-500 text-[10px]"></i>
                                </div>
                                <span class="text-[10px] font-bold text-gray-500 uppercase">{{ $reporte['tecnico'] ?? 'Sin asignar' }}</span>
                            </div>
                        </td>

                        {{-- Estado --}}
                        <td class="px-5 py-4 text-center">
                            @php
                                $estadoClasses = [
                                    'Pendiente'   => 'bg-amber-100 text-amber-700 border border-amber-200',
                                    'En Proceso'  => 'bg-blue-100 text-blue-700 border border-blue-200',
                                    'Cerrado'     => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                ];
                                $estadoIcons = [
                                    'Pendiente'   => 'ri-time-line',
                                    'En Proceso'  => 'ri-loader-4-line',
                                    'Cerrado'     => 'ri-checkbox-circle-line',
                                ];
                                $ec = $estadoClasses[$reporte['estado']] ?? 'bg-gray-100 text-gray-600';
                                $ei = $estadoIcons[$reporte['estado']] ?? 'ri-question-line';
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 {{ $ec }} rounded-lg text-[9px] font-black uppercase tracking-widest">
                                <i class="{{ $ei }}"></i> {{ $reporte['estado'] }}
                            </span>
                        </td>

                        {{-- Acciones --}}
                        <td class="px-5 py-4 whitespace-nowrap text-right">
                            <div class="flex justify-end items-center gap-2">
                                <button wire:click="exportarIndividual('{{ $reporte['folio'] }}')"
                                        class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                                        title="Exportar PDF">
                                    <i class="ri-file-pdf-line text-base"></i>
                                </button>
                                <a href="{{ route('reportes.atender', ['folio' => $reporte['folio']]) }}"
                                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm shadow-indigo-200 hover:bg-indigo-700 transition-all active:scale-95">
                                    Atender <i class="ri-arrow-right-line"></i>
                                </a>
                            </div>
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center">
                                    <i class="ri-inbox-archive-line text-3xl text-gray-300"></i>
                                </div>
                                <p class="text-xs font-black text-gray-300 uppercase tracking-widest">No hay reportes activos con los filtros seleccionados</p>
                                <button wire:click="limpiarFiltros" class="text-[10px] font-bold text-indigo-400 hover:text-indigo-600 uppercase tracking-widest transition-colors">
                                    Limpiar filtros
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($reportes instanceof \Illuminate\Pagination\LengthAwarePaginator && $reportes->hasPages())
        <div class="bg-gray-50 border-t border-gray-200 px-5 py-3">
            {{ $reportes->links() }}
        </div>
        @endif
    </div>

</div>