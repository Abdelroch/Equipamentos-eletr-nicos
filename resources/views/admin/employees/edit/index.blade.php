<form action="{{ route('admin.gestao.funcionario.editar', ['id' => $funcionario->id]) }}" method="POST">
    @csrf
    @include('_form.admin.employees.index', ['funcionario' => $funcionario])
</form>
