@extends('layouts.app')

@section('title', $simulado->nome . ' - RCP Concursos')

@section('content')
<div class="container">
    <div class="header-content" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <h1 style="color: white; font-size: 1.8rem;"><i class="fas fa-clipboard-list"></i> {{ $simulado->nome }}</h1>
        <div class="user-info" style="display: flex; gap: 15px; align-items: center;">
            @if (!$viewMode)
                <div class="timer" id="timer" style="background: linear-gradient(45deg, #ff4444, #cc0000); color: white; padding: 10px 20px; border-radius: 25px; display: flex; align-items: center; gap: 8px; font-weight: 600;">
                    <i class="fas fa-clock"></i>
                    <span id="time-display">00:00</span>
                </div>
            @endif
            <a href="{{ route('simulados.index') }}" class="btn-secondary" style="padding: 10px 20px; text-decoration: none; display: flex; align-items: center; gap: 5px;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    @if ($viewMode)
        <!-- Modo Visualização de Resultado -->
        <section class="resultado-section" style="margin-bottom: 30px;">
            <div class="card" style="padding: 30px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2);">
                <div class="resultado-header" style="text-align: center; margin-bottom: 30px;">
                    <h2 style="color: white; margin-bottom: 30px;"><i class="fas fa-trophy" style="color: #ff4444;"></i> Resultado do Simulado</h2>
                    <div class="resultado-stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 20px;">
                        <div class="stat-item" style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 10px; text-align: center;">
                            <i class="fas fa-check-circle" style="font-size: 2rem; color: #28a745; margin-bottom: 10px;"></i>
                            <span style="display: block; font-size: 1.5rem; font-weight: 700;">{{ $simulado->questoes_corretas }}/{{ $simulado->questoes_total }}</span>
                            <small style="color: #aaa;">Acertos</small>
                        </div>
                        <div class="stat-item" style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 10px; text-align: center;">
                            <i class="fas fa-star" style="font-size: 2rem; color: #ffc107; margin-bottom: 10px;"></i>
                            <span style="display: block; font-size: 1.5rem; font-weight: 700;">{{ $simulado->pontuacao_final }}</span>
                            <small style="color: #aaa;">Pontos</small>
                        </div>
                        <div class="stat-item" style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 10px; text-align: center;">
                            <i class="fas fa-percentage" style="font-size: 2rem; color: #17a2b8; margin-bottom: 10px;"></i>
                            @php 
                                $percentual = 0;
                                if ($simulado->questoes_total > 0) {
                                    $percentual = round(($simulado->questoes_corretas / $simulado->questoes_total) * 100, 1);
                                }
                            @endphp
                            <span style="display: block; font-size: 1.5rem; font-weight: 700;">{{ $percentual }}%</span>
                            <small style="color: #aaa;">Taxa de Acerto</small>
                        </div>
                        @if ($simulado->tempo_gasto)
                            <div class="stat-item" style="padding: 20px; background: rgba(255,255,255,0.05); border-radius: 10px; text-align: center;">
                                <i class="fas fa-clock" style="font-size: 2rem; color: #6c757d; margin-bottom: 10px;"></i>
                                <span style="display: block; font-size: 1.5rem; font-weight: 700;">{{ $simulado->tempo_gasto }}min</span>
                                <small style="color: #aaa;">Tempo</small>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Questões -->
    <section class="questoes-section">
        <form id="simulado-form" method="POST" action="{{ route('simulados.finalizar', $simulado->id) }}">
            @csrf
            <input type="hidden" name="tempo_gasto" id="tempo-gasto" value="0">
            
            @foreach ($questoes as $index => $questao)
                <div class="card questao-card" style="margin-bottom: 25px; padding: 30px; border-radius: 15px; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1);">
                    <div class="questao-header" style="display: flex; justify-content: space-between; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; margin-bottom: 20px;">
                        <h3 style="margin: 0; font-size: 1.2rem;">Questão {{ $index + 1 }}</h3>
                        <!-- Se tiver relacionamento com disciplina no model Questao, poderia usar $questao->disciplina->nome -->
                        <span class="disciplina-tag" style="background: #ff4444; padding: 2px 10px; border-radius: 12px; font-size: 0.8rem;">
                            {{-- Placeholder pois o Join não foi feito explicitamente aqui, mas poderia --}}
                            Questão #{{ $questao->id }}
                        </span>
                    </div>
                    
                    <div class="questao-content">
                        <p class="enunciado" style="font-size: 1.1rem; line-height: 1.6; margin-bottom: 25px;">
                            {!! nl2br(e($questao->enunciado)) !!}
                        </p>
                        
                        <div class="alternativas" style="display: flex; flex-direction: column; gap: 15px;">
                            @foreach (['a', 'b', 'c', 'd', 'e'] as $alt)
                                @php
                                    $coluna = 'alternativa_' . $alt;
                                    $texto = $questao->$coluna;
                                    
                                    // Status Visual (apenas no modo view)
                                    $class = '';
                                    if ($viewMode) {
                                        // Recuperar resposta do usuário da pivot table
                                        // O objeto $questao veio de $simulado->questoes, então tem pivot
                                        $respostaUsuario = $questao->pivot->resposta_usuario;
                                        $isCorreta = $questao->alternativa_correta == strtoupper($alt);
                                        $isSelecionada = $respostaUsuario == strtoupper($alt);
                                        
                                        if ($isCorreta) $class = 'border-color: #28a745; background: rgba(40, 167, 69, 0.1);';
                                        elseif ($isSelecionada) $class = 'border-color: #dc3545; background: rgba(220, 53, 69, 0.1);';
                                    }
                                    
                                    // Se está checked
                                    $checked = $viewMode && isset($respostaUsuario) && $respostaUsuario == strtoupper($alt);
                                @endphp

                                <label class="alternativa-label" style="display: flex; align-items: flex-start; gap: 10px; padding: 15px; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; cursor: pointer; transition: 0.2s; {{ $class }}">
                                    <input type="radio" 
                                           name="questao_{{ $questao->id }}" 
                                           value="{{ strtoupper($alt) }}"
                                           {{ $viewMode ? 'disabled' : '' }}
                                           {{ $checked ? 'checked' : '' }}
                                           style="margin-top: 5px;">
                                    <span style="font-weight: bold; color: #ff4444;">{{ strtoupper($alt) }})</span>
                                    <span style="flex: 1;">{{ $texto }}</span>
                                    
                                    @if ($viewMode)
                                        @if ($questao->alternativa_correta == strtoupper($alt))
                                            <i class="fas fa-check" style="color: #28a745;"></i>
                                        @elseif ($checked && $questao->alternativa_correta != strtoupper($alt))
                                            <i class="fas fa-times" style="color: #dc3545;"></i>
                                        @endif
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach
            
            @if (!$viewMode)
                <div class="submit-section" style="text-align: center; margin-top: 40px; margin-bottom: 60px;">
                    <button type="submit" class="btn-primary" style="padding: 15px 40px; font-size: 1.2rem; border-radius: 10px; border: none; background: linear-gradient(45deg, #ff4444, #cc0000); color: white; cursor: pointer; box-shadow: 0 5px 15px rgba(255, 68, 68, 0.4);">
                        <i class="fas fa-check-double"></i> Finalizar Simulado
                    </button>
                </div>
            @endif
        </form>
    </section>
</div>

@if (!$viewMode)
<script>
    // Timer Logic
    let startTime = Date.now();
    let timerInterval;
    
    function updateTimer() {
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        
        const display = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        const timerDisplay = document.getElementById('time-display');
        if(timerDisplay) timerDisplay.textContent = display;
        
        const hiddenInput = document.getElementById('tempo-gasto');
        if(hiddenInput) hiddenInput.value = Math.floor(elapsed / 60);
    }
    
    // Iniciar timer
    timerInterval = setInterval(updateTimer, 1000);
    
    // Parar timer ao enviar
    document.getElementById('simulado-form').addEventListener('submit', function() {
        clearInterval(timerInterval);
    });
    
    // Warn before leave
    window.addEventListener('beforeunload', function (e) {
        // e.preventDefault();
        // e.returnValue = '';
        // Desativado para não atrapalhar testes, mas user original tinha lógica de autosave
    });
</script>
@endif

<style>
    .alternativa-label:hover {
        background: rgba(255,255,255,0.05);
    }
    input[type='radio']:checked + span {
        color: white;
    }
    /* Estilo simples para radio custom se necessário, mantendo nativo por enquanto */
</style>
@endsection
