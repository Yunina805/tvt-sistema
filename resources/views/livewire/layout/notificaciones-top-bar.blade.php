<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = !open; if(open) { $wire.marcarComoLeidas() }" class="relative text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100 transition-colors focus:outline-none">
        <i class="ri-notification-3-line text-xl"></i>
        
        @if($noLeidas > 0)
            <span class="absolute top-1 right-1.5 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-white"></span>
        @endif
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden" 
         style="display: none;">
        
        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
            <span class="text-sm font-bold text-gray-700">Notificaciones</span>
            <span class="text-xs text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-full font-medium">{{ count($notificaciones) }} Nuevas</span>
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($notificaciones as $notificacion)
                <a href="{{ route('reportes.servicio') }}" class="block px-4 py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors {{ !$notificacion['leida'] ? 'bg-blue-50/30' : '' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="ri-tools-line text-indigo-500"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800">{{ $notificacion['titulo'] }}</p>
                            <p class="text-xs text-gray-600 mt-0.5">{{ $notificacion['mensaje'] }}</p>
                            <p class="text-[10px] text-gray-400 mt-1">{{ $notificacion['tiempo'] }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-6 text-center text-sm text-gray-500">
                    No tienes notificaciones nuevas.
                </div>
            @endforelse
        </div>
        
        <a href="{{ route('reportes.servicio') }}" class="block bg-gray-50 px-4 py-2 text-center text-xs font-medium text-indigo-600 hover:text-indigo-800 transition-colors">
            Ver todos los reportes
        </a>
    </div>
</div>