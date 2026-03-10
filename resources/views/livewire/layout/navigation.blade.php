<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div x-data="{ open: false }" class="relative w-full flex justify-end">

    <div class="flex items-center gap-1 sm:gap-2">

        {{-- ── DROPDOWN DE USUARIO MEJORADO ── --}}
        <x-dropdown align="right" width="56">
            <x-slot name="trigger">
                <button
                    class="flex items-center gap-2 px-2 py-1.5 sm:px-3 sm:py-2.5 border border-gray-200 dark:border-slate-600 rounded-xl text-sm font-medium text-gray-500 dark:text-slate-400 bg-white dark:bg-slate-700 hover:text-indigo-600 hover:bg-indigo-50 hover:border-indigo-200 dark:hover:text-indigo-400 dark:hover:bg-slate-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-indigo-100">

                    {{-- Nombre y Rol (Alineados a la derecha) --}}
                    <div class="hidden sm:block text-right" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-on:profile-updated.window="name = $event.detail.name">
                        <span x-text="name" class="block text-xs font-black text-gray-700 dark:text-slate-100 leading-tight uppercase"></span>
                        {{-- Puedes cambiar esto luego por el rol real del usuario desde la BD --}}
                        <span class="block text-[9px] font-bold text-gray-400 dark:text-slate-500 uppercase tracking-widest mt-0.5">Administrador</span>
                    </div>

                    {{-- Avatar dinámico --}}
                    <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-sm overflow-hidden border border-indigo-200 dark:border-indigo-700 shadow-sm shrink-0">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="{{ auth()->user()->name }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>

                    <i class="ri-arrow-down-s-line text-gray-400 dark:text-slate-500 text-lg"></i>
                </button>
            </x-slot>

            <x-slot name="content">
                {{-- Encabezado del Dropdown --}}
                <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700 bg-gray-50/50 dark:bg-slate-700/50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-base overflow-hidden shrink-0 border border-indigo-200 dark:border-indigo-700 shadow-sm">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-xs leading-tight font-black text-gray-900 dark:text-slate-100 uppercase truncate">
                            {{ auth()->user()->name }}
                        </p>
                        <p class="text-[10px] font-medium text-gray-500 dark:text-slate-400 truncate mt-0.5">
                            {{ auth()->user()->email }}
                        </p>
                    </div>
                </div>

                {{-- Sección 1: Gestión Personal --}}
                <div class="py-2">
                    <p class="px-4 py-1.5 text-[8px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-slate-500">Gestión Personal</p>
                    
                    <x-dropdown-link :href="route('profile')" wire:navigate class="flex items-center gap-2.5 text-gray-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/20 py-2">
                        <i class="ri-user-settings-line text-lg opacity-70"></i>
                        <span class="text-xs font-bold">Gestionar Perfil</span>
                    </x-dropdown-link>
                </div>

                <div class="border-t border-gray-100 dark:border-slate-700"></div>

                {{-- Sección 2: Sistema --}}
                <div class="py-2">
                    <p class="px-4 py-1.5 text-[8px] font-black uppercase tracking-[0.2em] text-gray-400 dark:text-slate-500">Sistema</p>
                    
                    <x-dropdown-link href="#" class="flex items-center gap-2.5 text-gray-600 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50/50 dark:hover:bg-indigo-900/20 py-2">
                        <i class="ri-question-line text-lg opacity-70"></i>
                        <span class="text-xs font-bold">Centro de Ayuda</span>
                    </x-dropdown-link>
                </div>

                <div class="border-t border-gray-100 dark:border-slate-700"></div>

                {{-- Sección 3: Salir --}}
                <div class="py-1.5">
                    <button wire:click="logout" class="w-full text-start focus:outline-none">
                        <x-dropdown-link class="flex items-center gap-2.5 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50/50 dark:hover:bg-red-900/20 py-2">
                            <i class="ri-logout-box-r-line text-lg opacity-80"></i>
                            <span class="text-xs font-black uppercase tracking-wider">Cerrar Sesión</span>
                        </x-dropdown-link>
                    </button>
                </div>
            </x-slot>
        </x-dropdown>
    </div>
</div>