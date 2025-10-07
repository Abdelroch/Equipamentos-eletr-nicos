<form action="{{ route('admin.gestao.fornecedor.cadastrar') }}" method="POST">
    @csrf
    {{ $fornecedor=null }}
    @include('_form.admin.fornecedores.index')
</form>