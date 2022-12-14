@extends('layouts.app')
@section('title')

@endsection
@section('content')


<div class="container" id="main-container"></div>





<script>

    // $.ajaxSetup({ async: false });

    var dataManufacturer = [];
    var dataOS = [];

    $(document).ready(function () {

        if (localStorage.token) {

            /* Получаем объект пользователь из localStorage */
            objUser = JSON.parse(localStorage.getItem('objUser'));
            role_id = objUser.role_id;

            // localStorage.token

            main_container_html = '' +
                '<div class="row">' +
                    '<div class="col-xs-24">' +
                        '<section class="section">' +
                            '<header class="section-header">' +
                                '<h1 class="section-title">' +
                                    'Статистика' +
                                '</h1>' +
                            '</header>' +
                            '<div class="row">' +
                                '<div class="col-md-12">' +
                                    '<div class="chart_wrap">' +
                                        '<div id="chartManufacturers" style="width: 100%; height: 360px;"></div>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-md-12">' +
                                    '<div class="chart_wrap">' +
                                        '<div id="chartOS" style="width: 100%; height: 360px;"></div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +
                            '<div class="row">' +
                                '<div class="col-md-12">' +
                                    '<div class="chart_wrap">' +
                                        '<div id="chartCPU" style="width: 100%; height: 360px;"></div>' +
                                    '</div>' +
                                '</div>' +
                                '<div class="col-md-12">' +
                                    '<div class="chart_wrap">' +
                                        '<div id="chartUpdated" style="width: 100%; height: 360px;"></div>' +
                                    '</div>' +
                                '</div>' +
                            '</div>' +

                        '</section>' +
                    '</div>' +
                '</div>' +
                '<div class="row">' +
                    '<div class="col-xs-24">' +
                        '<section class="section">' +
                            '<header class="section-header">' +
                                '<h1 class="section-title">' +
                                    'Компьютеры' +
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





            dataManufacturer = getDataFromInventoriz('/api/v1/reports/computers/properties/113'); //86
            dataOS = getDataFromInventoriz('/api/v1/reports/computers/properties/15');
            dataCPU = getDataFromInventoriz('/api/v1/reports/computers/properties/4');
            // dataRAM = getDataFromInventoriz('/api/v1/reports/computers/properties/88');
            dataUpdated = getDataFromInventorizUpdated('/api/v1/reports/computers/last_updated');

            // console.log(dataUpdated);


            google.charts.load("current", {packages:["corechart"]});
            google.charts.setOnLoadCallback(drawChartManufacturers);
            google.charts.setOnLoadCallback(drawChartOS);
            google.charts.setOnLoadCallback(drawChartCPU);

            google.charts.setOnLoadCallback(drawChartUpdated);


            $('#tableComputers').DataTable( {
                processing: true,
                // serverSide: true,
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

    function drawChartManufacturers() {
        var data = google.visualization.arrayToDataTable(dataManufacturer);
        var options = {
            width: '100%',
            title: 'Производители',
            pieHole: 0.4,
            chartArea: {
                left: 0,
                height: 250,
                width: 600
            },
            legend: {
                maxLines: 2,
            }
        };
        var chart = new google.visualization.PieChart(document.getElementById('chartManufacturers'));
        chart.draw(data, options);
    }

    function drawChartOS() {
        var data = google.visualization.arrayToDataTable(dataOS);
        var options = {
            width: '100%',
            title: 'Операционные системы',
            pieHole: 0.4,
            chartArea: {
                left: 0,
                height: 250,
                width: 600
            },
            legend: {
                maxLines: 2,
            }
        };
        var chart = new google.visualization.PieChart(document.getElementById('chartOS'));
        chart.draw(data, options);
    }

    function drawChartCPU() {
        var data = google.visualization.arrayToDataTable(dataCPU);
        var options = {
            width: '100%',
            title: 'Процессоры',
            pieHole: 0.4,
            chartArea: {
                left: 0,
                height: 250,
                width: 600
            },
            legend: {
                maxLines: 2,
            }
        };
        var chart = new google.visualization.PieChart(document.getElementById('chartCPU'));
        chart.draw(data, options);
    }

    function drawChartRAM() {
        var data = google.visualization.arrayToDataTable(dataRAM);
        var options = {
            width: '100%',
            title: 'Оперативная память, Мб',
            pieHole: 0.4,
            chartArea: {
                left: 0,
                height: 250,
                width: 600
            },
            height: 300,
            width: 600,
            legend: {
                maxLines: 2,
            }
        };
        var chart = new google.visualization.PieChart(document.getElementById('chartRAM'));
        chart.draw(data, options);
    }


    function drawChartUpdated() {
        var data = google.visualization.arrayToDataTable(dataUpdated);

        var options = {
            width: '100%',
            title: 'Последние опросы',
            // hAxis: {title: 'Дата',  titleTextStyle: {color: '#333'}},
            // vAxis: {title: 'Компьютеры', minValue: 0},
            curveType: 'function',
            chartArea: {
                left: 0,
                height: 250,
                width: 600
            },
            legend: { position: 'none' },


        };

        var chart = new google.visualization.LineChart(document.getElementById('chartUpdated'));
        // var chart = new google.visualization.AreaChart(document.getElementById('chartUpdated'));
        chart.draw(data, options);
    }


    function getDataFromInventoriz(dataUrl) {
        var arrValues = [];
        $.ajax({
            type: "GET",
            url: dataUrl,
            beforeSend: function (xhr) {
                if (localStorage.token) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.token);
                }
            },
            success: function (data) {
                // console.log(data);
                var result = [];
                data.reduce(function(res, dataR) {
                    if (!res[dataR.value]) {
                        res[dataR.value] = { Manufacturer: dataR.value, qty: 0 };
                        result.push(res[dataR.value])
                    }
                    res[dataR.value].qty += 1;
                    return res;
                }, {});

                arrValues.push(['Наименование', 'Количество']);
                $.each( result, function( key, value ) {
                    arrValues.push([value.Manufacturer, value.qty]);
                });
               
            },
            error: function (jqXHR, text, error) {
                console.log(error);
            }
        });
        return arrValues;
    }

    function getDataFromInventorizUpdated(dataUrl) {
        var arrValues = [];
        $.ajax({
            type: "GET",
            url: dataUrl,
            data: jQuery.param({ 'limit': '7', 'order': 'desc' }),
            beforeSend: function (xhr) {
                if (localStorage.token) {
                    xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.token);
                }
            },
            success: function (data) {
                // console.log(data);
                var result = [];

                arrValues.push(['Дата', 'Компьютеры']);
                $.each( data, function( key, value ) {
                    arrValues.push([value.date, value.total]);
                });
               
            },
            error: function (jqXHR, text, error) {
                console.log(error);
            }
        });
        return arrValues;
    }


</script>

@endsection
