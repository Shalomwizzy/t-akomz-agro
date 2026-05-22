<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Enforce strong passwords everywhere Password::defaults() is used
        Password::defaults(fn () =>
            Password::min(8)->mixedCase()->numbers()
        );

        // Finance / ERP Gates
        // wallet.view  — see finance dashboard, fund balance, expenses list
        Gate::define('wallet.view', fn(User $user) =>
            $user->hasAnyRole(['SUPER_ADMIN', 'ADMIN', 'MANAGER'])
        );

        // wallet.fund  — top-up the business wallet (super admin only)
        Gate::define('wallet.fund', fn(User $user) =>
            $user->hasAnyRole(['SUPER_ADMIN'])
        );

        // expense.create  — submit an expense request
        Gate::define('expense.create', fn(User $user) =>
            $user->hasAnyRole(['SUPER_ADMIN', 'ADMIN', 'MANAGER', 'SALES'])
        );

        // expense.approve  — approve or reject an expense
        Gate::define('expense.approve', fn(User $user) =>
            $user->hasAnyRole(['SUPER_ADMIN', 'ADMIN'])
        );

        // expense.view_all  — see all users' transactions (not just own)
        Gate::define('expense.view_all', fn(User $user) =>
            $user->hasAnyRole(['SUPER_ADMIN', 'ADMIN'])
        );
    }
}
