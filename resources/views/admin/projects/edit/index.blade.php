<form action="{{ route('admin.gestao.projeto.editar', ['id' => $projeto->id]) }}" method="POST">
    @csrf
    @include('_form.admin.projects.index', ['projeto' => $projeto])
</form>
