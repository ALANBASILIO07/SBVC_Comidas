<?php
/*
    Nombre del archivo        : index.blade.php
    Ruta                      : resources/views/subscripcion/index.blade.php
    Descripción               : Vista de planes y suscripción. Muestra el estado real del plan solo cuando la suscripción está activa (suscripcion_activa == true).
    Fecha de creación         : 06/01/2026
    Elaboró                   : Alan Osvaldo Basilio Delgado
    Fecha de liberación       : 06/01/2026
    Autorizó                  : Maileth Patiño Ensastegui
    Versión                   : 1.2
    Fecha de mantenimiento    : 15/01/2026
    Responsable               : Alan Osvaldo Basilio Delgado
    Revisor                   : Maileth Patiño Ensastegui
*/
?>

<x-layouts.app :title="__('Subscripción')">

{{-- PayPal SDK y SweetAlert --}}
@push('head')
@if(config('paypal.mode') !== 'demo')
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id', 'demo') }}&currency={{ config('paypal.currency', 'MXN') }}"></script>
@endif
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@php
    // Seguridad: si no se pasó $plan_actual, intentamos recuperarlo desde el cliente autenticado
    $clienteAuth = auth()->user()?->cliente ?? null;
    $planActualAssignado = $plan_actual ?? ($clienteAuth?->plan ?? null);
    $planActivo = $clienteAuth?->suscripcion_activa ?? false;
@endphp

