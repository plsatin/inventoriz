@extends('layouts.app')
@section('title')
{{-- <div class="page-header">
    <div class="container">
        <div class="row">
            <div class="col-xs-24">
                <h2>{{ $page_title ?? '' }}</h2>
            </div>
        </div>
    </div>
</div> --}}
@endsection
@section('content')

<link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" />

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>


<style>
    .chart_wrap {
        position: relative;
        margin: 2px;
    }
</style>



<div class="container">
    <div class="row">
        <div class="col-xs-24">
            <section class="section">
                <header class="section-header">
                    <h1 class="section-title">
                        {{ $page_title ?? '' }}
                    </h1>
                </header>
    
                <div class="row">
                    <div class="col-md-12">
                        <div class="chart_wrap">
                            <div id="chartManufacturers" style="width: 100%; height: 360px;"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="chart_wrap">
                            <div id="chartOS" style="width: 100%; height: 360px;"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="chart_wrap">
                            <div id="chartCPU" style="width: 100%; height: 360px;"></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="chart_wrap">
                            <div id="chartUpdated" style="width: 100%; height: 360px;"></div>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-24">
            <section class="section">
                <header class="section-header">
                    <h1 class="section-title">
                        Компьютеры
                    </h1>
                </header>

                <div class="row">
                    <div class="col-md-24">
                        <div class="table-responsive">
                            <table class="table" id="tableComputers">
                                <thead>
                                <tr>
                                    <th>Имя</th>
                                    <th>Последний опрос</th>
                                    <th>Операционная система</th>
                                    <th>Процессор</th>
                                    <th>Оперативная память</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </section>
        </div>
    </div>

</div>





<script>
// https://sawtech.ru/tehno-blog/rabota-s-grafikami-i-diagrammami-google-chart/


    $.ajaxSetup({ async: false });

    var dataManufacturer = [];
    var dataOS = [];

    // $(document).ready(function () {

        dataManufacturer = getDataFromInventoriz('/api/v1/reports/properties/113'); //86
        dataOS = getDataFromInventoriz('/api/v1/reports/properties/15');
        dataCPU = getDataFromInventoriz('/api/v1/reports/properties/4');
        // dataRAM = getDataFromInventoriz('/api/v1/reports/properties/88');
        dataUpdated = getDataFromInventorizUpdated('/api/v1/reports/computers/last_updated');

        // console.log(dataUpdated);


        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChartManufacturers);
        google.charts.setOnLoadCallback(drawChartOS);
        google.charts.setOnLoadCallback(drawChartCPU);

        google.charts.setOnLoadCallback(drawChartUpdated);


    // });

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


    // $.ajax({
    //     type: "GET",
    //     url: '/api/v1/reports/computers/list',
    //     success: function (data) {
    //         console.log(data);
    //     },
    //     error: function (jqXHR, text, error) {
    //         console.log(error);
    //     }
    // });


    setTimeout(function(){
        $('#tableComputers').DataTable( {
            "ajax": '/api/v1/reports/computers/list',
            "columns": [
                { "width": "20%" },
                { "width": "15%" },
                { "width": "28%" },
                { "width": "27%" },
                { "width": "10%", "className": "dt-body-right" }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/ru.json'
            }
        });
    }, 1000);

</script>

@endsection