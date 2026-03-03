<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Sedes e Infraestructura</span>
        <span>/</span>
        <span class="text-gray-600">Sucursales</span>
    </nav>

    {{-- Flash --}}
    @if(session('exito'))
        <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl">
            <i class="ri-checkbox-circle-line text-emerald-500 text-lg"></i>
            <p class="text-xs font-bold text-emerald-700">{{ session('exito') }}</p>
        </div>
    @endif
    @if(session('info'))
        <div class="mb-4 flex items-center gap-3 px-4 py-3 bg-amber-50 border border-amber-200 rounded-xl">
            <i class="ri-information-line text-amber-500 text-lg"></i>
            <p class="text-xs font-bold text-amber-700">{{ session('info') }}</p>
        </div>
    @endif

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Sucursales</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Sedes e Infraestructura · Catálogo</p>
        </div>
        @if($modo === 'lista')
            <button wire:click="nuevaSucursal"
                class="flex items-center gap-2 px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                <i class="ri-add-line"></i>
                Nueva Sucursal
            </button>
        @endif
    </div>

    {{-- Formulario Crear / Editar --}}
    @if($modo !== 'lista')
        <div class="bg-white border border-pink-200 rounded-2xl shadow-sm mb-6 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-pink-100 rounded-xl flex items-center justify-center">
                        <i class="ri-building-3-line text-pink-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-800">
                            {{ $modo === 'crear' ? 'Nueva Sucursal' : 'Editar Sucursal' }}
                        </p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Sedes e Infraestructura</p>
                    </div>
                </div>
                <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Nombre --}}
                <div class="col-span-2">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Nombre de Sucursal <span class="text-red-500">*</span></label>
                    <input wire:model="nombre" type="text" maxlength="120" placeholder="Ej. Sucursal Martínez de la Torre Centro"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('nombre') border-red-300 @enderror">
                    @error('nombre') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tipo Red --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Tipo de Red <span class="text-red-500">*</span></label>
                    <select wire:model="tipoRed"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('tipoRed') border-red-300 @enderror">
                        <option value="">— Seleccionar —</option>
                        <option value="COBRE">Cobre</option>
                        <option value="HIBRIDA">Híbrida</option>
                        <option value="FIBRA">Fibra Óptica</option>
                    </select>
                    @error('tipoRed') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Estado --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Estado <span class="text-red-500">*</span></label>
                    <select wire:model.live="estadoId"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('estadoId') border-red-300 @enderror">
                        <option value="">— Seleccionar —</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}">{{ $e->nombre_estado }}</option>
                        @endforeach
                    </select>
                    @error('estadoId') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Municipio --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Municipio <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model.live="municipioId"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 disabled:bg-gray-50 disabled:text-gray-400 @error('municipioId') border-red-300 @enderror"
                            @disabled(!$estadoId)>
                            <option value="">— Seleccionar —</option>
                            @foreach($municipiosFiltrados as $m)
                                <option value="{{ $m['id'] }}">{{ $m['nombre_municipio'] }}</option>
                            @endforeach
                        </select>
                        <span wire:loading wire:target="updatedEstadoId" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <i class="ri-loader-4-line animate-spin text-gray-400 text-xs"></i>
                        </span>
                    </div>
                    @error('municipioId') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Localidad --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Localidad <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model.live="localidadId"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 disabled:bg-gray-50 disabled:text-gray-400 @error('localidadId') border-red-300 @enderror"
                            @disabled(!$municipioId)>
                            <option value="">— Seleccionar —</option>
                            @foreach($localidadesFiltradas as $l)
                                <option value="{{ $l['id'] }}">{{ $l['nombre_localidad'] }}</option>
                            @endforeach
                        </select>
                        <span wire:loading wire:target="updatedMunicipioId" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <i class="ri-loader-4-line animate-spin text-gray-400 text-xs"></i>
                        </span>
                    </div>
                    @error('localidadId') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- CP (auto) --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Código Postal</label>
                    <input wire:model="codigoPostal" type="text" maxlength="5" placeholder="Auto desde localidad"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 bg-gray-50 @error('codigoPostal') border-red-300 @enderror">
                    @error('codigoPostal') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-5">
                <button wire:click="cancelar"
                    class="px-4 py-2 text-xs font-black uppercase tracking-wider text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button wire:click="guardar" wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-5 py-2 bg-pink-600 hover:bg-pink-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors disabled:opacity-60">
                    <span wire:loading.remove wire:target="guardar"><i class="ri-save-line"></i> Guardar</span>
                    <span wire:loading wire:target="guardar"><i class="ri-loader-4-line animate-spin"></i> Guardando...</span>
                </button>
            </div>
        </div>
    @endif

    {{-- Tabla --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-3">
            <div class="flex-1 relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre o clave..."
                    class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-300">
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Clave</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Nombre</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Tipo Red</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Localidad</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Municipio</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">C.P.</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($sucursales as $s)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded font-black text-[9px] tracking-widest">{{ $s->clave }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-800 font-semibold">{{ $s->nombre }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $tipoColor = match($s->tipo_red) {
                                        'FIBRA' => 'bg-blue-50 text-blue-700',
                                        'HIBRIDA' => 'bg-violet-50 text-violet-700',
                                        default => 'bg-amber-50 text-amber-700',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 {{ $tipoColor }} rounded text-[9px] font-black uppercase tracking-wider">{{ $s->tipo_red }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $s->localidad?->nombre_localidad ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $s->municipio?->nombre_municipio ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $s->estado?->nombre_estado ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-500 font-mono">{{ $s->codigo_postal ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @if($s->activa)
                                    <span class="flex items-center gap-1 text-emerald-600 text-[9px] font-black uppercase">
                                        <i class="ri-checkbox-circle-line"></i> Activa
                                    </span>
                                @else
                                    <span class="flex items-center gap-1 text-gray-400 text-[9px] font-black uppercase">
                                        <i class="ri-close-circle-line"></i> Inactiva
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editar({{ $s->id }})"
                                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                        <i class="ri-edit-line text-sm"></i>
                                    </button>
                                    @if($s->activa)
                                        <button wire:click="eliminar({{ $s->id }})"
                                            wire:confirm="¿Desactivar esta sucursal?"
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Desactivar">
                                            <i class="ri-toggle-fill text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-5 py-12 text-center">
                                <i class="ri-building-3-line text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin sucursales registradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sucursales->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $sucursales->links() }}
            </div>
        @endif
    </div>
</div>
