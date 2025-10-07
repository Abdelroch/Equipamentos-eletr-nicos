<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assetsindex/img/favicons/apple-touch-icon.png')}}">
        <link rel="icon" type="image/png" sizes="32x32" href='{{asset("assetsindex/img/favicons/favicon-32x32.png")}}'>
       <link rel="icon" type="image/png" sizes="16x16" href='{{asset("assetsindex/img/favicons/favicon-16x16.png")}}'>
       <link rel="shortcut icon" type="image/x-icon" href="{{asset("assetsindex/img/favicons/favicon.ico")}}">
      <link rel="manifest" href='{{asset("assetsindex/img/favicons/manifest.json")}}'>

        <title>Boas Vindas!</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
           <link href="{{ asset('build/assets/app-27abf3b7.css') }}" rel="stylesheet">
           <script src="{{ asset('build/assets/app-9bd023a7.js') }}" defer></script>
           <link href="{{ asset('css/app.css') }}" rel="stylesheet">
           <script src="{{ asset('js/app.js') }}"></script>
           <script src="{{ asset('js/bootstrap.js') }}"></script>
    </head>
    <body class="font-sans antialiased text-gray-900">
        <div class="flex flex-col items-center min-h-screen pt-6 bg-gray-100 sm:justify-center sm:pt-0 dark:bg-gray-900">
            <div>
                <a href="/">
                    <strong  style="font-size:40px; color:#4492ff;">Meu <b style="color:white; ">Deal</b></strong>
                </a>
            </div>

            <div class="w-full px-6 py-4 mt-6 overflow-hidden bg-white shadow-md sm:max-w-md dark:bg-gray-800 sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
