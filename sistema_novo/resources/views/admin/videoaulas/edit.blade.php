@extends('layouts.app')

@section('content')
<div class="container">
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-edit"></i> Editar Videoaula</h1>
            <div class="user-info">
                <a href="{{ route('admin.videoaulas.index') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>
    </header>

    <div class="card">
        <form action="{{ route('admin.videoaulas.update', $videoaula->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-grid">
                <div class="form-group full-width">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" required value="{{ old('titulo', $videoaula->titulo) }}">
                </div>

                <div class="form-group">
                    <label for="categoria_id">Categoria</label>
                    <select name="categoria_id" id="categoria_id" required>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}" {{ old('categoria_id', $videoaula->categoria_id) == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="url_video">URL do YouTube</label>
                    <input type="text" name="url_video" id="url_video" required value="{{ old('url_video', $videoaula->url_video) }}">
                </div>

                <div class="form-group">
                    <label for="duracao">Duração (minutos)</label>
                    <input type="number" name="duracao" id="duracao" min="0" value="{{ old('duracao', $videoaula->duracao) }}">
                </div>

                <div class="form-group">
                    <label for="ordem">Ordem de Exibição</label>
                    <input type="number" name="ordem" id="ordem" min="0" value="{{ old('ordem', $videoaula->ordem) }}">
                </div>

                <div class="form-group checkbox-group">
                    <label>
                        <input type="checkbox" name="ativo" value="1" {{ old('ativo', $videoaula->ativo) ? 'checked' : '' }}>
                        Ativo
                    </label>
                </div>

                <div class="form-group full-width">
                    <label for="descricao">Descrição (Opcional)</label>
                    <textarea name="descricao" id="descricao" rows="4">{{ old('descricao', $videoaula->descricao) }}</textarea>
                </div>
            </div>

            <div class="preview-section full-width" style="margin-bottom: 20px;">
                <label>Preview Atual:</label>
                <div class="video-container">
                    <iframe src="{{ $videoaula->url_video }}" frameborder="0" allowfullscreen style="width: 100%; height: 300px; border-radius: 8px;"></iframe>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary">Atualizar Videoaula</button>
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
