@extends('layouts.admin.body')

@section('title', 'Atividades')
    
@section('conteudo')
   
<div class="container-fluid">
 
  <center><h3><strong><b>Atividades</b></strong></h3></center>

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
                         
                     </div>
                </div>
             </div>
            <div class="card-body">
                <!-- table -->
                <table class="table datatables table-hover" id="dataTable-1">
                <thead>
                    <tr>
                   {{--  <th></th> --}}
                    <th>ID</th>
                    <th>Nome do Utilizador</th>
                    <th>Acção</th>
                    <th>Detalhes</th>
                    <th>IP</th>
                    <th>Data</th>
                    
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data['logs'] as $log) 
                        <tr>
                            {{-- <td>
                                <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input">
                                <label class="custom-control-label"></label>
                                </div>
                            </td> --}}
                            <td>{{$log->id}}</td>
                            <td>{{$log->nome_user}}</td>
                            <td>{{$log->accao}}</td>
                            <td>{{$log->descricao}}</td>
                            <td>{{$log->ip}}</td>
                            <td>{{ date('d/m/y', strtotime($log->created_at)) }}</td>
                            
                        </tr>
        
                   @endforeach 

                     @if ($data['logs']->isEmpty())
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
          <h5 class="modal-title" id="modalCreateLabel">Cadastrar Fornecedores</h5>
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




@endsection

