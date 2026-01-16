{{--
* Nombre de la vista           : terminos.blade.php
* Descripción de la vista      : Página de Términos y Condiciones del servicio SBVC Comidas
* Fecha de creación            : 24/11/2025
* Elaboró                      : Alan Osvaldo Basilio Delgado
* Fecha de liberación          : 06/01/2026
* Autorizó                     : Maileth Patiño Ensastegui
* Versión                      : 1.1
* Fecha de mantenimiento       : 16/01/2026
* Folio de mantenimiento       : DOC-002
* Tipo de mantenimiento        : Actualización de Contenido Legal
* Descripción del mantenimiento: Inclusión de detalles sobre planes de suscripción, pagos y uso de la plataforma.
* Responsable                  : Alan Osvaldo Basilio Delgado
* Revisor                      : Maileth Patiño Ensastegui
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
                        Términos y Condiciones de Uso
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
                1. Acuerdo General
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Bienvenido a <strong>SBVC Comidas</strong>. Al acceder, navegar y utilizar nuestra plataforma web y servicios asociados, usted acepta incondicionalmente estar sujeto a los presentes Términos y Condiciones. Estos términos constituyen un acuerdo legalmente vinculante entre usted (el "Usuario" o "Cliente") y SBVC Comidas. Si no está de acuerdo con alguna de estas condiciones, le rogamos abstenerse de utilizar nuestros servicios.
            </p>
        </section>

        {{-- Sección 2 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                2. Descripción del Servicio
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                SBVC Comidas ofrece una plataforma digital SaaS (Software as a Service) diseñada para la gestión administrativa y promocional de establecimientos de comida. Nuestros servicios permiten a los usuarios registrar establecimientos, crear y publicar promociones, gestionar banners publicitarios, recibir calificaciones de clientes y acceder a herramientas de administración según el plan contratado.
            </p>
        </section>

        {{-- Sección 3 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                3. Planes de Suscripción y Tarifas
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                El acceso a ciertas funcionalidades de la plataforma está determinado por el nivel de suscripción contratado. Los planes disponibles son:
            </p>
            <ul class="list-disc pl-6 text-sm text-black/80 dark:text-white/80 space-y-2 mt-2">
                <li><strong>Plan Básico (Gratuito):</strong> Permite registrar 1 establecimiento y publicar hasta 5 promociones activas mensuales. Incluye soporte estándar por correo electrónico.</li>
                <li><strong>Plan Estándar:</strong> Con costo mensual. Permite registrar 1 establecimiento, publicar promociones ilimitadas, acceder a estadísticas básicas y recibir soporte prioritario.</li>
                <li><strong>Plan Premium:</strong> Con costo mensual. Permite registrar establecimientos ilimitados, promociones ilimitadas, gestión avanzada de banners, estadísticas detalladas y soporte técnico 24/7.</li>
            </ul>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80 mt-2">
                Nos reservamos el derecho de modificar las tarifas y características de los planes, notificando a los usuarios con antelación razonable.
            </p>
        </section>

        {{-- Sección 4 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                4. Pagos y Facturación
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Los pagos de las suscripciones (Planes Estándar y Premium) se procesan de forma segura a través de <strong>PayPal</strong>. Al contratar un plan de pago, usted autoriza a SBVC Comidas a realizar el cobro correspondiente. Las suscripciones se renuevan automáticamente salvo que se cancelen antes de la fecha de corte.
            </p>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80 mt-2">
                <strong>Política de Reembolsos:</strong> No se ofrecen reembolsos por periodos parciales de servicio no utilizados, salvo en casos excepcionales determinados a discreción de SBVC Comidas o por fallas técnicas imputables a la plataforma.
            </p>
        </section>

        {{-- Sección 5 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                5. Cuenta de Usuario y Seguridad
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Para utilizar el servicio, deberá completar el registro proporcionando información veraz y actualizada. Usted es el único responsable de mantener la confidencialidad de sus credenciales de acceso. SBVC Comidas ofrece autenticación de dos factores (2FA) para mayor seguridad, y recomendamos encarecidamente su activación. Usted acepta notificarnos inmediatamente cualquier uso no autorizado de su cuenta.
            </p>
        </section>

        {{-- Sección 6 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                6. Uso Aceptable y Contenido del Usuario
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Usted conserva los derechos de propiedad sobre el contenido que publica (imágenes de comida, logotipos, descripciones), pero otorga a SBVC Comidas una licencia para alojar, mostrar y distribuir dicho contenido en la plataforma.
            </p>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80 mt-2">
                Está estrictamente prohibido subir contenido ilegal, ofensivo, fraudulento, o que infrinja derechos de autor. Nos reservamos el derecho de suspender o eliminar cuentas que violen estas políticas sin previo aviso.
            </p>
        </section>

        {{-- Sección 7 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                7. Disponibilidad del Servicio
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Nos esforzamos por garantizar una disponibilidad continua del servicio. Sin embargo, SBVC Comidas no garantiza que la plataforma esté libre de interrupciones, errores o virus. Podemos suspender el acceso temporalmente por mantenimiento o actualizaciones.
            </p>
        </section>

        {{-- Sección 8 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                8. Modificaciones a los Términos
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                SBVC Comidas se reserva el derecho de modificar estos Términos y Condiciones en cualquier momento. Las modificaciones entrarán en vigor inmediatamente después de su publicación en el sitio. El uso continuado del servicio tras dichas modificaciones constituirá su aceptación de los nuevos términos.
            </p>
        </section>

        {{-- Sección 9: Contacto --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                9. Contacto y Soporte
            </h2>
            <p class="text-sm text-black/80 dark:text-white/80">
                Si tiene preguntas sobre estos Términos y Condiciones o requiere soporte técnico, puede contactarnos a través de:
                <a href="mailto:soporte@sbvccomidas.com"
                    class="text-black dark:text-white hover:underline underline-offset-4 decoration-custom-orange decoration-2 transition-all">
                    soporte@sbvccomidas.com
                </a>
            </p>
        </section>

        {{-- Footer --}}
        <div class="text-center text-xs text-black/60 dark:text-white/60 pt-6 border-t border-zinc-200 dark:border-zinc-700">
            <flux:text class="text-xs text-black/60 dark:text-white/60">
                &copy; {{ now()->year }} SBVC Comidas. Todos los derechos reservados.
            </flux:text>

            <flux:text class="text-xs text-black/60 dark:text-white/60">
                Al utilizar este servicio, usted acepta regirse por estos Términos y Condiciones.
            </flux:text>
        </div>

    </main>

</body>

</html>