<div class="row" style="margin-bottom: 12px">
    <div class="col-md-12">
        <div class="alert alert-info">
            Saldo atual do orçamento: {{ number_format(\App\Models\Budget::sum('amount') ?? 0, 2, ',', '.') }} Kz
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="customer_id" class="col-form-label" style="color:black">Nome do Cliente:</label>
            <select class="form-control @error('customer_id') is-invalid @enderror" id="customer_id" name="customer_id" required>
                <option value="">Selecione um cliente</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" {{ old('customer_id', isset($sale) ? $sale->customer_id : '') == $customer->id ? 'selected' : '' }}>
                        {{ $customer->nome }}
                    </option>
                @endforeach
            </select>
            @error('customer_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="id_product" class="col-form-label" style="color:black">Nome do Produto:</label>
            <select class="form-control @error('id_product') is-invalid @enderror" id="id_product" name="id_product" required>
                <option value="">Selecione um produto</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}"
                            data-preco="{{ $product->preco }}"
                            data-estoque="{{ $product->quantidade_disponivel }}"
                            {{ old('id_product', isset($sale) ? $sale->id_product : '') == $product->id ? 'selected' : '' }}>
                        {{ $product->nome }} (Preço: {{ number_format($product->preco, 2, ',', '.') }} | Estoque: {{ $product->quantidade_disponivel }})
                    </option>
                @endforeach
            </select>
            @error('id_product')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="quantidade" class="col-form-label" style="color:black">Quantidade Comprada:</label>
            <input type="number" class="form-control @error('quantidade') is-invalid @enderror"
                   value="{{ old('quantidade', isset($sale) ? $sale->quantidade : '') }}"
                   id="quantidade" name="quantidade" min="1" required>
            @error('quantidade')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="data_venda" class="col-form-label" style="color:black">Data da Venda:</label>
            <input type="date" class="form-control @error('data_venda') is-invalid @enderror"
                   value="{{ old('data_venda', isset($sale) ? $sale->data_venda : '') }}"
                   id="data_venda" name="data_venda" required>
            @error('data_venda')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="total_display" class="col-form-label" style="color:black">Total:</label>
            <input type="text" class="form-control"
                   value="{{ isset($sale) ? number_format($sale->total, 2, ',', '.') . ' Kz' : '' }}"
                   id="total_display" readonly>
            {{-- Campo somente leitura, apenas para visualização: o backend
                 recalcula o total a partir de preço x quantidade no momento
                 do submit, então não enviamos este valor formatado. --}}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12" style="display: flex; justify-content: flex-end;">
        <button type="submit" class="mb-2 btn btn-primary" id="submitButton">Salvar</button>
    </div>
</div>

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const produtoSelect = document.getElementById('id_product');
            const quantidadeInput = document.getElementById('quantidade');
            const totalDisplay = document.getElementById('total_display');
            const submitButton = document.getElementById('submitButton');

            function calcularTotal() {
                const selectedOption = produtoSelect.options[produtoSelect.selectedIndex];
                const preco = selectedOption ? parseFloat(selectedOption.dataset.preco) : 0;
                const estoque = selectedOption ? parseInt(selectedOption.dataset.estoque) : 0;
                const quantidade = parseInt(quantidadeInput.value) || 0;

                if (quantidade > estoque) {
                    quantidadeInput.classList.add('is-invalid');
                    quantidadeInput.nextElementSibling.textContent = 'Quantidade excede o estoque disponível.';
                    submitButton.disabled = true;
                } else {
                    quantidadeInput.classList.remove('is-invalid');
                    quantidadeInput.nextElementSibling.textContent = '';
                    submitButton.disabled = false;
                }

                const total = preco * quantidade;
                totalDisplay.value = total.toLocaleString('pt-PT', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }) + ' Kz';
            }

            produtoSelect.addEventListener('change', calcularTotal);
            quantidadeInput.addEventListener('input', calcularTotal);

            calcularTotal();
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
