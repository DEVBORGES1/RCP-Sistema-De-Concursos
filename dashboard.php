<?php
session_start();
require __DIR__ . '/app/Classes/Gamificacao.php';
require __DIR__ . '/app/Classes/DashboardService.php';

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$gamificacao = new Gamificacao(); // Usa Database singleton interna
$dashboardService = new DashboardService();

// Atualizar streak ao logar
$gamificacao->atualizarStreak($_SESSION["usuario_id"]);

// Obter dados do usuário
$dados_usuario = $gamificacao->obterDadosUsuario($_SESSION["usuario_id"]);
$conquistas = $gamificacao->obterConquistasUsuario($_SESSION["usuario_id"]);
$ranking = $gamificacao->obterRankingMensal(5); // Top 5
$posicao_usuario = $gamificacao->obterPosicaoUsuario($_SESSION["usuario_id"]);

// Garantir que os dados do usuário tenham valores padrão
$nome_usuario = isset($dados_usuario['nome']) ? $dados_usuario['nome'] : 'Usuário';
$nivel_usuario = isset($dados_usuario['nivel']) ? (int)$dados_usuario['nivel'] : 1;
$pontos_usuario = isset($dados_usuario['pontos_total']) ? (int)$dados_usuario['pontos_total'] : 0;
$streak_usuario = isset($dados_usuario['streak_dias']) ? (int)$dados_usuario['streak_dias'] : 0;

// Obter estatísticas
$estatisticas = $dashboardService->getEstatisticas($_SESSION["usuario_id"]);
$total_questoes = $estatisticas['total_questoes'];
$percentual_acerto = $estatisticas['percentual_acerto'];
$total_editais = $estatisticas['total_editais'];
$total_simulados = $estatisticas['total_simulados'];

// Set active page for sidebar
$active_page = 'dashboard.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Concursos</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" href="assets/imagens/icon/iconeweb.png" type="image/png">
</head>
<body>
    
    <?php include 'includes/sidebar.php'; ?>

    <div class="content-with-sidebar">
        <div class="container">
            
            <?php include 'includes/header.php'; ?>

            <!-- Welcome Section -->
            <section class="welcome-section">
                <div class="welcome-card">
                    <div class="welcome-header">
                        <div>
                            <h2>Olá, <?= htmlspecialchars($nome_usuario) ?>! </h2>
                            <p>Continue estudando para alcançar seus objetivos!</p>
                        </div>
                        <div class="streak-info">
                            <i class="fas fa-fire"></i>
                            <span><?= $streak_usuario ?> dias seguidos</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Grid -->
            <section class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $total_questoes ?></h3>
                        <p>Questões Respondidas</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $percentual_acerto ?>%</h3>
                        <p>Taxa de Acerto</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-file-pdf"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $total_editais ?></h3>
                        <p>Editais Enviados</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="stat-content">
                        <h3><?= $total_simulados ?></h3>
                        <p>Simulados Realizados</p>
                    </div>
                </div>
            </section>

            <div class="dashboard-grid">
                <!-- Main Content Column -->
                <div class="main-column">
                    <!-- Progress Section -->
                    <section class="progress-section">
                        <div class="progress-card">
                            <h3><i class="fas fa-chart-line"></i> Seu Progresso</h3>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= min(100, $pontos_usuario / 10) ?>%"></div>
                            </div>
                            <p><?= $pontos_usuario ?> pontos para o próximo nível (Meta: <?= ($nivel_usuario * 1000) ?>)</p>
                        </div>
                    </section>

                    <!-- Achievements Section -->
                    <section class="achievements-section">
                        <div class="achievements-card">
                            <h3><i class="fas fa-medal"></i> Recentes</h3>
                            <?php if (empty($conquistas)): ?>
                                <div class="empty-state">
                                    <i class="fas fa-medal"></i>
                                    <h3>Nenhuma conquista ainda</h3>
                                    <p>Continue estudando para desbloquear!</p>
                                </div>
                            <?php else: ?>
                                <div class="achievements-grid">
                                    <?php 
                                    // Mostrar apenas as 3 últimas
                                    $recentes = array_slice($conquistas, 0, 3);
                                    foreach ($recentes as $conquista): 
                                    ?>
                                        <div class="achievement-item <?= $conquista['data_conquista'] ? 'unlocked' : 'locked' ?>">
                                            <div class="achievement-icon"><?= htmlspecialchars($conquista['icone']) ?></div>
                                            <div class="achievement-info">
                                                <h4><?= htmlspecialchars($conquista['nome']) ?></h4>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </section>
                </div>

                <!-- Sidebar Column -->
                <div class="side-column">
                    <!-- Ranking Section -->
                    <section class="ranking-section">
                        <div class="ranking-card">
                            <h3><i class="fas fa-trophy"></i> Top 5 Mensal</h3>
                            <div class="ranking-list">
                                <?php if (!empty($ranking)): ?>
                                    <?php foreach ($ranking as $index => $user): ?>
                                        <div class="ranking-item <?= $user['posicao'] == $posicao_usuario ? 'current-user' : '' ?>">
                                            <span class="position"><?= $user['posicao'] ?>º</span>
                                            <span class="name"><?= htmlspecialchars($user['nome']) ?></span>
                                            <span class="points"><?= $user['pontos_mes'] ?></span>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p>Ranking vazio.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            <!-- Quick Actions -->
            <section class="quick-actions">
                <h3><i class="fas fa-bolt"></i> Ações Rápidas</h3>
                <div class="actions-grid">
                    <a href="upload_edital.php" class="action-btn">
                        <i class="fas fa-upload"></i>
                        <span>Upload Edital</span>
                    </a>
                    <a href="questoes.php" class="action-btn">
                        <i class="fas fa-question-circle"></i>
                        <span>Questões</span>
                    </a>
                    <a href="simulados.php" class="action-btn">
                        <i class="fas fa-clipboard-list"></i>
                        <span>Simulados</span>
                    </a>
                    <a href="videoaulas.php" class="action-btn">
                        <i class="fas fa-play-circle"></i>
                        <span>Aulas</span>
                    </a>
                </div>
            </section>
        </div>
    </div>

    <!-- Theme and Logic Script -->
    <script src="assets/js/theme.js"></script>
</body>
</html>