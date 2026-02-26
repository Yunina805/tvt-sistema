{{-- resources/views/components/sidebar-item.blade.php --}}
{{--
    Props:
    - href:   string  — URL del enlace
    - active: bool    — ¿Está activo?
    - icon:   string  — Clase Remix Icon
    - label:  string  — Texto del enlace
--}}

@props([
    'href'   => '#',
    'active' => false,
    'icon'   => 'ri-circle-line',
    'label'  => '',
])

<a
    href="{{ $href }}"
    class="nav-item flex items-center gap-3 px-3 py-1.5 rounded-lg text-sm font-medium transition-colors
           {{ $active
                ? 'nav-active'
                : 'text-gray-600 hover:bg-indigo-50 hover:text-indigo-700' }}"
>
    <i class="{{ $icon }} text-base flex-shrink-0 {{ $active ? 'text-white' : 'text-gray-400' }}"></i>
    <span class="nav-label truncate">{{ $label }}</span>
</a>
