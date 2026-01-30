<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Simulado;
use App\Models\Edital;

class SidebarComposer
{
    public function compose(View $view)
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        // 1. Simulado em Andamento (Assume NULL score implies not finished)
        // If pontuacao_final is not nullable in DB, this might need adjustment, but usually it is.
        $simuladoAtivo = Simulado::where('usuario_id', $user->id)
            ->whereNull('pontuacao_final')
            ->orderBy('data_criacao', 'desc')
            ->first();

        // 2. Última Videoaula (from history)
        $ultimaVideoaula = DB::table('videoaulas_progresso as vp')
            ->join('videoaulas as v', 'vp.videoaula_id', '=', 'v.id')
            ->where('vp.usuario_id', $user->id)
            ->orderBy('vp.updated_at', 'desc') // Assuming timestamps on pivot/progress table
            ->select('v.id', 'v.titulo', 'vp.tempo_assistido', 'v.duracao')
            ->first();

        // 3. Badges / Counts
        $simuladosCount = Simulado::where('usuario_id', $user->id)->count();
        $editaisCount = Edital::where('usuario_id', $user->id)->count();
        
        // 4. Questões Erradas (Count) for "Revisar"
        // This query might be expensive, so keep it simple. 
        // Assuming table 'respostas_usuario' exists as seen in SimuladosController
        $questoesErradasCount = DB::table('respostas_usuario')
            ->where('usuario_id', $user->id)
            ->where('correta', 0)
            ->count();

        $view->with([
            'simuladoAtivo' => $simuladoAtivo,
            'ultimaVideoaula' => $ultimaVideoaula,
            'simuladosCount' => $simuladosCount,
            'editaisCount' => $editaisCount,
            'questoesErradasCount' => $questoesErradasCount,
        ]);
    }
}
