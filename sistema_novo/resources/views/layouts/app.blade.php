<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'RCP Sistema de Concursos')</title>
    <!-- Assets -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-new.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
    <!-- Alternativa caso o CSS principal não tenha sido copiado ou não funcione -->
    <link rel="stylesheet" href="{{ asset('assets/css/game.css') }}"> 
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/imagens/icon/iconeweb.png') }}" type="image/png">
    
    @stack('styles')
</head>
<body>
    
    @if(!request()->routeIs('planos'))
        @include('includes.sidebar')
        <div class="content-with-sidebar">
    @else
        <div class="content-full">
    @endif

        <div class="container" style="{{ request()->routeIs('planos') ? 'margin-left: 0; max-width: 100%;' : '' }}">
            
            @include('includes.header')

            @yield('content')
            
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/theme.js') }}"></script>
    <script>
        // Sidebar mobile toggle logic
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            
            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('open');
                });
                
                document.addEventListener('click', function(e) {
                    if (window.innerWidth <= 768) {
                        if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                            sidebar.classList.remove('open');
                        }
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
