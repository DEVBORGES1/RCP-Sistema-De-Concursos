<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\GamificationService;

class DashboardController extends Controller
{
    protected $gamificationService;

    public function __construct(GamificationService $gamificationService)
    {
        $this->gamificationService = $gamificationService;
    }

    public function index()
    {
        $user = Auth::user();
        $this->gamificationService->atualizarStreak($user->id);

        $dadosUsuario = $this->gamificationService->obterDadosUsuario($user->id);
        $conquistas = $this->gamificationService->obterConquistasUsuario($user->id);
        $ranking = $this->gamificationService->obterRankingMensal(5);
        $posicaoUsuario = $this->gamificationService->obterPosicaoUsuario($user->id);

        // Estatísticas para os cards
        $totalQuestoes = DB::table('respostas_usuario')->where('usuario_id', $user->id)->count();
        $questoesCorretas = DB::table('respostas_usuario')->where('usuario_id', $user->id)->where('correta', 1)->count();
        $percentualAcerto = $totalQuestoes > 0 ? round(($questoesCorretas / $totalQuestoes) * 100, 1) : 0;
        
        $totalEditais = DB::table('editais')->where('usuario_id', $user->id)->count();
        $totalSimulados = DB::table('simulados')->where('usuario_id', $user->id)->whereNotNull('questoes_corretas')->count();

        // Preparar dados para view
        // $dadosUsuario já vem array do Service, user pode ser acessado direto também
        
        return view('dashboard', [
            'user' => $user,
            'nome_usuario' => $user->nome,
            'dados_usuario' => $dadosUsuario,
            'conquistas' => collect($conquistas),
            'ranking' => collect($ranking),
            'posicao_usuario' => $posicaoUsuario,
            'total_questoes' => $totalQuestoes,
            'percentual_acerto' => $percentualAcerto,
            'total_editais' => $totalEditais,
            'total_simulados' => $totalSimulados,
            'nivel_usuario' => $dadosUsuario['nivel'],
            'pontos_usuario' => $dadosUsuario['pontos_total'],
            'streak_usuario' => $dadosUsuario['streak_dias']
        ]);
    }
}
