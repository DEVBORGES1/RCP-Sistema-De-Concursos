<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaUsuario extends Model
{
    use HasFactory;

    protected $table = 'respostas_usuario';
    public $timestamps = false; // Ajustar se tiver campos de timestamp na tabela original

    protected $fillable = [
        'usuario_id',
        'questao_id',
        'resposta',
        'correta',
        'pontos_ganhos',
        'data_resposta' // Assumindo que existe, verificar schema se der erro
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function questao()
    {
        return $this->belongsTo(Questao::class, 'questao_id');
    }
}
