<?php
/**
 * Nombre del archivo        : edit.blade.php (Vista: Editar Establecimiento)
 * Descripción               : Vista para editar un establecimiento existente.
 *                            Basada en create.blade.php, pero carga datos del modelo
 *                            `$establecimiento` y permite actualizar vía PUT.
 *                            Incluye:
 *                              - Carga inicial de datos (inputs, select, horarios).
 *                              - Google Maps con marcador inicial a lat/lng del establecimiento.
 *                              - Sincronización Tipo <-> Categoría (soporte "Otro").
 *                              - Validación cliente con SweetAlert2 (alertas centradas).
 *                              - Mensajes inline para campos erróneos.
 * Fecha de creación         : 20/01/2026
 * Elaboró                   : Alan Osvaldo Basilio Delgado
 * Fecha de liberación       : 20/01/2026
 * Autorizó                  : Maileth Patiño Ensastegui
 * Versión                   : 1.0
 * Fecha de mantenimiento    : 2026-01-20
 * Tipo de mantenimiento     : Nueva vista Edit (pre-carga de datos y validación)
 * Responsable               : Alan Osvaldo Basilio Delgado
 * Revisor                   : Maileth Patiño Ensastegui
 */
?>

@php
    // Fallbacks seguros para campos que pueden ser arrays/objetos
    $h = $establecimiento->horarios_establecimiento ?? [];
    $horarios_lv = $h['lunes_viernes'] ?? ['apertura' => '', 'cierre' => ''];
    $horarios_sab = $h['sabados'] ?? ['apertura' => '', 'cierre' => ''];
    $horarios_dom = $h['domingos'] ?? ['apertura' => '', 'cierre' => ''];
    $horarios_fest = $h['festivos'] ?? ['apertura' => '', 'cierre' => ''];
    $abre_festivos = !empty($horarios_fest['apertura']) || !empty($horarios_fest['cierre']);
@endphp

