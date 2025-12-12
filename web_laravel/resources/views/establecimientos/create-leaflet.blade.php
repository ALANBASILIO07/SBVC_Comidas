<x-layouts.app :title="__('Nuevo Establecimiento')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">

            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <flux:icon.building-storefront class="size-10 text-orange-500" />
                    <flux:heading size="xl">Nuevo Establecimiento</flux:heading>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Completa la información de tu establecimiento para comenzar a operar en la plataforma
                </p>
            </div>

            <!-- Errores -->
            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <flux:icon.exclamation-triangle class="h-5 w-5 text-red-400" />
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800 dark:text-red-200">Corrije los siguientes errores:</h3>
                            <ul class="mt-2 text-sm text-red-700 dark:text-red-300 list-disc list-inside">
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

                <!-- CARD 1: INFORMACIÓN GENERAL -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.information-circle class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">Información General</h3>
                        </div>
                    </div>
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="nombre_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombre del establecimiento *
                            </label>
                            <input type="text" name="nombre_establecimiento" id="nombre_establecimiento"
                                value="{{ old('nombre_establecimiento') }}" required minlength="3" maxlength="255"
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="Ej: Tacos El Tío">
                            @error('nombre_establecimiento') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="tipo_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tipo de establecimiento *
                            </label>
                            <select id="tipo_establecimiento" name="tipo_establecimiento" required
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="">Selecciona...</option>
                                <option value="Restaurante" {{ old('tipo_establecimiento') == 'Restaurante' ? 'selected' : '' }}>Restaurante</option>
                                <option value="Cafetería" {{ old('tipo_establecimiento') == 'Cafetería' ? 'selected' : '' }}>Cafetería</option>
                                <option value="Food Truck" {{ old('tipo_establecimiento') == 'Food Truck' ? 'selected' : '' }}>Food Truck</option>
                                <option value="Panadería" {{ old('tipo_establecimiento') == 'Panadería' ? 'selected' : '' }}>Panadería</option>
                                <option value="Bar" {{ old('tipo_establecimiento') == 'Bar' ? 'selected' : '' }}>Bar</option>
                                <option value="Otro" {{ old('tipo_establecimiento') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <!-- Campo Otro -->
                        <div id="otro_tipo_container" class="hidden md:col-span-2">
                            <label for="tipo_establecimiento_otro" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Especifica el tipo *
                            </label>
                            <input type="text" name="tipo_establecimiento_otro" id="tipo_establecimiento_otro"
                                value="{{ old('tipo_establecimiento_otro') }}" maxlength="100"
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="Ej: Taquería, Antojitos, etc.">
                        </div>

                        <div>
                            <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Categoría *
                            </label>
                            <select id="categoria_id" name="categoria_id" required
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <option value="">Selecciona una categoría...</option>
                                @php
                                    $categoriasAgrupadas = $categorias->groupBy('tipo_establecimiento');
                                @endphp
                                @foreach($categoriasAgrupadas as $tipo => $cats)
                                    <optgroup label="{{ $tipo }}">
                                        @foreach($cats as $cat)
                                            <option value="{{ $cat->id }}"
                                                data-tipo="{{ $cat->tipo_establecimiento }}"
                                                {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                                {{ $cat->nombre }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                            @error('categoria_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="telefono_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Teléfono *
                            </label>
                            <input type="tel" name="telefono_establecimiento" value="{{ old('telefono_establecimiento') }}"
                                pattern="[0-9]{10,20}" required
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="7201234567">
                        </div>

                        <div>
                            <label for="correo_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Correo electrónico *
                            </label>
                            <input type="email" name="correo_establecimiento" value="{{ old('correo_establecimiento') }}" required
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="contacto@mitaqueria.com">
                        </div>
                    </div>
                </div>

                <!-- CARD 2: UBICACIÓN CON MAPA LEAFLET (GRATIS) -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.map-pin class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">Ubicación</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-4 rounded">
                            <p class="text-sm text-blue-800 dark:text-blue-300">
                                <strong>Instrucciones:</strong> Haz clic en el mapa para seleccionar la ubicación exacta de tu establecimiento. Puedes mover el marcador arrastrándolo.
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar dirección</label>
                            <div class="flex gap-2">
                                <input type="text" id="search_address" placeholder="Escribe una dirección o ciudad en México..."
                                    class="flex-1 rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <button type="button" id="search_button" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors">
                                    <flux:icon.magnifying-glass class="size-5" />
                                </button>
                            </div>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Ejemplo: "Chilpancingo, Guerrero" o "Avenida Juárez, CDMX"
                            </p>
                        </div>

                        <div id="map" class="w-full h-96 rounded-lg border-2 border-gray-300 dark:border-zinc-600 z-0"></div>

                        <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}" required>
                        <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}" required>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Latitud</label>
                                <input type="text" id="lat_display" readonly
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 text-sm"
                                    placeholder="Selecciona en el mapa">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Longitud</label>
                                <input type="text" id="lng_display" readonly
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-900 text-sm"
                                    placeholder="Selecciona en el mapa">
                            </div>
                        </div>

                        <div>
                            <label for="direccion_completa_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Dirección completa *
                            </label>
                            <textarea name="direccion_completa_establecimiento" id="direccion_completa_establecimiento" rows="2" required
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="Calle, número, referencias...">{{ old('direccion_completa_establecimiento') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Colonia *</label>
                                <input type="text" name="colonia" id="colonia" value="{{ old('colonia') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Municipio *</label>
                                <input type="text" name="municipio" id="municipio" value="{{ old('municipio') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado *</label>
                                <input type="text" name="estado" id="estado" value="{{ old('estado') }}" required
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Código Postal *</label>
                                <input type="text" name="codigo_postal" id="codigo_postal" value="{{ old('codigo_postal') }}" pattern="[0-9]{5}" maxlength="5" required
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 3: HORARIOS -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.clock class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">Horarios de Atención</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                            <span class="text-sm font-medium">Lunes a Viernes *</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="horarios[lunes_viernes][apertura]" required class="w-32 rounded border-gray-300 dark:border-zinc-600 dark:bg-zinc-700">
                                <span>a</span>
                                <input type="time" name="horarios[lunes_viernes][cierre]" required class="w-32 rounded border-gray-300 dark:border-zinc-600 dark:bg-zinc-700">
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                            <span class="text-sm font-medium">Sábados</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="horarios[sabados][apertura]" class="w-32 rounded border-gray-300 dark:border-zinc-600 dark:bg-zinc-700">
                                <span>a</span>
                                <input type="time" name="horarios[sabados][cierre]" class="w-32 rounded border-gray-300 dark:border-zinc-600 dark:bg-zinc-700">
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                            <span class="text-sm font-medium">Domingos</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="horarios[domingos][apertura]" class="w-32 rounded border-gray-300 dark:border-zinc-600 dark:bg-zinc-700">
                                <span>a</span>
                                <input type="time" name="horarios[domingos][cierre]" class="w-32 rounded border-gray-300 dark:border-zinc-600 dark:bg-zinc-700">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- BOTONES -->
                <div class="flex flex-col sm:flex-row justify-between gap-4 pt-6">
                    <a href="{{ route('establecimientos.index') }}"
                        class="px-8 py-3 bg-white dark:bg-zinc-800 border-2 border-gray-300 dark:border-zinc-600 rounded-lg hover:bg-gray-50 text-center font-medium">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg font-bold shadow-lg">
                        Guardar Establecimiento
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('styles')
        <!-- Leaflet CSS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="anonymous"/>
        <style>
            .leaflet-container {
                z-index: 0;
            }
            .marker-pulse {
                animation: pulse 2s infinite;
            }
            @keyframes pulse {
                0% {
                    box-shadow: 0 0 0 0 rgba(249, 115, 22, 0.7);
                }
                70% {
                    box-shadow: 0 0 0 10px rgba(249, 115, 22, 0);
                }
                100% {
                    box-shadow: 0 0 0 0 rgba(249, 115, 22, 0);
                }
            }
        </style>
    @endpush

    @push('scripts')
        <!-- Leaflet JS -->
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin="anonymous"></script>

        <script>
            let map, marker;

            // Inicializar mapa centrado en México
            document.addEventListener('DOMContentLoaded', function() {
                // Centro de México (aproximado)
                const mexicoCenter = [23.6345, -102.5528];

                // Crear mapa
                map = L.map('map').setView(mexicoCenter, 5);

                // Agregar capa de OpenStreetMap
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 19,
                }).addTo(map);

                // Evento click en el mapa
                map.on('click', function(e) {
                    placeMarker(e.latlng);
                    reverseGeocode(e.latlng);
                });

                // Si hay valores antiguos, mostrar marcador
                @if(old('lat') && old('lng'))
                    const oldLat = {{ old('lat') }};
                    const oldLng = {{ old('lng') }};
                    const oldLatLng = L.latLng(oldLat, oldLng);
                    placeMarker(oldLatLng);
                    map.setView(oldLatLng, 17);
                @endif

                // Búsqueda de dirección
                document.getElementById('search_button').addEventListener('click', searchAddress);
                document.getElementById('search_address').addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        searchAddress();
                    }
                });
            });

            function placeMarker(latLng) {
                // Eliminar marcador anterior
                if (marker) {
                    map.removeLayer(marker);
                }

                // Crear nuevo marcador naranja
                const orangeIcon = L.divIcon({
                    className: 'custom-marker',
                    html: `
                        <div class="marker-pulse" style="
                            width: 30px;
                            height: 30px;
                            background-color: #f97316;
                            border: 3px solid white;
                            border-radius: 50%;
                            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                        "></div>
                    `,
                    iconSize: [30, 30],
                    iconAnchor: [15, 15]
                });

                marker = L.marker(latLng, {
                    icon: orangeIcon,
                    draggable: true
                }).addTo(map);

                // Actualizar coordenadas
                updateCoordinates(latLng);

                // Evento drag
                marker.on('dragend', function(e) {
                    const pos = e.target.getLatLng();
                    updateCoordinates(pos);
                    reverseGeocode(pos);
                });

                // Centrar mapa
                map.setView(latLng, 17);
            }

            function updateCoordinates(latLng) {
                const lat = latLng.lat.toFixed(8);
                const lng = latLng.lng.toFixed(8);

                document.getElementById('lat').value = lat;
                document.getElementById('lng').value = lng;
                document.getElementById('lat_display').value = lat;
                document.getElementById('lng_display').value = lng;
            }

            async function searchAddress() {
                const address = document.getElementById('search_address').value;
                if (!address) return;

                try {
                    // Usar Nominatim (OpenStreetMap) para geocodificación
                    const response = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&countrycodes=mx&limit=1`);
                    const data = await response.json();

                    if (data && data.length > 0) {
                        const result = data[0];
                        const latLng = L.latLng(result.lat, result.lon);
                        placeMarker(latLng);

                        // Llenar campos de dirección
                        if (result.display_name) {
                            document.getElementById('direccion_completa_establecimiento').value = result.display_name;
                        }

                        // Intentar extraer detalles de la dirección
                        reverseGeocode(latLng);
                    } else {
                        alert('No se encontró la dirección. Intenta con otra búsqueda o haz clic en el mapa.');
                    }
                } catch (error) {
                    console.error('Error al buscar dirección:', error);
                    alert('Error al buscar la dirección. Por favor, intenta de nuevo.');
                }
            }

            async function reverseGeocode(latLng) {
                try {
                    const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latLng.lat}&lon=${latLng.lng}&zoom=18&addressdetails=1`);
                    const data = await response.json();

                    if (data && data.address) {
                        const addr = data.address;

                        // Llenar campos
                        if (data.display_name) {
                            document.getElementById('direccion_completa_establecimiento').value = data.display_name;
                        }

                        if (addr.suburb || addr.neighbourhood || addr.quarter) {
                            document.getElementById('colonia').value = addr.suburb || addr.neighbourhood || addr.quarter || '';
                        }

                        if (addr.city || addr.town || addr.municipality) {
                            document.getElementById('municipio').value = addr.city || addr.town || addr.municipality || '';
                        }

                        if (addr.state) {
                            document.getElementById('estado').value = addr.state;
                        }

                        if (addr.postcode) {
                            document.getElementById('codigo_postal').value = addr.postcode;
                        }
                    }
                } catch (error) {
                    console.error('Error al obtener dirección:', error);
                }
            }

            // Mostrar campo "Otro"
            document.getElementById('tipo_establecimiento').addEventListener('change', function() {
                document.getElementById('otro_tipo_container').classList.toggle('hidden', this.value !== 'Otro');
            });

            // Filtrar categorías
            document.getElementById('tipo_establecimiento').addEventListener('change', function() {
                const tipo = this.value;
                document.querySelectorAll('#categoria_id option[data-tipo]').forEach(opt => {
                    opt.style.display = (tipo === 'Otro' || !opt.dataset.tipo || opt.dataset.tipo === tipo) ? '' : 'none';
                });
            });

            // Validación antes de enviar
            document.getElementById('form-establecimiento').addEventListener('submit', function(e) {
                const lat = document.getElementById('lat').value;
                const lng = document.getElementById('lng').value;

                if (!lat || !lng) {
                    e.preventDefault();
                    alert('Por favor, selecciona una ubicación en el mapa haciendo clic sobre él.');
                    return false;
                }
            });
        </script>
    @endpush
</x-layouts.app>
