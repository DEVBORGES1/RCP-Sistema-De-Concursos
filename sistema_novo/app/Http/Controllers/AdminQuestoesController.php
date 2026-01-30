<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questao;
use App\Services\GeminiService;
use App\Models\Disciplina;
use App\Models\Edital;
use Illuminate\Support\Facades\DB;

class AdminQuestoesController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->geminiService = $geminiService;
    }

    public function create()
    {
        // Carregar disciplinas para associar (opcional)
        $disciplinas = Disciplina::orderBy('nome_disciplina')->get();
        // Carregar editais (opcional)
        $editais = Edital::orderBy('nome_arquivo')->get();

        return view('admin.questoes.create', compact('disciplinas', 'editais'));
    }

    public function gerar(Request $request)
    {
        $request->validate([
            'texto_base' => 'required|string|min:50',
            'quantidade' => 'nullable|integer|min:1|max:20',
            'nivel' => 'nullable|string',
            'disciplina_id' => 'nullable|exists:disciplinas,id',
            'edital_id' => 'nullable|exists:editais,id',
        ]);

        $texto = $request->input('texto_base');
        $quantidade = $request->input('quantidade', 5);
        $nivel = $request->input('nivel', 'Médio');
        
        $questoesGeradas = $this->geminiService->gerarQuestoes($texto, $quantidade, $nivel);

        if (isset($questoesGeradas['erro'])) {
            return back()->with('erro', $questoesGeradas['erro'])->withInput();
        }

        return view('admin.questoes.review', [
            'questoes' => $questoesGeradas,
            'disciplina_id' => $request->input('disciplina_id'),
            'edital_id' => $request->input('edital_id')
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'questoes' => 'required|array',
            'questoes.*.enunciado' => 'required|string',
            'questoes.*.alternativa_a' => 'required|string',
            'questoes.*.alternativa_b' => 'required|string',
            'questoes.*.alternativa_c' => 'required|string',
            'questoes.*.alternativa_d' => 'required|string',
            'questoes.*.alternativa_e' => 'required|string',
            'questoes.*.alternativa_correta' => 'required|string|size:1',
            'disciplina_id' => 'nullable|exists:disciplinas,id',
            'edital_id' => 'nullable|exists:editais,id',
        ]);

        $questoesData = $request->input('questoes');
        $disciplinaId = $request->input('disciplina_id');
        $editalId = $request->input('edital_id');

        // Se não tiver edital_id, mas o banco exigir, podemos precisar logica extra.
        // Assumimos que a migration nullable passou.

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($questoesData as $q) {
                // Verificar se foi selecionada (checkbox na view enviaria apenas as selecionadas, ou filtramos aqui)
                // Na view review, vamos fazer um array apenas das selecionadas ou enviar todas e um campo 'selected'
                
                // Assumindo que o form envia apenas o que deve ser salvo (ou tem um indice 'save')
                if (!isset($q['save'])) continue;

                Questao::create([
                    'enunciado' => $q['enunciado'],
                    'alternativa_a' => $q['alternativa_a'],
                    'alternativa_b' => $q['alternativa_b'],
                    'alternativa_c' => $q['alternativa_c'],
                    'alternativa_d' => $q['alternativa_d'],
                    'alternativa_e' => $q['alternativa_e'],
                    'alternativa_correta' => strtoupper($q['alternativa_correta']),
                    'disciplina_id' => $disciplinaId,
                    'edital_id' => $editalId, // Pode ser null agora
                ]);
                $count++;
            }
            DB::commit();

            return redirect()->route('questoes.index')->with('sucesso', "$count questões cadastradas com sucesso!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('erro', 'Erro ao salvar questões: ' . $e->getMessage());
        }
    }
}
