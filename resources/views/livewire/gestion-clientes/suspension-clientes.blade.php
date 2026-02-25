<div class="max-w-7xl mx-auto py-6 px-4">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800 tracking-tight text-center">Clientes con Adeudos (> 31 Días)</h2>
        <p class="text-sm text-gray-500 text-center">Listado generado automáticamente para procesos de suspensión.</p>
    </div>

    @if (session()->has('mensaje'))
        <div class="mb-4 p-4 bg-indigo-100 text-indigo-700 rounded-lg border border-indigo-200">
            {{ session('mensaje') }}
        </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase"># Cliente</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Nombre / Sucursal</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Servicio</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Saldo Actual</th>
                        <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase">Último Pago</th>
                        <th class="px-6 py-3 text-center text-[10px] font-bold text-gray-500 uppercase">Acción</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($clientes as $cliente)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono font-bold text-indigo-600">{{ $cliente['id'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 uppercase">{{ $cliente['nombre'] }}</div>
                                <div class="text-xs text-gray-500">{{ $cliente['sucursal'] }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-700">{{ $cliente['servicio'] }}</div>
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-green-100 text-green-800 uppercase">{{ $cliente['estatus'] }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-black text-red-600">${{ number_format($cliente['saldo'], 2) }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $cliente['ultimo_pago'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="generarReporteSuspension('{{ $cliente['id'] }}')" 
                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 border border-red-200 rounded-md text-xs font-bold hover:bg-red-100 transition shadow-sm">
                                    <i class="ri-error-warning-line mr-1.5"></i> Generar Suspensión
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>