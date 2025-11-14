<x-layouts.app :title="__('Inicio')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">
            <!-- Título de la página -->
            <div class="flex items-center gap-3">
                <flux:icon.home class="size-8 text-orange-500" />
                <flux:heading size="xl">{{ __('INICIO') }}</flux:heading>
            </div>

            <!-- Grid de 4 tarjetas (2x2) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">
                <!-- Tarjeta 1: Establecimientos Activos -->
                <div class="bg-white dark:bg-zinc-800 rounded-lg border-2 border-orange-500 p-8 hover:shadow-lg transition-shadow duration-200">
                    <div class="text-center space-y-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            Establecimientos Activos
                        </h3>
                        <p class="text-5xl font-bold text-orange-500">
                            0
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
                            0
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
                            0
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
                            Básico
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>