<form action="{{ route('admin.gestao.cliente.editar',['id'=>$cliente->id])}}" method="POST">
    @csrf
    @include('_form.admin.clients.index')
</form>