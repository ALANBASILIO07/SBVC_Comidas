<x-layouts.app :title="__('Inicio')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">
            
            <!-- Título de la página -->
            <div class="flex items-center gap-3">
                <flux:icon.home class="size-8 text-orange-500" />
                <flux:heading size="xl">{{ __('INICIO') }}</flux:heading>
            </div>

            @if (auth()->user() && !auth()->user()->registro_completo)
                <div class="mb-6">
                    <div class="bg-white dark:bg-zinc-800 border border-orange-200 rounded-lg p-6 shadow-sm">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <div class="flex items-center">
                                <div class="p-3 bg-orange-100 rounded-lg mr-4">
                                    <flux:icon.exclamation-triangle class="h-6 w-6 text-orange-600" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">¡Completa tu registro!</h3>
                                    <p class="text-orange-600 text-sm">Agrega tu información fiscal para activar muchas más funciones</p>
                                </div>
                            </div>
                            <a href="{{ route('registro.completar') }}" class="bg-[#DC6601] hover:bg-[#c45a01] text-white font-semibold py-3 px-6 rounded-lg transition duration-200 flex items-center gap-2">
                                <flux:icon.arrow-right class="h-5 w-5" />
                                {{ __('Terminar Registro') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Grid de 4 tarjetas (Con datos dinámicos del controlador) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                
                <!-- Tarjeta 1: Establecimientos Activos -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border-2 border-orange-500 p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="text-center space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Establecimientos Activos
                        </h3>
                        <p class="text-5xl font-bold text-orange-500">
                            {{ $establecimientosCount ?? 0 }}
                        </p>
                    </div>
                </div>

                <!-- Tarjeta 2: Promociones Activas -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border-2 border-orange-500 p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="text-center space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Promociones Activas
                        </h3>
                        <p class="text-5xl font-bold text-orange-500">
                            {{ $promocionesCount ?? 0 }}
                        </p>
                    </div>
                </div>

                <!-- Tarjeta 3: Banners Activos -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border-2 border-orange-500 p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="text-center space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Banners Activos
                        </h3>
                        <p class="text-5xl font-bold text-orange-500">
                            {{ $bannersCount ?? 0 }}
                        </p>
                    </div>
                </div>

                <!-- Tarjeta 4: Plan Actual -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border-2 border-orange-500 p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="text-center space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Plan Actual
                        </h3>
                        <p class="text-2xl font-bold text-orange-500">
                            {{ $planActual ?? 'Básico' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
