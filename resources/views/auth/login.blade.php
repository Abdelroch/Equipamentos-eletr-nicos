<x-guest-layout>
    {{-- Status da sessão (ex: "palavra-passe redefinida") --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Botão Voltar --}}
        <div class="mb-4">
            <a href="{{ route('index') }}"
               class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                &larr; Voltar à página inicial
            </a>
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Endereço de e-mail')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email"
                :value="old('email')" required autofocus autocomplete="username"
                placeholder="exemplo@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Senha --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />
            <x-text-input id="password" class="block w-full mt-1" type="password"
                name="password" required autocomplete="current-password"
                placeholder="A sua senha" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Lembrar-me --}}
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="text-indigo-600 border-gray-300 rounded shadow-sm dark:bg-gray-900 dark:border-gray-700 focus:ring-indigo-500"
                    name="remember">
                <span class="text-sm text-gray-600 ms-2 dark:text-gray-400">Manter sessão iniciada</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('register') }}">
                Não tem conta? Criar agora
            </a>

            <x-primary-button class="ms-3">
                Entrar
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
