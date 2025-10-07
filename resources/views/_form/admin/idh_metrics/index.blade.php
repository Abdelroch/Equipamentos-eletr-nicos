<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="metric_name" class="col-form-label" style="color:black">Nome da Métrica:</label>
            <input type="text" class="form-control @error('metric_name') is-invalid @enderror"
                   value="{{ old('metric_name', isset($metrica) ? $metrica->metric_name : '') }}"
                   name="metric_name" required>
            @error('metric_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="value" class="col-form-label" style="color:black">Valor:</label>
            <input type="number" step="0.01" class="form-control @error('value') is-invalid @enderror"
                   value="{{ old('value', isset($metrica) ? $metrica->value : '') }}"
                   name="value" required>
            @error('value')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="recorded_at" class="col-form-label" style="color:black">Data de Registro:</label>
            <input type="date" class="form-control @error('recorded_at') is-invalid @enderror"
                   value="{{ old('recorded_at', isset($metrica) ? $metrica->recorded_at : '') }}"
                   name="recorded_at" required>
            @error('recorded_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="region" class="col-form-label" style="color:black">Região:</label>
            <input type="text" class="form-control @error('region') is-invalid @enderror"
                   value="{{ old('region', isset($metrica) ? $metrica->region : '') }}"
                   name="region">
            @error('region')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-12">
        <div class="form-group">
            <label for="notes" class="col-form-label" style="color:black">Notas:</label>
            <textarea class="form-control @error('notes') is-invalid @enderror"
                      name="notes">{{ old('notes', isset($metrica) ? $metrica->notes : '') }}</textarea>
            @error('notes')
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
