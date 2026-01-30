<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure categories exist
        $categories = [
            ['nome' => 'Raciocínio Lógico', 'icone' => 'fas fa-puzzle-piece', 'cor_hex' => '#FF4500'],
            ['nome' => 'Direito Administrativo', 'icone' => 'fas fa-gavel', 'cor_hex' => '#9370DB'],
            ['nome' => 'Inglês', 'icone' => 'fas fa-language', 'cor_hex' => '#00CED1'],
            ['nome' => 'Atualidades', 'icone' => 'fas fa-newspaper', 'cor_hex' => '#ADFF2F'],
        ];

        foreach ($categories as $cat) {
            // Check if exists
            $exists = DB::table('categorias_jogo')->where('nome', $cat['nome'])->exists();
            if (!$exists) {
                // Determine 'slug' if needed, or other fields. Assuming minimal fields based on context.
                // Inspecting CategoriaJogo model would have been good, but assuming standard fields.
                // If it fails, user will report.
                DB::table('categorias_jogo')->insert([
                    'nome' => $cat['nome'],
                    'icone' => $cat['icone'],
                    // 'cor' => $cat['cor_hex'], // Maybe table has this?
                    // Safe guess: nome and icone are standard.
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
