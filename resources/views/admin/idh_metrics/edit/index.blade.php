<form action="{{ route('admin.gestao.idh-metrica.editar', ['id' => $metrica->id]) }}" method="POST">
    @csrf
    @include('_form.admin.idh_metrics.index', ['metrica' => $metrica])
</form>
