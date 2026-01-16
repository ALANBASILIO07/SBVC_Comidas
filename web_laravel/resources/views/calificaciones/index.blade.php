<?php
/*
 * Nombre del archivo        : index.blade.php
 * Ruta                      : resources/views/calificaciones/index.blade.php
 * Descripción               : Vista de listado de calificaciones y reseñas.
 * Diseño                    : 
 * - Estado Vacío: Homologado visualmente con Promociones/Banners (Icono en caja bordeada, limpio).
 * - Estado con Datos: Estilo Dashboard (Tarjetas de resumen + Lista limpia).
 * Fecha de creación         : 21/01/2026
 * Versión                   : 1.2
 * Responsable               : Alan Osvaldo Basilio Delgado
 */
?>

<x-layouts.app :title="__('Calificaciones')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto space-y-8">

            {{-- HEADER --}}
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <flux:heading size="xl">{{ __('Calificaciones') }}</flux:heading>
                </div>
            </div>

            {{-- LÓGICA DE ESTADOS --}}

            @if($estadisticas['total'] == 0)
                
                {{-- ESTADO VACÍO (Diseño Consistente: Caja bordeada limpia) --}}
                <div class="text-center py-12">
                    {{-- Contenedor del Icono: Borde NEGRO aplicado --}}
                    <div class="w-28 h-28 rounded-lg flex items-center justify-center mx-auto mb-6 border border-black dark:border-zinc-700">
                        <flux:icon.star class="size-14 text-zinc-400 dark:text-zinc-500" />
                    </div>
                    
                    {{-- Título --}}
                    <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-white">
                        {{ __('No tienes calificaciones aún') }}
                    </h3>
                    
                    {{-- Descripción --}}
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400 max-w-md mx-auto">
                        {{ __('Las opiniones y puntuaciones de tus clientes aparecerán aquí una vez que comiencen a interactuar con tus establecimientos.') }}
                    </p>
                    
                    {{-- Sin botones de acción aquí --}}
                </div>

            @else
                
                {{-- CONTENIDO CON DATOS (Estilo Dashboard) --}}
                
                {{-- 1. TARJETAS DE RESUMEN --}}
                <div class="grid gap-6 sm:grid-cols-3">
                    
                    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 shadow-sm relative overflow-hidden">
                        <div class="flex items-center justify-between relative z-10">
                            <flux:heading level="3" size="xs" class="text-xs font-medium text-black/60 dark:text-white/60">
                                Promedio General
                            </flux:heading>
                            <div class="p-2 bg-orange-100 dark:bg-orange-900/20 rounded-full">
                                <flux:icon.star class="size-4 text-orange-500" variant="solid" />
                            </div>
                        </div>
                        <div class="mt-4 flex items-baseline gap-2">
                            <flux:text variant="strong" class="text-4xl font-black text-zinc-900 dark:text-white">
                                {{ number_format($estadisticas['promedio'], 1) }}
                            </flux:text>
                            <span class="text-sm text-zinc-500">/ 5.0</span>
                        </div>
                        {{-- Estrellas visuales --}}
                        <div class="flex text-yellow-400 text-sm mt-1">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= round($estadisticas['promedio']))
                                    ★
                                @else
                                    <span class="text-zinc-300 dark:text-zinc-600">★</span>
                                @endif
                            @endfor
                        </div>
                    </div>

                    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <flux:heading level="3" size="xs" class="text-xs font-medium text-black/60 dark:text-white/60">
                                Total de Reseñas
                            </flux:heading>
                            <div class="p-2 bg-blue-100 dark:bg-blue-900/20 rounded-full">
                                <flux:icon.users class="size-4 text-blue-500" />
                            </div>
                        </div>
                        <flux:text variant="strong" class="mt-4 text-3xl font-bold text-zinc-900 dark:text-white">
                            {{ number_format($estadisticas['total']) }}
                        </flux:text>
                        <p class="text-xs text-zinc-500 mt-1">Opiniones recibidas</p>
                    </div>

                    <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 shadow-sm">
                        <div class="flex items-center justify-between">
                            <flux:heading level="3" size="xs" class="text-xs font-medium text-black/60 dark:text-white/60">
                                Nuevas este mes
                            </flux:heading>
                            <flux:text class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-bold bg-green-100 text-green-600 dark:bg-green-500/10 dark:text-green-400">
                                Mensual
                            </flux:text>
                        </div>
                        <flux:text variant="strong" class="mt-4 text-3xl font-bold text-zinc-900 dark:text-white">
                            {{ number_format($estadisticas['este_mes']) }}
                        </flux:text>
                        <p class="text-xs text-zinc-500 mt-1">Interacción reciente</p>
                    </div>
                </div>

                {{-- 2. LISTADO DE RESEÑAS --}}
                <div class="space-y-4">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white border-b border-zinc-200 dark:border-zinc-700 pb-2">
                        Últimas Opiniones
                    </h3>

                    @foreach($resenasRecientes as $resena)
                        <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm transition hover:shadow-md">
                            <div class="flex flex-col sm:flex-row gap-4 justify-between items-start">
                                
                                {{-- Info Cliente y Rating --}}
                                <div class="flex gap-4">
                                    {{-- Avatar (Iniciales) --}}
                                    <div class="w-10 h-10 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center text-orange-600 dark:text-orange-400 font-bold text-sm flex-shrink-0">
                                        {{ substr($resena->cliente_nombre, 0, 2) }}
                                    </div>
                                    
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-zinc-900 dark:text-white">{{ $resena->cliente_nombre }}</span>
                                            <span class="text-xs text-zinc-400">•</span>
                                            <span class="text-xs text-zinc-500">{{ $resena->created_at->diffForHumans() }}</span>
                                        </div>
                                        
                                        {{-- Estrellas --}}
                                        <div class="flex text-yellow-400 text-sm mb-2">
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i <= $resena->puntuacion) ★ @else <span class="text-zinc-200 dark:text-zinc-700">★</span> @endif
                                            @endfor
                                        </div>

                                        {{-- Comentario --}}
                                        @if($resena->comentario)
                                            <p class="text-sm text-zinc-600 dark:text-zinc-300 leading-relaxed">
                                                "{{ $resena->comentario }}"
                                            </p>
                                        @else
                                            <p class="text-xs text-zinc-400 italic">Sin comentario escrito.</p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Badge Establecimiento --}}
                                <div class="flex-shrink-0">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-zinc-100 text-zinc-600 dark:bg-zinc-800 dark:text-zinc-400">
                                        <flux:icon.building-storefront class="size-3" />
                                        {{ $resena->establecimiento->nombre_establecimiento }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Paginación --}}
                    @if(method_exists($resenasRecientes, 'links') && $resenasRecientes->hasPages())
                        <div class="pt-4">
                            {{ $resenasRecientes->links() }}
                        </div>
                    @endif
                </div>

            @endif

        </div>
    </div>
</x-layouts.app>