<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partida extends Model
{
    use HasFactory;

    // Campos created_at e updated_at mapeados para criado_em e atualizado_em ou padrão?
    // O script setup_game_db usou 'criado_em' e 'atualizado_em'. Precisamos configurar isso.
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';

    protected $table = 'partidas';

    protected $fillable = ['jogador1', 'jogador2', 'status', 'vencedor_id'];

    public function rodadas()
    {
        return $this->hasMany(Rodada::class);
    }

    public function player1()
    {
        return $this->belongsTo(User::class, 'jogador1');
    }

    public function player2()
    {
        return $this->belongsTo(User::class, 'jogador2');
    }

    public function pontos()
    {
        return $this->hasMany(PartidaPonto::class, 'partida_id');
    }
}
