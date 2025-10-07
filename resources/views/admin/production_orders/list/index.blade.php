@extends('layouts.admin.body')

@section('title', 'Ordens de Produção')

@section('conteudo')
    <div class="container-fluid">
        <div class="mb-4 text-center">
            <h3><strong>Ordens de Produção</strong></h3>
        </div>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="my-4">
                    <div class="shadow card">
                        <div class="card-header">
                            <div class="row" style="display: flex; justify-content: flex-end">
                                    <div class="mt-3 mb-1 mr-3 col-4">
                                        <form action="">
                                            <input type="search" name="" id="" placeholder="Pesquisar..." class="form-control">
                                        </form>
                                    </div>
                                    <div class="mt-3 col-6">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate" data-whatever="@mdo">
                                            <i class="m-1 ti ti-plus fe-16"></i> Cadastrar
                                        </button>
                                    </div>
                                </div>
                        </div>
                        <div class="card-body">
                            <table class="table datatables table-hover" id="dataTable-1">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">ID</th>
                                        <th scope="col">Produto</th>
                                        <th scope="col">Quantidade</th>
                                        <th scope="col">Data de Início</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Ação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data['ordens_producao'] as $ordem)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="checkbox-{{ $ordem->id }}">
                                                    <label class="custom-control-label" for="checkbox-{{ $ordem->id }}"></label>
                                                </div>
                                            </td>
                                            <td>{{ $ordem->id }}</td>
                                            <td>{{ $ordem->product->nome ?? 'N/A' }}</td>
                                            <td>{{ $ordem->quantity }}</td>
                                            <td>{{ date('d/m/Y', strtotime($ordem->start_date)) }}</td>
                                            <td>{{ $ordem->status }}</td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Ações para ordem {{ $ordem->id }}">
                                                        Ação
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#modalEdit-{{ $ordem->id }}">Editar</a>
                                                        <a class="dropdown-item" href="{{ route('admin.gestao.ordem-producao.apagar', ['id' => $ordem->id]) }}" onclick="return confirm('Tem certeza que deseja remover esta ordem?')">Remover</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modalEdit-{{ $ordem->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel-{{ $ordem->id }}" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditLabel-{{ $ordem->id }}">Editar Ordem de Produção</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @include('admin.production_orders.edit.index', ['ordem' => $ordem])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-warning"><strong>Nenhum registro encontrado!</strong></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateLabel">Cadastrar Ordem de Produção</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @include('admin.production_orders.create.index')
                    </div>
                </div>
            </div>
        </div>

        <footer class="px-6 py-6 text-center">
            <p class="mb-0 fs-4">© {{ config('app.company_name', 'MK LDA') }} {{ date('Y') }} <a class="pe-1 text-primary text-decoration-underline">❤️</a></p>
        </footer>
    </div>

    @if (session('ordemProducaoCadastrada'))
        <script>
            Swal.fire('Ordem de Produção', '{{ session('ordemProducaoCadastrada') }} com sucesso!', 'success');
        </script>
    @endif

    @if (session('ordemProducaoAtualizada'))
        <script>
            Swal.fire('Ordem de Produção', '{{ session('ordemProducaoAtualizada') }} com sucesso!', 'success');
        </script>
    @endif

    @if (session('ordemProducaoRemovida'))
        <script>
            Swal.fire('Ordem de Produção', '{{ session('ordemProducaoRemovida') }} com sucesso!', 'success');
        </script>
    @endif

    <script>
        $(document).ready(function() {
            $('#dataTable-1').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json'
                },
                pageLength: 10,
                responsive: true
            });
        });
    </script>
@endsection
