@extends('layouts.app')
@section('title')
<div class="page-header">
  <h1>{{ $page_title ?? '' }}</h1>
</div>
@endsection
@section('content')



    <div class="computer-tree" id="tree"></div>
    <div id="statusline"></div>





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
