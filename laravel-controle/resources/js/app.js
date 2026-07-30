

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        const swUrl = new URL('sw-v8.js', document.baseURI);
        navigator.serviceWorker.register(swUrl, {
            updateViaCache: 'none',
        }).catch(() => {});
    });
}
