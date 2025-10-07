```blade
@extends('layouts.admin.body')

@section('title', 'Relatório de Rótulos')

@section('conteudo')
<div class="container-fluid">
    <center><h3><strong>Relatório de Rótulos</strong></h3></center>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Formulário de Filtros -->
                    <form action="{{ route('admin.reports.labels') }}" method="GET">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="">Todos</option>
                                    <option value="Bom" {{ $filters['status'] == 'Bom' ? 'selected' : '' }}>Bom</option>
                                    <option value="Avariado" {{ $filters['status'] == 'Avariado' ? 'selected' : '' }}>Avariado</option>
                                    <option value="Em Análise" {{ $filters['status'] == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                                    <option value="Reparado" {{ $filters['status'] == 'Reparado' ? 'selected' : '' }}>Reparado</option>
                                    <option value="Irreparável" {{ $filters['status'] == 'Irreparável' ? 'selected' : '' }}>Irreparável</option>
                                </select>
                            </div>
                            <div class="col-md-3 mt-4">
                                <button type="submit" class="btn btn-primary">Filtrar</button>
                                <a href="{{ route('admin.reports.labels', ['export' => 'pdf']) }}" class="btn btn-success">Exportar PDF</a>
                            </div>
                        </div>
                    </form>

                    <!-- Cards de Produtos -->
                    <div class="row">
                        @foreach ($data['produtos'] as $produto)
                            <div class="col-md-3 mb-4">
                                <div class="card shadow-sm">
                                    <div class="card-body text-center">
                                        <h5 class="card-title">{{ $produto->nome }}</h5>
                                        <p class="card-text">ID: {{ $produto->id }}</p>
                                        <span class="badge
                                            {{ Str::slug($produto->status, '-') == 'bom' ? 'bg-success' :
                                                (Str::slug($produto->status, '-') == 'avariado' ? 'bg-danger' :
                                                    (Str::slug($produto->status, '-') == 'em-análise' ? 'bg-warning text-dark' :
                                                        (Str::slug($produto->status, '-') == 'reparado' ? 'bg-info' :
                                                            (Str::slug($produto->status, '-') == 'irreparável' ? 'bg-secondary' :
                                                                'bg-light text-dark')))) }}
                                            p-2">
                                            {{ $produto->status ?? 'Sem Status' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        @if ($data['produtos']->isEmpty())
                            <div class="col-12 text-warning text-center">
                                <b>Nenhum produto encontrado!</b>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
```
