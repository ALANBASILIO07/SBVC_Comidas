<?php
/*
 * Nombre del archivo        : index.blade.php
 * Ruta                      : resources/views/banners/index.blade.php
 * Descripción               : Vista de listado de banners. Adecuada visualmente
 *                            para mantener consistencia con Establecimientos y Promociones.
 *                            - Soporte modo oscuro.
 *                            - Icono del título comentado.
 *                            - Botón "Nuevo banner" naranja en la cabecera cuando hay establecimientos.
 *                            - Botón negro grande en estado vacío ("Crear mi primer banner").
 *                            - Todas las alertas/confirmaciones con SweetAlert2.
 * Autor                     : Alan Osvaldo Basilio Delgado
 * Fecha de creación         : 2026-01-14
 * Versión                   : 1.3
 * Responsable               : Alan Osvaldo Basilio Delgado
 */
?>

<x-layouts.app :title="__('Banners')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Header: título --}}
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    {{-- <flux:icon.megaphone class="size-8 text-orange-500" /> --}}
                    <flux:heading size="xl">{{ __('Banners') }}</flux:heading>
                </div>

                {{-- Mostrar botón naranja en header SI el usuario tiene establecimientos --}}
                @if(isset($establecimientos) && $establecimientos->isNotEmpty())
                    <a href="{{ route('banners.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium shadow-sm transition"
                       aria-label="{{ __('Nuevo banner') }}">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ __('Nuevo banner') }}
                    </a>
                @endif
            </div>

            {{-- Si no hay establecimientos, mostrar mensaje para crear primero un establecimiento --}}
            @if(!isset($establecimientos) || $establecimientos->isEmpty())
                <div class="text-center py-12">
                    <div class="w-28 h-28 rounded-lg flex items-center justify-center mx-auto mb-6 border border-zinc-700">
                        <flux:icon.building-storefront class="size-14 text-zinc-400 dark:text-zinc-500" />
                    </div>

                    <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-white">
                        {{ __('No tienes establecimientos') }}
                    </h3>

                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('Primero debes crear un establecimiento para poder agregar banners.') }}
                    </p>

                    <div class="mt-6">
                        <a href="{{ route('establecimientos.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-black hover:bg-zinc-900 text-white text-sm font-semibold shadow-md hover:shadow-lg transition"
                           aria-label="{{ __('Crear establecimiento') }}">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ __('Crear establecimiento') }}
                        </a>
                    </div>
                </div>

            @else
                {{-- Si hay establecimientos: mostrar banners o vista vacía de banners --}}
                @if($banners->isEmpty())
                    {{-- VISTA VACÍA (hay establecimientos pero no banners) --}}
                    <div class="text-center py-12">
                        <div class="w-28 h-28 rounded-lg flex items-center justify-center mx-auto mb-6 border border-zinc-700">
                            <flux:icon.megaphone class="size-14 text-zinc-400 dark:text-zinc-500" />
                        </div>

                        <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-white">
                            {{ __('No tienes banners aún') }}
                        </h3>

                        <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                            {{ __('Comienza creando tu primer banner publicitario') }}
                        </p>

                        {{-- Botón negro grande en vista vacía --}}
                        <div class="mt-6">
                            <a href="{{ route('banners.create') }}"
                               class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-black hover:bg-zinc-900 text-white text-sm font-semibold shadow-md hover:shadow-lg transition"
                               aria-label="{{ __('Crear mi primer banner') }}">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ __('Crear mi primer banner') }}
                            </a>
                        </div>
                    </div>

                @else
                    {{-- LISTA DE BANNERS (estilo tarjeta naranja + sidebar estadísticas) --}}
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                        <div class="lg:col-span-4 space-y-4">
                            @foreach($banners as $banner)
                                <div class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl hover:from-orange-500 hover:to-orange-600">
                                    <div class="flex items-center gap-6">

                                        {{-- IMAGEN --}}
                                        <div class="bg-orange-200/50 rounded-xl w-20 h-20 flex-shrink-0 flex items-center justify-center p-2 text-center overflow-hidden border border-orange-100/30">
                                            @if($banner->imagen_banner)
                                                <img
                                                    src="{{ asset('storage/' . $banner->imagen_banner) }}"
                                                    alt="{{ $banner->titulo_banner }}"
                                                    class="w-full h-full object-cover rounded-lg"
                                                >
                                            @else
                                                <span class="text-xs font-semibold text-orange-800">Sin Imagen</span>
                                            @endif
                                        </div>

                                        {{-- CONTENIDO --}}
                                        <div class="flex-grow">
                                            <h3 class="text-xl font-bold text-white mb-1">
                                                {{ $banner->titulo_banner }}
                                            </h3>
                                            <p class="text-sm text-orange-100 mb-2">
                                                {{ Str::limit($banner->descripcion_banner, 100) }}
                                            </p>
                                            <div class="flex items-center gap-4 text-xs text-orange-100">
                                                <span class="flex items-center gap-1">
                                                    <flux:icon.building-storefront class="size-4" />
                                                    {{ $banner->establecimiento->nombre_establecimiento }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <flux:icon.calendar class="size-4" />
                                                    {{ $banner->fecha_inicio->format('d/m/Y') }} - {{ $banner->fecha_fin->format('d/m/Y') }}
                                                </span>
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                                    {{ $banner->estaDisponible() ? 'bg-green-500 text-white' :
                                                       ($banner->haExpirado() ? 'bg-red-500 text-white' : 'bg-gray-400 text-white') }}">
                                                    {{ $banner->estadoTexto() }}
                                                </span>
                                            </div>
                                        </div>

                                        {{-- ACCIONES --}}
                                        <div class="flex gap-2 flex-shrink-0">
                                            <a href="{{ route('banners.edit', $banner->id) }}"
                                               class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/10 hover:bg-white/25 text-white text-sm font-medium shadow-sm transition"
                                               title="{{ __('Editar banner') }}">
                                                {{ __('Editar') }}
                                            </a>

                                            <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="inline form-eliminar-banner">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-white/10 hover:bg-white/25 text-white text-sm font-medium shadow-sm transition"
                                                        title="{{ __('Eliminar banner') }}">
                                                    {{ __('Eliminar') }}
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- SIDEBAR ESTADÍSTICAS --}}
                        <div class="lg:col-span-1">
                            <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-zinc-200 dark:border-zinc-800 sticky top-6">
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-6">
                                    {{ __('Estadísticas') }}
                                </h3>

                                <div class="space-y-4">
                                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <p class="text-sm text-green-700 dark:text-green-300 font-medium mb-1">{{ __('Activos') }}</p>
                                        <p class="text-3xl font-bold text-green-600">
                                            {{ collect($banners)->filter(fn($b) => $b->estaDisponible())->count() }}
                                        </p>
                                    </div>

                                    <div class="p-4 bg-gray-50 dark:bg-zinc-800 rounded-lg">
                                        <p class="text-sm text-gray-700 dark:text-gray-300 font-medium mb-1">{{ __('Total') }}</p>
                                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                            {{ count($banners) }}
                                        </p>
                                    </div>

                                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                        <p class="text-sm text-red-700 dark:text-red-300 font-medium mb-1">{{ __('Expirados') }}</p>
                                        <p class="text-3xl font-bold text-red-600">
                                            {{ collect($banners)->filter(fn($b) => $b->haExpirado())->count() }}
                                        </p>
                                    </div>

                                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                                        <p class="text-sm text-blue-700 dark:text-blue-300 font-medium mb-1">{{ __('Programados') }}</p>
                                        <p class="text-3xl font-bold text-blue-600">
                                            {{ collect($banners)->filter(fn($b) => $b->noHaIniciado())->count() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                @endif
            @endif

        </div>
    </div>

    @push('scripts')
        {{-- Incluir SweetAlert2 si no está cargado globalmente --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Mostrar alerta SweetAlert desde sesión (normalizamos config) --}}
        @if(session('swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const config = @json(session('swal'));
                    try {
                        if (typeof config === 'object' && config !== null) {
                            config.position = config.position || 'center';
                            if (typeof config.showConfirmButton === 'undefined') config.showConfirmButton = true;
                            if (!config.customClass) config.customClass = {};
                        }
                    } catch (err) {
                        console.warn('No se pudo normalizar config swal:', err);
                    }
                    Swal.fire(config);
                });
            </script>
        @endif

        {{-- Confirmación SweetAlert para eliminar banners --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const forms = document.querySelectorAll('.form-eliminar-banner');
                forms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        Swal.fire({
                            title: '{{ __("¿Estás seguro?") }}',
                            text: "{{ __('Esta acción eliminará el banner permanentemente.') }}",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: '{{ __("Sí, eliminar") }}',
                            cancelButtonText: '{{ __("Cancelar") }}',
                            reverseButtons: true,
                            position: 'center',
                            customClass: { confirmButton: 'swal2-confirm custom-red', cancelButton: 'swal2-cancel custom-blue' }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>

        <style>
            .swal2-confirm.custom-red {
                background-color: #d33 !important;
                color: white !important;
            }
            .swal2-cancel.custom-blue {
                background-color: #3085d6 !important;
                color: white !important;
            }
            .swal2-container { display:flex !important; align-items:center !important; justify-content:center !important; }
            .swal2-popup { margin:0 auto !important; }
        </style>
    @endpush
</x-layouts.app>