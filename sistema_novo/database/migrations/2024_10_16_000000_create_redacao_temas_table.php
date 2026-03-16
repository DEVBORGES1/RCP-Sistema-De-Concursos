<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedacaoTemasTable extends Migration
{
    public function up()
    {
        Schema::create('redacao_temas', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('texto_motivador');
            $table->string('banca_referencia')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('redacao_temas');
    }
}
