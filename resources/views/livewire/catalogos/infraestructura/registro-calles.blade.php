<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Sedes e Infraestructura</span>
        <span>/</span>
        <span class="text-gray-600">Registro de Calles</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Registro de Calles</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Sedes e Infraestructura · Catálogo</p>
        </div>
        @if($modo === 'lista')
            <button wire:click="nuevaCalle"
                class="flex items-center gap-2 px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                <i class="ri-add-line"></i>
                Nueva Calle
            </button>
        @endif
    </div>

    {{-- Formulario --}}
    @if($modo !== 'lista')
        <div class="bg-white border border-pink-200 rounded-2xl shadow-sm mb-6 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-pink-100 rounded-xl flex items-center justify-center">
                        <i class="ri-road-map-line text-pink-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-800">
                            {{ $modo === 'crear' ? 'Nueva Calle' : 'Editar Calle' }}
                        </p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Sedes e Infraestructura</p>
                    </div>
                </div>
                <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Nombre Calle --}}
                <div class="col-span-2">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Nombre de Calle <span class="text-red-500">*</span></label>
                    <input wire:model="nombreCalle" type="text" maxlength="120" placeholder="Ej. Av. Hidalgo, Calle 5 de Mayo..."
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('nombreCalle') border-red-300 @enderror">
                    @error('nombreCalle') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Sucursal --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Sucursal <span class="text-red-500">*</span></label>
                    <select wire:model.live="sucursalId"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('sucursalId') border-red-300 @enderror">
                        <option value="">— Seleccionar —</option>
                        @foreach($sucursales as $s)
                            <option value="{{ $s->id }}">{{ $s->clave }} — {{ $s->nombre }}</option>
                        @endforeach
                    </select>
                    @error('sucursalId') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Info auto de sucursal --}}
                @if($sucursalInfo)
                    <div class="bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 grid grid-cols-2 gap-x-4 gap-y-1.5">
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Localidad</p>
                            <p class="text-xs text-gray-700 font-semibold">{{ $sucursalInfo['localidad'] ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Municipio</p>
                            <p class="text-xs text-gray-700 font-semibold">{{ $sucursalInfo['municipio'] ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</p>
                            <p class="text-xs text-gray-700 font-semibold">{{ $sucursalInfo['estado'] ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">C.P.</p>
                            <p class="text-xs text-gray-700 font-mono">{{ $sucursalInfo['cp'] ?? '—' }}</p>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 border border-dashed border-gray-200 rounded-xl flex items-center justify-center">
                        <p class="text-[9px] font-bold text-gray-300 uppercase tracking-wider">Selecciona una sucursal para ver detalles</p>
                    </div>
                @endif
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
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-3 flex-wrap">
            <div class="flex-1 min-w-[200px] relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar calle..."
                    class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-300">
            </div>
            <select wire:model.live="filtroSucursalId"
                class="border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-pink-300 min-w-[180px]">
                <option value="">Todas las sucursales</option>
                @foreach($sucursales as $s)
                    <option value="{{ $s->id }}">{{ $s->clave }} — {{ $s->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Nombre de Calle</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Sucursal</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Localidad</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Municipio</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($calles as $c)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-800 font-semibold">{{ $c->nombre_calle }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 bg-gray-100 text-gray-700 rounded text-[9px] font-black tracking-widest">{{ $c->sucursal?->clave }}</span>
                                <span class="ml-1 text-gray-600">{{ $c->sucursal?->nombre }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $c->sucursal?->localidad?->nombre_localidad ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $c->sucursal?->municipio?->nombre_municipio ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $c->sucursal?->estado?->nombre_estado ?? '—' }}</td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editar({{ $c->id }})"
                                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                        <i class="ri-edit-line text-sm"></i>
                                    </button>
                                    <button @click="$confirm('¿Desactivar esta calle?', () => $wire.eliminar({{ $c->id }}))"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Desactivar">
                                        <i class="ri-delete-bin-line text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <i class="ri-road-map-line text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin calles registradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($calles->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $calles->links() }}
            </div>
        @endif
    </div>
</div>
