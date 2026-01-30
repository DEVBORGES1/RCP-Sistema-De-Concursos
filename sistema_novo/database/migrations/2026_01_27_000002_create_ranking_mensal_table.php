<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ranking_mensal')) {
            Schema::create('ranking_mensal', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('usuario_id');
                $table->string('mes_ano', 7); // YYYY-MM
                $table->integer('pontos_mes')->default(0);
                $table->integer('posicao')->nullable();
                
                $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
                
                // Unique index to prevent duplicate entries for same user/month
                $table->unique(['usuario_id', 'mes_ano']);
                $table->index(['mes_ano', 'pontos_mes']); // Performance for sorting
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ranking_mensal');
    }
};
