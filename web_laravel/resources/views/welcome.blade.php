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
            height: 600px;
            background: #f5f5f5;
            overflow: hidden;
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
            font-size: 16px;
            line-height: 1.5;
        }

        .slide-content .slide-logo {
            width: 120px;
            height: 120px;
            margin-bottom: 20px;
            opacity: 0;
            animation: slideAnimate 1s ease-in-out 0.2s 1 forwards;
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

    {{-- Slider de Categorías y Establecimientos --}}
    <div class="slider-container">
        <div class="slide-wrapper">
            <div class="slide-item" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('https://images.pexels.com/photos/1581384/pexels-photo-1581384.jpeg?auto=compress&cs=tinysrgb&w=1920');">
                <div class="slide-content">
                    <img src="{{ asset('images/logo_comidas.png') }}" alt="SBVC Comidas Logo" class="slide-logo">
                    <div class="slide-name">Bienvenido a SBVC Comidas</div>
                    <div class="slide-desc">La plataforma integral para descubrir y gestionar establecimientos de comida. Conecta con los mejores restaurantes, cafeterías y más.</div>
                </div>
            </div>
            <div class="slide-item" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.pexels.com/photos/34832557/pexels-photo-34832557.jpeg?auto=compress&cs=tinysrgb&w=1920');">
                <div class="slide-content">
                    <div class="slide-name">Gestión Completa</div>
                    <div class="slide-desc">Administra tu establecimiento con herramientas profesionales. Controla horarios, promociones, métodos de pago y toda la información de tu negocio en un solo lugar.</div>
                </div>
            </div>
            <div class="slide-item" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.pexels.com/photos/2553651/pexels-photo-2553651.jpeg?auto=compress&cs=tinysrgb&w=1920');">
                <div class="slide-content">
                    <div class="slide-name">Promociones Efectivas</div>
                    <div class="slide-desc">Crea promociones atractivas que aumenten tus ventas. Conecta con clientes potenciales y fideliza a tus clientes actuales con ofertas especiales y descuentos exclusivos.</div>
                </div>
            </div>
            <div class="slide-item" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.pexels.com/photos/19833909/pexels-photo-19833909.jpeg?auto=compress&cs=tinysrgb&w=1920');">
                <div class="slide-content">
                    <div class="slide-name">Comida Mexicana</div>
                    <div class="slide-desc">Descubre los auténticos sabores de México. Encuentra restaurantes tradicionales con los platillos más representativos de la gastronomía mexicana en tu zona.</div>
                </div>
            </div>
            <div class="slide-item" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.pexels.com/photos/19834043/pexels-photo-19834043.jpeg?auto=compress&cs=tinysrgb&w=1920');">
                <div class="slide-content">
                    <div class="slide-name">Cafeterías Premium</div>
                    <div class="slide-desc">Relájate en los mejores espacios cafeteros. Descubre cafeterías especializadas con baristas expertos, granos selectos y ambientes acogedores perfectos para trabajar o socializar.</div>
                </div>
            </div>
            <div class="slide-item" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.pexels.com/photos/16258092/pexels-photo-16258092.jpeg?auto=compress&cs=tinysrgb&w=1920');">
                <div class="slide-content">
                    <div class="slide-name">Alta Cocina</div>
                    <div class="slide-desc">Experiencias gastronómicas únicas. Explora restaurantes de alta cocina con chefs reconocidos, ingredientes premium y maridajes excepcionales para ocasiones especiales.</div>
                </div>
            </div>
            <div class="slide-item" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.pexels.com/photos/2128109/pexels-photo-2128109.jpeg?auto=compress&cs=tinysrgb&w=1920');">
                <div class="slide-content">
                    <div class="slide-name">Postres Artesanales</div>
                    <div class="slide-desc">Endulza tu día con creaciones únicas. Encuentra reposterías artesanales con pasteles, tartas, galletas y postres elaborados con recetas tradicionales y técnicas innovadoras.</div>
                </div>
            </div>
            <div class="slide-item" style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.pexels.com/photos/4542998/pexels-photo-4542998.jpeg?auto=compress&cs=tinysrgb&w=1920');">
                <div class="slide-content">
                    <div class="slide-name">Cocina Internacional</div>
                    <div class="slide-desc">Viaja por el mundo a través del sabor. Explora establecimientos con auténtica comida italiana, asiática, mediterránea y más culturas culinarias sin salir de tu ciudad.</div>
                </div>
            </div>
        </div>

        <div class="slider-buttons">
            <button class="prev-btn"><i class="fa-solid fa-arrow-left"></i></button>
            <button class="next-btn"><i class="fa-solid fa-arrow-right"></i></button>
        </div>
    </div>

    {{-- Contenido Principal --}}
    <main class="flex-grow container mx-auto px-6 py-16">
        {{-- Beneficios Adicionales --}}
        <div class="mb-16">
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

        {{-- Planes de Suscripción --}}
        <div class="mt-16">
            <flux:heading level="3" size="lg" class="text-2xl md:text-3xl font-semibold mb-8 text-center text-custom-blue dark:text-white">
                Planes de Suscripción
            </flux:heading>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                {{-- Plan Básico --}}
                <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-lg p-8 border-2 border-zinc-200 dark:border-zinc-700 hover:shadow-xl transition-all hover:-translate-y-1">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-custom-blue/10 rounded-full mb-4">
                            <svg class="w-8 h-8 text-custom-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <flux:heading level="4" class="text-2xl font-bold mb-2 text-custom-blue dark:text-white">
                            Plan Básico
                        </flux:heading>
                        <flux:text class="text-sm text-black/60 dark:text-white/60">
                            Perfecto para comenzar
                        </flux:text>
                    </div>

                    <div class="mb-6 text-center">
                        <span class="text-4xl font-extrabold text-black dark:text-white">$299</span>
                        <span class="text-black/60 dark:text-white/60 text-sm">/mes</span>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-custom-green flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-black/70 dark:text-white/70">
                                Gestión de 1 establecimiento
                            </flux:text>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-custom-green flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-black/70 dark:text-white/70">
                                Hasta 5 promociones activas
                            </flux:text>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-custom-green flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-black/70 dark:text-white/70">
                                Soporte por correo electrónico
                            </flux:text>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-custom-green flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-black/70 dark:text-white/70">
                                Estadísticas básicas
                            </flux:text>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-custom-green flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-black/70 dark:text-white/70">
                                Actualizaciones mensuales
                            </flux:text>
                        </li>
                    </ul>

                    <flux:button variant="primary" class="w-full bg-custom-blue hover:bg-custom-blue/90 text-white">
                        Comenzar ahora
                    </flux:button>
                </div>

                {{-- Plan Premium --}}
                <div class="bg-gradient-to-br from-custom-orange to-custom-orange/80 dark:from-custom-orange dark:to-custom-orange/90 rounded-2xl shadow-xl p-8 border-2 border-custom-orange hover:shadow-2xl transition-all hover:-translate-y-1 relative">
                    <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                        <span class="bg-custom-green text-white px-4 py-1 rounded-full text-xs font-bold uppercase shadow-lg">
                            Recomendado
                        </span>
                    </div>

                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-white/20 rounded-full mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                            </svg>
                        </div>
                        <flux:heading level="4" class="text-2xl font-bold mb-2 text-white">
                            Plan Premium
                        </flux:heading>
                        <flux:text class="text-sm text-white/80">
                            Funcionalidades completas
                        </flux:text>
                    </div>

                    <div class="mb-6 text-center">
                        <span class="text-4xl font-extrabold text-white">$599</span>
                        <span class="text-white/80 text-sm">/mes</span>
                    </div>

                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-white">
                                Establecimientos ilimitados
                            </flux:text>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-white">
                                Promociones ilimitadas
                            </flux:text>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-white">
                                Soporte prioritario 24/7
                            </flux:text>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-white">
                                Análisis avanzado y reportes
                            </flux:text>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-white">
                                Personalización completa
                            </flux:text>
                        </li>
                        <li class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-white flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <flux:text class="text-sm text-white">
                                API de integración
                            </flux:text>
                        </li>
                    </ul>

                    <flux:button variant="primary" class="w-full bg-white text-custom-orange hover:bg-white/90 font-bold">
                        Elegir Premium
                    </flux:button>
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
