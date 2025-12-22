<?php
if (!isset($active_page)) {
    $active_page = basename($_SERVER['PHP_SELF']);
}
?>
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2><i class="fas fa-graduation-cap"></i> RCP Concursos</h2>
        <p>Sistema de Estudos</p>
    </div>
    <div class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">Navegação</div>
            <a href="dashboard.php" class="nav-item <?= $active_page == 'dashboard.php' ? 'active' : '' ?>">
                <i class="fas fa-home"></i>
                <span>Dashboard</span>
            </a>
            <a href="perfil.php" class="nav-item <?= $active_page == 'perfil.php' ? 'active' : '' ?>">
                <i class="fas fa-user"></i>
                <span>Meu Perfil</span>
            </a>
            <a href="simulados.php" class="nav-item <?= $active_page == 'simulados.php' ? 'active' : '' ?>">
                <i class="fas fa-clipboard-list"></i>
                <span>Simulados</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Estudos</div>
            <a href="questoes.php" class="nav-item <?= $active_page == 'questoes.php' ? 'active' : '' ?>">
                <i class="fas fa-question-circle"></i>
                <span>Banco de Questões</span>
            </a>
            <a href="videoaulas.php" class="nav-item <?= $active_page == 'videoaulas.php' ? 'active' : '' ?>">
                <i class="fas fa-play-circle"></i>
                <span>Videoaulas</span>
            </a>
            <a href="editais.php" class="nav-item <?= $active_page == 'editais.php' ? 'active' : '' ?>">
                <i class="fas fa-file-alt"></i>
                <span>Meus Editais</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Ferramentas</div>
            <a href="upload_edital.php" class="nav-item <?= $active_page == 'upload_edital.php' ? 'active' : '' ?>">
                <i class="fas fa-upload"></i>
                <span>Upload Edital</span>
            </a>
            <a href="gerar_cronograma.php" class="nav-item <?= $active_page == 'gerar_cronograma.php' ? 'active' : '' ?>">
                <i class="fas fa-calendar-alt"></i>
                <span>Gerar Cronograma</span>
            </a>
            <a href="dashboard_avancado.php" class="nav-item <?= $active_page == 'dashboard_avancado.php' ? 'active' : '' ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard Avançado</span>
            </a>
        </div>
        
        <div class="nav-section">
            <div class="nav-section-title">Conta</div>
            <a href="logout.php" class="nav-item">
                <i class="fas fa-sign-out-alt"></i>
                <span>Sair</span>
            </a>
        </div>
    </div>
</nav>

<!-- Mobile Sidebar Toggle -->
<button class="sidebar-toggle" id="sidebarToggle">
    <i class="fas fa-bars"></i>
</button>
