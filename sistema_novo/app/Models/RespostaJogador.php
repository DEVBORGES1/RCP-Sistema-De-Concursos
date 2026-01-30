<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaJogador extends Model
{
    use HasFactory;

    protected $table = 'respostas_jogadores';
    public $timestamps = false;

    // 'data_resposta' handles created_at logic roughly
    const CREATED_AT = 'data_resposta';

    protected $fillable = ['rodada_id', 'usuario_id', 'resposta_id', 'correta', 'pontos_ganhos', 'tempo_resposta', 'data_resposta'];

    public function rodada()
    {
        return $this->belongsTo(Rodada::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
