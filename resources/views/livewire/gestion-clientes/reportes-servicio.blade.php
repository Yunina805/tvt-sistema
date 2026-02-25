<div class="max-w-7xl mx-auto py-6 px-4">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-semibold text-gray-800 tracking-tight">Bandeja de Reportes de Servicio</h2>
            <p class="text-sm text-gray-500">Gestión de instalaciones, fallas y movimientos de planta externa.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="exportarTotal" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                <i class="ri-file-excel-line mr-2 text-green-600"></i> Exportar Lista Total
            </button>
        </div>
    </div>

    <div class="bg-gray-50 p-4 rounded-t-xl border border-gray-200 flex flex-col md:flex-row gap-4 justify-between">
        <div class="flex flex-wrap items-center gap-4">
            <div class="relative min-w-[300px]">
                <i class="ri-search-line absolute left-3 top-2.5 text-gray-400"></i>
                <input type="text" wire:model.live="busqueda" placeholder="Buscar por cliente o folio..." class="pl-9 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
            </div>
            <select wire:model.live="filtroEstado" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                <option value="Todos">Todos los Estados</option>
                <option value="Pendiente">Pendientes</option>
                <option value="En Proceso">En Proceso</option>
                <option value="Cerrado">Cerrados</option>
            </select>
        </div>
    </div>

    <div class="bg-white border-x border-b border-gray-200 rounded-b-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Folio / Apertura</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tipo de Reporte</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Cliente / Servicio Actual</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Sucursal / Técnico</th>
                        <th class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($reportes as $reporte)
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-indigo-600">{{ $reporte['folio'] }}</div>
                                <div class="text-[11px] text-gray-500">{{ $reporte['fecha_apertura'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ $reporte['tipo_reporte'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-800 uppercase">{{ $reporte['cliente'] }}</div>
                                <div class="text-xs text-indigo-500 italic">{{ $reporte['servicio_actual'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700 font-medium">{{ $reporte['sucursal'] }}</div>
                                <div class="text-xs text-gray-500 flex items-center gap-1">
                                    <i class="ri-user-settings-line"></i> {{ $reporte['tecnico'] }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-3 py-1 inline-flex text-[10px] leading-5 font-bold rounded-full 
                                    @if($reporte['estado'] == 'Pendiente') bg-yellow-100 text-yellow-800 
                                    @elseif($reporte['estado'] == 'En Proceso') bg-blue-100 text-blue-800 
                                    @else bg-green-100 text-green-800 @endif uppercase">
                                    {{ $reporte['estado'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center gap-2">
                                    <button wire:click="exportarIndividual('{{ $reporte['folio'] }}')" class="p-1.5 text-gray-400 hover:text-red-600 transition-colors" title="Exportar Reporte Individual">
                                        <i class="ri-file-pdf-line text-lg"></i>
                                    </button>
                                    
                                    <a href="{{ route('reportes.atender', ['folio' => $reporte['folio']]) }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-md transition-colors text-sm font-bold">
                                        Atender <i class="ri-arrow-right-line ml-1"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 italic">
                                <div class="flex flex-col items-center">
                                    <i class="ri-inbox-line text-4xl mb-2"></i>
                                    <span>No se encontraron reportes en proceso con los filtros seleccionados.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>