@extends('layouts.app')

@section('title', 'Ranking Mensal - RCP Concursos')

@section('content')
<div class="ranking-page-wrapper" style="position: relative; overflow: hidden; padding-bottom: 100px; min-height: 80vh;">
    
    <!-- Efeitos de Fundo (Blur/Glow) Cosmic Mesh -->
    <div style="position: absolute; top: -10%; left: -10%; width: 50vw; height: 50vh; background: radial-gradient(circle, rgba(124, 58, 237, 0.15) 0%, transparent 60%); filter: blur(60px); z-index: 0; pointer-events: none;"></div>
    <div style="position: absolute; bottom: 10%; right: -10%; width: 50vw; height: 50vh; background: radial-gradient(circle, rgba(236, 72, 153, 0.1) 0%, transparent 60%); filter: blur(60px); z-index: 0; pointer-events: none;"></div>

    <div class="ranking-container" style="position: relative; z-index: 2;">
        
        <div class="ranking-header">
            <div class="hero-badge" style="display: inline-block; padding: 8px 18px; border-radius: 100px; background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); color: #F59E0B; font-weight: 600; margin-bottom: 20px; font-size: 0.9rem; letter-spacing: 0.5px;">
                <i class="fas fa-crown" style="margin-right: 5px;"></i> Hall da Fama
            </div>
            <h1>Ranking <span style="background: linear-gradient(135deg, var(--dash-text-primary, #fff) 30%, #F59E0B 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Global</span></h1>
            <p>Os maiores estudantes da plataforma. Resolva questões, vença duelos e domine o pódio!</p>
        </div>

        <!-- Podium Section (Top 3) -->
        <div class="podium-section">
            @if(count($topPlayers) >= 3)
                <!-- 2nd Place -->
                <div class="podium-item second">
                    <div class="medal silver"><i class="fas fa-medal"></i></div>
                   <div class="podium-avatar">
                       @if($topPlayers[1]->foto_perfil)
                           <img src="{{ str_starts_with($topPlayers[1]->foto_perfil, 'http') || str_starts_with($topPlayers[1]->foto_perfil, '/') ? asset($topPlayers[1]->foto_perfil) : asset('storage/' . $topPlayers[1]->foto_perfil) }}" alt="Avatar">
                       @else
                           <i class="fas fa-user-circle"></i>
                       @endif
                    </div>
                    <div class="podium-name">{{ explode(' ', $topPlayers[1]->nome)[0] }}</div>
                    <div class="podium-score">{{ number_format($topPlayers[1]->pontos_mes, 0, ',', '.') }} XP</div>
                    <div class="podium-block">2</div>
                </div>
                
                <!-- 1st Place -->
                <div class="podium-item first">
                    <div class="crowning"><i class="fas fa-crown"></i></div>
                    <div class="medal gold"><i class="fas fa-trophy"></i></div>
                    <div class="podium-avatar winner">
                       @if($topPlayers[0]->foto_perfil)
                           <img src="{{ str_starts_with($topPlayers[0]->foto_perfil, 'http') || str_starts_with($topPlayers[0]->foto_perfil, '/') ? asset($topPlayers[0]->foto_perfil) : asset('storage/' . $topPlayers[0]->foto_perfil) }}" alt="Avatar">
                       @else
                           <i class="fas fa-user-circle"></i>
                       @endif
                    </div>
                    <div class="podium-name">{{ explode(' ', $topPlayers[0]->nome)[0] }}</div>
                    <div class="podium-score">{{ number_format($topPlayers[0]->pontos_mes, 0, ',', '.') }} XP</div>
                    <div class="podium-block">1</div>
                </div>

                <!-- 3rd Place -->
                <div class="podium-item third">
                    <div class="medal bronze"><i class="fas fa-medal"></i></div>
                    <div class="podium-avatar">
                       @if($topPlayers[2]->foto_perfil)
                           <img src="{{ str_starts_with($topPlayers[2]->foto_perfil, 'http') || str_starts_with($topPlayers[2]->foto_perfil, '/') ? asset($topPlayers[2]->foto_perfil) : asset('storage/' . $topPlayers[2]->foto_perfil) }}" alt="Avatar">
                       @else
                           <i class="fas fa-user-circle"></i>
                       @endif
                    </div>
                    <div class="podium-name">{{ explode(' ', $topPlayers[2]->nome)[0] }}</div>
                    <div class="podium-score">{{ number_format($topPlayers[2]->pontos_mes, 0, ',', '.') }} XP</div>
                    <div class="podium-block">3</div>
                </div>
            @else
                <!-- Fallback if not enough data yet -->
                <div class="empty-podium">
                    <i class="fas fa-ghost" style="font-size: 3rem; color: var(--dash-text-secondary); margin-bottom: 15px;"></i>
                    <p>Ainda não há jogadores suficientes para formar o pódio!<br>Seja o primeiro a subir aqui.</p>
                </div>
            @endif
        </div>

        <!-- List Section (4th onwards) -->
        <div class="ranking-list">
            <div class="list-header">
                <div>Posição</div>
                <div>Aspirante</div>
                <div style="text-align: right;">Experiência</div>
            </div>
            
            <div class="list-body">
                @foreach($topPlayers as $index => $player)
                    @if($index >= 3)
                        <div class="ranking-row {{ $player->nome == Auth::user()->nome ? 'highlight' : '' }}">
                            <div class="rank-pos">#{{ $player->posicao }}</div>
                            <div class="rank-user">
                                <div class="rank-avatar mini">
                                    @if($player->foto_perfil)
                                        <img src="{{ str_starts_with($player->foto_perfil, 'http') || str_starts_with($player->foto_perfil, '/') ? asset($player->foto_perfil) : asset('storage/' . $player->foto_perfil) }}" alt="Avatar">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <span>{{ $player->nome }}</span>
                            </div>
                            <div class="rank-score">{{ number_format($player->pontos_mes, 0, ',', '.') }} <span style="font-size: 0.8em; opacity: 0.7; font-weight: normal;">XP</span></div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Sticky Current User Bar -->
            @if(isset($minhaPosicao) && count($topPlayers) > 0)
            <div class="current-user-sticky">
                <div class="rank-pos" style="color: var(--primary-color);">#{{ $minhaPosicao ?? '?' }}</div>
                <div class="rank-user">
                    <div class="rank-avatar mini" style="border-color: var(--primary-color);">
                        @if(Auth::user()->foto_perfil)
                            <img src="{{ str_starts_with(Auth::user()->foto_perfil, 'http') || str_starts_with(Auth::user()->foto_perfil, '/') ? asset(Auth::user()->foto_perfil) : asset('storage/' . Auth::user()->foto_perfil) }}" alt="Avatar">
                        @else
                            <i class="fas fa-user"></i>
                        @endif
                    </div>
                    <span>Você</span>
                </div>
                <div class="rank-score" style="color: var(--primary-color);">{{ number_format(Auth::user()->xp_atual ?? 0, 0, ',', '.') }} <span style="font-size: 0.8em; opacity: 0.7; font-weight: normal;">XP</span></div>
            </div>
            @endif
        </div>

    </div>
