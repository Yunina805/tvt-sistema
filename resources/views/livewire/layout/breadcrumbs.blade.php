<?php

use Livewire\Volt\Component;

new class extends Component {
    public function getBreadcrumbs(): array
    {
        $routeName = request()->route()?->getName();
        
        // Mapeo de rutas a breadcrumbs
        $routeMap = [
            // Dashboard
            'dashboard' => [
                ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'ri-home-gear-line'],
            ],

            // ═══════════════════════════════════════════════════════════
            // GESTIÓN AL CLIENTE — Altas y Promociones
            // ═══════════════════════════════════════════════════════════
            'contrataciones.nuevas' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Nuevas Contrataciones', 'icon' => 'ri-file-add-line'],
            ],
            'servicios.adicionales' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Servicios Adicionales', 'icon' => 'ri-add-circle-line'],
            ],
            'contratacion.promocion' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Pago en Promoción', 'icon' => 'ri-discount-percent-line'],
            ],
            'cambio.servicio' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Cambio de Servicio', 'icon' => 'ri-exchange-line'],
            ],

            // ═══════════════════════════════════════════════════════════
            // GESTIÓN AL CLIENTE — Pagos y Estados
            // ═══════════════════════════════════════════════════════════
            'pago.mensualidad' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Pago de Mensualidad', 'icon' => 'ri-calendar-check-line'],
            ],
            'estado.cuenta' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Estado de Cuenta', 'icon' => 'ri-file-list-3-line'],
            ],

            // ═══════════════════════════════════════════════════════════
            // GESTIÓN AL CLIENTE — Operativa de Estatus
            // ═══════════════════════════════════════════════════════════
            'suspension.clientes' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Suspensión por Falta de Pago', 'icon' => 'ri-user-unfollow-line'],
            ],
            'reconexion.cliente' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Reconexión de Cliente', 'icon' => 'ri-plug-line'],
            ],
            'cancelacion.servicio' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Cancelación de Servicio', 'icon' => 'ri-close-circle-line'],
            ],
            'recuperacion.equipos' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Recuperación de Equipos', 'icon' => 'ri-router-line'],
            ],

            // ═══════════════════════════════════════════════════════════
            // GESTIÓN AL CLIENTE — Bandeja de Reportes
            // ═══════════════════════════════════════════════════════════
            'reportes.servicio' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Bandeja de Reportes', 'icon' => 'ri-inbox-archive-line'],
            ],
            'reportes.atender' => [
                ['label' => 'Modelos de Operación', 'route' => 'dashboard'],
                ['label' => 'Gestión al Cliente', 'route' => 'contrataciones.nuevas'],
                ['label' => 'Bandeja de Reportes', 'route' => 'reportes.servicio'],
                ['label' => 'Atender Reporte', 'icon' => 'ri-file-text-line'],
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
