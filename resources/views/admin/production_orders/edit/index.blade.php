<form action="{{ route('admin.gestao.ordem-producao.editar', ['id' => $ordem->id]) }}" method="POST">
    @csrf
    @include('_form.admin.production_orders.index', ['ordem' => $ordem])
</form>
