<?php
/*
 * Nombre del archivo        : index.blade.php
 * Ruta                      : resources/views/banners/index.blade.php
 * Descripción               : Vista de listado de banners.
 * Diseño homologado al 100% con Promociones.
 * Lógica de botones:
 * - Si no hay banners: Botón en Header + Botón Central.
 * - Si hay banners: Botón en Header DESAPARECE, se muestra en la Tabla.
 * - Vigencia formateada sin decimales.
 * Autor                     : Alan Osvaldo Basilio Delgado
 * Fecha de creación         : 2026-01-14
 * Versión                   : 2.5 (Final Polish)
 * Responsable               : Alan Osvaldo Basilio Delgado
 */
?>

<x-layouts.app :title="__('Banners')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- ========================================================================
                 HEADER: Título y Botón "Nuevo" (Solo si NO hay banners)
                 ======================================================================== --}}
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <flux:heading size="xl">{{ __('Banners') }}</flux:heading>
                </div>

                {{-- 
                    LÓGICA CORREGIDA: 
                    El botón del header SOLO se muestra si hay establecimientos PERO NO hay banners.
                    Cuando hay banners, este botón desaparece y se usa el de la tabla.
                --}}
                @if(isset($establecimientos) && $establecimientos->isNotEmpty() && $banners->isEmpty())
                    <a href="{{ route('banners.create') }}"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium shadow-sm transition">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        {{ __('Nuevo banner') }}
                    </a>
                @endif
            </div>

            {{-- ========================================================================
                 LÓGICA DE ESTADOS
                 ======================================================================== --}}

            {{-- CASO 1: NO HAY ESTABLECIMIENTOS (Bloqueo Inicial - Prioridad Alta) --}}
            @if(!isset($establecimientos) || $establecimientos->isEmpty())
                
                <div class="text-center py-12">
                    <div class="w-28 h-28 rounded-lg flex items-center justify-center mx-auto mb-6 border border-zinc-200 dark:border-zinc-700">
                        {{-- Icono Tienda --}}
                        <flux:icon.building-storefront class="size-14 text-zinc-400 dark:text-zinc-500" />
                    </div>
                    
                    <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-white">
                        {{ __('No tienes establecimientos') }}
                    </h3>
                    
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400 max-w-md mx-auto">
                        {{ __('Para poder crear banners publicitarios, primero necesitas registrar al menos un establecimiento.') }}
                    </p>
                    
                    <div class="mt-6">
                        <a href="{{ route('establecimientos.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-black hover:bg-zinc-900 text-white text-sm font-semibold shadow-md transition">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ __('Crear establecimiento') }}
                        </a>
                    </div>
                </div>

            {{-- CASO 2: HAY ESTABLECIMIENTOS PERO NO BANNERS (Empty State) --}}
            @elseif($banners->isEmpty())

                <div class="text-center py-12">
                    {{-- Icono gris limpio --}}
                    <div class="w-28 h-28 rounded-lg flex items-center justify-center mx-auto mb-6 border border-zinc-700 dark:border-zinc-600">
                        <flux:icon.megaphone class="size-14 text-zinc-400 dark:text-zinc-500" />
                    </div>
                    
                    <h3 class="mt-4 text-lg font-medium text-zinc-900 dark:text-white">
                        {{ __('No tienes banners aún') }}
                    </h3>
                    
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                        {{ __('Comienza creando tu primer banner publicitario para destacar tu negocio.') }}
                    </p>
                    
                    {{-- Botón Negro Grande (Call to Action Principal) --}}
                    <div class="mt-6">
                        <a href="{{ route('banners.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-lg bg-black hover:bg-zinc-900 text-white text-sm font-semibold shadow-md transition">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ __('Crear mi primer banner') }}
                        </a>
                    </div>
                </div>

            {{-- CASO 3: HAY BANNERS (Listado de Datos) --}}
            @else
                
                <div class="bg-white dark:bg-zinc-900 rounded-2xl shadow-sm border border-zinc-200 dark:border-zinc-800 overflow-hidden">
                    
                    {{-- Barra superior de la tabla (Buscador + Botón Nuevo) --}}
                    <div class="p-4 border-b border-zinc-200 dark:border-zinc-800 flex flex-wrap items-center gap-4 justify-between">
                        <div class="flex-1 min-w-[260px]">
                            <flux:input icon="magnifying-glass" placeholder="{{ __('Buscar banners...') }}" />
                        </div>
                        <a href="{{ route('banners.create') }}"
                           class="inline-flex items-center gap-2 px-4 py-2 rounded-md bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium shadow-sm transition">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            {{ __('Nuevo banner') }}
                        </a>
                    </div>

                    {{-- Tabla --}}
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
                                @foreach($banners as $banner)
                                    <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-800/30 transition-colors">
                                        {{-- Imagen --}}
                                        <td class="px-6 py-4">
                                            <div class="w-20 h-14 rounded-lg bg-zinc-100 dark:bg-zinc-800/50 flex items-center justify-center overflow-hidden border border-zinc-200 dark:border-zinc-700 shadow-sm">
                                                @if($banner->imagen_banner)
                                                    <img src="{{ asset('storage/' . $banner->imagen_banner) }}" 
                                                         alt="{{ $banner->titulo_banner }}" 
                                                         class="w-full h-full object-cover"
                                                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder-banner.png') }}'; this.classList.add('p-2', 'opacity-50');">
                                                @else
                                                    <flux:icon.photo class="size-6 text-zinc-400" />
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Info Principal --}}
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-zinc-900 dark:text-white">{{ $banner->titulo_banner }}</div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1 flex items-center gap-1">
                                                <flux:icon.building-storefront class="size-3" />
                                                <span>{{ $banner->establecimiento->nombre_establecimiento }}</span>
                                            </div>
                                        </td>

                                        {{-- Vigencia (SIN DECIMALES) --}}
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-zinc-700 dark:text-zinc-300">
                                                {{ $banner->fecha_inicio->format('d/m/Y') }} - {{ $banner->fecha_fin->format('d/m/Y') }}
                                            </div>
                                            <div class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                                                @if($banner->fecha_fin->isFuture())
                                                    {{-- intval para eliminar decimales --}}
                                                    Vence en {{ intval(now()->diffInDays($banner->fecha_fin)) }} días
                                                @else
                                                    Expiró hace {{ intval(now()->diffInDays($banner->fecha_fin)) }} días
                                                @endif
                                            </div>
                                        </td>

                                        {{-- Estado --}}
                                        <td class="px-6 py-4 text-center">
                                            @php
                                                $estaActivo = $banner->activo && $banner->fecha_inicio <= now() && $banner->fecha_fin >= now();
                                                $haExpirado = $banner->fecha_fin < now();
                                                
                                                if ($estaActivo) {
                                                    $badgeClass = 'text-green-800 bg-green-50 dark:bg-green-900/20 dark:text-green-300';
                                                    $estadoText = 'Activo';
                                                } elseif ($haExpirado) {
                                                    $badgeClass = 'text-rose-700 bg-rose-50 dark:bg-rose-900/10 dark:text-rose-300';
                                                    $estadoText = 'Expirado';
                                                } else {
                                                    $badgeClass = 'text-zinc-800 bg-zinc-100 dark:bg-zinc-800/10 dark:text-zinc-300';
                                                    $estadoText = 'Inactivo';
                                                }
                                            @endphp
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $badgeClass }}">
                                                {{ $estadoText }}
                                            </span>
                                        </td>

                                        {{-- Acciones --}}
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('banners.edit', $banner->id) }}" 
                                                   class="px-3 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm font-medium transition shadow-sm">
                                                    {{ __('Editar') }}
                                                </a>
                                                
                                                <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" class="inline form-eliminar-banner">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" 
                                                            class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm font-medium transition shadow-sm">
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

                    {{-- Paginación --}}
                    @if(method_exists($banners, 'links') && $banners->hasPages())
                        <div class="px-6 py-4 border-t border-zinc-200 dark:border-zinc-800">
                            {{ $banners->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        {{-- Manejo de Alertas de Sesión --}}
        @if(session('swal'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const config = @json(session('swal'));
                    config.confirmButtonColor = '#000000'; // Estilo negro para consistencia
                    Swal.fire(config);
                });
            </script>
        @endif

        {{-- Confirmación de Eliminación --}}
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