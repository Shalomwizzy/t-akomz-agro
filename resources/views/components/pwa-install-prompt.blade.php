<style>
@keyframes pwa-slide-up {
    from { opacity: 0; transform: translateY(40px) scale(0.96); }
    to   { opacity: 1; transform: translateY(0)   scale(1); }
}
@keyframes pwa-fade-in {
    from { opacity: 0; }
    to   { opacity: 1; }
}
@keyframes pwa-shimmer {
    0%   { background-position: -200% center; }
    100% { background-position:  200% center; }
}
@keyframes pwa-pulse-dot {
    0%, 100% { transform: scale(1); opacity: 1; }
    50%       { transform: scale(1.4); opacity: 0.6; }
}
@keyframes pwa-float {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(-6px); }
}
.pwa-shimmer-btn {
    background: linear-gradient(90deg, #B8F397 0%, #6FAE4B 30%, #B8F397 60%, #6FAE4B 100%);
    background-size: 200% auto;
    animation: pwa-shimmer 3s linear infinite;
}
.pwa-step { transition: transform 0.2s ease, box-shadow 0.2s ease; }
.pwa-step:hover { transform: translateX(4px); }
[x-cloak] { display: none !important; }
</style>

<div x-data="pwaInstallPrompt()" x-init="init()" x-cloak>

    {{-- Backdrop --}}
    <div x-show="visible"
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[990]"
         style="background: rgba(0,0,0,0.75); backdrop-filter: blur(6px);"
         @click="later()">
    </div>

    {{-- Modal --}}
    <div x-show="visible"
         x-transition:enter="transition ease-out duration-400"
         x-transition:enter-start="opacity-0 scale-95 translate-y-6"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-250"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-6"
         class="fixed z-[991] inset-x-3 bottom-3 sm:inset-auto sm:bottom-auto sm:top-1/2 sm:left-1/2 sm:-translate-x-1/2 sm:-translate-y-1/2 sm:w-[440px]">

        {{-- Card --}}
        <div class="relative overflow-hidden rounded-[28px]"
             style="background: linear-gradient(160deg, #181818 0%, #101010 100%);
                    border: 1px solid rgba(184,243,151,0.25);
                    box-shadow: 0 40px 100px rgba(0,0,0,0.9),
                                0 0 0 1px rgba(255,255,255,0.04),
                                0 0 80px rgba(184,243,151,0.06) inset;">

            {{-- Top glow bar --}}
            <div class="h-[2px]" style="background: linear-gradient(90deg, transparent 0%, #B8F397 30%, #6FAE4B 60%, transparent 100%);"></div>

            {{-- Ambient glow blob --}}
            <div class="absolute -top-16 left-1/2 -translate-x-1/2 w-64 h-32 pointer-events-none"
                 style="background: radial-gradient(ellipse, rgba(184,243,151,0.12) 0%, transparent 70%); filter: blur(24px);"></div>

            <div class="relative p-6 sm:p-7">

                {{-- Close button --}}
                <button @click="later()"
                        class="absolute top-4 right-4 w-8 h-8 rounded-full flex items-center justify-center transition-all hover:bg-white/8 active:scale-90"
                        style="color: #666;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                {{-- App identity --}}
                <div class="flex items-center gap-4 mb-5">
                    <div class="relative flex-shrink-0" style="animation: pwa-float 4s ease-in-out infinite;">
                        <div class="w-16 h-16 rounded-2xl overflow-hidden"
                             style="box-shadow: 0 8px 24px rgba(184,243,151,0.25), 0 0 0 1px rgba(184,243,151,0.2);">
                            <img src="{{ asset('images/icons/pwa-192.png') }}" alt="T-Akomz Agro" class="w-full h-full object-cover">
                        </div>
                        {{-- Live dot --}}
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 rounded-full flex items-center justify-center"
                             style="background: #050505; border: 2px solid #050505;">
                            <div class="w-2.5 h-2.5 rounded-full" style="background: #B8F397; animation: pwa-pulse-dot 2s ease-in-out infinite;"></div>
                        </div>
                    </div>
                    <div class="min-w-0 pr-8">
                        <div class="flex items-center gap-2 mb-0.5">
                            <h3 class="font-display font-black text-white text-xl leading-tight">Get the App</h3>
                            <span class="px-1.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider"
                                  style="background: rgba(184,243,151,0.12); color: #B8F397; border: 1px solid rgba(184,243,151,0.2);">FREE</span>
                        </div>
                        <p class="text-sm" style="color: #888;">T-Akomz Agro Estates</p>
                    </div>
                </div>

                {{-- Benefit row --}}
                <div class="grid grid-cols-3 gap-2 mb-5">
                    @foreach([
                        ['⚡', 'Lightning Fast', '#F5A623'],
                        ['📶', 'Works Offline', '#B8F397'],
                        ['🔔', 'Get Alerts',    '#29B6F6'],
                    ] as [$ic, $label, $color])
                    <div class="flex flex-col items-center gap-1 py-3 px-2 rounded-2xl text-center"
                         style="background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);">
                        <span class="text-xl">{{ $ic }}</span>
                        <span class="text-[11px] font-semibold leading-tight" style="color: {{ $color }};">{{ $label }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Divider --}}
                <div class="mb-4" style="height: 1px; background: linear-gradient(90deg, transparent, rgba(255,255,255,0.06), transparent);"></div>

                {{-- Steps heading --}}
                <div class="flex items-center gap-2 mb-3">
                    <div class="h-px flex-1" style="background: rgba(184,243,151,0.15);"></div>
                    <p class="text-[11px] font-bold uppercase tracking-[0.15em]" style="color: #B8F397;">
                        <span x-show="isIos">How to install on iOS</span>
                        <span x-show="!isIos && canInstall">Install in one tap</span>
                        <span x-show="!isIos && !canInstall">How to install on Android</span>
                    </p>
                    <div class="h-px flex-1" style="background: rgba(184,243,151,0.15);"></div>
                </div>

                {{-- iOS Steps --}}
                <div x-show="isIos" class="space-y-2 mb-5">
                    @foreach([
                        ['1', '📤', 'Tap the Share button', 'Bottom of Safari — the box with an upward arrow'],
                        ['2', '➕', 'Tap "Add to Home Screen"', 'Scroll down in the share menu to find it'],
                        ['3', '✅', 'Tap "Add" to confirm', 'The T-Akomz app icon appears on your home screen'],
                    ] as [$n, $icon, $title, $desc])
                    <div class="pwa-step flex items-center gap-3 p-3.5 rounded-2xl"
                         style="background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.06);">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm font-black flex-shrink-0"
                             style="background: linear-gradient(135deg, #B8F397, #6FAE4B); color: #050505;">{{ $n }}</div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5">
                                <span class="text-base">{{ $icon }}</span>
                                <p class="text-white text-xs font-semibold">{{ $title }}</p>
                            </div>
                            <p class="text-[11px] mt-0.5 leading-relaxed" style="color: #666;">{{ $desc }}</p>
                        </div>
                        <svg class="w-4 h-4 flex-shrink-0" style="color: rgba(184,243,151,0.4);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    @endforeach
                </div>

                {{-- Android native prompt --}}
                <div x-show="!isIos && canInstall" class="mb-5">
                    <div class="pwa-step flex items-center gap-3 p-4 rounded-2xl"
                         style="background: rgba(184,243,151,0.06); border: 1px solid rgba(184,243,151,0.18);">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-2xl flex-shrink-0"
                             style="background: rgba(184,243,151,0.1);">📲</div>
                        <div>
                            <p class="text-white text-sm font-semibold">Ready to install!</p>
                            <p class="text-[12px] mt-0.5 leading-relaxed" style="color: #888;">
                                Hit the button below — your browser will add the app instantly, no App Store needed.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Android manual steps --}}
                <div x-show="!isIos && !canInstall" class="space-y-2 mb-5">
                    @foreach([
                        ['1', '⋮', 'Tap the 3-dot menu', 'Top-right corner of your Chrome browser'],
                        ['2', '📲', 'Tap "Add to Home screen"', 'Or "Install app" — both work the same way'],
                        ['3', '✅', 'Confirm by tapping "Add"', 'T-Akomz Agro will appear on your home screen'],
                    ] as [$n, $icon, $title, $desc])
                    <div class="pwa-step flex items-center gap-3 p-3.5 rounded-2xl"
                         style="background: rgba(255,255,255,0.025); border: 1px solid rgba(255,255,255,0.06);">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm font-black flex-shrink-0"
                             style="background: linear-gradient(135deg, #B8F397, #6FAE4B); color: #050505;">{{ $n }}</div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-1.5">
                                <span class="text-base font-bold" style="color: #888;">{{ $icon }}</span>
                                <p class="text-white text-xs font-semibold">{{ $title }}</p>
                            </div>
                            <p class="text-[11px] mt-0.5 leading-relaxed" style="color: #666;">{{ $desc }}</p>
                        </div>
                        <svg class="w-4 h-4 flex-shrink-0" style="color: rgba(184,243,151,0.4);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </div>
                    @endforeach
                </div>

                {{-- Primary action --}}
                <button x-show="!isIos && canInstall"
                        @click="installNative()"
                        class="pwa-shimmer-btn w-full py-4 rounded-2xl font-black text-sm tracking-wide mb-2.5 transition-all active:scale-95"
                        style="color: #050505; box-shadow: 0 8px 24px rgba(184,243,151,0.3);">
                    📲 &nbsp;Install App Now — It's Free
                </button>

                <button x-show="isIos || !canInstall"
                        @click="later()"
                        class="pwa-shimmer-btn w-full py-4 rounded-2xl font-black text-sm tracking-wide mb-2.5 transition-all active:scale-95"
                        style="color: #050505; box-shadow: 0 8px 24px rgba(184,243,151,0.3);">
                    ✓ &nbsp;Got it — I'll install now
                </button>

                {{-- Secondary actions --}}
                <div class="grid grid-cols-2 gap-2">
                    <button @click="later()"
                            class="py-3 rounded-2xl text-xs font-semibold transition-all hover:brightness-110 active:scale-95"
                            style="background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.08); color: #CFCFCF;">
                        ⏰ &nbsp;Maybe Later
                    </button>
                    <button @click="neverShow()"
                            class="py-3 rounded-2xl text-xs font-medium transition-all hover:brightness-110 active:scale-95"
                            style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.05); color: #555;">
                        🚫 &nbsp;Never Show Again
                    </button>
                </div>

                {{-- Fine print --}}
                <p class="text-center text-[11px] mt-3" style="color: #444;">
                    No download required · Installs in seconds · Works on any device
                </p>

            </div>

            {{-- Bottom glow bar --}}
            <div class="h-[1px]" style="background: linear-gradient(90deg, transparent, rgba(184,243,151,0.08), transparent);"></div>
        </div>
    </div>
</div>

<script>
function pwaInstallPrompt() {
    return {
        visible: false,
        isIos: false,
        canInstall: false,
        deferredPrompt: null,

        init() {
            if (localStorage.getItem('pwa-install-never') === '1') return;

            this.isIos = /iphone|ipad|ipod/i.test(navigator.userAgent) && !window.MSStream;

            if (window.matchMedia('(display-mode: standalone)').matches) return;
            if (window.navigator.standalone === true) return;

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                this.deferredPrompt = e;
                this.canInstall = true;
            });

            setTimeout(() => { this.visible = true; }, 4000);
        },

        later() {
            this.visible = false;
        },

        neverShow() {
            localStorage.setItem('pwa-install-never', '1');
            this.visible = false;
        },

        installNative() {
            if (!this.deferredPrompt) return;
            this.deferredPrompt.prompt();
            this.deferredPrompt.userChoice.then((result) => {
                if (result.outcome === 'accepted') {
                    localStorage.setItem('pwa-install-never', '1');
                }
                this.deferredPrompt = null;
                this.visible = false;
            });
        },
    }
}
</script>
