@extends('layouts.visitor.main')

@section('title', 'Detalhes do Producto')

@section('content')

    <!-- BREADCRUMB -->
    <div id="breadcrumb" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <ul class="breadcrumb-tree">
                        <li><a href="#">Home</a></li>
                        <li><a href="#" style="text-transform: uppercase">{{ $product->categoria }}</a></li>
                        {{-- <li><a href="#">Accessories</a></li>
                            <li><a href="#">Headphones</a></li> --}}
                        <li class="active" style="text-transform: uppercase">{{ $product->nome }}</li>
                    </ul>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /BREADCRUMB -->

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <!-- Product main img -->
                <div class="col-md-5 col-md-push-2">
                    <div id="product-main-img">
                        <div class="product-preview">
                            <img style="max-width: 100%; height: 360px; object-fit: cover;"
                                src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                alt="cover image">
                        </div>

                        {{-- <div class="product-preview">
                                <img src="./img/product03.png" alt="">
                            </div>

                            <div class="product-preview">
                                <img src="./img/product06.png" alt="">
                            </div>

                            <div class="product-preview">
                                <img src="./img/product08.png" alt="">
                            </div> --}}

                        {{-- @foreach ($product->imagens as $imagem)
                                <div class="product-preview">
                                        <img src="{{ asset($imagem) }}" alt="Imagem de {{ $product->nome }}">
                                    </div>
                            @endforeach --}}

                        @empty($product->imagens)
                            <img src="{{ asset('visitor/img/no_image.png') }}" alt="undefined">
                        @else
                            @foreach ($product->imagens as $imagem)
                                <div class="product-preview">
                                    <img src="{{ asset($imagem) }}" alt="Imagem de {{ $product->nome }}">
                                </div>
                            @endforeach
                        @endempty
                    </div>
                </div>
                <!-- /Product main img -->

                <!-- Product thumb imgs -->
                <div class="col-md-2 col-md-pull-5">
                    <div id="product-imgs">
                        {{--   <div class="product-preview">
                                <img src="./img/product01.png" alt="">
                            </div> --}}

                        @empty($product->imagens)
                            <img src="{{ asset('visitor/img/no_image.png') }}" alt="undefined"
                                style="object-fit: cover; height: 100px;">
                        @else
                            @foreach ($product->imagens as $imagem)
                                <div class="product-preview">
                                    <img src="{{ asset($imagem) }}" alt="Imagem de {{ $product->nome }}">
                                </div>
                            @endforeach
                        @endempty

                        {{-- <div class="product-preview">
                                <img src="./img/product03.png" alt="">
                            </div>

                            <div class="product-preview">
                                <img src="./img/product06.png" alt="">
                            </div>

                            <div class="product-preview">
                                <img src="./img/product08.png" alt="">
                            </div> --}}
                    </div>
                </div>
                <!-- /Product thumb imgs -->

                <!-- Product details -->
                <div class="col-md-5">
                    <div class="product-details">
                        <h2 class="product-name">{{ $product->nome }}</h2>
                        {{--                         <div>
                                <div class="product-rating">
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star"></i>
                                    <i class="fa fa-star-o"></i>
                                </div>
                                <a class="review-link" href="#">10 Review(s) | Add your review</a>
                            </div> --}}
                        <div>
                            <h3 class="product-price">KZ {{ number_format($product->preco, 2, ',', '.') }} <del
                                    class="product-old-price">KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del>
                            </h3>
                            <span class="product-available">{{ $product->estado_venda }}</span>
                        </div>
                        <p>{{ $product->descricao }}</p>

                        <div class="product-options">
                            <label>
                                Tamanho
                                <select class="input-select" disabled>
                                    <option value="0">N/D</option>
                                </select>
                            </label>
                            <label>
                                Cor
                                <select class="input-select" disabled>
                                    <option value="0">N/D</option>
                                </select>
                            </label>
                        </div>

                        <div class="add-to-cart">
                            <div class="qty-label">
                                Quantidade
                                <div class="input-number">
                                    <input type="number" disabled
                                        value="{{ $product->quantidade_disponivel }}">{{--
                                        <span class="qty-up" >+</span>
                                        <span class="qty-down">-</span> --}}
                                </div>
                            </div>
                        </div>

                        {{-- <ul class="product-btns">
                                <li><a href="#"><i class="fa fa-heart-o"></i> add to wishlist</a></li>
                                <li><a href="#"><i class="fa fa-exchange"></i> add to compare</a></li>
                            </ul> --}}

                        <ul class="product-links">
                            <li>Categoria:</li>
                            <li><a href="#">{{ $product->categoria }}</a></li>
                        </ul>

                        {{-- <ul class="product-links">
                                <li>Share:</li>
                                <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                                <li><a href="#"><i class="fa fa-envelope"></i></a></li>
                            </ul> --}}

                    </div>
                </div>
                <!-- /Product details -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    <!-- SECTION -->
    <div class="section" style="margin-top: -5rem">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">

                <div class="col-md-7">
                    <!-- Billing Details -->
                    <div class="billing-details">
                        <div class="section-title">
                            <h3 class="title">FAZER COMPRA</h3>
                        </div>

                        <form method="POST" action="{{ route('customer.store_order_negotiation') }}">
                            @csrf
                            <div class="form-group">
                                <input class="input" type="number" name="offer" placeholder="Sua oferta" step="0.01"
                                    required>
                                @error('offer')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="order-notes">
                                <textarea class="input" name="notes" placeholder="Notas"></textarea>
                                @error('notes')
                                    <span class="error">{{ $message }}</span>
                                @enderror
                            </div>
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <!-- Ajuste conforme a variável $product -->
                            <button type="submit" class="btn-negotiate">COMPRAR</button>
                        </form>
                        {{-- Compra directa --}}
                        <form method="POST" action="{{ route('customer.store_direct_purchase') }}"
                            style="margin-top: 10px;">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <textarea class="input" name="notes" placeholder="Notas (opcional)"></textarea>
                            <button type="submit" class="btn-negotiate"
                                style="background-color: #27ae60; margin-top: 8px; width: 100%;">
                                <i class="fa fa-shopping-cart"></i> COMPRAR AO PREÇO FIXO —
                                KZ {{ number_format($product->preco + 2500, 2, ',', '.') }}
                            </button>
                        </form>

                        <!-- Alerta dinâmico -->
                        <div id="alert" class="alert"
                            style="display: none; position: fixed; top: 20px; right: 20px; z-index: 1000; padding: 10px 20px; border-radius: 5px;">
                            <span id="alert-message"></span>
                            <button id="close-alert"
                                style="float: right; border: none; background: none; font-size: 1.2em; cursor: pointer;">&times;</button>
                        </div>

                        <script>
                            // Exibir alerta com base nas mensagens de sessão
                            document.addEventListener('DOMContentLoaded', function() {
                                const successMessage = '{{ session('success') }}';
                                const errorMessage = '{{ session('error') }}';

                                if (successMessage) {
                                    showAlert(successMessage, 'success');
                                } else if (errorMessage) {
                                    showAlert(errorMessage, 'error');
                                }

                                // Fechar alerta após 5 segundos ou ao clicar no botão
                                const closeAlert = document.getElementById('close-alert');
                                closeAlert.addEventListener('click', hideAlert);

                                setTimeout(hideAlert, 5000); // Ocultar após 5 segundos
                            });

                            function showAlert(message, type) {
                                const alert = document.getElementById('alert');
                                const alertMessage = document.getElementById('alert-message');
                                alertMessage.textContent = message;
                                alert.style.display = 'block';
                                alert.style.backgroundColor = type === 'success' ? '#d4edda' : '#f8d7da';
                                alert.style.color = type === 'success' ? '#155724' : '#721c24';
                                alert.style.border = type === 'success' ? '1px solid #c3e6cb' : '1px solid #f5c6cb';
                            }

                            function hideAlert() {
                                const alert = document.getElementById('alert');
                                alert.style.display = 'none';
                            }
                        </script>

                        <style>
                            .error {
                                color: red;
                                font-size: 0.9em;
                            }

                            .btn-negotiate {
                                background-color: #dc3545;
                                color: white;
                                padding: 10px 20px;
                                border: none;
                                border-radius: 5px;
                                cursor: pointer;
                            }

                            .btn-negotiate:hover {
                                background-color: #c82333;
                            }

                            .input {
                                width: 100%;
                                padding: 10px;
                                margin: 10px 0;
                                border: 1px solid #ccc;
                                border-radius: 5px;
                            }
                        </style>
                        {{--  @if (!auth()->user()) --}}


                        @guest


                            <div class="form-group">
                                <div class="input-checkbox">
                                    <input type="checkbox" id="create-account">
                                    <label for="create-account">
                                        <span></span>
                                        Criar uma conta?
                                    </label>
                                    <div class="caption">
                                        {{--  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                            incididunt.</p>
                                        <input class="input" type="password" name="password" placeholder="Enter Your Password"> --}}



                                        <!-- -->
                                        <div id="newsletter" class="section">
                                            <!-- container -->
                                            <div class="container">
                                                <!-- row -->
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="newsletter">
                                                            <p>Crie sua conta e <strong>negocie</strong> os preços dos produtos!
                                                            </p>
                                                            <form method="POST"
                                                                action="{{ route('customer.create_account') }}">
                                                                @csrf
                                                                <div class="row"
                                                                    style="display: flex; flex-flow: row wrap; justify-content: center;">
                                                                    <input class="input" type="text" id="nif"
                                                                        name="nif" placeholder="Seu nif/nº do B.I.*"
                                                                        value="{{ old('nif') }}">
                                                                    @error('nif')
                                                                        <span class="error">{{ $message }}</span>
                                                                    @enderror
                                                                    <input class="input" type="text" id="name"
                                                                        name="name"
                                                                        placeholder="Nome (Auto preenchimento)*" readonly
                                                                        style="background: rgba(221, 221, 221, 0.678); outline: none;"
                                                                        value="{{ old('name') }}">
                                                                    @error('name')
                                                                        <span class="error">{{ $message }}</span>
                                                                    @enderror
                                                                    <input class="input" type="date" id="birth_date"
                                                                        name="birth_date" readonly
                                                                        style="background: rgba(221, 221, 221, 0.678); outline: none;"
                                                                        value="{{ old('birth_date') }}">
                                                                    @error('birth_date')
                                                                        <span class="error">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="row">
                                                                    <input class="input" type="email" name="email"
                                                                        placeholder="Seu e-mail*"
                                                                        value="{{ old('email') }}">
                                                                    @error('email')
                                                                        <span class="error">{{ $message }}</span>
                                                                    @enderror
                                                                    <input class="input" type="text" name="phone_number"
                                                                        placeholder="Seu contacto (WhatsApp)*"
                                                                        value="{{ old('phone_number') }}">
                                                                    @error('phone_number')
                                                                        <span class="error">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <div class="row">
                                                                    <input class="input" type="password" name="password"
                                                                        placeholder="Crie uma senha*">
                                                                    @error('password')
                                                                        <span class="error">{{ $message }}</span>
                                                                    @enderror
                                                                    <input class="input" type="password"
                                                                        name="password_confirmation"
                                                                        placeholder="Confirme a senha*">
                                                                    @error('confirm_password')
                                                                        <span class="error">{{ $message }}</span>
                                                                    @enderror
                                                                </div>
                                                                <br>
                                                                <button class="newsletter-btn" type="submit"><i
                                                                        class="fa fa-envelope"></i>
                                                                    Inscrever-se</button>
                                                            </form>
                                                            <ul class="newsletter-follow">
                                                                <li>
                                                                    <a href="#"><i class="fa fa-facebook"></i></a>
                                                                </li>
                                                                <li>
                                                                    <a href="#"><i class="fa fa-whatsapp"></i></a>
                                                                </li>{{--
                                                                    <li>
                                                                        <a href="#"><i class="fa fa-instagram"></i></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="#"><i class="fa fa-pinterest"></i></a>
                                                                    </li> --}}
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                                <!-- /row -->
                                            </div>
                                            <!-- /container -->
                                        </div>
                                        <!-- /-->

                                    </div>
                                </div>
                            </div>
                        @endguest
                        {{--  @endif --}}
                    </div>
                    <!-- /Billing Details -->

                </div>

                <!-- Order Details -->
                <div class="col-md-5 order-details">
                    <div class="text-center section-title">
                        <h3 class="title">Seu pedido</h3>
                    </div>
                    <div class="order-summary">
                        <div class="order-col">
                            <div><strong>PRODUCTO</strong></div>
                            <div><strong>TOTAL</strong></div>
                        </div>
                        <div class="order-products">
                            <div class="order-col">
                                <div>1x {{ $product->nome }}</div>
                                <div>KZ {{ number_format($product->preco, 2, ',', '.') }}</div>
                            </div>{{--
                                <div class="order-col">
                                    <div>2x Product Name Goes Here</div>
                                    <div>$980.00</div>
                                </div> --}}
                        </div>
                        <div class="order-col">
                            <div>Entrega</div>
                            <div><strong>Luanda-2500kzs</strong></div>
                        </div>
                        <div class="order-col">
                            <div><strong>TOTAL</strong></div>
                            <div><strong class="order-total">KZ
                                    {{ number_format($product->preco + 2500, 2, ',', '.') }}</strong></div>
                        </div>
                    </div>
                    <div class="payment-method">
                        <div class="input-radio">
                            <input type="radio" name="payment" id="payment-1" checked @selected(true)>
                            <label for="payment-1">
                                <span></span>
                                Transferência Bancária Direta
                            </label>
                            <div class="caption">
                                <p>BAN: AO06 0040 0000 6140 8348 1014 7 (BAI)</p>
                            </div>
                        </div>
                    </div>{{--
                        <div class="input-checkbox">
                            <input type="checkbox" id="terms" selected>
                            <label for="terms">
                                <span></span>
                                Li e aceito os <a href="#">termos e condições</a>
                            </label>
                        </div>
                        <a href="#" class="primary-btn order-submit">Negociar</a> --}}
                </div>
                <!-- /Order Details -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->


    <!-- Section -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">

                <div class="col-md-12">
                    <div class="text-center section-title">
                        <h3 class="title">Produtos Relacionados</h3>
                    </div>
                </div>



            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /Section -->


@endsection
