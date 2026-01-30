<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaJogo extends Model
{
    use HasFactory;

    protected $table = 'categorias_jogo';
    public $timestamps = false;

    protected $fillable = ['nome', 'icone', 'imagem', 'cor'];

    public function perguntas()
    {
        return $this->hasMany(PerguntaJogo::class, 'categoria_id');
    }
}
