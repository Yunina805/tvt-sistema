<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Servicios</span>
        <span>/</span>
        <span class="text-gray-600">Registro para Tarifas Principales</span>
    </nav>

    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Servicios — Tarifas Principales</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Catálogo de Servicios · Tarifas Principales</p>
        </div>
        @if($modo === 'lista')
        <button wire:click="nuevo"
            class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors shadow-sm shadow-emerald-200">
            <i class="ri-add-line"></i> Nuevo Servicio
        </button>
        @endif
    </div>

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ri-price-tag-3-line text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-0.5">Total</p>
                    <p class="text-2xl font-black text-gray-900 leading-none">{{ $total }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ri-checkbox-circle-line text-emerald-500"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-0.5">Activos</p>
                    <p class="text-2xl font-black text-gray-900 leading-none">{{ $activos }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="ri-close-circle-line text-red-400"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest leading-none mb-0.5">Inactivos</p>
                    <p class="text-2xl font-black text-gray-900 leading-none">{{ $inactivos }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Formulario --}}
    @if($modo !== 'lista')
    <div class="bg-white border border-emerald-200 rounded-2xl shadow-sm mb-6 p-6">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="ri-price-tag-3-line text-emerald-600 text-base"></i>
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-gray-800">
                        {{ $modo === 'crear' ? 'Nuevo Servicio' : 'Editar Servicio' }}
                    </p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Tarifas Principales</p>
                </div>
            </div>
            <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Nombre --}}
            <div class="space-y-1.5">
                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">
                    Nombre del Servicio <span class="text-red-400">*</span>
                </label>
                <input wire:model="nombre" type="text" placeholder="Nombre descriptivo del servicio"
                    class="w-full px-3 py-2.5 text-xs bg-white border {{ $errors->has('nombre') ? 'border-red-300' : 'border-gray-200' }} rounded-xl font-medium text-gray-700 placeholder:text-gray-300 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 transition-all">
                @error('nombre')<p class="text-[9px] font-bold text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Tarifa Principal --}}
            <div class="space-y-1.5">
                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">
                    Tarifa Principal Asociada <span class="text-red-400">*</span>
                </label>
                <select wire:model="tarifaPrincipalId"
                    class="w-full px-3 py-2.5 text-xs bg-white border {{ $errors->has('tarifaPrincipalId') ? 'border-red-300' : 'border-gray-200' }} rounded-xl font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 transition-all">
                    <option value="">— Selecciona una tarifa —</option>
                    @foreach($tarifasPrincipales as $tp)
                    <option value="{{ $tp->id }}">TP-{{ str_pad($tp->id, 4, '0', STR_PAD_LEFT) }} — {{ $tp->nombre_comercial }}</option>
                    @endforeach
                </select>
                @error('tarifaPrincipalId')<p class="text-[9px] font-bold text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Estado --}}
            <div class="space-y-1.5">
                <label class="block text-[9px] font-black text-gray-400 uppercase tracking-widest">Estado</label>
                <select wire:model="activo"
                    class="w-full px-3 py-2.5 text-xs bg-white border border-gray-200 rounded-xl font-medium text-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 transition-all">
                    <option value="1">Activo</option>
                    <option value="0">Inactivo</option>
                </select>
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-5">
            <button wire:click="cancelar"
                class="px-4 py-2 text-xs font-black uppercase tracking-wider text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl transition-colors">
                Cancelar
            </button>
            <button wire:click="guardar" wire:loading.attr="disabled"
                class="flex items-center gap-2 px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors disabled:opacity-60">
                <span wire:loading.remove wire:target="guardar"><i class="ri-save-line"></i> Guardar</span>
                <span wire:loading wire:target="guardar"><i class="ri-loader-4-line animate-spin"></i> Guardando…</span>
            </button>
        </div>
    </div>
    @endif

    {{-- Panel de Filtros --}}
    @php $hayFiltros = $search || $filtroActivo !== ''; @endphp
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
                <div class="sm:col-span-2 lg:col-span-3">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Búsqueda General</label>
                    <div class="relative">
                        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Nombre del servicio..."
                            class="w-full pl-9 pr-3 py-2.5 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 transition-all">
                    </div>
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

    {{-- Tabla --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50/50">
                        <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest w-20">ID</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Nombre del Servicio</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Tarifa Principal Asociada</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Fecha Creación</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black text-gray-400 uppercase tracking-widest">Responsable</th>
                        <th class="px-4 py-3 text-center text-[9px] font-black text-gray-400 uppercase tracking-widest">Estado</th>
                        <th class="px-4 py-3 text-right text-[9px] font-black text-gray-400 uppercase tracking-widest w-28">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($servicios as $svc)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-mono text-gray-400 text-[9px]">SV-{{ str_pad($svc->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td class="px-4 py-3 font-bold text-gray-800">{{ $svc->nombre }}</td>
                        <td class="px-4 py-3">
                            @if($svc->tarifaPrincipal)
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 text-[9px] font-black rounded-lg border border-emerald-100">
                                <i class="ri-price-tag-3-line text-[10px]"></i>
                                TP-{{ str_pad($svc->tarifaPrincipal->id, 4, '0', STR_PAD_LEFT) }} · {{ $svc->tarifaPrincipal->nombre_comercial }}
                            </span>
                            @else
                            <span class="text-gray-300 text-[9px] italic">Sin tarifa asignada</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-500 font-mono text-[10px]">{{ $svc->created_at->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-gray-500 text-[10px]">{{ $svc->usuario?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($svc->activo)
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[9px] font-black rounded-full border border-emerald-100">
                                <i class="ri-circle-fill text-[7px]"></i> Activo
                            </span>
                            @else
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-red-50 text-red-500 text-[9px] font-black rounded-full border border-red-100">
                                <i class="ri-circle-fill text-[7px]"></i> Inactivo
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                <button wire:click="editar({{ $svc->id }})"
                                    class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Editar">
                                    <i class="ri-edit-line text-sm"></i>
                                </button>
                                <button @click="$confirm('{{ $svc->activo ? '¿Desactivar este servicio?' : '¿Activar este servicio?' }}', () => $wire.toggleActivo({{ $svc->id }}))"
                                    class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                    title="{{ $svc->activo ? 'Desactivar' : 'Activar' }}">
                                    <i class="ri-toggle-{{ $svc->activo ? 'fill' : 'line' }} text-sm"></i>
                                </button>
                                <button @click="$confirm('¿Eliminar este servicio?', () => $wire.eliminar({{ $svc->id }}), { icon: 'warning', confirmText: 'Sí, eliminar' })"
                                    class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar">
                                    <i class="ri-delete-bin-line text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-14 text-center">
                            <i class="ri-price-tag-3-line text-3xl text-gray-200 block mb-2"></i>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin servicios registrados</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($servicios->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50/40">{{ $servicios->links() }}</div>
        @endif
    </div>
</div>
