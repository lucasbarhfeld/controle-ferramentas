<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CentroCusto extends Model
{
    protected $table = 'centros_custo';

    protected $fillable = [
        'codigo',
        'nome',
        'descricao',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function equipamentos(): HasMany
    {
        return $this->hasMany(Equipamento::class);
    }

    public function getLabelAttribute(): string
    {
        if ($this->nome && strcasecmp($this->nome, $this->codigo) !== 0) {
            return "{$this->codigo} - {$this->nome}";
        }

        return $this->codigo;
    }
}
