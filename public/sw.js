/**
 * T-Akomz Agro Estates — Service Worker
 * Version: v1
 * Strategy matrix:
 *   /build/ assets  → Cache-First  (shell cache)
 *   Images          → Cache-First  (image cache, 60-entry LRU)
 *   HTML pages      → Network-First (fallback to cache or /offline)
 *   Everything else → Network-Only
 */

const VERSION        = 'v2';
const CACHE_SHELL    = `takomz-v2-shell`;
const CACHE_IMAGES   = `takomz-v2-images`;
const CACHE_PAGES    = `takomz-v2-pages`;
const IMAGE_LIMIT    = 60;
const PRECACHE_URLS  = ['/offline'];

// ─── Install ─────────────────────────────────────────────────────────────────
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_PAGES).then((cache) => cache.addAll(PRECACHE_URLS))
    );
    self.skipWaiting();
});

// ─── Activate ────────────────────────────────────────────────────────────────
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) =>
            Promise.all(
                keys
                    .filter((key) => key !== CACHE_SHELL && key !== CACHE_IMAGES && key !== CACHE_PAGES)
                    .map((key) => caches.delete(key))
            )
        ).then(() => self.clients.claim())
    );
});

// ─── Message handler ─────────────────────────────────────────────────────────
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

// ─── Fetch ───────────────────────────────────────────────────────────────────
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Only handle same-origin GET requests
    if (request.method !== 'GET') return;
    if (url.origin !== self.location.origin) return;

    // Skip admin and API routes — always go straight to network
    if (url.pathname.startsWith('/admin') || url.pathname.startsWith('/api')) return;

    // ── Vite build assets: Cache-First ────────────────────────────────────
    if (url.pathname.startsWith('/build/')) {
        event.respondWith(cacheFirst(request, CACHE_SHELL));
        return;
    }

    // ── Images: Cache-First with network fallback + LRU eviction ─────────
    if (request.destination === 'image') {
        event.respondWith(imageStrategy(request));
        return;
    }

    // ── HTML pages: Network-First ─────────────────────────────────────────
    const acceptsHtml = request.headers.get('Accept')?.includes('text/html');
    if (acceptsHtml) {
        event.respondWith(networkFirst(request));
        return;
    }

    // ── Everything else: Network-Only ─────────────────────────────────────
    // No event.respondWith — browser handles it natively
});

// ─── Strategy: Cache-First ───────────────────────────────────────────────────
async function cacheFirst(request, cacheName) {
    const cache    = await caches.open(cacheName);
    const cached   = await cache.match(request);
    if (cached) return cached;

    try {
        const response = await fetch(request);
        if (response.ok) {
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        // No fallback for shell assets; let the browser show its error
        return new Response('Asset unavailable offline.', { status: 503 });
    }
}

// ─── Strategy: Image Cache-First + LRU eviction ──────────────────────────────
async function imageStrategy(request) {
    const cache  = await caches.open(CACHE_IMAGES);
    const cached = await cache.match(request);
    if (cached) return cached;

    try {
        const response = await fetch(request);
        if (response.ok) {
            await cache.put(request, response.clone());
            await evictImageCache(cache);
        }
        return response;
    } catch {
        // Return stale if we have it; otherwise nothing useful to return
        const stale = await cache.match(request);
        return stale ?? new Response('Image unavailable offline.', { status: 503 });
    }
}

async function evictImageCache(cache) {
    const keys = await cache.keys();
    if (keys.length > IMAGE_LIMIT) {
        // Delete oldest entries (they are in insertion order)
        const toDelete = keys.slice(0, keys.length - IMAGE_LIMIT);
        await Promise.all(toDelete.map((key) => cache.delete(key)));
    }
}

// ─── Strategy: Network-First ─────────────────────────────────────────────────
async function networkFirst(request) {
    const cache = await caches.open(CACHE_PAGES);

    try {
        const response = await fetch(request);
        if (response.ok) {
            cache.put(request, response.clone());
        }
        return response;
    } catch {
        const cached = await cache.match(request);
        if (cached) return cached;

        // Last resort: offline page
        const offline = await cache.match('/offline');
        return offline ?? new Response('You are offline.', {
            status: 503,
            headers: { 'Content-Type': 'text/plain' },
        });
    }
}
