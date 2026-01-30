<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Simulado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SimuladosController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $predefinedNames = [
            'Simulado Geral Básico', 
            'Simulado Português e Matemática', 
            'Simulado Conhecimentos Específicos', 
            'Simulado Raciocínio e Informática', 
            'Simulado Completo'
        ];
        
        // Histórico (Meus simulados personalizados)
        $simulados = Simulado::where('usuario_id', $user->id)
            ->whereNotIn('nome', $predefinedNames)
            ->orderBy('data_criacao', 'desc')
            ->get();

        // Disciplinas disponíveis
        $disciplinas = DB::select("SELECT DISTINCT d.* FROM disciplinas d 
            JOIN questoes q ON d.id = q.disciplina_id 
            WHERE q.edital_id IN (SELECT id FROM editais WHERE usuario_id = ?)", [$user->id]);

        // Simulados Pré-definidos
        $simuladosPredefinidos = Simulado::where('usuario_id', $user->id)
             ->whereIn('nome', $predefinedNames)
             ->orderBy('data_criacao', 'desc')
             ->get();

        return view('simulados.index', compact('simulados', 'disciplinas', 'simuladosPredefinidos'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Lógica para Pré-definidos
        if ($request->has('predefined')) {
            $slug = $request->input('predefined');
            $configs = [
                'geral' => ['nome' => 'Simulado Geral Básico', 'qtd' => 15, 'disciplina_id' => null],
                'portugues-matematica' => ['nome' => 'Simulado Português e Matemática', 'qtd' => 12, 'disciplina_id' => 'filter_pm'],
                'especificos' => ['nome' => 'Simulado Conhecimentos Específicos', 'qtd' => 10, 'disciplina_id' => 'filter_esp'],
                'logico-informatica' => ['nome' => 'Simulado Raciocínio e Informática', 'qtd' => 10, 'disciplina_id' => 'filter_li'],
                'completo' => ['nome' => 'Simulado Completo', 'qtd' => 30, 'disciplina_id' => null],
            ];

            if (!isset($configs[$slug])) {
                return redirect()->back()->with('erro', 'Simulado inválido');
            }

            $config = $configs[$slug];
            $questoes = $this->selecionarQuestoes($config, $user->id);
            
            if ($questoes->isEmpty()) {
                return redirect()->back()->with('erro', 'sem_questoes')->with('mensagem', 'Não há questões suficientes');
            }

            $simulado = $this->criarSimuladoNoBanco($user->id, $config['nome'], $questoes);
            return redirect()->route('simulados.show', $simulado->id);
        }

        // Lógica Customizada
        $request->validate([
            'nome_simulado' => 'required|string|max:255',
            'quantidade_questoes' => 'required|integer|min:1',
        ]);

        $config = [
            'nome' => $request->nome_simulado,
            'qtd' => $request->quantidade_questoes,
            'disciplina_id' => $request->disciplina_id
        ];

        // Lógica Customizada
        $request->validate([
            'nome_simulado' => 'required|string|max:255',
            'quantidade_questoes' => 'required|integer|min:1',
        ]);

        $config = [
            'nome' => $request->nome_simulado,
            'qtd' => $request->quantidade_questoes,
            'disciplina_id' => $request->disciplina_id
        ];

        // Se uma disciplina específica foi selecionada, tenta gerar questões inéditas com IA
        if ($request->disciplina_id) {
            $disciplina = \App\Models\Disciplina::find($request->disciplina_id);
            if ($disciplina) {
                // Instancia serviço (idealmente injetado, mas aqui new para rapidez)
                $gemini = new \App\Services\GeminiService();
                
                try {
                    // Solicita questões inéditas
                    $novasQuestoes = $gemini->gerarQuestoesPorDisciplina(
                        $disciplina->nome_disciplina, 
                        'Difícil', 
                        (int) $request->quantidade_questoes
                    );

                    if (!empty($novasQuestoes)) {
                        \Illuminate\Support\Facades\Log::info("SimuladosController: Recebeu " . count($novasQuestoes) . " questões da IA.");
                        
                        $idsGerados = [];
                        foreach ($novasQuestoes as $index => $q) {
                            // Proteção caso a IA retorne um objeto único solto em vez de array de objetos
                            if (!is_array($q)) {
                                \Illuminate\Support\Facades\Log::warning("Item $index não é array: " . json_encode($q));
                                continue;
                            }
                            
                            $novaQ = \App\Models\Questao::create([
                                'edital_id' => $disciplina->edital_id,
                                'disciplina_id' => $disciplina->id,
                                'enunciado' => $q['enunciado'] ?? 'Sem enunciado',
                                'alternativa_a' => $q['alternativa_a'] ?? 'A',
                                'alternativa_b' => $q['alternativa_b'] ?? 'B',
                                'alternativa_c' => $q['alternativa_c'] ?? 'C',
                                'alternativa_d' => $q['alternativa_d'] ?? 'D',
                                'alternativa_e' => $q['alternativa_e'] ?? 'E',
                                'alternativa_correta' => $q['alternativa_correta'] ?? 'A'
                            ]);
                            $idsGerados[] = $novaQ;
                        }
                        
                        // Cria o simulado DIRETAMENTE com essas questões novas
                        $simulado = $this->criarSimuladoNoBanco($user->id, $request->nome_simulado, collect($idsGerados));
                        return redirect()->route('simulados.show', $simulado->id);
                    } else {
                        \Illuminate\Support\Facades\Log::warning("SimuladosController: Recebeu array vazio da IA.");
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error("Erro IA Simulado: " . $e->getMessage());
                    // Silenciosamente falha e deixa o fluxo normal seguir para o banco de dados
                }
            }
        }

        // Fallback: Seleciona questões existentes do banco
        $questoes = $this->selecionarQuestoes($config, $user->id);

        if ($questoes->isEmpty()) {
            return redirect()->back()->with('erro', 'sem_questoes');
        }

        $simulado = $this->criarSimuladoNoBanco($user->id, $request->nome_simulado, $questoes);
        return redirect()->route('simulados.show', $simulado->id);
    }

    public function show(Request $request, $id)
    {
        $user = Auth::user();
        $viewMode = $request->has('view');

        $simulado = Simulado::where('id', $id)
            ->where('usuario_id', $user->id)
            ->firstOrFail();

        if ($simulado->questoes_total == 0) {
            return redirect()->route('simulados.index')->with('erro', 'Simulado sem questões.');
        }
        
        $questoes = $simulado->questoes()->get(); 

        return view('simulados.show', compact('simulado', 'questoes', 'viewMode'));
    }

    public function finalizar(Request $request, $id)
    {
        $user = Auth::user();
        $simulado = Simulado::where('id', $id)->where('usuario_id', $user->id)->firstOrFail();

        $pontosTotal = 0;
        $questoesCorretas = 0;
        $respostasProcessadas = [];

        foreach ($request->all() as $key => $resposta) {
            if (strpos($key, 'questao_') === 0) {
                $questaoId = str_replace('questao_', '', $key);
                
                if (in_array($questaoId, $respostasProcessadas)) continue;
                $respostasProcessadas[] = $questaoId;

                $questao = DB::table('questoes')->where('id', $questaoId)->first();
                if (!$questao) continue;

                $respostaUser = strtoupper(trim($resposta));
                $respostaCorreta = strtoupper(trim($questao->alternativa_correta));
                $acertou = ($respostaUser == $respostaCorreta);
                $pontosQuestao = $acertou ? 10 : 0; 

                DB::table('simulados_questoes')
                    ->where('simulado_id', $simulado->id)
                    ->where('questao_id', $questaoId)
                    ->update([
                        'resposta_usuario' => $respostaUser,
                        'correta' => $acertou
                    ]);

                try {
                    DB::table('respostas_usuario')->insertOrIgnore([
                        'usuario_id' => $user->id,
                        'questao_id' => $questaoId,
                        'resposta' => $resposta,
                        'correta' => $acertou,
                        'pontos_ganhos' => $pontosQuestao,
                    ]);
                } catch (\Exception $e) { }

                $pontosTotal += $pontosQuestao;
                if ($acertou) $questoesCorretas++;
            }
        }

        $simulado->questoes_corretas = $questoesCorretas;
        $simulado->pontuacao_final = $pontosTotal;
        $simulado->tempo_gasto = $request->input('tempo_gasto', 0);
        $simulado->save();

        $user->pontos += $pontosTotal;
        
        if ($questoesCorretas > 0 && $questoesCorretas == $simulado->questoes_total) {
            $user->pontos += 50; 
        }
        
        $user->save();

        return redirect()->route('simulados.show', ['simulado' => $simulado->id, 'view' => 1]);
    }

    private function selecionarQuestoes($config, $usuarioId)
    {
        $query = DB::table('questoes');

        if (!empty($config['disciplina_id'])) {
            if (is_numeric($config['disciplina_id'])) {
                $query->where('disciplina_id', $config['disciplina_id']);
            }
        }

        return $query->inRandomOrder()->limit($config['qtd'])->get();
    }

    private function criarSimuladoNoBanco($userId, $nome, $questoes)
    {
        $simulado = Simulado::create([
            'usuario_id' => $userId,
            'nome' => $nome,
            'questoes_total' => $questoes->count(),
        ]);

        foreach ($questoes as $questao) {
            DB::table('simulados_questoes')->insert([
                'simulado_id' => $simulado->id,
                'questao_id' => $questao->id
            ]);
        }

        return $simulado;
    }
}
