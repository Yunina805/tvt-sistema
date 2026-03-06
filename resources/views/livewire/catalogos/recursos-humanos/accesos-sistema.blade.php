<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Recursos Humanos</span>
        <span>/</span>
        <span class="text-gray-600">Accesos al Sistema</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Accesos al Sistema</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Recursos Humanos · Catálogo</p>
        </div>
        @if($modo === 'lista')
            <button wire:click="nuevoAcceso"
                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                <i class="ri-add-line"></i>
                Nuevo Acceso
            </button>
        @endif
    </div>

    {{-- KPIs --}}
    @if($modo === 'lista')
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-shield-keyhole-line text-indigo-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-indigo-500">Total Accesos</p>
                    <p class="text-lg font-black text-indigo-800">{{ $totalAccesos }}</p>
                </div>
            </div>
        </div>
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-user-follow-line text-emerald-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500">Activos</p>
                    <p class="text-lg font-black text-emerald-800">{{ $totalActivos }}</p>
                </div>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-admin-line text-blue-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-blue-500">Administradores</p>
                    <p class="text-lg font-black text-blue-800">{{ $porRol['ADMINISTRADOR'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Formulario Crear / Editar --}}
    @if($modo !== 'lista')
        <div class="bg-white border border-indigo-200 rounded-2xl shadow-sm mb-6 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="ri-shield-keyhole-line text-indigo-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-800">
                            {{ $modo === 'crear' ? 'Nuevo Acceso al Sistema' : 'Editar Acceso' }}
                        </p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Recursos Humanos</p>
                    </div>
                </div>
                <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            {{-- Sección: Empleado y Rol --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
                {{-- Empleado --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Empleado <span class="text-red-500">*</span></label>
                    @if($modo === 'crear')
                        <select wire:model="empleadoId"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('empleadoId') border-red-300 @enderror">
                            <option value="">— Seleccionar —</option>
                            @foreach($empleadosSinAcceso as $e)
                                <option value="{{ $e->id }}">{{ $e->apellido_paterno }} {{ $e->apellido_materno }} {{ $e->nombre }}</option>
                            @endforeach
                        </select>
                    @else
                        @php
                            $empActual = $empleados->firstWhere('id', (int) $empleadoId);
                        @endphp
                        <div class="w-full border border-gray-100 rounded-xl px-3 py-2 text-xs text-gray-500 bg-gray-50">
                            {{ $empActual ? "{$empActual->apellido_paterno} {$empActual->apellido_materno} {$empActual->nombre}" : '—' }}
                        </div>
                    @endif
                    @error('empleadoId') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Rol --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Rol <span class="text-red-500">*</span></label>
                    <select wire:model="rol"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('rol') border-red-300 @enderror">
                        <option value="">— Seleccionar —</option>
                        <option value="ADMINISTRADOR">Administrador</option>
                        <option value="GERENTE">Gerente</option>
                        <option value="SUPERVISOR">Supervisor</option>
                        <option value="OPERADOR">Operador</option>
                        <option value="CAJA">Caja</option>
                        <option value="LECTURA">Solo lectura</option>
                    </select>
                    @error('rol') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Activo --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Estado</label>
                    <label class="flex items-center gap-2 cursor-pointer mt-2">
                        <input wire:model="activo" type="checkbox" class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-300">
                        <span class="text-xs text-gray-700 font-medium">Acceso activo</span>
                    </label>
                </div>
            </div>

            {{-- Sección: Credenciales (CREAR) --}}
            @if($modo === 'crear')
                <div class="border-t border-dashed border-indigo-100 pt-4 mb-4">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-3 flex items-center gap-2">
                        <i class="ri-key-2-line"></i> Credenciales de Acceso
                    </p>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Correo Electrónico <span class="text-red-500">*</span></label>
                            <input wire:model="email" type="email" placeholder="usuario@tvisiontv.mx"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('email') border-red-300 @enderror">
                            @error('email') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Contraseña <span class="text-red-500">*</span></label>
                            <input wire:model="password" type="password" placeholder="Mínimo 8 caracteres"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('password') border-red-300 @enderror">
                            @error('password') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Confirmar Contraseña <span class="text-red-500">*</span></label>
                            <input wire:model="passwordConfirmation" type="password" placeholder="Repetir contraseña"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('passwordConfirmation') border-red-300 @enderror">
                            @error('passwordConfirmation') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- Sección: Usuario actual + Cambio de contraseña (EDITAR) --}}
            @if($modo === 'editar')
                <div class="border-t border-dashed border-indigo-100 pt-4 mb-4">
                    <p class="text-[9px] font-black uppercase tracking-[0.2em] text-indigo-500 mb-3 flex items-center gap-2">
                        <i class="ri-user-line"></i> Cuenta de Acceso
                    </p>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Correo (solo lectura)</label>
                            <div class="w-full border border-gray-100 rounded-xl px-3 py-2 text-xs text-gray-500 bg-gray-50">
                                {{ $userEmail ?? 'Sin cuenta vinculada' }}
                            </div>
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Nueva Contraseña <span class="text-gray-300">(opcional)</span></label>
                            <input wire:model="newPassword" type="password" placeholder="Dejar vacío para no cambiar"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('newPassword') border-red-300 @enderror">
                            @error('newPassword') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Confirmar Nueva Contraseña</label>
                            <input wire:model="newPasswordConfirmation" type="password" placeholder="Repetir nueva contraseña"
                                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 @error('newPasswordConfirmation') border-red-300 @enderror">
                            @error('newPasswordConfirmation') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            @endif

            {{-- Módulos y sub-links --}}
            <div class="border-t border-dashed border-gray-100 pt-4">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-[9px] font-black uppercase tracking-widest text-gray-500">
                        Módulos y Accesos Permitidos
                        <span class="text-gray-300 font-medium normal-case tracking-normal ml-1">(vacío = acceso total)</span>
                    </label>
                    @if(!empty($modulos))
                        <span class="text-[9px] font-bold text-indigo-500 bg-indigo-50 px-2 py-0.5 rounded-full">
                            {{ count($modulos) }} módulo(s) seleccionado(s)
                        </span>
                    @else
                        <span class="text-[9px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                            <i class="ri-check-line"></i> Acceso completo al sistema
                        </span>
                    @endif
                </div>

                <div class="space-y-2">
                    @foreach($modulosDisponibles as $modulo)
                        @php
                            $checked    = in_array($modulo, $modulos);
                            $subs       = $submodulosDisponibles[$modulo] ?? [];
                            $subChecked = $submodulos[$modulo] ?? [];
                            $label      = $modulosLabels[$modulo] ?? $modulo;
                            $todosLos   = empty($subChecked);
                        @endphp
                        <div class="border rounded-xl overflow-hidden transition-colors
                            {{ $checked ? 'border-indigo-200 bg-indigo-50/20' : 'border-gray-100 bg-white' }}">

                            {{-- Cabecera del módulo --}}
                            <label class="flex items-center gap-3 cursor-pointer px-3 py-2.5 hover:bg-indigo-50/40 transition-colors">
                                <input wire:model.live="modulos" type="checkbox" value="{{ $modulo }}"
                                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-300 shrink-0">
                                <span class="flex-1 text-[10px] font-black uppercase tracking-wide
                                    {{ $checked ? 'text-indigo-700' : 'text-gray-600' }}">
                                    {{ $label }}
                                </span>
                                @if($checked)
                                    @if($todosLos)
                                        <span class="text-[8px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-1.5 py-0.5 rounded-full uppercase tracking-wide">
                                            <i class="ri-check-double-line"></i> Todas las páginas
                                        </span>
                                    @else
                                        <span class="text-[8px] font-bold text-indigo-600 bg-indigo-50 border border-indigo-100 px-1.5 py-0.5 rounded-full uppercase tracking-wide">
                                            {{ count($subChecked) }}/{{ count($subs) }} páginas
                                        </span>
                                    @endif
                                @endif
                            </label>

                            {{-- Panel de sub-links (solo si módulo activo y tiene sub-links) --}}
                            @if($checked && count($subs) > 0)
                                <div class="border-t border-indigo-100 bg-white px-4 py-3">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-2 flex items-center gap-1.5">
                                        <i class="ri-layout-grid-line text-indigo-300"></i>
                                        Páginas permitidas
                                        <span class="font-normal normal-case text-gray-300 tracking-normal">(vacío = todas)</span>
                                    </p>
                                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-1">
                                        @foreach($subs as $key => $sublabel)
                                            <label class="flex items-center gap-1.5 cursor-pointer px-2 py-1.5 rounded-lg
                                                hover:bg-indigo-50 transition-colors
                                                {{ in_array($key, $subChecked) ? 'bg-indigo-50/60' : '' }}">
                                                <input wire:model="submodulos.{{ $modulo }}" type="checkbox" value="{{ $key }}"
                                                    class="rounded border-gray-300 text-indigo-500 focus:ring-indigo-300 w-3 h-3 shrink-0">
                                                <span class="text-[9px] font-medium leading-tight
                                                    {{ in_array($key, $subChecked) ? 'text-indigo-700' : 'text-gray-500' }}">
                                                    {{ $sublabel }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-5">
                <button wire:click="cancelar"
                    class="px-4 py-2 text-xs font-black uppercase tracking-wider text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button wire:click="guardar" wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors disabled:opacity-60">
                    <span wire:loading.remove wire:target="guardar"><i class="ri-save-line"></i> Guardar</span>
                    <span wire:loading wire:target="guardar"><i class="ri-loader-4-line animate-spin"></i> Guardando...</span>
                </button>
            </div>
        </div>
    @endif

    {{-- Panel de Filtros --}}
    @php $hayFiltros = $search || $filtroRol || $filtroActivo !== ''; @endphp
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-4" x-data="{ openFilters: true }">
        <button type="button" @click="openFilters = !openFilters"
            class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50/60 transition-colors rounded-2xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-equalizer-2-line text-indigo-500 text-base"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-black uppercase tracking-widest text-gray-700">Filtros de Búsqueda</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest mt-0.5 {{ $hayFiltros ? 'text-indigo-500' : 'text-gray-400' }}">
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
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Nombre del empleado o correo..."
                            class="w-full pl-9 pr-3 py-2.5 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Rol</label>
                    <select wire:model.live="filtroRol"
                        class="w-full py-2.5 px-3 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 transition-all">
                        <option value="">Todos los roles</option>
                        <option value="ADMINISTRADOR">Administrador</option>
                        <option value="GERENTE">Gerente</option>
                        <option value="SUPERVISOR">Supervisor</option>
                        <option value="OPERADOR">Operador</option>
                        <option value="CAJA">Caja</option>
                        <option value="LECTURA">Solo lectura</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Estado</label>
                    <select wire:model.live="filtroActivo"
                        class="w-full py-2.5 px-3 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-200 focus:border-indigo-300 transition-all">
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
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Empleado</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Cuenta</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Rol</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Módulos</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Último Acceso</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($accesos as $acc)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-800 font-semibold">{{ $acc->empleado?->nombre_completo ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-600">
                                @if($acc->usuario)
                                    <p class="font-medium">{{ $acc->usuario->name }}</p>
                                    <p class="text-[9px] text-gray-400">{{ $acc->usuario->email }}</p>
                                @else
                                    <span class="text-gray-300">Sin cuenta</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $rolColor = match($acc->rol) {
                                        'ADMINISTRADOR' => 'bg-red-50 text-red-700',
                                        'GERENTE'       => 'bg-violet-50 text-violet-700',
                                        'SUPERVISOR'    => 'bg-blue-50 text-blue-700',
                                        'OPERADOR'      => 'bg-emerald-50 text-emerald-700',
                                        'CAJA'          => 'bg-amber-50 text-amber-700',
                                        default         => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 {{ $rolColor }} rounded text-[9px] font-black uppercase">{{ $acc->rol }}</span>
                            </td>
                            <td class="px-5 py-3">
                                @if($acc->modulos)
                                    @php
                                        // Soporte para formato viejo (array plano) y nuevo (objeto)
                                        $permisos = $acc->modulos;
                                        $esViejo  = !empty($permisos) && array_is_list($permisos);
                                    @endphp
                                    <div class="flex flex-wrap gap-1">
                                        @if($esViejo)
                                            @foreach($permisos as $mod)
                                                <span class="px-1.5 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[8px] font-bold">{{ $mod }}</span>
                                            @endforeach
                                        @else
                                            @foreach($permisos as $mod => $subs)
                                                <span class="px-1.5 py-0.5 bg-indigo-50 text-indigo-600 rounded text-[8px] font-bold"
                                                    title="{{ empty($subs) ? 'Todas las páginas' : implode(', ', $subs) }}">
                                                    {{ $mod }}{{ !empty($subs) ? ' ·' . count($subs) : '' }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                @else
                                    <span class="text-gray-300 text-[9px]">Acceso total</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-gray-500 font-mono">
                                {{ $acc->ultimo_acceso?->format('d/m/Y H:i') ?? '—' }}
                            </td>
                            <td class="px-5 py-3">
                                @if($acc->activo)
                                    <span class="flex items-center gap-1 text-emerald-600 text-[9px] font-black uppercase">
                                        <i class="ri-checkbox-circle-line"></i> Activo
                                    </span>
                                @else
                                    <span class="flex items-center gap-1 text-gray-400 text-[9px] font-black uppercase">
                                        <i class="ri-close-circle-line"></i> Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editar({{ $acc->id }})"
                                        class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Editar">
                                        <i class="ri-edit-line text-sm"></i>
                                    </button>
                                    <button @click="$confirm('{{ $acc->activo ? '¿Desactivar este acceso?' : '¿Activar este acceso?' }}', () => $wire.toggleActivo({{ $acc->id }}))"
                                        class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                        title="{{ $acc->activo ? 'Desactivar' : 'Activar' }}">
                                        <i class="ri-toggle-{{ $acc->activo ? 'fill' : 'line' }} text-sm"></i>
                                    </button>
                                    <button @click="$confirm('¿Eliminar este acceso? También se eliminará el usuario del sistema.', () => $wire.eliminar({{ $acc->id }}), { icon: 'warning', confirmText: 'Sí, eliminar' })"
                                        class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Eliminar acceso">
                                        <i class="ri-delete-bin-line text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <i class="ri-shield-keyhole-line text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin accesos configurados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($accesos->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $accesos->links() }}
            </div>
        @endif
    </div>
</div>
