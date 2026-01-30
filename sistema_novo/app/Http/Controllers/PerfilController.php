<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Conquista;

class PerfilController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // --- Lógica de Nível e XP ---
        // Exemplo: Nível = (Pontos / 1000) + 1. XP para próximo nível = 1000.
        // Se pontos = 2500 -> Nível 3 (2.5). XP no nível atual = 500.
        $pontos = $user->pontos ?? 0;
        $xpPorNivel = 1000;
        $nivel = floor($pontos / $xpPorNivel) + 1;
        $xpAtual = $pontos % $xpPorNivel;
        $xpProximoNivel = $xpPorNivel; // Fixo por enquanto, poderia ser progressivo
        $progressoNivel = ($xpAtual / $xpProximoNivel) * 100;

        // --- Stats Gerais ---
        $stats = [
            'posicao' => 12, // Ranking fictício por enquanto
            'melhor_pontuacao' => DB::table('simulados')->where('usuario_id', $user->id)->max('pontuacao_final') ?? 0,
            'maior_streak' => 5, // Placeholder - Implementar tabela de daily logs depois
            'certificados' => DB::table('videoaulas_progresso')->where('usuario_id', $user->id)->where('concluida', 1)->distinct('videoaula_id')->count('videoaula_id'), // Contagem simples de aulas
            'questoes_respondidas' => DB::table('respostas_usuario')->where('usuario_id', $user->id)->count(),
            'questoes_corretas' => DB::table('respostas_usuario')->where('usuario_id', $user->id)->where('correta', 1)->count(),
            'simulados_feitos' => DB::table('simulados')->where('usuario_id', $user->id)->whereNotNull('pontuacao_final')->count(),
            // Activity Chart Data (Last 7 Days)
            'atividade_semanal' => $this->getAtividadeSemanal($user->id),
            // Focus Subjects
            'materias_foco' => $this->getMateriasFoco($user->id),
        ];
        
        $stats['taxa_acerto'] = $stats['questoes_respondidas'] > 0 
            ? round(($stats['questoes_corretas'] / $stats['questoes_respondidas']) * 100, 1) 
            : 0;

        // Conquistas
        $conquistas = Conquista::all(); 
        $conquistasUsuario = DB::table('usuarios_conquistas')->where('usuario_id', $user->id)->pluck('conquista_id')->toArray();

        // Certificados (Categorias 100% concluídas) - Mantendo lógica original
        $certificados = DB::table('videoaulas_categorias as vc')
            ->join('videoaulas as v', 'v.categoria_id', '=', 'vc.id')
            ->leftJoin('videoaulas_progresso as vp', function($join) use ($user) {
                $join->on('vp.videoaula_id', '=', 'v.id')
                     ->where('vp.usuario_id', '=', $user->id)
                     ->where('vp.concluida', '=', 1);
            })
            ->where('v.ativo', 1)
            ->select('vc.id as categoria_id', 'vc.nome as categoria', DB::raw('COUNT(v.id) as total'), DB::raw('COUNT(vp.id) as concluidos'), DB::raw('MAX(vp.data_conclusao) as data_conclusao'))
            ->groupBy('vc.id', 'vc.nome')
            ->havingRaw('COUNT(v.id) > 0 AND COUNT(v.id) = COUNT(vp.id)')
            ->orderBy('data_conclusao', 'desc')
            ->get();
            
        // Dados de perfil estendidos (caso o model User não tenha sido atualizado ainda no Eloquent, usamos array merge se necessário, mas Blade acessa direto)

        return view('perfil.index', compact('user', 'stats', 'conquistas', 'conquistasUsuario', 'certificados', 'nivel', 'xpAtual', 'xpProximoNivel', 'progressoNivel'));
    }

    private function getAtividadeSemanal($userId) {
        // Retorna array com dias da semana e qtd de questões/estudo
        // Ex: ['Seg' => 10, 'Ter' => 5, ...] 
        // Mock por enquanto, idealmente buscar de um log de atividades
        return [
            ['dia' => 'D', 'valor' => 0],
            ['dia' => 'S', 'valor' => 15],
            ['dia' => 'T', 'valor' => 30],
            ['dia' => 'Q', 'valor' => 45],
            ['dia' => 'Q', 'valor' => 20],
            ['dia' => 'S', 'valor' => 60],
            ['dia' => 'S', 'valor' => 10], // Hoje
        ];
    }

    private function getMateriasFoco($userId) {
        // Busca as top 3 disciplinas com mais erros ou mais estudadas
        // Mock
        return [
            ['nome' => 'Português', 'acerto' => 45, 'cor' => '#e74c3c'],
            ['nome' => 'Raciocínio Lógico', 'acerto' => 70, 'cor' => '#f1c40f'],
            ['nome' => 'Dir. Constitucional', 'acerto' => 85, 'cor' => '#2ecc71'],
        ];
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nome' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'senha_atual' => 'nullable|required_with:nova_senha',
            'nova_senha' => 'nullable|min:6|confirmed',
            // Novos campos
            'foto_perfil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'area_interesse' => 'nullable|string|max:100',
            'escolaridade' => 'nullable|string|max:50',
            'cargos_alvo' => 'nullable|string|max:500',
            'linkedin' => 'nullable|url|max:255',
            'biografia' => 'nullable|string|max:1000',
        ]);

        // Atualizar dados básicos
        $user->nome = $request->nome;
        $user->email = $request->email;
        
        // Upload de Foto (Cropped Base64)
        if ($request->filled('cropped_avatar')) {
            // Decodifica a imagem base64
            $imageData = $request->cropped_avatar;
            
            // Remove o prefixo data:image/...;base64,
            if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
                $type = strtolower($type[1]); // jpg, png, gif
                
                $imageData = base64_decode($imageData);
                
                if ($imageData !== false) {
                    // Remove foto antiga se existir
                    if ($user->foto_perfil && str_contains($user->foto_perfil, 'storage/avatars')) {
                        $oldPath = str_replace('/storage/', '', $user->foto_perfil);
                        \Storage::disk('public')->delete($oldPath);
                    }
                    
                    $fileName = 'avatar_' . $user->id . '_' . time() . '.jpg';
                    
                    // Salva em storage/app/public/avatars
                    \Storage::disk('public')->put('avatars/' . $fileName, $imageData);
                    
                    // Gera URL pública
                    $user->foto_perfil = '/storage/avatars/' . $fileName;
                }
            }
        }

        // Atualizar novos campos
        $user->area_interesse = $request->area_interesse;
        $user->escolaridade = $request->escolaridade;
        $user->cargos_alvo = $request->cargos_alvo;
        $user->linkedin = $request->linkedin;
        $user->biografia = $request->biografia;

        // Atualizar senha se fornecida
        if ($request->filled('nova_senha')) {
            if (!Hash::check($request->senha_atual, $user->getAuthPassword())) {
                return back()->withErrors(['senha_atual' => 'A senha atual está incorreta.']);
            }
            $user->senha_hash = Hash::make($request->nova_senha);
        }

        $user->save();

        return back()->with('success', 'Perfil atualizado com sucesso!');
    }
}
