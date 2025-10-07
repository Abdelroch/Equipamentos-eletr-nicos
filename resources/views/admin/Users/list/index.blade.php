@extends('layouts.admin.body')

@section('title', 'Usuários')

@section('conteudo')
    <div class="container-fluid">
        <center>
            <h3><strong>Usuários</strong></h3>
        </center>

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
                                                <input type="search" name="search" placeholder="Pesquisar..."
                                                    class="form-control">
                                            </form>
                                        </div>
                                        <div class="col-6 mt-3 mb-1 ml-5">
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                                                <i class="ti ti-user-plus m-1"></i> Cadastrar
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
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Data</th>
                                            <th>Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['users'] as $user)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox" class="custom-control-input">
                                                        <label class="custom-control-label"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $user->id }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->email }}</td>
                                                <td>{{ $user->role }}</td>
                                                <td>{{ date('d/m/y', strtotime($user->created_at)) }}</td>
                                                <td>
                                                    <button class="btn btn-sm dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="text-muted sr-only">Ação</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a class="dropdown-item" href="{{ route('admin.gestao.usuario.editar', ['usuario' => $user->id]) }}"
                                                            data-toggle="modal" data-target="#modalEdit{{ $user->id }}">Editar</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('admin.gestao.usuario.apagar', ['usuario' => $user->id]) }}">Remover</a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalEditLabel">Editar Usuário</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @include('admin.users.edit.index')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if ($data['users']->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-warning text-center"><b>Nenhum registro
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
                        <h5 class="modal-title" id="modalCreateLabel">Cadastrar Usuário</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @include('admin.users.create.index')
                    </div>
                </div>
            </div>
        </div>

        <div class="py-6 px-6 text-center">
            <p class="mb-0 fs-4">© MK LDA 2025 <a class="pe-1 text-primary text-decoration-underline">❤️</a></p>
        </div>
    </div>

    @if (session('userCadastrado'))
        <script>
            Swal.fire(
                'Usuário',
                '{{ session('userCadastrado') }} com sucesso!',
                'success'
            )
        </script>
    @endif

    @if (session('userAtualizado'))
        <script>
            Swal.fire(
                'Usuário',
                '{{ session('userAtualizado') }} com sucesso!',
                'success'
            )
        </script>
    @endif

    @if (session('userRemovido'))
        <script>
            Swal.fire(
                'Usuário',
                '{{ session('userRemovido') }} com sucesso!',
                'success'
            )
        </script>
    @endif
@endsection
