@extends('layouts.app')

@section('content')
<div class="container">
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-cog"></i> Gerenciar Videoaulas</h1>
            <div class="user-info">
                <a href="{{ route('admin.videoaulas.create') }}" class="action-btn">
                    <i class="fas fa-plus"></i>
                    <span>Nova Videoaula</span>
                </a>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <h2>Videoaulas Cadastradas</h2>
        
        @if($videoaulas->isEmpty())
            <div class="empty-state">
                <p>Nenhuma videoaula cadastrada.</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Categoria</th>
                            <th>Título</th>
                            <th>Duração</th>
                            <th>Ordem</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($videoaulas as $video)
                            <tr>
                                <td>
                                    <span class="badge" style="background-color: {{ $video->categoria->cor ?? '#ccc' }}; color: white;">
                                        {{ $video->categoria->nome ?? 'Sem Categoria' }}
                                    </span>
                                </td>
                                <td>{{ $video->titulo }}</td>
                                <td>{{ $video->duracao }} min</td>
                                <td>{{ $video->ordem }}</td>
                                <td>
                                    <span class="badge {{ $video->ativo ? 'badge-success' : 'badge-danger' }}">
                                        {{ $video->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="actions-group">
                                        <a href="{{ route('admin.videoaulas.edit', $video->id) }}" class="btn-icon" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.videoaulas.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Tem certeza?');" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon delete" title="Excluir">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
    .table-responsive {
        overflow-x: auto;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    .table th, .table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid var(--border-color);
    }
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: var(--text-primary);
    }
    .badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.85rem;
        display: inline-block;
    }
    .badge-success { background: #d4edda; color: #155724; }
    .badge-danger { background: #f8d7da; color: #721c24; }
    
    .actions-group {
        display: flex;
        gap: 8px;
    }
    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 1px solid var(--border-color);
        background: transparent;
        color: var(--text-secondary);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        background: var(--bg-input);
        color: var(--primary-color);
        border-color: var(--primary-color);
    }
    .btn-icon.delete:hover {
        color: #dc3545;
        border-color: #dc3545;
        background: rgba(220, 53, 69, 0.1);
    }
</style>
@endsection
