
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="transaction_type" class="col-form-label" style="color:black">Tipo de Transação:</label>
            <select class="form-control @error('transaction_type') is-invalid @enderror" name="transaction_type" required>
                <option value="" disabled {{ old('transaction_type') == '' ? 'selected' : '' }}>Selecione</option>
                <option value="Saque" {{ old('transaction_type') == 'Saque' ? 'selected' : '' }}>Saque</option>
                <option value="Empréstimo" {{ old('transaction_type') == 'Empréstimo' ? 'selected' : '' }}>Empréstimo</option>
            </select>
            @error('transaction_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="amount" class="col-form-label" style="color:black">Valor:</label>
            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                   value="{{ old('amount') }}" name="amount" required>
            @error('amount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="transaction_date" class="col-form-label" style="color:black">Data da Transação:</label>
            <input type="date" class="form-control @error('transaction_date') is-invalid @enderror"
                   value="{{ old('transaction_date') }}" name="transaction_date" required>
            @error('transaction_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="interest_rate" class="col-form-label" style="color:black">Taxa de Juros (apenas para empréstimos):</label>
            <input type="number" step="0.01" class="form-control @error('interest_rate') is-invalid @enderror"
                   value="{{ old('interest_rate') }}" name="interest_rate">
            @error('interest_rate')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="due_date" class="col-form-label" style="color:black">Data de Vencimento (apenas para empréstimos):</label>
            <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                   value="{{ old('due_date') }}" name="due_date">
            @error('due_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="description" class="col-form-label" style="color:black">Descrição:</label>
            <textarea class="form-control @error('description') is-invalid @enderror"
                      name="description">{{ old('description') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12" style="display: flex; justify-content: flex-end;">
        <button type="submit" class="mb-2 btn btn-primary">Salvar</button>
    </div>
</div>

@if($errors->any())
    <script>
        $(document).ready(() => {
            $('#modalCreate').modal('show');
        });
    </script>
@endif
