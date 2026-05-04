<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <title>@yield('title')</title>

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="{{ asset('visitor/css/bootstrap.min.css')}}" />

    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="{{ asset('visitor/css/slick.css')}}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('visitor/css/slick-theme.css')}}" />

    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="{{ asset('visitor/css/nouislider.min.css')}}" />

    <!-- Font Awesome Icon -->
    <link rel="stylesheet" href="{{ asset('visitor/css/font-awesome.min.css')}}">

    <!-- Custom stlylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('visitor/css/style.css')}}" />

    <!-- HTML5 shim and Respond.js')}} for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js')}} doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
		  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js')}}"></script>
		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js')}}"></script>
		<![endif]-->


    <script src="{{ asset('sweetalert.min.js') }}"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
    </style>

</head>

<body>
    <!-- HEADER -->
    <header style="background: black">
        <div class="container">
            <ul class="header-links pull-left">
                <li><a href="tel:+244-952-281-231"><i class="fa fa-phone"></i> +244-952-281-231</a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> 0040 0000 6140 8348 1014 7 </a></li>
                <li><a href="#"><i class="fa fa-map-marker"></i> Fubu / Condomínio Pelicano</a></li>
            </ul>
            <ul class="header-links pull-right">
                <li><a href="#">{{-- <i class="fa fa-dollar"></i> --}} AOA-KZ</a></li>
                @guest
                    <li><a href="{{ route('login') }}"><i class="fa fa-user-o"></i>Minha conta</a></li>
                @endguest
                @auth
                    <li><a href="{{ route('customer.settings.my_accout.profile') }}"><i class="fa fa-user-o"></i> {{ Auth::user()->name }}</a></li>
                @endauth
            </ul>
        </div>
        </div>
        <!-- /TOP HEADER -->

        <!-- MAIN HEADER -->
        <div id="header">
            <!-- container -->
            <div class="container">
                <!-- row -->
                <div class="row">
                    <!-- LOGO -->
                    <div class="col-md-3">
                        <div class="header-logo">
                            <br>
                            <a href="#" class="logo" >
                                <img src="{{ asset('deal..png')}}" alt="" width="250" height="40" style="margin-top: 1rem">
                            </a>
                        </div>
                    </div>
                    <!-- /LOGO -->

                    <!-- SEARCH BAR -->
                    <div class="col-md-6">
                        <div class="header-search">
                            <form>
                                <select class="input-select" style="width: 13rem">
                                    <option value="all" selected> T.categorias</option>
                                    <option value="smartphones">Smartphones</option>
                                    <option value="laptops">Laptops</option>
                                    <option value="desktops">Computadores Desktop</option>
                                    <option value="tablets"> Tablets</option>
                                    <option value="smartwatches">Smartwatches</option>
                                    <option value="headphones">Fones de Ouvido</option>
                                    <option value="speakers">Caixas de Som</option>
                                    <option value="gaming_consoles">Consoles de Jogos</option>
                                    <option value="monitors">Monitores</option>
                                    <option value="keyboards">Teclados</option>
                                    <option value="mice">Mouses</option>
                                    <option value="printers">Impressoras</option>
                                    <option value="routers"> Roteadores</option>
                                    <option value="cameras"> Câmeras</option>
                                    <option value="drones"> Drones</option>
                                    <option value="accessories">Acessórios</option>
                                </select>
                                <input class="input" placeholder="Nome, descrição, preço ou id do producto">
                                <button class="search-btn">Procurar</button>
                            </form>
                        </div>
                    </div>
                    <!-- /SEARCH BAR -->

                    <!-- ACCOUNT -->
                    <div class="col-md-3 clearfix">
                        <div class="header-ctn">

                            @auth
                                <!-- Wishlist -->
                                <div>
                                    <a href="{{ route('customer.order_requests') }}">
                                        <i class="fa fa-heart-o"></i>
                                        <span>Solicitações</span>
                                        <div class="qty">2</div>
                                    </a>
                                </div>
                                <!-- /Wishlist -->
                                <div>
                                    <a href="{{ route('customer.settings.my_accout.profile') }}">
                                        <i class="fa fa-user-o"></i>
                                        <span>Minha conta</span>
                                    </a>
                                </div>
                            @endauth

                            {{--
                            <!-- Cart -->
                            <div class="dropdown">
                                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-shopping-cart"></i>
                                    <span>Your Cart</span>
                                    <div class="qty">3</div>
                                </a>
                                <div class="cart-dropdown">
                                    <div class="cart-list">
                                        <div class="product-widget">
                                            <div class="product-img">
                                                <img src="{{ asset('visitor/img/product01.png')}}') }}" alt="">
                                            </div>
                                            <div class="product-body">
                                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                                <h4 class="product-price"><span class="qty">1x</span>$980.00</h4>
                                            </div>
                                            <button class="delete"><i class="fa fa-close"></i></button>
                                        </div>

                                        <div class="product-widget">
                                            <div class="product-img">
                                                <img src="{{ asset('visitor/img/product02.png')}}')}}" alt="">
                                            </div>
                                            <div class="product-body">
                                                <h3 class="product-name"><a href="#">product name goes here</a></h3>
                                                <h4 class="product-price"><span class="qty">3x</span>$980.00</h4>
                                            </div>
                                            <button class="delete"><i class="fa fa-close"></i></button>
                                        </div>
                                    </div>
                                    <div class="cart-summary">
                                        <small>3 Item(s) selected</small>
                                        <h5>SUBTOTAL: $2940.00</h5>
                                    </div>
                                    <div class="cart-btns">
                                        <a href="#">View Cart</a>
                                        <a href="#">Checkout <i class="fa fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                            <!-- /Cart -->
                            --}}

                            <!-- Menu Toogle -->
                            <div class="menu-toggle">
                                <a href="#">
                                    <i class="fa fa-bars"></i>
                                    <span>Menu</span>
                                </a>
                            </div>
                            <!-- /Menu Toogle -->
                        </div>
                    </div>
                    <!-- /ACCOUNT -->
                </div>
                <!-- row -->
            </div>
            <!-- container -->
        </div>
        <!-- /MAIN HEADER -->
    </header>
    <!-- /HEADER -->

    <!-- NAVIGATION -->
    <nav id="navigation">
        <!-- container -->
        <div class="container">
            <!-- responsive-nav -->
            <div id="responsive-nav">
                <!-- NAV -->
                <ul class="main-nav nav navbar-nav">
                    <li class="active"><a href="{{ route('index') }}">Home</a></li>
                    <li><a href="{{ route('store') }}">Loja</a></li>{{--
                    <li><a href="#">Categorias</a></li> --}}
                    <li><a href="#">Hot Deal</a></li>
                    <li><a href="#">Sobre Nós</a>{{--
                    <li><a href="#">Smartphones</a></li>
                    <li><a href="#">Carcaças</a></li>
                    <li><a href="#">Accessories</a></li> --}}
                    @auth
                        <li><a href="{{ route('logout') }}" onclick="document.getElementById('form-logout').submit();event.preventDefault()">Terminar sessão</a></li>
                        <form action="{{ route('logout') }}" id="form-logout" style="display: none" method="POST">
                            @csrf
                        </form>
                    @endauth
                </ul>
                <!-- /NAV -->
            </div>
            <!-- /responsive-nav -->
        </div>
        <!-- /container -->
    </nav>
    <!-- /NAVIGATION -->
    @yield('content')

    <br><br><br>


    @if (Route::is('index'))

        @guest


            <!-- -->
            <div id="newsletter" class="section">
                <!-- container -->
                <div class="container">
                    <!-- row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="newsletter">
                                <p>Crie sua conta e <strong>negocie</strong> os preços dos produtos!</p>
                                <form method="POST" action="{{ route('customer.create_account') }}">
                                    @csrf
                                    <div class="row" style="display: flex; flex-flow: row wrap; justify-content: center;">
                                        <input class="input" type="text" id="nif" name="nif" placeholder="Seu nif/nº do B.I.*"
                                            value="{{ old('nif') }}">
                                        @error('nif') <span class="error">{{ $message }}</span> @enderror
                                        <input class="input" type="text" id="name" name="name"
                                            placeholder="Nome (Auto preenchimento)*" readonly
                                            style="background: rgba(221, 221, 221, 0.678); outline: none;"
                                            value="{{ old('name') }}">
                                        @error('name') <span class="error">{{ $message }}</span> @enderror
                                        <input class="input" type="date" id="birth_date" name="birth_date" readonly
                                            style="background: rgba(221, 221, 221, 0.678); outline: none;"
                                            value="{{ old('birth_date') }}">
                                        @error('birth_date') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="row">
                                        <input class="input" type="email" name="email" placeholder="Seu e-mail*"
                                            value="{{ old('email') }}">
                                        @error('email') <span class="error">{{ $message }}</span> @enderror
                                        <input class="input" type="text" name="phone_number"
                                            placeholder="Seu contacto (WhatsApp)*" value="{{ old('phone_number') }}">
                                        @error('phone_number') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="row">
                                        <input class="input" type="password" name="password" placeholder="Crie uma senha*">
                                        @error('password') <span class="error">{{ $message }}</span> @enderror
                                        <input class="input" type="password" name="password_confirmation"
                                            placeholder="Confirme a senha*">
                                        @error('confirm_password') <span class="error">{{ $message }}</span> @enderror
                                    </div>
                                    <br>
                                    <button class="newsletter-btn" type="submit"><i class="fa fa-envelope"></i>
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

        @endguest

    @endif

    <!-- FOOTER -->
    <footer id="footer">
        <!-- top footer -->
        <div class="section">
            <!-- container -->
            {{--<div class="container">
                <!-- row -->
                <div class="row">
                    <div class="col-md-3 col-xs-6">
                        <div class="footer">
                            <h3 class="footer-title">About Us</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor
                                incididunt ut.</p>
                            <ul class="footer-links">
                                <li><a href="#"><i class="fa fa-map-marker"></i>1734 Stonecoal Road</a></li>
                                <li><a href="#"><i class="fa fa-phone"></i>+021-95-51-84</a></li>
                                <li><a href="#"><i class="fa fa-envelope-o"></i>email@email.com</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-6">
                        <div class="footer">
                            <h3 class="footer-title">Categories</h3>
                            <ul class="footer-links">
                                <li><a href="#">Hot deals</a></li>
                                <li><a href="#">Laptops</a></li>
                                <li><a href="#">Smartphones</a></li>
                                <li><a href="#">Cameras</a></li>
                                <li><a href="#">Accessories</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="clearfix visible-xs"></div>

                    <div class="col-md-3 col-xs-6">
                        <div class="footer">
                            <h3 class="footer-title">Information</h3>
                            <ul class="footer-links">
                                <li><a href="#">About Us</a></li>
                                <li><a href="#">Contact Us</a></li>
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Orders and Returns</a></li>
                                <li><a href="#">Terms & Conditions</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-3 col-xs-6">
                        <div class="footer">
                            <h3 class="footer-title">Service</h3>
                            <ul class="footer-links">
                                <li><a href="#">My Account</a></li>
                                <li><a href="#">View Cart</a></li>
                                <li><a href="#">Wishlist</a></li>
                                <li><a href="#">Track My Order</a></li>
                                <li><a href="#">Help</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /row -->
            </div>--}}
            <!-- /container -->
        </div>
        <!-- /top footer -->

        <!-- bottom footer -->
        <div id="bottom-footer" class="section">
            <div class="container">
                <!-- row -->
                <div class="row">
                    <div class="col-md-12 text-center">
                       {{-- <ul class="footer-payments">
                            <li><a href="#"><i class="fa fa-cc-visa"></i></a></li>
                            <li><a href="#"><i class="fa fa-credit-card"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-paypal"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-mastercard"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-discover"></i></a></li>
                            <li><a href="#"><i class="fa fa-cc-amex"></i></a></li>
                        </ul>--}}
                        <span class="copyright">
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            Copyright &copy;
                            <script>document.write(new Date().getFullYear());</script> All rights reserved | developed with <i class="fa fa-heart-o" aria-hidden="true"></i> by> MeuDeal[4m] *.* <a
                                href="https://colorlib.com" target="_blank"></a>
                        </span>
                    </div>
                </div>
                <!-- /row -->
            </div>
            <!-- /container -->
        </div>
        <!-- /bottom footer -->
    </footer>
    <!-- /FOOTER -->

    <!-- jQuery Plugins -->
    @if (!Route::is('customer.order_requests'))
        <script src="{{ asset('visitor/js/jquery.min.js')}}"></script>
    @endif
    <script src="{{ asset('visitor/js/bootstrap.min.js')}}"></script>
    <script src="{{ asset('visitor/js/slick.min.js')}}"></script>
    <script src="{{ asset('visitor/js/nouislider.min.js')}}"></script>
    <script src="{{ asset('visitor/js/jquery.zoom.min.js')}}"></script>
    <script src="{{ asset('visitor/js/main.js')}}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bi = document.getElementById('nif');

            bi.addEventListener('input', function () {
                $.ajax({
                    type: 'GET',
                    url: 'https://consulta.edgarsingui.ao/consultar/' + bi.value,
                    success: function (data) {
                        console.log(data);
                        $('#name').val(data.name);
                        $('#birth_date').val(data.data_de_nascimento);
                    },
                    error: function (error) {
                        console.log(error)
                    }
                });
            });
        });
    </script>



</body>

</html>
