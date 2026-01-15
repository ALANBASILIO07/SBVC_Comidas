<?php
/**
 * Vista de debug para confirmar sesión swal en creación de establecimiento
 */
?>

<x-layouts.app :title="__('Debug Creación Establecimiento')">
    <div class="max-w-4xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Debug: Sesión swal en creación de establecimiento</h1>

        <div class="mb-4">
            <h2 class="font-semibold">Contenido de la sesión <code>swal</code>:</h2>
            <pre id="swal-session-content" class="bg-gray-100 dark:bg-zinc-800 p-4 rounded text-sm overflow-auto" style="max-height: 300px;"></pre>
        </div>

        <div class="mb-4">
            <button id="btn-show-swal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Mostrar alerta Swal</button>
        </div>

        <div class="mb-4">
            <button id="btn-clear-session" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Limpiar sesión swal (simular sin alerta)</button>
        </div>

        <p class="text-sm text-gray-600 dark:text-gray-400">Abre la consola del navegador para ver logs adicionales.</p>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Si el controlador pasó $swal como variable de vista, estará disponible aquí.
            // Si no, intentamos leer la sesión con session('swal').
            const swalSession = @json($swal ?? session('swal') ?? null);
            const pre = document.getElementById('swal-session-content');
            const btnShow = document.getElementById('btn-show-swal');
            const btnClear = document.getElementById('btn-clear-session');

            console.log('Sesión swal recibida:', swalSession);

            if (pre) {
                pre.textContent = JSON.stringify(swalSession, null, 2) || 'No hay sesión swal';
            }

            btnShow.addEventListener('click', () => {
                if (swalSession) {
                    try {
                        Swal.fire(swalSession);
                    } catch (err) {
                        console.error('Error mostrando Swal:', err);
                        alert('Error mostrando Swal. Revisa la consola.');
                    }
                } else {
                    alert('No hay datos de sesión swal para mostrar.');
                }
            });

            btnClear.addEventListener('click', () => {
                pre.textContent = 'Sesión swal limpiada (simulada)';
                alert('Simulación: sesión swal limpiada. No se mostrará alerta.');
            });
        });
    </script>
    @endpush
</x-layouts.app>