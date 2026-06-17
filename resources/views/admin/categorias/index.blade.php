@extends('layouts.admin.body')

@section('content')
<div class="py-4 container-fluid">
    <div class="mb-3 d-flex justify-content-between align-items-center">
        <h4>Categorias de Produtos</h4>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalNovaCategoria">
            <i class="fa fa-plus"></i> Nova Categoria
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table id="myTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Ícone</th>
                <th>Nome</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Acções</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['categorias'] as $cat)
            <tr>
                <td>{{ $cat->id }}</td>
                <td><i class="{{ $cat->icone }}"></i></td>
                <td>{{ $cat->nome }}</td>
                <td>{{ $cat->slug }}</td>
                <td>
                    <span class="badge {{ $cat->activa ? 'bg-success' : 'bg-secondary' }}">
                        {{ $cat->activa ? 'Activa' : 'Inactiva' }}
                    </span>
                </td>
                <td>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editar{{ $cat->id }}">
                        <i class="fa fa-edit"></i>
                    </button>
                    <form action="{{ route('admin.categorias.destroy', $cat->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Remover esta categoria?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                    </form>
                </td>
            </tr>

            <div class="modal fade" id="editar{{ $cat->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.categorias.update', $cat->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Editar Categoria</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label>Nome</label>
                                    <input type="text" name="nome" class="form-control" value="{{ $cat->nome }}" required>
                                </div>
                                <div class="mb-3">
                                    <label>Ícone (Font Awesome)</label>
                                    <input type="text" name="icone" class="form-control" value="{{ $cat->icone }}" placeholder="fa fa-laptop">
                                </div>
                                <div class="form-check">
                                    <input type="checkbox" name="activa" class="form-check-input" value="1" {{ $cat->activa ? 'checked' : '' }}>
                                    <label class="form-check-label">Activa</label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalNovaCategoria" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.categorias.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Nova Categoria</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nome</label>
                        <input type="text" name="nome" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Ícone (Font Awesome, ex: fa fa-laptop)</label>
                        <input type="text" name="icone" class="form-control" placeholder="fa fa-laptop">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" name="activa" class="form-check-input" value="1" checked>
                        <label class="form-check-label">Activa</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Criar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
