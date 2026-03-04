<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.login')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        $acceso = Auth::user()->accesoSistema;

        if (! $acceso) {
            Auth::logout();
            Session::invalidate();
            $this->addError('form.email', 'Tu cuenta no tiene acceso al sistema. Contacta al administrador.');
            return;
        }

        if (! $acceso->activo) {
            Auth::logout();
            Session::invalidate();
            $this->addError('form.email', 'Tu acceso está desactivado. Contacta al administrador.');
            return;
        }

        $acceso->update(['ultimo_acceso' => now()]);

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="w-full max-w-sm">

    {{-- Logo móvil (visible solo en pantallas pequeñas) --}}
    <div class="flex items-center gap-3 mb-8 lg:hidden">
        <div class="w-10 h-10 bg-red-600 rounded-xl flex items-center justify-center">
            <i class="ri-tv-2-line text-white text-lg"></i>
        </div>
        <div>
            <p class="text-gray-900 text-sm font-black uppercase tracking-widest leading-none">TVT Sistema</p>
            <p class="text-gray-400 text-[10px] tracking-wider mt-0.5">Tu Visión Telecable</p>
        </div>
    </div>

    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-2xl font-black text-gray-900 tracking-tight">Acceso al Sistema</h1>
        <p class="text-sm text-gray-400 mt-1">Ingresa tus credenciales para continuar</p>
    </div>

    {{-- Alerta de estado de sesión --}}
    @if (session('status'))
        <div class="mb-5 flex items-center gap-3 px-4 py-3 bg-emerald-50 border border-emerald-200 rounded-xl">
            <i class="ri-checkbox-circle-line text-emerald-500"></i>
            <p class="text-xs font-medium text-emerald-700">{{ session('status') }}</p>
        </div>
    @endif

    <form wire:submit="login" class="space-y-5">

        {{-- Error general (acceso denegado) --}}
        @error('form.email')
            <div class="flex items-start gap-3 px-4 py-3 bg-red-50 border border-red-200 rounded-xl">
                <i class="ri-error-warning-line text-red-500 text-base mt-0.5 shrink-0"></i>
                <p class="text-xs font-medium text-red-700">{{ $message }}</p>
            </div>
        @enderror

        {{-- Correo electrónico --}}
        <div>
            <label for="email" class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1.5">
                Correo Electrónico
            </label>
            <div class="relative">
                <i class="ri-mail-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input wire:model="form.email"
                    id="email"
                    type="email"
                    name="email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="usuario@tvisiontv.mx"
                    class="w-full pl-9 pr-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-colors @error('form.email') border-red-300 @enderror">
            </div>
        </div>

        {{-- Contraseña --}}
        <div>
            <label for="password" class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-1.5">
                Contraseña
            </label>
            <div class="relative">
                <i class="ri-lock-line absolute left-3 top-1/2 -translate-y-1/2 text-gray-300 text-sm"></i>
                <input wire:model="form.password"
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                    class="w-full pl-9 pr-4 py-3 text-sm border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-300 focus:border-red-400 transition-colors @error('form.password') border-red-300 @enderror">
            </div>
            @error('form.password')
                <p class="text-[10px] text-red-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        {{-- Recordarme --}}
        <div class="flex items-center justify-between">
            <label for="remember" class="flex items-center gap-2 cursor-pointer">
                <input wire:model="form.remember"
                    id="remember"
                    type="checkbox"
                    class="rounded border-gray-300 text-red-600 focus:ring-red-300">
                <span class="text-xs text-gray-500 font-medium">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    wire:navigate
                    class="text-xs text-red-600 hover:text-red-800 font-semibold transition-colors">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        {{-- Botón de ingreso --}}
        <button type="submit"
            wire:loading.attr="disabled"
            class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white text-sm font-black uppercase tracking-wider rounded-xl transition-colors disabled:opacity-60 shadow-sm shadow-red-200">
            <span wire:loading.remove wire:target="login">
                <i class="ri-login-box-line"></i> Iniciar Sesión
            </span>
            <span wire:loading wire:target="login" class="flex items-center gap-2">
                <i class="ri-loader-4-line animate-spin"></i> Verificando...
            </span>
        </button>

    </form>

    <p class="mt-8 text-center text-[10px] text-gray-300 uppercase tracking-widest font-medium">
        Acceso restringido — Solo personal autorizado
    </p>
</div>
