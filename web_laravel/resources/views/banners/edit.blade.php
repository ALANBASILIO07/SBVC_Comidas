<?php
/*
 * Nombre del archivo        : edit.blade.php
 * Ruta                      : resources/views/banners/edit.blade.php
 * Descripción               : Vista para editar un banner existente.
 * Diseño                    : Homologado 100% con Promociones (UX Consistente).
 * Funcionalidad             :
 * - Carga datos actuales.
 * - Previsualización de imagen con lógica "tri-estado" (Original/Nueva/Restaurar).
 * - Validación de fechas y URL.
 * - Límite de imagen: 5MB.
 * Autor                     : Alan Osvaldo Basilio Delgado
 * Fecha de creación         : 2026-01-21
 * Versión                   : 1.0
 */
?>

<x-layouts.app :title="__('Editar Banner')">

    {{-- Variables para JS (Estado inicial de la imagen) --}}
    @php
        $hasImage = !empty($banner->imagen_banner);
        $imageUrl = $hasImage ? Storage::url($banner->imagen_banner) : '';
    @endphp

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            {{-- HEADER --}}
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-2">
                    <flux:heading size="xl" class="text-zinc-900 dark:text-white">
                        {{ __('Editar Banner') }}
                    </flux:heading>
                </div>
                <p class="text-sm text-zinc-700 dark:text-zinc-400">
                    {{ __('Actualiza la información de tu banner publicitario.') }}
                </p>

                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mt-2">
                    {{ __('Nota:') }}
                    <span class="text-zinc-600 dark:text-zinc-400">
                        {{ __('Los campos marcados con') }} <span class="font-bold">*</span> {{ __('son obligatorios.') }}
                        {{ __('Si no deseas cambiar la imagen, déjala vacía.') }}
                    </span>
                </p>
            </div>

            {{-- FORMULARIO --}}
            <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="form-banner" novalidate>
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- COLUMNA IZQUIERDA: DATOS GENERALES --}}
                    <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('Información general') }}</h3>
                        </div>

                        <div class="p-6 space-y-4">
                            
                            {{-- Establecimiento --}}
                            <div>
                                <label for="establecimiento_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('Establecimiento') }} *
                                </label>
                                <select
                                    id="establecimiento_id"
                                    name="establecimiento_id"
                                    required
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                    <option value="">{{ __('Seleccionar...') }}</option>
                                    @foreach($establecimientos as $est)
                                        <option value="{{ $est->id }}" {{ old('establecimiento_id', $banner->establecimiento_id) == $est->id ? 'selected' : '' }}>
                                            {{ $est->nombre_establecimiento }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="sbvc-error-msg" data-error-for="establecimiento_id" style="display:none"></div>
                                @error('establecimiento_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Título Banner --}}
                            <div>
                                <label for="titulo_banner" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('Título del Banner') }} *
                                </label>
                                <input
                                    type="text"
                                    id="titulo_banner"
                                    name="titulo_banner"
                                    value="{{ old('titulo_banner', $banner->titulo_banner) }}"
                                    required
                                    minlength="3"
                                    maxlength="255"
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    placeholder="{{ __('Ej: Gran Apertura') }}"
                                >
                                <div class="sbvc-error-msg" data-error-for="titulo_banner" style="display:none"></div>
                                @error('titulo_banner')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Descripción --}}
                            <div>
                                <label for="descripcion_banner" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('Descripción (Opcional)') }}
                                </label>
                                <textarea
                                    id="descripcion_banner"
                                    name="descripcion_banner"
                                    rows="3"
                                    maxlength="500"
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 resize-none"
                                    placeholder="{{ __('Texto breve para el banner...') }}"
                                >{{ old('descripcion_banner', $banner->descripcion_banner) }}</textarea>
                                <div class="sbvc-error-msg" data-error-for="descripcion_banner" style="display:none"></div>
                            </div>

                            {{-- URL Destino --}}
                            <div>
                                <label for="url_destino" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('URL de destino (Opcional)') }}
                                </label>
                                <input
                                    type="url"
                                    id="url_destino"
                                    name="url_destino"
                                    value="{{ old('url_destino', $banner->url_destino) }}"
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    placeholder="https://ejemplo.com/promo"
                                >
                                <div class="sbvc-error-msg" data-error-for="url_destino" style="display:none"></div>
                            </div>
                        </div>
                    </div>

                    {{-- COLUMNA DERECHA: IMAGEN (LOGICA TRI-ESTADO) --}}
                    <div class="lg:col-span-1 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden h-full">
                        <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('Imagen') }}</h3>
                        </div>

                        <div class="p-6">
                            {{-- Contenedor Visual --}}
                            <div class="w-full aspect-[4/3] rounded-lg border-2 border-dashed border-zinc-200 dark:border-zinc-600 bg-gray-50 dark:bg-zinc-900 flex flex-col items-center justify-center overflow-hidden relative group hover:border-orange-500 transition-colors">
                                
                                {{-- 1. IMAGEN: Se muestra si hay URL válida (original o preview nueva) --}}
                                <img id="img-preview" 
                                     src="{{ $imageUrl }}" 
                                     class="absolute inset-0 w-full h-full object-cover z-10 {{ $hasImage ? '' : 'hidden' }}">

                                {{-- 2. PLACEHOLDER: Se muestra solo si NO hay imagen visible --}}
                                <div id="img-placeholder" class="text-center p-4 z-0 {{ $hasImage ? 'hidden' : '' }}">
                                    <svg class="mx-auto h-12 w-12 text-zinc-400 mb-2" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <p class="text-sm text-zinc-500">{{ __('Click para seleccionar') }}</p>
                                    <p class="text-xs text-zinc-400 mt-1">Max. 5MB</p>
                                </div>

                                {{-- 3. INPUT FILE: Invisible pero cubre todo el área para click --}}
                                <input type="file" name="imagen_banner" id="imagen-input" accept="image/*"
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-30">
                            </div>

                            {{-- Botón y Textos Auxiliares --}}
                            <div class="text-center mt-3">
                                {{-- Botón para cancelar selección nueva y volver a la Original --}}
                                <button type="button" id="btn-reset-image" class="hidden text-sm text-red-600 hover:text-red-800 font-medium underline cursor-pointer z-40 relative">
                                    {{ __('Cancelar selección') }}
                                </button>

                                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-2 text-center">
                                    {{ __('Dejar vacío para mantener la imagen actual.') }}
                                </p>
                                <div class="sbvc-error-msg" data-error-for="imagen_banner" style="display:none"></div>
                                @error('imagen_banner')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD: VIGENCIA --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('Vigencia') }}</h3>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="fecha_inicio" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('Fecha inicio') }} *
                                </label>
                                <input
                                    type="date"
                                    id="fecha_inicio"
                                    name="fecha_inicio"
                                    value="{{ old('fecha_inicio', $banner->fecha_inicio ? $banner->fecha_inicio->format('Y-m-d') : '') }}"
                                    required
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                <div class="sbvc-error-msg" data-error-for="fecha_inicio" style="display:none"></div>
                                @error('fecha_inicio')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="fecha_fin" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('Fecha final') }} *
                                </label>
                                <input
                                    type="date"
                                    id="fecha_fin"
                                    name="fecha_fin"
                                    value="{{ old('fecha_fin', $banner->fecha_fin ? $banner->fecha_fin->format('Y-m-d') : '') }}"
                                    required
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                <div class="sbvc-error-msg" data-error-for="fecha_fin" style="display:none"></div>
                                @error('fecha_fin')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-end pb-2">
                                <label class="flex items-center space-x-2 cursor-pointer group">
                                    <input
                                        type="checkbox"
                                        id="activo"
                                        name="activo"
                                        value="1"
                                        {{ old('activo', $banner->activo) ? 'checked' : '' }}
                                        class="w-5 h-5 border-2 border-zinc-300 rounded text-orange-500 focus:ring-orange-500 group-hover:border-orange-500 transition-colors"
                                    >
                                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300 group-hover:text-orange-600 transition-colors">
                                        {{ __('Banner Activo') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BOTONES DE ACCIÓN --}}
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                    <button
                        type="submit"
                        id="btn-save"
                        class="w-full sm:w-auto px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition duration-200 text-center shadow-lg"
                    >
                        {{ __('Guardar Cambios') }}
                    </button>

                    <a
                        href="{{ route('banners.index') }}"
                        id="btn-cancel"
                        class="w-full sm:w-auto px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-lg transition-colors text-center shadow-sm"
                    >
                        {{ __('Cancelar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
    <style>
        .sbvc-error-msg { color: #dc2626; font-size: 0.8rem; margin-top: 0.25rem; display:block; font-weight: 500; }
        .sbvc-invalid { border-color: #dc2626 !important; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important; }
        /* SweetAlert Custom Black Button */
        body .swal2-container .btn-swal-black {
            background-color: #000 !important; color: #fff !important; border: none; padding: 10px 30px; border-radius: 6px; font-weight: bold;
        }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias
            const form = document.getElementById('form-banner');
            const imgInput = document.getElementById('imagen-input');
            const imgPreview = document.getElementById('img-preview');
            const imgPlaceholder = document.getElementById('img-placeholder');
            const btnReset = document.getElementById('btn-reset-image');
            
            // Estado Inicial desde el servidor (PHP)
            const hasOriginalImage = @json($hasImage);
            const originalImageUrl = @json($imageUrl);

            // Helpers Visuales
            const showSwalError = (title, text) => {
                Swal.fire({
                    icon: 'error',
                    title: title,
                    text: text,
                    confirmButtonText: 'Entendido',
                    buttonsStyling: false,
                    customClass: { confirmButton: 'btn-swal-black' },
                    confirmButtonColor: '#000000'
                });
            };

            const setFieldError = (id, msg) => {
                const el = document.getElementById(id);
                if (el) el.classList.add('sbvc-invalid');
                const errDiv = document.querySelector(`[data-error-for="${id}"]`);
                if (errDiv) { errDiv.innerText = msg; errDiv.style.display = 'block'; }
            };

            const clearAllErrors = () => {
                document.querySelectorAll('.sbvc-invalid').forEach(el => el.classList.remove('sbvc-invalid'));
                document.querySelectorAll('.sbvc-error-msg').forEach(el => { el.innerText = ''; el.style.display = 'none'; });
            };

            // 1. MANEJO DE IMAGEN (Tri-estado)
            
            // Inicializar botón reset si hay imagen original
            if (hasOriginalImage) {
                btnReset.innerText = '{{ __("Cambiar imagen actual") }}';
                btnReset.classList.remove('hidden');
            }

            imgInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return; // Usuario canceló diálogo de archivo

                // Validaciones
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'];
                if (!validTypes.includes(file.type)) {
                    showSwalError('Formato no válido', 'Solo imágenes (JPG, PNG, GIF, WEBP).');
                    this.value = '';
                    return;
                }
                if (file.size > 5 * 1024 * 1024) { // 5MB para banners
                    showSwalError('Archivo muy grande', 'Máximo 5MB por imagen.');
                    this.value = '';
                    return;
                }

                // Mostrar Preview Nueva
                const reader = new FileReader();
                reader.onload = function(evt) {
                    imgPreview.src = evt.target.result;
                    imgPreview.classList.remove('hidden');
                    imgPlaceholder.classList.add('hidden');
                    
                    // Activar botón de "Cancelar selección"
                    btnReset.classList.remove('hidden');
                    btnReset.innerText = '{{ __("Cancelar selección") }}';
                };
                reader.readAsDataURL(file);
            });

            // Acción: Cancelar selección / Restaurar
            btnReset.addEventListener('click', function() {
                // 1. Limpiar input (evita subida)
                imgInput.value = '';

                // 2. Restaurar vista
                if (hasOriginalImage) {
                    imgPreview.src = originalImageUrl;
                    imgPreview.classList.remove('hidden');
                    imgPlaceholder.classList.add('hidden');
                    btnReset.innerText = '{{ __("Cambiar imagen actual") }}';
                } else {
                    // No había imagen antes
                    imgPreview.src = '';
                    imgPreview.classList.add('hidden');
                    imgPlaceholder.classList.remove('hidden');
                    btnReset.classList.add('hidden');
                }
            });

            // 2. FECHAS DINÁMICAS
            const fInicio = document.getElementById('fecha_inicio');
            const fFin = document.getElementById('fecha_fin');
            
            if (fInicio && fFin) {
                fInicio.addEventListener('change', function() {
                    if (this.value) {
                        const d = new Date(this.value);
                        d.setDate(d.getDate() + 1); // Minimo un día después
                        fFin.min = d.toISOString().split('T')[0];
                        // Si la fecha final es menor a la nueva inicial, la limpiamos (opcional, pero buena UX)
                        if (fFin.value && new Date(fFin.value) <= new Date(this.value)) {
                             // fFin.value = ''; 
                        }
                    }
                });
            }

            // 3. SUBMIT
            form.addEventListener('submit', function(ev) {
                ev.preventDefault();
                clearAllErrors();
                let hasError = false;

                // Campos requeridos
                const required = ['establecimiento_id', 'titulo_banner', 'fecha_inicio', 'fecha_fin'];
                required.forEach(id => {
                    const el = document.getElementById(id);
                    if (!el || !el.value.trim()) {
                        setFieldError(id, 'Este campo es obligatorio.');
                        hasError = true;
                    }
                });

                // Validar longitud titulo
                const tit = document.getElementById('titulo_banner');
                if (tit && tit.value.length < 3) {
                    setFieldError('titulo_banner', 'Mínimo 3 caracteres.');
                    hasError = true;
                }

                // Validar Fechas lógica
                if (fInicio.value && fFin.value) {
                    if (new Date(fFin.value) <= new Date(fInicio.value)) {
                        setFieldError('fecha_fin', 'La fecha final debe ser posterior a la inicial.');
                        hasError = true;
                    }
                }

                if (hasError) {
                    showSwalError('Formulario incompleto', 'Por favor corrige los errores marcados en rojo.');
                } else {
                    const btn = document.getElementById('btn-save');
                    btn.disabled = true;
                    btn.innerText = 'Guardando...';
                    this.submit();
                }
            });

            // Limpiar errores al escribir
            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.addEventListener('input', () => {
                    el.classList.remove('sbvc-invalid');
                    const errDiv = document.querySelector(`[data-error-for="${el.id}"]`);
                    if (errDiv) errDiv.style.display = 'none';
                });
            });
        });
    </script>
    @endpush
</x-layouts.app>