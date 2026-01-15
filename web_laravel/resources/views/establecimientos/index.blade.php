<?php
/**
 * Nombre del archivo        : index.blade.php
 * Ruta                      : resources/views/establecimientos/index.blade.php
 * Descripción               : Vista de listado de establecimientos con SweetAlert para confirmaciones y alertas.
 * Fecha de creación         : 14/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 17/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Versión                   : 1.12
 * Fecha de mantenimiento    : 2026-01-14
 * Tipo de mantenimiento     : UI/UX - Confirmación y alertas con SweetAlert
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */
?>

<x-layouts.app :title="__('Establecimientos')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Header: título --}}
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    {{-- icono comentado --}}
                    {{-- <flux:icon.building-storefront class="size-8 text-orange-500" /> --}}
                    <flux:heading size="xl">{{ __('Establecimientos') }}</flux:heading>
                </div>

                {{-- Mostrar botón "Nuevo establecimiento" junto al título SOLO si NO hay registros --}}
                @if($establecimientos->count() === 0)
                    <a href="{{ route('establecimientos.create') }}" 
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium shadow-sm transition"
                       aria-label="{{ __('Nuevo establecimiento') }}">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ __('Nuevo establecimiento') }}
                    </a>
                @endif
            </div>

            @if($establecimientos->count() === 0)
                {{-- VISTA VACÍA --}}
                <div class="text-center py-12">
                    <flux:icon.building-storefront class="mx-auto size-20 text-zinc-400 dark:text-zinc-500" />

                    <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-white">
                        {{ __('No tienes establecimientos aún') }}
                    </h3>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('Comienza creando tu primer establecimiento') }}
                    </p>

                    {{-- Botón negro en vista vacía --}}
                    <div class="mt-6">
                        <a href="{{ route('establecimientos.create') }}" 
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-black hover:bg-zinc-900 text-white text-sm font-semibold shadow-md hover:shadow-lg transition"
                           aria-label="{{ __('Crear mi primer establecimiento') }}">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ __('Crear mi primer establecimiento') }}
                        </a>
                    </div>
                </div>
            @else
                {{-- VISTA CON DATOS: Tabla administrativa --}}
                <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">

                    {{-- Barra superior: búsqueda + botón Nuevo establecimiento (naranja) --}}
                    <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex flex-wrap items-center gap-4 justify-between">
                        <div class="flex-1 min-w-[260px]">
                            <flux:input icon="magnifying-glass" placeholder="{{ __('Buscar por nombre, municipio o teléfono') }}" />
                        </div>

                        <div class="flex gap-2">
                            <a href="{{ route('establecimientos.create') }}" 
                               class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium shadow-sm transition"
                               aria-label="{{ __('Nuevo establecimiento') }}">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{ __('Nuevo establecimiento') }}
                            </a>
                        </div>
                    </div>

                    {{-- Tabla responsive --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800">
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider">Establecimiento</th>
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider">Ubicación</th>
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider">Confianza</th>
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider">Estado</th>
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider text-right">Acciones</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                @foreach($establecimientos as $est)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                                        <td class="px-6 py-4 text-sm text-zinc-600 dark:text-zinc-400">#{{ $est->id }}</td>

                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-12 h-12 rounded-lg bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center overflow-hidden">
                                                    @if(!empty($est->imagen_portada))
                                                        <img src="{{ $est->urlImagenPortada() }}" alt="{{ $est->nombre_establecimiento }}" class="w-full h-full object-cover">
                                                    @else
                                                        <flux:icon.building-storefront class="size-6 text-orange-500" />
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-sm font-bold text-zinc-900 dark:text-white">{{ $est->nombre_establecimiento }}</div>
                                                    <div class="text-xs text-zinc-500">{{ $est->telefono_establecimiento ?? '-' }}</div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-sm text-zinc-700 dark:text-zinc-300">
                                                {{ $est->municipio ?? '-' }}, {{ $est->estado ?? '-' }}
                                            </div>
                                            <div class="text-xs text-zinc-500">{{ $est->colonia ?? '' }}</div>
                                        </td>

                                        <td class="px-6 py-4 text-zinc-900 dark:text-zinc-100 font-medium">
                                            {{ isset($est->tipo_establecimiento) ? ucfirst($est->tipo_establecimiento) : __('N/E') }}
                                        </td>

                                        <td class="px-6 py-4">
                                            @php $conf = isset($est->grado_confianza) ? intval($est->grado_confianza) : 0; @endphp
                                            <div class="flex items-center gap-2">
                                                <div class="w-24 bg-zinc-200 dark:bg-zinc-700 h-1.5 rounded-full overflow-hidden">
                                                    <div class="bg-orange-500 h-full" style="width: {{ $conf }}%"></div>
                                                </div>
                                                <span class="text-xs font-medium text-zinc-600 dark:text-zinc-400">{{ $conf }}%</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            @if(!empty($est->verificacion_establecimiento) && $est->verificacion_establecimiento)
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium text-green-800 bg-green-50 shadow-[0_2px_6px_rgba(16,185,129,0.06)] dark:bg-green-900/20 dark:text-green-300">
                                                    {{ __('Verificado') }}
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-sm font-medium text-rose-700 bg-rose-50 shadow-[0_2px_6px_rgba(239,68,68,0.04)] dark:bg-rose-900/10 dark:text-rose-300">
                                                    {{ __('Sin verificar') }}
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('establecimientos.edit', $est) }}" 
                                                   class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium shadow-md transition" 
                                                   title="{{ __('Editar') }}">
                                                    {{ __('Editar') }}
                                                </a>

                                                <form action="{{ route('establecimientos.destroy', $est) }}" method="POST" class="inline form-eliminar-establecimiento">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium shadow-md transition"
                                                            title="{{ __('Eliminar') }}">
                                                        {{ __('Eliminar') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($establecimientos, 'links') && $establecimientos->hasPages())
                        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800">
                            {{ $establecimientos->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
        {{-- Mostrar alerta SweetAlert si hay sesión swal --}}
        @if(session('swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const config = @json(session('swal'));
                    console.log('Ejecutando SweetAlert desde sesión:', config);

                    // Forzar centrado completo para cualquier config enviada desde backend
                    try {
                        if (typeof config === 'object' && config !== null) {
                            config.position = 'center';
                            // Asegurar que muestre el botón de confirmación y estilo si no vienen
                            if (typeof config.showConfirmButton === 'undefined') config.showConfirmButton = true;
                            // Si quieres forzar color negro globalmente, descomenta la siguiente línea:
                            // if (typeof config.confirmButtonColor === 'undefined') config.confirmButtonColor = '#000000';
                            // Normalizar customClass confirmButton si backend lo envía como string vacío
                            if (!config.customClass) config.customClass = {};
                        }
                    } catch (err) {
                        console.warn('No se pudo normalizar config swal:', err);
                    }

                    Swal.fire(config);
                });
            </script>
        @endif

        {{-- Confirmación SweetAlert para eliminar --}}
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const forms = document.querySelectorAll('.form-eliminar-establecimiento');
                forms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        Swal.fire({
                            title: '¿Estás seguro?',
                            text: "Esta acción no se puede deshacer.",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, eliminar',
                            cancelButtonText: 'Cancelar',
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
            /* Opcional: estilos para botones personalizados en SweetAlert */
            .swal2-confirm.custom-red {
                background-color: #d33 !important;
                color: white !important;
            }
            .swal2-cancel.custom-blue {
                background-color: #3085d6 !important;
                color: white !important;
            }

            /* Asegurar que el popup de Swal ocupe colocación centrada y tenga buen ancho en pantallas grandes */
            .swal2-container {
                /* por defecto SweetAlert2 centra, esto refuerza comportamiento en css si hay overrides */
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
            }
            .swal2-popup {
                margin: 0 auto !important;
            }
        </style>
    @endpush
</x-layouts.app>