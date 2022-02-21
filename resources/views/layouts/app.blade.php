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


        <script>
            var loginFormBefore = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Вход<i class="glyph glyph-chevron-down-2"></i></a>' +
                '<ul class="dropdown-menu" role="menu">' +
                '<li><a href="#login-form-dialog" data-toggle="modal">Вход</a></li></ul>';

            var loginFormAfterLogin = '';
            var objUser;
        
            $(document).ready(function () {
                if (localStorage.token) {

                    var date_now = new Date();
                    var token_date_exp = new Date(Date.parse(localStorage.token_date_exp));

                    if (date_now > token_date_exp) {
                        localStorage.clear();
                        $('#header-login').html(loginFormBefore);
                        console.log('При загрузке страницы обнаружен просроченный токен для авторизации!');
                        return false;
                    } else {
                        // console.log(token_date_exp);
                        console.log('При загрузке страницы обнаружен действующий токен для авторизации (' + token_date_exp + '), продолжаем работу.');
                    }
                    
                    if (localStorage.objUser) {
                        objUser = JSON.parse(localStorage.getItem('objUser'));
                        console.log('При загрузке страницы обнаружен сохраненный объект пользователя');
                        // console.log(objUser);
                        loginFormAfterLogin = '<div class="header-profile-form"><a href="/profile" >' + objUser.name + '</a></div>';
                        $('#header-login').html(loginFormAfterLogin);
                    } else {
                        var json_url_profile = '/api/profile';
                        $.ajax({
                            type: "GET",
                            url: json_url_profile,
                            beforeSend: function (xhr) {
                                if (localStorage.token) {
                                    xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.token);
                                }
                            },
                            success: function (data) {
                                localStorage.setItem('objUser', JSON.stringify(data.user));
                                console.log('Получен и сохранен объект пользователь');
                                loginFormAfterLogin = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' + data.user.name + '<i class="glyph glyph-chevron-down-2"></i></a>' +
                                        '<ul class="dropdown-menu" role="menu">' +
                                        '<li><a href="/profile" data-toggle="modal">Профиль</a></li>' +
                                        '<li><a href="/logout" data-toggle="modal">Выход</a></li></ul>';
                                $('#header-login').html(loginFormAfterLogin);
                            },
                            error: function (jqXHR, text, error) {
                                localStorage.clear();
                                console.log(error);
                                $('#header-login').html(loginFormBefore);
                                console.log('Не удалось получить профиль пользователя с сервера!');
                            }
                        });
                    }

                } else {
                    // localStorage.clear();
                    $('#header-login').html(loginFormBefore);
                    console.log('При загрузке страницы не обнаружен токен для авторизации!');
                }
        
                $("#login-form").submit(function () {
                    var json_url_login = '/api/login';
                    $.ajax({
                        type: "POST",
                        url: json_url_login,
                        headers: {
                            'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'
                        },
                        data: $('#login-form').serialize(),
                        success: function (data) {
                            if (data.token) {
                                localStorage.token = data.token;
                                var token_date = new Date();
                                var token_date_exp = new Date(token_date.getTime() + data.expires_in * 1000);
                                console.log(token_date_exp.toString());
                                localStorage.token_date_exp =  token_date_exp.toString();
                                // console.log(localStorage.token_date_exp);
                                var json_url_profile = '/api/profile';
                                $.ajax({
                                    type: "GET",
                                    url: json_url_profile,
                                    beforeSend: function (xhr) {
                                        if (localStorage.token) {
                                            xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.token);
                                        }
                                    },
                                    success: function (data) {
                                        loginFormAfterLogin = '<div class="header-profile-form"><a href="/profile" >' + data.user.name + '</a></div>';
                                        $('#header-login').html(loginFormAfterLogin);

                                        window.location.href='/';
                                    },
                                    error: function (jqXHR, text, error) {
                                        console.log(error);
                                        console.log('Не удалось получить профиль пользователя с сервера после авторизации!');
                                    }
                                });
                            }
                        },
                        error: function (jqXHR, text, error) {
                            console.log(error);
                            console.log('Не удалось авторизоваться на сервере!');
                        }
                    });
                    return false;
                });
            });
        </script>


    </head>
    <body>

        @include('layouts.partials.navbar')



        @yield('title')
        @yield('title-meta')
        @yield('content')



        @include('layouts.partials.login')

        @include('layouts.partials.backtop')

        <script src="{{ url('/assets/js/vendor/bootstrap.min.js') }}"></script>
        <script src="{{ url('/assets/js/app.js') }}"></script>




    </body>
</html>