{{-- resources/views/components/sidebar-group.blade.php --}}
{{--
    Props:
    - label: string  — Nombre del grupo
    - icon: string   — Clase Remix Icon (ej. 'ri-team-line')
    - active: bool   — ¿Alguna ruta hija está activa?
    - routes: array  — Patrones de rutas que mantienen el grupo abierto
--}}

@props([
    'label'  => '',
    'icon'   => 'ri-folder-line',
    'active' => false,
    'routes' => [],
])

@php
    $isOpen = $active;
@endphp

<div x-data="{ open: @js($isOpen) }">

    {{-- Group toggle button --}}
    <button
        @click="open = !open"
        class="nav-item w-full flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors
               {{ $active
                    ? 'text-indigo-700 bg-indigo-50'
                    : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}"
    >
        <i class="{{ $icon }} text-lg flex-shrink-0 {{ $active ? 'text-indigo-600' : 'text-indigo-400' }}"></i>
        <span class="nav-label flex-1 text-left">{{ $label }}</span>
        <i class="nav-arrow ri-arrow-down-s-line text-base transition-transform duration-200 text-gray-400"
           :class="open ? 'rotate-180' : ''"></i>
    </button>

    {{-- Submenu items --}}
    <div
        x-show="open"
        x-collapse
        x-cloak
        class="submenu-line mt-0.5 mb-0.5 space-y-0.5"
    >
        {{ $slot }}
    </div>

</div>
