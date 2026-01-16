{{--
* Nombre del archivo           : verify-email.blade.php
* Ruta                         : resources/views/auth/verify-email.blade.php
* Descripción de la vista      : Pantalla de espera que solicita al usuario verificar su correo electrónico antes de acceder al sistema.
* Fecha de creación            : 06/01/2026
* Elaboró                      : Alan Osvaldo Basilio Delgado
* Fecha de liberación          : 06/01/2026
* Autorizó                     : Maileth Patiño Ensastegui
* Versión                      : 1.1
* Fecha de mantenimiento       : 15/01/2026
* Tipo de mantenimiento        : Adaptación de Interfaz / UX
* Descripción del mantenimiento: Implementación de notificaciones con SweetAlert2 (estilo botón negro) y estandarización de textos.
* Responsable                  : Alan Osvaldo Basilio Delgado
* Revisor                      : Maileth Patiño Ensastegui
--}}

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Verifica tu correo electrónico')"
            :description="__('Gracias por registrarte. Antes de comenzar, ¿podrías verificar tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar? Si no recibiste el correo, con gusto te enviaremos otro.')"
        />

        <div class="flex flex-col items-center justify-between space-y-3">
            {{-- Formulario para reenviar email --}}
            <form method="POST" action="{{ route('verification.send') }}" class="w-full" id="resend-verification-form">
                @csrf
                <flux:button icon="envelope" icon-variant="outline" type="submit" variant="primary" class="w-full">
                    {{ __('Reenviar correo de verificación') }}
                </flux:button>
            </form>

            {{-- Formulario de Cerrar Sesión --}}
            <form method="POST" action="{{ route('logout') }}" class="w-full text-center">
                @csrf
                <flux:button variant="ghost" type="submit" class="text-sm cursor-pointer w-full" data-test="logout-button">
                    {{ __('Cerrar sesión') }}
                </flux:button>
            </form>
        </div>
    </div>

    {{-- SCRIPTS PARA SWEETALERT --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // 1. Manejo de Éxito (Cuando se reenvía el correo)
                @if (session('status') == 'verification-link-sent')
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("¡Correo enviado!") }}',
                        text: '{{ __("Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionaste durante el registro.") }}',
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#000000', // Estilo estandarizado negro
                        allowOutsideClick: false
                    });
                @endif

                // 2. UX: Feedback de carga al enviar
                const form = document.getElementById('resend-verification-form');
                form.addEventListener('submit', function() {
                    const btn = this.querySelector('button[type="submit"]');
                    if(btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<span>Enviando...</span>';
                    }
                });
            });
        </script>
    @endpush
</x-layouts.auth>