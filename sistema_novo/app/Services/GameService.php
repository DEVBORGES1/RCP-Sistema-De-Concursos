<?php

namespace App\Services;

use App\Models\Partida;
use App\Models\Rodada;
use App\Models\UsuarioProgresso;
use App\Models\User;
use App\Models\PerguntaJogo;
use App\Models\RespostaJogo;
use App\Models\RespostaJogador;
use App\Models\PartidaPonto;
use App\Models\CategoriaJogo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class GameService
{
    // ===================================
    // MATCHMAKING
    // ===================================

    public function buscarOuCriarPartida($usuarioId)
    {
        $botId = $this->getBotId();

        // 1. Verifica partida ativa (em andamento)
        $ativa = Partida::where(function($q) use ($usuarioId) {
                $q->where('jogador1', $usuarioId)->orWhere('jogador2', $usuarioId);
            })
            ->where('status', 'em_andamento')
            ->first();

        if ($ativa) {
            // Check trava Bot (10 min)
            if ($ativa->jogador2 == $botId) {
                // Fix: Usar 'atualizado_em' pois é a coluna customizada no Model
                $lastUpdate = $ativa->atualizado_em; 
                $diff = $lastUpdate ? $lastUpdate->diffInSeconds(now()) : 9999;
                
                if ($diff > 600) {
                    $this->finalizarPartida($ativa->id);
                } else {
                    return $ativa;
                }
            } else {
                return $ativa;
            }
        }

        // 2. Busca partida aguardando (matchmaking)
        $partida = Partida::where('status', 'aguardando')
            ->where('jogador1', '!=', $usuarioId)
            ->first();

        if ($partida) {
            $partida->update([
                'jogador2' => $usuarioId,
                'status' => 'em_andamento',
                'atualizado_em' => now()
            ]);
            return $partida;
        }

        // 3. Verifica se eu já estou aguardando
        $existente = Partida::where('jogador1', $usuarioId)
            ->where('status', 'aguardando')
            ->first();

        if ($existente) return $existente;

        // 4. Cria nova sala de espera
        return Partida::create([
            'jogador1' => $usuarioId,
            'status' => 'aguardando'
        ]);
    }

    private function getBotId()
    {
        $bot = User::firstOrCreate(
            ['email' => 'bot@sistema.com'],
            [
                'nome' => 'System Bot',
                'senha_hash' => Hash::make('system_bot_secret')
            ]
        );
        
        return $bot->id;
    }

    public function criarPartidaContraBot($usuarioId)
    {
        $botId = $this->getBotId();
        
        if (!$botId) {
            throw new \Exception("Falha ao criar/recuperar usuário Bot.");
        }

        // Limpa espera anterior
        Partida::where('jogador1', $usuarioId)->where('status', 'aguardando')->delete();

        // Limpa partidas "travadas" anteriores contra bot
        Partida::where('jogador1', $usuarioId)
            ->where('jogador2', $botId)
            ->where('status', 'em_andamento')
            ->update(['status' => 'finalizada']);

        return Partida::create([
            'jogador1' => $usuarioId,
            'jogador2' => $botId,
            'status' => 'em_andamento'
        ]);
    }

    // ===================================
    // RODADAS
    // ===================================

    public function getRodadaAtiva($partidaId)
    {
        $rodada = Rodada::where('partida_id', $partidaId)
            ->where('ativa', 1)
            ->with(['pergunta.categoria', 'pergunta.respostas'])
            ->orderBy('id', 'desc')
            ->first();

        if ($rodada) {
            $segundosRestantes = now()->diffInSeconds($rodada->fim, false);

            if ($segundosRestantes <= 0) {
                $this->finalizarRodada($rodada->id);
                return null;
            }

            // Transformar para formato amigável ao Controller/View
            // Usamos array ou objeto customizado
            // Para manter compatibilidade com a view antiga, formato array é útil, mas Objeto é melhor no Laravel
            return $rodada;
        }

        return null;
    }

    public function iniciarNovaRodada($partidaId)
    {
        $totalRodadas = Rodada::where('partida_id', $partidaId)->count();
        if ($totalRodadas >= 10) {
            $this->finalizarPartida($partidaId);
            return null;
        }

        $categoria = CategoriaJogo::inRandomOrder()->first();
        if (!$categoria) {
            \Log::error("ERRO GAME: Nenhuma categoria encontrada no banco.");
            return null;
        }

        // Pergunta não usada
        $perguntasUsadasIds = Rodada::where('partida_id', $partidaId)->pluck('pergunta_id');
        
        $pergunta = PerguntaJogo::where('categoria_id', $categoria->id)
            ->whereNotIn('id', $perguntasUsadasIds)
            ->inRandomOrder()
            ->first();

        if (!$pergunta) {
             \Log::warning("GAME: Sem perguntas inéditas na categoria {$categoria->id}. Tentando qualquer categoria.");
             $pergunta = PerguntaJogo::whereNotIn('id', $perguntasUsadasIds)
                ->inRandomOrder()
                ->first();
        }

        if ($pergunta) {
            \Log::info("GAME: Iniciando Rodada {$totalRodadas} (Partida {$partidaId}) - Pergunta ID {$pergunta->id}");
            return Rodada::create([
                'partida_id' => $partidaId,
                'pergunta_id' => $pergunta->id,
                'numero_rodada' => $totalRodadas + 1,
                'inicio' => now(),
                'fim' => now()->addSeconds(30),
                'ativa' => 1
            ]);
        }
        
        \Log::error("ERRO GAME: Nenhuma pergunta disponível para iniciar rodada.");
        return null;
    }

    public function finalizarRodada($rodadaId)
    {
        Rodada::where('id', $rodadaId)->update(['ativa' => 0]);
    }

    public function finalizarPartida($partidaId)
    {
        Partida::where('id', $partidaId)->update(['status' => 'finalizada']);
    }

    // ===================================
    // JOGABILIDADE
    // ===================================

    public function processarResposta($rodadaId, $usuarioId, $respostaId)
    {
        // Check duplicidade
        $jaRespondeu = RespostaJogador::where('rodada_id', $rodadaId)
            ->where('usuario_id', $usuarioId)
            ->exists();
            
        if ($jaRespondeu) return ['status' => 'ja_respondeu'];

        $correta = 0;
        $pontos = 0;

        if ($respostaId === 'SKIP') {
            $respostaId = null;
        } else {
            $resp = RespostaJogo::find($respostaId);
            $correta = $resp && $resp->correta ? 1 : 0;
            
            if ($correta) {
                // Pontuação dinâmica
                $anteriores = RespostaJogador::where('rodada_id', $rodadaId)->count();
                $pontos = ($anteriores == 0) ? 15 : 10;
            }
        }

        RespostaJogador::create([
            'rodada_id' => $rodadaId,
            'usuario_id' => $usuarioId,
            'resposta_id' => $respostaId,
            'correta' => $correta,
            'pontos_ganhos' => $pontos,
            'data_resposta' => now()
        ]);

        // Sudden Death check
        $this->verificarSuddenDeath($rodadaId);

        // Atualizar Placar
        if ($pontos > 0) {
            $rodada = Rodada::find($rodadaId);
            DB::table('partida_pontos')->updateOrInsert(
                ['partida_id' => $rodada->partida_id, 'usuario_id' => $usuarioId],
                ['data' => now()->toDateString(), 'pontos' => DB::raw("pontos + $pontos")]
            );
        }

        // Bot Logic Check
        $rodada = Rodada::find($rodadaId);
        $partida = Partida::find($rodada->partida_id);
        $botId = $this->getBotId();

        if ($partida->jogador2 == $botId) {
            $this->processarJogadaBot($rodadaId, $partida->id);
        }

        // Check Fim Rodada
        $this->verificarFimRodada($rodadaId);

        return ['status' => 'sucesso', 'correta' => $correta];
    }

    private function processarJogadaBot($rodadaId, $partidaId)
    {
        $botId = $this->getBotId();
        if (RespostaJogador::where('rodada_id', $rodadaId)->where('usuario_id', $botId)->exists()) return;

        $acertou = rand(0, 1);
        $rodada = Rodada::find($rodadaId);
        
        $query = RespostaJogo::where('pergunta_id', $rodada->pergunta_id);
        if ($acertou) $query->where('correta', 1);
        else $query->where('correta', 0);
        
        $resposta = $query->inRandomOrder()->first();
        
        if ($resposta) {
             $pontos = $acertou ? 10 : 0;
             RespostaJogador::create([
                'rodada_id' => $rodadaId,
                'usuario_id' => $botId,
                'resposta_id' => $resposta->id,
                'correta' => $acertou ? 1 : 0,
                'pontos_ganhos' => $pontos,
                'data_resposta' => now()
             ]);

             if ($pontos > 0) {
                DB::table('partida_pontos')->updateOrInsert(
                    ['partida_id' => $partidaId, 'usuario_id' => $botId],
                    ['data' => now()->toDateString(), 'pontos' => DB::raw("pontos + $pontos")]
                );
             }
        }
    }

    public function verificarVezDoBot($partidaId, $rodadaId)
    {
        $partida = Partida::find($partidaId);
        if (!$partida) return;
        
        $botId = $this->getBotId();
        if ($partida->jogador2 != $botId) return;

        if (RespostaJogador::where('rodada_id', $rodadaId)->where('usuario_id', $botId)->exists()) return;

        // Chance alta de responder (simulada)
        if (rand(1, 100) <= 90) {
            $this->processarJogadaBot($rodadaId, $partidaId);
            $this->verificarSuddenDeath($rodadaId);
        }
    }

    private function verificarSuddenDeath($rodadaId)
    {
        // Mecânica removida a pedido do usuário (Jogo ficava impossível).
        // O tempo agora corre normalmente (30s) para ambos.
    }

    private function verificarFimRodada($rodadaId)
    {
        $count = RespostaJogador::where('rodada_id', $rodadaId)->count();
        if ($count >= 2) {
            $this->finalizarRodada($rodadaId);
        }
    }

    public function obterHistoricoPartida($partidaId)
    {
        // Retorna array mapeado: [usuario_id => [rodada_numero => status_bool]]
        $respostas = DB::table('respostas_jogadores as rj')
            ->join('rodadas as r', 'r.id', '=', 'rj.rodada_id')
            ->where('r.partida_id', $partidaId)
            ->select('rj.usuario_id', 'r.numero_rodada', 'rj.correta')
            ->get();

        $historico = [];
        foreach ($respostas as $resp) {
            $historico[$resp->usuario_id][$resp->numero_rodada] = $resp->correta == 1;
        }
        return $historico;
    }

    public function obterPlacar($partidaId)
    {
        // Retorna pontos E quantidade de acertos por usuário
        return DB::select("
            SELECT 
                u.id as usuario_id,
                u.nome,
                AVG(pp.pontos) as pontos, -- Usando AVG/MAX pois updateOrUpdate soma no registro
                COUNT(rj.id) as acertos -- Contar respostas certas direto da tabela de respostas
            FROM partidas p
            JOIN usuarios u ON (u.id = p.jogador1 OR u.id = p.jogador2)
            LEFT JOIN partida_pontos pp ON pp.partida_id = p.id AND pp.usuario_id = u.id
            LEFT JOIN rodadas r ON r.partida_id = p.id
            LEFT JOIN respostas_jogadores rj ON rj.rodada_id = r.id AND rj.usuario_id = u.id AND rj.correta = 1
            WHERE p.id = ?
            GROUP BY u.id, u.nome
            ORDER BY pontos DESC
        ", [$partidaId]);
    }
}
