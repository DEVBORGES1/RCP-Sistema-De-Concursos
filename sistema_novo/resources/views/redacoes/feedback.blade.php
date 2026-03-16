@extends('layouts.app')

@section('title', 'Feedback da Redação')

@section('content')
<div class="feedback-wrapper">
    <div class="feedback-header">
        <a href="{{ route('redacoes.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Voltar aos Temas</a>
        <h2>Feedback da Inteligência Artificial</h2>
        <p>Tema: <strong>{{ $redacao->tema->titulo }}</strong></p>
    </div>

    <!-- Score Card Principal -->
    <div class="score-showcase">
        <div class="score-circle">
            <svg viewBox="0 0 36 36" class="circular-chart {{ $redacao->nota_total >= 80 ? 'green' : ($redacao->nota_total >= 50 ? 'orange' : 'red') }}">
              <path class="circle-bg"
                d="M18 2.0845
                  a 15.9155 15.9155 0 0 1 0 31.831
                  a 15.9155 15.9155 0 0 1 0 -31.831"
              />
              <path class="circle"
                stroke-dasharray="{{ $redacao->nota_total }}, 100"
                d="M18 2.0845
                  a 15.9155 15.9155 0 0 1 0 31.831
                  a 15.9155 15.9155 0 0 1 0 -31.831"
              />
              <text x="18" y="20.35" class="percentage">{{ $redacao->nota_total }}</text>
            </svg>
            <div class="score-label">Nota Final</div>
        </div>
        
        <div class="score-details">
            <h3 class="feedback-title">Análise de Critérios</h3>
            <div class="criteria-list">
                <!-- Gramática -->
                <div class="criterion">
                    <div class="crit-header">
                        <span><i class="fas fa-spell-check"></i> Gramática e Ortografia</span>
                        <strong>{{ $redacao->criterios_nota['gramatica'] ?? 0 }}/100</strong>
                    </div>
                    <div class="progress-bar"><div class="progress-fill" style="width: {{ $redacao->criterios_nota['gramatica'] ?? 0 }}%"></div></div>
                </div>
                
                <!-- Coesão -->
                <div class="criterion">
                    <div class="crit-header">
                        <span><i class="fas fa-link"></i> Coesão e Coerência</span>
                        <strong>{{ $redacao->criterios_nota['coesao_coerencia'] ?? 0 }}/100</strong>
                    </div>
                    <div class="progress-bar"><div class="progress-fill" style="width: {{ $redacao->criterios_nota['coesao_coerencia'] ?? 0 }}%"></div></div>
                </div>
                
                <!-- Tema/Argumentação -->
                <div class="criterion">
                    <div class="crit-header">
                        <span><i class="fas fa-bullseye"></i> Argumentação e Tema</span>
                        <strong>{{ $redacao->criterios_nota['fuga_tema'] ?? 0 }}/100</strong>
                    </div>
                    <div class="progress-bar"><div class="progress-fill" style="width: {{ $redacao->criterios_nota['fuga_tema'] ?? 0 }}%"></div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="feedback-grid">
        <!-- Parecer Geral da IA -->
        <div class="ai-comments">
            <h3 class="panel-title"><i class="fas fa-robot"></i> Comentários do Corretor IA</h3>
            <div class="comment-content">
                {!! nl2br($redacao->feedback_ia) !!}
            </div>
            <div class="info-footer">
                <i class="fas fa-info-circle"></i> Esta correção foi gerada por Inteligência Artificial visando padrões rigorosos de bancas de concurso. Use as dicas para melhorar na próxima tentativa.
            </div>
        </div>

        <!-- Texto Original Enviado -->
        <div class="original-text-panel">
            <h3 class="panel-title"><i class="fas fa-file-alt"></i> Seu Texto Original</h3>
            <div class="original-content">
                {!! nl2br(e($redacao->texto_enviado)) !!}
            </div>
        </div>
    </div>

</div>

