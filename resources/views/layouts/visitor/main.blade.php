<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link type="text/css" rel="stylesheet" href="{{ asset('visitor/css/bootstrap.min.css') }}" />

    <!-- Slick -->
    <link type="text/css" rel="stylesheet" href="{{ asset('visitor/css/slick.css') }}" />
    <link type="text/css" rel="stylesheet" href="{{ asset('visitor/css/slick-theme.css') }}" />

    <!-- nouislider -->
    <link type="text/css" rel="stylesheet" href="{{ asset('visitor/css/nouislider.min.css') }}" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('visitor/css/font-awesome.min.css') }}">

    <!-- Custom stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('visitor/css/style.css') }}" />

    <script src="{{ asset('sweetalert.min.js') }}"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');

        /* Badge de admins online */
        .admin-online-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: rgba(255, 183, 66, 0.15);
            border: 1px solid #ffb742;
            border-radius: 12px;
            padding: 2px 10px;
            font-size: 0.75rem;
            color: #ffb742;
            font-weight: 600;
            margin-right: 6px;
            vertical-align: middle;
        }
        .admin-online-badge .dot {
            width: 7px;
            height: 7px;
            background: #2ecc71;
            border-radius: 50%;
            display: inline-block;
            animation: pulse-green 1.5s infinite;
        }
        @keyframes pulse-green {
            0%, 100% { opacity: 1; }
            50%       { opacity: 0.3; }
        }
        /* Link do dashboard para admins */
        .admin-dash-link {
            color: #ffb742 !important;
            font-weight: 600;
        }
        .admin-dash-link i {
            margin-right: 3px;
        }
    </style>
</head>

