<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RCP - Sistema de Concursos - Plataforma de Estudos</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/imagens/icon/iconeweb.png') }}" type="image/png">
    <style>
        :root {
            --primary: #ff4444;
            --primary-dark: #cc0000;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.08);
            --glass-highlight: rgba(255, 255, 255, 0.15);
            --bento-radius: 32px;
        }

        body.homepage {
            background: #050505;
            font-family: 'Inter', sans-serif;
            margin: 0;
            color: #fff;
            overflow-x: hidden;
            /* Fundo gradiente sutil fixo */
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(255, 68, 68, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(204, 0, 0, 0.1) 0%, transparent 40%);
            background-attachment: fixed;
        }

        /* Glassmorphism Classes */
        .glass-panel {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
            border-radius: var(--bento-radius);
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        .glass-panel:hover {
            border-color: var(--glass-highlight);
            transform: translateY(-5px);
            box-shadow: 0 16px 48px 0 rgba(255, 68, 68, 0.15);
            background: rgba(255, 255, 255, 0.06);
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 40px;
            position: relative;
            z-index: 2;
        }

        /* Nav */
        .main-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 30px 0;
            margin-bottom: 40px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .nav-brand i { color: var(--primary); }

        .nav-link {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
            margin-right: 30px;
        }
        .nav-link:hover { color: #fff; }

        .nav-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            padding: 12px 28px;
            border-radius: 100px;
            color: white;
            text-decoration: none;
            font-weight: 700;
            box-shadow: 0 10px 20px rgba(255, 68, 68, 0.2);
            transition: 0.3s;
        }
        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(255, 68, 68, 0.4);
        }

        /* Hero Section Redesign */
        .hero-section {
            display: flex;
            align-items: Center;
            justify-content: space-between;
            min-height: 80vh;
            margin-bottom: 60px;
            position: relative;
            z-index: 10;
        }

        .hero-content {
            flex: 1;
            max-width: 650px;
            text-align: left;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 68, 68, 0.1);
            border: 1px solid rgba(255, 68, 68, 0.3);
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 100px;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 24px;
            backdrop-filter: blur(10px);
        }

        h1 {
            font-size: 4.5rem;
            line-height: 1.1;
            margin-bottom: 24px;
            font-weight: 800;
            letter-spacing: -2px;
        }

        .gradient-text {
            background: linear-gradient(135deg, #fff 30%, var(--primary) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-description {
            font-size: 1.25rem;
            color: rgba(255,255,255,0.6);
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 500px;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
        }

        .hero-visual {
            flex: 1;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .mascot-hero {
            max-width: 100%;
            height: auto;
            max-height: 600px;
            /* Neon Red Glow intenso */
            filter: drop-shadow(0 0 15px rgba(255, 68, 68, 0.8)) drop-shadow(0 0 50px rgba(255, 68, 68, 0.4));
            animation: float 6s ease-in-out infinite;
        }

        /* Bento Grid Layout */
        .bento-section {
            margin-bottom: 120px;
        }

        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }
        .section-title h2 { font-size: 3rem; font-weight: 800; margin-bottom: 15px; }
        .section-title p { color: rgba(255,255,255,0.6); font-size: 1.2rem; }

        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            grid-template-rows: repeat(2, minmax(300px, auto));
            gap: 24px;
        }

        .bento-item {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            border-radius: var(--bento-radius);
            min-height: 320px;
        }

        /* Bento Spans */
        .col-span-4 { grid-column: span 4; }
        .col-span-8 { grid-column: span 8; }
        .col-span-6 { grid-column: span 6; }
        .col-span-12 { grid-column: span 12; }

        /* Bento Content */
        .bento-content {
            position: relative;
            z-index: 2;
        }

        .bento-icon {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: var(--primary);
            margin-bottom: 24px;
        }

        .bento-item h3 { font-size: 2rem; margin-bottom: 15px; font-weight: 700; }
        .bento-item p { color: rgba(255,255,255,0.7); font-size: 1.1rem; line-height: 1.5; max-width: 90%; }

        .mascot-absolute {
            position: absolute;
            bottom: -20px;
            right: -20px;
            height: 350px;
            width: auto;
            z-index: 0;
            opacity: 0.9;
            transition: 0.5s cubic-bezier(0.25, 0.8, 0.25, 1);
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.3));
        }

        .bento-item:hover .mascot-absolute {
            transform: scale(1.1) rotate(-5deg);
            opacity: 1;
            /* Glow vermelho neon no hover */
            filter: drop-shadow(0 0 15px rgba(255, 68, 68, 0.6)) drop-shadow(0 0 30px rgba(255, 68, 68, 0.4));
        }

        /* Stats Strip */
        .stats-strip {
            display: flex;
            justify-content: space-around;
            padding: 60px;
            margin: 80px 0;
            background: rgba(0,0,0,0.3);
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
        }
        .stat-unit h4 { font-size: 3.5rem; color: var(--primary); margin: 0; }
        .stat-unit p { color: rgba(255,255,255,0.6); text-transform: uppercase; letter-spacing: 1px; font-weight: 600; margin-top: 5px; }

        /* Responsive */
        @media (max-width: 1200px) {
            h1 { font-size: 3.5rem; }
            .bento-grid { grid-template-columns: 1fr; grid-template-rows: auto; }
            .bento-item { grid-column: span 1 !important; min-height: 400px; }
            .mascot-hero { height: 400px; }
            .hero-section { flex-direction: column-reverse; text-align: center; justify-content: center; }
            .hero-content { margin-top: 40px; }
            .hero-actions { justify-content: center; }
            .hero-description { margin: 0 auto 40px auto; }
            .mascot-absolute { height: 200px; }
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
    </style>
</head>

<body class="homepage">
    <div id="particles-js" style="position: fixed; width: 100%; height: 100%; top: 0; left: 0; z-index: 0; pointer-events: none;"></div>

    <div class="container">
        <!-- Navigation -->
        <nav class="main-nav reveal">
            <div class="nav-brand">
                <i class="fas fa-graduation-cap"></i>
                <span>RCP Concursos</span>
            </div>
            <div class="nav-actions">
                <a href="{{ route('login') }}" class="nav-link">Plataforma</a>
                <a href="{{ route('login') }}" class="nav-link">Login</a>
                <a href="{{ route('login') }}" class="nav-btn">Começar Agora</a>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section reveal">
            <div class="hero-content">
                <div class="hero-badge">
                    <i class="fas fa-rocket"></i> Nova Versão 2.0
                </div>
                <h1>Aprovação Gamificada <br> <span class="gradient-text">Sem Tédio.</span></h1>
                <p class="hero-description">
                    Junte-se à revolução dos estudos. Nossa mascote e IA personalizam sua jornada com recompensas, rankings e conteúdo focado no edital.
                </p>
                <div class="hero-actions">
                    <a href="{{ route('login') }}" class="nav-btn">Criar Conta Grátis</a>
                    <a href="#features" class="nav-link" style="margin: 0; border: 1px solid rgba(255,255,255,0.2); border-radius: 100px; padding: 12px 28px;">
                        Ver Recursos
                    </a>
                </div>
            </div>
            <div class="hero-visual">
                <!-- Mascote Geral -->
                <img src="{{ asset('assets/imagens/mascotes/mascot_geral.png') }}" alt="Mascote RCP" class="mascot-hero">
            </div>
        </section>

        <!-- Stats Strip -->
        <div class="stats-strip reveal">
            <div class="stat-unit">
                <h4 class="counter" data-target="50000">0</h4>
                <p>Questões</p>
            </div>
            <div class="stat-unit">
                <h4 class="counter" data-target="15000">0</h4>
                <p>Alunos</p>
            </div>
            <div class="stat-unit">
                <h4>98%</h4>
                <p>Satisfação</p>
            </div>
        </div>

        <!-- Bento Grid Features -->
        <section id="features" class="bento-section reveal">
            <div class="section-title">
                <h2>Seu Ecossistema de Estudos</h2>
                <p>Tudo o que você precisa em um só lugar, guiado por especialistas virtuais.</p>
            </div>

            <div class="bento-grid">
                <!-- Card 1: Banco de Questões (Português/Geral) - Largo -->
                <div class="bento-item glass-panel col-span-8">
                    <div class="bento-content">
                        <div class="bento-icon"><i class="fas fa-brain"></i></div>
                        <h3>Banco de Questões Inteligente</h3>
                        <p>Milhares de questões comentadas e filtradas por banca, ano e dificuldade. Nosso algoritmo aprende seus pontos fracos.</p>
                    </div>
                    <!-- Mascote Portugues -->
                    <img src="{{ asset('assets/imagens/mascotes/mascot_portugues.png') }}" alt="Português" class="mascot-absolute" style="height: 450px; right: -50px; bottom: -50px;">
                </div>

                <!-- Card 2: Exatas/Estatísticas - Alto -->
                <div class="bento-item glass-panel col-span-4" style="background: linear-gradient(180deg, rgba(40,167,69,0.1), rgba(0,0,0,0)); border-color: rgba(40,167,69,0.3);">
                    <div class="bento-content">
                        <div class="bento-icon" style="background: rgba(40,167,69,0.2); color: #2ecc71;"><i class="fas fa-chart-pie"></i></div>
                        <h3>Métricas de Evolução</h3>
                        <p>Acompanhe seu desempenho em tempo real com gráficos detalhados de matemática e raciocínio lógico.</p>
                    </div>
                    <!-- Mascote Matemática -->
                    <img src="{{ asset('assets/imagens/mascotes/mascot_matematica.png') }}" alt="Matemática" class="mascot-absolute">
                </div>

                <!-- Card 3: Tecnologia/Simulados - Alto -->
                <div class="bento-item glass-panel col-span-4" style="background: linear-gradient(180deg, rgba(52,152,219,0.1), rgba(0,0,0,0)); border-color: rgba(52,152,219,0.3);">
                    <div class="bento-content">
                        <div class="bento-icon" style="background: rgba(52,152,219,0.2); color: #3498db;"><i class="fas fa-laptop-code"></i></div>
                        <h3>Simulados Adaptativos</h3>
                        <p>O sistema gera simulados baseados na tecnologia avançada de análise de editais.</p>
                    </div>
                    <!-- Mascote Informatica -->
                    <img src="{{ asset('assets/imagens/mascotes/mascot_informatica.png') }}" alt="Informática" class="mascot-absolute">
                </div>

                <!-- Card 4: Direito/Legislação - Largo -->
                <div class="bento-item glass-panel col-span-8" style="background: linear-gradient(180deg, rgba(231,76,60,0.1), rgba(0,0,0,0)); border-color: rgba(231,76,60,0.3);">
                    <div class="bento-content">
                        <div class="bento-icon"><i class="fas fa-balance-scale"></i></div>
                        <h3>Legislação Atualizada</h3>
                        <p>Acesso rápido a todos os códigos e leis cobrados, com o rigor jurídico necessário para sua aprovação.</p>
                    </div>
                    <!-- Mascote Direito -->
                    <img src="{{ asset('assets/imagens/mascotes/mascot_constitucional.png') }}" alt="Direito" class="mascot-absolute" style="height: 480px; right: -40px; bottom: -80px;">
                </div>
            </div>
        </section>

        <!-- CTA Final -->
        <section class="reveal" style="text-align: center; margin-bottom: 80px;">
            <div class="glass-panel" style="padding: 80px; position: relative; overflow: hidden;">
                <h2 style="font-size: 3rem; margin-bottom: 20px;">Sua Nomeação Começa Aqui</h2>
                <p style="margin-bottom: 40px; color: rgba(255,255,255,0.7);">Não estude mais difícil, estude mais inteligente.</p>
                <a href="{{ route('login') }}" class="nav-btn" style="font-size: 1.2rem; padding: 18px 40px;">Entrar na Plataforma</a>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer style="border-top: 1px solid rgba(255,255,255,0.05); padding: 40px 0; text-align: center; color: rgba(255,255,255,0.4);">
        <p>&copy; 2025 RCP Sistema de Concursos. Design Premium.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 40 },
                "color": { "value": "#ff4444" },
                "opacity": { "value": 0.2 },
                "size": { "value": 3 },
                "line_linked": { "enable": true, "color": "#ff4444", "opacity": 0.1 },
                "move": { "enable": true, "speed": 1 }
            }
        });

        // Simple Intersection Observer for Reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    entry.target.style.opacity = 1;
                    entry.target.style.transform = 'translateY(0)';
                    
                    // Trigger counters if needed
                    if(entry.target.querySelector('.counter')) {
                        startCounters();
                    }
                }
            });
        });

        document.querySelectorAll('.reveal').forEach((el) => {
            el.style.opacity = 0;
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s ease-out';
            observer.observe(el);
        });

        function startCounters() {
            const counters = document.querySelectorAll('.counter');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                const inc = target / 100;
                
                const updateCount = () => {
                    const c = +counter.innerText.replace('+','');
                    if (c < target) {
                        counter.innerText = Math.ceil(c + inc) + '+';
                        setTimeout(updateCount, 15);
                    } else {
                        counter.innerText = target + '+';
                    }
                };
                updateCount();
            });
        }
    </script>
</body>
</html>
