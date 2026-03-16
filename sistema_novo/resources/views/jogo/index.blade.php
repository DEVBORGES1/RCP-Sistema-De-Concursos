@extends('layouts.app')

@section('title', 'Arena de Duelo - Multiplayer')

@section('title', 'Arena de Duelo - Multiplayer')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;500;700;900&display=swap" rel="stylesheet">
<style>

    /* ===== ARENA DE DUELO — PREMIUM DESIGN SYSTEM ===== */
    :root {
        --p1: #7C3AED;        /* Player 1 — Violet */
        --p2: #EC4899;        /* Player 2 — Pink */
        --success: #10B981;
        --danger: #EF4444;
        --warn: #F59E0B;
        --base: #07051A;      /* Deep cosmic background */
        --card: rgba(255,255,255,0.04);
        --card-border: rgba(255,255,255,0.08);
        --text: #E2E8F0;
        --muted: #94A3B8;
        /* compat aliases */
        --primary: #7C3AED;
        --secondary: #EC4899;
        --dark-bg: #07051A;
        --card-bg: rgba(255,255,255,0.04);
        --glass: rgba(255,255,255,0.04);
        --border: rgba(255,255,255,0.08);
    }

    body {
        background-color: var(--base);
        background-image:
            radial-gradient(ellipse 80% 50% at 15% 95%, rgba(124, 58, 237, 0.22) 0%, transparent 60%),
            radial-gradient(ellipse 70% 40% at 85% 5%, rgba(236, 72, 153, 0.18) 0%, transparent 55%);
        background-attachment: fixed;
        font-family: 'Outfit', sans-serif;
        color: var(--text);
        min-height: 100vh;
        overflow-x: hidden;
    }
    body::before {
        content: '';
        position: fixed; inset: 0;
        background-image:
            linear-gradient(rgba(255,255,255,0.018) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255,255,255,0.018) 1px, transparent 1px);
        background-size: 60px 60px;
        pointer-events: none; z-index: 0;
    }

    .arena-container {
        max-width: 900px; margin: 0 auto;
        padding: 40px 20px 80px;
        position: relative; z-index: 1;
    }

    /* Steps Indicator */
    .duel-steps {
        display: flex; justify-content: center; gap: 40px;
        margin-bottom: 50px; position: relative;
    }
    .duel-steps::before {
        content: ''; position: absolute; top: 15px;
        left: 18%; right: 18%; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.15), transparent);
        z-index: 0;
    }
    .step {
        position: relative; z-index: 1;
        display: flex; flex-direction: column; align-items: center; gap: 8px;
        opacity: 0.35; transition: all 0.4s ease;
    }
    .step.active { opacity: 1; }
    .step-icon {
        width: 34px; height: 34px;
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1);
        border-radius: 50%; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.85rem; color: var(--muted); transition: all 0.4s;
    }
    .step.active .step-icon {
        background: var(--p1); border-color: rgba(124,58,237,0.8); color: white;
        box-shadow: 0 0 20px rgba(124,58,237,0.5);
    }
    .step-label { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); font-weight: 600; }
    .step.active .step-label { color: var(--text); }

    /* Duel Header */
    .duel-header {
        background: var(--card);
        backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px);
        border: 1px solid var(--card-border);
        border-radius: 24px; padding: 24px 30px;
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 40px; overflow: visible; position: relative;
        box-shadow: 0 16px 48px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.05);
    }
    .duel-header::before {
        content: ''; position: absolute; top: 0; left: 10%; right: 10%; height: 1px;
        background: linear-gradient(90deg, transparent, rgba(124,58,237,0.5), rgba(236,72,153,0.5), transparent);
    }

    .player-card { display: flex; align-items: center; gap: 16px; }
    .player-card.right { flex-direction: row-reverse; text-align: right; }

    .p-avatar {
        width: 72px; height: 72px; border-radius: 50%;
        background: linear-gradient(135deg, #1e1b4b, #0f172a);
        display: flex; align-items: center; justify-content: center;
        font-size: 2rem; color: var(--p1);
        border: 2px solid rgba(124,58,237,0.4);
        box-shadow: 0 0 0 4px rgba(124,58,237,0.1), 0 10px 30px rgba(0,0,0,0.4);
        position: relative; overflow: hidden; transition: box-shadow 0.3s;
    }
    .p-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .p-avatar.bot {
        color: var(--p2); border-color: rgba(236,72,153,0.4);
        box-shadow: 0 0 0 4px rgba(236,72,153,0.1), 0 10px 30px rgba(0,0,0,0.4);
    }

    .p-info .p-name { font-weight: 700; font-size: 0.95rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--muted); }
    .p-info .p-score {
        font-weight: 900; font-size: 2.2rem; line-height: 1;
        background: linear-gradient(135deg, #fff, rgba(255,255,255,0.6));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }

    /* History Dots */
    .history-dots { display: flex; flex-direction: row; gap: 5px; margin-top: 8px; width: fit-content; }
    .h-dot {
        width: 9px; height: 9px; border-radius: 50%;
        background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.06);
        transition: all 0.4s;
    }
    .h-dot.correct { background: var(--success); box-shadow: 0 0 8px rgba(16,185,129,0.7); border-color: var(--success); }
    .h-dot.wrong { background: var(--danger); box-shadow: 0 0 8px rgba(239,68,68,0.7); border-color: var(--danger); }
    .h-dot.active { background: white; box-shadow: 0 0 10px rgba(255,255,255,0.8); animation: pulse-dot 1.2s infinite; }
    @keyframes pulse-dot { 0%,100% { transform: scale(1); opacity: 0.8; } 50% { transform: scale(1.5); opacity: 1; } }
    .player-card.right .history-dots { margin-left: auto; margin-right: 0; }
    .player-card:not(.right) .history-dots { margin-right: auto; margin-left: 0; }

    /* VS divider + live badge */
    .vs-center { display: flex; flex-direction: column; align-items: center; gap: 6px; }
    .vs-divider {
        font-style: italic; font-weight: 900; font-size: 2.2rem;
        background: linear-gradient(135deg, #fff 30%, #94a3b8);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; line-height: 1;
    }
    .live-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.3);
        padding: 3px 10px; border-radius: 50px;
        font-size: 0.7rem; font-weight: 700; letter-spacing: 1.5px;
        color: #FCA5A5; text-transform: uppercase;
    }
    .live-dot { width: 6px; height: 6px; border-radius: 50%; background: var(--danger); animation: live-pulse 1.5s infinite; }
    @keyframes live-pulse { 0%,100% { box-shadow: 0 0 0 0 rgba(239,68,68,0.6); } 50% { box-shadow: 0 0 0 5px rgba(239,68,68,0); } }

    /* Lobby Card */
    .lobby-card {
        background: var(--card);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--card-border); border-radius: 28px;
        padding: 60px 50px; text-align: center;
        max-width: 580px; margin: 0 auto;
        position: relative; overflow: hidden;
        box-shadow: 0 30px 80px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.06);
    }
    .lobby-card::before {
        content: ''; position: absolute; top: -80%; left: -50%; width: 200%; height: 200%;
        background: radial-gradient(ellipse at 50% 50%, rgba(124,58,237,0.1), transparent 65%);
        animation: rotate-bg 25s linear infinite; pointer-events: none;
    }
    @keyframes rotate-bg { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .lobby-title {
        font-size: clamp(1.4rem, 5vw, 2.4rem); font-weight: 900; line-height: 1.1;
        margin-bottom: 8px; letter-spacing: -1px; text-transform: uppercase;
        word-wrap: break-word; overflow-wrap: break-word;
    }
    .lobby-title span {
        background: linear-gradient(135deg, var(--p1), var(--p2));
        -webkit-background-clip: text; -webkit-text-fill-color: transparent;
    }
    .lobby-subtitle { color: var(--muted); font-size: 1rem; margin-bottom: 40px; }

    .radar-ring {
        width: 90px; height: 90px; border-radius: 50%;
        border: 2px solid rgba(124,58,237,0.15); border-top-color: var(--p1);
        margin: 0 auto 40px; animation: spin 1.2s linear infinite;
        box-shadow: 0 0 30px rgba(124,58,237,0.2);
    }

    /* Matchup preview */
    .matchup-preview { display: flex; justify-content: center; align-items: center; gap: 30px; margin-bottom: 40px; }
    .matchup-slot { display: flex; flex-direction: column; align-items: center; gap: 10px; }
    .matchup-avatar {
        width: 64px; height: 64px; border-radius: 50%;
        background: linear-gradient(135deg, #1e1b4b, #0f172a);
        display: flex; align-items: center; justify-content: center;
        font-size: 1.8rem; color: var(--p1);
        border: 2px solid rgba(124,58,237,0.4); box-shadow: 0 0 20px rgba(124,58,237,0.2);
        overflow: hidden;
    }
    .matchup-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .matchup-avatar.dashed {
        border-style: dashed; border-color: rgba(255,255,255,0.2);
        color: rgba(255,255,255,0.3); box-shadow: none;
        animation: pulse-avatar 2s infinite;
    }
    @keyframes pulse-avatar { 0%,100% { opacity: 0.4; } 50% { opacity: 1; } }
    .matchup-name { font-size: 0.78rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--muted); }
    .matchup-vs { font-size: 1.8rem; font-weight: 900; font-style: italic; background: linear-gradient(135deg,#fff,#94a3b8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

    .search-status {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(0,0,0,0.3); padding: 6px 16px; border-radius: 50px;
        font-size: 0.85rem; color: var(--muted); border: 1px solid rgba(255,255,255,0.06);
    }
    .s-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--p1); animation: live-pulse 1.8s infinite; }

    .btn-bot-hero {
        background: linear-gradient(135deg, #4f46e5, #7c3aed 60%, #9333ea);
        color: white; border: none; padding: 18px 30px;
        border-radius: 16px; font-weight: 700; font-size: 1.05rem;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 14px;
        width: 100%; margin-top: 18px;
        transition: transform 0.25s, box-shadow 0.25s;
        box-shadow: 0 12px 32px rgba(124,58,237,0.4);
        text-decoration: none; font-family: 'Outfit', sans-serif;
    }
    .btn-bot-hero:hover { transform: translateY(-4px); box-shadow: 0 20px 50px rgba(124,58,237,0.6); color: white; }
    .btn-secondary-action {
        background: transparent; border: none; color: rgba(148,163,184,0.6);
        margin-top: 16px; cursor: pointer; font-size: 0.88rem; font-family: 'Outfit', sans-serif;
        display: flex; align-items: center; justify-content: center; gap: 6px; width: 100%;
        transition: color 0.2s;
    }
    .btn-secondary-action:hover { color: var(--text); }

    /* Animations */
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes pulse { 0%,100% { transform: scale(1); opacity: 0.8; } 50% { transform: scale(1.3); opacity: 1; } }
    @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    @keyframes entry { from { opacity: 0; transform: translateY(22px); } to { opacity: 1; transform: translateY(0); } }

    .animate-entry { animation: entry 0.5s cubic-bezier(0.2,0.8,0.2,1) forwards; }

    /* Question Card */
    .question-card {
        background: var(--card);
        backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--card-border); border-radius: 24px; padding: 40px;
        box-shadow: 0 24px 64px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.05);
        margin-top: 60px; overflow: visible !important; position: relative;
    }

    .answer-btn {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.09); color: var(--text);
        padding: 18px 22px; border-radius: 14px; text-align: left;
        font-size: 1.05rem; font-family: 'Outfit', sans-serif;
        cursor: pointer; width: 100%; transition: all 0.2s cubic-bezier(0.4,0,0.2,1);
        display: flex; align-items: center; gap: 12px;
        position: relative; overflow: hidden;
    }
    .answer-btn::before {
        content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
        border-radius: 3px 0 0 3px; background: var(--p1); opacity: 0; transition: opacity 0.2s;
    }
    .answer-btn:hover { background: rgba(124,58,237,0.1); border-color: rgba(124,58,237,0.4); transform: translateX(6px); }
    .answer-btn:hover::before { opacity: 1; }

    .search-status {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(0,0,0,0.3); padding: 5px 15px;
        border-radius: 50px; font-size: 0.85rem; color: var(--muted);
        border: 1px solid rgba(255,255,255,0.06); margin-top: 10px;
    }

    /* Mascot */
    .question-mascot {
        position: absolute; top: -80px; right: 28px;
        width: 110px; height: auto; z-index: 20;
        filter: drop-shadow(0 10px 16px rgba(0,0,0,0.5));
        transform: rotate(4deg); transition: transform 0.3s ease;
    }
    .question-mascot:hover { transform: rotate(0deg) scale(1.06); }
    .question-mascot img { width: 100%; height: auto; display: block; }
    .p-avatar, .p-info { position: relative; z-index: 5; }
    .duel-header { overflow: visible; position: relative; }

    /* Power Ups */
    .power-ups-container { display: flex; justify-content: center; gap: 12px; margin-bottom: 20px; flex-wrap: wrap; }
    .btn-power {
        background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.09);
        color: var(--muted); padding: 8px 16px; border-radius: 50px;
        cursor: pointer; display: flex; align-items: center; gap: 8px;
        transition: all 0.2s; font-size: 0.85rem; font-family: 'Outfit', sans-serif;
    }
    .btn-power i { color: #FCD34D; }
    .btn-power:hover:not(:disabled) {
        background: rgba(124,58,237,0.15); border-color: rgba(124,58,237,0.4);
        color: white; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .btn-power:disabled { opacity: 0.4; cursor: not-allowed; filter: grayscale(1); }
    .power-count { background: rgba(0,0,0,0.4); padding: 2px 7px; border-radius: 50px; font-size: 0.72rem; font-weight: 700; color: white; }

    /* Timer */
    .round-timer {
        border-radius: 10px; height: 22px; width: 100%;
        position: relative; overflow: hidden; margin-bottom: 24px;
        background: rgba(0,0,0,0.3); border: 1px solid rgba(255,255,255,0.06);
    }
    .timer-bar {
        height: 100%; background: var(--success); border-radius: 10px;
        transition: width 1s linear, background 0.5s ease;
    }
    #timerText {
        position: absolute; top: 50%; left: 50%;
        transform: translate(-50%,-50%);
        font-size: 0.85rem; font-weight: 700;
        color: rgba(255,255,255,0.9); text-shadow: 0 1px 3px rgba(0,0,0,0.8);
    }

    /* Answer feedback */
    .answer-btn.correct-choice {
        background: rgba(16,185,129,0.15) !important; border-color: var(--success) !important;
        color: #6EE7B7 !important; box-shadow: 0 0 20px rgba(16,185,129,0.15);
    }
    .answer-btn.wrong-choice {
        background: rgba(239,68,68,0.12) !important; border-color: var(--danger) !important; color: #FCA5A5 !important;
    }
    .answer-btn.disabled { pointer-events: none; opacity: 0.65; }

    /* Roulette center & arrow */
    .roulette-center {
        cursor: pointer; transition: transform 0.1s;
        background: white; color: #0F172A; font-weight: 900; z-index: 50;
    }
    .roulette-center:active { transform: translate(-50%, -50%) scale(0.93); }
    .roulette-center.disabled { pointer-events: none; opacity: 0.75; }
    .roulette-arrow {
        position: absolute; top: -20px; left: 50%; transform: translateX(-50%);
        width: 0; height: 0;
        border-left: 12px solid transparent;
        border-right: 12px solid transparent;
        border-top: 24px solid #FFD700;
        filter: drop-shadow(0 2px 6px rgba(255,215,0,0.7)); z-index: 10;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .lobby-card { padding: 40px 20px; }
        .lobby-title { font-size: 2rem; }
        .duel-header { flex-direction: column; gap: 20px; padding: 20px; }
        .player-card.right { flex-direction: row; text-align: left; }
        .player-card.right .history-dots { margin-left: 0; }
        .question-card { padding: 28px 20px; }
    }
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
        <!-- LOBBY SCREEN -->
        <div class="lobby-card animate-entry">
            <div class="radar-ring"></div>

            <h1 class="lobby-title">PROCURANDO<br><span>ADVERSÁRIO</span></h1>
            <p class="lobby-subtitle">Aguardando jogadores online...</p>

            <div class="matchup-preview">
                <div class="matchup-slot">
                    <div class="matchup-avatar">
                       @if(Auth::check() && Auth::user()->foto_perfil)
                            <img src="{{ asset(Auth::user()->foto_perfil) }}" alt="Você">
                       @else
                            <img src="{{ asset('assets/imagens/avatars/avatar_player.png') }}" alt="Você">
                       @endif
                    </div>
                    <div class="matchup-name">Você</div>
                </div>
                <div class="matchup-vs">VS</div>
                <div class="matchup-slot">
                    <div class="matchup-avatar dashed">?</div>
                    <div class="matchup-name" id="statusText">Buscando...</div>
                </div>
            </div>

            <div class="search-status">
                <span class="s-dot"></span> Buscando jogadores online...
            </div>

            <form method="POST" action="{{ route('jogo.bot') }}">
                @csrf
                <button type="submit" class="btn-bot-hero">
                    <i class="fas fa-robot" style="font-size: 1.5rem;"></i>
                    <div style="text-align: left; line-height: 1.3;">
                        <div>Jogar contra Bot</div>
                        <div style="font-size: 0.8rem; opacity: 0.75; font-weight: 400;">Treino instantâneo nível Hard</div>
                    </div>
                </button>
            </form>

            <button onclick="location.reload()" class="btn-secondary-action">
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
        <div class="lobby-card animate-entry" style="text-align: center;">
            @if($meusPontos > $oponentePontos)
                <div style="font-size: 3.5rem; margin-bottom: 4px;">🏆</div>
                <h1 style="font-size: 3rem; font-weight: 900; letter-spacing: -2px; color: var(--success); margin-bottom: 4px;">VITÓRIA!</h1>
                <p style="color: var(--muted); margin-bottom: 36px;">Parabéns! Você dominou o duelo.</p>
            @elseif($meusPontos < $oponentePontos)
                <div style="font-size: 3.5rem; margin-bottom: 4px;">💀</div>
                <h1 style="font-size: 3rem; font-weight: 900; letter-spacing: -2px; color: var(--danger); margin-bottom: 4px;">DERROTA</h1>
                <p style="color: var(--muted); margin-bottom: 36px;">Não desista, pratique mais!</p>
            @else
                <div style="font-size: 3.5rem; margin-bottom: 4px;">🤝</div>
                <h1 style="font-size: 3rem; font-weight: 900; letter-spacing: -2px; color: var(--warn); margin-bottom: 4px;">EMPATE!</h1>
                <p style="color: var(--muted); margin-bottom: 36px;">Igualados até o fim!</p>
            @endif

            <div style="display: flex; justify-content: center; gap: 50px; margin-bottom: 40px;">
                <div style="text-align: center;">
                    <div class="p-avatar" style="width: 80px; height: 80px; margin: 0 auto 12px;">
                        <img src="{{ asset('assets/imagens/avatars/avatar_player.png') }}" alt="Player">
                    </div>
                    <div style="font-size: 2.8rem; font-weight: 900; background: linear-gradient(135deg,#fff,rgba(255,255,255,0.5)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $meusPontos }}</div>
                    <div style="font-size: 0.8rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted);">Seus Pontos</div>
                </div>
                <div style="align-self: center; font-size: 1.5rem; font-weight: 900; font-style: italic; opacity: 0.3;">VS</div>
                <div style="text-align: center;">
                    <div class="p-avatar bot" style="width: 80px; height: 80px; margin: 0 auto 12px;">
                        <img src="{{ asset('assets/imagens/avatars/avatar_bot.png') }}" alt="Bot">
                    </div>
                    <div style="font-size: 2.8rem; font-weight: 900; background: linear-gradient(135deg,var(--p2),rgba(236,72,153,0.5)); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $oponentePontos }}</div>
                    <div style="font-size: 0.8rem; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted);">Oponente</div>
                </div>
            </div>

            <a href="{{ route('jogo.index') }}" class="btn-bot-hero" onclick="sessionStorage.removeItem('search_start_ts')">
                <i class="fas fa-redo"></i> Jogar Novamente
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
                            $histUser = (string)$meuId !== '' ? ($historico[(string)$meuId] ?? []) : [];
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

            <!-- VS + Live badge -->
            <div class="vs-center">
                <div class="vs-divider">VS</div>
                <div class="live-badge"><span class="live-dot"></span> AO VIVO</div>
                <div style="font-size: 0.72rem; color: var(--muted); font-weight: 600; letter-spacing: 1px;">ROUND {{ $rodada->numero_rodada }}/10</div>
            </div>

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

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px;">
                <div class="question-number">Questão <strong>{{ $rodada->numero_rodada }}</strong>/10</div>
                <div class="cat-badge">
                    <i class="{{ $rodada->pergunta->categoria->icone }}"></i> {{ $catName }}
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

            <h2 style="font-weight: 700; font-size: 1.4rem; text-align: center; margin: 28px 0 30px; line-height: 1.45; color: var(--text);">
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
