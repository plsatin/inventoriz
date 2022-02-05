<!DOCTYPE html>
<html lang="ru">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>{{ $page_title ?? '' }}</title>
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <meta name="keywords" content="">
        <meta name="description" content="">

        <link rel="icon" href="{{ url('/assets/img/logo/favicon.ico') }}" type="image/x-icon">



        <link rel="stylesheet" href="{{ url('/assets/css/winstrap.min.css') }}" />
        <script src="{{ url('/assets/js/vendor/jquery.min.js') }}"></script>
        <!--[if lt IE 9]>
            <script src="{{ url('/assets/js/vendor/html5shiv.min.js') }}"></script>
            <script src="{{ url('/assets/js/vendor/respond.min.js') }}"></script>
        <![endif]-->

        
        <link rel="stylesheet" type="text/css" href="{{ url('/assets/css/ui.fancytree.css') }}">

        <script type="text/javascript" src="{{ url('/assets/js/jquery-3.3.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/js/jquery-ui-dependencies/jquery.fancytree.ui-deps.js') }}"></script>
        <script type="text/javascript" src="{{ url('/assets/js/jquery.fancytree.js') }}"></script>
    </head>
    <body>

        @include('layouts.partials.navbar')



        @yield('title')
        @yield('title-meta')
        @yield('content')



        @include('layouts.about.navbar')
        @include('layouts.backtop.navbar')

        <script src="{{ url('/assets/js/vendor/bootstrap.min.js') }}"></script>
        <script src="{{ url('/assets/js/app.js') }}"></script>
    </body>
</html>