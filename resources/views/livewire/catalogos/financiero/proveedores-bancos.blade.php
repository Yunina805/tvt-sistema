<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Financiero</span>
        <span>/</span>
        <span class="text-gray-600">Proveedores / Bancos</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Proveedores / Bancos / Traspasos</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Financiero · Catálogo</p>
        </div>
        @if($modo === 'lista')
            <button wire:click="nuevo"
                class="flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                <i class="ri-add-line"></i> Nuevo Proveedor
            </button>
        @endif
    </div>

    {{-- KPIs --}}
    @if($modo === 'lista')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-bank-line text-emerald-600 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500">Total</p>
                <p class="text-lg font-black text-emerald-800">{{ $kpis['total'] }}</p>
            </div>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-user-follow-line text-blue-600 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-blue-500">Activos</p>
                <p class="text-lg font-black text-blue-800">{{ $kpis['activos'] }}</p>
            </div>
        </div>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-money-dollar-circle-line text-amber-600 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-amber-500">Al Contado</p>
                <p class="text-lg font-black text-amber-800">{{ $kpis['contado'] }}</p>
            </div>
        </div>
        <div class="bg-violet-50 border border-violet-100 rounded-2xl p-4 flex items-center gap-3">
            <div class="w-9 h-9 bg-violet-100 rounded-xl flex items-center justify-center shrink-0">
                <i class="ri-bank-card-line text-violet-600 text-base"></i>
            </div>
            <div>
                <p class="text-[9px] font-black uppercase tracking-widest text-violet-500">A Crédito</p>
                <p class="text-lg font-black text-violet-800">{{ $kpis['credito'] }}</p>
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
                    <i class="ri-bank-line text-emerald-600 text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-black uppercase tracking-widest text-gray-800">
                        {{ $modo === 'crear' ? 'Nuevo Proveedor / Banco' : 'Editar Proveedor / Banco' }}
                    </p>
                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Financiero</p>
                </div>
            </div>
            <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class="ri-close-line text-xl"></i>
            </button>
        </div>

        {{-- Nombre y tipo servicio --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Nombre del Proveedor / Banco <span class="text-red-500">*</span></label>
                <input wire:model="nombre" type="text" placeholder="Ej. CFE, Telmex, Banco BBVA..."
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('nombre') border-red-300 @enderror">
                @error('nombre') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">RFC / Cuenta de Depósito / Motivo</label>
                <input wire:model="rfcCuentaMotivo" type="text" placeholder="RFC, número de cuenta o motivo de traspaso"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
        </div>

        {{-- Contacto --}}
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Correo de Contacto</label>
                <input wire:model="correo" type="email" placeholder="contacto@proveedor.com"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('correo') border-red-300 @enderror">
                @error('correo') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Teléfono</label>
                <input wire:model="telefono" type="text" placeholder="(228) 000-0000"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
        </div>

        {{-- Tipo servicio y pago --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Tipo de Servicio <span class="text-red-500">*</span></label>
                <select wire:model="tipoServicio"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 @error('tipoServicio') border-red-300 @enderror">
                    <option value="">— Seleccionar —</option>
                    @foreach($tiposServicio as $tipo)
                        <option value="{{ $tipo }}">{{ $tipo }}</option>
                    @endforeach
                </select>
                @error('tipoServicio') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Tipo de Pago <span class="text-red-500">*</span></label>
                <select wire:model="tipoPago"
                    class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <option value="CONTADO">Contado</option>
                    <option value="CREDITO">Crédito</option>
                </select>
            </div>
            <div class="flex items-end pb-0.5">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input wire:model="activo" type="checkbox"
                        class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-300">
                    <span class="text-xs text-gray-600 font-medium">Proveedor activo</span>
                </label>
            </div>
        </div>

        {{-- Especificación --}}
        <div class="mb-4">
            <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Especificación <span class="text-gray-300 font-normal normal-case">(opcional)</span></label>
            <textarea wire:model="especificacion" rows="2" placeholder="Detalles adicionales sobre el proveedor, condiciones de pago, notas..."
                class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-emerald-300 resize-none"></textarea>
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
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar proveedor..."
                    class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-emerald-300">
            </div>
            <select wire:model.live="filtroTipo"
                class="border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value="">Todos los tipos</option>
                @foreach($tiposExistentes as $tipo)
                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                @endforeach
            </select>
            <select wire:model.live="filtroPago"
                class="border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value="">Contado y Crédito</option>
                <option value="CONTADO">Contado</option>
                <option value="CREDITO">Crédito</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">ID</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Nombre</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">RFC / Cuenta</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Contacto</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Tipo Servicio</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Pago</th>
                        <th class="px-4 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-4 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($proveedores as $prov)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 font-mono text-gray-400 text-[9px]">{{ str_pad($prov->id, 4, '0', STR_PAD_LEFT) }}</td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-800">{{ $prov->nombre }}</p>
                                @if($prov->especificacion)
                                    <p class="text-[9px] text-gray-400 mt-0.5 line-clamp-1">{{ $prov->especificacion }}</p>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-gray-500 font-mono text-[10px]">{{ $prov->rfc_cuenta_motivo ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-500 text-[10px]">
                                @if($prov->correo)
                                    <p>{{ $prov->correo }}</p>
                                @endif
                                @if($prov->telefono)
                                    <p class="text-gray-400">{{ $prov->telefono }}</p>
                                @endif
                                @if(!$prov->correo && !$prov->telefono)
                                    <span class="text-gray-300">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded text-[8px] font-bold uppercase">{{ $prov->tipo_servicio }}</span>
                            </td>
                            <td class="px-4 py-3">
                                @if($prov->tipo_pago === 'CONTADO')
                                    <span class="px-1.5 py-0.5 bg-amber-50 text-amber-700 rounded text-[8px] font-black uppercase">Contado</span>
                                @else
                                    <span class="px-1.5 py-0.5 bg-violet-50 text-violet-700 rounded text-[8px] font-black uppercase">Crédito</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if($prov->activo)
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
                                    <button wire:click="editar({{ $prov->id }})"
                                        class="p-1.5 text-gray-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors">
                                        <i class="ri-edit-line text-sm"></i>
                                    </button>
                                    <button @click="$confirm('{{ $prov->activo ? '¿Desactivar este proveedor?' : '¿Activar este proveedor?' }}', () => $wire.toggleActivo({{ $prov->id }}))"
                                        class="p-1.5 text-gray-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <i class="ri-toggle-{{ $prov->activo ? 'fill' : 'line' }} text-sm"></i>
                                    </button>
                                    <button @click="$confirm('¿Eliminar este proveedor?', () => $wire.eliminar({{ $prov->id }}), { icon: 'warning', confirmText: 'Sí, eliminar' })"
                                        class="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                        <i class="ri-delete-bin-line text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <i class="ri-bank-line text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin proveedores registrados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($proveedores->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">{{ $proveedores->links() }}</div>
        @endif
    </div>
</div>
