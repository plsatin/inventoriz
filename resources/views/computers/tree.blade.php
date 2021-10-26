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
    // $.ajaxSetup({ async: false });


    $("#tree").fancytree({
        checkbox: false,
        selectMode: 3,
        source: {
            url:
            "/api/v1/computers/6/properties"
        },

        // activate: function(event, data) {
        //     $("#statusLine").text(event.type + ": " + data.node);
        // },
        // select: function(event, data) {
        //     $("#statusLine").text(
        //     event.type + ": " + data.node.isSelected() + " " + data.node
        //     );
        // }
    });




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




    // var jsonTreeData;
    // $.ajax({
    //     type: "GET",
    //     url: "/api/v1/computers/6/properties",
    //     success: function (data) {
    //         jsonTreeData = data;

    //     }
    // });

    // console.log(jsonTreeData);

    // $('#tree').jstree({ 'core' : {
    //     'data' : [
    //         jsonTreeData,
    //     ]
    // } });



    // $(function () {
    //     $.ajax({
    //         async: true,
    //         type: "GET",
    //         url: "/api/v1/computers/6/properties",
    //         dataType: "json",
    //         success: function (json) {
    //             console.log(json);
    //             createJSTree(json);
    //         },

    //         error: function (xhr, ajaxOptions, thrownError) {
    //             alert(xhr.status);
    //             alert(thrownError);
    //         }
    //     });            
    // });

    // function createJSTree(jsondata) {            
    //     $('#tree').jstree({
    //             "data" : jsondata,
    //     });
    // }


    // $.ajaxSetup({ async: true });
});

</script>

@endsection
