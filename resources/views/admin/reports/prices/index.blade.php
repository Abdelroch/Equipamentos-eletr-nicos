
@extends('layouts.admin.body')

@section('title', 'Relatório de Preços')

@section('conteudo')
<div class="container-fluid">
    <center><h3><strong>Relatório de Preços</strong></h3></center>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <!-- Formulário de Filtros -->
                    <form action="{{ route('admin.reports.prices') }}" method="GET">
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label for="status" class.dom: label>Status:</label>
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
                                <a href="{{ route('admin.reports.prices', ['export' => 'pdf']) }}" class="btn btn-success">Exportar PDF</a>
                            </div>
                        </div>
                    </form>

                    <!-- Tabela de Resultados -->
                    <table class="table datatables table-hover" id="myTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nome</th>
                                <th>Status</th>
                                <th>Preço (KZ)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data['produtos'] as $produto)
                            {
                                <tr>
                                    <td>{{ $produto->id }}</td>
                                    <td>{{ $produto->nome }}</td>
                                    <td>
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
                                    </td>
                                    <td>{{ number_format($produto->preco, 2, ',', '.') }}</td>
                                </tr>
                            }
                            @endforeach
                            @if ($data['produtos']->isEmpty())
                                <tr>
                                    <td colspan="4" class="text-warning text-center"><b>Nenhum produto encontrado!</b></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
