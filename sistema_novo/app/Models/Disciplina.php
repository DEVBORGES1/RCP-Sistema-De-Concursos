<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    use HasFactory;

    protected $table = 'disciplinas';
    public $timestamps = false;

    protected $fillable = [
        'edital_id',
        'cargo_id',
        'nome_disciplina',
    ];

    public function edital()
    {
        return $this->belongsTo(Edital::class, 'edital_id');
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class, 'cargo_id');
    }

    public function questoes()
    {
        return $this->hasMany(Questao::class, 'disciplina_id');
    }
}
