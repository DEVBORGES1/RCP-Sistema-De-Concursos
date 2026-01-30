<?php

namespace App\Services;

use App\Models\Edital;
use App\Models\Disciplina;
use App\Models\Questao;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\DB;
use Exception;

class EditalService
{
    private $parser;

    public function __construct()
    {
        // Check if class exists to avoid crash if dependency not installed yet
        if (class_exists('Smalot\PdfParser\Parser')) {
            $this->parser = new Parser();
        }
    }

    // Lista de disciplinas comuns
    private $disciplinas_comuns = [
        'Português', 'Matemática', 'Raciocínio Lógico', 'Informática', 'Direito Constitucional',
        'Direito Administrativo', 'Direito Penal', 'Direito Civil', 'Direito Processual',
        'Direito Tributário', 'Direito do Trabalho', 'Direito Previdenciário', 'Direito Empresarial',
        'Administração Pública', 'Contabilidade', 'Auditoria', 'Economia', 'Finanças Públicas',
        'Estatística', 'História do Brasil', 'Geografia', 'Atualidades', 'Legislação',
        'Ética na Administração Pública', 'Regime Jurídico Único', 'Licitações',
        'Controle Interno e Externo', 'Orçamento Público', 'Gestão de Pessoas',
        'Gestão de Projetos', 'Gestão de Qualidade', 'Comunicação Social',
        'Arquitetura', 'Engenharia Civil', 'Engenharia Elétrica', 'Engenharia Mecânica',
        'Medicina', 'Enfermagem', 'Fisioterapia', 'Psicologia', 'Assistência Social',
        'Pedagogia', 'Letras', 'História', 'Filosofia', 'Sociologia', 'Antropologia'
    ];

    private $padroes_disciplinas = [
        '/\b(?:disciplina|matéria|área|conhecimento)s?\s*:?\s*([^\.\n]+)/i',
        '/\b(?:conteúdo|programa|matriz)\s+(?:curricular|programático)\s*:?\s*([^\.\n]+)/i',
        '/\b(?:prova|exame)\s+(?:de|da|do)\s+([^\.\n]+)/i',
        '/\b(?:área|conhecimento)\s+([^\.\n]+?)(?:\s+com\s+|\s+e\s+|\s+ou\s+)/i'
    ];

    public function extrairTextoPdf($path)
    {
        if (!file_exists($path)) {
            throw new Exception("Arquivo PDF não encontrado.");
        }

        if (!$this->parser) {
             throw new Exception("Biblioteca PDF Parser não instalada. Execute 'composer require smalot/pdfparser'");
        }

        $pdf = $this->parser->parseFile($path);
        $texto = $pdf->getText();
        
        // Limpeza específica para editais do PCI Concursos e similares
        $texto = preg_replace('/pcimarkpci\s+[\w\+\/=:-]+/i', '', $texto); // Remove hash do PCI
        $texto = str_ireplace('www.pciconcursos.com.br', '', $texto);
        
        // Limpeza geral de quebras de linha excessivas e espaços (Preservando newlines)
        $texto = preg_replace('/[ \t]+/', ' ', $texto);
        $texto = preg_replace('/\n\s*\n/', "\n", $texto);
        
        return trim($texto);
    }

