<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\WebPushService;
use Illuminate\Console\Command;

class SendTestPushNotification extends Command
{
    protected $signature = 'notifications:test {username=admin : Nome de login do destinatário}';

    protected $description = 'Envia uma notificação Web Push de teste para um usuário';

    public function handle(WebPushService $webPush): int
    {
        $user = User::where('username', $this->argument('username'))->first();

        if (! $user) {
            $this->error('Usuário não encontrado. Informe o nome usado no login.');

            return self::FAILURE;
        }

        $subscriptionCount = $user->pushSubscriptions()->count();

        if ($subscriptionCount === 0) {
            $this->error("O usuário {$user->username} não possui nenhum aparelho inscrito.");

            return self::FAILURE;
        }

        $result = $webPush->sendToUsers(collect([$user]), [
            'title' => 'Notificações ativadas',
            'body' => "Teste enviado para {$user->name}. O celular está pronto para receber alertas de calibração.",
            'icon' => asset('ferramentas-android-192-v10.png'),
            'badge' => asset('ferramentas-favicon-v3.png'),
            'url' => route('dashboard'),
            'tag' => 'controle-ferramentas-teste',
            'data' => ['teste' => true],
        ]);

        $this->info(
            "{$subscriptionCount} aparelho(s) inscrito(s); {$result['sent']} envio(s); "
            ."{$result['failed']} falha(s); {$result['expired']} inscrição(ões) expirada(s)."
        );

        return $result['sent'] > 0 ? self::SUCCESS : self::FAILURE;
    }
}
