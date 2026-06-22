<form action="{{ route('admin.gestao.usuario.cadastrar') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name" class="col-form-label" style="color:black">Nome</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       name="name" value="{{ old('name') }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="email" class="col-form-label" style="color:black">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="password" class="col-form-label" style="color:black">Senha</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                       name="password" required>
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="password_confirmation" class="col-form-label" style="color:black">Confirmar Senha</label>
                <input type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="access_level" class="col-form-label" style="color:black">Nível de Acesso</label>
                <select class="form-control @error('access_level') is-invalid @enderror"
                        name="access_level" required>
                    <option value="">Selecione o nível...</option>
                    <option value="admin" {{ old('access_level') === 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="manager" {{ old('access_level') === 'manager' ? 'selected' : '' }}>Gestor / Manager</option>
                    <option value="seller" {{ old('access_level') === 'seller' ? 'selected' : '' }}>Vendedor / Seller</option>
                    <option value="customer" {{ old('access_level') === 'customer' ? 'selected' : '' }}>Cliente</option>
                </select>
                @error('access_level')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12" style="display: flex; justify-content: flex-end; margin-top: 15px;">
            <button type="submit" class="mb-2 btn btn-primary">Salvar Utilizador</button>
        </div>
    </div>
</form>
