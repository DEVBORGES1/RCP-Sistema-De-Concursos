@extends('layouts.app')

@section('content')
<div class="container">
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-calendar-check"></i> {{ $cronograma->titulo ?? 'Cronograma Detalhado' }}</h1>
            <div class="user-info">
                <a href="{{ route('cronogramas.pdf', $cronograma->id) }}" class="action-btn">
                    <i class="fas fa-file-pdf"></i>
                    <span>Baixar PDF</span>
                </a>
                <a href="{{ route('cronogramas.index') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <!-- Resumo -->
    <section class="cronograma-info">
        <div class="card">
            <div class="info-grid">
                <div class="info-item">
                    <h4>Edital</h4>
                    <p>{{ $cronograma->edital->nome_arquivo }}</p>
                </div>
                <div class="info-item">
                    <h4>Período</h4>
                    <p>{{ \Carbon\Carbon::parse($cronograma->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($cronograma->data_fim)->format('d/m/Y') }}</p>
                </div>
                <div class="info-item">
                    <h4>Carga Horária</h4>
                    <p>{{ $cronograma->horas_por_dia }} horas/dia</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Cronograma Lists -->
    <section class="cronograma-days">
        <div class="timeline">
            @foreach($diasAgrupados as $data => $atividades)
                <div class="timeline-day">
                    <div class="day-header">
                        <div class="date-badge">
                            <span class="day">{{ \Carbon\Carbon::parse($data)->format('d') }}</span>
                            <span class="month">{{ \Carbon\Carbon::parse($data)->format('M') }}</span>
                        </div>
                        <div class="weekday">
                            {{ \Carbon\Carbon::parse($data)->locale('pt-BR')->dayName }}
                        </div>
                    </div>
                    
                    <div class="day-content">
                        @foreach($atividades as $atividade)
                            <div class="activity-card">
                                <div class="activity-icon">
                                    <i class="fas fa-book"></i>
                                </div>
                                <div class="activity-details">
                                    <h4>{{ $atividade->disciplina->nome_disciplina }}</h4>
                                    <span class="duration">
                                        <i class="fas fa-hourglass-half"></i> {{ $atividade->horas_previstas }}h
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</div>

<style>
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .info-item h4 {
        color: var(--text-secondary);
        font-size: 0.9rem;
        margin-bottom: 5px;
    }
    
    .info-item p {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 1.1rem;
    }
    
    .timeline {
        margin-top: 30px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .timeline-day {
        display: flex;
        gap: 20px;
    }
    
    .day-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        min-width: 80px;
    }
    
    .date-badge {
        background: var(--primary-color);
        color: white;
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        width: 60px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    
    .date-badge .day {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        line-height: 1;
    }
    
    .date-badge .month {
        display: block;
        font-size: 0.8rem;
        text-transform: uppercase;
    }
    
    .weekday {
        margin-top: 5px;
        color: var(--text-secondary);
        font-size: 0.8rem;
        text-transform: capitalize;
    }
    
    .day-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background: var(--bg-card);
        border-radius: 12px;
        padding: 20px;
        border: 1px solid var(--border-color);
    }
    
    .activity-card {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px;
        background: var(--bg-input);
        border-radius: 8px;
        transition: transform 0.2s;
    }
    
    .activity-card:hover {
        transform: translateX(5px);
    }
    
    .activity-icon {
        width: 40px;
        height: 40px;
        background: rgba(231, 76, 60, 0.1);
        color: var(--primary-color);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .activity-details h4 {
        margin: 0;
        color: var(--text-primary);
        font-size: 1rem;
    }
    
    .duration {
        font-size: 0.85rem;
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        gap: 5px;
        margin-top: 3px;
    }
    
    @media (max-width: 600px) {
        .timeline-day {
            flex-direction: column;
            gap: 10px;
        }
        
        .day-header {
            flex-direction: row;
            align-items: center;
            gap: 15px;
            width: 100%;
        }
        
        .weekday {
            margin-top: 0;
            font-size: 1rem;
            font-weight: 600;
        }
    }
</style>
@endsection
