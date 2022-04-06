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
                                        '<table class="table" id="tableComputers">' +
                                            '<thead>' +
                                            '<tr>' +
                                                '<th>Имя</th>' +
                                                '<th>Последний опрос</th>' +
                                                '<th>Операционная система</th>' +
                                                '<th>Процессор</th>' +
                                                '<th>Оперативная память</th>' +
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


            $('#tableComputers').DataTable( {
                processing: true,
                serverSide: true,
                'ajax': {
                    'url': '/api/v1/reports/computers/list',
                    'dataType': 'json',
                    'type': 'GET',
                    'beforeSend': function (xhr) {
                        if (localStorage.token) {
                            xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.token);
                        }
                    },
                },
                'columns': [
                    { 'width': '20%' },
                    { 'width': '15%' },
                    { 'width': '28%' },
                    { 'width': '27%' },
                    { 'width': '10%', 'className': 'dt-body-right' }
                ],
                language: {
                    url: '/assets/js/datatables/ru.json'
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
