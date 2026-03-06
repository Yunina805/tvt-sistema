<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Sedes e Infraestructura</span>
        <span>/</span>
        <span class="text-gray-600">Geografía INEGI</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Geografía INEGI</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Sedes e Infraestructura · Catálogo</p>
        </div>
        @if($modo === 'lista')
            <div class="flex items-center gap-2">
                <button wire:click="iniciarImportar"
                    class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                    <i class="ri-file-excel-2-line"></i>
                    Importar Excel
                </button>
                <button wire:click="iniciarAgregar"
                    class="flex items-center gap-2 px-4 py-2 bg-pink-600 hover:bg-pink-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                    <i class="ri-add-line"></i>
                    Agregar Localidad
                </button>
            </div>
        @endif
    </div>

    {{-- KPI cards --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center gap-4">
            <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-map-2-line text-blue-600 text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-blue-400">Estados</p>
                <p class="text-2xl font-black text-blue-700">{{ $totalEstados }}</p>
            </div>
        </div>
        <div class="bg-violet-50 border border-violet-100 rounded-2xl p-4 flex items-center gap-4">
            <div class="w-11 h-11 bg-violet-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-map-pin-2-line text-violet-600 text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-violet-400">Municipios</p>
                <p class="text-2xl font-black text-violet-700">{{ $totalMunicipios }}</p>
            </div>
        </div>
        <div class="bg-pink-50 border border-pink-100 rounded-2xl p-4 flex items-center gap-4">
            <div class="w-11 h-11 bg-pink-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-community-line text-pink-600 text-xl"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-pink-400">Localidades</p>
                <p class="text-2xl font-black text-pink-700">{{ $totalLocalidades }}</p>
            </div>
        </div>
    </div>

    {{-- ═══ PANEL: Importar Excel ═══════════════════════════════════════════ --}}
    @if($modo === 'importar')
        <div class="bg-white border border-indigo-200 rounded-2xl shadow-sm mb-6 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-indigo-100 rounded-xl flex items-center justify-center">
                        <i class="ri-file-excel-2-line text-indigo-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-800">Importar Excel INEGI</p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Carga masiva de localidades desde catálogo oficial</p>
                    </div>
                </div>
                <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            {{-- Formato esperado --}}
            <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-4 py-3 mb-5">
                <p class="text-[9px] font-black uppercase tracking-widest text-indigo-600 mb-2">
                    <i class="ri-information-line mr-1"></i>Columnas esperadas en el Excel
                </p>
                <div class="grid grid-cols-3 gap-2 text-[9px]">
                    <div class="flex items-center gap-1.5">
                        <span class="w-5 h-5 bg-indigo-200 text-indigo-800 rounded flex items-center justify-center font-black">A</span>
                        <span class="text-gray-600 font-semibold">CVE_ENT — Clave estado (2 dígitos)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-5 h-5 bg-indigo-200 text-indigo-800 rounded flex items-center justify-center font-black">B</span>
                        <span class="text-gray-600 font-semibold">NOM_ENT — Nombre del estado</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-5 h-5 bg-indigo-200 text-indigo-800 rounded flex items-center justify-center font-black">C</span>
                        <span class="text-gray-600 font-semibold">CVE_MUN — Clave municipio (3 dígitos)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-5 h-5 bg-indigo-200 text-indigo-800 rounded flex items-center justify-center font-black">D</span>
                        <span class="text-gray-600 font-semibold">NOM_MUN — Nombre del municipio</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-5 h-5 bg-indigo-200 text-indigo-800 rounded flex items-center justify-center font-black">E</span>
                        <span class="text-gray-600 font-semibold">CVE_LOC — Clave localidad (4 dígitos)</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-5 h-5 bg-indigo-200 text-indigo-800 rounded flex items-center justify-center font-black">F</span>
                        <span class="text-gray-600 font-semibold">NOM_LOC — Nombre de localidad</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-5 h-5 bg-gray-200 text-gray-600 rounded flex items-center justify-center font-black">G</span>
                        <span class="text-gray-400 font-semibold">CP — Código postal (opcional)</span>
                    </div>
                </div>
                <p class="text-[9px] text-indigo-500 mt-2 font-bold">
                    Los registros ya existentes (mismo municipio + clave) serán omitidos sin sobrescribir.
                    Formatos admitidos: .xlsx · .xls · .csv · Máx 10 MB
                </p>
            </div>

            {{-- File input --}}
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-2">
                    Seleccionar Archivo <span class="text-red-500">*</span>
                </label>
                <div class="relative border-2 border-dashed border-indigo-200 rounded-xl px-6 py-8 text-center hover:border-indigo-400 transition-colors">
                    <input wire:model="archivoExcel" type="file" accept=".xlsx,.xls,.csv"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div wire:loading.remove wire:target="archivoExcel">
                        @if($archivoExcel)
                            <i class="ri-file-excel-2-line text-3xl text-indigo-500 block mb-2"></i>
                            <p class="text-xs font-bold text-indigo-700">{{ $archivoExcel->getClientOriginalName() }}</p>
                            <p class="text-[9px] text-gray-400 mt-1">
                                {{ number_format($archivoExcel->getSize() / 1024, 1) }} KB · Listo para importar
                            </p>
                        @else
                            <i class="ri-upload-cloud-2-line text-3xl text-gray-300 block mb-2"></i>
                            <p class="text-xs font-bold text-gray-500">Arrastra el archivo aquí o haz clic para seleccionar</p>
                            <p class="text-[9px] text-gray-400 mt-1">xlsx · xls · csv — Máximo 10 MB</p>
                        @endif
                    </div>
                    <div wire:loading wire:target="archivoExcel" class="py-2">
                        <i class="ri-loader-4-line animate-spin text-indigo-400 text-xl block mb-1"></i>
                        <p class="text-[9px] text-indigo-400 font-bold">Cargando archivo...</p>
                    </div>
                </div>
                @error('archivoExcel')
                    <p class="text-[9px] text-red-500 mt-1.5 font-bold">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end gap-3 mt-5">
                <button wire:click="cancelar"
                    class="px-4 py-2 text-xs font-black uppercase tracking-wider text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button wire:click="procesarImport" wire:loading.attr="disabled"
                    @class(['flex items-center gap-2 px-5 py-2 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors disabled:opacity-60',
                        'bg-indigo-600 hover:bg-indigo-700' => $archivoExcel,
                        'bg-gray-300 cursor-not-allowed' => !$archivoExcel])>
                    <span wire:loading.remove wire:target="procesarImport">
                        <i class="ri-upload-2-line"></i> Procesar Importación
                    </span>
                    <span wire:loading wire:target="procesarImport">
                        <i class="ri-loader-4-line animate-spin"></i> Procesando...
                    </span>
                </button>
            </div>
        </div>
    @endif

    {{-- ═══ PANEL: Agregar Localidad Manual ════════════════════════════════ --}}
    @if($modo === 'agregar')
        <div class="bg-white border border-pink-200 rounded-2xl shadow-sm mb-6 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-pink-100 rounded-xl flex items-center justify-center">
                        <i class="ri-add-circle-line text-pink-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-800">Agregar Localidad</p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Registro manual en catálogo INEGI</p>
                    </div>
                </div>
                <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Estado --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Estado <span class="text-red-500">*</span></label>
                    <select wire:model.live="nuevoEstadoId"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('nuevoEstadoId') border-red-300 @enderror">
                        <option value="">— Seleccionar —</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}">{{ $e->nombre_estado }}</option>
                        @endforeach
                    </select>
                    @error('nuevoEstadoId') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Municipio --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Municipio <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <select wire:model.live="nuevoMunicipioId"
                            class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 disabled:bg-gray-50 disabled:text-gray-400 @error('nuevoMunicipioId') border-red-300 @enderror"
                            @disabled(!$nuevoEstadoId)>
                            <option value="">— Seleccionar —</option>
                            @foreach($nuevosMunicipios as $m)
                                <option value="{{ $m['id'] }}">{{ $m['nombre_municipio'] }}</option>
                            @endforeach
                        </select>
                        <span wire:loading wire:target="updatedNuevoEstadoId" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <i class="ri-loader-4-line animate-spin text-gray-400 text-xs"></i>
                        </span>
                    </div>
                    @error('nuevoMunicipioId') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Clave --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Clave INEGI (4 dígitos) <span class="text-red-500">*</span></label>
                    <input wire:model="nuevaClave" type="text" maxlength="4" placeholder="0001"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 font-mono @error('nuevaClave') border-red-300 @enderror">
                    @error('nuevaClave') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- CP --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Código Postal</label>
                    <input wire:model="nuevoCp" type="text" maxlength="5" placeholder="71518"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 font-mono @error('nuevoCp') border-red-300 @enderror">
                    @error('nuevoCp') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Nombre --}}
                <div class="col-span-2">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Nombre de Localidad <span class="text-red-500">*</span></label>
                    <input wire:model="nuevaLocalidad" type="text" maxlength="120" placeholder="Ej. Santiago Amuzgos"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-pink-300 @error('nuevaLocalidad') border-red-300 @enderror">
                    @error('nuevaLocalidad') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-5">
                <button wire:click="cancelar"
                    class="px-4 py-2 text-xs font-black uppercase tracking-wider text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button wire:click="guardarLocalidad" wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-5 py-2 bg-pink-600 hover:bg-pink-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors disabled:opacity-60">
                    <span wire:loading.remove wire:target="guardarLocalidad"><i class="ri-save-line"></i> Guardar</span>
                    <span wire:loading wire:target="guardarLocalidad"><i class="ri-loader-4-line animate-spin"></i> Guardando...</span>
                </button>
            </div>
        </div>
    @endif

    {{-- Panel de Filtros --}}
    @php $hayFiltros = $search || $filtroEstadoId || $filtroMunicipioId; @endphp
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-4" x-data="{ openFilters: true }">
        <button type="button" @click="openFilters = !openFilters"
            class="w-full flex items-center justify-between px-5 py-4 hover:bg-gray-50/60 transition-colors rounded-2xl">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-pink-50 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-equalizer-2-line text-pink-500 text-base"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-black uppercase tracking-widest text-gray-700">Filtros de Búsqueda</p>
                    <p class="text-[9px] font-bold uppercase tracking-widest mt-0.5 {{ $hayFiltros ? 'text-pink-500' : 'text-gray-400' }}">
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
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="Localidad, municipio, estado o C.P...."
                            class="w-full pl-9 pr-3 py-2.5 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-300 transition-all">
                    </div>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Estado</label>
                    <select wire:model.live="filtroEstadoId"
                        class="w-full py-2.5 px-3 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-300 transition-all">
                        <option value="">Todos los estados</option>
                        @foreach($estados as $e)
                            <option value="{{ $e->id }}">{{ $e->nombre_estado }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Municipio</label>
                    <div class="relative">
                        <select wire:model.live="filtroMunicipioId"
                            class="w-full py-2.5 px-3 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-pink-200 focus:border-pink-300 transition-all disabled:bg-gray-50 disabled:text-gray-300"
                            @disabled(!$filtroEstadoId)>
                            <option value="">{{ $filtroEstadoId ? 'Todos los municipios' : 'Selecciona un estado primero' }}</option>
                            @foreach($municipiosFiltrados as $m)
                                <option value="{{ $m['id'] }}">{{ $m['nombre_municipio'] }}</option>
                            @endforeach
                        </select>
                        <span wire:loading wire:target="updatedFiltroEstadoId" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <i class="ri-loader-4-line animate-spin text-pink-400 text-xs"></i>
                        </span>
                    </div>
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
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Cl. Edo.</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Cl. Mpio.</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Municipio</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Cl. Loc.</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Localidad</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">C.P.</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($localidades as $loc)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded font-black text-[9px] tracking-widest font-mono">{{ $loc->estado->clave_estado }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-700 font-medium">{{ $loc->estado->nombre_estado }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 bg-violet-50 text-violet-700 rounded font-black text-[9px] tracking-widest font-mono">{{ $loc->municipio->clave_municipio }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-700 font-medium">{{ $loc->municipio->nombre_municipio }}</td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 bg-pink-50 text-pink-700 rounded font-black text-[9px] tracking-widest font-mono">{{ $loc->clave_localidad }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-800 font-semibold">{{ $loc->nombre_localidad }}</td>
                            <td class="px-5 py-3 text-gray-500 font-mono">{{ $loc->codigo_postal ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center">
                                <i class="ri-map-2-line text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin resultados</p>
                                @if($totalLocalidades === 0)
                                    <p class="text-[10px] text-gray-300 mt-1">Importa el Excel INEGI o agrega localidades manualmente</p>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($localidades->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $localidades->links() }}
            </div>
        @endif
    </div>
</div>
