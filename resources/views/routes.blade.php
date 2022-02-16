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
                    <div class="col-xs-24">

                        <table id="routes-table" class="table table-bordered table-responsive">
                            <thead>
                                    <tr>
                                        <th>uri</th>
                                        <th>Controller</th>
                                        <th>Action</th>
                                        <th>Method</th>
                                    </tr>
                            </thead>
                            <tbody>
                                    @foreach ($routes as $route )
                                        <tr>
                                            <td>{{ $route->uri }}</td>
                                            <td>{{ $route->controller }}</td>
                                            <td>{{ $route->action }}</td>
                                            <td>{{ $route->method }}</td>
                                        </tr>
                                    @endforeach
                            </tbody>
                        </table>

                    </div>
                </div>
            
            </section>
        </div>
    </div>
</div>
            


@endsection