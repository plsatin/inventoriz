@extends('layouts.app')
@section('title')

@endsection
@section('content')


<div class="container" id="main-container"></div>





<script>


    $(document).ready(function () {

        if (localStorage.token) {

            /* Получаем объект пользователь из localStorage */
            objUser = JSON.parse(localStorage.getItem('objUser'));
            role_id = objUser.role_id;


            main_container_html = '' +
                '<div class="row">' +
                    '<div class="col-xs-24">' +
                        '<section class="section">' +
                            '<header class="section-header">' +
                                '<h1 class="section-title">' +
                                    '' +
                                '</h1>' +
                            '</header>' +
                            '<div class="row">' +
                                '<div class="col-md-24">' +
                                    '<div class="table-responsive">' +
                                        '<table class="table" id="tableSoftwares">' +
                                            '<thead>' +
                                            '<tr>' +
                                                '<th>Компьютер</th>' +
                                                '<th>Наименование</th>' +
                                                '<th>Версия</th>' +
                                                '<th>Производитель</th>' +
                                                '<th>Дата установки</th>' +
                                                '<th>Индентификатор</th>' +
                                            '</tr>' +
                                            '</thead>' +
                                            '<tbody>' +
                                            '</tbody>' +
                                        '</table>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                        '</section>' +
                    '</div>' +
                '</div>';

            $('#main-container').html(main_container_html);


            $('#tableSoftwares').DataTable( {
                'ajax': {
                    'url': '/api/v1/reports/softwares/list',
                    'dataType': 'json',
                    'type': 'GET',
                    'headers': {
                        'Authorization': 'Bearer: ' + localStorage.token,
                    },
                },
                'columns': [
                    { 'width': '18%' },
                    { 'width': '25%' },
                    { 'width': '12%', 'className': 'dt-body-right' },
                    { 'width': '16%' },
                    { 'width': '12%', 'className': 'dt-body-right' },
                    { 'width': '17%' }
                ],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/ru.json'
                }
            });

        } else {

            localStorage.clear();

            alerts_msg = '' +
                '<div class="alert alert-warning alert-dismissible fade in" role="alert">' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                        '<span aria-hidden="true"><i class="glyph glyph-cancel"></i></span>' +
                    '</button>' +
                    '<div class="container">' +
                        '<div class="row">' +
                            '<div class="col-md-20">' +
                                '<p>' +
                                    'Вы не авторизованы! Пройдите процедуру авторизации!' +
                                '</p>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>';

            $('#header-alert-stack').html(alerts_msg);

        } // if token


    });


</script>

@endsection
