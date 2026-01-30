@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 800px;">
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-question-circle"></i> Responder Questão</h1>
            <div class="user-info">
                <a href="{{ route('questoes.index') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>
    </header>

    <div class="card questao-display">
        <div class="questao-meta-info">
            <span class="badge">{{ $questao->disciplina->nome ?? 'Geral' }}</span>
            <span class="badge" style="background:var(--bg-secondary);color:var(--text-secondary);">{{ $questao->edital->titulo }}</span>
        </div>

        <div class="enunciado-box">
            <p>{{ $questao->enunciado }}</p>
        </div>

        @if(session('resultado'))
            <div class="resultado-feedback {{ session('resultado')['acertou'] ? 'sucesso' : 'erro' }}">
                @if(session('resultado')['acertou'])
                    <h3><i class="fas fa-check-circle"></i> Parabéns! Você acertou!</h3>
                @else
                    <h3><i class="fas fa-times-circle"></i> Resposta Incorreta</h3>
                    <p>A alternativa correta era: <strong>{{ session('resultado')['correta'] }}</strong></p>
                @endif
            </div>
        @endif

        <form action="{{ route('questoes.responder', $questao->id) }}" method="POST" class="alternativas-form">
            @csrf
            
            <div class="alternativas-list">
                @foreach(['a', 'b', 'c', 'd', 'e'] as $opcao)
                    @php 
                        $campo = 'alternativa_' . $opcao;
                        $valor = $questao->$campo;
                        $classe = '';
                        $icon = 'circle';
                        
                        if(session('resultado')) {
                            // Se já respondeu, marcar cores
                            if (session('resultado')['correta'] == strtoupper($opcao)) {
                                $classe = 'correct';
                                $icon = 'check-circle';
                            } elseif ( request('resposta') == strtoupper($opcao) && !session('resultado')['acertou'] ) {
                                $classe = 'incorrect';
                                $icon = 'times-circle';
                            }
                        }
                    @endphp

                    <label class="alternativa-item {{ $classe }}">
                        <input type="radio" name="resposta" value="{{ strtoupper($opcao) }}" required {{ session('resultado') ? 'disabled' : '' }}>
                        <span class="opcao-letra">{{ strtoupper($opcao) }}</span>
                        <div class="opcao-texto">{{ $valor }}</div>
                        @if($classe)
                            <i class="fas fa-{{ $icon }} status-icon"></i>
                        @endif
                    </label>
                @endforeach
            </div>

            @if(!session('resultado'))
                <div class="actions">
                    <button type="submit" class="btn-responder">Responder</button>
                </div>
            @else
                <div class="actions">
                    <a href="{{ route('questoes.index') }}" class="btn-responder btn-secondary">Próxima Questão</a>
                </div>
            @endif
        </form>
    </div>
</div>

<style>
    .questao-meta-info {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }
    .enunciado-box {
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 30px;
        padding: 20px;
        background: var(--bg-body);
        border-radius: 8px;
        border-left: 4px solid var(--primary-color);
    }
    .alternativas-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }
    .alternativa-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        gap: 15px;
    }
    .alternativa-item:hover:not(.correct):not(.incorrect) {
        border-color: var(--primary-color);
        background: rgba(52, 152, 219, 0.05);
    }
    .alternativa-item input { display: none; }
    
    .opcao-letra {
        font-weight: 800;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--bg-secondary);
        border-radius: 50%;
        color: var(--text-secondary);
    }
    .alternativa-item input:checked + .opcao-letra {
        background: var(--primary-color);
        color: white;
    }
    
    /* Estados de Feedback */
    .alternativa-item.correct {
        border-color: #2ecc71;
        background: rgba(46, 204, 113, 0.1);
    }
    .alternativa-item.correct .opcao-letra { background: #2ecc71; color: white; }
    
    .alternativa-item.incorrect {
        border-color: #e74c3c;
        background: rgba(231, 76, 60, 0.1);
    }
    .alternativa-item.incorrect .opcao-letra { background: #e74c3c; color: white; }

    .btn-responder {
        width: 100%;
        padding: 15px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.2rem;
        font-weight: bold;
        margin-top: 30px;
        cursor: pointer;
    }
    .resultado-feedback {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        text-align: center;
    }
    .resultado-feedback.sucesso { background: #d4edda; color: #155724; }
    .resultado-feedback.erro { background: #f8d7da; color: #721c24; }
</style>
@endsection
