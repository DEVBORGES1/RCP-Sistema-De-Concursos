<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerguntaJogo extends Model
{
    use HasFactory;

    protected $table = 'perguntas_jogo';
    public $timestamps = false; // Tem coluna 'criado_em' via DB default, mas Eloquent espera timestamps padrão

    protected $fillable = ['categoria_id', 'pergunta', 'dificuldade'];

    public function categoria()
    {
        return $this->belongsTo(CategoriaJogo::class, 'categoria_id');
    }

    public function respostas()
    {
        return $this->hasMany(RespostaJogo::class, 'pergunta_id');
    }
}