<body>
    <!-- HEADER -->
    <header style="background: black">

        @php
            /*
             * Calcula admins/gestores com sessão activa nos últimos 10 minutos.
             * Usa a tabela `sessions` que o Laravel mantém automaticamente.
             * Só executa se o utilizador estiver autenticado para evitar
             * consultas desnecessárias em visitas anónimas.
             */
            $adminsOnline = 0;
            $isAdmin      = false;

            if (auth()->check()) {
                $currentUser = auth()->user();
                $adminLevels = ['admin', 'manager', 'gestor'];
                $isAdmin     = in_array($currentUser->access_level, $adminLevels);

                // Conta sessões activas de admins/gestores nos últimos 10 min
                $adminsOnline = \Illuminate\Support\Facades\DB::table('sessions')
                    ->join('users', 'users.id', '=', 'sessions.user_id')
                    ->whereIn('users.access_level', $adminLevels)
                    ->where('sessions.last_activity', '>=', now()->subMinutes(10)->timestamp)
                    ->count();
            }
        @endphp

        <!-- TOP HEADER -->
        <div class="container">
            <ul class="header-links pull-left">
                <li><a href="tel:+244-952-281-231"><i class="fa fa-phone"></i> +244-952-281-231</a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> 0040 0000 6140 8348 1014 7</a></li>
                <li><a href="#"><i class="fa fa-map-marker"></i> Vila Alice / RUA ANTÓNIO DE CASTRO FEIJÓ</a></li>
            </ul>
            <ul class="header-links pull-right">
                <li><a href="#">AOA-KZ</a></li>

                @guest
                    {{-- Visitante anónimo --}}
                    <li><a href="{{ route('login') }}"><i class="fa fa-user-o"></i> Minha conta</a></li>
                @endguest

                @auth
                    @if ($isAdmin)
                        {{-- ===== ADMIN / GESTOR ===== --}}

                        {{-- Badge de admins online (só visível para admins) --}}
                        <li>
                            <span class="admin-online-badge">
                                <span class="dot"></span>
                                {{ $adminsOnline }} admin{{ $adminsOnline !== 1 ? 's' : '' }} online
                            </span>
                        </li>

                        {{-- Link para o dashboard em vez do perfil de cliente --}}
                        <li>
                            <a href="{{ route('admin.dashboard') }}" class="admin-dash-link">
                                <i class="fa fa-tachometer"></i> Dashboard
                            </a>
                        </li>

                    @else
                        {{-- ===== CLIENTE NORMAL ===== --}}
                        <li>
                            <a href="{{ route('customer.settings.my_accout.profile') }}">
                                <i class="fa fa-user-o"></i> {{ Auth::user()->name }}
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
        <!-- /TOP HEADER -->

        <!-- MAIN HEADER -->
        <div id="header">
            <div class="container">
                <div class="row">
                    <!-- LOGO -->
                    <div class="col-md-3">
                        <div class="header-logo">
                            <br>
                            <a href="{{ route('index') }}" class="logo">
                                <img src="{{ asset('bayqi.png') }}" alt="BAYQI" width="280" height="50"
                                    style="margin-top: 1rem">
                            </a>
                        </div>
                    </div>
                    <!-- /LOGO -->

                    <!-- SEARCH BAR -->
                    <div class="col-md-6">
                        <div class="header-search">
                            <form action="{{ route('store') }}" method="GET">
                                <select class="input-select" style="width: 13rem" name="categoria">
                                    <option value="all" selected>Todas as categorias</option>
                                    @foreach ($categorias ?? [] as $cat)
                                        <option value="{{ $cat->slug }}"
                                            {{ request('categoria') === $cat->slug ? 'selected' : '' }}>
                                            {{ $cat->nome }}
                                        </option>
                                    @endforeach
                                </select>
                                <input class="input" type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Nome, descrição, preço ou id do producto">
                                <button class="search-btn" type="submit">Procurar</button>
                            </form>
                        </div>
                    </div>
                    <!-- /SEARCH BAR -->

                    <!-- ACCOUNT (ícones do lado direito do header principal) -->
                    <div class="clearfix col-md-3">
                        <div class="header-ctn">
                            @auth
                                @if ($isAdmin)
                                    {{-- Admin vê atalho directo para encomendas pendentes --}}
                                    <div>
                                        <a href="{{ route('admin.orders.index') }}" class="admin-dash-link">
                                            <i class="fa fa-shopping-bag"></i>
                                            <span>Encomendas</span>
                                            @php
                                                $pendingOrders = \App\Models\OrderNegotiation::where('status', 'pending')->count();
                                            @endphp
                                            @if ($pendingOrders > 0)
                                                <div class="qty">{{ $pendingOrders }}</div>
                                            @endif
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.dashboard') }}" class="admin-dash-link">
                                            <i class="fa fa-tachometer"></i>
                                            <span>Dashboard</span>
                                        </a>
                                    </div>
                                @else
                                    {{-- Cliente normal vê solicitações e conta --}}
                                    <div>
                                        <a href="{{ route('customer.order_requests') }}">
                                            <i class="fa fa-heart-o"></i>
                                            <span>Solicitações</span>
                                            @php $unread = Auth::user()->unreadNotifications()->count(); @endphp
                                            @if ($unread > 0)
                                                <div class="qty">{{ $unread }}</div>
                                            @endif
                                        </a>
                                    </div>
                                    <div>
                                        <a href="{{ route('customer.settings.my_accout.profile') }}">
                                            <i class="fa fa-user-o"></i>
                                            <span>Minha conta</span>
                                        </a>
                                    </div>
                                @endif
                            @endauth

                            <!-- Menu Toggle -->
                            <div class="menu-toggle">
                                <a href="#">
                                    <i class="fa fa-bars"></i>
                                    <span>Menu</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <!-- /ACCOUNT -->
                </div>
            </div>
        </div>
        <!-- /MAIN HEADER -->
    </header>
    <!-- /HEADER -->

    <!-- NAVIGATION -->
    <nav id="navigation">
        <div class="container">
            <div id="responsive-nav">
                <ul class="main-nav nav navbar-nav">
                    <li class="active"><a href="{{ route('index') }}">Home</a></li>
                    <li><a href="{{ route('store') }}">Loja</a></li>
                    <li><a href="#">Hot Deal</a></li>
                    <li><a href="#">Sobre Nós</a></li>
                    @auth
                        <li>
                            <a href="{{ route('logout') }}"
                               onclick="document.getElementById('form-logout').submit(); event.preventDefault()">
                                Terminar sessão
                            </a>
                        </li>
                        <form action="{{ route('logout') }}" id="form-logout" style="display: none" method="POST">
                            @csrf
                        </form>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    <!-- /NAVIGATION -->

    <!-- NAVEGUE POR MARCA -->
    <div class="section">
        <div class="container">
            <h3 class="title">Navegue por Marca</h3>
            <div class="row" id="marcas-carousel">
                <div class="text-center col-md-2 col-xs-4">
                    <a href="{{ route('store') }}?marca=jbl">
                        <img src="{{ asset('visitor/img/marcas/jbl.png') }}" alt="JBL" class="img-responsive">
                    </a>
                </div>
                <div class="text-center col-md-2 col-xs-4">
                    <a href="{{ route('store') }}?marca=samsung">
                        <img src="{{ asset('visitor/img/marcas/samsung.png') }}" alt="Samsung" class="img-responsive">
                    </a>
                </div>
                <div class="text-center col-md-2 col-xs-4">
                    <a href="{{ route('store') }}?marca=apple">
                        <img src="{{ asset('visitor/img/marcas/apple.png') }}" alt="Apple" class="img-responsive">
                    </a>
                </div>
                <div class="text-center col-md-2 col-xs-4">
                    <a href="{{ route('store') }}?marca=hp">
                        <img src="{{ asset('visitor/img/marcas/hp.png') }}" alt="HP" class="img-responsive">
                    </a>
                </div>
                <div class="text-center col-md-2 col-xs-4">
                    <a href="{{ route('store') }}?marca=lenovo">
                        <img src="{{ asset('visitor/img/marcas/lenovo-2.png') }}" alt="Lenovo" class="img-responsive">
                    </a>
                </div>
                <div class="text-center col-md-2 col-xs-4">
                    <a href="{{ route('store') }}?marca=lg">
                        <img src="{{ asset('visitor/img/marcas/lg.png') }}" alt="LG" class="img-responsive">
                    </a>
                </div>
            </div>
        </div>
    </div>

    @yield('content')

    @if (Route::is('index'))
        @guest
            <!-- NEWSLETTER / CRIAR CONTA -->
            <div id="newsletter" class="section">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="newsletter">
                                <p>Crie sua conta e <strong>negocie</strong> os preços dos produtos!</p>

                                <form method="POST" action="{{ route('customer.create_account') }}">
                                    @csrf

                                    <div class="row"
                                        style="display: flex; flex-flow: row wrap; justify-content: center; gap: 12px;">
                                        <!-- BI / NIF -->
                                        <input class="input" type="text" id="nif" name="nif"
                                            placeholder="NIF / Nº do B.I. (14 caracteres)*" maxlength="14"
                                            value="{{ old('nif') }}" style="width: 300px;">

                                        <!-- Nome -->
                                        <input class="input" type="text" id="name" name="name"
                                            placeholder="Nome completo*" value="{{ old('name') }}"
                                            style="width: 300px;">

                                        <!-- Data de nascimento -->
                                        <input class="input" type="date" id="birth_date" name="birth_date"
                                            placeholder="Data de nascimento*"
                                            value="{{ old('birth_date') }}" style="width: 300px;">
                                    </div>

                                    <div class="row"
                                        style="display: flex; flex-flow: row wrap; justify-content: center; gap: 12px; margin-top: 12px;">
                                        <!-- Email -->
                                        <input class="input" type="email" name="email" placeholder="Seu e-mail*"
                                            value="{{ old('email') }}" style="width: 300px;">

                                        <!-- Telefone -->
                                        <input class="input" type="text" name="phone_number"
                                            placeholder="Telefone/WhatsApp — ex: 923456789*"
                                            value="{{ old('phone_number') }}" style="width: 300px;">
                                    </div>

                                    <div class="row"
                                        style="display: flex; flex-flow: row wrap; justify-content: center; gap: 12px; margin-top: 12px;">
                                        <!-- Senha -->
                                        <input class="input" type="password" name="password"
                                            placeholder="Crie uma senha*" style="width: 300px;">

                                        <!-- Confirmar Senha -->
                                        <input class="input" type="password" name="password_confirmation"
                                            placeholder="Confirme a senha*" style="width: 300px;">
                                    </div>

                                    <br>
                                    <button class="newsletter-btn" type="submit" style="min-width: 320px;">
                                        <i class="fa fa-envelope"></i> Inscrever-se
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endguest
    @endif

    <!-- FOOTER -->
    <footer id="footer">
        <div id="bottom-footer" class="section">
            <div class="container">
                <div class="row">
                    <div class="text-center col-md-12">
                        <span class="copyright">
                            Copyright &copy;
                            <script>document.write(new Date().getFullYear());</script>
                            All rights reserved | Developed by MeuDeal
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- /FOOTER -->

    <!-- jQuery Plugins -->
    @if (!Route::is('customer.order_requests'))
        <script src="{{ asset('visitor/js/jquery.min.js') }}"></script>
    @endif
    <script src="{{ asset('visitor/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('visitor/js/slick.min.js') }}"></script>
    <script src="{{ asset('visitor/js/nouislider.min.js') }}"></script>
    <script src="{{ asset('visitor/js/jquery.zoom.min.js') }}"></script>
    <script src="{{ asset('visitor/js/main.js') }}"></script>

    <!-- Carousel Marcas -->
    <script>
        $(window).on('load', function () {
            $('#marcas-carousel').slick({
                dots: false,
                infinite: true,
                speed: 800,
                slidesToShow: 6,
                slidesToScroll: 1,
                autoplay: true,
                autoplaySpeed: 1800,
                arrows: true,
                prevArrow: '<button type="button" class="slick-prev"><i class="fa fa-angle-left"></i></button>',
                nextArrow: '<button type="button" class="slick-next"><i class="fa fa-angle-right"></i></button>',
                responsive: [
                    { breakpoint: 1200, settings: { slidesToShow: 5 } },
                    { breakpoint: 992,  settings: { slidesToShow: 4 } },
                    { breakpoint: 768,  settings: { slidesToShow: 3 } },
                    { breakpoint: 480,  settings: { slidesToShow: 2 } }
                ]
            });
        });
    </script>

    {{-- ============================================================
         SweetAlert — notificações automáticas de sessão e validação
         ============================================================ --}}

    @if ($errors->any())
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var mensagens = @json($errors->all());
        swal({
            title: 'Erro no registo',
            text: mensagens.join('\n'),
            icon: 'error',
            button: 'OK',
        });
    });
    </script>
    @endif

    @if (session('success'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        swal({
            title: 'Sucesso!',
            text: @json(session('success')),
            icon: 'success',
            button: 'OK',
        });
    });
    </script>
    @endif

    @if (session('warning'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        swal({
            title: 'Atenção',
            text: @json(session('warning')),
            icon: 'warning',
            button: 'OK',
        });
    });
    </script>
    @endif

    @if (session('error'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        swal({
            title: 'Erro',
            text: @json(session('error')),
            icon: 'error',
            button: 'OK',
        });
    });
    </script>
    @endif

</body>
</html>
