

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        const swUrl = new URL('sw.js', document.baseURI);
        navigator.serviceWorker.register(swUrl).catch(() => {});
    });
}
