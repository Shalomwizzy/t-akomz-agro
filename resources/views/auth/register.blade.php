<x-guest-layout>

    <div class="text-center mb-8">
        <h1 class="font-display font-bold text-2xl text-content-primary">Create your account</h1>
        <p class="text-content-muted text-sm mt-1">Fresh farm produce, delivered to your door</p>
    </div>

    @if($errors->any())
    <div class="mb-5 bg-red-500/10 border border-red-500/20 text-red-400 text-sm px-4 py-3 rounded-xl">
        {{ $errors->first() }}
    </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="space-y-4" x-data>
        @csrf

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="label">Full name</label>
                <input type="text" name="name" value="{{ old('name') }}" required autofocus
                       class="input @error('name') border-red-500 @enderror"
                       placeholder="Emeka Okonkwo">
                @error('name')<p class="error-text">{{ $message }}</p>@enderror
            </div>
        </div>

        <div>
            <label class="label">Email address</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="input @error('email') border-red-500 @enderror"
                   placeholder="you@example.com">
            @error('email')<p class="error-text">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="label">Password</label>
            <div class="relative" x-data="{ show: false }">
                <input :type="show ? 'text' : 'password'" name="password" required
                       class="input pr-11 @error('password') border-red-500 @enderror"
                       placeholder="Min. 8 characters">
                <button type="button" @click="show = !show"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-content-muted hover:text-content-primary transition-colors">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
            </div>
            @error('password')<p class="error-text">{{ $message }}</p>@enderror
        </div>

        <div>
            <label class="label">Confirm password</label>
            <input type="password" name="password_confirmation" required
                   class="input"
                   placeholder="Repeat password">
        </div>

        <button type="submit" class="btn-primary w-full py-3 text-base mt-2">
            Create Account — It's Free
        </button>

        <p class="text-center text-xs text-content-muted">
            By registering you agree to our
            <a href="{{ route('terms') }}" class="text-brand-green hover:underline">Terms</a> and
            <a href="{{ route('privacy') }}" class="text-brand-green hover:underline">Privacy Policy</a>
        </p>

        <div class="relative flex items-center gap-3">
            <div class="flex-1 h-px bg-surface-border"></div>
            <span class="text-xs text-content-muted">or</span>
            <div class="flex-1 h-px bg-surface-border"></div>
        </div>

        <p class="text-center text-sm text-content-muted">
            Already have an account?
            <a href="{{ route('login') }}" class="text-brand-green font-medium hover:underline">Sign in</a>
        </p>
    </form>

    {{-- Benefits --}}
    <div class="mt-6 pt-5 border-t border-surface-border grid grid-cols-3 gap-2 text-center">
        @foreach([['🚚', 'Fast Delivery'], ['🌿', '100% Organic'], ['💳', 'Secure Pay']] as [$icon, $label])
        <div class="flex flex-col items-center gap-1">
            <span class="text-lg">{{ $icon }}</span>
            <span class="text-xs text-content-muted">{{ $label }}</span>
        </div>
        @endforeach
    </div>

</x-guest-layout>
