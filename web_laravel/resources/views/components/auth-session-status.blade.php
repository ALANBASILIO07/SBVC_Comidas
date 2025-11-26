@props([
    'status',
])

@if ($status)
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: "¡Éxito!",
                text: "{{ $status }}",
                icon: "success",
                confirmButtonText: "Aceptar",
                confirmButtonColor: "#42A958",
                draggable: true
            });
        });
    </script>
@endif
