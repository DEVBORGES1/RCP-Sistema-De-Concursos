<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoaulaCategoria extends Model
{
    use HasFactory;

    protected $table = 'videoaulas_categorias';
    public $timestamps = false;

    protected $fillable = [
        'nome',
        'descricao',
        'cor',
        'icone',
        'ordem',
        'ativo',
    ];

    public function videoaulas()
    {
        return $this->hasMany(Videoaula::class, 'categoria_id');
    }
}
