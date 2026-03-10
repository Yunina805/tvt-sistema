<?php

use Livewire\Volt\Component;

new class extends Component {
    public string $search = '';

    public function updatedSearch()
    {
        // Este método se ejecuta cuando cambia $search
    }

    public function getResults()
    {
        if (strlen($this->search) < 2) {
            return [];
        }

        $search_lower = strtolower($this->search);
        $mockClientes = [
            ['id' => 1, 'name' => 'Juan García López', 'phone' => '555-0101', 'type' => 'cliente', 'icon' => 'ri-user-line'],
            ['id' => 2, 'name' => 'María Rodriguez', 'phone' => '555-0102', 'type' => 'cliente', 'icon' => 'ri-user-line'],
        ];
        
        return array_filter($mockClientes, function ($item) use ($search_lower) {
            return str_contains(strtolower($item['name'] ?? ''), $search_lower) ||
                   str_contains(strtolower($item['phone'] ?? ''), $search_lower);
        });
    }
};

?>

<div x-data="{ open: false }" @click.outside="open = false" class="relative w-full max-w-md">
    <div class="relative">
        <input 
            type="text"
            placeholder="Buscar clientes, reportes, empleados..."
            wire:model.live="search"
            @keydown.escape="open = false"
            @focus="open = true"
            class="w-full px-4 py-2.5 pl-10 text-sm border border-gray-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-gray-900 dark:text-slate-100 placeholder-gray-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all"
        >
        <i class="ri-search-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 dark:text-slate-500"></i>
    </div>

    {{-- Dropdown de resultados --}}
    <div x-show="open && {{ json_encode(!empty($this->getResults())) }}" 
         x-transition
         class="absolute top-full left-0 right-0 mt-2 bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-lg shadow-lg z-50 max-h-64 overflow-y-auto">
        
        @forelse($this->getResults() as $result)
            <a href="#" class="flex items-center gap-3 px-4 py-3 border-b border-gray-100 dark:border-slate-700 hover:bg-gray-50 dark:hover:bg-slate-700 transition-colors last:border-b-0">
                <div class="flex-shrink-0">
                    <i class="{{ $result['icon'] }} text-lg text-indigo-600 dark:text-indigo-400"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-slate-100 truncate">
                        {{ $result['name'] ?? $result['title'] }}
                    </p>
                    <p class="text-xs text-gray-500 dark:text-slate-400 truncate">
                        {{ $result['phone'] ?? $result['folio'] ?? '' }}
                    </p>
                </div>
                <span class="px-2 py-1 text-[10px] font-bold uppercase rounded-full bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-300">
                    {{ ucfirst($result['type']) }}
                </span>
            </a>
        @empty
            @if(strlen($search) >= 2)
                <div class="px-4 py-6 text-center text-sm text-gray-500 dark:text-slate-400">
                    <i class="ri-search-2-line text-2xl opacity-50 mb-2 block"></i>
                    No se encontraron resultados
                </div>
            @endif
        @endforelse
    </div>
</div>
