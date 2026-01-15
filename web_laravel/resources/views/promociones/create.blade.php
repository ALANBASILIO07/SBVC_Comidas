<?php
/*
 * Nombre del archivo        : create.blade.php
 * Ruta                      : resources/views/promociones/create.blade.php
 * Descripción               : Vista para crear una nueva promoción.
 *                            - Estilo adaptado al create de Establecimientos.
 *                            - Todos los iconos flux:icon están comentados (no mostrarse).
 *                            - Preview de imagen con SweetAlert2 para errores.
 *                            - Botones: Guardar (verde) y Cancelar (rojo). Orden: Guardar a la izquierda.
 *                            - Validación cliente completa para TODOS los campos (mensajes acumulados en Swal).
 * Autor                     : Alan Osvaldo Basilio Delgado (adaptado)
 * Fecha de creación         : 2026-01-14
 * Versión                   : 1.2
 */
?>

<x-layouts.app :title="__('Nueva Promoción')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            {{-- HEADER --}}
            <div class="mb-4">
                <div class="flex items-center gap-3 mb-2">
                    {{-- <flux:icon.gift class="size-10 text-orange-500" /> --}}
                    <flux:heading size="xl" class="text-zinc-900 dark:text-white">
                        {{ __('Nueva Promoción') }}
                    </flux:heading>
                </div>
                <p class="text-sm text-zinc-700 dark:text-zinc-400">
                    {{ __('Crea una nueva promoción para atraer más clientes a tu establecimiento') }}
                </p>

                <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300 mt-2">
                    {{ __('Nota:') }}
                    <span class="text-zinc-600 dark:text-zinc-400">
                        {{ __('Los campos marcados con') }} <span class="font-bold">*</span> {{ __('son obligatorios. Debes completar correctamente todos los campos para continuar.') }}
                    </span>
                </p>
            </div>

            <form action="{{ route('promociones.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="form-promocion" novalidate>
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
                            <div>
                                <label for="establecimientos_id" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('Establecimiento') }} *
                                </label>
                                <select
                                    id="establecimientos_id"
                                    name="establecimientos_id"
                                    required
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                    <option value="">{{ __('Seleccionar...') }}</option>
                                    @foreach($establecimientos as $est)
                                        <option value="{{ $est->id }}" {{ old('establecimientos_id') == $est->id ? 'selected' : '' }}>
                                            {{ $est->nombre_establecimiento }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="sbvc-error-msg" data-error-for="establecimientos_id" style="display:none"></div>
                                @error('establecimientos_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="titulo" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('Título') }} *
                                </label>
                                <input
                                    type="text"
                                    id="titulo"
                                    name="titulo"
                                    value="{{ old('titulo') }}"
                                    required
                                    minlength="3"
                                    maxlength="255"
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    placeholder="{{ __('Ej: 2x1 en pizzas familiares') }}"
                                >
                                <div class="sbvc-error-msg" data-error-for="titulo" style="display:none"></div>
                                @error('titulo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="descripcion" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('Descripción') }} *
                                </label>
                                <textarea
                                    id="descripcion"
                                    name="descripcion"
                                    rows="4"
                                    required
                                    minlength="10"
                                    maxlength="1000"
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 resize-none"
                                    placeholder="{{ __('Describe los detalles de tu promoción...') }}"
                                >{{ old('descripcion') }}</textarea>
                                <div class="sbvc-error-msg" data-error-for="descripcion" style="display:none"></div>
                                @error('descripcion')
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

                                    <label for="imagen" class="cursor-pointer">
                                        <div id="upload-placeholder" class="flex flex-col items-center justify-center space-y-3 py-8">
                                            {{-- <flux:icon.cloud-arrow-up class="size-12 text-orange-500" /> --}}
                                            <p class="text-sm text-zinc-500 dark:text-zinc-400">{{ __('Insertar imagen') }}</p>
                                            <p class="text-xs text-zinc-400 dark:text-zinc-500">PNG, JPG, GIF, WEBP (Max. 2MB)</p>
                                        </div>
                                        <input
                                            type="file"
                                            id="imagen"
                                            name="imagen"
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
                                {{ __('Se recomienda imagen 1200x600px. Máx 2MB.') }}
                            </div>
                            <div class="sbvc-error-msg" data-error-for="imagen" style="display:none"></div>
                            @error('imagen')
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
                                <label for="fecha_final" class="block text-sm font-medium text-zinc-700 dark:text-zinc-300 mb-1">
                                    {{ __('Fecha final') }} *
                                </label>
                                <input
                                    type="date"
                                    id="fecha_final"
                                    name="fecha_final"
                                    value="{{ old('fecha_final') }}"
                                    min="{{ now()->addDay()->format('Y-m-d') }}"
                                    required
                                    class="w-full rounded-lg border border-zinc-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                <div class="sbvc-error-msg" data-error-for="fecha_final" style="display:none"></div>
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
                                        class="w-5 h-5 border-2 border-zinc-300 rounded text-orange-500 focus:ring-orange-500"
                                    >
                                    <span class="text-sm font-medium text-zinc-700 dark:text-zinc-300">
                                        {{ __('Activo') }}
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BOTONES: guardar a la izquierda (más importante), cancelar a la derecha --}}
                <div class="flex flex-col sm:flex-row justify-end items-center gap-4 pt-4">
                    <button
                        type="submit"
                        id="btn-save"
                        class="w-full sm:w-auto px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-lg transition duration-200 text-center shadow-lg flex items-center justify-center gap-2"
                    >
                        {{-- <flux:icon.check class="inline size-5" /> --}}
                        {{ __('Guardar Promoción') }}
                    </button>

                    <a
                        href="{{ route('promociones.index') }}"
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
            const form = document.getElementById('form-promocion');
            const imageInput = document.getElementById('imagen');
            const previewContainer = document.getElementById('preview-container');
            const previewImage = document.getElementById('preview-image');
            const uploadPlaceholder = document.getElementById('upload-placeholder');
            const removeButton = document.getElementById('remove-image');

            // Basic helpers para manejar errores inline + class
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
                console.error('Promociones: elementos de imagen no encontrados');
            } else {
                imageInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) return;

                    // Validar tipo
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
                            alert('{{ __("Tipo de archivo no válido. Solo PNG, JPG, GIF, WEBP.") }}');
                        }
                        imageInput.value = '';
                        return;
                    }

                    // Validar tamaño
                    if (file.size > 2 * 1024 * 1024) {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'warning',
                                title: '{{ __("Archivo demasiado grande") }}',
                                text: '{{ __("La imagen no puede pesar más de 2MB") }}',
                                confirmButtonColor: '#000000',
                                customClass: { confirmButton: 'custom-black' },
                                position: 'center'
                            });
                        } else {
                            alert('{{ __("La imagen no puede pesar más de 2MB") }}');
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

            // Fecha final min dinámico
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
                    clearFieldError(fechaInicio);
                });
            }

            // Validación completa en submit
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

                    // Obtener campos
                    const establecimientos = document.getElementById('establecimientos_id');
                    const titulo = document.getElementById('titulo');
                    const descripcion = document.getElementById('descripcion');
                    const imagen = document.getElementById('imagen');
                    const fInicio = fechaInicio;
                    const fFinal = fechaFinal;

                    // establecimientos_id
                    if (!establecimientos || !establecimientos.value) {
                        pushError(establecimientos, 'establecimientos_id', '{{ __("Debes seleccionar un establecimiento") }}');
                    }

                    // titulo: required, longitud, caracteres prohibidos
                    if (!titulo || !titulo.value.trim()) {
                        pushError(titulo, 'titulo', '{{ __("El título es obligatorio") }}');
                    } else {
                        const t = titulo.value.trim();
                        if (t.length < 3) pushError(titulo, 'titulo', '{{ __("El título debe tener al menos 3 caracteres") }}');
                        else if (t.length > 255) pushError(titulo, 'titulo', '{{ __("El título no debe exceder 255 caracteres") }}');
                        else if (/[<>]/.test(t)) pushError(titulo, 'titulo', '{{ __("El título contiene caracteres no permitidos") }}');
                    }

                    // descripcion: required, longitud, sin < >
                    if (!descripcion || !descripcion.value.trim()) {
                        pushError(descripcion, 'descripcion', '{{ __("La descripción es obligatoria") }}');
                    } else {
                        const d = descripcion.value.trim();
                        if (d.length < 10) pushError(descripcion, 'descripcion', '{{ __("La descripción debe tener al menos 10 caracteres") }}');
                        else if (d.length > 1000) pushError(descripcion, 'descripcion', '{{ __("La descripción no debe exceder 1000 caracteres") }}');
                        else if (/[<>]/.test(d)) pushError(descripcion, 'descripcion', '{{ __("La descripción contiene caracteres no permitidos") }}');
                    }

                    // imagen: validación obligatoria para esta vista (según requerimiento "absolutamente todos")
                    if (!imagen || !imagen.files || imagen.files.length === 0) {
                        pushError(imagen || null, 'imagen', '{{ __("Debes subir una imagen para la promoción") }}');
                    } else {
                        const file = imagen.files[0];
                        const validTypes = ['image/jpeg','image/jpg','image/png','image/gif','image/webp'];
                        if (validTypes.indexOf(file.type) === -1) pushError(imagen, 'imagen', '{{ __("Tipo de imagen no válido") }}');
                        else if (file.size > 2 * 1024 * 1024) pushError(imagen, 'imagen', '{{ __("La imagen no puede pesar más de 2MB") }}');
                    }

                    // fechas
                    if (!fInicio || !fInicio.value) {
                        pushError(fInicio || null, 'fecha_inicio', '{{ __("La fecha de inicio es obligatoria") }}');
                    }
                    if (!fFinal || !fFinal.value) {
                        pushError(fFinal || null, 'fecha_final', '{{ __("La fecha final es obligatoria") }}');
                    }
                    if (fInicio && fFinal && fInicio.value && fFinal.value) {
                        const fi = new Date(fInicio.value);
                        const ff = new Date(fFinal.value);
                        // Require ff strictly greater than fi (si esa es la lógica). Se requiere al menos 1 día.
                        if (ff <= fi) {
                            pushError(fFinal, 'fecha_final', '{{ __("La fecha final debe ser posterior a la fecha de inicio") }}');
                        }
                    }

                    // Si hay errores, mostrar acumulado con SweetAlert y marcar campos
                    if (errors.length > 0) {
                        const ul = document.createElement('ul');
                        ul.style.textAlign = 'left';
                        errors.forEach(msg => {
                            const li = document.createElement('li');
                            li.style.marginBottom = '6px';
                            li.innerText = msg;
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
                                if (firstInvalid.el) {
                                    firstInvalid.el.focus();
                                } else {
                                    // Buscar primer campo con clase sbvc-invalid si no se capturó el elemento
                                    const fi = document.querySelector('.sbvc-invalid');
                                    if (fi) fi.focus();
                                }
                            });
                        } else {
                            alert(errors.join("\n"));
                        }

                        return false;
                    }

                    // No hay errores -> enviar el formulario (deshabilitar botón para evitar doble submit)
                    const btnSave = document.getElementById('btn-save');
                    if (btnSave) {
                        btnSave.disabled = true;
                        btnSave.classList.add('opacity-70', 'pointer-events-none');
                    }
                    form.submit();
                });
            }

            // Quitar clase de error cuando el usuario corrige algo
            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.addEventListener('input', () => {
                    clearFieldError(el);
                });
                el.addEventListener('change', () => {
                    clearFieldError(el);
                });
            });
        });
    </script>
    @endpush
</x-layouts.app>