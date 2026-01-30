<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GamificationService;
use Illuminate\Support\Facades\Auth;

class RankingController extends Controller
{
    protected $gamification;

    public function __construct(GamificationService $gamification)
    {
        $this->gamification = $gamification;
    }

    public function index()
    {
        // For demo purposes, if table is empty, we might want to seed/mock or just show empty state.
        // The service 'obterRankingMensal' does a join.
        
        $topPlayers = $this->gamification->obterRankingMensal(10);
        $minhaPosicao = $this->gamification->obterPosicaoUsuario(Auth::id());
        
        // Mock data if empty for visualization (Premium Logic)
        if($topPlayers->isEmpty()) {
            $topPlayers = collect([
                (object)['nome' => 'Mestre Concurseiro', 'pontos_mes' => 15000, 'posicao' => 1, 'foto_perfil' => null],
                (object)['nome' => 'Aprovado 2026', 'pontos_mes' => 12500, 'posicao' => 2, 'foto_perfil' => null],
                (object)['nome' => 'Foco Total', 'pontos_mes' => 11000, 'posicao' => 3, 'foto_perfil' => null],
                (object)['nome' => Auth::user()->nome, 'pontos_mes' => 0, 'posicao' => 999, 'foto_perfil' => Auth::user()->foto_perfil], 
            ]);
            $minhaPosicao = 999;
        }

        return view('ranking.index', compact('topPlayers', 'minhaPosicao'));
    }
}
