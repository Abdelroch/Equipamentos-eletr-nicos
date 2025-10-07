@extends('layouts.admin.body')

@section('title', 'Clientes')

@section('conteudo')

<div class="container-fluid">

  <center><h3><strong><b>Clientes</b></strong></h3></center>

  <div class="row justify-content-center">
    <div class="col-12">
       {{--  <h5 class="mb-2 page-title text-muted">Painel/Utilizadores/Cidadãos</h5> --}}

       {{--  <p class="card-text">DataTables is a plug-in for the jQuery Javascript library. It is a highly flexible tool, built upon the foundations of progressive enhancement, that adds all of these advanced features to any HTML table. </p>
         --}}
        <div class="row my-4">
        <!-- Small table -->
        <div class="col-md-12">

            <div class="card shadow">

            <div class="row">
               <div class="col-6">
                    <div class="row" style="display: flex; justify-content: flex-end">
                        <div class="col-4 mt-3 mb-1 mr-3" >
                            <form action="">
                                <input type="search" name="" id="" placeholder="Pesquisar..." class="form-control">
                            </form>
                        </div>
                        <div class="col-6 mt-3 mb-1 ml-5">
                            <button style="display: flex; justify-content: flex-end ; " class="btn btn-primary" data-toggle="modal" data-target="#modalCreate" data-whatever="@mdo">

                                 <i class="ti ti-user-plus fe-16 m-1"></i> Cadastrar
                            </button>
                        </div>
                    </div>
               </div>
            </div>
            <div class="card-body">
                <!-- table -->
                <table class="table datatables table-hover" id="dataTable-1">
                <thead>
                    <tr>
                    <th></th>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Contacto</th>
                    <th>Província</th>
                    <th>Data</th>
                    <th>Acção</th>
                    </tr>
                </thead>
                <tbody>
                   @foreach ($data['clientes'] as $cliente)
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input">
                                <label class="custom-control-label"></label>
                                </div>
                            </td>
                            <td>{{$cliente->id}}</td>
                            <td>{{$cliente->nome}}</td>
                            <td>{{$cliente->numero}}</td>
                            <td>{{$cliente->provincia}}</td>
                            <td>{{ date('d/m/y', strtotime($cliente->created_at)) }}</td>


                            <td><button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  <span class="text-muted sr-only">Acção</span>
                                </button>
                                 <div class="dropdown-menu dropdown-menu-right">
                                   <a class="dropdown-item"  href="#" data-toggle="modal" data-target="#modalEdit{{-$cliente->id}}">Editar</a>
                                   <a class="dropdown-item"  href="{{ route('admin.gestao.cliente.apagar',['id' => $cliente->id])}}">Remover</a>
                                  {{--  <a class="dropdown-item" href="#">Purgar</a> --}}
                                </div>
                            </td>
                        </tr>


                            <div class="modal fade" id="modalEdit{{-$cliente->id}}" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditLabel">Editar Cliente</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    {{-- corpo do modal de edição --}}
                                    <div class="modal-body">
                                        @include('admin.clients.edit.index')

                                    </div>
                                </div>
                          </div>

                    @endforeach

                    @if ($data['clientes']->isEmpty())
                          <tr>
                              <td colspan="10" class="text-warning text-center"><b>Nenhum registo encontrado!</b></td>
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
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCreateLabel">Cadastrar Cliente</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            {{-- form --}}
            @include('admin.clients.create.index')
        </div>
       {{--  <div class="modal-footer">
          <button type="button" class="btn mb-2 btn-secondary" data-dismiss="modal">Fechar</button>
          <button type="button" class="btn mb-2 btn-primary">Salvar</button>
        </div> --}}
      </div>
    </div>
</div>


<div class="py-6 px-6 text-center">
    <p class="mb-0 fs-4">&copy;  MK LDA 2025 <a class="pe-1 text-primary text-decoration-underline">❤️</a></p>
  </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.getElementById('phone');
    phoneInput.addEventListener('input', function () {
        const phonePattern = /^\+?[1-9]\d{1,14}$/;
        if (!phonePattern.test(phoneInput.value)) {
            phoneInput.classList.add('is-invalid');
            phoneInput.nextElementSibling.textContent = 'Número de telefone inválido.';
        } else {
            phoneInput.classList.remove('is-invalid');
            phoneInput.nextElementSibling.textContent = '';
        }
    });
});
</script>

@if (session('clienteCadastrado'))
<script>
    Swal.fire(
        'Cliente',
        '{{ session("clienteCadastrado") }} com sucesso!',
        'success'
    )
</script>
@endif

@if (session('clienteAtualizado'))
<script>
    Swal.fire(
        'Cliente',
        '{{ session("clienteAtualizado") }} com sucesso!',
        'success'
    )
</script>
@endif

@if (session('clienteRemovido'))
<script>
    Swal.fire(
        'Cliente',
        '{{ session("clienteRemovido") }} com sucesso!',
        'success'
    )
</script>
@endif

@endsection

