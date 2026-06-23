<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        {{-- Botão Voltar --}}
        <div class="mb-4">
            <a href="{{ route('index') }}"
               class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                &larr; Voltar à página inicial
            </a>
        </div>

        {{-- Nome --}}
        <div>
            <x-input-label for="name" :value="__('Nome completo')" />
            <x-text-input id="name" class="block w-full mt-1" type="text" name="name"
                :value="old('name')" required autofocus autocomplete="name"
                placeholder="O seu nome completo" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        {{-- Email --}}
        <div class="mt-4">
            <x-input-label for="email" :value="__('Endereço de e-mail')" />
            <x-text-input id="email" class="block w-full mt-1" type="email" name="email"
                :value="old('email')" required autocomplete="username"
                placeholder="exemplo@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- NIF / Nº do B.I. (opcional) --}}
        <div class="mt-4">
            <x-input-label for="nif" :value="__('NIF / Nº do B.I. (opcional)')" />
            <x-text-input id="nif" class="block w-full mt-1" type="text" name="nif"
                :value="old('nif')" maxlength="14" autocomplete="off"
                placeholder="14 caracteres — ex: 003519344LA042" />
            <p class="mt-1 text-xs text-gray-500">
                {{ __('Pode preencher mais tarde no perfil, antes de negociar ou comprar.') }}
            </p>
            <x-input-error :messages="$errors->get('nif')" class="mt-2" />
        </div>

        {{-- Telefone / WhatsApp (opcional) --}}
        <div class="mt-4">
            <x-input-label for="phone_number" :value="__('Telefone / WhatsApp (opcional)')" />
            <x-text-input id="phone_number" class="block w-full mt-1" type="text" name="phone_number"
                :value="old('phone_number')" maxlength="9" autocomplete="tel"
                placeholder="9 dígitos — ex: 923456789" />
            <p class="mt-1 text-xs text-gray-500">
                {{ __('Formato angolano: 9 dígitos começando por 9.') }}
            </p>
            <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
        </div>

        {{-- Data de nascimento (opcional) --}}
        <div class="mt-4">
            <x-input-label for="birth_date" :value="__('Data de nascimento (opcional)')" />
            <x-text-input id="birth_date" class="block w-full mt-1" type="date" name="birth_date"
                :value="old('birth_date')" />
            <x-input-error :messages="$errors->get('birth_date')" class="mt-2" />
        </div>

        {{-- Senha --}}
        <div class="mt-4">
            <x-input-label for="password" :value="__('Senha')" />
            <x-text-input id="password" class="block w-full mt-1" type="password" name="password"
                required autocomplete="new-password"
                placeholder="Mínimo 8 caracteres (letras + números)" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Confirmar Senha --}}
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirmar senha')" />
            <x-text-input id="password_confirmation" class="block w-full mt-1" type="password"
                name="password_confirmation" required autocomplete="new-password"
                placeholder="Repita a senha" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="text-sm text-gray-600 underline rounded-md dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
               href="{{ route('login') }}">
                Já tem conta? Entrar
            </a>

            <x-primary-button class="ms-4">
                Criar conta
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
