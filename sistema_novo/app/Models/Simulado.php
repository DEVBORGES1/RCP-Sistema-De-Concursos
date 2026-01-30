<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simulado extends Model
{
    use HasFactory;

    protected $table = 'simulados';
    
    // Mapeamento de timestamps customizados
    const CREATED_AT = 'data_criacao';
    const UPDATED_AT = null; // Tabela parece não ter updated_at

    protected $fillable = [
        'usuario_id',
        'nome',
        'questoes_total',
        'questoes_corretas',
        'pontuacao_final',
        'tempo_gasto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function questoes()
    {
        return $this->belongsToMany(Questao::class, 'simulados_questoes', 'simulado_id', 'questao_id')
                    ->withPivot('resposta_usuario', 'correta');
    }
}
