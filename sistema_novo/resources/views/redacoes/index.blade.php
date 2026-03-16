@extends('layouts.app')

@section('title', 'Redações (Discursivas) - Treine com IA')

@section('content')
<div class="redacoes-container">
    <div class="redacoes-header">
        <h1><i class="fas fa-pen-nib" style="color: #EC4899;"></i> Foco na <span style="background: linear-gradient(135deg, #fff 30%, #EC4899 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Escrita</span></h1>
        <p>Pratique discursivas com temas atuais e receba correção instantânea e detalhada da nossa Inteligência Artificial.</p>
    </div>

    @if(session('info'))
        <div class="alert-premium info">
            <i class="fas fa-info-circle"></i> {{ session('info') }}
        </div>
    @endif

    <div class="temas-grid">
        @forelse($temas as $tema)
            <div class="tema-card">
                <div class="tema-badge">{{ $tema->banca_referencia ?? 'Tema Inédito' }}</div>
                <h3>{{ $tema->titulo }}</h3>
                <p class="tema-excerpt">{{ Str::limit($tema->texto_motivador, 120) }}</p>
                <div class="tema-actions">
                    <a href="{{ route('redacoes.escrever', $tema->id) }}" class="btn-premium">
                        Escrever Redação <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>Nenhum tema disponível no momento. Volte mais tarde!</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    .redacoes-container {
        max-width: 1000px;
        margin: 0 auto;
        padding-bottom: 80px;
    }
    
    .redacoes-header {
        text-align: center;
        margin-bottom: 50px;
        position: relative;
    }
    .redacoes-header h1 {
        font-family: 'Syne', sans-serif;
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 15px;
        color: var(--dash-text-primary, #fff);
    }
    .redacoes-header p {
        color: var(--dash-text-secondary, rgba(255, 255, 255, 0.6));
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .temas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 25px;
    }

    .tema-card {
        background: var(--dash-bg-card, rgba(255,255,255,0.03));
        border: 1px solid var(--dash-border, rgba(255,255,255,0.08));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border-radius: 20px;
        padding: 30px;
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .tema-card:hover {
        transform: translateY(-5px);
        border-color: rgba(236, 72, 153, 0.4);
        box-shadow: 0 10px 40px rgba(236, 72, 153, 0.1);
    }

    .tema-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: rgba(236, 72, 153, 0.1);
        color: #EC4899;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 5px 12px;
        border-radius: 100px;
        border: 1px solid rgba(236, 72, 153, 0.2);
    }

    .tema-card h3 {
        font-family: 'Syne', sans-serif;
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 15px;
        color: var(--dash-text-primary, #fff);
        padding-right: 80px; /* space for badge */
        line-height: 1.3;
    }

    .tema-excerpt {
        color: var(--dash-text-secondary, rgba(255, 255, 255, 0.6));
        font-size: 0.95rem;
        line-height: 1.6;
        margin-bottom: 30px;
        flex-grow: 1;
    }

    .btn-premium {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 14px 20px;
        border-radius: 12px;
        background: linear-gradient(135deg, #EC4899, #8B5CF6);
        color: white;
        font-weight: 700;
        font-family: 'Outfit', sans-serif;
        text-decoration: none;
        transition: opacity 0.2s, transform 0.2s;
        border: none;
    }
    .btn-premium:hover {
        opacity: 0.9;
        transform: scale(1.02);
        color: white;
    }

    .alert-premium {
        padding: 15px 20px;
        border-radius: 12px;
        margin-bottom: 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        font-weight: 600;
    }
    .alert-premium.info {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid rgba(59, 130, 246, 0.3);
        color: #60A5FA;
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 60px 20px;
        color: var(--dash-text-secondary);
    }
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    @media (max-width: 768px) {
        .redacoes-header h1 { font-size: 2.2rem; }
    }
</style>
@endsection
