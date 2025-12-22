<header class="header">
    <div class="header-content">
        <h1><i class="fas fa-graduation-cap"></i> RCP - Sistema de Concursos</h1>
        <div class="header-actions">
            <!-- Theme Toggle Button -->
            <button id="themeToggle" class="theme-toggle" title="Alternar Tema">
                <i class="fas fa-moon"></i>
            </button>
            <div class="user-info">
                <div class="user-level">
                    <span class="level-badge">Nível <?= isset($nivel_usuario) ? $nivel_usuario : '1' ?></span>
                    <span class="points"><?= isset($pontos_usuario) ? $pontos_usuario : '0' ?> pts</span>
                </div>
                <div class="user-avatar-mini">
                    <i class="fas fa-user-circle"></i>
                </div>
                <a href="logout.php" class="logout-btn" title="Sair"><i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </div>
</header>
