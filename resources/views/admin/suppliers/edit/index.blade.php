<form action="{{route('admin.gestao.fornecedor.editar',['id'=>$fornecedor->id])}}" method="POST">
    @csrf
    @include('_form.admin.fornecedores.index')
</form>