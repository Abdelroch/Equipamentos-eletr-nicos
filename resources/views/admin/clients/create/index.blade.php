<form action="{{ route('admin.gestao.cliente.cadastrar') }}" method="POST">
    @csrf
    {{ $cliente=null }}
    @include('_form.admin.clients.index')
</form>