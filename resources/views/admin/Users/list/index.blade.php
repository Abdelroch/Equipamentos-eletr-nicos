@extends('layouts.admin.body')

@section('title', 'Usuários')

@section('conteudo')
    <div class="container-fluid">
        <center>
            <h3><strong>Usuários</strong></h3>
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
                                                <input type="search" name="search" placeholder="Pesquisar..."
                                                    class="form-control">
                                            </form>
                                        </div>
                                        <div class="mt-3 mb-1 ml-5 col-6">
                                            <button class="btn btn-primary" data-toggle="modal" data-target="#modalCreate">
                                                <i class="m-1 ti ti-user-plus"></i> Cadastrar
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
<th>Nível de Acesso</th>
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
<td>{{ $user->access_level }}</td>
                                                <td>{{ date('d/m/y', strtotime($user->created_at)) }}</td>
                                                <td>
                                                    <button class="btn btn-sm dropdown-toggle" type="button"
                                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                        <span class="sr-only text-muted">Ação</span>
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-right">
    <a class="dropdown-item" href="#"
       data-toggle="modal"
       data-target="#modalEdit{{ $user->id }}">Editar</a>

    {{-- ✅ CORRECTO: form DELETE em vez de link GET --}}
    <form action="{{ route('admin.gestao.usuario.apagar', $user->id) }}" method="POST" style="display:inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="dropdown-item text-danger"
                onclick="return confirm('Tem a certeza que quer remover {{ $user->name }}?')">
            Remover
        </button>
    </form>
</div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if ($data['users']->isEmpty())
                                            <tr>
                                                <td colspan="7" class="text-center text-warning">
                                                    <b>Nenhum registro encontrado!</b>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>

                                {{-- FIX 2: Modais FORA da tabela, em loop separado --}}
                                @foreach ($data['users'] as $user)
                                    <div class="modal fade" id="modalEdit{{ $user->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="modalEditLabel{{ $user->id }}" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalEditLabel{{ $user->id }}">
                                                        Editar Usuário — {{ $user->name }}
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    @include('admin.users.edit.index', ['user' => $user])
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal Criar --}}
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

        <div class="px-6 py-6 text-center">
            <p class="mb-0 fs-4">© MK LDA 2025 <a class="pe-1 text-primary text-decoration-underline">❤️</a></p>
        </div>
    </div>

    @if (session('userCadastrado'))
        <script>
            Swal.fire('Usuário', '{{ session('userCadastrado') }} com sucesso!', 'success')
        </script>
    @endif

    @if (session('userAtualizado'))
        <script>
            Swal.fire('Usuário', '{{ session('userAtualizado') }} com sucesso!', 'success')
        </script>
    @endif

    @if (session('userRemovido'))
        <script>
            Swal.fire('Usuário', '{{ session('userRemovido') }} com sucesso!', 'success')
        </script>
    @endif
    @if (session('userCadastrado') || session('userAtualizado') || session('userRemovido'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        @if (session('userCadastrado'))
            Swal.fire({ icon: 'success', title: 'Utilizador', text: '{{ session('userCadastrado') }} com sucesso!' });
        @elseif (session('userAtualizado'))
            Swal.fire({ icon: 'success', title: 'Utilizador', text: '{{ session('userAtualizado') }} com sucesso!' });
        @elseif (session('userRemovido'))
            Swal.fire({ icon: 'success', title: 'Utilizador', text: '{{ session('userRemovido') }} com sucesso!' });
        @endif
    });
</script>
@endif
@endsection
