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

                        @if ($product->quantidade_disponivel > 0 && $product->estado_venda === 'disponivel')
                            <!-- Formulário de Negociação -->
                            <form method="POST" action="{{ route('customer.store_order_negotiation') }}">
                                @csrf
                                <div class="form-group">
                                    <input class="input" type="number" name="offer" placeholder="Sua oferta"
                                        step="0.01" required>
                                </div>
                                <div class="order-notes">
                                    <textarea class="input" name="notes" placeholder="Notas"></textarea>
                                </div>
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="btn-negotiate">NEGOCIAR PREÇO</button>
                            </form>

                            <!-- Compra Direta -->
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
                        @else
                            <div class="text-center alert alert-danger">
                                <i class="mb-3 fa fa-ban fa-2x d-block"></i>
                                <strong>Produto Esgotado</strong><br>
                                Este produto não está disponível no momento.
                            </div>
                        @endif

                        <!-- Restante do código (guest account creation) permanece igual -->

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
