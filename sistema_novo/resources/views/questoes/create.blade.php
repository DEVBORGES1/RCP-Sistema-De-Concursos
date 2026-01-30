@extends('layouts.app')

@section('content')
<div class="container">
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-plus-circle"></i> Criar Nova Questão</h1>
            <div class="user-info">
                <a href="{{ route('questoes.index') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>
    </header>

    <div class="card">
        <form action="{{ route('questoes.store') }}" method="POST">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="edital_id">Edital</label>
                    <select name="edital_id" id="edital_id" required>
                        <option value="">Selecione...</option>
                        @foreach($editais as $edital)
                            <option value="{{ $edital->id }}" {{ old('edital_id') == $edital->id ? 'selected' : '' }}>
                                {{ $edital->titulo }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="disciplina_id">Disciplina</label>
                    <select name="disciplina_id" id="disciplina_id">
                        <option value="">Selecione...</option>
                        {{-- TODO: Implementar carregamento dinâmico via JS baseado no edital --}}
                        <option value="">Carregar disciplinas via JS...</option> 
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="enunciado">Enunciado</label>
                    <textarea name="enunciado" id="enunciado" rows="5" required>{{ old('enunciado') }}</textarea>
                </div>

                @foreach(['a', 'b', 'c', 'd', 'e'] as $opcao)
                <div class="form-group full-width alternativa-group">
                    <label for="alternativa_{{ $opcao }}">Alternativa {{ strtoupper($opcao) }}</label>
                    <div class="input-with-radio">
                         <input type="radio" name="alternativa_correta" value="{{ strtoupper($opcao) }}" required {{ old('alternativa_correta') == strtoupper($opcao) ? 'checked' : '' }}>
                         <input type="text" name="alternativa_{{ $opcao }}" id="alternativa_{{ $opcao }}" required value="{{ old('alternativa_'.$opcao) }}" style="flex: 1;">
                    </div>
                </div>
                @endforeach
            </div>

            <div class="form-actions" style="margin-top: 20px;">
                <button type="submit" class="btn-primary">Salvar Questão</button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }
    .full-width { grid-column: 1 / -1; }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: var(--text-primary);
    }
    .form-group input[type="text"], .form-group textarea, .form-group select {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: var(--bg-input);
    }
    .input-with-radio {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .input-with-radio input[type="radio"] {
        transform: scale(1.5);
        cursor: pointer;
    }
    .btn-primary {
        width: 100%;
        padding: 15px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: bold;
        cursor: pointer;
        transition: opacity 0.2s;
    }
    .btn-primary:hover { opacity: 0.9; }
</style>
@endsection
