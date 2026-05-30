<form action="{{ route('admin.gestao.produto.cadastrar') }}"
      method="POST"
      enctype="multipart/form-data">
    @csrf
    {{ $produto = null }}
    @include('_form.admin.produtos.index')
</form>
