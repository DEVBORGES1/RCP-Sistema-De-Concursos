@extends('layouts.app')

@section('content')
<div class="container">
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-magic"></i> Criar Cronograma</h1>
            <div class="user-info">
                <a href="{{ route('cronogramas.index') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>
    </header>

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    <section class="create-section">
        <div class="card">
            <h2>Configure seu Cronograma</h2>
            <p style="color: var(--text-secondary); margin-bottom: 20px;">
                Selecione um edital base e personalize sua rotina de estudos.
            </p>

            <form action="{{ route('cronogramas.store') }}" method="POST" class="cronograma-form">
                @csrf
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label for="titulo">Título do Cronograma (Opcional)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-heading"></i>
                            <input type="text" name="titulo" id="titulo" placeholder="Ex: Cronograma TJ-SP 2026">
                        </div>
                    </div>

                    <div class="form-group full-width">
                        <label for="edital_id">Edital Base</label>
                        <div class="input-wrapper">
                            <i class="fas fa-file-alt"></i>
                            <select name="edital_id" id="edital_id" required onchange="toggleCargos()">
                                <option value="">Selecione um edital...</option>
                                @foreach($editais as $edital)
                                    <option value="{{ $edital->id }}" data-cargos="{{ json_encode($edital->cargos) }}" {{ request('edital_id') == $edital->id ? 'selected' : '' }}>
                                        {{ $edital->nome_arquivo }} ({{ $edital->disciplinas_count }} disciplinas)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group full-width" id="cargo_group" style="display: none;">
                        <label for="cargo_id">Selecione o Cargo (Opcional)</label>
                        <div class="input-wrapper">
                            <i class="fas fa-briefcase"></i>
                            <select name="cargo_id" id="cargo_id">
                                <option value="">Estudar para todos os cargos (Geral)</option>
                            </select>
                        </div>
                        <small style="margin-left: 5px; color: var(--text-secondary); margin-top: 5px; display: block;">
                            Se selecionar um cargo, o cronograma focará apenas nas disciplinas dele + comuns.
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="data_inicio">Data de Início</label>
                        <div class="input-wrapper">
                            <i class="fas fa-calendar-day"></i>
                            <input type="date" name="data_inicio" id="data_inicio" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="horas_por_dia">Carga Horária Diária</label>
                        <div class="input-wrapper">
                            <i class="fas fa-clock"></i>
                            <select name="horas_por_dia" id="horas_por_dia" required>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $i == 4 ? 'selected' : '' }}>{{ $i }} horas</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="duracao_semanas">Duração do Ciclo</label>
                        <div class="input-wrapper">
                            <i class="fas fa-hourglass-half"></i>
                            <select name="duracao_semanas" id="duracao_semanas" required>
                                <option value="2">2 semanas (Sprint)</option>
                                <option value="4" selected>4 semanas (Mensal)</option>
                                <option value="8">8 semanas (Bimestral)</option>
                                <option value="12">12 semanas (Trimestral)</option>
                                <option value="24">24 semanas (Semestral)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-cogs"></i> Gerar Cronograma
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>

<style>
    .create-section .card {
        padding: 40px;
        border-radius: 20px;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    }

    .cronograma-form {
        max-width: 800px;
        margin: 0 auto;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
        margin-bottom: 40px;
    }
    
    /* Title spans full width */
    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        position: relative;
    }
    
    .form-group label {
        color: var(--text-primary);
        font-weight: 600;
        font-size: 0.95rem;
        margin-left: 4px;
    }
    
    .input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-wrapper i {
        position: absolute;
        left: 15px;
        color: var(--text-secondary);
        pointer-events: none;
        transition: color 0.3s;
    }

    .form-group select,
    .form-group input {
        width: 100%;
        padding: 14px 14px 14px 45px; /* Space for icon */
        border: 2px solid var(--border-color);
        border-radius: 12px;
        background: var(--bg-input);
        color: var(--text-primary);
        font-size: 1rem;
        transition: all 0.3s ease;
        appearance: none;
    }
    
    .form-group select:focus,
    .form-group input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(231, 76, 60, 0.1);
    }

    .form-group select:focus + i,
    .form-group input:focus + i {
        color: var(--primary-color);
    }
    
    .form-actions {
        display: flex;
        justify-content: flex-end;
        border-top: 1px solid var(--border-color);
        padding-top: 24px;
    }
    
    .btn-primary {
        padding: 14px 32px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 12px;
        background: var(--primary-color);
        box-shadow: 0 4px 12px rgba(231, 76, 60, 0.3);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(231, 76, 60, 0.4);
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .create-section .card {
            padding: 24px;
        }
    }
</style>
<script>
    function toggleCargos() {
        const editalSelect = document.getElementById('edital_id');
        const cargoGroup = document.getElementById('cargo_group');
        const cargoSelect = document.getElementById('cargo_id');
        
        const selectedOption = editalSelect.options[editalSelect.selectedIndex];
        const cargosData = selectedOption.getAttribute('data-cargos');
        
        // Limpar cargos
        cargoSelect.innerHTML = '<option value="">Estudar para todos os cargos (Geral)</option>';
        
        if (cargosData) {
            const cargos = JSON.parse(cargosData);
            if (cargos.length > 0) {
                cargoGroup.style.display = 'flex';
                cargos.forEach(cargo => {
                    const option = document.createElement('option');
                    option.value = cargo.id;
                    option.textContent = cargo.nome;
                    cargoSelect.appendChild(option);
                });
            } else {
                cargoGroup.style.display = 'none';
            }
        } else {
            cargoGroup.style.display = 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if(document.getElementById('edital_id').value) {
            toggleCargos();
        }
    });
</script>
@endsection
