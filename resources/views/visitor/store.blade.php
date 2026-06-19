@extends('layouts.visitor.main')

@section('title', 'Loja')

@section('content')

    <!-- BREADCRUMB -->
    <div id="breadcrumb" class="section">
        <!-- container -->
        <div class="container">
            <!-- row -->
            <div class="row">
                <div class="col-md-12">
                    <h3 class="breadcrumb-header">LOJA</h3>
                    <ul class="breadcrumb-tree">
                        <li><a href="#">Home</a></li>
                        <li class="active">Produtos</li>
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
                <!-- ASIDE -->
                <div id="aside" class="col-md-3">
                    <!-- aside Widget -->
                    <div class="aside">
                        <h3 class="aside-title">CategoriAs</h3>
                        <div class="checkbox-filter">

                            <div class="input-checkbox">
                                <input type="checkbox" id="category-1">
                                <label for="category-1">
                                    <span></span>
                                    Laptops
                                    <small>(N/D)</small>
                                </label>
                            </div>

                            <div class="input-checkbox">
                                <input type="checkbox" id="category-2">
                                <label for="category-2">
                                    <span></span>
                                    Smartphones
                                    <small>(N/D)</small>
                                </label>
                            </div>

                            <div class="input-checkbox">
                                <input type="checkbox" id="category-3">
                                <label for="category-3">
                                    <span></span>
                                    Carcaças
                                    <small>(N/D)</small>
                                </label>
                            </div>

                            <div class="input-checkbox">
                                <input type="checkbox" id="category-4">
                                <label for="category-4">
                                    <span></span>
                                    Accessórios
                                    <small>(N/D)</small>
                                </label>
                            </div>

                            <div class="input-checkbox">
                                <input type="checkbox" id="category-5">
                                <label for="category-5">
                                    <span></span>
                                    Monitores
                                    <small>(N/D)</small>
                                </label>
                            </div>

                        </div>
                    </div>
                    <!-- /aside Widget -->

                    <!-- aside Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Preço</h3>
                        <div class="price-filter">
                            <div id="price-slider"></div>
                            <div class="input-number price-min">
                                <input id="price-min" type="number" disabled>
                                <span class="qty-up">+</span>
                                <span class="qty-down">-</span>
                            </div>
                            <span>-</span>
                            <div class="input-number price-max">
                                <input id="price-max" type="number" disabled>
                                <span class="qty-up">+</span>
                                <span class="qty-down">-</span>
                            </div>
                        </div>
                    </div>
                    <!-- /aside Widget -->

                    <!-- aside Widget -->
                    <div class="aside">
                        <h3 class="aside-title">Marcas</h3>
                        <div class="checkbox-filter">
                            <div class="input-checkbox">
                                <input type="checkbox" id="brand-1">
                                <label for="brand-1">
                                    <span></span>
                                    SAMSUNG
                                    <small>(N/D)</small>
                                </label>
                            </div>
                            <div class="input-checkbox">
                                <input type="checkbox" id="brand-2">
                                <label for="brand-2">
                                    <span></span>
                                    HP
                                    <small>(N/D)</small>
                                </label>
                            </div>
                            <div class="input-checkbox">
                                <input type="checkbox" id="brand-3">
                                <label for="brand-3">
                                    <span></span>
                                    SONY
                                    <small>(N/D)</small>
                                </label>
                            </div>
                            <div class="input-checkbox">
                                <input type="checkbox" id="brand-4">
                                <label for="brand-4">
                                    <span></span>
                                    DELL
                                    <small>(N/D)</small>
                                </label>
                            </div>
                            <div class="input-checkbox">
                                <input type="checkbox" id="brand-5">
                                <label for="brand-5">
                                    <span></span>
                                    MACBOOK
                                    <small>(N/D)</small>
                                </label>
                            </div>
                        </div>
                    </div>
                    <!-- /aside Widget -->

                </div>
                <!-- /ASIDE -->

                <!-- STORE -->
                <div id="store" class="col-md-9">
                    <!-- store top filter -->
                    <div class="clearfix store-filter">
                        <div class="store-sort">
                            <label>
                                Ordenar por:
                                <select class="input-select" disabled>
                                    <option value="0">Todos</option>
                                </select>
                            </label>

                            <label>
                                Exibir:
                                <select class="input-select" disabled>
                                    <option value="0">12</option>
                                    <option value="1">50</option>
                                </select>
                            </label>
                        </div>
                        <ul class="store-grid">
                            <li class="active"><i class="fa fa-th"></i></li>
                            <li><a href="#"><i class="fa fa-th-list"></i></a></li>
                        </ul>
                    </div>
                    <!-- /store top filter -->

                    <!-- store products -->
                    <div class="row">
                        @foreach ($products_very_good as $product)
                            <!-- product -->
                            <div class="col-md-5 col-xs-7">
                                <div class="product">
                                    <div class="product-img">
                                        <img style="height: 240px; object-fit: contain;"
                                            src="{{ $product->cover_image === null ? asset('visitor/img/no_image.png') : asset($product->cover_image) }}"
                                            alt="cover image">
                                    </div>
                                    <div class="product-body">
                                        <p class="product-category">{{ $product->categoria }}</p>
                                        <h3 class="product-name"><a href="#">{{ $product->nome }}</a></h3>
                                        <h4 class="product-price">KZ {{ number_format($product->preco, 2, ',', '.') }}<del
                                                class="product-old-price">KZ{{ number_format($product->preco + 13000, 2, ',', '.') }}</del>
                                        </h4>
                                        <div class="product-rating">
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                            <i class="fa fa-star"></i>
                                        </div>
                                        <div class="product-btns">
                                            {{-- <button class="add-to-wishlist"><i class="fa fa-heart-o"></i><span class="tooltipp">add to
                                                    wishlist</span></button>
                                            <button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">add to
                                                    compare</span></button> --}}
                                            <button class="quick-view"><i class="fa fa-eye"></i><span
                                                    class="tooltipp">Visualização
                                                    rápida</span></button>
                                        </div>
                                    </div>
                                    <div class="add-to-cart" style="display: flex; gap: 0px;">
    @if($product->quantidade_disponivel > 0 && $product->estado_venda === 'disponivel')
        {{-- Negociar --}}
        <a href="{{ route('visitor.negotiate', ['product_slug' => $product->slug]) }}" style="flex: 1;">
            <button class="add-to-cart-btn" style="width:90%; background:#d63031;">
                <i class="fa fa-gavel"></i> Negociar
            </button>
        </a>

        {{-- Comprar --}}
        <form method="POST" action="{{ route('customer.store_direct_purchase') }}" style="flex: 1;">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" class="add-to-cart-btn" style="width:90%; background:#27ae60;">
                <i class="fa fa-shopping-cart"></i> Comprar
            </button>
        </form>
    @else
        <button class="add-to-cart-btn" style="width:100%; background:#6c757d; cursor: not-allowed;" disabled>
            <i class="fa fa-ban"></i> Esgotado
        </button>
    @endif
