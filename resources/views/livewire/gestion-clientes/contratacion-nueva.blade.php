<div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ENCABEZADO --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-red-400"></i>
                <span>Gestión al Suscriptor</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-red-600">Contratación Nueva</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">Contratación de Servicio</h2>
            <p class="text-xs text-gray-400 mt-0.5">Servicio → Suscriptor → Pago → Recibo → Contrato → Reporte</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-600 font-bold text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all self-start">
            <i class="ri-arrow-left-line"></i> Panel Principal
        </a>
    </div>

    {{-- STEPPER --}}
    @if($paso < 7)
    <div class="mb-6 bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="flex">
            @php
                $pasos = [
                    1 => ['label' => 'Servicio',    'icon' => 'ri-tv-line'],
                    2 => ['label' => 'Suscriptor',  'icon' => 'ri-user-add-line'],
                    3 => ['label' => 'Pago',         'icon' => 'ri-secure-payment-line'],
                    4 => ['label' => 'Recibo',       'icon' => 'ri-file-text-line'],
                    5 => ['label' => 'Contrato',     'icon' => 'ri-pen-nib-line'],
                    6 => ['label' => 'Reporte',      'icon' => 'ri-tools-line'],
                ];
            @endphp
            @foreach($pasos as $num => $info)
            <div class="flex-1 flex flex-col items-center py-3 px-1 {{ !$loop->last ? 'border-r border-gray-200' : '' }}
                {{ $paso >= $num ? 'bg-red-50' : 'bg-gray-50' }}">
                <div class="w-7 h-7 rounded-full flex items-center justify-center mb-1
                    {{ $paso > $num ? 'bg-red-600 text-white' : ($paso == $num ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-400') }}">
                    @if($paso > $num)
                        <i class="ri-check-line text-xs"></i>
                    @else
                        <i class="{{ $info['icon'] }} text-xs"></i>
                    @endif
                </div>
                <span class="text-[9px] font-bold uppercase tracking-wider hidden sm:block
                    {{ $paso >= $num ? 'text-red-700' : 'text-gray-400' }}">
                    {{ $info['label'] }}
                </span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 1 — IDENTIFICACIÓN + SELECCIÓN DEL SERVICIO
    ================================================================ --}}
    @if($paso === 1)
    <div class="space-y-5">

        {{-- Carga de Identificación --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-id-card-line text-red-500"></i> Identificación del Suscriptor
            </h3>
            <p class="text-xs text-gray-500 mb-4">Solicita la credencial de elector o identificación oficial vigente y carga la fotografía.</p>
            <div class="flex flex-col sm:flex-row gap-4 items-start">
                <label class="flex flex-col items-center justify-center w-full sm:w-48 h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer hover:border-red-400 hover:bg-red-50 transition-all overflow-hidden">
                    @if($fotoIdentificacion)
                        <img src="{{ $fotoIdentificacion->temporaryUrl() }}" class="h-full w-full object-cover" alt="ID">
                    @else
                        <i class="ri-upload-cloud-2-line text-2xl text-gray-400 mb-1"></i>
                        <span class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Cargar foto de ID</span>
                        <span class="text-[9px] text-gray-300 mt-0.5">JPG, PNG · máx 5 MB</span>
                    @endif
                    <input type="file" class="hidden" wire:model="fotoIdentificacion" accept="image/*">
                </label>
                <div class="flex-1 text-xs text-gray-500 space-y-1 pt-2">
                    <p class="flex items-center gap-1.5"><i class="ri-checkbox-circle-line text-emerald-500"></i> Credencial de Elector (IFE/INE)</p>
                    <p class="flex items-center gap-1.5"><i class="ri-checkbox-circle-line text-emerald-500"></i> Pasaporte vigente</p>
                    <p class="flex items-center gap-1.5"><i class="ri-checkbox-circle-line text-emerald-500"></i> Cédula profesional</p>
                    <p class="flex items-center gap-1.5 text-amber-600 mt-2"><i class="ri-information-line"></i> La foto debe ser legible y vigente.</p>
                </div>
            </div>
        </div>

        {{-- Selección del Servicio --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-price-tag-3-line text-red-500"></i> Selección del Servicio
            </h3>

            @if(empty($tarifas))
                <div class="text-center py-10 text-gray-400">
                    <i class="ri-price-tag-line text-3xl mb-2 block"></i>
                    <p class="text-xs">No hay tarifas disponibles para contratar.</p>
                    <p class="text-[10px] text-gray-300 mt-1">Agrega tarifas con estado "VIGENTE_CONTRATAR" en el catálogo Financiero.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($tarifas as $tarifa)
                    @php
                        $tipo = $this->inferirTipoServicio($tarifa['nombre_comercial'] ?? '');
                        $colorBadge = match($tipo) {
                            'TV+INTERNET' => 'bg-purple-100 text-purple-700',
                            'INTERNET'    => 'bg-blue-100 text-blue-700',
                            default       => 'bg-orange-100 text-orange-700',
                        };
                        $iconoBadge = match($tipo) {
                            'TV+INTERNET' => 'ri-broadband-line',
                            'INTERNET'    => 'ri-wifi-line',
                            default       => 'ri-tv-line',
                        };
                    @endphp
                    <button wire:click="seleccionarTarifa({{ $tarifa['id'] }})"
                            class="text-left p-4 rounded-xl border-2 transition-all
                                {{ $tarifaId == $tarifa['id']
                                    ? 'border-red-500 bg-red-50 shadow-md'
                                    : 'border-gray-200 hover:border-red-300 hover:bg-red-50' }}">
                        <div class="flex items-center justify-between mb-2">
                            <span class="inline-flex items-center gap-1 text-[9px] font-black px-2 py-0.5 rounded-full {{ $colorBadge }}">
                                <i class="{{ $iconoBadge }}"></i> {{ $tipo }}
                            </span>
                            @if($tarifaId == $tarifa['id'])
                                <i class="ri-checkbox-circle-fill text-red-500"></i>
                            @else
                                <i class="ri-checkbox-blank-circle-line text-gray-300"></i>
                            @endif
                        </div>
                        <p class="text-sm font-black text-gray-900 leading-tight mb-1">{{ $tarifa['nombre_comercial'] }}</p>
                        @if($tarifa['descripcion'])
                            <p class="text-[10px] text-gray-500 mb-3 leading-relaxed">{{ $tarifa['descripcion'] }}</p>
                        @endif
                        <div class="space-y-1 border-t border-gray-100 pt-2">
                            <div class="flex justify-between text-[10px]">
                                <span class="text-gray-500">Instalación</span>
                                <span class="font-black text-gray-800">${{ number_format($tarifa['precio_instalacion'], 2) }}</span>
                            </div>
                            <div class="flex justify-between text-[10px]">
                                <span class="text-gray-500">Mensualidad</span>
                                <span class="font-black text-gray-800">${{ number_format($tarifa['precio_mensualidad'], 2) }}</span>
                            </div>
                        </div>
                    </button>
                    @endforeach
                </div>
            @endif

            {{-- Resumen primer pago --}}
            @if($tarifaId)
            <div class="mt-5 bg-gray-50 border border-gray-200 rounded-xl p-4">
                <h4 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-calculator-line text-red-500"></i> Cálculo del Primer Pago
                </h4>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                        <p class="text-[9px] text-gray-400 uppercase tracking-wider mb-1">Días restantes</p>
                        <p class="text-xl font-black text-red-600">{{ $diasRestantes }}</p>
                        <p class="text-[9px] text-gray-400">día {{ now()->day }} al 30</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                        <p class="text-[9px] text-gray-400 uppercase tracking-wider mb-1">Instalación</p>
                        <p class="text-xl font-black text-gray-800">${{ number_format($tarifaSeleccionada['precio_instalacion'], 2) }}</p>
                        <p class="text-[9px] text-gray-400">cargo único</p>
                    </div>
                    <div class="bg-white border border-gray-200 rounded-lg p-3">
                        <p class="text-[9px] text-gray-400 uppercase tracking-wider mb-1">Prorrateo</p>
                        <p class="text-xl font-black text-gray-800">${{ number_format($costoProrrateo, 2) }}</p>
                        <p class="text-[9px] text-gray-400">{{ $diasRestantes }} días proporcional</p>
                    </div>
                    <div class="bg-red-600 text-white rounded-lg p-3">
                        <p class="text-[9px] uppercase tracking-wider mb-1 opacity-80">Total a Cobrar</p>
                        <p class="text-xl font-black">${{ number_format($totalPagar, 2) }}</p>
                        <p class="text-[9px] opacity-70">incluye IVA ${{ number_format($iva, 2) }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="flex justify-end">
            <button wire:click="irPaso2"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                Continuar — Alta de Suscriptor <i class="ri-arrow-right-line"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 2 — ALTA DEL SUSCRIPTOR
    ================================================================ --}}
    @if($paso === 2)
    <div class="space-y-5">

        {{-- Servicio seleccionado --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="ri-tv-line text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-[9px] text-gray-400 uppercase tracking-wider">Servicio seleccionado</p>
                        <p class="text-sm font-black text-gray-900">{{ $tarifaSeleccionada['nombre_comercial'] }}</p>
                        <p class="text-[10px] text-gray-500">
                            Instalación: ${{ number_format($tarifaSeleccionada['precio_instalacion'], 2) }} ·
                            Mensualidad: ${{ number_format($tarifaSeleccionada['precio_mensualidad'], 2) }} ·
                            Primer pago: <span class="font-black text-red-600">${{ number_format($totalPagar, 2) }}</span>
                        </p>
                    </div>
                </div>
                <button x-data
                        @click="$confirm('¿Deseas regresar a cambiar el servicio?', () => $wire.set('paso', 1), { title: 'Cambiar servicio', confirmText: 'Sí, cambiar', icon: 'question' })"
                        class="text-[10px] text-red-600 font-bold cursor-pointer flex items-center gap-1 hover:underline flex-shrink-0">
                    <i class="ri-refresh-line"></i> Cambiar
                </button>
            </div>
        </div>

        {{-- Facturación --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-file-paper-2-line text-red-500"></i> Facturación Fiscal
            </h3>
            <label class="flex items-center gap-3 cursor-pointer mb-4">
                <div class="relative flex-shrink-0">
                    <input type="checkbox" wire:model.live="requiereFactura" class="sr-only">
                    <div class="w-10 h-5 rounded-full transition-colors {{ $requiereFactura ? 'bg-red-600' : 'bg-gray-300' }}"></div>
                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform {{ $requiereFactura ? 'translate-x-5' : '' }}"></div>
                </div>
                <span class="text-xs font-bold text-gray-700">El suscriptor requiere factura fiscal (CFDI)</span>
            </label>

            @if($requiereFactura)
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 border-t border-gray-100 pt-4">
                <div class="sm:col-span-2">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Nombre o Razón Social</label>
                    <input type="text" wire:model.live="razonSocial"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300 uppercase"
                           placeholder="JUAN PEREZ GARCIA o MI EMPRESA SA DE CV">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">RFC</label>
                    <input type="text" wire:model.live="rfc"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300 uppercase"
                           placeholder="XAXX010101000" maxlength="13">
                    @error('rfc') <p class="text-[10px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Código Postal Fiscal</label>
                    <input type="text" wire:model="cpFiscal"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                           placeholder="00000" maxlength="5">
                    @error('cpFiscal') <p class="text-[10px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Correo Electrónico</label>
                    <input type="email" wire:model="correoFactura"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                           placeholder="correo@ejemplo.com">
                    @error('correoFactura') <p class="text-[10px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Uso del CFDI</label>
                    <select wire:model="usoCfdi" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300">
                        <option value="G01">G01 — Adquisición de mercancias</option>
                        <option value="G03">G03 — Gastos en general</option>
                        <option value="P01">P01 — Por definir</option>
                        <option value="S01">S01 — Sin efectos fiscales</option>
                    </select>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Método de Pago</label>
                    <select wire:model="metodoPagoFiscal" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300">
                        <option value="PUE">PUE — Pago en una sola exhibición</option>
                        <option value="PPD">PPD — Pago en parcialidades o diferido</option>
                    </select>
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Dirección Fiscal (si es diferente al domicilio de instalación)</label>
                    <input type="text" wire:model="direccionFiscal"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                           placeholder="Calle, número, colonia, municipio...">
                </div>
            </div>
            @endif
        </div>

        {{-- Datos Personales --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-user-line text-red-500"></i> Datos Personales
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Nombre(s) <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="nombre"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300 uppercase font-bold"
                           placeholder="NOMBRE(S)">
                    @error('nombre') <p class="text-[10px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Apellido Paterno <span class="text-red-500">*</span></label>
                    <input type="text" wire:model.live="apellidoPaterno"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300 uppercase font-bold"
                           placeholder="APELLIDO PATERNO">
                    @error('apellidoPaterno') <p class="text-[10px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Apellido Materno</label>
                    <input type="text" wire:model.live="apellidoMaterno"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300 uppercase font-bold"
                           placeholder="APELLIDO MATERNO">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Teléfono <span class="text-red-500">*</span></label>
                    <input type="tel" wire:model="telefono"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                           placeholder="10 dígitos" maxlength="10">
                    @error('telefono') <p class="text-[10px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">CURP</label>
                    <input type="text" wire:model="curp"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300 uppercase"
                           placeholder="18 caracteres" maxlength="18">
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Correo Electrónico</label>
                    <input type="email" wire:model="correo"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                           placeholder="correo@ejemplo.com">
                </div>
            </div>
        </div>

        {{-- Dirección del Servicio --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-map-pin-line text-red-500"></i> Dirección del Servicio
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Sucursal <span class="text-red-500">*</span></label>
                    <select wire:model.live="sucursalId"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300">
                        <option value="">— Seleccionar —</option>
                        @foreach($sucursales as $s)
                            <option value="{{ $s['id'] }}">{{ $s['nombre'] }}</option>
                        @endforeach
                    </select>
                    @error('sucursalId') <p class="text-[10px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Estado</label>
                    <input type="text" value="{{ $estadoNombre ?: '— auto —' }}"
                           class="w-full border border-gray-100 bg-gray-50 rounded-lg px-3 py-2 text-xs text-gray-500" readonly>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Municipio</label>
                    <input type="text" value="{{ $municipioNombre ?: '— auto —' }}"
                           class="w-full border border-gray-100 bg-gray-50 rounded-lg px-3 py-2 text-xs text-gray-500" readonly>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Localidad</label>
                    <input type="text" value="{{ $localidadNombre ?: '— auto —' }}"
                           class="w-full border border-gray-100 bg-gray-50 rounded-lg px-3 py-2 text-xs text-gray-500" readonly>
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Calle <span class="text-red-500">*</span></label>
                    <select wire:model="calleId"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                            @disabled(!$sucursalId)>
                        <option value="">{{ $sucursalId ? '— Seleccionar —' : '— Primero elige sucursal —' }}</option>
                        @foreach($calles as $c)
                            <option value="{{ $c['id'] }}">{{ $c['nombre_calle'] }}</option>
                        @endforeach
                    </select>
                    @error('calleId') <p class="text-[10px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Núm. Exterior <span class="text-red-500">*</span></label>
                    <input type="text" wire:model="numExt"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                           placeholder="Ej. 45">
                    @error('numExt') <p class="text-[10px] text-red-500 mt-0.5">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Núm. Interior</label>
                    <input type="text" wire:model="numInt"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                           placeholder="Opcional">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1">Referencias del Domicilio</label>
                    <input type="text" wire:model="referencias"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                           placeholder="Ej. Casa azul, frente a la primaria, portón negro">
                </div>
            </div>
            <div class="mt-3 flex items-start gap-2 text-[10px] text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-2.5">
                <i class="ri-information-line mt-0.5 flex-shrink-0"></i>
                <span>Los datos del suscriptor aún no se guardan. Se registrarán al confirmar el pago en el siguiente paso.</span>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button wire:click="$set('paso', 1)"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                <i class="ri-arrow-left-line"></i> Regresar
            </button>
            <button wire:click="irPaso3"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                Ir a Confirmación de Pago <i class="ri-arrow-right-line"></i>
            </button>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 3 — CONFIRMACIÓN DE PAGO
    ================================================================ --}}
    @if($paso === 3)
    <div class="space-y-5">

        {{-- Resumen del suscriptor --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-user-check-line text-red-500"></i> Resumen del Suscriptor
            </h3>
            @php
                $sucursalObj = collect($sucursales)->firstWhere('id', $sucursalId);
                $calleObj    = collect($calles)->firstWhere('id', $calleId);
            @endphp
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs">
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Nombre completo</p>
                    <p class="font-black text-gray-900">{{ trim("$nombre $apellidoPaterno $apellidoMaterno") }}</p>
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Teléfono</p>
                    <p class="font-bold text-gray-700">{{ $telefono }}</p>
                </div>
                @if($curp)
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">CURP</p>
                    <p class="font-bold text-gray-700 font-mono text-[10px]">{{ $curp }}</p>
                </div>
                @endif
                <div class="sm:col-span-2">
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Dirección del Servicio</p>
                    <p class="font-bold text-gray-700">
                        {{ $calleObj['nombre_calle'] ?? '—' }} #{{ $numExt }}{{ $numInt ? " Int.$numInt" : '' }},
                        {{ $localidadNombre }}, {{ $municipioNombre }}, {{ $estadoNombre }}
                    </p>
                    @if($referencias) <p class="text-[10px] text-gray-400 mt-0.5">Ref: {{ $referencias }}</p> @endif
                </div>
                <div>
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">Sucursal</p>
                    <p class="font-bold text-gray-700">{{ $sucursalObj['nombre'] ?? '—' }}</p>
                </div>
                @if($requiereFactura)
                <div class="sm:col-span-3 bg-blue-50 border border-blue-200 rounded-lg p-2.5">
                    <p class="text-[9px] text-blue-600 font-black uppercase tracking-wider mb-1">Datos de Facturación</p>
                    <p class="font-bold text-gray-800 text-xs">{{ $razonSocial }} · RFC: {{ $rfc }}</p>
                    <p class="text-[10px] text-gray-500">CP: {{ $cpFiscal }} · {{ $correoFactura }} · CFDI: {{ $usoCfdi }}</p>
                </div>
                @endif
            </div>
        </div>

        {{-- Servicio + Método de pago --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

            {{-- Resumen de pago --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-tv-line text-red-500"></i> Servicio y Pago
                </h3>
                <p class="text-sm font-black text-gray-900 mb-3">{{ $tarifaSeleccionada['nombre_comercial'] }}</p>

                <div class="space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Instalación</span>
                        <span class="font-bold">${{ number_format($tarifaSeleccionada['precio_instalacion'], 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Prorrateo ({{ $diasRestantes }} días)</span>
                        <span class="font-bold">${{ number_format($costoProrrateo, 2) }}</span>
                    </div>
                    <div class="border-t border-gray-100 pt-1 flex justify-between">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-bold">${{ number_format($subtotal, 2) }}</span>
                    </div>

                    @if($descuentoAplicado)
                    <div class="flex justify-between text-emerald-600">
                        <span class="font-bold">Descuento aplicado</span>
                        <span class="font-bold">— ${{ number_format($montoDescuento, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Subtotal con descuento</span>
                        <span class="font-bold">${{ number_format($subtotalDescuento, 2) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">IVA (16%)</span>
                        <span class="font-bold">${{ number_format($ivaDescuento, 2) }}</span>
                    </div>
                    @else
                    <div class="flex justify-between">
                        <span class="text-gray-500">IVA (16%)</span>
                        <span class="font-bold">${{ number_format($iva, 2) }}</span>
                    </div>
                    @endif

                    <div class="border-t-2 border-red-200 pt-2 flex justify-between">
                        <span class="font-black text-gray-900">Total a Cobrar</span>
                        <span class="font-black text-red-600 text-base">
                            ${{ $descuentoAplicado ? number_format($totalConDescuento, 2) : number_format($totalPagar, 2) }}
                        </span>
                    </div>
                </div>

                {{-- Descuento --}}
                <div class="mt-4 border-t border-gray-100 pt-3" x-data="{ open: {{ $mostrarDescuento ? 'true' : 'false' }} }">
                    @if($descuentoAplicado)
                        <div class="flex items-center justify-between bg-emerald-50 border border-emerald-200 rounded-lg p-2.5">
                            <span class="text-[10px] text-emerald-700 font-bold flex items-center gap-1.5">
                                <i class="ri-price-tag-3-line"></i> Descuento de ${{ number_format($montoDescuento, 2) }} aplicado
                            </span>
                            <button wire:click="quitarDescuento" class="text-[10px] text-red-500 font-bold hover:underline">
                                Quitar
                            </button>
                        </div>
                    @else
                        <button @click="open = !open"
                                class="text-[10px] text-gray-500 font-bold flex items-center gap-1.5 hover:text-red-600 transition-colors">
                            <i class="ri-percent-line"></i> Aplicar descuento de supervisor
                            <i class="ri-arrow-down-s-line transition-transform" :class="open && 'rotate-180'"></i>
                        </button>
                        <div x-show="open" x-cloak class="mt-2 space-y-2">
                            <div class="grid grid-cols-2 gap-2">
                                <div>
                                    <label class="block text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-1">Monto de descuento ($)</label>
                                    <div class="relative">
                                        <span class="absolute left-2.5 top-1/2 -translate-y-1/2 text-xs text-gray-400 font-bold">$</span>
                                        <input type="number" wire:model="montoDescuentoInput" min="0.01" step="0.01"
                                               class="w-full border border-gray-300 rounded-lg pl-6 pr-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                                               placeholder="0.00">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-[9px] text-gray-400 font-bold uppercase tracking-wider mb-1">Contraseña supervisor</label>
                                    <input type="password" wire:model="passwordDescuento"
                                           class="w-full border border-gray-300 rounded-lg px-3 py-1.5 text-xs focus:outline-none focus:ring-2 focus:ring-red-300"
                                           placeholder="••••••••"
                                           wire:keydown.enter="aplicarDescuento">
                                </div>
                            </div>
                            <button wire:click="aplicarDescuento"
                                    class="w-full py-1.5 bg-gray-900 hover:bg-gray-700 text-white text-[10px] font-black uppercase tracking-widest rounded-lg transition-all">
                                Aplicar Descuento
                            </button>
                        </div>
                    @endif
                </div>

                {{-- Cambiar servicio --}}
                <div class="mt-3 border-t border-gray-100 pt-3">
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider mb-1.5">Cambiar servicio antes del pago</p>
                    <select wire:change="cambiarServicio($event.target.value)"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-red-300 text-gray-600">
                        @foreach($tarifas as $t)
                            <option value="{{ $t['id'] }}" {{ $tarifaId == $t['id'] ? 'selected' : '' }}>
                                {{ $t['nombre_comercial'] }} — ${{ number_format($t['precio_mensualidad'], 2) }}/mes
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Método de pago --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-secure-payment-line text-red-500"></i> Método de Pago
                </h3>
                <div class="space-y-2">
                    @foreach([
                        'efectivo'     => ['icon' => 'ri-money-dollar-circle-line', 'label' => 'Efectivo'],
                        'transferencia'=> ['icon' => 'ri-bank-line',                'label' => 'Transferencia Bancaria'],
                        'tarjeta'      => ['icon' => 'ri-bank-card-line',           'label' => 'Tarjeta'],
                    ] as $val => $mp)
                    <button wire:click="seleccionarMetodoPago('{{ $val }}')"
                            class="w-full flex items-center gap-3 p-3 border rounded-xl transition-all text-left
                                {{ $metodoPago === $val
                                    ? 'border-red-500 bg-red-50 shadow-sm'
                                    : 'border-gray-200 hover:border-red-300 hover:bg-red-50' }}">
                        <i class="{{ $mp['icon'] }} text-lg {{ $metodoPago === $val ? 'text-red-600' : 'text-gray-400' }}"></i>
                        <span class="text-xs font-bold flex-1 {{ $metodoPago === $val ? 'text-red-700' : 'text-gray-700' }}">
                            {{ $mp['label'] }}
                        </span>
                        <i class="{{ $metodoPago === $val ? 'ri-checkbox-circle-fill text-red-500' : 'ri-checkbox-blank-circle-line text-gray-300' }}"></i>
                    </button>
                    @endforeach
                </div>

                <div class="mt-4 bg-amber-50 border border-amber-200 rounded-xl p-3">
                    <p class="text-[10px] text-amber-700 font-bold flex items-center gap-1.5 mb-1.5">
                        <i class="ri-alert-line"></i> Al confirmar el ingreso el sistema:
                    </p>
                    <ul class="text-[10px] text-amber-600 space-y-0.5 list-disc list-inside">
                        <li>Genera el # de suscriptor</li>
                        <li>Guarda el suscriptor en la base de datos</li>
                        <li>Registra el ingreso en caja de la sucursal</li>
                        <li>Genera el recibo digital</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button wire:click="$set('paso', 2)"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                <i class="ri-arrow-left-line"></i> Regresar
            </button>
            <div class="flex items-center gap-3">
                <button x-data
                        @click="$confirm('¿Cancelar la contratación? No se guardará ningún registro.', () => $wire.set('paso', 1), { title: 'Cancelar contratación', confirmText: 'Sí, cancelar', icon: 'warning' })"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-red-300 text-red-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-50 transition-all">
                    <i class="ri-close-line"></i> Cancelar
                </button>
                <button wire:click="confirmarIngreso"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                    <i class="ri-check-double-line"></i> Confirmar Ingreso
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 4 — RECIBO DIGITAL
    ================================================================ --}}
    @if($paso === 4)
    <div class="space-y-5">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

            <div class="bg-gray-900 text-white p-5 flex items-center justify-between">
                <div>
                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Recibo Digital de Pago</p>
                    <p class="text-lg font-black tracking-tight">TU VISIÓN TELECABLE</p>
                    <p class="text-[10px] text-gray-400 mt-0.5">RFC: XAXX010101000 · Av. Principal #123, Veracruz, México</p>
                </div>
                <div class="text-right">
                    <p class="text-[9px] text-gray-400 uppercase tracking-wider">No. Recibo</p>
                    <p class="text-sm font-black text-red-400">{{ $folioRecibo }}</p>
                    <p class="text-[10px] text-gray-400 mt-1">{{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="p-5 space-y-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-2">Suscriptor</p>
                        <p class="text-sm font-black text-gray-900">{{ trim("$nombre $apellidoPaterno $apellidoMaterno") }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">Tel: {{ $telefono }}</p>
                        @php $calleObj = collect($calles)->firstWhere('id', $calleId); @endphp
                        <p class="text-xs text-gray-600 mt-0.5">
                            {{ $calleObj['nombre_calle'] ?? '' }} #{{ $numExt }}{{ $numInt ? " Int.$numInt" : '' }}<br>
                            {{ $localidadNombre }}, {{ $municipioNombre }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[9px] font-black uppercase tracking-widest text-gray-400 mb-2">No. de Suscriptor</p>
                        <div class="inline-flex items-center gap-2 bg-red-50 border border-red-200 rounded-xl px-4 py-2">
                            <i class="ri-user-star-line text-red-500"></i>
                            <span class="text-base font-black text-red-700 tracking-widest">{{ $numeroSuscriptor }}</span>
                        </div>
                        <p class="text-[10px] text-gray-400 mt-2">
                            Estado: <span class="font-bold text-amber-600">PENDIENTE DE INSTALACIÓN</span>
                        </p>
                        <p class="text-[10px] text-gray-400 mt-0.5">
                            Forma de pago: <span class="font-bold text-gray-600 uppercase">{{ $metodoPago }}</span>
                        </p>
                    </div>
                </div>

                <div class="border border-gray-200 rounded-xl overflow-hidden">
                    <table class="w-full text-xs">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left px-4 py-2 text-[9px] font-black uppercase tracking-widest text-gray-500">Concepto</th>
                                <th class="text-right px-4 py-2 text-[9px] font-black uppercase tracking-widest text-gray-500">Importe</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-4 py-2.5 text-gray-700">Cargo de instalación — {{ $tarifaSeleccionada['nombre_comercial'] }}</td>
                                <td class="px-4 py-2.5 text-right font-bold">
                                    ${{ number_format($tarifaSeleccionada['precio_instalacion'], 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2.5 text-gray-700">
                                    Prorrateo mensualidad ({{ $diasRestantes }} días — {{ now()->format('d/m') }} al 30/{{ now()->format('m') }})
                                </td>
                                <td class="px-4 py-2.5 text-right font-bold">${{ number_format($costoProrrateo, 2) }}</td>
                            </tr>
                            @if($descuentoAplicado)
                            <tr class="text-emerald-600">
                                <td class="px-4 py-2.5 font-bold">Descuento aplicado</td>
                                <td class="px-4 py-2.5 text-right font-bold">— ${{ number_format($montoDescuento, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                        <tfoot class="bg-gray-50 text-xs">
                            <tr>
                                <td class="px-4 py-2 text-gray-500">Subtotal (sin IVA)</td>
                                <td class="px-4 py-2 text-right font-bold">
                                    ${{ $descuentoAplicado ? number_format($subtotalDescuento, 2) : number_format($subtotal, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-gray-500">IVA (16%)</td>
                                <td class="px-4 py-2 text-right font-bold">
                                    ${{ $descuentoAplicado ? number_format($ivaDescuento, 2) : number_format($iva, 2) }}
                                </td>
                            </tr>
                            <tr class="bg-gray-900 text-white">
                                <td class="px-4 py-3 font-black uppercase text-sm">Total Cobrado</td>
                                <td class="px-4 py-3 text-right font-black text-base">
                                    ${{ $descuentoAplicado ? number_format($totalConDescuento, 2) : number_format($totalPagar, 2) }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($requiereFactura)
                <div class="flex items-center gap-2 text-[10px] text-blue-600 bg-blue-50 border border-blue-200 rounded-lg p-2.5">
                    <i class="ri-file-paper-2-line"></i>
                    <span>Factura CFDI solicitada — pendiente de timbrado vía Facturama</span>
                </div>
                @endif
            </div>

            <div class="border-t border-gray-200 px-5 py-4 flex items-center justify-between bg-gray-50">
                <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                    <i class="ri-printer-line"></i> Imprimir Recibo
                </button>
                <button wire:click="$set('paso', 5)"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                    Continuar — Firma del Contrato <i class="ri-arrow-right-line"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 5 — FIRMA DIGITAL DEL CONTRATO
    ================================================================ --}}
    @if($paso === 5)
    <div class="space-y-5">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden"
             x-data="{
                 drawing: false, vacio: true,
                 canvas: null, ctx: null,
                 lastX: 0, lastY: 0,
                 init() {
                     this.canvas = this.$refs.firmaCanvas;
                     this.ctx = this.canvas.getContext('2d');
                     this.ctx.strokeStyle = '#1e293b';
                     this.ctx.lineWidth = 2;
                     this.ctx.lineCap = 'round';
                     this.ctx.lineJoin = 'round';
                 },
                 getPos(e) {
                     const rect = this.canvas.getBoundingClientRect();
                     const src = e.touches ? e.touches[0] : e;
                     return {
                         x: (src.clientX - rect.left) * (this.canvas.width / rect.width),
                         y: (src.clientY - rect.top)  * (this.canvas.height / rect.height),
                     };
                 },
                 startDraw(e) { e.preventDefault(); this.drawing = true; const p = this.getPos(e); this.lastX = p.x; this.lastY = p.y; },
                 draw(e) {
                     if (!this.drawing) return; e.preventDefault();
                     const p = this.getPos(e);
                     this.ctx.beginPath(); this.ctx.moveTo(this.lastX, this.lastY);
                     this.ctx.lineTo(p.x, p.y); this.ctx.stroke();
                     this.lastX = p.x; this.lastY = p.y; this.vacio = false;
                 },
                 stopDraw() { this.drawing = false; },
                 limpiar() { this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height); this.vacio = true; },
                 confirmar() {
                     if (this.vacio) { alert('Por favor dibuja tu firma para continuar.'); return; }
                     $wire.guardarFirma(this.canvas.toDataURL('image/png'));
                 }
             }">

            <div class="p-5 border-b border-gray-200">
                <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-3 flex items-center gap-2">
                    <i class="ri-file-list-3-line text-red-500"></i> Contrato de Prestación de Servicios
                </h3>
                @php $calleObj2 = collect($calles)->firstWhere('id', $calleId); @endphp
                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-xs text-gray-700 space-y-2 max-h-48 overflow-y-auto leading-relaxed">
                    <p class="font-black uppercase">Tu Visión Telecable — Contrato de Prestación de Servicios</p>
                    <p>Por medio del presente instrumento, <strong>Tu Visión Telecable</strong> y
                    <strong>{{ trim("$nombre $apellidoPaterno $apellidoMaterno") }}</strong>
                    acuerdan la prestación del servicio de
                    <strong>{{ $tarifaSeleccionada['nombre_comercial'] }}</strong>
                    en el domicilio:
                    <strong>{{ $calleObj2['nombre_calle'] ?? '' }} #{{ $numExt }}{{ $numInt ? " Int.$numInt" : '' }}, {{ $localidadNombre }}, {{ $municipioNombre }}, {{ $estadoNombre }}</strong>.</p>
                    <p>Mensualidad acordada: <strong>${{ number_format($tarifaSeleccionada['precio_mensualidad'], 2) }} MXN</strong> más IVA.
                    Fecha de contratación: <strong>{{ now()->format('d/m/Y') }}</strong>.</p>
                    <p>El suscriptor acepta los términos y condiciones del servicio, incluyendo las políticas de uso, suspensión y cancelación de Tu Visión Telecable.</p>
                    <p>No. de Suscriptor asignado: <strong class="text-red-600">{{ $numeroSuscriptor }}</strong>.</p>
                    <p class="text-gray-400 text-[10px]">Fecha de registro: {{ now()->format('d/m/Y H:i') }} · TVT RFC: XAXX010101000</p>
                </div>
            </div>

            <div class="p-5">
                <p class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1.5 flex items-center gap-2">
                    <i class="ri-pen-nib-line text-red-500"></i> Firma Autógrafa Digital del Suscriptor
                </p>
                <p class="text-xs text-gray-400 mb-3">Dibuja tu firma en el recuadro usando mouse, dedo o lápiz óptico.</p>
                <div class="border-2 border-dashed border-gray-300 rounded-xl overflow-hidden bg-gray-50 hover:border-red-300 transition-colors">
                    <canvas x-ref="firmaCanvas" width="800" height="220"
                            class="w-full cursor-crosshair touch-none"
                            @mousedown="startDraw($event)" @mousemove="draw($event)"
                            @mouseup="stopDraw()" @mouseleave="stopDraw()"
                            @touchstart="startDraw($event)" @touchmove="draw($event)" @touchend="stopDraw()">
                    </canvas>
                </div>
                <p class="text-[10px] text-gray-400 mt-1.5 flex items-center gap-1">
                    <i class="ri-information-line"></i> La firma quedará almacenada en el expediente digital del suscriptor.
                </p>
            </div>

            <div class="border-t border-gray-200 px-5 py-4 flex items-center justify-between bg-gray-50">
                <button @click="limpiar()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                    <i class="ri-eraser-line"></i> Limpiar Firma
                </button>
                <button @click="confirmar()"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                    <i class="ri-check-double-line"></i> Confirmar Firma y Continuar
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 6 — ASIGNACIÓN DE TÉCNICO + DATOS PREVIOS DEL REPORTE
    ================================================================ --}}
    @if($paso === 6)
    <div class="space-y-5">

        {{-- Datos previos del reporte (autogenerados) --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-file-list-3-line text-red-500"></i> Datos Previos del Reporte de Instalación
            </h3>
            <p class="text-xs text-gray-400 mb-4">
                El sistema generará automáticamente un reporte de servicio de tipo
                <span class="font-black text-gray-700 uppercase">INSTALACIÓN</span>.
                Los siguientes datos se cargarán desde el expediente del suscriptor.
            </p>

            @php
                $sucursalObj2 = collect($sucursales)->firstWhere('id', $sucursalId);
                $calleObj3    = collect($calles)->firstWhere('id', $calleId);
                $colorTipo = match($tipoServicio) {
                    'TV+INTERNET' => 'bg-purple-100 text-purple-700 border-purple-200',
                    'INTERNET'    => 'bg-blue-100 text-blue-700 border-blue-200',
                    default       => 'bg-orange-100 text-orange-700 border-orange-200',
                };
                $iconTipo = match($tipoServicio) {
                    'TV+INTERNET' => 'ri-broadband-line',
                    'INTERNET'    => 'ri-wifi-line',
                    default       => 'ri-tv-line',
                };
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Tipo de reporte --}}
                <div class="sm:col-span-2 flex flex-wrap gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black border bg-red-100 text-red-700 border-red-200">
                        <i class="ri-tools-line"></i> TIPO: INSTALACIÓN
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black border {{ $colorTipo }}">
                        <i class="{{ $iconTipo }}"></i> SERVICIO: {{ $tipoServicio }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black border bg-amber-100 text-amber-700 border-amber-200">
                        <i class="ri-time-line"></i> ESTADO: PENDIENTE
                    </span>
                </div>

                {{-- Suscriptor --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-2">Suscriptor</p>
                    <div class="space-y-1 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-400 w-16 flex-shrink-0">ID</span>
                            <span class="font-black text-red-600 tracking-widest">{{ $numeroSuscriptor }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-400 w-16 flex-shrink-0">Nombre</span>
                            <span class="font-bold text-gray-800">{{ trim("$nombre $apellidoPaterno $apellidoMaterno") }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-400 w-16 flex-shrink-0">Teléfono</span>
                            <span class="font-bold text-gray-700">{{ $telefono }}</span>
                        </div>
                    </div>
                </div>

                {{-- Domicilio --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-2">Domicilio de Instalación</p>
                    <div class="space-y-1 text-xs">
                        <div class="flex items-start gap-2">
                            <span class="text-[9px] text-gray-400 w-16 flex-shrink-0">Dirección</span>
                            <span class="font-bold text-gray-800">
                                {{ $calleObj3['nombre_calle'] ?? '—' }} #{{ $numExt }}{{ $numInt ? " Int.$numInt" : '' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-400 w-16 flex-shrink-0">Localidad</span>
                            <span class="font-bold text-gray-700">{{ $localidadNombre }}, {{ $municipioNombre }}</span>
                        </div>
                        @if($referencias)
                        <div class="flex items-start gap-2">
                            <span class="text-[9px] text-gray-400 w-16 flex-shrink-0">Refs.</span>
                            <span class="text-gray-600">{{ $referencias }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Servicio contratado --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-2">Servicio Contratado</p>
                    <div class="space-y-1 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-400 w-20 flex-shrink-0">Tarifa</span>
                            <span class="font-bold text-gray-800">{{ $tarifaSeleccionada['nombre_comercial'] }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-400 w-20 flex-shrink-0">Mensualidad</span>
                            <span class="font-bold text-gray-700">${{ number_format($tarifaSeleccionada['precio_mensualidad'], 2) }}/mes</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-400 w-20 flex-shrink-0">Cobrado</span>
                            <span class="font-bold text-gray-700">
                                ${{ $descuentoAplicado ? number_format($totalConDescuento, 2) : number_format($totalPagar, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Sucursal + fecha --}}
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3">
                    <p class="text-[9px] text-gray-400 font-black uppercase tracking-widest mb-2">Sucursal y Fecha</p>
                    <div class="space-y-1 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-400 w-16 flex-shrink-0">Sucursal</span>
                            <span class="font-bold text-gray-800">{{ $sucursalObj2['nombre'] ?? '—' }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-400 w-16 flex-shrink-0">Creado</span>
                            <span class="font-bold text-gray-700">{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-[9px] text-gray-400 w-16 flex-shrink-0">Recibo</span>
                            <span class="font-bold text-gray-600">{{ $folioRecibo }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Nota sobre equipo --}}
            @php
                $msgEquipo = match($tipoServicio) {
                    'TV+INTERNET' => 'La asignación de ONU (nombre WiFi, contraseña, VLAN, encapsulamiento) se realizará dentro del reporte de servicio.',
                    'INTERNET'    => 'La asignación de ONU (nombre WiFi, contraseña, VLAN, encapsulamiento) se realizará dentro del reporte de servicio.',
                    default       => 'La asignación del Mininodo se realizará dentro del reporte de servicio.',
                };
            @endphp
            <div class="mt-3 flex items-start gap-2 text-[10px] text-blue-700 bg-blue-50 border border-blue-200 rounded-lg p-2.5">
                <i class="ri-information-line mt-0.5 flex-shrink-0"></i>
                <span>{{ $msgEquipo }}</span>
            </div>
        </div>

        {{-- Selección del técnico --}}
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-5">
            <h3 class="text-[10px] font-black uppercase tracking-widest text-gray-500 mb-4 flex items-center gap-2">
                <i class="ri-user-star-line text-red-500"></i> Asignación de Técnico <span class="text-red-500">*</span>
            </h3>

            @if(empty($tecnicos))
                <div class="text-center py-6 text-gray-400">
                    <i class="ri-user-unfollow-line text-2xl mb-2 block"></i>
                    <p class="text-xs">No hay técnicos activos registrados.</p>
                    <p class="text-[10px] mt-0.5">Registra empleados del área Técnica en el módulo de RRHH.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                    @foreach($tecnicos as $tec)
                    <button wire:click="$set('tecnicoId', {{ $tec['id'] }})"
                            class="text-left p-3 rounded-xl border-2 transition-all
                                {{ $tecnicoId == $tec['id']
                                    ? 'border-red-500 bg-red-50'
                                    : 'border-gray-200 hover:border-red-300 hover:bg-red-50' }}">
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0
                                {{ $tecnicoId == $tec['id'] ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-500' }}">
                                <i class="ri-user-line text-sm"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-black text-gray-900 truncate">{{ $tec['nombre'] }}</p>
                                <p class="text-[9px] text-gray-500">{{ $tec['clave'] }} · {{ $tec['puesto'] }}</p>
                            </div>
                            @if($tecnicoId == $tec['id'])
                                <i class="ri-checkbox-circle-fill text-red-500 ml-auto flex-shrink-0"></i>
                            @endif
                        </div>
                    </button>
                    @endforeach
                </div>
            @endif

            <div class="mt-3 flex items-start gap-2 text-[10px] text-amber-700 bg-amber-50 border border-amber-200 rounded-lg p-2.5">
                <i class="ri-information-line mt-0.5 flex-shrink-0"></i>
                <span>El técnico seleccionado recibirá una notificación por SMS con el folio del reporte. El cambio de técnico solo se puede realizar desde el reporte de servicio, con contraseña de sucursal.</span>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button wire:click="$set('paso', 5)"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                <i class="ri-arrow-left-line"></i> Regresar
            </button>
            <button wire:click="generarReporte"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                <i class="ri-file-add-line"></i> Generar Reporte de Instalación
            </button>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 7 — PROCESO COMPLETADO
    ================================================================ --}}
    @if($paso === 7)
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="text-center py-14 px-8">
            <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <i class="ri-check-double-line text-4xl text-emerald-600"></i>
            </div>
            <h3 class="text-xl font-black text-gray-900 uppercase tracking-tight mb-1">Contratación Completada</h3>
            <p class="text-sm text-gray-500 mb-6">El suscriptor fue registrado, el contrato firmado y el reporte de instalación fue enviado al técnico.</p>

            <div class="flex flex-col sm:flex-row gap-3 items-center justify-center mb-6">
                <div class="inline-flex flex-col items-center bg-emerald-50 border border-emerald-200 rounded-2xl px-6 py-4">
                    <p class="text-[9px] text-emerald-600 font-black uppercase tracking-widest mb-1">No. de Suscriptor</p>
                    <p class="text-2xl font-black text-emerald-700 tracking-widest">{{ $numeroSuscriptor }}</p>
                    <p class="text-[10px] text-emerald-500 mt-1 font-bold uppercase">Pendiente de Instalación</p>
                </div>
                <div class="inline-flex flex-col items-center bg-red-50 border border-red-200 rounded-2xl px-6 py-4">
                    <p class="text-[9px] text-red-600 font-black uppercase tracking-widest mb-1">Reporte de Instalación</p>
                    <p class="text-2xl font-black text-red-700 tracking-widest">{{ $folioReporte }}</p>
                    <p class="text-[10px] text-red-500 mt-1 font-bold uppercase">{{ $tipoServicio }} · Pendiente</p>
                </div>
            </div>

            <p class="text-xs text-gray-400 mb-6">
                Servicio: <span class="font-bold text-gray-600">{{ $tarifaSeleccionada['nombre_comercial'] }}</span> ·
                Recibo: <span class="font-bold text-gray-600">{{ $folioRecibo }}</span>
            </p>

            <div class="flex items-center justify-center gap-3 flex-wrap">
                <button onclick="window.print()"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-300 text-gray-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-gray-50 transition-all">
                    <i class="ri-printer-line"></i> Imprimir Recibo
                </button>
                <a href="{{ route('reportes-servicio') }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-red-300 text-red-600 font-bold text-xs uppercase tracking-widest rounded-xl hover:bg-red-50 transition-all">
                    <i class="ri-file-list-3-line"></i> Ver Reporte Generado
                </a>
                <a href="{{ route('contrataciones-nuevas') }}"
                   class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-black text-xs uppercase tracking-widest rounded-xl shadow transition-all">
                    <i class="ri-add-line"></i> Nueva Contratación
                </a>
            </div>
        </div>
    </div>
    @endif

</div>
