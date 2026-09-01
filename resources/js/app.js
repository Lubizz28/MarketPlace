import './bootstrap';
import './analytics';

// Register PWA Service Worker
if ('serviceWorker' in navigator && window.location.protocol === 'https:' || window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js')
            .then((registration) => {
                // Service worker successfully registered
                console.log('[PWA] ServiceWorker registered with scope:', registration.scope);
            })
            .catch((error) => {
                console.warn('[PWA] ServiceWorker registration failed:', error);
            });
    });
}
