@extends('layouts.app')

@section('content')
<div class="container">
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-calendar-alt"></i> Meus Cronogramas</h1>
            <div class="user-info">
                <a href="{{ route('dashboard') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </a>
                <a href="{{ route('cronogramas.create') }}" class="action-btn">
                    <i class="fas fa-plus"></i>
                    <span>Novo Cronograma</span>
                </a>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <section class="cronogramas-section">
        <div class="card">
            <h2><i class="fas fa-history"></i> Cronogramas Existentes</h2>
            
            @if(count($cronogramas) === 0)
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h3>Nenhum cronograma encontrado</h3>
                    <p>Comece criando seu primeiro cronograma de estudos!</p>
                    <a href="{{ route('cronogramas.create') }}" class="btn-primary" style="margin-top: 15px;">
                        <i class="fas fa-plus"></i> Criar Cronograma
                    </a>
                </div>
            @else
                <div class="cronogramas-grid">
                    @foreach($cronogramas as $cronograma)
                        <div class="cronograma-card">
                            <div class="cronograma-header">
                                <h3>{{ $cronograma->titulo ?? $cronograma->edital->nome_arquivo ?? 'Edital Removido' }}</h3>
                                @if($cronograma->titulo)
                                    <small style="color: var(--text-secondary); display: block; font-size: 0.8rem;">Baseado em: {{ $cronograma->edital->nome_arquivo ?? 'Edital Removido' }}</small>
                                @endif
                                <span class="cronograma-date">
                                    {{ \Carbon\Carbon::parse($cronograma->data_inicio)->format('d/m/Y') }} - 
                                    {{ \Carbon\Carbon::parse($cronograma->data_fim)->format('d/m/Y') }}
                                </span>
                            </div>
                            
                            <div class="cronograma-stats">
                                <div class="stat">
                                    <i class="fas fa-clock"></i>
                                    <span>{{ $cronograma->horas_por_dia }}h/dia</span>
                                </div>
                                <div class="stat">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>{{ $cronograma->edital->disciplinas->count() ?? 0 }} disciplinas</span>
                                </div>
                            </div>
                            
                            <div class="cronograma-actions">
                                <a href="{{ route('cronogramas.show', $cronograma->id) }}" class="btn-main">
                                    <i class="fas fa-eye"></i> Detalhes
                                </a>
                                
                                <div class="actions-group">
                                    <button type="button" class="btn-icon" onclick="renameCronograma({{ $cronograma->id }}, '{{ $cronograma->titulo }}')" title="Renomear">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    
                                    <a href="{{ route('cronogramas.pdf', $cronograma->id) }}" class="btn-icon" title="Baixar PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    
                                    <form action="{{ route('cronogramas.destroy', $cronograma->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este cronograma?');" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon delete" title="Excluir">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>

<style>
    .cronogramas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        margin-top: 25px;
    }
    
    .cronograma-card {
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 24px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    
    .cronograma-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 6px;
        background: linear-gradient(90deg, var(--primary-color), #ff8a65);
    }
    
    .cronograma-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: transparent;
    }
    
    .cronograma-header {
        margin-bottom: 20px;
    }
    
    .cronograma-header h3 {
        color: var(--text-primary);
        font-size: 1.25rem;
        margin-bottom: 8px;
        font-weight: 700;
        line-height: 1.4;
    }
    
    .cronograma-date {
        color: var(--text-secondary);
        font-size: 0.85rem;
        background: rgba(0,0,0,0.05); /* Leve fundo */
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
    }
    
    .cronograma-stats {
        display: flex;
        gap: 20px;
        margin-bottom: 25px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border-color);
    }
    
    .stat {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-secondary);
        font-size: 0.95rem;
    }
    
    .stat i {
        color: var(--primary-color);
        background: rgba(231, 76, 60, 0.1);
        padding: 6px;
        border-radius: 50%;
        font-size: 0.8rem;
    }
    
    .cronograma-actions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .btn-main {
        flex: 1;
        background: var(--primary-color);
        color: white;
        padding: 10px 16px;
        border-radius: 12px;
        text-align: center;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s;
        box-shadow: 0 4px 6px rgba(231, 76, 60, 0.2);
    }

    .btn-main:hover {
        background: #c0392b;
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(231, 76, 60, 0.3);
    }

    .actions-group {
        display: flex;
        gap: 8px;
    }

    .btn-icon {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-icon:hover {
        background: var(--bg-input);
        color: var(--primary-color);
        border-color: var(--primary-color);
        transform: translateY(-2px);
    }

    .btn-icon.delete:hover {
        color: #dc3545;
        border-color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
    }

    .empty-state {
        text-align: center;
        padding: 60px 40px;
        color: var(--text-secondary);
        background: var(--bg-card);
        border-radius: 16px;
        border: 2px dashed var(--border-color);
    }
</style>

<!-- Hidden Rename Form -->
<form id="rename-form" action="" method="POST" style="display: none;">
    @csrf
    @method('PUT')
    <input type="hidden" name="titulo" id="rename-titulo-input">
</form>

<script>
    function renameCronograma(id, currentTitle) {
        const newTitle = prompt("Novo título para o cronograma:", currentTitle || "");
        if (newTitle !== null) {
            const form = document.getElementById('rename-form');
            // Use Blade to generate a template URL and replace the ID placeholder
            const urlTemplate = "{{ route('cronogramas.update', ':id') }}";
            form.action = urlTemplate.replace(':id', id);
            
            document.getElementById('rename-titulo-input').value = newTitle;
            form.submit();
        }
    }
</script>
@endsection
