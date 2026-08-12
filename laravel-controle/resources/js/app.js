

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.data('pushNotifications', (config) => ({
    supported: false,
    enabled: false,
    loading: true,
    message: '',
    registration: null,
    subscription: null,

    async init() {
        this.supported = 'serviceWorker' in navigator
            && 'PushManager' in window
            && 'Notification' in window
            && Boolean(config.publicKey);

        if (!this.supported) {
            this.loading = false;
            this.message = 'Notificações não disponíveis neste navegador.';
            return;
        }

        try {
            this.registration = await navigator.serviceWorker.ready;
            this.subscription = await this.registration.pushManager.getSubscription();
            this.enabled = Boolean(this.subscription);

            if (this.subscription) {
                await this.request(config.subscribeUrl, 'POST', this.subscription.toJSON());
            }

            if (Notification.permission === 'denied') {
                this.message = 'Permissão bloqueada. Libere as notificações nas configurações do navegador.';
            }
        } catch (error) {
            this.message = 'Não foi possível consultar as notificações deste aparelho.';
        } finally {
            this.loading = false;
        }
    },

    buttonLabel() {
        if (this.loading) return 'Verificando...';
        return this.enabled ? 'Desativar neste aparelho' : 'Ativar neste aparelho';
    },

    async toggle() {
        if (!this.supported || this.loading) return;

        this.loading = true;
        this.message = '';

        try {
            if (this.enabled) {
                await this.disable();
            } else {
                await this.enable();
            }
        } catch (error) {
            this.message = error.message || 'Não foi possível alterar as notificações.';
        } finally {
            this.loading = false;
        }
    },

    async enable() {
        const permission = await Notification.requestPermission();

        if (permission !== 'granted') {
            throw new Error('A permissão de notificações não foi concedida.');
        }

        this.registration ??= await navigator.serviceWorker.ready;
        this.subscription = await this.registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: this.urlBase64ToUint8Array(config.publicKey),
        });

        try {
            await this.request(config.subscribeUrl, 'POST', this.subscription.toJSON());
        } catch (error) {
            await this.subscription.unsubscribe();
            this.subscription = null;
            throw error;
        }

        this.enabled = true;
        this.message = 'Este aparelho receberá mudanças de estado das suas ferramentas.';
    },

    async disable() {
        this.subscription ??= await this.registration.pushManager.getSubscription();

        if (this.subscription) {
            await this.request(config.unsubscribeUrl, 'DELETE', {
                endpoint: this.subscription.endpoint,
            });
            await this.subscription.unsubscribe();
        }

        this.subscription = null;
        this.enabled = false;
        this.message = 'Notificações desativadas neste aparelho.';
    },

    async testLocal() {
        if (!this.enabled || this.loading) return;

        this.loading = true;
        this.message = '';

        try {
            this.registration ??= await navigator.serviceWorker.ready;
            await this.registration.showNotification('Teste de notificações', {
                body: 'Se você está vendo isto, o Android e o aplicativo estão autorizados a exibir alertas.',
                icon: new URL('ferramentas-android-192-v10.png', this.registration.scope).href,
                badge: new URL('ferramentas-favicon-v3.png', this.registration.scope).href,
                tag: `controle-ferramentas-local-${Date.now()}`,
                data: {
                    url: new URL('dashboard', this.registration.scope).href,
                },
            });
            this.message = 'Teste local solicitado. Verifique a barra de notificações.';
        } catch (error) {
            this.message = `O Android recusou o teste local: ${error.message || 'erro desconhecido'}`;
        } finally {
            this.loading = false;
        }
    },

    async request(url, method, body) {
        const response = await fetch(url, {
            method,
            credentials: 'same-origin',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(body),
        });

        if (!response.ok) {
            const data = await response.json().catch(() => ({}));
            throw new Error(data.message || 'O servidor não conseguiu salvar esta configuração.');
        }

        return response.json();
    },

    urlBase64ToUint8Array(value) {
        const padding = '='.repeat((4 - value.length % 4) % 4);
        const base64 = (value + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);

        return Uint8Array.from([...rawData].map((character) => character.charCodeAt(0)));
    },
}));

Alpine.start();

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        const manifestUrl = document.querySelector('link[rel="manifest"]')?.href;
        const swUrl = new URL('sw.js', manifestUrl || document.baseURI);
        navigator.serviceWorker.register(swUrl, {
            updateViaCache: 'none',
        }).catch(() => {});
    });
}
