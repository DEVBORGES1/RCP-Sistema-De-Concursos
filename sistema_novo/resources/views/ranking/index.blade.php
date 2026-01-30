@extends('layouts.app')

@section('title', 'Ranking Mensal - RCP Concursos')

@section('content')
<div class="ranking-container">
    
    <div class="ranking-header">
        <h1><i class="fas fa-trophy" style="color: #FFD700;"></i> Ranking Mensal</h1>
        <p>Os melhores estudantes deste mês. Vença duelos e suba no topo!</p>
    </div>

    <!-- Podium Section (Top 3) -->
    <div class="podium-section">
        @if(count($topPlayers) >= 3)
            <!-- 2nd Place -->
            <div class="podium-item second">
                <div class="medal silver">2</div>
                <div class="podium-avatar">
                   <i class="fas fa-user-circle"></i>
                </div>
                <div class="podium-name">{{ $topPlayers[1]->nome }}</div>
                <div class="podium-score">{{ $topPlayers[1]->pontos_mes }} pts</div>
                <div class="podium-block"></div>
            </div>
            
            <!-- 1st Place -->
            <div class="podium-item first">
                <div class="crowning"><i class="fas fa-crown"></i></div>
                <div class="medal gold">1</div>
                <div class="podium-avatar winner">
                   <i class="fas fa-user-circle"></i>
                </div>
                <div class="podium-name">{{ $topPlayers[0]->nome }}</div>
                <div class="podium-score">{{ $topPlayers[0]->pontos_mes }} pts</div>
                <div class="podium-block"></div>
            </div>

            <!-- 3rd Place -->
            <div class="podium-item third">
                <div class="medal bronze">3</div>
                <div class="podium-avatar">
                   <i class="fas fa-user-circle"></i>
                </div>
                <div class="podium-name">{{ $topPlayers[2]->nome }}</div>
                <div class="podium-score">{{ $topPlayers[2]->pontos_mes }} pts</div>
                <div class="podium-block"></div>
            </div>
        @else
            <!-- Fallback if not enough data yet -->
            <div style="text-align:center; width:100%; opacity:0.7;">
                <p>Dados insuficientes para formar o pódio ainda! Seja o primeiro a jogar.</p>
            </div>
        @endif
    </div>

    <!-- List Section (4th onwards) -->
    <div class="ranking-list">
        @foreach($topPlayers as $index => $player)
            @if($index >= 3)
                <div class="ranking-row {{ $player->nome == Auth::user()->nome ? 'highlight' : '' }}">
                    <div class="rank-pos">{{ $player->posicao }}º</div>
                    <div class="rank-user">
                        <div class="rank-avatar mini">
                            <i class="fas fa-user"></i>
                        </div>
                        <span>{{ $player->nome }}</span>
                    </div>
                    <div class="rank-score">{{ $player->pontos_mes }} <span>pts</span></div>
                </div>
            @endif
        @endforeach
    </div>

</div>

<style>
    .ranking-container {
        max-width: 800px;
        margin: 40px auto;
        color: #fff;
    }
    .ranking-header { text-align: center; margin-bottom: 50px; }
    .ranking-header h1 { font-size: 2.5rem; font-weight: 800; margin-bottom: 10px; }
    .ranking-header p { color: #94a3b8; }

    /* PODIUM */
    .podium-section {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        height: 350px;
        gap: 20px;
        margin-bottom: 50px;
    }
    .podium-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 120px;
        position: relative;
    }
    .podium-block {
        width: 100%;
        border-radius: 12px 12px 0 0;
        background: linear-gradient(180deg, rgba(255,255,255,0.1), rgba(255,255,255,0.02));
        border: 1px solid rgba(255,255,255,0.1);
        border-bottom: none;
    }
    .podium-item.first .podium-block { height: 160px; background: linear-gradient(180deg, rgba(255, 215, 0, 0.2), rgba(255, 215, 0, 0.05)); border-color: rgba(255, 215, 0, 0.3); }
    .podium-item.second .podium-block { height: 120px; background: linear-gradient(180deg, rgba(192, 192, 192, 0.2), rgba(192, 192, 192, 0.05)); border-color: rgba(192, 192, 192, 0.3); }
    .podium-item.third .podium-block { height: 90px; background: linear-gradient(180deg, rgba(205, 127, 50, 0.2), rgba(205, 127, 50, 0.05)); border-color: rgba(205, 127, 50, 0.3); }

    .podium-avatar {
        width: 60px; height: 60px; border-radius: 50%;
        background: #1e293b; border: 3px solid #334155;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; color: #64748b;
        margin-bottom: 15px;
        box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    }
    .first .podium-avatar { width: 80px; height: 80px; border-color: #FFD700; color: #FFD700; }
    
    .podium-name { font-weight: 700; margin-bottom: 5px; text-shadow: 0 2px 4px rgba(0,0,0,0.5); }
    .podium-score { font-size: 0.9rem; opacity: 0.8; margin-bottom: 10px; }
    .crowning { position: absolute; top: -110px; color: #FFD700; font-size: 2rem; animation: float 2s infinite ease-in-out; }

    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

    /* LIST */
    .ranking-list {
        background: rgba(30, 41, 59, 0.5);
        border-radius: 20px;
        padding: 20px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    .ranking-row {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid rgba(255,255,255,0.05);
        transition: background 0.2s;
        border-radius: 10px;
    }
    .ranking-row:last-child { border-bottom: none; }
    .ranking-row:hover { background: rgba(255,255,255,0.05); }
    .ranking-row.highlight { background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); }
    
    .rank-pos { width: 40px; font-weight: 900; font-size: 1.2rem; color: #64748b; }
    .rank-user { flex-grow: 1; display: flex; align-items: center; gap: 15px; font-weight: 600; }
    .rank-score { font-weight: 700; color: #4ade80; }
    .rank-score span { font-size: 0.8rem; color: #64748b; font-weight: normal; }

    .rank-avatar.mini {
        width: 30px; height: 30px; border-radius: 50%;
        background: #0f172a; display: flex; align-items: center; justify-content: center;
        font-size: 0.8rem; color: #94a3b8;
    }
</style>
@endsection
