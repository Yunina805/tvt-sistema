<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Financiero</span>
        <span>/</span>
        <span class="text-gray-600">Tipo Ingresos / Egresos</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Tipo Ingresos / Egresos</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Financiero · Catálogo</p>
        </div>
        @if($modo === 'lista')
            <button wire:click="nuevo"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                <i class="ri-add-line"></i> Nuevo Tipo
            </button>
        @endif
    </div>

    {{-- KPIs --}}
    @if($modo === 'lista')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-arrow-up-down-line text-emerald-600 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500">Total</p>
                <p class="text-lg font-black text-emerald-800">{{ $kpis['total'] }}</p>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-arrow-up-circle-line text-blue-600 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-blue-500">Ingresos</p>
                <p class="text-lg font-black text-blue-800">{{ $kpis['ingresos'] }}</p>
            </div>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-arrow-down-circle-line text-red-500 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-red-400">Egresos</p>
                <p class="text-lg font-black text-red-700">{{ $kpis['egresos'] }}</p>
            </div>
        </div>
        <div class="bg-gray-50 border border-gray-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-toggle-line text-gray-500 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-gray-500">Activos</p>
                <p class="text-lg font-black text-gray-800">{{ $kpis['activos'] }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- Formulario --}}
    @if($modo !== 'lista')
    <div class="bg-white border border-emerald-200 rounded-2xl shadow-sm mb-6 p-6">
        <div class="flex items-center justify-between mb-5">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="ri-arrow-up-down-line text-emerald-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-gray-800">
                        {{ $modo === 'crear' ? 'Nuevo Tipo Ingreso / Egreso' : 'Editar Tipo' }}
                    </p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Financiero</p>
                </div>
            </div>
            <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Tipo <span class="text-red-500">*</span></label>
                <select wire:model="tipo"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('tipo') border-red-300 @enderror">
                    <option value="">— Seleccionar —</option>
                    <option value="INGRESO">Ingreso</option>
                    <option value="EGRESO">Egreso</option>
                </select>
                @error('tipo') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="lg:col-span-2">
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Nombre <span class="text-red-500">*</span></label>
                <input wire:model="nombre" type="text" placeholder="Ej. Pago mensualidad, Compra materiales..."
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('nombre') border-red-300 @enderror">
                @error('nombre') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Perfil Autorizado <span class="text-gray-300 font-normal normal-case">(opcional)</span></label>
                <select wire:model="perfilAutorizado"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('perfilAutorizado') border-red-300 @enderror">
                    <option value="">Todos los perfiles</option>
                    @foreach($roles as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
                @error('perfilAutorizado') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="flex items-end pb-0.5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="activo" type="checkbox"
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-300">
                    <span class="text-xs text-gray-600 font-medium">Tipo activo</span>
                </label>
            </div>
        </div>

        {{-- Vigencia --}}
        <div class="border-t border-dashed border-gray-100 pt-4 mb-4">
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 flex items-center gap-2">
                <i class="ri-calendar-line"></i> Vigencia
            </p>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Fecha Habilitación <span class="text-red-500">*</span></label>
                    <input wire:model="fechaHabilitacion" type="date"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('fechaHabilitacion') border-red-300 @enderror">
                    @error('fechaHabilitacion') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Fecha Término</label>
                    <input wire:model="fechaTermino" type="date" @disabled($sinTermino)
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 disabled:opacity-40 disabled:bg-gray-50 @error('fechaTermino') border-red-300 @enderror">
                    @error('fechaTermino') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="flex items-end pb-0.5">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model.live="sinTermino" type="checkbox"
                            class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-300">
                        <span class="text-xs text-gray-600 font-medium">Vigencia indefinida</span>
                    </label>
                </div>
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
                <span wire:loading wire:target="guardar"><i class="ri-loader-4-line animate-spin"></i> Guardando...</span>
            </button>
        </div>
    </div>
    @endif

    {{-- Panel de Filtros --}}
    @php $hayFiltros = $search || $filtroTipo || $filtroActivo !== ''; @endphp
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
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Nombre del tipo de movimiento..."
                            class="w-full pl-9 pr-3 py-2.5 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Tipo de Movimiento</label>
                    <select wire:model.live="filtroTipo"
                        class="w-full py-2.5 px-3 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-300 transition-all">
                        <option value="">Ingreso y Egreso</option>
                        <option value="INGRESO">Solo Ingresos</option>
                        <option value="EGRESO">Solo Egresos</option>
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

    {{-- Tabla --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">ID</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Tipo</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Nombre</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Perfil Autorizado</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Vigencia</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Creado por</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($registros as $reg)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-gray-400 text-[9px]">{{ str_pad($reg->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3">
                                @if($reg->tipo === 'INGRESO')
                                    <span class="flex items-center gap-1 text-blue-600 text-[9px] font-black uppercase">
                                        <i class="ri-arrow-up-circle-line"></i> Ingreso
                                    </span>
                                @else
                                    <span class="flex items-center gap-1 text-red-600 text-[9px] font-black uppercase">
                                        <i class="ri-arrow-down-circle-line"></i> Egreso
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $reg->nombre }}</td>
                            <td class="px-4 py-3 text-gray-500 text-[10px]">{{ $reg->perfil_autorizado ?? 'Todos' }}</td>
                            <td class="px-4 py-3 text-gray-500 font-mono text-[10px]">
                                {{ $reg->fecha_habilitacion->format('d/m/Y') }}
                                @if($reg->fecha_termino)
                                    — {{ $reg->fecha_termino->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-300">Indef.</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-[10px]">{{ $reg->usuario?->name ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($reg->activo)
                                    <span class="flex items-center gap-1 text-emerald-600 text-[9px] font-black uppercase">
                                        <i class="ri-checkbox-circle-line"></i> Activo
                                    </span>
                                @else
                                    <span class="flex items-center gap-1 text-gray-400 text-[9px] font-black uppercase">
                                        <i class="ri-close-circle-line"></i> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="editar({{ $reg->id }})"
                                        class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                        <i class="ri-edit-line text-sm"></i>
                                    </button>
                                    <button @click="$confirm('{{ $reg->activo ? '¿Desactivar este tipo?' : '¿Activar este tipo?' }}', () => $wire.toggleActivo({{ $reg->id }}))"
                                        class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <i class="ri-toggle-{{ $reg->activo ? 'fill' : 'line' }} text-sm"></i>
                                    </button>
                                    <button @click="$confirm('¿Eliminar este tipo?', () => $wire.eliminar({{ $reg->id }}), { icon: 'warning', confirmText: 'Sí, eliminar' })"
                                        class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="ri-delete-bin-line text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <i class="ri-arrow-up-down-line text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin tipos registrados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($registros->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">{{ $registros->links() }}</div>
        @endif
    </div>
</div>
