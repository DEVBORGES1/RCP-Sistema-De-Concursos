<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Verificar se a tabela já existe antes de criar
        if (!Schema::hasTable('questoes')) {
            Schema::create('questoes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('edital_id')->constrained('editais')->onDelete('cascade');
                $table->foreignId('disciplina_id')->nullable()->constrained('disciplinas')->onDelete('set null');
                $table->text('enunciado');
                $table->string('alternativa_a');
                $table->string('alternativa_b');
                $table->string('alternativa_c');
                $table->string('alternativa_d');
                $table->string('alternativa_e');
                $table->char('alternativa_correta', 1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questoes');
    }
};
