<x-app-layout>
    <div x-data="{ activeTab: 'resumen' }" class="w-full">
        
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">Centro de Operaciones Diario</h2>
            <div class="text-sm text-gray-500">
                <i class="ri-calendar-line"></i> {{ now()->format('d M Y') }}
            </div>
        </div>

<div class="bg-white border-b border-gray-200 rounded-t-lg shadow-sm">
            <nav class="flex -mb-px px-4">
                <button @click="activeTab = 'gestion_clientes'" 
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'gestion_clientes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'gestion_clientes' }"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm flex items-center transition-colors duration-150">
                    <i class="ri-group-line mr-2 text-lg"></i> Gestión de Clientes
                </button>
                <button @click="activeTab = 'reportes'" 
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'reportes', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'reportes' }"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm flex items-center transition-colors duration-150">
                    <i class="ri-tools-fill mr-2 text-lg"></i> Reportes de Servicio
                </button>
                <button @click="activeTab = 'instalaciones'" 
                        :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'instalaciones', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300': activeTab !== 'instalaciones' }"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm flex items-center transition-colors duration-150">
                    <i class="ri-router-line mr-2 text-lg"></i> Instalaciones Pendientes
                </button>
            </nav>
        </div>

        <div class="bg-white p-6 rounded-b-lg shadow-sm min-h-[400px]">
            
            <div x-show="activeTab === 'gestion_clientes'" x-transition>
                <div class="mb-6 border-b border-gray-200 pb-4">
                    <h3 class="text-xl font-bold text-gray-800">Menú Gestión al Cliente</h3>
                    <p class="text-sm text-gray-500 mt-1">Selecciona el módulo de operación para comenzar.</p>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    
                    <a href="{{ route('contrataciones.nuevas') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all cursor-pointer group text-center">
                        <i class="ri-user-add-line text-3xl text-gray-400 group-hover:text-indigo-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Contrataciones Nuevas</span>
                    </a>

                    <a href="{{ route('servicios.adicionales') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all cursor-pointer group text-center">
                        <i class="ri-add-box-line text-3xl text-gray-400 group-hover:text-indigo-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Servicios Adicionales</span>
                    </a>

                    <a href="{{ route('pago.mensualidad') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all cursor-pointer group text-center">
                        <i class="ri-money-dollar-circle-line text-3xl text-gray-400 group-hover:text-indigo-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Pago Mensualidad</span>
                    </a>

                    <a href="{{ route('suspension.clientes') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all cursor-pointer group text-center">
                        <i class="ri-pause-circle-line text-3xl text-gray-400 group-hover:text-indigo-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Suspensión Falta de Pago</span>
                    </a>

                    <a href="{{ route('reconexion.cliente') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all cursor-pointer group text-center">
                        <i class="ri-plug-line text-3xl text-gray-400 group-hover:text-indigo-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Reconexión de Cliente</span>
                    </a>

                    <a href="{{ route('cancelacion.servicio') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-red-50 hover:border-red-300 hover:text-red-700 transition-all cursor-pointer group text-center">
                        <i class="ri-close-circle-line text-3xl text-gray-400 group-hover:text-red-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-red-700">Cancelación del Servicio</span>
                    </a>

                    <a href="{{ route('recuperacion.equipos') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all cursor-pointer group text-center">
                        <i class="ri-router-line text-3xl text-gray-400 group-hover:text-indigo-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Recuperación de Equipo</span>
                    </a>

                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all cursor-pointer group text-center">
                        <i class="ri-file-list-3-line text-3xl text-gray-400 group-hover:text-indigo-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Estado de Cuenta</span>
                    </a>

                    <a href="{{ route('reportes.servicio') }}" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all cursor-pointer group text-center">
                        <i class="ri-tools-line text-3xl text-gray-400 group-hover:text-indigo-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Reportes de Servicio</span>
                    </a>

                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all cursor-pointer group text-center">
                        <i class="ri-receipt-line text-3xl text-gray-400 group-hover:text-indigo-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Facturación a Clientes</span>
                    </a>

                    <a href="#" class="flex flex-col items-center justify-center p-6 bg-gray-50 border border-gray-200 rounded-xl hover:bg-indigo-50 hover:border-indigo-300 hover:text-indigo-700 transition-all cursor-pointer group text-center">
                        <i class="ri-price-tag-3-line text-3xl text-gray-400 group-hover:text-indigo-500 mb-3"></i>
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-indigo-700">Contratación Promociones</span>
                    </a>

                </div>
            </div>

            <div x-show="activeTab === 'reportes'" x-transition x-cloak>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Gestión de Reportes</h3>
                <p class="text-gray-500 text-sm">Aquí irá la tabla de Livewire con los reportes de fallas de TV o Internet asignados a los técnicos.</p>
                </div>

            <div x-show="activeTab === 'instalaciones'" x-transition x-cloak>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Instalaciones Pendientes</h3>
                <p class="text-gray-500 text-sm">Aquí irá la tabla de Livewire para dar seguimiento a las instalaciones de nuevos clientes.</p>
            </div>

        </div>
    </div>
</x-app-layout>