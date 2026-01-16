{{--
* Nombre del archivo           : forgot-password.blade.php
* Ruta                         : resources/views/auth/forgot-password.blade.php
* Descripción de la vista      : Vista para solicitar el enlace de restablecimiento de contraseña vía correo electrónico.
* Fecha de creación            : 06/01/2026
* Elaboró                      : Alan Osvaldo Basilio Delgado
* Fecha de liberación          : 06/01/2026
* Autorizó                     : Maileth Patiño Ensastegui
* Versión                      : 1.1
* Fecha de mantenimiento       : 15/01/2026
* Tipo de mantenimiento        : Adaptación de Interfaz / UX
* Descripción del mantenimiento: Implementación de notificaciones con SweetAlert2 (estilo botón negro) y validaciones en español.
* Responsable                  : Alan Osvaldo Basilio Delgado
* Revisor                      : Maileth Patiño Ensastegui
--}}

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('¿Olvidaste tu contraseña?')"
            :description="__('No te preocupes. Ingresa tu correo electrónico y te enviaremos un enlace para restablecerla.')"
        />

        {{-- El estado de sesión se maneja vía SweetAlert, ocultamos el componente visual por defecto --}}
        @if(session('status'))
            <div class="hidden" id="status-message" data-msg="{{ session('status') }}"></div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-6" id="forgot-password-form">
            @csrf

            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                type="email"
                required
                autofocus
                placeholder="ejemplo@correo.com"
                :value="old('email')"
            />

            <flux:button icon="paper-airplane" icon-variant="outline" variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
                {{ __('Enviar enlace de restablecimiento') }}
            </flux:button>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-black/60 dark:text-white/60">
            <span>{{ __('O, regresar a') }}</span>
            <flux:link :href="route('login')" wire:navigate class="text-custom-orange hover:text-custom-orange/80">
                {{ __('iniciar sesión') }}
            </flux:link>
        </div>
    </div>

    {{-- SCRIPTS PARA SWEETALERT --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // 1. Manejo de Errores de Validación (Ej. Correo no encontrado)
                @if ($errors->any())
                    let errorList = '<ul style="text-align: left; margin-left: 1rem;">';
                    @foreach ($errors->all() as $error)
                        errorList += '<li>{{ $error }}</li>';
                    @endforeach
                    errorList += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("Error") }}',
                        html: errorList,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#000000', // Estilo estandarizado negro
                        allowOutsideClick: false
                    });
                @endif

                // 2. Manejo de Éxito (Enlace enviado)
                // Laravel devuelve un 'status' en sesión cuando envía el correo exitosamente
                @if (session('status'))
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("¡Enviado!") }}',
                        text: '{{ session("status") }}', // El mensaje suele ser: "Le hemos enviado por correo electrónico..."
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#000000',
                        allowOutsideClick: false
                    });
                @endif

                // 3. UX: Feedback de carga al enviar
                const form = document.getElementById('forgot-password-form');
                form.addEventListener('submit', function() {
                    const btn = this.querySelector('button[type="submit"]');
                    if(btn) {
                        btn.disabled = true;
                        // Cambiamos el texto temporalmente
                        const originalText = btn.innerText;
                        btn.innerHTML = '<span>Enviando...</span>';
                    }
                });
            });
        </script>
    @endpush
</x-layouts.auth>