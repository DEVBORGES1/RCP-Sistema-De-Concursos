<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoaulaProgresso extends Model
{
    use HasFactory;

    protected $table = 'videoaulas_progresso';
    public $timestamps = false; // Ajustar se a tabela tiver timestamps

    protected $fillable = [
        'usuario_id',
        'videoaula_id',
        'concluida',
        'tempo_assistido', // se existir
        'data_conclusao',
    ];

    public function videoaula()
    {
        return $this->belongsTo(Videoaula::class, 'videoaula_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
