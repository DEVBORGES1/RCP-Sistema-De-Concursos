<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RedacaoTema extends Model
{
    use HasFactory;

    protected $table = 'redacao_temas';

    protected $fillable = [
        'titulo',
        'texto_motivador',
        'banca_referencia',
        'ativo'
    ];
}
