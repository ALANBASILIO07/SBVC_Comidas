{{--
* Nombre de la vista           : privacidad.blade.php
* Descripción de la vista      : Página de Aviso de Privacidad del servicio SBVC Comidas
* Fecha de creación            : 24/11/2025
* Elaboró                      : Alan Osvaldo Basilio Delgado
* Fecha de liberación          : 06/01/2026
* Autorizó                     : Maileth Patiño Ensastegui
* Versión                      : 1.1
* Fecha de mantenimiento       : 16/01/2026
* Folio de mantenimiento       : DOC-001
* Tipo de mantenimiento        : Actualización de Contenido Legal
* Descripción del mantenimiento: Actualización de cláusulas conforme a la integración de pasarelas de pago y planes de suscripción.
* Responsable                  : Alan Osvaldo Basilio Delgado
* Revisor                      : Maileth Patiño Ensastegui
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
                        Aviso de Privacidad Integral
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
                1. Identidad y Domicilio del Responsable
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                <strong>SBVC Comidas</strong> (en adelante "La Plataforma"), comprometida con la protección de sus datos personales, asume la responsabilidad de su uso, manejo y confidencialidad. Para cualquier duda relacionada con la privacidad de su información, ponemos a su disposición el canal de contacto:
                <a href="mailto:privacidad@sbvccomidas.com" class="text-black dark:text-white hover:underline underline-offset-4 decoration-custom-orange decoration-2 transition-all">
                    privacidad@sbvccomidas.com
                </a>
            </p>
        </section>

        {{-- Sección 2 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                2. Datos Personales Recabados
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Para llevar a cabo las finalidades descritas en el presente aviso, recabaremos y trataremos los siguientes datos personales:
            </p>
            <ul class="list-disc pl-6 text-sm text-black/80 dark:text-white/80 space-y-1">
                <li><strong>Datos de Identificación y Contacto:</strong> Nombre completo, correo electrónico, número telefónico y fotografía de perfil (opcional).</li>
                <li><strong>Datos del Establecimiento:</strong> Nombre comercial, dirección física, geolocalización, categoría del negocio y Registro Federal de Contribuyentes (RFC).</li>
                <li><strong>Datos Patrimoniales y Financieros:</strong> Historial de transacciones, tipo de suscripción contratada (Básico, Estándar o Premium) e información procesada a través de pasarelas de pago (PayPal). Nota: La Plataforma no almacena números completos de tarjetas de crédito.</li>
                <li><strong>Datos de Uso y Navegación:</strong> Dirección IP, tipo de dispositivo, navegador web, interacciones con banners publicitarios y calificaciones otorgadas.</li>
            </ul>
        </section>

        {{-- Sección 3 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                3. Finalidades del Tratamiento
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Su información personal será utilizada para las siguientes finalidades principales, necesarias para el servicio solicitado:
            </p>
            <ul class="list-disc pl-6 text-sm text-black/80 dark:text-white/80 space-y-1 mb-3">
                <li>Creación y gestión de su cuenta de usuario y perfil de cliente.</li>
                <li>Administración de establecimientos, incluyendo la publicación de promociones y banners publicitarios.</li>
                <li>Procesamiento de pagos de suscripciones a través de proveedores externos (PayPal).</li>
                <li>Validación de identidad y seguridad de la cuenta (incluyendo autenticación de dos factores).</li>
                <li>Emisión de comprobantes fiscales y facturación.</li>
            </ul>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                <strong>Finalidades secundarias:</strong>
            </p>
            <ul class="list-disc pl-6 text-sm text-black/80 dark:text-white/80 space-y-1">
                <li>Análisis estadístico sobre el rendimiento de promociones y alcance de banners.</li>
                <li>Envío de notificaciones sobre actualizaciones del sistema, nuevas características o alertas de seguridad.</li>
                <li>Mejora continua de la interfaz y experiencia de usuario.</li>
            </ul>
        </section>

        {{-- Sección 4 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                4. Transferencia de Datos
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Sus datos personales pueden ser compartidos dentro y fuera del país con las siguientes personas, empresas, organizaciones o autoridades distintas a nosotros, para los siguientes fines:
            </p>
            <ul class="list-disc pl-6 text-sm text-black/80 dark:text-white/80 space-y-1">
                <li><strong>PayPal:</strong> Para el procesamiento seguro de pagos y cobro de suscripciones.</li>
                <li><strong>Proveedores de Servicios de Mapas (Google Maps):</strong> Para la geolocalización y visualización de sus establecimientos.</li>
                <li><strong>Autoridades Competentes:</strong> En los casos legalmente previstos para el cumplimiento de disposiciones fiscales o judiciales.</li>
            </ul>
        </section>

        {{-- Sección 5 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                5. Medidas de Seguridad
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Implementamos estrictas medidas de seguridad administrativas, técnicas y físicas para proteger sus datos personales. Esto incluye el uso de certificados SSL para el cifrado de datos en tránsito, almacenamiento seguro de contraseñas mediante hashing, y la opción de habilitar la autenticación de dos factores (2FA) para proteger el acceso a su cuenta.
            </p>
        </section>

        {{-- Sección 6 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                6. Derechos ARCO
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Usted tiene derecho a conocer qué datos personales tenemos de usted, para qué los utilizamos y las condiciones del uso que les damos (Acceso). Asimismo, es su derecho solicitar la corrección de su información personal en caso de que esté desactualizada, sea inexacta o incompleta (Rectificación); que la eliminemos de nuestros registros o bases de datos cuando considere que la misma no está siendo utilizada adecuadamente (Cancelación); así como oponerse al uso de sus datos personales para fines específicos (Oposición).
            </p>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80 mt-2">
                Para el ejercicio de cualquiera de los derechos ARCO, deberá presentar la solicitud respectiva a través del correo electrónico: <a href="mailto:privacidad@sbvccomidas.com" class="text-black dark:text-white hover:underline underline-offset-4 decoration-custom-orange decoration-2 transition-all">privacidad@sbvccomidas.com</a>.
            </p>
        </section>

        {{-- Sección 7 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                7. Uso de Cookies y Tecnologías de Rastreo
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Le informamos que en nuestra página de internet utilizamos cookies, web beacons u otras tecnologías, a través de las cuales es posible monitorear su comportamiento como usuario de internet, así como brindarle un mejor servicio y experiencia al navegar en nuestra página. Los datos personales que recabamos a través de estas tecnologías incluyen: horario de navegación, tiempo de navegación en nuestra página web, secciones consultadas y páginas de internet accedidas previo a la nuestra.
            </p>
        </section>

        {{-- Sección 8 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                8. Cambios al Aviso de Privacidad
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                El presente aviso de privacidad puede sufrir modificaciones, cambios o actualizaciones derivadas de nuevos requerimientos legales; de nuestras propias necesidades por los productos o servicios que ofrecemos; de nuestras prácticas de privacidad; de cambios en nuestro modelo de negocio, o por otras causas. Nos comprometemos a mantenerlo informado sobre los cambios que pueda sufrir el presente aviso de privacidad a través de la plataforma.
            </p>
        </section>

        {{-- Sección 9 --}}
        <section class="space-y-3 rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <h2 class="text-xl font-semibold">
                9. Aceptación de los Términos
            </h2>
            <p class="text-sm leading-relaxed text-black/80 dark:text-white/80">
                Al registrarse, utilizar la plataforma o contratar cualquiera de nuestros planes de suscripción, usted confirma que ha leído y comprendido este Aviso de Privacidad y otorga su consentimiento para el tratamiento de sus datos personales conforme a los términos aquí establecidos.
            </p>
        </section>

        {{-- Footer --}}
        <div class="text-center text-xs text-black/60 dark:text-white/60 pt-6 border-t border-zinc-200 dark:border-zinc-700">
            <flux:text class="text-xs text-black/60 dark:text-white/60">
                &copy; {{ now()->year }} SBVC Comidas. Todos los derechos reservados.
            </flux:text>

            <flux:text class="text-xs text-black/60 dark:text-white/60">
                El uso de este sitio implica la aceptación de este Aviso de Privacidad.
            </flux:text>
        </div>

    </main>

</body>

</html>