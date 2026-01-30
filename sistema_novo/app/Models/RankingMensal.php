<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankingMensal extends Model
{
    use HasFactory;

    protected $table = 'ranking_mensal';
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'mes_ano',
        'pontos_mes',
        'posicao',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
