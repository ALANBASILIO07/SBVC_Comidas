<x-layouts.app :title="__('Subscripción')">

{{-- PayPal SDK y SweetAlert --}}
@push('head')
@if(config('paypal.mode') !== 'demo')
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id', 'demo') }}&currency={{ config('paypal.currency', 'MXN') }}"></script>
@endif
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- Header --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <flux:icon.currency-dollar class="size-10 text-orange-500" />
                <flux:heading size="xl">{{ __('PLANES Y SUSCRIPCIÓN') }}</flux:heading>
            </div>

            @if($plan_actual !== 'basico')
            <div class="flex items-center gap-2 px-4 py-2 bg-orange-100 dark:bg-orange-900/20 rounded-lg">
                <flux:icon.check-circle class="size-6 text-orange-500" />
                <span class="text-sm font-semibold text-orange-700 dark:text-orange-400">
                    Plan Actual: {{ ucfirst($plan_actual) }}
                </span>
            </div>
            @endif
        </div>

        {{-- Modo DEMO Alert --}}
        @if(config('paypal.mode') === 'demo')
        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <flux:icon.information-circle class="h-6 w-6 text-blue-500" />
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                        Modo DEMO Activado
                    </h3>
                    <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                        <p>Los pagos se simularán automáticamente. No se procesarán pagos reales.</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Cards de Planes --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- PLAN BÁSICO --}}
            <div class="plan-card rounded-2xl p-8 transition-all duration-300 cursor-pointer
                        @if($plan_actual === 'basico')
                            bg-gradient-to-br from-gray-500 to-gray-600 border-4 border-gray-700
                        @else
                            bg-white dark:bg-zinc-900 border-2 border-zinc-300 dark:border-zinc-700 hover:border-orange-400 hover:shadow-xl
                        @endif"
                 data-plan="basico">

                <input type="radio" name="plan" value="basico" class="hidden"
                       @if($plan_actual === 'basico') checked @endif />

                @if($plan_actual === 'basico')
                <div class="absolute -top-3 -right-3">
                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                        ✓ ACTUAL
                    </span>
                </div>
                @endif

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold @if($plan_actual === 'basico') text-white @else text-zinc-900 dark:text-white @endif mb-2">
                        Plan Básico
                    </h3>
                    <div class="flex items-baseline justify-center gap-1 mb-2">
                        <span class="text-4xl font-bold @if($plan_actual === 'basico') text-white @else text-orange-500 @endif">
                            GRATIS
                        </span>
                    </div>
                    <p class="text-sm @if($plan_actual === 'basico') text-gray-200 @else text-zinc-600 dark:text-zinc-400 @endif">
                        Perfecto para comenzar
                    </p>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'basico') text-white @else text-zinc-700 dark:text-zinc-300 @endif">
                            1 Establecimiento
                        </span>
                    </li>
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'basico') text-white @else text-zinc-700 dark:text-zinc-300 @endif">
                            5 Promociones al mes
                        </span>
                    </li>
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'basico') text-white @else text-zinc-700 dark:text-zinc-300 @endif">
                            Soporte por email
                        </span>
                    </li>
                </ul>

                @if($plan_actual === 'basico')
                <flux:button class="w-full bg-white/20 text-white cursor-not-allowed" disabled>
                    Plan Actual
                </flux:button>
                @else
                <flux:button class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700">
                    Plan Gratuito
                </flux:button>
                @endif
            </div>

            {{-- PLAN ESTÁNDAR --}}
            <div class="plan-card rounded-2xl p-8 transition-all duration-300 cursor-pointer
                        @if($plan_actual === 'estandar')
                            bg-gradient-to-br from-blue-500 to-blue-600 border-4 border-blue-700
                        @else
                            bg-white dark:bg-zinc-900 border-2 border-zinc-300 dark:border-zinc-700 hover:border-orange-400 hover:shadow-xl
                        @endif"
                 data-plan="estandar">

                <input type="radio" name="plan" value="estandar" class="hidden"
                       @if($plan_actual === 'estandar') checked @endif />

                @if($plan_actual === 'estandar')
                <div class="absolute -top-3 -right-3">
                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                        ✓ ACTUAL
                    </span>
                </div>
                @endif

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold @if($plan_actual === 'estandar') text-white @else text-zinc-900 dark:text-white @endif mb-2">
                        Plan Estándar
                    </h3>
                    <div class="flex items-baseline justify-center gap-1 mb-2">
                        <span class="text-4xl font-bold @if($plan_actual === 'estandar') text-white @else text-orange-500 @endif">
                            $299
                        </span>
                        <span class="text-lg @if($plan_actual === 'estandar') text-blue-100 @else text-zinc-600 dark:text-zinc-400 @endif">
                            /mes
                        </span>
                    </div>
                    <p class="text-sm @if($plan_actual === 'estandar') text-blue-100 @else text-zinc-600 dark:text-zinc-400 @endif">
                        Para negocios en crecimiento
                    </p>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'estandar') text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            1 Establecimiento
                        </span>
                    </li>
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'estandar') text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Promociones ilimitadas
                        </span>
                    </li>
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'estandar') text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Estadísticas básicas
                        </span>
                    </li>
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'estandar') text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Soporte prioritario
                        </span>
                    </li>
                </ul>

                @if($plan_actual === 'estandar')
                <flux:button class="w-full bg-white/20 text-white cursor-not-allowed" disabled>
                    Plan Actual
                </flux:button>
                @elseif($plan_actual === 'premium')
                <flux:button class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700">
                    Cambiar a Estándar
                </flux:button>
                @else
                <flux:button class="w-full bg-blue-500 hover:bg-blue-600 text-white">
                    Actualizar a Estándar
                </flux:button>
                @endif
            </div>

            {{-- PLAN PREMIUM --}}
            <div class="plan-card rounded-2xl p-8 transition-all duration-300 cursor-pointer relative
                        @if($plan_actual === 'premium')
                            bg-gradient-to-br from-orange-500 to-orange-600 border-4 border-orange-700 shadow-2xl
                        @else
                            bg-white dark:bg-zinc-900 border-2 border-orange-400 hover:border-orange-500 hover:shadow-2xl
                        @endif"
                 data-plan="premium">

                <input type="radio" name="plan" value="premium" class="hidden"
                       @if($plan_actual === 'premium') checked @endif />

                @if($plan_actual !== 'premium')
                <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                    <span class="bg-yellow-400 text-zinc-900 px-4 py-1.5 rounded-full text-xs font-bold shadow-lg">
                        ⭐ RECOMENDADO
                    </span>
                </div>
                @else
                <div class="absolute -top-3 -right-3">
                    <span class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                        ✓ ACTUAL
                    </span>
                </div>
                @endif

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold @if($plan_actual === 'premium') text-white @else text-zinc-900 dark:text-white @endif mb-2">
                        Plan Premium
                    </h3>
                    <div class="flex items-baseline justify-center gap-1 mb-2">
                        <span class="text-4xl font-bold @if($plan_actual === 'premium') text-white @else text-orange-500 @endif">
                            $599
                        </span>
                        <span class="text-lg @if($plan_actual === 'premium') text-orange-100 @else text-zinc-600 dark:text-zinc-400 @endif">
                            /mes
                        </span>
                    </div>
                    <p class="text-sm @if($plan_actual === 'premium') text-orange-100 @else text-zinc-600 dark:text-zinc-400 @endif">
                        Máximo potencial
                    </p>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 @if($plan_actual === 'premium') text-white @else text-orange-500 @endif flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'premium') text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Establecimientos ilimitados
                        </span>
                    </li>
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 @if($plan_actual === 'premium') text-white @else text-orange-500 @endif flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'premium') text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Promociones ilimitadas
                        </span>
                    </li>
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 @if($plan_actual === 'premium') text-white @else text-orange-500 @endif flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'premium') text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Estadísticas avanzadas
                        </span>
                    </li>
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 @if($plan_actual === 'premium') text-white @else text-orange-500 @endif flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'premium') text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Soporte 24/7
                        </span>
                    </li>
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 @if($plan_actual === 'premium') text-white @else text-orange-500 @endif flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($plan_actual === 'premium') text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            API Access
                        </span>
                    </li>
                </ul>

                @if($plan_actual === 'premium')
                <flux:button class="w-full bg-white/20 text-white cursor-not-allowed" disabled>
                    Plan Actual
                </flux:button>
                @else
                <flux:button class="w-full bg-orange-600 hover:bg-orange-700 text-white shadow-lg">
                    Actualizar a Premium
                </flux:button>
                @endif
            </div>

        </div>

        {{-- Contenedor de Botones de PayPal --}}
        @if(config('paypal.mode') !== 'demo')
        <div id="paypal-button-container" class="max-w-md mx-auto"></div>
        @else
        <div id="demo-payment-button" class="max-w-md mx-auto"></div>
        @endif

        {{-- Tabla Comparativa --}}
        <div class="bg-white dark:bg-zinc-900 rounded-2xl p-8 shadow-lg border border-zinc-200 dark:border-zinc-800">
            <h3 class="text-2xl font-bold text-zinc-900 dark:text-white text-center mb-8">
                Comparación Detallada de Planes
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-zinc-200 dark:border-zinc-700">
                            <th class="text-left py-4 px-4 text-zinc-900 dark:text-white font-bold">Característica</th>
                            <th class="text-center py-4 px-4 text-zinc-900 dark:text-white font-bold">Básico</th>
                            <th class="text-center py-4 px-4 text-blue-600 dark:text-blue-400 font-bold">Estándar</th>
                            <th class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">Premium</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        <tr>
                            <td class="py-4 px-4 text-zinc-900 dark:text-white">Establecimientos</td>
                            <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">1</td>
                            <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">1</td>
                            <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">Ilimitados</td>
                        </tr>
                        <tr>
                            <td class="py-4 px-4 text-zinc-900 dark:text-white">Promociones</td>
                            <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">5/mes</td>
                            <td class="text-center py-4 px-4 text-blue-600 dark:text-blue-400 font-bold">Ilimitadas</td>
                            <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">Ilimitadas</td>
                        </tr>
                        <tr>
                            <td class="py-4 px-4 text-zinc-900 dark:text-white">Banners</td>
                            <td class="text-center py-4 px-4"><span class="text-red-600 text-xl">✗</span></td>
                            <td class="text-center py-4 px-4"><span class="text-green-600 text-xl">✓</span></td>
                            <td class="text-center py-4 px-4"><span class="text-green-600 text-xl">✓</span></td>
                        </tr>
                        <tr>
                            <td class="py-4 px-4 text-zinc-900 dark:text-white">Estadísticas</td>
                            <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">Básicas</td>
                            <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">Básicas</td>
                            <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">Avanzadas</td>
                        </tr>
                        <tr>
                            <td class="py-4 px-4 text-zinc-900 dark:text-white">Soporte</td>
                            <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">Email</td>
                            <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">Prioritario</td>
                            <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">24/7</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- JavaScript para Checkout --}}
