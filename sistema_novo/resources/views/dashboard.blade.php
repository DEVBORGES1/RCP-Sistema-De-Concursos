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

    <div class="dash-container">
        <!-- HEADER / HERO -->
        <header class="dash-hero">
            <div class="hero-content">
                <!-- Mascot Image -->
                <div class="hero-mascot-wrapper" style="margin-right: 20px;">
                     <img src="{{ asset('assets/imagens/mascotes/mascot_welcome_fresh.png') }}?v={{ time() }}" alt="Mascote" style="height: 140px; filter: drop-shadow(0 4px 15px rgba(0,0,0,0.5)); transform: rotate(-5deg); transition: transform 0.3s;" onmouseover="this.style.transform='rotate(0deg) scale(1.1)'" onmouseout="this.style.transform='rotate(-5deg)'">
                </div>

                <div class="user-welcome">
                    <h1>Olá, <span class="highlight-name">{{ explode(' ', $user->nome)[0] }}</span>!</h1>
                    <p>Sua jornada rumo à aprovação continua.</p>
                </div>
                
                <div class="hero-stats-row">
                    <div class="hero-stat-card">
                        <div class="stat-icon"><i class="fas fa-fire"></i></div>
                        <div class="stat-info">
                            <span class="stat-value">{{ $streak }}</span>
                            <span class="stat-label">Dias seguidos</span>
                        </div>
                    </div>
                    
                    <div class="hero-stat-card">
                        <div class="stat-icon" style="color:#4ade80"><i class="fas fa-bullseye"></i></div>
                        <div class="stat-info">
                            <span class="stat-value">{{ round($dailyPercent) }}%</span>
                            <span class="stat-label">Meta diária</span>
                        </div>
                        <div class="progress-ring-mini">
                            <svg width="40" height="40">
                                <circle r="18" cx="20" cy="20" class="ring-bg"></circle>
                                <circle r="18" cx="20" cy="20" class="ring-fill" style="stroke-dasharray: 113; stroke-dashoffset: {{ 113 - (113 * $dailyPercent / 100) }};"></circle>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- MAIN GRID -->
        <div class="dash-grid">
            
            <!-- LEFT COLUMN: ACTIONS -->
            <div class="dash-col-left">
                <div class="section-title">
                    <h3><i class="fas fa-rocket"></i> Acesso Rápido</h3>
                </div>
                
                <div class="action-buttons-grid">
                    <a href="{{ route('jogo.index') }}" class="action-btn-large play">
                        <div class="btn-icon"><i class="fas fa-gamepad"></i></div>
                        <div class="btn-text">
                            <h4>Jogar Agora</h4>
                            <p>Duelo Multiplayer</p>
                        </div>
                        <div class="btn-arrow"><i class="fas fa-chevron-right"></i></div>
                    </a>

                    <a href="{{ route('ranking.index') }}" class="action-btn-large rank">
                        <div class="btn-icon"><i class="fas fa-trophy"></i></div>
                        <div class="btn-text">
                            <h4>Ranking</h4>
                            <p>Ver Classificação</p>
                        </div>
                    </a>

                    <a href="{{ route('simulados.index') }}" class="action-btn-normal">
                        <i class="fas fa-file-alt"></i> Simulados
                    </a>
                    
                    <a href="{{ route('questoes.index') }}" class="action-btn-normal">
                        <i class="fas fa-list"></i> Questões
                    </a>
                    
                    <a href="{{ route('videoaulas.index') }}" class="action-btn-normal">
                        <i class="fas fa-video"></i> Aulas
                    </a>
                    
                    <a href="{{ route('editais.index') }}" class="action-btn-normal">
                        <i class="fas fa-clipboard-check"></i> Editais
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: STATS/HISTORY -->
            <div class="dash-col-right">
                <div class="dash-card-glass chart-card">
                    <div class="card-head">
                        <h3><i class="fas fa-chart-area"></i> Produtividade</h3>
                    </div>
                    <div class="chart-wrapper">
                         <canvas id="studyChart"></canvas>
                    </div>
                </div>

                <div class="dash-card-glass history-card">
                    <div class="card-head">
                        <h3><i class="fas fa-history"></i> Atividades Recentes</h3>
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
                            @endphp
                            <div class="history-item">
                                <div class="h-icon"><i class="fas fa-bolt"></i></div>
                                <div class="h-info">
                                    <span class="h-title">Duelo 1x1</span>
                                    <span class="h-date">{{ $partida->atualizado_em->format('d/m H:i') }}</span>
                                </div>
                                <div class="h-score">+{{ $pontos }} xp</div>
                            </div>
                        @empty
                            <div style="padding: 20px; text-align: center; color: #94a3b8;">Nenhuma atividade recente.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Styles injected directly for this view or move to dashboard.css -->
    <style>
        .dash-container { max-width: 1200px; margin: 0 auto; padding: 20px; color: #fff; }
        
        .dash-hero {
            background: linear-gradient(135deg, rgba(30,41,59,0.8), rgba(15,23,42,0.9)), url('{{ asset("assets/img/pattern.png") }}');
            border-radius: 24px; padding: 40px; margin-bottom: 40px;
            border: 1px solid rgba(255,255,255,0.05);
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
            position: relative; overflow: hidden;
        }
        .dash-hero::before { content:''; position:absolute; top:0; left:0; width:100%; height:100%; background: radial-gradient(circle at 80% 20%, rgba(139,92,246,0.15), transparent 50%); pointer-events:none; }
        
        .hero-content { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; position: relative; z-index: 2; }
        
        .user-welcome h1 { font-size: 2.2rem; margin: 0; font-weight: 800; }
        .highlight-name { background: linear-gradient(to right, #818cf8, #c084fc); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .user-welcome p { color: #94a3b8; font-size: 1.1rem; margin-top: 5px; }

        .hero-stats-row { display: flex; gap: 20px; }
        .hero-stat-card {
            background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.1);
            padding: 15px 25px; border-radius: 16px; display: flex; align-items: center; gap: 15px;
            backdrop-filter: blur(10px);
        }
        .stat-icon { font-size: 1.5rem; color: #f97316; }
        .stat-info { display: flex; flex-direction: column; }
        .stat-value { font-weight: 800; font-size: 1.2rem; }
        .stat-label { font-size: 0.8rem; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
        
        /* Ring */
        .progress-ring-mini circle { fill: transparent; stroke-width: 4; }
        .ring-bg { stroke: rgba(255,255,255,0.1); }
        .ring-fill { stroke: #4ade80; stroke-linecap: round; transform: rotate(-90deg); transform-origin: 50% 50%; }

        /* GRID */
        .dash-grid { display: grid; grid-template-columns: 1.2fr 1.8fr; gap: 30px; }
        @media (max-width: 900px) { .dash-grid { grid-template-columns: 1fr; } }

        .section-title h3 { font-size: 1.2rem; margin-bottom: 20px; color: #e2e8f0; display: flex; align-items: center; gap: 10px; }
        
        .action-buttons-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .action-btn-large {
            grid-column: span 2;
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px; border-radius: 16px; text-decoration: none; color: white;
            transition: transform 0.2s, box-shadow 0.2s;
            position: relative; overflow: hidden;
        }
        .action-btn-large:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.3); }
        .action-btn-large.play { background: linear-gradient(135deg, #4f46e5, #7c3aed); }
        .action-btn-large.rank { background: linear-gradient(135deg, #059669, #10b981); }
        
        .btn-icon { font-size: 1.8rem; opacity: 0.9; }
        .btn-text { flex-grow: 1; margin-left: 15px; }
        .btn-text h4 { margin: 0; font-size: 1.1rem; font-weight: 700; }
        .btn-text p { margin: 0; font-size: 0.85rem; opacity: 0.8; }
        
        .action-btn-normal {
            background: rgba(30,41,59,0.5); border: 1px solid rgba(255,255,255,0.05);
            padding: 15px; border-radius: 12px; color: #cbd5e1; text-decoration: none;
            display: flex; align-items: center; gap: 10px; font-weight: 600;
            transition: all 0.2s;
        }
        .action-btn-normal:hover { background: rgba(51,65,85,0.5); color: #fff; border-color: rgba(255,255,255,0.2); }

        /* RIGHT CARDS */
        .dash-card-glass {
            background: rgba(30,41,59,0.3); border: 1px solid rgba(255,255,255,0.05);
            border-radius: 20px; padding: 25px; margin-bottom: 25px;
        }
        .card-head h3 { margin: 0 0 20px 0; font-size: 1.1rem; color: #94a3b8; display: flex; align-items: center; gap: 10px; }
        
        .history-item {
            display: flex; align-items: center; gap: 15px;
            padding: 12px; border-radius: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.03);
        }
        .history-item:last-child { border: none; }
        .h-icon {
            width: 40px; height: 40px; border-radius: 50%;
            background: rgba(124, 58, 237, 0.1); color: #8b5cf6;
            display: flex; align-items: center; justify-content: center;
        }
        .h-info { flex-grow: 1; display: flex; flex-direction: column; }
        .h-title { font-weight: 600; font-size: 0.95rem; }
        .h-date { font-size: 0.8rem; color: #64748b; }
        .h-score { font-weight: 700; color: #10b981; }

        .chart-wrapper { height: 200px; position: relative; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('studyChart').getContext('2d');
            // Mock data
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab', 'Dom'],
                    datasets: [{
                        label: 'XP Ganho',
                        data: [150, 300, 100, 450, 200, 600, 500],
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        fill: true, tension: 0.4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#64748b' } },
                        y: { grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#64748b' } }
                    }
                }
            });
        });
    </script>
@endsection
