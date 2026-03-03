<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Sedes e Infraestructura</span>
        <span>/</span>
        <span class="text-gray-600">Inventario de Postes</span>
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
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Inventario de Postes</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Sedes e Infraestructura · Catálogo</p>
        </div>
        @if($modo === 'lista')
            <button wire:click="nuevoPoste"
                class="flex items-center gap-2 px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                <i class="ri-add-line"></i>
                Nuevo Poste
            </button>
        @endif
    </div>

    {{-- KPI cards --}}
    @if($modo === 'lista')
        <div class="grid grid-cols-4 gap-4 mb-6">
            <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-11 h-11 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-base-station-line text-gray-500 text-xl"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-gray-400">Total Postes</p>
                    <p class="text-2xl font-black text-gray-700">{{ $totalPostes }}</p>
                </div>
            </div>
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-11 h-11 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-flashlight-line text-amber-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-amber-400">CFE</p>
                    <p class="text-2xl font-black text-amber-700">{{ $totalCfe }}</p>
                </div>
            </div>
            <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-phone-line text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-blue-400">Telmex</p>
                    <p class="text-2xl font-black text-blue-700">{{ $totalTelmex }}</p>
                </div>
            </div>
            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center gap-4">
                <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-signal-tower-line text-emerald-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-emerald-400">Propio TVT</p>
                    <p class="text-2xl font-black text-emerald-700">{{ $totalPropio }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Formulario --}}
    @if($modo !== 'lista')
        <div class="bg-white border border-pink-200 rounded-2xl shadow-sm mb-6 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-pink-100 rounded-xl flex items-center justify-center">
                        <i class="ri-base-station-line text-pink-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-800">
                            {{ $modo === 'crear' ? 'Nuevo Poste' : 'Editar Poste' }}
                        </p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">
                            {{ $modo === 'crear' ? 'Se generará ID automáticamente' : 'Editando registro existente' }}
                        </p>
                    </div>
                </div>
                <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-3 gap-4">
                {{-- Sucursal --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Sucursal <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model.live="sucursalId"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('sucursalId') border-red-300 @enderror">
                            <option value="">— Seleccionar —</option>
                            @foreach($sucursales as $s)
                                <option value="{{ $s->id }}">{{ $s->clave }} — {{ $s->nombre }}</option>
                            @endforeach
                        </select>
                        <span wire:loading wire:target="updatedSucursalId" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <i class="ri-loader-4-line animate-spin text-gray-400 text-xs"></i>
                        </span>
                    </div>
                    @error('sucursalId') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Número de Poste --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Número de Poste <span class="text-red-500">*</span></label>
                    <input wire:model="numeroPoste" type="text" maxlength="30" placeholder="Ej. 042"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('numeroPoste') border-red-300 @enderror">
                    @error('numeroPoste') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Tipo --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Tipo de Poste <span class="text-red-500">*</span></label>
                    <select wire:model="tipoPoste"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('tipoPoste') border-red-300 @enderror">
                        <option value="">— Seleccionar —</option>
                        <option value="CFE">CFE</option>
                        <option value="TELMEX">Telmex</option>
                        <option value="PROPIO_TVT">Propio TVT</option>
                    </select>
                    @error('tipoPoste') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Calle principal --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Calle Principal</label>
                    <select wire:model="calleId"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 disabled:bg-gray-50 disabled:text-gray-400"
                        @disabled(!$sucursalId)>
                        <option value="">— Seleccionar —</option>
                        @foreach($callesDisponibles as $cl)
                            <option value="{{ $cl['id'] }}">{{ $cl['nombre_calle'] }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Entre calle 1 --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Entre Calle 1</label>
                    <select wire:model="entreCalle1Id"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 disabled:bg-gray-50 disabled:text-gray-400"
                        @disabled(!$sucursalId)>
                        <option value="">— Seleccionar —</option>
                        @foreach($callesDisponibles as $cl)
                            <option value="{{ $cl['id'] }}">{{ $cl['nombre_calle'] }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Entre calle 2 --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Entre Calle 2</label>
                    <select wire:model="entreCalle2Id"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 disabled:bg-gray-50 disabled:text-gray-400"
                        @disabled(!$sucursalId)>
                        <option value="">— Seleccionar —</option>
                        @foreach($callesDisponibles as $cl)
                            <option value="{{ $cl['id'] }}">{{ $cl['nombre_calle'] }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Zona --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Zona / Colonia</label>
                    <input wire:model="zona" type="text" maxlength="30" placeholder="Ej. Centro, Norte..."
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('zona') border-red-300 @enderror">
                    @error('zona') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Coordenadas UTM --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Latitud UTM</label>
                    <input wire:model="latitudUtm" type="number" step="any" placeholder="0.00000000"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('latitudUtm') border-red-300 @enderror">
                    @error('latitudUtm') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Longitud UTM</label>
                    <input wire:model="longitudUtm" type="number" step="any" placeholder="0.00000000"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('longitudUtm') border-red-300 @enderror">
                    @error('longitudUtm') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Coordenadas en grados --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Latitud (grados)</label>
                    <input wire:model="latitudGrados" type="number" step="any" min="-90" max="90" placeholder="20.00000000"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('latitudGrados') border-red-300 @enderror">
                    @error('latitudGrados') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Longitud (grados)</label>
                    <input wire:model="longitudGrados" type="number" step="any" min="-180" max="180" placeholder="-97.00000000"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('longitudGrados') border-red-300 @enderror">
                    @error('longitudGrados') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
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
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-3 flex-wrap">
            <div class="flex-1 min-w-[180px] relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por ID, número, zona..."
                    class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-300">
            </div>
            <select wire:model.live="filtroSucursalId"
                class="border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-pink-300 min-w-[160px]">
                <option value="">Todas las sucursales</option>
                @foreach($sucursales as $s)
                    <option value="{{ $s->id }}">{{ $s->clave }} — {{ $s->nombre }}</option>
                @endforeach
            </select>
            <select wire:model.live="filtroTipo"
                class="border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-700 focus:outline-none focus:ring-2 focus:ring-pink-300">
                <option value="">Todos los tipos</option>
                <option value="CFE">CFE</option>
                <option value="TELMEX">Telmex</option>
                <option value="PROPIO_TVT">Propio TVT</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">ID Poste</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Sucursal</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Número</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Tipo</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Calle</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Zona</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Coords.</th>
                        <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($postes as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="font-mono text-[9px] font-black tracking-widest text-gray-700 bg-gray-100 px-2 py-0.5 rounded">{{ $p->id_poste }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="text-[9px] font-black text-gray-500">{{ $p->sucursal?->clave }}</span>
                                <span class="ml-1 text-gray-600">{{ $p->sucursal?->nombre }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-700 font-semibold">{{ $p->numero_poste }}</td>
                            <td class="px-5 py-3">
                                @php
                                    $tc = match($p->tipo_poste) {
                                        'CFE' => 'bg-amber-50 text-amber-700',
                                        'TELMEX' => 'bg-blue-50 text-blue-700',
                                        default => 'bg-emerald-50 text-emerald-700',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 {{ $tc }} rounded text-[9px] font-black uppercase tracking-wider">{{ $p->tipo_poste }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $p->calle?->nombre_calle ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-500">{{ $p->zona ?? '—' }}</td>
                            <td class="px-5 py-3">
                                @if($p->latitud_grados && $p->longitud_grados)
                                    <span class="font-mono text-[9px] text-gray-500">
                                        {{ number_format($p->latitud_grados, 4) }}, {{ number_format($p->longitud_grados, 4) }}
                                    </span>
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <button wire:click="editar({{ $p->id }})"
                                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                        <i class="ri-edit-line text-sm"></i>
                                    </button>
                                    <button wire:click="eliminar({{ $p->id }})"
                                        wire:confirm="¿Desactivar este poste?"
                                        class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Desactivar">
                                        <i class="ri-delete-bin-line text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <i class="ri-base-station-line text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin postes registrados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($postes->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $postes->links() }}
            </div>
        @endif
    </div>
</div>
