@extends('layouts.app')

@section('title', 'Planos e Preços')

@section('content')
<style>
    .pricing-container {
        padding: 40px;
        max-width: 1300px;
        margin: 0 auto;
    }

    .pricing-header {
        text-align: center;
        margin-bottom: 60px;
    }

    .pricing-header h1 {
        font-size: 3rem;
        font-weight: 800;
        margin-bottom: 15px;
        background: linear-gradient(135deg, #fff 30%, var(--primary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .pricing-header p {
        color: rgba(255, 255, 255, 0.6);
        font-size: 1.2rem;
    }

    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        align-items: center;
    }

    .pricing-card {
        background: var(--glass-bg);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--glass-border);
        border-radius: 24px;
        padding: 40px;
        position: relative;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .pricing-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 16px 48px 0 rgba(255, 68, 68, 0.15);
        border-color: rgba(255, 68, 68, 0.3);
    }

    /* Popular / Highlight Card */
    .pricing-card.highlight {
        background: linear-gradient(180deg, rgba(255, 68, 68, 0.1), rgba(0, 0, 0, 0.3));
        border-color: rgba(255, 68, 68, 0.4);
        transform: scale(1.05); /* Ligeiramente maior */
        box-shadow: 0 0 40px rgba(255, 68, 68, 0.15);
        z-index: 10;
    }

    .pricing-card.highlight:hover {
        transform: scale(1.05) translateY(-10px);
    }

    .popular-badge {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, var(--primary-color), #cc0000);
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(255, 68, 68, 0.4);
    }

    .plan-name {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--dash-text-primary, white);
    }

    .plan-price {
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--dash-text-primary, white);
        margin-bottom: 20px;
        display: flex;
        align-items: baseline;
    }

    .plan-price span.currency {
        font-size: 1.5rem;
        font-weight: 600;
        color: rgba(255,255,255,0.6);
        margin-right: 5px;
    }

    .plan-price span.period {
        font-size: 1rem;
        font-weight: 500;
        color: rgba(255,255,255,0.5);
        margin-left: 5px;
    }

    .plan-features {
        list-style: none;
        padding: 0;
        margin: 0 0 40px 0;
        flex: 1; /* Preenche o espaço p empurrar botão pra baixo */
    }

    .plan-features li {
        margin-bottom: 15px;
        color: rgba(255,255,255,0.8);
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1rem;
    }

    .plan-features li i {
        font-size: 1.1rem;
    }

    .plan-features li .fa-check-circle {
        color: #2ecc71; /* Verde pros ativos */
    }

    .plan-features li .fa-times-circle {
        color: rgba(255,255,255,0.2); /* Cinza pros inativos */
    }
    
    .plan-features li.inactive {
        color: rgba(255,255,255,0.4);
        text-decoration: line-through;
    }

    .btn-pricing {
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.1rem;
        text-align: center;
        text-decoration: none;
        transition: 0.3s;
        display: block;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--dash-border, rgba(255, 255, 255, 0.2));
        color: var(--dash-text-primary, white);
    }

    .btn-outline:hover {
        border-color: var(--dash-text-primary, white);
        background: var(--dash-border, rgba(255, 255, 255, 0.05));
    }

    .btn-primary-gradient {
        background: linear-gradient(135deg, var(--primary-color), #cc0000);
        color: white;
        border: none;
        box-shadow: 0 8px 20px rgba(255, 68, 68, 0.3);
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(255, 68, 68, 0.5);
    }

    @media (max-width: 1024px) {
        .pricing-grid {
            grid-template-columns: 1fr;
            gap: 40px;
        }
        .pricing-card.highlight {
            transform: scale(1); /* Tira o efeito de Pop no celular */
        }
        .pricing-card.highlight:hover {
            transform: translateY(-10px);
        }
    }
</style>
<div class="pricing-page-wrapper" style="position: relative; overflow: hidden; padding-bottom: 100px;">
    
    <!-- Efeitos de Fundo (Blur/Glow) -->
    <div style="position: absolute; top: -10%; left: -10%; width: 50%; height: 50%; background: radial-gradient(circle, rgba(255, 68, 68, 0.15) 0%, transparent 60%); z-index: 0; pointer-events: none;"></div>
    <div style="position: absolute; bottom: 10%; right: -10%; width: 50%; height: 50%; background: radial-gradient(circle, rgba(204, 0, 0, 0.1) 0%, transparent 60%); z-index: 0; pointer-events: none;"></div>

    <div class="pricing-container" style="position: relative; z-index: 2;">
        
        <div class="pricing-header" style="margin-top: 40px;">
            <div class="hero-badge" style="display: inline-block; padding: 6px 16px; border-radius: 100px; background: rgba(255, 68, 68, 0.1); border: 1px solid rgba(255, 68, 68, 0.3); color: var(--primary-color); font-weight: 600; margin-bottom: 20px; font-size: 0.9rem;">
                <i class="fas fa-star" style="margin-right: 5px;"></i> Invista no Seu Futuro
            </div>
            <h1>Escolha o seu <span style="background: linear-gradient(135deg, #fff 30%, var(--primary-color) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Nível de Aprovação</span></h1>
            <p style="max-width: 600px; margin: 0 auto;">Pare de perder tempo com materiais desatualizados. Estude com a nossa inteligência artificial e conquiste sua vaga mais rápido.</p>
        </div>

        <div class="pricing-grid">
            @foreach($planos as $plano)
                <div class="pricing-card {{ $plano['destaque'] ? 'highlight' : '' }}">
                    @if($plano['destaque'])
                        <div class="popular-badge">Mais Escolhido</div>
                    @endif
                    
                    <div class="plan-name">{{ $plano['nome'] }}</div>
                    
                    <div class="plan-price">
                        <span class="currency">R$</span>
                        {{ explode(',', $plano['preco'])[0] }},<span style="font-size:1.5rem">{{ explode(',', $plano['preco'])[1] ?? '00' }}</span>
                        <span class="period">{{ $plano['periodo'] }}</span>
                    </div>

                    <ul class="plan-features">
                        @foreach($plano['recursos'] as $recurso)
                            <li class="{{ !$recurso['ativo'] ? 'inactive' : '' }}">
                                <i class="{{ $recurso['ativo'] ? 'fas fa-check-circle' : 'fas fa-times-circle' }}"></i>
                                {{ $recurso['nome'] }}
                            </li>
                        @endforeach
                    </ul>

                    <a href="#" class="btn-pricing {{ $plano['destaque'] ? 'btn-primary-gradient' : 'btn-outline' }}">
                        {{ $plano['cta'] }}
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Section: Benefícios Extras Visuais -->
        <div class="features-highlight" style="margin-top: 100px; text-align: center;">
            <h2 style="font-size: 2.2rem; font-weight: 800; margin-bottom: 50px;">Por que ser Premium?</h2>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px;">
                <!-- Feature Box 1 -->
                <div style="background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); padding: 30px; border-radius: 20px;">
                    <i class="fas fa-robot" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 20px;"></i>
                    <h3 style="font-size: 1.3rem; margin-bottom: 10px;">Inteligência no Edital</h3>
                    <p style="color: var(--dash-text-secondary, rgba(255,255,255,0.6)); font-size: 0.95rem;">Nossa IA analisa seu edital e gera cronogramas e simulados com probabilidade de cair na sua prova.</p>
                </div>
                
                <!-- Feature Box 2 -->
                <div style="background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); padding: 30px; border-radius: 20px;">
                    <i class="fas fa-chart-line" style="font-size: 2.5rem; color: #3498db; margin-bottom: 20px;"></i>
                    <h3 style="font-size: 1.3rem; margin-bottom: 10px;">Métricas Avançadas</h3>
                    <p style="color: var(--dash-text-secondary, rgba(255,255,255,0.6)); font-size: 0.95rem;">Descubra suas fraquezas por disciplina e assunt, compare-se aos concorrentes e evolua.</p>
                </div>

                <!-- Feature Box 3 -->
                <div style="background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); padding: 30px; border-radius: 20px;">
                    <i class="fas fa-trophy" style="font-size: 2.5rem; color: #f1c40f; margin-bottom: 20px;"></i>
                    <h3 style="font-size: 1.3rem; margin-bottom: 10px;">Gamificação Exclusiva</h3>
                    <p style="color: var(--dash-text-secondary, rgba(255,255,255,0.6)); font-size: 0.95rem;">Ganhe o dobro de XP, desbloqueie mascotes novos e domine o topo do ranking mensal com sua dedicação.</p>
                </div>
            </div>
        </div>

        <!-- Section: FAQ Simples -->
        <div class="faq-section" style="margin-top: 100px; max-width: 800px; margin-left: auto; margin-right: auto; padding-bottom: 40px;">
            <h2 style="font-size: 2.2rem; font-weight: 800; text-align: center; margin-bottom: 40px;">Dúvidas Frequentes</h2>

            <div style="background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); border-radius: 16px; padding: 25px; margin-bottom: 20px;">
                <h4 style="font-size: 1.1rem; margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-question-circle" style="color: var(--primary-color);"></i> Posso cancelar a qualquer momento?
                </h4>
                <p style="color: var(--dash-text-secondary, rgba(255,255,255,0.6)); margin: 0; font-size: 0.95rem; padding-left: 26px;">Sim. Não temos fidelidade. Você gerencia sua assinatura no painel "Meu Plano" e pode pausar ou cancelar quando desejar, com um único clique.</p>
            </div>

            <div style="background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); border-radius: 16px; padding: 25px; margin-bottom: 20px;">
                <h4 style="font-size: 1.1rem; margin-bottom: 10px; display: flex; align-items: center; gap: 10px;">
                    <i class="fas fa-question-circle" style="color: var(--primary-color);"></i> Como funciona a garantia?
                </h4>
                <p style="color: var(--dash-text-secondary, rgba(255,255,255,0.6)); margin: 0; font-size: 0.95rem; padding-left: 26px;">Nós confiamos tanto em nosso material e metodologia que você tem 7 dias de garantia incondicional no primeiro pagamento para pedir reembolso 100%.</p>
            </div>
            
        </div>

    </div>
</div>
@endsection
