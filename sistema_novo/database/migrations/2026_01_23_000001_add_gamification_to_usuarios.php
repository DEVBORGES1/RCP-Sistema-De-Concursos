<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) { 
            // Check mostly because of potential partial migrations
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
        
        // Also check 'users' table if they are using different tables for auth vs profile, 
        // but the error mentioned "update `usuarios`", so sticking to that.
        // However, standard Laravel uses 'users'. Providing fix for 'users' too just in case 
        // the model links to 'users' but table name variable is customized or confused.
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['pontos', 'xp_atual', 'nivel']);
        });
    }
};
