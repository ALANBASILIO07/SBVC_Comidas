<x-layouts.app :title="__('Establecimientos')">
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

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            
            <div class="lg:col-span-4">
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                    
                    <!-- Establecimiento 1: La Marquesa -->
                    <div class="bg-white dark:bg-zinc-900 rounded-3xl overflow-hidden border-4 border-orange-500 shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                        <!-- Imagen/Icono del establecimiento -->
                        <div class="bg-gradient-to-br from-orange-200 to-orange-300 h-48 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M12 2 L12 12 L8 8" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                                <circle cx="12" cy="12" r="1.5" fill="currentColor"/>
                                <circle cx="8" cy="8" r="1.5" fill="currentColor"/>
                                <circle cx="16" cy="8" r="1.5" fill="currentColor"/>
                                <circle cx="8" cy="16" r="1.5" fill="currentColor"/>
                                <circle cx="16" cy="16" r="1.5" fill="currentColor"/>
                                <circle cx="12" cy="16" r="1.5" fill="currentColor"/>
                            </svg>
                        </div>
                        
                        <!-- Información del establecimiento -->
                        <div class="p-6 space-y-3">
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">
                                La Marquesa
                            </h3>
                            
                            <div class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <div class="flex items-center gap-2">
                                    <flux:icon.map-pin class="size-4 text-pink-500 flex-shrink-0" />
                                    <span>Av. Principal #123, Centro</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:icon.phone class="size-4 text-pink-500 flex-shrink-0" />
                                    <span>(555) 123-4567</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:icon.clock class="size-4 text-pink-500 flex-shrink-0" />
                                    <span>Lun - Dom: 8:00 AM - 10:00 PM</span>
                                </div>
                            </div>

                            <div class="pt-2">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    ✓ Verificado
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Establecimiento 2: Vips -->
                    <div class="bg-white dark:bg-zinc-900 rounded-3xl overflow-hidden border-4 border-orange-500 shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                        <!-- Imagen/Icono del establecimiento -->
                        <div class="bg-gradient-to-br from-orange-200 to-orange-300 h-48 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <!-- Hamburguesa -->
                                <path d="M20 12 C20 12, 20 10, 12 10 C4 10, 4 12, 4 12 Z" fill="currentColor" opacity="0.3"/>
                                <path d="M4 12 L20 12" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M5 14 L19 14 L18 18 L6 18 Z" fill="currentColor" opacity="0.3"/>
                                <rect x="10" y="6" width="4" height="2" rx="1" fill="currentColor"/>
                            </svg>
                        </div>
                        
                        <!-- Información del establecimiento -->
                        <div class="p-6 space-y-3">
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">
                                Vips
                            </h3>
                            
                            <div class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <div class="flex items-center gap-2">
                                    <flux:icon.map-pin class="size-4 text-pink-500 flex-shrink-0" />
                                    <span>Calle Comercial #456, Plaza Norte</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:icon.phone class="size-4 text-pink-500 flex-shrink-0" />
                                    <span>(555) 234-5678</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:icon.clock class="size-4 text-pink-500 flex-shrink-0" />
                                    <span>Lun - Dom: 7:00 AM - 11:00 PM</span>
                                </div>
                            </div>

                            <div class="pt-2">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    ✓ Verificado
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Establecimiento 3: Chilis -->
                    <div class="bg-white dark:bg-zinc-900 rounded-3xl overflow-hidden border-4 border-orange-500 shadow-lg transition-all duration-300 hover:shadow-xl hover:scale-105">
                        <!-- Imagen/Icono del establecimiento -->
                        <div class="bg-gradient-to-br from-orange-200 to-orange-300 h-48 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <!-- Taco -->
                                <path d="M4 18 Q12 10, 20 18 Z" fill="currentColor" opacity="0.3"/>
                                <path d="M4 18 Q12 10, 20 18" stroke="currentColor" stroke-width="1.5"/>
                                <line x1="8" y1="15" x2="8" y2="17" stroke="currentColor" stroke-width="1.5"/>
                                <line x1="12" y1="13" x2="12" y2="15" stroke="currentColor" stroke-width="1.5"/>
                                <line x1="16" y1="15" x2="16" y2="17" stroke="currentColor" stroke-width="1.5"/>
                            </svg>
                        </div>
                        
                        <!-- Información del establecimiento -->
                        <div class="p-6 space-y-3">
                            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white">
                                Chilis
                            </h3>
                            
                            <div class="space-y-2 text-sm text-zinc-600 dark:text-zinc-400">
                                <div class="flex items-center gap-2">
                                    <flux:icon.map-pin class="size-4 text-pink-500 flex-shrink-0" />
                                    <span>Plaza Central #789, Col. Moderna</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:icon.phone class="size-4 text-pink-500 flex-shrink-0" />
                                    <span>(555) 345-6789</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <flux:icon.clock class="size-4 text-pink-500 flex-shrink-0" />
                                    <span>Lun - Dom: 12:00 PM - 10:00 PM</span>
                                </div>
                            </div>

                            <div class="pt-2">
                                <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    ⏳ Sin verificar
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-zinc-200 dark:border-zinc-800 sticky top-6">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-6">
                        Filtro de establecimientos
                    </h3>

                    <div class="space-y-6">
                        <div>
                            <flux:label class="mb-2">Estado de verificación</flux:label>
                            <flux:select>
                                <option value="">Todos los estados</option>
                                <option value="verificado">Verificados</option>
                                <option value="pendiente">Sin verificar</option>
                                <option value="proceso">En proceso</option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:label class="mb-2">Tipo de cocina</flux:label>
                            <flux:select>
                                <option value="">Todos los tipos</option>
                                <option value="italiana">Italiana</option>
                                <option value="mexicana">Mexicana</option>
                                <option value="americana">Americana</option>
                                <option value="asiática">Asiática</option>
                                <option value="postres">Postres</option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:label class="mb-2">Ubicación</flux:label>
                            <flux:select>
                                <option value="">Todas las ubicaciones</option>
                                <option value="centro">Centro</option>
                                <option value="norte">Zona Norte</option>
                                <option value="sur">Zona Sur</option>
                                <option value="este">Zona Este</option>
                                <option value="oeste">Zona Oeste</option>
                            </flux:select>
                        </div>

                        <div>
                            <flux:label class="mb-2">Horario</flux:label>
                            <flux:select>
                                <option value="">Todos los horarios</option>
                                <option value="manana">Mañana (6am - 12pm)</option>
                                <option value="tarde">Tarde (12pm - 6pm)</option>
                                <option value="noche">Noche (6pm - 12am)</option>
                                <option value="24h">Abierto 24 horas</option>
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