<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Exibe o formulário de registo.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Processa o registo de um novo utilizador.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                // Obrigatórios
                'name'         => ['required', 'string', 'min:3', 'max:255'],
                'email'        => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password'     => ['required', 'confirmed', Rules\Password::min(8)->letters()->numbers()],

                // Opcionais — se preenchidos, devem ter formato correcto
                // NIF: 14 caracteres alfanuméricos (003519344LA042 ou 12345678000090)
                'nif'          => ['nullable', 'string', 'regex:/^[A-Za-z0-9]{14}$/', 'unique:customer,nif'],
                // Telefone angolano: 9 dígitos começando por 9
                'phone_number' => ['nullable', 'string', 'regex:/^9[0-9]{8}$/', 'unique:customer,phone_number'],
                'birth_date'   => ['nullable', 'date', 'before:today', 'after:1900-01-01'],
            ],
            [
                'name.required'       => 'O nome completo é obrigatório.',
                'name.min'            => 'O nome deve ter pelo menos 3 caracteres.',
                'name.max'            => 'O nome não pode ultrapassar 255 caracteres.',

                'email.required'      => 'O endereço de e-mail é obrigatório.',
                'email.email'         => 'Introduza um endereço de e-mail válido.',
                'email.unique'        => 'Já existe uma conta com este endereço de e-mail.',

                'password.required'   => 'A senha é obrigatória.',
                'password.confirmed'  => 'A confirmação da senha não corresponde.',

                'nif.regex'           => 'O B.I. deve ter exactamente 14 caracteres alfanuméricos (ex: 003519344LA042).',
                'nif.unique'          => 'Este número de B.I. já está registado.',

                'phone_number.regex'  => 'Telefone inválido. Use 9 dígitos começando por 9 (ex: 923456789).',
                'phone_number.unique' => 'Este número de telefone já está registado.',

                'birth_date.date'     => 'A data de nascimento não é válida.',
                'birth_date.before'   => 'A data de nascimento deve ser anterior à data de hoje.',
                'birth_date.after'    => 'A data de nascimento não parece ser válida.',
            ]
        );

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'         => $request->name,
                'email'        => $request->email,
                'password'     => Hash::make($request->password),
                'access_level' => 'customer',
            ]);

            \App\Models\Customer::create([
                'user_id'      => $user->id,
                // Se o campo vier preenchido, guarda. Se vier vazio/null, fica null.
                'nif'          => $request->nif          ? strtoupper($request->nif) : null,
                'birth_date'   => $request->birth_date   ?: null,
                'phone_number' => $request->phone_number ?: null,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        event(new Registered($user));
        Auth::login($user);

        return redirect()->route('customer.settings.my_accout.profile')
            ->with('warning', 'Complete o seu perfil (NIF, telefone e data de nascimento) para poder negociar ou comprar produtos.');
    }
}
