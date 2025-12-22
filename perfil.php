<?php
session_start();
require_once __DIR__ . '/app/Classes/Database.php';
require_once __DIR__ . '/app/Classes/GamificacaoRefatorada.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$gamificacao = new GamificacaoRefatorada();
$gamificacao->atualizarStreak($_SESSION["usuario_id"]);
$gamificacao->verificarTodasConquistas($_SESSION["usuario_id"]);

// Obter dados do usuário
$dados_usuario = $gamificacao->obterDadosUsuario($_SESSION["usuario_id"]);
$conquistas = $gamificacao->obterConquistasUsuario($_SESSION["usuario_id"]);
$ranking = $gamificacao->obterRankingMensal(10);
$posicao_usuario = $gamificacao->obterPosicaoUsuario($_SESSION["usuario_id"]);

// Calcular estatísticas com valores padrão seguros
$total_questoes = isset($dados_usuario['questoes_respondidas']) ? (int)$dados_usuario['questoes_respondidas'] : 0;
$questoes_corretas = isset($dados_usuario['questoes_corretas']) ? (int)$dados_usuario['questoes_corretas'] : 0;
$percentual_acerto = $total_questoes > 0 ? round(($questoes_corretas / $total_questoes) * 100, 1) : 0;

// Garantir que os dados do usuário tenham valores padrão
$nome_usuario = isset($dados_usuario['nome']) ? $dados_usuario['nome'] : 'Usuário';
$nivel_usuario = isset($dados_usuario['nivel']) ? (int)$dados_usuario['nivel'] : 1;
$pontos_usuario = isset($dados_usuario['pontos_total']) ? (int)$dados_usuario['pontos_total'] : 0;
$streak_usuario = isset($dados_usuario['streak_dias']) ? (int)$dados_usuario['streak_dias'] : 0;

// Obter outras estatísticas usando Database
$pdo = Database::getInstance()->getConnection();

// Obter melhor pontuação em simulado
$stmt = $pdo->prepare("SELECT MAX(pontuacao_final) as melhor_pontuacao FROM simulados WHERE usuario_id = ? AND pontuacao_final IS NOT NULL");
$stmt->execute([$_SESSION["usuario_id"]]);
$melhor_pontuacao = $stmt->fetchColumn() ?: 0;

// Obter maior sequência de dias seguidos
$stmt = $pdo->prepare("SELECT MAX(streak_dias) as maior_streak FROM usuarios_progresso WHERE usuario_id = ?");
$stmt->execute([$_SESSION["usuario_id"]]);
$maior_streak = $stmt->fetchColumn() ?: 0;

// Obter simulados concluídos
$stmt = $pdo->prepare("SELECT COUNT(*) as total_simulados FROM simulados WHERE usuario_id = ? AND questoes_corretas IS NOT NULL");
$stmt->execute([$_SESSION["usuario_id"]]);
$total_simulados = $stmt->fetchColumn() ?: 0;

// Obter certificados (videoaulas assistidas) - verifica se tabela existe
try {
    $stmt = $pdo->prepare("SELECT COUNT(*) as certificados FROM videoaulas_progresso WHERE usuario_id = ? AND concluida = 1");
    $stmt->execute([$_SESSION["usuario_id"]]);
    $total_certificados = $stmt->fetchColumn() ?: 0;
} catch (PDOException $e) {
    $total_certificados = 0;
}

