{{--
* Nombre del archivo           : update-password-form.blade.php
* Ruta                         : resources/views/livewire/settings/update-password-form.blade.php
* Descripción de la vista      : Componente Livewire/Volt para la actualización de la contraseña del usuario.
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

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Volt\Component;

new class extends Component {
    public string $current_password = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Update the password for the currently authenticated user.
     */
    public function updatePassword(): void
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::defaults(), 'confirmed'],
            ], [
                'current_password.required' => __('La contraseña actual es obligatoria.'),
                'current_password.current_password' => __('La contraseña actual es incorrecta.'),
                'password.required' => __('La nueva contraseña es obligatoria.'),
                'password.confirmed' => __('La confirmación de la contraseña no coincide.'),
                'password.min' => __('La contraseña debe tener al menos :min caracteres.'),
            ]);

        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');
            
            // Despachar evento para alerta de error visual
            $this->dispatch('password-update-error', message: __('Hubo un error al actualizar la contraseña. Verificá los campos.'));
            
            throw $e;
        }

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        $this->dispatch('password-updated');
    }
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Actualizar contraseña')" :subheading="__('Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantenerse segura.')">
        <form method="POST" wire:submit="updatePassword" class="mt-6 space-y-6">
            
            {{-- Contraseña Actual --}}
            <flux:input
                wire:model="current_password"
                :label="__('Contraseña actual')"
                type="password"
                required
                autocomplete="current-password"
                viewable
            />

            {{-- Nueva Contraseña --}}
            <flux:input
                wire:model="password"
                :label="__('Nueva contraseña')"
                type="password"
                required
                autocomplete="new-password"
                viewable
            />

            {{-- Confirmar Contraseña --}}
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirmar contraseña')"
                type="password"
                required
                autocomplete="new-password"
                viewable
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end w-full">
                    <flux:button variant="primary" type="submit" class="w-full sm:w-auto" data-test="update-password-button">
                        {{ __('Guardar') }}
                    </flux:button>
                </div>
            </div>
        </form>
    </x-settings.layout>

    {{-- SCRIPTS PARA SWEETALERT --}}
    @script
    <script>
        // Alerta de Éxito
        Livewire.on('password-updated', () => {
            Swal.fire({
                icon: 'success',
                title: '{{ __("¡Contraseña actualizada!") }}',
                text: '{{ __("Tu contraseña ha sido modificada exitosamente.") }}',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#16a34a', // Verde
                timer: 3000
            });
        });

        // Alerta de Error
        Livewire.on('password-update-error', ({ message }) => {
            Swal.fire({
                icon: 'error',
                title: '{{ __("Error") }}',
                text: message,
                confirmButtonText: 'Reintentar',
                confirmButtonColor: '#000000', // Negro
            });
        });
    </script>
    @endscript
</section>