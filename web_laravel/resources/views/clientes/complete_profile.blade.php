{{--
    Nombre del archivo        : complete_profile.blade.php
    Ruta                      : resources/views/clientes/complete_profile.blade.php
    Descripción               : Vista para completar registro del cliente y realizar el pago de suscripción.
    Fecha de creación         : 06/01/2026
    Elaboró                   : Alan Osvaldo Basilio Delgado
    Fecha de liberación       : 06/01/2026
    Autorizó                  : Maileth Patiño Ensastegui
    Versión                   : 4.3
    Fecha de mantenimiento    : 21/01/2026
    Tipo de mantenimiento     : FIX UI Definitivo (SweetAlert Colors) / UX / Lógica de Pago
    Descripción del mantenimiento: 
        - IMPLEMENTACIÓN CRÍTICA: Uso de propiedades nativas 'confirmButtonColor' y 'cancelButtonColor' 
          de SweetAlert2 para garantizar la visualización correcta de colores (Negro, Verde, Rojo) 
          sobreponiéndose a los estilos globales del framework CSS.
        - Diseño del modal de pago ajustado a referencia visual (Tarjeta ancha, inputs fecha/cvv alineados).
        - Validación lógica de fecha de vencimiento (no solo formato).
        - Solución de seguridad CSRF en peticiones AJAX.
    Responsable               : Alan Osvaldo Basilio Delgado
    Revisor                   : Maileth Patiño Ensastegui
--}}

