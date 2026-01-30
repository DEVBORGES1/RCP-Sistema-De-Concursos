@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-file-alt"></i> Detalhes do Edital</h1>
            <div class="user-info">
                <a href="{{ route('editais.index') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Informações do Edital -->
    <section class="edital-info">
        <div class="card">
            <div class="edital-header">
                <div class="edital-title">
                    <h2><i class="fas fa-file-pdf"></i> {{ $edital->nome_arquivo }}</h2>
                    <p>Enviado em {{ \Carbon\Carbon::parse($edital->data_upload)->format('d/m/Y H:i') }}</p>
                </div>
                <div class="edital-stats">
                    <div class="stat">
                        <i class="fas fa-graduation-cap"></i>
                        <span>{{ $edital->disciplinas->count() }} disciplinas</span>
                    </div>
                    <div class="stat">
                        <i class="fas fa-question-circle"></i>
                        <span>{{ $edital->questoes->count() }} questões</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Texto do Edital -->
    <section class="edital-text">
        <div class="card">
            <h3><i class="fas fa-align-left"></i> Texto Extraído</h3>
            <div class="text-content">
                <pre>{{ $edital->texto_extraido }}</pre>
            </div>
        </div>
    </section>

    <!-- Disciplinas Detectadas -->
    <section class="disciplinas-section">
        <div class="card">
            <h3><i class="fas fa-graduation-cap"></i> Disciplinas Detectadas</h3>
            
            @if($edital->disciplinas->isEmpty())
                <div class="empty-state">
                    <i class="fas fa-graduation-cap"></i>
                    <h4>Nenhuma disciplina detectada</h4>
                    <p>O sistema não conseguiu identificar disciplinas neste edital.</p>
                </div>
            @else
                <div class="disciplinas-grid">
                    @foreach($edital->disciplinas as $disciplina)
                        <div class="disciplina-card">
                            <div class="disciplina-header">
                                <h4>{{ $disciplina->nome_disciplina }}</h4>
                                <span class="questoes-count">
                                    <i class="fas fa-question-circle"></i>
                                    {{ $disciplina->questoes->count() }} questões
                                </span>
                            </div>
                            
                            <div class="disciplina-actions">
                                <!-- Assumindo rotas para questoes e simulados baseadas em disciplina_id que podem ser implementadas futuramente -->
                                <a href="{{ route('questoes.index', ['disciplina_id' => $disciplina->id]) }}" class="btn-primary">
                                    <i class="fas fa-eye"></i> Ver Questões
                                </a>
                                <!-- Rota simulada para simulados -->
                                <a href="#" class="btn-secondary" onclick="alert('Funcionalidade de criar simulado por disciplina ainda em migração.')">
                                    <i class="fas fa-play"></i> Criar Simulado
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    <!-- Ações Rápidas -->
    <section class="quick-actions">
        <div class="card">
            <h3><i class="fas fa-bolt"></i> Ações Rápidas</h3>
            <div class="actions-grid">
                <a href="{{ route('questoes.index', ['edital_id' => $edital->id]) }}" class="action-btn">
                    <i class="fas fa-question-circle"></i>
                    <span>Todas as Questões</span>
                </a>
                <a href="#" class="action-btn" onclick="alert('Ainda em migração.'); return false;">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Criar Simulado</span>
                </a>
                <a href="#" class="action-btn" onclick="alert('Ainda em migração.'); return false;">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Gerar Cronograma</span>
                </a>
            </div>
        </div>
    </section>
</div>

<style>
    .edital-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    
    .edital-title h2 {
        color: var(--text-primary);
        margin: 0 0 5px 0;
    }
    
    .edital-title p {
        color: var(--text-secondary);
        margin: 0;
    }
    
    .edital-stats {
        display: flex;
        gap: 20px;
    }
    
    .stat {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
        font-weight: 500;
    }
    
    .stat i {
        color: var(--primary-color);
    }
    
    .card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        border: 1px solid var(--border-color);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 30px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        color: var(--text-primary);
    }
    
    .text-content {
        background: var(--bg-input);
        border-radius: 10px;
        padding: 20px;
        max-height: 400px;
        overflow-y: auto;
    }
    
    .text-content pre {
        margin: 0;
        white-space: pre-wrap;
        word-wrap: break-word;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        line-height: 1.6;
        color: var(--text-primary);
    }
    
    .disciplinas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }
    
    .disciplina-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        transition: all 0.3s ease;
        color: var(--text-primary);
    }
    
    .disciplina-card:hover {
        transform: translateY(-3px);
        background: var(--bg-card-hover);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }
    
    .disciplina-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .disciplina-header h4 {
        color: var(--text-primary);
        margin: 0;
        font-size: 1.1rem;
    }
    
    .questoes-count {
        display: flex;
        align-items: center;
        gap: 5px;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }
    
    .questoes-count i {
        color: var(--primary-color);
    }
    
    .disciplina-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }
    
    .action-btn {
        background: var(--bg-card);
        color: var(--text-primary);
        padding: 15px 20px;
        border: none;
        border-radius: 10px;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
        text-align: center;
        border: 1px solid var(--border-color);
    }
    
    .action-btn:hover {
        background: var(--bg-card-hover);
        transform: translateY(-2px);
    }
    
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #666;
    }
    
    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #ccc;
    }
    
    .empty-state h4 {
        color: var(--text-primary);
        margin-bottom: 10px;
    }
</style>
@endsection
