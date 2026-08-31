<?php

namespace App\Console\Commands;

use App\Models\Equipamento;
use App\Models\EquipamentoStatusControle;
use App\Models\User;
use App\Services\WebPushService;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class CheckEquipmentCalibrationStatuses extends Command
{
    protected $signature = 'notifications:check-calibration-statuses
                            {--notify-current : Notifica estados de alerta ainda sem controle inicial}';

    protected $description = 'Detecta mudanças no estado das calibrações e envia notificações Web Push';

    private const NOTIFIABLE_STATUSES = ['atencao', 'critica', 'vencida'];

    public function handle(WebPushService $webPush): int
    {
        if (! $webPush->isConfigured()) {
            $this->error('Configure VAPID_SUBJECT, VAPID_PUBLIC_KEY e VAPID_PRIVATE_KEY antes de executar.');

            return self::FAILURE;
        }

        $checked = 0;
        $transitions = 0;
        $sent = 0;

        Equipamento::ativos()
            ->with(['usuarioResponsavel', 'centroCusto'])
            ->orderBy('id')
            ->chunkById(100, function ($equipamentos) use ($webPush, &$checked, &$transitions, &$sent) {
                foreach ($equipamentos as $equipamento) {
                    $checked++;
                    $currentStatus = $equipamento->status_calibragem_key;
                    $control = EquipamentoStatusControle::firstOrNew([
                        'equipamento_id' => $equipamento->id,
                    ]);

                    $isNew = ! $control->exists;
                    $changed = ! $isNew && $control->ultimo_status !== $currentStatus;
                    $shouldNotify = in_array($currentStatus, self::NOTIFIABLE_STATUSES, true)
                        && ($changed || ($isNew && $this->option('notify-current')));

                    if ($changed) {
                        $transitions++;
                    }

                    if ($shouldNotify) {
                        $result = $webPush->sendToUsers(
                            $this->recipientsFor($equipamento),
                            $this->payloadFor($equipamento),
                        );
                        $sent += $result['sent'];

                        if ($result['sent'] > 0) {
                            $control->ultima_notificacao_em = now();
                        }
                    }

                    $control->ultimo_status = $currentStatus;
                    $control->save();
                }
            });

        $this->info("{$checked} ferramenta(s) verificadas; {$transitions} transição(ões); {$sent} notificação(ões) enviada(s).");

        return self::SUCCESS;
    }

    private function recipientsFor(Equipamento $equipamento): Collection
    {
        $recipients = User::where('perfil', 'admin')->get();

        if (
            $equipamento->tipo_vinculacao_efetivo === Equipamento::VINCULO_USUARIO
            && $equipamento->usuarioResponsavel
        ) {
            $recipients->push($equipamento->usuarioResponsavel);
        }

        return $recipients->unique('id')->values();
    }

    private function payloadFor(Equipamento $equipamento): array
    {
        $responsavel = $equipamento->vinculo_label;
        $patrimonio = $equipamento->patrimonio ?: 'Não informado';
        $vencimento = $equipamento->proxima_calibragem?->format('d/m/Y') ?? 'Não informado';

        return [
            'title' => "{$equipamento->nome} entrou em {$equipamento->status_calibragem}",
            'body' => "Responsável: {$responsavel} • Patrimônio: {$patrimonio} • Vencimento: {$vencimento}",
            'icon' => asset('ferramentas-android-192-v10.png'),
            'badge' => asset('ferramentas-favicon-v3.png'),
            'url' => route('equipamentos.show', $equipamento),
            'tag' => "equipamento-status-{$equipamento->id}",
            'data' => [
                'equipamento_id' => $equipamento->id,
                'estado' => $equipamento->status_calibragem,
                'responsavel' => $responsavel,
                'patrimonio' => $patrimonio,
                'vencimento' => $vencimento,
            ],
        ];
    }
}
