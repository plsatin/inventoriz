@extends('layouts.app')
@section('title')
<div class="page-header">
  <h1>{{ $page_title ?? '' }}</h1>
</div>
@endsection
@section('content')


<div class="computer-tree" id="tree"></div>





<script>
$(document).ready(function () {
    $.ajaxSetup({ async: false });

// $('#tree').jstree({
//     'core': {
//         'data': {
//             'url': "/api/v1/computers/6/properties",
//             'type': 'GET',
//             'dataType': 'JSON',
//             'contentType':'application/json',
//             'data': function (node) {
//                 console.log(node);
//                 return { 'id' : node.id };
//             }
//         }
//     }
// });


    var jsonTreeData;


    $.ajax({
        type: "GET",
        url: "/api/v1/computers/6/properties",
        success: function (data) {
            jsonTreeData = data;

        }
    });

    $('#tree').jstree({ 'core' : {
        'data' : [
            jsonTreeData,
        ]
    } });




    $.ajaxSetup({ async: true });
});

</script>

@endsection
