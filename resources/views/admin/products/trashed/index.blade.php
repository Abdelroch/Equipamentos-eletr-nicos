@extends('layouts.admin.body')

@section('title', 'Produtos Deletados')

@section('conteudo')
<div class="container-fluid">
    <center><h3><strong>Produtos Deletados</strong></h3></center>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="my-4 row">
                <div class="col-md-12">
                    <div class="shadow card">
                        <div class="card-body">
                            <table class="table datatables table-hover" id="myTable">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Status</th>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Quantidade Disponível</th>
                                        <th>Categoria</th>
                                        <th>Data de Deleção</th>
                                        <th>Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['produtos'] as $produto)
                                        <tr>
                                            <td>{{ $produto->id }}</td>
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
                                            <td>{{ $produto->nome }}</td>
                                            <td>{{ $produto->descricao }}</td>
                                            <td>{{ $produto->quantidade_disponivel }}</td>
                                            <td>{{ $produto->categoria }}</td>
                                            <td>{{ $produto->deleted_at ? date('d/m/y', strtotime($produto->deleted_at)) : '-' }}</td>
                                            <td>
                                                <form action="{{ route('admin.gestao.produto.restaurar', ['id' => $produto->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja restaurar este produto?');">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @if ($data['produtos']->isEmpty())
                                        <tr>
                                            <td colspan="8" class="text-center text-warning"><b>Nenhum produto deletado encontrado!</b></td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if (session('produtoRestaurado'))
    <script>
        Swal.fire(
            'Produto',
            '{{ session("produtoRestaurado") }}',
            'success'
        )
    </script>
@endif
@endsection
