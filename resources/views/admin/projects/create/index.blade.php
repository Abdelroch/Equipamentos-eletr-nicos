<form action="{{ route('admin.gestao.projeto.cadastrar') }}" method="POST">
    @csrf
    @include('_form.admin.projects.index')
</form>
