<x-layouts.auth>
    <div class="flex flex-col gap-6">
        <x-auth-header :title="__('Crea una cuenta')" :description="__('Ingresa tus datos para crear tu cuenta')" />

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('register.store') }}" class="flex flex-col gap-6">
            @csrf
            <!-- Name -->
            <flux:input
                name="name"
                :label="__('Nombre')"
                type="text"
                required
                autofocus
                autocomplete="name"
                :placeholder="__('Juan Pérez')"
            />

            <!-- Email Address -->
            <flux:input
                name="email"
                :label="__('Correo electrónico')"
                type="email"
                required
                autocomplete="email"
                placeholder="email@gmail.com"
            />

            <!-- Password -->
            <flux:input
                name="password"
                :label="__('Contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Contraseña')"
                viewable
            />

            <!-- Confirm Password -->
            <flux:input
                name="password_confirmation"
                :label="__('Confirmar contraseña')"
                type="password"
                required
                autocomplete="new-password"
                :placeholder="__('Confirmar contraseña')"
                viewable
            />

        <div class="space-y-4">
            <!-- Términos y Condiciones -->
            <div class="flex items-start gap-3">
                <flux:checkbox name="terms" required class="mt-1" />
                <label class="text-sm text-gray-700 leading-relaxed">
                    Acepto los 
                    <a href="{{ route('terminos') }}" 
                        wire:navigate 
                        class="text-custom-orange hover:underline decoration-custom-orange decoration-2 font-semibold">
                        Términos y Condiciones
                    </a>
                </label>
                <flux:error name="terms" />
            </div>

            <!-- Aviso de Privacidad -->
            <div class="flex items-start gap-3">
                <flux:checkbox name="privacy" required class="mt-1" />
                <label class="text-sm text-gray-700 leading-relaxed">
                    Acepto el 
                    <a href="{{ route('privacidad') }}" 
                        wire:navigate                             
                        class="text-custom-orange hover:underline decoration-custom-orange decoration-2 font-semibold">
                        Aviso de Privacidad
                    </a>
                </label>
                <flux:error name="privacy" />
            </div>
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
</x-layouts.auth> 