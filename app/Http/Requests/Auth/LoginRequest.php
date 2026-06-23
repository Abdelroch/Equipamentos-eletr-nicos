<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine se o utilizador tem autorização para fazer este pedido.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Mensagens de validação em Português.
     */
    public function messages(): array
    {
        return [
            'email.required'    => 'O endereço de e-mail é obrigatório.',
            'email.string'      => 'O endereço de e-mail deve ser um texto válido.',
            'email.email'       => 'Introduza um endereço de e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
            'password.string'   => 'A senha deve ser um texto válido.',
        ];
    }

    /**
     * Tenta autenticar as credenciais do pedido.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'As credenciais introduzidas estão incorrectas. Verifique o e-mail e a senha.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Verifica se o utilizador não está bloqueado por demasiadas tentativas.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => 'Demasiadas tentativas de acesso. Por favor aguarde ' .
                       ceil($seconds / 60) . ' minuto(s) e tente novamente.',
        ]);
    }

    /**
     * Chave única de rate-limiting para este pedido.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
