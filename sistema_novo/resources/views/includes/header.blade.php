<header class="header">
    @php $user = Auth::user(); /** @var \App\Models\User $user */ @endphp
    <div class="header-content">
        <h1>
            <span style="font-weight: 300; opacity: 0.8; font-size: 0.9em; margin-right: 10px;">{{ Auth::user()->nome }}</span>
            <span style="color: var(--primary-color); font-weight: 700;">| Sistema de Estudos</span>
        </h1>
        <div class="header-actions">
            <!-- Theme Toggle Button -->
            <button id="themeToggle" class="theme-toggle" title="Alternar Tema">
                <i class="fas fa-moon"></i>
            </button>
            <div class="user-info">
                <div class="user-level">
                    <span class="level-badge">Nível {{ $nivel_usuario ?? '1' }}</span>
                    <span class="points">{{ $pontos_usuario ?? '0' }} pts</span>
                </div>
                <div class="user-avatar-mini">
                    @if(Auth::user()->foto_perfil)
                        <img src="{{ asset(Auth::user()->foto_perfil) }}" alt="" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.2);">
                    @else
                        <i class="fas fa-user-circle"></i>
                    @endif
                </div>
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="logout-btn" title="Sair" style="background: none; border: none; color: inherit; cursor: pointer;">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
