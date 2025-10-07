<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="type" class="col-form-label" style="color:black">Tipo:</label>
            <select class="form-control @error('type') is-invalid @enderror" name="type" required>
                <option value="" disabled {{ old('type', isset($financeiro) ? $financeiro->tipo : '') == '' ? 'selected' : '' }}>Selecione</option>
                <option value="Conta a Pagar" {{ old('type', isset($financeiro) ? $financeiro->tipo : '') == 'Conta a Pagar' ? 'selected' : '' }}>Conta a Pagar</option>
                <option value="Conta a Receber" {{ old('type', isset($financeiro) ? $financeiro->tipo : '') == 'Conta a Receber' ? 'selected' : '' }}>Conta a Receber</option>
            </select>
            @error('type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="amount" class="col-form-label" style="color:black">Valor:</label>
            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                   value="{{ old('amount', isset($financeiro) ? $financeiro->valor : '') }}"
                   name="amount" required>
            @error('amount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="due_date" class="col-form-label" style="color:black">Data de Vencimento:</label>
            <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                   value="{{ old('due_date', isset($financeiro) ? $financeiro->data_vencimento : '') }}"
                   name="due_date" required>
            @error('due_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="status" class="col-form-label" style="color:black">Status:</label>
            <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                <option value="" disabled {{ old('status', isset($financeiro) ? $financeiro->status : '') == '' ? 'selected' : '' }}>Selecione</option>
                <option value="Pendente" {{ old('status', isset($financeiro) ? $financeiro->status : '') == 'Pendente' ? 'selected' : '' }}>Pendente</option>
                <option value="Pago" {{ old('status', isset($financeiro) ? $financeiro->status : '') == 'Pago' ? 'selected' : '' }}>Pago</option>
                <option value="Atrasado" {{ old('status', isset($financeiro) ? $financeiro->status : '') == 'Atrasado' ? 'selected' : '' }}>Atrasado</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="description" class="col-form-label" style="color:black">Descrição:</label>
            <textarea class="form-control @error('description') is-invalid @enderror"
                      name="description">{{ old('description', isset($financeiro) ? $financeiro->descricao : '') }}</textarea>
            @error('description')
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
