<x-layouts.app :title="__('Nuevo Establecimiento')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            
            {{-- HEADER --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <flux:icon.building-storefront class="size-10 text-orange-500" />
                    <flux:heading size="xl" class="text-gray-900 dark:text-white">
                        {{ __('Nuevo Establecimiento') }}
                    </flux:heading>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Completa la información de tu establecimiento para comenzar a operar en la plataforma
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

            {{-- FORMULARIO --}}
            <form action="{{ route('establecimientos.store') }}" method="POST" class="space-y-6">
            @csrf
                
                {{-- ============================================ --}}
                {{-- CARD 1: INFORMACIÓN GENERAL --}}
                {{-- ============================================ --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.information-circle class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">
                                Información General
                            </h3>
                        </div>
                    </div>

                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label for="nombre_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombre del establecimiento *
                            </label>
                            <input 
                                type="text" 
                                id="nombre_establecimiento" 
                                name="nombre_establecimiento"
                                value="{{ old('nombre_establecimiento') }}"
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                placeholder="Ej: Mi Restaurante"
                                minlength="3"
                                maxlength="255"
                                required
                            >
                        </div>

                        <div>
                            <label for="tipo_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Tipo de establecimiento *
                            </label>
                            <select 
                                id="tipo_establecimiento" 
                                name="tipo_establecimiento" 
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                required
                            >
                                <option value="">Selecciona...</option>
                                <option value="Restaurante" {{ old('tipo_establecimiento') == 'Restaurante' ? 'selected' : '' }}>Restaurante</option>
                                <option value="Cafetería" {{ old('tipo_establecimiento') == 'Cafetería' ? 'selected' : '' }}>Cafetería</option>
                                <option value="Food Truck" {{ old('tipo_establecimiento') == 'Food Truck' ? 'selected' : '' }}>Food Truck</option>
                                <option value="Panadería" {{ old('tipo_establecimiento') == 'Panadería' ? 'selected' : '' }}>Panadería</option>
                                <option value="Bar" {{ old('tipo_establecimiento') == 'Bar' ? 'selected' : '' }}>Bar</option>
                                <option value="Otro" {{ old('tipo_establecimiento') == 'Otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                        </div>

                        <div>
                            <label for="categoria_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Categoría
                            </label>
                            <select 
                                id="categoria_id" 
                                name="categoria_id" 
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                            >
                                <option value="">Selecciona una categoría...</option>
                                @foreach($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="telefono_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Teléfono *
                            </label>
                            <input 
                                type="tel" 
                                id="telefono_establecimiento" 
                                name="telefono_establecimiento" 
                                value="{{ old('telefono_establecimiento') }}" 
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                placeholder="7201234567"
                                pattern="[0-9]{10,20}"
                                title="Solo números, entre 10 y 20 dígitos"
                                minlength="10"
                                maxlength="20"
                                required
                            >
                        </div>

                        <div>
                            <label for="correo_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Correo electrónico *
                            </label>
                            <input 
                                type="email" 
                                id="correo_establecimiento" 
                                name="correo_establecimiento" 
                                value="{{ old('correo_establecimiento') }}" 
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                placeholder="contacto@mirestaurante.com"
                                required
                            >
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- CARD 2: UBICACIÓN --}}
                {{-- ============================================ --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.map-pin class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">
                                Ubicación
                            </h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label for="direccion_completa_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Dirección completa *
                            </label>
                            <textarea 
                                id="direccion_completa_establecimiento" 
                                name="direccion_completa_establecimiento" 
                                rows="2" 
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                placeholder="Calle, número, referencias..." 
                                maxlength="500"
                                required
                            >{{ old('direccion_completa_establecimiento') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="colonia" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Colonia *
                                </label>
                                <input 
                                    type="text" 
                                    id="colonia" 
                                    name="colonia" 
                                    value="{{ old('colonia') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    maxlength="100"
                                    required
                                >
                            </div>

                            <div>
                                <label for="municipio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Municipio *
                                </label>
                                <input 
                                    type="text" 
                                    id="municipio" 
                                    name="municipio" 
                                    value="{{ old('municipio') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    maxlength="100"
                                    required
                                >
                            </div>

                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Estado *
                                </label>
                                <input 
                                    type="text" 
                                    id="estado" 
                                    name="estado" 
                                    value="{{ old('estado') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    maxlength="100"
                                    required
                                >
                            </div>

                            <div>
                                <label for="codigo_postal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Código Postal *
                                </label>
                                <input 
                                    type="text" 
                                    id="codigo_postal" 
                                    name="codigo_postal" 
                                    value="{{ old('codigo_postal') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    pattern="[0-9]{5}"
                                    title="Código postal de 5 dígitos"
                                    minlength="5"
                                    maxlength="5"
                                    placeholder="00000"
                                    required
                                >
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- CARD 3: INFORMACIÓN FISCAL (OPCIONAL) --}}
                {{-- ============================================ --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-gray-300 dark:border-zinc-700 overflow-hidden">
                    <div class="bg-gradient-to-r from-gray-500 to-gray-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.document-text class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">
                                Información Fiscal (Opcional)
                            </h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/10 rounded-lg border border-blue-200 dark:border-blue-800">
                            <p class="text-sm text-blue-800 dark:text-blue-200 flex items-start gap-2">
                                <flux:icon.information-circle class="size-5 flex-shrink-0 mt-0.5" />
                                <span>
                                    Si tu establecimiento ofrece facturación, completa esta sección. 
                                    De lo contrario, puedes dejarla en blanco.
                                </span>
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="rfc_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        RFC del Establecimiento
                                    </label>
                                    <input 
                                        type="text" 
                                        id="rfc_establecimiento" 
                                        name="rfc_establecimiento" 
                                        value="{{ old('rfc_establecimiento') }}" 
                                        class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 uppercase" 
                                        placeholder="Ej: XAXX010101000"
                                        pattern="[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}"
                                        title="Formato: 3-4 letras, 6 números, 3 caracteres"
                                        minlength="13"
                                        maxlength="13"
                                    >
                                </div>

                                <div>
                                    <label for="razon_social_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Razón Social
                                    </label>
                                    <input 
                                        type="text" 
                                        id="razon_social_establecimiento" 
                                        name="razon_social_establecimiento" 
                                        value="{{ old('razon_social_establecimiento') }}" 
                                        class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                        placeholder="Nombre legal del establecimiento"
                                        maxlength="255"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="direccion_fiscal_establecimiento" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Dirección Fiscal
                                </label>
                                <textarea 
                                    id="direccion_fiscal_establecimiento" 
                                    name="direccion_fiscal_establecimiento" 
                                    rows="2" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    placeholder="Dirección registrada ante el SAT..."
                                    maxlength="500"
                                >{{ old('direccion_fiscal_establecimiento') }}</textarea>
                            </div>

                            <div>
                                <label class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        id="facturacion_establecimiento" 
                                        name="facturacion_establecimiento" 
                                        value="1" 
                                        {{ old('facturacion_establecimiento') ? 'checked' : '' }} 
                                        class="rounded border-gray-300 dark:border-zinc-600 text-orange-600 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                    >
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                        <flux:icon.receipt-percent class="inline size-4" />
                                        Este establecimiento ofrece facturación electrónica
                                    </span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- CARD 4: MÉTODOS DE PAGO --}}
                {{-- ============================================ --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.credit-card class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">
                                Métodos de Pago Aceptados
                            </h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php 
                                $metodos_pago = ['Efectivo', 'Tarjeta Débito', 'Tarjeta Crédito', 'Transferencia', 'PayPal', 'Mercado Pago', 'Oxxo Pay', 'Otro'];
                                $metodos_old = old('tipos_pago_establecimiento', []);
                            @endphp
                            @foreach ($metodos_pago as $metodo)
                            <label class="flex items-center p-3 border-2 border-gray-200 dark:border-zinc-700 rounded-lg hover:border-orange-300 dark:hover:border-orange-600 transition-colors cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="tipos_pago_establecimiento[]" 
                                    value="{{ $metodo }}" 
                                    {{ in_array($metodo, $metodos_old) ? 'checked' : '' }} 
                                    class="rounded border-gray-300 dark:border-zinc-600 text-orange-600 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $metodo }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- BOTONES DE ACCIÓN --}}
                {{-- ============================================ --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a 
                        href="{{ route('establecimientos.index') }}" 
                        class="w-full sm:w-auto px-8 py-3 bg-white dark:bg-zinc-800 border-2 border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 rounded-lg transition duration-200 font-medium text-center shadow-sm"
                    >
                        <flux:icon.arrow-left class="inline size-5 mr-2" />
                        Cancelar
                    </a>
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg transition duration-200 font-bold text-center shadow-lg"
                    >
                        <flux:icon.check class="inline size-5 mr-2" />
                        Guardar Establecimiento
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>