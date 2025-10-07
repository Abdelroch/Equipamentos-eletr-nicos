```blade
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Relatório de Rótulos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 10mm; }
        h1 { text-align: center; color: #333; font-size: 20px; margin-bottom: 10mm; }
        .page { margin-bottom: 10mm; }
        .card-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-auto-rows: 35mm;
    gap: 5mm;
    width: 235mm;
    margin: 0 auto;
}
        .card {
            width: 55mm;
            height: 35mm;
            border: 1px solid #ccc;
            padding: 3mm;
            text-align: center;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            /* Debug: border: 1px solid blue; */
        }
        .card-title { font-size: 11px; font-weight: bold; margin-bottom: 2mm; }
        .card-text { font-size: 9px; margin-bottom: 2mm; }
        .badge {
            padding: 1.5mm;
            border-radius: 3px;
            color: white;
            font-size: 7px;
            display: inline-block;
        }
        .bg-success { background-color: #28a745; }
        .bg-danger { background-color: #dc3545; }
        .bg-warning { background-color: #ffc107; color: #333; }
        .bg-info { background-color: #17a2b8; }
        .bg-secondary { background-color: #6c757d; }
        .bg-light { background-color: #f8f9fa; color: #333; }
        .footer { text-align: center; font-size: 8px; position: fixed; bottom: 5mm; width: 100%; }
        @page { size: A4 landscape; margin: 10mm; }
        .page-break { page-break-before: always; }
    </style>
</head>
<body>
    <h1>Relatório de Rótulos</h1>
    @foreach ($data['produtos']->chunk(16) as $chunkIndex => $chunk)
        @if ($chunkIndex > 0)
            <div class="page-break"></div>
        @endif
        <div class="page">
            <div class="card-container">
                @foreach ($chunk as $produto)
                    <div class="card">
                        <div class="card-title">{{ $produto->nome }}</div>
                        <div class="card-text">ID: {{ $produto->id }}</div>
                        <span class="badge
                            {{ Str::slug($produto->status, '-') == 'bom' ? 'bg-success' :
                                (Str::slug($produto->status, '-') == 'avariado' ? 'bg-danger' :
                                    (Str::slug($produto->status, '-') == 'em-análise' ? 'bg-warning' :
                                        (Str::slug($produto->status, '-') == 'reparado' ? 'bg-info' :
                                            (Str::slug($produto->status, '-') == 'irreparável' ? 'bg-secondary' :
                                                'bg-light')))) }}">
                            {{ $produto->status ?? 'Sem Status' }}
                        </span>
                    </div>
                @endforeach
                @for ($i = $chunk->count(); $i < 16; $i++)
                    <div class="card"></div> <!-- Card vazio para preencher o grid -->
                @endfor
            </div>
        </div>
    @endforeach
    @if ($data['produtos']->isEmpty())
        <div style="text-align: center; font-size: 14px;">Nenhum produto encontrado!</div>
    @endif
    <div class="footer">
        <p>© MK LDA 2025 - Gerado em {{ now()->format('d/m/Y H:i') }}</p>
    </div>
</body>
</html>
```
