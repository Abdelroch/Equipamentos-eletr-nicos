
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="shortcut icon" type="image/png" href="{{asset('../assets/images/logos/favicon.png')}}" />
    <link rel="stylesheet" href="{{asset('../assets/css/styles.min.css')}}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assetsindex/img/favicons/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" sizes="32x32" href='{{asset("assetsindex/img/favicons/favicon-32x32.png")}}'>
    <link rel="icon" type="image/png" sizes="16x16" href='{{asset("assetsindex/img/favicons/favicon-16x16.png")}}'>
    <link rel="shortcut icon" type="image/x-icon" href="{{asset("assetsindex/img/favicons/favicon.ico")}}">
    <link rel="manifest" href='{{asset("assetsindex/img/favicons/manifest.json")}}'>
    <link rel="stylesheet" href="{{asset('swalert/sweetalert2@11.js')}}" />
    <!-- No cabeçalho ou antes do fechamento da tag </body> -->
    <link rel="stylesheet" href="{{ asset('sweetalert/sweetalert2.min.css') }}">{{--
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('admin/sweetalert2.min.css') }}">

    <!-- DataTables CSS -->{{--
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css"> --}}
    <link rel="stylesheet" href="{{ asset('admin/dataTables.bootstrap5.min2.css') }}">
    <<!-- Fix: dropdown de notificações do header aparecendo atrás/cortado pela sidebar -->
    <style>
        .app-header {
            position: relative;
            z-index: 1050;
        }

        .app-header .nav-item.dropdown {
            position: relative;
            z-index: 1100;
        }

        /* O tema usa data-bs-popper="static", que desativa o Popper.js e faz o
           dropdown nascer "preso" no fluxo normal do documento em vez de flutuar
           sob o botão. Forçamos position fixed calculado via JS abaixo, e aqui
           garantimos que o menu nunca fique contido/cortado pelos pais. */
        #drop1 ~ .dropdown-menu {
            position: absolute !important;
            top: 100% !important;
            left: auto !important;
            right: 0 !important;
            inset: auto !important;
            transform: none !important;
            z-index: 1200 !important;
            margin-top: 0.5rem;
        }
    </style>

 </head>
