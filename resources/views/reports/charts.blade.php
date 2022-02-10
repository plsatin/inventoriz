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
                    <div class="col-md-24">

                        <div class="chart_wrap">
                            <div id="donutchart" style="width: 100%; height: 250px;"></div>
                        </div>

                    </div>
                </div>



            </section>
        </div>
    </div>
</div>





<script>

    var dataManufacturer = [];

    $(document).ready(function () {

        dataManufacturer = getDataManufacturer();
        console.log(dataManufacturer);


        google.charts.load("current", {packages:["corechart"]});
        google.charts.setOnLoadCallback(drawChart(dataManufacturer));

    });

    function drawChart(data) {
        var data = google.visualization.arrayToDataTable(data);

        var options = {
            title: 'Top Manufacturer',
            pieHole: 0.4,
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }

    function getDataManufacturer() {

        $.ajax({
            type: "GET",
            url: '/api/v1/reports/properties/86',
            success: function (data) {
                // console.log(data);
                // var arrComputersManufacturer;
                // for (var computer in data) {
                // }

                var result = [];
                result = data.reduce(function(res, dataR) {
                    if (!res[dataR.value]) {
                        res[dataR.value] = { Manufacturer: dataR.value, qty: 0 };
                        result.push(res[dataR.value])
                    }
                    res[dataR.value].qty += 1;
                    return res;
                }, {});

                console.log(result);

                dataManufacturer = result;
            },
            error: function (jqXHR, text, error) {
                console.log(error);
            }
        });
        
    }




</script>

@endsection
