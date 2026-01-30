<aside class="sidebar" id="sidebar">
    <!-- Brand -->
    <div class="sidebar-brand">
        <div class="brand-icon">
            <i class="fas fa-graduation-cap"></i>
        </div>
        <div class="brand-text">RCP Concursos</div>
    </div>

    <!-- Navigation -->
    <ul class="sidebar-menu">
        <!-- Main -->
        <div class="menu-label">Início</div>
        <li class="menu-item">
            <a href="{{ route('dashboard') }}" class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home menu-icon"></i>
                <span>Menu Principal</span>
            </a>
        </li>

        <!-- Shortcuts Logic -->
        @if(isset($simuladoAtivo) && $simuladoAtivo)
            <li class="menu-item">
                <a href="{{ route('simulados.show', $simuladoAtivo->id) }}" class="menu-link shortcut-link">
                    <i class="fas fa-play menu-icon" style="color: #4ade80;"></i>
                    <span>Continuar Simulado</span>
                </a>
            </li>
        @endif

        @if(isset($ultimaVideoaula) && $ultimaVideoaula)
            <li class="menu-item">
                <a href="{{ route('videoaulas.player', $ultimaVideoaula->id) }}" class="menu-link shortcut-link">
                    <i class="fas fa-history menu-icon" style="color: #60a5fa;"></i>
                    <span>Continuar Aula</span>
                </a>
            </li>
        @endif

        @if(isset($questoesErradasCount) && $questoesErradasCount > 0)
             <li class="menu-item">
                <a href="{{ route('questoes.index', ['filtro' => 'erradas']) }}" class="menu-link shortcut-link"> <!-- Assuming logic for filter exists or will exist -->
                    <i class="fas fa-exclamation-circle menu-icon" style="color: #f87171;"></i>
                    <span>Revisar Erradas ({{ $questoesErradasCount }})</span>
                </a>
            </li>
        @endif

        <!-- Estudos -->
        <div class="menu-label">Estudos</div>
        <li class="menu-item">
            <a href="{{ route('cronogramas.index') }}" class="menu-link {{ request()->routeIs('cronogramas.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-check menu-icon"></i>
                <span>Meus Cronogramas</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('videoaulas.index') }}" class="menu-link {{ request()->routeIs('videoaulas.*') ? 'active' : '' }}">
                <i class="fas fa-play-circle menu-icon"></i>
                <span>Videoaulas</span>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('simulados.index') }}" class="menu-link {{ request()->routeIs('simulados.*') ? 'active' : '' }}">
                <i class="fas fa-clock menu-icon"></i>
                <div style="flex: 1; display: flex; justify-content: space-between; align-items: center;">
                    <span>Simulados</span>
                    @if(isset($simuladosCount) && $simuladosCount > 0)
                        <span class="badge">{{ $simuladosCount }}</span>
                    @endif
                </div>
            </a>
        </li>

        <!-- Ferramentas -->
        <div class="menu-label">Ferramentas</div>
        <li class="menu-item">
            <a href="{{ route('editais.index') }}" class="menu-link {{ request()->routeIs('editais.index') || request()->routeIs('editais.show') ? 'active' : '' }}">
                <i class="fas fa-file-alt menu-icon"></i>
                <div style="flex: 1; display: flex; justify-content: space-between; align-items: center;">
                    <span>Meus Editais</span>
                    @if(isset($editaisCount) && $editaisCount > 0)
                        <span class="badge">{{ $editaisCount }}</span>
                    @endif
                </div>
            </a>
        </li>
        <li class="menu-item">
            <a href="{{ route('questoes.index') }}" class="menu-link {{ request()->routeIs('questoes.*') ? 'active' : '' }}">
                <i class="fas fa-question-circle menu-icon"></i>
                <span>Banco de Questões</span>
            </a>
        </li>

        <!-- Multiplayer -->
        <div class="menu-label">Multiplayer</div>
        <li class="menu-item">
            <a href="{{ route('jogo.index') }}" class="menu-link {{ request()->routeIs('jogo.*') ? 'active' : '' }}">
                <i class="fas fa-gamepad menu-icon"></i>
                <span>Ranking e Multijogador</span>
            </a>
        </li>

        <!-- Administração (Protected) -->
        @php $user = Auth::user(); /** @var \App\Models\User $user */ @endphp
        @if($user && $user->is_admin)
            <div class="menu-label" style="color: #ff4444;">Admin</div>
            <li class="menu-item">
                <a href="{{ route('admin.videoaulas.index') }}" class="menu-link {{ request()->routeIs('admin.videoaulas.*') ? 'active' : '' }}">
                    <i class="fas fa-cog menu-icon"></i>
                    <span>Gerenciar Videoaulas</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route('admin.questoes.create') }}" class="menu-link {{ request()->routeIs('admin.questoes.*') ? 'active' : '' }}">
                    <i class="fas fa-magic menu-icon"></i>
                    <span>Gerador IA</span>
                </a>
            </li>
        @endif

        <!-- Conta -->
        <div class="menu-label">Conta</div>
        <li class="menu-item">
            <a href="{{ route('perfil.index') }}" class="menu-link {{ request()->routeIs('perfil.*') ? 'active' : '' }}">
                <i class="fas fa-user menu-icon"></i>
                <span>Meu Perfil</span>
            </a>
        </li>
        <li class="menu-item">
           <a href="#" class="menu-link"> <!-- Placeholder for Preferences/Notifications -->
               <i class="fas fa-bell menu-icon"></i>
               <span>Notificações</span>
           </a>
       </li>
        <li class="menu-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="#" onclick="event.preventDefault(); this.closest('form').submit();" class="menu-link">
                    <i class="fas fa-sign-out-alt menu-icon"></i>
                    <span>Sair</span>
                </a>
            </form>
        </li>
    </ul>

    <!-- Action Button (Optional) -->
    <div class="sidebar-action">
        <a href="{{ route('editais.create') }}" class="btn-new-crono">
            <i class="fas fa-plus"></i>
            <span>Novo Edital</span>
        </a>
    </div>
</aside>
