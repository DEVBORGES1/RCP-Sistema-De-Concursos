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
        Schema::table('editais', function (Blueprint $table) {
            $table->string('cidade_prova')->nullable()->after('texto_extraido');
            $table->string('instituicao_banca')->nullable()->after('cidade_prova');
            $table->string('ano_prova', 4)->nullable()->after('instituicao_banca');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('editais', function (Blueprint $table) {
            $table->dropColumn(['cidade_prova', 'instituicao_banca', 'ano_prova']);
        });
    }
};
