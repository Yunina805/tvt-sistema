<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ================================================================
         ENCABEZADO DE PÁGINA
    ================================================================ --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-indigo-600">Contratación Nueva</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Registro de Nuevo Suscriptor
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Flujo integral: paquete → datos → caja → contrato → técnico</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-bold text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 hover:border-gray-300 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>

    {{-- ================================================================
         STEPPER
    ================================================================ --}}
    <div class="mb-8 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex">
            @php
                $pasos = [
                    1 => ['label' => 'Servicio',  'sub' => 'Selección de paquete', 'icon' => 'ri-tv-2-line'],
                    2 => ['label' => 'Datos',     'sub' => 'Expediente del cliente','icon' => 'ri-user-settings-line'],
                    3 => ['label' => 'Caja',      'sub' => 'Liquidación de inicio', 'icon' => 'ri-money-dollar-circle-line'],
                    4 => ['label' => 'Contrato',  'sub' => 'Firma digital',         'icon' => 'ri-file-text-line'],
                    5 => ['label' => 'Técnico',   'sub' => 'Asignación de campo',   'icon' => 'ri-tools-line'],
                ];
            @endphp

            @foreach($pasos as $num => $p)
                @php
                    $isActive    = $paso === $num;
                    $isCompleted = $paso > $num;
                    $isLast      = $num === count($pasos);
                @endphp
                <div class="flex-1 relative
                    {{ $isActive    ? 'bg-indigo-600' : '' }}
                    {{ $isCompleted ? 'bg-indigo-50'  : '' }}
                    {{ !$isActive && !$isCompleted ? 'bg-white' : '' }}
                    {{ !$isLast ? 'border-r border-gray-200' : '' }}">

                    {{-- Chevron decorativo --}}
                    @if(!$isLast)
                        <div class="absolute right-0 top-0 bottom-0 w-3 z-10 flex items-center justify-end overflow-hidden">
                            <svg viewBox="0 0 12 48" class="h-full w-3 {{ $isActive ? 'text-indigo-600' : ($isCompleted ? 'text-indigo-50' : 'text-white') }}" preserveAspectRatio="none">
                                <path d="M0,0 L8,24 L0,48 L12,48 L12,0 Z" fill="currentColor"/>
                            </svg>
                        </div>
                    @endif

                    <div class="flex items-center gap-2.5 px-4 py-3.5 pr-6">
                        {{-- Ícono / Check --}}
                        <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center
                            {{ $isActive    ? 'bg-white/20' : '' }}
                            {{ $isCompleted ? 'bg-indigo-100' : '' }}
                            {{ !$isActive && !$isCompleted ? 'bg-gray-100' : '' }}">
                            @if($isCompleted)
                                <i class="ri-check-line text-indigo-600 text-base font-black"></i>
                            @else
                                <i class="{{ $p['icon'] }} text-base
                                    {{ $isActive ? 'text-white' : 'text-gray-400' }}"></i>
                            @endif
                        </div>
                        {{-- Texto --}}
                        <div class="hidden sm:block min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-widest leading-none
                                {{ $isActive ? 'text-white' : ($isCompleted ? 'text-indigo-600' : 'text-gray-400') }}">
                                {{ $p['label'] }}
                            </p>
                            <p class="text-[9px] mt-0.5 truncate
                                {{ $isActive ? 'text-indigo-200' : ($isCompleted ? 'text-indigo-400' : 'text-gray-300') }}">
                                {{ $p['sub'] }}
                            </p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================================================================
         MENSAJE DE SESIÓN
    ================================================================ --}}
    @if(session()->has('mensaje'))
        <div class="mb-5 p-3.5 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200 text-[11px] font-bold uppercase tracking-widest flex items-center gap-2">
            <i class="ri-checkbox-circle-fill text-base text-emerald-500"></i>
            {{ session('mensaje') }}
        </div>
    @endif

    {{-- ================================================================
         PASO 1 — SELECCIÓN DE SERVICIO
    ================================================================ --}}
    @if($paso == 1)
    <div class="space-y-6">

        {{-- Captura de INE --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center flex-shrink-0">
                    <i class="ri-id-card-line text-indigo-600 text-base"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Identificación Oficial</p>
                    <p class="text-[10px] text-gray-400">Capture foto del INE o pasaporte vigente del titular</p>
                </div>
                <span class="ml-auto text-[9px] font-bold text-red-500 uppercase tracking-widest bg-red-50 border border-red-100 px-2 py-1 rounded-md">Requerido</span>
            </div>
            <div class="border-2 border-dashed border-gray-200 rounded-lg p-4 text-center hover:border-indigo-300 hover:bg-indigo-50/30 transition-colors cursor-pointer group">
                <input type="file" wire:model="identificacion" class="block w-full text-xs text-gray-500
                    file:mr-4 file:py-2 file:px-5 file:rounded-lg file:border-0
                    file:text-[10px] file:font-black file:bg-indigo-600 file:text-white
                    hover:file:bg-indigo-700 cursor-pointer">
                <p class="text-[10px] text-gray-400 mt-2 uppercase tracking-wider">JPG, PNG o PDF — Máx 5MB</p>
            </div>
        </div>

        {{-- Selector de paquetes --}}
        <div>
            <div class="flex items-center justify-between mb-4">
                <p class="text-[11px] font-black text-gray-500 uppercase tracking-widest">Selección de Paquete de Servicio</p>
                <span class="text-[9px] font-bold text-indigo-500 bg-indigo-50 border border-indigo-100 px-2 py-1 rounded-md uppercase tracking-widest">{{ count($paquetes) }} disponibles</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($paquetes as $key => $paquete)
                <div wire:click="seleccionarServicio('{{ $key }}')"
                     class="relative bg-white border-2 rounded-xl p-5 transition-all cursor-pointer group
                            {{ $servicioSeleccionado === $key
                                ? 'border-indigo-500 shadow-lg shadow-indigo-100 ring-1 ring-indigo-500/30'
                                : 'border-gray-200 hover:border-indigo-300 hover:shadow-md' }}">

                    {{-- Badge tipo de servicio --}}
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex flex-col gap-1">
                            @if($paquete['canales'] > 0)
                                <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase bg-violet-100 text-violet-700 px-2 py-0.5 rounded-md tracking-widest">
                                    <i class="ri-tv-2-line"></i> TV
                                </span>
                            @endif
                            @if($paquete['internet'])
                                <span class="inline-flex items-center gap-1 text-[9px] font-black uppercase bg-sky-100 text-sky-700 px-2 py-0.5 rounded-md tracking-widest">
                                    <i class="ri-wifi-line"></i> Internet
                                </span>
                            @endif
                        </div>
                        {{-- Indicador seleccionado --}}
                        <div class="w-5 h-5 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                    {{ $servicioSeleccionado === $key ? 'border-indigo-500 bg-indigo-500' : 'border-gray-300' }}">
                            @if($servicioSeleccionado === $key)
                                <i class="ri-check-line text-white text-[10px]"></i>
                            @endif
                        </div>
                    </div>

                    <h4 class="font-black text-gray-800 text-sm uppercase tracking-tight mb-3">{{ $paquete['nombre'] }}</h4>

                    {{-- Precio --}}
                    <div class="bg-gray-50 rounded-lg px-4 py-3 mb-4 text-center">
                        <p class="text-2xl font-black text-indigo-600 tracking-tight">${{ number_format($paquete['mensualidad'], 2) }}</p>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">/ mensualidad</p>
                    </div>

                    {{-- Features --}}
                    <ul class="space-y-2 mb-4 text-xs">
                        @if($paquete['canales'] > 0)
                            <li class="flex items-center gap-2 text-gray-600 font-medium">
                                <i class="ri-checkbox-circle-fill text-violet-500 text-base flex-shrink-0"></i>
                                {{ $paquete['canales'] }} canales digitales
                            </li>
                        @endif
                        @if($paquete['internet'])
                            <li class="flex items-center gap-2 text-gray-600 font-medium">
                                <i class="ri-checkbox-circle-fill text-sky-500 text-base flex-shrink-0"></i>
                                Internet de alta velocidad
                            </li>
                        @endif
                    </ul>

                    {{-- Instalación --}}
                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <span class="text-[9px] font-bold text-gray-400 uppercase">Instalación</span>
                        <span class="text-sm font-black text-gray-700">${{ number_format($paquete['instalacion'], 2) }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- CTA --}}
        @if($servicioSeleccionado)
            <div class="flex justify-end pt-2">
                <button wire:click="irAPaso(2)"
                        class="inline-flex items-center gap-2 px-8 py-3.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all active:scale-95">
                    Capturar Datos del Titular <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        @endif
    </div>
    @endif

    {{-- ================================================================
         PASO 2 — DATOS DEL CLIENTE
    ================================================================ --}}
    @if($paso == 2)
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

        {{-- Header del paso --}}
        <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                <i class="ri-user-settings-line text-indigo-600"></i>
            </div>
            <div>
                <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Expediente del Cliente</p>
                <p class="text-[10px] text-gray-400">Todos los campos marcados con * son obligatorios</p>
            </div>
            {{-- Resumen del paquete elegido --}}
            <div class="ml-auto flex items-center gap-2 bg-indigo-50 border border-indigo-100 rounded-lg px-3 py-2">
                <i class="ri-tv-2-line text-indigo-500 text-sm"></i>
                <span class="text-[10px] font-black text-indigo-700 uppercase tracking-wider">{{ $paquetes[$servicioSeleccionado]['nombre'] ?? '--' }}</span>
            </div>
        </div>

        <div class="p-6 space-y-6">

            {{-- Datos personales --}}
            <div>
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 flex items-center gap-2">
                    <span class="w-3 h-px bg-gray-300 inline-block"></span> Datos Personales
                </p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Nombre(s) *</label>
                        <input type="text" wire:model="nombre"
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-semibold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors placeholder:text-gray-300"
                               placeholder="JUAN">
                        @error('nombre') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Apellidos *</label>
                        <input type="text" wire:model="apellidos"
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-semibold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors placeholder:text-gray-300"
                               placeholder="PÉREZ GARCÍA">
                        @error('apellidos') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Teléfono Celular * <span class="text-gray-300 font-normal normal-case">(10 dígitos)</span></label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-[11px] font-black text-gray-400">+52</span>
                            <input type="text" wire:model="telefono" maxlength="10"
                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 pl-12 pr-4 text-sm font-semibold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors placeholder:text-gray-300"
                                   placeholder="9511234567">
                        </div>
                        @error('telefono') <p class="text-[10px] text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">CURP <span class="text-gray-300 font-normal normal-case">(18 caracteres)</span></label>
                        <input type="text" wire:model="curp" maxlength="18"
                               class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-semibold uppercase tracking-widest focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors placeholder:text-gray-300"
                               placeholder="PERJ900101HOCRNN00">
                    </div>
                </div>
            </div>

            {{-- Domicilio --}}
            <div class="bg-indigo-50/60 border border-indigo-100 rounded-xl p-5">
                <p class="text-[10px] font-black text-indigo-700 uppercase tracking-widest mb-4 flex items-center gap-2">
                    <i class="ri-map-pin-line text-indigo-500"></i> Domicilio de Instalación
                </p>
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-7 space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Calle (del catálogo) *</label>
                        <select wire:model="calle"
                                class="w-full bg-white border border-indigo-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
                            <option value="">— Seleccione vía —</option>
                            <option value="Independencia">AV. INDEPENDENCIA</option>
                            <option value="Reforma">CALLE REFORMA</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Núm. Ext. *</label>
                        <input type="text" wire:model="num_ext"
                               class="w-full bg-white border border-indigo-200 rounded-lg py-2.5 px-4 text-sm font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors"
                               placeholder="100">
                    </div>
                    <div class="md:col-span-3 space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Núm. Int. <span class="text-gray-300 font-normal">opcional</span></label>
                        <input type="text" wire:model="num_int"
                               class="w-full bg-white border border-indigo-200 rounded-lg py-2.5 px-4 text-sm font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors"
                               placeholder="NA">
                    </div>
                    <div class="md:col-span-12 space-y-1.5">
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Referencias para cuadrilla técnica</label>
                        <textarea wire:model="referencias" rows="2"
                                  class="w-full bg-white border border-indigo-200 rounded-lg py-2.5 px-4 text-xs font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors resize-none"
                                  placeholder="Ej: Frente a la farmacia Similares, portón café..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Facturación --}}
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <label class="flex items-center justify-between p-4 bg-gray-50 cursor-pointer hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-gray-200 flex items-center justify-center">
                            <i class="ri-bill-line text-gray-600 text-base"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">¿El cliente requiere Factura (CFDI)?</p>
                            <p class="text-[10px] text-gray-400">Si no requiere, se emite recibo simple</p>
                        </div>
                    </div>
                    <div class="relative">
                        <input type="checkbox" wire:model.live="requiereFactura" class="sr-only peer">
                        <div class="w-10 h-6 bg-gray-200 peer-checked:bg-indigo-600 rounded-full transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-4"></div>
                    </div>
                </label>

                @if($requiereFactura)
                <div class="p-5 border-t border-gray-200 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">RFC *</label>
                            <input type="text" wire:model="rfc" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">C.P. Fiscal *</label>
                            <input type="text" wire:model="cp_fiscal" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Uso CFDI *</label>
                            <select wire:model="uso_cfdi" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="G03">G03 — Gastos en general</option>
                                <option value="P01">P01 — Por definir</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Correo para envío de CFDI *</label>
                            <input type="email" wire:model="correo_fiscal" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400" placeholder="facturacion@empresa.com">
                        </div>
                        <div class="space-y-1.5">
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Método de Pago</label>
                            <select wire:model="metodo_pago_cfdi" class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400">
                                <option value="PUE">PUE — Pago en una sola exhibición</option>
                                <option value="PPD">PPD — Pago en parcialidades</option>
                            </select>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>

        {{-- Footer con acciones --}}
        <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-between">
            <button wire:click="irAPaso(1)"
                    class="inline-flex items-center gap-2 text-[10px] font-black text-gray-500 hover:text-gray-800 uppercase tracking-widest transition-colors">
                <i class="ri-arrow-left-line"></i> Atrás
            </button>
            <button wire:click="irAPaso(3)"
                    class="inline-flex items-center gap-2 px-7 py-3 bg-indigo-600 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all active:scale-95">
                Proceder a Caja <i class="ri-arrow-right-line"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 3 — CAJA
    ================================================================ --}}
    @if($paso == 3)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Columna izquierda: Resumen de cobro --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center">
                    <i class="ri-receipt-line text-emerald-600"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Resumen de Cobro</p>
                    <p class="text-[10px] text-gray-400 font-mono">Folio: #{{ $folioPago }}</p>
                </div>
            </div>

            <div class="p-5 space-y-1">
                {{-- Cliente --}}
                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Cliente</span>
                    <span class="text-xs font-black text-gray-800 uppercase">{{ $nombre }} {{ $apellidos }}</span>
                </div>
                {{-- Servicio --}}
                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Servicio</span>
                    <span class="text-xs font-black text-indigo-600 uppercase">{{ $paquetes[$servicioSeleccionado]['nombre'] ?? '--' }}</span>
                </div>
                {{-- Instalación --}}
                <div class="flex justify-between items-center py-2.5 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold text-gray-600 uppercase">Instalación (único)</span>
                    </div>
                    <span class="text-sm font-black text-gray-900">${{ number_format($paquetes[$servicioSeleccionado]['instalacion'], 2) }}</span>
                </div>
                {{-- Prorrateo --}}
                <div class="flex justify-between items-start py-2.5 border-b border-gray-100">
                    <div>
                        <span class="text-xs font-bold text-gray-600 uppercase">Mensualidad prorrateada</span>
                        <p class="text-[9px] text-gray-400 mt-0.5 uppercase font-medium">{{ $diasUso }} días de uso calculados</p>
                    </div>
                    <span class="text-sm font-black text-gray-900">${{ number_format($costoProrrateo, 2) }}</span>
                </div>
                {{-- Subtotal / IVA --}}
                <div class="flex justify-between items-center py-2 text-xs text-gray-400 font-medium">
                    <span class="uppercase">Subtotal</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between items-center py-2 text-xs text-gray-400 font-medium">
                    <span class="uppercase">I.V.A. (16%)</span>
                    <span>${{ number_format($iva, 2) }}</span>
                </div>
                {{-- Total --}}
                <div class="flex justify-between items-center mt-2 bg-gray-900 rounded-xl px-5 py-4">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Total a Cobrar</span>
                    <span class="text-2xl font-black text-white tracking-tight">${{ number_format($totalPagar, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Columna derecha: Gestión de cobro --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                    <i class="ri-secure-payment-line text-indigo-600"></i>
                </div>
                <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Gestión de Cobro</p>
            </div>

            <div class="p-5 space-y-5">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Método de Pago</label>
                    <select wire:model="metodo_pago"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2.5 px-4 text-xs font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
                        <option value="efectivo">Efectivo en ventanilla</option>
                        <option value="tarjeta">Tarjeta — Terminal bancaria</option>
                        <option value="transferencia">Transferencia / Depósito</option>
                    </select>
                </div>

                {{-- Checkbox de validación cajero --}}
                <div class="border-2 border-dashed border-indigo-200 rounded-xl p-4 bg-indigo-50/50">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model.live="confirmacionCaja"
                               class="mt-0.5 h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <div>
                            <p class="text-[11px] font-black text-indigo-900 uppercase tracking-widest mb-1">Validación de Cajero</p>
                            <p class="text-[10px] text-indigo-600 leading-relaxed font-medium">
                                Certifico que el monto total fue recibido e ingresado correctamente a caja física. Esta acción es irreversible y habilitará el contrato digital.
                            </p>
                        </div>
                    </label>
                </div>

                {{-- Alerta cuando no ha confirmado --}}
                @if(!$confirmacionCaja)
                <div class="flex items-center gap-2 text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 rounded-lg px-3 py-2.5">
                    <i class="ri-alert-line text-base flex-shrink-0"></i>
                    Debe confirmar la recepción del pago antes de continuar
                </div>
                @endif
            </div>

            <div class="bg-gray-50 border-t border-gray-200 p-5 space-y-3">
                <button wire:click="confirmarCobro"
                        @if(!$confirmacionCaja) disabled @endif
                        class="w-full py-3.5 bg-indigo-600 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-indigo-700 shadow-md shadow-indigo-200 transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed">
                    <i class="ri-check-double-line mr-2"></i> Confirmar Ingreso y Generar ID
                </button>
                <button wire:click="irAPaso(2)"
                        class="w-full py-2.5 text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors">
                    <i class="ri-arrow-left-line mr-1"></i> Ajustar datos del cliente
                </button>
            </div>
        </div>

    </div>
    @endif

    {{-- ================================================================
         PASO 4 — CONTRATO / FIRMA DIGITAL
    ================================================================ --}}
    @if($paso == 4)
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- Panel lateral izquierdo --}}
        <div class="space-y-4">

            {{-- Resumen del expediente --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-900 px-5 py-4">
                    <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest">Resumen del Expediente</p>
                </div>
                <div class="p-4 space-y-3 text-xs">
                    <div>
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Titular Legal</p>
                        <p class="font-black text-gray-900 uppercase">{{ $nombre }} {{ $apellidos }}</p>
                    </div>
                    <div class="border-t border-gray-100 pt-3">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Servicio</p>
                        <span class="inline-flex items-center gap-1 text-[10px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-1 rounded-md uppercase">
                            <i class="ri-tv-2-line"></i> {{ $paquetes[$servicioSeleccionado]['nombre'] ?? '--' }}
                        </span>
                    </div>
                    <div class="border-t border-gray-100 pt-3">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Domicilio</p>
                        <p class="font-semibold text-gray-700 uppercase">{{ $calle }} #{{ $num_ext }}</p>
                        @if($referencias)
                            <p class="text-[10px] text-gray-400 italic mt-0.5">{{ $referencias }}</p>
                        @endif
                    </div>
                    <div class="border-t border-gray-100 pt-3">
                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">ID Generado</p>
                        <p class="font-mono font-black text-gray-900 tracking-widest">#{{ $reporteGeneradoId ?? '---' }}</p>
                    </div>
                </div>
            </div>

            {{-- Aviso legal --}}
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex gap-3">
                <i class="ri-shield-check-line text-blue-500 text-xl flex-shrink-0 mt-0.5"></i>
                <p class="text-[10px] font-medium text-blue-700 leading-relaxed">
                    Al firmar, el cliente acepta los <strong>términos y condiciones</strong> del contrato de adhesión de Tu Visión Telecable conforme a la normativa CRT.
                </p>
            </div>
        </div>

        {{-- Panel principal: captura de firma --}}
        <div class="md:col-span-2 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden flex flex-col">
            <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                    <i class="ri-pen-nib-line text-violet-600"></i>
                </div>
                <div>
                    <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Firma Digital del Contrato</p>
                    <p class="text-[10px] text-gray-400">El cliente firma con dedo o stylus en el área habilitada</p>
                </div>
            </div>

            <div class="flex-1 p-6 flex flex-col">
                {{-- Área de firma --}}
                <div class="flex-1 border-2 border-dashed border-gray-200 rounded-xl min-h-[220px] flex items-center justify-center bg-gray-50 hover:border-indigo-300 transition-colors relative group mb-4">
                    <div class="text-center">
                        <i class="ri-edit-2-line text-4xl text-gray-200 group-hover:text-indigo-300 transition-colors block mb-2"></i>
                        <p class="text-[10px] font-bold text-gray-300 uppercase tracking-widest">Área de captura de firma</p>
                    </div>
                    {{-- Línea base de firma --}}
                    <div class="absolute bottom-10 left-12 right-12 border-b border-gray-200"></div>
                    <p class="absolute bottom-4 left-12 text-[9px] text-gray-300 uppercase tracking-widest font-bold">Firma del titular</p>
                </div>

                {{-- Controles --}}
                <div class="flex items-center justify-between mb-5">
                    <button class="inline-flex items-center gap-1.5 text-[10px] font-black text-red-400 hover:text-red-600 hover:bg-red-50 px-3 py-2 rounded-lg transition-all uppercase tracking-widest">
                        <i class="ri-delete-bin-line"></i> Borrar
                    </button>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" wire:model.live="aceptaTerminos"
                               class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                        <span class="text-[10px] font-black text-gray-700 uppercase tracking-wider">Confirmo veracidad de datos</span>
                    </label>
                </div>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex items-center justify-between">
                <button wire:click="irAPaso(3)"
                        class="inline-flex items-center gap-2 text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors">
                    <i class="ri-arrow-left-line"></i> Caja
                </button>
                <button wire:click="firmarContrato"
                        @if(!$aceptaTerminos) disabled @endif
                        class="inline-flex items-center gap-2 px-7 py-3 bg-gray-900 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-black shadow-lg transition-all active:scale-95 disabled:opacity-40 disabled:cursor-not-allowed">
                    <i class="ri-file-text-line"></i> Guardar Firma y Continuar
                </button>
            </div>
        </div>

    </div>
    @endif

    {{-- ================================================================
         PASO 5 — ASIGNACIÓN TÉCNICA
    ================================================================ --}}
    @if($paso == 5)
    <div class="space-y-6 max-w-3xl mx-auto">

        {{-- Header del reporte auto-generado --}}
        <div class="bg-gray-900 rounded-xl p-5 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black text-indigo-400 uppercase tracking-widest mb-1">Reporte de Servicio Auto-Generado</p>
                <p class="font-mono font-black text-white text-lg tracking-widest">#{{ $reporteGeneradoId }}</p>
            </div>
            <div class="text-right">
                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest">Fecha de apertura</p>
                <p class="text-xs font-bold text-gray-300">{{ now()->format('d/m/Y — H:i') }}</p>
            </div>
        </div>

        {{-- Datos del reporte --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                <i class="ri-file-list-3-line text-indigo-500"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Datos Previos del Reporte</p>
                <span class="ml-auto text-[9px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-100 px-2 py-1 rounded-md uppercase">Generado automáticamente</span>
            </div>
            <div class="divide-y divide-gray-100">
                @php
                    $datosReporte = [
                        ['label' => 'Sucursal',           'value' => 'Oaxaca Centro', 'icon' => 'ri-store-2-line'],
                        ['label' => 'Estado del Cliente', 'value' => 'PENDIENTE DE INSTALACIÓN', 'icon' => 'ri-user-follow-line', 'badge' => 'amber'],
                        ['label' => 'Servicio',           'value' => $paquetes[$servicioSeleccionado]['nombre'] ?? '--', 'icon' => 'ri-tv-2-line', 'badge' => 'indigo'],
                        ['label' => 'Domicilio',          'value' => ($calle ?? '--') . ' #' . ($num_ext ?? '--'), 'icon' => 'ri-map-pin-line'],
                        ['label' => 'Referencias',        'value' => $referencias ?: '—', 'icon' => 'ri-map-2-line'],
                    ];
                @endphp
                @foreach($datosReporte as $dato)
                <div class="flex items-start gap-4 px-5 py-3.5">
                    <i class="{{ $dato['icon'] }} text-base text-gray-400 flex-shrink-0 mt-0.5"></i>
                    <div class="flex-1 min-w-0 flex items-start justify-between gap-3">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest flex-shrink-0">{{ $dato['label'] }}</span>
                        @if(isset($dato['badge']) && $dato['badge'] === 'amber')
                            <span class="text-[9px] font-black text-amber-700 bg-amber-50 border border-amber-200 px-2 py-1 rounded-md uppercase tracking-widest">{{ $dato['value'] }}</span>
                        @elseif(isset($dato['badge']) && $dato['badge'] === 'indigo')
                            <span class="text-[10px] font-black text-indigo-700 bg-indigo-50 border border-indigo-100 px-2 py-1 rounded-md uppercase">{{ $dato['value'] }}</span>
                        @else
                            <span class="text-xs font-bold text-gray-800 uppercase text-right">{{ $dato['value'] }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Asignación de técnico --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 border-b border-gray-200 px-5 py-3.5 flex items-center gap-2">
                <i class="ri-tools-line text-orange-500"></i>
                <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Asignación de Personal Técnico</p>
                <span class="ml-auto text-[9px] font-bold text-red-500 bg-red-50 border border-red-100 px-2 py-1 rounded-md uppercase">Requerido para finalizar</span>
            </div>
            <div class="p-5 space-y-4">
                <div class="space-y-1.5">
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest">Técnico o Cuadrilla Responsable</label>
                    <div class="relative">
                        <i class="ri-user-star-line absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <select wire:model.live="tecnicoAsignado"
                                class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-sm font-bold uppercase focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-colors">
                            <option value="">— Seleccione técnico o cuadrilla —</option>
                            <option value="Juan Pérez">ING. JUAN PÉREZ (ZONA CENTRO)</option>
                            <option value="Cuadrilla 2">CUADRILLA 2 (OAXACA NORTE)</option>
                        </select>
                    </div>
                </div>

                @if($tecnicoAsignado)
                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
                    <i class="ri-checkbox-circle-fill text-emerald-500 text-lg"></i>
                    <div>
                        <p class="text-[10px] font-black text-emerald-800 uppercase tracking-widest">Técnico seleccionado</p>
                        <p class="text-xs font-bold text-emerald-700 uppercase">{{ $tecnicoAsignado }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- Notificación SMS --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 flex items-center gap-4">
            <label class="flex items-center gap-3 cursor-pointer flex-1">
                <input type="checkbox" wire:model="notificarSms"
                       class="h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500">
                <div>
                    <p class="text-[11px] font-black text-gray-700 uppercase tracking-widest">Activar Notificaciones SMS</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">Se enviará aviso al técnico y al cliente al confirmar</p>
                </div>
            </label>
            <i class="ri-message-3-line text-2xl text-gray-300"></i>
        </div>

        {{-- Botón final --}}
        <button wire:click="finalizarProceso"
                @if(!$tecnicoAsignado) disabled @endif
                class="w-full py-4 bg-emerald-600 text-white font-black text-sm uppercase tracking-[0.3em] rounded-xl shadow-lg shadow-emerald-200 hover:bg-emerald-700 transition-all active:scale-95 flex items-center justify-center gap-3 disabled:opacity-40 disabled:grayscale disabled:cursor-not-allowed">
            <i class="ri-save-3-line text-xl"></i>
            Finalizar Contratación y Cerrar Expediente
        </button>

    </div>
    @endif

</div>