const CACHE  = 'bartaro-v1';
const STATIC = [
    '/',
    '/favicon.svg',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css',
    'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css',
    'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap',
];

// Install: cache static shell
self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(CACHE).then(c => c.addAll(STATIC)).then(() => self.skipWaiting())
    );
});

// Activate: delete old caches
self.addEventListener('activate', e => {
    e.waitUntil(
        caches.keys()
            .then(keys => Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k))))
            .then(() => self.clients.claim())
    );
});

// Fetch: network-first for HTML, cache-first for assets
self.addEventListener('fetch', e => {
    const { request } = e;

    // Only handle GET requests for same-origin or CDN assets
    if (request.method !== 'GET') return;

    const url = new URL(request.url);
    const isAsset = url.hostname !== location.hostname ||
                    request.destination === 'style' ||
                    request.destination === 'script' ||
                    request.destination === 'font'   ||
                    request.destination === 'image';

    if (isAsset) {
        // Cache-first
        e.respondWith(
            caches.match(request).then(cached => cached || fetch(request).then(res => {
                if (res.ok) {
                    const clone = res.clone();
                    caches.open(CACHE).then(c => c.put(request, clone));
                }
                return res;
            }))
        );
    } else {
        // Network-first (HTML pages — always fresh)
        e.respondWith(
            fetch(request).catch(() => caches.match(request))
        );
    }
});
