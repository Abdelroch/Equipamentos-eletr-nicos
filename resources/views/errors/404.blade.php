<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Página não encontrada</title>


     {{-- arquivo admin/header  --}}
     <link rel="shortcut icon" type="image/png" href="{{asset('../assets/images/logos/favicon.png')}}" />
     <link rel="stylesheet" href="{{asset('../assets/css/styles.min.css')}}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assetsindex/img/favicons/apple-touch-icon.png')}}">
     <link rel="icon" type="image/png" sizes="32x32" href='{{asset("assetsindex/img/favicons/favicon-32x32.png")}}'>
     <link rel="icon" type="image/png" sizes="16x16" href='{{asset("assetsindex/img/favicons/favicon-16x16.png")}}'>
     <link rel="shortcut icon" type="image/x-icon" href="{{asset("assetsindex/img/favicons/favicon.ico")}}">
     <link rel="manifest" href='{{asset("assetsindex/img/favicons/manifest.json")}}'>
     <link href="{{ asset('fontawesome/css/all.css') }}" rel="stylesheet">

</head>
<body>

    <div class="container vh-100 d-flex justify-content-center align-items-center text-center">
        <div class="row">
            <div class="col-md-12">
                <!-- Ícone animado -->
                <div class="mb-4">
                    <i class="fas fa-exclamation-circle text-danger" style="font-size: 5rem; animation: bounce 1.5s infinite;"></i>
                </div>
    
                <!-- Mensagens -->
                <h1 class="display-1 text-primary">404</h1>
                <h2 class="mb-4">Ops! Página Não Encontrada</h2>
                <p class="lead text-muted mb-4">
                    A página que você está procurando pode ter sido removida, teve seu nome alterado ou está temporariamente indisponível.
                </p>
    
                <!-- Botão -->
                <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-house-door"></i> Voltar à Página Inicial
                </a>
            </div>
        </div>
    </div>
    
    <!-- Estilos personalizados -->
    <style>
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-15px);
            }
            60% {
                transform: translateY(-10px);
            }
        }
    </style>
    
</body>
</html>
