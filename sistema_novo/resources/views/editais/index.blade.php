@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-file-alt"></i> Meus Editais</h1>
            <div class="user-info">
                <a href="{{ route('dashboard') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </a>
                <a href="{{ route('editais.create') }}" class="action-btn">
                    <i class="fas fa-plus"></i>
                    <span>Novo Edital</span>
                </a>
            </div>
        </div>
    </header>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Estatísticas -->
    <section class="stats-section">
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalEditais }}</h3>
                    <p>Editais Enviados</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalDisciplinas }}</h3>
                    <p>Disciplinas Detectadas</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $totalQuestoes }}</h3>
                    <p>Questões Geradas</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Lista de Editais -->
    <section class="editais-section">
        <div class="card">
            <h2><i class="fas fa-list"></i> Editais Analisados</h2>
            
            @if(count($editais) === 0)
                <div class="empty-state">
                    <i class="fas fa-file-pdf"></i>
                    <h3>Nenhum edital enviado ainda</h3>
                    <p>Envie seu primeiro edital para começar a análise automática!</p>
                    <a href="{{ route('editais.create') }}" class="btn-primary">
                        <i class="fas fa-upload"></i> Enviar Primeiro Edital
                    </a>
                </div>
            @else
                <div class="editais-grid">
                    @foreach($editais as $edital)
                        <div class="edital-card">
                            <div class="edital-header">
                                <h3>{{ $edital->nome_arquivo }}</h3>
                                <span class="edital-date">
                                    {{ \Carbon\Carbon::parse($edital->data_upload)->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            
                            <div class="edital-stats">
                                <div class="stat">
                                    <i class="fas fa-graduation-cap"></i>
                                    <span>{{ $edital->disciplinas_count }} disciplinas</span>
                                </div>
                                
                                <div class="stat">
                                    <i class="fas fa-question-circle"></i>
                                    <span>{{ $edital->questoes_count }} questões</span>
                                </div>
                            </div>
                            
                            <div class="edital-actions">
                                <a href="{{ route('editais.show', $edital->id) }}" class="btn-primary">
                                    <i class="fas fa-eye"></i> Ver Detalhes
                                </a>
                                <a href="{{ route('questoes.index', ['edital_id' => $edital->id]) }}" class="btn-secondary">
                                    <i class="fas fa-question-circle"></i> Questões
                                </a>
                                <form action="{{ route('editais.destroy', $edital->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este edital? Todas as disciplinas e questões vinculadas serão removidas.')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-danger" style="background: #dc3545; color: white; padding: 12px 20px; border: none; border-radius: 10px; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; font-size: 0.9rem;">
                                        <i class="fas fa-trash-alt"></i> Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>

<style>
    /* Copiando estilos específicos da página legacy se não estiverem no global */
    .stats-section {
        margin-bottom: 30px;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    
    .stat-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        border: 1px solid var(--border-color);
        border-radius: 15px;
        padding: 25px;
        display: flex;
        align-items: center;
        gap: 20px;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        transition: all 0.3s ease;
        color: var(--text-primary);
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        background: var(--bg-card-hover);
        box-shadow: var(--shadow-md);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        background: linear-gradient(45deg, #ff4444, #cc0000);
        border-radius: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-icon i {
        font-size: 1.5rem;
        color: white;
    }
    
    .stat-content h3 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
    }
    
    .stat-content p {
        color: var(--text-secondary);
        margin: 5px 0 0 0;
        font-weight: 500;
    }
    
    .editais-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }
    
    .edital-card {
        background: var(--bg-card);
        backdrop-filter: blur(20px);
        border: 1px solid var(--border-color);
        border-radius: 15px;
        padding: 25px;
        transition: all 0.3s ease;
        color: var(--text-primary);
    }
    
    .edital-card:hover {
        transform: translateY(-5px);
        background: var(--bg-card-hover);
        box-shadow: var(--shadow-md);
        border-color: var(--primary-color);
    }
    
    .edital-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    
    .edital-header h3 {
        color: var(--text-primary);
        margin: 0;
        font-size: 1.2rem;
        flex: 1;
        margin-right: 15px;
    }
    
    .edital-date {
        color: var(--text-secondary);
        font-size: 0.9rem;
        white-space: nowrap;
    }
    
    .edital-stats {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-bottom: 20px;
    }
    
    .stat {
        display: flex;
        align-items: center;
        gap: 10px;
        color: var(--text-secondary);
        font-size: 0.95rem;
    }
    
    .stat i {
        color: var(--primary-color);
        font-size: 1.1rem;
    }
    
    .edital-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: auto;
    }

    .edital-actions .btn-primary,
    .edital-actions .btn-secondary,
    .edital-actions .btn-danger,
    .edital-actions button {
        flex: 1;
        min-width: 120px;
        justify-content: center;
        padding: 10px 15px;
        border: none;
        border-radius: 8px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: all 0.2s ease;
        font-size: 0.9rem;
        cursor: pointer;
        height: 40px;
        color: white;
    }

    .edital-actions form {
        flex: 1;
        display: flex;
        min-width: 120px;
    }
    
    .edital-actions form button {
        width: 100%;
    }

    .btn-primary {
        background: linear-gradient(45deg, #ff4444, #cc0000);
    }

    .btn-secondary {
        background: #6c757d;
    }
    
    .btn-danger {
        background: #dc3545;
    }
    
    .edital-actions a:hover,
    .edital-actions button:hover {
        transform: translateY(-2px);
        filter: brightness(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: var(--text-secondary);
    }
    
    .empty-state i {
        font-size: 4rem;
        margin-bottom: 20px;
        color: rgba(255, 255, 255, 0.6);
    }
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #FF4444 0%, #CC0000 100%);
            --card-gradient: linear-gradient(145deg, rgba(30, 30, 30, 0.9), rgba(20, 20, 20, 0.95));
            --hover-gradient: linear-gradient(145deg, rgba(40, 40, 40, 0.95), rgba(30, 30, 30, 1));
            --border-color: rgba(255, 255, 255, 0.08);
            --text-primary: #EEEEEE;
            --text-secondary: #AAAAAA;
            --shadow-sm: 0 4px 6px rgba(0, 0, 0, 0.2);
            --shadow-md: 0 8px 15px rgba(0, 0, 0, 0.3);
            --glow: 0 0 20px rgba(255, 68, 68, 0.15);
            --btn-secondary-bg: rgba(255, 255, 255, 0.1);
            --btn-secondary-border: rgba(255, 255, 255, 0.1);
            --btn-secondary-text: white;
            --btn-secondary-hover: rgba(255, 255, 255, 0.2);
        }

        body.light-mode {
            --card-gradient: linear-gradient(145deg, #ffffff, #f0f0f0);
            --hover-gradient: linear-gradient(145deg, #f8f9fa, #e9ecef);
            --border-color: rgba(0, 0, 0, 0.1);
            --text-primary: #2c3e50;
            --text-secondary: #596275;
            --shadow-sm: 0 4px 6px rgba(0,0,0,0.05);
            --shadow-md: 0 8px 15px rgba(0,0,0,0.1);
            --glow: 0 0 15px rgba(255, 68, 68, 0.1);
            --btn-secondary-bg: #e2e6ea;
            --btn-secondary-border: #dae0e5;
            --btn-secondary-text: #2c3e50;
            --btn-secondary-hover: #d3d9e0;
        }

        body {
            color: var(--text-primary);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .stats-section {
            margin-bottom: 40px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }
        
        .stat-card {
            background: var(--card-gradient);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 30px;
            display: flex;
            align-items: center;
            gap: 25px;
            box-shadow: var(--shadow-sm);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: var(--primary-gradient);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px) scale(1.02);
            background: var(--hover-gradient);
            box-shadow: var(--shadow-md), var(--glow);
            border-color: rgba(255, 68, 68, 0.3);
        }

        .stat-card:hover::before {
            opacity: 1;
        }
        
        .stat-icon {
            width: 65px;
            height: 65px;
            background: rgba(255, 68, 68, 0.1);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }
        
        .stat-card:hover .stat-icon {
            background: var(--primary-gradient);
            transform: rotate(5deg);
        }

        .stat-card:hover .stat-icon i {
            color: white;
            transform: scale(1.1);
        }
        
        .stat-icon i {
            font-size: 1.8rem;
            color: #ff4444;
            transition: all 0.3s ease;
        }
        
        .stat-content h3 {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin: 0;
            line-height: 1.1;
            letter-spacing: -0.5px;
        }
        
        .stat-content p {
            color: var(--text-secondary);
            margin: 8px 0 0 0;
            font-weight: 500;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .card {
            background: transparent;
            box-shadow: none;
            border: none;
            padding: 0;
            margin-bottom: 30px;
        }

        .card h2 {
            font-size: 1.8rem;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 15px;
            color: var(--text-primary);
        }

        .editais-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 30px;
            margin-top: 20px;
        }
        
        .edital-card {
            background: var(--card-gradient);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 30px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
            position: relative;
        }
        
        .edital-card:hover {
            transform: translateY(-8px);
            background: var(--hover-gradient);
            box-shadow: var(--shadow-md), var(--glow);
            border-color: rgba(255, 68, 68, 0.3);
            z-index: 10;
        }
        
        .edital-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .edital-header h3 {
            color: white;
            margin: 0;
            font-size: 1.3rem;
            font-weight: 700;
            flex: 1;
            margin-right: 20px;
            line-height: 1.4;
        }
        
        .edital-date {
            color: var(--text-secondary);
            font-size: 0.85rem;
            padding: 6px 12px;
            background: rgba(255,255,255,0.05);
            border-radius: 20px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .edital-date::before {
            content: '\f017';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            font-size: 0.8rem;
        }
        
        .edital-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--text-secondary);
            font-size: 0.95rem;
            padding: 10px;
            background: rgba(255,255,255,0.02);
            border-radius: 12px;
            transition: all 0.3s ease;
        }

        .edital-card:hover .stat {
            background: rgba(255,255,255,0.05);
        }
        
        .stat i {
            color: #ff4444;
            font-size: 1.2rem;
            opacity: 0.8;
        }
        
        .edital-actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: auto;
        }

        .edital-actions .btn-primary,
        .edital-actions .btn-secondary,
        .edital-actions .btn-danger,
        .edital-actions button {
            flex: 1;
            min-width: 100px;
            justify-content: center;
            padding: 12px 20px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            height: 45px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: white;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        
        .btn-primary {
            background: var(--primary-gradient);
            box-shadow: 0 4px 15px rgba(255, 68, 68, 0.3);
        }
        
        .btn-secondary {
            background: var(--btn-secondary-bg);
            border: 1px solid var(--btn-secondary-border);
            color: var(--btn-secondary-text);
        }

        .btn-secondary:hover {
            background: var(--btn-secondary-hover);
            border-color: var(--btn-secondary-hover);
            color: var(--btn-secondary-text);
        }
        
        .btn-danger {
            background: rgba(220, 53, 69, 0.15) !important;
            color: #ff6b6b !important;
            border: 1px solid rgba(220, 53, 69, 0.3) !important;
        }
        
        body.light-mode .btn-danger {
            background: rgba(220, 53, 69, 0.1) !important;
            color: #dc3545 !important;
            border-color: rgba(220, 53, 69, 0.2) !important;
        }

        .btn-danger:hover {
            background: #dc3545 !important;
            color: white !important;
            border-color: #dc3545 !important;
            box-shadow: 0 4px 15px rgba(220, 53, 69, 0.4);
        }
        
        body.light-mode .btn-danger:hover {
            background: #dc3545 !important;
            border-color: #dc3545 !important;
        }

        .edital-actions form {
            flex: 1;
            display: flex;
            min-width: 100px;
        }
        
        .edital-actions form button {
            width: 100%;
        }

        .edital-actions a:hover,
        .edital-actions button:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }
        
        .action-btn {
            background: var(--btn-secondary-bg);
            color: var(--btn-secondary-text);
            padding: 12px 24px;
            border: 1px solid var(--btn-secondary-border);
            border-radius: 12px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .action-btn:hover {
            background: var(--btn-secondary-hover);
            border-color: var(--btn-secondary-hover);
            transform: translateY(-2px);
        }
        
        .empty-state {
            background: var(--card-gradient);
            border: 2px dashed var(--border-color);
            border-radius: 20px;
            padding: 80px 20px;
            text-align: center;
        }

        .empty-state i {
            color: var(--text-secondary);
            opacity: 0.3;
            margin-bottom: 25px;
            display: block;
        }

        .empty-state h3 {
            color: var(--text-primary);
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
    </style>
@endsection
