<x-guest-layout>

    <div class="text-center mb-8">
        <h1 class="font-display font-bold text-2xl text-content-primary">Welcome back</h1>
        <p class="text-content-muted text-sm mt-1">Sign in to your T-Akomz account</p>
    </div>

    <x-auth-session-status class="mb-4 text-brand-green text-sm bg-brand-green/10 border border-brand-green/20 px-4 py-2.5 rounded-xl" :status="session('status')" />

    @if($errors->any())
    <div class="mb-5 bg-red-500/10 border border-red-500/20 text-red-400 text-sm px-4 py-3 rounded-xl">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data>
        @csrf

        <div>
            <label class="label">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="input @error('email') border-red-500 @enderror"
                   placeholder="you@example.com">
        </div>

        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label class="label mb-0">Password</label>
                @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs text-brand-green hover:underline">Forgot password?</a>
                @endif
            </div>
            <div class="relative" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'" name="password" required
                       class="input pr-11 @error('password') border-red-500 @enderror"
                       placeholder="••••••••">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-content-muted hover:text-content-primary transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
        </div>

        <label class="flex items-center gap-3 cursor-pointer select-none" x-data="{ on: false }">
            <input type="checkbox" name="remember" x-model="on" class="sr-only">
            <div class="relative w-10 h-5 rounded-full transition-colors duration-200 flex-shrink-0"
                 :class="on ? 'bg-brand-green' : 'bg-surface-elevated border border-surface-border'">
                <div class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-200"
                     :class="on ? 'translate-x-5' : 'translate-x-0'"></div>
            </div>
            <span class="text-sm transition-colors" :class="on ? 'text-brand-green font-medium' : 'text-content-secondary'">Remember me for 30 days</span>
        </label>

        <button type="submit" class="btn-primary w-full py-3 text-base">Sign In</button>

        <div class="relative flex items-center gap-3 py-1">
            <div class="flex-1 h-px bg-surface-border"></div>
            <span class="text-xs text-content-muted">or</span>
            <div class="flex-1 h-px bg-surface-border"></div>
        </div>

        <p class="text-center text-sm text-content-muted">
            New to T-Akomz?
            <a href="{{ route('register') }}" class="text-brand-green font-medium hover:underline">Create account</a>
        </p>
    </form>

    {{-- Trust badges --}}
    <div class="mt-6 pt-5 border-t border-surface-border flex items-center justify-center gap-5">
        <div class="flex items-center gap-1.5 text-xs text-content-muted">
            <svg class="w-3.5 h-3.5 text-brand-green" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
            SSL Secured
        </div>
        <div class="flex items-center gap-1.5 text-xs text-content-muted">
            <svg class="w-3.5 h-3.5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Privacy Protected
        </div>
        <div class="flex items-center gap-1.5 text-xs text-content-muted">
            <svg class="w-3.5 h-3.5 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            Secure Payments
        </div>
    </div>

</x-guest-layout>
