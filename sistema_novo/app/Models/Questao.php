<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Questao extends Model
{
    use HasFactory;

    protected $table = 'questoes';
    public $timestamps = true;

    protected $fillable = [
        'edital_id',
        'disciplina_id',
        'enunciado',
        'alternativa_a',
        'alternativa_b',
        'alternativa_c',
        'alternativa_d',
        'alternativa_e',
        'alternativa_correta',
    ];

    public function simulados()
    {
        return $this->belongsToMany(Simulado::class, 'simulados_questoes', 'questao_id', 'simulado_id')
                    ->withPivot('resposta_usuario', 'correta');
    }

    public function edital()
    {
        return $this->belongsTo(Edital::class, 'edital_id');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }

    public function respostasUsuario()
    {
        return $this->hasMany(RespostaUsuario::class, 'questao_id');
    }
}

