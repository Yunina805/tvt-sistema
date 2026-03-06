<div>
    {{-- BREADCRUMB --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Clientes</span>
        <span>/</span>
        <span class="text-gray-600">Registro de Clientes</span>
    </nav>

    {{-- ENCABEZADO --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Registro de Clientes</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Clientes · Catálogo</p>
        </div>
        @if($modo === 'lista')
        <button wire:click="nuevo"
            class="flex items-center gap-2 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-colors shadow-sm">
            <i class="ri-user-add-line text-sm"></i>
            <span>Nuevo Cliente</span>
        </button>
        @endif
    </div>

    {{-- KPI CARDS --}}
    @if($modo === 'lista')
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-teal-50 rounded-2xl p-4 flex items-center gap-3 shadow-sm border border-teal-100">
            <div class="w-10 h-10 bg-teal-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-group-line text-teal-600 text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-teal-500">Total Clientes</p>
                <p class="text-xl font-black text-teal-800">{{ $totalClientes }}</p>
            </div>
        </div>
        <div class="bg-emerald-50 rounded-2xl p-4 flex items-center gap-3 shadow-sm border border-emerald-100">
            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-user-follow-line text-emerald-600 text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500">Activos</p>
                <p class="text-xl font-black text-emerald-800">{{ $totalActivos }}</p>
            </div>
        </div>
        <div class="bg-red-50 rounded-2xl p-4 flex items-center gap-3 shadow-sm border border-red-100">
            <div class="w-10 h-10 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-user-unfollow-line text-red-500 text-lg"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-red-400">Inactivos</p>
                <p class="text-xl font-black text-red-700">{{ $totalClientes - $totalActivos }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- FORMULARIO CREAR / EDITAR                                              --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    @if($modo !== 'lista')
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden mb-6">
        {{-- Header formulario --}}
        <div class="flex items-center justify-between px-5 py-3.5 border-b border-gray-100 bg-gray-50">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 bg-teal-100 rounded-lg flex items-center justify-center">
                    <i class="ri-user-{{ $modo === 'crear' ? 'add' : 'edit' }}-line text-teal-600 text-sm"></i>
                </div>
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-700">
                    {{ $modo === 'crear' ? 'Nuevo Cliente' : 'Editar Cliente' }}
                </p>
            </div>
            <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-lg"></i>
            </button>
        </div>

        <form wire:submit="guardar" class="p-5 space-y-5">

            {{-- ── DATOS PERSONALES ── --}}
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-teal-500 mb-3 flex items-center gap-1.5">
                    <i class="ri-user-line"></i> Datos Personales
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    {{-- Nombre --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Nombre <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="nombre"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent placeholder-gray-300 uppercase"
                            placeholder="JUAN">
                        @error('nombre') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    {{-- Apellido Paterno --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Apellido Paterno <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="apellidoPaterno"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent placeholder-gray-300 uppercase"
                            placeholder="PÉREZ">
                        @error('apellidoPaterno') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    {{-- Apellido Materno --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Apellido Materno
                        </label>
                        <input type="text" wire:model="apellidoMaterno"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent placeholder-gray-300 uppercase"
                            placeholder="GARCÍA">
                        @error('apellidoMaterno') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    {{-- Teléfono --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Teléfono <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="telefono"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent placeholder-gray-300"
                            placeholder="7891234567">
                        @error('telefono') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    {{-- Correo --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Correo Electrónico
                        </label>
                        <input type="email" wire:model="correo"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent placeholder-gray-300"
                            placeholder="cliente@correo.com">
                        @error('correo') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    {{-- CURP --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            CURP
                        </label>
                        <input type="text" wire:model="curp" maxlength="18"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent placeholder-gray-300 uppercase"
                            placeholder="PEGJ800101HVZRRL05">
                        @error('curp') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-dashed border-gray-100"></div>

            {{-- ── DOMICILIO ── --}}
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-teal-500 mb-3 flex items-center gap-1.5">
                    <i class="ri-map-pin-line"></i> Domicilio
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    {{-- Sucursal --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Sucursal <span class="text-red-500">*</span>
                        </label>
                        <select wire:model.live="sucursalId"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent bg-white">
                            <option value="">— Seleccionar —</option>
                            @foreach($sucursales as $suc)
                            <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                            @endforeach
                        </select>
                        @error('sucursalId') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    {{-- Estado (read-only) --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Estado <span class="text-[9px] text-gray-400 normal-case font-bold">(automático)</span>
                        </label>
                        <input type="text" value="{{ $estadoNombre }}" readonly
                            class="w-full border border-gray-100 rounded-xl px-3 py-2 text-xs font-semibold text-gray-500 bg-gray-50 cursor-not-allowed"
                            placeholder="Se completa al seleccionar sucursal">
                    </div>
                    {{-- Municipio (read-only) --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Municipio <span class="text-[9px] text-gray-400 normal-case font-bold">(automático)</span>
                        </label>
                        <input type="text" value="{{ $municipioNombre }}" readonly
                            class="w-full border border-gray-100 rounded-xl px-3 py-2 text-xs font-semibold text-gray-500 bg-gray-50 cursor-not-allowed"
                            placeholder="Se completa al seleccionar sucursal">
                    </div>
                    {{-- Localidad (read-only) --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Localidad <span class="text-[9px] text-gray-400 normal-case font-bold">(automático)</span>
                        </label>
                        <input type="text" value="{{ $localidadNombre }}" readonly
                            class="w-full border border-gray-100 rounded-xl px-3 py-2 text-xs font-semibold text-gray-500 bg-gray-50 cursor-not-allowed"
                            placeholder="Se completa al seleccionar sucursal">
                    </div>
                    {{-- Calle --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Calle
                        </label>
                        <select wire:model="calleId"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent bg-white disabled:bg-gray-50 disabled:text-gray-400"
                            @disabled(empty($callesDisponibles))>
                            <option value="">— {{ empty($callesDisponibles) ? 'Selecciona sucursal primero' : 'Seleccionar' }} —</option>
                            @foreach($callesDisponibles as $calle)
                            <option value="{{ $calle['id'] }}">{{ $calle['nombre_calle'] }}</option>
                            @endforeach
                        </select>
                        @error('calleId') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    {{-- CP (read-only) --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            CP <span class="text-[9px] text-gray-400 normal-case font-bold">(automático)</span>
                        </label>
                        <input type="text" value="{{ $codigoPostal }}" readonly
                            class="w-full border border-gray-100 rounded-xl px-3 py-2 text-xs font-semibold text-gray-500 bg-gray-50 cursor-not-allowed"
                            placeholder="00000">
                    </div>
                    {{-- Número Exterior --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Número Exterior <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="numeroExterior"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent placeholder-gray-300 uppercase"
                            placeholder="12">
                        @error('numeroExterior') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    {{-- Número Interior --}}
                    <div>
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Número Interior
                        </label>
                        <input type="text" wire:model="numeroInterior"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent placeholder-gray-300 uppercase"
                            placeholder="NA">
                        @error('numeroInterior') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                    {{-- Referencias --}}
                    <div class="sm:col-span-3">
                        <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">
                            Referencias
                        </label>
                        <textarea wire:model="referencias" rows="2"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-teal-300 focus:border-transparent placeholder-gray-300 resize-none"
                            placeholder="Casa de color azul, frente al parque..."></textarea>
                        @error('referencias') <p class="text-[9px] text-red-500 mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-dashed border-gray-100"></div>

            {{-- Activo toggle --}}
            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer select-none">
                    <div class="relative">
                        <input type="checkbox" wire:model="activo" class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-200 rounded-full peer-checked:bg-teal-500 transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                    <span class="text-[9px] font-black uppercase tracking-widest text-gray-600">Activo</span>
                </label>
            </div>

            {{-- Botones --}}
            <div class="flex items-center justify-end gap-3 pt-2">
                <button type="button" wire:click="cancelar"
                    class="px-5 py-2 text-xs font-black uppercase tracking-widest text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors">
                    Cancelar
                </button>
                <button type="submit"
                    class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-colors shadow-sm flex items-center gap-2">
                    <i class="ri-save-line text-sm"></i>
                    {{ $modo === 'crear' ? 'Registrar Cliente' : 'Guardar Cambios' }}
                </button>
            </div>
        </form>
    </div>
    @endif

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- PANEL DE FILTROS                                                       --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    @php $hayFiltros = $search || $filtroSucursal || $filtroActivo !== ''; @endphp
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-4" x-data="{ openFilters: true }">
        <button type="button" @click="openFilters = !openFilters"
            class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50/60 transition-colors rounded-2xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-teal-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-equalizer-2-line text-teal-500 text-base"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-black uppercase tracking-widest text-gray-700">Filtros de Búsqueda</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest mt-0.5 {{ $hayFiltros ? 'text-teal-500' : 'text-gray-400' }}">
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
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Nombre, teléfono, CURP..."
                            class="w-full pl-9 pr-3 py-2.5 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-200 focus:border-teal-300 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Sucursal</label>
                    <select wire:model.live="filtroSucursal"
                        class="w-full py-2.5 px-3 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-200 focus:border-teal-300 transition-all">
                        <option value="">Todas las sucursales</option>
                        @foreach($sucursales as $suc)
                        <option value="{{ $suc->id }}">{{ $suc->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Estatus</label>
                    <select wire:model.live="filtroActivo"
                        class="w-full py-2.5 px-3 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-teal-200 focus:border-teal-300 transition-all">
                        <option value="">Todos los estatus</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    {{-- LISTA                                                                  --}}
    {{-- ═══════════════════════════════════════════════════════════════════════ --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">

        {{-- Tabla --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">ID</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Nombre Completo</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Teléfono</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">CURP</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Sucursal</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Calle / Número</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Estatus</th>
                        <th class="text-left px-4 py-2.5 text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($clientes as $cliente)
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- ID --}}
                        <td class="px-4 py-3">
                            <span class="text-[9px] font-black text-teal-600 bg-teal-50 px-2 py-0.5 rounded-lg uppercase tracking-widest">
                                CLI-{{ str_pad($cliente->id, 4, '0', STR_PAD_LEFT) }}
                            </span>
                        </td>
                        {{-- Nombre --}}
                        <td class="px-4 py-3">
                            <p class="text-xs font-black text-gray-800">{{ $cliente->nombre }} {{ $cliente->apellido_paterno }}</p>
                            @if($cliente->apellido_materno)
                            <p class="text-[9px] text-gray-400 font-bold">{{ $cliente->apellido_materno }}</p>
                            @endif
                        </td>
                        {{-- Teléfono --}}
                        <td class="px-4 py-3">
                            <p class="text-xs font-semibold text-gray-600">{{ $cliente->telefono }}</p>
                            @if($cliente->correo)
                            <p class="text-[9px] text-gray-400">{{ $cliente->correo }}</p>
                            @endif
                        </td>
                        {{-- CURP --}}
                        <td class="px-4 py-3">
                            <p class="text-[9px] font-mono font-bold text-gray-500">
                                {{ $cliente->curp ?? '—' }}
                            </p>
                        </td>
                        {{-- Sucursal --}}
                        <td class="px-4 py-3">
                            <p class="text-xs font-semibold text-gray-700">{{ $cliente->sucursal?->nombre ?? '—' }}</p>
                        </td>
                        {{-- Calle / Número --}}
                        <td class="px-4 py-3">
                            <p class="text-xs font-semibold text-gray-700">
                                {{ $cliente->calle?->nombre_calle ?? 'S/C' }}
                                {{ $cliente->numero_exterior }}
                                @if($cliente->numero_interior && $cliente->numero_interior !== 'NA')
                                Int. {{ $cliente->numero_interior }}
                                @endif
                            </p>
                        </td>
                        {{-- Estatus --}}
                        <td class="px-4 py-3">
                            <button @click="$confirm(
                                    '{{ $cliente->activo ? 'Desactivar' : 'Activar' }} cliente',
                                    () => $wire.toggleActivo({{ $cliente->id }}),
                                    { icon: '{{ $cliente->activo ? 'warning' : 'question' }}', confirmText: 'Sí, {{ $cliente->activo ? 'desactivar' : 'activar' }}' }
                                )"
                                class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest cursor-pointer transition-all
                                    {{ $cliente->activo
                                        ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200'
                                        : 'bg-red-100 text-red-600 hover:bg-red-200' }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $cliente->activo ? 'bg-emerald-500' : 'bg-red-400' }}"></span>
                                {{ $cliente->activo ? 'Activo' : 'Inactivo' }}
                            </button>
                        </td>
                        {{-- Acciones --}}
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                <button wire:click="editar({{ $cliente->id }})"
                                    class="p-1.5 text-gray-400 hover:text-teal-600 hover:bg-teal-50 rounded-lg transition-colors"
                                    title="Editar">
                                    <i class="ri-pencil-line text-sm"></i>
                                </button>
                                <button @click="$confirm(
                                        '¿Eliminar al cliente {{ addslashes($cliente->nombre . ' ' . $cliente->apellido_paterno) }}? Esta acción no se puede deshacer.',
                                        () => $wire.eliminar({{ $cliente->id }}),
                                        { icon: 'warning', confirmText: 'Sí, eliminar', title: '¿Eliminar cliente?' }
                                    )"
                                    class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors"
                                    title="Eliminar">
                                    <i class="ri-delete-bin-line text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-12 text-center">
                            <div class="flex flex-col items-center gap-2">
                                <i class="ri-user-search-line text-3xl text-gray-200"></i>
                                <p class="text-xs font-black uppercase tracking-widest text-gray-300">Sin resultados</p>
                                @if($search || $filtroSucursal || $filtroActivo)
                                <button wire:click="limpiarFiltros"
                                    class="text-[9px] font-black uppercase tracking-widest text-teal-500 hover:underline mt-1">
                                    Limpiar filtros
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($clientes->hasPages())
        <div class="px-5 py-3 border-t border-gray-100">
            {{ $clientes->links() }}
        </div>
        @endif
    </div>
</div>
