@extends('layouts.admin.body')

@section('title', 'Impostos')

@section('conteudo')
    <div class="container-fluid">
        <center>
            <h3><strong><b>Impostos</b></strong></h3>
        </center>

        <div class="row justify-content-center">
            <div class="col-12">
                <div class="my-4 row">
                    <div class="col-md-12">
                        <div class="shadow card">
                            <div class="row">
                                <div class="col-6">
                                    <div class="row" style="display: flex; justify-content: flex-end">
                                        <div class="mt-3 mb-1 mr-3 col-4">
                                            <form action="">
                                                <input type="search" name="" id=""
                                                    placeholder="Pesquisar..." class="form-control">
                                            </form>
                                        </div>
                                        <div class="mt-3 mb-1 ml-5 col-6">
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate"
                                                data-whatever="@mdo">
                                                <i class="m-1 ti ti-plus fe-16"></i> Cadastrar
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
                                            <th>Data Vencimento</th>
                                            <th>Status</th>
                                            <th>Acção</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['impostos'] as $imposto)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input">
                                                        <label class="custom-control-label"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $imposto->id }}</td>
                                                <td>{{ $imposto->tax_type }}</td>
                                                <td>{{ number_format($imposto->amount, 2, ',', '.') }}</td>
                                                <td>{{ date('d/m/y', strtotime($imposto->due_date)) }}</td>
                                                <td>{{ $imposto->status }}</td>
                                                <td>
                                                    <button class="btn btn-sm dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="sr-only text-muted">Acção</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <!-- Botão para Editar (abre modal) -->
                                                        <a class="dropdown-item" href="#" data-toggle="modal"
                                                            data-target="#modalEdit-{{ $imposto->id }}">Editar</a>
                                                        <!-- Botão para Remover -->
                                                        <form
                                                            action="{{ route('admin.gestao.imposto.apagar', ['id' => $imposto->id]) }}"
                                                            method="POST" style="display: inline;"
                                                            onsubmit="return confirm('Tem certeza que deseja remover este imposto?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="dropdown-item">Remover</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="modalEdit-{{ $imposto->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalEditLabel">Editar Imposto</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @include('admin.taxes.edit.index', [
                                                                'imposto' => $imposto,
                                                            ])
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if ($data['impostos']->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center text-warning"><b>Nenhum registo
                                                        encontrado!</b></td>
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

        <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCreateLabel">Cadastrar Imposto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('admin.taxes.create.index')
                    </div>
                </div>
            </div>
        </div>

        <div class="px-6 py-6 text-center">
            <p class="mb-0 fs-4">© MK LDA 2025 <a class="pe-1 text-primary text-decoration-underline">❤️</a></p>
        </div>
    </div>

    @if (session('impostoCadastrado'))
        <script>
            Swal.fire('Imposto', '{{ session('impostoCadastrado') }} com sucesso!', 'success')
        </script>
    @endif

    @if (session('impostoAtualizado'))
        <script>
            Swal.fire('Imposto', '{{ session('impostoAtualizado') }} com sucesso!', 'success')
        </script>
    @endif

    @if (session('impostoRemovido'))
        <script>
            Swal.fire('Imposto', '{{ session('impostoRemovido') }} com sucesso!', 'success')
        </script>
    @endif
@endsection
