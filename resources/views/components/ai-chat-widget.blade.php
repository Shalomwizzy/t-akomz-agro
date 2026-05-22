<div x-data="aiChat()" x-init="init()" class="fixed bottom-20 lg:bottom-6 right-6 z-50">

    {{-- Chat Panel --}}
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-95"
         class="absolute bottom-16 right-0 w-80 sm:w-96 bg-surface-card border border-surface-border rounded-2xl shadow-card overflow-hidden flex flex-col"
         style="height: 520px;">

        {{-- Header --}}
        <div class="flex items-center gap-3 px-4 py-3.5 bg-surface-elevated border-b border-surface-border flex-shrink-0">
            <div class="relative">
                <div class="w-9 h-9 rounded-full bg-brand-green/15 border border-brand-green/30 flex items-center justify-center">
                    <x-logo class="w-6 h-6" />
                </div>
                <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-brand-green rounded-full border-2 border-surface-elevated"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-content-primary">T-Akomz AI</p>
                <p class="text-xs text-brand-green">Online · Ready to help</p>
            </div>
            <button @click="open = false" class="p-1 text-content-muted hover:text-content-primary rounded-lg hover:bg-surface-card transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Messages --}}
        <div class="flex-1 overflow-y-auto px-4 py-4 space-y-4" x-ref="messages">

            {{-- Welcome message --}}
            <div class="flex gap-2.5">
                <div class="w-7 h-7 rounded-full bg-brand-green/15 flex-shrink-0 flex items-center justify-center mt-0.5">
                    <x-logo class="w-4 h-4" />
                </div>
                <div class="flex-1">
                    <div class="bg-surface-elevated rounded-2xl rounded-tl-sm px-4 py-3 text-sm text-content-secondary leading-relaxed">
                        Hello! 👋 I'm your T-Akomz AI assistant. I can help you find fresh farm products, answer questions about delivery, and more. How can I help you today?
                    </div>
                    <div class="flex gap-2 mt-2 flex-wrap">
                        @foreach(['Find products', 'Delivery info', 'Track order', 'Contact us'] as $q)
                        <button @click="sendQuickMessage('{{ $q }}')"
                                class="text-xs px-3 py-1 bg-brand-green/10 border border-brand-green/20 text-brand-green rounded-full hover:bg-brand-green/20 transition-colors">
                            {{ $q }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Dynamic messages --}}
            <template x-for="(msg, i) in messages" :key="i">
                <div>
                    {{-- User message --}}
                    <div x-show="msg.role === 'user'" class="flex justify-end">
                        <div class="max-w-xs bg-brand-green text-surface-bg text-sm px-4 py-2.5 rounded-2xl rounded-tr-sm leading-relaxed"
                             x-text="msg.content"></div>
                    </div>

                    {{-- AI message --}}
                    <div x-show="msg.role === 'assistant'" class="flex gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-brand-green/15 flex-shrink-0 flex items-center justify-center mt-0.5">
                            <x-logo class="w-4 h-4" />
                        </div>
                        <div class="flex-1 max-w-xs">
                            <div class="bg-surface-elevated rounded-2xl rounded-tl-sm px-4 py-3 text-sm text-content-secondary leading-relaxed"
                                 x-html="formatReply(msg.content)"></div>

                            {{-- Product cards --}}
                            <template x-if="msg.products && msg.products.length">
                                <div class="mt-2 space-y-2">
                                    <template x-for="product in msg.products" :key="product.id">
                                        <div class="bg-surface-elevated border border-surface-border rounded-xl p-3 flex gap-3">
                                            <img :src="product.image" :alt="product.name" class="w-14 h-14 rounded-lg object-cover flex-shrink-0 bg-surface-card">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-semibold text-content-primary truncate" x-text="product.name"></p>
                                                <p class="text-xs text-content-muted" x-text="product.category"></p>
                                                <p class="text-sm font-bold text-brand-green mt-1" x-text="product.price"></p>
                                            </div>
                                            <a :href="product.url" class="self-center flex-shrink-0 text-xs bg-brand-green text-surface-bg px-2.5 py-1.5 rounded-lg font-medium hover:bg-brand-dark-green transition-colors">
                                                View
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </template>

            {{-- Typing indicator --}}
            <div x-show="loading" class="flex gap-2.5">
                <div class="w-7 h-7 rounded-full bg-brand-green/15 flex-shrink-0 flex items-center justify-center">
                    <x-logo class="w-4 h-4" />
                </div>
                <div class="bg-surface-elevated rounded-2xl rounded-tl-sm px-4 py-3">
                    <div class="flex gap-1.5 items-center h-4">
                        <span class="w-2 h-2 bg-content-muted rounded-full animate-bounce" style="animation-delay:0ms"></span>
                        <span class="w-2 h-2 bg-content-muted rounded-full animate-bounce" style="animation-delay:150ms"></span>
                        <span class="w-2 h-2 bg-content-muted rounded-full animate-bounce" style="animation-delay:300ms"></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Input --}}
        <div class="p-3 border-t border-surface-border flex-shrink-0">
            <form @submit.prevent="sendMessage()" class="flex gap-2">
                <input x-model="input" type="text"
                       placeholder="Ask me anything about T-Akomz…"
                       :disabled="loading"
                       class="input flex-1 py-2.5 text-sm"
                       @keydown.enter.prevent="sendMessage()">
                <button type="submit" :disabled="loading || !input.trim()"
                        class="w-10 h-10 bg-brand-green hover:bg-brand-dark-green text-surface-bg rounded-xl flex items-center justify-center flex-shrink-0 transition-colors disabled:opacity-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                </button>
            </form>
            <p class="text-center text-xs text-content-muted mt-2 opacity-60">Powered by T-Akomz AI</p>
        </div>
    </div>

    {{-- Toggle Button --}}
    <button @click="open = !open"
            class="w-14 h-14 rounded-full shadow-glow-green flex items-center justify-center transition-all duration-300 hover:scale-110 relative"
            :class="open ? 'bg-surface-elevated border border-surface-border' : 'bg-brand-green'"
            :title="open ? 'Close assistant' : 'Chat with T-Akomz AI'">

        <svg x-show="!open" class="w-6 h-6 text-surface-bg" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
        </svg>
        <svg x-show="open" x-cloak class="w-5 h-5 text-content-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>

        {{-- Unread dot --}}
        <span x-show="!open && hasNew" x-cloak
              class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 rounded-full border-2 border-surface-bg"></span>
    </button>
