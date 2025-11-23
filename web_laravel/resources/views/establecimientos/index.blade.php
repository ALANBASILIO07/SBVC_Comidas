<x-layouts.app :title="__('Mis Establecimientos')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-6">

            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-3">
                    <flux:icon.building-storefront class="size-8 text-orange-500" />
                    <flux:heading size="xl">{{ __('MIS ESTABLECIMIENTOS') }}</flux:heading>
                </div>

                <flux:button 
                    :href="route('establecimientos.create')" 
                    wire:navigate
                    variant="primary" 
                    icon="plus"
                    class="bg-orange-500 hover:bg-orange-600 text-white"
                >
                    {{ __('Nuevo establecimiento') }}
                </flux:button>
            </div>

            @if($establecimientos->count() === 0)
                <div class="text-center py-12">
                    <flux:icon.building-storefront class="mx-auto size-20 text-gray-300" />
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">
                        No tienes establecimientos aún
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        Comienza creando tu primer establecimiento
                    </p>
                    <div class="mt-6">
                        <flux:button :href="route('establecimientos.create')" wire:navigate>
                            Crear mi primer establecimiento
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($establecimientos as $est)
                        <div class="bg-white dark:bg-zinc-900 rounded-3xl overflow-hidden border-4 border-orange-500 shadow-lg transition-all hover:shadow-xl hover:scale-105">
                            <div class="bg-gradient-to-br from-orange-200 to-orange-300 h-48 flex items-center justify-center">
                                <flux:icon.building-storefront class="size-24 text-orange-600" />
                            </div>
                            <div class="p-6 space-y-3">
                                <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">
                                    {{ $est->nombre_establecimiento }}
                                </h3>
                                <div class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    <div class="flex items-center gap-2">
                                        <flux:icon.map-pin class="size-4 text-pink-500" />
                                        <span>{{ $est->municipio }}, {{ $est->estado }}</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <flux:icon.phone class="size-4 text-pink-500" />
                                        <span>{{ $est->telefono_establecimiento }}</span>
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold 
                                        {{ $est->verificacion_establecimiento ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-400' }}">
                                        {{ $est->verificacion_establecimiento ? 'Verificado' : 'Sin verificar' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-layouts.app>