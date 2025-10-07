@extends('layouts.admin.body')

@section('title', 'Fornecedores')
    
@section('conteudo')
   
<div class="container-fluid">
 
  <center><h3><strong><b>Fornecedores</b></strong></h3></center>

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
                               
                                  <i class="ti ti-shopping-cart-plus fe-16 m-1"></i> Cadastrar   
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
                    <th>Email</th>
                    <th>Contacto</th>
                    <th>País</th>
                    <th>Província</th>
                    <th>Data</th>
                    <th>Acção</th>
                    </tr>
                </thead>
                <tbody>
                     @foreach ($data['fornecedores'] as $fornecedor) 
                        <tr>
                            <td>
                                <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input">
                                <label class="custom-control-label"></label>
                                </div>
                            </td>
                            <td>{{$fornecedor->id}}</td>
                            <td>{{$fornecedor->nome}}</td>
                            <td>{{$fornecedor->email}}</td>
                            <td>{{$fornecedor->numero}}</td>
                            <td>{{$fornecedor->pais}}</td>
                            <td>{{$fornecedor->provincia}}</td>
                            <td>{{ date('d/m/y', strtotime($fornecedor->created_at)) }}</td>
                            
                            <td><button class="btn btn-sm dropdown-toggle" type="button" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="false">
                                   <span class="text-muted sr-only">Acção</span> 
                                </button>
                                
                                 <div class="dropdown-menu dropdown-menu-right">
                                   <a class="dropdown-item"  href="#" data-toggle="modal" data-target="#modalEdit{{$fornecedor->id}}">Editar</a>
                                   <a class="dropdown-item" href="{{route('admin.gestao.fornecedor.apagar',['id'=>$fornecedor->id])}}">Remover</a>
                                </div>
                            </td>
                         </tr>
                                    
                            
                            <div class="modal fade" id="modalEdit{{$fornecedor->id}} " tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalEditLabel">Editar Fornecedor</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @include('admin.suppliers.edit.index')
                                    </div>
                              </div>
                          </div>

                    @endforeach 

                    @if ($data['fornecedores'] ->isEmpty())
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
          <h5 class="modal-title" id="modalCreateLabel">Cadastrar Fornecedor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            {{-- form --}}
            @include('admin.suppliers.create.index')
        </div>
        {{-- <div class="modal-footer">
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

@if (session('fornecedorCadastrado'))
<script>
    Swal.fire(
        'Fornecedor',
        '{{ session("fornecedorCadastrado") }} com sucesso!',
        'success'
    )
</script>
@endif

@if (session('fornecedorAtualizado'))
<script>
    Swal.fire(
        'Fornecedor',
        '{{ session("fornecedorAtualizado") }} com sucesso!',
        'success'
    )
</script>
@endif

@if (session('fornecedorRemovido'))
<script>
    Swal.fire(
        'Fornecedor',
        '{{ session("fornecedorRemovido") }} com sucesso!',
        'success'
    )
</script>
@endif

@endsection

