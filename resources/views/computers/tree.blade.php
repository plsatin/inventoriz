@extends('layouts.app')
@section('title')
<div class="page-header">
  <h1>{{ $page_title ?? '' }}</h1>
</div>
@endsection
@section('content')


<div class="computer-tree" id="tree"></div>





<script>

$('#tree').jstree({
    'core': {
        'data': {
            'url': "/api/v1/computers/5/properties",
            'type': 'GET',
            'dataType': 'JSON',
            'contentType':'application/json',
            'data': function (node) {
                console.log(node);
                return { 'id' : node.id };
            }
        }
    }
});

</script>

@endsection
