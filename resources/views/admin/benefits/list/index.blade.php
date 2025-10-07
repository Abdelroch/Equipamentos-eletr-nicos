@extends('layouts.admin.body')

@section('title', 'Benefícios')

@section('conteudo')
<div class="container-fluid">
    <center><h3><strong><b>Benefícios</b></strong></h3></center>

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
                                        <th>Tipo</th>
                                        <th>Valor</th>
                                        <th>Data Início</th>
                                        <th>Funcionário</th>
                                        <th>Acção</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data['beneficios'] as $beneficio)
                                        <tr>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input">
                                                    <label class="custom-control-label"></label>
                                                </div>
                                            </td>
                                            <td>{{ $beneficio->id }}</td>
                                            <td>{{ $beneficio->benefit_type }}</td>
                                            <td>{{ number_format($beneficio->amount, 2, ',', '.') }}</td>
                                            <td>{{ date('d/m/y', strtotime($beneficio->start_date)) }}</td>
                                            <td>{{ $beneficio->employee->name ?? 'N/A' }}</td>
                                            <td>
                                                <button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <span class="text-muted sr-only">Acção</span>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modalEdit-{{ $beneficio->id }}">Editar</a>
                                                    <a class="dropdown-item" href="{{ route('admin.gestao.beneficio.apagar', ['id' => $beneficio->id]) }}">Remover</a>
                                                </div>
                                            </td>
                                        </tr>
                                        <div class="modal fade" id="modalEdit-{{ $beneficio->id }}" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEditLabel">Editar Benefício</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">×</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        @include('admin.benefits.edit.index', ['beneficio' => $beneficio])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($data['beneficios']->isEmpty())
                                        <tr>
                                            <td colspan="7" class="text-warning text-center"><b>Nenhum registo encontrado!</b></td>
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
                    <h5 class="modal-title" id="modalCreateLabel">Cadastrar Benefício</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    @include('admin.benefits.create.index')
                </div>
            </div>
        </div>
    </div>

    <div class="py-6 px-6 text-center">
        <p class="mb-0 fs-4">© MK LDA 2025 <a class="pe-1 text-primary text-decoration-underline">❤️</a></p>
    </div>
</div>

@if (session('beneficioCadastrado'))
<script>
    Swal.fire('Benefício', '{{ session("beneficioCadastrado") }} com sucesso!', 'success')
</script>
@endif

@if (session('beneficioAtualizado'))
<script>
    Swal.fire('Benefício', '{{ session("beneficioAtualizado") }} com sucesso!', 'success')
</script>
@endif

@if (session('beneficioRemovido'))
<script>
    Swal.fire('Benefício', '{{ session("beneficioRemovido") }} com sucesso!', 'success')
</script>
@endif
@endsection
