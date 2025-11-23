<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white dark:bg-zinc-900">

    <flux:sidebar sticky stashable class="bg-gradient-to-b from-orange-600 to-orange-700 text-white">

        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
            <x-app-logo />
        </a>

        <flux:navlist>
            <flux:navlist.group :heading="__('Plataforma')">

                <flux:navlist.item 
                    icon="home" 
                    :href="route('dashboard')" 
                    :current="request()->routeIs('dashboard')" 
                    wire:navigate
                    class="text-white hover:bg-white/10">
                    {{ __('Inicio') }}
                </flux:navlist.item>

                <flux:navlist.item 
                    icon="building-storefront" 
                    :href="route('establecimientos.index')" 
                    :current="request()->routeIs('establecimientos.*')" 
                    wire:navigate
                    class="text-white hover:bg-white/10">
                    {{ __('Establecimientos') }}
                </flux:navlist.item>

                <flux:navlist.item 
                    icon="tag" 
                    :href="route('promociones.index')" 
                    :current="request()->routeIs('promociones.*')" 
                    wire:navigate
                    class="text-white hover:bg-white/10">
                    {{ __('Promociones') }}
                </flux:navlist.item>

                <flux:navlist.item 
                    icon="photo" 
                    :href="route('banners.index')" 
                    :current="request()->routeIs('banners.*')" 
                    wire:navigate
                    class="text-white hover:bg-white/10">
                    {{ __('Banners') }}
                </flux:navlist.item>

                <flux:navlist.item 
                    icon="bell" 
                    :href="route('notificaciones.index')" 
                    :current="request()->routeIs('notificaciones.*')" 
                    wire:navigate
                    class="text-white hover:bg-white/10">
                    {{ __('Notificaciones') }}
                </flux:navlist.item>

                <flux:navlist.item 
                    icon="star" 
                    :href="route('calificaciones.index')" 
                    :current="request()->routeIs('calificaciones.*')" 
                    wire:navigate
                    class="text-white hover:bg-white/10">
                    {{ __('Calificaciones') }}
                </flux:navlist.item>

                <flux:navlist.item 
                    icon="credit-card" 
                    :href="route('subscripcion.index')" 
                    :current="request()->routeIs('subscripcion.*')" 
                    wire:navigate
                    class="text-white hover:bg-white/10">
                    {{ __('Subscripción') }}
                </flux:navlist.item>

            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:navlist>
            <flux:navlist.item 
                icon="document-text" 
                href="/terminos-y-condiciones" 
                target="_blank"
                class="text-white hover:bg-white/10">
                {{ __('Términos y Condiciones') }}
            </flux:navlist.item>

            <flux:navlist.item 
                icon="shield-check" 
                href="/aviso-de-privacidad" 
                target="_blank"
                class="text-white hover:bg-white/10">
                {{ __('Aviso de Privacidad') }}
            </flux:navlist.item>
        </flux:navlist>

        <flux:spacer />

        <!-- User Menu Desktop -->
        <flux:dropdown class="hidden lg:block" position="top" align="start">
            <flux:profile
                :name="auth()->user()->name"
                :initials="auth()->user()->initials()"
                icon:trailing="chevrons-up-down"
                class="text-white hover:bg-white/10"
            />

            <flux:menu class="w-64">
                <flux:menu.radio.group>
                    <div class="px-3 py-2 text-sm text-gray-600">
                        <div class="font-medium text-gray-900 dark:text-white">{{ auth()->user()->name }}</div>
                        <div class="text-xs">{{ auth()->user()->email }}</div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                    {{ __('Configuración') }}
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                        {{ __('Cerrar Sesión') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>

    </flux:sidebar>

    <!-- Mobile Header - FUERA del sidebar -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle icon="bars-3" inset="left" />
        <flux:spacer />
        <flux:dropdown>
            <flux:profile :initials="auth()->user()->initials()" />
            <flux:menu>
                <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                    {{ __('Configuración') }}
                </flux:menu.item>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle">
                        {{ __('Cerrar Sesión') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <!-- Contenido Principal - FUERA del sidebar -->
    <main class="lg:pl-72">
        {{ $slot }}
    </main>

    @fluxScripts
</body>
</html>