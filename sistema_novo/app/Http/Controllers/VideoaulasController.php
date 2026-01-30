<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\VideoaulaCategoria;
use App\Models\Videoaula;

class VideoaulasController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Verificar se existem categorias, se não, criar as padrão (Logica legada)
        // Isso idealmente deveria ser um Seeder, mas vamos manter a compatibilidade da lógica original por enquanto
        if (VideoaulaCategoria::where('ativo', 1)->count() == 0) {
            $categoriasPadrao = [
                ['nome' => 'Português', 'descricao' => 'Língua Portuguesa - Gramática, Interpretação de Texto, Redação', 'cor' => '#3498db', 'icone' => 'fas fa-book', 'ordem' => 1, 'ativo' => 1],
                ['nome' => 'Matemática', 'descricao' => 'Matemática - Álgebra, Geometria, Trigonometria, Estatística', 'cor' => '#e74c3c', 'icone' => 'fas fa-calculator', 'ordem' => 2, 'ativo' => 1],
                ['nome' => 'Raciocínio Lógico', 'descricao' => 'Raciocínio Lógico e Quantitativo', 'cor' => '#9b59b6', 'icone' => 'fas fa-brain', 'ordem' => 3, 'ativo' => 1],
                ['nome' => 'Direito Constitucional', 'descricao' => 'Direito Constitucional e Legislação', 'cor' => '#16a085', 'icone' => 'fas fa-gavel', 'ordem' => 4, 'ativo' => 1],
                ['nome' => 'Direito Administrativo', 'descricao' => 'Direito Administrativo e Licitações', 'cor' => '#27ae60', 'icone' => 'fas fa-landmark', 'ordem' => 5, 'ativo' => 1],
                ['nome' => 'Direito Penal', 'descricao' => 'Direito Penal e Processo Penal', 'cor' => '#c0392b', 'icone' => 'fas fa-balance-scale', 'ordem' => 6, 'ativo' => 1],
                ['nome' => 'Direito Civil', 'descricao' => 'Direito Civil e Processo Civil', 'cor' => '#2980b9', 'icone' => 'fas fa-scroll', 'ordem' => 7, 'ativo' => 1],
                ['nome' => 'Direito do Trabalho', 'descricao' => 'Direito do Trabalho e Processo do Trabalho', 'cor' => '#f39c12', 'icone' => 'fas fa-briefcase', 'ordem' => 8, 'ativo' => 1],
                ['nome' => 'Direito Tributário', 'descricao' => 'Direito Tributário e Fiscal', 'cor' => '#e67e22', 'icone' => 'fas fa-file-invoice-dollar', 'ordem' => 9, 'ativo' => 1],
                ['nome' => 'Informática', 'descricao' => 'Informática Básica e Avançada', 'cor' => '#1abc9c', 'icone' => 'fas fa-laptop-code', 'ordem' => 10, 'ativo' => 1],
                ['nome' => 'Atualidades', 'descricao' => 'Atualidades e Conhecimentos Gerais', 'cor' => '#34495e', 'icone' => 'fas fa-newspaper', 'ordem' => 11, 'ativo' => 1],
                ['nome' => 'Administração Pública', 'descricao' => 'Administração Pública e Gestão', 'cor' => '#95a5a6', 'icone' => 'fas fa-building', 'ordem' => 12, 'ativo' => 1],
                ['nome' => 'Legislação Específica', 'descricao' => 'Legislação Específica do Cargo', 'cor' => '#2c3e50', 'icone' => 'fas fa-file-alt', 'ordem' => 13, 'ativo' => 1],
                ['nome' => 'Noções de Gestão', 'descricao' => 'Noções de Gestão Pública e Organizacional', 'cor' => '#7f8c8d', 'icone' => 'fas fa-chart-line', 'ordem' => 14, 'ativo' => 1],
                ['nome' => 'Ética', 'descricao' => 'Ética no Serviço Público', 'cor' => '#8e44ad', 'icone' => 'fas fa-hands-helping', 'ordem' => 15, 'ativo' => 1]
            ];
            
            foreach ($categoriasPadrao as $cat) {
                 VideoaulaCategoria::create($cat);
            }
        }

        // Query Stats
        $categorias = DB::select("
            SELECT 
                vc.*,
                COUNT(v.id) as total_videoaulas,
                COUNT(vp.videoaula_id) as videoaulas_iniciadas,
                COUNT(CASE WHEN vp.concluida = 1 THEN 1 END) as videoaulas_concluidas,
                ROUND(
                    CASE 
                        WHEN COUNT(v.id) > 0 THEN 
                            (COUNT(CASE WHEN vp.concluida = 1 THEN 1 END) / COUNT(v.id)) * 100 
                        ELSE 0 
                    END, 1
                ) as porcentagem_concluida
            FROM videoaulas_categorias vc
            LEFT JOIN videoaulas v ON vc.id = v.categoria_id AND v.ativo = 1
            LEFT JOIN videoaulas_progresso vp ON v.id = vp.videoaula_id AND vp.usuario_id = ?
            WHERE vc.ativo = 1
            GROUP BY vc.id
            ORDER BY vc.ordem, vc.nome
        ", [$user->id]);

        $stats = DB::selectOne("
            SELECT 
                COUNT(DISTINCT v.id) as total_videoaulas,
                COUNT(DISTINCT vp.videoaula_id) as videoaulas_iniciadas,
                COUNT(CASE WHEN vp.concluida = 1 THEN 1 END) as videoaulas_concluidas,
                SUM(v.duracao) as duracao_total,
                SUM(CASE WHEN vp.concluida = 1 THEN v.duracao ELSE 0 END) as duracao_assistida
            FROM videoaulas v
            LEFT JOIN videoaulas_progresso vp ON v.id = vp.videoaula_id AND vp.usuario_id = ?
            WHERE v.ativo = 1
        ", [$user->id]);

        return view('videoaulas.index', compact('categorias', 'stats'));
    }

    public function show($id)
    {
        $user = Auth::user();
        $categoria = VideoaulaCategoria::findOrFail($id);

        // Recuperar videoaulas com progresso
        $videoaulas = DB::select("
            SELECT 
                v.*,
                vp.tempo_assistido,
                vp.concluida,
                CASE 
                    WHEN vp.concluida = 1 THEN 100
                    WHEN vp.tempo_assistido > 0 AND v.duracao > 0 THEN 
                        ROUND((vp.tempo_assistido / (v.duracao * 60)) * 100, 1)
                    ELSE 0 
                END as progresso_percentual
            FROM videoaulas v
            LEFT JOIN videoaulas_progresso vp ON v.id = vp.videoaula_id AND vp.usuario_id = ?
            WHERE v.categoria_id = ? AND v.ativo = 1
            ORDER BY v.ordem, v.titulo
        ", [$user->id, $id]);

        $temas = [];
        $temasPadrao = [
             // Manter lista padrão (simplificada aqui, usar a mesma do PHP original ou mover para config/helper)
             'Português' => ['Gramática', 'Interpretação de Texto', 'Redação', 'Ortografia', 'Pontuação', 'Morfologia', 'Sintaxe'],
             'Matemática' => ['Álgebra', 'Geometria', 'Trigonometria', 'Estatística', 'Aritmética', 'Funções'],
             // ... Adicionar os outros conforme necessário, ou deixar dinâmico
        ];

        // Lógica de Agrupamento
        if (empty($videoaulas) && isset($temasPadrao[$categoria->nome])) {
            foreach ($temasPadrao[$categoria->nome] as $temaNome) {
               $temas[$temaNome] = ['nome' => $temaNome, 'videoaulas' => [], 'total' => 0, 'concluidas' => 0, 'progresso' => 0];
            }
        } else {
            foreach ($videoaulas as $aula) {
                // Extrai tema do título "Tema - Aula" ou "Tema: Aula"
                $temaNome = $aula->titulo; 
                if (strpos($aula->titulo, ' - ') !== false) {
                    $parts = explode(' - ', $aula->titulo, 2);
                    $temaNome = trim($parts[0]);
                } elseif (strpos($aula->titulo, ': ') !== false) {
                    $parts = explode(': ', $aula->titulo, 2);
                    $temaNome = trim($parts[0]);
                }

                if (!isset($temas[$temaNome])) {
                    $temas[$temaNome] = ['nome' => $temaNome, 'videoaulas' => [], 'total' => 0, 'concluidas' => 0, 'progresso' => 0];
                }
                
                $temas[$temaNome]['videoaulas'][] = $aula;
                $temas[$temaNome]['total']++;
                if ($aula->concluida) {
                    $temas[$temaNome]['concluidas']++;
                }
            }
        }

        // Calcular progresso dos temas
        foreach ($temas as &$t) {
            if ($t['total'] > 0) {
                $t['progresso'] = round(($t['concluidas'] / $t['total']) * 100, 1);
            }
        }
        
        // Transformar em array numérico para a view
        $temas = array_values($temas);

        return view('videoaulas.show', compact('categoria', 'temas'));
    }

    public function player($id)
    {
        $user = Auth::user();
        
        $videoaula = DB::table('videoaulas as v')
            ->join('videoaulas_categorias as vc', 'v.categoria_id', '=', 'vc.id')
            ->leftJoin('videoaulas_progresso as vp', function($join) use ($user) {
                $join->on('v.id', '=', 'vp.videoaula_id')
                     ->where('vp.usuario_id', '=', $user->id);
            })
            ->where('v.id', $id)
            ->where('v.ativo', 1)
            ->select(
                'v.*',
                'vc.nome as categoria_nome',
                'vc.cor as categoria_cor',
                'vc.icone as categoria_icone',
                'vp.tempo_assistido',
                'vp.concluida'
            )
            ->first();

        if (!$videoaula) {
            return redirect()->route('videoaulas.index')->with('erro', 'Videoaula não encontrada.');
        }

        $progresso_percentual = 0;
        if ($videoaula->concluida) {
            $progresso_percentual = 100;
        } elseif ($videoaula->tempo_assistido > 0 && $videoaula->duracao > 0) {
            $progresso_percentual = round(($videoaula->tempo_assistido / ($videoaula->duracao * 60)) * 100, 1);
        }

        // Relacionadas
        $relacionadas = DB::table('videoaulas')
            ->where('categoria_id', $videoaula->categoria_id)
            ->where('id', '!=', $id)
            ->where('ativo', 1)
            ->orderBy('ordem')
            ->orderBy('titulo')
            ->limit(5)
            ->get();

        return view('videoaulas.player', compact('videoaula', 'progresso_percentual', 'relacionadas'));
    }

    public function atualizarProgresso(Request $request, $id)
    {
        $user = Auth::user();
        
        $request->validate([
            'tempo_assistido' => 'required|numeric',
            'concluida' => 'required|boolean'
        ]);

        $tempo = $request->input('tempo_assistido');
        $concluida = $request->input('concluida');

        DB::table('videoaulas_progresso')->updateOrInsert(
            ['usuario_id' => $user->id, 'videoaula_id' => $id],
            [
                'tempo_assistido' => $tempo,
                'concluida' => $concluida,
                'data_conclusao' => $concluida ? now() : null,
                'updated_at' => now()
            ]
        );

        // Se concluiu, dar pontos (lógica simplificada, idealmente chamar serviço de gamificação)
        if ($concluida) {
             $user->pontos += 10; // Exemplo
             $user->save();
        }

        return response()->json(['success' => true]);
    }
}
