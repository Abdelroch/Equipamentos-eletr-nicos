<form action="{{ route('admin.gestao.ordem-producao.cadastrar') }}" method="POST">
    @csrf
    @include('_form.admin.production_orders.index')
</form>
