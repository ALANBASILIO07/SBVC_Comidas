<x-layouts.app :title="__('Nueva Promoción')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto">

            {{-- HEADER --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <flux:icon.gift class="size-10 text-orange-500" />
                    <flux:heading size="xl" class="text-gray-900 dark:text-white">
                        {{ __('Nueva Promoción') }}
                    </flux:heading>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Crea una nueva promoción para atraer más clientes a tu establecimiento
                </p>
            </div>

            {{-- Mensajes de error --}}
            @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <flux:icon.exclamation-triangle class="h-5 w-5 text-red-400" />
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800 dark:text-red-200">
                            Por favor corrige los siguientes errores:
                        </h3>
                        <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('promociones.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

                {{-- GRID SUPERIOR: INFORMACIÓN GENERAL E IMAGEN --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- CARD: INFORMACIÓN GENERAL --}}
                    <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border-2 border-orange-400 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <flux:icon.information-circle class="size-6 text-white" />
                                <h3 class="text-lg font-bold text-white">Información general</h3>
                            </div>
                        </div>

                        <div class="p-6 space-y-4">
                            <div>
                                <label for="establecimientos_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Establecimiento *
                                </label>
                                <select
                                    id="establecimientos_id"
                                    name="establecimientos_id"
                                    required
                                    class="w-full rounded-lg border-orange-400 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                    <option value="">Seleccionar...</option>
                                    @foreach($establecimientos as $est)
                                        <option value="{{ $est->id }}" {{ old('establecimientos_id') == $est->id ? 'selected' : '' }}>
                                            {{ $est->nombre_establecimiento }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('establecimientos_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="titulo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Título *
                                </label>
                                <input
                                    type="text"
                                    id="titulo"
                                    name="titulo"
                                    value="{{ old('titulo') }}"
                                    required
                                    minlength="3"
                                    maxlength="255"
                                    class="w-full rounded-lg border-orange-400 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    placeholder="Ej: 2x1 en pizzas familiares"
                                >
                                @error('titulo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="descripcion" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Descripción *
                                </label>
                                <textarea
                                    id="descripcion"
                                    name="descripcion"
                                    rows="4"
                                    required
                                    minlength="10"
                                    maxlength="1000"
                                    class="w-full rounded-lg border-orange-400 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 resize-none"
                                    placeholder="Describe los detalles de tu promoción..."
                                >{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- CARD: IMAGEN --}}
                    <div class="lg:col-span-1 bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border-2 border-orange-400 overflow-hidden">
                        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                            <div class="flex items-center gap-3">
                                <flux:icon.photo class="size-6 text-white" />
                                <h3 class="text-lg font-bold text-white">Imagen</h3>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="border-2 border-dashed border-orange-400 dark:border-zinc-600 rounded-xl p-6 bg-gray-50 dark:bg-zinc-900 hover:border-orange-500 transition-colors">
                                <div class="text-center">
                                    <div id="preview-container" class="mb-4 hidden">
                                        <img id="preview-image" src="" alt="Preview" class="w-full h-64 object-cover rounded-lg">
                                    </div>

                                    <label for="imagen" class="cursor-pointer">
                                        <div id="upload-placeholder" class="flex flex-col items-center justify-center space-y-3 py-8">
                                            <flux:icon.cloud-arrow-up class="size-12 text-orange-500" />
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Insertar</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">imagen</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">PNG, JPG, GIF, WEBP (Max. 2MB)</p>
                                        </div>
                                        <input
                                            type="file"
                                            id="imagen"
                                            name="imagen"
                                            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                            class="hidden"
                                        >
                                    </label>

                                    <button
                                        type="button"
                                        id="remove-image"
                                        class="hidden mt-4 text-sm text-red-600 hover:text-red-700"
                                    >
                                        Eliminar imagen
                                    </button>
                                </div>
                            </div>
                            @error('imagen')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- CARD: VIGENCIA --}}
                <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-sm border-2 border-orange-400 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.calendar class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">Vigencia</h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Fecha inicio *
                                </label>
                                <input
                                    type="date"
                                    id="fecha_inicio"
                                    name="fecha_inicio"
                                    value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}"
                                    min="{{ now()->format('Y-m-d') }}"
                                    required
                                    class="w-full rounded-lg border-orange-400 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                @error('fecha_inicio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="fecha_final" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Fecha final *
                                </label>
                                <input
                                    type="date"
                                    id="fecha_final"
                                    name="fecha_final"
                                    value="{{ old('fecha_final') }}"
                                    min="{{ now()->addDay()->format('Y-m-d') }}"
                                    required
                                    class="w-full rounded-lg border-orange-400 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                @error('fecha_final')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-end">
                                <label class="flex items-center space-x-2">
                                    <input
                                        type="checkbox"
                                        id="activo"
                                        name="activo"
                                        value="1"
                                        {{ old('activo', true) ? 'checked' : '' }}
                                        class="w-5 h-5 border-2 border-orange-400 rounded text-orange-500 focus:ring-orange-500"
                                    >
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Activo
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a
                        href="{{ route('promociones.index') }}"
                        class="w-full sm:w-auto px-8 py-3 bg-gray-300 dark:bg-zinc-700 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-400 dark:hover:bg-zinc-600 transition-colors text-center shadow-sm"
                    >
                        Cancelar
                    </a>
                    <button
                        type="submit"
                        class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold rounded-lg transition duration-200 text-center shadow-lg"
                    >
                        <flux:icon.check class="inline size-5 mr-2" />
                        Guardar Promoción
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const imageInput = document.getElementById('imagen');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const removeButton = document.getElementById('remove-image');

            console.log('Promociones - Verificando elementos del preview:');
            console.log({
                imageInput: !!imageInput,
                previewContainer: !!previewContainer,
                previewImage: !!previewImage,
                uploadPlaceholder: !!uploadPlaceholder,
                removeButton: !!removeButton
            });

            // Validar que todos los elementos existan antes de agregar event listeners
            if (!imageInput || !previewContainer || !previewImage || !uploadPlaceholder || !removeButton) {
                console.error('Error: No se encontraron todos los elementos necesarios para el preview de imagen');
                return;
            }

            console.log('Promociones - Todos los elementos encontrados, inicializando preview...');

            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (file) {
                    // Validar tamaño del archivo (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        alert('La imagen no puede pesar más de 2MB');
                        imageInput.value = '';
                        return;
                    }

                    const reader = new FileReader();

                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.classList.remove('hidden');
                        uploadPlaceholder.classList.add('hidden');
                        removeButton.classList.remove('hidden');
                    };

                    reader.readAsDataURL(file);
                }
            });

            removeButton.addEventListener('click', function() {
                imageInput.value = '';
                previewImage.src = '';
                previewContainer.classList.add('hidden');
                uploadPlaceholder.classList.remove('hidden');
                removeButton.classList.add('hidden');
            });

            // Validación de fecha final
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFinal = document.getElementById('fecha_final');

            if (fechaInicio && fechaFinal) {
                fechaInicio.addEventListener('change', function() {
                    const minFechaFinal = new Date(this.value);
                    minFechaFinal.setDate(minFechaFinal.getDate() + 1);
                    fechaFinal.min = minFechaFinal.toISOString().split('T')[0];

                    if (fechaFinal.value && new Date(fechaFinal.value) <= new Date(this.value)) {
                        fechaFinal.value = '';
                    }
                });
            }
        });
    </script>
    @endpush
</x-layouts.app>
