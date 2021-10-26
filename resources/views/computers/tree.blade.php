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


    $(function(){
      // using default options
      $("#tree").fancytree({
        tooltip: true,
        iconTooltip: function(event, data) {
            return data.typeInfo.iconTooltip;
        },
        source: {url: "/api/v1/computers/6/properties"},


        // postProcess: function(event, data) {

        // data = data.children.map(item => {
        //         return item * 2
        //     });

        // console.log(data);

        // //   // Convert incoming ITIS format to native Fancytree
        // //   var response = data.response;
        // //   data.node.info(response);
        // //   switch( response.class ) {
        // //   case "gov.usgs.itis.itis_service.metadata.SvcKingdomNameList":
        // //     data.result = $.map(response.kingdomNames, function(o){
        // //       return o && {title: o.kingdomName, key: o.tsn, folder: true, lazy: true};
        // //     });
        // //     break;
        // //   case "gov.usgs.itis.itis_service.data.SvcHierarchyRecordList":
        // //     data.result = $.map(response.hierarchyList, function(o){
        // //       return o && {title: o.taxonName, key: o.tsn, folder: true, lazy: true};
        // //     });
        // //     break;
        // //   default:
        // //     $.error("Unsupported class: " + response.class);
        // //   }

        // }




      });
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
