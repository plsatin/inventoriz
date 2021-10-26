<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="utf-8">
	  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title></title>
    
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="{{ url('/assets/css/ui.fancytree.css') }}">

    <script type="text/javascript" src="{{ url('/assets/js/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/js/jquery-ui-dependencies/jquery.fancytree.ui-deps.js') }}"></script>
    <script type="text/javascript" src="{{ url('/assets/js/jquery.fancytree.js') }}"></script>

  </head>
  <body>
    <section class="page-head">
        <header class="container">
        </header>
    </section>

    <section class="container">
        <div class="page-content">


        @yield('title')
        @yield('title-meta')
        @yield('content')



      </div>
    </section>


    <section class="page-footer">
        <footer>
        </footer>
    </section>
  
  </body>
</html>