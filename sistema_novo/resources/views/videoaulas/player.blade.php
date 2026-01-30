@extends('layouts.app')

@section('title', $videoaula->titulo . ' - Videoaula')

@section('content')
<div class="container">
    <div class="header-content" style="display: flex;justify-content: space-between;align-items: center;margin-bottom: 30px;">
        <h1 style="font-size: 1.8rem;color:white;"><i class="{{ $videoaula->categoria_icone }}"></i> {{ $videoaula->titulo }}</h1>
        <div class="user-info">
            <a href="{{ route('videoaulas.show', $videoaula->categoria_id) }}" class="btn-secondary" style="padding: 10px 20px;text-decoration: none;display: flex;align-items: center;gap: 5px;">
                <i class="fas fa-arrow-left"></i>
                <span>Voltar</span>
            </a>
        </div>
    </div>

    <div class="videoaula-container" style="max-width: 1200px; margin: 0 auto;">
        
        <div class="videoaula-header" style="background: linear-gradient(135deg, {{ $videoaula->categoria_cor }} 0%, {{ $videoaula->categoria_cor }}dd 100%); color: white; padding: 30px; border-radius: 20px; margin-bottom: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <h1 style="margin: 0 0 10px 0; font-size: 2.2em; display: flex; align-items: center; gap: 15px;">
                <i class="fas fa-play-circle"></i> {{ $videoaula->titulo }}
            </h1>
            <p style="margin: 0; opacity: 0.9; font-size: 1.1em;">{{ $videoaula->descricao }}</p>
            
            <div class="videoaula-meta" style="display: flex; gap: 20px; margin-top: 15px; flex-wrap: wrap;">
                <div class="meta-item" style="display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 20px; font-size: 0.9em;">
                    <i class="fas fa-layer-group"></i>
                    <span>{{ $videoaula->categoria_nome }}</span>
                </div>
                <div class="meta-item" style="display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 20px; font-size: 0.9em;">
                    <i class="fas fa-clock"></i>
                    <span>{{ $videoaula->duracao }} minutos</span>
                </div>
                @if ($videoaula->concluida)
                    <div class="meta-item" style="display: flex; align-items: center; gap: 8px; background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 20px; font-size: 0.9em;">
                        <i class="fas fa-check-circle"></i>
                        <span>Concluída</span>
                    </div>
                @endif
            </div>
        </div>

        <div class="videoaula-content" style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px; margin-bottom: 30px;">
            <!-- Player e Info -->
            <div class="videoaula-player" style="background: rgba(255,255,255,0.05); border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                <div class="video-player" id="videoPlayer" style="width: 100%; height: 500px; border-radius: 10px; margin-bottom: 20px; background: #000; overflow: hidden; position: relative;">
                    @php
                        $url = $videoaula->url_video;
                        $videoId = null;
                        if (strpos($url, 'embed/') !== false) {
                             preg_match('/embed\/([a-zA-Z0-9_-]{11})/', $url, $matches);
                             $videoId = $matches[1] ?? null;
                        } elseif (strpos($url, 'youtu.be/') !== false) {
                             preg_match('/youtu\.be\/([a-zA-Z0-9_-]{11})/', $url, $matches);
                             $videoId = $matches[1] ?? null;
                        } elseif (strpos($url, 'watch') !== false) {
                             preg_match('/[?&]v=([a-zA-Z0-9_-]{11})/', $url, $matches);
                             $videoId = $matches[1] ?? null;
                        }
                    @endphp

                    @if ($videoId)
                        <iframe id="ytplayer" width="100%" height="100%" 
                            src="https://www.youtube.com/embed/{{ $videoId }}?rel=0&modestbranding=1&enablejsapi=1" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen>
                        </iframe>
                    @else
                        <div style="text-align: center; padding: 50px; color: white; display: flex; flex-direction: column; justify-content: center; height: 100%;">
                            <i class="fas fa-exclamation-triangle" style="font-size: 3em; margin-bottom: 15px; color: #f39c12;"></i>
                            <p>URL do vídeo inválida ou não reconhecida</p>
                        </div>
                    @endif
                </div>

                <div class="videoaula-info">
                    <h3 style="margin: 0 0 15px 0; color: white; font-size: 1.3em;"><i class="fas fa-info-circle"></i> Sobre esta videoaula</h3>
                    <p style="margin: 0 0 15px 0; color: #ccc; line-height: 1.6;">{{ $videoaula->descricao }}</p>
                    
                    <div class="videoaula-actions" style="display: flex; gap: 15px; margin-top: 20px;">
                        @if ($videoaula->concluida)
                            <button class="btn-videoaula" style="flex: 1; padding: 15px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: default; background: #6c757d; color: white;">
                                <i class="fas fa-check"></i> Concluída
                            </button>
                            <button class="btn-videoaula" onclick="marcarComoNaoConcluida()" style="flex: 1; padding: 15px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; background: {{ $videoaula->categoria_cor }}; color: white; transition: 0.3s;">
                                <i class="fas fa-redo"></i> Reassistir
                            </button>
                        @else
                            <button class="btn-videoaula" onclick="marcarComoConcluida()" style="flex: 1; padding: 15px 25px; border: none; border-radius: 10px; font-weight: 600; cursor: pointer; background: {{ $videoaula->categoria_cor }}; color: white; transition: 0.3s;">
                                <i class="fas fa-check"></i> Marcar como Concluída
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="videoaula-sidebar" style="display: flex; flex-direction: column; gap: 20px;">
                <!-- Progresso -->
                <div class="progress-card" style="background: rgba(255,255,255,0.05); border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <div class="progress-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                        <span class="progress-label" style="font-size: 1.1em; font-weight: 600; color: white;">Seu Progresso</span>
                        <span class="progress-percentage" style="font-size: 1.3em; font-weight: bold; color: {{ $videoaula->categoria_cor }};">{{ $progresso_percentual }}%</span>
                    </div>
                    <div class="progress-bar" style="width: 100%; height: 12px; background: rgba(255,255,255,0.1); border-radius: 10px; overflow: hidden; margin-bottom: 15px;">
                        <div class="progress-fill" style="height: 100%; background: linear-gradient(90deg, {{ $videoaula->categoria_cor }}, {{ $videoaula->categoria_cor }}88); width: {{ $progresso_percentual }}%;"></div>
                    </div>
                    
                    <div class="progress-stats" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                        <div class="stat-item" style="text-align: center; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 10px; border-left: 4px solid {{ $videoaula->categoria_cor }};">
                            <h4 style="margin: 0; color: {{ $videoaula->categoria_cor }}; font-size: 1.5em; font-weight: bold;">{{ $videoaula->duracao }}</h4>
                            <p style="margin: 5px 0 0 0; color: #ccc; font-size: 0.9em;">Minutos</p>
                        </div>
                        <div class="stat-item" style="text-align: center; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 10px; border-left: 4px solid {{ $videoaula->categoria_cor }};">
                            <h4 style="margin: 0; color: {{ $videoaula->categoria_cor }}; font-size: 1.5em; font-weight: bold;">{{ round($videoaula->tempo_assistido / 60, 1) }}</h4>
                            <p style="margin: 5px 0 0 0; color: #ccc; font-size: 0.9em;">Assistidos</p>
                        </div>
                    </div>
                </div>
                
                <!-- Relacionadas -->
                @if ($relacionadas->count() > 0)
                <div class="related-videoaulas" style="background: rgba(255,255,255,0.05); border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">
                    <h3 style="margin: 0 0 20px 0; color: white; font-size: 1.3em;"><i class="fas fa-list"></i> Relacionadas</h3>
                    <div class="related-list" style="display: flex; flex-direction: column; gap: 10px;">
                        @foreach ($relacionadas as $relacionada)
                            <a href="{{ route('videoaulas.player', $relacionada->id) }}" class="related-item" style="display: flex; align-items: center; gap: 15px; padding: 15px; background: rgba(255,255,255,0.05); border-radius: 10px; text-decoration: none; color: white; transition: all 0.3s ease;">
                                <div class="related-item-icon" style="width: 40px; height: 40px; border-radius: 8px; background: {{ $videoaula->categoria_cor }}; display: flex; align-items: center; justify-content: center; color: white;">
                                    <i class="fas fa-play"></i>
                                </div>
                                <div class="related-item-info">
                                    <h4 style="margin: 0; font-size: 1em;">{{ $relacionada->titulo }}</h4>
                                    <p style="margin: 5px 0 0 0; color: #ccc; font-size: 0.8em;">{{ $relacionada->duracao }} min</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    function marcarComoConcluida() {
        if (confirm('Deseja marcar esta videoaula como concluída?')) {
            atualizarProgresso({{ $videoaula->duracao * 60 }}, 1);
        }
    }
    
    function marcarComoNaoConcluida() {
        if (confirm('Deseja marcar esta videoaula como não concluída?')) {
            atualizarProgresso(0, 0);
        }
    }
    
    function atualizarProgresso(tempoAssistido, concluida) {
        fetch('{{ route("videoaulas.progresso", $videoaula->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                tempo_assistido: tempoAssistido,
                concluida: concluida
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erro ao atualizar progresso');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro ao atualizar progresso');
        });
    }

    // YouTube API Integration if needed
    @if ($videoId)
        var tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        var firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        
        var player;
        function onYouTubeIframeAPIReady() {
            player = new YT.Player('ytplayer', {
                events: {
                    'onStateChange': onPlayerStateChange
                }
            });
        }
        
        function onPlayerStateChange(event) {
            if (event.data == YT.PlayerState.ENDED) {
                atualizarProgresso({{ $videoaula->duracao * 60 }}, 1);
            }
        }
    @endif
</script>
@endsection
