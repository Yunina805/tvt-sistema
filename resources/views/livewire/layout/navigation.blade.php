<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component
{
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{ open: false }" class="relative">
    
    <div class="flex items-center">
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="flex items-center gap-2 px-3 py-2 border border-transparent rounded-xl text-sm font-medium text-gray-500 bg-white hover:text-indigo-600 hover:bg-indigo-50 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">
                    
                    {{-- Avatar opcional para el header --}}
                    <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>

                    {{-- Nombre del usuario --}}
                    <div class="hidden sm:block text-left" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-on:profile-updated.window="name = $event.detail.name">
                        <span x-text="name" class="block text-sm font-semibold text-gray-700 leading-none"></span>
                    </div>

                    <i class="ri-arrow-down-s-line text-gray-400"></i>
                </button>
            </x-slot>

            <x-slot name="content">
                {{-- Encabezado del Dropdown --}}
                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm leading-5 font-medium text-gray-900 truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs leading-5 font-medium text-gray-500 truncate">
                        {{ auth()->user()->email }}
                    </p>
                </div>

                {{-- Enlaces del Dropdown --}}
                <div class="py-1">
                    <x-dropdown-link :href="route('profile')" wire:navigate class="flex items-center gap-2 text-gray-700 hover:text-indigo-600 hover:bg-indigo-50">
                        <i class="ri-user-settings-line text-lg"></i>
                        {{ __('Mi Perfil') }}
                    </x-dropdown-link>

                    <button wire:click="logout" class="w-full text-start">
                        <x-dropdown-link class="flex items-center gap-2 text-red-600 hover:text-red-700 hover:bg-red-50">
                            <i class="ri-logout-box-r-line text-lg"></i>
                            {{ __('Cerrar Sesión') }}
                        </x-dropdown-link>
                    </button>
                </div>
            </x-slot>
        </x-dropdown>
    </div>

    {{-- He removido el menú hamburguesa duplicado de Breeze porque el control del layout principal ya lo maneja --}}
</div>