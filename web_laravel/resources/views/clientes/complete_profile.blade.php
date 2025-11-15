<x-layouts.app :title="__('Completar Registro')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-6xl mx-auto">
            
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <flux:icon.clipboard-document-check class="size-10 text-orange-500" />
                    <flux:heading size="xl" class="text-gray-900 dark:text-white">
                        {{ __('Terminar Registro') }}
                    </flux:heading>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Completa tu información para activar más funciones de la plataforma
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

            {{-- Mensaje de éxito --}}
            @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 rounded-lg p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <flux:icon.check-circle class="h-5 w-5 text-green-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
            @endif


            {{-- FORMULARIO --}}
            <form action="{{ route('clientes.store') }}" method="POST" class="space-y-6">
            @csrf
                
                {{-- ============================================ --}}
                {{-- CARD 1: INFORMACIÓN BÁSICA --}}
                {{-- ============================================ --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.building-storefront class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">
                                1. Información Básica del Negocio
                            </h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label for="nombre_negocio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombre del negocio *
                            </label>
                            <input 
                                type="text" 
                                id="nombre_negocio" 
                                name="nombre_negocio" 
                                value="{{ old('nombre_negocio') }}" 
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                placeholder="Ej: Mi Restaurante" 
                                required
                            >
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tipo_negocio" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tipo de negocio *
                                </label>
                                <select 
                                    id="tipo_negocio" 
                                    name="tipo_negocio" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    required
                                >
                                    <option value="">Selecciona...</option>
                                    <option value="Restaurante" {{ old('tipo_negocio') == 'Restaurante' ? 'selected' : '' }}>Restaurante</option>
                                    <option value="Cafetería" {{ old('tipo_negocio') == 'Cafetería' ? 'selected' : '' }}>Cafetería</option>
                                    <option value="Food Truck" {{ old('tipo_negocio') == 'Food Truck' ? 'selected' : '' }}>Food Truck</option>
                                    <option value="Panadería" {{ old('tipo_negocio') == 'Panadería' ? 'selected' : '' }}>Panadería</option>
                                    <option value="Bar" {{ old('tipo_negocio') == 'Bar' ? 'selected' : '' }}>Bar</option>
                                    <option value="Otro" {{ old('tipo_negocio') == 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>
                            </div>

                            <div>
                                <label for="formalidad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Formalidad del negocio *
                                </label>
                                <select 
                                    id="formalidad" 
                                    name="formalidad" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    required
                                >
                                    <option value="">Selecciona...</option>
                                    <option value="formal" {{ old('formalidad') == 'formal' ? 'selected' : '' }}>Formal (con registro ante SAT)</option>
                                    <option value="informal" {{ old('formalidad') == 'informal' ? 'selected' : '' }}>Informal (sin registro formal)</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="tipo_cuenta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Tipo de cuenta *
                                </label>
                                <select 
                                    id="tipo_cuenta" 
                                    name="tipo_cuenta" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    required
                                >
                                    <option value="">Selecciona...</option>
                                    <option value="basica" {{ old('tipo_cuenta') == 'basica' ? 'selected' : '' }}>Básica (Gratuita)</option>
                                    <option value="premium" {{ old('tipo_cuenta') == 'premium' ? 'selected' : '' }}>Premium (De pago)</option>
                                </select>
                            </div>

                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Teléfono *
                                </label>
                                <input 
                                    type="tel" 
                                    id="telefono" 
                                    name="telefono" 
                                    value="{{ old('telefono') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    placeholder="+52 123 456 7890" 
                                    required
                                >
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- CARD 2: INFORMACIÓN FISCAL (CONDICIONAL) --}}
                {{-- ============================================ --}}
                {{-- Se eliminó style="display: none;" para que sea visible por defecto --}}
                <div id="seccion-fiscal" class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.document-text class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white" id="header-fiscal">
                                2. Información Fiscal
                            </h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="rfc" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    RFC *
                                </label>
                                <input 
                                    type="text" 
                                    id="rfc" 
                                    name="rfc" 
                                    value="{{ old('rfc') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 uppercase" 
                                    placeholder="Ej: XAXX010101000"
                                    maxlength="13"
                                >
                            </div>

                            <div>
                                <label for="razon_social" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Razón Social *
                                </label>
                                <input 
                                    type="text" 
                                    id="razon_social" 
                                    name="razon_social" 
                                    value="{{ old('razon_social') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    placeholder="Nombre legal de la empresa"
                                >
                            </div>
                        </div>

                        <div>
                            <label for="direccion_fiscal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Dirección Fiscal Completa *
                            </label>
                            <textarea 
                                id="direccion_fiscal" 
                                name="direccion_fiscal" 
                                rows="3" 
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                placeholder="Calle, número, colonia, código postal, ciudad, estado..."
                            >{{ old('direccion_fiscal') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <flux:icon.information-circle class="inline size-4" />
                                Dirección registrada oficialmente ante el SAT
                            </p>
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input 
                                    type="checkbox" 
                                    id="ofrece_facturacion" 
                                    name="ofrece_facturacion" 
                                    value="1" 
                                    {{ old('ofrece_facturacion') ? 'checked' : '' }} 
                                    class="rounded border-gray-300 dark:border-zinc-600 text-orange-600 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                    <flux:icon.receipt-percent class="inline size-4" />
                                    Ofrece facturación electrónica a clientes
                                </span>
                            </label>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- CARD 3: DIRECCIÓN DEL ESTABLECIMIENTO --}}
                {{-- ============================================ --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.map-pin class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white" id="header-direccion">
                                3. Dirección del Establecimiento
                            </h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label for="direccion_completa" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Dirección completa *
                            </label>
                            <textarea 
                                id="direccion_completa" 
                                name="direccion_completa" 
                                rows="3" 
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                placeholder="Calle, número, colonia..." 
                                required
                            >{{ old('direccion_completa') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label for="ciudad" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Ciudad *
                                </label>
                                <input 
                                    type="text" 
                                    id="ciudad" 
                                    name="ciudad" 
                                    value="{{ old('ciudad') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
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
                                    maxlength="5"
                                    required
                                >
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
                            <h3 class="text-lg font-bold text-white" id="header-pagos">
                                4. Métodos de Pago Aceptados
                            </h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @php $metodos_pago_old = old('metodos_pago', []); @endphp
                            @foreach (['Efectivo', 'Tarjeta Débito', 'Tarjeta Crédito', 'Transferencia', 'Pago Móvil', 'PayPal', 'Mercado Pago', 'Otro'] as $metodo)
                            <label class="flex items-center p-3 border-2 border-gray-200 dark:border-zinc-700 rounded-lg hover:border-orange-300 dark:hover:border-orange-600 transition-colors cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    name="metodos_pago[]" 
                                    value="{{ $metodo }}" 
                                    {{ in_array($metodo, $metodos_pago_old) ? 'checked' : '' }} 
                                    class="rounded border-gray-300 dark:border-zinc-600 text-orange-600 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $metodo }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- CARD 5: HORARIOS DE ATENCIÓN --}}
                {{-- ============================================ --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.clock class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white" id="header-horarios">
                                5. Horarios de Atención
                            </h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="mb-4 p-4 bg-orange-50 dark:bg-orange-900/10 rounded-lg border-2 border-orange-200 dark:border-orange-800">
                            <label class="flex items-center cursor-pointer">
                                <input 
                                    type="checkbox" 
                                    id="abre_dias_festivos" 
                                    name="abre_dias_festivos" 
                                    value="1" 
                                    {{ old('abre_dias_festivos') ? 'checked' : '' }} 
                                    class="rounded border-gray-300 dark:border-zinc-600 text-orange-600 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                >
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <flux:icon.calendar-days class="inline size-5 text-orange-600" />
                                    Abrimos los días festivos
                                </span>
                            </label>
                        </div>

                        <div class="space-y-3">
                            <div class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-orange-50 dark:bg-orange-900/10 rounded-lg border border-orange-200 dark:border-orange-800">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 md:mb-0 flex items-center gap-2">
                                    <flux:icon.calendar class="size-4" />
                                    Lunes a Viernes
                                </span>
                                <div class="flex items-center space-x-2">
                                    <input 
                                        type="time" 
                                        name="horarios[lunes_viernes][apertura]" 
                                        value="{{ old('horarios.lunes_viernes.apertura') }}" 
                                        class="border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white rounded-lg shadow-sm w-32 focus:border-orange-500 focus:ring-orange-500" 
                                        required
                                    >
                                    <span class="text-gray-700 dark:text-gray-300">a</span>
                                    <input 
                                        type="time" 
                                        name="horarios[lunes_viernes][cierre]" 
                                        value="{{ old('horarios.lunes_viernes.cierre') }}" 
                                        class="border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white rounded-lg shadow-sm w-32 focus:border-orange-500 focus:ring-orange-500" 
                                        required
                                    >
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 md:mb-0 flex items-center gap-2">
                                    <flux:icon.calendar class="size-4" />
                                    Sábados
                                </span>
                                <div class="flex items-center space-x-2">
                                    <input 
                                        type="time" 
                                        name="horarios[sabados][apertura]" 
                                        value="{{ old('horarios.sabados.apertura') }}" 
                                        class="border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white rounded-lg shadow-sm w-32 focus:border-orange-500 focus:ring-orange-500"
                                    >
                                    <span class="text-gray-700 dark:text-gray-300">a</span>
                                    <input 
                                        type="time" 
                                        name="horarios[sabados][cierre]" 
                                        value="{{ old('horarios.sabados.cierre') }}" 
                                        class="border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white rounded-lg shadow-sm w-32 focus:border-orange-500 focus:ring-orange-500"
                                    >
                                </div>
                            </div>

                            <div class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-gray-50 dark:bg-zinc-700 rounded-lg">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 md:mb-0 flex items-center gap-2">
                                    <flux:icon.calendar class="size-4" />
                                    Domingos
                                </span>
                                <div class="flex items-center space-x-2">
                                    <input 
                                        type="time" 
                                        name="horarios[domingos][apertura]" 
                                        value="{{ old('horarios.domingos.apertura') }}" 
                                        class="border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white rounded-lg shadow-sm w-32 focus:border-orange-500 focus:ring-orange-500"
                                    >
                                    <span class="text-gray-700 dark:text-gray-300">a</span>
                                    <input 
                                        type="time" 
                                        name="horarios[domingos][cierre]" 
                                        value="{{ old('horarios.domingos.cierre') }}" 
                                        class="border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white rounded-lg shadow-sm w-32 focus:border-orange-500 focus:ring-orange-500"
                                    >
                                </div>
                            </div>

                            <div id="seccion-festivos" class="flex flex-col md:flex-row md:items-center justify-between p-4 bg-gray-50 dark:bg-zinc-700 rounded-lg" style="display: none;">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 md:mb-0 flex items-center gap-2">
                                    <flux:icon.calendar class="size-4" />
                                    Días Festivos
                                </span>
                                <div class="flex items-center space-x-2">
                                    <input 
                                        type="time" 
                                        name="horarios[festivos][apertura]" 
                                        value="{{ old('horarios.festivos.apertura') }}" 
                                        class="border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white rounded-lg shadow-sm w-32 focus:border-orange-500 focus:ring-orange-500"
                                    >
                                    <span class="text-gray-700 dark:text-gray-300">a</span>
                                    <input 
                                        type="time" 
                                        name="horarios[festivos][cierre]" 
                                        value="{{ old('horarios.festivos.cierre') }}" 
                                        class="border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white rounded-lg shadow-sm w-32 focus:border-orange-500 focus:ring-orange-500"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================================ --}}
                {{-- BOTONES DE ACCIÓN --}}
                {{-- ============================================ --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg transition duration-200 font-bold text-center shadow-lg"
                    >
                        <flux:icon.x-mark class="inline size-5 mr-2" />
                        Cancelar
                    </a>
                    <button 
                        type="submit" 
                        class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition duration-200 font-bold text-center shadow-lg"
                    >
                        <flux:icon.check class="inline size-5 mr-2" />
                        Guardar y Continuar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- El JavaScript ya se movió a app.js --}}
</x-layouts.app>