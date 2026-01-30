<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GameService;
use Illuminate\Support\Facades\Auth;

class JogoController extends Controller
{
    protected $gameService;

    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // 1. Busca ou cria partida
        $partida = $this->gameService->buscarOuCriarPartida($user->id);
        
        $dados = [
            'partida' => $partida,
            'user' => $user,
            'aguardando' => false,
            'finalizada' => false,
            'rodada' => null,
            'placar' => [],
            'meusPontos' => 0,
            'oponentePontos' => 0
        ];

        if ($partida->status == 'aguardando') {
            $dados['aguardando'] = true;
        } elseif ($partida->status == 'finalizada') {
            $dados['finalizada'] = true;
            $dados['placar'] = $this->gameService->obterPlacar($partida->id);
        } else {
            // Em andamento
            $rodada = $this->gameService->getRodadaAtiva($partida->id);
            if (!$rodada) {
                // Tenta iniciar nova
                $rodada = $this->gameService->iniciarNovaRodada($partida->id);
                // Se falhar e jogo acabou
                if (!$rodada) {
                    // Recarrega status
                    $partida->refresh();
                    if ($partida->status == 'finalizada') {
                        return redirect()->route('jogo.index');
                    }
                }
            }
            
            // Prepara dados da rodada se existir
            if ($rodada) {
                // Adiciona campos calculados para view (segundos restantes)
                $rodada->segundos_restantes = now()->diffInSeconds($rodada->fim, false);
                $rodada->total_rodadas = 10;
                $rodada->opcoes = $rodada->pergunta->respostas; // Carregado via 'with' no Service? Preciso checar.
                // O Service usa 'with' -> 'pergunta.respostas', então $rodada->pergunta->respostas existe.
                // Mas para facilitar na view vamos passar direto ou usar computed.
            }
            $dados['rodada'] = $rodada;
        }

        $dados['meusAcertos'] = 0;
        $dados['oponenteAcertos'] = 0;
        $dados['meuId'] = $user->id;
        $dados['oponenteId'] = ($partida->jogador1 == $user->id) ? $partida->jogador2 : $partida->jogador1;

        // Placar
        $placar = $this->gameService->obterPlacar($partida->id);
        $dados['placarPartida'] = $placar;
        
        foreach ($placar as $p) {
            if ($p->usuario_id == $user->id) {
                $dados['meusPontos'] = $p->pontos;
                $dados['meusAcertos'] = $p->acertos;
            } else {
                $dados['oponentePontos'] = $p->pontos;
                $dados['oponenteAcertos'] = $p->acertos;
            }
        }

        // Histórico de Rodadas (para os dots)
        $dados['historico'] = $this->gameService->obterHistoricoPartida($partida->id);

        return view('jogo.index', $dados);
    }

    public function jogarBot()
    {
        try {
            $this->gameService->criarPartidaContraBot(Auth::id());
            return redirect()->route('jogo.index');
        } catch (\Exception $e) {
            \Log::error("Erro ao iniciar jogo com bot: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return redirect()->route('jogo.index')->withErrors(['erro' => 'Falha ao iniciar jogo: ' . $e->getMessage()]);
        }
    }

    public function desistir()
    {
        $partida = $this->gameService->buscarOuCriarPartida(Auth::id());
        if ($partida) {
            $this->gameService->finalizarPartida($partida->id);
        }
        return redirect()->route('dashboard');
    }

    public function responder(Request $request)
    {
        $request->validate([
            'rodada_id' => 'required',
            'resposta_id' => 'required'
        ]);

        try {
            $this->gameService->processarResposta(
                $request->rodada_id,
                Auth::id(),
                $request->resposta_id
            );
        } catch (\Exception $e) {
            \Log::error("Erro ao responder: " . $e->getMessage());
        }

        return redirect()->route('jogo.index');
    }

    public function checkStatus(Request $request)
    {
        try {
            $user = Auth::user();
            $partida = $this->gameService->buscarOuCriarPartida($user->id); // Busca a atual
            
            if ($partida->status == 'finalizada') {
                 $placar = $this->gameService->obterPlacar($partida->id);
                 return response()->json([
                     'status' => 'finalizada',
                     'placar' => $placar
                 ]);
            }

            $rodada = $this->gameService->getRodadaAtiva($partida->id);
            if ($rodada) {
                // Verifica Bot
                $this->gameService->verificarVezDoBot($partida->id, $rodada->id);
                
                // Recalcula tempo
                $segRestantes = now()->diffInSeconds($rodada->fim, false);
                
                return response()->json([
                    'status' => 'rodada_ativa',
                    'segundos_restantes' => $segRestantes,
                    'rodada_id' => $rodada->id
                ]);
            } 
            
            return response()->json(['status' => 'aguardando_rodada']);
        } catch (\Exception $e) {
             \Log::error("Erro no status do jogo: " . $e->getMessage());
             return response()->json(['status' => 'erro', 'message' => $e->getMessage()]);
        }
    }
}
