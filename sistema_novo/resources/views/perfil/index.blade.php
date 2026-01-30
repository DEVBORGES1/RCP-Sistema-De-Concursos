@extends('layouts.app')

<?php
/** @var \App\Models\User $user */
?>


@section('title', 'Study Hub - Meu Perfil')

@section('content')
<div class="container main-profile-container">

    @if (session('success'))
        <div class="alert alert-success" style="background: rgba(39, 174, 96, 0.2); border: 1px solid #27ae60; color: #2ecc71; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 20px;">
           <ul style="margin: 0;">@foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach</ul>
        </div>
    @endif

    <!-- Profile Header / Hero -->
    <div class="profile-hero">
        <div class="hero-content">
            <div class="avatar-section">
                <div class="avatar-ring">
                    @if($user->foto_perfil)
                        <img src="{{ asset($user->foto_perfil) }}" alt="" class="profile-avatar-img">
                    @else
                        <div class="profile-avatar-default">
                            {{ strtoupper(substr($user->nome, 0, 1)) }}
                        </div>
                    @endif
                    <div class="level-badge">LVL {{ $nivel }}</div>
                </div>
            </div>
            
            <div class="user-details">
                <h1 class="user-name">{{ $user->nome }}</h1>
                <div class="user-meta">
                    <span class="meta-item"><i class="fas fa-graduation-cap"></i> {{ $user->escolaridade ?? 'Escolaridade N/D' }}</span>
                    @if($user->linkedin)
                        <a href="{{ $user->linkedin }}" target="_blank" class="meta-item link"><i class="fab fa-linkedin"></i> LinkedIn</a>
                    @endif
                    <span class="meta-item"><i class="fas fa-briefcase"></i> {{ $user->area_interesse ?? 'Explorador' }}</span>
                </div>
                
                <!-- XP Bar -->
                <div class="xp-container">
                    <div class="xp-info">
                        <span>XP {{ $xpAtual }} / {{ $xpProximoNivel }}</span>
                        <span class="xp-next">Próximo Nível</span>
                    </div>
                    <div class="xp-bar-bg">
                        <div class="xp-bar-fill" style="width: {{ $progressoNivel }}%"></div>
                    </div>
                </div>
            </div>

            <!-- Streak Card (Right Side) -->
            <div class="streak-card">
                <div class="fire-icon"><i class="fas fa-fire"></i></div>
                <div class="streak-number">{{ $stats['maior_streak'] }}</div>
                <div class="streak-label">Dias Seguidos</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <button class="action-card primary" onclick="alert('Funcionalidade em breve: Continuar último estudo')">
            <i class="fas fa-play"></i> Continuar Estudando
        </button>
        <button class="action-card secondary" onclick="switchTab('editar')">
            <i class="fas fa-cog"></i> Editar Perfil
        </button>
    </div>

    <!-- Navigation Tabs -->
    <div class="profile-tabs">
        <button class="tab-btn active" onclick="switchTab('visao-geral')" id="btn-visao-geral">
            <i class="fas fa-chart-pie"></i> Visão Geral
        </button>
        <button class="tab-btn" onclick="switchTab('conquistas')" id="btn-conquistas">
            <i class="fas fa-trophy"></i> Conquistas
        </button>
        <button class="tab-btn" onclick="switchTab('editar')" id="btn-editar">
            <i class="fas fa-user-edit"></i> Dados & Preferências
        </button>
    </div>

    <!-- TAB: Visão Geral -->
    <div id="tab-visao-geral" class="tab-content active">
        <!-- Stats Grid -->
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-icon yellow"><i class="fas fa-crown"></i></div>
                <div class="stat-value">{{ $stats['posicao'] }}º</div>
                <div class="stat-label">Ranking Geral</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon red"><i class="fas fa-bullseye"></i></div>
                <div class="stat-value">{{ $stats['taxa_acerto'] }}%</div>
                <div class="stat-label">Precisão Média</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon blue"><i class="fas fa-book-reader"></i></div>
                <div class="stat-value">{{ $stats['questoes_respondidas'] }}</div>
                <div class="stat-label">Questões Feitas</div>
            </div>
            <div class="stat-box">
                <div class="stat-icon green"><i class="fas fa-certificate"></i></div>
                <div class="stat-value">{{ $stats['certificados'] }}</div>
                <div class="stat-label">Certificados</div>
            </div>
        </div>

        <div class="charts-row">
            <!-- Weekly Activity (Mock CSS Chart) -->
            <div class="chart-card">
                <h3><i class="fas fa-chart-bar"></i> Atividade (7 Dias)</h3>
                <div class="activity-graph">
                    @foreach($stats['atividade_semanal'] as $dia)
                        <div class="bar-col">
                            <div class="bar-fill" style="height: {{ $dia['valor'] }}%; opacity: {{ $dia['valor'] > 0 ? 1 : 0.2 }}"></div>
                            <span class="bar-label">{{ $dia['dia'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Focus Subjects -->
            <div class="chart-card">
                <h3><i class="fas fa-crosshairs"></i> Matérias em Foco</h3>
                <div class="focus-list">
                    @foreach($stats['materias_foco'] as $materia)
                        <div class="focus-item">
                            <div class="focus-header">
                                <span>{{ $materia['nome'] }}</span>
                                <span style="color: {{ $materia['cor'] }}">{{ $materia['acerto'] }}%</span>
                            </div>
                            <div class="focus-bar-bg">
                                <div class="focus-bar-fill" style="width: {{ $materia['acerto'] }}%; background: {{ $materia['cor'] }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Certificados Recentes -->
         <section class="certificates-section" style="margin-top: 30px;">
            <h3 style="color: white; margin-bottom: 20px; font-size: 1.2rem;">Meus Certificados</h3>
            @if (count($certificados) === 0)
                <div class="empty-state-mini">
                    <p>Nenhum certificado ainda. Assista às aulas!</p>
                </div>
            @else
                <div class="certificates-horizontal">
                    @foreach ($certificados as $cert)
                        <div class="cert-card-mini">
                            <i class="fas fa-award"></i>
                            <div class="cert-info">
                                <strong>{{ $cert->categoria }}</strong>
                                <small>{{ \Carbon\Carbon::parse($cert->data_conclusao)->format('d/m/Y') }}</small>
                            </div>
                            <a href="{{ route('certificados.gerar', $cert->categoria_id) }}" target="_blank" class="btn-icon-xs"><i class="fas fa-download"></i></a>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    <!-- TAB: Conquistas -->
    <div id="tab-conquistas" class="tab-content" style="display: none;">
        <div class="badges-grid">
            @foreach($conquistas as $conquista)
                @php $unlocked = in_array($conquista->id, $conquistasUsuario); @endphp
                <div class="badge-card {{ $unlocked ? 'unlocked' : 'locked' }}">
                    <div class="badge-icon">
                        <i class="{{ $conquista->icone ?? 'fas fa-medal' }}"></i>
                    </div>
                    <h4>{{ $conquista->titulo }}</h4>
                    <p>{{ $conquista->descricao }}</p>
                    @if(!$unlocked) <div class="lock-overlay"><i class="fas fa-lock"></i></div> @endif
                </div>
            @endforeach
            <!-- Mock Badges se tabela vazia -->
            @if(count($conquistas) == 0)
                <div class="badge-card unlocked">
                    <div class="badge-icon"><i class="fas fa-user-plus"></i></div>
                    <h4>Bem-vindo!</h4>
                    <p>Criou sua conta.</p>
                </div>
                <div class="badge-card locked">
                    <div class="badge-icon"><i class="fas fa-fire"></i></div>
                    <h4>Semana de Fogo</h4>
                    <p>Estude 7 dias seguidos.</p>
                    <div class="lock-overlay"><i class="fas fa-lock"></i></div>
                </div>
                 <div class="badge-card locked">
                    <div class="badge-icon"><i class="fas fa-book"></i></div>
                    <h4>Devorador de Livros</h4>
                    <p>Complete 5 disciplinas.</p>
                    <div class="lock-overlay"><i class="fas fa-lock"></i></div>
                </div>
            @endif
        </div>
    </div>

    <!-- TAB: Editar -->
    <div id="tab-editar" class="tab-content" style="display: none;">
        <div class="edit-form-card">
            <form method="POST" action="{{ route('perfil.update') }}" enctype="multipart/form-data" id="profile-form">
                @csrf
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Foto de Perfil</label>
                        <div class="avatar-upload-container">
                            <div class="avatar-preview-wrapper">
                                <div class="avatar-preview" id="avatar-preview">
                                    @if($user->foto_perfil)
                                        <img src="{{ asset($user->foto_perfil) }}" alt="">
                                    @else
                                        <div class="avatar-placeholder">
                                            <i class="fas fa-user"></i>
                                            <span>Clique para enviar</span>
                                        </div>
                                    @endif
                                </div>
                                <button type="button" class="btn-change-avatar" onclick="document.getElementById('avatar-input').click()">
                                    <i class="fas fa-camera"></i> Alterar Foto
                                </button>
                            </div>
                            <input type="file" id="avatar-input" name="foto_perfil" accept="image/*" style="display: none;">
                            <input type="hidden" id="cropped-avatar" name="cropped_avatar">
                        </div>
                        <small style="color: #7f8c8d; display: block; margin-top: 10px;">Formatos: JPG, PNG. Máx: 2MB. Clique na imagem para ajustar.</small>
                    </div>
                    
                    <div class="form-group">
                        <label>Nome Completo</label>
                        <input type="text" name="nome" value="{{ $user->nome }}" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" value="{{ $user->email }}" required class="form-input">
                    </div>

                    <div class="form-group">
                        <label>LinkedIn (URL)</label>
                        <input type="url" name="linkedin" value="{{ $user->linkedin }}" class="form-input">
                    </div>

                    <div class="form-group">
                        <label>Escolaridade</label>
                        <select name="escolaridade" class="form-input">
                            <option value="">Selecione...</option>
                            <option value="Médio" {{ $user->escolaridade == 'Médio' ? 'selected' : '' }}>Ensino Médio</option>
                            <option value="Superior" {{ $user->escolaridade == 'Superior' ? 'selected' : '' }}>Ensino Superior</option>
                            <option value="Pós-Graduação" {{ $user->escolaridade == 'Pós-Graduação' ? 'selected' : '' }}>Pós-Graduação/Mestrado/Doutorado</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Área de Interesse</label>
                         <select name="area_interesse" class="form-input">
                            <option value="">Selecione...</option>
                            <option value="Administrativa" {{ $user->area_interesse == 'Administrativa' ? 'selected' : '' }}>Administrativa</option>
                            <option value="Policial" {{ $user->area_interesse == 'Policial' ? 'selected' : '' }}>Policial (PF/PRF/PC/PM)</option>
                            <option value="Tribunais" {{ $user->area_interesse == 'Tribunais' ? 'selected' : '' }}>Tribunais (TJ/TRT/TRE/TRF)</option>
                            <option value="Fiscal" {{ $user->area_interesse == 'Fiscal' ? 'selected' : '' }}>Fiscal & Controle</option>
                             <option value="Bancária" {{ $user->area_interesse == 'Bancária' ? 'selected' : '' }}>Bancária</option>
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label>Cargos Alvo (Separados por vírgula)</label>
                        <textarea name="cargos_alvo" rows="2" class="form-input" placeholder="Ex: Técnico Judiciário, Agente Administrativo">{{ $user->cargos_alvo }}</textarea>
                    </div>
                    
                    <div class="form-group full-width">
                        <label>Biografia / Objetivo</label>
                        <textarea name="biografia" rows="3" class="form-input" placeholder="Seu objetivo profissional...">{{ $user->biografia }}</textarea>
                    </div>

                    <div class="divider full-width"></div>
                    <div class="form-group">
                         <label>Nova Senha (Opcional)</label>
                         <input type="password" name="nova_senha" class="form-input">
                    </div>
                     <div class="form-group">
                         <label>Senha Atual (Para confirmar)</label>
                         <input type="password" name="senha_atual" class="form-input">
                    </div>
                </div>

                <div class="form-footer">
                    <button type="submit" class="btn-save">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>

</div>

<style>
    /* Styling for the new Profile Hub */
    :root {
        --bg-dark: #121214;
        --card-bg: #202024;
        --primary: #FF4444;
        --text: #E1E1E6;
        --text-mute: #A8A8B3;
    }

    .main-profile-container {
        max-width: 1000px;
        padding-top: 20px;
    }

    /* Hero */
    .profile-hero {
        background: linear-gradient(180deg, rgba(32,32,36,0.8) 0%, rgba(18,18,20,0.9) 100%);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 16px;
        padding: 30px;
        backdrop-filter: blur(10px);
        margin-bottom: 25px;
    }
    .hero-content {
        display: flex;
        align-items: center;
        gap: 30px;
        flex-wrap: wrap;
    }
    .avatar-ring {
        position: relative;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: linear-gradient(45deg, var(--primary), #cc3333);
        padding: 3px;
    }
    .profile-avatar-img, .profile-avatar-default {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        object-fit: cover;
        background: #121214;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        border: 4px solid #121214;
    }
    .level-badge {
        position: absolute;
        bottom: -5px;
        left: 50%;
        transform: translateX(-50%);
        background: var(--primary);
        color: white;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 3px 10px;
        border-radius: 10px;
        white-space: nowrap;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
    }
    .user-details {
        flex: 1;
    }
    .user-name {
        font-size: 2rem;
        color: white;
        margin: 0 0 10px 0;
    }
    .user-meta {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }
    .meta-item {
        color: var(--text-mute);
        font-size: 0.9rem;
        background: rgba(255,255,255,0.05);
        padding: 5px 12px;
        border-radius: 6px;
    }
    .meta-item.link {
        color: #0077b5;
        text-decoration: none;
    }
    .xp-container {
        max-width: 400px;
    }
    .xp-info {
        display: flex;
        justify-content: space-between;
        color: var(--text-mute);
        font-size: 0.8rem;
        margin-bottom: 5px;
        text-transform: uppercase;
        font-weight: 600;
        letter-spacing: 0.5px;
    }
    .xp-bar-bg {
        height: 8px;
        background: rgba(255,255,255,0.1);
        border-radius: 4px;
        overflow: hidden;
    }
    .xp-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--primary), #ff8844);
        border-radius: 4px;
    }
    .streak-card {
        background: rgba(255, 68, 68, 0.1);
        border: 1px solid rgba(255, 68, 68, 0.2);
        padding: 15px 25px;
        border-radius: 12px;
        text-align: center;
        min-width: 100px;
    }
    .fire-icon { font-size: 1.5rem; color: #ff4444; margin-bottom: 5px; }
    .streak-number { font-size: 1.8rem; font-weight: 800; color: white; line-height: 1; }
    .streak-label { font-size: 0.75rem; color: #ff8888; text-transform: uppercase; }

    /* Quick Actions */
    .quick-actions {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }
    .action-card {
        flex: 1;
        padding: 15px;
        border-radius: 12px;
        border: none;
        color: white;
        font-weight: bold;
        font-size: 1rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        transition: transform 0.2s;
    }
    .action-card:hover { transform: translateY(-3px); }
    .action-card.primary { background: linear-gradient(135deg, var(--primary), #b32020); box-shadow: 0 4px 15px rgba(255,68,68,0.3); }
    .action-card.secondary { background: var(--card-bg); border: 1px solid rgba(255,255,255,0.1); }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 15px;
        margin-bottom: 25px;
    }
    .stat-box {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.05);
        padding: 20px;
        border-radius: 12px;
        text-align: center;
    }
    .stat-icon { font-size: 1.5rem; margin-bottom: 10px; }
    .stat-icon.yellow { color: #f1c40f; }
    .stat-icon.red { color: #e74c3c; }
    .stat-icon.blue { color: #3498db; }
    .stat-icon.green { color: #2ecc71; }
    .stat-value { font-size: 1.5rem; font-weight: 700; color: white; }
    .stat-label { font-size: 0.8rem; color: var(--text-mute); }

    /* Charts Row */
    .charts-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    @media(max-width: 768px) { .charts-row { grid-template-columns: 1fr; } }
    .chart-card {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.05);
        padding: 20px;
        border-radius: 12px;
    }
    .chart-card h3 { font-size: 1rem; color: white; margin-bottom: 20px; opacity: 0.9; }
    
    /* Activity Graph */
    .activity-graph {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        height: 150px;
    }
    .bar-col {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        flex: 1;
        height: 100%;
        justify-content: flex-end;
    }
    .bar-fill {
        width: 12px;
        background: var(--primary);
        border-radius: 10px;
        min-height: 4px;
        transition: height 0.5s ease;
    }
    .bar-label { font-size: 0.75rem; color: var(--text-mute); }

    /* Focus Subjects */
    .focus-list { display: flex; flex-direction: column; gap: 15px; }
    .focus-header { display: flex; justify-content: space-between; font-size: 0.9rem; color: var(--text); margin-bottom: 5px; }
    .focus-bar-bg { height: 6px; background: rgba(255,255,255,0.05); border-radius: 3px; }
    .focus-bar-fill { height: 100%; border-radius: 3px; }

    /* Tabs */
    .profile-tabs {
        display: flex;
        gap: 20px;
        border-bottom: 1px solid rgba(255,255,255,0.1);
        margin-bottom: 25px;
    }
    .tab-btn {
        background: none;
        border: none;
        color: var(--text-mute);
        font-size: 1rem;
        padding: 10px 5px;
        cursor: pointer;
        position: relative;
        font-weight: 500;
        transition: color 0.2s;
    }
    .tab-btn:hover { color: white; }
    .tab-btn.active { color: var(--primary); }
    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background: var(--primary);
    }
    
    /* Edit Form */
    .edit-form-card {
        background: var(--card-bg);
        padding: 30px;
        border-radius: 12px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    .full-width { grid-column: 1 / -1; }
    .form-group label { display: block; color: var(--text-mute); margin-bottom: 8px; font-size: 0.9rem; }
    .form-input {
        width: 100%;
        padding: 12px;
        background: rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: white;
        font-family: inherit;
    }
    .form-input:focus { border-color: var(--primary); outline: none; }
    .btn-save {
        width: 100%;
        margin-top: 20px;
        background: var(--primary);
        color: white;
        padding: 15px;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
    }
    .divider { height: 1px; background: rgba(255,255,255,0.1); margin: 10px 0; }
    
    /* Badges */
    .badges-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 15px;
    }
    .badge-card {
        background: var(--card-bg);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 15px;
        text-align: center;
        position: relative;
    }
    .badge-icon { font-size: 2rem; color: #f1c40f; margin-bottom: 10px; }
    .badge-card h4 { font-size: 0.85rem; color: white; margin-bottom: 5px; }
    .badge-card p { font-size: 0.7rem; color: var(--text-mute); }
    .badge-card.locked { opacity: 0.6; filter: grayscale(1); }
    .lock-overlay {
        position: absolute; top:0; left:0; width:100%; height:100%;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem; color: rgba(255,255,255,0.5);
    }
    
    /* Certificate Mini */
    .certificates-horizontal { display: flex; gap: 15px; overflow-x: auto; padding-bottom: 10px; }
    .cert-card-mini {
        min-width: 200px;
        background: rgba(46, 204, 113, 0.1);
        border: 1px solid rgba(46, 204, 113, 0.3);
        padding: 15px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .cert-card-mini i { color: #2ecc71; font-size: 1.5rem; }
    .cert-info strong { display: block; color: white; font-size: 0.9rem; }
    .cert-info small { color: #aaa; font-size: 0.75rem; }
    .btn-icon-xs { color: white; opacity: 0.7; margin-left: auto; }

    /* Avatar Upload System */
    .avatar-upload-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    .avatar-preview-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    .avatar-preview {
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 3px solid var(--primary);
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.2s;
        position: relative;
    }
    .avatar-preview:hover {
        transform: scale(1.05);
    }
    .avatar-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .avatar-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
        color: var(--text-mute);
    }
    .avatar-placeholder i {
        font-size: 3rem;
    }
    .avatar-placeholder span {
        font-size: 0.85rem;
    }
    .btn-change-avatar {
        background: var(--primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .btn-change-avatar:hover {
        background: #cc3333;
        transform: translateY(-2px);
    }

    /* Crop Modal */
    .crop-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.9);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    .crop-modal.active {
        display: flex;
    }
    .crop-container {
        background: var(--card-bg);
        border-radius: 16px;
        padding: 30px;
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow: auto;
    }
    .crop-container h3 {
        color: white;
        margin-bottom: 20px;
        text-align: center;
    }
    .crop-area {
        max-width: 100%;
        max-height: 400px;
        margin-bottom: 20px;
        background: #000;
        border-radius: 8px;
        overflow: hidden;
    }
    .crop-area img {
        max-width: 100%;
        display: block;
    }
    .crop-controls {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .crop-controls button {
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-crop-save {
        background: var(--primary);
        color: white;
    }
    .btn-crop-save:hover {
        background: #cc3333;
    }
    .btn-crop-cancel {
        background: rgba(255,255,255,0.1);
        color: white;
    }
    .btn-crop-cancel:hover {
        background: rgba(255,255,255,0.2);
    }

</style>

<!-- Crop Modal -->
<div class="crop-modal" id="crop-modal">
    <div class="crop-container">
        <h3><i class="fas fa-crop"></i> Ajustar Foto de Perfil</h3>
        <div class="crop-area">
            <img id="crop-image" src="">
        </div>
        <div class="crop-controls">
            <button type="button" class="btn-crop-save" onclick="saveCroppedImage()">
                <i class="fas fa-check"></i> Salvar
            </button>
            <button type="button" class="btn-crop-cancel" onclick="closeCropModal()">
                <i class="fas fa-times"></i> Cancelar
            </button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

<script>
    let cropper = null;
    let currentFile = null;

    // Avatar Upload Handler
    document.getElementById('avatar-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        // Validate file type
        if (!file.type.match('image.*')) {
            alert('Por favor, selecione uma imagem válida.');
            return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('A imagem deve ter no máximo 2MB.');
            return;
        }

        currentFile = file;
        const reader = new FileReader();
        
        reader.onload = function(event) {
            const cropImage = document.getElementById('crop-image');
            cropImage.src = event.target.result;
            
            // Show modal
            document.getElementById('crop-modal').classList.add('active');
            
            // Initialize cropper
            if (cropper) {
                cropper.destroy();
            }
            
            cropper = new Cropper(cropImage, {
                aspectRatio: 1,
                viewMode: 2,
                dragMode: 'move',
                autoCropArea: 1,
                restore: false,
                guides: true,
                center: true,
                highlight: false,
                cropBoxMovable: true,
                cropBoxResizable: true,
                toggleDragModeOnDblclick: false,
            });
        };
        
        reader.readAsDataURL(file);
    });

    function saveCroppedImage() {
        if (!cropper) return;
        
        // Get cropped canvas
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            imageSmoothingQuality: 'high'
        });
        
        // Convert to blob and update preview
        canvas.toBlob(function(blob) {
            const url = URL.createObjectURL(blob);
            
            // Update preview
            const preview = document.getElementById('avatar-preview');
            preview.innerHTML = '<img src="' + url + '" alt="Avatar">';
            
            // Store base64 for upload
            canvas.toBlob(function(blob) {
                const reader = new FileReader();
                reader.onloadend = function() {
                    document.getElementById('cropped-avatar').value = reader.result;
                };
                reader.readAsDataURL(blob);
            }, 'image/jpeg', 0.9);
            
            closeCropModal();
        }, 'image/jpeg', 0.9);
    }

    function closeCropModal() {
        document.getElementById('crop-modal').classList.remove('active');
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        document.getElementById('avatar-input').value = '';
    }

    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('.tab-btn').forEach(el => el.classList.remove('active'));
        
        document.getElementById('tab-' + tabId).style.display = 'block';
        document.getElementById('btn-' + tabId).classList.add('active');
    }
</script>
@endsection
