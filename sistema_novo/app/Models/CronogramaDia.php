<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CronogramaDia extends Model
{
    use HasFactory;

    protected $table = 'cronograma_detalhado';
    public $timestamps = false;

    protected $fillable = [
        'cronograma_id',
        'disciplina_id',
        'data_estudo',
        'horas_previstas',
    ];

    public function cronograma()
    {
        return $this->belongsTo(Cronograma::class, 'cronograma_id');
    }

    public function disciplina()
    {
        return $this->belongsTo(Disciplina::class, 'disciplina_id');
    }
}
