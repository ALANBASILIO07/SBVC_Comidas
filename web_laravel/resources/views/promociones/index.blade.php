<x-layouts.app :title="__('Promociones')">
<div class="py-8 px-4 sm:px-6 lg:px-8">
<div class="max-w-7xl mx-auto space-y-6">

        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <flux:icon.gift class="size-8 text-orange-500" />
                <flux:heading size="xl">{{ __('MIS PROMOCIONES') }}</flux:heading>
            </div>

            <flux:button
                :href="route('promociones.create')"
                wire:navigate
                variant="primary"
                icon="plus"
                class="bg-orange-500 hover:bg-orange-600 text-white"
            >
                {{ __('Nueva promoción') }}
            </flux:button>
        </div>

        @if($promociones->count() === 0)
            {{-- ESTADO VACÍO --}}
            <div class="text-center py-12">
                <flux:icon.gift class="mx-auto size-20 text-gray-300 dark:text-zinc-700" />
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">
                    No tienes promociones aún
                </h3>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Comienza creando tu primera promoción para atraer más clientes
                </p>
                <div class="mt-6">
                    <flux:button
                        :href="route('promociones.create')"
                        wire:navigate
                        class="bg-orange-500 hover:bg-orange-600 text-white"
                    >
                        <flux:icon.plus class="inline size-5 mr-2" />
                        Crear mi primera promoción
                    </flux:button>
                </div>
            </div>
        @else
            {{-- LISTA DE PROMOCIONES --}}
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                <div class="lg:col-span-4 space-y-4">
                    @foreach($promociones as $promo)
                        <div class="bg-gradient-to-r from-orange-400 to-orange-500 rounded-2xl p-6 shadow-lg transition-all duration-300 hover:shadow-xl hover:from-orange-500 hover:to-orange-600">
                            <div class="flex items-center gap-6">

                                {{-- IMAGEN --}}
                                <div class="bg-orange-200/50 rounded-xl size-20 flex-shrink-0 flex items-center justify-center p-2 text-center overflow-hidden">
                                    @if($promo->imagen)
                                        <img
                                            src="{{ asset('storage/' . $promo->imagen) }}"
                                            alt="{{ $promo->titulo }}"
                                            class="w-full h-full object-cover rounded-lg"
                                        >
                                    @else
                                        <span class="text-xs font-semibold text-orange-800">Sin Imagen</span>
                                    @endif
                                </div>

                                {{-- CONTENIDO --}}
                                <div class="flex-grow">
                                    <h3 class="text-xl font-bold text-white mb-1">
                                        {{ $promo->titulo }}
                                    </h3>
                                    <p class="text-sm text-orange-100 mb-2">
                                        {{ Str::limit($promo->descripcion, 100) }}
                                    </p>
                                    <div class="flex items-center gap-4 text-xs text-orange-100">
                                        <span class="flex items-center gap-1">
                                            <flux:icon.building-storefront class="size-4" />
                                            {{ $promo->establecimiento->nombre_establecimiento }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <flux:icon.calendar class="size-4" />
                                            {{ $promo->fecha_inicio->format('d/m/Y') }} - {{ $promo->fecha_final->format('d/m/Y') }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                            {{ $promo->activo && $promo->estaVigente() ? 'bg-green-500 text-white' : 'bg-gray-400 text-white' }}">
                                            {{ $promo->estadoTexto() }}
                                        </span>
                                    </div>
                                </div>

                                {{-- ACCIONES --}}
                                <div class="flex gap-2 flex-shrink-0">
                                    <flux:button
                                        :href="route('promociones.edit', $promo->id)"
                                        wire:navigate
                                        size="sm"
                                        variant="ghost"
                                        icon="pencil"
                                        class="text-white bg-white/10 hover:bg-white/25"
                                        title="Editar promoción"
                                    />
                                    <form action="{{ route('promociones.destroy', $promo->id) }}" method="POST" class="inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button
                                            type="button"
                                            size="sm"
                                            variant="ghost"
                                            icon="trash"
                                            class="text-white bg-white/10 hover:bg-white/25 delete-btn"
                                            title="Eliminar promoción"
                                        />
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- SIDEBAR FILTROS --}}
                <div class="lg:col-span-1">
                    <div class="bg-white dark:bg-zinc-900 rounded-xl p-6 border border-zinc-200 dark:border-zinc-800 sticky top-6">
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white mb-6">
                            Estadísticas
                        </h3>

                        <div class="space-y-4">
                            <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                <p class="text-sm text-green-700 dark:text-green-300 font-medium mb-1">Activas</p>
                                <p class="text-3xl font-bold text-green-600">
                                    {{ $promociones->where('activo', true)->where('fecha_final', '>=', now())->count() }}
                                </p>
                            </div>

                            <div class="p-4 bg-gray-50 dark:bg-zinc-800 rounded-lg">
                                <p class="text-sm text-gray-700 dark:text-gray-300 font-medium mb-1">Total</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                    {{ $promociones->count() }}
                                </p>
                            </div>

                            <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                <p class="text-sm text-red-700 dark:text-red-300 font-medium mb-1">Expiradas</p>
                                <p class="text-3xl font-bold text-red-600">
                                    {{ $promociones->where('fecha_final', '<', now())->count() }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @endif

    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Manejar eliminación con SweetAlert2
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('.delete-form');

                Swal.fire({
                    icon: 'warning',
                    title: '¿Estás seguro?',
                    text: 'Esta acción eliminará la promoción permanentemente',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    draggable: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
</x-layouts.app>
