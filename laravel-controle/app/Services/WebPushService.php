<?php

namespace App\Services;

use App\Models\PushSubscription as PushSubscriptionModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;
use RuntimeException;
use Throwable;

class WebPushService
{
    public function isConfigured(): bool
    {
        return filled(config('services.web_push.public_key'))
            && filled(config('services.web_push.private_key'))
            && filled(config('services.web_push.subject'));
    }

    /**
     * @return array{sent: int, failed: int, expired: int}
     */
    public function sendToUsers(Collection $users, array $payload): array
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('As chaves VAPID não estão configuradas.');
        }

        $subscriptions = PushSubscriptionModel::whereIn('user_id', $users->pluck('id')->all())->get();
        $result = ['sent' => 0, 'failed' => 0, 'expired' => 0];

        if ($subscriptions->isEmpty()) {
            return $result;
        }

        $webPush = new WebPush([
            'VAPID' => [
                'subject' => config('services.web_push.subject'),
                'publicKey' => config('services.web_push.public_key'),
                'privateKey' => config('services.web_push.private_key'),
            ],
        ], [
            'TTL' => 86400,
            'urgency' => 'high',
            'contentType' => 'application/json',
        ]);
        $webPush->setReuseVAPIDHeaders(true);

        $payloadJson = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);

        foreach ($subscriptions as $storedSubscription) {
            try {
                $webPush->queueNotification(
                    Subscription::create([
                        'endpoint' => $storedSubscription->endpoint,
                        'keys' => [
                            'p256dh' => $storedSubscription->public_key,
                            'auth' => $storedSubscription->auth_token,
                        ],
                        'contentEncoding' => $storedSubscription->content_encoding,
                    ]),
                    $payloadJson,
                );
            } catch (Throwable $exception) {
                $result['failed']++;
                Log::warning('Inscrição Web Push inválida.', [
                    'subscription_id' => $storedSubscription->id,
                    'error' => $exception->getMessage(),
                ]);
            }
        }

        foreach ($webPush->flush() as $report) {
            if ($report->isSuccess()) {
                $result['sent']++;
                continue;
            }

            $result['failed']++;

            if ($report->isSubscriptionExpired()) {
                PushSubscriptionModel::where('endpoint_hash', hash('sha256', $report->getEndpoint()))->delete();
                $result['expired']++;
            }

            Log::warning('Falha ao enviar notificação Web Push.', [
                'endpoint_hash' => hash('sha256', $report->getEndpoint()),
                'reason' => $report->getReason(),
            ]);
        }

        return $result;
    }
}
