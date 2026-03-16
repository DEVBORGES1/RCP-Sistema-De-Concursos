@extends('layouts.app')

@section('title', 'Dashboard - RCP Concursos')

@section('content')
    @php 
        /** @var \App\Models\User $user */ 
        $user = Auth::user();
        
        $dailyGoal = 2 * 60; 
        $dailyProgress = 45; 
        $dailyPercent = ($dailyProgress / $dailyGoal) * 100;
        $streak = $streak_usuario ?? 5; 
    @endphp

    <div class="dashboard-container fade-in">
        <!-- HEADER / HERO -->
        <section class="hero-section">
            <div class="hero-card fade-in delay-1">
                <div class="hero-content-wrapper" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px;">
                    <div style="display: flex; gap: 20px; align-items: center;">
                        <!-- Mascot Image -->
                        <div class="hero-mascot-wrapper">
                             <img src="{{ asset('assets/imagens/mascotes/mascot_welcome_fresh.png') }}?v={{ time() }}" alt="Mascote" style="height: 120px; filter: drop-shadow(0 4px 15px rgba(0,0,0,0.5)); transform: rotate(-5deg); transition: transform 0.3s;" onmouseover="this.style.transform='rotate(0deg) scale(1.1)'" onmouseout="this.style.transform='rotate(-5deg)'">
                        </div>

                        <div class="hero-welcome">
                            <h2>Olá, <span style="color: var(--dash-primary);">{{ explode(' ', $user->nome)[0] }}</span>!</h2>
                            <p>Sua jornada rumo à aprovação continua.</p>
                        </div>
                    </div>
                    
                    <div class="hero-stats">
                        <div class="stat-item-hero">
                            <div class="stat-icon-hero" style="color: var(--dash-accent)"><i class="fas fa-bullseye"></i></div>
                            <div class="stat-info-hero">
                                <span class="stat-val">{{ round($dailyPercent) }}%</span>
                                <span class="stat-label">Meta diária</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Streak Card -->
            <div class="streak-card fade-in delay-2">
                <div class="fire-anim"><i class="fas fa-fire"></i></div>
                <div class="stat-info-hero" style="align-items: center;">
                    <span class="stat-val" style="font-size: 2rem;">{{ $streak }}</span>
                    <span class="stat-label">Dias Seguidos</span>
                </div>
            </div>
        </section>

        <!-- MAIN GRID -->
        <div class="dashboard-main-grid">
            
            <!-- LEFT COLUMN: ACTIONS -->
            <div class="dash-col-left fade-in delay-3">
                <div class="card-header">
                    <h3><i class="fas fa-rocket" style="color: var(--dash-primary)"></i> Acesso Rápido</h3>
                </div>
                
                <div class="quick-actions-grid">
                    <a href="{{ route('jogo.index') }}" class="action-card play fade-in delay-4">
                        <div class="action-icon"><i class="fas fa-gamepad"></i></div>
                        <h3>Jogar Agora</h3>
                        <p>Duelo Multiplayer</p>
                    </a>

                    <a href="{{ route('ranking.index') }}" class="action-card fade-in delay-4">
                        <div class="action-icon" style="color: var(--dash-warning)"><i class="fas fa-trophy"></i></div>
                        <h3>Ranking</h3>
                        <p>Ver Classificação</p>
                    </a>

                    <a href="{{ route('simulados.index') }}" class="action-card fade-in delay-4">
                        <div class="action-icon" style="color: var(--dash-accent)"><i class="fas fa-file-alt"></i></div>
                        <h3>Simulados</h3>
                        <p>Testar Conhecimentos</p>
                    </a>
                    
                    <a href="{{ route('questoes.index') }}" class="action-card fade-in delay-5">
                        <div class="action-icon" style="color: var(--dash-primary)"><i class="fas fa-list"></i></div>
                        <h3>Questões</h3>
                        <p>Prática Direcionada</p>
                    </a>
                    
                    <a href="{{ route('videoaulas.index') }}" class="action-card fade-in delay-5">
                        <div class="action-icon" style="color: #ef4444"><i class="fas fa-video"></i></div>
                        <h3>Aulas</h3>
                        <p>Aprender Novos Temas</p>
                    </a>
                    
                    <a href="{{ route('editais.index') }}" class="action-card fade-in delay-5">
                        <div class="action-icon" style="color: #64748b"><i class="fas fa-clipboard-check"></i></div>
                        <h3>Editais</h3>
                        <p>Acompanhar Vagas</p>
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: STATS/HISTORY -->
            <div class="dash-col-right fade-in delay-6">
                <div class="dash-card">
                    <div class="card-header">
                        <h3><i class="fas fa-chart-area" style="color: var(--dash-primary)"></i> Produtividade</h3>
                    </div>
                    <div style="height: 200px; position: relative;">
                         <canvas id="studyChart"></canvas>
                    </div>
                </div>

                <div class="dash-card" style="margin-top: 24px;">
                    <div class="card-header">
                        <h3><i class="fas fa-history" style="color: var(--dash-accent)"></i> Atividades Recentes</h3>
                    </div>
                    <div class="history-list">
                         @php
                            $ultimasPartidas = \App\Models\Partida::where(function($q) use ($user) {
                                    $q->where('jogador1', $user->id)->orWhere('jogador2', $user->id);
                                })
                                ->where('status', 'finalizada')
                                ->orderBy('atualizado_em', 'desc')
                                ->take(3)
                                ->get();
                        @endphp
                        
                        @forelse($ultimasPartidas as $partida)
                             @php
                                $placar = DB::table('partida_pontos')->where('partida_id', $partida->id)->where('usuario_id', $user->id)->first();
                                $pontos = $placar ? $placar->pontos : 0;
                                $isWin = $pontos >= 0;
                            @endphp
                            <div class="match-item">
                                <div class="match-info">
                                    <div class="match-icon"><i class="fas fa-bolt"></i></div>
                                    <div class="match-details">
                                        <h4>Duelo 1x1</h4>
                                        <p class="match-date">{{ $partida->atualizado_em->format('d/m H:i') }}</p>
                                    </div>
                                </div>
                                <div class="match-score {{ $isWin ? 'win' : 'loss' }}">
                                    {{ $isWin ? '+' : '' }}{{ $pontos }} xp
                                </div>
                            </div>
                        @empty
                            <div style="padding: 20px; text-align: center; color: var(--dash-text-secondary);">Nenhuma atividade recente.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Setup -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('studyChart');
            if(ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'],
                        datasets: [{
                            label: 'XP Ganho',
                            data: [150, 300, 100, 450, 200, 600, 500],
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                            fill: true, tension: 0.4,
                            borderWidth: 2,
                            pointBackgroundColor: '#8b5cf6',
                            pointHoverBackgroundColor: '#fff',
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            x: { grid: { display: false }, ticks: { color: '#94a3b8' } },
                            y: { grid: { color: 'rgba(255,255,255,0.05)', drawBorder: false }, ticks: { color: '#94a3b8' } }
                        }
                    }
                });
            }
        });
    </script>
@endsection
