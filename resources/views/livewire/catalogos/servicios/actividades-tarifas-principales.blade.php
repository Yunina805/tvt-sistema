<div>
    {{-- BREADCRUMB --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Servicios</span>
        <span>/</span>
        <span class="text-gray-600">Actividades · Tarifas Principales</span>
    </nav>

    {{-- ENCABEZADO --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Actividades para Tarifas Principales</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Servicios · Registro de Actividades</p>
        </div>
        @if($modo === 'lista')
        <button wire:click="nuevo"
            class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-colors shadow-sm">
            <i class="ri-add-line text-sm"></i>
            <span>Nueva Actividad</span>
        </button>
        @endif
    </div>

    {{-- KPI CARDS --}}
    @if($modo === 'lista')
    <div class="grid grid-cols-2 gap-4 mb-6">
        <div class="bg-emerald-50 rounded-2xl p-4 flex items-center gap-3 shadow-sm border border-emerald-100">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-list-check-3 text-emerald-600 text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500">Total Actividades</p>
                <p class="text-xl font-black text-emerald-800">{{ $total }}</p>
            </div>
        </div>
        <div class="bg-green-50 rounded-2xl p-4 flex items-center gap-3 shadow-sm border border-green-100">
            <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-checkbox-circle-line text-green-600 text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-green-500">Activas</p>
                <p class="text-xl font-black text-green-800">{{ $activos }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- FORMULARIO --}}
    @if($modo !== 'lista')
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 bg-emerald-100 rounded-lg flex items-center justify-center">
                    <i class="ri-{{ $modo === 'crear' ? 'add' : 'edit' }}-line text-emerald-600 text-sm"></i>
                </div>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-700">
                    {{ $modo === 'crear' ? 'Nueva Actividad' : 'Editar Actividad' }}
                </p>
            </div>
            <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>

        <form wire:submit="guardar" class="p-5">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                {{-- Nombre --}}
                <div class="sm:col-span-3">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                        Nombre de la Actividad <span class="text-red-500">*</span>
                    </label>
                    <input type="text" wire:model="nombre"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-transparent placeholder-gray-300 uppercase"
                        placeholder="INSTALACIÓN DE DECODIFICADOR...">
                    @error('nombre') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
                {{-- Servicio Asociado --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                        Servicio Asociado
                    </label>
                    <select wire:model="servicioId"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-transparent bg-white">
                        <option value="">— Sin servicio —</option>
                        @foreach($servicios as $svc)
                        <option value="{{ $svc->id }}">{{ $svc->nombre }}</option>
                        @endforeach
                    </select>
                    @error('servicioId') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
                {{-- Responsable --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                        Responsable de la Ejecución
                    </label>
                    <select wire:model="puestoResponsable"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-transparent bg-white">
                        <option value="">— Seleccionar puesto —</option>
                        @foreach($puestos as $puesto)
                        <option value="{{ $puesto }}">{{ $puesto }}</option>
                        @endforeach
                    </select>
                    @error('puestoResponsable') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                </div>
                {{-- Activo --}}
                <div class="flex items-end pb-1">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <div class="relative">
                            <input type="checkbox" wire:model="activo" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 rounded-full peer-checked:bg-emerald-500 transition-colors"></div>
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-widest text-gray-600">Activo</span>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-2 border-t border-dashed border-gray-100">
                <button type="button" wire:click="cancelar"
                    class="px-5 py-2 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-colors shadow-sm flex items-center gap-2">
                    <i class="ri-save-line text-sm"></i>
                    {{ $modo === 'crear' ? 'Registrar' : 'Guardar Cambios' }}
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- Panel de Filtros --}}
    @php $hayFiltros = $search || $filtroPuesto || $filtroActivo !== ''; @endphp
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-4" x-data="{ openFilters: true }">
        <button type="button" @click="openFilters = !openFilters"
            class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50/60 transition-colors rounded-2xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-equalizer-2-line text-emerald-500 text-base"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-black uppercase tracking-widest text-gray-700">Filtros de Búsqueda</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest mt-0.5 {{ $hayFiltros ? 'text-emerald-500' : 'text-gray-400' }}">
                        {{ $hayFiltros ? 'Filtros activos · resultados filtrados' : 'Sin filtros · mostrando todo el catálogo' }}
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($hayFiltros)
                <span wire:click.stop="limpiarFiltros"
                    class="flex items-center gap-1 px-2.5 py-1 text-[9px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors cursor-pointer">
                    <i class="ri-close-circle-line text-xs"></i> Limpiar filtros
                </span>
                @endif
                <i class="ri-arrow-down-s-line text-gray-400 text-lg transition-transform duration-200" :class="openFilters && 'rotate-180'"></i>
            </div>
        </button>
        <div x-show="openFilters" x-cloak class="border-t border-gray-100 px-5 pt-4 pb-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Búsqueda General</label>
                    <div class="relative">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nombre de la actividad..."
                            class="w-full pl-9 pr-3 py-2.5 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Puesto</label>
                    <select wire:model.live="filtroPuesto"
                        class="w-full py-2.5 px-3 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 transition-all">
                        <option value="">Todos los puestos</option>
                        @foreach($puestos as $puesto)
                        <option value="{{ $puesto }}">{{ $puesto }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Estado</label>
                    <select wire:model.live="filtroActivo"
                        class="w-full py-2.5 px-3 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 transition-all">
                        <option value="">Todos</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- LISTA --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">ID</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Nombre de la Actividad</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Servicio Asociado</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Responsable</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Fecha</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($actividades as $act)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="text-[9px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-lg uppercase tracking-widest">
                                ACT-{{ str_pad($act->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-xs font-black text-gray-800">{{ $act->nombre }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if($act->servicio)
                            <span class="text-[9px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-lg">
                                {{ $act->servicio->nombre }}
                            </span>
                            @else
                            <span class="text-[9px] text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-xs font-semibold text-gray-600">{{ $act->puesto_responsable ?? '—' }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-[9px] text-gray-400 font-bold">{{ $act->created_at->format('d/m/Y') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <button @click="$confirm(
                                    '{{ $act->activo ? 'Desactivar' : 'Activar' }} actividad',
                                    () => $wire.toggleActivo({{ $act->id }}),
                                    { icon: '{{ $act->activo ? 'warning' : 'question' }}', confirmText: 'Sí, {{ $act->activo ? 'desactivar' : 'activar' }}' }
                                )"
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest cursor-pointer transition-all
                                    {{ $act->activo ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-red-100 text-red-600 hover:bg-red-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $act->activo ? 'bg-emerald-500' : 'bg-red-400' }}"></span>
                                {{ $act->activo ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button wire:click="editar({{ $act->id }})"
                                    class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Editar">
                                    <i class="ri-pencil-line text-sm"></i>
                                </button>
                                <button @click="$confirm(
                                        '¿Eliminar la actividad {{ addslashes($act->nombre) }}?',
                                        () => $wire.eliminar({{ $act->id }}),
                                        { icon: 'warning', confirmText: 'Sí, eliminar', title: '¿Eliminar actividad?' }
                                    )"
                                    class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                    <i class="ri-delete-bin-line text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="ri-list-check-3 text-3xl text-gray-200"></i>
                                <p class="text-xs font-black uppercase tracking-widest text-gray-300">Sin actividades registradas</p>
                                @if($search || $filtroPuesto)
                                <button wire:click="limpiarFiltros" class="text-[9px] font-black uppercase tracking-widest text-emerald-500 hover:underline mt-1">Limpiar filtros</button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($actividades->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">{{ $actividades->links() }}</div>
        @endif
    </div>
</div>
