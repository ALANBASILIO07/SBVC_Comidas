<x-layouts.app :title="__('Calificaciones')">
<div class="py-8 px-4 sm:px-6 lg:px-8">
<div class="max-w-7xl mx-auto space-y-6">

        <div class="flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="size-10 text-orange-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <flux:heading size="xl">{{ __('CALIFICACIONES') }}</flux:heading>
        </div>

        <!-- Formulario de Filtros -->
        <form method="GET" action="{{ route('calificaciones.index') }}" class="flex gap-4 flex-wrap">
            <!-- Filtro por Establecimiento -->
            <flux:select name="establecimiento" class="flex-1 min-w-[200px]" onchange="this.form.submit()">
                <option value="">Todos los establecimientos ▼</option>
                @foreach($establecimientos as $establecimiento)
                    <option value="{{ $establecimiento->id }}"
                        {{ request('establecimiento') == $establecimiento->id ? 'selected' : '' }}>
                        {{ $establecimiento->nombre_establecimiento }}
                    </option>
                @endforeach
            </flux:select>

            <!-- Filtro por Puntuación -->
            <flux:select name="puntuacion" class="flex-1 min-w-[200px]" onchange="this.form.submit()">
                <option value="">Todas las puntuaciones ▼</option>
                <option value="5" {{ request('puntuacion') == '5' ? 'selected' : '' }}>5 estrellas</option>
                <option value="4" {{ request('puntuacion') == '4' ? 'selected' : '' }}>4 estrellas</option>
                <option value="3" {{ request('puntuacion') == '3' ? 'selected' : '' }}>3 estrellas</option>
                <option value="2" {{ request('puntuacion') == '2' ? 'selected' : '' }}>2 estrellas</option>
                <option value="1" {{ request('puntuacion') == '1' ? 'selected' : '' }}>1 estrella</option>
            </flux:select>

            <!-- Filtro por Orden -->
            <flux:select name="orden" class="flex-1 min-w-[200px]" onchange="this.form.submit()">
                <option value="recientes" {{ request('orden', 'recientes') == 'recientes' ? 'selected' : '' }}>Más recientes</option>
                <option value="antiguas" {{ request('orden') == 'antiguas' ? 'selected' : '' }}>Más antiguas</option>
                <option value="mejor" {{ request('orden') == 'mejor' ? 'selected' : '' }}>Mejor calificadas</option>
                <option value="peor" {{ request('orden') == 'peor' ? 'selected' : '' }}>Peor calificadas</option>
            </flux:select>
        </form>

        <!-- Tarjetas de Estadísticas -->
        <div class="bg-white dark:bg-zinc-900 border-4 border-orange-500 rounded-2xl p-8 shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Calificación Promedio -->
                <div class="text-center p-6 bg-orange-50 dark:bg-orange-950/30 rounded-xl border-2 border-orange-300">
                    <div class="text-6xl font-bold text-orange-600 dark:text-orange-400 mb-2">
                        {{ $estadisticas['promedio'] }}
                    </div>
                    <p class="text-zinc-900 dark:text-white font-semibold mb-3">
                        Calificación promedio
                    </p>
                    <div class="flex justify-center gap-1 text-3xl text-yellow-400">
                        @php
                            $promedio = $estadisticas['promedio'];
                            $estrellas_llenas = floor($promedio);
                            $tiene_media = ($promedio - $estrellas_llenas) >= 0.5;
                        @endphp
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <= $estrellas_llenas)
                                ★
                            @elseif ($i == $estrellas_llenas + 1 && $tiene_media)
                                ★
                            @else
                                ☆
                            @endif
                        @endfor
                    </div>
                </div>

                <!-- Total de Reseñas -->
                <div class="text-center p-6 bg-orange-50 dark:bg-orange-950/30 rounded-xl border-2 border-orange-300">
                    <div class="text-6xl font-bold text-orange-600 dark:text-orange-400 mb-2">
                        {{ number_format($estadisticas['total']) }}
                    </div>
                    <p class="text-zinc-900 dark:text-white font-semibold">
                        Total de reseñas
                    </p>
                </div>

                <!-- Reseñas Este Mes -->
                <div class="text-center p-6 bg-orange-50 dark:bg-orange-950/30 rounded-xl border-2 border-orange-300">
                    <div class="text-6xl font-bold text-orange-600 dark:text-orange-400 mb-2">
                        {{ number_format($estadisticas['este_mes']) }}
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
                    @foreach($distribucion as $puntuacion => $datos)
                    <!-- {{ $puntuacion }} Estrellas -->
                    <div class="flex items-center gap-4">
                        <span class="text-lg font-semibold text-zinc-900 dark:text-white w-8">{{ $puntuacion }}</span>
                        <span class="text-yellow-400 text-xl">★</span>
                        <div class="flex-1 h-8 bg-zinc-200 dark:bg-zinc-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-blue-400 to-blue-500 rounded-full"
                                 style="width: {{ $datos['porcentaje'] }}%"></div>
                        </div>
                        <span class="text-sm text-zinc-600 dark:text-zinc-400 w-16 text-right">{{ $datos['cantidad'] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Reseñas Recientes -->
            <div class="bg-white dark:bg-zinc-900 border-4 border-orange-500 rounded-2xl p-8 shadow-lg">
                <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-6 pb-4 border-b-2 border-orange-300">
                    Reseñas recientes
                </h3>

                <div class="space-y-6">
                    @forelse($resenasRecientes as $resena)
                    <!-- Reseña -->
                    <div class="p-4 bg-zinc-50 dark:bg-zinc-800 rounded-xl border-2 border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-start justify-between mb-2">
                            <div>
                                <div class="font-bold text-zinc-900 dark:text-white">{{ $resena->cliente_nombre }}</div>
                                <div class="text-xs text-zinc-500 dark:text-zinc-400">{{ $resena->fechaFormateada() }}</div>
                            </div>
                            <div class="flex gap-0.5 text-yellow-400">
                                {!! $resena->estrellasTexto() !!}
                            </div>
                        </div>
                        <div class="text-xs text-pink-600 dark:text-pink-400 mb-2">
                            📍 {{ $resena->establecimiento->nombre_establecimiento }}
                        </div>
                        <p class="text-sm text-zinc-700 dark:text-zinc-300 leading-relaxed">
                            {{ $resena->comentario }}
                        </p>
                    </div>
                    @empty
                    <div class="text-center py-8 text-zinc-500 dark:text-zinc-400">
                        <p>No hay reseñas aún</p>
                    </div>
                    @endforelse
                </div>

                <!-- Botón Ver Más -->
                @if($resenasRecientes->count() > 0)
                <div class="mt-6 text-center">
                    <flux:button
                        href="{{ route('calificaciones.todas', request()->query()) }}"
                        variant="ghost"
                        class="bg-orange-100 hover:bg-orange-200 text-orange-700 dark:bg-orange-900/30 dark:hover:bg-orange-900/50 dark:text-orange-400"
                    >
                        Ver todas las reseñas
                    </flux:button>
                </div>
                @endif
            </div>

        </div>

    </div>
</div>
</x-layouts.app>
