<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Equipamento;
use App\Models\Calibracao;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'perfil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function equipamentosResponsaveis(): HasMany
    {
        return $this->hasMany(Equipamento::class, 'usuario_responsavel_id');
    }

    public function calibracoes(): HasMany
    {
        return $this->hasMany(Calibracao::class, 'user_id');
    }

    public function isAdmin(): bool
    {
        return $this->perfil === 'admin';
    }
}
