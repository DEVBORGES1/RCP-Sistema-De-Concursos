@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4" style="color: var(--text-primary); font-weight: 700;">
                <i class="fas fa-robot text-primary"></i> Gerador de Questões com IA
            </h2>

            @if(session('erro'))
                <div class="alert alert-danger">
                    {{ session('erro') }}
                </div>
            @endif

            <div class="card" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                <div class="card-body p-4">
                    <form action="{{ route('admin.questoes.gerar') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <!-- Configurações -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Disciplina (Opcional)</label>
                                    <select name="disciplina_id" class="form-control bg-dark text-white border-secondary">
                                        <option value="">Selecione...</option>
                                        @foreach($disciplinas as $d)
                                            <option value="{{ $d->id }}" {{ old('disciplina_id') == $d->id ? 'selected' : '' }}>{{ $d->nome }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Edital (Opcional)</label>
                                    <select name="edital_id" class="form-control bg-dark text-white border-secondary">
                                        <option value="">Selecione...</option>
                                        @foreach($editais as $e)
                                            <option value="{{ $e->id }}" {{ old('edital_id') == $e->id ? 'selected' : '' }}>{{ $e->nome_arquivo }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row">
                                    <div class="col-6">
                                        <label class="form-label">Qtd.</label>
                                        <input type="number" name="quantidade" class="form-control bg-dark text-white border-secondary" value="5" min="1" max="20">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Nível</label>
                                        <select name="nivel" class="form-control bg-dark text-white border-secondary">
                                            <option value="Fácil">Fácil</option>
                                            <option value="Médio" selected>Médio</option>
                                            <option value="Difícil">Difícil</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Texto Base -->
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label class="form-label">Texto Base (Cole aqui o conteúdo: Lei, Resumo, etc)</label>
                                    <textarea name="texto_base" rows="12" class="form-control bg-dark text-white border-secondary" placeholder="Cole o texto aqui..." required>{{ old('texto_base') }}</textarea>
                                    <small class="text-muted">A IA usará este texto como única fonte para criar as perguntas.</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                             <a href="{{ route('questoes.index') }}" class="btn btn-secondary me-2">Voltar</a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-magic me-2"></i> Gerar Questões
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
