<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-900">
        {{-- Sidebar fijo en escritorio --}}
        <flux:sidebar
            stashable
            class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 lg:fixed lg:inset-y-0 lg:left-0 lg:z-20 lg:w-72"
        >
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Plataforma')" class="grid">
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

                    <flux:navlist.item icon="bell" :href="route('notificaciones.index')" :current="request()->routeIs('notificaciones.*')" wire:navigate>
                        {{ __('Notificaciones') }}
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

            {{-- Enlaces legales --}}
            <flux:navlist variant="outline">
                <flux:navlist.item icon="document-text" :href="route('terminos')" target="_blank">
                    {{ __('Términos y Condiciones') }}
                </flux:navlist.item>

                <flux:navlist.item icon="shield-check" :href="route('privacidad')" target="_blank">
                    {{ __('Aviso de Privacidad') }}
                </flux:navlist.item>
            </flux:navlist>

            <flux:spacer />

            {{-- Menú de usuario en escritorio --}}
            <flux:dropdown class="hidden lg:block" position="top" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Configuración') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        {{-- Header móvil --}}
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Configuración') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{-- Contenido Principal --}}
        <div class="lg:pl-72">
            {{ $slot }}
        </div>

        @fluxScripts
    </body>
</html>