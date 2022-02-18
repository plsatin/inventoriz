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


                        <div class="table-responsive">
                            <table class="table" id="tableSoftwares">
                                <thead>
                                <tr>
                                    <th>Наименование</th>
                                    <th>Версия</th>
                                    <th>Производитель</th>
                                    <th>Дата установки</th>
                                    <th>Индентификатор</th>
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
    setTimeout(function(){
        $('#tableSoftwares').DataTable( {
            "ajax": '/api/v1/reports/softwares/list',
            "columns": [
                { "width": "30%" },
                { "width": "15%", "className": "dt-body-right" },
                { "width": "20%" },
                { "width": "15%", "className": "dt-body-right" },
                { "width": "20%" }
            ],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/ru.json'
            }
        });
    }, 500);

</script>

@endsection
