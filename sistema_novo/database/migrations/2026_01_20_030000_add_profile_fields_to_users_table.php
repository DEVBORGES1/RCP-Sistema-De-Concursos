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
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('foto_perfil')->nullable()->after('email');
            $table->string('area_interesse')->nullable()->after('foto_perfil'); // Ex: Policial, Administrativa
            $table->string('escolaridade')->nullable()->after('area_interesse'); // Ex: Superior Completo
            $table->text('cargos_alvo')->nullable()->after('escolaridade'); // Pode ser separado por vírgula ou JSON
            $table->string('linkedin')->nullable()->after('cargos_alvo');
            $table->text('biografia')->nullable()->after('linkedin');
            $table->string('tema_preferencia')->default('dark')->after('biografia'); // dark ou light
            
            // Campos de Gamificação (caso não existam, garantimos aqui, mas já devem existir pelo contexto anterior)
            if (!Schema::hasColumn('users', 'pontos')) {
                $table->integer('pontos')->default(0)->after('name');
            }
            if (!Schema::hasColumn('users', 'xp_atual')) {
                $table->integer('xp_atual')->default(0)->after('pontos');
            }
            if (!Schema::hasColumn('users', 'nivel')) {
                $table->integer('nivel')->default(1)->after('xp_atual');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn([
                'foto_perfil', 
                'area_interesse', 
                'escolaridade', 
                'cargos_alvo', 
                'linkedin', 
                'biografia',
                'tema_preferencia',
                'pontos',
                'xp_atual',
                'nivel'
            ]);
        });
    }
};
