<x-guest-layout>
    {{-- Logo --}}
    <div class="mb-8">
        <x-logo-cucsh size="xl" />
    </div>

    {{-- Encabezado --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">
            Iniciar sesión
        </h1>
        <p class="mt-2 text-sm text-gray-500">
            Accede al sistema de gestión de eventos académicos del CUCSH.
        </p>
    </div>

    <!-- Estado de sesión -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <!-- Correo electrónico -->
        <div>
            <x-input-label for="email" value="Correo electrónico" />
            <x-text-input id="email"
                          class="block mt-1.5 w-full"
                          type="email"
                          name="email"
                          :value="old('email')"
                          required
                          autofocus
                          autocomplete="username"
                          aria-describedby="email-error" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" id="email-error" />
        </div>

        <!-- Contraseña -->
        <div class="mt-5">
            <x-input-label for="password" value="Contraseña" />
            <x-text-input id="password"
                          class="block mt-1.5 w-full"
                          type="password"
                          name="password"
                          required
                          autocomplete="current-password"
                          aria-describedby="password-error" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" id="password-error" />
        </div>

        <!-- Recordarme + Olvidaste contraseña -->
        <div class="flex items-center justify-between mt-5">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me"
                       type="checkbox"
                       class="rounded border-gray-300 text-primary shadow-sm focus:ring-udg-gold/30 focus:ring-2"
                       name="remember">
                <span class="ms-2 text-sm text-gray-600">Recordarme</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-gray-500 hover:text-udg-blue transition-colors"
                   href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <!-- Botón iniciar sesión -->
        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 text-sm rounded-lg tracking-normal normal-case font-semibold">
                Iniciar sesión
            </x-primary-button>
        </div>
    </form>

    {{-- Link al calendario publico --}}
    <div class="mt-6 text-center">
        <a href="{{ route('calendario.publico') }}"
           class="text-sm text-gray-400 hover:text-gray-600 transition-colors">
            Ver calendario público
        </a>
    </div>
</x-guest-layout>
