<form action="{{ route('admin.gestao.usuario.atualizar', ['usuario' => $user->id]) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="name" class="col-form-label" style="color:black">Nome</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="email" class="col-form-label" style="color:black">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="password" class="col-form-label" style="color:black">Senha</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Deixe em branco para não alterar">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="password_confirmation" class="col-form-label" style="color:black">Confirmar Senha</label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirme a senha">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="role" class="col-form-label" style="color:black">Role</label>
                <select class="form-control @error('role') is-invalid @enderror" name="role" required>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role')
                    <div class="invalid-feedback">{{ $message }}</div>
                @endif
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12" style="display: flex; justify-content: flex-end;">
            <button type="submit" class="btn mb-2 btn-primary">Salvar</button>
        </div>
    </div>
</form>
