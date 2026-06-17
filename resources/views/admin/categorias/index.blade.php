@extends('layouts.admin.body')
@section('title', 'Categorias')

@section('conteudo')
<div class="container-fluid">
    <div class="row">

        {{-- Formulário criar --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header"><h5 class="mb-0"><i class="fa fa-plus"></i> Nova Categoria</h5></div>
                <div class="card-body">
                    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                    @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                    <form method="POST" action="{{ route('admin.categorias.store') }}">
                        @csrf
                        <div class="form-group">
                            <label>Nome <span class="text-danger">*</span></label>
                            <input type="text" name="nome" class="form-control" value="{{ old('nome') }}"
                                   placeholder="ex: Laptops" required>
                            @error('nome')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label>Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" id="slug" class="form-control"
                                   value="{{ old('slug') }}" placeholder="ex: laptops" required>
                            @error('slug')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>
                        <div class="form-group">
                            <label>Ícone (FontAwesome)</label>
                            <input type="text" name="icone" class="form-control"
                                   value="{{ old('icone', 'fa fa-tag') }}" placeholder="fa fa-laptop">
                            <small class="text-muted">Ver ícones em <a href="https://fontawesome.com/v4/icons/" target="_blank">fontawesome.com/v4</a></small>
                        </div>
                        <div class="form-group">
                            <label>Estado</label>
                            <select name="activa" class="form-control">
                                <option value="1">Activa</option>
                                <option value="0">Inactiva</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-dark btn-block">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Tabela --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><h5 class="mb-0"><i class="fa fa-list"></i> Todas as Categorias</h5></div>
                <div class="p-0 card-body">
                    <table class="table mb-0 table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Ícone</th>
                                <th>Nome</th>
                                <th>Slug</th>
                                <th>Estado</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($categorias as $cat)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td><i class="{{ $cat->icone }}"></i></td>
                                <td>{{ $cat->nome }}</td>
                                <td><code>{{ $cat->slug }}</code></td>
                                <td>
                                    @if($cat->activa)
                                        <span class="badge badge-success">Activa</span>
                                    @else
                                        <span class="badge badge-secondary">Inactiva</span>
                                    @endif
                                </td>
                                <td>
                                    {{-- Editar --}}
                                    <button class="btn btn-warning btn-sm"
                                            onclick="editarCategoria({{ $cat->id }}, '{{ $cat->nome }}', '{{ $cat->slug }}', '{{ $cat->icone }}', {{ $cat->activa }})">
                                        <i class="fa fa-edit"></i>
                                    </button>

                                    {{-- Eliminar --}}
                                    <form method="POST"
                                          action="{{ route('admin.categorias.destroy', $cat->id) }}"
                                          class="d-inline"
                                          onsubmit="return confirm('Eliminar categoria {{ $cat->nome }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-3 text-center text-muted">Nenhuma categoria criada.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal Editar --}}
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" id="formEditar">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar Categoria</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nome *</label>
                        <input type="text" name="nome" id="edit_nome" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Slug *</label>
                        <input type="text" name="slug" id="edit_slug" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Ícone</label>
                        <input type="text" name="icone" id="edit_icone" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <select name="activa" id="edit_activa" class="form-control">
                            <option value="1">Activa</option>
                            <option value="0">Inactiva</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning"><i class="fa fa-save"></i> Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editarCategoria(id, nome, slug, icone, activa) {
    document.getElementById('edit_nome').value   = nome;
    document.getElementById('edit_slug').value   = slug;
    document.getElementById('edit_icone').value  = icone;
    document.getElementById('edit_activa').value = activa;
    document.getElementById('formEditar').action = '/admin/categorias/' + id;
    $('#modalEditar').modal('show');
}

// Auto-slug a partir do nome
document.querySelector('[name="nome"]').addEventListener('input', function () {
    const slug = this.value
        .toLowerCase()
        .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
        .replace(/[^a-z0-9\s-]/g, '')
        .trim()
        .replace(/\s+/g, '-');
    document.getElementById('slug').value = slug;
});
</script>
@endsection