    public function processarEdital(Edital $edital, $texto, $contexto = [])
    {
        DB::beginTransaction();
        try {
            // Limpar dados anteriores se for reanálise
            $edital->questoes()->delete();
            $edital->disciplinas()->delete();

            // Atualizar texto no edital
            $edital->texto_extraido = $texto;
            $edital->save();

            // 1. Tentar recortar o trecho relevante (se houver cargo alvo)
            $textoParaAnalise = $texto;
            if (!empty($contexto['cargo_alvo'])) {
                $trecho = $this->recortarTextoRelevante($texto, $contexto['cargo_alvo']);
                if (strlen($trecho) > 500) { // Se achou algo útil
                    $textoParaAnalise = $trecho;
                }
            }

            // 2. Tentar Análise via IA (Gemini)
            $geminiService = new \App\Services\GeminiService();
            // Passa o contexto completo para o Gemini
            $dadosIA = $geminiService->analisarEdital($textoParaAnalise, $contexto);
            
            $disciplinasEncontradas = [];
            $cargosEncontrados = [];
            $usouIA = false;
            
            // Inicializar Cargo Alvo se existir no contexto
            $cargoAlvoModel = null;
            if (!empty($contexto['cargo_alvo'])) {
                $cargoAlvoModel = \App\Models\Cargo::firstOrCreate([
                    'edital_id' => $edital->id,
                    'nome' => $contexto['cargo_alvo']
                ]);
                $cargosEncontrados[] = $contexto['cargo_alvo'];
            }

            if ($dadosIA && is_array($dadosIA)) {
                $usouIA = true;
                
                // Normaliza estrutura: Se vier {cargos: [...]} ou direto [...]
                $listaCargos = isset($dadosIA['cargos']) ? $dadosIA['cargos'] : $dadosIA;

                // Tenta salvar Metadados PRIORITÁRIOS do Contexto Manual
                $cols = \Illuminate\Support\Facades\Schema::getColumnListing('editais');
                if (in_array('cidade_prova', $cols)) {
                    // Prioriza o manual ($contexto), se não tiver usa o da IA
                    $edital->cidade_prova = $contexto['cidade'] ?? ($dadosIA['cidade'] ?? null);
                    $edital->instituicao_banca = $contexto['banca'] ?? ($dadosIA['banca'] ?? null);
                    $edital->ano_prova = $contexto['ano'] ?? ($dadosIA['ano'] ?? null);
                    
                    // Se a IA achou algo diferente e o manual estava vazio, ótimo. 
                    // Se o manual foi preenchido, ele ganha.
                    $edital->save();
                }

                // Processa Cargos da IA
                foreach ($listaCargos as $item) {
                    if (isset($item['cargo']) && !empty($item['cargo'])) {
                        
                        // Lógica: Se o nome retornado pela IA for muito parecido com o Cargo Alvo, usamos o ID do Cargo Alvo
                        // Se não, cria um novo.
                        $nomeCargo = $item['cargo'];
                        $cargoIdParaVincular = null;

                        if ($cargoAlvoModel && (stripos($nomeCargo, $contexto['cargo_alvo']) !== false || stripos($contexto['cargo_alvo'], $nomeCargo) !== false)) {
                            // IA retornou algo compatível com o alvo (ex: user digitou "Médico", IA retornou "Médico ESF")
                            // Atualizamos o nome do model para o mais completo (geralmente o da IA)
                            if (strlen($nomeCargo) > strlen($cargoAlvoModel->nome)) {
                                $cargoAlvoModel->nome = $nomeCargo;
                                $cargoAlvoModel->save();
                            }
                            $cargoIdParaVincular = $cargoAlvoModel->id;
                        } else {
                            // Cargo distinto
                            $cargo = \App\Models\Cargo::firstOrCreate([
                                'edital_id' => $edital->id,
                                'nome' => $nomeCargo
                            ]);
                            $cargoIdParaVincular = $cargo->id;
                        }
                        
                        $cargosEncontrados[] = $nomeCargo;

                        // Criar Disciplinas vinculadas a este cargo
                        if (isset($item['disciplinas']) && is_array($item['disciplinas'])) {
                            foreach ($item['disciplinas'] as $nomeDisc) {
                                $disciplina = Disciplina::firstOrCreate([
                                    'edital_id' => $edital->id,
                                    'nome_disciplina' => $nomeDisc,
                                    'cargo_id' => $cargoIdParaVincular 
                                ]);
                                
                                $this->gerarQuestoesAutomaticas($edital, $disciplina);
                                $disciplinasEncontradas[] = $nomeDisc;
                            }
                        }
                    }
                }
            } 
            
            // Fallback: Se a IA não retornou nada ou falhou, usa o método antigo (Regex)
            if (!$usouIA || empty($cargosEncontrados)) {
                // Extrair Disciplinas (Método Regex)
                $disciplinasEncontradas = $this->extrairDisciplinas($texto);
    
                // Extrair Cargos (Método Regex)
                $cargosEncontrados = $this->extrairCargos($texto);
                
                // Salvar Cargos
                foreach($cargosEncontrados as $nomeCargo) {
                    \App\Models\Cargo::firstOrCreate([
                        'edital_id' => $edital->id,
                        'nome' => $nomeCargo
                    ]);
                }
    
                // Salvar Disciplinas (Genéricas ou do Regex)
                $cargoIdDefault = $cargoAlvoModel ? $cargoAlvoModel->id : null;

                foreach ($disciplinasEncontradas as $nomeDisciplina) {
                    $disciplina = Disciplina::firstOrCreate([
                        'edital_id' => $edital->id,
                        'nome_disciplina' => $nomeDisciplina,
                        'cargo_id' => $cargoIdDefault // Vincula ao cargo alvo se existir
                    ]);
    
                    $this->gerarQuestoesAutomaticas($edital, $disciplina);
                }
            }

            DB::commit();
            
            return [
                'sucesso' => true,
                'disciplinas_count' => count($disciplinasEncontradas),
                'cargos_count' => count($cargosEncontrados)
            ];

        } catch (Exception $e) {
            DB::rollBack();
            return [
                'sucesso' => false,
                'erro' => $e->getMessage()
            ];
        }
    }

