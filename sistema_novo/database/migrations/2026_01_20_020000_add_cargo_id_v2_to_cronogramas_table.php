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
        // Verifica se a coluna já existe antes de tentar adicionar
        if (!Schema::hasColumn('cronogramas', 'cargo_id')) {
            Schema::table('cronogramas', function (Blueprint $table) {
                $table->foreignId('cargo_id')->nullable()->constrained('cargos')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cronogramas', function (Blueprint $table) {
            if (Schema::hasColumn('cronogramas', 'cargo_id')) {
                $table->dropForeign(['cargo_id']);
                $table->dropColumn('cargo_id');
            }
        });
    }
};
