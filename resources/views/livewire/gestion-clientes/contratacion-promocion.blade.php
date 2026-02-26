<div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

    {{-- ENCABEZADO --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-start justify-between gap-3">
        <div>
            <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1.5">
                <i class="ri-home-4-line text-indigo-400"></i>
                <span>Gestión al Cliente</span>
                <i class="ri-arrow-right-s-line"></i>
                <span class="text-orange-600">Promociones</span>
            </div>
            <h2 class="text-xl font-black text-gray-900 tracking-tight uppercase">
                Contratación de <span class="text-orange-600">Promociones</span>
            </h2>
            <p class="text-xs text-gray-400 mt-0.5">Pago anticipado con beneficios de meses gratis (6×7, 12×14)</p>
        </div>
        <a href="{{ route('dashboard') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-600 font-black text-[10px] uppercase tracking-widest rounded-lg shadow-sm hover:bg-gray-50 transition-all group self-start">
            <i class="ri-arrow-left-line group-hover:-translate-x-0.5 transition-transform"></i> Panel Principal
        </a>
    </div>

    {{-- ================================================================
         PASO 1 — IDENTIFICAR CLIENTE
    ================================================================ --}}
    @if($paso == 1)
    <div class="max-w-2xl mx-auto">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="bg-orange-50 border-b border-orange-100 px-6 py-5 text-center">
                <div class="w-14 h-14 bg-white border border-orange-100 rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-3">
                    <i class="ri-star-smile-line text-orange-500 text-2xl"></i>
                </div>
                <p class="text-[11px] font-black text-orange-800 uppercase tracking-widest">Identificar Cliente para Aplicar Promoción</p>
                <p class="text-[10px] text-orange-500 mt-1 font-medium">Busque por nombre, ID o teléfono</p>
            </div>
            <div class="p-5 space-y-4">
                <div class="flex gap-3">
                    <div class="relative flex-1">
                        <i class="ri-user-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-base"></i>
                        <input type="text" wire:model="search"
                               placeholder="Nombre, ID o teléfono..."
                               wire:keydown.enter="buscarCliente"
                               class="w-full pl-9 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-lg text-sm font-medium focus:ring-2 focus:ring-orange-500/20 focus:border-orange-400 transition-colors placeholder:text-gray-300">
                    </div>
                    <button wire:click="buscarCliente"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-black transition-all active:scale-95 shadow-sm">
                        Consultar
                    </button>
                </div>

                @if($cliente)
                <div class="border-2 border-orange-200 bg-orange-50/40 rounded-xl p-4 space-y-3">
                    <span class="text-[9px] font-black text-orange-600 uppercase tracking-widest bg-orange-100 border border-orange-200 px-2 py-1 rounded-md">
                        Cliente encontrado
                    </span>
                    <div>
                        <p class="text-base font-black text-gray-900 uppercase tracking-tight">{{ $clienteEncontrado['nombre'] }}</p>
                        <p class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 uppercase mt-1">
                            <i class="ri-broadcast-line text-indigo-400"></i> {{ $clienteEncontrado['servicio'] }}
                        </p>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            <span class="text-[9px] font-black text-emerald-700 uppercase tracking-widest">{{ $clienteEncontrado['estado'] }}</span>
                        </div>
                    </div>
                    <button wire:click="irAPaso(2)"
                            class="w-full py-3 bg-orange-500 text-white rounded-lg font-black text-xs uppercase tracking-widest hover:bg-orange-600 shadow-sm shadow-orange-200 transition-all active:scale-95 flex items-center justify-center gap-2">
                        Seleccionar Cliente <i class="ri-arrow-right-line"></i>
                    </button>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    {{-- ================================================================
         PASO 2 — SELECCIÓN DE PROMO + LIQUIDACIÓN
    ================================================================ --}}
    @if($paso == 2)
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Columna izquierda: expediente + selección promo --}}
        <div class="lg:col-span-4 space-y-4">

            {{-- Expediente --}}
            <div class="bg-gray-900 rounded-xl p-5 text-white shadow-sm relative overflow-hidden">
                <div class="absolute -right-4 -top-4 opacity-10"><i class="ri-user-star-line text-8xl"></i></div>
                <p class="text-[9px] font-black text-orange-400 uppercase tracking-widest mb-3 border-b border-gray-800 pb-2">Expediente Actual</p>
                <div class="space-y-3 text-xs relative z-10">
                    <div>
                        <p class="text-[9px] text-gray-500 uppercase font-bold mb-0.5">Titular</p>
                        <p class="font-black text-white uppercase tracking-tight text-sm">{{ $cliente['nombre'] }}</p>
                    </div>
                    <div>
                        <p class="text-[9px] text-gray-500 uppercase font-bold mb-0.5">Servicio</p>
                        <p class="font-bold text-indigo-400 uppercase">{{ $cliente['servicio'] }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span class="text-[9px] font-black text-emerald-400 uppercase tracking-widest">{{ $cliente['estado'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Selección de promo --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="bg-gray-50 border-b border-gray-200 px-4 py-3">
                    <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Seleccione una Promoción</p>
                </div>
                <div class="p-3 space-y-2">
                    @foreach($promociones as $p)
                    <button wire:click="seleccionarPromo({{ $p['id'] }})"
                            class="w-full p-4 rounded-xl border-2 transition-all text-left group
                                   {{ $promoSeleccionada && $promoSeleccionada['id'] == $p['id']
                                       ? 'border-orange-500 bg-orange-50 shadow-sm'
                                       : 'border-gray-100 hover:border-orange-200 hover:bg-orange-50/30' }}">
                        <div class="flex justify-between items-center">
                            <p class="text-[11px] font-black uppercase {{ $promoSeleccionada && $promoSeleccionada['id'] == $p['id'] ? 'text-orange-700' : 'text-gray-700' }}">
                                {{ $p['nombre'] }}
                            </p>
                            <div class="w-4 h-4 rounded-full border-2 flex items-center justify-center flex-shrink-0
                                        {{ $promoSeleccionada && $promoSeleccionada['id'] == $p['id'] ? 'border-orange-500 bg-orange-500' : 'border-gray-300' }}">
                                @if($promoSeleccionada && $promoSeleccionada['id'] == $p['id'])
                                    <i class="ri-check-line text-white text-[9px]"></i>
                                @endif
                            </div>
                        </div>
                        <p class="text-[9px] text-gray-400 font-bold uppercase mt-1">
                            Paga {{ $p['meses_pago'] }} meses — Recibe {{ $p['meses_beneficio'] }} meses
                        </p>
                        <div class="flex items-center gap-1.5 mt-2">
                            <span class="text-[9px] font-black text-orange-500 bg-orange-50 border border-orange-100 px-1.5 py-0.5 rounded uppercase tracking-widest">
                                +{{ $p['meses_beneficio'] - $p['meses_pago'] }} mes gratis
                            </span>
                        </div>
                    </button>
                    @endforeach
                </div>
            </div>

            <button wire:click="irAPaso(1)"
                    class="text-[10px] font-black text-gray-400 hover:text-gray-700 uppercase tracking-widest transition-colors flex items-center gap-1.5 px-1">
                <i class="ri-arrow-left-line"></i> Cambiar cliente
            </button>
        </div>

        {{-- Columna derecha: desglose de liquidación --}}
        <div class="lg:col-span-8">

            @if(!$promoSeleccionada)
            <div class="bg-white border-2 border-dashed border-gray-200 rounded-xl h-full flex items-center justify-center py-24">
                <div class="text-center">
                    <i class="ri-calculator-line text-5xl text-gray-100 block mb-3"></i>
                    <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest">Seleccione una promoción para ver el desglose</p>
                </div>
            </div>

            @else
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

                <div class="bg-gray-50 border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-black text-gray-800 uppercase tracking-widest">Liquidación de Pago Anticipado</p>
                        <p class="text-[10px] text-orange-500 font-bold uppercase mt-0.5">{{ $promoSeleccionada['nombre'] }}</p>
                    </div>
                    <span class="text-[9px] font-black text-orange-600 bg-orange-50 border border-orange-100 px-3 py-1.5 rounded-lg uppercase tracking-widest">
                        {{ $promoSeleccionada['meses_pago'] }}×{{ $promoSeleccionada['meses_beneficio'] }}
                    </span>
                </div>

                <div class="p-6 space-y-5">

                    {{-- Cards de desglose --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="bg-gray-50 border border-gray-100 rounded-xl p-5">
                            <div class="flex items-start justify-between mb-3">
                                <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest leading-tight">Días de Uso<br>(Prorrateo)</p>
                                <span class="text-[9px] font-black text-indigo-600 bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-md uppercase">{{ $calculos['dias_uso'] }} días</span>
                            </div>
                            <p class="text-2xl font-black text-gray-900 tracking-tight">${{ number_format($calculos['importe_dias'], 2) }}</p>
                            <p class="text-[9px] text-gray-400 font-medium mt-1">Para iniciar el día 1 del siguiente mes</p>
                        </div>

                        <div class="bg-orange-50 border border-orange-100 rounded-xl p-5">
                            <p class="text-[10px] font-black text-orange-700 uppercase tracking-widest mb-3">Importe de la Promoción</p>
                            <p class="text-2xl font-black text-orange-600 tracking-tight">${{ number_format($calculos['importe_promo'], 2) }}</p>
                            <p class="text-[9px] text-orange-400 font-medium mt-1">{{ $promoSeleccionada['meses_pago'] }} meses de renta pagados</p>
                        </div>
                    </div>

                    {{-- Vigencia --}}
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                        <div class="bg-gray-50 border-b border-gray-200 px-5 py-3">
                            <p class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Vigencia Proyectada</p>
                        </div>
                        <div class="grid grid-cols-3 divide-x divide-gray-100">
                            <div class="px-5 py-4">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Aplica desde</p>
                                <p class="text-sm font-black text-gray-800 uppercase">{{ $calculos['fecha_inicio'] }}</p>
                            </div>
                            <div class="px-5 py-4">
                                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-1">Término promo</p>
                                <p class="text-sm font-black text-red-600 uppercase">{{ $calculos['fecha_termino'] }}</p>
                            </div>
                            <div class="px-5 py-4">
                                <p class="text-[9px] font-bold text-indigo-500 uppercase tracking-widest mb-1">Próximo pago</p>
                                <p class="text-sm font-black text-indigo-700 uppercase">{{ $calculos['proximo_pago'] }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Total --}}
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-5 bg-gray-900 rounded-xl px-6 py-5">
                        <div>
                            <p class="text-[9px] font-black text-orange-400 uppercase tracking-widest mb-1">Total a Liquidar</p>
                            <p class="text-3xl font-black text-white tracking-tight">
                                ${{ number_format($calculos['total'], 2) }}
                            </p>
                        </div>
                        <button wire:click="confirmarContratacion"
                                class="w-full sm:w-auto px-8 py-3.5 bg-orange-500 text-white rounded-xl font-black text-xs uppercase tracking-widest hover:bg-orange-600 shadow-lg shadow-orange-900/20 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i class="ri-shield-check-line text-base"></i> Confirmar y Activar Promo
                        </button>
                    </div>

                    <p class="text-center text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                        El estatus cambiará automáticamente a
                        <span class="text-orange-500 font-black">PROMOCIÓN CONTRATADA</span>
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>