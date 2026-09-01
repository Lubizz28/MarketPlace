const CACHE_NAME = 'sulastikajaya-pwa-v1.0.0';
const OFFLINE_URL = '/offline';

const PRECACHE_ASSETS = [
    '/',
    '/catalog',
    '/offline',
    '/favicon.ico',
    '/manifest.webmanifest',
];

// Install Event: Precaches core assets and offline fallback
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                console.warn('[PWA SW] Precache warning:', err);
            });
        }).then(() => self.skipWaiting())
    );
});

// Activate Event: Cleans up obsolete cache versions
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        console.log('[PWA SW] Removing old cache:', cache);
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch Event: Network-first for pages, Cache-first / Stale-while-revalidate for assets
self.addEventListener('fetch', (event) => {
    // Only handle GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    const requestUrl = new URL(event.request.url);

    // Ignore non-http requests (e.g. chrome-extension://)
    if (!requestUrl.protocol.startsWith('http')) {
        return;
    }

    // Handle HTML Navigation requests (Network-first with offline fallback)
    if (event.request.mode === 'navigate') {
        event.respondWith(
            fetch(event.request)
                .then((networkResponse) => {
                    // Cache successful navigation responses
                    if (networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(event.request, responseClone);
                        });
                    }
                    return networkResponse;
                })
                .catch(async () => {
                    // Try to retrieve from cache first
                    const cachedResponse = await caches.match(event.request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    // Otherwise return offline page
                    const offlineFallback = await caches.match(OFFLINE_URL);
                    return offlineFallback || new Response('Offline - MedinaStyle', {
                        status: 503,
                        statusText: 'Service Unavailable',
                        headers: { 'Content-Type': 'text/plain' }
                    });
                })
        );
        return;
    }

    // Handle Static Assets (Images, Fonts, CSS, JS) - Cache-first with network refresh
    if (
        requestUrl.pathname.match(/\.(css|js|woff|woff2|ttf|svg|png|jpg|jpeg|webp|avif|ico)$/) ||
        event.request.destination === 'style' ||
        event.request.destination === 'script' ||
        event.request.destination === 'image' ||
        event.request.destination === 'font'
    ) {
        event.respondWith(
            caches.match(event.request).then((cachedResponse) => {
                if (cachedResponse) {
                    // Fetch in background to update cache
                    fetch(event.request).then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            caches.open(CACHE_NAME).then((cache) => {
                                cache.put(event.request, networkResponse);
                            });
                        }
                    }).catch(() => {});
                    return cachedResponse;
                }

                // If not cached, fetch from network and cache it
                return fetch(event.request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const responseClone = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => {
                            cache.put(event.request, responseClone);
                        });
                    }
                    return networkResponse;
                });
            })
        );
        return;
    }

    // Default fetch for other requests
    event.respondWith(
        fetch(event.request).catch(() => caches.match(event.request))
    );
});
