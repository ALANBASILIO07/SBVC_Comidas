{{-- Componente para renderizar notificaciones de sesión con SweetAlert2 --}}
{{-- Documentación: https://sweetalert2.github.io/ --}}

@if (session('success'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: "success",
                title: "¡Éxito!",
                text: "{{ session('success') }}",
                showConfirmButton: true,
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#42A958",
                draggable: true
            });
        });
    </script>
@endif

@if (session('error'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: "error",
                title: "¡Error!",
                text: "{{ session('error') }}",
                showConfirmButton: true,
                confirmButtonText: "Entendido",
                confirmButtonColor: "#ef4444",
                draggable: true
            });
        });
    </script>
@endif

@if (session('warning'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: "warning",
                title: "Advertencia",
                text: "{{ session('warning') }}",
                showConfirmButton: true,
                confirmButtonText: "Entendido",
                confirmButtonColor: "#f59e0b",
                draggable: true
            });
        });
    </script>
@endif

@if (session('info'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: "info",
                title: "Información",
                text: "{{ session('info') }}",
                showConfirmButton: true,
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#3b82f6",
                draggable: true
            });
        });
    </script>
@endif

{{-- Notificación personalizada con título y mensaje --}}
@if (session('notification'))
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @php
                $notification = session('notification');
            @endphp

            const notificationType = '{{ $notification['type'] ?? 'info' }}';
            const buttonColor = notificationType === 'success' ? '#42A958' :
                               notificationType === 'error' ? '#ef4444' :
                               notificationType === 'warning' ? '#f59e0b' : '#3b82f6';

            Swal.fire({
                title: '{{ $notification['title'] ?? 'Notificación' }}',
                text: '{{ $notification['message'] ?? '' }}',
                icon: notificationType,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: buttonColor,
                draggable: true
            });
        });
    </script>
@endif

{{-- Errores de validación --}}
@if ($errors->any())
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const errors = @json($errors->all());
            const errorList = errors.map(error => `<li style="text-align: left; margin: 5px 0;">${error}</li>`).join('');

            Swal.fire({
                icon: "error",
                title: "Errores de validación",
                html: `<div style="text-align: left;"><p><strong>Por favor corrige los siguientes errores:</strong></p><ul style="margin: 10px 0;">${errorList}</ul></div>`,
                confirmButtonText: "Entendido",
                confirmButtonColor: "#ef4444",
                draggable: true
            });
        });
    </script>
@endif
