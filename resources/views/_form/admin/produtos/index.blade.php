@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="row" style="margin-bottom: 12px">

    @if (isset($produto))
        <div class="col-md-12">
            <div class="form-group">
                <label for="cover_image" class="col-form-label" style="color:black">Imagem de capa [Será exibido no visitante]:</label>
                <input type="file" class="form-control" name="cover_image">
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="name" class="col-form-label" style="color:black">Imagens do Produto:</label>
                <input type="file" class="form-control" name="imgs[]" multiple>
            </div>
        </div>
    @endif
    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="col-form-label" style="color:black">Nome do Produto:</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', isset($produto) ? $produto->nome : '') }}" name="name" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="inform" class="col-form-label " style="color:black">Descrição:</label>
                <textarea name="inform" id="" class="form-control">{{ old('inform', isset($produto) ? $produto->descricao : '') }}</textarea>
            @error('inform')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="price" class="col-form-label" style="color:black">Preço (KZ):</label>
            <input type="text" class="form-control @error('price') is-invalid @enderror"
                value="{{ old('price', isset($produto) ? number_format($produto->preco, 2, '.', '') : '') }}"
                id="price" name="price" required placeholder="Ex.: 1999.89">
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="quantity" class="col-form-label" style="color:black">Quantidade Disponível:</label>
            <input type="text" class="form-control @error('quantity') is-invalid @enderror"
                value="{{ old('quantity', isset($produto) ? $produto->quantidade_disponivel : '1') }}" id="quantity"
                name="quantity" required>
            @error('quantity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

   {{--  <div class="col-md-6">
        <div class="form-group">
            <label for="category" class="col-form-label" style="color:black">Categoria:</label>
            <input type="text" class="form-control @error('category') is-invalid @enderror"
                value="{{ old('category', isset($produto) ? $produto->categoria : '') }}" id="category"
                name="category" required>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div> --}}

    <div class="col-md-6">
        <div class="form-group">
            <label for="category" class="col-form-label" style="color:black">Categoria:</label>
            <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                <option value="" disabled {{ old('category', isset($produto) ? $produto->categoria : '') == '' ? 'selected' : '' }}>Selecione uma categoria</option>
                <option value="smartphones" {{ old('category', isset($produto) ? $produto->categoria : '') == 'smartphones' ? 'selected' : '' }}>Smartphones</option>
                <option value="laptops" {{ old('category', isset($produto) ? $produto->categoria : '') == 'laptops' ? 'selected' : '' }} selected>Laptops</option>
                <option value="desktops" {{ old('category', isset($produto) ? $produto->categoria : '') == 'desktops' ? 'selected' : '' }}>Computadores Desktop</option>
                <option value="tablets" {{ old('category', isset($produto) ? $produto->categoria : '') == 'tablets' ? 'selected' : '' }}>Tablets</option>
                <option value="smartwatches" {{ old('category', isset($produto) ? $produto->categoria : '') == 'smartwatches' ? 'selected' : '' }}>Smartwatches</option>
                <option value="headphones" {{ old('category', isset($produto) ? $produto->categoria : '') == 'headphones' ? 'selected' : '' }}>Fones de Ouvido</option>
                <option value="speakers" {{ old('category', isset($produto) ? $produto->categoria : '') == 'speakers' ? 'selected' : '' }}>Caixas de Som</option>
                <option value="gaming_consoles" {{ old('category', isset($produto) ? $produto->categoria : '') == 'gaming_consoles' ? 'selected' : '' }}>Consoles de Jogos</option>
                <option value="monitors" {{ old('category', isset($produto) ? $produto->categoria : '') == 'monitors' ? 'selected' : '' }}>Monitores</option>
                <option value="keyboards" {{ old('category', isset($produto) ? $produto->categoria : '') == 'keyboards' ? 'selected' : '' }}>Teclados</option>
                <option value="mice" {{ old('category', isset($produto) ? $produto->categoria : '') == 'mice' ? 'selected' : '' }}>Mouses</option>
                <option value="printers" {{ old('category', isset($produto) ? $produto->categoria : '') == 'printers' ? 'selected' : '' }}>Impressoras</option>
                <option value="routers" {{ old('category', isset($produto) ? $produto->categoria : '') == 'routers' ? 'selected' : '' }}>Roteadores</option>
                <option value="cameras" {{ old('category', isset($produto) ? $produto->categoria : '') == 'cameras' ? 'selected' : '' }}>Câmeras</option>
                <option value="drones" {{ old('category', isset($produto) ? $produto->categoria : '') == 'drones' ? 'selected' : '' }}>Drones</option>

                <option value="memória ram" {{ old('category', isset($produto) ? $produto->categoria : '') == 'memória ram' ? 'selected' : '' }}>Memória Ram</option>
                <option value="hd" {{ old('category', isset($produto) ? $produto->categoria : '') == 'hd' ? 'selected' : '' }}>HD</option>
                <option value="accessories" {{ old('category', isset($produto) ? $produto->categoria : '') == 'accessories' ? 'selected' : '' }}>Acessórios</option>
            </select>
            @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        </div>


    <div class="col-md-12">
        <div class="form-group">
            <label for="supplier_name" class="col-form-label" style="color:black">Nome do Fornecedor:</label>
            <select class="form-control @error('supplier_name') is-invalid @enderror" id="supplier_name"
                name="supplier_name" required>
                <option value="">Selecione um fornecedor</option>
                @foreach ($fornecedores as $fornecedor)
                    <option value="{{ $fornecedor->id }}" selected
                        {{ old('supplier_name') == $fornecedor->id || (isset($produto) && $produto->nome_fornecedor == $fornecedor->id) ? 'selected' : '' }}
                        required>
                        {{ $fornecedor->nome }}
                    </option>
                @endforeach
            </select>
            @error('supplier_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <label for="status" class="col-form-label" style="color:black">Status:</label>
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>

                <option value="" disabled {{ old('status', isset($produto) ? $produto->status : '') == '' ? 'selected' : '' }}>Selecione o status</option>

                <option value="Extremamente Bom" {{ old('status', isset($produto) ? $produto->status : '') == 'Extremamente Bom' ? 'selected' : '' }}>
                    Extremamente Bom</option>

                <option value="Bom" {{ old('status', isset($produto) ? $produto->status : '') == 'Bom' ? 'selected' : '' }}>
                    Bom</option>
                <option value="Avariado" {{ old('status', isset($produto) ? $produto->status : '') == 'Avariado' ? 'selected' : '' }}>Avariado</option>
                <option value="Em Análise" {{ old('status', isset($produto) ? $produto->status : '') == 'Em Análise' ? 'selected' : '' }}>Em Análise</option>
                <option value="Reparado" {{ old('status', isset($produto) ? $produto->status : '') == 'Reparado' ? 'selected' : '' }}>Reparado</option>
                <option value="Irreparável" {{ old('status', isset($produto) ? $produto->status : '') == 'Irreparável' ? 'selected' : '' }}>Irreparável</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        </div>

        @if (isset($produto))


        <div class="col-md-12">
            <div class="form-group">
                <label for="estado_venda" class="col-form-label" style="color:black">Estado da Venda:</label>
                <select class="form-control" name="estado_venda" id="estado_venda">
                    <option value="disponivel" {{ old('estado_venda', $produto->estado_venda ?? '') == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                    <option value="reservado" {{ old('estado_venda', $produto->estado_venda ?? '') == 'reservado' ? 'selected' : '' }}>Reservado</option>
                    <option value="ofertado" {{ old('estado_venda', $produto->estado_venda ?? '') == 'ofertado' ? 'selected' : '' }}>
                        Ofertado</option>
                    <option value="vendido" {{ old('estado_venda', $produto->estado_venda ?? '') == 'vendido' ? 'selected' : '' }}>
                        Vendido</option>
                    <option value="cancelado" {{ old('estado_venda', $produto->estado_venda ?? '') == 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                    <option value="pendente" {{ old('estado_venda', $produto->estado_venda ?? '') == 'pendente' ? 'selected' : '' }}>
                        Pendente</option>
                </select>
                </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                <label for="customer" class="col-form-label" style="color:black">Cliente (em caso de vendas):</label>
                <select class="form-control @error('customer') is-invalid @enderror" id="customer"
                    name="customer">
                    <option value="">Selecione um cliente</option>
                    @foreach ($fornecedores as $fornecedor)
                       {{--  <option value="{{ $fornecedor->id }}" selected {{ old('customer') == $fornecedor->id || (isset($produto) && $produto->nome_fornecedor == $fornecedor->id) ? 'selected' : '' }} required>
                            {{ $fornecedor->nome }}
                        </option> --}}
                    @endforeach
                </select>
                @error('customer')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            </div>
                 @endif

</div>


<div class="row">
    <div class="col-12" style="display: flex; justify-content: flex-end;">
        <button type="submit" class="mb-2 btn btn-primary">Salvar</button>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const priceInput = document.getElementById('price');

        priceInput.addEventListener('input', function(e) {
            // Remove tudo que não é dígito ou vírgula
            let value = e.target.value.replace(/[^0-9,]/g, '');

            // Se houver uma vírgula, substitui para formatação correta
            if (value.includes(',')) {
                value = value.replace(',',
                '.'); // Converte a vírgula em ponto para facilitar a manipulação
            }

            // Formata o valor como número
            const numericValue = parseFloat(value.replace('.', ','));

            if (!isNaN(numericValue)) {
                // Formata o valor para exibição
                e.target.value = numericValue.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 1,
                }).replace(',', '.');
            }
        });
    });
</script>

@if ($errors->any())
    <script>
        $(document).ready(() => {
            $('#modalCreate').modal('show');
        });
    </script>
@endif
