<?php
/*
 * Nombre del archivo           : recovery-codes.blade.php
 * Descripción de la vista      : Componente Livewire/Volt para visualizar y regenerar códigos de recuperación 2FA.
 * Fecha de creación            : 06/01/2026
 * Elaboró                      : Alan Osvaldo Basilio Delgado
 * Fecha de liberación          : 06/01/2026
 * Autorizó                     : Maileth Patiño Ensastegui
 * Versión                      : 1.1
 * Fecha de mantenimiento       : 15/01/2026
 * Tipo de mantenimiento        : Adaptación de Interfaz / UX
 * Descripción del mantenimiento: Implementación de SweetAlert2 para confirmación de regeneración y traducción al español.
 * Responsable                  : Alan Osvaldo Basilio Delgado
 * Revisor                      : Maileth Patiño Ensastegui
 */

use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;

new class extends Component {
    #[Locked]
    public array $recoveryCodes = [];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->loadRecoveryCodes();
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generateNewRecoveryCodes): void
    {
        $generateNewRecoveryCodes(auth()->user());

        $this->loadRecoveryCodes();

        // Despachar evento para mostrar SweetAlert de éxito
        $this->dispatch('recovery-codes-regenerated');
    }

    /**
     * Load the recovery codes for the user.
     */
    private function loadRecoveryCodes(): void
    {
        $user = auth()->user();

        if ($user->hasEnabledTwoFactorAuthentication() && $user->two_factor_recovery_codes) {
            try {
                $this->recoveryCodes = json_decode(decrypt($user->two_factor_recovery_codes), true);
            } catch (Exception) {
                $this->addError('recoveryCodes', 'Error al cargar los códigos de recuperación.');
                $this->recoveryCodes = [];
            }
        }
    }
}; ?>

<div
    class="py-6 space-y-6 border shadow-sm rounded-xl border-zinc-200 dark:border-white/10"
    wire:cloak
    x-data="{ showRecoveryCodes: false }"
>
    {{-- Encabezado --}}
    <div class="px-6 space-y-2">
        <div class="flex items-center gap-2">
            <flux:icon.lock-closed variant="outline" class="size-4"/>
            <flux:heading size="lg" level="3">{{ __('Códigos de recuperación 2FA') }}</flux:heading>
        </div>
        <flux:text variant="subtle">
            {{ __('Los códigos de recuperación te permiten recuperar el acceso a tu cuenta si pierdes tu dispositivo de autenticación. Guárdalos en un gestor de contraseñas seguro.') }}
        </flux:text>
    </div>

    {{-- Acciones --}}
    <div class="px-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            
            {{-- Botón Mostrar --}}
            <flux:button
                x-show="!showRecoveryCodes"
                icon="eye"
                icon:variant="outline"
                variant="primary"
                @click="showRecoveryCodes = true;"
                aria-expanded="false"
                aria-controls="recovery-codes-section"
            >
                {{ __('Ver códigos') }}
            </flux:button>

            {{-- Botón Ocultar --}}
            <flux:button
                x-show="showRecoveryCodes"
                icon="eye-slash"
                icon:variant="outline"
                variant="primary"
                @click="showRecoveryCodes = false"
                aria-expanded="true"
                aria-controls="recovery-codes-section"
            >
                {{ __('Ocultar códigos') }}
            </flux:button>

            {{-- Botón Regenerar (Con confirmación JS) --}}
            @if (filled($recoveryCodes))
                <flux:button
                    x-show="showRecoveryCodes"
                    icon="arrow-path"
                    variant="filled"
                    onclick="confirmRegeneration()" 
                >
                    {{ __('Regenerar códigos') }}
                </flux:button>
            @endif
        </div>

        {{-- Lista de Códigos --}}
        <div
            x-show="showRecoveryCodes"
            x-transition
            id="recovery-codes-section"
            class="relative overflow-hidden"
            x-bind:aria-hidden="!showRecoveryCodes"
        >
            <div class="mt-3 space-y-3">
                {{-- Manejo de errores de carga --}}
                @error('recoveryCodes')
                    <flux:callout variant="danger" icon="x-circle" heading="{{ $message }}"/>
                @enderror

                @if (filled($recoveryCodes))
                    <div
                        class="grid gap-1 p-4 font-mono text-sm rounded-lg bg-zinc-100 dark:bg-white/5"
                        role="list"
                        aria-label="Códigos de recuperación"
                    >
                        @foreach($recoveryCodes as $code)
                            <div
                                role="listitem"
                                class="select-text"
                                wire:loading.class="opacity-50 animate-pulse"
                            >
                                {{ $code }}
                            </div>
                        @endforeach
                    </div>
                    <flux:text variant="subtle" class="text-xs">
                        {{ __('Cada código de recuperación puede utilizarse una sola vez. Si necesitas más, haz clic en "Regenerar códigos".') }}
                    </flux:text>
                @endif
            </div>
        </div>
    </div>

    {{-- SCRIPTS PARA SWEETALERT --}}
    @script
    <script>
        // Función global para confirmar la regeneración
        window.confirmRegeneration = () => {
            Swal.fire({
                title: '{{ __("¿Estás seguro?") }}',
                text: '{{ __("Los códigos de recuperación anteriores dejarán de funcionar inmediatamente. Asegúrate de guardar los nuevos.") }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000000', // Estilo negro
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ __("Sí, regenerar") }}',
                cancelButtonText: '{{ __("Cancelar") }}',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Llamar al método Livewire
                    $wire.regenerateRecoveryCodes();
                }
            });
        };

        // Escuchar evento de éxito tras regenerar
        Livewire.on('recovery-codes-regenerated', () => {
            Swal.fire({
                icon: 'success',
                title: '{{ __("¡Regenerados!") }}',
                text: '{{ __("Se han generado nuevos códigos de recuperación exitosamente.") }}',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#000000',
                timer: 3000
            });
        });
    </script>
    @endscript
</div>