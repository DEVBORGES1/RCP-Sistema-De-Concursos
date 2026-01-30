@extends('layouts.app')

@section('title', 'Arena de Duelo - Multiplayer')

@section('title', 'Arena de Duelo - Multiplayer')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
<!-- O arquivo game.css já é carregado no layout, mas mantemos por garantia ou removemos duplicidade -->
<!-- <link rel="stylesheet" href="{{ asset('assets/css/game.css') }}"> -->
<style>
    /* DESIGN SYSTEM: DUEL ARENA */
    :root {
        --primary: #8B5CF6; /* Violet */
        --secondary: #EC4899; /* Pink */
        --success: #10B981;
        --danger: #EF4444;
        --dark-bg: #0F172A;
        --card-bg: rgba(30, 41, 59, 0.7);
        --glass: rgba(255, 255, 255, 0.05);
        --border: rgba(255, 255, 255, 0.1);
    }

    body {
        background-color: var(--dark-bg);
        background-image: radial-gradient(circle at 50% 0%, #1e1b4b 0%, #0f172a 100%);
        font-family: 'Outfit', sans-serif;
        color: #fff;
        min-height: 100vh;
    }

    /* Container Principal */
    .arena-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 40px 20px;
        position: relative;
    }

    /* Steps Indicator */
    .duel-steps {
        display: flex;
        justify-content: center;
        gap: 40px;
        margin-bottom: 40px;
        position: relative;
    }
    .duel-steps::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 20%;
        right: 20%;
        height: 2px;
        background: rgba(255,255,255,0.1);
        z-index: 0;
    }
    .step {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        opacity: 0.5;
        transition: all 0.3s;
    }
    .step.active {
        opacity: 1;
    }
    .step-icon {
        width: 32px;
        height: 32px;
        background: var(--dark-bg);
        border: 2px solid var(--border);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.9rem;
    }
    .step.active .step-icon {
        border-color: var(--primary);
        background: var(--primary);
        box-shadow: 0 0 15px rgba(139, 92, 246, 0.5);
    }
    .step-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Header do Duelo (Placar) */
    .duel-header {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--border);
        border-radius: 20px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }

    .player-card {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .player-card.right {
        flex-direction: row-reverse;
        text-align: right;
    }

    .p-avatar {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        background: linear-gradient(135deg, #334155, #1e293b);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: var(--primary);
        border: 2px solid var(--border);
        box-shadow: 0 0 20px rgba(0,0,0,0.3);
        position: relative;
        overflow: hidden; /* For images */
    }
    .p-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .p-avatar.bot { color: var(--secondary); border-color: rgba(236, 72, 153, 0.3); }

    .p-info .p-name { font-weight: 700; font-size: 1.1rem; text-transform: uppercase; color: #cbd5e1; }
    .p-info .p-score { font-weight: 900; font-size: 1.8rem; line-height: 1; text-shadow: 0 0 15px rgba(255,255,255,0.2); }

    /* History Dots - Horizontal Fix */
    .history-dots { 
        display: flex; 
        flex-direction: row; /* Horizontal */
        gap: 6px; 
        margin-top: 8px; 
        width: fit-content;
    }
    .h-dot { width: 10px; height: 10px; border-radius: 50%; background: rgba(255,255,255,0.1); transition: all 0.3s; }
    .h-dot.correct { background: var(--success); box-shadow: 0 0 8px rgba(16, 185, 129, 0.6); }
    .h-dot.wrong { background: var(--danger); box-shadow: 0 0 8px rgba(239, 68, 68, 0.6); }
    .h-dot.active { background: #fff; animation: pulse 1s infinite; }
    
    .player-card.right .history-dots { margin-left: auto; margin-right: 0; }
    .player-card:not(.right) .history-dots { margin-right: auto; margin-left: 0; }

    .vs-divider {
        font-style: italic;
        font-weight: 900;
        font-size: 2.5rem;
        padding: 0 20px; /* Add spacing to avoid cut off */
        background: linear-gradient(to bottom, #fff, #94a3b8);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        opacity: 0.8;
        line-height: 1;
    }

    /* Lobby Card */
    .lobby-card {
        background: var(--card-bg);
        backdrop-filter: blur(12px);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 60px 40px;
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
        position: relative;
        overflow: hidden;
    }
    .lobby-card::before {
        content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(circle at 50% 50%, rgba(139, 92, 246, 0.1), transparent 70%);
        animation: rotate 20s linear infinite;
        pointer-events: none;
    }

    .radar-spinner {
        width: 80px; height: 80px;
        border-radius: 50%;
        border: 2px solid rgba(139, 92, 246, 0.3);
        border-top-color: var(--primary);
        margin: 0 auto 30px;
        animation: spin 1s linear infinite;
    }

    .btn-bot-hero {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        color: white;
        border: none;
        padding: 18px 40px;
        border-radius: 16px;
        font-weight: 700;
        font-size: 1.1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
        box-shadow: 0 10px 30px rgba(124, 58, 237, 0.4);
        margin-top: 30px;
        text-decoration: none;
    }
    .btn-bot-hero:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(124, 58, 237, 0.6);
    }
    
    /* Animations */
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes pulse { 0% { transform: scale(1); opacity: 0.8; } 50% { transform: scale(1.3); opacity: 1; } 100% { transform: scale(1); opacity: 0.8; } }
    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    @keyframes entry { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .animate-entry { animation: entry 0.6s cubic-bezier(0.2, 0.8, 0.2, 1) forwards; }

    /* Fixes for Question Card */
    .question-card {
        background: rgba(30, 41, 59, 0.8);
        border: 1px solid var(--border);
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }
    .answer-btn {
        background: rgba(255,255,255,0.05);
        border: 1px solid var(--border);
        color: #e2e8f0;
        padding: 20px;
        border-radius: 12px;
        text-align: left;
        font-size: 1.1rem;
        transition: all 0.2s;
    }
    .answer-btn:hover {
        background: rgba(139, 92, 246, 0.1);
        border-color: var(--primary);
        transform: translateX(10px);
    }

    /* Status Bar in Lobby */
    .search-status {
        background: rgba(0,0,0,0.3);
        padding: 5px 15px;
        border-radius: 50px;
        display: inline-block;
        font-size: 0.85rem;
        color: #94a3b8;
        margin-top: 10px;
    }

    /* QUESTION MASCOT - IMPROVED INTEGRATION */
    .question-mascot {
        position: absolute;
        top: -75px; /* Sitting on top of the card */
        right: 30px; /* Aligned with padding */
        width: 120px;
        height: auto;
        z-index: 20;
        filter: drop-shadow(0 8px 12px rgba(0,0,0,0.4));
        /* Removed float animation to make it feel more grounded/stable */
        transform: rotate(5deg); /* Slight tilt for personality */
        transition: transform 0.3s ease;
    }
    
    .question-mascot:hover {
        transform: rotate(0deg) scale(1.05);
    }

    .question-mascot img {
        width: 100%;
        height: auto;
        display: block;
    }
    
    /* Modify question card to ensure mascot is visible */
    .question-card {
        margin-top: 60px; /* More space at top so mascot doesn't hit header */
        overflow: visible !important;
        position: relative;
        /* create room for the mascot so text doesn't go under it if it's long? 
           The mascot is top-right, usually header area. Layout should be fine. */
    }

    /* Ensure content is above mascot if needed, but mascot should be on top of card bg */
    .p-avatar, .p-info {
        position: relative;
        z-index: 5;
    }
    .p-avatar, .p-info {
        position: relative;
        z-index: 5;
    }
    
    /* Ensure Header has overflow visible for mascots */
    .duel-header {
        overflow: visible; 
        position: relative;
    }

    /* POWER UPS */
    .power-ups-container {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin-bottom: 25px;
    }
    .btn-power {
        background: rgba(30, 41, 59, 0.6);
        border: 1px solid var(--border);
        color: #cbd5e1;
        padding: 8px 16px;
        border-radius: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        font-size: 0.9rem;
    }
    .btn-power:hover:not(:disabled) {
        background: var(--primary);
        color: white;
        transform: translateY(-2px);
    }
    .btn-power:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .power-count {
        background: rgba(0,0,0,0.3);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: bold;
    }

    /* FEEDBACK STYLES */
    .answer-btn.correct-choice {
        background: rgba(16, 185, 129, 0.2) !important;
        border-color: var(--success) !important;
        color: var(--success) !important;
        box-shadow: 0 0 15px rgba(16, 185, 129, 0.2);
    }
    .answer-btn.wrong-choice {
        background: rgba(239, 68, 68, 0.2) !important;
        border-color: var(--danger) !important;
        color: var(--danger) !important;
    }
    .answer-btn.disabled {
        pointer-events: none;
        opacity: 0.7;
    }

    /* ROULETTE INTERACTIVE */
    .roulette-center {
        cursor: pointer;
        transition: transform 0.1s;
        background: white;
        color: #0F172A;
        font-weight: 900;
        z-index: 50; /* Ensure clickable */
    }
    .roulette-center:active { transform: translate(-50%, -50%) scale(0.95); }
    .roulette-center.disabled { pointer-events: none; opacity: 0.8; }
</style>
@endpush

@section('content')
<div class="arena-container">
    
    <!-- Step Indicator -->
    <div class="duel-steps">
        <div class="step {{ $aguardando ? 'active' : '' }}">
            <div class="step-icon">1</div>
            <div class="step-label">Lobby</div>
        </div>
        <div class="step {{ !$aguardando && !$finalizada ? 'active' : '' }}">
            <div class="step-icon">2</div>
            <div class="step-label">Duelo</div>
        </div>
        <div class="step {{ $finalizada ? 'active' : '' }}">
            <div class="step-icon">3</div>
            <div class="step-label">Resultado</div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger animate-entry" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.3); color: #fca5a5; padding: 15px; border-radius: 12px; margin-bottom: 20px;">
            <ul style="margin:0; padding-left:20px;">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif

    @if ($aguardando)
        <!-- NEW LOBBY SCREEN -->
        <div class="lobby-card animate-entry">
            <div class="radar-spinner"></div>
            
            <h1 style="font-size: 2.5rem; font-weight: 900; margin-bottom: 10px; letter-spacing: -1px;">
                PROCURANDO<br><span style="color: var(--primary);">OPONENTE</span>
            </h1>
            
            <div class="search-status" id="statusText">
                <i class="fas fa-search"></i> Buscando jogadores online...
            </div>

            <div style="margin-top: 40px; display: flex; justify-content: center; gap: 40px; opacity: 0.6;">
                <div style="text-align: center;">
                    <div class="p-avatar" style="width: 60px; height: 60px; margin: 0 auto 10px; box-shadow: none;">
                        <img src="{{ asset('assets/imagens/avatars/avatar_player.png') }}" alt="Player">
                    </div>
                    <div style="font-weight: bold;">VOCÊ</div>
                </div>
                <div style="font-size: 2rem; font-weight: 900; align-self: center;">VS</div>
                <div style="text-align: center;">
                    <div class="p-avatar" style="width: 60px; height: 60px; margin: 0 auto 10px; border-style: dashed; opacity: 0.5; box-shadow: none;">?</div>
                    <div style="font-weight: bold;">?</div>
                </div>
            </div>

            <form method="POST" action="{{ route('jogo.bot') }}">
                @csrf
                <button type="submit" class="btn-bot-hero">
                    <i class="fas fa-robot" style="font-size: 1.4rem;"></i>
                    <div style="text-align: left;">
                        <div style="line-height: 1;">Jogar contra Bot</div>
                        <div style="font-size: 0.8rem; opacity: 0.8; font-weight: 400;">Treino instantâneo nível Hard</div>
                    </div>
                </button>
            </form>
            
            <button onclick="location.reload()" style="background: transparent; border: none; color: #64748b; margin-top: 20px; cursor: pointer; font-size: 0.9rem;">
                <i class="fas fa-sync"></i> Atualizar Status
            </button>
        </div>

        <script>
            setTimeout(() => location.reload(), 5000);
            
            // Logic to change text after 10s
            let start = sessionStorage.getItem('search_start_ts');
            if(!start || (Date.now() - start > 300000)) {
                start = Date.now();
                sessionStorage.setItem('search_start_ts', start);
            }
            if ((Date.now() - start) > 10000) {
                 const el = document.getElementById('statusText');
                 if(el) {
                    el.innerHTML = '<i class="fas fa-exclamation-circle"></i> Demorando? Tente o Bot!';
                    el.style.color = '#F59E0B';
                 }
            }
        </script>

    @elseif ($finalizada)
        <!-- RESULT SCREEN -->
        <div class="lobby-card animate-entry">
            <h1>FIM DE JOGO!</h1>
            <div style="display: flex; justify-content: center; margin: 40px 0; gap: 40px; align-items: flex-end;">
                <div style="text-align: center;">
                    <div class="p-avatar" style="width: 80px; height: 80px; margin: 0 auto 15px;">
                        <img src="{{ asset('assets/imagens/avatars/avatar_player.png') }}" alt="Player">
                    </div>
                    <div style="font-size: 2.5rem; font-weight: 900; color: var(--primary);">{{ $meusPontos }}</div>
                    <div style="font-weight: bold; color: #94a3b8;">SEUS PONTOS</div>
                </div>
                <div style="text-align: center;">
                    <div class="p-avatar bot" style="width: 80px; height: 80px; margin: 0 auto 15px;">
                        <img src="{{ asset('assets/imagens/avatars/avatar_bot.png') }}" alt="Bot">
                    </div>
                    <div style="font-size: 2.5rem; font-weight: 900; color: var(--secondary);">{{ $oponentePontos }}</div>
                    <div style="font-weight: bold; color: #94a3b8;">OPONENTE</div>
                </div>
            </div>
            
            @if($meusPontos > $oponentePontos)
                <h2 style="color: var(--success); margin-bottom: 30px;">VITÓRIA! 🏆</h2>
            @elseif($meusPontos < $oponentePontos)
                <h2 style="color: var(--danger); margin-bottom: 30px;">DERROTA... 💀</h2>
            @else
                <h2 style="color: #F59E0B; margin-bottom: 30px;">EMPATE! 🤝</h2>
            @endif

            <a href="{{ route('jogo.index') }}" class="btn-bot-hero" onclick="sessionStorage.removeItem('search_start_ts')">
                Jogar Novamente
            </a>
        </div>

    @elseif ($rodada)
        <!-- DUEL HEADER -->
        <div class="duel-header animate-entry">
            
            <!-- Player 1 (You) -->
            <div class="player-card">
                <div class="p-avatar">
                   @if(Auth::user()->foto_perfil)
                        <img src="{{ asset(Auth::user()->foto_perfil) }}" alt="Player">
                   @else
                        <img src="{{ asset('assets/imagens/avatars/avatar_player.png') }}" alt="Player">
                   @endif
                </div>
                <div class="p-info">
                    <div class="p-name">Você</div>
                    <div class="p-score">{{ $meusPontos }}</div>
                    <div class="history-dots">
                        @php
                            $histUser = $historico[$meuId] ?? []; // Safe access
                        @endphp
                        @for ($i = 1; $i <= 10; $i++)
                            @php
                                $status = '';
                                if (isset($histUser[$i])) {
                                    $status = $histUser[$i] ? 'correct' : 'wrong';
                                } elseif ($rodada && $rodada->numero_rodada == $i) {
                                    $status = 'active'; 
                                }
                            @endphp
                            <div class="h-dot {{ $status }}"></div>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="vs-divider">VS</div>

            <!-- Player 2 (Opponent) -->
            <div class="player-card right">
                <div class="p-avatar bot">
                    <!-- TODO: Logic to show real opponent avatar if human -->
                    <img src="{{ asset('assets/imagens/avatars/avatar_bot.png') }}" alt="Bot">
                </div>
                <div class="p-info">
                    <div class="p-name">Oponente</div>
                    <div class="p-score">{{ $oponentePontos }}</div>
                    <div class="history-dots">
                        @php
                            $histOpp = isset($oponenteId) ? ($historico[$oponenteId] ?? []) : [];
                        @endphp
                        @for ($i = 1; $i <= 10; $i++)
                            @php
                                $status = '';
                                if (isset($histOpp[$i])) {
                                    $status = $histOpp[$i] ? 'correct' : 'wrong';
                                } elseif ($rodada && $rodada->numero_rodada == $i) {
                                    $status = 'active';
                                }
                            @endphp
                            <div class="h-dot {{ $status }}"></div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <!-- ROULETTE & GAME AREA -->
        @php
            $showRoulette = !session('roulette_round_' . $rodada->id);
            if ($showRoulette) session(['roulette_round_' . $rodada->id => true]);
        @endphp

        <!-- Roulette Overlay (Keeping Logic but enhancing style) -->
        <div id="rouletteOverlay" class="roulette-overlay" style="{{ $showRoulette ? 'display:flex;' : 'display:none;' }}">
             <div class="roulette-container">
                    <div class="roulette-arrow"></div>
                    <!-- Wheel CSS is in head -->
                    <div class="roulette-wheel" id="rouletteWheel">
                        <!-- Icons - 12 Slices -->
                        <div class="roulette-icon icon-1"><i class="fas fa-book"></i></div>        <!-- Constitucional -->
                        <div class="roulette-icon icon-2"><i class="fas fa-desktop"></i></div>     <!-- Informática -->
                        <div class="roulette-icon icon-3"><i class="fas fa-pen-nib"></i></div>     <!-- Português -->
                        <div class="roulette-icon icon-4"><i class="fas fa-calculator"></i></div>  <!-- Matemática -->
                        <div class="roulette-icon icon-5"><i class="fas fa-landmark"></i></div>    <!-- História -->
                        <div class="roulette-icon icon-6"><i class="fas fa-puzzle-piece"></i></div><!-- Raciocínio -->
                        <div class="roulette-icon icon-7"><i class="fas fa-gavel"></i></div>       <!-- Administrativo -->
                        <div class="roulette-icon icon-8"><i class="fas fa-leaf"></i></div>        <!-- Biologia -->
                        <div class="roulette-icon icon-9"><i class="fas fa-globe-americas"></i></div><!-- Geografia -->
                        <div class="roulette-icon icon-10"><i class="fas fa-language"></i></div>   <!-- Inglês -->
                        <div class="roulette-icon icon-11"><i class="fas fa-lightbulb"></i></div>  <!-- Conhecimentos Gerais -->
                        <div class="roulette-icon icon-12"><i class="fas fa-newspaper"></i></div>  <!-- Atualidades -->
                    </div>
                    <div class="roulette-center" id="spinButton" style="font-size: 0.8rem;">GIRAR</div>
            </div>
            <h2 id="rouletteMsg" style="font-weight: 900; font-size: 2rem; margin-top: 20px;">
                <span style="opacity:0.7; font-size:1rem; font-weight:400; display:block; margin-bottom:5px;">Categoria da Rodada:</span>
                SORTEANDO...
            </h2>
        </div>

        <!-- QUESTION CARD -->
        <div class="question-card animate-entry" id="questionCard" style="{{ $showRoulette ? 'opacity:0;' : '' }}">
            
            <!-- Category Mascot (Server Side Logic) -->
            @php
                $catName = $rodada->pergunta->categoria->nome ?? 'Geral';
                $mascotFile = 'mascot_geral.png'; // Default

                // Map logic
                if (str_contains($catName, 'Constitucional')) $mascotFile = 'mascot_constitucional.png';
                elseif (str_contains($catName, 'Informática')) $mascotFile = 'mascot_informatica.png';
                elseif (str_contains($catName, 'Português')) $mascotFile = 'mascot_portugues.png';
                elseif (str_contains($catName, 'Matemática')) $mascotFile = 'mascot_matematica.png';
                elseif (str_contains($catName, 'História')) $mascotFile = 'mascot_historia.png';
                elseif (str_contains($catName, 'Lógico')) $mascotFile = 'mascot_logica.png'; // Raciocínio Lógico
                elseif (str_contains($catName, 'Administrativo')) $mascotFile = 'mascot_administrativo.png';
                elseif (str_contains($catName, 'Biologia')) $mascotFile = 'mascot_biologia.png';
                elseif (str_contains($catName, 'Geografia')) $mascotFile = 'mascot_geografia.png';
                elseif (str_contains($catName, 'Inglês')) $mascotFile = 'mascot_ingles.png';
                elseif (str_contains($catName, 'Atualidades')) $mascotFile = 'mascot_atualidades.png';
                elseif (str_contains($catName, 'Gerais')) $mascotFile = 'mascot_geral.png';
            @endphp

            <div class="question-mascot">
                <img src="{{ asset('assets/imagens/mascotes/' . $mascotFile) }}" alt="Mascote {{ $catName }}">
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <div style="background: rgba(255,255,255,0.1); padding: 5px 15px; border-radius: 50px; font-size: 0.9rem;">
                    Questão <span style="font-weight: bold; color: var(--primary);">{{ $rodada->numero_rodada }}</span>/10
                </div>
                <div class="category-badge" style="font-weight: bold; color: #94a3b8; display:flex; align-items:center; gap:8px;">
                    {{ $catName }} <i class="{{ $rodada->pergunta->categoria->icone }}"></i>
                </div>
            </div>

            <!-- POWER UPS -->
            <div class="power-ups-container">
                <button type="button" class="btn-power" id="btnSkip" title="Pular pergunta (0 pts)">
                    <i class="fas fa-forward"></i> Pular
                    <span class="power-count">1</span>
                </button>
                <button type="button" class="btn-power" id="btnFifty" title="Eliminar 2 erradas">
                    <i class="fas fa-adjust"></i> 50/50
                    <span class="power-count">1</span>
                </button>
                <button type="button" class="btn-power" id="btnFreeze" title="Congelar tempo (5s)">
                    <i class="fas fa-snowflake"></i> Congelar
                    <span class="power-count">1</span>
                </button>
            </div>

            <!-- Timer -->
            <div class="round-timer">
                <div class="timer-bar" id="timerBar" style="width: 100%;"></div>
                <span id="timerText">{{ $rodada->segundos_restantes }}s</span>
            </div>

            <h2 style="font-weight: 700; font-size: 1.5rem; text-align: center; margin: 30px 0; line-height: 1.4;">
                {{ $rodada->pergunta->pergunta }}
            </h2>

            <!-- Answers -->
            <form method="POST" action="{{ route('jogo.responder') }}" id="gameForm" style="display: flex; flex-direction: column; gap: 15px;">
                @csrf
                <input type="hidden" name="rodada_id" value="{{ $rodada->id }}">
                @foreach ($rodada->pergunta->respostas as $opcao)
                    <button type="submit" 
                            name="resposta_id" 
                            value="{{ $opcao->id }}" 
                            data-correct="{{ $opcao->correta }}"
                            class="answer-btn">
                        {{ $opcao->texto }}
                    </button>
                @endforeach
            </form>

            <div style="margin-top: 30px; display: flex; justify-content: center; gap: 20px;">
                 <button type="button" onclick="confirm('Desistir?') && document.getElementById('formDesist').submit()" style="background: transparent; border: none; color: #64748b; cursor: pointer;">
                    <i class="fas fa-flag"></i> Desistir
                 </button>
            </div>
            <form id="formDesist" action="{{ route('jogo.desistir') }}" method="POST" style="display:none;">@csrf</form>
        </div>
        
        <!-- SCRIPT DO JOGO (Timer & Roleta & Lógica) -->
        <script>
            // --- VARIÁVEIS GLOBAIS ---
            const categoryName = "{{ $rodada->pergunta->categoria->nome }}";
            const showRoulette = {{ $showRoulette ? 'true' : 'false' }};
            const currentRoundId = "{{ $rodada->id }}";
            let timeRemaining = {{ $rodada->segundos_restantes }};
            const totalTime = 30;
            let timerInterval;
            let isFreezeActive = false;

            // Mapas de ângulos (12 slices of 30deg. Center of slice N = (N-1)*30 + 15)
            // Note: rotation = angle. Wheel stops at specific rotation.
            // If arrow is at TOP (0 deg), then to show Slice 1 (0-30), we need rotation 345? NO.
            // Wheel rotates. Arrow is fixed.
            // Assume Arrow at Top (0).
            // Slice 1 is 0-30. Center 15. To put 15 at Top (0), we rotate -15 (or 345).
            // Let's use the logic: Target = 360 - CenterAngle.
            const catMap = { 
                'Direito Constitucional': 345, // Slice 1 (15) -> 360-15 = 345
                'Informática': 315,            // Slice 2 (45) -> 315
                'Português': 285,              // Slice 3 (75) -> 285
                'Matemática': 255,             // Slice 4 (105) -> 255
                'História': 225,               // Slice 5 (135) -> 225
                'Raciocínio Lógico': 195,      // Slice 6 (165) -> 195
                'Direito Administrativo': 165, // Slice 7 (195) -> 165
                'Biologia': 135,               // Slice 8 (225) -> 135
                'Geografia': 105,              // Slice 9 (255) -> 105
                'Inglês': 75,                  // Slice 10 (285) -> 75
                'Conhecimentos Gerais': 45,    // Slice 11 (315) -> 45
                'Atualidades': 15,             // Slice 12 (345) -> 15
                'Cultura': 45                  // Alias to Conhecimentos Gerais
            };
            
            // --- SOUNDS ---
            const sounds = {
                spin: new Audio("{{ asset('assets/sounds/spin.mp3') }}"),
                correct: new Audio("{{ asset('assets/sounds/correct.mp3') }}"),
                wrong: new Audio("{{ asset('assets/sounds/wrong.mp3') }}"),
            };
            function playSound(t) { 
                if(sounds[t]) { 
                    sounds[t].volume=0.3; 
                    sounds[t].currentTime=0; 
                    sounds[t].play().catch(e=>{ console.log('Audio Blocked', e) }); 
                } 
            }

            // --- LÓGICA DA ROLETA (MANUAL) ---
            if(showRoulette) {
                document.addEventListener('DOMContentLoaded', () => {
                    const wheel = document.getElementById('rouletteWheel');
                    const msg = document.getElementById('rouletteMsg');
                    const overlay = document.getElementById('rouletteOverlay');
                    const card = document.getElementById('questionCard');
                    const spinBtn = document.getElementById('spinButton');
                    
                    let rotation = 0;
                    let speed = 0;
                    let spinState = 'idle'; 
                    let animationId = null;

                    // Calculates shortest distance to target angle on a circle
                    function getDistanceToTarget(currentAngle, targetAngle) {
                        let diff = targetAngle - (currentAngle % 360);
                        if (diff < 0) diff += 360; // Ensure positive (clockwise distance)
                        return diff;
                    }
                    
                    function spinLoop() {
                        // 1. SPINNING PHASE: Accelerate to max speed (Faster start)
                        if (spinState === 'spinning') {
                            if(speed < 40) speed += 2; // Accelerate much faster
                            rotation += speed;
                        } 
                        // 2. STOPPING PHASE
                        else if (spinState === 'stopping') {
                            const target = catMap[categoryName] || 22.5;
                            const dist = getDistanceToTarget(rotation, target);

                            // Deceleration physics (Smoother long tail)
                            speed *= 0.985; 

                            // "Magnetic" Logic:
                            // If we are getting slow but are far away, keep minimum speed to reach target
                            if (speed < 5) {
                                // We are in the "approach" phase
                                if (dist > 10) {
                                    // Keep spinning until we get close
                                    speed = Math.max(speed, 2); 
                                } else {
                                    // We are close (within 10 deg), slow down based on distance (Linear easing)
                                    // This prevents overshooting
                                    speed = Math.max(0.5, dist * 0.1);
                                }
                            }

                            // STOP CONDITION
                            // If extremely close and extremely slow
                            if (dist < 1 && speed < 1) {
                                rotation = Math.floor(rotation / 360) * 360 + target; // Snap exactly
                                wheel.style.transform = `rotate(${rotation}deg)`;
                                cancelAnimationFrame(animationId);
                                finalizeRoulette();
                                return;
                            }

                            rotation += speed;
                        }

                        wheel.style.transform = `rotate(${rotation}deg)`;
                        animationId = requestAnimationFrame(spinLoop);
                    }

                    // Click Behavior
                    spinBtn.addEventListener('click', () => {
                        if(spinState === 'idle') {
                            spinState = 'spinning';
                            spinBtn.innerText = "PARAR";
                            spinBtn.style.color = "#EC4899";
                            spinBtn.style.borderColor = "#EC4899";
                            playSound('spin');
                            spinLoop();
                        } else if (spinState === 'spinning') {
                            spinState = 'stopping';
                            spinBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>'; // Show loading/waiting icon
                            spinBtn.classList.add('disabled'); // Prevent double click
                        }
                    });

                    function finalizeRoulette() {
                        msg.innerHTML = `<span style='color:var(--success)'>${categoryName}!</span>`;
                        playSound('correct'); 
                        
                        setTimeout(() => {
                            overlay.style.transition = "opacity 0.5s ease";
                            overlay.style.opacity = '0';
                            overlay.style.pointerEvents = 'none';
                            
                            setTimeout(() => {
                                overlay.remove();
                                card.style.opacity = '1';
                                startTimer();
                            }, 500);
                        }, 1000);
                    }
                });
            } else {
                document.addEventListener('DOMContentLoaded', () => startTimer());
            }

            // --- TIMER ---
            function startTimer() {
                if(timerInterval) clearInterval(timerInterval);
                timerInterval = setInterval(() => {
                    if(!isFreezeActive) {
                        timeRemaining--;
                        
                        // Sync UI
                        const min = Math.max(0, parseInt(timeRemaining));
                        const elTx = document.getElementById('timerText');
                        if(elTx) elTx.innerText = min + 's';
                        
                        const elBar = document.getElementById('timerBar');
                        if(elBar) {
                            const pct = (timeRemaining / totalTime) * 100;
                            elBar.style.width = pct + '%';
                            if(pct > 66) elBar.style.background = '#10B981';
                            else if(pct > 33) elBar.style.background = '#F59E0B';
                            else elBar.style.background = '#EF4444';
                        }
    
                        if(timeRemaining <= 0) {
                            clearInterval(timerInterval);
                            // Auto submit timeout
                             window.location.reload(); 
                        }
                    }
                }, 1000);
            }

            // --- POWER UPS LOGIC ---
            document.addEventListener('DOMContentLoaded', () => {
                const btnSkip = document.getElementById('btnSkip');
                const btnFifty = document.getElementById('btnFifty');
                const btnFreeze = document.getElementById('btnFreeze');
                const form = document.getElementById('gameForm');

                if(btnSkip) {
                    btnSkip.addEventListener('click', () => {
                        if(confirm('Pular esta pergunta? (Você não ganhará pontos)')) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'resposta_id';
                            input.value = 'SKIP';
                            form.appendChild(input);
                            form.submit();
                        }
                    });
                }

                if(btnFifty) {
                    btnFifty.addEventListener('click', function() {
                        this.disabled = true;
                        this.querySelector('.power-count').innerText = "0";
                        
                        // Select wrong answers
                        const buttons = Array.from(document.querySelectorAll('.answer-btn'));
                        // Assuming backend doesn't send 'is_correct' in HTML for security, 
                        // BUT typically in this app we might rely on ID. 
                        // Wait, without 'data-correct' how do we know?
                        // Checking HTML for answers
                        // We need to know which are wrong.
                        // Ideally we should inject data-correct attribute in the loop. 
                        // FIX: We need to modify the loop in PHP above. 
                        // For now let's assume we will adding logic to PHP loop or JS based guess? 
                        // No, let's use the 'data-correct' attribute which I will add in the PHP view loop below.
                        
                        const incorrects = buttons.filter(b => b.dataset.correct != '1');
                        // Shuffle and hide 2
                        incorrects.sort(() => 0.5 - Math.random());
                        incorrects.slice(0, 2).forEach(btn => {
                            btn.style.opacity = '0.2';
                            btn.style.pointerEvents = 'none';
                        });
                    });
                }

                if(btnFreeze) {
                    btnFreeze.addEventListener('click', function() {
                        this.disabled = true;
                        this.querySelector('.power-count').innerText = "0";
                        isFreezeActive = true;
                        
                        const bar = document.getElementById('timerBar');
                        if(bar) bar.style.backgroundColor = '#38BDF8'; // Blue freeze
                        
                        setTimeout(() => {
                            isFreezeActive = false;
                        }, 5000);
                    });
                }
            });

            // --- GAMEPLAY INTERACTION (Feedback) ---
            document.querySelectorAll('.answer-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Stop everything
                    clearInterval(timerInterval);
                    document.querySelectorAll('.answer-btn').forEach(b => b.classList.add('disabled'));
                    
                    const isCorrect = this.dataset.correct == '1';
                    
                    if(isCorrect) {
                        this.classList.add('correct-choice');
                        playSound('correct');
                    } else {
                        this.classList.add('wrong-choice');
                        playSound('wrong');
                        // Show correct one
                        const correctBtn = document.querySelector('.answer-btn[data-correct="1"]');
                        if(correctBtn) correctBtn.classList.add('correct-choice');
                    }

                    // Delay submit
                    setTimeout(() => {
                        const form = document.getElementById('gameForm');
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'resposta_id';
                        input.value = this.value;
                        form.appendChild(input);
                        form.submit();
                    }, 1500);
                });
            });

            // --- POLLING STATUS (Optimized) ---
            // Only reload if status CHANGED or NEW ROUND
            setInterval(() => {
                 fetch("{{ route('jogo.status') }}")
                    .then(r => r.json())
                    .then(data => {
                        if (data.status === 'finalizada') window.location.reload();
                        
                        // If we are waiting for a new round (current expired or answered), reload
                        // But if we are playing the SAME round, don't reload unless massive sync error
                        
                        if (data.status === 'rodada_ativa') {
                            if (data.rodada_id != currentRoundId) {
                                window.location.reload(); // New round detected
                            }
                            // Time sync (only if huge drift)
                            if (Math.abs(timeRemaining - data.segundos_restantes) > 5) {
                                timeRemaining = data.segundos_restantes;
                            }
                        }
                    })
                    .catch(e => console.log('Polling error', e));
            }, 3000);

        </script>

    @endif

</div>
@endsection
