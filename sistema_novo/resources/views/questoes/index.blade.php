@extends('layouts.app')

@section('content')
<div class="container">
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-database"></i> Banco de Questões</h1>
            <div class="user-info">
                <a href="{{ route('questoes.create') }}" class="action-btn">
                    <i class="fas fa-plus"></i>
                    <span>Adicionar Questão</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Stats -->
    <div class="stats-overview">
        <div class="stat-card">
            <h3>{{ $total_questoes }}</h3>
            <p><i class="fas fa-layer-group"></i> Total de Questões</p>
        </div>
        <div class="stat-card">
            <h3>N/A</h3>
            <p><i class="fas fa-check-circle"></i> Respondidas</p>
        </div>
        <div class="stat-card">
            <h3>0%</h3>
            <p><i class="fas fa-chart-line"></i> Aproveitamento</p>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card" style="margin-bottom: 20px;">
        <form action="{{ route('questoes.index') }}" method="GET" class="filters-form">
            <div class="form-group">
                <label>Edital</label>
                <select name="edital_id">
                    <option value="">Todos</option>
                    @foreach($editais as $edital)
                        <option value="{{ $edital->id }}" {{ request('edital_id') == $edital->id ? 'selected' : '' }}>
                            {{ $edital->titulo }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="form-group">
                <label>Disciplina</label>
                <select name="disciplina_id">
                    <option value="">Todas</option>
                    @foreach($disciplinas as $disc)
                        <option value="{{ $disc->id }}" {{ request('disciplina_id') == $disc->id ? 'selected' : '' }}>
                            {{ $disc->nome }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn-filter">
                <i class="fas fa-filter"></i> Filtrar
            </button>
        </form>
    </div>

    <!-- Lista -->
    <div class="questoes-grid">
        @forelse($questoes as $questao)
            <div class="card questao-card">
                <div class="questao-header">
                    <span class="badge badge-info">{{ $questao->disciplina->nome ?? 'Geral' }}</span>
                    <small>Edital: {{ $questao->edital->titulo }}</small>
                </div>
                <div class="questao-body">
                    <p>{{ Str::limit($questao->enunciado, 150) }}</p>
                </div>
                <div class="questao-footer">
                    <a href="{{ route('questoes.show', $questao->id) }}" class="btn-responder">
                        Responder <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @empty
            <div class="empty-state">
                <p>Nenhuma questão encontrada.</p>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrapper">
        {{ $questoes->links() }}
    </div>
</div>

<style>
    .filters-form {
        display: flex;
        gap: 15px;
        align-items: flex-end;
    }
    .filters-form .form-group {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .filters-form select {
        padding: 10px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-input);
    }
    .btn-filter, .btn-responder {
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-filter {
        background: var(--bg-secondary);
        color: var(--text-primary);
        height: 40px; 
    }
    .btn-responder {
        background: var(--primary-color);
        color: white;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .questoes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
    }
    .questao-card {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .questao-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .badge-info {
        background: rgba(52, 152, 219, 0.1);
        color: #3498db;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.8rem;
    }
    .stats-overview {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-card {
        background: var(--bg-card);
        padding: 20px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .stat-card h3 {
        font-size: 2rem;
        margin-bottom: 5px;
        color: var(--primary-color);
    }
</style>
@endsection
