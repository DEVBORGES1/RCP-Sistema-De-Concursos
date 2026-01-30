@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <h1><i class="fas fa-upload"></i> Upload de Edital</h1>
            <div class="user-info">
                <a href="{{ route('editais.index') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i>
                    <span>Voltar</span>
                </a>
            </div>
        </div>
    </header>

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-times-circle"></i> {{ session('error') }}
        </div>
    @endif

    <!-- Upload Form -->
    <section class="upload-section">
        <div class="card">
            <h2><i class="fas fa-file-pdf"></i> Enviar Edital</h2>
            <p>Envie o PDF do edital para análise automática das disciplinas e geração de questões.</p>
            
            <form action="{{ route('editais.store') }}" method="POST" enctype="multipart/form-data" class="upload-form">
                @csrf
                
                <!-- Blocos de Metadados -->
                <div class="metadata-section" style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 10px; margin-bottom: 25px; border: 1px solid var(--border-color);">
                    <h3 style="color: var(--primary-color); margin-bottom: 15px; font-size: 1.2rem;"><i class="fas fa-info-circle"></i> Dados do Concurso</h3>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                        <div>
                            <label for="orgao">Órgão (Ex: Prefeitura de...):</label>
                            <input type="text" name="orgao" id="orgao" class="form-control" placeholder="Digite o órgão...">
                        </div>
                        <div>
                            <label for="banca">Banca Organizadora:</label>
                            <input type="text" name="banca" id="banca" class="form-control" placeholder="Ex: FGV, Cebraspe...">
                        </div>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                         <div style="display: flex; gap: 10px;">
                             <div style="flex: 1;">
                                <label for="estado">UF:</label>
                                <select name="estado" id="estado" class="form-control" style="background: var(--bg-input);">
                                    <option value="">UF</option>
                                </select>
                             </div>
                             <div style="flex: 3;">
                                <label for="cidade">Município da Prova:</label>
                                <select name="cidade" id="cidade" class="form-control" style="background: var(--bg-input);" disabled>
                                    <option value="">Selecione o Estado primeiro...</option>
                                </select>
                             </div>
                        </div>
                        <div>
                            <label for="ano">Ano do Concurso:</label>
                            <input type="number" name="ano" id="ano" class="form-control" placeholder="202X" value="{{ date('Y') }}">
                        </div>
                    </div>
                    
                    <div style="margin-top: 15px; border-top: 1px dashed var(--border-color); padding-top: 15px;">
                        <label for="cargo_alvo" style="color: #00C851; font-weight: bold;">Cargo Alvo (Foco da Análise):</label>
                        <input type="text" name="cargo_alvo" id="cargo_alvo" class="form-control" placeholder="Ex: Agente Administrativo, Técnico em Enfermagem..." required style="border-color: #00C851;">
                        <small style="color: #bbb;">A IA focará exclusivamente neste cargo para extrair o conteúdo.</small>
                    </div>
                </div>

                <div class="form-group">
                    <label for="edital">Upload do Edital (PDF Completo ou Trecho):</label>
                    <div class="file-input-wrapper">
                        <input type="file" id="edital" name="edital" accept=".pdf" onchange="updateFileName(this)">
                        <label for="edital" class="file-input-label">
                            <i class="fas fa-cloud-upload-alt"></i>
                            <span id="file-label-text">Clique para selecionar o PDF</span>
                        </label>
                    </div>
                    @error('edital')
                        <div class="text-danger" style="margin-top: 10px; color: #ff4444;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="divider" style="display: flex; align-items: center; margin: 30px 0; color: var(--text-secondary);">
                    <div style="flex: 1; height: 1px; background: var(--border-color);"></div>
                    <span style="padding: 0 15px; font-weight: 600;">OU COLE O TEXTO</span>
                    <div style="flex: 1; height: 1px; background: var(--border-color);"></div>
                </div>

                <div class="form-group">
                    <label for="texto_manual">Cole APENAS o trecho com as vagas e conteúdo programático:</label>
                    <textarea name="texto_manual" id="texto_manual" rows="10" class="form-control" placeholder="Se preferir, copie e cole aqui apenas a parte do edital que fala do seu Cargo e do Conteúdo Programático (Anexos, Quadros, etc). Isso melhora a precisão da IA." style="width: 100%; background: var(--bg-input); border: 1px solid var(--border-color); color: var(--text-primary); border-radius: 10px; padding: 15px;"></textarea>
                </div>
                
                <button type="submit" class="btn-primary" style="margin-top: 20px; width: 100%; justify-content: center;">
                    <i class="fas fa-magic"></i> Analisar Edital e Gerar Matérias
                </button>
            </form>
        </div>
    </section>

    <!-- Features Info -->
    <section class="features-info">
        <div class="card">
            <h3><i class="fas fa-magic"></i> O que acontece após o upload?</h3>
            <div class="features-grid">
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <h4>Análise Automática</h4>
                    <p>O sistema analisa o texto do edital e identifica automaticamente as disciplinas.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h4>Disciplinas Detectadas</h4>
                    <p>As disciplinas são cadastradas automaticamente no sistema.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <h4>Questões Geradas</h4>
                    <p>Questões de exemplo são criadas automaticamente para cada disciplina.</p>
                </div>
                
                <div class="feature-item">
                    <div class="feature-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h4>Simulados Prontos</h4>
                    <p>Você pode criar simulados imediatamente com as disciplinas detectadas.</p>
                </div>
            </div>
        </div>
    </section>
