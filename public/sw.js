/**
 * Service Worker - E-Voting PILKETOS
 * Progressive Web App (PWA)
 */

const CACHE_NAME = 'pilketos-pwa-v1';
const OFFLINE_URL = '/offline.html';

const PRECACHE_ASSETS = [
  '/',
  '/manifest.json',
  '/offline.html',
  '/icons/icon-192x192.png',
  '/icons/icon-512x512.png',
  '/icons/apple-touch-icon.png',
  '/assets/images/logo-smk.png',
  '/assets/images/logo-osis.png',
  '/assets/images/logo-mpk.png'
];

// Install Event: Pre-cache core shell & offline page
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return cache.addAll(PRECACHE_ASSETS).catch((err) => {
        console.warn('[SW] Precache partial error:', err);
      });
    }).then(() => self.skipWaiting())
  );
});

// Activate Event: Clean up stale caches & claim clients
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            console.log('[SW] Deleting old cache:', cache);
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event Strategy
self.addEventListener('fetch', (event) => {
  const req = event.request;
  const url = new URL(req.url);

  // Skip non-GET requests (e.g. POST for voting / login)
  if (req.method !== 'GET') {
    return;
  }

  // 1. Navigation requests (HTML pages) -> Network First, Fallback to offline.html
  if (req.mode === 'navigate') {
    event.respondWith(
      fetch(req)
        .then((networkRes) => {
          // If valid response, don't cache voting/auth pages to avoid stale sessions
          return networkRes;
        })
        .catch(async () => {
          const cache = await caches.open(CACHE_NAME);
          const cachedResponse = await cache.match(req);
          return cachedResponse || (await cache.match(OFFLINE_URL));
        })
    );
    return;
  }

  // 2. Realtime APIs & Livewire internal endpoints -> Network Only (never serve stale vote counts)
  if (
    url.pathname.startsWith('/api/') ||
    url.pathname.startsWith('/livewire/') ||
    url.pathname.includes('livewire')
  ) {
    event.respondWith(fetch(req));
    return;
  }

  // 3. Static assets (images, fonts, scripts, styles) -> Stale While Revalidate
  if (
    url.pathname.match(/\.(png|jpg|jpeg|svg|webp|gif|ico|css|js|woff|woff2|ttf|eot)$/) ||
    url.hostname.includes('fonts.googleapis.com') ||
    url.hostname.includes('fonts.gstatic.com') ||
    url.hostname.includes('cdnjs.cloudflare.com') ||
    url.hostname.includes('cdn.tailwindcss.com')
  ) {
    event.respondWith(
      caches.match(req).then((cachedRes) => {
        const fetchPromise = fetch(req).then((networkRes) => {
          if (networkRes && networkRes.status === 200) {
            const resClone = networkRes.clone();
            caches.open(CACHE_NAME).then((cache) => {
              cache.put(req, resClone);
            });
          }
          return networkRes;
        }).catch(() => cachedRes);

        return cachedRes || fetchPromise;
      })
    );
    return;
  }

  // Default: Network with Cache Fallback
  event.respondWith(
    fetch(req).catch(() => caches.match(req))
  );
});
