<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespostaJogo extends Model
{
    use HasFactory;

    protected $table = 'respostas_jogo';
    public $timestamps = false;

    protected $fillable = ['pergunta_id', 'texto', 'correta'];
}
