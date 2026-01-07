{{--
    Nombre del archivo        : create.blade.php
    Descripción               : Vista para creación de establecimientos
    Fecha de mantenimiento    : 07/01/2026
    Folio de mantenimiento    : MANT-PROD
    Tipo de mantenimiento     : Versión Final Estable
    Descripción del mantenimiento: 
        1. Uso de Geocoding API para búsqueda manual (compatible con cuentas nuevas).
        2. Inyección segura de API Key desde variables de entorno (.env).
        3. Configuración de mapa optimizada.
--}}

<x-layouts.app :title="__('Nuevo Establecimiento')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <flux:icon.building-storefront class="size-10 text-orange-500" />
                    <flux:heading size="xl">Nuevo Establecimiento</flux:heading>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Completa la información de tu establecimiento.
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-lg">
                    <div class="flex">
                        <flux:icon.exclamation-triangle class="h-5 w-5 text-red-400" />
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Errores detectados:</h3>
                            <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('establecimientos.store') }}" method="POST" id="form-establecimiento">
                @csrf

                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 mb-6 overflow-hidden">
                    <div class="bg-orange-600 px-6 py-3">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <flux:icon.information-circle class="size-5" /> Datos Generales
                        </h3>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium mb-1 dark:text-gray-200">Nombre del establecimiento *</label>
                            <input type="text" name="nombre_establecimiento" value="{{ old('nombre_establecimiento') }}" required 
                                class="w-full rounded-lg border-gray-300 dark:bg-zinc-900 dark:border-zinc-600" placeholder="Ej: Restaurante Los Arcos">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-200">Tipo *</label>
                            <select id="tipo_establecimiento" name="tipo_establecimiento" required class="w-full rounded-lg border-gray-300 dark:bg-zinc-900 dark:border-zinc-600">
                                <option value="">Selecciona...</option>
                                <option value="Restaurante" {{ old('tipo_establecimiento') == 'Restaurante' ? 'selected' : '' }}>Restaurante</option>
                                <option value="Cafetería" {{ old('tipo_establecimiento') == 'Cafetería' ? 'selected' : '' }}>Cafetería</option>
                                <option value="Food Truck" {{ old('tipo_establecimiento') == 'Food Truck' ? 'selected' : '' }}>Food Truck</option>
                                <option value="Panadería" {{ old('tipo_establecimiento') == 'Panadería' ? 'selected' : '' }}>Panadería</option>
                                <option value="Bar" {{ old('tipo_establecimiento') == 'Bar' ? 'selected' : '' }}>Bar</option>
                                <option value="Otro" {{ old('tipo_establecimiento') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div id="otro_tipo_container" class="hidden md:col-span-2">
                            <label class="block text-sm font-medium mb-1 dark:text-gray-200">Especifica el tipo *</label>
                            <input type="text" name="tipo_establecimiento_otro" id="tipo_establecimiento_otro" value="{{ old('tipo_establecimiento_otro') }}" 
                                class="w-full rounded-lg border-gray-300 dark:bg-zinc-900 dark:border-zinc-600">
                        </div>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-200">Categoría *</label>
                            <select id="categoria_id" name="categoria_id" required class="w-full rounded-lg border-gray-300 dark:bg-zinc-900 dark:border-zinc-600">
                                <option value="">Selecciona...</option>
                                @foreach($categorias as $cat)
                                    <option value="{{ $cat->id }}" data-tipo="{{ $cat->tipo_establecimiento }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div><label class="block text-sm font-medium mb-1 dark:text-gray-200">Teléfono *</label><input type="tel" name="telefono_establecimiento" value="{{ old('telefono_establecimiento') }}" required class="w-full rounded-lg border-gray-300 dark:bg-zinc-900 dark:border-zinc-600"></div>
                        <div><label class="block text-sm font-medium mb-1 dark:text-gray-200">Correo *</label><input type="email" name="correo_establecimiento" value="{{ old('correo_establecimiento') }}" required class="w-full rounded-lg border-gray-300 dark:bg-zinc-900 dark:border-zinc-600"></div>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 mb-6 overflow-hidden">
                    <div class="bg-orange-600 px-6 py-3">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <flux:icon.map-pin class="size-5" /> Ubicación
                        </h3>
                    </div>
                    <div class="p-6 space-y-6">
                        
                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-200">Buscar dirección</label>
                            <div class="flex gap-2">
                                <input type="text" id="manual_search" placeholder="Escribe calle, ciudad y estado y presiona ENTER o el botón Buscar..." 
                                    class="flex-1 rounded-lg border-gray-300 dark:bg-zinc-900 dark:border-zinc-600">
                                <button type="button" id="btn_search" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg font-bold flex items-center gap-2">
                                    <flux:icon.magnifying-glass class="size-5" /> Buscar
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Ejemplo: "Av. Morelos 123, Toluca, Estado de México"</p>
                        </div>

                        <div id="map" class="w-full h-96 rounded-lg border border-gray-300 dark:border-zinc-600 bg-gray-100"></div>

                        <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}" required>
                        <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}" required>

                        <div>
                            <label class="block text-sm font-medium mb-1 dark:text-gray-200">Dirección completa *</label>
                            <textarea name="direccion_completa_establecimiento" id="direccion_completa_establecimiento" rows="2" required 
                                class="w-full rounded-lg border-gray-300 dark:bg-zinc-900 dark:border-zinc-600">{{ old('direccion_completa_establecimiento') }}</textarea>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div><label class="text-xs font-bold">Colonia *</label><input type="text" name="colonia" id="colonia" value="{{ old('colonia') }}" required class="w-full rounded-lg border-gray-300 dark:bg-zinc-900"></div>
                            <div><label class="text-xs font-bold">Municipio *</label><input type="text" name="municipio" id="municipio" value="{{ old('municipio') }}" required class="w-full rounded-lg border-gray-300 dark:bg-zinc-900"></div>
                            <div><label class="text-xs font-bold">Estado *</label><input type="text" name="estado" id="estado" value="{{ old('estado') }}" required class="w-full rounded-lg border-gray-300 dark:bg-zinc-900"></div>
                            <div><label class="text-xs font-bold">C.P. *</label><input type="text" name="codigo_postal" id="codigo_postal" value="{{ old('codigo_postal') }}" required class="w-full rounded-lg border-gray-300 dark:bg-zinc-900"></div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border border-zinc-200 dark:border-zinc-700 mb-6 overflow-hidden">
                    <div class="bg-orange-600 px-6 py-3">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <flux:icon.clock class="size-5" /> Horarios de Atención
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                            <span class="text-sm font-medium">Lunes a Viernes *</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="horarios[lunes_viernes][apertura]" required class="w-32 rounded border-gray-300 text-sm">
                                <span>a</span>
                                <input type="time" name="horarios[lunes_viernes][cierre]" required class="w-32 rounded border-gray-300 text-sm">
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                            <span class="text-sm font-medium">Sábados</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="horarios[sabados][apertura]" class="w-32 rounded border-gray-300 text-sm">
                                <span>a</span>
                                <input type="time" name="horarios[sabados][cierre]" class="w-32 rounded border-gray-300 text-sm">
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                            <span class="text-sm font-medium">Domingos</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="horarios[domingos][apertura]" class="w-32 rounded border-gray-300 text-sm">
                                <span>a</span>
                                <input type="time" name="horarios[domingos][cierre]" class="w-32 rounded border-gray-300 text-sm">
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="cierra_dias_festivos" value="1" {{ old('cierra_dias_festivos') ? 'checked' : '' }} class="rounded text-orange-600">
                            <span class="ml-2 text-sm">Cerramos los días festivos</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-4">
                    <a href="{{ route('establecimientos.index') }}" class="px-6 py-2 border rounded-lg hover:bg-gray-100 dark:hover:bg-zinc-700">Cancelar</a>
                    <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-lg font-bold">Guardar Establecimiento</button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.${c}apis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
                key: "{{ config('services.google_maps.api_key') }}", // Lee la clave del .env de forma segura
                v: "weekly",
            });
        </script>

        <script>
            (() => {
                let map, marker, geocoder;

                async function initMap() {
                    try {
                        const { Map } = await google.maps.importLibrary("maps");
                        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
                        const { Geocoder } = await google.maps.importLibrary("geocoding");

                        const mexico = { lat: 23.6345, lng: -102.5528 };
                        const mapEl = document.getElementById("map");

                        if (!mapEl) return;

                        map = new Map(mapEl, {
                            center: mexico,
                            zoom: 5,
                            mapId: "DEMO_MAP_ID",
                        });

                        geocoder = new Geocoder();

                        // Listener de Click en Mapa
                        map.addListener("click", (e) => {
                            placeMarker(e.latLng);
                            reverseGeocode(e.latLng);
                        });

                        // Configurar Búsqueda MANUAL
                        setupManualSearch();

                        // Recuperar datos previos
                        @if(old('lat') && old('lng'))
                            const pos = { lat: {{ old('lat') }}, lng: {{ old('lng') }} };
                            map.setCenter(pos);
                            map.setZoom(17);
                            placeMarker(pos);
                        @endif

                    } catch (error) {
                        console.error('Error iniciando mapa:', error);
                        document.getElementById('map').innerHTML = `<div class="p-4 text-red-500 bg-red-100 h-full flex items-center justify-center">Error al cargar el mapa. Verifica tu conexión y configuración.</div>`;
                    }
                }

                function setupManualSearch() {
                    const input = document.getElementById("manual_search");
                    const btn = document.getElementById("btn_search");

                    const performSearch = () => {
                        const address = input.value;
                        if (!address) return;

                        geocoder.geocode({ address: address, componentRestrictions: { country: 'mx' } }, (results, status) => {
                            if (status === 'OK' && results[0]) {
                                map.setCenter(results[0].geometry.location);
                                map.setZoom(16);
                                placeMarker(results[0].geometry.location);
                                fillFromPlace(results[0]);
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'No encontrado',
                                    text: 'No se encontró la dirección. Intenta ser más específico.',
                                    confirmButtonColor: '#ea580c'
                                });
                            }
                        });
                    };

                    btn.addEventListener("click", performSearch);
                    input.addEventListener("keypress", (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault(); 
                            performSearch();
                        }
                    });
                }

                async function placeMarker(location) {
                    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
                    if (marker) marker.setMap(null);

                    marker = new AdvancedMarkerElement({
                        map: map,
                        position: location,
                        gmpDraggable: true,
                        title: "Ubicación Seleccionada",
                    });

                    updateInputs(location);

                    marker.addListener("dragend", () => {
                        updateInputs(marker.position);
                        reverseGeocode(marker.position);
                    });
                }

                function updateInputs(location) {
                    const lat = typeof location.lat === 'function' ? location.lat() : location.lat;
                    const lng = typeof location.lng === 'function' ? location.lng() : location.lng;
                    document.getElementById('lat').value = lat;
                    document.getElementById('lng').value = lng;
                }

                function reverseGeocode(latLng) {
                    geocoder.geocode({ location: latLng }, (results, status) => {
                        if (status === "OK" && results[0]) {
                            fillFromPlace(results[0]);
                        }
                    });
                }

                function fillFromPlace(place) {
                    document.getElementById('direccion_completa_establecimiento').value = place.formatted_address || '';
                    
                    document.getElementById('colonia').value = '';
                    document.getElementById('municipio').value = '';
                    document.getElementById('estado').value = '';
                    document.getElementById('codigo_postal').value = '';

                    if (place.address_components) {
                        place.address_components.forEach(c => {
                            if (c.types.includes('sublocality') || c.types.includes('neighborhood')) 
                                document.getElementById('colonia').value = c.long_name;
                            if (c.types.includes('locality')) 
                                document.getElementById('municipio').value = c.long_name;
                            if (c.types.includes('administrative_area_level_1')) 
                                document.getElementById('estado').value = c.long_name;
                            if (c.types.includes('postal_code')) 
                                document.getElementById('codigo_postal').value = c.long_name;
                        });
                    }
                }

                const tipoSelect = document.getElementById('tipo_establecimiento');
                if (tipoSelect) {
                    tipoSelect.addEventListener('change', function() {
                        const tipo = this.value;
                        document.getElementById('otro_tipo_container').classList.toggle('hidden', tipo !== 'Otro');
                        document.querySelectorAll('#categoria_id option[data-tipo]').forEach(opt => {
                            opt.style.display = (tipo === 'Otro' || !opt.dataset.tipo || opt.dataset.tipo === tipo) ? '' : 'none';
                        });
                    });
                }

                document.addEventListener('DOMContentLoaded', () => {
                    if (tipoSelect) tipoSelect.dispatchEvent(new Event('change'));
                    initMap();
                });
            })();
        </script>
    @endpush
</x-layouts.app>