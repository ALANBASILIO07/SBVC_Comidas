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

                <!-- CARD 2: UBICACIÓN CON MAPA -->
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden mb-6">
                    <div class="bg-gradient-to-r from-orange-500 fields to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.map-pin class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">Ubicación</h3>
                        </div>
                    </div>
                    <div class="p-6 space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar dirección</label>
                            <div class="flex gap-2">
                                <input type="text" id="search_address" placeholder="Escribe una dirección en México..."
                                    class="flex-1 rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500">
                                <button type="button" id="search_button" class="px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg">
                                    <flux:icon.magnifying-glass class="size-5" />
                                </button>
                            </div>
                        </div>

                        <div id="map" class="w-full h-96 rounded-lg border-2 border-gray-300 dark:border-zinc-600"></div>

                        <input type="hidden" name="lat" id="lat" value="{{ old('lat') }}" required>
                        <input type="hidden" name="lng" id="lng" value="{{ old('lng') }}" required>

                        <div>
                            <label for="direccion_completa_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Dirección completa *
                            </label>
                            <textarea name="direccion_completa_establecimiento" id="direccion_completa_establecimiento" rows="2" required
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="Calle, número, referencias...">{{ old('direccion_completa_establecimiento') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div><label>Colonia *</label><input type="text" name="colonia" id="colonia" value="{{ old('colonia') }}" required class="w-full rounded-lg"></div>
                            <div><label>Municipio *</label><input type="text" name="municipio" id="municipio" value="{{ old('municipio') }}" required class="w-full rounded-lg"></div>
                            <div><label>Estado *</label><input type="text" name="estado" id="estado" value="{{ old('estado') }}" required class="w-full rounded-lg"></div>
                            <div><label>Código Postal *</label><input type="text" name="codigo_postal" id="codigo_postal" value="{{ old('codigo_postal') }}" pattern="[0-9]{5}" maxlength="5" required class="w-full rounded-lg"></div>
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
                                <input type="time" name="horarios[lunes_viernes][apertura]" required class="w-32 rounded border-gray-300">
                                <span>a</span>
                                <input type="time" name="horarios[lunes_viernes][cierre]" required class="w-32 rounded border-gray-300">
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                            <span class="text-sm font-medium">Sábados</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="horarios[sabados][apertura]" class="w-32 rounded border-gray-300">
                                <span>a</span>
                                <input type="time" name="horarios[sabados][cierre]" class="w-32 rounded border-gray-300">
                            </div>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center justify-between p-3 bg-gray-50 dark:bg-zinc-900 rounded-lg">
                            <span class="text-sm font-medium">Domingos</span>
                            <div class="flex items-center space-x-2">
                                <input type="time" name="horarios[domingos][apertura]" class="w-32 rounded border-gray-300">
                                <span>a</span>
                                <input type="time" name="horarios[domingos][cierre]" class="w-32 rounded border-gray-300">
                            </div>
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="cierra_dias_festivos" value="1" {{ old('cierra_dias_festivos') ? 'checked' : '' }}
                                class="rounded text-orange-600">
                            <span class="ml-2 text-sm">Cerramos los días festivos</span>
                        </div>
                    </div>
                </div>

                <!-- Métodos de pago y fiscal (los tienes bien) -->
                <!-- ... (copia tus cards de métodos de pago y fiscal) ... -->

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

    @push('scripts')
        <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=places&callback=initMap" async defer></script>
        <script>
            let map, marker, geocoder;

            function initMap() {
                const mexico = { lat: 23.6345, lng: -102.5528 };
                map = new google.maps.Map(document.getElementById('map'), {
                    center: mexico,
                    zoom: 5,
                });

                geocoder = new google.maps.Geocoder();
                const autocomplete = new google.maps.places.Autocomplete(
                    document.getElementById('search_address'),
                    { types: ['geocode'], componentRestrictions: { country: 'mx' } }
                );

                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    if (place.geometry) {
                        map.setCenter(place.geometry.location);
                        map.setZoom(17);
                        placeMarker(place.geometry.location);
                        fillFromPlace(place);
                    }
                });

                map.addListener('click', e => {
                    placeMarker(e.latLng);
                    reverseGeocode(e.latLng);
                });

                @if(old('lat') && old('lng'))
                    const pos = { lat: {{ old('lat') }}, lng: {{ old('lng') }} };
                    map.setCenter(pos);
                    map.setZoom(17);
                    placeMarker(new google.maps.LatLng(pos.lat, pos.lng));
                @endif
            }

            function placeMarker(latLng) {
                if (marker) marker.setMap(null);
                marker = new google.maps.Marker({
                    position: latLng,
                    map: map,
                    draggable: true
                });
                document.getElementById('lat').value = latLng.lat();
                document.getElementById('lng').value = latLng.lng();

                marker.addListener('dragend', () => {
                    const pos = marker.getPosition();
                    document.getElementById('lat').value = pos.lat();
                    document.getElementById('lng').value = pos.lng();
                    reverseGeocode(pos);
                });
            }

            function reverseGeocode(latLng) {
                geocoder.geocode({ location: latLng }, (results, status) => {
                    if (status === 'OK' && results[0]) fillFromPlace(results[0]);
                });
            }

            function fillFromPlace(place) {
                document.getElementById('direccion_completa_establecimiento').value = place.formatted_address || '';
                let colonia = '', municipio = '', estado = '', cp = '';

                place.address_components.forEach(c => {
                    if (c.types.includes('sublocality') || c.types.includes('neighborhood')) colonia = c.long_name;
                    if (c.types.includes('locality')) municipio = c.long_name;
                    if (c.types.includes('administrative_area_level_1')) estado = c.long_name;
                    if (c.types.includes('postal_code')) cp = c.long_name;
                });

                document.getElementById('colonia').value = colonia;
                document.getElementById('municipio').value = municipio;
                document.getElementById('estado').value = estado;
                document.getElementById('codigo_postal').value = cp;
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

            document.addEventListener('DOMContentLoaded', () => {
                document.getElementById('tipo_establecimiento').dispatchEvent(new Event('change'));
            });
        </script>
    @endpush
</x-layouts.app>