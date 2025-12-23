<?php
session_start();
require_once __DIR__ . '/app/Classes/Database.php';
require_once __DIR__ . '/app/Classes/User.php';
require_once __DIR__ . '/app/Classes/GamificacaoRefatorada.php';

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = new User();
    
    if ($user->authenticate($_POST["email"], $_POST["senha"])) {
        $_SESSION["usuario_id"] = $user->getId();

        // Inicializar progresso do usuário usando a classe GamificacaoRefatorada
        $gamificacao = new GamificacaoRefatorada();

        // Garantir que o usuário tenha progresso inicializado
        $gamificacao->garantirProgressoUsuario($user->getId());

        // Atualizar streak
        $gamificacao->atualizarStreak($user->getId());

        header("Location: dashboard.php");
        exit;
    } else {
        $mensagem = "Email ou senha incorretos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RCP Sistema de Concursos</title>
    <link rel="stylesheet" href="assets/css/auth.css">
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
                <img src="assets/Imagens/Icon/Iconeweb.png" alt="RCP Icon" class="auth-brand-icon">
                
                <div class="auth-tagline">
                    Transforme seus estudos em uma
                    <br><span>jornada épica</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="auth-right">
            <div class="theme-toggle-container" style="position: absolute; top: 20px; right: 20px;">
                <button id="themeToggle" class="theme-toggle" title="Alternar Tema" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-color);">
                    <i class="fas fa-moon"></i>
                </button>
            </div>

            <div class="auth-container">
                <div class="auth-header">
                    <h2>Bem-vindo de volta!</h2>
                    <p>Entre para continuar sua preparação.</p>
                </div>

                <?php if ($mensagem): ?>
                    <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.2); color: #ff6b6b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(220, 53, 69, 0.3); display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-exclamation-circle"></i>
                        <?= $mensagem ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <div class="input-wrapper">
                            <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                            <i class="fas fa-envelope"></i>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="senha">Senha</label>
                        <div class="input-wrapper">
                            <input type="password" id="senha" name="senha" placeholder="Sua senha secreta" required>
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth">
                        <i class="fas fa-sign-in-alt"></i> Entrar
                    </button>
                </form>

                <div class="auth-footer">
                    <p>Ainda não tem uma conta? <a href="register.php">Criar conta gratuita</a></p>
                    <a href="index.php" class="back-link">
                        <i class="fas fa-arrow-left"></i> Voltar ao início
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="assets/js/theme.js"></script>
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