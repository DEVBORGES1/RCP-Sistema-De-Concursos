<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenAIService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('OPENAI_API_KEY');
    }

    /**
     * Send a raw prompt to OpenAI and get the text response.
     * Expects strict JSON if the prompt asks for it.
     */
    public function gerarTextoRaw(string $prompt): string
    {
        if (!$this->apiKey) {
            // Se não tiver chave instalada na .env, vamos mockar uma correção perfeita para o Flow Funcionar (MVP).
            // Retorna um JSON simulando a IA
            return '{
                "nota_total": 85,
                "criterios_nota": {
                    "gramatica": 90,
                    "coesao_coerencia": 80,
                    "fuga_tema": 85
                },
                "feedback_ia": "Seu texto apresenta uma boa estrutura argumentativa, compreendendo bem o tema proposto sobre Inteligência Artificial. No entanto, houve alguns deslizes de concordância verbal no segundo parágrafo. Procure também diversificar os conectivos para melhorar a fluidez (coesão) entre as frases."
            }';
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini', // Modelo rápido e barato p/ correção
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Você é um corretor rigoroso de redação de concurso público. Retorne apenas JSON válido como solicitado, sem markdown ou formatação externa.'
                ],
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 800,
        ]);

        if ($response->successful()) {
            return $response->json('choices.0.message.content');
        }

        throw new \Exception('Falha na comunicação com a OpenAI: ' . $response->body());
    }
}
