<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videoaula extends Model
{
    use HasFactory;

    protected $table = 'videoaulas';
    public $timestamps = false; // Assumindo false por enquanto, ajustar se existir created_at

    protected $fillable = [
        'categoria_id',
        'titulo',
        'descricao',
        'url_video',
        'duracao',
        'ordem',
        'ativo',
    ];

    public function categoria()
    {
        return $this->belongsTo(VideoaulaCategoria::class, 'categoria_id');
    }

    public function progresso()
    {
        return $this->hasMany(VideoaulaProgresso::class, 'videoaula_id');
    }
}
