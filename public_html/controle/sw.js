const CACHE_NAME = 'controle-ferramentas-v14';
const STATIC_ASSETS = [
  './manifest.webmanifest',
  './ferramentas-android-192-v10.png',
  './ferramentas-android-512-v10.png',
  './ferramentas-android-maskable-512-v10.png',
  './ferramentas-favicon-v3.png',
  './ferramentas-favicon-v3.ico',
  './ferramentas-apple-v4.png',
  './logo-lippel.svg'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => cache.addAll(STATIC_ASSETS))
      .then(() => self.skipWaiting())
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys()
      .then((keys) => Promise.all(
        keys.filter((key) => key !== CACHE_NAME).map((key) => caches.delete(key))
      ))
      .then(() => self.clients.claim())
  );
});

self.addEventListener('fetch', (event) => {
  const request = event.request;

  if (request.method !== 'GET') {
    return;
  }

  const url = new URL(request.url);

  if (url.origin !== self.location.origin) {
    return;
  }

  if (url.pathname.includes('/build/') || STATIC_ASSETS.some((asset) => url.pathname.endsWith(asset.replace('./', '/')))) {
    event.respondWith(
      caches.match(request).then((cached) => {
        if (cached) {
          return cached;
        }

        return fetch(request).then((response) => {
          const copy = response.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
          return response;
        });
      })
    );
  }
});

self.addEventListener('push', (event) => {
  let payload = {};

  try {
    payload = event.data ? event.data.json() : {};
  } catch (error) {
    payload = { body: event.data ? event.data.text() : '' };
  }

  const notificationData = {
    ...(payload.data || {}),
    url: payload.url || new URL('./dashboard', self.registration.scope).href,
  };

  event.waitUntil(
    self.registration.showNotification(payload.title || 'Controle de ferramentas', {
      body: payload.body || 'Uma ferramenta mudou de estado.',
      icon: payload.icon || './ferramentas-android-192-v10.png',
      badge: payload.badge || './ferramentas-favicon-v3.png',
      tag: payload.tag || 'controle-ferramentas-status',
      renotify: true,
      timestamp: payload.timestamp || Date.now(),
      vibrate: [180, 80, 180],
      data: notificationData,
    })
  );
});

self.addEventListener('notificationclick', (event) => {
  event.notification.close();

  const targetUrl = new URL(
    event.notification.data?.url || './dashboard',
    self.registration.scope
  ).href;

  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(async (windowClients) => {
      for (const client of windowClients) {
        if (client.url === targetUrl && 'focus' in client) {
          return client.focus();
        }
      }

      const existingClient = windowClients[0];
      if (existingClient && 'navigate' in existingClient) {
        await existingClient.navigate(targetUrl);
        return existingClient.focus();
      }

      return clients.openWindow(targetUrl);
    })
  );
});
