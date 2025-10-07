<form action="{{ route('admin.gestao.financeiro.cadastrar') }}" method="POST">
    @csrf
    @include('_form.admin.financials.index')
</form>
