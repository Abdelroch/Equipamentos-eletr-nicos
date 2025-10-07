<form action="{{ route('admin.gestao.funcionario.cadastrar') }}" method="POST">
    @csrf
    @include('_form.admin.employees.index')
</form>