</div>

@push('scripts')
<script>
function aiChat() {
    return {
        open: false,
        input: '',
        messages: [],
        loading: false,
        hasNew: false,
        history: [],

        init() {
            // Show greeting dot after 3s on first load
            setTimeout(() => { if (!this.open) this.hasNew = true; }, 3000);
        },

        async sendMessage() {
            const text = this.input.trim();
            if (!text || this.loading) return;

            this.input = '';
            this.messages.push({ role: 'user', content: text });
            this.scrollToBottom();
            this.loading = true;

            try {
                const res = await fetch('/api/ai/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({
                        message: text,
                        history: this.history.slice(-10),
                    }),
                });

                const data = await res.json();
                const reply = data.reply || 'Sorry, I could not process that.';

                this.messages.push({
                    role: 'assistant',
                    content: reply,
                    products: data.products || null,
                });

                this.history.push(
                    { role: 'user', content: text },
                    { role: 'assistant', content: reply }
                );

            } catch (e) {
                this.messages.push({
                    role: 'assistant',
                    content: 'Sorry, I\'m having trouble connecting. Please try again.',
                    products: null,
                });
            }

            this.loading = false;
            this.hasNew = false;
            this.$nextTick(() => this.scrollToBottom());
        },

        sendQuickMessage(text) {
            this.input = text;
            this.sendMessage();
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const el = this.$refs.messages;
                if (el) el.scrollTop = el.scrollHeight;
            });
        },

        formatReply(text) {
            // Escape HTML first so AI output cannot inject markup
            const safe = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#x27;');
            // Apply markdown-like formatting on the escaped text only
            return safe
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/\*(.*?)\*/g, '<em>$1</em>')
                .replace(/\n/g, '<br>');
        },
    };
}
</script>
@endpush
