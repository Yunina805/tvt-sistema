<div>
    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6">
        <i class="ri-home-3-line text-gray-300"></i>
        <span>/</span>
        <span>Recursos Humanos</span>
        <span>/</span>
        <span class="text-gray-600">Registro de Empleados</span>
    </nav>

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Registro de Empleados</h1>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Recursos Humanos · Catálogo</p>
        </div>
        @if($modo === 'lista')
            <button wire:click="nuevoEmpleado"
                class="flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors">
                <i class="ri-add-line"></i>
                Nuevo Empleado
            </button>
        @endif
    </div>

    {{-- KPIs --}}
    @if($modo === 'lista')
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-team-line text-blue-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-blue-500">Total Empleados</p>
                    <p class="text-lg font-black text-blue-800">{{ $totalEmpleados }}</p>
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
        <div class="bg-violet-50 border border-violet-100 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-violet-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-tools-line text-violet-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-violet-500">Técnicos Campo</p>
                    <p class="text-lg font-black text-violet-800">{{ $porArea['TECNICO_CAMPO'] ?? 0 }}</p>
                </div>
            </div>
        </div>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                    <i class="ri-customer-service-2-line text-amber-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[9px] font-black uppercase tracking-widest text-amber-500">Atención Cliente</p>
                    <p class="text-lg font-black text-amber-800">{{ $porArea['ATENCION_CLIENTE'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Formulario Crear / Editar --}}
    @if($modo !== 'lista')
        <div class="bg-white border border-blue-200 rounded-2xl shadow-sm mb-6 p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="ri-user-add-line text-blue-600 text-lg"></i>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase tracking-widest text-gray-800">
                            {{ $modo === 'crear' ? 'Nuevo Empleado' : 'Editar Empleado' }}
                        </p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Recursos Humanos</p>
                    </div>
                </div>
                <button wire:click="cancelar" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Nombre --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Nombre <span class="text-red-500">*</span></label>
                    <input wire:model="nombre" type="text" maxlength="80" placeholder="Nombre(s)"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('nombre') border-red-300 @enderror">
                    @error('nombre') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Apellido Paterno --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Apellido Paterno <span class="text-red-500">*</span></label>
                    <input wire:model="apellidoPaterno" type="text" maxlength="80" placeholder="Apellido paterno"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('apellidoPaterno') border-red-300 @enderror">
                    @error('apellidoPaterno') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Apellido Materno --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Apellido Materno</label>
                    <input wire:model="apellidoMaterno" type="text" maxlength="80" placeholder="Apellido materno"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                {{-- CURP --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">CURP</label>
                    <input wire:model="curp" type="text" maxlength="18" placeholder="18 caracteres"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 uppercase @error('curp') border-red-300 @enderror">
                    @error('curp') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- RFC --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">RFC</label>
                    <input wire:model="rfc" type="text" maxlength="13" placeholder="RFC"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 uppercase">
                </div>
                {{-- NSS --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">NSS</label>
                    <input wire:model="nss" type="text" maxlength="11" placeholder="Núm. Seguridad Social"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                {{-- Fecha Nacimiento --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Fecha Nacimiento</label>
                    <input wire:model="fechaNacimiento" type="date"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                {{-- Sexo --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Sexo</label>
                    <select wire:model="sexo"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">— Seleccionar —</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                    </select>
                </div>
                {{-- Teléfono --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Teléfono</label>
                    <input wire:model="telefono" type="text" maxlength="15" placeholder="10 dígitos"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                </div>
                {{-- Email --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Correo Electrónico</label>
                    <input wire:model="email" type="email" maxlength="120" placeholder="correo@empresa.com"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('email') border-red-300 @enderror">
                    @error('email') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Sucursal --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Sucursal</label>
                    <select wire:model="sucursalId"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <option value="">— Sin sucursal —</option>
                        @foreach($sucursales as $s)
                            <option value="{{ $s->id }}">{{ $s->nombre }} ({{ $s->clave }})</option>
                        @endforeach
                    </select>
                </div>
                {{-- Área --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Área <span class="text-red-500">*</span></label>
                    <select wire:model="area"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('area') border-red-300 @enderror">
                        <option value="">— Seleccionar —</option>
                        <option value="DIRECCION">Dirección</option>
                        <option value="ADMINISTRACION">Administración</option>
                        <option value="TECNICO_CAMPO">Técnico de Campo</option>
                        <option value="TECNICO_INSTALACIONES">Técnico de Instalaciones</option>
                        <option value="ATENCION_CLIENTE">Atención al Cliente</option>
                        <option value="CAJA_COBRANZA">Caja y Cobranza</option>
                        <option value="RRHH">Recursos Humanos</option>
                        <option value="SISTEMAS">Sistemas</option>
                    </select>
                    @error('area') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Puesto --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Puesto <span class="text-red-500">*</span></label>
                    <input wire:model="puesto" type="text" maxlength="100" placeholder="Ej. Técnico instalador"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('puesto') border-red-300 @enderror">
                    @error('puesto') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Tipo Contrato --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Tipo Contrato <span class="text-red-500">*</span></label>
                    <select wire:model="tipoContrato"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('tipoContrato') border-red-300 @enderror">
                        <option value="">— Seleccionar —</option>
                        <option value="PLANTA">Planta</option>
                        <option value="CONFIANZA">Confianza</option>
                        <option value="TEMPORAL">Temporal</option>
                        <option value="HONORARIOS">Honorarios</option>
                    </select>
                    @error('tipoContrato') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Fecha Ingreso --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Fecha Ingreso <span class="text-red-500">*</span></label>
                    <input wire:model="fechaIngreso" type="date"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('fechaIngreso') border-red-300 @enderror">
                    @error('fechaIngreso') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Salario Base --}}
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Salario Base <span class="text-red-500">*</span></label>
                    <input wire:model="salarioBase" type="number" min="0" step="0.01" placeholder="0.00"
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 @error('salarioBase') border-red-300 @enderror">
                    @error('salarioBase') <p class="text-[9px] text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Observaciones --}}
                <div class="col-span-2 lg:col-span-3">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Observaciones</label>
                    <textarea wire:model="observaciones" rows="2" placeholder="Notas adicionales..."
                        class="w-full border border-gray-200 rounded-xl px-3 py-2 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-300 resize-none"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-5">
                <button wire:click="cancelar"
                    class="px-4 py-2 text-xs font-black uppercase tracking-wider text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl transition-colors">
                    Cancelar
                </button>
                <button wire:click="guardar" wire:loading.attr="disabled"
                    class="flex items-center gap-2 px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-wider rounded-xl transition-colors disabled:opacity-60">
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
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Buscar por nombre, clave, puesto..."
                    class="w-full pl-8 pr-3 py-2 text-xs border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-300">
            </div>
            <select wire:model.live="filtroSucursal"
                class="text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-700">
                <option value="">Todas las sucursales</option>
                @foreach($sucursales as $s)
                    <option value="{{ $s->id }}">{{ $s->nombre }}</option>
                @endforeach
            </select>
            <select wire:model.live="filtroArea"
                class="text-xs border border-gray-200 rounded-xl px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 text-gray-700">
                <option value="">Todas las áreas</option>
                <option value="DIRECCION">Dirección</option>
                <option value="ADMINISTRACION">Administración</option>
                <option value="TECNICO_CAMPO">Técnico Campo</option>
                <option value="TECNICO_INSTALACIONES">Técnico Instalaciones</option>
                <option value="ATENCION_CLIENTE">Atención Cliente</option>
                <option value="CAJA_COBRANZA">Caja y Cobranza</option>
                <option value="RRHH">RRHH</option>
                <option value="SISTEMAS">Sistemas</option>
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-gray-100">
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Clave</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Empleado</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Área / Puesto</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Contrato</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Sucursal</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Ingreso</th>
                        <th class="px-5 py-3 text-left text-[9px] font-black uppercase tracking-widest text-gray-400">Estado</th>
                        <th class="px-5 py-3 text-right text-[9px] font-black uppercase tracking-widest text-gray-400">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($empleados as $emp)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 bg-blue-50 text-blue-700 rounded font-black text-[9px] tracking-widest">{{ $emp->clave_empleado }}</span>
                            </td>
                            <td class="px-5 py-3">
                                <p class="text-gray-800 font-semibold">{{ $emp->nombre_completo }}</p>
                                @if($emp->email)
                                    <p class="text-[9px] text-gray-400">{{ $emp->email }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-3">
                                <p class="text-gray-700 font-medium">{{ $emp->puesto }}</p>
                                <p class="text-[9px] text-gray-400 uppercase tracking-wider">{{ str_replace('_', ' ', $emp->area) }}</p>
                            </td>
                            <td class="px-5 py-3">
                                @php
                                    $contratoColor = match($emp->tipo_contrato) {
                                        'PLANTA'    => 'bg-emerald-50 text-emerald-700',
                                        'CONFIANZA' => 'bg-blue-50 text-blue-700',
                                        'TEMPORAL'  => 'bg-amber-50 text-amber-700',
                                        default     => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp
                                <span class="px-2 py-0.5 {{ $contratoColor }} rounded text-[9px] font-black uppercase">{{ $emp->tipo_contrato }}</span>
                            </td>
                            <td class="px-5 py-3 text-gray-600">{{ $emp->sucursal?->nombre ?? '—' }}</td>
                            <td class="px-5 py-3 text-gray-500 font-mono">{{ $emp->fecha_ingreso->format('d/m/Y') }}</td>
                            <td class="px-5 py-3">
                                @if($emp->activo)
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
                                    <button wire:click="editar({{ $emp->id }})"
                                        class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Editar">
                                        <i class="ri-edit-line text-sm"></i>
                                    </button>
                                    @if($emp->activo)
                                        <button @click="$confirm('¿Desactivar este empleado?', () => $wire.eliminar({{ $emp->id }}))"
                                            class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Desactivar">
                                            <i class="ri-toggle-fill text-sm"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-12 text-center">
                                <i class="ri-user-add-line text-3xl text-gray-200 block mb-2"></i>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sin empleados registrados</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($empleados->hasPages())
            <div class="px-5 py-3 border-t border-gray-100">
                {{ $empleados->links() }}
            </div>
        @endif
    </div>
</div>
