<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            if ($user->hasRole('MANAGER')) {
                return redirect()->route('admin.finance.dashboard');
            }
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$seconds} seconds.",
            ])->withInput();
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();

            if (!$user->isAdmin() && !$user->hasRole('MANAGER')) {
                Auth::logout();
                RateLimiter::hit($throttleKey, 300);
                return back()->withErrors(['email' => 'You do not have admin access.'])->withInput();
            }

            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            if ($user->hasRole(['ADMIN', 'SUPER_ADMIN'])) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->hasRole('MANAGER')) {
                return redirect()->intended(route('admin.finance.dashboard'));
            }

            return redirect()->intended(route('admin.dashboard'));
        }

        RateLimiter::hit($throttleKey, 300);
        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
