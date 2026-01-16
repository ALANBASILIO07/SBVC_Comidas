{{--
* Nombre del archivo           : two-factor.blade.php
* Ruta                         : resources/views/livewire/settings/two-factor.blade.php
* Descripción de la vista      : Componente Livewire/Volt para la gestión de la autenticación de dos factores (2FA).
* Incluye activación (QR), confirmación (OTP) y desactivación.
* Fecha de creación            : 06/01/2026
* Elaboró                      : Alan Osvaldo Basilio Delgado
* Fecha de liberación          : 06/01/2026
* Autorizó                     : Maileth Patiño Ensastegui
* Versión                      : 1.1
* Fecha de mantenimiento       : 15/01/2026
* Tipo de mantenimiento        : Adaptación de Interfaz / UX / Traducción
* Descripción del mantenimiento: Implementación de notificaciones con SweetAlert2 y traducción completa al español.
* Responsable                  : Alan Osvaldo Basilio Delgado
* Revisor                      : Maileth Patiño Ensastegui
--}}

<?php

use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;
use Symfony\Component\HttpFoundation\Response;

new class extends Component {
    #[Locked]
    public bool $twoFactorEnabled;

    #[Locked]
    public bool $requiresConfirmation;

    #[Locked]
    public string $qrCodeSvg = '';

    #[Locked]
    public string $manualSetupKey = '';

    public bool $showModal = false;

    public bool $showVerificationStep = false;

    #[Validate('required|string|size:6', onUpdate: false)]
    public string $code = '';

    /**
     * Mount the component.
     */
    public function mount(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        abort_unless(Features::enabled(Features::twoFactorAuthentication()), Response::HTTP_FORBIDDEN);

        if (Fortify::confirmsTwoFactorAuthentication() && is_null(auth()->user()->two_factor_confirmed_at)) {
            $disableTwoFactorAuthentication(auth()->user());
        }

        $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        $this->requiresConfirmation = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm');
    }

    /**
     * Enable two-factor authentication for the user.
     */
    public function enable(EnableTwoFactorAuthentication $enableTwoFactorAuthentication): void
    {
        $enableTwoFactorAuthentication(auth()->user());

        if (! $this->requiresConfirmation) {
            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        }

        $this->loadSetupData();

        $this->showModal = true;
    }

    /**
     * Load the two-factor authentication setup data for the user.
     */
    private function loadSetupData(): void
    {
        $user = auth()->user();

        try {
            $this->qrCodeSvg = $user?->twoFactorQrCodeSvg();
            $this->manualSetupKey = decrypt($user->two_factor_secret);
        } catch (Exception) {
            $this->addError('setupData', __('No se pudieron cargar los datos de configuración.'));
            $this->reset('qrCodeSvg', 'manualSetupKey');
        }
    }

    /**
     * Show the two-factor verification step if necessary.
     */
    public function showVerificationIfNecessary(): void
    {
        if ($this->requiresConfirmation) {
            $this->showVerificationStep = true;
            $this->resetErrorBag();
            return;
        }

        $this->closeModal();
    }

    /**
     * Confirm two-factor authentication for the user.
     */
    public function confirmTwoFactor(ConfirmTwoFactorAuthentication $confirmTwoFactorAuthentication): void
    {
        try {
            $this->validate();
            $confirmTwoFactorAuthentication(auth()->user(), $this->code);
            
            $this->closeModal();
            $this->twoFactorEnabled = true;

            // Despachar evento de éxito
            $this->dispatch('2fa-confirmed');

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Mostrar error visualmente
            $this->addError('code', __('El código de autenticación proporcionado no es válido.'));
            $this->dispatch('2fa-error', message: __('El código es incorrecto. Intenta nuevamente.'));
        }
    }

    /**
     * Reset two-factor verification state.
     */
    public function resetVerification(): void
    {
        $this->reset('code', 'showVerificationStep');
        $this->resetErrorBag();
    }

    /**
     * Disable two-factor authentication for the user.
     */
    public function disable(DisableTwoFactorAuthentication $disableTwoFactorAuthentication): void
    {
        $disableTwoFactorAuthentication(auth()->user());
        $this->twoFactorEnabled = false;
        
        $this->dispatch('2fa-disabled');
    }

    /**
     * Close the two-factor authentication modal.
     */
    public function closeModal(): void
    {
        $this->reset(
            'code',
            'manualSetupKey',
            'qrCodeSvg',
            'showModal',
            'showVerificationStep',
        );

        $this->resetErrorBag();

        if (! $this->requiresConfirmation) {
            $this->twoFactorEnabled = auth()->user()->hasEnabledTwoFactorAuthentication();
        }
    }

    /**
     * Get the current modal configuration state (Translated).
     */
    public function getModalConfigProperty(): array
    {
        if ($this->twoFactorEnabled) {
            return [
                'title' => __('Autenticación de 2 Factores Habilitada'),
                'description' => __('La autenticación de dos factores está habilitada. Escanea el código QR o ingresa la clave de configuración en tu aplicación de autenticación.'),
                'buttonText' => __('Cerrar'),
            ];
        }

        if ($this->showVerificationStep) {
            return [
                'title' => __('Verificar Código de Autenticación'),
                'description' => __('Ingresa el código de 6 dígitos de tu aplicación de autenticación.'),
                'buttonText' => __('Continuar'),
            ];
        }

        return [
            'title' => __('Habilitar Autenticación de 2 Factores'),
            'description' => __('Para finalizar la activación, escanea el código QR o ingresa la clave de configuración en tu aplicación de autenticación.'),
            'buttonText' => __('Continuar'),
        ];
    }
} ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout
        :heading="__('Autenticación de dos factores')"
        :subheading="__('Gestiona la configuración de seguridad adicional de tu cuenta')"
    >
        <div class="flex flex-col w-full mx-auto space-y-6 text-sm" wire:cloak>
            @if ($twoFactorEnabled)
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="green">{{ __('Habilitado') }}</flux:badge>
                    </div>

                    <flux:text>
                        {{ __('Con la autenticación de dos factores habilitada, se te pedirá un PIN seguro y aleatorio durante el inicio de sesión, el cual puedes obtener de la aplicación TOTP en tu teléfono.') }}
                    </flux:text>

                    <livewire:settings.two-factor.recovery-codes :$requiresConfirmation/>

                    <div class="flex justify-start">
                        {{-- Botón Desactivar con confirmación JS --}}
                        <flux:button
                            variant="danger"
                            icon="shield-exclamation"
                            icon:variant="outline"
                            onclick="confirmDisable2FA()"
                        >
                            {{ __('Desactivar 2FA') }}
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="space-y-4">
                    <div class="flex items-center gap-3">
                        <flux:badge color="red">{{ __('Deshabilitado') }}</flux:badge>
                    </div>

                    <flux:text variant="subtle">
                        {{ __('Cuando habilitas la autenticación de dos factores, se te pedirá un PIN seguro durante el inicio de sesión. Este PIN se puede obtener desde una aplicación compatible con TOTP (como Google Authenticator) en tu teléfono.') }}
                    </flux:text>

                    <flux:button
                        variant="primary"
                        icon="shield-check"
                        icon:variant="outline"
                        wire:click="enable"
                    >
                        {{ __('Activar 2FA') }}
                    </flux:button>
                </div>
            @endif
        </div>
    </x-settings.layout>

    <flux:modal
        name="two-factor-setup-modal"
        class="max-w-md md:min-w-md"
        @close="closeModal"
        wire:model="showModal"
    >
        <div class="space-y-6">
            <div class="flex flex-col items-center space-y-4">
                {{-- Contenedor QR --}}
                <div class="p-0.5 w-auto rounded-full border border-stone-100 dark:border-stone-600 bg-white dark:bg-stone-800 shadow-sm">
                    <div class="p-2.5 rounded-full border border-stone-200 dark:border-stone-600 overflow-hidden bg-stone-100 dark:bg-stone-200 relative">
                        {{-- Fondo Decorativo --}}
                        <div class="flex items-stretch absolute inset-0 w-full h-full divide-x [&>div]:flex-1 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++) <div></div> @endfor
                        </div>
                        <div class="flex flex-col items-stretch absolute w-full h-full divide-y [&>div]:flex-1 inset-0 divide-stone-200 dark:divide-stone-300 justify-around opacity-50">
                            @for ($i = 1; $i <= 5; $i++) <div></div> @endfor
                        </div>
                        <flux:icon.qr-code class="relative z-20 dark:text-accent-foreground"/>
                    </div>
                </div>

                <div class="space-y-2 text-center">
                    <flux:heading size="lg">{{ $this->modalConfig['title'] }}</flux:heading>
                    <flux:text>{{ $this->modalConfig['description'] }}</flux:text>
                </div>
            </div>

            @if ($showVerificationStep)
                <div class="space-y-6">
                    <div class="flex flex-col items-center space-y-3">
                        <x-input-otp
                            :digits="6"
                            name="code"
                            wire:model="code"
                            autocomplete="one-time-code"
                        />
                        @error('code')
                            <flux:text color="red">
                                {{ $message }}
                            </flux:text>
                        @enderror
                    </div>

                    <div class="flex items-center space-x-3">
                        <flux:button
                            variant="outline"
                            class="flex-1"
                            wire:click="resetVerification"
                        >
                            {{ __('Atrás') }}
                        </flux:button>

                        <flux:button
                            variant="primary"
                            class="flex-1"
                            wire:click="confirmTwoFactor"
                            x-bind:disabled="$wire.code.length < 6"
                        >
                            {{ __('Confirmar') }}
                        </flux:button>
                    </div>
                </div>
            @else
                @error('setupData')
                    <flux:callout variant="danger" icon="x-circle" heading="{{ $message }}"/>
                @enderror

                <div class="flex justify-center">
                    <div class="relative w-64 overflow-hidden border rounded-lg border-stone-200 dark:border-stone-700 aspect-square">
                        @empty($qrCodeSvg)
                            <div class="absolute inset-0 flex items-center justify-center bg-white dark:bg-stone-700 animate-pulse">
                                <flux:icon.loading/>
                            </div>
                        @else
                            <div class="flex items-center justify-center h-full p-4">
                                <div class="bg-white p-3 rounded">
                                    {!! $qrCodeSvg !!}
                                </div>
                            </div>
                        @endempty
                    </div>
                </div>

                <div>
                    <flux:button
                        :disabled="$errors->has('setupData')"
                        variant="primary"
                        class="w-full"
                        wire:click="showVerificationIfNecessary"
                    >
                        {{ $this->modalConfig['buttonText'] }}
                    </flux:button>
                </div>

                <div class="space-y-4">
                    <div class="relative flex items-center justify-center w-full">
                        <div class="absolute inset-0 w-full h-px top-1/2 bg-stone-200 dark:bg-stone-600"></div>
                        <span class="relative px-2 text-sm bg-white dark:bg-stone-800 text-stone-600 dark:text-stone-400">
                            {{ __('o, ingresa el código manualmente') }}
                        </span>
                    </div>

                    <div
                        class="flex items-center space-x-2"
                        x-data="{
                            copied: false,
                            async copy() {
                                try {
                                    await navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                    this.copied = true;
                                    
                                    // Feedback visual
                                    const Toast = Swal.mixin({
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 1500,
                                        background: '#333',
                                        color: '#fff'
                                    });
                                    Toast.fire({ icon: 'success', title: 'Copiado' });

                                    setTimeout(() => this.copied = false, 1500);
                                } catch (e) {
                                    console.warn('No se pudo copiar al portapapeles');
                                }
                            }
                        }"
                    >
                        <div class="flex items-stretch w-full border rounded-xl dark:border-stone-700">
                            @empty($manualSetupKey)
                                <div class="flex items-center justify-center w-full p-3 bg-stone-100 dark:bg-stone-700">
                                    <flux:icon.loading variant="mini"/>
                                </div>
                            @else
                                <input
                                    type="text"
                                    readonly
                                    value="{{ $manualSetupKey }}"
                                    class="w-full p-3 bg-transparent outline-none text-stone-900 dark:text-stone-100"
                                />

                                <button
                                    @click="copy()"
                                    class="px-3 transition-colors border-l cursor-pointer border-stone-200 dark:border-stone-600 hover:bg-stone-100 dark:hover:bg-stone-700"
                                    title="{{ __('Copiar al portapapeles') }}"
                                >
                                    <flux:icon.document-duplicate x-show="!copied" variant="outline"></flux:icon>
                                    <flux:icon.check
                                        x-show="copied"
                                        variant="solid"
                                        class="text-green-500"
                                    ></flux:icon>
                                </button>
                            @endempty
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </flux:modal>

    {{-- SCRIPTS PARA SWEETALERT --}}
    @script
    <script>
        // Confirmación para Desactivar
        window.confirmDisable2FA = () => {
            Swal.fire({
                title: '{{ __("¿Desactivar 2FA?") }}',
                text: '{{ __("Tu cuenta será menos segura si desactivas la autenticación de dos factores.") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Rojo para acción de riesgo
                cancelButtonColor: '#000000', // Negro para cancelar
                confirmButtonText: '{{ __("Sí, desactivar") }}',
                cancelButtonText: '{{ __("Cancelar") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.disable();
                }
            });
        };

        // Escuchar éxito al desactivar
        Livewire.on('2fa-disabled', () => {
            Swal.fire({
                icon: 'success',
                title: '{{ __("Desactivado") }}',
                text: '{{ __("La autenticación de dos factores ha sido desactivada.") }}',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#000000',
                timer: 2000
            });
        });

        // Escuchar éxito al confirmar/activar
        Livewire.on('2fa-confirmed', () => {
            Swal.fire({
                icon: 'success',
                title: '{{ __("¡Habilitado!") }}',
                text: '{{ __("La autenticación de dos factores se ha configurado correctamente.") }}',
                confirmButtonText: 'Excelente',
                confirmButtonColor: '#16a34a' // Verde
            });
        });

        // Escuchar errores de validación del código
        Livewire.on('2fa-error', ({ message }) => {
            Swal.fire({
                icon: 'error',
                title: '{{ __("Error") }}',
                text: message,
                confirmButtonText: 'Reintentar',
                confirmButtonColor: '#000000'
            });
        });
    </script>
    @endscript
</section>