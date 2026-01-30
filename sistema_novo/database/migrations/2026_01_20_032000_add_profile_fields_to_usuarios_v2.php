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
        // Garantir que estamos alterando a tabela CORRETA: 'usuarios'
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                
                if (!Schema::hasColumn('usuarios', 'foto_perfil')) {
                    $table->string('foto_perfil')->nullable()->after('email');
                }
                
                if (!Schema::hasColumn('usuarios', 'area_interesse')) {
                    $table->string('area_interesse')->nullable()->after('foto_perfil');
                }

                if (!Schema::hasColumn('usuarios', 'escolaridade')) {
                    $table->string('escolaridade')->nullable()->after('area_interesse');
                }

                if (!Schema::hasColumn('usuarios', 'cargos_alvo')) {
                    $table->text('cargos_alvo')->nullable()->after('escolaridade');
                }

                if (!Schema::hasColumn('usuarios', 'linkedin')) {
                    $table->string('linkedin')->nullable()->after('cargos_alvo');
                }

                if (!Schema::hasColumn('usuarios', 'biografia')) {
                    $table->text('biografia')->nullable()->after('linkedin');
                }

                if (!Schema::hasColumn('usuarios', 'tema_preferencia')) {
                    $table->string('tema_preferencia')->default('dark')->after('biografia');
                }

                // Gamificação
                if (!Schema::hasColumn('usuarios', 'pontos')) {
                    $table->integer('pontos')->default(0);
                }
                if (!Schema::hasColumn('usuarios', 'xp_atual')) {
                    $table->integer('xp_atual')->default(0);
                }
                if (!Schema::hasColumn('usuarios', 'nivel')) {
                    $table->integer('nivel')->default(1);
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('usuarios')) {
            Schema::table('usuarios', function (Blueprint $table) {
                // Removemos apenas se existir, para evitar erros no rollback
                $columns = [
                    'foto_perfil', 'area_interesse', 'escolaridade', 
                    'cargos_alvo', 'linkedin', 'biografia', 
                    'tema_preferencia', 'pontos', 'xp_atual', 'nivel'
                ];
                
                foreach ($columns as $col) {
                    if (Schema::hasColumn('usuarios', $col)) {
                        $table->dropColumn($col);
                    }
                }
            });
        }
    }
};
