<form action="{{ route('admin.gestao.beneficio.editar', ['id' => $beneficio->id]) }}" method="POST">
    @csrf
    @include('_form.admin.benefits.index', ['beneficio' => $beneficio])
</form>
