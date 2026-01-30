<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Simulado;
use App\Models\Questao;
use App\Services\GeminiService;
use Illuminate\Support\Facades\DB;

class GerarSimuladosSemanais extends Command
{
    protected $signature = 'app:gerar-simulados-semanais';
    protected $description = 'Gera simulados semanais automáticos baseados nos editais dos usuários';

    public function handle()
    {
        $this->info('Iniciando geração de simulados semanais...');
        
        $geminiService = new GeminiService();
        $users = User::all(); // Idealmente filtrar apenas ativos

        foreach ($users as $user) {
            $this->info("Processando usuário: {$user->name}");

            // Buscar disciplinas de todos os editais do usuário
            $disciplinas = DB::table('disciplinas')
                ->join('editais', 'disciplinas.edital_id', '=', 'editais.id')
                ->where('editais.usuario_id', $user->id)
                ->select('disciplinas.id', 'disciplinas.nome_disciplina', 'disciplinas.edital_id')
                ->inRandomOrder()
                ->limit(3) // Escolhe 3 disciplinas aleatórias
                ->get();

            if ($disciplinas->isEmpty()) {
                $this->line(" - Sem disciplinas encontradas.");
                continue;
            }

            $questoesParaSimulado = [];

            foreach ($disciplinas as $disciplina) {
                $this->line(" - Gerando questões para: {$disciplina->nome_disciplina}");
                
                // Gera 5 questões por disciplina
                $novasQuestoes = $geminiService->gerarQuestoesPorDisciplina($disciplina->nome_disciplina, 'Difícil', 5);
                
                if (empty($novasQuestoes)) {
                    $this->error("   - Falha ao gerar questões para {$disciplina->nome_disciplina}");
                    continue;
                }

                foreach ($novasQuestoes as $q) {
                    $questao = Questao::create([
                        'edital_id' => $disciplina->edital_id,
                        'disciplina_id' => $disciplina->id,
                        'enunciado' => $q['enunciado'],
                        'alternativa_a' => $q['alternativa_a'],
                        'alternativa_b' => $q['alternativa_b'],
                        'alternativa_c' => $q['alternativa_c'],
                        'alternativa_d' => $q['alternativa_d'],
                        'alternativa_e' => $q['alternativa_e'],
                        'alternativa_correta' => $q['alternativa_correta']
                    ]);
                    $questoesParaSimulado[] = $questao->id;
                }
            }

            if (count($questoesParaSimulado) > 0) {
                $simulado = Simulado::create([
                    'usuario_id' => $user->id,
                    'nome' => 'Desafio Semanal #' . date('W') . ' - ' . date('d/m'),
                    'questoes_total' => count($questoesParaSimulado),
                ]);

                foreach ($questoesParaSimulado as $qId) {
                    DB::table('simulados_questoes')->insert([
                        'simulado_id' => $simulado->id,
                        'questao_id' => $qId
                    ]);
                }
                
                $this->info(" - Simulado criado com " . count($questoesParaSimulado) . " questões!");
            }
        }

        $this->info('Geração concluída!');
    }
}
