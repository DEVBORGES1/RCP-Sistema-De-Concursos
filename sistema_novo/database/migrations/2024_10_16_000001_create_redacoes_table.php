<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedacoesTable extends Migration
{
    public function up()
    {
        Schema::create('redacoes', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreignId('tema_id')->constrained('redacao_temas')->onDelete('cascade');
            $table->text('texto_enviado');
            $table->integer('nota_total')->nullable();
            $table->json('criterios_nota')->nullable(); // Guardar notas detalhadas
            $table->text('feedback_ia')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('redacoes');
    }
}
