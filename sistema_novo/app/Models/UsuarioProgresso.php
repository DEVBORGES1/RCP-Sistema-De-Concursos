<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioProgresso extends Model
{
    use HasFactory;

    protected $table = 'usuarios_progresso';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'nivel',
        'pontos_total',
        'streak_dias',
        'ultimo_login',
    ];
}
