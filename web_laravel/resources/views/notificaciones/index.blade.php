<x-layouts.app :title="__('Notificaciones')">
<div class="py-8 px-4 sm:px-6 lg:px-8">
<div class="max-w-7xl mx-auto space-y-6">

        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <flux:icon.bell class="size-8 text-orange-500" />
                <flux:heading size="xl">{{ __('NOTIFICACIONES') }}</flux:heading>
            </div>

            <flux:button 
                variant="ghost" 
                icon="check-circle"
                class="text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-200"
            >
                {{ __('Marcar todas como leídas') }}
            </flux:button>
        </div>

        <!-- Pestañas -->
        <div class="bg-white dark:bg-zinc-900 rounded-xl border border-zinc-200 dark:border-zinc-800 overflow-hidden">
            <div class="flex border-b border-zinc-200 dark:border-zinc-800">
                <button class="flex-1 px-6 py-4 text-center font-semibold text-zinc-900 dark:text-white border-b-2 border-orange-500 bg-orange-50 dark:bg-orange-950/30">
                    Todas
                </button>
                <button class="flex-1 px-6 py-4 text-center font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                    Leídas
                </button>
                <button class="flex-1 px-6 py-4 text-center font-semibold text-zinc-600 dark:text-zinc-400 hover:bg-zinc-50 dark:hover:bg-zinc-800">
                    No leídas
                </button>
            </div>
        </div>

        <!-- Lista de Notificaciones -->
        <div class="space-y-4">
            
            <!-- Notificación 1: Nueva calificación -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border-4 border-orange-300 shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-start gap-6">
                    <!-- Icono -->
                    <div class="flex-shrink-0">
                        <div class="size-16 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center border-2 border-yellow-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-yellow-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="flex-grow">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                                Nueva calificación recibida
                            </h3>
                            <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                Hace 5 minutos
                            </span>
                        </div>
                        
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3 leading-relaxed">
                            María Rodríguez dejó una reseña de 5 estrellas: "Excelente servicio y la comida deliciosa. Definitivamente volveré a ordenar..."
                        </p>

                        <div class="flex items-center gap-3">
                            <flux:button 
                                size="sm" 
                                variant="primary"
                                class="bg-blue-600 hover:bg-blue-700"
                            >
                                Ver reseña
                            </flux:button>
                            <flux:button 
                                size="sm" 
                                variant="ghost"
                                class="bg-zinc-200 hover:bg-zinc-300 text-zinc-800 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-zinc-200"
                            >
                                Descartar
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notificación 2: Promoción activada -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border-4 border-orange-300 shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-start gap-6">
                    <!-- Icono -->
                    <div class="flex-shrink-0">
                        <div class="size-16 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center border-2 border-green-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="flex-grow">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                                Promoción activada exitosamente
                            </h3>
                            <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                Hace 2 horas
                            </span>
                        </div>
                        
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3 leading-relaxed">
                            Tu promoción "2x1 en Pizzas Familiares" ha sido activada y ya es visible para los clientes en la plataforma.
                        </p>

                        <div class="flex items-center gap-3">
                            <flux:button 
                                size="sm" 
                                variant="primary"
                                class="bg-blue-600 hover:bg-blue-700"
                            >
                                Ver promoción
                            </flux:button>
                            <flux:button 
                                size="sm" 
                                variant="ghost"
                                class="bg-zinc-200 hover:bg-zinc-300 text-zinc-800 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-zinc-200"
                            >
                                Descartar
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notificación 3: Promoción próxima a expirar -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border-4 border-orange-300 shadow-lg transition-all duration-300 hover:shadow-xl">
                <div class="flex items-start gap-6">
                    <!-- Icono -->
                    <div class="flex-shrink-0">
                        <div class="size-16 rounded-full bg-orange-100 dark:bg-orange-900/30 flex items-center justify-center border-2 border-orange-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="flex-grow">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                                Promoción próxima a expirar
                            </h3>
                            <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                Hace 5 horas
                            </span>
                        </div>
                        
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3 leading-relaxed">
                            El banner "Campaña Tacos Especiales" expirará en 3 días. Renueva o crea uno nuevo para mantener tu visibilidad.
                        </p>

                        <div class="flex items-center gap-3">
                            <flux:button 
                                size="sm" 
                                variant="primary"
                                class="bg-blue-600 hover:bg-blue-700"
                            >
                                Renovar ahora
                            </flux:button>
                            <flux:button 
                                size="sm" 
                                variant="ghost"
                                class="bg-zinc-200 hover:bg-zinc-300 text-zinc-800 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-zinc-200"
                            >
                                Descartar
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notificación 4: Actualización del sistema -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border-4 border-zinc-300 shadow-lg transition-all duration-300 hover:shadow-xl opacity-75">
                <div class="flex items-start gap-6">
                    <!-- Icono -->
                    <div class="flex-shrink-0">
                        <div class="size-16 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center border-2 border-blue-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="flex-grow">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                                Actualización del sistema
                            </h3>
                            <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                Hace 1 día
                            </span>
                        </div>
                        
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3 leading-relaxed">
                            Hemos añadido nuevas funcionalidades al panel de control. Ahora puedes programar tus promociones con anticipación y gestionar múltiples banners.
                        </p>

                        <div class="flex items-center gap-3">
                            <flux:button 
                                size="sm" 
                                variant="primary"
                                class="bg-blue-600 hover:bg-blue-700"
                            >
                                Conocer más
                            </flux:button>
                            <flux:button 
                                size="sm" 
                                variant="ghost"
                                class="bg-zinc-200 hover:bg-zinc-300 text-zinc-800 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-zinc-200"
                            >
                                Descartar
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notificación 5: Pago pendiente -->
            <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border-4 border-zinc-300 shadow-lg transition-all duration-300 hover:shadow-xl opacity-75">
                <div class="flex items-start gap-6">
                    <!-- Icono -->
                    <div class="flex-shrink-0">
                        <div class="size-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center border-2 border-red-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="size-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                    </div>

                    <!-- Contenido -->
                    <div class="flex-grow">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                                Pago de suscripción pendiente
                            </h3>
                            <span class="text-xs text-zinc-500 dark:text-zinc-400">
                                Hace 2 días
                            </span>
                        </div>
                        
                        <p class="text-sm text-zinc-600 dark:text-zinc-400 mb-3 leading-relaxed">
                            Tu suscripción Premium vence en 5 días. Renueva ahora para no perder acceso a los filtros ilimitados y funciones exclusivas.
                        </p>

                        <div class="flex items-center gap-3">
                            <flux:button 
                                size="sm" 
                                variant="primary"
                                class="bg-blue-600 hover:bg-blue-700"
                            >
                                Renovar ahora
                            </flux:button>
                            <flux:button 
                                size="sm" 
                                variant="ghost"
                                class="bg-zinc-200 hover:bg-zinc-300 text-zinc-800 dark:bg-zinc-700 dark:hover:bg-zinc-600 dark:text-zinc-200"
                            >
                                Descartar
                            </flux:button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Mensaje vacío (cuando no hay notificaciones) -->
        {{-- 
        <div class="text-center py-20">
            <div class="inline-flex items-center justify-center size-20 rounded-full bg-zinc-100 dark:bg-zinc-800 mb-4">
                <flux:icon.bell class="size-10 text-zinc-400" />
            </div>
            <h3 class="text-xl font-bold text-zinc-900 dark:text-white mb-2">
                No tienes notificaciones
            </h3>
            <p class="text-zinc-600 dark:text-zinc-400">
                Las notificaciones nuevas aparecerán aquí
            </p>
        </div>
        --}}

    </div>
</div>
</x-layouts.app>