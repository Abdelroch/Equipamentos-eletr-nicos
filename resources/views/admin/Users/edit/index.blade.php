<form action="{{ route('admin.gestao.usuario.atualizar', ['usuario' => $user->id]) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="col-form-label" style="color:black">Nome</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="col-form-label" style="color:black">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="col-form-label" style="color:black">Nova Senha <small class="text-muted">(deixar em branco para manter)</small></label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="col-form-label" style="color:black">Confirmar Nova Senha</label>
                <input type="password" class="form-control" name="password_confirmation">
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label class="col-form-label" style="color:black">Nível de Acesso</label>
                <select class="form-control @error('access_level') is-invalid @enderror"
                        name="access_level" required>
                    <option value="">Selecione o nível...</option>
                    <option value="admin"    {{ old('access_level', $user->access_level) === 'admin'    ? 'selected' : '' }}>Admin</option>
                    <option value="manager"  {{ old('access_level', $user->access_level) === 'manager'  ? 'selected' : '' }}>Gestor / Manager</option>
                    <option value="seller"   {{ old('access_level', $user->access_level) === 'seller'   ? 'selected' : '' }}>Vendedor / Seller</option>
                    <option value="customer" {{ old('access_level', $user->access_level) === 'customer' ? 'selected' : '' }}>Cliente</option>
                </select>
                @error('access_level')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12" style="display: flex; justify-content: flex-end; margin-top: 15px;">
            <button type="submit" class="mb-2 btn btn-primary">Actualizar Utilizador</button>
        </div>
    </div>
</form>
