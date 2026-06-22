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
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    DB::beginTransaction();
    try {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'access_level' => 'customer',
        ]);

        \App\Models\Customer::create([
            'user_id' => $user->id,
            'nif' => null,
            'birth_date' => null,
            'phone_number' => null,
            'access_level' => 'customer',
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