</div>


    <!-- Loading Overlay -->
    <div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.85); z-index: 9999; flex-direction: column; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
        <div class="loader-content" style="text-align: center; color: white;">
            <div class="spinner" style="margin-bottom: 20px;">
                <i class="fas fa-brain fa-spin" style="font-size: 4rem; color: #ff4444; filter: drop-shadow(0 0 10px #ff0000);"></i>
            </div>
            <h2 style="font-size: 2rem; margin-bottom: 10px;">IA Analisando Edital...</h2>
            <p style="font-size: 1.2rem; color: #ccc;">Isso pode levar alguns segundos. Estamos identificando cargos, banca e disciplinas.</p>
            <div class="progress-bar-container" style="width: 300px; height: 6px; background: #333; border-radius: 3px; margin: 20px auto; overflow: hidden;">
                <div class="progress-bar-fill" style="width: 0%; height: 100%; background: #ff4444; transition: width 0.5s;"></div>
            </div>
        </div>
    </div>

</div>

<script>
    function updateFileName(input) {
        const label = document.getElementById('file-label-text');
        if (input.files && input.files[0]) {
            label.textContent = `Arquivo selecionado: ${input.files[0].name}`;
        } else {
            label.textContent = 'Clique para selecionar o arquivo PDF';
        }
    }

    document.querySelector('.upload-form').addEventListener('submit', function(e) {
        document.getElementById('loading-overlay').style.display = 'flex';
        
        let width = 0;
        const bar = document.querySelector('.progress-bar-fill');
        setInterval(() => {
            if(width < 90) { 
                width += 1;
                bar.style.width = width + '%';
            }
        }, 100);
    });

    // IBGE API Integration
    document.addEventListener('DOMContentLoaded', function() {
        const estadoSelect = document.getElementById('estado');
        const cidadeSelect = document.getElementById('cidade');

        // Fetch Estados
        fetch('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome')
            .then(response => response.json())
            .then(estados => {
                estados.forEach(estado => {
                    const option = document.createElement('option');
                    option.value = estado.sigla;
                    option.textContent = estado.sigla;
                    option.setAttribute('data-id', estado.id);
                    estadoSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Erro ao carregar estados:', error));

        // On Estado Change -> Fetch Cidades
        estadoSelect.addEventListener('change', function() {
            const uf = this.value;
            cidadeSelect.innerHTML = '<option value="">Carregando...</option>';
            cidadeSelect.disabled = true;

            if (uf) {
                fetch(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios`)
                    .then(response => response.json())
                    .then(cidades => {
                        cidadeSelect.innerHTML = '<option value="">Selecione a cidade</option>';
                        cidades.forEach(cidade => {
                            const option = document.createElement('option');
                            option.value = cidade.nome;
                            option.textContent = cidade.nome;
                            cidadeSelect.appendChild(option);
                        });
                        cidadeSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Erro ao carregar cidades:', error);
                        cidadeSelect.innerHTML = '<option value="">Erro ao carregar</option>';
                    });
            } else {
                cidadeSelect.innerHTML = '<option value="">Selecione o Estado primeiro...</option>';
                cidadeSelect.disabled = true;
            }
        });
    });
</script>

<style>
    .file-input-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .file-input-wrapper input[type="file"] {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 2;
    }
    
    .file-input-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 40px 20px;
        border: 3px dashed var(--primary-color);
        border-radius: 15px;
        background: var(--bg-input);
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
        color: var(--text-primary);
    }
    
    .file-input-wrapper input[type="file"]:hover + .file-input-label,
    .file-input-label:hover {
        border-color: #cc0000;
        background: var(--bg-card-hover);
        transform: translateY(-2px);
    }
    
    .file-input-label i {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 10px;
    }
    
    .file-input-label span {
        color: var(--text-primary);
        font-weight: 500;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }
    
    .feature-item {
        text-align: center;
        padding: 20px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .feature-item:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }
    
    .feature-icon {
        margin-bottom: 15px;
    }
    
    .feature-icon i {
        font-size: 2rem;
        color: var(--primary-color);
    }
    
    .feature-item h4 {
        color: var(--text-primary);
        margin-bottom: 10px;
    }
    
    .feature-item p {
        color: var(--text-secondary);
        font-size: 0.9rem;
        line-height: 1.5;
    }
</style>
@endsection
