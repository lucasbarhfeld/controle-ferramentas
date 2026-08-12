<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipamentoStatusControle extends Model
{
    protected $fillable = [
        'equipamento_id',
        'ultimo_status',
        'ultima_notificacao_em',
    ];

    protected $casts = [
        'ultima_notificacao_em' => 'datetime',
    ];

    public function equipamento(): BelongsTo
    {
        return $this->belongsTo(Equipamento::class);
    }
}
