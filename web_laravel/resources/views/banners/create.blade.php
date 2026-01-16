<?php
/*
 * Nombre del archivo        : create.blade.php
 * Ruta                      : resources/views/banners/create.blade.php
 * Descripción               : Vista para crear un nuevo banner.
 * - Diseño homologado 100% con Promociones.
 * - Iconos Flux comentados.
 * - Validación JS adaptada a campos de Banner (titulo_banner, fecha_fin, etc).
 * - Limite de imagen ajustado a 5MB (según controlador).
 * Autor                     : Alan Osvaldo Basilio Delgado
 * Fecha de creación         : 2026-01-21
 * Versión                   : 1.0
 */
?>

<x-layouts.app :title="__('Nuevo Banner')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            {{-- HEADER --}}
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-2">
                    {{-- <flux:icon.megaphone class="size-10 text-orange-500" /> --}}
                    <flux:heading size="xl" class="text-zinc-900 dark:text-white">
                        {{ __('Nuevo Banner') }}
                    </flux:heading>
                </div>
                <p class="text-sm text-zinc-700 dark:text-zinc-400">
                    {{ __('Crea un nuevo banner publicitario para destacar tu establecimiento.') }}
                </p>

                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mt-2">
                    {{ __('Nota:') }}
                    <span class="text-zinc-600 dark:text-zinc-400">
                        {{ __('Los campos marcados con') }} <span class="font-bold">*</span> {{ __('son obligatorios. Completa el formulario para continuar.') }}
                    </span>
                </p>
            </div>

            <form action="{{ route('banners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="form-banner" novalidate>
                @csrf

                {{-- GRID SUPERIOR: INFORMACIÓN GENERAL E IMAGEN --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- CARD: INFORMACIÓN GENERAL --}}
                    <div class="lg:col-span-2 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center gap-3">
                                {{-- <flux:icon.information-circle class="size-6 text-orange-500" /> --}}
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('Información general') }}</h3>
                            </div>
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
                                        <option value="{{ $est->id }}" {{ old('establecimiento_id') == $est->id ? 'selected' : '' }}>
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
                                    value="{{ old('titulo_banner') }}"
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

                            {{-- Descripción (Opcional según controller, pero recomendado validar longitud) --}}
                            <div>
                                <label for="descripcion_banner" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('Descripción') }}
                                </label>
                                <textarea
                                    id="descripcion_banner"
                                    name="descripcion_banner"
                                    rows="3"
                                    maxlength="500"
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 resize-none"
                                    placeholder="{{ __('Texto breve para el banner (Opcional)...') }}"
                                >{{ old('descripcion_banner') }}</textarea>
                                <div class="sbvc-error-msg" data-error-for="descripcion_banner" style="display:none"></div>
                                @error('descripcion_banner')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
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
                                    value="{{ old('url_destino') }}"
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    placeholder="https://ejemplo.com/promo"
                                >
                                <div class="sbvc-error-msg" data-error-for="url_destino" style="display:none"></div>
                                @error('url_destino')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- CARD: IMAGEN --}}
                    <div class="lg:col-span-1 bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                        <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
                            <div class="flex items-center gap-3">
                                {{-- <flux:icon.photo class="size-6 text-orange-500" /> --}}
                                <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('Imagen') }}</h3>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="border-2 border-dashed border-zinc-200 dark:border-zinc-700 rounded-xl p-6 bg-gray-50 dark:bg-zinc-900 hover:border-zinc-300 transition-colors">
                                <div class="text-center">
                                    <div id="preview-container" class="mb-4 hidden">
                                        <img id="preview-image" src="" alt="Preview" class="w-full h-56 object-cover rounded-lg">
                                    </div>

                                    <label for="imagen_banner" class="cursor-pointer">
                                        <div id="upload-placeholder" class="flex flex-col items-center justify-center space-y-3 py-8">
                                            {{-- <flux:icon.cloud-arrow-up class="size-12 text-orange-500" /> --}}
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Insertar imagen') }}</p>
                                            <p class="text-xs text-zinc-400 dark:text-zinc-500">PNG, JPG, GIF, WEBP (Max. 5MB)</p>
                                        </div>
                                        <input
                                            type="file"
                                            id="imagen_banner"
                                            name="imagen_banner"
                                            accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                                            class="hidden"
                                            aria-describedby="imagen-help"
                                        >
                                    </label>

                                    <button
                                        type="button"
                                        id="remove-image"
                                        class="hidden mt-4 text-sm text-red-600 hover:text-red-700"
                                    >
                                        {{ __('Eliminar imagen') }}
                                    </button>
                                </div>
                            </div>
                            <div id="imagen-help" class="text-xs text-zinc-500 mt-2">
                                {{ __('Se recomienda imagen 1200x600px. Máx 5MB.') }}
                            </div>
                            <div class="sbvc-error-msg" data-error-for="imagen_banner" style="display:none"></div>
                            @error('imagen_banner')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- CARD: VIGENCIA --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 overflow-hidden">
                    <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800">
                        <div class="flex items-center gap-3">
                            {{-- <flux:icon.calendar class="size-6 text-orange-500" /> --}}
                            <h3 class="text-lg font-bold text-zinc-900 dark:text-white">{{ __('Vigencia') }}</h3>
                        </div>
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
                                    value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}"
                                    min="{{ now()->format('Y-m-d') }}"
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
                                    value="{{ old('fecha_fin') }}"
                                    min="{{ now()->addDay()->format('Y-m-d') }}"
                                    required
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                <div class="sbvc-error-msg" data-error-for="fecha_fin" style="display:none"></div>
                                @error('fecha_fin')
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
                                        class="w-5 h-5 border-2 border-zinc-300 rounded text-orange-500 focus:ring-orange-500"
                                    >
                                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                        {{ __('Banner Activo') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4">
                    <button
                        type="submit"
                        id="btn-save"
                        class="w-full sm:w-auto px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition duration-200 text-center shadow-lg flex items-center justify-center gap-2"
                    >
                        {{ __('Guardar Banner') }}
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
            .sbvc-error-msg { color: #dc2626; font-size: 0.875rem; margin-top: 0.35rem; display:block; }
            .sbvc-invalid { border-color: #dc2626 !important; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08) !important; }
            .swal2-popup { max-width: 680px; }
            .swal2-confirm.custom-black { background-color: #000000 !important; color: #ffffff !important; }
        </style>
    @endpush

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Elementos
            const form = document.getElementById('form-banner');
            const imageInput = document.getElementById('imagen_banner');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const removeButton = document.getElementById('remove-image');
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');

            // Basic helpers
            function setFieldError(el, message) {
                if (!el) return;
                el.classList.add('sbvc-invalid');
                const key = el.id || el.name;
                const container = document.querySelector(`[data-error-for="${key}"]`);
                if (container) {
                    container.innerText = message;
                    container.style.display = 'block';
                }
            }
            function clearFieldError(el) {
                if (!el) return;
                el.classList.remove('sbvc-invalid');
                const key = el.id || el.name;
                const container = document.querySelector(`[data-error-for="${key}"]`);
                if (container) {
                    container.innerText = '';
                    container.style.display = 'none';
                }
            }
            function clearAllErrors() {
                document.querySelectorAll('.sbvc-invalid').forEach(e => e.classList.remove('sbvc-invalid'));
                document.querySelectorAll('.sbvc-error-msg').forEach(e => { e.innerText=''; e.style.display='none'; });
            }

            // Preview imagen
            if (!imageInput || !previewContainer || !previewImage || !uploadPlaceholder || !removeButton) {
                console.error('Banners: elementos de imagen no encontrados');
            } else {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    const validTypes = ['image/jpeg','image/jpg','image/png','image/gif','image/webp'];
                    if (validTypes.indexOf(file.type) === -1) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: '{{ __("Tipo de archivo no válido") }}',
                                text: '{{ __("Solo se permiten: PNG, JPG, GIF, WEBP") }}',
                                confirmButtonColor: '#000000',
                                customClass: { confirmButton: 'custom-black' },
                                position: 'center'
                            });
                        } else {
                            alert('{{ __("Tipo de archivo no válido") }}');
                        }
                        imageInput.value = '';
                        return;
                    }

                    // 5MB Max (5 * 1024 * 1024)
                    if (file.size > 5 * 1024 * 1024) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: '{{ __("Archivo demasiado grande") }}',
                                text: '{{ __("La imagen no puede pesar más de 5MB") }}',
                                confirmButtonColor: '#000000',
                                customClass: { confirmButton: 'custom-black' },
                                position: 'center'
                            });
                        } else {
                            alert('{{ __("La imagen no puede pesar más de 5MB") }}');
                        }
                        imageInput.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(evt) {
                        previewImage.src = evt.target.result;
                        previewContainer.classList.remove('hidden');
                        uploadPlaceholder.classList.add('hidden');
                        removeButton.classList.remove('hidden');
                        clearFieldError(imageInput);
                    };
                    reader.readAsDataURL(file);
                });

                removeButton.addEventListener('click', function() {
                    imageInput.value = '';
                    previewImage.src = '';
                    previewContainer.classList.add('hidden');
                    uploadPlaceholder.classList.remove('hidden');
                    removeButton.classList.add('hidden');
                });
            }

            // Fechas dinámicas
            if (fechaInicio && fechaFin) {
                fechaInicio.addEventListener('change', function() {
                    if (this.value) {
                        const minFechaFin = new Date(this.value);
                        minFechaFin.setDate(minFechaFin.getDate() + 1);
                        fechaFin.min = minFechaFin.toISOString().split('T')[0];
                        if (fechaFin.value && new Date(fechaFin.value) <= new Date(this.value)) {
                            // Opcional: limpiar fecha fin si no cumple
                            // fechaFin.value = '';
                        }
                    }
                    clearFieldError(fechaInicio);
                });
            }

            // Validación en submit
            if (form) {
                form.addEventListener('submit', function(evt) {
                    evt.preventDefault();
                    clearAllErrors();

                    const errors = [];
                    const firstInvalid = { el: null };

                    function pushError(el, key, msg) {
                        errors.push(msg);
                        if (el) setFieldError(el, msg);
                        else {
                            const fake = document.querySelector(`[data-error-for="${key}"]`);
                            if (fake) { fake.innerText = msg; fake.style.display = 'block'; }
                        }
                        if (!firstInvalid.el && el) firstInvalid.el = el;
                    }

                    // Campos Banner
                    const est = document.getElementById('establecimiento_id');
                    const tit = document.getElementById('titulo_banner');
                    const img = document.getElementById('imagen_banner');
                    const fIni = fechaInicio;
                    const fEnd = fechaFin;
                    const url = document.getElementById('url_destino');

                    if (!est || !est.value) pushError(est, 'establecimiento_id', '{{ __("Debes seleccionar un establecimiento") }}');
                    
                    if (!tit || !tit.value.trim()) pushError(tit, 'titulo_banner', '{{ __("El título es obligatorio") }}');
                    else if (tit.value.trim().length < 3) pushError(tit, 'titulo_banner', '{{ __("El título debe tener al menos 3 caracteres") }}');

                    // Imagen es obligatoria en create
                    if (!img || !img.files || img.files.length === 0) {
                        pushError(img, 'imagen_banner', '{{ __("Debes subir una imagen para el banner") }}');
                    }

                    if (!fIni || !fIni.value) pushError(fIni, 'fecha_inicio', '{{ __("La fecha de inicio es obligatoria") }}');
                    if (!fEnd || !fEnd.value) pushError(fEnd, 'fecha_fin', '{{ __("La fecha final es obligatoria") }}');
                    
                    if (fIni && fEnd && fIni.value && fEnd.value) {
                        if (new Date(fEnd.value) <= new Date(fIni.value)) {
                            pushError(fEnd, 'fecha_fin', '{{ __("La fecha final debe ser posterior a la de inicio") }}');
                        }
                    }

                    // URL opcional pero si tiene valor, formato simple (el input type=url ya valida algo, pero añadimos lógica extra si se requiere)
                    // Dejamos que el navegador valide type="url"

                    if (errors.length > 0) {
                        const ul = document.createElement('ul');
                        ul.style.textAlign = 'left';
                        errors.forEach(msg => {
                            const li = document.createElement('li');
                            li.innerText = msg;
                            li.style.marginBottom = '5px';
                            ul.appendChild(li);
                        });

                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: '{{ __("Errores en el formulario") }}',
                                html: ul,
                                confirmButtonColor: '#000000',
                                customClass: { confirmButton: 'custom-black' },
                                position: 'center'
                            }).then(() => {
                                if (firstInvalid.el) firstInvalid.el.focus();
                            });
                        } else {
                            alert(errors.join("\n"));
                        }
                        return false;
                    }

                    // Enviar
                    const btnSave = document.getElementById('btn-save');
                    if (btnSave) {
                        btnSave.disabled = true;
                        btnSave.classList.add('opacity-70', 'pointer-events-none');
                        btnSave.innerText = '{{ __("Guardando...") }}';
                    }
                    form.submit();
                });
            }

            // Limpieza al escribir
            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.addEventListener('input', () => clearFieldError(el));
                el.addEventListener('change', () => clearFieldError(el));
            });
        });
    </script>
    @endpush
</x-layouts.app>