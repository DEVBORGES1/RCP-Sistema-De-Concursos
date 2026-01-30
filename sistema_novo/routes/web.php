<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Rotas de Editais
    Route::post('editais/{id}/reanalisar', [\App\Http\Controllers\EditaisController::class, 'reanalisar'])->name('editais.reanalisar');
    Route::post('editais/{id}/buscar-ia', [\App\Http\Controllers\EditaisController::class, 'buscarConteudoCargo'])->name('editais.buscar_ia');
    Route::get('editais/{id}/configurar', [\App\Http\Controllers\EditaisController::class, 'configurar'])->name('editais.configurar');
    Route::post('editais/{id}/configurar', [\App\Http\Controllers\EditaisController::class, 'salvarConfiguracao'])->name('editais.salvar_configuracao');
    Route::resource('editais', \App\Http\Controllers\EditaisController::class);

    // Rotas de Cronogramas
    Route::get('cronogramas/{id}/pdf', [\App\Http\Controllers\CronogramaController::class, 'pdf'])->name('cronogramas.pdf');
    Route::resource('cronogramas', \App\Http\Controllers\CronogramaController::class);

    // Rotas de Questões
    Route::post('/questoes/{id}/responder', [\App\Http\Controllers\QuestoesController::class, 'responder'])->name('questoes.responder');
    Route::resource('questoes', \App\Http\Controllers\QuestoesController::class);

    // Rotas de Simulados
    Route::post('/simulados/{id}/finalizar', [\App\Http\Controllers\SimuladosController::class, 'finalizar'])->name('simulados.finalizar');
    Route::resource('simulados', \App\Http\Controllers\SimuladosController::class);

    // Rotas de Admin
    Route::prefix('admin')->name('admin.')->middleware(\App\Http\Middleware\AdminMiddleware::class)->group(function () {
        Route::resource('videoaulas', App\Http\Controllers\AdminVideoaulasController::class);
        
        // Banco de Questões IA
        Route::get('questoes/criar', [\App\Http\Controllers\AdminQuestoesController::class, 'create'])->name('questoes.create');
        Route::post('questoes/gerar', [\App\Http\Controllers\AdminQuestoesController::class, 'gerar'])->name('questoes.gerar');
        Route::post('questoes/salvar', [\App\Http\Controllers\AdminQuestoesController::class, 'store'])->name('questoes.store');
    });

    // Rotas de Videoaulas (Usuário)
    Route::resource('videoaulas', App\Http\Controllers\VideoaulasController::class)->only(['index', 'show']);
    Route::get('videoaulas/{id}/player', [App\Http\Controllers\VideoaulasController::class, 'player'])->name('videoaulas.player');
    Route::post('videoaulas/{id}/progresso', [App\Http\Controllers\VideoaulasController::class, 'atualizarProgresso'])->name('videoaulas.progresso');

    // Rotas de Perfil
    Route::get('/perfil', [\App\Http\Controllers\PerfilController::class, 'index'])->name('perfil.index');
    Route::post('/perfil/update', [\App\Http\Controllers\PerfilController::class, 'update'])->name('perfil.update');

    // Rotas de Jogo Multiplayer
    Route::get('/jogo', [\App\Http\Controllers\JogoController::class, 'index'])->name('jogo.index');
    Route::post('/jogo/bot', [\App\Http\Controllers\JogoController::class, 'jogarBot'])->name('jogo.bot');
    Route::post('/jogo/responder', [\App\Http\Controllers\JogoController::class, 'responder'])->name('jogo.responder');
    Route::post('/jogo/desistir', [\App\Http\Controllers\JogoController::class, 'desistir'])->name('jogo.desistir');
    Route::get('/jogo/status', [\App\Http\Controllers\JogoController::class, 'checkStatus'])->name('jogo.status');

    Route::get('/certificado/{categoria}', [\App\Http\Controllers\CertificadoController::class, 'gerar'])->name('certificados.gerar');
    
    // Rota de Ranking
    Route::get('/ranking', [\App\Http\Controllers\RankingController::class, 'index'])->name('ranking.index');
});

// DEBUG TEMPORÁRIO
Route::get('/debug-game-data', function () {
    try {
        $cats = \App\Models\CategoriaJogo::count();
        $pergs = \App\Models\PerguntaJogo::count();
        $bot = \App\Models\User::where('email', 'bot@sistema.com')->first();
        
        return response()->json([
            'categorias' => $cats,
            'perguntas' => $pergs,
            'bot_status' => $bot ? 'Existe (ID: '.$bot->id.')' : 'Não existe'
        ]);
    } catch (\Exception $e) {
        return "Erro: " . $e->getMessage();
    }
});

Route::get('/fix-db-schema', function() {
    try {
        Illuminate\Support\Facades\Schema::table('usuarios', function (Illuminate\Database\Schema\Blueprint $table) {
            if (!Illuminate\Support\Facades\Schema::hasColumn('usuarios', 'foto_perfil')) $table->string('foto_perfil')->nullable()->after('email');
            if (!Illuminate\Support\Facades\Schema::hasColumn('usuarios', 'area_interesse')) $table->string('area_interesse')->nullable()->after('foto_perfil');
            if (!Illuminate\Support\Facades\Schema::hasColumn('usuarios', 'escolaridade')) $table->string('escolaridade')->nullable()->after('area_interesse');
            if (!Illuminate\Support\Facades\Schema::hasColumn('usuarios', 'cargos_alvo')) $table->text('cargos_alvo')->nullable()->after('escolaridade');
            if (!Illuminate\Support\Facades\Schema::hasColumn('usuarios', 'linkedin')) $table->string('linkedin')->nullable()->after('cargos_alvo');
            if (!Illuminate\Support\Facades\Schema::hasColumn('usuarios', 'biografia')) $table->text('biografia')->nullable()->after('linkedin');
            if (!Illuminate\Support\Facades\Schema::hasColumn('usuarios', 'tema_preferencia')) $table->string('tema_preferencia')->default('dark')->after('biografia');
            
            // Gamification
            if (!Illuminate\Support\Facades\Schema::hasColumn('usuarios', 'pontos')) $table->integer('pontos')->default(0)->after('name');
            if (!Illuminate\Support\Facades\Schema::hasColumn('usuarios', 'xp_atual')) $table->integer('xp_atual')->default(0)->after('pontos');
            if (!Illuminate\Support\Facades\Schema::hasColumn('usuarios', 'nivel')) $table->integer('nivel')->default(1)->after('xp_atual');
        });
        return "Banco de dados corrigido com sucesso! Colunas criadas na tabela 'usuarios'. Agora você pode voltar ao Perfil.";
    } catch (\Exception $e) {
        return "Erro ao corrigir DB: " . $e->getMessage();
    }
});


Route::get('/fix-video-progresso', function() {
    try {
        Illuminate\Support\Facades\Schema::table('videoaulas_progresso', function (Illuminate\Database\Schema\Blueprint $table) {
            if (!Illuminate\Support\Facades\Schema::hasColumn('videoaulas_progresso', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
                $table->timestamp('created_at')->nullable();
            }
        });
        return "Tabela videoaulas_progresso corrigida! Colunas updated_at e created_at adicionadas.";
    } catch (\Exception $e) {
        return "Erro ao corrigir DB: " . $e->getMessage();
    }
});
