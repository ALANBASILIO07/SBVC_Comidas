@props(['title' => config('app.name', 'SBVC - Comidas')])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @fluxAppearance
</head>
<body class="min-h-screen bg-white dark:bg-zinc-900">

    <!-- Sidebar Principal -->
    <flux:sidebar 
        sticky 
        stashable 
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 lg:fixed lg:inset-y-0 lg:z-50 lg:w-72"
    >
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <!-- Logo + Título OPTIMIZADO (sin duplicación) -->
        <div class="flex items-center gap-3 px-4 py-4 border-b border-zinc-200 dark:border-zinc-700">
            <!-- Solo el ícono, sin fondo -->
            <div class="flex-shrink-0">
                <x-app-logo-icon class="size-9" />
            </div>
            
            <!-- Título con ajuste perfecto -->
            <div class="min-w-0 flex-1">
                <h1 class="text-base font-bold text-zinc-900 dark:text-white truncate leading-tight">
                    SBVC - Comidas
                </h1>
            </div>
        </div>

        <!-- Menú principal -->
        <flux:navlist variant="outline" class="p-4">
            <flux:navlist.group :heading="__('Plataforma')">
                <flux:navlist.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Inicio') }}
                </flux:navlist.item>
                <flux:navlist.item icon="building-storefront" :href="route('establecimientos.index')" :current="request()->routeIs('establecimientos.*')" wire:navigate>
                    {{ __('Establecimientos') }}
                </flux:navlist.item>
                <flux:navlist.item icon="tag" :href="route('promociones.index')" :current="request()->routeIs('promociones.*')" wire:navigate>
                    {{ __('Promociones') }}
                </flux:navlist.item>
                <flux:navlist.item icon="photo" :href="route('banners.index')" :current="request()->routeIs('banners.*')" wire:navigate>
                    {{ __('Banners') }}
                </flux:navlist.item>
                <flux:navlist.item icon="star" :href="route('calificaciones.index')" :current="request()->routeIs('calificaciones.*')" wire:navigate>
                    {{ __('Calificaciones') }}
                </flux:navlist.item>
                <flux:navlist.item icon="credit-card" :href="route('subscripcion.index')" :current="request()->routeIs('subscripcion.*')" wire:navigate>
                    {{ __('Subscripción') }}
                </flux:navlist.item>
            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <!-- Menú de usuario (escritorio) -->
        <div class="border-t border-zinc-200 dark:border-zinc-700 p-4">
            <flux:dropdown position="top" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    class="w-full justify-between"
                />

                <flux:menu class="w-64">
                    <flux:menu.radio.group>
                        <div class="px-3 py-2 text-sm">
                            <div class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.item :href="route('profile.edit')" icon="user" wire:navigate>
                        {{ __('Perfil') }}
                    </flux:menu.item>
                    <flux:menu.item :href="route('privacidad')" icon="shield-check" wire:navigate>
                        {{ __('Aviso de Privacidad') }}
                    </flux:menu.item>
                    <flux:menu.item :href="route('terminos')" icon="document-text" wire:navigate>
                        {{ __('Términos y Condiciones') }}
                    </flux:menu.item>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                            {{ __('Cerrar sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </div>
    </flux:sidebar>

    <!-- Header móvil -->
    <flux:header class="lg:hidden border-b border-zinc-200 dark:border-zinc-700">
        <flux:sidebar.toggle icon="bars-3" inset="left" />
        <flux:spacer />
        
        <flux:dropdown position="top" align="end">
            <flux:profile 
                :initials="auth()->user()->initials()" 
                icon-trailing="chevron-down"
                class="text-sm"
            />
            
            <flux:menu>
                <flux:menu.radio.group>
                    <div class="px-3 py-2 text-sm">
                        <div class="font-medium">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</div>
                    </div>
                </flux:menu.radio.group>
                
                <flux:menu.separator />
                
                <flux:menu.item :href="route('profile.edit')" icon="user" wire:navigate>
                    {{ __('Perfil') }}
                </flux:menu.item>
                <flux:menu.item :href="route('privacidad')" icon="shield-check" wire:navigate>
                    {{ __('Aviso de Privacidad') }}
                </flux:menu.item>
                <flux:menu.item :href="route('terminos')" icon="document-text" wire:navigate>
                    {{ __('Términos y Condiciones') }}
                </flux:menu.item>
                
                <flux:menu.separator />
                
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                        {{ __('Cerrar sesión') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <!-- Contenido principal -->
    <flux:main>
        {{ $slot }}
    </flux:main>

    @fluxScripts

    <!-- SweetAlert2 -->
    @php($swal = session()->pull('swal'))
    @if ($swal)
        <script>
            Swal.fire(@json($swal));
        </script>
    @endif

    @if ($errors->any())
        <script>
            const errs = @json($errors->all());
            const list = '<ul style="text-align:left;margin:0;padding-left:18px;">' +
                errs.slice(0, 5).map(e => `<li>${e}</li>`).join('') +
                (errs.length > 5 ? `<li>… y ${errs.length - 5} más</li>` : '') +
                '</ul>';
            Swal.fire({
                icon: 'error',
                title: 'Errores en el formulario',
                html: list,
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>