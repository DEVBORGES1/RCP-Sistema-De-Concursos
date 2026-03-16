@extends('layouts.app')

@section('title', 'Escrevendo Redação')

@section('content')
<div class="escrever-wrapper">
    <div class="editor-header">
        <a href="{{ route('redacoes.index') }}" class="back-link"><i class="fas fa-arrow-left"></i> Voltar</a>
        <h2>{{ $tema->titulo }}</h2>
        <div class="word-count" id="word-count"><i class="fas fa-info-circle"></i> 0 palavras | 0 caracteres</div>
    </div>

    <div class="editor-grid">
        <!-- Lado Esquerdo: Texto Motivador -->
        <div class="motivador-panel">
            <h3 class="panel-title"><i class="fas fa-book-open"></i> Texto Motivador</h3>
            <div class="motivador-content">
                {!! nl2br(e($tema->texto_motivador)) !!}
            </div>
        </div>

        <!-- Lado Direito: Área de Escrita -->
        <div class="writing-panel">
            @if($errors->any())
                <div class="alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('redacoes.submit', $tema->id) }}" method="POST" id="redacao-form">
                @csrf
                <textarea 
                    name="texto_enviado" 
                    id="texto_enviado" 
                    class="redacao-textarea" 
                    placeholder="Comece a digitar sua dissertação aqui... Recomendamos entre 20 a 30 linhas de conteúdo (aprox. 1500 caracteres)."
                    required
                >{{ old('texto_enviado') }}</textarea>
                
                <div class="submit-bar">
                    <p class="submit-info">Sua redação será corrigida em segundos por Inteligência Artificial ao enviar.</p>
                    <button type="submit" class="btn-submit" id="btn-submit">
                        <i class="fas fa-paper-plane"></i> Enviar para Correção
                    </button>
                </div>
            </form>
            
            <!-- Loading State overlay -->
            <div class="loading-overlay" id="loading-overlay">
                <div class="loader-spinner"></div>
                <h3 class="loader-text">Avaliando Gramática e Argumentação...</h3>
                <p>Nossa IA está lendo sua redação. Isso pode levar alguns segundos.</p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Ocupar a tela inteira com o editor */
    .escrever-wrapper {
        min-height: calc(100vh - 100px);
        display: flex;
        flex-direction: column;
    }

    .editor-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--dash-border, rgba(255,255,255,0.08));
    }
    
    .back-link {
        color: var(--dash-text-secondary, #94a3b8);
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    .back-link:hover { color: #fff; }
    
    .editor-header h2 {
        font-family: 'Syne', sans-serif;
        font-size: 1.4rem;
        margin: 0;
        text-align: center;
        flex-grow: 1;
        padding: 0 20px;
        color: var(--dash-text-primary, #fff);
    }
    
    .word-count {
        font-size: 0.85rem;
        color: #94a3b8;
        background: var(--dash-bg-card, rgba(255,255,255,0.05));
        padding: 6px 12px;
        border-radius: 100px;
    }

    .editor-grid {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 20px;
        flex-grow: 1;
    }

    .motivador-panel, .writing-panel {
        background: var(--dash-bg-card, rgba(255,255,255,0.02));
        border: 1px solid var(--dash-border, rgba(255,255,255,0.05));
        border-radius: 16px;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .panel-title {
        background: rgba(0,0,0,0.2);
        padding: 15px 20px;
        margin: 0;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 700;
        color: var(--dash-text-secondary, #94a3b8);
        border-bottom: 1px solid var(--dash-border, rgba(255,255,255,0.05));
    }

    .motivador-content {
        padding: 20px;
        overflow-y: auto;
        color: var(--dash-text-primary, #e2e8f0);
        line-height: 1.7;
        font-size: 0.95rem;
        flex-grow: 1;
    }

    .writing-panel {
        position: relative;
    }

    .redacao-textarea {
        width: 100%;
        height: 100%;
        min-height: 400px;
        flex-grow: 1;
        background: transparent;
        border: none;
        resize: none;
        padding: 30px;
        color: var(--dash-text-primary, #fff);
        font-size: 1.1rem;
        line-height: 1.8;
        font-family: inherit;
    }
    .redacao-textarea:focus { outline: none; }
    
    .submit-bar {
        background: rgba(0,0,0,0.2);
        padding: 15px 20px;
        border-top: 1px solid var(--dash-border, rgba(255,255,255,0.05));
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .submit-info { margin: 0; font-size: 0.85rem; color: #94a3b8; }
    
    .btn-submit {
        background: linear-gradient(135deg, #10B981, #059669);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 10px;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.2s, box-shadow 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-submit:hover {
        transform: scale(1.03);
        box-shadow: 0 5px 15px rgba(16, 185, 129, 0.3);
    }

    .alert-error {
        background: rgba(239, 68, 68, 0.1);
        border-bottom: 1px solid rgba(239, 68, 68, 0.3);
        color: #F87171;
        padding: 15px;
    }
    .alert-error ul { margin: 0; padding-left: 20px; }

    /* LOADING OVERLAY */
    .loading-overlay {
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.9);
        backdrop-filter: blur(5px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 10;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }
    .loading-overlay.active {
        opacity: 1; pointer-events: auto;
    }

    .loader-spinner {
        width: 60px; height: 60px;
        border: 4px solid rgba(255,255,255,0.1);
        border-top-color: #EC4899;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 20px;
    }
    @keyframes spin { 100% { transform: rotate(360deg); } }
    
    .loader-text {
        color: white; font-family: 'Syne', sans-serif; font-size: 1.5rem; margin-bottom: 10px;
    }
    .loading-overlay p { color: #94a3b8; }

    @media (max-width: 900px) {
        .editor-grid { grid-template-columns: 1fr; }
        .motivador-panel { max-height: 200px; }
        .word-count { display: none; }
        .submit-bar { flex-direction: column; gap: 15px; text-align: center; }
        .btn-submit { width: 100%; }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('texto_enviado');
    const wordCount = document.getElementById('word-count');
    const form = document.getElementById('redacao-form');
    const overlay = document.getElementById('loading-overlay');

    // Live Word/Char Count
    textarea.addEventListener('input', function() {
        const text = this.value;
        const chars = text.length;
        const words = text.trim() === '' ? 0 : text.trim().split(/\s+/).length;
        
        let icon = '<i class="fas fa-check-circle" style="color:#10B981"></i>';
        if(chars < 100) icon = '<i class="fas fa-exclamation-triangle" style="color:#F59E0B"></i>';
        
        wordCount.innerHTML = `${icon} ${words} palavras | ${chars} caracteres`;
    });

    // Loading State
    form.addEventListener('submit', function() {
        overlay.classList.add('active');
        document.getElementById('btn-submit').disabled = true;
    });
});
</script>
@endsection
