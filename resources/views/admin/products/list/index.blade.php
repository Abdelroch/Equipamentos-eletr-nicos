@extends('layouts.admin.body')

@section('title', 'Produtos')

@section('conteudo')

    <div class="container-fluid">

        <center>
            <h3><strong><b>Produtos</b></strong></h3>
        </center>

        <div class="row justify-content-center">
            <div class="col-12">
                {{--  <h5 class="mb-2 page-title text-muted">Painel/Utilizadores/Cidadãos</h5> --}}

                {{--  <p class="card-text">DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, built upon the foundations of progressive enhancement, that adds all of these advanced features to any HTML table. </p>
             --}}
                <div class="my-4 row">
                    <!-- Small table -->
                    <div class="col-md-12">

                        <div class="shadow card">

                            <div class="row">
                                <div class="col-6">
                                    <div class="row" style="margin-left: 1rem">
                                        {{-- <div class="mt-3 mb-1 mr-3 col-4">
                                            <form action="{{ route('admin.gestao.produtos') }}" method="GET">
                                                <input type="search" name="search" id="search"
                                                    placeholder="Pesquisar por nome, descrição ou status..."
                                                    class="form-control" value="{{ request('search') }}">
                                            </form>
                                        </div> --}}
                                        <div class="mt-3 mb-1 ml-5 col-6">
                                            <button style="display: flex; justify-content: flex-end ; "
                                                class="btn btn-primary" data-toggle="modal" data-target="#modalCreate"
                                                data-whatever="@mdo">

                                                <i class="m-1 ti ti-shopping-bag fe-16"></i> Cadastrar
                                            </button>
                                        </div>
                                        <div class="mt-3 mb-1 col-6">
                                            <a href="{{ route('admin.gestao.produtos.deletados') }}" class="btn btn-warning">Ver Produtos Deletados</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!-- table -->
                                <table class="table datatables table-hover" id="myTable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Estado venda</th>
                                            <th>Status</th>
                                            <th>Nome</th>
                                            <th>Descrição</th>
                                             <th>Preço (KZ)</th>
                                            <th>Quantidade Disponivel</th>
                                            <th>Categoria</th>
                                            <th>Imgs</th>
                                            <th>Data</th>
                                            <th>Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <style>
                                            /* .bg-primary{
                                                background: #54B4D3;
                                            } */
                                        </style>

                                        @foreach ($data['produtos'] as $produto)
                                                                                                                                                                                                                                                                                                                                            <tr>
                                                                                                                                                                                                                                                                                                                                                <td>{{ $produto->id }}</td>
                                                                                                                                                                                                                                                                                        <td><strong class="@if ($produto->estado_venda === "disponivel")
                                                                                                                                                                                                                                                                                            bg-info @elseif ($produto->estado_venda === "reservado") bg-warning

                                                                                                                                                                                                                                                                                            @elseif ($produto->estado_venda === "vendido") bg-success
                                                                                                                                                                                                                                                                                            @else bg-muted
                                                                                                                                                                                                                                                                                        @endif" style="padding: 0.2rem; color: ">{{ $produto->estado_venda }}</strong></td>
                                                                                                                                                                                                                                                                                                                                                {{--  <td class="">{{$produto->status}}</td> --}}
                                                                                                                                                                                                                                                                                                                                                <td>
                                                                                                                                                                                                                                                                                                                                                    <span
                                                                                                                                                                                                                                                                                                                                                        class="badge
                                                                                                                                                                                                                                                                                                                                                                                    {{ Str::slug($produto->status, '-') == 'bom'
                                            ? 'bg-success'
                                            : (Str::slug($produto->status, '-') == 'avariado'
                                                ? 'bg-danger'
                                                : (Str::slug($produto->status, '-') == 'em-análise'
                                                    ? 'bg-warning text-dark'
                                                    : (Str::slug($produto->status, '-') == 'reparado'
                                                        ? 'bg-info'
                                                        : (Str::slug($produto->status, '-') == 'irreparável'
                                                            ? 'bg-secondary'
                                                            : 'bg-light text-dark')))) }}
                                                                                                                                                                                                                                                                                                                                                                                                                {{ Str::slug($produto->status, '-') == 'extremamente-bom' ? 'bg-success' : '' }}
                                                                                                                                                                                                                                                                                                                                                                                    p-2">
                                                                                                                                                                                                                                                                                                                                                        {{ $produto->status ?? 'Sem Status' }}
                                                                                                                                                                                                                                                                                                                                                    </span>
                                                                                                                                                                                                                                                                                                                                                </td>
                                                                                                                                                                                                                                                                                                                                                <td>{{ $produto->nome }}</td>
                                                                                                                                                                                                                                                                                                                                                <td>{{ $produto->descricao }}</td>
                                                                                                                                                                                                                                                                                                                                                 <td>{{ number_format($produto->preco, 2, ',', '.')}} AOA</td>
                                                                                                                                                                                                                                                                                                                                                <td>{{ $produto->quantidade_disponivel }}</td>
                                                                                                                                                                                                                                                                                                                                                <td>{{ $produto->categoria }}</td>
                                                                                                                                                                                                                                                                                                                                                <td>
                                                                                                                                                                                                                                                                                                                                                    <button class="btn btn-primary p-2" data-toggle="modal"
                                                                                                                                                                                                                                                                                                                                                    data-target="#modalImgs{{ $produto->id }}">
                                                                                                                                                                                                                                                                                                                                                        <i class="ti ti-eye"></i>
                                                                                                                                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                                                                                                                                </td>
                                                                                                                                                                                                                                                                                                                                                <td>{{ date('d/m/y', strtotime($produto->created_at)) }}</td>

                                                                                                                                                                                                                                                                                                                                                <td>
                                                                                                                                                                                                                                                                                                                                                    <div class="dropdown">
                                                                                                                                                                                                                                                                                                                                                        <button class="btn btn-sm dropdown-toggle" type="button"
                                                                                                                                                                                                                                                                                                                                                            data-toggle="dropdown" aria-haspopup="true"
                                                                                                                                                                                                                                                                                                                                                            aria-expanded="false">
                                                                                                                                                                                                                                                                                                                                                            <span class="sr-only text-muted">Ação</span>
                                                                                                                                                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                                                                                                                                                        <div class="dropdown-menu dropdown-menu-right">
                                                                                                                                                                                                                                                                                                                                                            <a class="dropdown-item" href="#" data-toggle="modal"
                                                                                                                                                                                                                                                                                                                                                                data-target="#modalEdit{{ $produto->id }}">Editar</a>
                                                                                                                                                                                                                                                                                                                                                            <form
                                                                                                                                                                                                                                                                                                                                                                action="{{ route('admin.gestao.produto.apagar', ['id' => $produto->id]) }}"
                                                                                                                                                                                                                                                                                                                                                                method="POST" style="display:inline;"
                                                                                                                                                                                                                                                                                                                                                                onsubmit="return confirm('Tem certeza que deseja remover este produto?');">
                                                                                                                                                                                                                                                                                                                                                                @csrf
                                                                                                                                                                                                                                                                                                                                                                @method('DELETE')
                                                                                                                                                                                                                                                                                                                                                                <button type="submit"
                                                                                                                                                                                                                                                                                                                                                                    class="dropdown-item">Remover</button>
                                                                                                                                                                                                                                                                                                                                                            </form>
                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                                                                </td>
                                                                                                                                                                                                                                                                                                                                            </tr>

                                                                                                                                                                                                                                                                    {{-- modal-edit {{ $produto->id }} --}}
                                                                                                                                                                                                                                                                    <div class="modal fade" id="modalEdit{{ $produto->id }}" tabindex="-1"
                                                                                                                                                                                                                                                                        role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
                                                                                                                                                                                                                                                                        <div class="modal-dialog" role="document">
                                                                                                                                                                                                                                                                            <div class="modal-content">
                                                                                                                                                                                                                                                                                <div class="modal-header">
                                                                                                                                                                                                                                                                                    <h5 class="modal-title" id="modalEditLabel">Editar Produto</h5>
                                                                                                                                                                                                                                                                                    <button type="button" class="close" data-dismiss="modal"
                                                                                                                                                                                                                                                                                        aria-label="Close">
                                                                                                                                                                                                                                                                                        <span aria-hidden="true">&times;</span>
                                                                                                                                                                                                                                                                                    </button>
                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                <div class="modal-body">
                                                                                                                                                                                                                                                                                    @include('admin.products.edit.index')


                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                        </div></div>

                                                                                                                                                                                                                                                                        <div class="modal fade" id="modalImgs{{ $produto->id }}" tabindex="-1" role="dialog" aria-labelledby="modalimgsLabel"
                                                                                                                                                                                                                                                                            aria-hidden="true">
                                                                                                                                                                                                                                                                            <div class="modal-dialog" role="document">
                                                                                                                                                                                                                                                                                <div class="modal-content">
                                                                                                                                                                                                                                                                                    <div class="modal-header">
                                                                                                                                                                                                                                                                                        <h5 class="modal-title" id="modalimgsLabel">Imagens do Produto: <strong>{{ $produto->nome }}</strong>
                                                                                                                                                                                                                                                                                        </h5>
                                                                                                                                                                                                                                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                                                                                                                                                                                                                                            <span aria-hidden="true">×</span>
                                                                                                                                                                                                                                                                                        </button>
                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                    <div class="modal-body">
                                                                                                                                                                                                                                                                                        @if (is_array($produto->imagens) && !empty($produto->imagens))
                                                                                                                                                                                                                                                                                            <div class="row">
                                                                                                                                                                                                                                                                                                <h6>Imagens do Produto:</h6>
                                                                                                                                                                                                                                                                                                @foreach ($produto->imagens as $imagem)
                                                                                                                                                                                                                                                                                                    <div class="col-md-3 mb-2">
                                                                                                                                                                                                                                                                                                        <img src="{{ asset($imagem) }}" alt="Imagem de {{ $produto->nome }}" class="img-thumbnail"
                                                                                                                                                                                                                                                                                                            style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                                                                                                                                                                                                                                                                                        <!-- Debug: Mostrar o caminho da imagem -->
                                                                                                                                                                                                                                                                                                        <p class="small text-muted">{{ $imagem }}</p>
                                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                                @endforeach
                                                                                                                                                                                                                                                                                            </div>
                                                                                                                                                                                                                                                                                        @else
                                                                                                                                                                                                                                                                                            <p class="text-muted">Nenhuma imagem disponível.</p>
                                                                                                                                                                                                                                                                                        @endif
                                                                                                                                                                                                                                                                                    </div>
                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                            </div>                                                                                                                                                                                          </div>                                                                                                                                              </div>
                                        @endforeach

                                        @if ($data['produtos']->isEmpty())
                                            <tr>
                                                <td colspan="10" class="text-center text-warning"><b>Nenhum registo
                                                        encontrado!</b></td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div> <!-- simple table -->
                </div> <!-- end section -->
            </div> <!-- .col-12 -->
        </div> <!-- .row -->
    </div> <!-- .container-fluid -->


    {{-- modal-create --}}
    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Cadastrar Produto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{-- form --}}
                    @include('admin.products.create.index')
                </div>
                {{-- <div class="modal-footer">
              <button type="button" class="mb-2 btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" class="mb-2 btn btn-primary">Salvar</button>
            </div> --}}
            </div>
        </div>
    </div>


    <div class="px-6 py-6 text-center">
        <p class="mb-0 fs-4">&copy; MK LDA 2025 <a class="pe-1 text-primary text-decoration-underline">❤️</a></p>
    </div>
    </div>

    @if (session('produtoCadastrado'))
        <script>
            Swal.fire(
                'Produto',
                '{{ session('produtoCadastrado') }} com sucesso!',
                'success'
            )
        </script>
    @endif

    @if (session('produtoAtualizado'))
        <script>
            Swal.fire(
                'Produto',
                '{{ session('produtoAtualizado') }} com sucesso!',
                'success'
            )
        </script>
    @endif

    @if (session('produtoRemovido'))
        <script>
            Swal.fire(
                'Produto',
                '{{ session('produtoRemovido') }} com sucesso!',
                'success'
            )
        </script>
    @endif

@endsection

@section('style')
    <style>
        .status {
            padding: 8px 12px;
            border-radius: 4px;
            text-align: center;
            color: white;
            font-weight: 500;
        }

        .status.bom {
            background-color: #28a745;
            /* Verde para "Bom" */
        }

        .status.extremamente-bom {
            background-color: #28a745;
            /* Verde para "Bom" */
        }

        .status.avariado {
            background-color: #dc3545;
            /* Vermelho para "Avariado" */
        }

        .status.em-analise {
            background-color: #ffc107;
            /* Amarelo para "Em Analise" */
            color: #333;
            /* Texto escuro para melhor legibilidade */
        }

        .status.reparado {
            background-color: #17a2b8;
            /* Ciano para "Reparado" */
        }

        .status.irreparavel {
            background-color: #6c757d;
            /* Cinza para "Irreparável" */
        }

        .status.sem-status {
            background-color: #f8f9fa;
            /* Cinza claro para "Sem Status" */
            color: #333;
            /* Texto escuro */
        }
    </style>
@endsection
