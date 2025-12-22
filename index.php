<?php
session_start();
if (isset($_SESSION["usuario_id"])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RCP - Sistema de Concursos - Plataforma de Estudos</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="/assets/css/concurso.png" type="image/png">
</head>

<body class="homepage">
    <div id="particles-js"></div>
    <div class="container">
        <!-- Navigation -->
        <nav class="main-nav">
            <div class="nav-brand">
                <i class="fas fa-graduation-cap"></i>
                <span>RCP - Sistema de Concursos</span>
            </div>
            <div class="nav-actions">
                <a href="login.php" class="nav-link">Entrar</a>
                <a href="register.php" class="nav-btn">Cadastrar</a>
            </div>
        </nav>
    </div>

    <!-- Hero Section (Full Width) -->
    <section class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content centered-content">
            <div class="hero-badge">
                <i class="fas fa-star"></i>
                <span>Plataforma #1 em Gamificação de Estudos</span>
            </div>
            <h1>Transforme seus estudos em uma <span class="gradient-text">jornada épica</span></h1>
            <p class="hero-subtitle">
                A única plataforma que combina inteligência artificial, gamificação e
                análise de dados para maximizar seu desempenho em concursos públicos.
            </p>
            
            <div class="hero-actions">
                <a href="register.php" class="btn-primary btn-large">
                    <i class="fas fa-rocket"></i> Começar Jornada
                    <span class="btn-glow"></span>
                </a>
                <a href="login.php" class="btn-secondary btn-large">
                    <i class="fas fa-sign-in-alt"></i> Já tenho conta
                </a>
            </div>

            <div class="hero-stats">
                <div class="stat">
                    <span class="number">95%</span>
                    <span class="label">Taxa de Aprovação</span>
                </div>
                <div class="stat">
                    <span class="number">50k+</span>
                    <span class="label">Questões</span>
                </div>
                <div class="stat">
                    <span class="number">10k+</span>
                    <span class="label">Usuários</span>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <!-- How it Works Section -->
        <section class="how-it-works reveal">
            <div class="section-header">
                <h2>Como funciona nossa plataforma?</h2>
                <p>Três passos simples para transformar seus estudos</p>
            </div>
            <div class="steps-container">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <i class="fas fa-upload"></i>
                        <h3>Upload do Edital</h3>
                        <p>Envie o PDF do edital e nossa IA extrai automaticamente todo o conteúdo programático</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <i class="fas fa-calendar-check"></i>
                        <h3>Cronograma Inteligente</h3>
                        <p>Receba um plano de estudo personalizado baseado no seu tempo e dificuldade</p>
                    </div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <i class="fas fa-trophy"></i>
                        <h3>Estude e Ganhe</h3>
                        <p>Pratique questões, ganhe pontos, suba de nível e conquiste sua vaga!</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section reveal">
            <div class="section-header">
                <h2>Por que escolher nossa plataforma?</h2>
                <p>Recursos que fazem a diferença na sua preparação</p>
            </div>

            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-upload"></i>
                    </div>
                    <h3>Upload de Editais</h3>
                    <p>Envie PDFs de editais e provas anteriores. Nossa IA extrai automaticamente o conteúdo programático e identifica as disciplinas.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3>Cronograma Inteligente</h3>
                    <p>Gere planos de estudo personalizados baseados no tempo disponível, peso das disciplinas e dificuldade dos tópicos.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h3>Banco de Questões</h3>
                    <p>Cadastre questões das provas anteriores e pratique com nosso sistema inteligente de questões personalizadas.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3>Simulados Personalizados</h3>
                    <p>Crie simulados adaptados ao seu nível e disciplinas de interesse, com correção automática e feedback detalhado.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-gamepad"></i>
                    </div>
                    <h3>Sistema Gamificado</h3>
                    <p>Ganhe pontos, suba de nível, desbloqueie conquistas e compete com outros estudantes em rankings mensais.</p>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Dashboard Completo</h3>
                    <p>Acompanhe seu progresso com estatísticas detalhadas, gráficos de evolução e métricas de performance.</p>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section class="testimonials-section reveal">
            <div class="section-header">
                <h2>O que nossos usuários dizem</h2>
                <p>Histórias reais de quem já conquistou a vaga</p>
            </div>
            <div class="testimonials-grid">
                <div class="testimonial">
                    <div class="testimonial-content">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p>"A gamificação me manteve motivado durante toda a preparação. Consegui minha vaga no TRT em 6 meses!"</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <h4>Maria Silva</h4>
                            <span>Analista Judiciário - TRT</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial">
                    <div class="testimonial-content">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p>"O cronograma inteligente foi fundamental. Estudei de forma organizada e eficiente."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <h4>João Santos</h4>
                            <span>Auditor Fiscal - SEFAZ</span>
                        </div>
                    </div>
                </div>

                <div class="testimonial">
                    <div class="testimonial-content">
                        <div class="stars">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p>"A plataforma me ajudou a identificar meus pontos fracos e melhorar rapidamente."</p>
                    </div>
                    <div class="testimonial-author">
                        <div class="author-avatar">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="author-info">
                            <h4>Ana Costa</h4>
                            <span>Procuradora - MP</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="stats-section reveal">
            <div class="stats-container">
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="stat-number">50k+</div>
                    <div class="stat-label">Questões Cadastradas</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-number">10k+</div>
                    <div class="stat-label">Usuários Ativos</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-number">95%</div>
                    <div class="stat-label">Taxa de Aprovação</div>
                </div>
                <div class="stat-item">
                    <div class="stat-icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Disponibilidade</div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section reveal">
            <div class="cta-content">
                <h2>Pronto para transformar seus estudos?</h2>
                <p>Junte-se a milhares de candidatos que já descobriram uma forma mais eficiente e divertida de estudar para concursos.</p>
                <a href="register.php" class="btn-primary btn-large">
                    <i class="fas fa-user-plus"></i> Criar Conta Gratuita
                </a>
            </div>
        </section>
    </div>

    <!-- Footer -->
    <footer class="main-footer reveal">
        <div class="footer-content">
            <div class="footer-brand">
                <div class="nav-brand">
                    <i class="fas fa-graduation-cap"></i>
                    <span>RCP Concursos</span>
                </div>
                <p>Sua jornada rumo à aprovação começa aqui. Tecnologia e gamificação unidas para o seu sucesso.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="footer-links">
                <h3>Plataforma</h3>
                <a href="#">Início</a>
                <a href="#">Recursos</a>
                <a href="#">Planos</a>
                <a href="#">Blog</a>
            </div>

            <div class="footer-links">
                <h3>Suporte</h3>
                <a href="#">FAQ</a>
                <a href="#">Contato</a>
                <a href="#">Termos de Uso</a>
                <a href="#">Privacidade</a>
            </div>

            <div class="footer-newsletter">
                <h3>Novidades</h3>
                <p>Inscreva-se para receber dicas de estudo e ofertas.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Seu e-mail">
                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2024 RCP Sistema de Concursos. Todos os direitos reservados.</p>
        </div>
    </footer>

    <style>
        /* Homepage specific styles */
        body.homepage {
            background: linear-gradient(135deg, #000000 0%, #1a0000 25%, #330000 50%, #660000 75%, #cc0000 100%);
            background-attachment: fixed;
            font-family: 'Inter', sans-serif;
        }

        /* Navigation */
        .main-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            margin-bottom: 60px;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .nav-brand i {
            color: #ff4444;
            font-size: 2rem;
        }

        .nav-actions {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: white;
        }

        .nav-btn {
            background: linear-gradient(45deg, #ff4444, #cc0000);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .nav-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 68, 68, 0.3);
        }

        /* Hero Section */
        .hero-section {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 85vh;
            padding: 80px 0;
            background-image: url('assets/imagens/hero-cover.png');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            position: relative;
            margin-bottom: 80px;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0.8) 50%, rgba(0,0,0,0.6) 100%);
            z-index: 1;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            text-align: center;
        }

        .hero-content.centered-content {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 68, 68, 0.15);
            border: 1px solid rgba(255, 68, 68, 0.5);
            color: #ff4444;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 30px;
            backdrop-filter: blur(5px);
            box-shadow: 0 0 20px rgba(255, 68, 68, 0.2);
            animation: fadeInDown 1s ease-out;
        }

        .hero-content h1 {
            font-size: 4rem;
            color: white;
            margin-bottom: 30px;
            font-weight: 800;
            line-height: 1.1;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
            animation: fadeInUp 1s ease-out 0.2s backwards;
        }

        .gradient-text {
            background: linear-gradient(45deg, #ff4444, #ff6666, #ff8888);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: none;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 50px;
            font-weight: 400;
            line-height: 1.6;
            max-width: 800px;
            text-shadow: 0 1px 5px rgba(0,0,0,0.5);
            animation: fadeInUp 1s ease-out 0.4s backwards;
        }

        .hero-stats {
            display: flex;
            gap: 60px;
            margin-top: 60px;
            animation: fadeInUp 1s ease-out 0.8s backwards;
            background: rgba(0,0,0,0.5);
            padding: 20px 40px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }

        .stat {
            text-align: center;
        }

        .stat .number {
            display: block;
            font-size: 2.5rem;
            font-weight: 800;
            color: #ff4444;
            margin-bottom: 5px;
            text-shadow: 0 2px 5px rgba(0,0,0,0.5);
        }

        .stat .label {
            font-size: 1rem;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .hero-actions {
            display: flex;
            gap: 25px;
            flex-wrap: wrap;
            justify-content: center;
            animation: fadeInUp 1s ease-out 0.6s backwards;
        }

        .btn-large {
            padding: 20px 40px;
            font-size: 1.2rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            border-radius: 15px;
            font-weight: 700;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(45deg, #ff4444, #cc0000);
            color: white;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(255, 68, 68, 0.4);
            border: none;
        }

        .btn-primary:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(255, 68, 68, 0.6);
        }

        .btn-glow {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover .btn-glow {
            left: 100%;
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.4);
            backdrop-filter: blur(10px);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.25);
            border-color: white;
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float-badge {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease;
        }
        .reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Section Headers */
        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h2 {
            font-size: 2.5rem;
            color: white;
            margin-bottom: 15px;
            font-weight: 700;
        }

        .section-header p {
            font-size: 1.1rem;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
        }

        /* How it Works Section */
        .how-it-works {
            margin-bottom: 120px;
        }

        .steps-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 40px;
        }

        .step {
            text-align: center;
            position: relative;
        }

        .step-number {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #ff4444, #cc0000);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 auto 30px;
            position: relative;
            z-index: 2;
        }

        .step-content {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px 30px;
            border-radius: 20px;
            color: white;
            transition: all 0.3s ease;
        }

        .step-content:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.15);
        }

        .step-content i {
            font-size: 3rem;
            color: #ff4444;
            margin-bottom: 20px;
        }

        .step-content h3 {
            font-size: 1.3rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .step-content p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        .features-section {
            margin-bottom: 120px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 30px;
        }

        .feature-item {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            color: white;
            transition: all 0.3s ease;
        }

        .feature-item:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 68, 68, 0.3);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(45deg, #ff4444, #cc0000);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 25px;
        }

        .feature-icon i {
            font-size: 2rem;
            color: white;
        }

        .feature-item h3 {
            color: white;
            font-size: 1.3rem;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .feature-item p {
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        /* Testimonials Section */
        .testimonials-section {
            margin-bottom: 120px;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .testimonial {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 30px;
            border-radius: 20px;
            color: white;
            transition: all 0.3s ease;
        }

        .testimonial:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.15);
        }

        .stars {
            color: #ff4444;
            margin-bottom: 20px;
        }

        .stars i {
            margin-right: 2px;
        }

        .testimonial-content p {
            font-style: italic;
            margin-bottom: 25px;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            background: linear-gradient(45deg, #ff4444, #cc0000);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
        }

        .author-info h4 {
            color: white;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .author-info span {
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
        }

        .stats-section {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 60px;
            margin-bottom: 120px;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
        }

        .stat-item {
            text-align: center;
            color: white;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, #ff4444, #cc0000);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .stat-icon i {
            font-size: 1.5rem;
            color: white;
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #ff4444;
            margin-bottom: 10px;
            display: block;
        }

        .stat-label {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 500;
        }

        .cta-section {
            text-align: center;
            background: linear-gradient(45deg, #ff4444, #cc0000);
            color: white;
            padding: 80px;
            border-radius: 20px;
            margin-bottom: 40px;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="10" cy="60" r="0.5" fill="rgba(255,255,255,0.1)"/><circle cx="90" cy="40" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.3;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            position: relative;
            z-index: 2;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            opacity: 0.9;
            position: relative;
            z-index: 2;
        }

        .cta-section .btn-primary {
            background: white;
            color: #ff4444;
            position: relative;
            z-index: 2;
        }

        .cta-section .btn-primary:hover {
            background: #f8f9fa;
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 60px 0;
                min-height: 70vh;
            }

            .hero-content h1 {
                font-size: 2.8rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .hero-stats {
                gap: 30px;
                padding: 15px 30px;
            }

            .steps-container {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .testimonials-grid {
                grid-template-columns: 1fr;
            }

            .stats-container {
                grid-template-columns: repeat(2, 1fr);
            }

            .cta-section {
                padding: 40px 20px;
            }

            .cta-section h2 {
                font-size: 2rem;
            }

            .section-header h2 {
                font-size: 2rem;
            }
        }

        @media (max-width: 480px) {
            .hero-content h1 {
                font-size: 2rem;
            }

            .hero-stats {
                flex-direction: column;
                gap: 20px;
            }

            .trust-badges {
                flex-direction: column;
                align-items: center;
            }

            .stats-container {
                grid-template-columns: 1fr;
            }

            .hero-actions {
                flex-direction: column;
                align-items: center;
            }

            .nav-actions {
                flex-direction: column;
                gap: 10px;
            }
        }
        /* Particles */
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            pointer-events: none;
        }

        /* Stats Animation Class */
        .stats-section.reveal .stat-number,
        .hero-section .stat .number {
            transition: color 0.3s ease;
        }

        /* Footer Styles */
        .main-footer {
            background: linear-gradient(to bottom, #000000 0%, #4d0000 100%);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(255, 68, 68, 0.2);
            color: white;
            padding: 60px 0 20px;
            margin-top: 80px;
            position: relative;
            z-index: 1;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 40px;
            padding: 0 20px;
        }

        .footer-brand p {
            color: rgba(255, 255, 255, 0.6);
            margin: 20px 0;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .social-links {
            display: flex;
            gap: 15px;
        }

        .social-links a {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: #ff4444;
            transform: translateY(-3px);
        }

        .footer-links h3, .footer-newsletter h3 {
            font-size: 1.1rem;
            margin-bottom: 20px;
            color: white;
            font-weight: 600;
        }

        .footer-links a {
            display: block;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 10px;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #ff4444;
            padding-left: 5px;
        }

        .newsletter-form {
            display: flex;
            gap: 10px;
            margin-top: 15px;
        }

        .newsletter-form input {
            flex: 1;
            padding: 10px;
            border-radius: 5px;
            border: none;
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .newsletter-form button {
            background: #ff4444;
            border: none;
            color: white;
            padding: 0 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .newsletter-form button:hover {
            background: #cc0000;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 40px auto 0;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            text-align: center;
            color: rgba(255, 255, 255, 0.4);
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 30px;
            }
            .social-links {
                justify-content: center;
            }
            .newsletter-form {
                flex-direction: column;
            }
            .footer-links a:hover {
                padding-left: 0;
                color: #ff4444;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        /* Particles Init */
        if(typeof particlesJS !== 'undefined') {
            particlesJS("particles-js", {
                "particles": {
                    "number": { "value": 80, "density": { "enable": true, "value_area": 800 } },
                    "color": { "value": "#ff4444" },
                    "shape": { "type": "circle" },
                    "opacity": { "value": 0.2, "random": true },
                    "size": { "value": 3, "random": true },
                    "line_linked": { "enable": true, "distance": 150, "color": "#ff4444", "opacity": 0.1, "width": 1 },
                    "move": { "enable": true, "speed": 1, "direction": "none", "random": true, "out_mode": "out" }
                },
                "interactivity": {
                    "detect_on": "canvas",
                    "events": { "onhover": { "enable": true, "mode": "grab" }, "onclick": { "enable": true, "mode": "push" } },
                    "modes": { "grab": { "distance": 140, "line_linked": { "opacity": 0.5 } } }
                },
                "retina_detect": true
            });
        }

        /* Scroll Animation (Reveal) */
        window.addEventListener('scroll', reveal);
        function reveal(){
            var reveals = document.querySelectorAll('.reveal');
            for(var i = 0; i < reveals.length; i++){
                var windowHeight = window.innerHeight;
                var elementTop = reveals[i].getBoundingClientRect().top;
                var elementVisible = 150;
                if(elementTop < windowHeight - elementVisible){
                    reveals[i].classList.add('active');
                    
                    // Trigger CountUp if it's the stats section
                    if(reveals[i].classList.contains('stats-section') && !reveals[i].classList.contains('counted')){
                        startCountAnimation();
                        reveals[i].classList.add('counted');
                    }
                }
            }
        }
        
        function startCountAnimation() {
            const stats = document.querySelectorAll('.stat-number, .stat .number');
            stats.forEach(stat => {
                const targetText = stat.innerText;
                const hasSuffix = targetText.includes('k') || targetText.includes('%') || targetText.includes('+');
                const suffix = targetText.replace(/[0-9.]/g, '');
                const target = parseFloat(targetText.replace(/[^0-9.]/g, ''));
                
                if(isNaN(target)) return;

                let count = 0;
                const duration = 2000; // 2s
                const increment = target / (duration / 16); // 60fps

                const timer = setInterval(() => {
                    count += increment;
                    if (count >= target) {
                        count = target;
                        clearInterval(timer);
                    }
                    // Format number: if it was an integer, show int, else fixed
                    let display = Number.isInteger(target) ? Math.round(count) : count.toFixed(1);
                    stat.innerText = display + suffix;
                }, 16);
            });
        }

        // Trigger once to check initial view
        reveal();
    </script>
</body>

</html>