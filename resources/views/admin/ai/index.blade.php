@extends('layouts.admin')

@section('page-title', 'AI Assistant')

@section('content')
<div x-data="adminAI()" x-init="init()" class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center gap-4">
        <div class="w-12 h-12 rounded-2xl bg-brand-green/15 border border-brand-green/30 flex items-center justify-center">
            <x-logo class="w-7 h-7" />
        </div>
        <div>
            <h1 class="font-display font-bold text-2xl text-content-primary">T-Akomz AI Assistant</h1>
            <p class="text-content-muted text-sm">Powered by GROQ & Gemini — your intelligent business partner</p>
        </div>
        <div class="ml-auto flex items-center gap-2">
            <span class="w-2 h-2 bg-brand-green rounded-full animate-pulse"></span>
            <span class="text-xs text-brand-green font-medium">AI Online</span>
        </div>
    </div>

    {{-- Tool Tabs --}}
    <div class="flex gap-2 overflow-x-auto scrollbar-hide pb-1">
        @foreach([
            ['chat',        '💬', 'AI Chat'],
            ['description', '📝', 'Product Description'],
            ['blog',        '✍️', 'Blog Post'],
            ['business',    '📊', 'Business Plan'],
        ] as [$tab, $icon, $label])
        <button @click="activeTab = '{{ $tab }}'"
                :class="activeTab === '{{ $tab }}' ? 'bg-brand-green text-surface-bg' : 'bg-surface-card border border-surface-border text-content-secondary hover:border-brand-green/40'"
                class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium whitespace-nowrap transition-all">
            <span>{{ $icon }}</span> {{ $label }}
        </button>
        @endforeach
    </div>

    {{-- ── AI CHAT ────────────────────────────────────────────────────────── --}}
    <div x-show="activeTab === 'chat'" class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Chat area --}}
        <div class="lg:col-span-2 card p-0 overflow-hidden flex flex-col" style="height: 600px;">
            <div class="px-5 py-4 border-b border-surface-border flex-shrink-0">
                <h2 class="font-semibold text-content-primary">General Assistant</h2>
                <p class="text-xs text-content-muted">Ask anything about your business, products, or get advice</p>
            </div>
            <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4" x-ref="chatMessages">
                <template x-for="(msg, i) in chatHistory" :key="i">
                    <div>
                        <div x-show="msg.role === 'user'" class="flex justify-end">
                            <div class="max-w-lg bg-brand-green text-surface-bg text-sm px-4 py-3 rounded-2xl rounded-tr-sm leading-relaxed"
                                 x-text="msg.content"></div>
                        </div>
                        <div x-show="msg.role === 'assistant'" class="flex gap-3">
                            <div class="w-8 h-8 rounded-full bg-brand-green/15 flex-shrink-0 flex items-center justify-center mt-0.5">
                                <x-logo class="w-5 h-5" />
                            </div>
                            <div class="flex-1 max-w-lg bg-surface-elevated rounded-2xl rounded-tl-sm px-4 py-3 text-sm text-content-secondary leading-relaxed prose-sm"
                                 x-html="formatText(msg.content)"></div>
                        </div>
                    </div>
                </template>
                <div x-show="chatLoading" class="flex gap-3">
                    <div class="w-8 h-8 rounded-full bg-brand-green/15 flex-shrink-0 flex items-center justify-center">
                        <x-logo class="w-5 h-5" />
                    </div>
                    <div class="bg-surface-elevated rounded-2xl rounded-tl-sm px-4 py-3 flex gap-1.5 items-center">
                        <span class="w-2 h-2 bg-content-muted rounded-full animate-bounce" style="animation-delay:0ms"></span>
                        <span class="w-2 h-2 bg-content-muted rounded-full animate-bounce" style="animation-delay:150ms"></span>
                        <span class="w-2 h-2 bg-content-muted rounded-full animate-bounce" style="animation-delay:300ms"></span>
                    </div>
                </div>
            </div>
            <div class="p-4 border-t border-surface-border flex-shrink-0">
                <form @submit.prevent="sendChat()" class="flex gap-2">
                    <input x-model="chatInput" type="text"
                           placeholder="Ask your AI assistant…"
                           :disabled="chatLoading"
                           class="input flex-1 py-2.5 text-sm">
                    <button type="submit" :disabled="chatLoading || !chatInput.trim()"
                            class="btn-primary px-4 py-2.5 disabled:opacity-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                    </button>
                </form>
            </div>
        </div>

        {{-- Quick prompts --}}
        <div class="space-y-4">
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary text-sm mb-3">Quick Prompts</h3>
                <div class="space-y-2">
                    @foreach([
                        'What are the top-selling products in Nigerian agri-markets?',
                        'How can I reduce poultry farm operational costs?',
                        'Write a promotional message for our egg subscription box',
                        'Suggest 5 strategies to increase repeat customer orders',
                        'What seasonal products should I stock in Q4?',
                        'How do I price my products competitively in Lagos?',
                    ] as $prompt)
                    <button @click="chatInput = '{{ $prompt }}'; sendChat()"
                            class="w-full text-left text-xs text-content-secondary bg-surface-elevated hover:bg-surface-border px-3 py-2.5 rounded-lg transition-colors border border-surface-border hover:border-brand-green/30">
                        {{ $prompt }}
                    </button>
                    @endforeach
                </div>
            </div>
            <div class="card p-5">
                <h3 class="font-semibold text-content-primary text-sm mb-2">Clear Chat</h3>
                <button @click="chatHistory = []; chatInput = ''" class="btn-ghost w-full py-2 text-sm text-red-400">
                    Clear conversation
                </button>
            </div>
        </div>
    </div>

    {{-- ── PRODUCT DESCRIPTION ────────────────────────────────────────────── --}}
    <div x-show="activeTab === 'description'" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6 space-y-4">
            <h2 class="font-semibold text-content-primary">Generate Product Description</h2>
            <div>
                <label class="label">Product Name <span class="text-red-400">*</span></label>
                <input x-model="desc.name" type="text" class="input" placeholder="e.g. Fresh Broiler Chicken">
            </div>
            <div>
                <label class="label">Category <span class="text-red-400">*</span></label>
                <select x-model="desc.category" class="input">
                    <option value="">Select category</option>
                    @foreach(\App\Models\Category::active()->get() as $cat)
                    <option value="{{ $cat->name }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Additional Details (optional)</label>
                <textarea x-model="desc.details" class="input resize-none text-sm" rows="3"
                          placeholder="Weight, size, organic certified, farm origin, etc."></textarea>
            </div>
            <button @click="generateDescription()"
                    :disabled="descLoading || !desc.name || !desc.category"
                    class="btn-primary w-full disabled:opacity-50">
                <span x-show="!descLoading">✨ Generate Description</span>
                <span x-show="descLoading">Generating…</span>
            </button>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-content-primary">Generated Description</h2>
                <button x-show="desc.result" @click="copyText(desc.result)"
                        class="text-xs text-brand-green hover:underline">Copy</button>
            </div>
            <div x-show="!desc.result && !descLoading"
                 class="flex flex-col items-center justify-center h-48 text-content-muted text-sm text-center">
                <span class="text-4xl mb-3">📝</span>
                Fill in the form and click generate to create a compelling product description.
            </div>
            <div x-show="descLoading" class="flex items-center justify-center h-48">
                <div class="flex flex-col items-center gap-3 text-content-muted">
                    <div class="w-8 h-8 border-2 border-brand-green border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-sm">Writing description…</span>
                </div>
            </div>
            <div x-show="desc.result && !descLoading"
                 class="text-sm text-content-secondary leading-relaxed whitespace-pre-wrap bg-surface-elevated rounded-xl p-4 max-h-80 overflow-y-auto"
                 x-text="desc.result"></div>
        </div>
    </div>

    {{-- ── BLOG POST ──────────────────────────────────────────────────────── --}}
    <div x-show="activeTab === 'blog'" class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-2 card p-6 space-y-4">
            <h2 class="font-semibold text-content-primary">Generate Blog Post</h2>
            <div>
                <label class="label">Blog Topic <span class="text-red-400">*</span></label>
                <input x-model="blog.topic" type="text" class="input" placeholder="e.g. 5 Benefits of Eating Farm-Fresh Eggs">
            </div>
            <div>
                <label class="label">Category</label>
                <select x-model="blog.category" class="input">
                    <option value="Farming Tips">Farming Tips</option>
                    <option value="Nutrition & Health">Nutrition & Health</option>
                    <option value="Farm News">Farm News</option>
                    <option value="Recipes">Recipes</option>
                    <option value="Business">Business</option>
                </select>
            </div>
            <button @click="generateBlog()" :disabled="blogLoading || !blog.topic" class="btn-primary w-full disabled:opacity-50">
                <span x-show="!blogLoading">✍️ Generate Blog Post</span>
                <span x-show="blogLoading">Writing…</span>
            </button>

            <div class="pt-3 border-t border-surface-border space-y-3">
                <h3 class="text-xs font-semibold text-content-muted uppercase tracking-wider">Actions</h3>
                <button @click="useBlogPost()" x-show="blog.result.title" class="btn-outline w-full py-2.5 text-sm">
                    Open in Blog Editor →
                </button>
                <button @click="autoPublish()" :disabled="autoPublishLoading || !blog.topic"
                        class="w-full py-2.5 text-sm font-semibold rounded-xl transition-all duration-200 disabled:opacity-50 flex items-center justify-center gap-2"
                        style="background: linear-gradient(135deg, rgba(184,243,151,0.15) 0%, rgba(111,174,75,0.15) 100%); border: 1px solid rgba(184,243,151,0.3); color: rgb(var(--brand-green));">
                    <span x-show="!autoPublishLoading">🚀 AI Auto-Publish to Blog</span>
                    <span x-show="autoPublishLoading" class="flex items-center gap-2">
                        <span class="w-4 h-4 border-2 border-current border-t-transparent rounded-full animate-spin"></span>
                        Publishing…
                    </span>
                </button>
                <div x-show="autoPublishResult.title" class="rounded-xl p-3 text-xs space-y-1"
                     style="background: rgba(184,243,151,0.08); border: 1px solid rgba(184,243,151,0.2);">
                    <p class="font-bold text-brand-green">✓ Published!</p>
                    <p class="text-content-secondary" x-text="autoPublishResult.title"></p>
                    <a :href="autoPublishResult.url" target="_blank" rel="noopener noreferrer" 
                       class="text-brand-green underline">View Post →</a>
                </div>
                <div x-show="autoPublishError" class="rounded-xl p-3 text-xs text-red-400"
                     style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);"
                     x-text="autoPublishError"></div>
            </div>
        </div>

        <div class="lg:col-span-3 card p-6">
            <div x-show="!blog.result.title && !blogLoading"
                 class="flex flex-col items-center justify-center h-64 text-content-muted text-sm text-center">
                <span class="text-4xl mb-3">✍️</span>
                Enter a topic to generate a full blog post with title, excerpt, and article content.
            </div>
            <div x-show="blogLoading" class="flex items-center justify-center h-64">
                <div class="flex flex-col items-center gap-3 text-content-muted">
                    <div class="w-8 h-8 border-2 border-brand-green border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-sm">Writing your blog post…</span>
                </div>
            </div>
            <div x-show="blog.result.title && !blogLoading" class="space-y-4">
                <div>
                    <p class="text-xs text-content-muted uppercase tracking-wider mb-1">Title</p>
                    <h3 class="font-display font-bold text-lg text-content-primary" x-text="blog.result.title"></h3>
                </div>
                <div>
                    <p class="text-xs text-content-muted uppercase tracking-wider mb-1">Excerpt</p>
                    <p class="text-sm text-content-secondary italic" x-text="blog.result.excerpt"></p>
                </div>
                <div>
                    <p class="text-xs text-content-muted uppercase tracking-wider mb-1">Content Preview</p>
                    <div class="text-sm text-content-secondary leading-relaxed max-h-64 overflow-y-auto bg-surface-elevated rounded-xl p-4 whitespace-pre-wrap"
                         x-text="blog.result.content"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── BUSINESS PLAN ──────────────────────────────────────────────────── --}}
    <div x-show="activeTab === 'business'" class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-2 card p-6 space-y-4">
            <h2 class="font-semibold text-content-primary">Business Plan Generator</h2>
            <div>
                <label class="label">Section <span class="text-red-400">*</span></label>
                <select x-model="biz.section" class="input">
                    <option value="">Choose section…</option>
                    @foreach([
                        'Executive Summary',
                        'Market Analysis — Nigerian Agri-Market',
                        'Products & Services',
                        'Marketing & Sales Strategy',
                        'Operations Plan',
                        'Financial Projections (3-Year)',
                        'Risk Analysis & Mitigation',
                        'Investment Proposal',
                        'SWOT Analysis',
                        'Full Business Plan',
                    ] as $section)
                    <option value="{{ $section }}">{{ $section }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="label">Additional Context (optional)</label>
                <textarea x-model="biz.context" class="input resize-none text-sm" rows="4"
                          placeholder="e.g. Seeking ₦50M investment, expanding to 100 acres, targeting export market…"></textarea>
            </div>
            <button @click="generateBizPlan()" :disabled="bizLoading || !biz.section" class="btn-primary w-full disabled:opacity-50">
                <span x-show="!bizLoading">📊 Generate Section</span>
                <span x-show="bizLoading">Generating…</span>
            </button>
        </div>

        <div class="lg:col-span-3 card p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-content-primary" x-text="biz.section || 'Business Plan'"></h2>
                <button x-show="biz.result" @click="copyText(biz.result)" class="text-xs text-brand-green hover:underline">Copy</button>
            </div>
            <div x-show="!biz.result && !bizLoading"
                 class="flex flex-col items-center justify-center h-64 text-content-muted text-sm text-center">
                <span class="text-4xl mb-3">📊</span>
                Select a section and generate professional business plan content tailored to T-Akomz Agro Estates.
            </div>
            <div x-show="bizLoading" class="flex items-center justify-center h-64">
                <div class="flex flex-col items-center gap-3 text-content-muted">
                    <div class="w-8 h-8 border-2 border-brand-green border-t-transparent rounded-full animate-spin"></div>
                    <span class="text-sm">Generating business plan content…</span>
                </div>
            </div>
            <div x-show="biz.result && !bizLoading"
                 class="text-sm text-content-secondary leading-relaxed whitespace-pre-wrap bg-surface-elevated rounded-xl p-4 max-h-96 overflow-y-auto"
                 x-html="formatText(biz.result)"></div>
        </div>
    </div>

</div>

@push('scripts')
<script>
function adminAI() {
    return {
        activeTab: 'chat',

        // Chat
        chatInput: '',
        chatHistory: [],
        chatLoading: false,

        // Product description
        desc: { name: '', category: '', details: '', result: '' },
        descLoading: false,

        // Blog
        blog: { topic: '', category: 'Farming Tips', result: { title: '', excerpt: '', content: '' } },
        blogLoading: false,
        autoPublishLoading: false,
        autoPublishResult: {},
        autoPublishError: '',

        // Business plan
        biz: { section: '', context: '', result: '' },
        bizLoading: false,

        init() {
            this.chatHistory.push({
                role: 'assistant',
                content: "Hello! 👋 I'm your T-Akomz Admin AI. I can help you write product descriptions, generate business plans, create blog posts, and answer any business questions. How can I assist you today?"
            });
        },

        async sendChat() {
            const text = this.chatInput.trim();
            if (!text || this.chatLoading) return;
            this.chatInput = '';
            this.chatHistory.push({ role: 'user', content: text });
            this.chatLoading = true;
            this.$nextTick(() => this.scrollChat());

            try {
                const res = await this.apiCall('/admin/ai/chat', {
                    message: text,
                    history: this.chatHistory.slice(-10).filter(m => m.content),
                });
                this.chatHistory.push({ role: 'assistant', content: res.reply || 'No response received.' });
            } catch (e) {
                this.chatHistory.push({ role: 'assistant', content: 'Connection error. Please try again.' });
            }

            this.chatLoading = false;
            this.$nextTick(() => this.scrollChat());
        },

        async generateDescription() {
            this.descLoading = true;
            this.desc.result = '';
            try {
                const res = await this.apiCall('/admin/ai/product-description', {
                    product_name: this.desc.name,
                    category: this.desc.category,
                    details: this.desc.details,
                });
                this.desc.result = res.description || '';
            } catch (e) {
                this.desc.result = 'Error generating description. Please try again.';
            }
            this.descLoading = false;
        },

        async generateBlog() {
            this.blogLoading = true;
            this.blog.result = { title: '', excerpt: '', content: '' };
            try {
                const res = await this.apiCall('/admin/ai/blog-post', {
                    topic: this.blog.topic,
                    category: this.blog.category,
                });
                this.blog.result = { title: res.title || '', excerpt: res.excerpt || '', content: res.content || '' };
            } catch (e) {
                this.blog.result = { title: 'Error', excerpt: '', content: 'Error generating blog post. Please try again.' };
            }
            this.blogLoading = false;
        },

        useBlogPost() {
            const params = new URLSearchParams({
                title: this.blog.result.title,
                excerpt: this.blog.result.excerpt,
                content: this.blog.result.content,
            });
            window.location.href = '{{ route('admin.blog.create') }}?' + params.toString();
        },

        async autoPublish() {
            if (!this.blog.topic) return;
            this.autoPublishLoading = true;
            this.autoPublishResult  = {};
            this.autoPublishError   = '';

            // If no content generated yet, generate first
            if (!this.blog.result.title) {
                await this.generateBlog();
            }

            try {
                const res = await this.apiCall('/admin/ai/auto-publish', {
                    topic:    this.blog.topic,
                    category: this.blog.category,
                });
                if (res.success) {
                    this.autoPublishResult = res.post;
                } else {
                    this.autoPublishError = res.error || 'Publishing failed. Please try again.';
                }
            } catch (e) {
                this.autoPublishError = 'Publishing failed. Please try again.';
            }
            this.autoPublishLoading = false;
        },

        async generateBizPlan() {
            this.bizLoading = true;
            this.biz.result = '';
            try {
                const res = await this.apiCall('/admin/ai/business-plan', {
                    section: this.biz.section,
                    context: this.biz.context,
                });
                this.biz.result = res.content || '';
            } catch (e) {
                this.biz.result = 'Error generating business plan. Please try again.';
            }
            this.bizLoading = false;
        },

        async apiCall(url, data) {
            const res = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify(data),
            });
            if (!res.ok) throw new Error('API error: ' + res.status);
            return res.json();
        },

        scrollChat() {
            const el = this.$refs.chatMessages;
            if (el) el.scrollTop = el.scrollHeight;
        },

        formatText(text) {
            if (!text) return '';
            // Escape HTML first so AI output cannot inject markup
            const safe = text
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#x27;');
            return safe
                .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                .replace(/#{1,3} (.+)/g, '<p class="font-semibold text-content-primary mt-3 mb-1">$1</p>')
                .replace(/\n\n/g, '</p><p class="mb-2">')
                .replace(/\n/g, '<br>');
        },

        async copyText(text) {
            try {
                await navigator.clipboard.writeText(text);
                alert('Copied to clipboard!');
            } catch (e) {
                console.error('Copy failed');
            }
        },
    };
}
</script>
@endpush
@endsection
