<form action="{{ route('admin.gestao.imposto.cadastrar') }}" method="POST">
    @csrf
    @include('_form.admin.taxes.index')
</form>
