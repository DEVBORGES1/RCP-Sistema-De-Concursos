<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Videoaula; // Assumindo existência ou usando DB Facade
use Carbon\Carbon;

class CertificadoController extends Controller
{
    public function gerar($categoriaId)
    {
        $user = Auth::user();
        
        // 1. Obter Categoria
        $categoria = DB::table('videoaulas_categorias')->where('id', $categoriaId)->first();
        if (!$categoria) {
            return redirect()->route('perfil.index')->withErrors(['erro' => 'Categoria não encontrada.']);
        }

        // 2. Verificar Progresso (Lógica migrada de GamificacaoRefatorada)
        $totalVideos = DB::table('videoaulas')
            ->where('categoria_id', $categoriaId)
            ->where('ativo', 1)
            ->count();
            
        if ($totalVideos == 0) {
             return redirect()->route('perfil.index')->withErrors(['erro' => 'Esta categoria não possui aulas.']);
        }

        $concluidas = DB::table('videoaulas as v')
            ->join('videoaulas_progresso as vp', 'v.id', '=', 'vp.videoaula_id')
            ->where('v.categoria_id', $categoriaId)
            ->where('v.ativo', 1)
            ->where('vp.usuario_id', $user->id)
            ->where('vp.concluida', 1)
            ->count();

        if ($concluidas < $totalVideos) {
            return redirect()->route('perfil.index')->withErrors(['erro' => 'Você precisa concluir todas as aulas desta matéria para emitir o certificado.']);
        }

        // 3. Obter Estatísticas para o Certificado
        $minutosTotal = DB::table('videoaulas as v')
            ->join('videoaulas_progresso as vp', 'v.id', '=', 'vp.videoaula_id')
            ->where('v.categoria_id', $categoriaId)
            ->where('vp.usuario_id', $user->id)
            ->where('vp.concluida', 1)
            ->sum('v.duracao');
            
        $horasEstudadas = round($minutosTotal / 60, 1);
        
        // Dados para View
        $dados = [
            'user' => $user,
            'categoria' => $categoria,
            'horas' => $horasEstudadas,
            'videoaulas_count' => $concluidas,
            'data_emissao' => now()->format('d/m/Y'),
            'codigo_validacao' => 'RCP-' . $user->id . '-' . $categoriaId . '-' . now()->format('YmdHis')
        ];

        return view('certificados.modelo', $dados);
    }
}
