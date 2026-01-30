<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cronograma extends Model
{
    use HasFactory;

    protected $table = 'cronogramas';
    public $timestamps = false; // Legacy table might not have timestamps, checking legacy code doesn't show standardized timestamps

    protected $fillable = [
        'usuario_id',
        'edital_id',
        'cargo_id',
        'titulo',
        'data_inicio',
        'data_fim',
        'horas_por_dia',
        'concluido',
    ];

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function edital()
    {
        return $this->belongsTo(Edital::class, 'edital_id');
    }

    public function dias()
    {
        return $this->hasMany(CronogramaDia::class, 'cronograma_id');
    }
}
