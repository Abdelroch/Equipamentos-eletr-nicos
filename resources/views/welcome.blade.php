<!DOCTYPE html>
<html lang="en-US" dir="ltr">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- ===============================================-->
    <!--    Document Title-->
    <!-- ===============================================-->
    <title>MK LDA</title>


    <!-- ===============================================-->
    <!--    Favicons-->
    <!-- ===============================================-->
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assetsindex/img/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href="assetsindex/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assetsindex/img/favicons/favicon-16x16.png">
    <link rel="shortcut icon" type="image/x-icon" href="assetsindex/img/favicons/favicon.ico">
    <link rel="manifest" href="assetsindex/img/favicons/manifest.json">
    <meta name="msapplication-TileImage" content="assetsindex/img/favicons/mstile-150x150.png">
    <meta name="theme-color" content="#ffffff">


    <!-- ===============================================-->
    <!--    Stylesheets-->
    <!-- ===============================================-->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200;300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <link href="vendors/prism/prism.css" rel="stylesheet">
    <link href="vendors/swiper/swiper-bundle.min.css" rel="stylesheet">
    <link href="assetsindex/css/theme.css" rel="stylesheet" />
    <link href="assetsindex/css/user.css" rel="stylesheet" />

  </head>


  <body>

    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->
    <main class="main" id="top">
      <nav class="navbar navbar-expand-lg fixed-top navbar-dark" data-navbar-on-scroll="data-navbar-on-scroll">
        <div class="container"><a class="navbar-brand" href="index.html">  <strong  style="font-size:40px; color:#4492ff;">Meu <b style="color:#121111; ">Deal</b></strong></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="fa-solid fa-bars text-white fs-3"></i></button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
              <li class="nav-item"><a class="nav-link active" aria-current="page" href="/" style="color: #4492ff; font-size:22px;"> <b>Home</b></a></li>

         @if (Route::has('login'))
             @auth

                  <li class="nav-item mt-2 mt-lg-0"><a class="nav-link btn btn-light text-black w-md-25 w-50 w-lg-100" aria-current="page" href="{{ url('/admin/dashboard') }}">Dashboard</a></li>
             @else
           
                 <li class="nav-item"><a class="nav-link" aria-current="page" href="{{ route('login') }}"> Login</a></li>             

           {{--   @if (Route::has('register'))
                     
                     <li class="nav-item"><a class="nav-link" aria-current="page" href="{{ route('register') }}"> Register</a></li>             
             @endif --}}
         @endauth
         @endif
            </ul>
          </div>
        </div>
      </nav>
      <div class="bg-dark"><img class="img-fluid position-absolute end-0" src="assetsindex/img/hero/hero-bg.png" alt="" />


        <!-- ============================================-->
        <!-- <section> begin ============================-->
        <section>

          <div class="container">
            <div class="row align-items-center py-lg-6 py-4">
              <div class="col-lg-6 text-center text-lg-start">
                <h1 class="text-white fs-5 fs-xl-6">Bem-vindo de Volta!</h1>
                <p class="text-white py-lg-2 py-1">Adicione facilmente novos produtos, clientes ou fornecedores e mantenha o controle centralizado do seu negócio!</p>
              
              </div>
              <div class="col-lg-6 text-center text-lg-end mt-3 mt-lg-0"><img class="img-fluid" src="assetsindex/img/hero/hero-graphics.png" alt="" /></div>
            </div>

            <center>
                 <p class="copy">
                &copy; Escola 30 de Setembro, 2025 ❤️
                 </p>
            </center>

            <style>
                center{
                    margin-bottom: 0;

                }

                .copy{
                    margin-bottom: 0;
                }
            </style>
           
           
    </main>

   
    <!-- ===============================================-->
    <!--    JavaScripts-->
    <!-- ===============================================-->
    <script src="vendors/popper/popper.min.js"></script>
    <script src="vendors/bootstrap/bootstrap.min.js"></script>
    <script src="vendors/anchorjs/anchor.min.js"></script>
    <script src="vendors/is/is.min.js"></script>
    <script src="vendors/fontawesome/all.min.js"></script>
    <script src="vendors/lodash/lodash.min.js"></script>
    <script src="https://polyfill.io/v3/polyfill.min.js?features=window.scroll"></script>
    <script src="vendors/prism/prism.js"></script>
    <script src="vendors/swiper/swiper-bundle.min.js"></script>
    <script src="assetsindex/js/theme.js"></script>

  </body>

</html>
