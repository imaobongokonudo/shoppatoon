/**
 * Shoppaton Store - Service Worker
 * Enables offline cart functionality
 */

const CACHE_NAME = 'shoppaton-v1';
const STATIC_CACHE = 'shoppaton-static-v1';
const DYNAMIC_CACHE = 'shoppaton-dynamic-v1';

// Assets to cache immediately
const STATIC_ASSETS = [
    '/wp-content/plugins/shoppaton-store/assets/css/shoppaton-style.css',
    '/wp-content/plugins/shoppaton-store/assets/css/shoppaton-animations.css',
    '/wp-content/plugins/shoppaton-store/assets/js/shoppaton-main.js',
    '/wp-content/plugins/shoppaton-store/assets/images/logo.png'
];

// Pages to cache for offline access
const OFFLINE_PAGES = [
    '/',
    '/shop/',
    '/cart/',
    '/wishlist/'
];

// Install event - cache static assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then(cache => {
                console.log('Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate event - clean old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys()
            .then(keys => {
                return Promise.all(
                    keys.filter(key => key !== STATIC_CACHE && key !== DYNAMIC_CACHE)
                        .map(key => caches.delete(key))
                );
            })
            .then(() => self.clients.claim())
    );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', event => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip admin and login pages
    if (url.pathname.includes('/wp-admin') || url.pathname.includes('/wp-login')) {
        return;
    }

    // API requests - network first, then cache
    if (url.pathname.includes('/wp-json/') || url.pathname.includes('admin-ajax.php')) {
        event.respondWith(networkFirst(request));
        return;
    }

    // Static assets - cache first
    if (isStaticAsset(url.pathname)) {
        event.respondWith(cacheFirst(request));
        return;
    }

    // HTML pages - stale while revalidate
    if (request.headers.get('Accept').includes('text/html')) {
        event.respondWith(staleWhileRevalidate(request));
        return;
    }

    // Default - cache first
    event.respondWith(cacheFirst(request));
});

// Cache first strategy
async function cacheFirst(request) {
    const cached = await caches.match(request);
    if (cached) {
        return cached;
    }
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch (error) {
        return caches.match('/offline.html');
    }
}

// Network first strategy
async function networkFirst(request) {
    try {
        const response = await fetch(request);
        if (response.ok) {
            const cache = await caches.open(DYNAMIC_CACHE);
            cache.put(request, response.clone());
        }
        return response;
    } catch (error) {
        const cached = await caches.match(request);
        if (cached) {
            return cached;
        }
        // Return cached cart data if available
        if (request.url.includes('get_cart')) {
            return new Response(JSON.stringify({
                success: true,
                data: { cart: [], offline: true }
            }), {
                headers: { 'Content-Type': 'application/json' }
            });
        }
        throw error;
    }
}

// Stale while revalidate strategy
async function staleWhileRevalidate(request) {
    const cached = await caches.match(request);
    const fetchPromise = fetch(request)
        .then(response => {
            if (response.ok) {
                const cache = caches.open(DYNAMIC_CACHE);
                cache.then(c => c.put(request, response.clone()));
            }
            return response;
        })
        .catch(() => cached);
    
    return cached || fetchPromise;
}

// Check if URL is a static asset
function isStaticAsset(pathname) {
    const staticExtensions = ['.js', '.css', '.png', '.jpg', '.jpeg', '.gif', '.svg', '.woff', '.woff2', '.ttf'];
    return staticExtensions.some(ext => pathname.endsWith(ext));
}

// Background sync for cart
self.addEventListener('sync', event => {
    if (event.tag === 'sync-cart') {
        event.waitUntil(syncCart());
    }
});

// Sync cart data
async function syncCart() {
    const cartData = await getStoredCartData();
    if (cartData && cartData.length > 0) {
        try {
            const response = await fetch('/wp-admin/admin-ajax.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    action: 'shoppaton_sync_cart',
                    cart: JSON.stringify(cartData)
                })
            });
            if (response.ok) {
                await clearStoredCartData();
            }
        } catch (error) {
            console.error('Cart sync failed:', error);
        }
    }
}

// Get stored cart data from IndexedDB
async function getStoredCartData() {
    // Implementation would use IndexedDB
    return null;
}

// Clear stored cart data
async function clearStoredCartData() {
    // Implementation would clear IndexedDB
    return null;
}

// Push notification handler
self.addEventListener('push', event => {
    const data = event.data ? event.data.json() : {};
    
    const options = {
        body: data.body || 'You have a notification from Shoppaton Store',
        icon: '/wp-content/plugins/shoppaton-store/assets/images/logo.png',
        badge: '/wp-content/plugins/shoppaton-store/assets/images/logo.png',
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/'
        },
        actions: [
            { action: 'open', title: 'View' },
            { action: 'close', title: 'Close' }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(data.title || 'Shoppaton Store', options)
    );
});

// Notification click handler
self.addEventListener('notificationclick', event => {
    event.notification.close();
    
    if (event.action === 'open' || !event.action) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    }
});
