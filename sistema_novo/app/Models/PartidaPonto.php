<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PartidaPonto extends Model
{
    use HasFactory;

    protected $table = 'partida_pontos';
    public $timestamps = false;
    public $incrementing = false; // Chave composta não suportada nativamente pelo Eloquent update/save fácil, mas ok para leitura
    protected $primaryKey = ['partida_id', 'usuario_id']; // Laravel não suporta composite keys nativamente bem, cuidado ao usar save()

    protected $fillable = ['partida_id', 'usuario_id', 'pontos', 'data'];

    public function user() 
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
