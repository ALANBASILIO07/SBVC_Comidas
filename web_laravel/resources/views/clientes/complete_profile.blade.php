<x-layouts.app :title="__('Completar Registro')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            
            {{-- HEADER --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <flux:icon.clipboard-document-check class="size-10 text-orange-500" />
                    <flux:heading size="xl" class="text-gray-900 dark:text-white">
                        {{ __('Completar Registro') }}
                    </flux:heading>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Por favor completa tu información como titular de la cuenta para continuar
                </p>
            </div>

            {{-- Mensajes de error --}}
            @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <flux:icon.exclamation-triangle class="h-5 w-5 text-red-400" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            Por favor corrige los siguientes errores:
                        </h3>
                        <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            {{-- Mensaje de éxito --}}
            @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <flux:icon.check-circle class="h-5 w-5 text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif


            {{-- FORMULARIO --}}
            <form action="{{ route('clientes.store') }}" method="POST" class="space-y-6">
            @csrf
                
                {{-- ============================================ --}}
                {{-- CARD 1: INFORMACIÓN PERSONAL --}}
                {{-- ============================================ --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.identification class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">
                                Información Personal
                            </h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label for="nombre_titular" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombre completo del titular *
                            </label>
                            <input 
                                type="text" 
                                id="nombre_titular" 
                                name="nombre_titular" 
                                value="{{ old('nombre_titular', auth()->user()->name) }}" 
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                placeholder="Ej: Juan Pérez González" 
                                required
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <flux:icon.information-circle class="inline size-4" />
                                Nombre completo de la persona responsable de la cuenta
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="email_contacto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Email de contacto *
                                </label>
                                <input 
                                    type="email" 
                                    id="email_contacto" 
                                    name="email_contacto" 
                                    value="{{ old('email_contacto', auth()->user()->email) }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 bg-gray-100 dark:bg-zinc-900" 
                                    readonly
                                >
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Este email se toma de tu cuenta de usuario
                                </p>
                            </div>

                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Teléfono de contacto *
                                </label>
                                <input 
                                    type="tel" 
                                    id="telefono" 
                                    name="telefono" 
                                    value="{{ old('telefono') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    placeholder="7202002222" 
                                    required
                                >
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- CARD 2: INFORMACIÓN FISCAL (OPCIONAL) --}}
                {{-- ============================================ --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-gray-300 dark:border-zinc-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.document-text class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">
                                Información Fiscal (Opcional)
                            </h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-200 dark:border-blue-800">
                            <p class="text-sm text-blue-800 dark:text-blue-200 flex items-start gap-2">
                                <flux:icon.information-circle class="size-5 flex-shrink-0 mt-0.5" />
                                <span>
                                    Si necesitas facturar o manejar datos fiscales, completa esta sección. 
                                    De lo contrario, puedes dejarla en blanco y agregarla después.
                                </span>
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="rfc_titular" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        RFC del Titular
                                    </label>
                                    <input 
                                        type="text" 
                                        id="rfc_titular" 
                                        name="rfc_titular" 
                                        value="{{ old('rfc_titular') }}" 
                                        class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 uppercase" 
                                        placeholder="Ej: XAXX010101000"
                                        pattern="[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}"
                                        title="Formato: 3-4 letras, 6 números, 3 caracteres (ej: XAXX010101000)"
                                        minlength="13"
                                        maxlength="13"
                                    >
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        13 caracteres (personas físicas o morales)
                                    </p>
                                </div>

                                <div>
                                    <label for="razon_social_titular" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Razón Social
                                    </label>
                                    <input 
                                        type="text" 
                                        id="razon_social_titular" 
                                        name="razon_social_titular" 
                                        value="{{ old('razon_social_titular') }}" 
                                        class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                        placeholder="Nombre legal del titular o empresa"
                                        maxlength="255"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- INFORMACIÓN DE PLAN (SOLO LECTURA) --}}
                {{-- ============================================ --}}
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl border-2 border-orange-300 dark:border-orange-700 p-6">
                    <div class="flex items-start gap-4">
                        <flux:icon.sparkles class="size-8 text-orange-600 flex-shrink-0" />
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                Puedes actualizar tu plan
                            </h4>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                Tu cuenta aún no tiene un plan. Puedes actualizar tu plan desde la sección de subcripción.
                            </p>
                            <div class="flex flex-wrap gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <flux:icon.check class="size-4 mr-1" />
                                    Gestión básica de menús
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <flux:icon.check class="size-4 mr-1" />
                                    Hasta 50 platillos
                                </span>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                    <flux:icon.check class="size-4 mr-1" />
                                    1 establecimiento
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- BOTONES DE ACCIÓN --}}
                {{-- ============================================ --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="w-full sm:w-auto px-8 py-3 bg-white dark:bg-zinc-800 border-2 border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 rounded-lg transition duration-200 font-medium text-center shadow-sm"
                    >
                        <flux:icon.arrow-left class="inline size-5 mr-2" />
                        Volver
                    </a>
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg transition duration-200 font-bold text-center shadow-lg"
                    >
                        <flux:icon.check class="inline size-5 mr-2" />
                        Guardar y Continuar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>