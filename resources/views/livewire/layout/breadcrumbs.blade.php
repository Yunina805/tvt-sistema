<?php

use Livewire\Volt\Component;

new class extends Component {
    public function getBreadcrumbs(): array
    {
        $routeName = request()->route()?->getName();
        
        $routeMap = [
            'contrataciones.nuevas' => [
                ['label' => 'Gestión de Clientes', 'route' => 'dashboard'],
                ['label' => 'Nuevas Contrataciones', 'route' => 'contrataciones.nuevas', 'icon' => 'ri-file-add-line'],
            ],
            'servicios.adicionales' => [
                ['label' => 'Gestión de Clientes', 'route' => 'dashboard'],
                ['label' => 'Servicios Adicionales', 'route' => 'servicios.adicionales', 'icon' => 'ri-add-circle-line'],
            ],
            'dashboard' => [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'ri-home-gear-line'],
            ],
        ];

        return $routeMap[$routeName] ?? [['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'ri-home-gear-line']];
    }
};

?>

<nav class="flex items-center gap-2 text-sm font-medium" aria-label="breadcrumb">
    @foreach($this->getBreadcrumbs() as $index => $breadcrumb)
        @if($index > 0)
            <span class="text-gray-400 dark:text-slate-500 mx-1">/</span>
        @endif
        
        @if($index === count($this->getBreadcrumbs()) - 1)
            {{-- Último elemento (actual) --}}
            <div class="flex items-center gap-2 text-gray-700 dark:text-slate-100">
                @if(isset($breadcrumb['icon']))
                    <i class="{{ $breadcrumb['icon'] }} text-sm opacity-70"></i>
                @endif
                <span class="font-bold">{{ $breadcrumb['label'] }}</span>
            </div>
        @else
            {{-- Enlaces previos --}}
            <a href="{{ route($breadcrumb['route']) }}" wire:navigate 
                class="flex items-center gap-2 text-gray-500 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                @if(isset($breadcrumb['icon']))
                    <i class="{{ $breadcrumb['icon'] }} text-sm opacity-60"></i>
                @endif
                <span>{{ $breadcrumb['label'] }}</span>
            </a>
        @endif
    @endforeach
</nav>
