@extends('layouts.admin.body')

@section('title', 'Projetos')

@section('conteudo')
<div class="container-fluid">
    <center><h3><strong><b>Projetos</b></strong></h3></center>

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="row my-4">
                <div class="col-md-12">
                    <div class="card shadow">
                        <div class="row">
                            <div class="col-6">
                                <div class="row" style="display: flex; justify-content: flex-end">
                                    <div class="col-4 mt-3 mb-1 mr-3">
                                        <form action="">
                                            <input type="search" name="" id="" placeholder="Pesquisar..." class="form-control">
                                        </form>
                                    </div>
                                    <div class="col-6 mt-3 mb-1 ml-5">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate" data-whatever="@mdo">
                                            <i class="ti ti-plus fe-16 m-1"></i> Cadastrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table datatables table-hover" id="dataTable-1">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Descrição</th>
                                        <th>Data Início</th>
                                        <th>Status</th>
                                        <th>Orçamento</th>
                                        <th>Responsável</th>
                                        <th>Acção</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['projetos'] as $projeto)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <label class="custom-control-label"></label>
                                                </div>
                                            </td>
                                            <td>{{ $projeto->id }}</td>
                                            <td>{{ $projeto->name }}</td>
                                            <td>{{ Str::limit($projeto->description, 50) }}</td>
                                            <td>{{ date('d/m/y', strtotime($projeto->start_date)) }}</td>
                                            <td>{{ $projeto->status }}</td>
                                            <td>{{ number_format($projeto->budget, 2, ',', '.') }}</td>
                                            <td>{{ $projeto->responsible->name }}</td>
                                            <td>
                                                <button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Acção</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalEdit-{{ $projeto->id }}">Editar</a>
                                                    <a class="dropdown-item" href="{{ route('admin.gestao.projeto.apagar', ['id' => $projeto->id]) }}">Remover</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modalEdit-{{ $projeto->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditLabel">Editar Projeto</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @include('admin.projects.edit.index', ['projeto' => $projeto])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($data['projetos']->isEmpty())
                                        <tr>
                                            <td colspan="9" class="text-warning text-center"><b>Nenhum registo encontrado!</b></td>
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

    <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateLabel">Cadastrar Projeto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.projects.create.index')
                </div>
            </div>
        </div>
    </div>

    <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">© MK LDA 2025 <a class="pe-1 text-primary text-decoration-underline">❤️</a></p>
    </div>
</div>

@if (session('projetoCadastrado'))
<script>
    Swal.fire('Projeto', '{{ session("projetoCadastrado") }} com sucesso!', 'success')
</script>
@endif

@if (session('projetoAtualizado'))
<script>
    Swal.fire('Projeto', '{{ session("projetoAtualizado") }} com sucesso!', 'success')
</script>
@endif

@if (session('projetoRemovido'))
<script>
    Swal.fire('Projeto', '{{ session("projetoRemovido") }} com sucesso!', 'success')
</script>
@endif
@endsection
