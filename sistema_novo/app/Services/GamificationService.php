<?php

namespace App\Services;

use App\Models\User;
use App\Models\UsuarioProgresso;
use App\Models\Conquista;
use App\Models\RankingMensal;
use App\Models\UsuarioConquista; // Pivot table model if needed, or DB queries
use Illuminate\Support\Facades\DB;

class GamificationService
{
    public function garantirProgressoUsuario($usuarioId)
    {
        $progresso = UsuarioProgresso::where('usuario_id', $usuarioId)->first();
        
        if (!$progresso) {
            UsuarioProgresso::create([
                'usuario_id' => $usuarioId,
                'nivel' => 1,
                'pontos_total' => 0,
                'streak_dias' => 0,
                'ultimo_login' => date('Y-m-d')
            ]);
        }
        return true;
    }

    public function atualizarStreak($usuarioId)
    {
        $this->garantirProgressoUsuario($usuarioId);
        
        $progresso = UsuarioProgresso::where('usuario_id', $usuarioId)->first();
        
        $hoje = date('Y-m-d');
        $ontem = date('Y-m-d', strtotime('-1 day'));
        
        if ($progresso->ultimo_login == $ontem) {
            $progresso->streak_dias += 1;
            $progresso->ultimo_login = $hoje;
            $progresso->save();
        } elseif ($progresso->ultimo_login != $hoje) {
            $progresso->streak_dias = 1;
            $progresso->ultimo_login = $hoje;
            $progresso->save();
        }
    }

    public function adicionarPontos($usuarioId, $pontos)
    {
        $this->garantirProgressoUsuario($usuarioId);
        $progresso = UsuarioProgresso::where('usuario_id', $usuarioId)->first();
        $progresso->pontos_total += $pontos;
        
        // Lógica simples de nível: cada 1000 pontos = 1 nível
        $novoNivel = floor($progresso->pontos_total / 1000) + 1;
        if ($novoNivel > $progresso->nivel) {
            $progresso->nivel = $novoNivel;
            // Poderia disparar evento de 'LevelUp' aqui
        }
        
        $progresso->save();
    }

    public function obterDadosUsuario($usuarioId)
    {
        $this->garantirProgressoUsuario($usuarioId);
        
        $user = User::select('id', 'nome', 'email')->find($usuarioId);
        $progresso = UsuarioProgresso::where('usuario_id', $usuarioId)->first();
        
        // Contadores simples
        // Assumindo tabelas originais, usar DB raw para performance ou models se tiver
        $questoesRespondidas = DB::table('respostas_usuario')
            ->where('usuario_id', $usuarioId)
            ->distinct('questao_id')
            ->count();
            
        $questoesCorretas = DB::table('respostas_usuario')
            ->where('usuario_id', $usuarioId)
            ->where('correta', 1)
            ->distinct('questao_id')
            ->count();

        return [
            'nome' => $user->nome,
            'email' => $user->email,
            'nivel' => $progresso->nivel,
            'pontos_total' => $progresso->pontos_total,
            'streak_dias' => $progresso->streak_dias,
            'questoes_respondidas' => $questoesRespondidas,
            'questoes_corretas' => $questoesCorretas
        ];
    }

    public function obterConquistasUsuario($usuarioId)
    {
        return Conquista::select('conquistas.*', 'usuarios_conquistas.data_conquista')
            ->leftJoin('usuarios_conquistas', function($join) use ($usuarioId) {
                $join->on('conquistas.id', '=', 'usuarios_conquistas.conquista_id')
                     ->where('usuarios_conquistas.usuario_id', '=', $usuarioId);
            })
            ->orderBy('conquistas.pontos_necessarios')
            ->get();
    }

    public function obterRankingMensal($limite = 5)
    {
        $mesAno = date('Y-m');
        
        return RankingMensal::join('usuarios', 'ranking_mensal.usuario_id', '=', 'usuarios.id')
            ->where('mes_ano', $mesAno)
            ->orderBy('posicao')
            ->limit($limite)
            ->get(['usuarios.nome', 'ranking_mensal.pontos_mes', 'ranking_mensal.posicao']);
    }

    public function obterPosicaoUsuario($usuarioId)
    {
        $mesAno = date('Y-m');
        $ranking = RankingMensal::where('usuario_id', $usuarioId)
            ->where('mes_ano', $mesAno)
            ->first();
            
        return $ranking ? $ranking->posicao : null;
    }
}