<x-layouts.app :title="__('Completar Registro')">
    <div class="py-8 px-6 sm:px-10 lg:px-12 max-w-5xl mx-auto">
        {{-- HEADER --}}
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-3">
                <h1 class="text-2xl font-medium text-gray-900 dark:text-white">
                    {{ __('Completar Registro') }}
                </h1>
            </div>

            <p class="text-base text-gray-600 dark:text-gray-400 max-w-3xl">
                Por favor completa tu información como titular de la cuenta para continuar.
                <span class="font-semibold text-red-500 ml-1">Los campos marcados con <span class="text-red-500">*</span> son obligatorios.</span>
            </p>
        </div>

        {{-- FORMULARIO --}}
        <form id="profile-form" class="space-y-8" novalidate>
            <input type="hidden" name="_token" id="csrf_token_hidden" value="{{ csrf_token() }}">

            {{-- 1. INFORMACIÓN PERSONAL --}}
            <section class="bg-white dark:bg-zinc-900 rounded-xl shadow border border-zinc-200 dark:border-zinc-700 p-6">
                <header class="flex items-center gap-3 mb-4 border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Información Personal</h2>
                </header>

                <div class="space-y-4">
                    <div>
                        <label for="nombre_titular" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                            Nombre completo del titular <span class="text-red-500">*</span>
                        </label>
                        <input
                            id="nombre_titular"
                            name="nombre_titular"
                            type="text"
                            value="{{ old('nombre_titular', auth()->user()->name) }}"
                            class="w-full rounded-md border border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                            placeholder="Ej: Juan Pérez González"
                            required
                            autocomplete="name"
                        >
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="email_contacto" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Email de contacto <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="email_contacto"
                                name="email_contacto"
                                type="email"
                                value="{{ old('email_contacto', auth()->user()->email) }}"
                                readonly
                                class="w-full rounded-md border border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm bg-gray-100 dark:bg-zinc-900 cursor-not-allowed"
                            >
                        </div>

                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                Teléfono de contacto <span class="text-red-500">*</span>
                            </label>
                            <input
                                id="telefono"
                                name="telefono"
                                type="tel"
                                value="{{ old('telefono') }}"
                                class="w-full rounded-md border border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-orange-500 focus:ring-orange-500"
                                placeholder="Solo números, 10 dígitos"
                                maxlength="10"
                                required
                            >
                        </div>
                    </div>
                </div>
            </section>

            {{-- 2. INFORMACIÓN FISCAL --}}
            <section class="bg-white dark:bg-zinc-900 rounded-xl shadow border border-zinc-200 dark:border-zinc-700 p-6">
                <header class="flex items-center gap-3 mb-4 border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Información Fiscal (Opcional)</h2>
                </header>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="rfc_titular" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">RFC del Titular</label>
                        <input
                            id="rfc_titular"
                            name="rfc_titular"
                            type="text"
                            value="{{ old('rfc_titular') }}"
                            class="w-full rounded-md border border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-orange-500 uppercase"
                            placeholder="Ej: XAXX010101000"
                            maxlength="13"
                        >
                    </div>

                    <div>
                        <label for="razon_social_titular" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Razón Social</label>
                        <input
                            id="razon_social_titular"
                            name="razon_social_titular"
                            type="text"
                            value="{{ old('razon_social_titular') }}"
                            class="w-full rounded-md border border-gray-300 dark:border-zinc-600 dark:bg-zinc-800 dark:text-white shadow-sm focus:border-orange-500"
                            placeholder="Nombre legal del titular o empresa"
                        >
                    </div>
                </div>
            </section>

            {{-- 3. SELECCIÓN DE PLAN --}}
            <section class="bg-white dark:bg-zinc-900 rounded-xl shadow border border-zinc-200 dark:border-zinc-700 p-6">
                <header class="flex items-center gap-3 mb-4 border-b border-zinc-200 dark:border-zinc-700 pb-3">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-white">Selecciona tu plan</h2>
                </header>

                @php
                    $cliente = auth()->user()->cliente ?? null;
                    $planActual = $cliente?->plan ?? null;
                @endphp

                <input type="hidden" name="plan" id="plan_input" value="{{ old('plan', $planActual) }}">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    {{-- Plan Básico --}}
                    <label class="plan-card relative p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ ($planActual === 'basico') ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20' : 'border-zinc-200 dark:border-zinc-700' }}" data-plan="basico" data-price="199">
                        <input type="radio" name="plan_radio" value="basico" class="hidden" {{ ($planActual === 'basico') ? 'checked' : '' }}>
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-orange-600">Plan Básico</h3>
                                <p class="text-2xl font-extrabold text-zinc-900 dark:text-white">$199 <span class="text-sm font-normal text-zinc-500 dark:text-zinc-400">/mes</span></p>
                            </div>
                            <div class="selection-indicator {{ ($planActual === 'basico') ? 'block' : 'hidden' }}">
                                <span class="px-3 py-1 rounded-full bg-orange-600 text-white text-sm font-semibold">Seleccionado</span>
                            </div>
                        </div>
                        <ul class="mt-4 space-y-2 text-sm text-zinc-700 dark:text-zinc-300">
                            <li>2 Establecimientos</li>
                            <li>Promociones: máximo 10</li>
                            <li>Banners: máximo 3</li>
                            <li>Soporte 24/7</li>
                        </ul>
                    </label>

                    {{-- Plan Premium --}}
                    <label class="plan-card relative p-5 border-2 rounded-xl cursor-pointer transition-all duration-200 {{ ($planActual === 'premium') ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/20' : 'border-zinc-200 dark:border-zinc-700' }}" data-plan="premium" data-price="399">
                        <input type="radio" name="plan_radio" value="premium" class="hidden" {{ ($planActual === 'premium') ? 'checked' : '' }}>
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-semibold text-orange-600">Plan Premium</h3>
                                <p class="text-2xl font-extrabold text-zinc-900 dark:text-white">$399 <span class="text-sm font-normal text-zinc-500 dark:text-zinc-400">/mes</span></p>
                            </div>
                            <div class="selection-indicator {{ ($planActual === 'premium') ? 'block' : 'hidden' }}">
                                <span class="px-3 py-1 rounded-full bg-orange-600 text-white text-sm font-semibold">Seleccionado</span>
                            </div>
                        </div>
                        <ul class="mt-4 space-y-2 text-sm text-zinc-700 dark:text-zinc-300">
                            <li>6 Establecimientos</li>
                            <li>Promociones: máximo 30</li>
                            <li>Banners: máximo 10</li>
                            <li>Soporte 24/7</li>
                        </ul>
                    </label>
                </div>

                {{-- Tabla Comparativa --}}
                <div class="bg-white dark:bg-zinc-950 rounded-lg border border-zinc-200 dark:border-zinc-800 p-4">
                    <h3 class="text-lg font-medium text-center text-zinc-900 dark:text-white mb-4">Comparación Detallada de Planes</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                    <th class="text-left py-3 px-3 text-zinc-900 dark:text-white">Característica</th>
                                    <th class="text-center py-3 px-3 text-blue-600 dark:text-blue-400">Básico</th>
                                    <th class="text-center py-3 px-3 text-orange-600 dark:text-orange-400">Premium</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700 text-sm">
                                <tr>
                                    <td class="py-3 px-3 text-zinc-900 dark:text-white">Precio</td>
                                    <td class="text-center py-3 px-3 font-semibold">$199/mes</td>
                                    <td class="text-center py-3 px-3 font-semibold">$399/mes</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-3 text-zinc-900 dark:text-white">Establecimientos</td>
                                    <td class="text-center py-3 px-3">2</td>
                                    <td class="text-center py-3 px-3 font-semibold text-orange-600">6</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-3 text-zinc-900 dark:text-white">Promociones</td>
                                    <td class="text-center py-3 px-3">10</td>
                                    <td class="text-center py-3 px-3">30</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-3 text-zinc-900 dark:text-white">Soporte</td>
                                    <td class="text-center py-3 px-3">24/7</td>
                                    <td class="text-center py-3 px-3">24/7</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            {{-- BOTONES FINALES --}}
            <div class="flex justify-end items-center gap-4 pt-4">
                <button
                    type="submit"
                    id="submit-btn"
                    class="px-8 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-md font-bold shadow-md transition-colors duration-200"
                >
                    Terminar Registro
                </button>

                <a
                    href="{{ route('dashboard') }}"
                    class="px-8 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-md font-bold shadow-md transition-colors duration-200"
                >
                    Cancelar
                </a>
            </div>
        </form>
    </div>

    {{-- ESTILOS CSS --}}
    @push('styles')
    <style>
        /* Ajustes básicos para el modal de pago */
        .swal2-popup.swal-payment-modal {
            width: 580px !important;
            padding: 2rem !important;
            border-radius: 12px !important;
        }

        /* Estilo de los inputs dentro del modal */
        .payment-label {
            display: block;
            text-align: left;
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
            font-size: 0.95rem;
        }
        
        .payment-input {
            width: 100%;
            border: 1px solid #d1d5db;
            padding: 10px 12px;
            border-radius: 6px;
            font-size: 16px;
            color: #111827;
            background-color: #fff;
            text-align: center;
            box-sizing: border-box;
        }
        
        .payment-input:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 1px #000;
        }

        .payment-total {
            text-align: right;
            font-size: 1.25rem;
            font-weight: 800;
            margin-top: 1.5rem;
            color: #000;
        }

        .selection-indicator { display: inline-block; }
        .selection-indicator.hidden { display: none; }
    </style>
    @endpush

    {{-- SCRIPTS --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profile-form');
        const planInputHidden = document.getElementById('plan_input');
        const nombreInput = document.getElementById('nombre_titular');
        const telefonoInput = document.getElementById('telefono');
        const rfcInput = document.getElementById('rfc_titular');
        const planCards = document.querySelectorAll('.plan-card');

        // --- 1. Lógica de Selección de Plan ---
        if (planInputHidden && planInputHidden.value) {
            const pre = planInputHidden.value;
            planCards.forEach(c => {
                if (c.getAttribute('data-plan') === pre) selectPlanVisual(c);
            });
        }
        
        planCards.forEach(card => {
            card.addEventListener('click', function() {
                planCards.forEach(c => deselectPlanVisual(c));
                selectPlanVisual(this);
                planInputHidden.value = this.getAttribute('data-plan');
                const radio = this.querySelector('input[name="plan_radio"]');
                if (radio) radio.checked = true;
            });
        });
        
        function selectPlanVisual(card) {
            card.classList.remove('border-zinc-200', 'dark:border-zinc-700');
            card.classList.add('border-orange-500', 'bg-orange-50', 'dark:bg-orange-900/20');
            const ind = card.querySelector('.selection-indicator');
            if (ind) ind.classList.remove('hidden');
        }
        
        function deselectPlanVisual(card) {
            card.classList.remove('border-orange-500', 'bg-orange-50', 'dark:bg-orange-900/20');
            card.classList.add('border-zinc-200', 'dark:border-zinc-700');
            const ind = card.querySelector('.selection-indicator');
            if (ind) ind.classList.add('hidden');
        }

        // --- 2. Sanitización ---
        nombreInput?.addEventListener('input', e => e.target.value = e.target.value.replace(/[^A-Za-zÁÉÍÓÚáéíóúÑñ\s'-]/g, ''));
        telefonoInput?.addEventListener('input', e => e.target.value = e.target.value.replace(/[^0-9]/g, '').slice(0, 10));
        rfcInput?.addEventListener('input', e => e.target.value = e.target.value.toUpperCase().replace(/[^A-ZÑ&0-9]/g, '').slice(0, 13));

        // --- 3. SUBMIT PRINCIPAL ---
        form?.addEventListener('submit', function(ev) {
            ev.preventDefault();

            const nombre = nombreInput ? nombreInput.value.trim() : '';
            const telefono = telefonoInput ? telefonoInput.value.trim() : '';
            const rfc = rfcInput ? rfcInput.value.trim() : '';
            const razon = document.getElementById('razon_social_titular') ? document.getElementById('razon_social_titular').value.trim() : '';
            const plan = planInputHidden ? planInputHidden.value : '';

            // Validaciones
            let errores = [];
            if (!nombre) errores.push('El nombre es obligatorio.');
            if (telefono.length !== 10) errores.push('El teléfono debe tener 10 dígitos.');
            if (!plan) errores.push('Selecciona un plan.');

            if (errores.length > 0) {
                // ✅ IMPLEMENTACIÓN CORRECTA ALERTA ERROR (Botón Negro)
                Swal.fire({
                    icon: 'error',
                    title: 'Errores en el formulario',
                    html: `<div style="text-align:center; color:#555;">${errores.join('<br>')}</div>`,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#000000', // 🔥 CLAVE 1: Color inline (Prioridad máxima)
                    position: 'center'
                });
                return;
            }

            // Precio
            let planPrice = '0';
            planCards.forEach(c => {
                if(c.getAttribute('data-plan') === plan) planPrice = c.getAttribute('data-price');
            });

            // --- 4. MODAL DE PAGO (Botones Verde y Rojo Nativos) ---
            Swal.fire({
                title: 'Realiza tu pago',
                html: `
                    <div style="text-align:left;">
                        <label class="payment-label">Nombre del titular</label>
                        <input class="payment-input" value="${nombre}" readonly style="background:#f3f4f6; color:#555;">
                        
                        <div style="margin-top:15px;">
                            <label class="payment-label">Número de tarjeta</label>
                            <input id="card-number" class="payment-input" placeholder="0000 0000 0000 0000" maxlength="19">
                        </div>
                        
                        <div style="display:flex; gap:15px; margin-top:15px;">
                            <div style="width:50%;">
                                <label class="payment-label" style="text-align:center;">MM / YY</label>
                                <input id="card-exp" class="payment-input" placeholder="MM / YY" maxlength="5">
                            </div>
                            <div style="width:50%;">
                                <label class="payment-label" style="text-align:center;">CVV</label>
                                <input id="card-cvv" class="payment-input" placeholder="000" maxlength="3">
                            </div>
                        </div>
                        
                        <div class="payment-total">Importe: $ ${planPrice}</div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Pagar',
                cancelButtonText: 'Cancelar',
                // ✅ IMPLEMENTACIÓN CORRECTA COLORES PAGO
                confirmButtonColor: '#00a650', // 🔥 CLAVE 2: Verde
                cancelButtonColor: '#dc3545',  // 🔥 CLAVE 3: Rojo
                reverseButtons: true, // Pone el botón de confirmación (Pagar) a la derecha visualmente
                allowOutsideClick: false,
                customClass: {
                    popup: 'swal-payment-modal'
                },
                didOpen: () => {
                    // Formatos de inputs
                    document.getElementById('card-number').addEventListener('input', function(e){
                        let v = e.target.value.replace(/\D/g, '').substring(0,16);
                        e.target.value = v.match(/.{1,4}/g)?.join(' ') || v;
                    });
                    document.getElementById('card-exp').addEventListener('input', function(e){
                        let v = e.target.value.replace(/\D/g, '').substring(0,4);
                        if(v.length>=3) v = v.slice(0,2)+'/'+v.slice(2);
                        e.target.value = v;
                    });
                    document.getElementById('card-cvv').addEventListener('input', e => e.target.value = e.target.value.replace(/\D/g,'').substring(0,3));
                },
                preConfirm: () => {
                    const cNum = document.getElementById('card-number').value.replace(/\s/g,'');
                    const cExp = document.getElementById('card-exp').value;
                    const cCvv = document.getElementById('card-cvv').value;

                    if (cNum.length < 15) return Swal.showValidationMessage('Tarjeta incompleta.');
                    if (cCvv.length !== 3) return Swal.showValidationMessage('CVV incorrecto.');
                    if (!cExp.includes('/') || cExp.length !== 5) return Swal.showValidationMessage('Fecha inválida (MM/YY).');

                    // Lógica fecha
                    const [mm, yy] = cExp.split('/');
                    const mes = parseInt(mm, 10);
                    const anio = parseInt('20'+yy, 10);
                    const hoy = new Date();
                    const anioAct = hoy.getFullYear();
                    const mesAct = hoy.getMonth() + 1;

                    if (mes < 1 || mes > 12) return Swal.showValidationMessage('Mes inválido (01-12).');
                    if (anio < anioAct || (anio === anioAct && mes < mesAct)) return Swal.showValidationMessage('Tarjeta vencida.');

                    return { last4: cNum.slice(-4) };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    processPayment(nombre, telefono, rfc, razon, plan, result.value);
                }
            });
        });

        // --- 5. AJAX BACKEND ---
        function processPayment(nombre, telefono, rfc, razon, plan, cardData) {
            Swal.fire({
                title: 'Procesando...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
                showConfirmButton: false
            });

            const token = document.getElementById('csrf_token_hidden').value;

            fetch("{{ route('clientes.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    nombre_titular: nombre,
                    telefono: telefono,
                    rfc_titular: rfc,
                    razon_social_titular: razon,
                    plan: plan,
                    payment_data: cardData
                })
            })
            .then(async res => {
                if (res.status === 419) throw new Error('SESSION_EXPIRED');
                const json = await res.json().catch(()=>({}));
                if (!res.ok) throw new Error(json.message || 'Error del servidor');
                return json;
            })
            .then(data => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Pago Exitoso!',
                    text: 'Registro completado.',
                    showConfirmButton: false,
                    timer: 2000
                }).then(() => window.location.href = "{{ route('dashboard') }}");
            })
            .catch(err => {
                let msg = err.message;
                if(msg === 'SESSION_EXPIRED') msg = 'Tu sesión ha expirado. Por favor recarga la página.';
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: msg,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#000000' // 🔥 CLAVE 1 REPETIDA
                }).then((r) => {
                    if(msg.includes('sesión') || err.message === 'SESSION_EXPIRED') location.reload();
                });
            });
        }
    });
    </script>
    @endpush
</x-layouts.app>