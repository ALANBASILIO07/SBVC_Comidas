{{--
* Nombre de la vista           : terminos.blade.php
* Descripción de la vista      : Página de Términos y Condiciones del servicio SBVC Comidas
* Fecha de creación            : 24/11/2025
* Elaboró                      : Claude Code
* Fecha de liberación          : 24/11/2025
* Versión                      : 1.0
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        :
* Descripción del mantenimiento:
* Responsable                  :
* Revisor                      :
--}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Términos y Condiciones - SBVC Comidas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-zinc-50 dark:bg-zinc-900 text-black dark:text-white min-h-screen">

    <main class="max-w-5xl mx-auto p-6 space-y-8">

        {{-- Header con ícono y título --}}
        <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-custom-orange/10 dark:bg-custom-orange/20 flex items-center justify-center">
                    <svg class="h-6 w-6 text-custom-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold">
                        Términos y Condiciones
                    </h1>
                    <p class="text-sm text-black/60 dark:text-white/60">
                        Última actualización: {{ now()->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <a href="javascript:history.back()" class="text-sm text-black dark:text-white hover:underline underline-offset-4 decoration-custom-orange decoration-2 transition-all">
                Volver
            </a>
        </div>

        {{-- Sección 1 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                1. Aceptación de los Términos
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Al acceder y utilizar la plataforma <strong>SBVC Comidas</strong>, usted acepta estar sujeto a estos Términos y Condiciones.
                Si no está de acuerdo con alguna parte de estos términos, no debe utilizar nuestros servicios.
            </p>
        </section>

        {{-- Sección 2 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                2. Uso del Servicio
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Nuestra plataforma está diseñada para ayudar a los establecimientos de comida a gestionar sus operaciones,
                promociones y relaciones con clientes. El usuario se compromete a utilizar el servicio de manera responsable
                y conforme a las leyes aplicables.
            </p>
        </section>

        {{-- Sección 3 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                3. Registro de Cuenta
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Para utilizar ciertos servicios, deberá crear una cuenta. Es su responsabilidad mantener la confidencialidad
                de su contraseña y cuenta. Usted es responsable de todas las actividades que ocurran bajo su cuenta.
            </p>
        </section>

        {{-- Sección 4 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                4. Contenido del Usuario
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Usted es responsable del contenido que publique en la plataforma. No debe publicar contenido que sea
                ilegal, ofensivo, fraudulento o que infrinja los derechos de terceros.
            </p>
        </section>

        {{-- Sección 5 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                5. Propiedad Intelectual
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Todo el contenido, características y funcionalidades de la plataforma son propiedad exclusiva de
                <strong>SBVC Comidas</strong> y están protegidos por las leyes de propiedad intelectual aplicables.
            </p>
        </section>

        {{-- Sección 6 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                6. Limitación de Responsabilidad
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                <strong>SBVC Comidas</strong> no será responsable de daños indirectos, incidentales, especiales o consecuentes
                que resulten del uso o la imposibilidad de usar el servicio.
            </p>
        </section>

        {{-- Sección 7 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                7. Modificaciones
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Nos reservamos el derecho de modificar estos términos en cualquier momento. Las modificaciones
                entrarán en vigor inmediatamente después de su publicación en la plataforma.
            </p>
        </section>

        {{-- Sección 8: Contacto --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                8. Contacto
            </h2>
            <p class="text-sm text-black/80 dark:text-white/80">
                Si tiene preguntas sobre estos Términos y Condiciones, puede contactarnos a través de:
                <a href="mailto:soporte@sbvccomidas.com"
                    class="text-black dark:text-white hover:underline underline-offset-4 decoration-custom-orange decoration-2 transition-all">
                    soporte@sbvccomidas.com
                </a>
            </p>
        </section>

        {{-- Footer --}}
        <div class="text-center text-xs text-black/60 dark:text-white/60 pt-6 border-t border-zinc-200 dark:border-zinc-700">
            <p>&copy; {{ now()->year }} SBVC Comidas. Todos los derechos reservados.</p>
            <p>Plataforma de gestión de establecimientos de comida y promociones.</p>
        </div>

    </main>

</body>

</html>