<x-layouts.app :title="__('Editar Establecimiento')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <flux:heading size="xl">Editar Establecimiento</flux:heading>
                </div>
                <p class="text-sm text-zinc-700 dark:text-zinc-400">
                    Modifica la información de tu establecimiento. Guarda los cambios cuando hayas terminado.
                </p>
            </div>

            <form action="{{ route('establecimientos.update', $establecimiento->id) }}" method="POST" id="form-establecimiento" novalidate>
                @csrf
                @method('PUT')

                {{-- DATOS GENERALES --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 mb-6 overflow-hidden">
                    <div class="px-6 py-3 border-b border-zinc-100 dark:border-zinc-800">
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                            Datos Generales
                        </h3>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="nombre_establecimiento" class="block text-sm font-medium mb-1 text-zinc-700 dark:text-zinc-200">Nombre del establecimiento *</label>
                            <input type="text" id="nombre_establecimiento" name="nombre_establecimiento"
                                   value="{{ old('nombre_establecimiento', $establecimiento->nombre_establecimiento) }}" required
                                   class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-orange-300 dark:focus:ring-orange-600/40 px-3 py-2">
                            <div class="sbvc-error-msg" data-error-for="nombre_establecimiento" style="display:none"></div>
                        </div>

                        <div>
                            <label for="tipo_establecimiento" class="block text-sm font-medium mb-1 text-zinc-700 dark:text-zinc-200">Tipo *</label>
                            <select id="tipo_establecimiento" name="tipo_establecimiento" required
                                    class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-orange-300 dark:focus:ring-orange-600/40 px-3 py-2">
                                <option value="">Selecciona...</option>
                                <option value="Restaurante" {{ old('tipo_establecimiento', $establecimiento->tipo_establecimiento) == 'Restaurante' ? 'selected' : '' }}>Restaurante</option>
                                <option value="Cafetería" {{ old('tipo_establecimiento', $establecimiento->tipo_establecimiento) == 'Cafetería' ? 'selected' : '' }}>Cafetería</option>
                                <option value="Food Truck" {{ old('tipo_establecimiento', $establecimiento->tipo_establecimiento) == 'Food Truck' ? 'selected' : '' }}>Food Truck</option>
                                <option value="Panadería" {{ old('tipo_establecimiento', $establecimiento->tipo_establecimiento) == 'Panadería' ? 'selected' : '' }}>Panadería</option>
                                <option value="Bar" {{ old('tipo_establecimiento', $establecimiento->tipo_establecimiento) == 'Bar' ? 'selected' : '' }}>Bar</option>
                                <option value="Otro" {{ in_array(old('tipo_establecimiento', $establecimiento->tipo_establecimiento), ['Otro']) ? 'selected' : '' }}>Otro</option>
                            </select>
                            <div class="sbvc-error-msg" data-error-for="tipo_establecimiento" style="display:none"></div>
                        </div>

                        <div id="otro_tipo_container" class="{{ (old('tipo_establecimiento', $establecimiento->tipo_establecimiento) === 'Otro') ? '' : 'hidden' }} md:col-span-2">
                            <label for="tipo_establecimiento_otro" class="block text-sm font-medium mb-1 text-zinc-700 dark:text-zinc-200">Especifica el tipo *</label>
                            <input type="text" id="tipo_establecimiento_otro" name="tipo_establecimiento_otro"
                                   value="{{ old('tipo_establecimiento_otro', ($establecimiento->tipo_establecimiento != 'Restaurante' && $establecimiento->tipo_establecimiento != 'Cafetería' && $establecimiento->tipo_establecimiento != 'Food Truck' && $establecimiento->tipo_establecimiento != 'Panadería' && $establecimiento->tipo_establecimiento != 'Bar') ? $establecimiento->tipo_establecimiento : '') }}"
                                   class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-orange-300 dark:focus:ring-orange-600/40 px-3 py-2">
                            <div class="sbvc-error-msg" data-error-for="tipo_establecimiento_otro" style="display:none"></div>
                        </div>

                        {{-- Categoría --}}
                        <div>
                            <label for="categoria_id" class="block text-sm font-medium mb-1 text-zinc-700 dark:text-zinc-200">Categoría *</label>

                            {{-- Select para categorías normales --}}
                            <select id="categoria_id" name="categoria_id"
                                    class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 px-3 py-2"
                                    {{ (old('tipo_establecimiento', $establecimiento->tipo_establecimiento) === 'Otro') ? 'style=display:none' : '' }}>
                                <option value="">Selecciona...</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}"
                                        data-tipo="{{ $cat->tipo_establecimiento }}"
                                        {{ (string) old('categoria_id', $establecimiento->categoria_id) === (string) $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>

                            {{-- Input para categoría libre cuando tipo es Otro --}}
                            <input type="text" id="categoria_otro" name="categoria_otro"
                                   value="{{ old('categoria_otro', $establecimiento->categoria_otro ?? '') }}"
                                   placeholder="Especifica la categoría"
                                   class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 px-3 py-2"
                                   {{ (old('tipo_establecimiento', $establecimiento->tipo_establecimiento) === 'Otro') ? '' : 'style=display:none' }}>

                            <div class="sbvc-error-msg" data-error-for="categoria_id" style="display:none"></div>
                        </div>

                        <div>
                            <label for="telefono_establecimiento" class="block text-sm font-medium mb-1 text-zinc-700 dark:text-zinc-200">Teléfono *</label>
                            <input type="tel" id="telefono_establecimiento" name="telefono_establecimiento"
                                   value="{{ old('telefono_establecimiento', $establecimiento->telefono_establecimiento) }}" required maxlength="10" pattern="\d{10}"
                                   class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-orange-300 dark:focus:ring-orange-600/40 px-3 py-2" placeholder="Solo números, 10 dígitos">
                            <div class="sbvc-error-msg" data-error-for="telefono_establecimiento" style="display:none"></div>
                        </div>

                        <div>
                            <label for="correo_establecimiento" class="block text-sm font-medium mb-1 text-zinc-700 dark:text-zinc-200">Correo *</label>
                            <input type="email" id="correo_establecimiento" name="correo_establecimiento"
                                   value="{{ old('correo_establecimiento', $establecimiento->correo_establecimiento) }}" required
                                   class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-orange-300 dark:focus:ring-orange-600/40 px-3 py-2">
                            <div class="sbvc-error-msg" data-error-for="correo_establecimiento" style="display:none"></div>
                        </div>
                    </div>
                </div>

                {{-- UBICACIÓN --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 mb-6 overflow-hidden">
                    <div class="px-6 py-3 border-b border-zinc-100 dark:border-zinc-800">
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                            Ubicación
                        </h3>
                    </div>

                    <div class="p-6 space-y-6">
                        <div>
                            <label for="manual_search" class="block text-sm font-medium mb-1 text-zinc-700 dark:text-zinc-200">Buscar dirección</label>
                            <div class="flex gap-2">
                                <input type="text" id="manual_search" placeholder="Escribe calle, ciudad y estado y presiona ENTER o el botón Buscar..."
                                    class="w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-orange-300 dark:focus:ring-orange-600/40 px-3 py-2">
                                <button type="button" id="btn_search" class="px-4 py-2 rounded-lg font-bold flex items-center gap-2">
                                    Buscar
                                </button>
                            </div>
                            <p class="text-xs text-zinc-500 mt-1">Ejemplo: "Av. Morelos 123, Toluca, Estado de México"</p>
                        </div>

                        <div id="map" class="w-full h-96 rounded-lg border border-gray-300 dark:border-zinc-600 bg-gray-100"></div>

                        <input type="hidden" name="lat" id="lat" value="{{ old('lat', $establecimiento->lat) }}" required>
                        <input type="hidden" name="lng" id="lng" value="{{ old('lng', $establecimiento->lng) }}" required>

                        <div>
                            <label for="direccion_completa_establecimiento" class="block text-sm font-medium mb-1 text-zinc-700 dark:text-zinc-200">Dirección completa *</label>
                            <textarea name="direccion_completa_establecimiento" id="direccion_completa_establecimiento" rows="2" required
                                class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 focus:outline-none focus:ring-2 focus:ring-orange-300 dark:focus:ring-orange-600/40 px-3 py-2">{{ old('direccion_completa_establecimiento', $establecimiento->direccion_completa_establecimiento) }}</textarea>
                            <div class="sbvc-error-msg" data-error-for="direccion_completa_establecimiento" style="display:none"></div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label for="colonia" class="text-xs font-bold text-zinc-700 dark:text-zinc-200">Colonia *</label>
                                <input type="text" name="colonia" id="colonia" value="{{ old('colonia', $establecimiento->colonia) }}" required class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 px-3 py-2">
                                <div class="sbvc-error-msg" data-error-for="colonia" style="display:none"></div>
                            </div>
                            <div>
                                <label for="municipio" class="text-xs font-bold text-zinc-700 dark:text-zinc-200">Municipio *</label>
                                <input type="text" name="municipio" id="municipio" value="{{ old('municipio', $establecimiento->municipio) }}" required class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 px-3 py-2">
                                <div class="sbvc-error-msg" data-error-for="municipio" style="display:none"></div>
                            </div>
                            <div>
                                <label for="estado" class="text-xs font-bold text-zinc-700 dark:text-zinc-200">Estado *</label>
                                <input type="text" name="estado" id="estado" value="{{ old('estado', $establecimiento->estado) }}" required class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 px-3 py-2">
                                <div class="sbvc-error-msg" data-error-for="estado" style="display:none"></div>
                            </div>
                            <div>
                                <label for="codigo_postal" class="text-xs font-bold text-zinc-700 dark:text-zinc-200">C.P. *</label>
                                <input type="text" name="codigo_postal" id="codigo_postal" value="{{ old('codigo_postal', $establecimiento->codigo_postal) }}" required class="sbvc-input w-full rounded-lg border border-gray-300 dark:bg-zinc-900 dark:border-zinc-600 px-3 py-2">
                                <div class="sbvc-error-msg" data-error-for="codigo_postal" style="display:none"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- HORARIOS --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 mb-6 overflow-hidden">
                    <div class="px-6 py-3 border-b border-zinc-100 dark:border-zinc-800">
                        <h3 class="text-lg font-bold text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                            Horarios de Atención
                        </h3>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                            <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100">Lunes a Viernes *</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" id="horario_lv_apertura" name="horarios[lunes_viernes][apertura]" required class="sbvc-input w-32 rounded border border-gray-300 text-sm px-2 py-1" value="{{ old('horarios.lunes_viernes.apertura', $horarios_lv['apertura'] ?? '') }}">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">a</span>
                                <input type="time" id="horario_lv_cierre" name="horarios[lunes_viernes][cierre]" required class="sbvc-input w-32 rounded border border-gray-300 text-sm px-2 py-1" value="{{ old('horarios.lunes_viernes.cierre', $horarios_lv['cierre'] ?? '') }}">
                            </div>
                            <div class="sbvc-error-msg" data-error-for="horarios_lunes_viernes" style="display:none"></div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                            <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100">Sábados</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" id="horario_sab_apertura" name="horarios[sabados][apertura]" class="sbvc-input w-32 rounded border border-gray-300 text-sm px-2 py-1" value="{{ old('horarios.sabados.apertura', $horarios_sab['apertura'] ?? '') }}">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">a</span>
                                <input type="time" id="horario_sab_cierre" name="horarios[sabados][cierre]" class="sbvc-input w-32 rounded border border-gray-300 text-sm px-2 py-1" value="{{ old('horarios.sabados.cierre', $horarios_sab['cierre'] ?? '') }}">
                            </div>
                            <div class="sbvc-error-msg" data-error-for="horarios_sabados" style="display:none"></div>
                        </div>

                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg">
                            <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100">Domingos</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" id="horario_dom_apertura" name="horarios[domingos][apertura]" class="sbvc-input w-32 rounded border border-gray-300 text-sm px-2 py-1" value="{{ old('horarios.domingos.apertura', $horarios_dom['apertura'] ?? '') }}">
                                <span class="text-sm text-zinc-600 dark:text-zinc-400">a</span>
                                <input type="time" id="horario_dom_cierre" name="horarios[domingos][cierre]" class="sbvc-input w-32 rounded border border-gray-300 text-sm px-2 py-1" value="{{ old('horarios.domingos.cierre', $horarios_dom['cierre'] ?? '') }}">
                            </div>
                            <div class="sbvc-error-msg" data-error-for="horarios_domingos" style="display:none"></div>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" name="abre_dias_festivos" id="abre_dias_festivos" value="1" {{ old('abre_dias_festivos', $abre_festivos) ? 'checked' : '' }} class="rounded text-orange-600">
                            <label for="abre_dias_festivos" class="ml-2 text-sm text-zinc-800 dark:text-zinc-100 cursor-pointer">Abrimos días festivos</label>
                        </div>

                        <div id="horarios_festivos_container" class="{{ old('abre_dias_festivos', $abre_festivos) ? '' : 'hidden' }}">
                            <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-zinc-50 dark:bg-zinc-800 rounded-lg mt-2">
                                <span class="text-sm font-medium text-zinc-800 dark:text-zinc-100">Horario días festivos</span>
                                <div class="flex items-center space-x-2">
                                    <input type="time" id="horario_fest_apertura" name="horarios[festivos][apertura]" class="sbvc-input w-32 rounded border border-gray-300 text-sm px-2 py-1" value="{{ old('horarios.festivos.apertura', $horarios_fest['apertura'] ?? '') }}">
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400">a</span>
                                    <input type="time" id="horario_fest_cierre" name="horarios[festivos][cierre]" class="sbvc-input w-32 rounded border border-gray-300 text-sm px-2 py-1" value="{{ old('horarios.festivos.cierre', $horarios_fest['cierre'] ?? '') }}">
                                </div>
                                <div class="sbvc-error-msg" data-error-for="horarios_festivos" style="display:none"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACCIONES --}}
                <div class="flex justify-end gap-4">
                    <button type="submit" id="btn_submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold transition">
                        {{ __('Guardar cambios') }}
                    </button>

                    <a href="{{ route('establecimientos.index') }}" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold transition">
                        {{ __('Cancelar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <style>
            .sbvc-error-msg { color: #dc2626; font-size: 0.875rem; margin-top: 0.35rem; }
            .sbvc-invalid { border-color: #dc2626 !important; box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08) !important; }
            .swal2-popup { max-width: 680px; }
            #btn_search { background-color: #000000 !important; color: #ffffff !important; }
            #btn_search:hover { background-color: #222222 !important; color: #ffffff !important; }
            label, .text-link { color: #263238; }
            @media (prefers-color-scheme: dark) { label, .text-link { color: #e6eef6; } }

            /* Reforzar centrado de Swal */
            .swal2-container { display: flex !important; align-items: center !important; justify-content: center !important; }
            .swal2-popup { margin: 0 auto !important; }
        </style>
    @endpush

    @push('scripts')
        @if (app()->environment('local') || config('app.debug'))
            @vite(['resources/js/app.js'])
        @else
            <script type="module" src="{{ asset('js/app.js') }}"></script>
        @endif

        {{-- Google Maps loader + init --}}
        <script>
            (function (configKey) {
                if (window.initSBVCMapLibrary) return;
                window.initSBVCMapLibrary = function(opts = {}) {
                    const key = opts.key || configKey || '';
                    if (!key) {
                        console.warn('Google Maps API key no definida');
                        return Promise.reject('no-key');
                    }
                    if (window.google && window.google.maps && window.google.maps.importLibrary) {
                        return Promise.resolve(window.google.maps);
                    }
                    return new Promise((resolve, reject) => {
                        const qs = new URLSearchParams({ key: key, v: 'weekly' });
                        const script = document.createElement('script');
                        script.src = `https://maps.googleapis.com/maps/api/js?${qs.toString()}&callback=__sbvc_maps_callback`;
                        script.async = true;
                        script.defer = true;
                        script.onerror = () => reject(new Error('maps-load-error'));
                        window.__sbvc_maps_callback = function() {
                            resolve(window.google.maps);
                        };
                        document.head.appendChild(script);
                    });
                };
            })("{{ config('services.google_maps.api_key') ?? '' }}");
        </script>

        <script>
            (function () {
                let map, marker, geocoder;

                async function initMap() {
                    try {
                        await window.initSBVCMapLibrary();
                        if (!window.google || !window.google.maps) {
                            console.warn('Google Maps no disponible después del load');
                            return;
                        }
                        const Maps = window.google.maps;
                        geocoder = new Maps.Geocoder();

                        const mapEl = document.getElementById('map');
                        if (!mapEl) return;

                        const defaultCenter = { lat: 23.6345, lng: -102.5528 };
                        const latInput = document.getElementById('lat');
                        const lngInput = document.getElementById('lng');

                        const initialPos = (latInput && latInput.value && lngInput && lngInput.value)
                            ? { lat: parseFloat(latInput.value), lng: parseFloat(lngInput.value) }
                            : defaultCenter;

                        map = new Maps.Map(mapEl, {
                            center: initialPos,
                            zoom: initialPos === defaultCenter ? 5 : 16,
                        });

                        if (initialPos && initialPos.lat && initialPos.lng) {
                            placeMarker(initialPos);
                        }

                        map.addListener('click', (e) => {
                            placeMarker(e.latLng);
                            reverseGeocode(e.latLng);
                        });

                        window.performMapSearch = function(address) {
                            if (!address) return;
                            geocoder.geocode({ address: address, componentRestrictions: { country: 'MX' } }, (results, status) => {
                                if (status === 'OK' && results[0]) {
                                    const loc = results[0].geometry.location;
                                    map.setCenter(loc);
                                    map.setZoom(16);
                                    placeMarker(loc);
                                    fillFromPlace(results[0]);
                                } else {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'No encontrado',
                                        text: 'No se encontró la dirección.',
                                        confirmButtonColor: '#000000',
                                        customClass: { confirmButton: 'custom-black' },
                                        position: 'center'
                                    });
                                }
                            });
                        };

                    } catch (err) {
                        console.error('Error inicializando Google Maps:', err);
                    }
                }

                function placeMarker(location) {
                    if (!window.google || !window.google.maps) return;
                    const Maps = window.google.maps;
                    const pos = (typeof location.lat === 'function') ? { lat: location.lat(), lng: location.lng() } : { lat: location.lat, lng: location.lng };
                    if (marker) marker.setMap(null);
                    marker = new Maps.Marker({
                        position: pos,
                        map: map,
                        draggable: true,
                        title: 'Ubicación seleccionada'
                    });
                    updateInputs(pos);

                    marker.addListener('dragend', function () {
                        const p = marker.getPosition();
                        updateInputs({ lat: p.lat(), lng: p.lng() });
                        reverseGeocode(p);
                    });
                }

                function updateInputs(pos) {
                    const latInput = document.getElementById('lat');
                    const lngInput = document.getElementById('lng');
                    if (latInput) latInput.value = pos.lat;
                    if (lngInput) lngInput.value = pos.lng;
                }

                function reverseGeocode(latLng) {
                    if (!geocoder) return;
                    geocoder.geocode({ location: latLng }, (results, status) => {
                        if (status === 'OK' && results[0]) {
                            fillFromPlace(results[0]);
                        }
                    });
                }

                function fillFromPlace(place) {
                    const addrEl = document.getElementById('direccion_completa_establecimiento');
                    if (addrEl) addrEl.value = place.formatted_address || '';
                    const coloniaEl = document.getElementById('colonia');
                    const municipioEl = document.getElementById('municipio');
                    const estadoEl = document.getElementById('estado');
                    const cpEl = document.getElementById('codigo_postal');

                    if (coloniaEl) coloniaEl.value = '';
                    if (municipioEl) municipioEl.value = '';
                    if (estadoEl) estadoEl.value = '';
                    if (cpEl) cpEl.value = '';

                    if (place.address_components) {
                        place.address_components.forEach(c => {
                            if (c.types.includes('sublocality') || c.types.includes('neighborhood')) if (coloniaEl) coloniaEl.value = c.long_name;
                            if (c.types.includes('locality')) if (municipioEl) municipioEl.value = c.long_name;
                            if (c.types.includes('administrative_area_level_1')) if (estadoEl) estadoEl.value = c.long_name;
                            if (c.types.includes('postal_code')) if (cpEl) cpEl.value = c.long_name;
                        });
                    }
                }

                document.addEventListener('DOMContentLoaded', function () {
                    initMap();
                });

                (function () {
                    // Helpers para errores
                    function setFieldError(el, message) {
                        if(!el) return;
                        el.classList.add('sbvc-invalid');
                        const container = document.querySelector(`[data-error-for="${el.id || el.name}"]`);
                        if(container) {
                            container.innerText = message;
                            container.style.display = 'block';
                        }
                    }
                    function clearFieldError(el) {
                        if(!el) return;
                        el.classList.remove('sbvc-invalid');
                        const container = document.querySelector(`[data-error-for="${el.id || el.name}"]`);
                        if(container) { container.innerText = ''; container.style.display = 'none'; }
                    }
                    function clearAllErrors() {
                        document.querySelectorAll('.sbvc-invalid').forEach(e => e.classList.remove('sbvc-invalid'));
                        document.querySelectorAll('.sbvc-error-msg').forEach(e => { e.innerText = ''; e.style.display='none'; });
                    }

                    // Sincronizar Tipo y Categoría con soporte para categoría libre
                    const tipoSelect = document.getElementById('tipo_establecimiento');
                    const categoriaSelect = document.getElementById('categoria_id');
                    const categoriaOtroInput = document.getElementById('categoria_otro');
                    const otroContainer = document.getElementById('otro_tipo_container');
                    const btnBuscar = document.getElementById('btn_search');
                    const inputBuscar = document.getElementById('manual_search');

                    if (tipoSelect && categoriaSelect && categoriaOtroInput) {
                        tipoSelect.addEventListener('change', () => {
                            if (tipoSelect.value === 'Otro') {
                                otroContainer.classList.remove('hidden');
                                categoriaSelect.style.display = 'none';
                                categoriaOtroInput.style.display = 'block';
                                categoriaSelect.value = '';
                            } else {
                                otroContainer.classList.add('hidden');
                                categoriaSelect.style.display = 'block';
                                categoriaOtroInput.style.display = 'none';
                                categoriaOtroInput.value = '';
                                filterCategorias(tipoSelect.value);
                            }
                        });

                        categoriaSelect.addEventListener('change', () => {
                            if (tipoSelect.value !== 'Otro') {
                                const selectedOption = categoriaSelect.options[categoriaSelect.selectedIndex];
                                const tipoFromCat = selectedOption ? selectedOption.getAttribute('data-tipo') : '';
                                if (tipoFromCat && tipoSelect.value !== tipoFromCat) {
                                    tipoSelect.value = tipoFromCat;
                                    otroContainer.classList.add('hidden');
                                    categoriaOtroInput.style.display = 'none';
                                    categoriaOtroInput.value = '';
                                    filterCategorias(tipoFromCat);
                                }
                            }
                        });
                    }

                    function filterCategorias(tipo) {
                        if (!categoriaSelect) return;
                        for (let i = 0; i < categoriaSelect.options.length; i++) {
                            const option = categoriaSelect.options[i];
                            const optionTipo = option.getAttribute('data-tipo');
                            if (!tipo || tipo === '') {
                                option.style.display = '';
                            } else {
                                option.style.display = (optionTipo === tipo) ? '' : 'none';
                            }
                        }
                        if (categoriaSelect.selectedIndex >= 0) {
                            const selectedOption = categoriaSelect.options[categoriaSelect.selectedIndex];
                            if (selectedOption && selectedOption.style.display === 'none') {
                                categoriaSelect.selectedIndex = 0;
                            }
                        }
                    }

                    filterCategorias(tipoSelect ? tipoSelect.value : null);

                    // Toggle "Abrimos días festivos"
                    const chkFestivos = document.getElementById('abre_dias_festivos');
                    const festivosContainer = document.getElementById('horarios_festivos_container');
                    if(chkFestivos && festivosContainer) {
                        chkFestivos.addEventListener('change', () => {
                            if(chkFestivos.checked) festivosContainer.classList.remove('hidden');
                            else {
                                festivosContainer.classList.add('hidden');
                                const fAp = document.getElementById('horario_fest_apertura');
                                const fCi = document.getElementById('horario_fest_cierre');
                                if(fAp) clearFieldError(fAp);
                                if(fCi) clearFieldError(fCi);
                                const ef = document.querySelector('[data-error-for="horarios_festivos"]');
                                if(ef) { ef.innerText = ''; ef.style.display = 'none'; }
                            }
                        });
                    }

                    // Teléfono: permitir solo números y máximo 10
                    const telInput = document.getElementById('telefono_establecimiento');
                    if(telInput) {
                        telInput.addEventListener('input', (e) => {
                            const digits = e.target.value.replace(/\D/g,'').slice(0,10);
                            e.target.value = digits;
                            if (digits.length === 10) clearFieldError(e.target);
                        });
                        telInput.addEventListener('paste', (e) => {
                            e.preventDefault();
                            const pasted = (e.clipboardData || window.clipboardData).getData('text');
                            const digits = pasted.replace(/\D/g,'').slice(0,10);
                            telInput.value = digits;
                        });
                    }

                    if(btnBuscar && inputBuscar) {
                        btnBuscar.addEventListener('click', () => {
                            const q = inputBuscar.value || '';
                            if (typeof window.performMapSearch === 'function') {
                                window.performMapSearch(q);
                            } else if (window.Livewire && typeof window.Livewire.emit === 'function') {
                                window.Livewire.emit('buscarDireccion', q);
                            } else {
                                const ev = new CustomEvent('sbvc:buscarDireccion', { detail: q });
                                document.dispatchEvent(ev);
                            }
                        });
                        inputBuscar.addEventListener('keypress', (e) => {
                            if (e.key === 'Enter') { e.preventDefault(); btnBuscar.click(); }
                        });
                    }

                    // Validación de formulario
                    const form = document.getElementById('form-establecimiento');
                    if (form) {
                        form.addEventListener('submit', function(evt) {
                            evt.preventDefault();
                            clearAllErrors();

                            const errors = [];
                            function pushError(el, key, msg) {
                                errors.push(msg);
                                if(el) setFieldError(el, msg);
                                else {
                                    const fake = document.querySelector(`[data-error-for="${key}"]`);
                                    if(fake) { fake.innerText = msg; fake.style.display='block'; }
                                }
                            }

                            const nombre = document.getElementById('nombre_establecimiento');
                            if(!nombre.value.trim()) pushError(nombre, 'nombre_establecimiento', 'El nombre es obligatorio');

                            const tipo = document.getElementById('tipo_establecimiento');
                            if(!tipo.value) pushError(tipo, 'tipo_establecimiento', 'Debes seleccionar un tipo de establecimiento');

                            if(tipo.value === 'Otro') {
                                if(!categoriaOtroInput.value.trim()) {
                                    pushError(categoriaOtroInput, 'categoria_id', 'Debes especificar la categoría');
                                }
                            } else {
                                if(!categoriaSelect.value) {
                                    pushError(categoriaSelect, 'categoria_id', 'Debes seleccionar una categoría');
                                }
                            }

                            const correo = document.getElementById('correo_establecimiento');
                            if(!correo.value.trim()) pushError(correo, 'correo_establecimiento', 'El correo es obligatorio');
                            else {
                                const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                                if(!re.test(correo.value.trim())) pushError(correo, 'correo_establecimiento', 'Correo no válido');
                            }

                            if(telInput) {
                                if(!telInput.value) pushError(telInput, 'telefono_establecimiento', 'El teléfono es obligatorio');
                                else if(telInput.value.length !== 10) pushError(telInput, 'telefono_establecimiento', 'El teléfono debe tener 10 dígitos');
                            }

                            const direccion = document.getElementById('direccion_completa_establecimiento');
                            if(!direccion.value.trim()) pushError(direccion, 'direccion_completa_establecimiento', 'La dirección completa es obligatoria');

                            const lvA = document.getElementById('horario_lv_apertura');
                            const lvC = document.getElementById('horario_lv_cierre');
                            if(!lvA.value || !lvC.value) {
                                pushError(lvA || lvC, 'horarios_lunes_viernes', 'Debes especificar horario de Lunes a Viernes');
                            } else {
                                if(lvA.value > lvC.value) pushError(lvA, 'horarios_lunes_viernes', 'El horario de cierre debe ser igual o posterior a la apertura (L-V)');
                            }

                            if(chkFestivos && chkFestivos.checked) {
                                const fA = document.getElementById('horario_fest_apertura');
                                const fC = document.getElementById('horario_fest_cierre');
                                if(!fA.value || !fC.value) pushError(fA || fC, 'horarios_festivos', 'Debes especificar horario para días festivos');
                                else if(fA.value > fC.value) pushError(fA, 'horarios_festivos', 'El cierre debe ser igual o posterior a la apertura (festivos)');
                            }

                            if(errors.length > 0) {
                                const ul = document.createElement('ul');
                                ul.style.textAlign = 'left';
                                errors.forEach(msg => {
                                    const li = document.createElement('li');
                                    li.style.marginBottom = '6px';
                                    li.innerText = msg;
                                    ul.appendChild(li);
                                });

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Errores en el formulario',
                                    html: ul,
                                    confirmButtonColor: '#000000',
                                    customClass: { confirmButton: 'custom-black' },
                                    confirmButtonText: 'OK',
                                    position: 'center'
                                });
                                return false;
                            }

                            const btn = document.getElementById('btn_submit');
                            if(btn) { btn.disabled = true; btn.classList.add('opacity-70'); }
                            form.submit();
                        });
                    }

                    // quitar borde rojo cuando corrigen campos
                    document.querySelectorAll('input, select, textarea').forEach(el => {
                        el.addEventListener('input', () => { el.classList.remove('sbvc-invalid'); const c=document.querySelector(`[data-error-for="${el.id||el.name}"]`); if(c){c.style.display='none'; c.innerText='';} });
                        el.addEventListener('change', () => { el.classList.remove('sbvc-invalid'); const c=document.querySelector(`[data-error-for="${el.id||el.name}"]`); if(c){c.style.display='none'; c.innerText='';} });
                    });

                })();

            })();
        </script>

        {{-- Mostrar alerta SweetAlert si hay sesión swal (FORZAR centrado) --}}
        @if(session('swal'))
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const cfg = @json(session('swal'));
                    try {
                        if (typeof cfg === 'object' && cfg !== null) {
                            cfg.position = 'center';
                            if (typeof cfg.showConfirmButton === 'undefined') cfg.showConfirmButton = true;
                            if (!cfg.customClass) cfg.customClass = {};
                            cfg.customClass.confirmButton = cfg.customClass.confirmButton || 'custom-black';
                            cfg.confirmButtonColor = cfg.confirmButtonColor || '#000000';
                        }
                    } catch(e) { console.warn('normalizar cfg swal error', e); }
                    Swal.fire(cfg);
                });
            </script>
        @endif
    @endpush
</x-layouts.app>