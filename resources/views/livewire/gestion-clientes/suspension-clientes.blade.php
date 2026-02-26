<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ENCABEZADO --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-red-600">Suspensión por Adeudo</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Clientes con Adeudo <span class="text-red-600">+31 Días</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Listado automático para cortes de servicio por falta de pago</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>

    {{-- KPIs rápidos --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
        @php
            $kpis = [
                ['label' => 'Total Deudores',   'value' => $totalDeudores    ?? 0, 'icon' => 'ri-user-forbid-line',        'color' => 'red'],
                ['label' => 'Monto Total',       'value' => '$'.number_format($montoTotal ?? 0, 2), 'icon' => 'ri-money-dollar-circle-line', 'color' => 'red', 'raw' => true],
                ['label' => 'Suspendidos Hoy',   'value' => $suspendidosHoy   ?? 0, 'icon' => 'ri-pause-circle-line',       'color' => 'amber'],
                ['label' => 'Pendientes',        'value' => $pendientesSuspension ?? 0,'icon' => 'ri-time-line',            'color' => 'indigo'],
            ];
        @endphp
        @foreach($kpis as $k)
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-3 shadow-sm">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0
                {{ $k['color'] === 'red'   ? 'bg-red-100' : '' }}
                {{ $k['color'] === 'amber' ? 'bg-amber-100' : '' }}
                {{ $k['color'] === 'indigo'? 'bg-indigo-100' : '' }}">
                <i class="{{ $k['icon'] }} text-lg
                    {{ $k['color'] === 'red'   ? 'text-red-600' : '' }}
                    {{ $k['color'] === 'amber' ? 'text-amber-600' : '' }}
                    {{ $k['color'] === 'indigo'? 'text-indigo-600' : '' }}"></i>
            </div>
            <div>
                <p class="text-lg font-black text-gray-900 leading-none">{{ $k['value'] }}</p>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ $k['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- FILTROS --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm mb-5 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-200 px-5 py-3 flex items-center gap-2">
            <i class="ri-filter-3-line text-red-500 text-sm"></i>
            <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Filtros</p>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-3">
            <div class="xl:col-span-2 space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Buscar cliente</label>
                <div class="relative">
                    <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" wire:model.live.debounce.300ms="search"
                           placeholder="Nombre, ID, teléfono o dirección..."
                           class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-red-500/20 focus:border-red-400 transition-colors placeholder:text-gray-300">
                </div>
            </div>
            <div class="space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Sucursal</label>
                <select wire:model.live="filterSucursal"
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                    <option value="">Todas</option>
                    <option value="oaxaca">Oaxaca Centro</option>
                    <option value="puerto">Puerto Escondido</option>
                </select>
            </div>
            <div class="space-y-1">
                <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Estatus</label>
                <div class="flex gap-2">
                    <select wire:model.live="filterStatus"
                            class="flex-1 bg-gray-50 border border-gray-200 rounded-lg text-xs font-bold uppercase py-2.5 px-3 focus:ring-2 focus:ring-red-500/20 focus:border-red-400">
                        <option value="">Todos</option>
                        <option value="activo">Solo Activos</option>
                        <option value="suspendido">Suspendidos</option>
                    </select>
                    <button wire:click="resetFilters"
                            class="px-3 bg-white border border-gray-200 text-gray-400 rounded-lg hover:bg-red-50 hover:text-red-500 hover:border-red-200 transition-all text-xs font-black"
                            title="Limpiar filtros">
                        <i class="ri-filter-off-line"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- TABLA --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest"># ID</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Titular / Sucursal</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Servicio / Estatus</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Adeudo Total</th>
                        <th class="px-5 py-3.5 text-left text-[10px] font-black text-gray-400 uppercase tracking-widest">Último Pago</th>
                        <th class="px-5 py-3.5 text-right text-[10px] font-black text-gray-400 uppercase tracking-widest">Acción</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($clientes as $cliente)
                    <tr class="hover:bg-red-50/20 transition-colors group">

                        <td class="px-5 py-4">
                            <span class="font-mono text-xs font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2.5 py-1 rounded-lg">{{ $cliente['id'] }}</span>
                        </td>

                        <td class="px-5 py-4">
                            <p class="text-sm font-black text-gray-800 uppercase tracking-tight">{{ $cliente['nombre'] }}</p>
                            <p class="flex items-center gap-1 text-[10px] font-bold text-gray-400 mt-0.5 uppercase">
                                <i class="ri-map-pin-2-line text-indigo-400"></i> {{ $cliente['sucursal'] }}
                            </p>
                        </td>

                        <td class="px-5 py-4">
                            <p class="text-xs font-bold text-gray-700 uppercase">{{ $cliente['servicio'] }}</p>
                            <div class="flex items-center gap-1.5 mt-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-400 animate-pulse"></span>
                                <span class="text-[9px] font-black text-amber-700 uppercase tracking-widest">{{ $cliente['estatus'] }}</span>
                            </div>
                        </td>

                        <td class="px-5 py-4">
                            <p class="text-lg font-black text-red-600 tracking-tight">${{ number_format($cliente['saldo'], 2) }}</p>
                            @php $dias = \Carbon\Carbon::parse($cliente['ultimo_pago'])->diffInDays(now()); @endphp
                            <span class="inline-flex items-center gap-1 text-[9px] font-black text-red-500 bg-red-50 border border-red-100 px-1.5 py-0.5 rounded-md mt-1">
                                <i class="ri-alarm-warning-line"></i> {{ $dias }} días sin pago
                            </span>
                        </td>

                        <td class="px-5 py-4">
                            <p class="text-xs font-bold text-gray-600">{{ \Carbon\Carbon::parse($cliente['ultimo_pago'])->format('d/m/Y') }}</p>
                        </td>

                        <td class="px-5 py-4 text-right">
                            <button wire:click="generarReporteSuspension('{{ $cliente['id'] }}')"
                                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm shadow-red-200 hover:bg-red-700 transition-all active:scale-95">
                                <i class="ri-pause-circle-line text-sm"></i> Suspender
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-14 h-14 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                    <i class="ri-checkbox-circle-line text-2xl text-emerald-500"></i>
                                </div>
                                <p class="text-xs font-black text-gray-300 uppercase tracking-widest">No hay clientes con adeudos críticos</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($clientes) && method_exists($clientes, 'hasPages') && $clientes->hasPages())
        <div class="bg-gray-50 border-t border-gray-200 px-5 py-3">
            {{ $clientes->links() }}
        </div>
        @endif
    </div>

</div>