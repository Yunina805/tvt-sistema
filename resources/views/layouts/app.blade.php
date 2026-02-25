<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'TVT Sistema') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-100">
        <div class="flex h-screen overflow-hidden">
            
            <aside class="flex flex-col w-64 bg-white border-r border-gray-200">
                <div class="flex items-center justify-center h-16 border-b border-gray-200">
                    <span class="text-xl font-bold uppercase text-indigo-600">TVT Sistema</span>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <nav class="px-4 py-4 space-y-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-700 bg-gray-100 rounded-md">
                            <i class="ri-dashboard-line mr-3 text-lg"></i> Inicio
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-md">
                            <i class="ri-team-line mr-3 text-lg"></i> Clientes
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-md">
                            <i class="ri-tools-line mr-3 text-lg"></i> Planta Externa
                        </a>
                        <a href="#" class="flex items-center px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-md">
                            <i class="ri-money-dollar-circle-line mr-3 text-lg"></i> Finanzas
                        </a>
                    </nav>
                </div>
            </aside>

            <div class="flex flex-col flex-1 overflow-hidden">
                
                <header class="flex items-center justify-between h-16 px-6 bg-white border-b border-gray-200">
                    <div class="flex items-center">
                        <button class="text-gray-500 focus:outline-none lg:hidden">
                            <i class="ri-menu-line text-2xl"></i>
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <livewire:layout.notificaciones-top-bar />
                        
                        <livewire:layout.navigation />
                    </div>
                </header>

                <main class="flex-1 overflow-y-auto bg-gray-100 p-6">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>