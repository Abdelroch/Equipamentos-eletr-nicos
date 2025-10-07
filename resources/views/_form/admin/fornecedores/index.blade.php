<div class="row">
    
    <div class="col-md-6">
        <div class="form-group">
            <label for="name" class="col-form-label" style="color:black">Nome Completo:</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" 
             value="{{ old('name', isset($fornecedor) ? $fornecedor->nome : '' ) }}"  
                name="name" required >
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="email" class="col-form-label" style="color:black">Email:</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" 
             value="{{ old('email', isset($fornecedor) ? $fornecedor->email : '' ) }}"  
                name="email" required >
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>


    <div class="col-md-6">
        <div class="form-group">
            <label for="phone" class="col-form-label " style="color:black">Contacto:</label>
            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
            value="{{ old('phone', isset($fornecedor) ? $fornecedor->numero : '' ) }}"  
                id="phone" name="phone" required maxlength="15"> 
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="country" class="col-form-label" style="color:black">País:</label>
            <input type="text" class="form-control @error('country') is-invalid @enderror" 
            value="{{ old('country', isset($fornecedor) ? $fornecedor->pais : '' ) }}"  
                id="country" name="country" required>
            @error('country')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="col-md-6">
        <div class="form-group">
            <label for="province" class="col-form-label" style="color:black">Província:</label>
            <input type="text" class="form-control @error('province') is-invalid @enderror" 
            value="{{ old('province', isset($fornecedor) ? $fornecedor->provincia : '' ) }}"  
                id="province" name="province" required>
            @error('province')
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