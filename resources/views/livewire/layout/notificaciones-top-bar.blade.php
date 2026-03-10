<div class="relative" x-data="{ open: false }" @click.outside="open = false">
    <button @click="open = !open; if(open) { $wire.marcarComoLeidas() }" class="relative p-2.5 text-gray-500 dark:text-slate-400 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 shadow-sm hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 dark:hover:text-indigo-400 dark:hover:bg-slate-600 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
        <i class="ri-notification-3-line text-lg leading-none"></i>
        
        @if($noLeidas > 0)
            <span class="absolute top-1 right-1 h-2.5 w-2.5 bg-red-500 rounded-full border-2 border-white"></span>
        @endif
    </button>

    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-lg z-50 overflow-hidden" 
         style="display: none;">
        
        <div class="bg-gray-50 dark:bg-slate-700 px-4 py-3 border-b border-gray-200 dark:border-slate-600 flex justify-between items-center">
            <span class="text-sm font-bold text-gray-700 dark:text-slate-100">Notificaciones</span>
            <span class="text-xs text-indigo-600 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 px-2 py-0.5 rounded-full font-medium">{{ count($notificaciones) }} Nuevas</span>
        </div>

        <div class="max-h-80 overflow-y-auto">
            @forelse($notificaciones as $notificacion)
                <a href="{{ route('reportes.servicio') }}" class="block px-4 py-3 border-b border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors {{ !$notificacion['leida'] ? 'bg-blue-50/30 dark:bg-blue-900/30' : '' }}">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 mt-0.5">
                            <i class="ri-tools-line text-indigo-500 dark:text-indigo-400"></i>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-800 dark:text-slate-100">{{ $notificacion['titulo'] }}</p>
                            <p class="text-xs text-gray-600 dark:text-slate-400 mt-0.5">{{ $notificacion['mensaje'] }}</p>
                            <p class="text-[10px] text-gray-400 dark:text-slate-500 mt-1">{{ $notificacion['tiempo'] }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-6 text-center text-sm text-gray-500 dark:text-slate-400">
                    No tienes notificaciones nuevas.
                </div>
            @endforelse
        </div>
        
        <a href="{{ route('reportes.servicio') }}" class="block bg-gray-50 dark:bg-slate-700 px-4 py-2 text-center text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 transition-colors">
            Ver todos los reportes
        </a>
    </div>
</div>