<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificado - {{ $categoria->nome }}</title>
    <style>
        @page {
            size: A4 landscape;
            margin: 0; 
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: "Times New Roman", serif;
            background: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #333;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-family: sans-serif;
            z-index: 9999;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        
        .certificado {
            background: #ffffff;
            width: 1000px; /* Fixed width for better control */
            height: 700px; /* Fixed height roughly A4 landscape ratio */
            padding: 60px 80px;
            border: 20px solid #1a1a1a;
            box-shadow: 0 10px 50px rgba(0,0,0,0.3);
            position: relative;
        }
        
        .borda-dourada {
            border: 8px solid #d4af37;
            padding: 40px;
            position: relative;
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        
        .cabecalho {
            text-align: center;
        }
        
        .titulo-principal {
            font-size: 42px;
            font-weight: bold;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 10px;
            border-bottom: 4px solid #d4af37;
            padding-bottom: 15px;
            display: inline-block;
            width: 80%;
        }
        
        .subtitulo {
            font-size: 24px;
            color: #333;
            font-style: italic;
            margin-top: 10px;
        }
        
        .conteudo {
            text-align: center;
            line-height: 1.6;
        }
        
        .texto-principal {
            font-size: 20px;
            color: #2c2c2c;
            margin: 20px 0;
            text-align: justify;
            text-align-last: center;
        }
        
        .nome-usuario {
            font-size: 32px;
            font-weight: bold;
            color: #1a1a1a;
            text-transform: uppercase;
            border-bottom: 2px solid #d4af37;
            padding: 0 20px;
            margin: 10px 0;
            display: block;
        }
        
        .materia {
            font-size: 28px;
            font-weight: bold;
            color: #cc0000;
            text-transform: uppercase;
            margin: 15px 0;
        }
        
        .estatisticas {
            background: #f9f9f9;
            border: 2px solid #d4af37;
            border-radius: 10px;
            padding: 15px;
            margin: 20px auto;
            display: flex;
            justify-content: space-around;
            width: 80%;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-numero {
            font-size: 24px;
            font-weight: bold;
            color: #cc0000;
        }
        
        .stat-label {
            font-size: 14px;
            color: #666;
            margin-top: 5px;
        }
        
        .texto-secundario {
            font-size: 16px;
            color: #444;
            font-style: italic;
            margin-top: 20px;
        }
        
        .rodape {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
        }
        
        .assinatura {
            text-align: center;
            width: 250px;
        }
        
        .linha-assinatura {
            border-top: 2px solid #1a1a1a;
            padding-top: 10px;
            font-size: 16px;
            font-weight: bold;
        }
        
        .data {
            text-align: center;
            font-size: 18px;
            color: #555;
        }
        
        .selo {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 100px;
            height: 100px;
            background: #cc0000;
            border: 4px solid #d4af37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
        }
        
        .numero-certificado {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 10px;
            color: #999;
            font-family: monospace;
        }
        
        @media print {
            body {
                background: white;
                padding: 0;
                display: block;
            }
            .no-print { display: none; }
            .certificado {
                box-shadow: none;
                width: 100%;
                height: 100vh;
                border: none;
                padding: 1cm;
                page-break-after: always;
            }
            .borda-dourada {
                border: 5px solid #d4af37;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    @php /** @var \App\Models\User $user */ @endphp
    <button onclick="window.print()" class="no-print">🖨️ Imprimir / Salvar PDF</button>

    <div class="certificado">
        <div class="borda-dourada">
            <div class="selo">✓</div>
            
            <div class="cabecalho">
                <div class="titulo-principal">Certificado de Conclusão</div>
                <div class="subtitulo">RCP - Sistema de Concursos Públicos</div>
            </div>
            
            <div class="conteudo">
                <div class="texto-principal">
                    Certificamos que 
                    <span class="nome-usuario">{{ strtoupper($user->nome) }}</span>
                    concluiu com êxito todas as etapas e videoaulas do módulo de estudos da disciplina:
                </div>
                
                <div class="materia">{{ $categoria->nome }}</div>
                
                <div class="estatisticas">
                    <div class="stat-item">
                        <div class="stat-numero">{{ $videoaulas_count }}</div>
                        <div class="stat-label">Aulas Concluídas</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-numero">{{ $horas }}h</div>
                        <div class="stat-label">Carga Horária</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-numero">100%</div>
                        <div class="stat-label">Aproveitamento</div>
                    </div>
                </div>
                
                <div class="texto-secundario">
                    Este certificado comprova a dedicação e o empenho do aluno na busca pelo conhecimento, atestando sua competência nos tópicos abordados.
                </div>
            </div>
            
            <div class="rodape">
                <div class="assinatura">
                    <div class="linha-assinatura">Sistema RCP</div>
                    <small>Coordenação Pedagógica</small>
                </div>
                <div class="data">
                    Concedido em {{ $data_emissao }}
                </div>
                <div class="assinatura">
                    <div class="linha-assinatura">Diretoria Acadêmica</div>
                    <small>RCP Concursos</small>
                </div>
            </div>
            
            <div class="numero-certificado">
                Autenticação: {{ $codigo_validacao }}
            </div>
        </div>
    </div>
</body>
</html>
