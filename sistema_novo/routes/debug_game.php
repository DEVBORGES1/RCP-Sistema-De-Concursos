<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\CategoriaJogo;
use App\Models\PerguntaJogo;
use App\Models\User;

Route::get('/debug-game-data', function () {
    try {
        $cats = CategoriaJogo::count();
        $pergs = PerguntaJogo::count();
        $users = User::count();
        $bot = User::where('email', 'bot@sistema.com')->first();
        
        echo "<h1>Diagnóstico de Dados do Jogo</h1>";
        echo "<ul>";
        echo "<li><strong>Categorias:</strong> $cats " . ($cats == 0 ? "(CRÍTICO: Sem categorias, o jogo não abre)" : "OK") . "</li>";
        echo "<li><strong>Perguntas:</strong> $pergs " . ($pergs == 0 ? "(CRÍTICO: Sem perguntas, rodada não inicia)" : "OK") . "</li>";
        echo "<li><strong>Usuários:</strong> $users</li>";
        echo "<li><strong>Bot System:</strong> " . ($bot ? "Encontrado (ID: {$bot->id})" : "NÃO ENCONTRADO (O jogo tentará criar)") . "</li>";
        echo "</ul>";
        
        if ($cats == 0 || $pergs == 0) {
            echo "<p style='color:red'><strong>AÇÃO NECESSÁRIA:</strong> Rode o seed ou insira dados manualmente.</p>";
        } else {
             echo "<p style='color:green'><strong>DADOS OK:</strong> O problema provavelmente não é falta de dados.</p>";
        }
        
    } catch (\Exception $e) {
        echo "Erro ao conectar/consultar banco: " . $e->getMessage();
    }
});
