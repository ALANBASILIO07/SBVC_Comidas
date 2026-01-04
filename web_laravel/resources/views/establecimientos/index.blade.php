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

            <!-- Filtros -->
            <form method="GET" action="{{ route('establecimientos.index') }}" class="bg-white dark:bg-zinc-900 rounded-2xl border-4 border-orange-500 p-6 shadow-lg">
                <div class="flex flex-wrap gap-4">
                    <!-- Filtro por Tipo de Establecimiento -->
                    <div class="flex-1 min-w-[200px]">
                        <label class="block text-sm font-semibold text-zinc-900 dark:text-white mb-2">
                            Tipo de Establecimiento
                        </label>
                        <flux:select name="tipo" onchange="this.form.submit()">
                            <option value="">Todos los tipos</option>
                            <option value="Restaurante" {{ request('tipo') == 'Restaurante' ? 'selected' : '' }}>Restaurante</option>
                            <option value="Cafetería" {{ request('tipo') == 'Cafetería' ? 'selected' : '' }}>Cafetería</option>
                            <option value="Food Truck" {{ request('tipo') == 'Food Truck' ? 'selected' : '' }}>Food Truck</option>
                            <option value="Panadería" {{ request('tipo') == 'Panadería' ? 'selected' : '' }}>Panadería</option>
                            <option value="Bar" {{ request('tipo') == 'Bar' ? 'selected' : '' }}>Bar</option>
                        </flux:select>
                    </div>

                    <!-- Filtro por Categoría -->
                    <div class="flex-1 min-w-[250px]">
                        <label class="block text-sm font-semibold text-zinc-900 dark:text-white mb-2">
                            Categoría
                        </label>
                        <flux:select name="categoria" onchange="this.form.submit()">
                            <option value="">Todas las categorías</option>
                            @foreach($categorias as $tipo => $cats)
                                <optgroup label="{{ $tipo }}">
                                    @foreach($cats as $cat)
                                        <option value="{{ $cat->id }}" {{ request('categoria') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->nombre }}
                                        </option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </flux:select>
                    </div>

                    <!-- Botón para limpiar filtros -->
                    @if(request()->hasAny(['tipo', 'categoria']))
                    <div class="flex items-end">
                        <flux:button
                            href="{{ route('establecimientos.index') }}"
                            variant="ghost"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700 dark:text-gray-300"
                        >
                            Limpiar filtros
                        </flux:button>
                    </div>
                    @endif
                </div>
            </form>

            @if($establecimientos->count() === 0)
                <div class="text-center py-12">
                    <flux:icon.building-storefront class="mx-auto size-20 text-gray-300" />
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">
                        @if(request()->hasAny(['tipo', 'categoria']))
                            No se encontraron establecimientos con los filtros seleccionados
                        @else
                            No tienes establecimientos aún
                        @endif
                    </h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        @if(request()->hasAny(['tipo', 'categoria']))
                            Intenta con otros criterios de búsqueda
                        @else
                            Comienza creando tu primer establecimiento
                        @endif
                    </p>
                    <div class="mt-6">
                        @if(request()->hasAny(['tipo', 'categoria']))
                            <flux:button :href="route('establecimientos.index')" wire:navigate>
                                Ver todos los establecimientos
                            </flux:button>
                        @else
                            <flux:button :href="route('establecimientos.create')" wire:navigate>
                                Crear mi primer establecimiento
                            </flux:button>
                        @endif
                    </div>
                </div>
            @else
                <!-- Contador de resultados -->
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    Mostrando {{ $establecimientos->count() }}
                    {{ $establecimientos->count() === 1 ? 'establecimiento' : 'establecimientos' }}
                    @if(request()->hasAny(['tipo', 'categoria']))
                        <span class="font-semibold text-orange-600 dark:text-orange-400">
                            (filtrados)
                        </span>
                    @endif
                </div>

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

                                <!-- Categoría del establecimiento -->
                                @if($est->categoria)
                                <div class="pb-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300">
                                        {{ $est->categoria->nombre }}
                                    </span>
                                </div>
                                @endif

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
