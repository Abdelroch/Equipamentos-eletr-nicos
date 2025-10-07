@extends('layouts.admin.body')

@section('title', __('Editar Transação'))

@section('conteudo')
    <div class="container-fluid">
        <h4 class="mb-3"><strong>Editar {{ $transaction->transaction_type }}</strong></h4>
        <p class="mb-4">Atualize os detalhes da transação de {{ $transaction->transaction_type === 'Saque' ? 'saque' : 'empréstimo' }}.</p>

        <div class="shadow-sm card">
            <div class="card-body">
                <div class="alert alert-info">
                    Saldo atual: {{ number_format($currentBalance, 2, ',', '.') }} AOA
                </div>
                <form action="{{ route('admin.gestao.transaction.update', $transaction->id) }}" method="POST" id="editForm">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="mb-3 col-md-6>
                            <label for="amount" class="form-label">Valor (AOA) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror"
                                   name="amount" id="amount" value="{{ old('amount', abs($transaction->amount)) }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="transaction_date" class="form-label">Data da Transação <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('transaction_date') is-invalid @endif"
                                   name="transaction_date" value="{{ old('transaction_date', $transaction->transaction_date) }}" required>
                            @error('transaction_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        @if ($transaction->transaction_type === 'Empréstimo')
                            <div class="mb-3 col-md-6">
                                <label for="interest_rate" class="form-label">Taxa de Juros (%) <span class="text-muted">(Opcional)</span></label>
                                <input type="number" step="0.01" class="form-control @error('interest_rate') is-invalid @enderror"
                                       name="interest_rate" id="interest_rate" value="{{ old('interest_rate', $transaction->financial->interest_rate ?? '') }}">
                                @error('interest_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3 col-md-6">
                                <label for="due_date" class="form-label">Data de Vencimento <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror"
                                       name="due_date" value="{{ old('due_date', $transaction->financial->due_date ?? '') }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                        <div class="mb-3 col-12">
                            <label for="description" class="form-label">Descrição <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      name="description" rows="4" required>{{ old('description', $transaction->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('admin.gestao.withdrawals-loans') }}" class="btn btn-secondary me-2">Cancelar</a>
                        <button type="submit" class="btn btn-primary" id="submitBtn">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    @section('scripts')
        <script>
            // Validação de saldo para saques
            @if ($transaction->transaction_type === 'Saque')
                document.addEventListener('DOMContentLoaded', function () {
                    const editForm = document.getElementById('editForm');
                    if (editForm) {
                        const amountInput = document.getElementById('amount');
                        const submitBtn = document.getElementById('submitBtn');
                        const currentBalance = {{ $currentBalance }};

                        function validateBalance() {
                            const amount = parseFloat(amountInput.value) || 0;
                            if (amount > currentBalance) {
                                amountInput.classList.add('is-invalid');
                                amountInput.nextElementSibling.textContent = 'Valor excede o saldo disponível.';
                                submitBtn.disabled = true;
                            } else {
                                amountInput.classList.remove('is-invalid');
                                amountInput.nextElementSibling.textContent = '';
                                submitBtn.disabled = false;
                            }
                        }

                        amountInput.addEventListener('input', validateBalance);
                        validateBalance();
                    }
                });
            @endif
        </script>
    </div>
@endsection