</div>

<style>
    .ranking-container {
        max-width: 900px;
        margin: 0 auto;
    }
    .ranking-header { 
        text-align: center; 
        margin-bottom: 60px; 
    }
    .ranking-header h1 { 
        font-family: 'Syne', sans-serif;
        font-size: 3.5rem; 
        font-weight: 800; 
        margin-bottom: 15px;
        letter-spacing: -1.5px; 
    }
    .ranking-header p { 
        color: var(--dash-text-secondary, rgba(255, 255, 255, 0.6)); 
        font-size: 1.15rem;
    }

    /* PODIUM */
    .podium-section {
        display: flex;
        justify-content: center;
        align-items: flex-end;
        height: 380px;
        gap: 20px;
        margin-bottom: 60px;
        position: relative;
    }
    .empty-podium {
        text-align: center;
        color: var(--dash-text-secondary);
        background: var(--dash-bg-card, rgba(255,255,255,0.02));
        border: 1px dashed var(--dash-border, rgba(255,255,255,0.1));
        border-radius: 20px;
        width: 100%;
        padding: 50px 20px;
    }

    .podium-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 140px;
        position: relative;
        z-index: 2;
    }
    
    .podium-name { 
        font-family: 'Syne', sans-serif;
        font-weight: 700; 
        font-size: 1.2rem;
        margin-bottom: 5px; 
        color: var(--dash-text-primary, #fff);
        text-shadow: 0 2px 10px rgba(0,0,0,0.5); 
        text-align: center;
    }
    
    .podium-score { 
        font-size: 0.95rem; 
        color: var(--dash-text-secondary, rgba(255,255,255,0.7));
        margin-bottom: 15px; 
        font-family: 'Outfit', sans-serif;
        font-weight: 600;
    }

    /* Blocks */
    .podium-block {
        width: 100%;
        border-radius: 16px 16px 0 0;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        display: flex;
        align-items: flex-start;
        justify-content: center;
        padding-top: 15px;
        font-family: 'Syne', sans-serif;
        font-size: 2.5rem;
        font-weight: 800;
        color: rgba(255,255,255,0.3);
        box-shadow: 0 -10px 30px rgba(0,0,0,0.2) inset;
    }

    /* 1st Place Gold */
    .podium-item.first { z-index: 3; }
    .podium-item.first .podium-block { 
        height: 180px; 
        background: linear-gradient(180deg, rgba(250, 204, 21, 0.15), var(--dash-bg-card, rgba(0,0,0,0.3))); 
        border: 1px solid rgba(250, 204, 21, 0.4); border-bottom: none;
        box-shadow: 0 -20px 40px rgba(250, 204, 21, 0.1) inset, 0 10px 30px rgba(250, 204, 21, 0.2);
    }
    /* 2nd Place Silver */
    .podium-item.second .podium-block { 
        height: 130px; 
        background: linear-gradient(180deg, rgba(148, 163, 184, 0.15), var(--dash-bg-card, rgba(0,0,0,0.3))); 
        border: 1px solid rgba(148, 163, 184, 0.3); border-bottom: none;
    }
    /* 3rd Place Bronze */
    .podium-item.third .podium-block { 
        height: 100px; 
        background: linear-gradient(180deg, rgba(217, 119, 6, 0.15), var(--dash-bg-card, rgba(0,0,0,0.3))); 
        border: 1px solid rgba(217, 119, 6, 0.3); border-bottom: none;
    }

    /* Avatars */
    .podium-avatar {
        width: 70px; height: 70px; border-radius: 50%;
        background: var(--dash-bg-base, #1e293b); 
        border: 3px solid var(--dash-border, #334155);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; color: var(--dash-text-secondary);
        margin-bottom: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        position: relative;
        overflow: hidden;
    }
    .podium-avatar img { width: 100%; height: 100%; object-fit: cover; }
    
    .first .podium-avatar { 
        width: 100px; height: 100px; 
        border: 4px solid #FACC15; 
        box-shadow: 0 0 30px rgba(250, 204, 21, 0.4);
    }
    .second .podium-avatar { border-color: #94A3B8; box-shadow: 0 0 20px rgba(148, 163, 184, 0.3); }
    .third .podium-avatar { border-color: #D97706; box-shadow: 0 0 20px rgba(217, 119, 6, 0.3); }

    /* Medals & Crown */
    .medal {
        position: absolute;
        top: -10px;
        right: -10px;
        width: 30px; height: 30px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem;
        font-weight: 800;
        z-index: 4;
        box-shadow: 0 4px 10px rgba(0,0,0,0.4);
    }
    .gold { background: linear-gradient(135deg, #FEF08A, #CA8A04); color: #713F12; width: 36px; height: 36px; font-size: 1.1rem; right: -5px; top: -5px; }
    .silver { background: linear-gradient(135deg, #F1F5F9, #94A3B8); color: #0F172A; }
    .bronze { background: linear-gradient(135deg, #FDE68A, #B45309); color: #451A03; }

    .crowning { 
        position: absolute; 
        top: -45px; 
        color: #FACC15; 
        font-size: 3rem; 
        animation: float 3s infinite ease-in-out; 
        filter: drop-shadow(0 0 15px rgba(250, 204, 21, 0.6));
    }

    @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-12px); } }

    /* LIST SECTION */
    .ranking-list {
        background: var(--dash-bg-card, rgba(255,255,255,0.02));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 24px;
        border: 1px solid var(--dash-border, rgba(255,255,255,0.05));
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
    }

    .list-header {
        display: flex;
        padding: 20px 30px;
        border-bottom: 1px solid var(--dash-border, rgba(255,255,255,0.08));
        color: var(--dash-text-secondary, rgba(255,255,255,0.5));
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .list-header div:nth-child(1) { width: 80px; }
    .list-header div:nth-child(2) { flex-grow: 1; }
    .list-header div:nth-child(3) { width: 120px; }

    .list-body {
        padding: 10px;
    }

    .ranking-row {
        display: flex;
        align-items: center;
        padding: 16px 20px;
        border-radius: 16px;
        margin-bottom: 5px;
        transition: all 0.2s ease;
    }
    
    .ranking-row:hover { 
        background: var(--dash-border, rgba(255,255,255,0.04)); 
        transform: translateX(4px);
    }
    
    /* Current user highlight within the list */
    .ranking-row.highlight { 
        background: rgba(124, 58, 237, 0.1); 
        border: 1px solid rgba(124, 58, 237, 0.3); 
        box-shadow: 0 0 20px rgba(124, 58, 237, 0.1);
    }
    
    .rank-pos { 
        width: 80px; 
        font-family: 'Syne', sans-serif;
        font-weight: 800; 
        font-size: 1.2rem; 
        color: var(--dash-text-secondary, #64748b); 
    }
    
    .rank-user { 
        flex-grow: 1; 
        display: flex; 
        align-items: center; 
        gap: 15px; 
        font-weight: 600; 
        color: var(--dash-text-primary, #fff);
        font-family: 'Outfit', sans-serif;
    }
    
    .rank-score { 
        width: 120px;
        text-align: right;
        font-weight: 800; 
        color: var(--dash-text-primary, #fff); 
        font-family: 'Syne', sans-serif;
        font-size: 1.1rem;
    }

    .rank-avatar.mini {
        width: 36px; height: 36px; border-radius: 50%;
        background: var(--dash-bg-base, #0f172a); 
        display: flex; align-items: center; justify-content: center;
        font-size: 0.9rem; color: var(--dash-text-secondary);
        overflow: hidden;
        border: 1px solid var(--dash-border);
    }
    .rank-avatar.mini img { width: 100%; height: 100%; object-fit: cover; }

    /* STICKY BOTTOM BAR */
    .current-user-sticky {
        display: flex;
        align-items: center;
        padding: 20px 30px;
        background: rgba(124, 58, 237, 0.15); /* Deep Violet */
        border-top: 1px solid rgba(124, 58, 237, 0.3);
        margin-top: 10px;
    }

    @media (max-width: 768px) {
        .ranking-header h1 { font-size: 2.5rem; }
        .podium-section { gap: 10px; height: 300px; }
        .podium-item { width: 100px; }
        .podium-item.first .podium-block { height: 140px; }
        .podium-item.second .podium-block { height: 100px; }
        .podium-item.third .podium-block { height: 80px; }
        .first .podium-avatar { width: 80px; height: 80px; }
        .podium-avatar { width: 55px; height: 55px; }
        .crowning { top: -110px; font-size: 2.2rem; }
        .list-header { display: none; }
        .ranking-list { border-radius: 16px; padding-bottom: 0; }
        .rank-pos { width: 50px; }
    }
</style>
@endsection
