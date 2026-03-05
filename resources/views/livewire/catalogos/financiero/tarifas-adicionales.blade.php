<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Financiero</span>
        <span>/</span>
        <span class="text-gray-600">Tarifas Adicionales</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Tarifas Adicionales</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Financiero · Catálogo</p>
        </div>
        @if($modo === 'lista')
            <button wire:click="nueva"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                <i class="ri-add-line"></i> Nueva Tarifa Adicional
            </button>
        @endif
    </div>

    {{-- KPIs --}}
    @if($modo === 'lista')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-add-circle-line text-emerald-600 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500">Total</p>
                <p class="text-lg font-black text-emerald-800">{{ $kpis['total'] }}</p>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-checkbox-circle-line text-blue-600 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-blue-500">Vigentes</p>
                <p class="text-lg font-black text-blue-800">{{ $kpis['vigentes'] }}</p>
            </div>
        </div>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-time-line text-amber-600 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-amber-500">Vencidas</p>
                <p class="text-lg font-black text-amber-800">{{ $kpis['vencidas'] }}</p>
            </div>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-red-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-close-circle-line text-red-500 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-red-400">Canceladas</p>
                <p class="text-lg font-black text-red-700">{{ $kpis['canceladas'] }}</p>
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
                    <i class="ri-add-circle-line text-emerald-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-gray-800">
                        {{ $modo === 'crear' ? 'Nueva Tarifa Adicional' : 'Editar Tarifa Adicional' }}
                    </p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Financiero</p>
                </div>
            </div>
            <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        {{-- Tarifa principal + nombre + estado --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Tarifa Principal Asociada <span class="text-red-500">*</span></label>
                <select wire:model="tarifaPrincipalId"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('tarifaPrincipalId') border-red-300 @enderror">
                    <option value="">— Seleccionar —</option>
                    @foreach($principalesSelect as $tp)
                        <option value="{{ $tp->id }}">{{ $tp->nombre_comercial }}</option>
                    @endforeach
                </select>
                @error('tarifaPrincipalId') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Nombre Comercial <span class="text-red-500">*</span></label>
                <input wire:model="nombreComercial" type="text" placeholder="Ej. Punto adicional TV"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('nombreComercial') border-red-300 @enderror">
                @error('nombreComercial') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Estado</label>
                <select wire:model="estado"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <option value="VIGENTE">Vigente</option>
                    <option value="VENCIDA">Vencida</option>
                    <option value="CANCELADA">Cancelada</option>
                </select>
            </div>
        </div>

        {{-- Descripción --}}
        <div class="mb-4">
            <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Descripción</label>
            <textarea wire:model="descripcion" rows="2" placeholder="Descripción de la tarifa adicional..."
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 resize-none"></textarea>
        </div>

        {{-- Precios --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Precio Instalación <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold">$</span>
                    <input wire:model="precioInstalacion" type="number" step="0.01" min="0" placeholder="0.00"
                        class="w-full border border-gray-200 rounded-xl pl-7 pr-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('precioInstalacion') border-red-300 @enderror">
                </div>
                @error('precioInstalacion') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Precio Mensualidad <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold">$</span>
                    <input wire:model="precioMensualidad" type="number" step="0.01" min="0" placeholder="0.00"
                        class="w-full border border-gray-200 rounded-xl pl-7 pr-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('precioMensualidad') border-red-300 @enderror">
                </div>
                @error('precioMensualidad') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
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

        {{-- CRT --}}
        <div class="border-t border-dashed border-gray-100 pt-4 mb-4">
            <p class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-3 flex items-center gap-2">
                <i class="ri-government-line"></i> Registro C.R.T. <span class="font-normal normal-case text-gray-300 tracking-normal">(opcional)</span>
            </p>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Fecha Registro CRT</label>
                    <input wire:model="fechaRegistroCrt" type="date"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Folio CRT</label>
                    <input wire:model="folioCrt" type="text" placeholder="Ej. CRT-2024-00123"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300">
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

    {{-- Tabla --}}
    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm">
        <div class="px-5 py-3 border-b border-gray-100 flex flex-wrap items-center gap-3">
            <div class="flex-1 min-w-48 relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar tarifa adicional..."
                    class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
            <select wire:model.live="filtroPrincipal"
                class="border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value="">Todas las principales</option>
                @foreach($principalesFiltro as $tp)
                    <option value="{{ $tp->id }}">{{ $tp->nombre_comercial }}</option>
                @endforeach
            </select>
            <select wire:model.live="filtroEstado"
                class="border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value="">Todos los estados</option>
                <option value="VIGENTE">Vigente</option>
                <option value="VENCIDA">Vencida</option>
                <option value="CANCELADA">Cancelada</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">ID</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Nombre</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Tarifa Principal</th>
                        <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Instalación</th>
                        <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Mensualidad</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Vigencia</th>
                        <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($tarifas as $tarifa)
                        @php
                            $estadoColor = match($tarifa->estado) {
                                'VIGENTE'  => 'bg-emerald-50 text-emerald-700',
                                'VENCIDA'  => 'bg-amber-50 text-amber-700',
                                'CANCELADA'=> 'bg-red-50 text-red-600',
                                default    => 'bg-gray-100 text-gray-500',
                            };
                        @endphp
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-gray-400 text-[9px]">TA-{{ str_pad($tarifa->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $tarifa->nombre_comercial }}</td>
                            <td class="px-4 py-3 text-gray-500 text-[10px]">{{ $tarifa->tarifaPrincipal?->nombre_comercial ?? '—' }}</td>
                            <td class="px-4 py-3 text-right font-mono text-gray-700">${{ number_format($tarifa->precio_instalacion, 2) }}</td>
                            <td class="px-4 py-3 text-right font-mono font-black text-emerald-700">${{ number_format($tarifa->precio_mensualidad, 2) }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-0.5 {{ $estadoColor }} rounded text-[8px] font-black uppercase">{{ $tarifa->estado }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-500 font-mono text-[10px]">
                                {{ $tarifa->fecha_habilitacion->format('d/m/Y') }}
                                @if($tarifa->fecha_termino)
                                    — {{ $tarifa->fecha_termino->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-300">Indef.</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    <button wire:click="editar({{ $tarifa->id }})"
                                        class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                        <i class="ri-edit-line text-sm"></i>
                                    </button>
                                    <button @click="$confirm('¿Eliminar esta tarifa adicional?', () => $wire.eliminar({{ $tarifa->id }}), { icon: 'warning', confirmText: 'Sí, eliminar' })"
                                        class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="ri-delete-bin-line text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <i class="ri-add-circle-line text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin tarifas adicionales registradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tarifas->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">{{ $tarifas->links() }}</div>
        @endif
    </div>
</div>
