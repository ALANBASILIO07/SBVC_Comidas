<?php
/*
 * Nombre del archivo        : index.blade.php
 * Ruta                      : resources/views/promociones/index.blade.php
 * Descripción               : Vista de listado de promociones. Adaptada visualmente
 *                            para que coincida con el estilo de Establecimientos.
 *                            - Soporte modo oscuro.
 *                            - Botón "Nueva promoción" naranja cuando hay registros (en la cabecera).
 *                            - Botón negro grande en estado vacío ("Crear mi primera promoción").
 *                            - Todas las alertas/confirmaciones manejadas con SweetAlert2.
 *                            - Visualización de imagen corregida (sin debug).
 * Autor                     : Alan Osvaldo Basilio Delgado
 * Fecha de creación         : 2026-01-14
 * Versión                   : 1.7
 * Responsable               : Alan Osvaldo Basilio Delgado
 */
?>

<x-layouts.app :title="__('Promociones')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Header: título --}}
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <flux:heading size="xl">{{ __('Promociones') }}</flux:heading>
                </div>

                @if($promociones->count() === 0)
                    <a href="{{ route('promociones.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium shadow-sm transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ __('Nueva promoción') }}
                    </a>
                @endif
            </div>

            @if($promociones->count() === 0)
                {{-- VISTA VACÍA --}}
                <div class="text-center py-12">
                    <div class="w-28 h-28 rounded-lg flex items-center justify-center mx-auto mb-6 border border-zinc-700">
                        <flux:icon.gift class="size-14 text-zinc-400 dark:text-zinc-500" />
                    </div>
                    <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-white">{{ __('No tienes promociones aún') }}</h3>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">{{ __('Comienza creando tu primera promoción para atraer más clientes.') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('promociones.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-black hover:bg-zinc-900 text-white text-sm font-semibold shadow-md transition">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ __('Crear mi primera promoción') }}
                        </a>
                    </div>
                </div>
            @else
                {{-- VISTA CON DATOS --}}
                <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">

                    <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex flex-wrap items-center gap-4 justify-between">
                        <div class="flex-1 min-w-[260px]">
                            <flux:input icon="magnifying-glass" placeholder="{{ __('Buscar promociones...') }}" />
                        </div>
                        <a href="{{ route('promociones.create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium shadow-sm transition">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ __('Nueva promoción') }}
                        </a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-zinc-50 dark:bg-zinc-800/50 border-b border-zinc-200 dark:border-zinc-800">
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider w-32">{{ __('Imagen') }}</th>
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('Título / Establecimiento') }}</th>
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider">{{ __('Vigencia') }}</th>
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider text-center w-28">{{ __('Estado') }}</th>
                                    <th class="px-6 py-4 text-xs font-bold text-zinc-500 uppercase tracking-wider text-right w-40">{{ __('Acciones') }}</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-800">
                                @foreach($promociones as $promo)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                                        <td class="px-6 py-4">
                                            {{-- Contenedor de imagen optimizado --}}
                                            <div class="w-20 h-14 rounded-lg bg-zinc-100 dark:bg-zinc-800/50 flex items-center justify-center overflow-hidden border border-zinc-200 dark:border-zinc-700 shadow-sm">
                                                @if($promo->imagen)
                                                    <img src="{{ asset('storage/' . $promo->imagen) }}" 
                                                         alt="{{ $promo->titulo }}" 
                                                         class="w-full h-full object-cover"
                                                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder-promo.png') }}'; this.classList.add('p-2', 'opacity-50');">
                                                @else
                                                    <flux:icon.gift class="size-6 text-zinc-400" />
                                                @endif
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-zinc-900 dark:text-white">{{ $promo->titulo }}</div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 flex items-center gap-1">
                                                <flux:icon.building-storefront class="size-3" />
                                                <span>{{ $promo->establecimiento->nombre_establecimiento }}</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4">
                                            <div class="text-sm text-zinc-700 dark:text-zinc-300">
                                                {{ $promo->fecha_inicio->format('d/m/Y') }} - {{ $promo->fecha_final->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">{{ $promo->resumenVigencia() }}</div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $estado = $promo->estadoTexto();
                                                $statusColor = match($estado) {
                                                    'Vigente' => 'text-green-800 bg-green-50 dark:bg-green-900/20 dark:text-green-300',
                                                    'Inactiva' => 'text-zinc-800 bg-zinc-100 dark:bg-zinc-800/10 dark:text-zinc-300',
                                                    'Expirada' => 'text-rose-700 bg-rose-50 dark:bg-rose-900/10 dark:text-rose-300',
                                                    'Próximamente' => 'text-sky-800 bg-sky-50 dark:bg-sky-900/10 dark:text-sky-300',
                                                    default => 'bg-gray-200 text-gray-700'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColor }}">{{ $estado }}</span>
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('promociones.edit', $promo->id) }}" class="px-3 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition">{{ __('Editar') }}</a>
                                                <form action="{{ route('promociones.destroy', $promo->id) }}" method="POST" class="inline form-eliminar-promocion">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition">{{ __('Eliminar') }}</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if(method_exists($promociones, 'links') && $promociones->hasPages())
                        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800">{{ $promociones->links() }}</div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        @if(session('swal'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    Swal.fire(@json(session('swal')));
                });
            </script>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.form-eliminar-promocion').forEach(form => {
                    form.addEventListener('submit', (e) => {
                        e.preventDefault();
                        Swal.fire({
                            title: '{{ __("¿Estás seguro?") }}',
                            text: "{{ __('Esta acción eliminará la promoción permanentemente.') }}",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: '{{ __("Sí, eliminar") }}',
                            cancelButtonText: '{{ __("Cancelar") }}',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.isConfirmed) form.submit();
                        });
                    });
                });
            });
        </script>
    @endpush
</x-layouts.app>