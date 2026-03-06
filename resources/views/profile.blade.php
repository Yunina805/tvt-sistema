<x-app-layout>
    <div class="py-6 sm:px-6 lg:px-8">

        {{-- Breadcrumb --}}
        <nav
            class="flex items-center gap-2 text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-6 px-4 sm:px-0">
            <i class="ri-home-3-line text-gray-300"></i>
            <span>/</span>
            <span class="text-gray-600">Mi Perfil</span>
        </nav>

        {{-- Header --}}
        <div class="px-4 sm:px-0 mb-6 flex justify-between items-end">
            <div>
                <h1 class="text-base font-black uppercase tracking-widest text-gray-900">Portal del Empleado</h1>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mt-0.5">Gestión Personal · TVT
                    Sistema</p>
            </div>
            <button
                class="flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-wider rounded-xl transition-colors shadow-sm">
                <i class="ri-save-line text-sm"></i> Guardar Cambios
            </button>
        </div>

        {{-- Estado Global con Alpine (Controla pestañas y color de tema) --}}
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 px-4 sm:px-0" x-data="{ 
                tab: 'cuenta', 
                theme: 'indigo',
                themes: {
                    'indigo': 'from-indigo-500 to-purple-600',
                    'emerald': 'from-emerald-400 to-teal-500',
                    'rose': 'from-rose-400 to-pink-600',
                    'amber': 'from-amber-400 to-orange-500',
                    'slate': 'from-slate-600 to-gray-800'
                }
             }">

            {{-- ── COLUMNA IZQUIERDA: TARJETA DE PERFIL ── --}}
            <div class="xl:col-span-4 space-y-6">

                <div
                    class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden transition-all duration-300">
                    {{-- Cover Dinámico --}}
                    <div class="h-28 bg-gradient-to-r transition-all duration-500" :class="themes[theme]"></div>

                    <div class="relative px-6 pb-6">
                        {{-- Avatar con Hover y Vista Previa (Alpine.js) --}}
                        <div class="-mt-14 mb-4 flex justify-between items-end" x-data="{ 
                                photoPreview: null,
                                updatePhotoPreview(event) {
                                    const file = event.target.files[0];
                                    if (!file) return;
                                    const reader = new FileReader();
                                    reader.onload = (e) => { this.photoPreview = e.target.result; };
                                    reader.readAsDataURL(file);
                                }
                            }">

                            <div
                                class="relative group w-28 h-28 bg-white rounded-2xl p-1 shadow-sm border border-gray-100">

                                {{-- Foto actual / Iniciales (Se oculta si hay vista previa) --}}
                                <div x-show="!photoPreview"
                                    class="w-full h-full rounded-xl flex items-center justify-center text-4xl font-black transition-colors duration-300"
                                    :class="theme === 'indigo' ? 'text-indigo-600 bg-indigo-50' : (theme === 'emerald' ? 'text-emerald-600 bg-emerald-50' : (theme === 'rose' ? 'text-rose-600 bg-rose-50' : (theme === 'amber' ? 'text-amber-600 bg-amber-50' : 'text-slate-600 bg-slate-50')))">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>

                                {{-- Vista Previa de la Imagen Seleccionada --}}
                                <img x-show="photoPreview" :src="photoPreview" x-cloak
                                    class="w-full h-full object-cover rounded-xl" alt="Vista previa del perfil">

                                {{-- Overlay para cambiar foto --}}
                                <label
                                    class="absolute inset-1 bg-gray-900/60 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex flex-col items-center justify-center cursor-pointer backdrop-blur-sm overflow-hidden">
                                    <i class="ri-camera-add-line text-white text-2xl mb-1"></i>
                                    <span
                                        class="text-[8px] font-black uppercase tracking-widest text-white">Cambiar</span>
                                    {{-- Importante: el @change dispara la función de Alpine --}}
                                    <input type="file" class="hidden" accept="image/*" @change="updatePhotoPreview">
                                </label>
                            </div>

                            <span
                                class="px-2 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-lg text-[9px] font-black uppercase tracking-widest mb-2">
                                <i class="ri-checkbox-circle-line mr-1"></i> Activo
                            </span>
                        </div>

                        {{-- Info básica --}}
                        <h2 class="text-xl font-black text-gray-900 leading-tight uppercase">{{ auth()->user()->name }}
                        </h2>
                        <p class="text-xs font-medium text-gray-500 mt-1 flex items-center gap-1.5">
                            <i class="ri-mail-line text-gray-400"></i> {{ auth()->user()->email }}
                        </p>

                        <div
                            class="mt-4 inline-flex items-center gap-1.5 px-2.5 py-1 bg-gray-50 border border-gray-200 rounded-lg text-[10px] font-bold text-gray-600 uppercase tracking-wider">
                            <i class="ri-briefcase-line text-gray-400"></i> Departamento TVT
                        </div>
                    </div>

                    {{-- Resumen de Días --}}
                    <div class="border-t border-gray-100 px-6 py-4 bg-gray-50/50">
                        <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-3">Balance Actual
                        </p>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                    <i class="ri-sun-line text-amber-500"></i> 12 Días
                                </p>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">
                                    Vacaciones</p>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-800 flex items-center gap-1.5">
                                    <i class="ri-calendar-event-line text-blue-500"></i> 1 / 2
                                </p>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mt-0.5">Descansos
                                    Mes</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mockup: Mi Equipo de Trabajo (Opcional) --}}
                <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
                    <p
                        class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-400 mb-4 flex items-center gap-2">
                        <i class="ri-team-line text-gray-300"></i> Equipo Directo
                    </p>
                    <div class="flex items-center gap-3">
                        <div class="flex -space-x-2">
                            <div
                                class="w-8 h-8 rounded-full border-2 border-white bg-indigo-100 flex items-center justify-center text-[10px] font-bold text-indigo-600">
                                JP</div>
                            <div
                                class="w-8 h-8 rounded-full border-2 border-white bg-emerald-100 flex items-center justify-center text-[10px] font-bold text-emerald-600">
                                MR</div>
                            <div
                                class="w-8 h-8 rounded-full border-2 border-white bg-amber-100 flex items-center justify-center text-[10px] font-bold text-amber-600">
                                LM</div>
                        </div>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider">+2 Integrantes</span>
                    </div>
                </div>

            </div>

            {{-- ── COLUMNA DERECHA: PESTAÑAS Y CONTENIDO ── --}}
            <div class="xl:col-span-8">

                {{-- Navegación de Pestañas --}}
                <div
                    class="bg-white border border-gray-200 rounded-2xl shadow-sm mb-6 p-2 flex overflow-x-auto hide-scrollbar">
                    <button @click="tab = 'cuenta'"
                        :class="tab === 'cuenta' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        <i class="ri-user-settings-line text-sm"></i> Datos y Apariencia
                    </button>
                    <button @click="tab = 'solicitudes'"
                        :class="tab === 'solicitudes' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        <i class="ri-flight-takeoff-line text-sm"></i> Mis Solicitudes
                    </button>
                    <button @click="tab = 'seguridad'"
                        :class="tab === 'seguridad' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        <i class="ri-shield-keyhole-line text-sm"></i> Seguridad
                    </button>
                    <button @click="tab = 'preferencias'"
                        :class="tab === 'preferencias' ? 'bg-indigo-50 text-indigo-700' : 'text-gray-500 hover:bg-gray-50 hover:text-gray-700'"
                        class="flex-1 flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap">
                        <i class="ri-notification-3-line text-sm"></i> Preferencias
                    </button>
                </div>

                {{-- Contenido de Pestañas --}}
                <div>

                    {{-- 1. PESTAÑA: DATOS Y APARIENCIA --}}
                    <div x-show="tab === 'cuenta'" x-cloak class="space-y-6">

                        {{-- APARIENCIA --}}
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-9 h-9 bg-pink-50 rounded-xl flex items-center justify-center">
                                    <i class="ri-palette-line text-pink-500 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-800">Tema del
                                        Perfil</h3>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Personaliza
                                        los colores de tu portada</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <button @click="theme = 'indigo'"
                                    class="w-10 h-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-600 focus:outline-none transition-transform hover:scale-110"
                                    :class="theme === 'indigo' ? 'ring-4 ring-indigo-200 ring-offset-2 scale-110' : ''"></button>
                                <button @click="theme = 'emerald'"
                                    class="w-10 h-10 rounded-full bg-gradient-to-r from-emerald-400 to-teal-500 focus:outline-none transition-transform hover:scale-110"
                                    :class="theme === 'emerald' ? 'ring-4 ring-emerald-200 ring-offset-2 scale-110' : ''"></button>
                                <button @click="theme = 'rose'"
                                    class="w-10 h-10 rounded-full bg-gradient-to-r from-rose-400 to-pink-600 focus:outline-none transition-transform hover:scale-110"
                                    :class="theme === 'rose' ? 'ring-4 ring-rose-200 ring-offset-2 scale-110' : ''"></button>
                                <button @click="theme = 'amber'"
                                    class="w-10 h-10 rounded-full bg-gradient-to-r from-amber-400 to-orange-500 focus:outline-none transition-transform hover:scale-110"
                                    :class="theme === 'amber' ? 'ring-4 ring-amber-200 ring-offset-2 scale-110' : ''"></button>
                                <button @click="theme = 'slate'"
                                    class="w-10 h-10 rounded-full bg-gradient-to-r from-slate-600 to-gray-800 focus:outline-none transition-transform hover:scale-110"
                                    :class="theme === 'slate' ? 'ring-4 ring-slate-200 ring-offset-2 scale-110' : ''"></button>
                            </div>
                        </div>

                        {{-- DATOS PERSONALES (Laravel Form Stylized) --}}
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-9 h-9 bg-indigo-50 rounded-xl flex items-center justify-center">
                                    <i class="ri-user-settings-line text-indigo-600 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-800">Información
                                        del Perfil</h3>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Actualiza tu
                                        nombre y correo electrónico</p>
                                </div>
                            </div>

                            {{-- Aquí iría la lógica de tu form original, estilizada --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-2xl">
                                <div>
                                    <label
                                        class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Nombre
                                        Completo</label>
                                    <input type="text" value="{{ auth()->user()->name }}"
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all">
                                </div>
                                <div>
                                    <label
                                        class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Correo
                                        Electrónico</label>
                                    <input type="email" value="{{ auth()->user()->email }}"
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. PESTAÑA: MIS SOLICITUDES --}}
                    <div x-show="tab === 'solicitudes'" x-cloak class="space-y-6">
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-800">Historial de
                                        Permisos</h3>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider mt-1">Sujeto a
                                        aprobación de RH</p>
                                </div>
                                <button
                                    class="flex items-center gap-2 px-4 py-2 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 text-[10px] font-black uppercase tracking-wider rounded-xl transition-colors">
                                    <i class="ri-add-line"></i> Solicitar
                                </button>
                            </div>

                            {{-- Tabla (Mantengo la misma del mensaje anterior) --}}
                            <div class="border border-gray-100 rounded-xl overflow-hidden">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-gray-50 border-b border-gray-100">
                                            <th
                                                class="px-4 py-3 text-[9px] font-black uppercase tracking-widest text-gray-400">
                                                Tipo</th>
                                            <th
                                                class="px-4 py-3 text-[9px] font-black uppercase tracking-widest text-gray-400">
                                                Fechas</th>
                                            <th
                                                class="px-4 py-3 text-[9px] font-black uppercase tracking-widest text-gray-400">
                                                Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        <tr class="hover:bg-gray-50 transition-colors">
                                            <td class="px-4 py-3 text-xs font-bold text-gray-700"><i
                                                    class="ri-sun-line text-amber-500 mr-1"></i> Vacaciones</td>
                                            <td class="px-4 py-3 text-[10px] font-medium text-gray-500">15 Dic 2025 - 20
                                                Dic 2025</td>
                                            <td class="px-4 py-3">
                                                <span
                                                    class="px-2 py-0.5 bg-emerald-50 text-emerald-600 rounded text-[8px] font-black uppercase tracking-widest border border-emerald-100">Aprobado</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- 3. PESTAÑA: SEGURIDAD --}}
                    <div x-show="tab === 'seguridad'" x-cloak class="space-y-6">

                        {{-- ACTUALIZAR CONTRASEÑA --}}
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-9 h-9 bg-amber-50 rounded-xl flex items-center justify-center">
                                    <i class="ri-lock-password-line text-amber-500 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-800">Actualizar
                                        Contraseña</h3>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Usa una
                                        contraseña larga y aleatoria</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-2xl">
                                <div class="md:col-span-2">
                                    <label
                                        class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Contraseña
                                        Actual</label>
                                    <input type="password"
                                        class="w-full md:w-1/2 border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-300 transition-all">
                                </div>
                                <div>
                                    <label
                                        class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Nueva
                                        Contraseña</label>
                                    <input type="password"
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-300 transition-all">
                                </div>
                                <div>
                                    <label
                                        class="block text-[9px] font-black uppercase tracking-widest text-gray-500 mb-1.5">Confirmar
                                        Contraseña</label>
                                    <input type="password"
                                        class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-xs text-gray-800 focus:outline-none focus:ring-2 focus:ring-amber-300 transition-all">
                                </div>
                            </div>
                        </div>

                        {{-- SESIONES ACTIVAS (Bonus UI) --}}
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center">
                                    <i class="ri-macbook-line text-blue-500 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-800">Sesiones
                                        Activas</h3>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Dispositivos
                                        donde has iniciado sesión</p>
                                </div>
                            </div>

                            <div class="space-y-4 max-w-2xl">
                                <div
                                    class="flex items-center justify-between p-4 bg-gray-50 border border-gray-100 rounded-xl">
                                    <div class="flex items-center gap-4">
                                        <i class="ri-computer-line text-2xl text-gray-400"></i>
                                        <div>
                                            <p class="text-xs font-bold text-gray-800">Windows - Google Chrome</p>
                                            <p class="text-[9px] text-gray-500 uppercase tracking-wider">Hace 2 minutos
                                                · IP: 192.168.1.10</p>
                                        </div>
                                    </div>
                                    <span class="text-[9px] font-black text-emerald-500 uppercase tracking-widest">Este
                                        Dispositivo</span>
                                </div>
                                <div
                                    class="flex items-center justify-between p-4 bg-white border border-gray-100 rounded-xl">
                                    <div class="flex items-center gap-4">
                                        <i class="ri-smartphone-line text-2xl text-gray-400"></i>
                                        <div>
                                            <p class="text-xs font-bold text-gray-800">iPhone - Safari</p>
                                            <p class="text-[9px] text-gray-500 uppercase tracking-wider">Ayer a las
                                                14:30 · IP: 189.10.X.X</p>
                                        </div>
                                    </div>
                                    <button
                                        class="text-[9px] font-black text-red-500 hover:text-red-700 uppercase tracking-widest underline">Cerrar
                                        Sesión</button>
                                </div>
                            </div>
                        </div>

                        {{-- ELIMINAR CUENTA --}}
                        <div class="bg-red-50/50 border border-red-100 rounded-2xl shadow-sm p-6">
                            <h3 class="text-xs font-black uppercase tracking-widest text-red-700 mb-1">Peligro: Eliminar
                                Cuenta</h3>
                            <p class="text-[10px] font-medium text-red-500 mb-4">Una vez eliminada, todos tus datos y
                                configuraciones se perderán permanentemente.</p>
                            <button
                                class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-[9px] font-black uppercase tracking-wider rounded-xl transition-colors">
                                <i class="ri-delete-bin-line mr-1"></i> Borrar Mi Cuenta
                            </button>
                        </div>
                    </div>

                    {{-- 4. PESTAÑA: PREFERENCIAS (Bonus UI) --}}
                    <div x-show="tab === 'preferencias'" x-cloak class="space-y-6">
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-9 h-9 bg-violet-50 rounded-xl flex items-center justify-center">
                                    <i class="ri-notification-3-line text-violet-500 text-lg"></i>
                                </div>
                                <div>
                                    <h3 class="text-xs font-black uppercase tracking-widest text-gray-800">Alertas y
                                        Correos</h3>
                                    <p class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Decide qué
                                        notificaciones recibir</p>
                                </div>
                            </div>

                            <div class="max-w-xl space-y-5">
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <div>
                                        <p class="text-xs font-bold text-gray-800">Reportes de Falla Asignados</p>
                                        <p class="text-[9px] text-gray-500 uppercase tracking-wider">Recibe un correo
                                            cuando se te asigne un ticket.</p>
                                    </div>
                                    <input type="checkbox" checked
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-300">
                                </label>
                                <hr class="border-gray-100">
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <div>
                                        <p class="text-xs font-bold text-gray-800">Estatus de mis solicitudes (RH)</p>
                                        <p class="text-[9px] text-gray-500 uppercase tracking-wider">Notificarme si mis
                                            vacaciones son aprobadas.</p>
                                    </div>
                                    <input type="checkbox" checked
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-300">
                                </label>
                                <hr class="border-gray-100">
                                <label class="flex items-center justify-between cursor-pointer group">
                                    <div>
                                        <p class="text-xs font-bold text-gray-800">Boletín Interno TVT</p>
                                        <p class="text-[9px] text-gray-500 uppercase tracking-wider">Noticias generales
                                            de la empresa.</p>
                                    </div>
                                    <input type="checkbox"
                                        class="w-4 h-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-300">
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>