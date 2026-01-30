<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Cronograma de Estudos</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #ccc;
            padding-bottom: 10px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .info {
            margin-bottom: 20px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #2c3e50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .day-header {
            background-color: #e9ecef;
            font-weight: bold;
            padding: 10px;
            margin-top: 15px;
            border-left: 5px solid #2c3e50;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #777;
            border-top: 1px solid #ccc;
            padding-top: 5px;
        }
        .tips-section {
            margin-top: 30px;
            padding: 15px;
            background-color: #e8f4fd;
            border: 1px solid #b6d4fe;
            border-radius: 8px;
            page-break-inside: avoid;
        }
        .tips-section h3 {
            color: #0d6efd;
            margin-top: 0;
            border-bottom: 1px solid #b6d4fe;
            padding-bottom: 5px;
        }
        .tips-section ul {
            margin: 10px 0 0 0;
            padding-left: 20px;
        }
        .tips-section li {
            margin-bottom: 5px;
            color: #444;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $cronograma->titulo ?? 'Cronograma de Estudos - RCP Concursos' }}</h1>
        <p>Gerado em {{ date('d/m/Y') }}</p>
    </div>

    <div class="info">
        <strong>Edital Base:</strong> {{ $cronograma->edital->nome_arquivo }}<br>
        <strong>Período:</strong> {{ \Carbon\Carbon::parse($cronograma->data_inicio)->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($cronograma->data_fim)->format('d/m/Y') }}<br>
        <strong>Carga Horária Diária:</strong> {{ $cronograma->horas_por_dia }} horas
    </div>

    @foreach($diasAgrupados as $data => $atividades)
        <div class="day-section">
            <div class="day-header">
                {{ \Carbon\Carbon::parse($data)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($data)->locale('pt-BR')->dayName }}
            </div>
            <table>
                <thead>
                    <tr>
                        <th width="70%">Disciplina</th>
                        <th width="30%">Tempo Previsto</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($atividades as $atividade)
                        <tr>
                            <td>{{ $atividade->disciplina->nome_disciplina }}</td>
                            <td>{{ $atividade->horas_previstas }} horas</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endforeach

    <div class="tips-section">
        <h3><img src="https://cdn-icons-png.flaticon.com/512/3176/3176384.png" width="16" style="vertical-align: middle;"> Dicas Potencializadoras de Estudo</h3>
        <ul>
            <li><strong>Pomodoro:</strong> Utilize a técnica Pomodoro (25min estudo / 5min pausa) para manter o foco.</li>
            <li><strong>Revisão Ativa:</strong> Não apenas releia. Tente explicar o conteúdo para si mesmo em voz alta.</li>
            <li><strong>Resolução de Questões:</strong> A teoria é importante, mas a prática leva à aprovação. Faça muitas questões da banca.</li>
            <li><strong>Ambiente:</strong> Mantenha seu local de estudos organizado e livre de distrações (celular longe!).</li>
            <li><strong>Consistência:</strong> É melhor estudar 1 hora todos os dias do que 10 horas apenas no sábado.</li>
        </ul>
    </div>

    <div class="footer">
        <p>Gerado automaticamente pelo <strong>RCP Sistema de Concursos</strong>. Mantenha o foco e boa prova!</p>
    </div>
</body>
</html>
