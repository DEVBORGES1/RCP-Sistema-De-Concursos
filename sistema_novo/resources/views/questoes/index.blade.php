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
    <div class="stats-overview" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
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
    <div class="card filters-card" style="margin-bottom: 25px; background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); border-radius: 16px; padding: 20px;">
        <form action="{{ route('questoes.index') }}" method="GET" class="filters-form">
            <div class="form-group">
                <label>Edital</label>
                <select name="edital_id" class="premium-select">
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
                <select name="disciplina_id" class="premium-select">
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
            <div class="questao-card">
                <div class="questao-header">
                    <span class="badge badge-info"><i class="fas fa-tag"></i> {{ $questao->disciplina->nome ?? 'Geral' }}</span>
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
                <i class="fas fa-database" style="font-size: 3rem; opacity: 0.3; margin-bottom: 15px;"></i>
                <p>Nenhuma questão encontrada.</p>
            </div>
        @endforelse
    </div>

    <div class="pagination-wrapper" style="margin-top: 30px;">
        {{ $questoes->links() }}
    </div>
</div>

<style>
    :root {
        --q-primary: #7C3AED;
        --q-primary-hover: #6D28D9;
        --q-info: #3B82F6;
        --q-card: rgba(255,255,255,0.03);
        --q-border: rgba(255,255,255,0.08);
        --q-text: #E2E8F0;
        --q-muted: #94A3B8;
        --q-input: rgba(0,0,0,0.2);
    }
    
    body.light-mode {
        --q-primary: #4F46E5;
        --q-primary-hover: #4338CA;
        --q-info: #2563EB;
        --q-card: #FFFFFF;
        --q-border: rgba(0,0,0,0.1);
        --q-text: #1E293B;
        --q-muted: #64748B;
        --q-input: #F8FAFC;
    }

    h1 { color: var(--dash-text-primary, var(--q-text)); }

    /* Stats Overview */
    .stat-card {
        background: linear-gradient(135deg, var(--q-primary) 0%, #4C1D95 100%);
        color: white; padding: 25px 20px; border-radius: 16px;
        text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        transition: transform 0.3s ease;
    }
    body.light-mode .stat-card {
        background: linear-gradient(135deg, var(--q-primary) 0%, #3730A3 100%);
    }
    .stat-card:hover { transform: translateY(-3px); }
    .stat-card h3 { font-size: 2.5rem; margin: 0 0 5px; font-weight: 800; line-height: 1; }
    .stat-card p { margin: 0; font-size: 1rem; opacity: 0.9; font-weight: 500; }

    /* Filtros */
    .filters-form { display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap; }
    .filters-form .form-group { flex: 1; min-width: 200px; display: flex; flex-direction: column; gap: 8px; }
    .filters-form label { font-size: 0.85rem; font-weight: 600; color: var(--q-muted); text-transform: uppercase; letter-spacing: 0.5px; }
    .premium-select {
        width: 100%; padding: 12px 15px; border-radius: 10px;
        background: var(--q-input); border: 1px solid var(--q-border);
        color: var(--q-text); font-size: 0.95rem; outline: none; transition: border-color 0.2s;
    }
    .premium-select:focus { border-color: var(--q-primary); }
    
    .btn-filter, .btn-responder {
        padding: 12px 24px; border-radius: 10px; border: none; font-family: 'Outfit', sans-serif;
        font-weight: 600; cursor: pointer; transition: all 0.2s; display: inline-flex; align-items: center; justify-content: center; gap: 8px;
    }
    .btn-filter {
        background: var(--q-card); color: var(--q-text); border: 1px solid var(--q-border); 
        height: 45px; min-width: 120px;
    }
    .btn-filter:hover { background: var(--q-border); }

    /* Grid de Questões */
    .questoes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 25px; }
    .questao-card {
        background: var(--q-card); border: 1px solid var(--q-border);
        border-radius: 16px; padding: 25px; display: flex; flex-direction: column;
        transition: transform 0.3s, box-shadow 0.3s;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    body.light-mode .questao-card { box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
    .questao-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.1); border-color: var(--q-primary); }
    
    .questao-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; gap: 10px; flex-wrap: wrap; }
    .badge-info {
        background: rgba(59, 130, 246, 0.15); color: var(--q-info);
        padding: 6px 12px; border-radius: 50px; font-size: 0.8rem; font-weight: 700;
        display: inline-flex; align-items: center; gap: 5px;
    }
    .questao-header small { color: var(--q-muted); font-size: 0.8rem; font-weight: 500; }
    
    .questao-body { flex-grow: 1; margin-bottom: 25px; }
    .questao-body p { margin: 0; color: var(--q-text); font-size: 1.05rem; line-height: 1.6; }
    
    .questao-footer { display: flex; justify-content: flex-end; padding-top: 15px; border-top: 1px solid var(--q-border); }
    .btn-responder { background: var(--q-primary); color: white; text-decoration: none; font-size: 0.95rem; }
    .btn-responder:hover { background: var(--q-primary-hover); transform: translateY(-2px); box-shadow: 0 8px 15px rgba(124, 58, 237, 0.3); color: white; }

    .empty-state {
        grid-column: 1 / -1; text-align: center; padding: 60px 20px;
        background: var(--q-card); border: 2px dashed var(--q-border);
        border-radius: 16px; color: var(--q-muted);
    }
    
    /* Paginação Customizada (para corrigir o markup padrão do Laravel) */
    .pagination-wrapper nav {
        display: flex; flex-direction: column; gap: 15px; align-items: center;
    }
    .pagination-wrapper svg { width: 20px; height: 20px; }
    .pagination-wrapper p { color: var(--q-muted); font-size: 0.9rem; margin: 0; }
    .pagination-wrapper span.relative, .pagination-wrapper a.relative {
        display: inline-flex; align-items: center; justify-content: center;
        padding: 8px 12px; min-width: 40px; height: 40px;
        border-radius: 8px; border: 1px solid var(--q-border);
        background: var(--q-card); color: var(--q-text);
        text-decoration: none; font-weight: 500; font-size: 0.95rem;
        transition: all 0.2s;
    }
    .pagination-wrapper a.relative:hover {
        background: var(--q-primary); border-color: var(--q-primary); color: white;
    }
    /* Active page */
    .pagination-wrapper span[aria-current="page"] > span {
        background: var(--q-primary); border-color: var(--q-primary); color: white;
        display: inline-flex; align-items: center; justify-content: center;
        padding: 8px 12px; min-width: 40px; height: 40px; border-radius: 8px; font-weight: 700;
    }
    /* Hidden elements in desktop/mobile tailwind layout */
    .pagination-wrapper .hidden { display: none !important; }
    @media (min-width: 640px) {
        .pagination-wrapper .sm\:flex { display: flex !important; }
        .pagination-wrapper .sm\:hidden { display: none !important; }
        .pagination-wrapper .sm\:flex-1 { flex: 1 1 0% !important; }
        .pagination-wrapper .sm\:items-center { align-items: center !important; }
        .pagination-wrapper .sm\:justify-between { justify-content: space-between !important; }
        .pagination-wrapper nav { flex-direction: row; justify-content: space-between; }
    }
</style>
@endsection
