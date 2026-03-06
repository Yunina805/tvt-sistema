<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================ ENCABEZADO
    ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-indigo-600">Reportes de Servicio</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Reportes de Servicio en Proceso</h2>
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

    {{-- Flash --}}
    @if(session()->has('exito'))
        <div class="mb-5 bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-center gap-3">
            <i class="ri-checkbox-circle-fill text-emerald-500 text-xl"></i>
            <p class="text-[11px] font-black text-emerald-800 uppercase tracking-widest">{{ session('exito') }}</p>
        </div>
    @endif

    {{-- ================================================================ KPI CARDS
    ================================================================ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        @php
            $kpis = [
                ['label' => 'Total Reportes', 'value' => $totalReportes, 'icon' => 'ri-file-list-3-line', 'bg' => 'bg-indigo-50', 'ib' => 'bg-indigo-100', 'tc' => 'text-indigo-600'],
                ['label' => 'Pendientes', 'value' => $totalPendientes, 'icon' => 'ri-time-line', 'bg' => 'bg-amber-50', 'ib' => 'bg-amber-100', 'tc' => 'text-amber-600'],
                ['label' => 'En Proceso', 'value' => $totalEnProceso, 'icon' => 'ri-loader-4-line', 'bg' => 'bg-blue-50', 'ib' => 'bg-blue-100', 'tc' => 'text-blue-600'],
                ['label' => 'Cerrados', 'value' => $totalCerradosHoy, 'icon' => 'ri-checkbox-circle-line', 'bg' => 'bg-emerald-50', 'ib' => 'bg-emerald-100', 'tc' => 'text-emerald-600'],
            ];
        @endphp
        @foreach($kpis as $k)
            <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 shadow-sm">
                <div class="w-10 h-10 {{ $k['ib'] }} rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="{{ $k['icon'] }} {{ $k['tc'] }} text-lg"></i>
                </div>
                <div>
                    <p class="text-2xl font-black text-gray-900 leading-none">{{ $k['value'] }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $k['label'] }}</p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ================================================================ FILTROS
    ================================================================ --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-5 overflow-hidden">
        <div class="px-5 py-2.5 flex items-center justify-between bg-gray-50/60 border-b border-gray-100">
            <div class="flex items-center gap-2">
                <i class="ri-equalizer-2-line text-indigo-400 text-sm"></i>
                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Filtros</span>
                @if($busqueda || $filtroEstado !== 'Todos' || $filtroTipo !== 'Todos' || $filtroSucursal || $filtroPeriodo)
                    <span
                        class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-500 text-[9px] font-black px-2 py-0.5 rounded-full border border-indigo-100">
                        <i class="ri-circle-fill text-[7px]"></i> Activos
                    </span>
                @endif
            </div>
            @if($busqueda || $filtroEstado !== 'Todos' || $filtroTipo !== 'Todos' || $filtroSucursal || $filtroPeriodo)
                <button wire:click="limpiarFiltros"
                    class="flex items-center gap-1.5 text-[9px] font-black text-red-400 hover:text-red-600 uppercase tracking-widest transition-all">
                    <i class="ri-close-circle-line text-sm"></i> Limpiar
                </button>
            @endif
        </div>
        <div class="px-5 py-3.5 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3">
            <div class="sm:col-span-2 xl:col-span-2 space-y-1.5">
                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Buscar cliente o
                    folio</label>
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-xs"></i>
                    <input type="text" wire:model.live.debounce.300ms="busqueda" placeholder="Nombre, ID o folio..."
                        class="w-full pl-8 pr-3 py-2.5 text-xs bg-white border border-gray-200 rounded-xl font-medium text-gray-700 placeholder:text-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 transition-all">
                </div>
            </div>
            <div class="space-y-1.5">
                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Tipo de
                    reporte</label>
                <select wire:model.live="filtroTipo"
                    class="w-full py-2.5 px-3 text-xs bg-white border border-gray-200 rounded-xl font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 transition-all">
                    <option value="Todos">Todos los tipos</option>
                    <option value="INSTALACION">Instalación</option>
                    <option value="FALLA_TV">Falla TV</option>
                    <option value="FALLA_INTERNET">Falla Internet</option>
                    <option value="FALLA_TV_INTERNET">Falla TV+Internet</option>
                    <option value="CAMBIO_DOMICILIO">Cambio Domicilio</option>
                    <option value="SUSPENSION">Suspensión</option>
                    <option value="CANCELACION">Cancelación</option>
                    <option value="RECUPERACION">Rec. Equipo</option>
                    <option value="SERVICIO_ADICIONAL_TV">Serv. Adicional TV</option>
                    <option value="AUMENTO_VELOCIDAD">Aumento Velocidad</option>
                </select>
            </div>
            <div class="space-y-1.5">
                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Estado</label>
                <select wire:model.live="filtroEstado"
                    class="w-full py-2.5 px-3 text-xs bg-white border border-gray-200 rounded-xl font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 transition-all">
                    <option value="Todos">Todos</option>
                    <option value="Pendiente">Pendientes</option>
                    <option value="En Proceso">En Proceso</option>
                    <option value="Cerrado">Cerrados</option>
                </select>
            </div>
            <div class="space-y-1.5">
                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Periodo</label>
                <input type="month" wire:model.live="filtroPeriodo"
                    class="w-full py-2.5 px-3 text-xs bg-white border border-gray-200 rounded-xl font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 transition-all">
            </div>
        </div>
    </div>

    {{-- ================================================================ TABLA
    ================================================================ --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50">
                        <th
                            class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Folio / Apertura</th>
                        <th
                            class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Tipo de Reporte</th>
                        <th
                            class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Cliente / Servicio</th>
                        <th
                            class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Sucursal / Técnico</th>
                        <th
                            class="px-5 py-3.5 text-center text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Estado</th>
                        <th
                            class="px-5 py-3.5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">
                            Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $tipoCfg = [
                            'INSTALACION' => ['label' => 'Instalación Nueva', 'class' => 'bg-emerald-100 text-emerald-700', 'icon' => 'ri-install-line'],
                            'FALLA_TV' => ['label' => 'Falla TV', 'class' => 'bg-violet-100 text-violet-700', 'icon' => 'ri-tv-2-line'],
                            'FALLA_INTERNET' => ['label' => 'Falla Internet', 'class' => 'bg-sky-100 text-sky-700', 'icon' => 'ri-wifi-off-line'],
                            'FALLA_TV_INTERNET' => ['label' => 'Falla TV+Internet', 'class' => 'bg-blue-100 text-blue-700', 'icon' => 'ri-router-line'],
                            'CAMBIO_DOMICILIO' => ['label' => 'Cambio Domicilio', 'class' => 'bg-orange-100 text-orange-700', 'icon' => 'ri-map-pin-line'],
                            'SUSPENSION' => ['label' => 'Suspensión', 'class' => 'bg-red-100 text-red-700', 'icon' => 'ri-pause-circle-line'],
                            'CANCELACION' => ['label' => 'Cancelación', 'class' => 'bg-gray-200 text-gray-700', 'icon' => 'ri-close-circle-line'],
                            'RECUPERACION' => ['label' => 'Rec. Equipo', 'class' => 'bg-amber-100 text-amber-700', 'icon' => 'ri-arrow-down-circle-line'],
                            'SERVICIO_ADICIONAL_TV' => ['label' => 'Serv. Adicional TV', 'class' => 'bg-purple-100 text-purple-700', 'icon' => 'ri-tv-add-line'],
                            'AUMENTO_VELOCIDAD' => ['label' => 'Aumento Velocidad', 'class' => 'bg-teal-100 text-teal-700', 'icon' => 'ri-speed-up-line'],
                        ];
                        $estadoCfg = [
                            'Pendiente' => ['class' => 'bg-amber-100 text-amber-700 border border-amber-200', 'icon' => 'ri-time-line'],
                            'En Proceso' => ['class' => 'bg-blue-100 text-blue-700 border border-blue-200', 'icon' => 'ri-loader-4-line'],
                            'Cerrado' => ['class' => 'bg-emerald-100 text-emerald-700 border border-emerald-200', 'icon' => 'ri-checkbox-circle-line'],
                        ];
                    @endphp

                    @forelse($filtrados as $rep)
                        @php
                            $dias = \Carbon\Carbon::parse($rep['fecha_apertura'])->diffInDays(now());
                            $tipo = $tipoCfg[$rep['tipo_reporte']] ?? ['label' => $rep['tipo_reporte'], 'class' => 'bg-gray-100 text-gray-600', 'icon' => 'ri-file-list-line'];
                            $est = $estadoCfg[$rep['estado']] ?? ['class' => 'bg-gray-100 text-gray-600', 'icon' => 'ri-question-line'];
                        @endphp
                        <tr class="hover:bg-indigo-50/20 transition-colors group">

                            {{-- Folio + fecha --}}
                            <td class="px-5 py-4 whitespace-nowrap">
                                <p class="text-sm font-black text-indigo-600 tracking-tight font-mono">{{ $rep['folio'] }}
                                </p>
                                <p class="text-[10px] text-gray-400 font-medium mt-0.5">{{ $rep['fecha_apertura'] }}</p>
                                @if($dias >= 3 && $rep['estado'] !== 'Cerrado')
                                    <span
                                        class="inline-flex items-center gap-1 text-[9px] font-black text-red-600 bg-red-50 border border-red-100 px-1.5 py-0.5 rounded-md mt-1">
                                        <i class="ri-alarm-warning-line"></i> {{ $dias }}d sin cerrar
                                    </span>
                                @elseif($dias >= 1 && $rep['estado'] !== 'Cerrado')
                                    <span
                                        class="inline-flex items-center gap-1 text-[9px] font-black text-amber-600 bg-amber-50 border border-amber-100 px-1.5 py-0.5 rounded-md mt-1">
                                        <i class="ri-time-line"></i> {{ $dias }}d
                                    </span>
                                @endif
                            </td>

                            {{-- Tipo de reporte --}}
                            <td class="px-5 py-4">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $tipo['class'] }} rounded-lg text-[10px] font-black uppercase tracking-tight">
                                    <i class="{{ $tipo['icon'] }} text-sm"></i> {{ $tipo['label'] }}
                                </span>
                                @if(isset($rep['quien_reporto']))
                                    <p class="text-[9px] text-gray-400 mt-1 uppercase font-bold">Por:
                                        {{ $rep['quien_reporto'] }}</p>
                                @endif
                            </td>

                            {{-- Cliente / Servicio --}}
                            <td class="px-5 py-4">
                                <p class="text-xs font-black text-gray-900 uppercase tracking-tight">{{ $rep['cliente'] }}
                                </p>
                                <p class="text-[10px] text-indigo-500 font-bold uppercase mt-0.5">
                                    {{ $rep['servicio_actual'] }}</p>
                            </td>

                            {{-- Sucursal / Técnico --}}
                            <td class="px-5 py-4">
                                <p class="text-[11px] font-black text-gray-700 uppercase">{{ $rep['sucursal'] }}</p>
                                <div class="flex items-center gap-1.5 mt-1">
                                    <div
                                        class="w-5 h-5 rounded-full bg-indigo-100 flex items-center justify-center flex-shrink-0">
                                        <i class="ri-user-settings-line text-indigo-500 text-[9px]"></i>
                                    </div>
                                    <span
                                        class="text-[10px] font-bold text-gray-500 uppercase">{{ $rep['tecnico'] ?? 'Sin asignar' }}</span>
                                </div>
                            </td>

                            {{-- Estado --}}
                            <td class="px-5 py-4 text-center">
                                <span
                                    class="inline-flex items-center gap-1 px-2.5 py-1 {{ $est['class'] }} rounded-lg text-[9px] font-black uppercase tracking-widest">
                                    <i class="{{ $est['icon'] }}"></i> {{ $rep['estado'] }}
                                </span>
                            </td>

                            {{-- Acciones --}}
                            <td class="px-5 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end items-center gap-2">
                                    <button wire:click="exportarIndividual('{{ $rep['folio'] }}')"
                                        class="p-2 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all"
                                        title="Exportar PDF">
                                        <i class="ri-file-pdf-line text-base"></i>
                                    </button>
                                    <a href="{{ route('reportes.atender', ['folio' => $rep['folio']]) }}"
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
                                    <p class="text-xs font-black text-gray-300 uppercase tracking-widest">Sin reportes con
                                        los filtros seleccionados</p>
                                    <button wire:click="limpiarFiltros"
                                        class="text-[10px] font-bold text-indigo-400 hover:text-indigo-600 uppercase tracking-widest">
                                        Limpiar filtros
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>