<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — T-Akomz Agro Estates</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-bg min-h-screen flex items-center justify-center px-4" x-data>

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-10">
            <a href="{{ route('home') }}" class="inline-flex flex-col items-center gap-3">
                <x-logo class="h-20 w-auto" />
                <div>
                    <div class="font-display font-bold text-2xl text-brand-green tracking-wider">T-AKOMZ</div>
                    <div class="text-content-muted text-xs tracking-widest uppercase mt-0.5">Admin Panel</div>
                </div>
            </a>
        </div>

        {{-- Card --}}
        <div class="bg-surface-card border border-surface-border rounded-2xl shadow-card overflow-hidden">

            {{-- Top accent --}}
            <div class="h-1 bg-gradient-to-r from-brand-green to-brand-dark-green"></div>

            <div class="p-8">
                <h1 class="font-display font-bold text-xl text-content-primary mb-1">Administrator Sign In</h1>
                <p class="text-content-muted text-sm mb-7">Restricted to authorised personnel only.</p>

                @if($errors->any())
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 text-sm px-4 py-3 rounded-xl mb-5">
                    {{ $errors->first() }}
                </div>
                @endif

                <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label class="label">Email address</label>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               class="input @error('email') border-red-500 @enderror"
                               placeholder="admin@takomzagro.com">
                    </div>

                    <div>
                        <label class="label">Password</label>
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

                    <label class="flex items-center gap-3 cursor-pointer select-none group" x-data="{ on: false }">
                        <input type="checkbox" name="remember" x-model="on" class="sr-only">
                        <div class="relative w-10 h-5 rounded-full transition-colors duration-200 flex-shrink-0"
                             :class="on ? 'bg-brand-green' : 'bg-surface-elevated border border-surface-border'">
                            <div class="absolute top-0.5 left-0.5 w-4 h-4 rounded-full bg-white shadow transition-transform duration-200"
                                 :class="on ? 'translate-x-5' : 'translate-x-0'"></div>
                        </div>
                        <span class="text-sm transition-colors" :class="on ? 'text-brand-green font-medium' : 'text-content-secondary'">Keep me signed in</span>
                    </label>

                    <button type="submit" class="btn-primary w-full py-3 text-base">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Sign In to Admin Panel
                    </button>
                </form>
            </div>
        </div>

        <p class="text-center text-xs text-content-muted mt-6">
            Not an admin?
            <a href="{{ route('login') }}" class="text-brand-green hover:underline">Customer login</a>
            &nbsp;·&nbsp;
            <a href="{{ route('home') }}" class="text-brand-green hover:underline">Back to site</a>
        </p>
    </div>

</body>
</html>
