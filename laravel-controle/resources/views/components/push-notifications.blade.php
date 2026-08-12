<div
    {{ $attributes->class(['app-status-card']) }}
    x-data="pushNotifications({
        publicKey: @js(config('services.web_push.public_key')),
        subscribeUrl: @js(route('push-subscriptions.store')),
        unsubscribeUrl: @js(route('push-subscriptions.destroy')),
    })"
>
    <div class="flex items-start gap-3">
        <div class="app-accent-surface app-accent-text grid h-10 w-10 shrink-0 place-items-center rounded-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
            </svg>
        </div>
        <div class="min-w-0 flex-1">
            <p class="font-black text-white">Notificações</p>
            <p class="mt-1 text-xs leading-relaxed text-slate-400">Receba um aviso quando uma ferramenta entrar em atenção, crítica ou vencida.</p>
        </div>
    </div>

    <button
        type="button"
        class="app-button app-button-secondary mt-3 w-full"
        :disabled="loading || !supported"
        @click="toggle()"
        x-text="buttonLabel()"
    ></button>
    <button
        x-show="enabled"
        type="button"
        class="app-button app-button-secondary mt-2 w-full"
        :disabled="loading"
        @click="testLocal()"
    >Testar neste aparelho</button>
    <p x-show="message" x-text="message" class="mt-2 text-xs leading-relaxed text-slate-400" aria-live="polite"></p>
</div>
