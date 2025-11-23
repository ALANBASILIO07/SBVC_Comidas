<x-layouts.app :title="__('Inicio')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">
            
            <!-- Título de la página -->
            <div class="flex items-center gap-3">
                <flux:icon.home class="size-8 text-orange-500" />
                <flux:heading size="xl">{{ __('INICIO') }}</flux:heading>
            </div>

            {{-- Banner de completar registro (solo si NO está completo) --}}
            @if (!$registroCompleto)
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 border-2 border-orange-300 dark:border-orange-700 rounded-lg p-6 shadow-sm">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center">
                                <div class="p-3 bg-orange-500 rounded-lg mr-4">
                                    <flux:icon.exclamation-triangle class="h-6 w-6 text-white" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">¡Completa tu registro!</h3>
                                    <p class="text-orange-700 dark:text-orange-300 text-sm">
                                        Necesitas completar tu información para acceder a todas las funciones de la plataforma
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('registro.completar') }}" 
                               class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200 flex items-center gap-2 shadow-lg">
                                <flux:icon.clipboard-document-check class="size-5" />
                                {{ __('Completar Registro') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Mensaje de bienvenida si ya completó el registro --}}
            @if ($registroCompleto)
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
            @endif

            <!-- Grid de 4 tarjetas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                
                <!-- Tarjeta 1: Establecimientos Activos -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border-2 border-orange-500 p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="text-center space-y-4">
                        <flux:icon.building-storefront class="size-12 mx-auto text-orange-500" />
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Establecimientos Activos
                        </h3>
                        <p class="text-5xl font-bold text-orange-500">
                            {{ $establecimientosCount }}
                        </p>
                    </div>
                </div>

                <!-- Tarjeta 2: Promociones Activas -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border-2 border-orange-500 p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="text-center space-y-4">
                        <flux:icon.tag class="size-12 mx-auto text-orange-500" />
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Promociones Activas
                        </h3>
                        <p class="text-5xl font-bold text-orange-500">
                            {{ $promocionesCount }}
                        </p>
                    </div>
                </div>

                <!-- Tarjeta 3: Banners Activos -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border-2 border-orange-500 p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="text-center space-y-4">
                        <flux:icon.photo class="size-12 mx-auto text-orange-500" />
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Banners Activos
                        </h3>
                        <p class="text-5xl font-bold text-orange-500">
                            {{ $bannersCount }}
                        </p>
                    </div>
                </div>

                <!-- Tarjeta 4: Plan Actual -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg border-2 border-orange-600 p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="text-center space-y-4">
                        <flux:icon.sparkles class="size-12 mx-auto text-white" />
                        <h3 class="text-lg font-semibold text-white">
                            Plan Actual
                        </h3>
                        <p class="text-3xl font-bold text-white">
                            {{ $planActual }}
                        </p>
                        @if($registroCompleto)
                            <a href="#" class="inline-block mt-4 px-4 py-2 bg-white text-orange-600 rounded-lg font-medium hover:bg-orange-50 transition">
                                Actualizar Plan
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>