{{--
* Nombre del archivo           : two-factor-challenge.blade.php
* Ruta                         : resources/views/auth/two-factor-challenge.blade.php
* Descripción de la vista      : Pantalla de seguridad para validar el segundo factor de autenticación (2FA).
* Permite alternar entre código TOTP (App) y código de recuperación.
* Fecha de creación            : 06/01/2026
* Elaboró                      : Alan Osvaldo Basilio Delgado
* Fecha de liberación          : 06/01/2026
* Autorizó                     : Maileth Patiño Ensastegui
* Versión                      : 1.1
* Fecha de mantenimiento       : 15/01/2026
* Tipo de mantenimiento        : Adaptación de Interfaz / UX
* Descripción del mantenimiento: Implementación de notificaciones con SweetAlert2 y traducción completa al español.
* Responsable                  : Alan Osvaldo Basilio Delgado
* Revisor                      : Maileth Patiño Ensastegui
--}}

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        
        {{-- Contenedor Alpine para alternar entre Código App y Código Recuperación --}}
        <div
            class="relative w-full h-auto"
            x-cloak
            x-data="{
                showRecoveryInput: @js($errors->has('recovery_code')),
                code: '',
                recovery_code: '',
                toggleInput() {
                    this.showRecoveryInput = !this.showRecoveryInput;
                    this.code = '';
                    this.recovery_code = '';
                    $dispatch('clear-2fa-auth-code');
            
                    $nextTick(() => {
                        this.showRecoveryInput
                            ? this.$refs.recovery_code?.focus()
                            : $dispatch('focus-2fa-auth-code');
                    });
                },
            }"
        >
            {{-- Encabezado: Código de App --}}
            <div x-show="!showRecoveryInput">
                <x-auth-header
                    :title="__('Código de autenticación')"
                    :description="__('Por favor confirma el acceso a tu cuenta ingresando el código proporcionado por tu aplicación de autenticación (Google Authenticator, Authy, etc.).')"
                />
            </div>

            {{-- Encabezado: Código de Recuperación --}}
            <div x-show="showRecoveryInput">
                <x-auth-header
                    :title="__('Código de recuperación')"
                    :description="__('Por favor confirma el acceso a tu cuenta ingresando uno de tus códigos de recuperación de emergencia.')"
                />
            </div>

            <form method="POST" action="{{ route('two-factor.login.store') }}" id="two-factor-form">
                @csrf

                <div class="space-y-5 text-center">
                    
                    {{-- Input: Código App (OTP) --}}
                    <div x-show="!showRecoveryInput">
                        <div class="flex items-center justify-center my-5">
                            <x-input-otp
                                name="code"
                                digits="6"
                                autocomplete="one-time-code"
                                x-model="code"
                            />
                        </div>
                    </div>

                    {{-- Input: Código Recuperación (Texto simple) --}}
                    <div x-show="showRecoveryInput">
                        <div class="my-5">
                            <flux:input
                                type="text"
                                name="recovery_code"
                                label="{{ __('Código de recuperación') }}"
                                placeholder="XXXXXXXX-XXXXXXXX"
                                x-ref="recovery_code"
                                x-bind:required="showRecoveryInput"
                                autocomplete="one-time-code"
                                x-model="recovery_code"
                            />
                        </div>
                    </div>

                    <flux:button
                        icon="lock-closed"
                        icon-variant="outline"
                        variant="primary"
                        type="submit"
                        class="w-full"
                    >
                        {{ __('Verificar') }}
                    </flux:button>
                </div>

                {{-- Enlace para alternar método --}}
                <div class="mt-5 space-x-0.5 text-sm leading-5 text-center text-zinc-600 dark:text-zinc-400">
                    <span class="opacity-70">{{ __('¿Problemas?') }}</span>
                    <div class="inline font-medium underline cursor-pointer text-custom-orange hover:text-custom-orange/80 transition-colors">
                        <span x-show="!showRecoveryInput" @click="toggleInput()">
                            {{ __('Usar un código de recuperación') }}
                        </span>
                        <span x-show="showRecoveryInput" @click="toggleInput()">
                            {{ __('Usar un código de autenticación') }}
                        </span>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS PARA SWEETALERT --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // 1. Manejo de Errores (Código incorrecto o inválido)
                @if ($errors->any())
                    let errorMsg = '';
                    @if($errors->has('code'))
                        errorMsg = '{{ $errors->first("code") }}';
                    @elseif($errors->has('recovery_code'))
                        errorMsg = '{{ $errors->first("recovery_code") }}';
                    @else
                        errorMsg = '{{ $errors->first() }}';
                    @endif

                    // Si el mensaje es genérico en inglés, lo traducimos visualmente
                    if(errorMsg.includes('The provided two factor authentication code was invalid')) {
                        errorMsg = 'El código de autenticación proporcionado no es válido.';
                    } else if (errorMsg.includes('The provided recovery code was invalid')) {
                        errorMsg = 'El código de recuperación proporcionado no es válido.';
                    }

                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("Acceso denegado") }}',
                        text: errorMsg,
                        confirmButtonText: 'Intentar de nuevo',
                        confirmButtonColor: '#000000', // Estilo estandarizado negro
                        allowOutsideClick: false
                    });
                @endif

                // 2. UX: Bloquear botón al enviar
                const form = document.getElementById('two-factor-form');
                form.addEventListener('submit', function() {
                    const btn = this.querySelector('button[type="submit"]');
                    if(btn) {
                        btn.disabled = true;
                        btn.innerText = 'Verificando...';
                    }
                });
            });
        </script>
    @endpush
</x-layouts.auth>