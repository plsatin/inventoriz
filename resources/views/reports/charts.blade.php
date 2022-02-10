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


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

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

            </section>
        </div>
    </div>
</div>





<script>

    $.ajaxSetup({ async: false });

    var dataManufacturer = [];
    var dataOS = [];

    // $(document).ready(function () {

        dataManufacturer = getDataFromInventoriz('86');
        dataOS = getDataFromInventoriz('15');
        // console.log(dataManufacturer);


        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChartManufacturers);
        google.charts.setOnLoadCallback(drawChartOS);

    // });

    function drawChartManufacturers() {
        var data = google.visualization.arrayToDataTable(dataManufacturer);
        var options = {
            title: 'Производители',
            pieHole: 0.4,
            legend: {
                position: 'right',
                alignment: 'center',
                width: 400,
                floating: true
            },
        };
        var chart = new google.visualization.PieChart(document.getElementById('chartManufacturers'));
        chart.draw(data, options);
    }

    function drawChartOS() {
        var data = google.visualization.arrayToDataTable(dataOS);
        var options = {
            title: 'Операционные системы',
            pieHole: 0.4,
            legend: { position: "right", alignment: "start", maxLines: 2 },
        };
        var chart = new google.visualization.PieChart(document.getElementById('chartOS'));
        chart.draw(data, options);
    }


    function getDataFromInventoriz(dataId) {
        var arrValues = [];
        $.ajax({
            type: "GET",
            url: '/api/v1/reports/properties/' + dataId,
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




</script>

@endsection
