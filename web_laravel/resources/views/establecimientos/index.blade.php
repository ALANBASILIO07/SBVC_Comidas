<x-layouts.app :title="__('Establecimientos')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto space-y-8">
            
            <!-- Título de la página -->
            <div class="flex items-center gap-3">
                <flux:icon.building-storefront class="size-8 text-orange-500" />
                <flux:heading size="xl">{{ __('ESTABLECIMIENTOS') }}</flux:heading>
            </div>

            <!-- Contenido de prueba -->
            <div class="bg-white dark:bg-zinc-900 rounded-lg p-6">
                <p class="text-zinc-600 dark:text-zinc-400">
                    Vista de establecimientos en construcción...
                </p>
            </div>

        </div>
    </div>
</x-layouts.app>