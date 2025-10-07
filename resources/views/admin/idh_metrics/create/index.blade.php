<form action="{{ route('admin.gestao.idh-metrica.cadastrar') }}" method="POST">
    @csrf
    @include('_form.admin.idh_metrics.index')
</form>
