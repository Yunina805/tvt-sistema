<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Recursos Humanos</span>
        <span>/</span>
        <span class="text-gray-600">Vacaciones</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Vacaciones</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Recursos Humanos · Catálogo</p>
        </div>
        @if($modo === 'lista')
            <button wire:click="nuevaSolicitud"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                <i class="ri-add-line"></i>
                Nueva Solicitud
            </button>
        @endif
    </div>

    {{-- KPIs --}}
    @if($modo === 'lista')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-calendar-2-line text-emerald-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500">Total Solicitudes</p>
                    <p class="text-lg font-black text-emerald-800">{{ $totalSolicitudes }}</p>
                </div>
            </div>
        </div>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-time-line text-amber-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-amber-500">Pendientes</p>
                    <p class="text-lg font-black text-amber-800">{{ $pendientes }}</p>
                </div>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-checkbox-circle-line text-blue-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-blue-500">Aprobadas</p>
                    <p class="text-lg font-black text-blue-800">{{ $aprobadas }}</p>
                </div>
            </div>
        </div>
        <div class="bg-violet-50 border border-violet-100 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-violet-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-flight-takeoff-line text-violet-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-violet-500">Tomadas</p>
                    <p class="text-lg font-black text-violet-800">{{ $tomadas }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Formulario Crear / Editar --}}
    @if($modo !== 'lista')
        <div class="bg-white border border-emerald-200 rounded-2xl shadow-sm mb-6 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="ri-calendar-2-line text-emerald-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-800">
                            {{ $modo === 'crear' ? 'Nueva Solicitud de Vacaciones' : 'Editar Solicitud' }}
                        </p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Recursos Humanos</p>
                    </div>
                </div>
                <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Empleado --}}
                <div class="col-span-2 lg:col-span-1">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Empleado <span class="text-red-500">*</span></label>
                    <select wire:model="empleadoId"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('empleadoId') border-red-300 @enderror">
                        <option value="">— Seleccionar —</option>
                        @foreach($empleados as $e)
                            <option value="{{ $e->id }}">{{ $e->apellido_paterno }} {{ $e->apellido_materno }} {{ $e->nombre }}</option>
                        @endforeach
                    </select>
                    @error('empleadoId') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Año --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Año <span class="text-red-500">*</span></label>
                    <input wire:model="anio" type="number" min="2000" max="2099" placeholder="{{ date('Y') }}"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('anio') border-red-300 @enderror">
                    @error('anio') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Días Asignados --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Días Asignados <span class="text-red-500">*</span></label>
                    <input wire:model="diasAsignados" type="number" min="1" max="365"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('diasAsignados') border-red-300 @enderror">
                    @error('diasAsignados') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Fecha Inicio --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Fecha Inicio</label>
                    <input wire:model="fechaInicio" type="date"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('fechaInicio') border-red-300 @enderror">
                    @error('fechaInicio') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Fecha Fin --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Fecha Fin</label>
                    <input wire:model="fechaFin" type="date"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('fechaFin') border-red-300 @enderror">
                    @error('fechaFin') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Observaciones --}}
                <div class="col-span-2 lg:col-span-3">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Observaciones</label>
                    <textarea wire:model="observaciones" rows="2" placeholder="Notas adicionales..."
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 resize-none"></textarea>
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
        <div class="px-5 py-3 border-b border-gray-100 flex items-center gap-3 flex-wrap">
            <div class="flex-1 min-w-[180px] relative">
                <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar empleado..."
                    class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
            <select wire:model.live="filtroEstado"
                class="text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-300 text-gray-700">
                <option value="">Todos los estados</option>
                <option value="PENDIENTE">Pendiente</option>
                <option value="APROBADO">Aprobado</option>
                <option value="RECHAZADO">Rechazado</option>
                <option value="TOMADO">Tomado</option>
            </select>
            <select wire:model.live="filtroEmpleadoId"
                class="text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-emerald-300 text-gray-700">
                <option value="">Todos los empleados</option>
                @foreach($empleados as $e)
                    <option value="{{ $e->id }}">{{ $e->apellido_paterno }} {{ $e->nombre }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Empleado</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Año</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Días</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Periodo</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($vacaciones as $vac)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 text-gray-800 font-semibold">{{ $vac->empleado?->nombre_completo ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-600 font-mono">{{ $vac->anio }}</td>
                            <td class="px-5 py-3">
                                <span class="text-gray-700 font-bold">{{ $vac->dias_tomados }}</span>
                                <span class="text-gray-400">/</span>
                                <span class="text-gray-700 font-bold">{{ $vac->dias_asignados }}</span>
                                <span class="text-[9px] text-gray-400 ml-1">días</span>
                            </td>
                            <td class="px-5 py-3 text-gray-500">
                                @if($vac->fecha_inicio && $vac->fecha_fin)
                                    {{ $vac->fecha_inicio->format('d/m/Y') }} — {{ $vac->fecha_fin->format('d/m/Y') }}
                                @else
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $estadoColor = match($vac->estado) {
                                        'PENDIENTE' => 'bg-amber-50 text-amber-700',
                                        'APROBADO'  => 'bg-emerald-50 text-emerald-700',
                                        'RECHAZADO' => 'bg-red-50 text-red-700',
                                        'TOMADO'    => 'bg-blue-50 text-blue-700',
                                        default     => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 {{ $estadoColor }} rounded text-[9px] font-black uppercase">{{ $vac->estado }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1">
                                    @if($vac->estado === 'PENDIENTE')
                                        <button @click="$confirm('¿Aprobar esta solicitud?', () => $wire.aprobar({{ $vac->id }}))"
                                            class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" title="Aprobar">
                                            <i class="ri-check-line text-sm"></i>
                                        </button>
                                        <button @click="$confirm('¿Rechazar esta solicitud?', () => $wire.rechazar({{ $vac->id }}))"
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Rechazar">
                                            <i class="ri-close-line text-sm"></i>
                                        </button>
                                    @endif
                                    @if($vac->estado === 'APROBADO')
                                        <button @click="$confirm('¿Marcar vacaciones como tomadas?', () => $wire.marcarTomado({{ $vac->id }}))"
                                            class="p-1.5 text-gray-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors" title="Marcar tomado">
                                            <i class="ri-flight-takeoff-line text-sm"></i>
                                        </button>
                                    @endif
                                    <button wire:click="editar({{ $vac->id }})"
                                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                        <i class="ri-edit-line text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-12 text-center">
                                <i class="ri-calendar-2-line text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin solicitudes de vacaciones</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($vacaciones->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $vacaciones->links() }}
            </div>
        @endif
    </div>
</div>
