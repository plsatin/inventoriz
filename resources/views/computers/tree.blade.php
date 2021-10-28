@extends('layouts.app')
@section('title')
<div class="page-header">
  <h1></h1>
</div>
@endsection
@section('content')



<style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 25%;
  min-width: 300px;
  padding: 10px;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

/* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
@media screen and (max-width: 600px) {
  .column {
    width: 100%;
  }
}</style>



<div class="row">
    <div class="column left">
        <h2>Список компьютеров</h2>
        <p>Some text..</p>
    </div>
    <div class="column right">
        <h2 id="header-devmng">Диспетчер устройств</h2>
        <div class="computer-tree" id="tree"></div>
        <div id="statusline"></div>
    </div>
</div>







<script>
$(document).ready(function () {
var computerName;

    $(function(){
        $("#tree").fancytree({
            tooltip: true,
            iconTooltip: function(event, data) {
                // console.log(data);
                return data.typeInfo.iconTooltip;

            },
            source: {url: "/api/v1/computers/{{ $computer->id }}/properties"},
            function(event, data) {
                console.log(data);
                
            },
        });


    });

    

    // $("#header-devmng").html("Диспетчер устройств " + data.name);



});

</script>

@endsection
