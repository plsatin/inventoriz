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
    

                <div class="computer-tree" id="tree" style="height: 600px; overflow-x: hidden; overflow-y: scroll;"></div>
                <div id="statusline"></div>



            </section>
        </div>
    </div>
</div>





<script>
    $(document).ready(function () {
    var computerName = "{{ $computer->name }}";
    var computerId = "{{ $computer->id }}";

        renderComputerTree(computerId);

        function renderComputerTree(computerId){
            $('#tree').fancytree({
                tooltip: true,
                iconTooltip: function(event, data) {
                    return data.typeInfo.iconTooltip;
                },
                source: {url: '/api/v1/computers/'+computerId+'/properties'},
            });
        }



    });

</script>

@endsection