// Set active page for sidebar
$active_page = 'perfil.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - Sistema de Concursos</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="/assets/css/concurso.ico" type="image/png">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="content-with-sidebar">
        <div class="container">
            <!-- Header -->
            <?php include 'includes/header.php'; ?>

            <!-- Informações Pessoais -->
            <section class="welcome-section">
                <div class="welcome-card">
                    <div class="profile-header">
                        <div class="profile-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <div class="profile-info">
                            <h2><?= htmlspecialchars($nome_usuario) ?></h2>
                            <p>Nível <?= $nivel_usuario ?> • <?= $pontos_usuario ?> pontos</p>
                            <div class="streak-info">
                                <i class="fas fa-fire"></i>
                                <span><?= $streak_usuario ?> dias seguidos</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Estatísticas Principais -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $posicao_usuario ? $posicao_usuario . 'º' : 'N/A' ?></h3>
                        <p>Posição no Ranking</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $melhor_pontuacao ?></h3>
                        <p>Melhor Pontuação</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-fire"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $maior_streak ?></h3>
                        <p>Maior Sequência</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $total_certificados ?></h3>
                        <p>Certificados</p>
                    </div>
                </div>
            </section>

            <!-- Estatísticas Detalhadas -->
            <section class="progress-section">
                <div class="progress-card">
                    <h3><i class="fas fa-chart-line"></i> Estatísticas de Estudo</h3>
                    <div class="stats-detail">
                        <div class="stat-row">
                            <span>Questões Respondidas:</span>
                            <span class="stat-value"><?= $total_questoes ?></span>
                        </div>
                        <div class="stat-row">
                            <span>Taxa de Acerto:</span>
                            <span class="stat-value"><?= $percentual_acerto ?>%</span>
                        </div>
                        <div class="stat-row">
                            <span>Simulados Realizados:</span>
                            <span class="stat-value"><?= $total_simulados ?></span>
                        </div>
                        <div class="stat-row">
                            <span>Pontos Totais:</span>
                            <span class="stat-value"><?= $pontos_usuario ?></span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Conquistas -->
            <section class="achievements-section">
                <div class="achievements-card">
                    <h3><i class="fas fa-medal"></i> Minhas Conquistas</h3>
                    <?php if (empty($conquistas)): ?>
                        <div class="empty-state">
                            <i class="fas fa-medal"></i>
                            <h3>Nenhuma conquista disponível</h3>
                            <p>As conquistas serão exibidas aqui conforme você progride no sistema.</p>
                        </div>
                    <?php else: ?>
                        <div class="achievements-grid">
                            <?php foreach ($conquistas as $conquista): ?>
                                <div class="achievement-item <?= $conquista['data_conquista'] ? 'unlocked' : 'locked' ?>">
                                    <div class="achievement-icon"><?= htmlspecialchars($conquista['icone']) ?></div>
                                    <div class="achievement-info">
                                        <h4><?= htmlspecialchars($conquista['nome']) ?></h4>
                                        <p><?= htmlspecialchars($conquista['descricao']) ?></p>
                                        <?php if ($conquista['data_conquista']): ?>
                                            <small>Conquistada em <?= date('d/m/Y', strtotime($conquista['data_conquista'])) ?></small>
                                        <?php else: ?>
                                            <small><?= $conquista['pontos_necessarios'] ?> pontos necessários</small>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <!-- Certificados -->
            <section class="certificates-section">
                <div class="achievements-card">
                    <h3><i class="fas fa-certificate"></i> Meus Certificados</h3>
                    <?php if ($total_certificados == 0): ?>
                        <div class="empty-state">
                            <i class="fas fa-certificate"></i>
                            <h3>Nenhum certificado disponível</h3>
                            <p>Assista às videoaulas para ganhar certificados!</p>
                        </div>
                    <?php else: ?>
                        <div class="certificates-grid">
                            <?php
                            // Obter detalhes dos certificados
                            $stmt = $pdo->prepare("
                                SELECT v.titulo, vc.nome as categoria, vp.data_conclusao 
                                FROM videoaulas_progresso vp 
                                JOIN videoaulas v ON vp.videoaula_id = v.id 
                                JOIN videoaulas_categorias vc ON v.categoria_id = vc.id
                                WHERE vp.usuario_id = ? AND vp.concluida = 1
                                ORDER BY vp.data_conclusao DESC
                            ");
                            $stmt->execute([$_SESSION["usuario_id"]]);
                            $certificados = $stmt->fetchAll();
                            ?>
                            <?php foreach ($certificados as $certificado): ?>
                                <div class="certificate-item">
                                    <div class="certificate-icon">
                                        <i class="fas fa-certificate"></i>
                                    </div>
                                    <div class="certificate-info">
                                        <h4><?= htmlspecialchars($certificado['titulo']) ?></h4>
                                        <p><?= htmlspecialchars($certificado['categoria']) ?></p>
                                        <small>Concluído em <?= date('d/m/Y', strtotime($certificado['data_conclusao'])) ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>

    <!-- Theme Logic -->
    <script src="assets/js/theme.js"></script>
</body>
</html>
