<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Equipamento;
use App\Models\User;

class Calibracao extends Model
{
    protected $table = 'calibracoes';

    protected $fillable = [
        'equipamento_id',
        'user_id',
        'data_calibragem',
        'resultado',
        'observacoes',
        'data_registro',
    ];

    protected $casts = [
        'data_calibragem' => 'date',
        'data_registro' => 'datetime',
    ];

    public function equipamento(): BelongsTo
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}