<form action="{{ route('admin.gestao.venda.cadastrar') }}" method="POST">
    @csrf
    {{ $venda=null }}
    @include('_form.admin.vendas.index')
</form>