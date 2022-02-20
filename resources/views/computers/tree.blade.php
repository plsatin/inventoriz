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


<style>
    .list-computers, .computer-tree {
        height: 600px;
        overflow-x: hidden;
        overflow-y: scroll;
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
                        <div class="computer-tree" id="tree"></div>
                        <div id="statusline"></div>
                    </div>
                </div>



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
                source: {url: '/api/v1/computers/'+computerId+'/properties'},
                tooltip: true,
                iconTooltip: function(event, data) {
                    return data.typeInfo.iconTooltip;
                },
                icon: function(event, data) {
                    if (data.node.icon) {
                        return '/assets/img/icons/' + data.node.icon;
                    }
                },

            });
        }



    });

</script>

@endsection