    private function extrairDisciplinas($texto) {
        $disciplinas_encontradas = [];
        
        // Buscar disciplinas usando padrões regex
        foreach ($this->padroes_disciplinas as $padrao) {
            preg_match_all($padrao, $texto, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $match) {
                    $disciplinas_candidatas = $this->processarTextoDisciplina($match);
                    $disciplinas_encontradas = array_merge($disciplinas_encontradas, $disciplinas_candidatas);
                }
            }
        }
        
        // Buscar disciplinas conhecidas no texto
        foreach ($this->disciplinas_comuns as $disciplina) {
            if (stripos($texto, $disciplina) !== false) {
                $disciplinas_encontradas[] = $disciplina;
            }
        }
        
        // Remover duplicatas e limpar
        $disciplinas_encontradas = array_unique($disciplinas_encontradas);
        $disciplinas_encontradas = array_filter($disciplinas_encontradas, function($d) {
            return strlen(trim($d)) > 2 && strlen(trim($d)) < 100;
        });
        
        return array_values($disciplinas_encontradas);
    }

    private function processarTextoDisciplina($texto) {
        $disciplinas = [];
        $texto = trim($texto);
        
        // Remove pontuação final
        $texto = preg_replace('/[.;:]$/', '', $texto);
        
        $partes = preg_split('/[,;]\s*|\s+e\s+|\s+ou\s+/i', $texto);
        
        // Blacklist de termos que indicam texto jurídico/edital e não disciplina
        $blacklist = [
            'aceitação', 'tácita', 'condições', 'estabelecidas', 'candidato', 'inscrição',
            'edital', 'homologação', 'prazo', 'recurso', 'isenção', 'pagamento',
            'prova', 'objetiva', 'discursiva', 'títulos', 'classificação', 'eliminatória',
            'classificatória', 'banca', 'examinadora', 'cargo', 'requisito', 'atribuições',
            'remuneração', 'jornada', 'vaga', 'reserva', 'deficiência', 'negro',
            'habilidade', 'sobre', 'matéria', 'relacionada', 'cada'
        ];

        foreach ($partes as $parte) {
            $parte = trim($parte);
            $parteLower = mb_strtolower($parte, 'UTF-8');

            // Regras de validação de comprimento (Relaxado)
            if (strlen($parte) < 3 || strlen($parte) > 100) continue;
            
            // Verifica blacklist
            $ehLixo = false;
            foreach ($blacklist as $termo) {
                if (stripos($parte, $termo) !== false) {
                    $ehLixo = true;
                    break;
                }
            }
            if ($ehLixo) continue;

            // Tenta casar com disciplinas conhecidas
            $encontrouConhecida = false;
            foreach ($this->disciplinas_comuns as $disciplina_conhecida) {
                if (stripos($parte, $disciplina_conhecida) !== false) {
                    $disciplinas[] = $disciplina_conhecida;
                    $encontrouConhecida = true;
                }
            }
            
            // Se não é conhecida, aceita se parecer um título curto e válido
            if (!$encontrouConhecida) {
                // Heurística: se tem muitos espaços (frase longa), ignora
                // Aumentado para 10 espaços para aceitar nomes longos de legislação
                if (substr_count($parte, ' ') > 10) continue;
                
                // Ignora se começar com preposição solta (erro de split)
                if (preg_match('/^(do|da|de|em|na|no|com|por|para)\s+/i', $parte)) continue;

                // Evita adicionar duplicatas
                $novaDisciplina = ucwords(mb_strtolower($parte, 'UTF-8'));
                if (!in_array($novaDisciplina, $disciplinas)) {
                    $disciplinas[] = $novaDisciplina;
                }
            }
        }
        
        return array_unique($disciplinas);
    }

    private function extrairCargos($texto) {
        $cargos_encontrados = [];
        
        // 1. Dicionário de Cargos Comuns (Palavras-chave fortes)
        $cargos_comuns = [
            'Analista', 'Técnico', 'Auditor', 'Procurador', 'Defensor', 'Juiz', 'Promotor', 'Delegado', 
            'Agente', 'Escrivão', 'Papiloscopista', 'Perito', 'Médico', 'Enfermeiro', 'Professor', 
            'Diretor', 'Coordenador', 'Assistente', 'Auxiliar', 'Motorista', 'Guarda', 'Fiscal', 
            'Gestor', 'Pesquisador', 'Especialista', 'Advogado', 'Engenheiro', 'Arquiteto', 'Psicólogo',
            'Contador', 'Administrador', 'Economista', 'Assistente Social', 'Soldado', 'Cabo', 'Sargento',
            'Investigador', 'Inspetor', 'Oficial'
        ];

        // 2. Busca por Regex Contextual (Títulos Seguidos de Nome)
        $padroes = [
            '/\b(?:Cargo|Emprego|Função)(?:s)?(?:\s+Público)?\s*[:\-]?\s*([A-ZÀ-Ú][A-ZÀ-Úa-zà-ú\s\-\/\(\)]+)/u',
            '/\bCódigo\s+\d+\s*-\s*([A-ZÀ-Ú][A-ZÀ-Úa-zà-ú\s\-\/\(\)]+)/u', 
            // Removido regex agressivo de linha isolada que estava pegando URLs e cabeçalhos
        ];

        foreach ($padroes as $padrao) {
            preg_match_all($padrao, $texto, $matches);
            if (!empty($matches[1])) {
                foreach ($matches[1] as $match) {
                     $this->adicionarCargoSeValido($match, $cargos_encontrados, $cargos_comuns);
                }
            }
        }

        // 3. Varredura por Dicionário
        $linhas = explode("\n", $texto);
        foreach ($linhas as $linha) {
            $linha = trim($linha);
            // Filtros rápidos antes de processar
            if (strlen($linha) < 5 || strlen($linha) > 100) continue;
            if (stripos($linha, 'http') !== false) continue;
            if (stripos($linha, 'www.') !== false) continue;
            if (stripos($linha, 'site:') !== false) continue;
            if (stripos($linha, 'email:') !== false) continue;

            foreach ($cargos_comuns as $chave) {
                if (stripos($linha, $chave) !== false) {
                     if (preg_match_all('/\d/', $linha) > 5) continue;
                     
                     $linha_limpa = preg_split('/\s{2,}|\t|R\$|Vagas/', $linha)[0];
                     $this->adicionarCargoSeValido($linha_limpa, $cargos_encontrados, $cargos_comuns);
                }
            }
        }
        
        return array_values(array_unique($cargos_encontrados));
    }

    private function adicionarCargoSeValido($candidato, &$lista, $dicionario) {
        $candidato = trim($candidato);
        $candidato = preg_replace('/[.;:]$/', '', $candidato);
        
        // Lista Negra de termos que invalidam o cargo
        $blacklist = [
            'http', 'www.', '.com', '.br', 'site', 'email', 'edital', 'concurso', 
            'página', 'anexo', 'data', 'inscrição', 'fepese', 'banca', 'instituto',
            'universidade', 'polícia científica', 'estado de', 'prefeitura', 'município'
        ];

        foreach ($blacklist as $termo) {
            if (stripos($candidato, $termo) !== false) return;
        }
        
        // Regras de validação
        if (strlen($candidato) < 4) return;
        if (strlen($candidato) > 80) return;
        if (is_numeric($candidato)) return;
        
        $contem_chave = false;
        foreach ($dicionario as $chave) {
            if (stripos($candidato, $chave) !== false) {
                $contem_chave = true;
                break;
            }
        }
        
        // Só aceita se contiver uma palavra-chave válida de cargo
        if ($contem_chave) {
             $candidato = ucwords(strtolower($candidato));
             $lista[] = $candidato;
        }
    }

    public function gerarQuestoesAutomaticas(Edital $edital, Disciplina $disciplina) {
        $geminiService = new \App\Services\GeminiService();
        
        // Tenta gerar via IA
        $questoes = $geminiService->gerarQuestoesPorDisciplina($disciplina->nome_disciplina, 'Médio', 10);
        
        // Se falhar, LOGA erro mas NÃO gera lixo.
        if (empty($questoes) || isset($questoes['erro'])) {
            // Log::warning("Falha ao gerar questões IA para {$disciplina->nome_disciplina}");
            return;
        }
        
        foreach ($questoes as $q) {
            Questao::create([
                'edital_id' => $edital->id,
                'disciplina_id' => $disciplina->id,
                'enunciado' => $q['enunciado'],
                'alternativa_a' => $q['alternativa_a'],
                'alternativa_b' => $q['alternativa_b'],
                'alternativa_c' => $q['alternativa_c'],
                'alternativa_d' => $q['alternativa_d'],
                'alternativa_e' => $q['alternativa_e'],
                'alternativa_correta' => $q['alternativa_correta']
            ]);
        }
    }

    private function gerarQuestoesPorDisciplinaMock($nome) {
        $nomeLower = strtolower($nome);
        
        if (strpos($nomeLower, 'português') !== false) return $this->gerarQuestoesPortugues();
        if (strpos($nomeLower, 'matemática') !== false) return $this->gerarQuestoesMatematica();
        if (strpos($nomeLower, 'lógico') !== false) return $this->gerarQuestoesRaciocinioLogico();
        if (strpos($nomeLower, 'informática') !== false) return $this->gerarQuestoesInformatica();
        if (strpos($nomeLower, 'constitucional') !== false) return $this->gerarQuestoesDireitoConstitucional();
        if (strpos($nomeLower, 'administrativo') !== false) return $this->gerarQuestoesDireitoAdministrativo();
        
        return $this->gerarQuestoesGenericas($nome);
    }

    // --- Helpers de Geração de Questões (Copiados do Legado) ---

    private function gerarQuestoesPortugues() {
        return [
            [
                'enunciado' => 'Qual é a função sintática da palavra destacada na frase: "O aluno estudou MUITO para a prova"?',
                'alternativa_a' => 'Adjunto adverbial',
                'alternativa_b' => 'Complemento nominal',
                'alternativa_c' => 'Predicativo do sujeito',
                'alternativa_d' => 'Objeto direto',
                'alternativa_e' => 'Adjunto adnominal',
                'alternativa_correta' => 'A'
            ],
            [
                'enunciado' => 'Assinale a alternativa que apresenta erro de concordância:',
                'alternativa_a' => 'Haviam muitas pessoas na reunião.',
                'alternativa_b' => 'Fazem dois anos que não nos vemos.',
                'alternativa_c' => 'Devem existir várias soluções.',
                'alternativa_d' => 'Há muitas pessoas interessadas.',
                'alternativa_e' => 'Existem várias possibilidades.',
                'alternativa_correta' => 'A'
            ]
        ];
    }

    private function gerarQuestoesMatematica() {
        return [
            [
                'enunciado' => 'Se um retângulo tem comprimento 8 cm e largura 5 cm, qual é sua área?',
                'alternativa_a' => '13 cm²',
                'alternativa_b' => '26 cm²',
                'alternativa_c' => '40 cm²',
                'alternativa_d' => '65 cm²',
                'alternativa_e' => '80 cm²',
                'alternativa_correta' => 'C'
            ],
             [
                'enunciado' => 'Qual é o valor de x na equação 2x + 5 = 13?',
                'alternativa_a' => '2',
                'alternativa_b' => '3',
                'alternativa_c' => '4',
                'alternativa_d' => '5',
                'alternativa_e' => '6',
                'alternativa_correta' => 'C'
            ]
        ];
    }

    private function gerarQuestoesRaciocinioLogico() {
        return [
            [
                'enunciado' => 'Se todos os gatos são mamíferos e todos os mamíferos são animais, então:',
                'alternativa_a' => 'Todos os animais são gatos',
                'alternativa_b' => 'Todos os gatos são animais',
                'alternativa_c' => 'Alguns gatos não são animais',
                'alternativa_d' => 'Nenhum gato é animal',
                'alternativa_e' => 'Todos os animais são mamíferos',
                'alternativa_correta' => 'B'
            ]
        ];
    }

    private function gerarQuestoesInformatica() {
        return [
            [
                'enunciado' => 'Qual é a função da tecla F5 em um navegador web?',
                'alternativa_a' => 'Salvar página',
                'alternativa_b' => 'Atualizar página',
                'alternativa_c' => 'Fechar aba',
                'alternativa_d' => 'Abrir nova aba',
                'alternativa_e' => 'Imprimir página',
                'alternativa_correta' => 'B'
            ]
        ];
    }

    private function gerarQuestoesDireitoConstitucional() {
        return [
            [
                'enunciado' => 'Qual é o fundamento da República Federativa do Brasil?',
                'alternativa_a' => 'A soberania, a cidadania, a dignidade da pessoa humana',
                'alternativa_b' => 'Os valores sociais do trabalho e da livre iniciativa',
                'alternativa_c' => 'O pluralismo político',
                'alternativa_d' => 'Todas as alternativas anteriores',
                'alternativa_e' => 'Apenas a soberania',
                'alternativa_correta' => 'D'
            ]
        ];
    }

    private function gerarQuestoesDireitoAdministrativo() {
        return [
            [
                'enunciado' => 'Qual é o princípio que determina que a Administração Pública deve agir com transparência?',
                'alternativa_a' => 'Princípio da legalidade',
                'alternativa_b' => 'Princípio da publicidade',
                'alternativa_c' => 'Princípio da impessoalidade',
                'alternativa_d' => 'Princípio da moralidade',
                'alternativa_e' => 'Princípio da eficiência',
                'alternativa_correta' => 'B'
            ]
        ];
    }

    private function gerarQuestoesGenericas($disciplina) {
        return [
            [
                'enunciado' => "Sobre {$disciplina}, assinale a alternativa correta:",
                'alternativa_a' => 'É uma área de conhecimento importante',
                'alternativa_b' => 'Não possui aplicação prática',
                'alternativa_c' => 'É estudada apenas teoricamente',
                'alternativa_d' => 'Não possui relevância atual',
                'alternativa_e' => 'É uma disciplina obsoleta',
                'alternativa_correta' => 'A'
            ]
        ];
    }

    private function recortarTextoRelevante($texto, $cargoAlvo)
    {
        // Palavras-chave que indicam o início do conteúdo ou tabelas de cargo
        $palavrasChave = [
            'CONTEÚDO PROGRAMÁTICO',
            'PROGRAMA DE PROVAS',
            'ANEXO III', 
            'ANEXO IV',
            'ANEXO II',
            'ANEXO I',
            'CONHECIMENTOS GERAIS',
            'CONHECIMENTOS ESPECÍFICOS',
            'DAS VAGAS',
            'DO CARGO',
            'DOS CARGOS'
        ];
        
        $melhorPosicao = strlen($texto);
        $encontrou = false;

        // Tenta achar a primeira ocorrência das seções chaves
        foreach ($palavrasChave as $chave) {
            $pos = stripos($texto, $chave);
            if ($pos !== false && $pos < $melhorPosicao) {
                $melhorPosicao = $pos;
                $encontrou = true;
            }
        }

        // Se encontrou seção de conteúdo, corta o que vem antes
        if ($encontrou) {
            $textoCortado = substr($texto, $melhorPosicao);
            if (strlen($textoCortado) > 500) {
                return $textoCortado;
            }
        }

        // Tenta achar o nome do cargo para ver se está próximo
        $posCargo = stripos($texto, $cargoAlvo);
        if ($posCargo !== false) {
             $start = max(0, $posCargo - 1000);
             return substr($texto, $start);
        }

        return $texto;
    }
}
