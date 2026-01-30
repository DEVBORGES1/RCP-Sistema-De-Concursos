<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    private $apiKey;
    // Usando Gemini Flash Latest (Nome de modelo validado na lista)
    private $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-flash-latest:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function analisarEdital($texto, $contexto = [])
    {
        if (!$this->apiKey) {
            Log::warning('Gemini API Key não configurada.');
            return null;
        }

        // Limita tamanho para evitar erros de memory limit, mantendo contexto razoável
        // Se a gente recortou antes, o texto já deve estar focado.
        $textoLimitado = substr($texto, 0, 100000); 

        $focoCargo = "";
        if (!empty($contexto['cargo_alvo'])) {
            $focoCargo = "ATENÇÃO: O foco deve ser TOTAL no cargo: **" . mb_strtoupper($contexto['cargo_alvo']) . "**. Ignore outros cargos se possível e extraia fielmente as DISCIPLINAS para este.";
        }
        
        $infoContexto = "";
        if(!empty($contexto['orgao'])) $infoContexto .= "Órgão: " . $contexto['orgao'] . ". ";
        if(!empty($contexto['banca'])) $infoContexto .= "Banca: " . $contexto['banca'] . ". ";
        
        $cargoAlvo = $contexto['cargo_alvo'] ?? 'Cargo não especificado';

        $prompt = "Você é um especialista em concursos públicos.

        Analise o trecho do edital abaixo e considere que o candidato escolheu o cargo: \"$cargoAlvo\".
        $infoContexto
        
        Sua missão é extrair estruturadamente:
        1. METADADOS DO CONCURSO:
           - Cidade da Prova (ou município do órgão).
           - Banca Examinadora (Instituição organizadora).
           - Ano do concurso.
        
        2. CARGO E CONTEÚDO:
           - Confirme o NOME COMPLETO do cargo exatamente como aparece no edital.
           - Extraia APENAS as disciplinas cobradas na prova teórica/objetiva para esse cargo.
           - Inclua conhecimentos gerais/básicos e específicos em uma lista única de disciplinas.
           - Inclua temas regionais (Lei Orgânica, História do Município, etc.) como disciplinas.
           - IMPORTANTE: Se o cargo for \"$cargoAlvo\", não invente. Se não achar, retorne vazio.

        FORMATO OBRIGATÓRIO (JSON):
        {
            \"cidade\": \"Nome da Cidade/Município ou null\",
            \"banca\": \"Nome da Banca ou null\",
            \"ano\": \"202X ou null\",
            \"cargos\": [
                {
                    \"cargo\": \"Nome Completo do Cargo Confirmado\",
                    \"disciplinas\": [\"Disciplina 1\", \"Disciplina 2\"]
                }
            ]
        }
        
        Responda SOMENTE com JSON válido, sem comentários, sem markdown (` ```json `), sem texto antes ou depois.
        
        Trecho do Edital:
        " . $textoLimitado;

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($this->baseUrl . '?key=' . $this->apiKey, [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => ['temperature' => 0.1, 'response_mime_type' => 'application/json']
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
                
                // Extração robusta de JSON
                $jsonStr = trim($content);
                // Remove blocos de código markdown se existirem
                $jsonStr = str_replace(['```json', '```'], '', $jsonStr);
                
                // Tenta encontrar o primeiro { e o último }
                if (preg_match('/\{.*\}/s', $jsonStr, $matches)) {
                    $jsonStr = $matches[0];
                }

                $resultado = json_decode($jsonStr, true);
                
                if (json_last_error() === JSON_ERROR_NONE && is_array($resultado)) {
                    return $resultado;
                }
                
                Log::error('Erro JSON Decode Gemini. Raw: ' . $content . ' | JSON Error: ' . json_last_error_msg());
                return ['erro' => 'Falha ao processar resposta da IA.'];
            } else {
                Log::error('Erro API Gemini: ' . $response->body());
                return ['erro' => 'Erro na API Gemini: ' . $response->status()];
            }
        } catch (\Exception $e) {
            Log::error('Exceção no GeminiService: ' . $e->getMessage());
            return null;
        }
    }

    public function sugerirDisciplinas($cargo, $cidade = null, $banca = null)
    {
        if (!$this->apiKey) return null;

        $contexto = "";
        if ($cidade) $contexto .= " para o município/órgão de $cidade";
        if ($banca) $contexto .= " organizado pela banca $banca";

        $prompt = "Você é um especialista em concursos públicos.
        O usuário vai prestar concurso para o cargo: **$cargo**$contexto.
        
        Com base no histórico de provas anteriores para este cargo e nesta região/banca (se informada), liste as DISCIPLINAS e ASSUNTOS que com certeza cairão ou são padrão para esta função.
        
        Retorne APENAS um JSON com a sugestão de estudo:
        [
            {
                \"nome_disciplina\": \"Português\",
                \"assuntos\": [\"Crase\", \"Sintaxe\", \"Interpretação de Texto\"]
            },
            {
                \"nome_disciplina\": \"Conhecimentos Específicos\",
                \"assuntos\": [\"Assunto 1\", \"Assunto 2\"]
            }
        ]
        
        Seja detalhista nos assuntos. Se o cargo for genérico, use o padrão nacional.";

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($this->baseUrl . '?key=' . $this->apiKey, [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => ['response_mime_type' => 'application/json', 'temperature' => 0.3]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
                $cleanJson = str_replace(['```json', '```'], '', trim($text));
                
                $decoded = json_decode($cleanJson, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                } else {
                    Log::error("Erro JSON Decode Suggestion: " . json_last_error_msg() . " | Raw: " . $cleanJson);
                    return ['erro' => 'Falha ao decodificar resposta da IA.'];
                }
            } else {
                Log::error("Erro API Gemini Suggestion: " . $response->body());
                return ['erro' => 'Erro na API Gemini: ' . $response->status()];
            }
        } catch (\Exception $e) {
            Log::error("Erro ao sugerir disciplinas: " . $e->getMessage());
            return ['erro' => 'Exceção interna: ' . $e->getMessage()];
        }
        return ['erro' => 'Erro desconhecido.'];
    }

    public function gerarQuestoes($texto, $quantidade = 5, $nivel = 'Médio')
    {
        if (!$this->apiKey) return ['erro' => 'API Key não configurada'];

        $prompt = "Você é uma banca examinadora de concursos públicos.
        Com base EXCLUSIVAMENTE no texto fornecido abaixo, crie $quantidade questões de múltipla escolha de nível $nivel.
        
        Regras:
        1. As questões devem ter 5 alternativas (A, B, C, D, E).
        2. Apenas uma alternativa correta.
        3. O formato de saída deve ser estritamente JSON.
        4. O JSON deve ser um array de objetos.
        
        Texto Base:
        \"" . substr($texto, 0, 50000) . "\"
        
        Modelo de JSON Esperado (Responda APENAS o JSON):
        [
            {
                \"enunciado\": \"Pergunta aqui...\",
                \"alternativa_a\": \"Opção A\",
                \"alternativa_b\": \"Opção B\",
                \"alternativa_c\": \"Opção C\",
                \"alternativa_d\": \"Opção D\",
                \"alternativa_e\": \"Opção E\",
                \"alternativa_correta\": \"A\" (ou B, C, D, E),
                \"comentario\": \"Explicação breve do porquê ser a correta (opcional)\"
            }
        ]";

        try {
            $response = Http::withHeaders(['Content-Type' => 'application/json'])
                ->post($this->baseUrl . '?key=' . $this->apiKey, [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature' => 0.4,
                        'response_mime_type' => 'application/json'
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
                
                // Limpeza básica
                $content = str_replace(['```json', '```'], '', $content);
                
                $perguntas = json_decode($content, true);
                
                if (json_last_error() === JSON_ERROR_NONE && is_array($perguntas)) {
                    return $perguntas;
                }
                
                Log::error('Erro JSON Gemini Questões: ' . json_last_error_msg());
                return ['erro' => 'Falha ao processar resposta JSON da IA.'];
            }
            
            Log::error('Erro API Gemini Questões: ' . $response->body());
            return ['erro' => 'Erro na API Gemini: ' . $response->status()];

        } catch (\Exception $e) {
            Log::error('Exceção Gemini Questões: ' . $e->getMessage());
            return ['erro' => 'Erro interno ao gerar questões.'];
        }
    }
    public function gerarQuestoesPorDisciplina($disciplina, $nivel = 'Médio', $quantidade = 3)
    {
        if (!$this->apiKey) return [];

        $prompt = "Você é uma respeitada banca examinadora de concursos públicos (estilo Cebraspe/Vunesp).
        
        TAREFA: Crie EXATAMENTE $quantidade questões de múltipla escolha INÉDITAS sobre a disciplina: **$disciplina**.
        
        Nível de dificuldade: $nivel (Questões para cargos de nível superior/técnico).
        
        REGRAS ABSOLUTAS:
        1. A quantidade de questões deve ser EXATAMENTE $quantidade. Não retorne menos.
        2. Cada questão DEVE ter 5 alternativas (A, B, C, D, E).
        3. Apenas uma alternativa correta.
        4. O formato de saída deve ser ESTRITAMENTE JSON. Não use Markdown (` ```json `).
        
        MODELO DE JSON (Array de Objetos):
        [
            {
                \"enunciado\": \"Texto completo da pergunta...\",
                \"alternativa_a\": \"Texto da opção A\",
                \"alternativa_b\": \"Texto da opção B\",
                \"alternativa_c\": \"Texto da opção C\",
                \"alternativa_d\": \"Texto da opção D\",
                \"alternativa_e\": \"Texto da opção E\",
                \"alternativa_correta\": \"A\"
            }
        ]
        
        Responda APENAS o JSON. Sem introduções.";

        try {
            $response = Http::withoutVerifying() // Fix critical for Localhost/Laragon SSL issues
                ->withHeaders(['Content-Type' => 'application/json'])
                ->post($this->baseUrl . '?key=' . $this->apiKey, [
                    'contents' => [['parts' => [['text' => $prompt]]]],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'response_mime_type' => 'application/json'
                    ]
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '[]';
                
                // Limpeza avançada
                $content = trim($content);
                $content = str_replace(['```json', '```'], '', $content);
                
                // Log da resposta bruta para debug
                Log::info("Gemini Raw Response (Req: $quantidade): " . substr($content, 0, 500) . "...");

                // Garante que pegamos apenas o array JSON
                if (preg_match('/\[.*\]/s', $content, $matches)) {
                    $content = $matches[0];
                }

                $perguntas = json_decode($content, true);
                
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Forçar estrutura de lista de listas se vier um objeto único
                    if (is_array($perguntas) && !isset($perguntas[0])) {
                        // Não é indexado numericamente, provavel objeto unico
                        $perguntas = [$perguntas];
                    }
                    
                    if (is_array($perguntas)) {
                        return $perguntas;
                    }
                } else {
                    Log::error('Erro ao decodificar JSON Gemini: ' . json_last_error_msg() . ' | Content: ' . substr($content, 0, 200));
                }
            } else {
                Log::error('Gemini API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Erro Gemini por Disciplina: ' . $e->getMessage());
        }
        
        return [];
    }
}