<div class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- Header --}}
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                {{-- <flux:icon.currency-dollar class="size-10 text-orange-500" /> --}}
                <flux:heading size="xl">{{ __('Subscripción') }}</flux:heading>
            </div>

            <div class="flex items-center gap-2 px-4 py-2 bg-orange-100 dark:bg-orange-900/20 rounded-lg">
                <flux:icon.check-circle class="size-6 text-orange-500" />
                <span class="text-sm font-semibold text-orange-700 dark:text-orange-400">
                    {{-- Mostrar plan solo si está activo (pagado) --}}
                    Plan Actual: {{ $planActivo ? ucfirst($planActualAssignado) : __('Sin plan') }}
                </span>
            </div>
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
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-5xl mx-auto">

            {{-- PLAN BÁSICO --}}
            <div class="plan-card rounded-2xl p-8 transition-all duration-300 cursor-pointer relative
                        @if($planActualAssignado === 'basico' && $planActivo)
                            bg-gradient-to-br from-blue-500 to-blue-600 border-4 border-blue-700
                        @else
                            bg-white dark:bg-zinc-900 border-2 border-zinc-300 dark:border-zinc-700 hover:border-orange-400 hover:shadow-xl
                        @endif"
                 data-plan="basico">

                <input type="radio" name="plan" value="basico" class="hidden"
                       @if($planActualAssignado === 'basico') checked @endif />

                @if($planActualAssignado === 'basico' && $planActivo)
                <div class="mb-4">
                    <span class="inline-block bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                        ✓ PLAN ACTUAL
                    </span>
                </div>
                @endif

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold @if($planActualAssignado === 'basico' && $planActivo) text-white @else text-zinc-900 dark:text-white @endif mb-2">
                        Plan Básico
                    </h3>
                    <div class="flex items-baseline justify-center gap-1 mb-2">
                        <span class="text-4xl font-bold @if($planActualAssignado === 'basico' && $planActivo) text-white @else text-orange-500 @endif">
                            $199
                        </span>
                        <span class="text-lg @if($planActualAssignado === 'basico' && $planActivo) text-blue-100 @else text-zinc-600 dark:text-zinc-400 @endif">
                            /mes
                        </span>
                    </div>
                    <p class="text-sm @if($planActualAssignado === 'basico' && $planActivo) text-blue-100 @else text-zinc-600 dark:text-zinc-400 @endif">
                        Para negocios en crecimiento
                    </p>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($planActualAssignado === 'basico' && $planActivo) text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            2 Establecimientos
                        </span>
                    </li>

                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($planActualAssignado === 'basico' && $planActivo) text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Promociones: máximo 10 en total
                        </span>
                    </li>

                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($planActualAssignado === 'basico' && $planActivo) text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Banners: máximo 3 en total
                        </span>
                    </li>

                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 text-green-500 flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($planActualAssignado === 'basico' && $planActivo) text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Soporte 24/7
                        </span>
                    </li>
                </ul>

                @if($planActualAssignado === 'basico' && $planActivo)
                <flux:button class="w-full bg-white/20 text-white cursor-not-allowed" disabled>
                    Plan Actual
                </flux:button>
                @elseif($planActualAssignado === 'premium' && $planActivo)
                <flux:button class="w-full bg-gray-300 hover:bg-gray-400 text-gray-700">
                    Cambiar a Básico
                </flux:button>
                @else
                <flux:button class="w-full bg-blue-500 hover:bg-blue-600 text-white">
                    Actualizar a Básico
                </flux:button>
                @endif
            </div>

            {{-- PLAN PREMIUM --}}
            <div class="plan-card rounded-2xl p-8 transition-all duration-300 cursor-pointer relative
                        @if($planActualAssignado === 'premium' && $planActivo)
                            bg-gradient-to-br from-orange-500 to-orange-600 border-4 border-orange-700 shadow-2xl
                        @else
                            bg-white dark:bg-zinc-900 border-2 border-orange-400 hover:border-orange-500 hover:shadow-2xl
                        @endif"
                 data-plan="premium">

                <input type="radio" name="plan" value="premium" class="hidden"
                       @if($planActualAssignado === 'premium') checked @endif />

                @if($planActualAssignado === 'premium' && $planActivo)
                <div class="mb-4">
                    <span class="inline-block bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg">
                        ✓ PLAN ACTUAL
                    </span>
                </div>
                @else
                <div class="mb-4">
                    <span class="inline-block bg-yellow-400 text-zinc-900 px-4 py-1.5 rounded-full text-xs font-bold shadow-lg">
                        ⭐ RECOMENDADO
                    </span>
                </div>
                @endif

                <div class="text-center mb-6">
                    <h3 class="text-2xl font-bold @if($planActualAssignado === 'premium' && $planActivo) text-white @else text-zinc-900 dark:text-white @endif mb-2">
                        Plan Premium
                    </h3>
                    <div class="flex items-baseline justify-center gap-1 mb-2">
                        <span class="text-4xl font-bold @if($planActualAssignado === 'premium' && $planActivo) text-white @else text-orange-500 @endif">
                            $399
                        </span>
                        <span class="text-lg @if($planActualAssignado === 'premium' && $planActivo) text-orange-100 @else text-zinc-600 dark:text-zinc-400 @endif">
                            /mes
                        </span>
                    </div>
                    <p class="text-sm @if($planActualAssignado === 'premium' && $planActivo) text-orange-100 @else text-zinc-600 dark:text-zinc-400 @endif">
                        Máximo potencial
                    </p>
                </div>

                <ul class="space-y-3 mb-6">
                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 @if($planActualAssignado === 'premium' && $planActivo) text-white @else text-orange-500 @endif flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($planActualAssignado === 'premium' && $planActivo) text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            6 Establecimientos
                        </span>
                    </li>

                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 @if($planActualAssignado === 'premium' && $planActivo) text-white @else text-orange-500 @endif flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($planActualAssignado === 'premium' && $planActivo) text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Promociones: máximo 30 en total
                        </span>
                    </li>

                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 @if($planActualAssignado === 'premium' && $planActivo) text-white @else text-orange-500 @endif flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($planActualAssignado === 'premium' && $planActivo) text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Banners: máximo 10 en total
                        </span>
                    </li>

                    <li class="flex items-start gap-2">
                        <flux:icon.check class="size-5 @if($planActualAssignado === 'premium' && $planActivo) text-white @else text-orange-500 @endif flex-shrink-0 mt-0.5" />
                        <span class="text-sm @if($planActualAssignado === 'premium' && $planActivo) text-white font-semibold @else text-zinc-700 dark:text-zinc-300 @endif">
                            Soporte 24/7
                        </span>
                    </li>
                </ul>

                @if($planActualAssignado === 'premium' && $planActivo)
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
                            <th class="text-center py-4 px-4 text-blue-600 dark:text-blue-400 font-bold">Básico</th>
                            <th class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">Premium</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        <tr>
                            <td class="py-4 px-4 text-zinc-900 dark:text-white">Precio</td>
                            <td class="text-center py-4 px-4 text-blue-600 dark:text-blue-400 font-bold">$199/mes</td>
                            <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">$399/mes</td>
                        </tr>

                        <tr>
                            <td class="py-4 px-4 text-zinc-900 dark:text-white">Establecimientos</td>
                            <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400">2</td>
                            <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">6</td>
                        </tr>

                        <tr>
                            <td class="py-4 px-4 text-zinc-900 dark:text-white">Promociones (total)</td>
                            <td class="text-center py-4 px-4 text-blue-600 dark:text-blue-400 font-bold">10</td>
                            <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">30</td>
                        </tr>

                        <tr>
                            <td class="py-4 px-4 text-zinc-900 dark:text-white">Banners (total)</td>
                            <td class="text-center py-4 px-4 text-blue-600 dark:text-blue-400 font-bold">3</td>
                            <td class="text-center py-4 px-4 text-orange-600 dark:text-orange-400 font-bold">10</td>
                        </tr>

                        <tr>
                            <td class="py-4 px-4 text-zinc-900 dark:text-white">Soporte</td>
                            <td class="text-center py-4 px-4 text-zinc-600 dark:text-zinc-400 font-bold">24/7</td>
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
    captureOrderUrl: '{{ url('paypal/orders') }}',
    csrfToken: '{{ csrf_token() }}',
    demoMode: {{ config('paypal.mode') === 'demo' ? 'true' : 'false' }},
    currentPlan: '{{ $planActualAssignado }}',
    currentPlanActive: {{ $planActivo ? 'true' : 'false' }}
};

// (Resto del JS que ya tenías — no modifiqué la lógica demo/real aquí salvo el uso de currentPlanActive
// para condicionar botones si necesitas)
</script>
@endpush

</x-layouts.app>