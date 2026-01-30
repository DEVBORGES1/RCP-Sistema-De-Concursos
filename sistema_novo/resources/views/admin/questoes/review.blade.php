@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4" style="color: var(--text-primary);">
                <i class="fas fa-check-double text-success"></i> Revisar Questões Geradas
            </h2>

            <form action="{{ route('admin.questoes.store') }}" method="POST">
                @csrf
                <input type="hidden" name="disciplina_id" value="{{ $disciplina_id }}">
                <input type="hidden" name="edital_id" value="{{ $edital_id }}">

                @foreach($questoes as $index => $q)
                <div class="card mb-4" style="background: var(--card-bg); border: 1px solid var(--border-color);">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span class="badge bg-primary">Questão {{ $index + 1 }}</span>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="questoes[{{ $index }}][save]" value="1" checked>
                            <label class="form-check-label text-white">Salvar esta questão</label>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label text-muted">Enunciado</label>
                            <textarea name="questoes[{{ $index }}][enunciado]" class="form-control bg-dark text-white mb-2" rows="3">{{ $q['enunciado'] }}</textarea>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-secondary text-white">A</span>
                                    <input type="text" name="questoes[{{ $index }}][alternativa_a]" class="form-control bg-dark text-white" value="{{ $q['alternativa_a'] }}">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-secondary text-white">B</span>
                                    <input type="text" name="questoes[{{ $index }}][alternativa_b]" class="form-control bg-dark text-white" value="{{ $q['alternativa_b'] }}">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-secondary text-white">C</span>
                                    <input type="text" name="questoes[{{ $index }}][alternativa_c]" class="form-control bg-dark text-white" value="{{ $q['alternativa_c'] }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-secondary text-white">D</span>
                                    <input type="text" name="questoes[{{ $index }}][alternativa_d]" class="form-control bg-dark text-white" value="{{ $q['alternativa_d'] }}">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text bg-secondary text-white">E</span>
                                    <input type="text" name="questoes[{{ $index }}][alternativa_e]" class="form-control bg-dark text-white" value="{{ $q['alternativa_e'] }}">
                                </div>
                                
                                <div class="mt-3">
                                    <label class="form-label text-success">Alternativa Correta</label>
                                    <select name="questoes[{{ $index }}][alternativa_correta]" class="form-select bg-dark text-white border-success">
                                        <option value="A" {{ strtoupper($q['alternativa_correta']) == 'A' ? 'selected' : '' }}>A</option>
                                        <option value="B" {{ strtoupper($q['alternativa_correta']) == 'B' ? 'selected' : '' }}>B</option>
                                        <option value="C" {{ strtoupper($q['alternativa_correta']) == 'C' ? 'selected' : '' }}>C</option>
                                        <option value="D" {{ strtoupper($q['alternativa_correta']) == 'D' ? 'selected' : '' }}>D</option>
                                        <option value="E" {{ strtoupper($q['alternativa_correta']) == 'E' ? 'selected' : '' }}>E</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="text-end mb-5">
                    <button type="submit" class="btn btn-success btn-lg">
                        <i class="fas fa-save me-2"></i> Salvar Questões Selecionadas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
