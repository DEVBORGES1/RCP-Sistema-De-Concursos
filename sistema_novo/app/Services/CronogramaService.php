<?php

namespace App\Services;

use App\Models\Cronograma;
use App\Models\CronogramaDia;
use App\Models\Disciplina;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CronogramaService
{
    /**
     * Gera cronograma personalizado baseado no edital e horas disponíveis
     */
    public function gerarCronograma($usuario_id, $edital_id, $horas_por_dia, $data_inicio, $duracao_semanas = 4, $titulo = null, $cargo_id = null)
    {
        try {
            DB::beginTransaction();

            // Obter disciplinas do edital com contagem de questões
            // Filtro por Cargo: Se cargo_id setado, traz:
            // 1. Disciplinas comuns (cargo_id = null)
            // 2. Disciplinas específicas (cargo_id = $cargo_id)
            $query = Disciplina::where('edital_id', $edital_id);
            
            if ($cargo_id) {
                $query->where(function($q) use ($cargo_id) {
                    $q->whereNull('cargo_id')
                      ->orWhere('cargo_id', $cargo_id);
                });
            } else {
                // Se nenhum cargo selecionado, e o edital tem cargos configurados... 
                // Talvez devêssemos perguntar? Mas se o usuário não escolheu, vamos assumir "Modo Geral" (apenas comuns ou todas?
                // Vamos assumir TODAS se não tiver cargo, ou apenas COMUNS?
                // Legacy behavior: All. Mas agora temos separação.
                // Se $cargo_id for null, podemos trazer TODAS (talvez o user queira estudar tudo)
                // OU trazer só as comuns.
                // Decisão: Se não tem cargo selecionado, traz todas (comportamento padrão antigo).
            }

            $disciplinas = $query->withCount('questoes')->get();

            if ($disciplinas->isEmpty()) {
                throw new \Exception("Nenhuma disciplina encontrada para este edital/cargo.");
            }

            // Calcular data fim
            $dataInicio = Carbon::parse($data_inicio);
            $dataFim = $dataInicio->copy()->addWeeks($duracao_semanas);

            // Criar cronograma principal
            $cronograma = Cronograma::create([
                'usuario_id' => $usuario_id,
                'edital_id' => $edital_id,
                'cargo_id' => $cargo_id,
                'titulo' => $titulo,
                'data_inicio' => $dataInicio->toDateString(),
                'data_fim' => $dataFim->toDateString(),
                'horas_por_dia' => $horas_por_dia,
            ]);

            // Gerar distribuição de disciplinas
            $distribuicao = $this->distribuirDisciplinas($disciplinas, $horas_por_dia, $duracao_semanas);

            // Criar cronograma detalhado
            $this->criarCronogramaDetalhado($cronograma->id, $distribuicao, $dataInicio, $duracao_semanas, $horas_por_dia);

            DB::commit();

            return [
                'sucesso' => true,
                'cronograma' => $cronograma
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'sucesso' => false,
                'erro' => $e->getMessage()
            ];
        }
    }

    private function distribuirDisciplinas($disciplinas, $horas_por_dia, $duracao_semanas)
    {
        $total_dias = $duracao_semanas * 7;
        $total_horas = $total_dias * $horas_por_dia;

        // Calcular peso de cada disciplina baseado no número de questões
        $pesos = [];
        $total_questoes = 0;

        foreach ($disciplinas as $disciplina) {
            // Se não tiver questões, assumimos peso mínimo de 1 para não excluir a disciplina
            $questoes = max(1, $disciplina->questoes_count);
            $pesos[$disciplina->id] = $questoes;
            $total_questoes += $questoes;
        }

        // Distribuir horas proporcionalmente
        $distribuicao = [];
        foreach ($disciplinas as $disciplina) {
            $peso = $pesos[$disciplina->id];
            $horas_disciplina = round(($peso / $total_questoes) * $total_horas);

            $distribuicao[$disciplina->id] = [
                'disciplina' => $disciplina,
                'horas_total' => $horas_disciplina,
                'horas_por_sessao' => $this->calcularHorasPorSessao($horas_disciplina, $duracao_semanas),
                // 'sessoes_por_semana' => $this->calcularSessoesPorSemana($horas_disciplina, $duracao_semanas) // Não usado diretamente na lógica abaixo, mas útil se fosse exibir
            ];
        }

        return $distribuicao;
    }

    private function calcularHorasPorSessao($horas_total, $semanas)
    {
        // Assumindo estudo em todos os dias ou 5 dias? O legacy dizia "5 dias úteis" no comentário mas a lógica iterava 7 dias.
        // Vamos manter a lógica legacy de dividir por (semanas * 5) como base para "sessões" ideais.
        $sessoes_totais = $semanas * 5;
        if ($sessoes_totais == 0) return 1;
        
        // Mínimo 0.5h por sessão
        return max(0.5, round($horas_total / $sessoes_totais, 1));
    }

    private function criarCronogramaDetalhado($cronograma_id, $distribuicao, Carbon $data_inicio, $duracao_semanas, $horas_por_dia)
    {
        $data_atual = $data_inicio->copy();
        
        for ($semana = 0; $semana < $duracao_semanas; $semana++) {
            for ($dia = 0; $dia < 7; $dia++) {
                // Pular domingos
                if ($dia == 6) { 
                    $data_atual->addDay();
                    continue;
                }

                $disciplinas_dia = $this->selecionarDisciplinasParaDiaComHoras($distribuicao, $dia, $horas_por_dia);

                foreach ($disciplinas_dia as $disciplina_id => $info) {
                    CronogramaDia::create([
                        'cronograma_id' => $cronograma_id,
                        'disciplina_id' => $disciplina_id,
                        'data_estudo' => $data_atual->toDateString(),
                        'horas_previstas' => $info['horas']
                    ]);
                }

                $data_atual->addDay();
            }
        }
    }

    private function selecionarDisciplinasParaDia($distribuicao, $dia_loop_index)
    {
        $disciplinas_dia = [];
        $horas_restantes_no_dia = 4; // Legacy hardcoded 4 hours max per day allocation in this specific method? 
        // Espera, o legacy recebia $horas_por_dia no método principal, mas em 'selecionarDisciplinasParaDia' tinha:
        // $horas_restantes = 4; // Máximo 4 horas por dia
        // Isso parece um bug ou limitação do legacy ignorando o input do usuário para a distribuição diária Específica.
        // Porém, vou verificar se $horas_por_dia era usado em outro lugar.
        // Era usado em 'criarCronogramaPrincipal' e 'distribuirDisciplinas' (para calcular horas totais).
        // Mas na hora de alocar no dia, ele limitava a 4. 
        // Vou melhorar isso: usar a variável $horas_por_dia que vem do input (preciso passar ela ou pegar do contexto).
        // O método selecionarDisciplinasParaDia no legacy não recebia $horas_por_dia.
        // Vou assumir que devemos usar o valor configurado pelo usuário, senão a configuração não faz sentido.
        // Como não tenho o valor aqui fácil sem passar argumento extra, vou mudar a assinatura ou buscar de outra forma.
        // Vou mudar a lógica para usar uma média ou o valor real.
        // Para ser fiel ao legacy, eu deveria deixar 4, mas isso parece errado.
        // Vou tentar inferir que o usuário quer preencher as horas que ele pediu.
        
        // Na verdade, vou corrigir esse "bug" do legacy e usar o valor real se possível.
        // Mas para garantir que funcione vou checar o $distribuicao.
        // Vou pegar o max de horas de alguma das disciplinas para ter base? Não.
        
        // Vamos fixar em 4 se quisermos ser 100% fiéis, ou permitir flexibilidade.
        // Vou ser fiel ao legacy por enquanto mas adicionar um comentário.
        // ESPERA: Se o usuário pede 8 horas por dia, e o script limita a 4, o cronograma fica curto.
        // Vou assumir que o $horas_restantes deve ser o $horas_por_dia configurado.
        
        // Preciso passar $horas_por_dia para essa função.
        // Vou hackear e pegar da primeira sessao? Não.
        // Vou alterar a assinatura no meu Service.
        
        // Nova assinatura abaixo.
        return $disciplinas_dia;
    }

    // Sobrescrevendo a lógica acima com a correta passando horas_diarias
    private function selecionarDisciplinasParaDiaComHoras($distribuicao, $dia_loop_index, $horas_diarias_limite)
    {
        $disciplinas_dia = [];
        $horas_restantes = $horas_diarias_limite;

        // Ordenar disciplinas por prioridade (mais questões = mais prioridade)
        // No legacy: uasort($distribuicao, function($a, $b) { return $b['disciplina']['total_questoes'] - $a['disciplina']['total_questoes']; });
        // Isso ordena SEMPRE igual. Ou seja, todo dia as mesmas matérias têm prioridade.
        // Para variar, o ideal seria rotacionar ou usar o dia da semana.
        // O legacy passava $dia_semana, mas não usava dentro da função!
        // A função legacy usava `uasort` estático.
        // Resultado: Todo dia estuda as matérias com mais questões primeiro.
        
        // Vou manter a lógica de ordenação, mas talvez introduzir um "offset" baseado no dia para variar?
        // Se eu mudar muito, o usuário pode estranhar.
        // Mas estudar sempre a mesma coisa é chato.
        // Vou manter fiel ao legacy primeiro. Se user reclamar, melhoramos.
        
        $items = collect($distribuicao)->sortByDesc(function ($item) {
            return $item['disciplina']->questoes_count;
        });

        foreach ($items as $disciplina_id => $info) {
            if ($horas_restantes <= 0) break;

            $horas_disciplina = min($info['horas_por_sessao'], $horas_restantes);

            if ($horas_disciplina > 0) {
                $disciplinas_dia[$disciplina_id] = [
                    'horas' => $horas_disciplina,
                    'disciplina' => $info['disciplina']
                ];
                $horas_restantes -= $horas_disciplina;
            }
        }

        return $disciplinas_dia;
    }
}
