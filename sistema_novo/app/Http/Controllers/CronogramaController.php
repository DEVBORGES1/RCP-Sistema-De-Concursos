<?php

namespace App\Http\Controllers;

use App\Models\Cronograma;
use App\Models\Edital;
use App\Services\CronogramaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CronogramaController extends Controller
{
    protected $cronogramaService;

    public function __construct(CronogramaService $cronogramaService)
    {
        $this->cronogramaService = $cronogramaService;
    }

    public function index()
    {
        $cronogramas = Cronograma::where('usuario_id', Auth::id())
            ->orderBy('data_inicio', 'desc')
            ->get();

        return view('cronogramas.index', compact('cronogramas'));
    }

    public function create()
    {
        $user = Auth::user();
        $editais = Edital::where('usuario_id', $user->id)->with('cargos')->orderBy('nome_arquivo')->get();
        return view('cronogramas.create', compact('editais'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'edital_id' => 'required|exists:editais,id',
            'cargo_id' => 'nullable|exists:cargos,id',
            'titulo' => 'required|string|max:255',
            'data_inicio' => 'required|date',
            'horas_por_dia' => 'required|integer|min:1|max:24',
            'duracao_semanas' => 'required|integer|min:1|max:52',
        ]);

        $edital = Edital::findOrFail($request->edital_id);
        
        // Verificar se edital pertence ao usuário
        if ($edital->usuario_id != Auth::id()) {
            abort(403);
        }

        $resultado = $this->cronogramaService->gerarCronograma(
            Auth::id(),
            $request->edital_id,
            (int) $request->horas_por_dia,
            $request->data_inicio,
            (int) $request->duracao_semanas,
            $request->titulo
        );

        if ($resultado['sucesso']) {
            return redirect()->route('cronogramas.show', $resultado['cronograma']->id)
                ->with('success', 'Cronograma gerado com sucesso!');
        } else {
            return back()->with('error', 'Erro ao gerar cronograma: ' . $resultado['erro']);
        }
    }

    public function show($id)
    {
        $cronograma = Cronograma::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->with(['edital', 'dias.disciplina'])
            ->firstOrFail();

        // Organizar dias para exibição
        $eventos = [];
        foreach ($cronograma->dias as $dia) {
            $eventos[] = [
                'title' => $dia->disciplina->nome_disciplina,
                'start' => $dia->data_estudo,
                'description' => $dia->horas_previstas . 'h'
            ];
        }

        // Agrupar por data para visualização em lista
        $diasAgrupados = $cronograma->dias->groupBy('data_estudo');

        return view('cronogramas.show', compact('cronograma', 'eventos', 'diasAgrupados'));
    }
    public function update(Request $request, $id)
    {
        $cronograma = Cronograma::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'titulo' => 'nullable|string|max:255',
        ]);

        $cronograma->update([
            'titulo' => $request->titulo
        ]);

        return redirect()->route('cronogramas.index')
            ->with('success', 'Cronograma atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $cronograma = Cronograma::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->firstOrFail();

        // Excluir dias associados (se não houver cascade no banco, mas Eloquent geralmente precisa de manual se não tiver no DB)
        // Mas como definimos nas migrations (que não vi), vamos assumir que o Eloquent resolve ou o DB.
        // Vamos deletar manualmente para garantir.
        $cronograma->dias()->delete();
        $cronograma->delete();

        return redirect()->route('cronogramas.index')
            ->with('success', 'Cronograma excluído com sucesso!');
    }

    public function pdf($id)
    {
        $cronograma = Cronograma::where('id', $id)
            ->where('usuario_id', Auth::id())
            ->with(['edital', 'dias.disciplina'])
            ->firstOrFail();

        $diasAgrupados = $cronograma->dias->groupBy('data_estudo');

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cronogramas.pdf', compact('cronograma', 'diasAgrupados'));
        
        return $pdf->download('cronograma_' . $cronograma->id . '.pdf');
    }
}
