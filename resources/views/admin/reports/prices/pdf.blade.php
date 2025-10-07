```blade
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Relatório de Preços</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 15mm;

            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        h1 {
            text-align: center;
            color: #333;
            font-size: 28px;
            margin-bottom: 15mm;
        }

        .product-container {
            display: flex;
            justify-content: center;
            gap: 10mm;
            margin-bottom: 20mm;
        }

        .product {
            width: 120mm;
            /* Reduzido de 48% para valor fixo para melhor controle */
            border: 2px solid #333;
            padding: 15mm;
            text-align: center;
            box-sizing: border-box;
            min-height: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-title {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .product-text {
            font-size: 18px;
            font-weight: 600;
            color: #474747;
            margin-bottom: 10mm;
            font-family: arial;
        }

        .product-price {
            font-size: 48px;
            color: #dc3545;
            font-weight: bold;
        }

        .badge {
            padding: 5mm;
            border-radius: 6px;
            color: white;
            font-size: 18px;
            display: inline-block;
        }

        .bg-success {
            background-color: #28a745;
        }

        .bg-danger {
            background-color: #dc3545;
        }

        .bg-warning {
            background-color: #ffc107;
            color: #333;
        }

        .bg-info {
            background-color: #17a2b8;
        }

        .bg-secondary {
            background-color: #6c757d;
        }

        .bg-light {
            background-color: #f8f9fa;
            color: #333;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            position: fixed;
            bottom: 10mm;
            width: 100%;
        }

        @page {
            size: A4 landscape;
            margin: 15mm;
        }

        .page-break {
            page-break-before: always;
        }

        /* .product{
            position: relative;
        } */
        .product-id::before{
            content: '';/*
            position: absolute; */
            top: 0;
            bottom: 0;
            right: 0;
            left: 0;
            background: #f0f0f0af;
        }
    </style>
</head>

<body>{{--
    <h1>Preço e Detalhes dos Produtos</h1> --}}
    @foreach ($data['produtos'] as $index => $produto)
        @if ($index % 2 == 0)
            @if ($index > 0)
                <div class="page-break"></div>
            @endif
            <div class="product-container">
        @endif
        <center>

            <div class="product">
                <div class="product-title">{{ $produto->nome }}</div>
                <div class="product-tex">{{-- Detalhes: <br> --}}
                <p style="color:#000000; font-family: Arial, Helvetica, sans-serif; font-weight: 400;">{{ $produto->descricao }}</p></div>
                <div class="product-id"><span style="color:gray">ID: {{ $produto->id }}</span></div>
                {{-- <span
                    class="badge
                {{ Str::slug($produto->status, '-') == 'bom'
        ? 'bg-success'
        : (Str::slug($produto->status, '-') == 'avariado'
            ? 'bg-danger'
            : (Str::slug($produto->status, '-') == 'em-análise'
                ? 'bg-warning'
                : (Str::slug($produto->status, '-') == 'reparado'
                    ? 'bg-info'
                    : (Str::slug($produto->status, '-') == 'irreparável'
                        ? 'bg-secondary'
                        : 'bg-light')))) }}">
                    {{ $produto->status ?? 'Sem Status' }}
                </span> --}}
                <div class="product-price">{{ number_format($produto->preco, 2, ',', '.') }} KZ</div>
            </div>
        </center>

        @if ($index % 2 == 1 || $index == $data['produtos']->count() - 1)
            </div>
        @endif
    @endforeach
    @if ($data['produtos']->isEmpty())
        <div style="text-align: center; font-size: 20px;">Nenhum produto encontrado!</div>
    @endif
    <div class="footer">
        <p>© MK LDA 2025 - Gerado em {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>

</html>

