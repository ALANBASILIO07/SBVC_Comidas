{{-- 
* Nombre de la vista           : index.blade.php
* Descripción de la vista      : Panel de inicio del sistema SBVC Comidas.
* Fecha de creación            : 06/01/2026
* Elaboró                      : Alan Osvaldo Basilio Delgado
* Fecha de liberación          : 06/01/2026
* Autorizó                     : Maileth Patiño Ensastegui
* Version                      : 1.0
* Fecha de mantenimiento       : 06/01/2026
* Folio de mantenimiento       :
* Tipo de mantenimiento        : Estético / Adaptación de Interfaz
* Descripción del mantenimiento: Cambio de layout para coincidir con el estándar de administración.
* Responsable                  : Alan Osvaldo Basilio Delgado
* Revisor                      : Maileth Patiño Ensastegui
--}}

<x-layouts.app :title="__('Inicio')">
    <div class="p-6 w-full max-w-6xl mx-auto">

        {{-- Encabezado estilo Imagen 1 --}}
        <div class="flex items-center justify-between mb-6">
            <flux:heading level="2" size="xl" class="text-2xl !font-black text-black dark:text-white">
                {{ __('Panel de administración') }}
            </flux:heading>

            <flux:text class="text-xs text-black/60 dark:text-white/60">
                {{ __('Resumen general de establecimientos y servicios') }}
            </flux:text>
        </div>

        {{-- Banner de alerta (si no está completo) --}}
        @if (!$registroCompleto)
            <div class="mb-8">
                <div class="bg-orange-50 dark:bg-orange-900/10 border border-orange-200 dark:border-orange-800 rounded-xl p-5 shadow-sm flex items-center justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="p-2 bg-orange-500 rounded-lg">
                            <flux:icon.exclamation-triangle class="size-6 text-white" />
                        </div>
                        <div>
                            <flux:heading level="3" size="sm" class="!font-bold text-orange-900 dark:text-orange-100">
                                ¡Completa tu registro!
                            </flux:heading>
                            <flux:text class="text-orange-700 dark:text-orange-300 text-xs">
                                Necesitas completar tu información para acceder a todas las funciones de la plataforma
                            </flux:text>
                        </div>
                    </div>
                    <flux:button href="{{ route('registro.completar') }}" variant="primary" class="bg-orange-500 hover:bg-orange-600 shadow-md">
                        {{ __('Completar Registro') }}
                    </flux:button>
                </div>
            </div>
        @endif

        {{-- Grid de Tarjetas estilo Imagen 1 --}}
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            
            <!-- Tarjeta 1: Establecimientos -->
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <flux:heading level="3" size="xs" class="text-xs font-medium text-black/60 dark:text-white/60">
                        Establecimientos Activos
                    </flux:heading>
                    <flux:text class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-bold bg-orange-100 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400">
                        Hoy
                    </flux:text>
                </div>
                <flux:text variant="strong" class="mt-3 text-3xl font-bold text-orange-500">
                    {{ number_format($establecimientosCount) }}
                </flux:text>
            </div>

            <!-- Tarjeta 2: Promociones -->
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <flux:heading level="3" size="xs" class="text-xs font-medium text-black/60 dark:text-white/60">
                        Promociones Activas
                    </flux:heading>
                    <flux:text class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-bold bg-orange-100 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400">
                        Hoy
                    </flux:text>
                </div>
                <flux:text variant="strong" class="mt-3 text-3xl font-bold text-orange-500">
                    {{ number_format($promocionesCount) }}
                </flux:text>
            </div>

            <!-- Tarjeta 3: Banners -->
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <flux:heading level="3" size="xs" class="text-xs font-medium text-black/60 dark:text-white/60">
                        Banners Activos
                    </flux:heading>
                    <flux:text class="inline-flex items-center rounded-md px-2 py-1 text-[10px] font-bold bg-orange-100 text-orange-600 dark:bg-orange-500/10 dark:text-orange-400">
                        Hoy
                    </flux:text>
                </div>
                <flux:text variant="strong" class="mt-3 text-3xl font-bold text-orange-500">
                    {{ number_format($bannersCount) }}
                </flux:text>
            </div>

            <!-- Tarjeta 4: Plan Actual (Destacada) -->
            <div class="rounded-xl border border-orange-200 dark:border-orange-800 bg-orange-500 dark:bg-orange-600 p-5 shadow-md">
                <div class="flex items-center justify-between">
                    <flux:heading level="3" size="xs" class="text-xs font-medium text-white/90">
                        Plan Actual
                    </flux:heading>
                    <flux:icon.sparkles class="size-4 text-white/80" />
                </div>
                <div class="flex items-baseline justify-between mt-3">
                    <flux:text variant="strong" class="text-2xl font-bold text-white">
                        {{ $planActual }}
                    </flux:text>
                    @if($registroCompleto && $planRaw !== 'premium')
                        <a href="{{ route('subscripcion.index') }}" class="text-[10px] font-bold bg-white text-orange-600 px-2 py-1 rounded-md hover:bg-orange-50 transition-colors">
                            MEJORAR
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Espacio para contenido adicional o tablas futuras --}}
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-8 text-center shadow-sm">
            <flux:icon.building-storefront class="size-12 mx-auto text-zinc-300 dark:text-zinc-600 mb-4" />
            <flux:heading size="lg" class="text-zinc-500">{{ __('Bienvenido al sistema SBVC Comidas') }}</flux:heading>
            <flux:text class="max-w-md mx-auto mt-2">
                Utiliza el menú lateral para gestionar tus establecimientos, crear promociones y actualizar tus banners publicitarios.
            </flux:text>
        </div>

    </div>
</x-layouts.app>