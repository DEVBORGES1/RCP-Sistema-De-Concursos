<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RedacaoTema;
use App\Models\Redacao;
use Illuminate\Support\Facades\Auth;
use App\Services\OpenAIService;
use App\Services\GamificationService;

class RedacaoController extends Controller
{
    protected $openAIService;
    protected $gamification;

    public function __construct(OpenAIService $openAIService, GamificationService $gamification)
    {
        $this->openAIService = $openAIService;
        $this->gamification = $gamification;
    }

    public function index()
    {
        $temas = RedacaoTema::where('ativo', true)->orderBy('created_at', 'desc')->get();
        return view('redacoes.index', compact('temas'));
    }

    public function escrever($id)
    {
        $tema = RedacaoTema::findOrFail($id);
        
        // Verifica se o usuário já enviou esta redação (regra simples: 1 envio por tema, ou pode permitir multiplos)
        $envioAnterior = Redacao::where('tema_id', $id)->where('usuario_id', Auth::id())->first();
        if ($envioAnterior) {
            return redirect()->route('redacoes.feedback', $envioAnterior->id)->with('info', 'Você já enviou uma redação para este tema. Veja o feedback abaixo.');
        }

        return view('redacoes.escrever', compact('tema'));
    }

    public function submit(Request $request, $id)
    {
        $request->validate([
            'texto_enviado' => 'required|min:100|max:5000'
        ], [
            'texto_enviado.min' => 'Sua redação precisa ter no mínimo 100 caracteres.',
            'texto_enviado.max' => 'Sua redação excedeu o limite de 5000 caracteres.'
        ]);

        $tema = RedacaoTema::findOrFail($id);
        
        // --- CHAMA A IA PARA AVALIAR ---
        $prompt = "Aja como um corretor rigoroso de redação de concurso público.
O tema da redação é: '{$tema->titulo}'.
O texto motivador foi: '{$tema->texto_motivador}'.
O texto do aluno é o seguinte:
\"{$request->texto_enviado}\"

Avalie o texto me retornando EXATAMENTE UM JSON válido (sem markdown de formatação) com a seguinte estrutura:
{
    \"nota_total\": (int de 0 a 100),
    \"criterios_nota\": {
        \"gramatica\": (int de 0 a 100),
        \"coesao_coerencia\": (int de 0 a 100),
        \"fuga_tema\": (int de 0 a 100)
    },
    \"feedback_ia\": \"Um parágrafo detalhado com elogios, críticas e o que melhorar. Formate com tags HTML como <b> ou <br> se precisar.\"
}";

        try {
            // Em um app real de produção pesada, isso seria enviado para uma Queue (Job).
            // Para manter a UI fluída para a demo usaremos await bloqueante curto
            $respostaIA = $this->openAIService->gerarTextoRaw($prompt);
            
            // Tenta decodificar o JSON retornado pela IA
            $dadosAvaliacao = json_decode(trim($respostaIA, " \t\n\r\0\x0B`"), true);

            if (!$dadosAvaliacao || !isset($dadosAvaliacao['nota_total'])) {
                 throw new \Exception("A IA não retornou um formato de nota válido.");
            }

            // Salva o envido da redação
            $redacao = Redacao::create([
                'usuario_id' => Auth::id(),
                'tema_id' => $tema->id,
                'texto_enviado' => $request->texto_enviado,
                'nota_total' => $dadosAvaliacao['nota_total'],
                'criterios_nota' => $dadosAvaliacao['criterios_nota'],
                'feedback_ia' => $dadosAvaliacao['feedback_ia']
            ]);

            // Gamificação: Adiciona XP p/ o aluno por enviar redação
            $xpGanho = 200 + ($dadosAvaliacao['nota_total'] * 2); // Ex: tira 80 = 200 + 160 = 360 XP
            $this->gamification->adicionarPontos(Auth::id(), $xpGanho);

            return redirect()->route('redacoes.feedback', $redacao->id)->with('success', "Redação enviada e corrigida com sucesso! Você ganhou {$xpGanho} XP.");

        } catch (\Exception $e) {
            return back()->with('error', 'Ocorreu um erro ao corrigir sua redação usando nossa inteligência artificial: ' . $e->getMessage())->withInput();
        }
    }

    public function feedback($redacao_id)
    {
        $redacao = Redacao::where('id', $redacao_id)->where('usuario_id', Auth::id())->with('tema')->firstOrFail();
        return view('redacoes.feedback', compact('redacao'));
    }
}
