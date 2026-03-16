<header class="header" style="background: var(--dash-bg-card, rgba(15, 23, 42, 0.4)); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border-bottom: 1px solid var(--dash-border, rgba(255, 255, 255, 0.08)); padding: 16px 32px; border-radius: 0 0 24px 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); margin-bottom: 24px;">
    @php $user = Auth::user(); /** @var \App\Models\User $user */ @endphp
    <div class="header-content" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
        <h1 style="font-family: var(--dash-font-heading, 'Syne', sans-serif); font-size: 1.25rem; font-weight: 700; color: var(--dash-text-primary, #f8fafc); margin: 0; display: flex; align-items: center; gap: 8px;">
            @if($user)
                <span style="font-weight: 400; opacity: 0.7; font-size: 0.95em;">{{ explode(' ', $user->nome)[0] }}</span>
                <span style="color: var(--dash-text-secondary, rgba(255,255,255,0.2));">/</span>
            @endif
            <span style="background: linear-gradient(135deg, #8b5cf6, #10b981); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent; font-weight: 800; letter-spacing: -0.02em;">RCP Estudos</span>
        </h1>
        <div class="header-actions" style="display: flex; align-items: center; gap: 20px;">
            <!-- Theme Toggle Button -->
            <button id="themeToggle" class="theme-toggle" title="Alternar Tema" style="background: var(--dash-border, rgba(255,255,255,0.05)); border: 1px solid var(--dash-border, rgba(255,255,255,0.1)); width: 40px; height: 40px; border-radius: 50%; color: var(--dash-text-secondary, #94a3b8); cursor: pointer; transition: all 0.3s ease;">
                <i class="fas fa-moon"></i>
            </button>
            @if($user)
                <div class="user-info" style="display: flex; align-items: center; gap: 16px; background: var(--dash-border, rgba(255,255,255,0.03)); padding: 6px 16px; border-radius: 50px; border: 1px solid var(--dash-border, rgba(255,255,255,0.05));">
                    <div class="user-level" style="display: flex; flex-direction: column; text-align: right; line-height: 1.2;">
                        <span class="level-badge" style="font-size: 0.8rem; font-weight: 700; color: var(--dash-accent, #10b981); text-transform: uppercase;">Nível {{ $nivel_usuario ?? '1' }}</span>
                        <span class="points" style="font-size: 0.85rem; color: var(--dash-text-secondary, #94a3b8); font-family: var(--dash-font-heading, 'Syne', sans-serif);">{{ $pontos_usuario ?? '0' }} xp</span>
                    </div>
                    <div class="user-avatar-mini" style="position: relative;">
                        @if($user->foto_perfil)
                            <img src="{{ asset($user->foto_perfil) }}" alt="" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(139, 92, 246, 0.5); box-shadow: 0 0 10px rgba(139, 92, 246, 0.3);">
                        @else
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #8b5cf6, #10b981); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                                {{ substr($user->nome, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline; margin-left: 8px;">
                        @csrf
                        <button type="submit" class="logout-btn" title="Sair" style="background: rgba(239, 68, 68, 0.1); border: 1px solid rgba(239, 68, 68, 0.2); color: #ef4444; width: 36px; height: 36px; border-radius: 50%; cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            @else
                <!-- Botão de Login Rápido para visitantes -->
                <a href="{{ route('login') }}" class="btn-new-crono" style="background: rgba(139, 92, 246, 0.1); color: #8b5cf6; padding: 8px 20px; border-radius: 50px; text-decoration: none; font-weight: 600; font-family: var(--dash-font-heading, 'Syne', sans-serif); border: 1px solid rgba(139, 92, 246, 0.2); transition: all 0.3s ease;">
                    <i class="fas fa-sign-in-alt"></i> Entrar
                </a>
            @endif
        </div>
    </div>
</header>
