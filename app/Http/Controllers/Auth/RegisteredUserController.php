<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NewUserAdminAlert;
use App\Mail\WelcomeEmail;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
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
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class.',email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        try {
            Mail::to($user->email)->send(new WelcomeEmail($user));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Welcome email failed', ['error' => $e->getMessage()]);
        }

        // Alert admin of new registration
        try {
            $adminEmail = \App\Models\SiteSetting::adminEmail();
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new NewUserAdminAlert($user));
            }
        } catch (\Throwable) {}


        return redirect(route('account.dashboard', absolute: false));
    }
}
