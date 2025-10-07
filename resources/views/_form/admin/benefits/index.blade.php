<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            Saldo atual do orçamento: {{ number_format(\App\Models\Budget::sum('amount') ?? 0, 2, ',', '.') }} KZ
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="employee_id" class="col-form-label" style="color:black">Funcionário:</label>
            <select class="form-control @error('employee_id') is-invalid @enderror" name="employee_id" required>
                <option value="" disabled {{ old('employee_id', isset($beneficio) ? $beneficio->employee_id : '') == '' ? 'selected' : '' }}>Selecione</option>
                @foreach ($data['funcionarios'] as $funcionario)
                    <option value="{{ $funcionario->id }}" {{ old('employee_id', isset($beneficio) ? $beneficio->employee_id : '') == $funcionario->id ? 'selected' : '' }}>{{ $funcionario->name }}</option>
                @endforeach
            </select>
            @error('employee_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="benefit_type" class="col-form-label" style="color:black">Tipo de Benefício:</label>
            <select class="form-control @error('benefit_type') is-invalid @enderror" name="benefit_type" required>
                <option value="" disabled {{ old('benefit_type', isset($beneficio) ? $beneficio->benefit_type : '') == '' ? 'selected' : '' }}>Selecione</option>
                <option value="Seguro de Saúde" {{ old('benefit_type', isset($beneficio) ? $beneficio->benefit_type : '') == 'Seguro de Saúde' ? 'selected' : '' }}>Seguro de Saúde</option>
                <option value="Bônus" {{ old('benefit_type', isset($beneficio) ? $beneficio->benefit_type : '') == 'Bônus' ? 'selected' : '' }}>Bônus</option>
                <option value="Vale Alimentação" {{ old('benefit_type', isset($beneficio) ? $beneficio->benefit_type : '') == 'Vale Alimentação' ? 'selected' : '' }}>Vale Alimentação</option>
            </select>
            @error('benefit_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="amount" class="col-form-label" style="color:black">Valor:</label>
            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                   value="{{ old('amount', isset($beneficio) ? $beneficio->amount : '') }}"
                   name="amount" id="amount">
            @error('amount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="start_date" class="col-form-label" style="color:black">Data de Início:</label>
            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                   value="{{ old('start_date', isset($beneficio) ? $beneficio->start_date : '') }}"
                   name="start_date" required>
            @error('start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="end_date" class="col-form-label" style="color:black">Data de Fim:</label>
            <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                   value="{{ old('end_date', isset($beneficio) ? $beneficio->end_date : '') }}"
                   name="end_date">
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="description" class="col-form-label" style="color:black">Descrição:</label>
            <textarea class="form-control @error('description') is-invalid @enderror"
                      name="description">{{ old('description', isset($beneficio) ? $beneficio->description : '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12" style="display: flex; justify-content: flex-end;">
        <button type="submit" class="btn mb-2 btn-primary" id="submitButton">Salvar</button>
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const amountInput = document.getElementById('amount');
            const submitButton = document.getElementById('submitButton');
            const currentBalance = {{ \App\Models\Budget::sum('amount') ?? 0 }};

            function validateBalance() {
                const amount = parseFloat(amountInput.value) || 0;
                if (amount > currentBalance) {
                    amountInput.classList.add('is-invalid');
                    amountInput.nextElementSibling.textContent = 'Valor excede o saldo disponível.';
                    submitButton.disabled = true;
                } else {
                    amountInput.classList.remove('is-invalid');
                    amountInput.nextElementSibling.textContent = '';
                    submitButton.disabled = false;
                }
            }

            amountInput.addEventListener('input', validateBalance);
            validateBalance();
        });
    </script>
@endsection

@if($errors->any())
    <script>
        $(document).ready(() => {
            $('#modalCreate').modal('show');
        });
    </script>
@endif
