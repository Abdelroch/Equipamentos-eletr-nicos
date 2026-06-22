@extends('layouts.visitor.main')

@section('title', 'BAYQI.')

@section('content')

    <style>
        #section-welcome {
            position: relative;
            background-size: cover;
            background-attachment: fixed;
            background-position: 0%;
            width: 100vw;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        #section-welcome .col-md-12 h2 {
            font-size: 38px;
            font-weight: 900;
            letter-spacing: 1px;
            transform: scale(1.2);
            margin-bottom: 3rem;
        }

        #section-welcome .col-md-12 h4 {
            font-size: 30px;
            font-weight: 500;
            letter-spacing: 1px;
            transform: scale(1.1);
            margin-bottom: 3rem;
        }

        #section-welcome .col-md-12 h2,
        #section-welcome .col-md-12 h4 {
            color: #FBFBFB;
            font-family: 'Poppins', sans-serif;
        }

        #section-welcome::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            width: 100vw;
            height: 500px;
            background: rgba(26, 25, 25, 0.575);
            z-index: 1;
        }

        /* Fundo roxo em degradê */
        .guarantee-section {
            background: linear-gradient(200deg, #FFF, #FFF);
            padding: 100px 0;
            width: 100%;

        }

        /* Container dos cards */
        .guarantee {
            display: flex;
            justify-content: center;
            gap: 100px;
            padding: 0 80px;
            width: 100%;
        }

        /* Card */
        .guarantee .item {
            background: #2c2f33;
            display: flex;
            align-items: center;
            gap: 200px;
            height: 110px;
            width: 150%;
            max-width: 420px;
            border-radius: 15px;
            padding: 0 30px;
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        /* Hover */
        .guarantee .item:hover {
            border-color: #ffb742;
        }

        /* Ícone redondo */
        .guarantee .item .icon {
            width: 80px;
            height: 55px;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
        }

        .guarantee .item:hover .icon {
            background: #ffb742;
        }

        .guarantee .item .icon i {
            font-size: 28px;
            color: #000;
        }

        /* Texto */
        .guarantee .item .info {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* centraliza horizontalmente */
            justify-content: center;
            /* centraliza verticalmente se houver altura */
            text-align: center;
            /* centraliza o texto */


        }

        .guarantee .item .info h3 {
            color: #ffb742;
            font-size: 30px;
            margin: 0;
        }

        .guarantee .item .info p {
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            margin: 0;
        }
    </style>

    <div class="section">
        <!-- container -->
        <div class="container-fluid" id="section-welcome"
            style="background-image: url({{ asset('deal.jpg') }}); margin-top: -3rem; min-height: 500px;">

            <div class="row">
                <div class="col-md-12" style="z-index: 200">
                    <h2>Bem-vindo ao BAYQI</h2>
                    <h4>Encontre as Melhores Ofertas em Eletrônicos.</h4>
                </div>
            </div>

        </div>
    </div>

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <style>
                #img-style-min {
                    height: 245px;
                    object-fit: contain
                }
            </style>

            <section class="guarantee-section">
                <div class="guarantee">
                    <div class="item">
                        <div class="icon">
                            <i class='bx bx-check-shield'></i>
                        </div>
                        <div class="info">
                            <h3>+10.000</h3>
                            <p>Contas Abertas</p>
                        </div>
                    </div>

                    <div class="item">
                        <div class="icon">
                            <i class='bx bx-check-circle'></i>
                        </div>
                        <div class="info">
                            <h3>+100</h3>
                            <p>Cartões entregues</p>
                        </div>
                    </div>

                    <div class="item">
                        <div class="icon">
                            <i class='bx bx-laugh'></i>
                        </div>
                        <div class="info">
                            <h3>+70</h3>
                            <p>Clientes Satisfeitos</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="row">
                <!-- shop -->
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="{{ asset('visitor/img/shop01.png') }}" alt="" id="img-style-min">
                        </div>
                        <div class="shop-body">
                            <h3>Coleção de<br>Laptops</h3>
                            <a href="{{ route('store') }}" class="cta-btn">Visualizar <i
                                    class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /shop -->

                <!-- shop -->
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="{{ asset('visitor/img/carcaça1.jpeg') }}" alt="" id="img-style-min">
                        </div>
                        <div class="shop-body">
                            <h3>Coleção de<br>Carcaças</h3>
                            <a href="{{ route('store') }}" class="cta-btn">Visualizar <i
                                    class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /shop -->

                <!-- shop -->
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="{{ asset('visitor/img/troca-vidro-samsung-s9-plus-abcsmart.jpg') }}" alt=""
                                id="img-style-min">
                        </div>
                        <div class="shop-body">
                            <h3>Coleção de<br>Smartphones</h3>
                            <a href="{{ route('store') }}" class="cta-btn">Visualizar <i
                                    class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /shop -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">

                <!-- section title -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Em destaque</h3>
                        <div class="section-nav">
                            <ul class="section-tab-nav tab-nav">
                                <li class="active"><a data-toggle="tab" href="#tab1">Todas categorias</a></li>
                                {{-- <li><a data-toggle="tab" href="#tab1">Smartphones</a></li>
                                        <li><a data-toggle="tab" href="#tab1">Carcaças</a></li> --}}{{--
                                        <li><a data-toggle="tab" href="#tab1">Accessories</a></li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /section title -->

                <!-- Products tab & slick -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="products-tabs">
                            <!-- tab -->
                            <div id="tab1" class="tab-pane active">
                                <div class="products-slick" data-nav="#slick-nav-1">
                                    {{--
    Coloca este bloco onde actualmente tens o @foreach dos produtos.
    Substitui toda a secção que itera $products_very_good.
--}}

{{-- Barra de contexto: mostra o que está filtrado actualmente --}}
@if ($termoPesquisa || $categoriaActiva || $marcaActiva)
    <div class="container" style="margin-bottom: 16px;">
        <p class="text-muted" style="font-size: 0.9rem;">
            A mostrar resultados para:
            @if ($termoPesquisa)
                <strong>"{{ $termoPesquisa }}"</strong>
            @endif
            @if ($categoriaActiva)
                na categoria <strong>{{ $categoriaActiva->nome }}</strong>
            @endif
            @if ($marcaActiva && !$marcaInexistente)
                marca <strong>{{ strtoupper($marcaActiva) }}</strong>
            @endif
            &nbsp;·&nbsp;
            <a href="{{ route('store') }}">Limpar filtros</a>
        </p>
    </div>
@endif

{{-- Caso 1: Marca pedida via URL mas não existe em nenhum produto do catálogo --}}
@if ($marcaInexistente)
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="text-center col-md-12" style="padding: 60px 0;">
                    <i class="fa fa-search" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                    <h3>Marca não encontrada</h3>
                    <p class="text-muted">
                        Não temos produtos da marca <strong>{{ strtoupper($marcaActiva) }}</strong> disponíveis
                        no nosso catálogo de momento.
                    </p>
                    <p class="text-muted">
                        Pode explorar o catálogo completo ou pesquisar por outra marca.
                    </p>
                    <a href="{{ route('store') }}" class="btn btn-primary" style="margin-top: 12px;">
                        Ver todos os produtos
                    </a>
                </div>
            </div>
        </div>
    </div>

{{-- Caso 2: Filtros activos mas sem resultados (pesquisa/categoria sem match) --}}
@elseif (($termoPesquisa || $categoriaActiva) && $products_very_good->isEmpty())
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="text-center col-md-12" style="padding: 60px 0;">
                    <i class="fa fa-inbox" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                    <h3>Nenhum produto encontrado</h3>
                    @if ($termoPesquisa)
                        <p class="text-muted">
                            Não encontrámos produtos para <strong>"{{ $termoPesquisa }}"</strong>.
                            Experimenta termos diferentes ou verifica a ortografia.
                        </p>
                    @elseif ($categoriaActiva)
                        <p class="text-muted">
                            Não há produtos disponíveis na categoria
                            <strong>{{ $categoriaActiva->nome }}</strong> de momento.
                        </p>
                    @endif
                    @if ($totalSemFiltro > 0)
                        <p class="text-muted">
                            Temos <strong>{{ $totalSemFiltro }}</strong> produtos disponíveis noutras categorias.
                        </p>
                    @endif
                    <a href="{{ route('store') }}" class="btn btn-primary" style="margin-top: 12px;">
                        Ver todos os produtos
                    </a>
                </div>
            </div>
        </div>
    </div>

{{-- Caso 3: Catálogo vazio (sem filtros, mas sem produtos disponíveis) --}}
@elseif (!$termoPesquisa && !$categoriaActiva && !$marcaActiva && $products_very_good->isEmpty())
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="text-center col-md-12" style="padding: 60px 0;">
                    <i class="fa fa-shopping-cart" style="font-size: 48px; color: #ccc; margin-bottom: 20px;"></i>
                    <h3>Sem produtos disponíveis</h3>
                    <p class="text-muted">
                        O catálogo está temporariamente vazio. Volte em breve para ver as novidades.
                    </p>
                </div>
            </div>
        </div>
    </div>

{{-- Caso 4: Há resultados — renderiza normalmente --}}
@else
    <div class="section">
        <div class="container">
            <div class="row">
                @foreach ($products_very_good as $product)
                    <div class="col-md-4 col-xs-6">
                        {{-- O teu card de produto existente vai aqui, sem alteração --}}
                        @include('visitor.partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>

            {{-- Paginação --}}
            @if ($products_very_good->hasPages())
                <div class="row">
                    <div class="text-center col-md-12" style="margin-top: 24px;">
                        {{ $products_very_good->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
                                </div>
                                <div id="slick-nav-1" class="products-slick-nav"></div>
                            </div>
                            <!-- /tab -->
                        </div>
                    </div>
                </div>
                <!-- Products tab & slick -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    <!-- HOT DEAL SECTION -->
    <div id="hot-deal" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="hot-deal">
                        <ul class="hot-deal-countdown">
                            <li>
                                <div>
                                    <h3 id="days">02</h3>
                                    <span>Days</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3 id="hours">10</h3>
                                    <span>Hours</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3 id="minutes">34</h3>
                                    <span>Mins</span>
                                </div>
                            </li>
                            <li>
                                <div>
                                    <h3 id="seconds">60</h3>
                                    <span>Secs</span>
                                </div>
                            </li>
                        </ul>

                        <script>
                            // Definir a data final da contagem regressiva (ex.: 7 dias a partir de hoje, 26/06/2025 21:14 WAT)
                            const countDownDate = new Date("July 3, 2025 21:14:00").getTime();

                            // Atualizar a contagem a cada 1 segundo
                            const countdown = setInterval(function() {
                                // Obter a data e hora atuais
                                const now = new Date().getTime();

                                // Calcular a diferença entre a data final e a atual
                                const distance = countDownDate - now;

                                // Calcular dias, horas, minutos e segundos
                                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                // Exibir os resultados nos elementos HTML
                                document.getElementById("days").textContent = String(days).padStart(2, '0');
                                document.getElementById("hours").textContent = String(hours).padStart(2, '0');
                                document.getElementById("minutes").textContent = String(minutes).padStart(2, '0');
                                document.getElementById("seconds").textContent = String(seconds).padStart(2, '0');

                                // Se a contagem regressiva terminar, exibir mensagem
                                if (distance < 0) {
                                    clearInterval(countdown);
                                    document.getElementById("days").textContent = "00";
                                    document.getElementById("hours").textContent = "00";
                                    document.getElementById("minutes").textContent = "00";
                                    document.getElementById("seconds").textContent = "00";
                                    document.querySelector(".hot-deal-countdown").innerHTML = "Oferta expirada!";
                                }
                            }, 1000); // Atualiza a cada segundo
                        </script>

                        <style>
                            .hot-deal-countdown {
                                display: flex;
                                list-style: none;
                                padding: 0;
                                justify-content: center;
                                gap: 10px;
                            }

                            .hot-deal-countdown li {
                                text-align: center;
                                padding: 10px;
                                background-color: #f8f9fa;
                                border-radius: 5px;
                            }

                            .hot-deal-countdown h3 {
                                margin: 0;
                                font-size: 1.5em;
                                color: #dc3545;
                            }

                            .hot-deal-countdown span {
                                display: block;
                                font-size: 0.8em;
                                color: #6c757d;
                            }
                        </style>
                        <h2 class="text-uppercase">oferta quente desta semana</h2>
                        <p>Nova coleção de carcaças com até 10% de desconto</p>
                        <a class="primary-btn cta-btn" href="#">Negociar agora</a>
                    </div>
                </div>
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /HOT DEAL SECTION -->

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">

                <!-- section title -->
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Últimas vendas</h3>
                        <div class="section-nav">
                            <ul class="section-tab-nav tab-nav">
                                <li class="active"><a data-toggle="tab" href="#tab2">Todas categorias</a></li>
                                {{--  	<li><a data-toggle="tab" href="#tab2">Smartphones</a></li>
                                        <li><a data-toggle="tab" href="#tab2">Cameras</a></li>
                                        <li><a data-toggle="tab" href="#tab2">Accessories</a></li> --}}
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /section title -->

                <!-- Products tab & slick -->
                <div class="col-md-12">
                    <div class="row">
                        <div class="products-tabs">
                            <!-- tab -->
                            <div id="tab2" class="tab-pane fade in active">
                                <div class="products-slick" data-nav="#slick-nav-2">

                                    @foreach ($products_sold as $product)
                                        <div class="product">
                                            <div class="product-img">
                                                <img style="height: 240px; object-fit: contain;"
                                                    src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                                    alt="">
                                            </div>
                                            <div class="product-body">
                                                <p class="product-category">{{ $product->categoria }}</p>
                                                <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                                <h4 class="product-price">KZ
                                                    {{ number_format($product->preco, 2, ',', '.') }}<del
                                                        class="product-old-price">KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del>
                                                </h4>
                                                <div class="product-rating">
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                    <i class="fa fa-star"></i>
                                                </div>
                                                {{--  <div class="product-btns">
                                                            <button class="quick-view"><i class="fa fa-eye"></i><span class="tooltipp">Visualização
                                                                    rápida</span></button>
                                                        </div> --}}
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($products_sold->isEmpty())
                                        <div class="container">
                                            <center>
                                                <hr>
                                                <h3 class="text-warning">
                                                    <i>
                                                        <strong>
                                                            Nenhum registro encontrado.
                                                        </strong>
                                                    </i>
                                                </h3>
                                                <hr>
                                            </center>
                                        </div>
                                    @endif

                                </div>
                                <div id="slick-nav-2" class="products-slick-nav"></div>
                            </div>
                            <!-- /tab -->
                        </div>
                    </div>
                </div>
                <!-- /Products tab & slick -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

    <!-- SECTION -->
    <div class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-4 col-xs-6">
                    <div class="section-title">
                        <h4 class="title">COMPUTADORES</h4>
                        <div class="section-nav">
                            <div id="slick-nav-3" class="products-slick-nav"></div>
                        </div>
                    </div>

                    <div class="products-widget-slick" data-nav="#slick-nav-3">
                        <div>
                            @foreach ($products_very_good->take(3) as $product)
                                <a href="{{ route('visitor.negotiate', ['product_slug' => $product->slug]) }}">
                                    <!-- product widget -->
                                    <div class="product-widget">
                                        <div class="product-img">
                                            <img class="rounded" style="height: 80px; object-fit: contain;"
                                                src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                                alt="">
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->categoria }}</p>
                                            <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                            <h4 class="product-price">
                                                KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del></h4>
                                        </div>
                                    </div>
                                    <!-- /product widget -->
                                </a>
                            @endforeach
                        </div>

                        <div>
                            @foreach ($products_very_good->skip(3)->take(3) as $product)
                                <a href="{{ route('visitor.negotiate', ['product_slug' => $product->slug]) }}">
                                    <!-- product widget -->
                                    <div class="product-widget">
                                        <div class="product-img">
                                            <img class="rounded" style="height: 80px; object-fit: contain;"
                                                src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                                alt="">
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->categoria }}</p>
                                            <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                            <h4 class="product-price">
                                                KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del></h4>
                                        </div>
                                    </div>
                                </a>
                                <!-- /product widget -->
                            @endforeach
                        </div>

                        <div>
                            @foreach ($products_very_good->skip(6)->take(3) as $product)
                                <a href="{{ route('visitor.negotiate', ['product_slug' => $product->slug]) }}">
                                    <!-- product widget -->
                                    <div class="product-widget">
                                        <div class="product-img">
                                            <img class="rounded" style="height: 80px; object-fit: contain;"
                                                src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                                alt="">
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->categoria }}</p>
                                            <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                            <h4 class="product-price">
                                                KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del></h4>
                                        </div>
                                    </div>
                                </a>
                                <!-- /product widget -->
                            @endforeach
                        </div>

                        <div>
                            @foreach ($products_very_good->skip(9)->take(3) as $product)
                                <a href="{{ route('visitor.negotiate', ['product_slug' => $product->slug]) }}">
                                    <!-- product widget -->
                                    <div class="product-widget">
                                        <div class="product-img">
                                            <img class="rounded" style="height: 80px; object-fit: contain;"
                                                src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                                alt="">
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->categoria }}</p>
                                            <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                            <h4 class="product-price">
                                                KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del></h4>
                                        </div>
                                    </div>
                                </a>
                                <!-- /product widget -->
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-xs-6">
                    <div class="section-title">
                        <h4 class="title">carcaças</h4>
                        <div class="section-nav">
                            <div id="slick-nav-4" class="products-slick-nav"></div>
                        </div>
                    </div>

                    <div class="products-widget-slick" data-nav="#slick-nav-4">
                        <div>
                            <!-- product widget -->
                            @foreach ($products_carcass->take(3) as $product)
                                <a href="{{ route('visitor.negotiate', ['product_slug' => $product->slug]) }}">
                                    <!-- product widget -->
                                    <div class="product-widget">
                                        <div class="product-img">
                                            <img class="rounded" style="height: 80px; object-fit: contain;"
                                                src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                                alt="">
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->categoria }}</p>
                                            <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                            <h4 class="product-price">
                                                KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del></h4>
                                        </div>
                                    </div>
                                </a>
                                <!-- /product widget -->
                            @endforeach
                            <!-- /product widget -->
                        </div>

                        <div>
                            @foreach ($products_carcass->skip(3)->take(3) as $product)
                                <a href="{{ route('visitor.negotiate', ['product_slug' => $product->slug]) }}">
                                    <!-- product widget -->
                                    <div class="product-widget">
                                        <div class="product-img">
                                            <img class="rounded" style="height: 80px; object-fit: contain;"
                                                src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                                alt="">
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->categoria }}</p>
                                            <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                            <h4 class="product-price">
                                                KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del></h4>
                                        </div>
                                    </div>
                                </a>
                                <!-- /product widget -->
                            @endforeach
                        </div>

                        <div>
                            @foreach ($products_carcass->skip(6)->take(3) as $product)
                                <a href="{{ route('visitor.negotiate', ['product_slug' => $product->slug]) }}">
                                    <!-- product widget -->
                                    <div class="product-widget">
                                        <div class="product-img">
                                            <img class="rounded" style="height: 80px; object-fit: contain;"
                                                src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                                alt="">
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->categoria }}</p>
                                            <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                            <h4 class="product-price">
                                                KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del></h4>
                                        </div>
                                    </div>
                                </a>
                                <!-- /product widget -->
                            @endforeach
                        </div>


                        <div>
                            @foreach ($products_carcass->skip(9)->take(3) as $product)
                                <a href="{{ route('visitor.negotiate', ['product_slug' => $product->slug]) }}">
                                    <!-- product widget -->
                                    <div class="product-widget">
                                        <div class="product-img">
                                            <img class="rounded" style="height: 80px; object-fit: contain;"
                                                src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                                alt="">
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->categoria }}</p>
                                            <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                            <h4 class="product-price">
                                                KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del></h4>
                                        </div>
                                    </div>
                                </a>
                                <!-- /product widget -->
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="clearfix visible-sm visible-xs"></div>

                <div class="col-md-4 col-xs-6">
                    <div class="section-title">
                        <h4 class="title">MONITORES</h4>
                        <div class="section-nav">
                            <div id="slick-nav-5" class="products-slick-nav"></div>
                        </div>
                    </div>

                    <div class="products-widget-slick" data-nav="#slick-nav-5">
                        <div>
                            @foreach ($products_monitors->take(3) as $product)
                                <a href="{{ route('visitor.negotiate', ['product_slug' => $product->slug]) }}">
                                    <!-- product widget -->
                                    <div class="product-widget">

                                        <div class="product-img">
                                            <img class="rounded" style="height: 80px; object-fit: contain;"
                                                src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                                alt="">
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category">{{ $product->categoria }}</p>
                                            <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                            <h4 class="product-price">
                                                KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del></h4>
                                        </div>
                                    </div>
                                </a>
                                <!-- /product widget -->
                            @endforeach
                        </div>

                    </div>
                </div>

            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->

@endsection
