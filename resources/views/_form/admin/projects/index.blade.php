<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="col-form-label" style="color:black">Nome:</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', isset($projeto) ? $projeto->nome : '') }}"
                   name="name" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="description" class="col-form-label" style="color:black">Descrição:</label>
            <textarea class="form-control @error('description') is-invalid @enderror"
                      name="description" required>{{ old('description', isset($projeto) ? $projeto->descricao : '') }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="start_date" class="col-form-label" style="color:black">Data de Início:</label>
            <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                   value="{{ old('start_date', isset($projeto) ? $projeto->data_inicio : '') }}"
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
                   value="{{ old('end_date', isset($projeto) ? $projeto->data_fim : '') }}"
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
                <option value="" disabled {{ old('status', isset($projeto) ? $projeto->status : '') == '' ? 'selected' : '' }}>Selecione</option>
                <option value="Planejamento" {{ old('status', isset($projeto) ? $projeto->status : '') == 'Planejamento' ? 'selected' : '' }}>Planejamento</option>
                <option value="Em Andamento" {{ old('status', isset($projeto) ? $projeto->status : '') == 'Em Andamento' ? 'selected' : '' }}>Em Andamento</option>
                <option value="Concluído" {{ old('status', isset($projeto) ? $projeto->status : '') == 'Concluído' ? 'selected' : '' }}>Concluído</option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="budget" class="col-form-label" style="color:black">Orçamento:</label>
            <input type="number" step="0.01" class="form-control @error('budget') is-invalid @enderror"
                   value="{{ old('budget', isset($projeto) ? $projeto->orcamento : '') }}"
                   name="budget" required>
            @error('budget')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="responsible_id" class="col-form-label" style="color:black">Responsável:</label>
            <select class="form-control @error('responsible_id') is-invalid @enderror" name="responsible_id" required>
                <option value="" disabled {{ old('responsible_id', isset($projeto) ? $projeto->id_responsavel : '') == '' ? 'selected' : '' }}>Selecione</option>
                @foreach ($data['funcionarios'] as $funcionario)
                    <option value="{{ $funcionario->id }}" {{ old('responsible_id', isset($projeto) ? $projeto->id_responsavel : '') == $funcionario->id ? 'selected' : '' }}>{{ $funcionario->name }}</option>
                @endforeach
            </select>
            @error('responsible_id')
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
