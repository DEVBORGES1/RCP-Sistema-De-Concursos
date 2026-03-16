@extends('layouts.app')

@section('title', 'Meu Plano')

@section('content')
<style>
    .plan-dashboard {
        padding: 20px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .plan-header {
        margin-bottom: 30px;
    }

    .plan-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--dash-text-primary, white);
    }
    
    .plan-header p {
        color: var(--dash-text-secondary, rgba(255,255,255,0.6));
    }

    .card-plano {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 20px;
        padding: 30px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
    }

    /* Borda superior colorida baseada no plano. Se fosse premium, mudaria aqui. */
    .card-plano::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 5px;
        background: {{ $planoAtual['nome'] == 'Gratuito' ? 'linear-gradient(90deg, #333, #666)' : 'linear-gradient(90deg, var(--primary-color), #cc0000)' }};
    }

    .status-badge {
        display: inline-block;
        padding: 5px 12px;
        border-radius: 100px;
        background: rgba(46, 204, 113, 0.2);
        color: #2ecc71;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        margin-bottom: 15px;
    }

    .plano-info {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .plano-detalhes h3 {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0 0 5px 0;
        color: var(--dash-text-primary, white);
    }

    .plano-detalhes p {
        color: var(--dash-text-secondary, rgba(255,255,255,0.5));
        margin: 0;
    }

    .plano-actions {
        display: flex;
        gap: 15px;
    }

    .btn-upgrade {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        color: white;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
        border: none;
        cursor: pointer;
    }

    .btn-upgrade:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(255, 68, 68, 0.4);
    }

    .btn-secondary {
        background: var(--dash-bg-card, rgba(255,255,255,0.05));
        color: var(--dash-text-primary, white);
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
        border: 1px solid var(--dash-border, rgba(255,255,255,0.1));
    }

    .btn-secondary:hover {
        background: var(--dash-border, rgba(255,255,255,0.1));
    }

    /* Consumo Grid */
    .consumo-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .consumo-box {
        background: rgba(0,0,0,0.2);
        border: 1px solid rgba(255,255,255,0.05);
        border-radius: 12px;
        padding: 20px;
    }

    .consumo-box h4 {
        margin: 0 0 15px 0;
        color: var(--dash-text-primary, white);
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .consumo-box h4 i {
        color: var(--primary-color);
    }

    .progress-info {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: rgba(255,255,255,0.7);
    }

    .progress-bar {
        height: 8px;
        background: rgba(255,255,255,0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .progress-fill.safe { background: #2ecc71; }
    .progress-fill.warning { background: #f1c40f; }
    .progress-fill.danger { background: #e74c3c; }

    @media (max-width: 768px) {
        .plano-info { flex-direction: column; align-items: flex-start; gap: 20px; }
        .plano-actions { width: 100%; flex-direction: column; }
        .btn-upgrade, .btn-secondary { width: 100%; text-align: center; }
        .consumo-grid { grid-template-columns: 1fr; }
    }
</style>

<div class="plan-dashboard">
    <div class="plan-header">
        <h2>Assinatura e Uso</h2>
        <p>Acompanhe o limite do seu plano e gerencie o faturamento desta conta.</p>
    </div>

    <!-- Informações do Plano -->
    <div class="card-plano">
        <span class="status-badge"><i class="fas fa-check-circle"></i> {{ $planoAtual['status'] }}</span>
        
        <div class="plano-info">
            <div class="plano-detalhes">
                <h3>Plano {{ $planoAtual['nome'] }}</h3>
                @if($planoAtual['renovacao'])
                    <p>Próxima renovação em {{ $planoAtual['renovacao'] }}</p>
                @else
                    <p>Você não possui assinaturas de cobrança recorrente ativas.</p>
                @endif
            </div>

            <div class="plano-actions">
                @if($planoAtual['nome'] == 'Gratuito')
                    <a href="{{ route('planos') }}" class="btn-upgrade"><i class="fas fa-bolt"></i> Fazer Upgrade (Pro)</a>
                @else
                    <a href="#" class="btn-secondary"><i class="fas fa-credit-card"></i> Gerenciar Pagamento</a>
                @endif
            </div>
        </div>

        <hr style="border-color: rgba(255,255,255,0.05); margin: 30px 0;">

        <!-- Consumo do Mês -->
        <h3 style="margin-bottom: 20px; font-size: 1.2rem; color: var(--dash-text-primary, white);">Consumo deste mês</h3>
        <div class="consumo-grid">
            
            <!-- Box Questões -->
            <div class="consumo-box">
                <h4><i class="fas fa-brain"></i> Questões Resolvidas</h4>
                
                @php
                    $qUsadas = $planoAtual['questoes_usadas'];
                    $qLimite = $planoAtual['questoes_limite'];
                    
                    // Se for ilimitado (Premium), o mock pode ter mandado -1 ou null. Tratar aqui caso exista a lógica.
                    $porcentagemQ = $qLimite > 0 ? ($qUsadas / $qLimite) * 100 : 0;
                    $classQ = $porcentagemQ > 80 ? 'danger' : ($porcentagemQ > 50 ? 'warning' : 'safe');
                @endphp

                <div class="progress-info">
                    <span>{{ $qUsadas }} de {{ $qLimite == -1 ? 'Ilimitado' : $qLimite }} acessos</span>
                    <span>{{ round($porcentagemQ) }}%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill {{ $classQ }}" style="width: {{ $porcentagemQ }}%;"></div>
                </div>
                @if($porcentagemQ >= 100)
                    <p style="color: #e74c3c; font-size: 0.85rem; margin-top: 10px; margin-bottom:0;"><i class="fas fa-exclamation-triangle"></i> Você atingiu o limite do seu plano.</p>
                @endif
            </div>

            <!-- Box Simulados -->
            <div class="consumo-box">
                <h4><i class="fas fa-laptop-code"></i> Simulados Gerados</h4>
                
                @php
                    $sUsados = $planoAtual['simulados_usados'];
                    $sLimite = $planoAtual['simulados_limite'];
                    $porcentagemS = $sLimite > 0 ? ($sUsados / $sLimite) * 100 : 0;
                    $classS = $porcentagemS > 80 ? 'danger' : ($porcentagemS > 50 ? 'warning' : 'safe');
                @endphp

                <div class="progress-info">
                    <span>{{ $sUsados }} de {{ $sLimite == -1 ? 'Ilimitado' : $sLimite }} acessos</span>
                    <span>{{ round($porcentagemS) }}%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-fill {{ $classS }}" style="width: {{ $porcentagemS }}%;"></div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
