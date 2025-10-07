<form action="{{ route('admin.gestao.produto.cadastrar') }}" method="POST">
    @csrf
    {{ $produto=null }}
    @include('_form.admin.produtos.index')
</form>