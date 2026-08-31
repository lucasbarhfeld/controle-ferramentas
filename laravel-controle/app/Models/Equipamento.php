<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Equipamento extends Model
{
    public const STATUS_ATIVO = 'Ativo';

    public const STATUS_INATIVO = 'Inativo';

    public const VINCULO_SEM_RESPONSAVEL = 'sem_responsavel';

    public const VINCULO_USUARIO = 'usuario';

    public const VINCULO_ARMARIO_COLETIVO = 'armario_coletivo';

    public const VINCULO_CENTRO_CUSTO = 'centro_custo';

    public const LIMITE_CRITICO_DIAS = 5;

    public const LIMITE_ATENCAO_DIAS = 15;

    protected $fillable = [
        'codigo',
        'patrimonio',
        'nome',
        'fabricante',
        'modelo',
        'localizacao',
        'faixa_uso',
        'foto_path',
        'status',
        'tipo_vinculacao',
        'usuario_responsavel_id',
        'centro_custo_id',
        'ultima_calibragem',
        'periodo_calibragem_dias',
    ];

    protected $casts = [
        'ultima_calibragem' => 'date',
    ];

    public function usuarioResponsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_responsavel_id');
    }

    public function centroCusto(): BelongsTo
    {
        return $this->belongsTo(CentroCusto::class);
    }

    public function calibracoes(): HasMany
    {
        return $this->hasMany(Calibracao::class);
    }

    public function statusControle(): HasOne
    {
        return $this->hasOne(EquipamentoStatusControle::class);
    }

    public function scopeAtivos(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ATIVO);
    }

    public function scopeInativos(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_INATIVO);
    }

    public function getAtivoAttribute(): bool
    {
        return $this->status === self::STATUS_ATIVO;
    }

    public function getProximaCalibragemAttribute()
    {
        if (! $this->ultima_calibragem) {
            return null;
        }

        return $this->ultima_calibragem->copy()->addDays($this->periodo_calibragem_dias);
    }

    public function getTipoVinculacaoEfetivoAttribute(): string
    {
        if ($this->tipo_vinculacao) {
            return $this->tipo_vinculacao;
        }

        return $this->usuario_responsavel_id
            ? self::VINCULO_USUARIO
            : self::VINCULO_SEM_RESPONSAVEL;
    }

    public function getVinculoLabelAttribute(): string
    {
        return match ($this->tipo_vinculacao_efetivo) {
            self::VINCULO_USUARIO => $this->usuarioResponsavel?->name ?? 'Sem responsável',
            self::VINCULO_ARMARIO_COLETIVO => 'Armário coletivo',
            self::VINCULO_CENTRO_CUSTO => $this->centroCusto?->label ?? 'Centro de custo não definido',
            default => 'Sem responsável',
        };
    }

    public function getVinculoTipoLabelAttribute(): string
    {
        return match ($this->tipo_vinculacao_efetivo) {
            self::VINCULO_USUARIO => 'Pessoa responsável',
            self::VINCULO_ARMARIO_COLETIVO => 'Uso coletivo',
            self::VINCULO_CENTRO_CUSTO => 'Centro de custo',
            default => 'Sem vinculação',
        };
    }

    public static function nomePareceVinculoOrganizacional(?string $nome): bool
    {
        $normalizado = Str::of((string) $nome)->lower()->ascii()->squish()->toString();
        $compacto = preg_replace('/[^a-z0-9]+/', '', $normalizado);

        return in_array($normalizado, ['armario coletivo', 'armario de uso coletivo'], true)
            || preg_match('/^(?:cc|centrodecusto)\d+$/', $compacto) === 1;
    }

    public function getDiasRestantesAttribute()
    {
        if (! $this->proxima_calibragem) {
            return null;
        }

        return Carbon::today()->diffInDays($this->proxima_calibragem, false);
    }

    public function getStatusCalibragemKeyAttribute(): string
    {
        if (! $this->ultima_calibragem) {
            return 'sem-calibracao';
        }

        if ($this->dias_restantes < 0) {
            return 'vencida';
        }

        if ($this->dias_restantes <= self::LIMITE_CRITICO_DIAS) {
            return 'critica';
        }

        if ($this->dias_restantes <= self::LIMITE_ATENCAO_DIAS) {
            return 'atencao';
        }

        return 'em-dia';
    }

    public function getStatusCalibragemAttribute(): string
    {
        return match ($this->status_calibragem_key) {
            'sem-calibracao' => 'Sem calibração',
            'vencida' => 'Vencida',
            'critica' => 'Crítica',
            'atencao' => 'Atenção',
            default => 'Em dia',
        };
    }
}
