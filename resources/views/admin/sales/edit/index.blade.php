<form action="{{ route('admin.gestao.venda.editar', ['id' =>$venda->id]) }}" method="POST">
    @csrf
   
    @include('_form.admin.vendas.index')
</form>