{{--
* Nombre del archivo           : confirm-password.blade.php
* Ruta                         : resources/views/auth/confirm-password.blade.php
* Descripción de la vista      : Vista de seguridad para confirmar la contraseña del usuario antes de acceder a zonas protegidas.
* Fecha de creación            : 06/01/2026
* Elaboró                      : Alan Osvaldo Basilio Delgado
* Fecha de liberación          : 06/01/2026
* Autorizó                     : Maileth Patiño Ensastegui
* Versión                      : 1.1
* Fecha de mantenimiento       : 15/01/2026
* Tipo de mantenimiento        : Adaptación de Interfaz / UX
* Descripción del mantenimiento: Implementación de notificaciones con SweetAlert2 y estandarización de textos en español.
* Responsable                  : Alan Osvaldo Basilio Delgado
* Revisor                      : Maileth Patiño Ensastegui
--}}

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header
            :title="__('Confirmar contraseña')"
            :description="__('Esta es un área segura de la aplicación. Por favor confirma tu contraseña antes de continuar.')"
        />

        {{-- El estado de sesión se manejará vía SweetAlert, pero mantenemos el componente oculto si se requiere lógica legacy --}}
        @if(session('status'))
            <div class="hidden" id="session-status" data-message="{{ session('status') }}"></div>
        @endif

        <form method="POST" action="{{ route('password.confirm.store') }}" class="flex flex-col gap-6" id="confirm-password-form">
            @csrf

            <flux:input
                name="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="current-password"
                :placeholder="__('Ingresa tu contraseña')"
                viewable
            />

            <flux:button icon="lock-closed" icon-variant="outline" variant="primary" type="submit" class="w-full" data-test="confirm-password-button">
                {{ __('Confirmar') }}
            </flux:button>
        </form>
    </div>

    {{-- SCRIPTS PARA SWEETALERT --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // 1. Manejo de Errores de Validación (Ej. Contraseña incorrecta)
                @if ($errors->any())
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("Error de validación") }}',
                        text: '{{ $errors->first() }}', // Muestra el primer error (usualmente "La contraseña es incorrecta")
                        confirmButtonText: 'intentar de nuevo',
                        confirmButtonColor: '#000000', // Estilo estandarizado negro
                        allowOutsideClick: false
                    });
                @endif

                // 2. Manejo de Estado de Sesión (Mensajes de éxito o info)
                const statusDiv = document.getElementById('session-status');
                if (statusDiv) {
                    Swal.fire({
                        icon: 'info',
                        title: '{{ __("Aviso") }}',
                        text: statusDiv.getAttribute('data-message'),
                        confirmButtonText: 'Aceptar',
                        confirmButtonColor: '#000000'
                    });
                }

                // 3. Interceptación del envío para UX (Opcional: Spinner de carga)
                const form = document.getElementById('confirm-password-form');
                form.addEventListener('submit', function() {
                    // Pequeña espera visual para evitar doble envío
                    const btn = this.querySelector('button[type="submit"]');
                    if(btn) {
                        btn.disabled = true;
                        btn.innerHTML = '<span class="loading-spinner"></span> Verificando...'; // Ajustar según tu css de spinner
                    }
                });
            });
        </script>
    @endpush
</x-layouts.auth>