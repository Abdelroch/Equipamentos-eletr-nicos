<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="product_id" class="col-form-label" style="color:black">Produto:</label>
            <select class="form-control @error('product_id') is-invalid @enderror" name="product_id" required>
                <option value="" disabled {{ old('product_id', isset($ordem) ? $ordem->product_id : '') == '' ? 'selected' : '' }}>Selecione</option>
                @foreach ($data['produtos'] as $produto)
                    <option value="{{ $produto->id }}" {{ old('product_id', isset($ordem) ? $ordem->product_id : '') == $produto->id ? 'selected' : '' }}>{{ $produto->nome }}</option>
                @endforeach
            </select>
            @error('product_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="quantity" class="col-form-label" style="color:black">Quantidade:</label>
            <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                   value="{{ old('quantity', isset($ordem) ? $ordem->quantity : '') }}"
                   name="quantity" required>
            @error('quantity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="start_date" class="col-form-label" style="color:black">Data de Início:</label>
            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                   value="{{ old('start_date', isset($ordem) ? $ordem->start_date : '') }}"
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
                   value="{{ old('end_date', isset($ordem) ? $ordem->end_date : '') }}"
                   name="end_date">
            @error('end_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="status" class="col-form-label" style="color:black">Status:</label>
            <select class="form-control @error('status') is-invalid @enderror" name="status" required>
                <option value="" disabled {{ old('status', isset($ordem) ? $ordem->status : '') == '' ? 'selected' : '' }}>Selecione</option>
                <option value="Agendado" {{ old('status', isset($ordem) ? $ordem->status : '') == 'Agendado' ? 'selected' : '' }}>Agendado</option>
                <option value="Em Andamento" {{ old('status', isset($ordem) ? $ordem->status : '') == 'Em Andamento' ? 'selected' : '' }}>Em Andamento</option>
                <option value="Concluído" {{ old('status', isset($ordem) ? $ordem->status : '') == 'Concluído' ? 'selected' : '' }}>Concluído</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="raw_materials" class="col-form-label" style="color:black">Matérias-Primas:</label>
            <textarea class="form-control @error('raw_materials') is-invalid @enderror"
                      name="raw_materials">{{ old('raw_materials', isset($ordem) ? $ordem->raw_materials : '') }}</textarea>
            @error('raw_materials')
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
