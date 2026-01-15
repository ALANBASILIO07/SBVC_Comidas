{{--
    Nombre del archivo        : complete_profile.blade.php
    Descripción               : Vista para completar registro del cliente y permitir la selección/actualización de plan desde la misma pantalla.
    Fecha de creación         : 06/01/2026
    Elaboró                   : Alan Osvaldo Basilio Delgado
    Fecha de liberación       : 06/01/2026
    Autorizó                  : Maileth Patiño Ensastegui
    Versión                   : 1.3
    Fecha de mantenimiento    : 07/01/2026
    Folio de mantenimiento    :
    Tipo de mantenimiento     : UX / Compatibilidad Livewire
    Descripción del mantenimiento: Guard SPA para rutas protegidas y compatibilidad Swal con wire:navigate
    Responsable               : Alan Osvaldo Basilio Delgado
    Revisor                   : Maileth Patiño Ensastegui
--}}

<x-layouts.app :title="__('Completar Registro')">
    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            {{-- HEADER --}}
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-2">
                    <flux:icon.clipboard-document-check class="size-10 text-orange-500" />
                    <flux:heading size="xl" class="text-gray-900 dark:text-white">
                        {{ __('Completar Registro') }}
                    </flux:heading>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Por favor completa tu información como titular de la cuenta para continuar
                </p>
            </div>

            {{-- FORMULARIO --}}
            <form action="{{ route('clientes.store') }}" method="POST" class="space-y-6">
            @csrf

                {{-- CARD 1: INFORMACIÓN PERSONAL --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-sm border-2 border-orange-500 overflow-hidden">
                    <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-6 py-4">
                        <div class="flex items-center gap-3">
                            <flux:icon.identification class="size-6 text-white" />
                            <h3 class="text-lg font-bold text-white">
                                Información Personal
                            </h3>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div>
                            <label for="nombre_titular" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Nombre completo del titular *
                            </label>
                            <input 
                                type="text" 
                                id="nombre_titular" 
                                name="nombre_titular" 
                                value="{{ old('nombre_titular', auth()->user()->name) }}" 
                                class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                placeholder="Ej: Juan Pérez González" 
                                required
                            >
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                <flux:icon.information-circle class="inline size-4" />
                                Nombre completo de la persona responsable de la cuenta
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="email_contacto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Email de contacto *
                                </label>
                                <input 
                                    type="email" 
                                    id="email_contacto" 
                                    name="email_contacto" 
                                    value="{{ old('email_contacto', auth()->user()->email) }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 bg-gray-100 dark:bg-zinc-900" 
                                    readonly
                                >
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    Este email se toma de tu cuenta de usuario
                                </p>
                            </div>

                            <div>
                                <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    Teléfono de contacto *
                                </label>
                                <input 
                                    type="tel" 
                                    id="telefono" 
                                    name="telefono" 
                                    value="{{ old('telefono') }}" 
                                    class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                    placeholder="7202002222" 
                                    required
                                >
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: INFORMACIÓN FISCAL (OPCIONAL) --}}
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
                                    Si necesitas facturar o manejar datos fiscales, completa esta sección. 
                                    De lo contrario, puedes dejarla en blanco y agregarla después.
                                </span>
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="rfc_titular" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        RFC del Titular
                                    </label>
                                    <input 
                                        type="text" 
                                        id="rfc_titular" 
                                        name="rfc_titular" 
                                        value="{{ old('rfc_titular') }}" 
                                        class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500 uppercase" 
                                        placeholder="Ej: XAXX010101000"
                                        pattern="[A-ZÑ&]{3,4}[0-9]{6}[A-Z0-9]{3}"
                                        title="Formato: 3-4 letras, 6 números, 3 caracteres (ej: XAXX010101000)"
                                        minlength="13"
                                        maxlength="13"
                                    >
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        13 caracteres (personas físicas o morales)
                                    </p>
                                </div>

                                <div>
                                    <label for="razon_social_titular" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Razón Social
                                    </label>
                                    <input 
                                        type="text" 
                                        id="razon_social_titular" 
                                        name="razon_social_titular" 
                                        value="{{ old('razon_social_titular') }}" 
                                        class="w-full rounded-lg border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500" 
                                        placeholder="Nombre legal del titular o empresa"
                                        maxlength="255"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- NUEVA SECCIÓN: SELECCIÓN / ACTUALIZACIÓN DE PLAN --}}
                <div class="bg-gradient-to-r from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-xl border-2 border-orange-300 dark:border-orange-700 p-6">
                    <div class="flex items-start gap-4">
                        <flux:icon.sparkles class="size-8 text-orange-600 flex-shrink-0" />
                        <div class="flex-1">
                            <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                Puedes actualizar tu plan
                            </h4>
                            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                Selecciona un plan aquí. Puedes:
                                <ul class="list-disc list-inside">
                                    <li>Seleccionarlo ahora y se guardará junto con tu registro.</li>
                                    <li>O, si ya completaste tu registro, usar "Actualizar plan" para cambiarlo de inmediato (sin pago en modo demo).</li>
                                </ul>
                            </p>

                            <input type="hidden" name="plan" id="plan_input" value="{{ old('plan') }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                {{-- Básico --}}
                                <label class="plan-card p-4 rounded-lg border cursor-pointer" data-plan="basico" for="plan_basico">
                                    <input type="radio" name="plan_radio" id="plan_basico" value="basico" class="hidden" 
                                        @if(old('plan') === 'basico') checked @endif>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h5 class="font-bold text-lg">Plan Básico</h5>
                                            <p class="text-sm text-gray-600">$299 / mes — 1 establecimiento, promociones ilimitadas</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-2xl font-bold text-orange-500">$299</span>
                                            <div class="text-xs text-gray-500">/mes</div>
                                        </div>
                                    </div>
                                </label>

                                {{-- Premium --}}
                                <label class="plan-card p-4 rounded-lg border cursor-pointer" data-plan="premium" for="plan_premium">
                                    <input type="radio" name="plan_radio" id="plan_premium" value="premium" class="hidden"
                                        @if(old('plan') === 'premium') checked @endif>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h5 class="font-bold text-lg">Plan Premium</h5>
                                            <p class="text-sm text-gray-600">$599 / mes — establecimientos ilimitados, API access</p>
                                        </div>
                                        <div class="text-right">
                                            <span class="text-2xl font-bold text-orange-600">$599</span>
                                            <div class="text-xs text-gray-500">/mes</div>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            <div class="flex gap-3">
                                <button type="button" id="update-plan-btn" class="px-4 py-2 bg-orange-600 text-white rounded-lg shadow hover:bg-orange-700">
                                    Actualizar plan (rápido)
                                </button>

                                <button type="button" id="clear-plan-btn" class="px-4 py-2 bg-white border rounded-lg text-gray-700 hover:bg-gray-50">
                                    Quitar selección
                                </button>

                                <p class="text-xs text-gray-500 ml-3 self-center">Si quieres guardar la selección junto con el registro, haz clic en "Guardar y Continuar".</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BOTONES DE ACCIÓN --}}
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 pt-4">
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="w-full sm:w-auto px-8 py-3 bg-white dark:bg-zinc-800 border-2 border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 rounded-lg transition duration-200 font-medium text-center shadow-sm"
                    >
                        <flux:icon.arrow-left class="inline size-5 mr-2" />
                        Volver
                    </a>
                    <button
                        type="submit"
                        id="submit-btn"
                        class="w-full sm:w-auto px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg transition duration-200 font-bold text-center shadow-lg disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <flux:icon.check class="inline size-5 mr-2" />
                        <span id="btn-text">Guardar y Continuar</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Variables principales
            const form = document.querySelector('form[action*="clientes.store"]');
            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const nombreInput = document.getElementById('nombre_titular');
            const telefonoInput = document.getElementById('telefono');
            const rfcInput = document.getElementById('rfc_titular');

            // Plan UI
            const planCards = document.querySelectorAll('.plan-card');
            const planInputHidden = document.getElementById('plan_input');
            const updatePlanBtn = document.getElementById('update-plan-btn');
            const clearPlanBtn = document.getElementById('clear-plan-btn');
            const csrfToken = '{{ csrf_token() }}';

            // Manejo selección visual de plan
            function clearPlanSelectionVisual() {
                planCards.forEach(c => {
                    c.classList.remove('border-orange-500', 'bg-orange-50', 'ring-2');
                    const radio = c.querySelector('input[type="radio"]');
                    if (radio) radio.checked = false;
                });
            }

            planCards.forEach(card => {
                card.addEventListener('click', function() {
                    const plan = card.getAttribute('data-plan');
                    clearPlanSelectionVisual();
                    card.classList.add('border-orange-500', 'bg-orange-50', 'ring-2');
                    const radio = card.querySelector('input[type="radio"]');
                    if (radio) radio.checked = true;
                    planInputHidden.value = plan;
                });
            });

            clearPlanBtn.addEventListener('click', function() {
                clearPlanSelectionVisual();
                planInputHidden.value = '';
            });

            // Actualizar plan rápido (AJAX) — para usuarios que ya tienen cliente creado
            updatePlanBtn.addEventListener('click', function() {
                const selectedPlan = planInputHidden.value;
                if (!selectedPlan) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Ningún plan seleccionado',
                        text: 'Por favor selecciona un plan antes de actualizar.',
                        confirmButtonColor: '#ef4444'
                    });
                    return;
                }

                // Mostrar confirmación
                Swal.fire({
                    title: '¿Actualizar plan?',
                    html: `Se cambiará tu plan a <strong>${selectedPlan.toUpperCase()}</strong>.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, actualizar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#F7941D'
                }).then((res) => {
                    if (res.isConfirmed) {
                        // Enviar petición AJAX
                        Swal.fire({
                            title: 'Actualizando plan...',
                            text: 'Por favor espera',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => Swal.showLoading()
                        });

                        fetch("{{ route('clientes.changePlan') }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({ plan: selectedPlan })
                        })
                        .then(async resp => {
                            const json = await resp.json().catch(() => ({}));
                            if (!resp.ok) throw json;
                            return json;
                        })
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Plan actualizado!',
                                text: `Tu plan ha sido cambiado a ${selectedPlan.toUpperCase()}`,
                                confirmButtonColor: '#F7941D'
                            }).then(() => location.reload());
                        })
                        .catch(err => {
                            console.error(err);
                            const msg = err?.message || 'Error al actualizar el plan';
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: msg,
                                confirmButtonColor: '#ef4444'
                            });
                        });
                    }
                });
            });

            // --- Validación de formulario (tal como ya tenías) ---
            if (!form) {
                console.error('No se encontró el formulario');
                return;
            }

            if (!nombreInput || !telefonoInput) {
                console.error('No se encontraron los campos requeridos');
                return;
            }

            // Validación en tiempo real del nombre (solo letras y espacios)
            nombreInput.addEventListener('input', function(e) {
                let value = e.target.value;
                value = value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s]/g, '');
                e.target.value = value;
            });

            // Validación en tiempo real del teléfono (solo números)
            telefonoInput.addEventListener('input', function(e) {
                let value = e.target.value;
                value = value.replace(/[^0-9]/g, '');
                if (value.length > 20) {
                    value = value.substring(0, 20);
                }
                e.target.value = value;
            });

            // Validación del RFC (convertir a mayúsculas)
            if (rfcInput) {
                rfcInput.addEventListener('input', function(e) {
                    let value = e.target.value.toUpperCase();
                    value = value.replace(/[^A-ZÑ&0-9]/g, '');
                    if (value.length > 13) {
                        value = value.substring(0, 13);
                    }
                    e.target.value = value;
                });
            }

            // Al enviar el formulario, enviamos también el plan seleccionado (si existe)
            form.addEventListener('submit', function(e) {
                const selectedPlanFromRadio = document.querySelector('input[name="plan_radio"]:checked');
                if (selectedPlanFromRadio) {
                    planInputHidden.value = selectedPlanFromRadio.value;
                }

                const nombre = nombreInput.value.trim();
                const telefono = telefonoInput.value.trim();

                if (!nombre || nombre.length < 3) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "error",
                        title: "Campo incompleto",
                        text: "El nombre completo debe tener al menos 3 caracteres",
                        confirmButtonText: "Entendido",
                        confirmButtonColor: "#ef4444"
                    });
                    nombreInput.focus();
                    return false;
                }

                if (!telefono || telefono.length < 10) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "error",
                        title: "Campo incompleto",
                        text: "El teléfono debe tener al menos 10 dígitos",
                        confirmButtonText: "Entendido",
                        confirmButtonColor: "#ef4444"
                    });
                    telefonoInput.focus();
                    return false;
                }

                const rfc = rfcInput ? rfcInput.value.trim() : '';
                if (rfc && rfc.length > 0 && rfc.length !== 13) {
                    e.preventDefault();
                    Swal.fire({
                        icon: "error",
                        title: "RFC inválido",
                        text: "El RFC debe tener exactamente 13 caracteres",
                        confirmButtonText: "Entendido",
                        confirmButtonColor: "#ef4444"
                    });
                    rfcInput.focus();
                    return false;
                }

                // Mostrar loader y permitir envío
                submitBtn.disabled = true;
                btnText.textContent = 'Guardando...';

                Swal.fire({
                    title: 'Guardando información...',
                    html: 'Por favor espera mientras procesamos tus datos',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                return true;
            });

        });
    </script>
    @endpush
</x-layouts.app>