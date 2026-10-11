<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->merge(['login' => $request->input('login', $request->input('email'))]);
        $credentials = $request->validate(['login' => ['required', 'string'], 'password' => ['required', 'string']]);
        $loginField = str_contains($credentials['login'], '@') ? 'email' : 'username';

        if (! Auth::attempt([$loginField => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            return back()->withErrors(['login' => 'Username/email atau password tidak cocok.'])->onlyInput('login');
        }

        $request->session()->regenerate();

        return to_route($request->user()->role === 'admin' ? 'admin.dashboard' : 'dashboard');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([...$data, 'role' => 'customer']);
        Auth::login($user);
        $request->session()->regenerate();

        return to_route('dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return to_route('login');
    }
}
