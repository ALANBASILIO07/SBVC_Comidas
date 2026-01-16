{{--
* Nombre del archivo           : delete-user-form.blade.php
* Ruta                         : resources/views/livewire/profile/delete-user-form.blade.php
* Descripción de la vista      : Componente Livewire/Volt para la eliminación permanente de la cuenta de usuario.
* Incluye confirmación mediante contraseña y validación visual con SweetAlert.
* Fecha de creación            : 06/01/2026
* Elaboró                      : Alan Osvaldo Basilio Delgado
* Fecha de liberación          : 06/01/2026
* Autorizó                     : Maileth Patiño Ensastegui
* Versión                      : 1.1
* Fecha de mantenimiento       : 15/01/2026
* Tipo de mantenimiento        : Adaptación de Interfaz / UX / Traducción
* Descripción del mantenimiento: Implementación de textos en español y manejo de errores de validación con SweetAlert2.
* Responsable                  : Alan Osvaldo Basilio Delgado
* Revisor                      : Maileth Patiño Ensastegui
--}}

<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;
use Illuminate\Validation\ValidationException;

new class extends Component {
    public string $password = '';

    /**
     * Delete the currently authenticated user.
     */
    public function deleteUser(Logout $logout): void
    {
        try {
            $this->validate([
                'password' => ['required', 'string', 'current_password'],
            ], [
                'password.required' => __('La contraseña es obligatoria para confirmar.'),
                'password.current_password' => __('La contraseña ingresada es incorrecta.'),
            ]);

            tap(Auth::user(), $logout(...))->delete();

            $this->redirect('/', navigate: true);

        } catch (ValidationException $e) {
            // Despachar evento para mostrar el error en SweetAlert sin recargar
            $this->dispatch('delete-account-error', message: $e->validator->errors()->first('password'));
            throw $e;
        }
    }
}; ?>

<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('Eliminar cuenta') }}</flux:heading>
        <flux:subheading>{{ __('Elimina tu cuenta y todos sus recursos permanentemente.') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" data-test="delete-user-button">
            {{ __('Eliminar cuenta') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form method="POST" wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('¿Estás seguro de que deseas eliminar tu cuenta?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Una vez que se elimine tu cuenta, todos sus recursos y datos se eliminarán permanentemente. Por favor ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma permanente.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Contraseña')" type="password" placeholder="Ingresa tu contraseña" />

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit" data-test="confirm-delete-user-button">
                    {{ __('Eliminar cuenta') }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    {{-- SCRIPTS PARA SWEETALERT --}}
    @script
    <script>
        Livewire.on('delete-account-error', ({ message }) => {
            Swal.fire({
                icon: 'error',
                title: '{{ __("No se pudo eliminar") }}',
                text: message,
                confirmButtonText: 'Intentar de nuevo',
                confirmButtonColor: '#000000', // Estilo negro estandarizado
                target: document.getElementById('confirm-user-deletion') // Opcional: asegura que se vea sobre el modal si hay problemas de z-index
            });
        });
    </script>
    @endscript
</section>