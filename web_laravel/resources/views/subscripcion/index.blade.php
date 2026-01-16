{{--
    Nombre del archivo        : index.blade.php
    Ruta                      : resources/views/subscripcion/index.blade.php
    Descripción               : Panel de suscripción.
    Funcionalidad             :
        - Muestra plan actual.
        - Modal de pago con validación real de fecha y botones Verde/Rojo.
        - Lógica para redirigir si el usuario no ha completado su registro (Error 404).
    Fecha de mantenimiento    : 21/01/2026
    Versión                   : 2.2 (Fix Colors & Validation)
--}}

<x-layouts.app :title="__('Suscripción')">

    @php
        $cliente = auth()->user()->cliente;
        $planActual = $cliente ? ($cliente->plan ?? 'sin_plan') : 'sin_plan';
        $esActivo = $cliente ? $cliente->suscripcion_activa : false;
        
        $fechaFin = ($cliente && $cliente->fecha_fin_suscripcion) ? $cliente->fecha_fin_suscripcion->format('d/m/Y') : 'N/A';
        $proximoPlan = $cliente ? $cliente->plan_proximo_vencimiento : null;
    @endphp

    <div class="py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto space-y-8">

            {{-- HEADER Y ESTADO ACTUAL --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                        {{ __('Suscripción') }}
                    </h1>
                    <p class="text-sm text-zinc-600 dark:text-zinc-400 mt-1">
                        Gestiona tu plan y facturación.
                    </p>
                </div>

                {{-- Tarjeta de Estado Actual --}}
                <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-700 rounded-xl p-4 shadow-sm flex items-center gap-4">
                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-full">
                        <flux:icon.check-circle class="size-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div>
                        <p class="text-xs text-zinc-500 dark:text-zinc-400 uppercase font-semibold">Plan Actual</p>
                        <p class="text-lg font-bold text-zinc-900 dark:text-white capitalize">
                            {{ $esActivo ? ucfirst($planActual) : 'Inactivo / Sin Plan' }}
                        </p>
                        @if($esActivo)
                            <p class="text-xs text-zinc-500 mt-1">
                                Vence: <span class="font-medium text-zinc-700 dark:text-zinc-300">{{ $fechaFin }}</span>
                                @if($proximoPlan)
                                    <br><span class="text-orange-500 font-medium">Cambio a {{ ucfirst($proximoPlan) }} programado.</span>
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- SELECCIÓN DE PLANES --}}
            <section>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    {{-- PLAN BÁSICO --}}
                    <div class="relative p-6 border-2 rounded-2xl transition-all duration-200 
                        {{ $planActual === 'basico' && $esActivo ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/10' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900' }}">
                        
                        @if($planActual === 'basico' && $esActivo)
                            <div class="absolute top-4 right-4">
                                <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full">PLAN ACTUAL</span>
                            </div>
                        @endif

                        <div class="text-center mb-6 pt-4">
                            <h3 class="text-xl font-bold text-orange-600">Plan Básico</h3>
                            <div class="flex justify-center items-baseline gap-1 my-2">
                                <span class="text-4xl font-extrabold text-zinc-900 dark:text-white">$199</span>
                                <span class="text-zinc-500">/mes</span>
                            </div>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Ideal para iniciar tu negocio</p>
                        </div>

                        <ul class="space-y-3 mb-8 text-sm text-zinc-700 dark:text-zinc-300">
                            <li class="flex items-center gap-2"><flux:icon.check class="size-4 text-green-500"/> 2 Establecimientos</li>
                            <li class="flex items-center gap-2"><flux:icon.check class="size-4 text-green-500"/> 10 Promociones</li>
                            <li class="flex items-center gap-2"><flux:icon.check class="size-4 text-green-500"/> 3 Banners</li>
                            <li class="flex items-center gap-2"><flux:icon.check class="size-4 text-green-500"/> Soporte 24/7</li>
                        </ul>

                        <div class="mt-auto">
                            @if($planActual === 'basico' && $esActivo)
                                <button disabled class="w-full py-2.5 rounded-lg bg-zinc-200 dark:bg-zinc-700 text-zinc-500 font-medium cursor-not-allowed">
                                    Plan Activo
                                </button>
                            @elseif($planActual === 'premium' && $esActivo)
                                {{-- Downgrade --}}
                                <button type="button" onclick="confirmarCambioPlan('basico', 'downgrade')" 
                                        class="w-full py-2.5 rounded-lg border border-zinc-300 dark:border-zinc-600 hover:bg-zinc-100 dark:hover:bg-zinc-800 text-zinc-900 dark:text-white font-medium transition">
                                    Cambiar a Básico
                                </button>
                            @else
                                {{-- Upgrade/Contratación --}}
                                <button type="button" onclick="iniciarProcesoPago('basico', 199)"
                                        class="w-full py-2.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-bold shadow-md transition transform hover:scale-[1.02]">
                                    Elegir Básico
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- PLAN PREMIUM --}}
                    <div class="relative p-6 border-2 rounded-2xl transition-all duration-200 
                        {{ $planActual === 'premium' && $esActivo ? 'border-orange-500 bg-orange-50 dark:bg-orange-900/10' : 'border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900' }}">
                        
                        @if($planActual === 'premium' && $esActivo)
                            <div class="absolute top-4 right-4">
                                <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full">PLAN ACTUAL</span>
                            </div>
                        @endif

                        <div class="text-center mb-6 pt-4">
                            <h3 class="text-xl font-bold text-orange-600">Plan Premium</h3>
                            <div class="flex justify-center items-baseline gap-1 my-2">
                                <span class="text-4xl font-extrabold text-zinc-900 dark:text-white">$399</span>
                                <span class="text-zinc-500">/mes</span>
                            </div>
                            <p class="text-sm text-zinc-600 dark:text-zinc-400">Máximo potencial y sin límites</p>
                        </div>

                        <ul class="space-y-3 mb-8 text-sm text-zinc-700 dark:text-zinc-300">
                            <li class="flex items-center gap-2"><flux:icon.check class="size-4 text-green-500"/> 6 Establecimientos</li>
                            <li class="flex items-center gap-2"><flux:icon.check class="size-4 text-green-500"/> 30 Promociones</li>
                            <li class="flex items-center gap-2"><flux:icon.check class="size-4 text-green-500"/> 10 Banners</li>
                            <li class="flex items-center gap-2"><flux:icon.check class="size-4 text-green-500"/> Soporte Prioritario</li>
                        </ul>

                        <div class="mt-auto">
                            @if($planActual === 'premium' && $esActivo)
                                <button disabled class="w-full py-2.5 rounded-lg bg-zinc-200 dark:bg-zinc-700 text-zinc-500 font-medium cursor-not-allowed">
                                    Plan Activo
                                </button>
                            @else
                                {{-- Upgrade --}}
                                <button type="button" onclick="iniciarProcesoPago('premium', 399)"
                                        class="w-full py-2.5 rounded-lg bg-orange-600 hover:bg-orange-700 text-white font-bold shadow-md transition transform hover:scale-[1.02]">
                                    Mejorar a Premium
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Tabla Comparativa --}}
                <div class="bg-white dark:bg-zinc-900 rounded-2xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                    <h3 class="text-lg font-bold text-center text-zinc-900 dark:text-white mb-6">Comparación Detallada</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-zinc-200 dark:border-zinc-700">
                                    <th class="text-left py-3 px-4 text-zinc-500">Característica</th>
                                    <th class="text-center py-3 px-4 text-blue-600 font-bold">Básico</th>
                                    <th class="text-center py-3 px-4 text-orange-600 font-bold">Premium</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                                <tr>
                                    <td class="py-3 px-4 text-zinc-900 dark:text-white">Establecimientos</td>
                                    <td class="text-center py-3 px-4">2</td>
                                    <td class="text-center py-3 px-4 font-bold">6</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-zinc-900 dark:text-white">Promociones</td>
                                    <td class="text-center py-3 px-4">10</td>
                                    <td class="text-center py-3 px-4 font-bold">30</td>
                                </tr>
                                <tr>
                                    <td class="py-3 px-4 text-zinc-900 dark:text-white">Banners</td>
                                    <td class="text-center py-3 px-4">3</td>
                                    <td class="text-center py-3 px-4 font-bold">10</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                {{-- Botón Cancelar Suscripción --}}
                @if($esActivo && $planActual !== 'sin_plan')
                    <div class="mt-8 pt-8 border-t border-zinc-200 dark:border-zinc-700 text-center">
                        <p class="text-sm text-zinc-500 mb-4">¿Deseas cancelar tu suscripción? Se mantendrá activa hasta el final del periodo.</p>
                        <button type="button" onclick="confirmarCambioPlan('sin_plan', 'cancel')" 
                                class="text-red-600 hover:text-red-700 font-medium text-sm underline hover:no-underline">
                            Cancelar mi suscripción
                        </button>
                    </div>
                @endif
            </section>
        </div>
    </div>

    {{-- ESTILOS CSS (Sobrescritura Nuclear para los Botones) --}}
    @push('styles')
    <style>
        .swal2-popup.swal-payment-modal { width: 500px !important; padding: 2rem !important; border-radius: 16px !important; }
        .payment-label { display: block; text-align: left; font-weight: 600; color: #374151; margin-bottom: 5px; font-size: 0.9rem; }
        .payment-input { width: 100%; border: 1px solid #d1d5db; padding: 10px; border-radius: 8px; font-size: 16px; color: #111827; text-align: center; }
        .payment-input:focus { outline: none; border-color: #f97316; box-shadow: 0 0 0 1px #f97316; }
        .payment-total { text-align: center; font-size: 1.5rem; font-weight: 800; margin-top: 1.5rem; color: #111827; }
        
        /* 🔥 CLAVES para el color de los botones (Sobrescriben temas globales) */
        /* Verde Pagar */
        body .swal2-container .swal2-confirm.btn-swal-green {
            background-color: #16a34a !important; 
            color: #ffffff !important;
            border: none !important;
            box-shadow: none !important;
        }
        /* Rojo Cancelar */
        body .swal2-container .swal2-cancel.btn-swal-red {
            background-color: #dc2626 !important; 
            color: #ffffff !important;
            border: none !important;
            box-shadow: none !important;
        }
        /* Negro (Errores/Info) */
        body .swal2-container .swal2-confirm.btn-swal-black {
            background-color: #000000 !important; 
            color: #ffffff !important;
            border: none !important;
        }
    </style>
    @endpush

    {{-- SCRIPTS DE LÓGICA --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Datos del usuario para el modal
        const userData = {
            nombre: "{{ auth()->user()->name }}",
            csrf: "{{ csrf_token() }}",
            url: "{{ route('clientes.changePlan') }}"
        };

        // --- 1. FLUJO DE PAGO (UPGRADE / CONTRATACIÓN) ---
        function iniciarProcesoPago(plan, precio) {
            Swal.fire({
                title: 'Realiza tu pago',
                html: `
                    <div style="text-align:left;">
                        <label class="payment-label">Nombre del titular</label>
                        <input class="payment-input" value="${userData.nombre}" readonly style="background:#f3f4f6; color:#6b7280;">
                        
                        <div style="margin-top:15px;">
                            <label class="payment-label">Número de tarjeta</label>
                            <input id="card-number" class="payment-input" placeholder="0000 0000 0000 0000" maxlength="19">
                        </div>
                        
                        <div style="display:flex; gap:15px; margin-top:15px;">
                            <div style="width:50%;">
                                <label class="payment-label" style="text-align:center;">Vencimiento</label>
                                <input id="card-exp" class="payment-input" placeholder="MM / AA" maxlength="5">
                            </div>
                            <div style="width:50%;">
                                <label class="payment-label" style="text-align:center;">CVV</label>
                                <input id="card-cvv" class="payment-input" placeholder="123" maxlength="3" type="password">
                            </div>
                        </div>
                        
                        <div class="payment-total">Importe: $${precio}</div>
                        <p style="text-align:center; font-size:0.8rem; color:#6b7280; margin-top:5px;">Pago seguro simulado</p>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: `Pagar`,
                cancelButtonText: 'Cancelar',
                reverseButtons: true, 
                focusConfirm: false,
                allowOutsideClick: false,
                
                // ✅ CLASES PERSONALIZADAS PARA COLORES VERDE/ROJO
                customClass: { 
                    popup: 'swal-payment-modal',
                    confirmButton: 'btn-swal-green',
                    cancelButton: 'btn-swal-red'
                },
                // ✅ Respaldo Inline
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#dc2626',

                didOpen: () => {
                    // Mascaras simples
                    document.getElementById('card-number').addEventListener('input', e => {
                        e.target.value = e.target.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
                    });
                    document.getElementById('card-exp').addEventListener('input', e => {
                        e.target.value = e.target.value.replace(/\D/g, '').replace(/^(\d{2})(\d{0,2})/, '$1/$2').substr(0, 5);
                    });
                    document.getElementById('card-cvv').addEventListener('input', e => {
                        e.target.value = e.target.value.replace(/\D/g, '').substr(0, 3);
                    });
                },
                preConfirm: () => {
                    const num = document.getElementById('card-number').value.replace(/\s/g, '');
                    const exp = document.getElementById('card-exp').value;
                    const cvv = document.getElementById('card-cvv').value;

                    // 1. Campos vacíos
                    if (num.length < 16 || exp.length < 5 || cvv.length < 3) {
                        Swal.showValidationMessage('Por favor completa los datos de la tarjeta.');
                        return false;
                    }

                    // 2. Lógica de Fecha (MM/AA)
                    const [mm, aa] = exp.split('/');
                    const month = parseInt(mm, 10);
                    // Asumimos año 20xx
                    const year = parseInt('20' + aa, 10);
                    
                    const now = new Date();
                    const currentYear = now.getFullYear();
                    const currentMonth = now.getMonth() + 1; // 1-12

                    if (month < 1 || month > 12) {
                        Swal.showValidationMessage('Mes inválido (01-12).');
                        return false;
                    }

                    // Si el año es menor al actual, o es el actual pero el mes ya pasó
                    if (year < currentYear || (year === currentYear && month < currentMonth)) {
                        Swal.showValidationMessage('La tarjeta está vencida.');
                        return false;
                    }

                    return true; // Todo OK
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    ejecutarCambioPlan(plan, true); // true = simulate_payment
                }
            });
        }

        // --- 2. FLUJO DE CAMBIO SIN PAGO (DOWNGRADE / CANCELAR) ---
        function confirmarCambioPlan(plan, tipo) {
            let titulo = '';
            let texto = '';
            let btnConfirmClass = '';
            let btnConfirmColor = '';

            if (tipo === 'downgrade') {
                titulo = '¿Cambiar a Plan Básico?';
                texto = 'El cambio se aplicará al finalizar tu periodo actual.';
                btnConfirmClass = 'btn-swal-black'; // O naranja si prefieres
                btnConfirmColor = '#f59e0b';
            } else if (tipo === 'cancel') {
                titulo = '¿Cancelar Suscripción?';
                texto = 'Tu suscripción se cancelará al finalizar el periodo actual.';
                btnConfirmClass = 'btn-swal-red';
                btnConfirmColor = '#dc2626';
            }

            Swal.fire({
                title: titulo,
                text: texto,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Volver',
                reverseButtons: true,
                // Estilos
                customClass: { confirmButton: btnConfirmClass, cancelButton: 'btn-swal-black' },
                confirmButtonColor: btnConfirmColor,
                cancelButtonColor: '#000000'
            }).then((result) => {
                if (result.isConfirmed) {
                    ejecutarCambioPlan(plan, false);
                }
            });
        }

        // --- 3. PETICIÓN AJAX UNIFICADA ---
        function ejecutarCambioPlan(plan, simulatePayment) {
            Swal.fire({
                title: 'Procesando...',
                text: 'Por favor espera un momento.',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading(),
                showConfirmButton: false
            });

            fetch(userData.url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': userData.csrf
                },
                body: JSON.stringify({
                    plan: plan,
                    simulate_payment: simulatePayment
                })
            })
            .then(res => {
                // MANEJO ESPECIAL DE ERROR 404: CLIENTE NO EXISTE
                if (res.status === 404) {
                    throw new Error('CLIENTE_NO_ENCONTRADO');
                }
                return res.json();
            })
            .then(data => {
                if (data.status === 'success' || data.status === 'scheduled') {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Listo!',
                        text: data.message,
                        confirmButtonText: 'Aceptar',
                        customClass: { confirmButton: 'btn-swal-green' },
                        confirmButtonColor: '#16a34a'
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ocurrió un error',
                        text: data.message || 'No se pudo procesar la solicitud.',
                        confirmButtonText: 'Entendido',
                        customClass: { confirmButton: 'btn-swal-black' },
                        confirmButtonColor: '#000000'
                    });
                }
            })
            .catch(err => {
                // LÓGICA DE REGISTRO INCOMPLETO
                if (err.message === 'CLIENTE_NO_ENCONTRADO') {
                    Swal.fire({
                        title: 'Registro Incompleto',
                        text: 'Para suscribirte a un plan, primero debes completar tu registro de cliente (Nombre, Teléfono, RFC).',
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Completar Registro',
                        cancelButtonText: 'Cancelar',
                        reverseButtons: true,
                        // Botón Completar en Negro (Acción Principal), Cancelar en Rojo
                        customClass: { confirmButton: 'btn-swal-black', cancelButton: 'btn-swal-red' },
                        confirmButtonColor: '#000000',
                        cancelButtonColor: '#dc2626'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "{{ route('registro.completar') }}";
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de conexión',
                        text: 'No se pudo conectar con el servidor.',
                        confirmButtonText: 'Cerrar',
                        customClass: { confirmButton: 'btn-swal-black' },
                        confirmButtonColor: '#000000'
                    });
                }
            });
        }
    </script>
    @endpush
</x-layouts.app>