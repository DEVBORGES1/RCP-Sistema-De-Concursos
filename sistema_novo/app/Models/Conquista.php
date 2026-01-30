<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conquista extends Model
{
    use HasFactory;

    protected $table = 'conquistas';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'icone',
        'descricao',
        'tipo',
        'pontos_necessarios',
        'pontos', // Pontos bônus que dá?
    ];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuarios_conquistas', 'conquista_id', 'usuario_id')
                    ->withPivot('data_conquista');
    }
}
