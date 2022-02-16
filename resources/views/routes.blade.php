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


@endsection