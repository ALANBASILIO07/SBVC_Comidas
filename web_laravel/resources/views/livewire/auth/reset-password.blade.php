{{--
* Nombre del archivo           : reset-password.blade.php
* Ruta                         : resources/views/auth/reset-password.blade.php
* Descripción de la vista      : Formulario final para establecer una nueva contraseña mediante el token de recuperación.
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
            :title="__('Restablecer contraseña')"
            :description="__('Por favor ingresa tu nueva contraseña a continuación.')"
        />

        {{-- El estado de sesión se maneja vía SweetAlert --}}
        @if(session('status'))
            <div class="hidden" id="status-message" data-msg="{{ session('status') }}"></div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-6" id="reset-password-form">
            @csrf

            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                type="email"
                required
                autocomplete="email"
                placeholder="ejemplo@correo.com"
                :value="old('email', $request->email)"
                readonly 
                class="bg-gray-100 cursor-not-allowed"
            />
            {{-- Nota: Se sugiere readonly para evitar cambiar el email asociado al token, aunque Laravel lo valida --}}

            <flux:input
                name="password"
                :label="__('Nueva contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Mínimo 8 caracteres')"
                viewable
                autofocus
            />

            <flux:input
                name="password_confirmation"
                :label="__('Confirmar contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Repite la contraseña')"
                viewable
            />

            <div class="flex items-center justify-end">
                <flux:button icon="key" icon-variant="outline" type="submit" variant="primary" class="w-full" data-test="reset-password-button">
                    {{ __('Restablecer contraseña') }}
                </flux:button>
            </div>
        </form>
    </div>

    {{-- SCRIPTS PARA SWEETALERT --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // 1. Manejo de Errores de Validación (Ej. Contraseñas no coinciden, token inválido)
                @if ($errors->any())
                    let errorList = '<ul style="text-align: left; margin-left: 1rem;">';
                    @foreach ($errors->all() as $error)
                        errorList += '<li>{{ $error }}</li>';
                    @endforeach
                    errorList += '</ul>';

                    Swal.fire({
                        icon: 'error',
                        title: '{{ __("Error al restablecer") }}',
                        html: errorList,
                        confirmButtonText: 'Entendido',
                        confirmButtonColor: '#000000', // Estilo estandarizado negro
                        allowOutsideClick: false
                    });
                @endif

                // 2. Manejo de Éxito (Si Laravel redirecciona con 'status')
                const statusDiv = document.getElementById('status-message');
                if (statusDiv) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __("¡Contraseña actualizada!") }}',
                        text: statusDiv.getAttribute('data-msg'),
                        confirmButtonText: 'Iniciar Sesión',
                        confirmButtonColor: '#000000',
                        allowOutsideClick: false
                    });
                }

                // 3. UX: Feedback de carga al enviar
                const form = document.getElementById('reset-password-form');
                form.addEventListener('submit', function() {
                    const btn = this.querySelector('button[type="submit"]');
                    if(btn) {
                        btn.disabled = true;
                        // Cambiamos el texto temporalmente
                        btn.innerHTML = '<span>Actualizando...</span>';
                    }
                });
            });
        </script>
    @endpush
</x-layouts.auth>