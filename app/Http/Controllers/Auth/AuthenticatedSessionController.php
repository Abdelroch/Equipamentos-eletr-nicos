<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /*
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard', absolute: false));
    } */

    public function store(LoginRequest $request): RedirectResponse
    {
        // Autenticar o usuário
        $request->authenticate();

        // Regenerar a sessão para evitar ataques de fixação de sessão
        $request->session()->regenerate();

        // Verificar o access_level do usuário autenticado
        $user = Auth::user();

        if ($user->access_level === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.settings.my_accout.profile');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
