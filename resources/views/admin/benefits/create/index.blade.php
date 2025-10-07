<form action="{{ route('admin.gestao.beneficio.cadastrar') }}" method="POST">
    @csrf
    @include('_form.admin.benefits.index')
</form>
