<x-layouts.app :title="__('Banners')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">
            
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <flux:icon.photo class="size-8 text-orange-500" />
                    <flux:heading size="xl">{{ __('BANNERS') }}</flux:heading>
                </div>

                <flux:button 
                    {{-- Corregí la ruta para que apunte a banners --}}
                    :href="route('banners.create')" 
                    wire:navigate
                    variant="primary" 
                    icon="plus"
                    class="bg-orange-500 hover:bg-orange-600 text-white"
                >
                    {{ __('Nuevo banner') }}
                </flux:button>
            </div>

            <div class="bg-white dark:bg-zinc-900 rounded-xl shadow-lg border border-zinc-200 dark:border-zinc-800">
                
                <nav class="border-b border-zinc-200 dark:border-zinc-700 px-6">
                    <div class="flex flex-wrap items-center gap-x-8 gap-y-2">
                        <a href="#" wire:navigate class="py-4 text-sm font-medium text-orange-600 border-b-2 border-orange-500">
                            {{ __('Todos') }}
                        </a>
                        <a href="#" wire:navigate class="py-4 text-sm font-medium text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 border-b-2 border-transparent">
                            {{ __('Activos') }}
                        </a>
                        <a href="#" wire:navigate class="py-4 text-sm font-medium text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 border-b-2 border-transparent">
                            {{ __('Pausados') }}
                        </a>
                        <a href="#" wire:navigate class="py-4 text-sm font-medium text-zinc-500 hover:text-zinc-700 dark:text-zinc-400 dark:hover:text-zinc-200 border-b-2 border-transparent">
                            {{ __('Finalizados') }}
                        </a>
                    </div>
                </nav>

                <div class="divide-y divide-zinc-200 dark:divide-zinc-800">

                    {{-- 
                      AQUÍ COMIENZA EL BUCLE 
                      Asumimos que pasas una variable $banners a la vista
                    --}}
                    @forelse ($banners as $banner)
                    <div class="p-6 flex items-center flex-wrap gap-6">
                        
                        {{-- 
                          AQUÍ ESTÁ LA LÓGICA DE IMAGEN (TOMADA DE 'PROMOCIONES')
                          Usa 'imagen_url' o el nombre del campo en tu modelo Banner
                        --}}
                        @if ($banner->imagen_url)
                            <img 
                                src="{{ $banner->imagen_url }}" 
                                alt="{{ $banner->nombre }}" 
                                class="size-16 rounded-lg object-cover flex-shrink-0"
                            >
                        @else
                            {{-- Este es tu placeholder (estilo de 'promociones' adaptado) --}}
                            <div class="bg-orange-200/50 rounded-lg size-16 flex-shrink-0 flex items-center justify-center p-2 text-center">
                                <span class="text-xs font-semibold text-orange-800">Sin Imagen</span>
                            </div>
                        @endif
                        
                        <div class="flex-grow min-w-0">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white truncate">
                                {{ $banner->nombre }}
                            </h3>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                <span class="font-medium">Establecimiento:</span> {{ $banner->establecimiento->nombre ?? 'N/A' }}
                            </p>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">
                                {{-- Formatea las fechas (requiere que sean objetos Carbon en el modelo) --}}
                                <span class="font-medium">Vigencia:</span> 
                                {{ $banner->fecha_inicio->format('d M, Y') }} - {{ $banner->fecha_fin->format('d M, Y') }}
                            </p>
                        </div>
                        
                        <div class="flex items-center flex-wrap gap-3 ml-auto flex-shrink-0">
                            
                            {{-- Lógica de Estado (asumiendo un campo 'estado') --}}
                            @if ($banner->estado === 'activo')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                                    Activo
                                </span>
                            @elseif ($banner->estado === 'pausado')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300">
                                    Pausado
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-zinc-200 text-zinc-800 dark:bg-zinc-700 dark:text-zinc-300">
                                    Finalizado
                                </span>
                            @endif

                            {{-- Botones Dinámicos --}}
                            <flux:button 
                                :href="route('banners.edit', $banner)" 
                                wire:navigate 
                                size="sm" 
                                variant="primary" 
                                class="bg-blue-600 hover:bg-blue-700"
                            >
                                Editar
                            </flux:button>
                            
                            @if ($banner->estado === 'activo')
                                <flux:button 
                                    wire:click="pausar({{ $banner->id }})"
                                    size="sm" 
                                    variant="primary" 
                                    class="bg-red-600 hover:bg-red-700"
                                >
                                    Pausar
                                </flux:button>
                            @else
                                <flux:button 
                                    wire:click="activar({{ $banner->id }})"
                                    size="sm" 
                                    variant="primary" 
                                    class="bg-green-600 hover:bg-green-700"
                                >
                                    Activar
                                </flux:button>
                            @endif
                            
                            <flux:button 
                                wire:click="eliminar({{ $banner->id }})"
                                wire:confirm="¿Estás seguro de eliminar este banner?"
                                size="sm" 
                                variant="ghost" 
                                class="bg-zinc-200 hover:bg-zinc-300 text-zinc-800 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-zinc-200" 
                                icon="trash" 
                                title="Eliminar" 
                            />
                        </div>
                    </div>

                    @empty
                    {{-- Mensaje si no hay banners --}}
                    <div class="p-6 text-center">
                        <p class="text-zinc-500 dark:text-zinc-400">No hay banners para mostrar.</p>
                    </div>
                    @endforelse

                </div>

            </div>

        </div>
    </div>
</x-layouts.app>