@extends('layouts.app')

@section('title', 'Simulados - RCP Concursos')

@section('content')
<div class="container">
    @if (session('mensagem'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('mensagem') }}
        </div>
    @endif
    
    @if (request('erro') == 'sem_questoes')
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            Não há questões suficientes disponíveis para criar este simulado. Por favor, adicione mais questões através do upload de editais.
        </div>
    @endif

    <!-- Criar Novo Simulado -->
    <section class="create-simulado" style="margin-bottom: 40px;">
        <div class="card" style="padding: 30px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.1); background: rgba(30,30,30,0.8);">
            <h2 style="margin-bottom: 25px;"><i class="fas fa-plus-circle"></i> Criar Novo Simulado</h2>
            <form method="POST" action="{{ route('simulados.store') }}">
                @csrf
                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="nome_simulado" style="display: block; margin-bottom: 10px;">Nome do Simulado:</label>
                    <input type="text" id="nome_simulado" name="nome_simulado"
                        placeholder="Ex: Simulado de Português" required class="form-control" 
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: rgba(0,0,0,0.2); color: white;">
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="quantidade_questoes" style="display: block; margin-bottom: 10px;">Quantidade de Questões:</label>
                    <select id="quantidade_questoes" name="quantidade_questoes" required class="form-control"
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: #2a2a2a; color: white;">
                        <option value="5">5 questões</option>
                        <option value="10">10 questões</option>
                        <option value="15">15 questões</option>
                        <option value="20">20 questões</option>
                        <option value="30">30 questões</option>
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 25px;">
                    <label for="disciplina_id" style="display: block; margin-bottom: 10px;">Disciplina (opcional):</label>
                    <select id="disciplina_id" name="disciplina_id" class="form-control"
                        style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.2); background: #2a2a2a; color: white;">
                        <option value="">Todas as disciplinas</option>
                        @foreach ($disciplinas as $disciplina)
                            <option value="{{ $disciplina->id }}">
                                {{ $disciplina->nome_disciplina }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn-primary" style="padding: 12px 30px; border-radius: 8px; background: #ff4444; color: white; border: none; font-weight: 600; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 10px;" onclick="this.form.submit(); document.getElementById('loadingOverlay').style.display = 'flex';">
                    <i class="fas fa-play"></i> Iniciar Simulado
                </button>
            </form>
        </div>
    </section>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; flex-direction: column; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        
        <!-- Rat Spinner Animation -->
        <div class="rat-spinner" style="width: 120px; height: 120px; background: url('{{ asset('assets/images/rat_spinner.png') }}') no-repeat center center; background-size: contain; animation: spinRat 1s linear infinite;"></div>
        
        <h3 style="color: white; margin-top: 25px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px;">Gerando Simulados...</h3>
        <p style="color: #aaa; font-size: 0.9rem;">O Rato Concurseiro está buscando as melhores questões!</p>
    </div>

    <style>
        @keyframes spinRat { 
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); } 
        }
    </style>

    <!-- Simulados Pré-definidos -->
    <section class="predefined-simulados" style="margin-bottom: 40px;">
        <div class="card" style="padding: 30px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.1); background: rgba(30,30,30,0.8);">
            <h2 style="margin-bottom: 15px;"><i class="fas fa-star"></i> Simulados Pré-definidos</h2>
            <p style="margin-bottom: 30px; color: #ccc;">Escolha um dos simulados criados especialmente para você:</p>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <!-- Cards hardcoded para estrutura -->
                @php
                    $predefinedOpts = [
                        ['icon' => 'graduation-cap', 'name' => 'Simulado Geral Básico', 'desc' => 'Todas as disciplinas em um simulado equilibrado', 'q' => 15, 'slug' => 'geral'],
                        ['icon' => 'language', 'name' => 'Português e Matemática', 'desc' => 'Foco nas disciplinas mais importantes', 'q' => 12, 'slug' => 'portugues-matematica'],
                        ['icon' => 'gavel', 'name' => 'Conhecimentos Específicos', 'desc' => 'Direito, administração e atualidades', 'q' => 10, 'slug' => 'especificos'],
                        ['icon' => 'brain', 'name' => 'Raciocínio e Informática', 'desc' => 'Lógica e conhecimentos de informática', 'q' => 10, 'slug' => 'logico-informatica'],
                        ['icon' => 'trophy', 'name' => 'Simulado Completo', 'desc' => 'Todas as questões disponíveis', 'q' => 30, 'slug' => 'completo'],
                    ];
                @endphp

                @foreach ($predefinedOpts as $opt)
                <div class="predefined-card" style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; text-align: center; border: 1px solid rgba(255,255,255,0.1); transition: transform 0.3s;">
                    <div style="font-size: 2rem; color: #ff4444; margin-bottom: 15px;"><i class="fas fa-{{ $opt['icon'] }}"></i></div>
                    <h3 style="font-size: 1.1rem; margin-bottom: 10px;">{{ $opt['name'] }}</h3>
                    <p style="font-size: 0.9rem; color: #aaa; margin-bottom: 15px;">{{ $opt['desc'] }}</p>
                    <div style="font-size: 0.85rem; color: #888; margin-bottom: 20px;">
                        <span><i class="fas fa-question-circle"></i> {{ $opt['q'] }} questões</span>
                    </div>
                    <form action="{{ route('simulados.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="predefined" value="{{ $opt['slug'] }}">
                        <button type="submit" class="btn-primary" style="width: 100%; padding: 10px; border: none; border-radius: 6px; background: #ff4444; color: white; cursor: pointer;">
                            <i class="fas fa-play"></i> Iniciar
                        </button>
                    </form>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Simulados Anteriores -->
    <section class="simulados-history">
        <div class="card" style="padding: 30px; border-radius: 15px; border: 1px solid rgba(255,255,255,0.1); background: rgba(30,30,30,0.8);">
            <h2 style="margin-bottom: 25px;"><i class="fas fa-history"></i> Seus Simulados Recentes</h2>

            @if (count($simulados) === 0 && count($simuladosPredefinidos) === 0)
                <div class="empty-state" style="text-align: center; padding: 40px; color: #888;">
                    <i class="fas fa-clipboard-list" style="font-size: 3rem; margin-bottom: 20px; color: #333;"></i>
                    <h3>Nenhum simulado realizado ainda</h3>
                    <p>Crie seu primeiro simulado para começar a praticar!</p>
                </div>
            @else
                <div class="simulados-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                    @foreach (collect($simulados)->merge($simuladosPredefinidos)->sortByDesc('data_criacao') as $simulado)
                        @php
                            $isWeekly = str_contains($simulado->nome, 'Desafio Semanal');
                            $borderStyle = $isWeekly ? '2px solid #ffd700' : '1px solid rgba(255,255,255,0.1)';
                            $bgStyle = $isWeekly ? 'linear-gradient(145deg, rgba(255, 215, 0, 0.1), rgba(30,30,30,0.8))' : 'rgba(255,255,255,0.05)';
                        @endphp
                        <div class="simulado-card" style="background: {{ $bgStyle }}; padding: 20px; border-radius: 12px; border: {{ $borderStyle }}; position: relative;">
                            
                            <!-- Status -->
                            <div style="position: absolute; top: 15px; right: 15px; font-size: 0.8rem; padding: 4px 10px; border-radius: 20px; background: {{ $simulado->questoes_corretas === null ? 'rgba(255, 193, 7, 0.2); color: #ffc107;' : 'rgba(40, 167, 69, 0.2); color: #28a745;' }}">
                                {{ $simulado->questoes_corretas === null ? 'Em Andamento' : 'Concluído' }}
                            </div>

                            @if($isWeekly)
                                <div style="position: absolute; top: -10px; left: 20px; background: #ffd700; color: #000; font-weight: bold; font-size: 0.7rem; padding: 2px 10px; border-radius: 10px; box-shadow: 0 2px 10px rgba(255,215,0,0.3);">
                                    <i class="fas fa-fire"></i> DESAFIO DA SEMANA
                                </div>
                            @endif

                            <h3 style="margin-bottom: 5px; padding-right: 80px; {{ $isWeekly ? 'color: #ffd700;' : '' }}">{{ $simulado->nome }}</h3>
                            <span style="font-size: 0.85rem; color: #888; display: block; margin-bottom: 15px;">
                                {{ date('d/m/Y H:i', strtotime($simulado->data_criacao)) }}
                            </span>

                            <div style="display: flex; gap: 15px; font-size: 0.9rem; color: #ccc; margin-bottom: 20px; flex-wrap: wrap;">
                                <span><i class="fas fa-question-circle"></i> {{ $simulado->questoes_total }} qts</span>
                                @if($simulado->questoes_corretas !== null)
                                    <span><i class="fas fa-check-circle"></i> {{ $simulado->questoes_corretas }} acertos</span>
                                @endif
                            </div>

                            @if ($simulado->questoes_corretas === null)
                                <a href="{{ route('simulados.show', $simulado->id) }}" class="btn-primary" style="display: block; text-align: center; padding: 10px; background: #ff4444; color: white; text-decoration: none; border-radius: 6px;">
                                    Continuar
                                </a>
                            @else
                                <a href="{{ route('simulados.show', $simulado->id) }}?view=1" class="btn-secondary" style="display: block; text-align: center; padding: 10px; background: rgba(255,255,255,0.1); color: white; text-decoration: none; border-radius: 6px;">
                                    Ver Resultado
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
