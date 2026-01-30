<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rodada extends Model
{
    use HasFactory;

    protected $table = 'rodadas';
    public $timestamps = false; // Rodada tem 'inicio' e 'fim' gerenciados manualmente/logic

    protected $fillable = ['partida_id', 'pergunta_id', 'numero_rodada', 'inicio', 'fim', 'ativa'];

    protected $casts = [
        'inicio' => 'datetime',
        'fim' => 'datetime',
        'ativa' => 'boolean'
    ];

    public function partida()
    {
        return $this->belongsTo(Partida::class);
    }

    public function pergunta()
    {
        return $this->belongsTo(PerguntaJogo::class, 'pergunta_id');
    }

    public function respostasJogadores()
    {
        return $this->hasMany(RespostaJogador::class, 'rodada_id');
    }
}