</div>
                                </div>



                            </div>
                            <!-- /product -->

                            <div class="clearfix visible-sm visible-xs"></div>
                        @endforeach

                    </div>
                    <!-- /store products -->

                    <style>
                        .pagination {
                            text-align: center;
                            margin-top: 20px;
                        }

                        .pagination a,
                        .pagination span {
                            padding: 5px 10px;
                            margin: 0 5px;
                            border: 1px solid #ddd;
                            text-decoration: none;
                            color: #333;
                        }

                        .pagination a:hover {
                            background-color: #f0f0f0;
                        }
                    </style>

                    <!-- store bottom filter -->
                    <div class="clearfix store-filter">
                        <!-- Navegação de paginação -->
                        <div class="pagination">
                            {{ $products_very_good->links() }}
                        </div>
                        {{-- <span class="store-qty">Showing 20-100 products</span>
                            <ul class="store-pagination">
                                <li class="active">1</li>
                                <li><a href="#">2</a></li>
                                <li><a href="#">3</a></li>
                                <li><a href="#">4</a></li>
                                <li><a href="#"><i class="fa fa-angle-right"></i></a></li>
                            </ul> --}}
                    </div>
                    <!-- /store bottom filter -->
                </div>
                <!-- /STORE -->
            </div>
            <!-- /row -->
        </div>
        <!-- /container -->
    </div>
    <!-- /SECTION -->


@endsection
