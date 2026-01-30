<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Edital extends Model
{
    use HasFactory;

    protected $table = 'editais';
    public $timestamps = false; // Ajustar se necessário

    protected $fillable = [
        'usuario_id',
        'nome_arquivo',
        'data_upload',
        'texto_extraido',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function disciplinas()
    {
        return $this->hasMany(Disciplina::class, 'edital_id');
    }

    public function questoes()
    {
        return $this->hasMany(Questao::class, 'edital_id');
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class, 'edital_id');
    }
}
