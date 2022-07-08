@extends('layouts.app')
@section('title')

@endsection
@section('content')


<div class="container" id="main-container"></div>



<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/base/jquery-ui.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.jqueryui.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.jqueryui.min.css"/>
 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/dataTables.jqueryui.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.jqueryui.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.colVis.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>


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
                dom: 'Bfrtlip',
                buttons: [
                    {
                        extend: 'pdfHtml5',
                        orientation: 'landscape',
                        pageSize: 'A4',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'excel',
                    'colvis'
                ],
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10 строк', '25 строк', '50 строк', 'Все' ]
                ],
                processing: true,
                serverSide: true,
                'ajax': {
                    'url': '/api/v1/reports/softwares/list',
                    'dataType': 'json',
                    'type': 'GET',
                    'beforeSend': function (xhr) {
                        if (localStorage.token) {
                            xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.token);
                        }
                    },
                },
                'columns': [
                    { data: 'computer', 'width': '18%' },
                    { data: 'name', 'width': '25%' },
                    { data: 'version', 'width': '12%', 'className': 'dt-body-right' },
                    { data: 'vendor', 'width': '16%' },
                    { data: 'install_date', 'width': '12%', 'className': 'dt-body-right' },
                    { data: 'identifying_number', 'width': '17%' }
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
