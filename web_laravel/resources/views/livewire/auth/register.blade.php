{{--
    Nombre del archivo        : register.blade.php
    Descripción               : Vista para registro de usuario con validación y alertas SweetAlert en español.
    Fecha de creación         : 12/01/2026
    Elaboró                   : Alan Osvaldo Basilio Delgado
    Fecha de liberación       : 12/01/2026
    Autorizó                  : Maileth Patiño Ensastegui
    Versión                   : 1.1
    Fecha de mantenimiento    : 12/01/2026
    Folio de mantenimiento    :
    Tipo de mantenimiento     : UX / Validación y alertas SweetAlert
    Descripción del mantenimiento: Validación de campos con SweetAlert (centrado), bloqueo de caracteres no permitidos, marcado de inputs con errores y mensajes simbólicos inline.
    Responsable               : Alan Osvaldo Basilio Delgado
    Revisor                   : Maileth Patiño Ensastegui
--}}

<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Crea una cuenta')" :description="__('Ingresa tus datos para crear tu cuenta')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6" id="register-form" novalidate>
            @csrf

            <!-- Name -->
            <div>
                <flux:input
                    name="name"
                    id="name"
                    :label="__('Nombre')"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    :placeholder="__('Juan Pérez')"
                    class="{{ $errors->has('name') ? 'is-invalid' : '' }}"
                />
                <div id="error-name" class="hidden text-sm text-red-500 mt-1"></div>
            </div>

            <!-- Email Address -->
            <div>
                <flux:input
                    name="email"
                    id="email"
                    :label="__('Correo electrónico')"
                    type="email"
                    required
                    autocomplete="email"
                    placeholder="email@gmail.com"
                    class="{{ $errors->has('email') ? 'is-invalid' : '' }}"
                />
                <div id="error-email" class="hidden text-sm text-red-500 mt-1"></div>
            </div>

            <!-- Password -->
            <div>
                <flux:input
                    name="password"
                    id="password"
                    :label="__('Contraseña')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Contraseña')"
                    viewable
                    class="{{ $errors->has('password') ? 'is-invalid' : '' }}"
                />
                <div id="error-password" class="hidden text-sm text-red-500 mt-1"></div>
            </div>

            <!-- Confirm Password -->
            <div>
                <flux:input
                    name="password_confirmation"
                    id="password_confirmation"
                    :label="__('Confirmar contraseña')"
                    type="password"
                    required
                    autocomplete="new-password"
                    :placeholder="__('Confirmar contraseña')"
                    viewable
                    class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                />
                <div id="error-password_confirmation" class="hidden text-sm text-red-500 mt-1"></div>
            </div>

            <div class="space-y-4">
                <!-- Términos y Condiciones -->
                <div class="flex items-start gap-3">
                    <flux:checkbox name="terms" id="terms" class="mt-1 {{ $errors->has('terms') ? 'is-invalid' : '' }}" />
                    <label for="terms" class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! __('Acepto los') !!}
                        <a href="{{ route('terminos') }}" wire:navigate class="text-custom-orange hover:underline decoration-custom-orange decoration-2 font-semibold">
                            {{ __('Términos y Condiciones') }}
                        </a>
                    </label>
                </div>
                <div id="error-terms" class="hidden text-sm text-red-500 mt-1 ml-8"></div>

                <!-- Aviso de Privacidad -->
                <div class="flex items-start gap-3">
                    <flux:checkbox name="privacy" id="privacy" class="mt-1 {{ $errors->has('privacy') ? 'is-invalid' : '' }}" />
                    <label for="privacy" class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! __('Acepto el') !!}
                        <a href="{{ route('privacidad') }}" wire:navigate class="text-custom-orange hover:underline decoration-custom-orange decoration-2 font-semibold">
                            {{ __('Aviso de Privacidad') }}
                        </a>
                    </label>
                </div>
                <div id="error-privacy" class="hidden text-sm text-red-500 mt-1 ml-8"></div>
            </div>

            <div class="flex items-center justify-end">
                <flux:button icon="user-plus" icon-variant="outline" type="submit" variant="primary" class="w-full" data-test="register-user-button">
                    {{ __('Registrarse') }}
                </flux:button>
            </div>
        </form>

        <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
            <span>{{ __('¿Ya tienes una cuenta?') }}</span>
            <flux:link :href="route('login')" wire:navigate>{{ __('Iniciar sesión') }}</flux:link>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Traducciones / textos usando Blade para mantener consistencia con resources/lang
        const texts = {
            title: '{{ __("Errores de validación") }}',
            description: '{{ __("Por favor corrige los siguientes errores:") }}',
            ok: '{{ __("Entendido") }}',
            email_invalid: '{{ __("El correo electrónico no tiene un formato válido.") }}',
            email_required: '{{ __("El correo electrónico es obligatorio.") }}',
            name_required: '{{ __("El nombre es obligatorio.") }}',
            name_invalid: '{{ __("El nombre contiene caracteres no permitidos. Usa letras, espacios, guiones o apóstrofes.") }}',
            password_required: '{{ __("La contraseña es obligatoria.") }}',
            password_invalid: '{{ __("La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas y números.") }}',
            password_example: '{{ __("Ejemplo válido: MiClave123!") }}',
            password_confirm_mismatch: '{{ __("Las contraseñas no coinciden.") }}',
            terms_required: '{{ __("Debes aceptar los Términos y Condiciones.") }}',
            privacy_required: '{{ __("Debes aceptar el Aviso de Privacidad.") }}'
        };

        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('register-form');

            // Patterns
            const namePattern = /^[\p{L}\s'-]+$/u; // letras (Unicode), espacios, guion y apóstrofe
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            // Password: min 8, at least one uppercase, one lowercase, one number
            const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

            // Helper to reset field error UI
            function clearFieldError(fieldId) {
                const el = document.getElementById(fieldId);
                if (el) el.classList.remove('is-invalid');
                const err = document.getElementById('error-' + fieldId);
                if (err) {
                    err.textContent = '';
                    err.classList.add('hidden');
                }
            }

            // Helper to set field error UI
            function setFieldError(fieldId, message) {
                const el = document.getElementById(fieldId);
                if (el) el.classList.add('is-invalid');
                const err = document.getElementById('error-' + fieldId);
                if (err) {
                    err.textContent = message;
                    err.classList.remove('hidden');
                }
            }

            function sanitizeInput(value) {
                // Eliminamos caracteres de control y etiquetas HTML
                return value.replace(/<[^>]*>?/gm, '').trim();
            }

            form.addEventListener('submit', function(event) {
                // Limpiamos errores previos
                ['name','email','password','password_confirmation','terms','privacy'].forEach(clearFieldError);

                // Obtener valores y sanitizar
                const name = sanitizeInput(document.getElementById('name').value || '');
                const email = sanitizeInput(document.getElementById('email').value || '');
                const password = (document.getElementById('password').value || '').trim();
                const password_confirmation = (document.getElementById('password_confirmation').value || '').trim();
                const terms = document.getElementById('terms').checked;
                const privacy = document.getElementById('privacy').checked;

                const errors = [];

                // Validaciones por campo
                // Nombre
                if (!name) {
                    errors.push({field:'name', msg: texts.name_required });
                } else if (!namePattern.test(name)) {
                    errors.push({field:'name', msg: texts.name_invalid});
                }

                // Email
                if (!email) {
                    errors.push({field:'email', msg: texts.email_required});
                } else if (!emailPattern.test(email)) {
                    errors.push({field:'email', msg: texts.email_invalid});
                }

                // Password
                if (!password) {
                    errors.push({field:'password', msg: texts.password_required});
                } else if (!passwordPattern.test(password)) {
                    // proveer guía / ejemplo al usuario
                    errors.push({field:'password', msg: texts.password_invalid + ' ' + texts.password_example});
                }

                // Confirm password
                if (!password_confirmation) {
                    errors.push({field:'password_confirmation', msg: texts.password_required});
                } else if (password !== password_confirmation) {
                    errors.push({field:'password_confirmation', msg: texts.password_confirm_mismatch});
                }

                // Checkboxes
                if (!terms) errors.push({field:'terms', msg: texts.terms_required});
                if (!privacy) errors.push({field:'privacy', msg: texts.privacy_required});

                if (errors.length > 0) {
                    event.preventDefault();

                    // Marcar campos con error e insertar mensajes simbolicos
                    const uniqueFields = new Set();
                    errors.forEach(e => {
                        uniqueFields.add(e.field);
                        setFieldError(e.field, e.msg);
                    });

                    // Construir html para SweetAlert: lista con mensajes (agrupados)
                    const htmlList = errors.map(e => `<li style="text-align:left;margin-bottom:6px;">${e.msg}</li>`).join('');
                    const finalHtml = `
                        <div style="text-align:center;">
                            <p style="margin-bottom:8px;">${texts.description}</p>
                            <ul style="display:inline-block;text-align:left;padding-left:18px;margin:0;">
                                ${htmlList}
                            </ul>
                        </div>
                    `;

                    Swal.fire({
                        icon: 'error',
                        title: texts.title,
                        html: finalHtml,
                        confirmButtonText: texts.ok,
                        confirmButtonColor: '#F7941D',
                        customClass: {
                            popup: 'swal2-center-text'
                        }
                    });

                    // Mantener focus en primer campo con error
                    const firstField = errors[0].field;
                    const firstEl = document.getElementById(firstField);
                    if (firstEl) firstEl.focus();

                    return false;
                }

                // Si pasa validación cliente, permitimos envío normal al servidor.
                // Nota: servidor volverá a validar y sanear (por seguridad).
            });

            // Optional: limpiar error visual al escribir de nuevo
            ['name','email','password','password_confirmation'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('input', () => clearFieldError(id));
                }
            });
            ['terms','privacy'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.addEventListener('change', () => clearFieldError(id));
                }
            });

            // Accesibilidad: asegurar texto centrado en SweetAlert
            const style = document.createElement('style');
            style.innerHTML = '.swal2-center-text .swal2-html-container { text-align: center; }';
            document.head.appendChild(style);
        });
    </script>
    @endpush

    <style>
        /* Estilos para inputs y checkboxes con error */
        .is-invalid {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 2px rgba(239,68,68,0.12) !important;
        }

        /* Pequeño ajuste para que el texto inline sea visible en dark mode */
        .text-red-500 { color: #ef4444; }

        /* Si Flux monta inputs como 'input' nativo, evitar estilos colision */
        input.is-invalid, textarea.is-invalid, select.is-invalid {
            outline: none;
        }
    </style>
</x-layouts.auth>