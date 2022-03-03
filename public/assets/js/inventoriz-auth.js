/**
 * Скрипт авторизации приложения Inventoriz
 * 2022.02.21 (c) Павел Сатин
 * 
 */



$(document).ready(function () {

    var loginFormBefore = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Вход<i class="glyph glyph-chevron-down-2"></i></a>' +
        '<ul class="dropdown-menu" role="menu">' +
        '<li><a href="#login-form-dialog" data-toggle="modal">Вход</a></li></ul>';

    var loginFormAfterLogin = '';
    var objUser;


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
            loginFormAfterLogin = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' + objUser.name + '<i class="glyph glyph-chevron-down-2"></i></a>' +
                '<ul class="dropdown-menu" role="menu">' +
                '<li><a href="/profile" data-toggle="modal">Профиль</a></li>' +
                '<li><a href="#" data-toggle="modal" id="logout-btn">Выход</a></li></ul>';

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
                        '<li><a href="#" data-toggle="modal" id="logout-btn">Выход</a></li></ul>';

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
                alert('Не удалось авторизоваться на сервере!');
            }
        });
        return false;
    });


    $("#logout-btn").click(function () {
        var json_url_logout = '/api/logout';
        $.ajax({
            type: "POST",
            url: json_url_logout,
            beforeSend: function (xhr) {
                if (localStorage.token) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.token);
                }
            },
            success: function (data) {
                localStorage.clear();
                window.location.href='/';
            },
            error: function (jqXHR, text, error) {
                console.log(error);
                console.log('Не удалось деавторизоваться на сервере!');
            }
        });
        return false;
    });




});
