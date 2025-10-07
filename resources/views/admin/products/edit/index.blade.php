<form id="editForm{{ $produto->id }}" action="{{ route('admin.gestao.produto.editar', ['id' => $produto->id]) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('_form.admin.produtos.index')
</form>
