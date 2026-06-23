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

    {{-- Imagem de Capa --}}
    <div class="col-md-12">
        <div class="form-group">
            <label class="col-form-label" style="color:black">
                Imagem de Capa <small class="text-muted">(exibida ao visitante)</small>:
            </label>
            @if (isset($produto) && $produto->cover_image)
                <div class="mb-2">
                    <img src="{{ asset($produto->cover_image) }}" alt="Capa actual"
                        style="max-height:80px; object-fit:cover; border-radius:4px;">
                    <small class="text-muted d-block">Imagem actual — envie uma nova para substituir</small>
                </div>
            @endif
            <input type="file" class="form-control" name="cover_image" accept="image/*">
        </div>
    </div>

    {{-- Imagens adicionais --}}
    <div class="col-md-12">
        <div class="form-group">
            <label class="col-form-label" style="color:black">
                Imagens Adicionais do Produto:
            </label>
            @if (isset($produto) && is_array($produto->imagens) && !empty($produto->imagens))
                <div class="flex-wrap gap-1 mb-2 d-flex">
                    @foreach ($produto->imagens as $img)
                        <img src="{{ asset($img) }}"
                            style="max-height:60px; object-fit:cover; border-radius:4px; margin-right:4px;">
                    @endforeach
                    <small class="text-muted w-100">Enviar novas imagens irá adicionar às existentes</small>
                </div>
            @endif
            <input type="file" class="form-control" name="imgs[]" multiple accept="image/*">
        </div>
    </div>

    {{-- Nome --}}
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

    {{-- Marca --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="marca" class="col-form-label" style="color:black">
                Marca <small class="text-muted">(ex: Samsung, JBL, Apple)</small>:
            </label>
            <input type="text"
                class="form-control @error('marca') is-invalid @enderror"
                id="marca"
                name="marca"
                value="{{ old('marca', isset($produto) ? $produto->marca : '') }}"
                placeholder="Deixe em branco se não aplicável">
            @error('marca')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Descrição --}}
    <div class="col-md-12">
        <div class="form-group">
            <label for="inform" class="col-form-label" style="color:black">
                Descrição:
                <small class="text-muted">(máx. 300 caracteres)</small>
            </label>
            <textarea name="inform" id="inform" class="form-control @error('inform') is-invalid @enderror"
                      rows="3" maxlength="300"
                      style="resize: vertical; min-height: 80px; max-height: 160px;"
            >{{ old('inform', isset($produto) ? $produto->descricao : '') }}</textarea>
            <div class="mt-1 d-flex justify-content-end">
                <small id="inform-counter" class="text-muted">
                    <span id="inform-used">0</span>/300
                </small>
            </div>
            @error('inform')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Preço --}}
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

    {{-- Quantidade --}}
    <div class="col-md-6">
        <div class="form-group">
            <label for="quantity" class="col-form-label" style="color:black">Quantidade Disponível:</label>
            <input type="text" class="form-control @error('quantity') is-invalid @enderror"
                value="{{ old('quantity', isset($produto) ? $produto->quantidade_disponivel : '1') }}"
                id="quantity" name="quantity" required>
            @error('quantity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Categoria --}}
    <div class="col-md-6">
        <div class="col-md-6">
            <div class="form-group">
                <label for="category" class="col-form-label" style="color:black">Categoria:</label>
                <select class="form-control @error('category') is-invalid @enderror" id="category" name="category" required>
                    <option value="" disabled {{ old('category', isset($produto) ? $produto->categoria : '') == '' ? 'selected' : '' }}>
                        Selecione uma categoria
                    </option>
                    @foreach ($categorias as $cat)
                        <option value="{{ $cat->slug }}"
                            {{ old('category', isset($produto) ? $produto->categoria : '') == $cat->slug ? 'selected' : '' }}>
                            {{ $cat->nome }}
                        </option>
                    @endforeach
                </select>
                @error('category')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    {{-- Cores --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-form-label">Cores Disponíveis <small>(separadas por vírgula)</small></label>
            <input type="text" class="form-control" name="cores"
                   value="{{ old('cores', isset($produto) && $produto->cores ? implode(', ', $produto->cores) : '') }}"
                   placeholder="Prateado, Preto, Dourado">
            <small class="text-muted">Deixe em branco se não tiver cores.</small>
        </div>
    </div>

    {{-- Tamanhos --}}
    <div class="col-md-6">
        <div class="form-group">
            <label class="col-form-label">Tamanhos Disponíveis <small>(separados por vírgula)</small></label>
            <input type="text" class="form-control" name="tamanhos"
                   value="{{ old('tamanhos', isset($produto) && $produto->tamanhos ? implode(', ', $produto->tamanhos) : '') }}"
                   placeholder="34mm, 38mm, 42mm">
            <small class="text-muted">Deixe em branco se não tiver tamanhos.</small>
        </div>
    </div>

    {{-- Fornecedor --}}
    <div class="col-md-12">
        <div class="form-group">
            <label for="supplier_name" class="col-form-label" style="color:black">Nome do Fornecedor:</label>
            <select class="form-control @error('supplier_name') is-invalid @enderror" id="supplier_name"
                name="supplier_name" required>
                <option value="">Selecione um fornecedor</option>
                @foreach ($fornecedores as $fornecedor)
                    <option value="{{ $fornecedor->id }}"
                        {{ old('supplier_name', isset($produto) ? $produto->id_fornecedor : '') == $fornecedor->id ? 'selected' : '' }}>
                        {{ $fornecedor->nome }}
                    </option>
                @endforeach
            </select>
            @error('supplier_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Status --}}
    <div class="col-md-12">
        <div class="form-group">
            <label for="status" class="col-form-label" style="color:black">Status:</label>
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status" required>
                <option value="" disabled {{ old('status', isset($produto) ? $produto->status : '') == '' ? 'selected' : '' }}>Selecione o status</option>
                @foreach ([
                    'Extremamente Bom' => 'Extremamente Bom',
                    'Bom'              => 'Bom',
                    'Avariado'         => 'Avariado',
                    'Em Análise'       => 'Em Análise',
                    'Reparado'         => 'Reparado',
                    'Irreparável'      => 'Irreparável',
                ] as $value => $label)
                    <option value="{{ $value }}"
                        {{ old('status', isset($produto) ? $produto->status : '') == $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    {{-- Estado da venda — só no edit --}}
    @if (isset($produto))
        <div class="col-md-12">
            <div class="form-group">
                <label for="estado_venda" class="col-form-label" style="color:black">Estado da Venda:</label>
                <select class="form-control" name="estado_venda" id="estado_venda">
                    @foreach ([
                        'disponivel' => 'Disponível',
                        'reservado'  => 'Reservado',
                        'ofertado'   => 'Ofertado',
                        'vendido'    => 'Vendido',
                        'cancelado'  => 'Cancelado',
                        'pendente'   => 'Pendente',
                    ] as $value => $label)
                        <option value="{{ $value }}"
                            {{ old('estado_venda', $produto->estado_venda ?? '') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
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
    document.addEventListener('DOMContentLoaded', function () {
        // --- Contador de caracteres da descrição ---
        const informTA  = document.getElementById('inform');
        const informUsed = document.getElementById('inform-used');
        if (informTA && informUsed) {
            const max = parseInt(informTA.getAttribute('maxlength'), 10);
            const update = () => {
                const len = informTA.value.length;
                informUsed.textContent = len;
                informUsed.style.color = len >= max ? '#dc3545' : (len >= max * 0.85 ? '#fd7e14' : '');
            };
            update(); // estado inicial (útil no edit onde já vem texto)
            informTA.addEventListener('input', update);
        }

        // --- Formatação do preço ---
        const priceInput = document.getElementById('price');
        if (!priceInput) return;

        priceInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/[^0-9,]/g, '');
            if (value.includes(',')) {
                value = value.replace(',', '.');
            }
            const numericValue = parseFloat(value);
            if (!isNaN(numericValue)) {
                e.target.value = numericValue.toLocaleString('pt-BR', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
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
