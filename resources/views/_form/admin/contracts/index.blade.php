<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="title" class="col-form-label" style="color:black">Título:</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror"
                   value="{{ old('title', isset($contrato) ? $contrato->title : '') }}"
                   name="title" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="description" class="col-form-label" style="color:black">Descrição:</label>
            <textarea class="form-control @error('description') is-invalid @enderror"
                      name="description" required>{{ old('description', isset($contrato) ? $contrato->description : '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="sign_date" class="col-form-label" style="color:black">Data de Assinatura:</label>
            <input type="date" class="form-control @error('sign_date') is-invalid @enderror"
                   value="{{ old('sign_date', isset($contrato) ? $contrato->sign_date : '') }}"
                   name="sign_date" required>
            @error('sign_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="expiration_date" class="col-form-label" style="color:black">Data de Expiração:</label>
            <input type="date" class="form-control @error('expiration_date') is-invalid @enderror"
                   value="{{ old('expiration_date', isset($contrato) ? $contrato->expiration_date : '') }}"
                   name="expiration_date">
            @error('expiration_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="status" class="col-form-label" style="color:black">Status:</label>
            <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                <option value="" disabled {{ old('status', isset($contrato) ? $contrato->status : '') == '' ? 'selected' : '' }}>Selecione</option>
                <option value="Ativo" {{ old('status', isset($contrato) ? $contrato->status : '') == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                <option value="Expirado" {{ old('status', isset($contrato) ? $contrato->status : '') == 'Expirado' ? 'selected' : '' }}>Expirado</option>
                <option value="Terminado" {{ old('status', isset($contrato) ? $contrato->status : '') == 'Terminado' ? 'selected' : '' }}>Terminado</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="client_id" class="col-form-label" style="color:black">Cliente:</label>
            <select class="form-control @error('client_id') is-invalid @enderror" name="client_id" required>
                <option value="" disabled {{ old('client_id', isset($contrato) ? $contrato->client_id : '') == '' ? 'selected' : '' }}>Selecione</option>
                @foreach ($data['clientes'] as $cliente)
                    <option value="{{ $cliente->id }}" {{ old('client_id', isset($contrato) ? $contrato->client_id : '') == $cliente->id ? 'selected' : '' }}>{{ $cliente->nome }}</option>
                @endforeach
            </select>
            @error('client_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12" style="display: flex; justify-content: flex-end;">
        <button type="submit" class="btn mb-2 btn-primary">Salvar</button>
    </div>
</div>

@if($errors->any())
    <script>
        $(document).ready(() => {
            $('#modalCreate').modal('show');
        });
    </script>
@endif
