<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório Completo de Produtos</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
            position: relative;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.1;
            z-index: -1;
            width: 95%;
            height: auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .header h1 {
            font-size: 24px;
            margin: 10px 0;
            color: #333;
        }
        .header h2 {
            font-size: 18px;
            color: #555;
        }
        .filters {
            margin-bottom: 20px;
            font-size: 12px;
        }
        .filters p {
            margin: 5px 0;
        }
        .summary {
            margin-bottom: 20px;
            font-size: 12px;
        }
        .summary table {
            width: 50%;
            border-collapse: collapse;
        }
        .summary th, .summary td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        .summary th {
            background-color: #f2f2f2;
        }
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table-data th, .table-data td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table-data th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .status-bom { background-color: #28a745; color: white; }
        .status-avariado { background-color: #dc3545; color: white; }
        .status-em-analise { background-color: #ffc107; color: black; }
        .status-reparado { background-color: #17a2b8; color: white; }
        .status-irreparavel { background-color: #6c757d; color: white; }
        .footer {
            position: fixed;
            bottom: 20px;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <img src="{{ public_path('img/logo/ChatGPT_Image_Apr_5__2025__08_06_17_AM-removebg-preview.png') }}" alt="Marca d'Água" class="watermark">
    <div class="header">
        <img src="{{ public_path('img/logo/Captura_de_ecrã_2025-04-03_083554-removebg-preview.png') }}" alt="Logo da Empresa">
        <h1>MKPLDA</h1>
        <h2>Relatório Completo de Produtos</h2>
        <p>Gerado em: {{ $data['reportDate'] }}</p>
    </div>

    <!-- Filtros Aplicados -->
    <div class="filters">
        <h3>Filtros Aplicados</h3>
        <p><strong>Pesquisa:</strong> {{ $data['filters']['search'] ?: 'Nenhum' }}</p>
        <p><strong>Status:</strong> {{ $data['filters']['status'] ?: 'Todos' }}</p>
        <p><strong>Categoria:</strong> {{ $data['filters']['category'] ?: 'Todas' }}</p>
        <p><strong>Data Início:</strong> {{ $data['filters']['date_start'] ?: 'Nenhuma' }}</p>
        <p><strong>Data Fim:</strong> {{ $data['filters']['date_end'] ?: 'Nenhuma' }}</p>
    </div>

    <!-- Resumo por Status -->
    <div class="summary">
        <h3>Resumo por Status</h3>
        <table class="summary">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['statusSummary'] as $status => $count)
                    <tr>
                        <td>{{ $status ?: 'Sem Status' }}</td>
                        <td>{{ $count }}</td>
                    </tr>
                @endforeach
                @if (empty($data['statusSummary']))
                    <tr>
                        <td colspan="2">Nenhum dado disponível</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Tabela de Produtos -->
    <div class="table-data">
        <h3>Lista de Produtos</h3>
        <table class="table-data">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Quantidade</th>
                    <th>Categoria</th>
                    <th>Fornecedor</th>
                    <th>Data de Criação</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['produtos'] as $produto)
                    <tr>
                        <td>{{ $produto->id }}</td>
                        <td>{{ $produto->nome }}</td>
                        <td>{{ $produto->descricao }}</td>
                        <td class="status-{{ Str::slug($produto->status, '-') }}">
                            {{ $produto->status ?? 'Sem Status' }}
                        </td>
                        <td>{{ $produto->quantidade_disponivel }}</td>
                        <td>{{ $produto->categoria }}</td>
                        <td>{{ $produto->nome_fornecedor }}</td>
                        <td>{{ date('d/m/Y', strtotime($produto->created_at)) }}</td>
                    </tr>
                @endforeach
                @if ($data['produtos']->isEmpty())
                    <tr>
                        <td colspan="8" style="text-align: center;">Nenhum produto encontrado!</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <div class="footer">
        Relatório gerado automaticamente por máquina em {{ $data['reportDate'] }}
    </div>
</body>
</html>
