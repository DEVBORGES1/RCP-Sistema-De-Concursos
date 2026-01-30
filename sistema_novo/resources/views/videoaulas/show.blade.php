@extends('layouts.app')

@section('title', $categoria->nome . ' - Disciplinas')

@section('content')
<div class="container">
    <div class="header-content" style="display: flex;justify-content: space-between;align-items: center;margin-bottom: 30px;">
        <h1 style="font-size: 1.8rem;color:white;"><i class="{{ $categoria->icone }}"></i> {{ $categoria->nome }}</h1>
        <div class="user-info">
            <a href="{{ route('videoaulas.index') }}" class="btn-secondary" style="padding: 10px 20px;text-decoration: none;display: flex;align-items: center;gap: 5px;">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
        </div>
    </div>

    <div class="categoria-header" style="background: linear-gradient(135deg, {{ $categoria->cor }} 0%, {{ $categoria->cor }}dd 100%); color: white; padding: 30px; border-radius: 20px; margin-bottom: 30px; text-align: center; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
        <h1 style="margin: 0 0 10px 0; font-size: 2.5em; display: flex; align-items: center; justify-content: center; gap: 15px;">
            <i class="{{ $categoria->icone }}"></i> {{ $categoria->nome }}
        </h1>
        <p style="margin: 0; opacity: 0.9; font-size: 1.2em;">{{ $categoria->descricao }}</p>
    </div>

    @if (empty($temas))
        <div class="empty-state" style="text-align: center; padding: 60px 20px; color: #7f8c8d;">
            <i class="fas fa-book" style="font-size: 4em; margin-bottom: 20px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0; color: white;">Nenhuma disciplina encontrada</h3>
            <p style="color: #ccc;">Ainda não há videoaulas cadastradas para esta matéria.</p>
        </div>
    @else
        <div class="temas-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 25px;">
            @foreach ($temas as $tema)
                <div class="tema-card {{ $tema['progresso'] == 100 ? 'completo' : '' }}" 
                     style="background: rgba(255,255,255,0.05); border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: all 0.3s ease; border: 2px solid transparent; position: relative; overflow: hidden; --tema-cor: {{ $categoria->cor }}">
                    
                    @if ($tema['progresso'] == 100)
                        <div class="badge-completo" style="position: absolute; top: 15px; right: 15px; background: #27ae60; color: white; padding: 5px 12px; border-radius: 15px; font-size: 0.75em; font-weight: 600;">
                            <i class="fas fa-check-circle"></i> Completo
                        </div>
                    @endif
                    
                    <div class="tema-header" style="display: flex; align-items: center; margin-bottom: 15px;">
                        <div class="tema-icon" style="width: 50px; height: 50px; border-radius: 10px; background: {{ $categoria->cor }}; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.3em; margin-right: 15px;">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="tema-info">
                            <h3 style="margin: 0; color: white; font-size: 1.3em; font-weight: bold;">{{ $tema['nome'] }}</h3>
                            <p style="margin: 5px 0 0 0; color: #ccc; font-size: 0.9em;">{{ count($tema['videoaulas']) > 0 ? count($tema['videoaulas']) . ' videoaula(s)' : 'Aguardando videoaulas' }}</p>
                        </div>
                    </div>
                    
                    <div class="tema-progress" style="margin: 15px 0;">
                        <div class="progress-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <span class="progress-label" style="font-size: 0.9em; font-weight: 600; color: white;">Progresso</span>
                            <span class="progress-percentage" style="font-weight: bold; color: {{ $categoria->cor }}; font-size: 1em;">{{ $tema['progresso'] }}%</span>
                        </div>
                        <div class="progress-bar" style="width: 100%; height: 10px; background: rgba(255,255,255,0.1); border-radius: 10px; overflow: hidden;">
                            <div class="progress-fill" style="width: {{ $tema['progresso'] }}%; height: 100%; background: linear-gradient(90deg, {{ $categoria->cor }}, {{ $categoria->cor }}88); border-radius: 10px; transition: width 0.8s ease;"></div>
                        </div>
                    </div>

                    <!-- Lista de Aulas (Accordion ou Links diretos) -->
                    @if (count($tema['videoaulas']) > 0)
                    <div class="aulas-list" style="margin-top: 15px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 10px;">
                         @foreach ($tema['videoaulas'] as $aula)
                            <a href="{{ route('videoaulas.player', $aula->id) }}" class="aula-link" style="display: block; padding: 8px; color: #ccc; text-decoration: none; font-size: 0.9rem; border-radius: 5px; margin-bottom: 5px;">
                                <i class="fas fa-play-circle" style="color: {{ $aula->concluida ? '#27ae60' : $categoria->cor }}"></i> 
                                {{ $aula->titulo }}
                            </a>
                         @endforeach
                    </div>
                    @endif
                    
                    <div class="tema-stats" style="display: flex; justify-content: space-around; margin-top: 15px; padding-top: 15px; border-top: 1px solid rgba(255,255,255,0.1);">
                        <div class="stat-item" style="text-align: center;">
                            <h4 style="margin: 0; color: {{ $categoria->cor }}; font-size: 1.3em; font-weight: bold;">{{ $tema['total'] }}</h4>
                            <p style="margin: 3px 0 0 0; color: #ccc; font-size: 0.8em;">Total</p>
                        </div>
                        <div class="stat-item" style="text-align: center;">
                            <h4 style="margin: 0; color: {{ $categoria->cor }}; font-size: 1.3em; font-weight: bold;">{{ $tema['concluidas'] }}</h4>
                            <p style="margin: 3px 0 0 0; color: #ccc; font-size: 0.8em;">Concluídas</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
