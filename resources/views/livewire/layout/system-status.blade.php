<?php

use Livewire\Volt\Component;

new class extends Component {
    public function getSystemStatus(): array
    {
        return [
            'database' => [
                'status' => 'connected',
                'label' => 'Base de Datos',
                'icon' => 'ri-database-line',
                'color' => 'text-green-600',
                'bg' => 'bg-green-100/50',
            ],
            'server' => [
                'status' => 'healthy',
                'label' => 'Servidor',
                'icon' => 'ri-server-line',
                'color' => 'text-emerald-600',
                'bg' => 'bg-emerald-100/50',
            ],
        ];
    }
};

?>

<div x-data="{ open: false }" @click.outside="open = false" class="relative">
    <button @click="open = !open"
        class="p-2.5 text-gray-500 dark:text-slate-400 bg-white dark:bg-slate-700 border border-gray-200 dark:border-slate-600 shadow-sm hover:text-emerald-600 hover:bg-emerald-50 hover:border-emerald-200 dark:hover:text-emerald-400 dark:hover:bg-slate-600 rounded-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-100"
        title="Estado del Sistema">
        <i class="ri-pulse-line text-lg leading-none"></i>
    </button>

    {{-- Dropdown de estado --}}
    <div x-show="open"
         x-transition
         class="absolute right-0 mt-2 w-72 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-lg z-50">
        
        <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700">
            <p class="text-sm font-bold text-gray-900 dark:text-slate-100">Estado del Sistema</p>
        </div>

        <div class="p-4 space-y-3">
            @foreach($this->getSystemStatus() as $key => $status)
                <div class="flex items-center gap-3 p-3 rounded-lg {{ $status['bg'] }} dark:bg-slate-700/60 border border-gray-100 dark:border-slate-600">
                    <i class="{{ $status['icon'] }} text-lg {{ $status['color'] }} dark:text-emerald-400"></i>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-gray-700 dark:text-slate-100">{{ $status['label'] }}</p>
                        <p class="text-[10px] text-gray-600 dark:text-slate-300 mt-0.5">
                            <span class="inline-block w-2 h-2 rounded-full {{ str_contains($status['color'], 'green') ? 'bg-green-500' : (str_contains($status['color'], 'emerald') ? 'bg-emerald-500' : 'bg-blue-500') }} mr-1"></span>
                            {{ ucfirst($status['status']) }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="px-4 py-2.5 border-t border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-700/50">
            <a href="{{ route('dashboard') }}" wire:navigate class="text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline">
                Ver dashboard →
            </a>
        </div>
    </div>
</div>
