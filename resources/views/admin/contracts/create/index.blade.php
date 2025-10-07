<form action="{{ route('admin.gestao.contrato.cadastrar') }}" method="POST">
    @csrf
    @include('_form.admin.contracts.index')
</form>
