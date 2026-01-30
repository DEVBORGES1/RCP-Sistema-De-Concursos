@extends('layouts.app')

@section('content')
<div class="container">
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-plus-circle"></i> Nova Videoaula</h1>
            <div class="user-info">
                <a href="{{ route('admin.videoaulas.index') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>
    </header>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <form action="{{ route('admin.videoaulas.store') }}" method="POST">
            @csrf
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" required placeholder="Ex: Aula 01 - Introdução" value="{{ old('titulo') }}">
                </div>

                <div class="form-group">
                    <label for="categoria_id">Categoria</label>
                    <select name="categoria_id" id="categoria_id" required>
                        <option value="">Selecione...</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="url_video">URL do YouTube</label>
                    <input type="text" name="url_video" id="url_video" required placeholder="https://youtube.com/watch?v=..." value="{{ old('url_video') }}">
                    <small>Cole o link completo do vídeo.</small>
                </div>

                <div class="form-group">
                    <label for="duracao">Duração (minutos)</label>
                    <input type="number" name="duracao" id="duracao" min="0" value="{{ old('duracao', 0) }}">
                </div>

                <div class="form-group">
                    <label for="ordem">Ordem de Exibição</label>
                    <input type="number" name="ordem" id="ordem" min="0" value="{{ old('ordem', 0) }}">
                </div>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" name="ativo" value="1" {{ old('ativo', '1') == '1' ? 'checked' : '' }}>
                        Ativo
                    </label>
                </div>

                <div class="form-group full-width">
                    <label for="descricao">Descrição (Opcional)</label>
                    <textarea name="descricao" id="descricao" rows="4">{{ old('descricao') }}</textarea>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Salvar Videoaula</button>
            </div>
        </form>
    </div>
</div>

<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    .full-width { grid-column: 1 / -1; }
    
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .form-group label {
        font-weight: 600;
        color: var(--text-primary);
    }
    .form-group input, .form-group select, .form-group textarea {
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        background: var(--bg-input);
    }
    .checkbox-group label {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }
    .form-actions {
        display: flex;
        justify-content: flex-end;
    }
    .btn-primary {
        padding: 12px 24px;
        background: var(--primary-color);
        color: white;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 600;
    }
    @media (max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection
