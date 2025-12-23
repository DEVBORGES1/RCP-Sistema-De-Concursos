<?php
session_start();
require_once __DIR__ . '/app/Classes/Database.php';
require_once __DIR__ . '/app/Classes/User.php';
require_once __DIR__ . '/app/Classes/GamificacaoRefatorada.php';

$mensagem = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $senha = $_POST["senha"];
    $confirmar_senha = $_POST["confirmar_senha"];
    
    // Validações
    if (empty($nome) || empty($email) || empty($senha)) {
        $erro = "Todos os campos são obrigatórios.";
    } elseif (strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } elseif ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = "Email inválido.";
    } else {
        // Cadastrar usuário usando POO
        $user = new User();
        
        if ($user->create($nome, $email, $senha)) {
            $usuario_id = $user->getId();
            
            // Inicializar progresso do usuário usando a classe GamificacaoRefatorada
            $gamificacao = new GamificacaoRefatorada();
            $gamificacao->garantirProgressoUsuario($usuario_id);
            
            // Adicionar pontos de boas-vindas
            $gamificacao->adicionarPontos($usuario_id, 50, 'primeiro_acesso');
            
            $mensagem = "Cadastro realizado com sucesso! Você ganhou 50 pontos de boas-vindas!";
            
            // Fazer login automático
            $_SESSION["usuario_id"] = $usuario_id;
            
            // Redirecionar após 2 segundos
            header("refresh:2;url=dashboard.php");
        } else {
            $erro = "Erro ao cadastrar usuário. Este email já está cadastrado.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - RCP Sistema de Concursos</title>
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
                    Junte-se à elite dos
                    <br><span>Ratos de Concurso</span>
                </div>
            </div>
        </div>

        <!-- Right Side: Register Form -->
        <div class="auth-right">
            <div class="theme-toggle-container" style="position: absolute; top: 20px; right: 20px;">
                <button id="themeToggle" class="theme-toggle" title="Alternar Tema" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: var(--text-color);">
                    <i class="fas fa-moon"></i>
                </button>
            </div>
            <div class="auth-container">
                <div class="auth-header">
                    <h2>Criar Conta</h2>
                    <p>Comece sua jornada rumo à aprovação.</p>
                </div>
                
                <?php if ($mensagem): ?>
                    <div class="alert alert-success" style="background: rgba(40, 167, 69, 0.2); color: #28a745; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(40, 167, 69, 0.3); display: flex; flex-direction: column; align-items: center; text-align: center; gap: 5px;">
                        <div style="display: flex; align-items: center; gap: 10px; font-weight: bold;">
                             <i class="fas fa-check-circle"></i> <?= $mensagem ?>
                        </div>
                        <p style="font-size: 0.9rem; margin-top: 5px; color: rgba(255,255,255,0.7);">Redirecionando para o dashboard...</p>
                    </div>
                <?php endif; ?>
                
                <?php if ($erro): ?>
                    <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.2); color: #ff6b6b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid rgba(220, 53, 69, 0.3); display: flex; align-items: center; gap: 10px;">
                        <i class="fas fa-exclamation-triangle"></i>
                        <?= $erro ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!$mensagem): ?>
                    <form method="POST" class="auth-form">
                        <div class="form-group">
                            <label for="nome">Nome Completo</label>
                            <div class="input-wrapper">
                                <input type="text" id="nome" name="nome" placeholder="Seu nome completo" 
                                       value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>" required>
                                <i class="fas fa-user"></i>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <div class="input-wrapper">
                                <input type="email" id="email" name="email" placeholder="seu@email.com" 
                                       value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" required>
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="senha">Senha</label>
                            <div class="input-wrapper">
                                <input type="password" id="senha" name="senha" placeholder="Mínimo 6 caracteres" required>
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirmar_senha">Confirmar Senha</label>
                            <div class="input-wrapper">
                                <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Repita a senha" required>
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn-auth">
                            <i class="fas fa-user-plus"></i> Criar Conta Gratuita
                        </button>
                    </form>
                <?php endif; ?>
                
                <div class="auth-footer">
                    <p>Já tem uma conta? <a href="login.php">Fazer Login</a></p>
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
        /* Particles Init */
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

        /* Password Validation Logic */
        const senhaInput = document.getElementById('senha');
        const confirmInput = document.getElementById('confirmar_senha');

        function validatePasswordMatch() {
            if (!confirmInput.value) {
                confirmInput.style.borderColor = 'var(--auth-border)';
                return;
            }
            
            if (senhaInput.value !== confirmInput.value) {
                confirmInput.style.borderColor = '#dc3545';
                confirmInput.style.boxShadow = '0 0 0 4px rgba(220, 53, 69, 0.1)';
            } else {
                confirmInput.style.borderColor = '#28a745';
                confirmInput.style.boxShadow = '0 0 0 4px rgba(40, 167, 69, 0.1)';
            }
        }

        function validatePasswordStrength() {
            if (!senhaInput.value) {
                 senhaInput.style.borderColor = 'var(--auth-border)';
                 return;
            }

            if (senhaInput.value.length < 6) {
                senhaInput.style.borderColor = '#dc3545';
                senhaInput.style.boxShadow = '0 0 0 4px rgba(220, 53, 69, 0.1)';
            } else {
                senhaInput.style.borderColor = '#28a745';
                senhaInput.style.boxShadow = '0 0 0 4px rgba(40, 167, 69, 0.1)';
            }
            // Trigger match validation if confirm field is filled
            if(confirmInput.value) validatePasswordMatch();
        }

        if(senhaInput && confirmInput) {
            confirmInput.addEventListener('input', validatePasswordMatch);
            senhaInput.addEventListener('input', validatePasswordStrength);
        }
    </script>
</body>
</html>