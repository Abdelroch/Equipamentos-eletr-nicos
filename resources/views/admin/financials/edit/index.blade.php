<form action="{{ route('admin.gestao.financeiro.editar', ['id' => $financeiro->id]) }}" method="POST">
    @csrf
    @include('_form.admin.financials.index', ['financeiro' => $financeiro])
</form>
