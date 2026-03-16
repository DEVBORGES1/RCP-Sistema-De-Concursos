<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Redacao extends Model
{
    use HasFactory;

    protected $table = 'redacoes';

    protected $fillable = [
        'usuario_id',
        'tema_id',
        'texto_enviado',
        'nota_total',
        'criterios_nota',
        'feedback_ia'
    ];

    protected $casts = [
        'criterios_nota' => 'array',
    ];

    public function tema()
    {
        return $this->belongsTo(RedacaoTema::class, 'tema_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
