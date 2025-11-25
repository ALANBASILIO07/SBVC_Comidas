{{--
* Nombre de la vista           : privacidad.blade.php
* Descripción de la vista      : Página de Aviso de Privacidad del servicio SBVC Comidas
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
    <title>Aviso de Privacidad - SBVC Comidas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-zinc-50 dark:bg-zinc-900 text-black dark:text-white min-h-screen">

    <main class="max-w-5xl mx-auto p-6 space-y-8">

        {{-- Header con ícono y título --}}
        <div class="flex items-center justify-between border-b border-zinc-200 dark:border-zinc-700 pb-4">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-xl bg-custom-orange/10 dark:bg-custom-orange/20 flex items-center justify-center">
                    <svg class="h-6 w-6 text-custom-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-2xl font-bold">
                        Aviso de Privacidad
                    </h1>
                    <p class="text-sm text-black/60 dark:text-white/60">
                        Última actualización: {{ now()->format('d/m/Y') }}
                    </p>
                </div>
            </div>

            <a href="{{ route('home') }}" class="text-sm text-black dark:text-white hover:underline underline-offset-4 decoration-custom-orange decoration-2 transition-all">
                Volver al inicio
            </a>
        </div>

        {{-- Sección 1 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                1. Responsable del Tratamiento de Datos
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                <strong>SBVC Comidas</strong>, con domicilio en [Dirección], es responsable del tratamiento de sus datos personales.
                Para cualquier duda o aclaración, puede contactarnos en
                <a href="mailto:privacidad@sbvccomidas.com" class="text-black dark:text-white hover:underline underline-offset-4 decoration-custom-orange decoration-2 transition-all">
                    privacidad@sbvccomidas.com
                </a>
            </p>
        </section>

        {{-- Sección 2 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                2. Datos Personales que Recabamos
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Para la prestación de nuestros servicios, recabamos los siguientes datos personales:
            </p>
            <ul class="list-disc pl-6 text-sm text-black/80 dark:text-white/80 space-y-1">
                <li>Datos de identificación (nombre, correo electrónico, teléfono)</li>
                <li>Datos de establecimiento (nombre comercial, dirección, RFC)</li>
                <li>Datos bancarios y de facturación (cuando aplique)</li>
                <li>Datos de navegación y uso de la plataforma</li>
            </ul>
        </section>

        {{-- Sección 3 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                3. Finalidades del Tratamiento
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Los datos personales recabados serán utilizados para las siguientes finalidades:
            </p>
            <ul class="list-disc pl-6 text-sm text-black/80 dark:text-white/80 space-y-1">
                <li>Proveer los servicios de la plataforma</li>
                <li>Gestión de su cuenta de usuario</li>
                <li>Procesamiento de pagos y facturación</li>
                <li>Envío de notificaciones relacionadas con el servicio</li>
                <li>Mejora de nuestros servicios y experiencia de usuario</li>
                <li>Cumplimiento de obligaciones legales</li>
            </ul>
        </section>

        {{-- Sección 4 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                4. Compartir Información
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Sus datos personales podrán ser compartidos con:
            </p>
            <ul class="list-disc pl-6 text-sm text-black/80 dark:text-white/80 space-y-1">
                <li>Proveedores de servicios tecnológicos</li>
                <li>Procesadores de pago</li>
                <li>Autoridades competentes cuando sea requerido por ley</li>
            </ul>
        </section>

        {{-- Sección 5 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                5. Medidas de Seguridad
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Implementamos medidas de seguridad administrativas, técnicas y físicas para proteger sus datos personales
                contra daño, pérdida, alteración, destrucción o uso no autorizado, incluyendo encriptación y
                autenticación de dos factores.
            </p>
        </section>

        {{-- Sección 6 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                6. Derechos ARCO
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Usted tiene derecho a <strong>Acceder, Rectificar, Cancelar u Oponerse</strong> (derechos ARCO) al tratamiento de sus
                datos personales. Para ejercer estos derechos, puede enviar una solicitud a
                <a href="mailto:privacidad@sbvccomidas.com" class="text-black dark:text-white hover:underline underline-offset-4 decoration-custom-orange decoration-2 transition-all">
                    privacidad@sbvccomidas.com
                </a>
            </p>
        </section>

        {{-- Sección 7 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                7. Cookies y Tecnologías de Rastreo
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Utilizamos cookies y tecnologías similares para mejorar su experiencia en la plataforma,
                analizar el uso del servicio y personalizar el contenido. Puede configurar su navegador para
                rechazar las cookies, aunque esto puede afectar la funcionalidad del servicio.
            </p>
        </section>

        {{-- Sección 8 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                8. Modificaciones al Aviso de Privacidad
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Nos reservamos el derecho de actualizar este Aviso de Privacidad. Cualquier cambio será publicado
                en esta página con la fecha de actualización correspondiente.
            </p>
        </section>

        {{-- Sección 9 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                9. Consentimiento
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Al utilizar nuestra plataforma, usted consiente el tratamiento de sus datos personales conforme
                a lo establecido en este Aviso de Privacidad.
            </p>
        </section>

        {{-- Footer --}}
        <div class="text-center text-xs text-black/60 dark:text-white/60 pt-6 border-t border-zinc-200 dark:border-zinc-700">
            <p>&copy; {{ now()->year }} SBVC Comidas. Todos los derechos reservados.</p>
        </div>

    </main>

</body>

</html>
