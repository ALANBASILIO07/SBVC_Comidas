{{--
* Nombre de la vista           : welcome.blade.php
* Descripción de la vista      : Página principal de la web donde se muestra la bienvenida al sistema
*                               de gestión de establecimientos de comida, con categorías disponibles
*                               y enlaces para iniciar sesión o registrarse.
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
    <meta name="description" content="SBVC Comidas - Plataforma de gestión de establecimientos de comida, promociones y reservaciones">
    <title>Bienvenido - SBVC Comidas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .slider-container {
            position: relative;
            width: 100%;
            max-width: 1000px;
            height: 600px;
            background: #f5f5f5;
            box-shadow: 0 30px 50px #dbdbdb;
            margin: 0 auto;
            overflow: hidden;
            border-radius: 20px;
        }

        .slide-wrapper .slide-item {
            width: 200px;
            height: 300px;
            position: absolute;
            top: 50%;
            transform: translate(0, -50%);
            border-radius: 20px;
            box-shadow: 0 30px 50px #505050;
            background-position: 50% 50%;
            background-size: cover;
            transition: 0.5s;
        }

        .slide-wrapper .slide-item:nth-child(1),
        .slide-wrapper .slide-item:nth-child(2) {
            top: 0;
            left: 0;
            transform: translate(0, 0);
            border-radius: 0;
            width: 100%;
            height: 100%;
        }

        .slide-wrapper .slide-item:nth-child(3) {
            left: 50%;
        }

        .slide-wrapper .slide-item:nth-child(4) {
            left: calc(50% + 220px);
        }

        .slide-wrapper .slide-item:nth-child(5) {
            left: calc(50% + 440px);
        }

        .slide-wrapper .slide-item:nth-child(n + 6) {
            left: calc(50% + 660px);
            opacity: 0;
        }

        .slide-item .slide-content {
            position: absolute;
            top: 50%;
            left: 100px;
            width: 300px;
            text-align: left;
            color: #eee;
            transform: translate(0, -50%);
            display: none;
        }

        .slide-wrapper .slide-item:nth-child(2) .slide-content {
            display: block;
        }

        .slide-content .slide-name {
            font-size: 40px;
            text-transform: uppercase;
            font-weight: bold;
            opacity: 0;
            animation: slideAnimate 1s ease-in-out 1 forwards;
        }

        .slide-content .slide-desc {
            margin-top: 10px;
            margin-bottom: 20px;
            opacity: 0;
            animation: slideAnimate 1s ease-in-out 0.3s 1 forwards;
        }

        @keyframes slideAnimate {
            from {
                opacity: 0;
                transform: translate(0, 100px);
                filter: blur(33px);
            }
            to {
                opacity: 1;
                transform: translate(0);
                filter: blur(0);
            }
        }

        .slider-buttons {
            width: 100%;
            text-align: center;
            position: absolute;
            bottom: 20px;
            z-index: 10;
        }

        .slider-buttons button {
            width: 40px;
            height: 35px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            margin: 0 5px;
            border: 1px solid #000;
            background: white;
            transition: 0.3s;
        }

        .slider-buttons button:hover {
            background: #DE6601;
            color: #fff;
            border-color: #DE6601;
        }
    </style>
</head>

<body class="font-sans bg-zinc-50 dark:bg-zinc-900 text-black dark:text-white flex flex-col min-h-screen">
    {{-- Header --}}
    <header class="bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 py-4 shadow-sm">
        <div class="container mx-auto px-6 flex justify-between items-center">
            {{-- Logo / Título --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo_comidas.png') }}" alt="SBVC Comidas Logo" class="w-10 h-10 object-contain">
                <flux:heading level="1" size="xl" class="text-2xl font-bold text-black dark:text-white">
                    SBVC Comidas
                </flux:heading>
            </div>

            {{-- Navegación --}}
            <nav class="flex items-center gap-4">
                <flux:button
                    icon="arrow-right-start-on-rectangle"
                    icon-variant="outline"
                    variant="primary"
                    :href="route('login')"
                    class="px-4 py-2 bg-custom-orange text-white rounded-lg hover:bg-custom-orange/90 transition-colors">
                    Iniciar sesión
                </flux:button>

                <flux:button
                    icon="user-plus"
                    icon-variant="outline"
                    variant="primary"
                    :href="route('register')"
                    class="px-4 py-2 bg-custom-orange text-white rounded-lg hover:bg-custom-orange/90 transition-colors">
                    Registrarse
                </flux:button>
            </nav>
        </div>
    </header>

    {{-- Contenido Principal --}}
    <main class="flex-grow container mx-auto px-6 py-16">
        {{-- Hero Section --}}
        <div class="text-center mb-16">
            <flux:heading level="2" size="xl" class="text-4xl md:text-5xl font-extrabold mb-4 text-custom-blue dark:text-white">
                ¡Bienvenido a SBVC Comidas!
            </flux:heading>

            <flux:text class="text-lg md:text-xl text-black/70 dark:text-white/70 max-w-3xl mx-auto leading-relaxed">
                La plataforma integral para gestionar tu establecimiento de comida. Administra tus promociones,
                conecta con tus clientes y haz crecer tu negocio de manera simple y efectiva.
            </flux:text>
        </div>

        {{-- Características Principales --}}
        <div class="mb-16">
            <flux:heading level="3" size="lg" class="text-2xl md:text-3xl font-semibold mb-8 text-center text-custom-blue dark:text-white">
                ¿Qué ofrecemos?
            </flux:heading>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Tarjeta 1: Gestión de Establecimientos --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-md p-6 border border-zinc-200 dark:border-zinc-700 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-center w-16 h-16 bg-custom-blue/10 rounded-full mb-4 mx-auto">
                        <svg class="w-8 h-8 text-custom-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <flux:heading level="4" size="lg" class="text-xl font-bold mb-3 text-center text-custom-blue dark:text-white">
                        Gestión de Establecimientos
                    </flux:heading>
                    <flux:text class="text-black/70 dark:text-white/70 text-center">
                        Administra tu negocio, horarios, métodos de pago y toda la información de tu establecimiento en un solo lugar.
                    </flux:text>
                </div>

                {{-- Tarjeta 2: Promociones --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-md p-6 border-2 border-custom-green hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-center w-16 h-16 bg-custom-green/10 rounded-full mb-4 mx-auto">
                        <svg class="w-8 h-8 text-custom-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <flux:heading level="4" size="lg" class="text-xl font-bold mb-3 text-center text-custom-green dark:text-white">
                        Promociones Efectivas
                    </flux:heading>
                    <flux:text class="text-black/70 dark:text-white/70 text-center">
                        Crea y gestiona promociones atractivas para tus clientes. Aumenta tus ventas con ofertas especiales.
                    </flux:text>
                </div>

                {{-- Tarjeta 3: Calificaciones --}}
                <div class="bg-white dark:bg-zinc-800 rounded-xl shadow-md p-6 border border-zinc-200 dark:border-zinc-700 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-center w-16 h-16 bg-custom-orange/10 rounded-full mb-4 mx-auto">
                        <svg class="w-8 h-8 text-custom-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                    </div>
                    <flux:heading level="4" size="lg" class="text-xl font-bold mb-3 text-center text-custom-orange dark:text-white">
                        Calificaciones y Reseñas
                    </flux:heading>
                    <flux:text class="text-black/70 dark:text-white/70 text-center">
                        Recibe feedback de tus clientes y mejora continuamente la experiencia que ofreces.
                    </flux:text>
                </div>
            </div>
        </div>

        {{-- Slider de Categorías y Establecimientos --}}
        <div class="mb-16">
            <flux:heading level="3" size="lg" class="text-2xl md:text-3xl font-semibold mb-8 text-center text-black dark:text-white">
                Descubre Establecimientos por Categoría
            </flux:heading>

            <div class="slider-container">
                <div class="slide-wrapper">
                    <div class="slide-item" style="background-image: url('https://images.pexels.com/photos/19833909/pexels-photo-19833909.jpeg?auto=compress&cs=tinysrgb&w=1200');">
                        <div class="slide-content">
                            <div class="slide-name">Comida Mexicana</div>
                            <div class="slide-desc">Encuentra los mejores restaurantes de comida mexicana tradicional en tu zona.</div>
                        </div>
                    </div>
                    <div class="slide-item" style="background-image: url('https://images.pexels.com/photos/19834043/pexels-photo-19834043.jpeg?auto=compress&cs=tinysrgb&w=1200');">
                        <div class="slide-content">
                            <div class="slide-name">Comida Rápida</div>
                            <div class="slide-desc">Descubre establecimientos de comida rápida con las mejores promociones.</div>
                        </div>
                    </div>
                    <div class="slide-item" style="background-image: url('https://images.pexels.com/photos/16258092/pexels-photo-16258092.jpeg?auto=compress&cs=tinysrgb&w=1200');">
                        <div class="slide-content">
                            <div class="slide-name">Restaurantes</div>
                            <div class="slide-desc">Explora restaurantes de alta cocina y experiencias gastronómicas únicas.</div>
                        </div>
                    </div>
                    <div class="slide-item" style="background-image: url('https://images.pexels.com/photos/16235564/pexels-photo-16235564.jpeg?auto=compress&cs=tinysrgb&w=1200');">
                        <div class="slide-content">
                            <div class="slide-name">Cafeterías</div>
                            <div class="slide-desc">Encuentra las mejores cafeterías y espacios para disfrutar tu bebida favorita.</div>
                        </div>
                    </div>
                    <div class="slide-item" style="background-image: url('https://images.pexels.com/photos/2128109/pexels-photo-2128109.jpeg?auto=compress&cs=tinysrgb&w=1200');">
                        <div class="slide-content">
                            <div class="slide-name">Postres y Repostería</div>
                            <div class="slide-desc">Descubre establecimientos especializados en postres artesanales y repostería.</div>
                        </div>
                    </div>
                    <div class="slide-item" style="background-image: url('https://images.pexels.com/photos/4542998/pexels-photo-4542998.jpeg?auto=compress&cs=tinysrgb&w=1200');">
                        <div class="slide-content">
                            <div class="slide-name">Comida Internacional</div>
                            <div class="slide-desc">Explora sabores del mundo en establecimientos de cocina internacional.</div>
                        </div>
                    </div>
                </div>

                <div class="slider-buttons">
                    <button class="prev-btn"><i class="fa-solid fa-arrow-left"></i></button>
                    <button class="next-btn"><i class="fa-solid fa-arrow-right"></i></button>
                </div>
            </div>
        </div>

        {{-- Beneficios Adicionales --}}
        <div class="mt-16">
            <flux:heading level="3" size="lg" class="text-2xl md:text-3xl font-semibold mb-8 text-center text-custom-blue dark:text-white">
                Beneficios de usar nuestra plataforma
            </flux:heading>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-custom-green" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <flux:heading level="5" class="font-semibold mb-1 text-black dark:text-white">
                            Fácil de usar
                        </flux:heading>
                        <flux:text class="text-sm text-black/70 dark:text-white/70">
                            Interfaz intuitiva y amigable que no requiere conocimientos técnicos avanzados.
                        </flux:text>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-custom-green" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <flux:heading level="5" class="font-semibold mb-1 text-black dark:text-white">
                            Seguridad garantizada
                        </flux:heading>
                        <flux:text class="text-sm text-black/70 dark:text-white/70">
                            Protección de datos con autenticación de dos factores y encriptación.
                        </flux:text>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-custom-green" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <flux:heading level="5" class="font-semibold mb-1 text-black dark:text-white">
                            Soporte dedicado
                        </flux:heading>
                        <flux:text class="text-sm text-black/70 dark:text-white/70">
                            Atención personalizada para resolver cualquier duda o problema que tengas.
                        </flux:text>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-custom-green" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <flux:heading level="5" class="font-semibold mb-1 text-black dark:text-white">
                            Actualizaciones constantes
                        </flux:heading>
                        <flux:text class="text-sm text-black/70 dark:text-white/70">
                            Nuevas funcionalidades y mejoras continuas para mantener tu negocio competitivo.
                        </flux:text>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700 text-center py-6 mt-auto">
        <flux:text variant="subtle" class="text-black/70 dark:text-white/70 text-sm mb-2">
            © {{ date('Y') }} SBVC Comidas. Todos los derechos reservados.
        </flux:text>

        <div class="flex justify-center gap-6 mt-3">
            <a href="{{ route('terminos') }}"
               class="text-custom-orange hover:text-custom-orange/80 text-sm underline-offset-4 hover:underline transition-colors">
                Términos y Condiciones
            </a>
            <a href="{{ route('privacidad') }}"
               class="text-custom-orange hover:text-custom-orange/80 text-sm underline-offset-4 hover:underline transition-colors">
                Aviso de Privacidad
            </a>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let nextBtn = document.querySelector('.next-btn');
            let prevBtn = document.querySelector('.prev-btn');

            nextBtn.addEventListener('click', function() {
                let items = document.querySelectorAll('.slide-item');
                document.querySelector('.slide-wrapper').appendChild(items[0]);
            });

            prevBtn.addEventListener('click', function() {
                let items = document.querySelectorAll('.slide-item');
                document.querySelector('.slide-wrapper').prepend(items[items.length - 1]);
            });
        });
    </script>
</body>

</html>
