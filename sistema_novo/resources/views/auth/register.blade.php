<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - RCP Sistema de Concursos</title>
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<body class="auth-page">
    <div class="split-screen">
        <!-- Left Side: Visual & Branding -->
        <div class="auth-left">
            <div id="particles-js" class="particles-container"></div>
            <div class="auth-brand-content">
                <div class="auth-logo">
                    <i class="fas fa-graduation-cap"></i>
                    <span>RCP Concursos</span>
                </div>
                <!-- Icone do Usuário -->
                <!-- Ajuste de caminho para asset() do Laravel -->
                <img src="{{ asset('assets/Imagens/Icon/Iconeweb.png') }}" alt="RCP Icon" class="auth-brand-icon">
                
                <div class="auth-tagline">
                    Transforme seus estudos em uma
                    <br><span>jornada épica</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="auth-right">
            <div class="theme-toggle-container" style="position: absolute; top: 20px; right: 20px;">
                <button id="themeToggle" class="theme-toggle" title="Alternar Tema" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--auth-text);">
                    <i class="fas fa-moon"></i>
                </button>
            </div>

            <div class="auth-container">
                <div class="auth-header">
                    <h2>Crie sua conta!</h2>
                    <p>Junte-se a nós e comece sua preparação.</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.2); color: #ff6b6b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(220, 53, 69, 0.3); display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <ul style="list-style: none; margin: 0; padding: 0;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="auth-form">
                    @csrf
                    <div class="form-group">
                        <label for="nome">Nome Completo</label>
                        <div class="input-wrapper">
                            <input type="text" id="nome" name="nome" value="{{ old('nome') }}" placeholder="Seu nome" required autofocus>
                            <i class="fas fa-user"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="seu@email.com" required>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Senha</label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" placeholder="Mínimo 8 caracteres" required>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation">Confirmação de Senha</label>
                        <div class="input-wrapper">
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Mínimo 8 caracteres" required>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth">
                        <i class="fas fa-user-plus"></i> Cadastrar-se
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Já tem uma conta? <a href="{{ route('login') }}">Faça login</a></p>
                    <a href="{{ url('/') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i> Voltar ao início
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script>
        particlesJS("particles-js", {
            "particles": {
                "number": { "value": 60, "density": { "enable": true, "value_area": 800 } },
                "color": { "value": "#ff4444" },
                "shape": { "type": "circle" },
                "opacity": { "value": 0.3, "random": true },
                "size": { "value": 3, "random": true },
                "line_linked": { "enable": true, "distance": 150, "color": "#ff4444", "opacity": 0.2, "width": 1 },
                "move": { "enable": true, "speed": 1, "direction": "none", "random": true, "out_mode": "out" }
            },
            "interactivity": {
                "detect_on": "canvas",
                "events": { "onhover": { "enable": true, "mode": "grab" }, "onclick": { "enable": true, "mode": "push" } },
                "modes": { "grab": { "distance": 140, "line_linked": { "opacity": 0.6 } } }
            },
            "retina_detect": true
        });
    </script>
</body>

</html>
