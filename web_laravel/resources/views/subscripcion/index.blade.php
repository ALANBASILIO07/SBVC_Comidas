<x-layouts.app :title="__('Subscripción')">
<div class="py-8 px-4 sm:px-6 lg:px-8">
<div class="max-w-7xl mx-auto space-y-6">

        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <flux:heading size="xl">{{ __('SUBSCRIPCIÓN') }}</flux:heading>
        </div>

        <!-- Tarjetas de Planes -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 max-w-5xl mx-auto">
            
            <!-- PLAN BÁSICO -->
            <div class="bg-white dark:bg-zinc-900 rounded-3xl p-8 border-4 border-zinc-800 dark:border-zinc-700 shadow-xl transition-all duration-300 hover:scale-105 hover:shadow-2xl">
                <!-- Header del Plan -->
                <div class="text-center mb-8 pb-6 border-b-2 border-zinc-200 dark:border-zinc-700">
                    <h3 class="text-3xl font-bold text-zinc-900 dark:text-white mb-4">
                        PLAN BÁSICO
                    </h3>
                    <div class="flex items-baseline justify-center gap-2 mb-2">
                        <span class="text-5xl font-bold text-zinc-900 dark:text-white">$299</span>
                        <span class="text-xl text-zinc-600 dark:text-zinc-400">MN</span>
                    </div>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400">Por mes</p>
                </div>

                <!-- Lista de características -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-zinc-900 dark:text-white font-medium">10 Filtros por día</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-zinc-900 dark:text-white font-medium">Gestión de menú básica</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-zinc-900 dark:text-white font-medium">Calificaciones y reseñas</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-zinc-900 dark:text-white font-medium">3 promociones activas</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-zinc-900 dark:text-white font-medium">Soporte por email</span>
                    </div>

                    <!-- Características no incluidas -->
                    <div class="flex items-start gap-3 opacity-40">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <span class="text-zinc-400 dark:text-zinc-600 font-medium">Anuncios y banners</span>
                    </div>

                    <div class="flex items-start gap-3 opacity-40">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <span class="text-zinc-400 dark:text-zinc-600 font-medium">Estadísticas avanzadas</span>
                    </div>

                    <div class="flex items-start gap-3 opacity-40">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        <span class="text-zinc-400 dark:text-zinc-600 font-medium">Soporte prioritario</span>
                    </div>
                </div>

                <!-- Botón de acción -->
                <flux:button 
                    variant="ghost"
                    class="w-full bg-zinc-200 hover:bg-zinc-300 text-zinc-800 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-zinc-200 py-4 text-lg font-bold"
                >
                    Plan Actual
                </flux:button>
            </div>

            <!-- PLAN PREMIUM -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-3xl p-8 border-4 border-orange-700 shadow-2xl transition-all duration-300 hover:scale-105 hover:shadow-3xl relative">
                <!-- Badge recomendado -->
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-yellow-400 text-zinc-900 px-6 py-2 rounded-full text-sm font-bold shadow-lg">
                        ⭐ RECOMENDADO
                    </span>
                </div>

                <!-- Header del Plan -->
                <div class="text-center mb-8 pb-6 border-b-2 border-orange-400">
                    <h3 class="text-3xl font-bold text-white mb-4">
                        PLAN PREMIUM
                    </h3>
                    <div class="flex items-baseline justify-center gap-2 mb-2">
                        <span class="text-5xl font-bold text-white">$599</span>
                        <span class="text-xl text-orange-100">MN</span>
                    </div>
                    <p class="text-sm text-orange-100">Por mes</p>
                </div>

                <!-- Lista de características -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-white font-bold">Filtros ilimitados</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-white font-bold">Gestión de menú avanzada</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-white font-bold">Calificaciones y reseña</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-white font-bold">Promociones ilimitadas</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-white font-bold">Anuncios y banners</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-white font-bold">Estadísticas avanzadas</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-white font-bold">Soporte prioritario 24/7</span>
                    </div>

                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span class="text-white font-bold">Búsquedas avanzadas</span>
                    </div>
                </div>

                <!-- Botón de acción -->
                <flux:button 
                    variant="primary"
                    class="w-full bg-white hover:bg-orange-50 text-orange-600 py-4 text-lg font-bold shadow-lg"
                >
                    Actualizar a Premium
                </flux:button>
            </div>

        </div>

        <!-- Tabla de Comparación -->
        <div class="max-w-5xl mx-auto mt-12">
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-8 shadow-lg border border-zinc-200 dark:border-zinc-800">
                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white text-center mb-8">
                    Comparación Detallada de Planes
                </h3>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b-2 border-zinc-200 dark:border-zinc-700">
                                <th class="text-left py-4 px-4 text-zinc-900 dark:text-white font-bold">Característica</th>
                                <th class="text-center py-4 px-4 text-zinc-900 dark:text-white font-bold">Plan Básico</th>
                                <th class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">Plan Premium</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                            <tr>
                                <td class="py-4 px-4 text-zinc-900 dark:text-white">Filtros de búsqueda</td>
                                <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">10 por día</td>
                                <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">Ilimitados</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4 text-zinc-900 dark:text-white">Promociones activas</td>
                                <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">3</td>
                                <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">Ilimitadas</td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4 text-zinc-900 dark:text-white">Anuncios y Banners</td>
                                <td class="text-center py-4 px-4">
                                    <span class="text-red-600 text-xl">✗</span>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <span class="text-green-600 text-xl">✓</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4 text-zinc-900 dark:text-white">Estadísticas avanzadas</td>
                                <td class="text-center py-4 px-4">
                                    <span class="text-red-600 text-xl">✗</span>
                                </td>
                                <td class="text-center py-4 px-4">
                                    <span class="text-green-600 text-xl">✓</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="py-4 px-4 text-zinc-900 dark:text-white">Soporte técnico</td>
                                <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">Por email</td>
                                <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">24/7 Prioritario</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
</x-layouts.app>