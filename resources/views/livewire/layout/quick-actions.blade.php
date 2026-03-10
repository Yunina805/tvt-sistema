<?php

use Livewire\Volt\Component;

new class extends Component {
    public function getQuickActions(): array
    {
        return [
            [
                'label' => 'Nueva Contratación',
                'route' => 'contrataciones.nuevas',
                'icon' => 'ri-file-add-line',
                'color' => 'text-blue-600',
                'bg' => 'bg-blue-100/50',
            ],
            [
                'label' => 'Nuevo Reporte',
                'route' => 'reportes.servicio',
                'icon' => 'ri-tools-line',
                'color' => 'text-red-600',
                'bg' => 'bg-red-100/50',
            ],
            [
                'label' => 'Pago de Mensualidad',
                'route' => 'pago.mensualidad',
                'icon' => 'ri-money-dollar-circle-line',
                'color' => 'text-green-600',
                'bg' => 'bg-green-100/50',
            ],
        ];
    }
};

?>

<div x-data="{ open: false }" @click.outside="open = false" class="relative">
    <button @click="open = !open"
        class="p-2.5 text-gray-500 dark:text-slate-400 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 shadow-sm hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 dark:hover:text-indigo-400 dark:hover:bg-slate-600 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-100"
        title="Acciones Rápidas">
        <i class="ri-add-circle-fill text-lg leading-none text-indigo-600 dark:text-indigo-400"></i>
    </button>

    {{-- Dropdown de acciones --}}
    <div x-show="open"
         x-transition
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-lg z-50">
        
        <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700">
            <p class="text-sm font-bold text-gray-900 dark:text-slate-100">Acciones Rápidas</p>
            <p class="text-xs text-gray-500 dark:text-slate-400 mt-0.5">Accesos directos a funciones frecuentes</p>
        </div>

        <div class="p-3 space-y-2 max-h-96 overflow-y-auto">
            @foreach($this->getQuickActions() as $action)
                <a href="{{ route($action['route']) }}" wire:navigate
                    @click="open = false"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg border border-gray-100 dark:border-slate-700 hover:border-indigo-200 dark:hover:border-indigo-600 {{ $action['bg'] }} dark:bg-slate-700/60 hover:bg-opacity-75 dark:hover:bg-opacity-100 transition-all group">
                    <div class="flex-shrink-0">
                        <i class="{{ $action['icon'] }} text-lg {{ $action['color'] }}"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900 dark:text-slate-100">{{ $action['label'] }}</p>
                    </div>
                    <i class="ri-arrow-right-s-line text-gray-400 dark:text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors"></i>
                </a>
            @endforeach
        </div>

        <div class="px-4 py-2.5 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-700/50 text-center">
            <a href="{{ route('dashboard') }}" wire:navigate class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                Ver todas las opciones
            </a>
        </div>
    </div>
</div>
