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
        font-family: 'Syne', sans-serif;
        font-size: 3.5rem;
        font-weight: 800;
        letter-spacing: -1.5px;
        margin-bottom: 15px;
        background: linear-gradient(135deg, var(--dash-text-primary, #fff) 30%, var(--primary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .pricing-header p {
        color: var(--dash-text-secondary, rgba(255, 255, 255, 0.6));
        font-size: 1.15rem;
        line-height: 1.6;
    }

    .pricing-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        align-items: center;
    }

    .pricing-card {
        background: var(--dash-bg-card, rgba(255,255,255,0.03));
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid var(--dash-border, rgba(255,255,255,0.08));
        border-radius: 24px;
        padding: 40px;
        position: relative;
        transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    body.light-mode .pricing-card { box-shadow: 0 4px 15px rgba(0,0,0,0.03); }

    .pricing-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        border-color: rgba(124, 58, 237, 0.3); /* Violet Hover Border */
    }

    /* Popular / Highlight Card */
    .pricing-card.highlight {
        background: linear-gradient(180deg, rgba(124, 58, 237, 0.1), var(--dash-bg-card, rgba(0,0,0,0.3)));
        border-color: rgba(124, 58, 237, 0.4);
        transform: scale(1.05); /* Ligeiramente maior */
        box-shadow: 0 0 40px rgba(124, 58, 237, 0.15);
        z-index: 10;
        border-top: 2px solid var(--primary-color);
    }

    .pricing-card.highlight:hover {
        transform: scale(1.05) translateY(-8px);
        box-shadow: 0 20px 50px rgba(124, 58, 237, 0.25);
    }

    .popular-badge {
        position: absolute;
        top: -15px;
        left: 50%;
        transform: translateX(-50%);
        background: linear-gradient(135deg, var(--primary-color), #4F46E5); /* Violet to Indigo */
        color: white;
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        box-shadow: 0 4px 15px rgba(124, 58, 237, 0.4);
    }

    .plan-name {
        font-family: 'Syne', sans-serif;
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 10px;
        color: var(--dash-text-primary, white);
        letter-spacing: -0.5px;
    }

    .plan-price {
        font-family: 'Syne', sans-serif;
        font-size: 3.5rem;
        font-weight: 800;
        color: var(--dash-text-primary, white);
        margin-bottom: 25px;
        display: flex;
        align-items: baseline;
        letter-spacing: -1px;
    }

    .plan-price span.currency {
        font-family: 'Outfit', sans-serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--dash-text-secondary, rgba(255,255,255,0.6));
        margin-right: 5px;
        letter-spacing: 0;
    }

    .plan-price span.period {
        font-family: 'Outfit', sans-serif;
        font-size: 1rem;
        font-weight: 500;
        color: var(--dash-text-secondary, rgba(255,255,255,0.5));
        margin-left: 5px;
        letter-spacing: 0;
    }

    .plan-features {
        list-style: none;
        padding: 0;
        margin: 0 0 40px 0;
        flex: 1; /* Preenche o espaço p empurrar botão pra baixo */
    }

    .plan-features li {
        margin-bottom: 15px;
        color: var(--dash-text-primary, rgba(255,255,255,0.9));
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.05rem;
    }

    .plan-features li i {
        font-size: 1.1rem;
    }

    .plan-features li .fa-check-circle {
        color: var(--primary-color, #7C3AED); /* Primary color for active */
    }

    .plan-features li .fa-times-circle {
        color: var(--dash-text-secondary, rgba(255,255,255,0.2)); /* Cinza pros inativos */
    }
    
    .plan-features li.inactive {
        color: var(--dash-text-secondary, rgba(255,255,255,0.4));
        text-decoration: line-through;
    }

    .btn-pricing {
        width: 100%;
        padding: 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 1.05rem;
        text-align: center;
        text-decoration: none;
        transition: 0.3s;
        display: block;
        font-family: 'Outfit', sans-serif;
    }

    .btn-outline {
        background: transparent;
        border: 2px solid var(--dash-border, rgba(255, 255, 255, 0.15));
        color: var(--dash-text-primary, white);
    }

    .btn-outline:hover {
        border-color: var(--primary-color);
        background: rgba(124, 58, 237, 0.05); /* very light violet background on hover */
        color: var(--primary-color);
    }

    .btn-primary-gradient {
        background: linear-gradient(135deg, var(--primary-color), #4F46E5); /* Violet to Indigo CTA */
        color: white;
        border: none;
        box-shadow: 0 8px 20px rgba(124, 58, 237, 0.3);
    }

    .btn-primary-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 25px rgba(124, 58, 237, 0.5);
        color: white;
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
        .pricing-header h1 { font-size: 2.5rem; }
    }
</style>
<div class="pricing-page-wrapper" style="position: relative; overflow: hidden; padding-bottom: 100px;">
    
    <!-- Efeitos de Fundo (Blur/Glow) Cosmic Mesh -->
    <div style="position: absolute; top: -10%; left: -10%; width: 50vw; height: 50vh; background: radial-gradient(circle, rgba(124, 58, 237, 0.12) 0%, transparent 60%); filter: blur(60px); z-index: 0; pointer-events: none;"></div>
    <div style="position: absolute; bottom: 10%; right: -10%; width: 50vw; height: 50vh; background: radial-gradient(circle, rgba(79, 70, 229, 0.1) 0%, transparent 60%); filter: blur(60px); z-index: 0; pointer-events: none;"></div>

    <div class="pricing-container" style="position: relative; z-index: 2;">
        
        <!-- Botão Voltar -->
        <div style="margin-bottom: 20px;">
            <a href="{{ url('/') }}" class="btn-outline" style="display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 12px; font-weight: 600; text-decoration: none; width: auto;">
                <i class="fas fa-arrow-left"></i> Voltar ao Início
            </a>
        </div>

        <div class="pricing-header" style="margin-top: 20px;">
            <div class="hero-badge" style="display: inline-block; padding: 8px 18px; border-radius: 100px; background: rgba(124, 58, 237, 0.1); border: 1px solid rgba(124, 58, 237, 0.3); color: var(--primary-color, #7C3AED); font-weight: 600; margin-bottom: 25px; font-size: 0.9rem; letter-spacing: 0.5px;">
                <i class="fas fa-star" style="margin-right: 5px;"></i> Invista no Seu Futuro
            </div>
            <h1>Escolha o seu <span style="background: linear-gradient(135deg, var(--dash-text-primary, #fff) 30%, var(--primary-color) 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Nível de Aprovação</span></h1>
            <p style="max-width: 650px; margin: 0 auto;">Pare de perder tempo com materiais desatualizados. Estude com a nossa inteligência artificial e conquiste sua vaga mais rápido.</p>
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
                <div style="background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); padding: 35px; border-radius: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.3s; cursor: default;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-robot" style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 25px; background: rgba(124, 58, 237, 0.1); padding: 15px; border-radius: 12px;"></i>
                    <h3 style="font-family: 'Syne', sans-serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 12px; color: var(--dash-text-primary);">Inteligência no Edital</h3>
                    <p style="color: var(--dash-text-secondary, rgba(255,255,255,0.6)); font-size: 1rem; line-height: 1.6;">Nossa IA analisa seu edital e gera cronogramas e simulados com probabilidade real de cair na sua prova.</p>
                </div>
                
                <!-- Feature Box 2 -->
                <div style="background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); padding: 35px; border-radius: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.3s; cursor: default;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-chart-line" style="font-size: 2.5rem; color: #3B82F6; margin-bottom: 25px; background: rgba(59, 130, 246, 0.1); padding: 15px; border-radius: 12px;"></i>
                    <h3 style="font-family: 'Syne', sans-serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 12px; color: var(--dash-text-primary);">Métricas Avançadas</h3>
                    <p style="color: var(--dash-text-secondary, rgba(255,255,255,0.6)); font-size: 1rem; line-height: 1.6;">Descubra suas fraquezas por disciplina e assunto, compare-se aos principais concorrentes e evolua.</p>
                </div>

                <!-- Feature Box 3 -->
                <div style="background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); padding: 35px; border-radius: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); transition: transform 0.3s; cursor: default;" onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                    <i class="fas fa-trophy" style="font-size: 2.5rem; color: #F59E0B; margin-bottom: 25px; background: rgba(245, 158, 11, 0.1); padding: 15px; border-radius: 12px;"></i>
                    <h3 style="font-family: 'Syne', sans-serif; font-size: 1.4rem; font-weight: 700; margin-bottom: 12px; color: var(--dash-text-primary);">Gamificação Exclusiva</h3>
                    <p style="color: var(--dash-text-secondary, rgba(255,255,255,0.6)); font-size: 1rem; line-height: 1.6;">Ganhe o dobro de XP, desbloqueie mascotes novos e domine o topo do ranking mensal com sua dedicação.</p>
                </div>
            </div>
        </div>

        <!-- Section: FAQ Simples -->
        <div class="faq-section" style="margin-top: 100px; max-width: 800px; margin-left: auto; margin-right: auto; padding-bottom: 40px;">
            <h2 style="font-size: 2.2rem; font-weight: 800; text-align: center; margin-bottom: 40px; font-family: 'Syne', sans-serif; color: var(--dash-text-primary);">Dúvidas Frequentes</h2>

            <div style="background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); border-radius: 16px; padding: 25px; margin-bottom: 20px; transition: transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateX(5px)'" onmouseout="this.style.transform='translateX(0)'">
                <h4 style="font-size: 1.15rem; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; color: var(--dash-text-primary); font-weight: 700;">
                    <i class="fas fa-question-circle" style="color: var(--primary-color);"></i> Posso cancelar a qualquer momento?
                </h4>
                <p style="color: var(--dash-text-secondary, rgba(255,255,255,0.6)); margin: 0; font-size: 1rem; padding-left: 28px; line-height: 1.6;">Sim. Não temos fidelidade. Você gerencia sua assinatura no painel "Meu Plano" e pode pausar ou cancelar quando desejar, com um único clique.</p>
            </div>

            <div style="background: var(--dash-bg-card, rgba(255,255,255,0.02)); border: 1px solid var(--dash-border, rgba(255,255,255,0.05)); border-radius: 16px; padding: 25px; margin-bottom: 20px; transition: transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.05);" onmouseover="this.style.transform='translateX(5px)'" onmouseout="this.style.transform='translateX(0)'">
                <h4 style="font-size: 1.15rem; margin-bottom: 10px; display: flex; align-items: center; gap: 10px; color: var(--dash-text-primary); font-weight: 700;">
                    <i class="fas fa-question-circle" style="color: var(--primary-color);"></i> Como funciona a garantia?
                </h4>
                <p style="color: var(--dash-text-secondary, rgba(255,255,255,0.6)); margin: 0; font-size: 1rem; padding-left: 28px; line-height: 1.6;">Nós confiamos tanto em nosso material e metodologia que você tem 7 dias de garantia incondicional no primeiro pagamento para pedir reembolso 100%.</p>
            </div>
            
        </div>

    </div>
</div>
@endsection
