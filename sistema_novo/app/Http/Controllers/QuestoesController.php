<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Questao;
use App\Models\Edital;
use App\Models\Disciplina;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuestoesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Filtros
        $query = Questao::query();
        
        if ($request->filled('edital_id')) {
            $query->where('edital_id', $request->edital_id);
        }
        
        if ($request->filled('disciplina_id')) {
            $query->where('disciplina_id', $request->disciplina_id);
        }
        
        // Paginação
        $questoes = $query->with(['edital', 'disciplina'])->latest()->paginate(10);
        
        // Listas para filtros
        $editais = Edital::where('usuario_id', $user->id)->orderBy('nome_arquivo')->get();
        // A princípio mostramos disciplinas de todos os editais do usuário
        $disciplinas = Disciplina::whereIn('edital_id', $editais->pluck('id'))->orderBy('nome_disciplina')->get();

        // Estatísticas
        $total_questoes = Questao::count(); // Idealmente filtrar por usuário/contexto se for multi-tenant real
        // StatsMock (implementar real depois)
        $resolvidas = 0;
        $acertos = 0;

        return view('questoes.index', compact('questoes', 'editais', 'disciplinas', 'total_questoes', 'resolvidas', 'acertos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        $editais = Edital::where('usuario_id', $user->id)->orderBy('nome_arquivo')->get();
        return view('questoes.create', compact('editais'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'edital_id' => 'required|exists:editais,id',
            'disciplina_id' => 'nullable|exists:disciplinas,id',
            'enunciado' => 'required|string',
            'alternativa_a' => 'required|string',
            'alternativa_b' => 'required|string',
            'alternativa_c' => 'required|string',
            'alternativa_d' => 'required|string',
            'alternativa_e' => 'required|string',
            'alternativa_correta' => 'required|in:A,B,C,D,E',
        ]);

        Questao::create($request->all());

        return redirect()->route('questoes.index')->with('success', 'Questão adicionada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $questao = Questao::with(['edital', 'disciplina'])->findOrFail($id);
        return view('questoes.show', compact('questao'));
    }

    /**
     * Responde a questão via AJAX ou Form Post
     */
    public function responder(Request $request, $id)
    {
        $request->validate([
            'resposta' => 'required|in:A,B,C,D,E'
        ]);

        $questao = Questao::findOrFail($id);
        $acertou = $request->resposta === $questao->alternativa_correta;
        
        // Lógica de gamificação (pontos)
        // TODO: Salvar resposta do usuário no banco
        // TODO: Adicionar pontos ao User

        if ($request->ajax()) {
            return response()->json([
                'acertou' => $acertou,
                'correta' => $questao->alternativa_correta,
                'mensagem' => $acertou ? 'Parabéns! Resposta correta.' : 'Ops! Resposta incorreta.'
            ]);
        }

        return back()->with('resultado', [
            'acertou' => $acertou,
            'correta' => $questao->alternativa_correta
        ]);
    }
}