<style>
    .feedback-wrapper {
        max-width: 1100px;
        margin: 0 auto;
        padding-bottom: 80px;
    }

    .feedback-header {
        text-align: center;
        margin-bottom: 40px;
    }
    
    .back-link {
        display: inline-block;
        color: var(--dash-text-secondary, #94a3b8);
        text-decoration: none;
        font-weight: 600;
        margin-bottom: 20px;
        background: var(--dash-bg-card, rgba(255,255,255,0.05));
        padding: 8px 16px;
        border-radius: 100px;
        transition: all 0.2s;
    }
    .back-link:hover { color: #fff; background: rgba(255,255,255,0.1); }
    
    .feedback-header h2 {
        font-family: 'Syne', sans-serif;
        font-size: 2.2rem;
        margin-bottom: 10px;
        color: var(--dash-text-primary, #fff);
    }
    .feedback-header p {
        color: var(--dash-text-secondary, #94a3b8);
        font-size: 1.1rem;
    }

    /* SCORE SHOWCASE */
    .score-showcase {
        display: flex;
        align-items: center;
        gap: 50px;
        background: var(--dash-bg-card, rgba(255,255,255,0.02));
        border: 1px solid var(--dash-border, rgba(255,255,255,0.05));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 24px;
        padding: 40px;
        margin-bottom: 40px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .score-circle {
        text-align: center;
        width: 180px;
    }
    
    .circular-chart {
        display: block;
        margin: 0 auto;
        max-width: 80%;
        max-height: 250px;
    }
    .circle-bg { fill: none; stroke: rgba(255,255,255,0.05); stroke-width: 3.8; }
    .circle { fill: none; stroke-width: 2.8; stroke-linecap: round; animation: progress 1s ease-out forwards; }
    
    @keyframes progress { 0% { stroke-dasharray: 0 100; } }
    
    .circular-chart.green .circle { stroke: #10B981; filter: drop-shadow(0 0 8px rgba(16,185,129,0.5)); }
    .circular-chart.orange .circle { stroke: #F59E0B; filter: drop-shadow(0 0 8px rgba(245,158,11,0.5)); }
    .circular-chart.red .circle { stroke: #EF4444; filter: drop-shadow(0 0 8px rgba(239,68,68,0.5)); }
    
    .percentage { fill: #fff; font-family: 'Syne', sans-serif; font-size: 0.5em; font-weight: 800; text-anchor: middle; }
    
    .score-label {
        font-weight: 700;
        font-size: 1.2rem;
        color: var(--dash-text-primary, #fff);
        margin-top: 15px;
        font-family: 'Outfit', sans-serif;
    }

    .score-details { flex-grow: 1; }
    
    .feedback-title {
        font-family: 'Syne', sans-serif;
        font-size: 1.5rem;
        margin-bottom: 25px;
        color: var(--dash-text-primary);
    }

    .criteria-list { display: flex; flex-direction: column; gap: 20px; }
    
    .criterion { width: 100%; }
    .crit-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.95rem;
        color: var(--dash-text-secondary);
        font-weight: 600;
    }
    .crit-header i { margin-right: 5px; color: #8B5CF6; }
    
    .progress-bar {
        width: 100%; height: 8px; background: rgba(255,255,255,0.05); border-radius: 10px; overflow: hidden;
    }
    .progress-fill { height: 100%; background: linear-gradient(90deg, #8B5CF6, #3B82F6); border-radius: 10px; }

    /* CONTENT GRID */
    .feedback-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .ai-comments, .original-text-panel {
        background: var(--dash-bg-card, rgba(255,255,255,0.02));
        border: 1px solid var(--dash-border, rgba(255,255,255,0.05));
        border-radius: 20px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .panel-title {
        background: rgba(0,0,0,0.2);
        padding: 18px 25px;
        margin: 0;
        font-size: 1.1rem;
        font-family: 'Syne', sans-serif;
        font-weight: 700;
        color: var(--dash-text-primary);
        border-bottom: 1px solid var(--dash-border, rgba(255,255,255,0.05));
    }
    .panel-title i { margin-right: 8px; color: #EC4899; }

    .comment-content, .original-content {
        padding: 30px;
        line-height: 1.8;
        font-size: 1rem;
        color: var(--dash-text-primary, #e2e8f0);
        flex-grow: 1;
    }
    
    .original-content {
        color: var(--dash-text-secondary, #94a3b8);
        font-style: italic;
        background: rgba(0,0,0,0.1);
    }
    
    .info-footer {
        padding: 15px 25px;
        background: rgba(139, 92, 246, 0.1);
        border-top: 1px solid rgba(139, 92, 246, 0.2);
        color: #A78BFA;
        font-size: 0.85rem;
        line-height: 1.5;
    }

    @media (max-width: 900px) {
        .score-showcase { flex-direction: column; text-align: center; gap: 30px; padding: 30px 20px; }
        .feedback-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection
