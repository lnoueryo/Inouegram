<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    {{--  <link href="{{ asset('css/app.css') }}" rel="stylesheet">  --}}
    <style>
        [v-cloak] {
            opacity: 0;
          }
    </style>
</head>
<body>
    <div id="app" v-cloak>
        <v-app>
            @auth
            <header-bar :user='@json(Auth::user())'></header-bar>
            @endauth
            @guest
            <header-bar :user="null"></header-bar>
            @endguest
            <keep-alive>
                <v-main>
                    <v-container>
                        @yield('content')
                    </v-container>
                </v-main>
            </keep-alive>
        </v-app>
    </div>
</body>
</html>
