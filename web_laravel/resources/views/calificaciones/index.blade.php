<x-layouts.app :title="__('Calificaciones')">
<div class="py-8 px-4 sm:px-6 lg:px-8">
<div class="max-w-7xl mx-auto space-y-6">

        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <flux:heading size="xl">{{ __('CALIFICACIONES') }}</flux:heading>
        </div>

        <!-- Filtros -->
        <div class="flex gap-4 flex-wrap">
            <flux:select class="flex-1 min-w-[200px]">
                <option value="">Todos los restaurantes ▼</option>
                <option value="la-marquesa">La Marquesa</option>
                <option value="vips">Vips</option>
                <option value="chilis">Chilis</option>
            </flux:select>

            <flux:select class="flex-1 min-w-[200px]">
                <option value="">Todos los restaurantes ▼</option>
                <option value="5">5 estrellas</option>
                <option value="4">4 estrellas</option>
                <option value="3">3 estrellas</option>
                <option value="2">2 estrellas</option>
                <option value="1">1 estrella</option>
            </flux:select>

            <flux:select class="flex-1 min-w-[200px]">
                <option value="">Todos los restaurantes ▼</option>
                <option value="recientes">Más recientes</option>
                <option value="antiguas">Más antiguas</option>
                <option value="mejor">Mejor calificadas</option>
                <option value="peor">Peor calificadas</option>
            </flux:select>
        </div>

        <!-- Tarjetas de Estadísticas -->
        <div class="bg-white dark:bg-zinc-900 border-4 border-orange-500 rounded-2xl p-8 shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- Calificación Promedio -->
                <div class="text-center p-6 bg-orange-50 dark:bg-orange-950/30 rounded-xl border-2 border-orange-300">
                    <div class="text-6xl font-bold text-orange-600 dark:text-orange-400 mb-2">
                        4.8
                    </div>
                    <p class="text-zinc-900 dark:text-white font-semibold mb-3">
                        Calificación promedio
                    </p>
                    <div class="flex justify-center gap-1 text-3xl text-yellow-400">
                        ★★★★★
                    </div>
                </div>

                <!-- Total de Reseñas -->
                <div class="text-center p-6 bg-orange-50 dark:bg-orange-950/30 rounded-xl border-2 border-orange-300">
                    <div class="text-6xl font-bold text-orange-600 dark:text-orange-400 mb-2">
                        1,243
                    </div>
                    <p class="text-zinc-900 dark:text-white font-semibold">
                        Total de reseñas
                    </p>
                </div>

                <!-- Reseñas Este Mes -->
                <div class="text-center p-6 bg-orange-50 dark:bg-orange-950/30 rounded-xl border-2 border-orange-300">
                    <div class="text-6xl font-bold text-orange-600 dark:text-orange-400 mb-2">
                        150
                    </div>
                    <p class="text-zinc-900 dark:text-white font-semibold">
                        Este mes
                    </p>
                </div>

            </div>
        </div>

        <!-- Grid: Distribución y Reseñas -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Distribución de Calificaciones -->
            <div class="bg-white dark:bg-zinc-900 border-4 border-orange-500 rounded-2xl p-8 shadow-lg">
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-6 pb-4 border-b-2 border-orange-300">
                    Distribución de calificaciones
                </h3>

                <div class="space-y-4">
                    <!-- 5 Estrellas -->
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-semibold text-zinc-900 dark:text-white w-8">5</span>
                        <span class="text-yellow-400 text-xl">★</span>
                        <div class="flex-1 h-8 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-400 to-blue-500 rounded-full" style="width: 75%"></div>
                        </div>
                        <span class="text-sm text-zinc-600 dark:text-zinc-400 w-16 text-right">932</span>
                    </div>

                    <!-- 4 Estrellas -->
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-semibold text-zinc-900 dark:text-white w-8">4</span>
                        <span class="text-yellow-400 text-xl">★</span>
                        <div class="flex-1 h-8 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-400 to-blue-500 rounded-full" style="width: 60%"></div>
                        </div>
                        <span class="text-sm text-zinc-600 dark:text-zinc-400 w-16 text-right">186</span>
                    </div>

                    <!-- 3 Estrellas -->
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-semibold text-zinc-900 dark:text-white w-8">3</span>
                        <span class="text-yellow-400 text-xl">★</span>
                        <div class="flex-1 h-8 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-400 to-blue-500 rounded-full" style="width: 30%"></div>
                        </div>
                        <span class="text-sm text-zinc-600 dark:text-zinc-400 w-16 text-right">75</span>
                    </div>

                    <!-- 2 Estrellas -->
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-semibold text-zinc-900 dark:text-white w-8">2</span>
                        <span class="text-yellow-400 text-xl">★</span>
                        <div class="flex-1 h-8 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-400 to-blue-500 rounded-full" style="width: 15%"></div>
                        </div>
                        <span class="text-sm text-zinc-600 dark:text-zinc-400 w-16 text-right">38</span>
                    </div>

                    <!-- 1 Estrella -->
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-semibold text-zinc-900 dark:text-white w-8">1</span>
                        <span class="text-yellow-400 text-xl">★</span>
                        <div class="flex-1 h-8 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-400 to-blue-500 rounded-full" style="width: 8%"></div>
                        </div>
                        <span class="text-sm text-zinc-600 dark:text-zinc-400 w-16 text-right">12</span>
                    </div>
                </div>
            </div>

            <!-- Reseñas Recientes -->
            <div class="bg-white dark:bg-zinc-900 border-4 border-orange-500 rounded-2xl p-8 shadow-lg">
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-6 pb-4 border-b-2 border-orange-300">
                    Reseñas recientes
                </h3>

                <div class="space-y-6">
                    <!-- Reseña 1 -->
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-xl border-2 border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="font-bold text-zinc-900 dark:text-white">María Rodríguez</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">12 de Noviembre, 2025</div>
                            </div>
                            <div class="flex gap-0.5 text-yellow-400">
                                ★★★★★
                            </div>
                        </div>
                        <div class="text-xs text-pink-600 dark:text-pink-400 mb-2">📍 La Marquesa</div>
                        <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">
                            Excelente servicio y la comida deliciosa. La pizza estaba perfecta y llegó caliente. Definitivamente volveré a ordenar.
                        </p>
                    </div>

                    <!-- Reseña 2 -->
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-xl border-2 border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="font-bold text-zinc-900 dark:text-white">Juan García</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">11 de Noviembre, 2025</div>
                            </div>
                            <div class="flex gap-0.5 text-yellow-400">
                                ★★★★☆
                            </div>
                        </div>
                        <div class="text-xs text-pink-600 dark:text-pink-400 mb-2">📍 Vips</div>
                        <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">
                            Buena experiencia en general. La hamburguesa estaba rica pero las papas llegaron un poco frías.
                        </p>
                    </div>

                    <!-- Reseña 3 -->
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-xl border-2 border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="font-bold text-zinc-900 dark:text-white">Ana López</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">10 de Noviembre, 2025</div>
                            </div>
                            <div class="flex gap-0.5 text-yellow-400">
                                ★★★★★
                            </div>
                        </div>
                        <div class="text-xs text-pink-600 dark:text-pink-400 mb-2">📍 Chilis</div>
                        <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">
                            Los tacos están increíbles! El sabor auténtico y las salsas están perfectas.
                        </p>
                    </div>

                    <!-- Reseña 4 -->
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-xl border-2 border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="font-bold text-zinc-900 dark:text-white">Carlos Sánchez</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">09 de Noviembre, 2025</div>
                            </div>
                            <div class="flex gap-0.5 text-yellow-400">
                                ★★★☆☆
                            </div>
                        </div>
                        <div class="text-xs text-pink-600 dark:text-pink-400 mb-2">📍 La Marquesa</div>
                        <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">
                            La comida está bien pero el tiempo de espera fue muy largo. Tardaron casi una hora.
                        </p>
                    </div>
                </div>

                <!-- Botón Ver Más -->
                <div class="mt-6 text-center">
                    <flux:button 
                        variant="ghost"
                        class="bg-orange-100 hover:bg-orange-200 text-orange-700 dark:bg-orange-900/30 dark:hover:bg-orange-900/50 dark:text-orange-400"
                    >
                        Ver todas las reseñas
                    </flux:button>
                </div>
            </div>

        </div>

    </div>
</div>
</x-layouts.app>