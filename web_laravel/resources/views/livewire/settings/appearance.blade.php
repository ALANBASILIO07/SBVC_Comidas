<?php
/*
 * Nombre del archivo           : appearance.blade.php
 * Descripción de la vista      : Componente Livewire/Volt para gestionar el tema (Claro/Oscuro/Sistema) de la aplicación.
 * Fecha de creación            : 06/01/2026
 * Elaboró                      : Alan Osvaldo Basilio Delgado
 * Fecha de liberación          : 06/01/2026
 * Autorizó                     : Maileth Patiño Ensastegui
 * Versión                      : 1.1
 * Fecha de mantenimiento       : 15/01/2026
 * Tipo de mantenimiento        : Adaptación de Interfaz / Traducción
 * Descripción del mantenimiento: Estandarización de textos al español y estructura base para notificaciones.
 * Responsable                  : Alan Osvaldo Basilio Delgado
 * Revisor                      : Maileth Patiño Ensastegui
 */

use Livewire\Volt\Component;

new class extends Component {
    // Por el momento la lógica es puramente AlpineJS ($flux.appearance).
    // Si decides guardar la preferencia en BD, aquí iría el método save().
}; ?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Apariencia')" :subheading="__('Actualiza la configuración de apariencia de tu cuenta')">
        <flux:radio.group x-data variant="segmented" x-model="$flux.appearance">
            <flux:radio value="light" icon="sun">{{ __('Claro') }}</flux:radio>
            <flux:radio value="dark" icon="moon">{{ __('Oscuro') }}</flux:radio>
            <flux:radio value="system" icon="computer-desktop">{{ __('Sistema') }}</flux:radio>
        </flux:radio.group>
    </x-settings.layout>

    {{-- SCRIPTS PARA SWEETALERT (Preparado para consistencia) --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        @if(session('swal'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const config = @json(session('swal'));
                    config.confirmButtonColor = '#000000'; // Estilo negro estandarizado
                    Swal.fire(config);
                });
            </script>
        @endif
    @endpush
</section>