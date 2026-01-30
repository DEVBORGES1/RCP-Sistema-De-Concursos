<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property string $senha_hash
 * @property boolean $is_admin
 * @property string|null $foto_perfil
 * @property string|null $area_interesse
 * @property string|null $escolaridade
 * @property string|null $cargos_alvo
 * @property string|null $linkedin
 * @property string|null $biografia
 * @property string|null $tema_preferencia
 * @property int $pontos
 * @property int $xp_atual
 * @property int $nivel
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'usuarios';

    /**
     * Indica se o modelo deve usar timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nome',
        'email',
        'senha_hash',
        'is_admin',
        'foto_perfil',
        'area_interesse',
        'escolaridade',
        'cargos_alvo',
        'linkedin',
        'biografia',
        'tema_preferencia',
        'pontos',
        'xp_atual',
        'nivel',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'senha_hash',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'senha_hash' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    /**
     * Sobrescreve o nome da coluna de senha para autenticação.
     */
    public function getAuthPassword()
    {
        return $this->senha_hash;
    }

    /**
     * Get the column name for the password.
     *
     * @return string
     */
    public function getAuthPasswordName()
    {
        return 'senha_hash';
    }
}