@push('scripts')
<script>
// Configuración del checkout
const checkoutConfig = {
    createOrderUrl: '{{ route('paypal.create') }}',
    captureOrderUrl: '{{ route('paypal.capture') }}',
    csrfToken: '{{ csrf_token() }}',
    demoMode: {{ config('paypal.mode') === 'demo' ? 'true' : 'false' }},
    currentPlan: '{{ $plan_actual }}'
};

// Modo DEMO - Simular pagos
if (checkoutConfig.demoMode) {
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.plan-card');
        const demoButton = document.getElementById('demo-payment-button');
        let selectedPlan = null;

        const SELECTED_CLASSES = [
            'border-2',
            'border-orange-500',
            'ring-4',
            'ring-orange-500/40',
            'shadow-xl',
            'scale-[1.02]',
            'transform'
        ];

        function clearSelectedStyles() {
            cards.forEach(card => card.classList.remove(...SELECTED_CLASSES));
        }

        function setActiveCard() {
            clearSelectedStyles();
            const checked = document.querySelector('input[name="plan"]:checked');
            if (checked) {
                selectedPlan = checked.value;
                const card = checked.closest('.plan-card');
                if (card) {
                    card.classList.add(...SELECTED_CLASSES);
                    showDemoButton();
                }
            }
        }

        function showDemoButton() {
            if (!selectedPlan || selectedPlan === checkoutConfig.currentPlan) {
                demoButton.innerHTML = '';
                return;
            }

            if (selectedPlan === 'basico') {
                demoButton.innerHTML = '';
                return;
            }

            demoButton.innerHTML = `
                <button id="demo-pay-btn" class="w-full px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                    <div class="flex items-center justify-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span>Simular Pago - Plan ${selectedPlan.charAt(0).toUpperCase() + selectedPlan.slice(1)}</span>
                    </div>
                </button>
            `;

            document.getElementById('demo-pay-btn').addEventListener('click', processDemoPayment);
        }

        function processDemoPayment() {
            Swal.fire({
                title: 'Procesando pago...',
                html: 'Simulando transacción...<br><small class="text-gray-500">Modo DEMO activado</small>',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Simular delay de pago
            setTimeout(() => {
                fetch('{{ route('paypal.create') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': checkoutConfig.csrfToken,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        plan: selectedPlan,
                        demo: true
                    })
                })
                .then(res => res.json())
                .then(data => {
                    Swal.fire({
                        icon: 'success',
                        title: '¡Pago Simulado Exitoso!',
                        html: `
                            <p class="mb-2">Plan actualizado a: <strong class="text-orange-500">${selectedPlan.toUpperCase()}</strong></p>
                            <p class="text-sm text-gray-600">En producción, este sería un pago real con PayPal</p>
                        `,
                        timer: 3000,
                        timerProgressBar: true,
                        confirmButtonColor: '#F7941D',
                        draggable: true
                    }).then(() => {
                        window.location.reload();
                    });
                })
                .catch(err => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error en simulación',
                        text: 'Hubo un error al simular el pago',
                        confirmButtonColor: '#ef4444',
                        draggable: true
                    });
                });
            }, 2000);
        }

        cards.forEach(card => {
            card.addEventListener('click', () => {
                const radio = card.querySelector('input[name="plan"]');
                if (radio && !radio.disabled) {
                    radio.checked = true;
                    setActiveCard();
                }
            });
        });

        // Inicializar
        const checkedRadio = document.querySelector('input[name="plan"]:checked');
        if (checkedRadio) {
            setActiveCard();
        }
    });
} else {
    // Modo PayPal Real - Cargar script de checkout
    const script = document.createElement('script');
    script.src = '{{ asset('js/plans-checkout.js') }}';
    script.type = 'module';
    script.onload = function() {
        if (window.initPlansCheckout) {
            window.initPlansCheckout(checkoutConfig);
        }
    };
    document.head.appendChild(script);
}
</script>
@endpush

</x-layouts.app>
