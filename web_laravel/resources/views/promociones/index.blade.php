<x-layouts.app :title="__('Promociones')">
<div class="py-8 px-4 sm:px-6 lg:px-8">
<div class="max-w-7xl mx-auto space-y-6">

        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <flux:icon.tag class="size-8 text-orange-500" />
                <flux:heading size="xl">{{ __('PROMOCIONES') }}</flux:heading>
            </div>

            <flux:button 
                :href="route('promociones.create')" 
                wire:navigate
                variant="primary" 
                icon="gift"
                class="bg-orange-500 hover:bg-orange-600 text-white"
            >
                {{ __('Nueva promoción') }}
            </flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            
            <div class="lg:col-span-4 space-y-4">
                
                <div class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl hover:from-orange-500 hover:to-orange-600">
                    <div class="flex items-center gap-6">
                        
                        <div class="bg-orange-200/50 rounded-xl size-20 flex-shrink-0 flex items-center justify-center p-2 text-center">
                            <span class="text-xs font-semibold text-orange-800">Sin Imagen</span>
                        </div>
                        
                        <div class="flex-grow">
                            <h3 class="text-xl font-bold text-white mb-1">
                                2x1 en pizzas familiares
                            </h3>
                            <p class="text-2xl font-bold text-red-100">
                                50% OFF
                            </p>
                        </div>

                        <div class="flex gap-2 flex-shrink-0">
                            <flux:button 
                                size="sm" 
                                variant="ghost" 
                                icon="pencil" 
                                class="text-white bg-white/10 hover:bg-white/25" 
                                title="Editar promoción" 
                            />
                            <flux:button 
                                size="sm" 
                                variant="ghost" 
                                icon="trash" 
                                class="text-white bg-white/10 hover:bg-white/25" 
                                title="Eliminar promoción" 
                            />
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl hover:from-orange-500 hover:to-orange-600">
                    <div class="flex items-center gap-6">
                        
                        <div class="bg-orange-200/50 rounded-xl size-20 flex-shrink-0 flex items-center justify-center p-2 text-center">
                            <span class="text-xs font-semibold text-orange-800">Sin Imagen</span>
                        </div>
                        
                        <div class="flex-grow">
                            <h3 class="text-xl font-bold text-white mb-1">
                                Combo Burger + bebida
                            </h3>
                            <p class="text-2xl font-bold text-red-100">
                                30% OFF
                            </p>
                        </div>

                        <div class="flex gap-2 flex-shrink-0">
                            <flux:button 
                                size="sm" 
                                variant="ghost" 
                                icon="pencil" 
                                class="text-white bg-white/10 hover:bg-white/25" 
                                title="Editar promoción" 
                            />
                            <flux:button 
                                size="sm" 
                                variant="ghost" 
                                icon="trash" 
                                class="text-white bg-white/10 hover:bg-white/25" 
                                title="Eliminar promoción" 
                            />
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl hover:from-orange-500 hover:to-orange-600">
                    <div class="flex items-center gap-6">

                        <div class="bg-orange-200/50 rounded-xl size-20 flex-shrink-0 flex items-center justify-center p-2 text-center">
                            <span class="text-xs font-semibold text-orange-800">Sin Imagen</span>
                        </div>
                        
                        <div class="flex-grow">
                            <h3 class="text-xl font-bold text-white mb-1">
                                Martes de tacos
                            </h3>
                            <p class="text-2xl font-bold text-red-100">
                                20% OFF
                            </p>
                        </div>

                        <div class="flex gap-2 flex-shrink-0">
                            <flux:button 
                                size="sm" 
                                variant="ghost" 
                                icon="pencil" 
                                class="text-white bg-white/10 hover:bg-white/25" 
                                title="Editar promoción" 
                            />
                            <flux:button 
                                size="sm" 
                                variant="ghost" 
                                icon="trash" 
                                class="text-white bg-white/10 hover:bg-white/25" 
                                title="Eliminar promoción" 
                            />
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl hover:from-orange-500 hover:to-orange-600">
                    <div class="flex items-center gap-6">

                        <div class="bg-orange-200/50 rounded-xl size-20 flex-shrink-0 flex items-center justify-center p-2 text-center">
                            <span class="text-xs font-semibold text-orange-800">Sin Imagen</span>
                        </div>
                        
                        <div class="flex-grow">
                            <h3 class="text-xl font-bold text-white mb-1">
                                ¡Cupón por $100!
                            </h3>
                            <p class="text-sm text-orange-100 mt-1">
                                *Aplica restricciones
                            </p>
                        </div>

                        <div class="flex gap-2 flex-shrink-0">
                            <flux:button 
                                size="sm" 
                                variant="ghost" 
                                icon="pencil" 
                                class="text-white bg-white/10 hover:bg-white/25" 
                                title="Editar promoción" 
                            />
                            <flux:button 
                                size="sm" 
                                variant="ghost" 
                                icon="trash" 
                                class="text-white bg-white/10 hover:bg-white/25" 
                                title="Eliminar promoción" 
                            />
                        </div>
                    </div>
                </div>

                {{-- <div class... --}}

            </div>

            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-zinc-200 dark:border-zinc-800 sticky top-6">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-6">
                        Filtro de promociones
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <flux:label class="mb-2">Rango de precios</flux:label>
                            <flux:select>
                                <option value="">Todos los precios</option>
                                <option value="0-50">$0 - $50</option>
                                <option value="50-100">$50 - $100</option>
                                <option value="100-200">$100 - $200</option>
                                <option value="200+">$200+</option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:label class="mb-2">Categorías de comida</flux:label>
                            <flux:select>
                                <option value="">Todas las categorías</option>
                                <option value="pizzas">Pizzas</option>
                                <option value="hamburguesas">Hamburguesas</option>
                                <option value="tacos">Tacos</option>
                                <option value="bebidas">Bebidas</option>
                                <option value="postres">Postres</option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:label class="mb-2">Tipo de promoción</flux:label>
                            <flux:select>
                                <option value="">Todos los tipos</option>
                                <option value="descuento">Descuento %</option>
                                <option value="2x1">2x1</option>
                                <option value="combo">Combo</option>
                                <option value="cupon">Cupón</option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:label class="mb-2">Disponibilidad</flux:label>
                            <flux:select>
                                <option value="">Todas</option>
                                <option value="activas">Activas</option>
                                <option value="programadas">Programadas</option>
                                <option value="expiradas">Expiradas</option>
                                <option value="pausadas">Pausadas</option>
                            </flux:select>
                        </div>

                        <div class="flex flex-col gap-3 pt-4">
                            <flux:button 
                                variant="primary" 
                                class="w-full bg-green-600 hover:bg-green-700"
                            >
                                Aplicar filtros
                            </flux:button>
                            <flux:button 
                                variant="ghost"
                                class="w-full bg-zinc-200 hover:bg-zinc-300 text-zinc-800 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-zinc-200"
                            >
                                Limpiar
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
</x-layouts.app>