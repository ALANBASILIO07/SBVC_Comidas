{{--
    Nombre del archivo        : login.blade.php
    Descripción               : Vista para inicio de sesión con validación y mensajes en español usando SweetAlert.
    Fecha de creación         : 06/01/2026
    Elaboró                   : Alan Osvaldo Basilio Delgado
    Fecha de liberación       : 06/01/2026
    Autorizó                  : Maileth Patiño Ensastegui
    Versión                   : 1.5
    Fecha de mantenimiento    : 12/01/2026
    Folio de mantenimiento    :
    Tipo de mantenimiento     : UX / Estética de Alertas
    Descripción del mantenimiento: Centrado de texto en SweetAlert y resaltado de inputs con error.
    Responsable               : Alan Osvaldo Basilio Delgado
    Revisor                   : Maileth Patiño Ensastegui
--}}

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Ingresar a tu cuenta')"
            :description="__('Ingresa tu correo electrónico y contraseña a continuación para iniciar sesión')"
        />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        {{-- 
            Nota: Se eliminó el bloque de visualización de errores en HTML 
            para cumplir con el estándar de usar únicamente SweetAlert.
        --}}

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-6" id="login-form">
            @csrf

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@gmail.com"
                id="email"
                {{-- Aplicamos clase de error dinámicamente si existe el error --}}
                class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
            />

            <!-- Password -->
            <div class="relative">
                <flux:input
                    name="password"
                    :label="__('Contraseña')"
                    type="password"
                    required
                    autocomplete="current-password"
                    :placeholder="__('Contraseña')"
                    viewable
                    id="password"
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-sm end-0 text-custom-orange hover:text-custom-orange/80" :href="route('password.request')" wire:navigate>
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Recordarme')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button
                    icon="arrow-right-start-on-rectangle"
                    icon-variant="outline"
                    variant="primary"
                    type="submit"
                    class="w-full"
                    data-test="login-button">
                    {{ __('Iniciar sesión') }}
                </flux:button>
            </div>
        </form>

        @if (Route::has('register'))
            <div class="space-x-1 text-sm text-center rtl:space-x-reverse text-black/60 dark:text-white/60">
                <span>{{ __('¿No tienes una cuenta?') }}</span>
                <flux:link :href="route('register')" wire:navigate class="text-custom-orange hover:text-custom-orange/80">
                    {{ __('Regístrate') }}
                </flux:link>
            </div>
        @endif
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                const errors = @json($errors->all());
                
                Swal.fire({
                    icon: 'error',
                    title: '{{ __("Errores de validación") }}',
                    html: `
                        <div style="text-align: center;">
                            <p>{{ __("Por favor corrige los siguientes errores:") }}</p>
                            <div style="margin-top: 10px; font-weight: 500;">
                                ${errors.join('<br>')}
                            </div>
                        </div>
                    `,
                    confirmButtonText: '{{ __("Entendido") }}',
                    confirmButtonColor: '#F7941D', {{-- Color corporativo naranja --}}
                    draggable: true
                });
            @endif
        });
    </script>
    @endpush

    <style>
        /* Estilos para resaltar campos con error */
        .is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 1px #ef4444 !important;
        }
    </style>
</x-layouts.auth>