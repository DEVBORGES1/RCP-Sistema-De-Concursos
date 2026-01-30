@extends('layouts.app')

@section('title', 'Videoaulas - RCP Concursos')

@section('content')
<div class="container">
    <div class="header-content" style="display: flex;justify-content: space-between;align-items: center;margin-bottom: 30px;">
        <h1 style="font-size: 1.8rem;color:white;"><i class="fas fa-play-circle"></i> Videoaulas</h1>
        <div class="user-info">
            <a href="{{ route('dashboard') }}" class="btn-secondary" style="padding: 10px 20px;text-decoration: none;display: flex;align-items: center;gap: 5px;">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
        </div>
    </div>

    <!-- Estatísticas Gerais -->
    <div class="stats-overview" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 15px; text-align: center; box-shadow: 0 8px 25px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
            <h3 style="font-size: 2.5em; margin: 0; font-weight: bold;">{{ $stats->total_videoaulas }}</h3>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 1.1em;"><i class="fas fa-video"></i> Total de Videoaulas</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 15px; text-align: center; box-shadow: 0 8px 25px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
            <h3 style="font-size: 2.5em; margin: 0; font-weight: bold;">{{ $stats->videoaulas_concluidas }}</h3>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 1.1em;"><i class="fas fa-check-circle"></i> Concluídas</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 15px; text-align: center; box-shadow: 0 8px 25px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
            <h3 style="font-size: 2.5em; margin: 0; font-weight: bold;">
                @php 
                    $total = $stats->total_videoaulas ?: 1;
                    $perc = round(($stats->videoaulas_concluidas / $total) * 100, 1);
                @endphp
                {{ $perc }}%
            </h3>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 1.1em;"><i class="fas fa-chart-line"></i> Progresso Geral</p>
        </div>
        <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 15px; text-align: center; box-shadow: 0 8px 25px rgba(0,0,0,0.1); transition: transform 0.3s ease;">
            <h3 style="font-size: 2.5em; margin: 0; font-weight: bold;">{{ round($stats->duracao_assistida / 60, 1) }}h</h3>
            <p style="margin: 10px 0 0 0; opacity: 0.9; font-size: 1.1em;"><i class="fas fa-clock"></i> Tempo Assistido</p>
        </div>
    </div>

    <!-- Categorias -->
    @if (empty($categorias))
        <div class="empty-state" style="text-align: center; padding: 60px 20px; color: #7f8c8d;">
            <i class="fas fa-video" style="font-size: 4em; margin-bottom: 20px; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 10px 0; color: white;">Nenhuma matéria disponível</h3>
            <p style="color: #ccc;">Entre em contato com o administrador para configurar as matérias de videoaulas.</p>
        </div>
    @else
        <div class="categorias-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 25px; margin-top: 30px;">
            @foreach ($categorias as $categoria)
                <div class="categoria-card {{ $categoria->porcentagem_concluida == 100 ? 'completa' : '' }}" 
                     style="background: rgba(255,255,255,0.05); border-radius: 20px; padding: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.3); transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.1); position: relative; overflow: hidden; --categoria-cor: {{ $categoria->cor }}">
                    
                    @if ($categoria->porcentagem_concluida == 100)
                        <div class="badge-completo" style="position: absolute; top: 15px; right: 15px; background: #27ae60; color: white; padding: 8px 15px; border-radius: 20px; font-size: 0.85em; font-weight: 600; display: flex; align-items: center; gap: 5px; box-shadow: 0 3px 10px rgba(39, 174, 96, 0.3);">
                            <i class="fas fa-check-circle"></i> Completo!
                        </div>
                    @endif

                    <div class="categoria-header" style="display: flex; align-items: center; margin-bottom: 20px;">
                        <div class="categoria-icon" style="width: 60px; height: 60px; border-radius: 15px; background: {{ $categoria->cor }}; display: flex; align-items: center; justify-content: center; margin-right: 15px; color: white; font-size: 1.5em;">
                            <i class="{{ $categoria->icone }}"></i>
                        </div>
                        <div class="categoria-info">
                            <h3 style="margin: 0; color: white; font-size: 1.4em; font-weight: bold;">{{ $categoria->nome }}</h3>
                            <p style="margin: 5px 0 0 0; color: #ccc; font-size: 0.9em;">{{ $categoria->descricao }}</p>
                        </div>
                    </div>

                    <div class="progress-section" style="margin: 20px 0;">
                        <div class="progress-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <span class="progress-label" style="font-weight: 600; color: white;">Progresso</span>
                            <span class="progress-percentage" style="font-weight: bold; color: {{ $categoria->cor }}; font-size: 1.1em;">{{ $categoria->porcentagem_concluida }}%</span>
                        </div>
                        <div class="progress-bar" style="width: 100%; height: 12px; background: rgba(255,255,255,0.1); border-radius: 10px; overflow: hidden; position: relative;">
                            <div class="progress-fill" style="width: {{ $categoria->porcentagem_concluida }}%; height: 100%; background: linear-gradient(90deg, {{ $categoria->cor }}, {{ $categoria->cor }}88); border-radius: 10px; transition: width 0.8s ease;"></div>
                        </div>
                    </div>

                    <div class="categoria-stats" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin: 20px 0;">
                        <div class="stat-item" style="text-align: center; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 10px; border-left: 4px solid {{ $categoria->cor }};">
                            <h4 style="margin: 0; color: {{ $categoria->cor }}; font-size: 1.5em; font-weight: bold;">{{ $categoria->total_videoaulas }}</h4>
                            <p style="margin: 5px 0 0 0; color: #ccc; font-size: 0.9em;">Total</p>
                        </div>
                        <div class="stat-item" style="text-align: center; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 10px; border-left: 4px solid {{ $categoria->cor }};">
                            <h4 style="margin: 0; color: {{ $categoria->cor }}; font-size: 1.5em; font-weight: bold;">{{ $categoria->videoaulas_iniciadas }}</h4>
                            <p style="margin: 5px 0 0 0; color: #ccc; font-size: 0.9em;">Iniciadas</p>
                        </div>
                        <div class="stat-item" style="text-align: center; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 10px; border-left: 4px solid {{ $categoria->cor }};">
                            <h4 style="margin: 0; color: {{ $categoria->cor }}; font-size: 1.5em; font-weight: bold;">{{ $categoria->videoaulas_concluidas }}</h4>
                            <p style="margin: 5px 0 0 0; color: #ccc; font-size: 0.9em;">Concluídas</p>
                        </div>
                    </div>

                    <div class="categoria-actions" style="display: flex; gap: 10px; margin-top: 20px;">
                        <a href="{{ route('videoaulas.show', $categoria->id) }}" class="btn-categoria" style="flex: 1; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; text-decoration: none; text-align: center; transition: all 0.3s ease; cursor: pointer; background: {{ $categoria->cor }}; color: white;">
                            <i class="fas fa-book-open"></i> Ver Disciplinas
                        </a>
                        @if ($categoria->porcentagem_concluida == 100)
                            <a href="#" class="btn-categoria" style="flex: 1; padding: 12px 20px; border: none; border-radius: 10px; font-weight: 600; text-decoration: none; text-align: center; transition: all 0.3s ease; cursor: pointer; background: #27ae60; color: white;">
                                <i class="fas fa-certificate"></i> Certificado
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
