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

/* Create two unequal columns that floats next to each other */
.column {
  float: left;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

.left {
  width: 25%;
}

.right {
  width: 75%;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

</style>



<div class="row">
    <div class="column left" style="background-color:#aaa;">
        <h2>Список компьютеров</h2>
        <p>Some text..</p>
    </div>
    <div class="column right" style="background-color:#bbb;">
        <h2>Диспетчер устройств</h2>
        <div class="computer-tree" id="tree"></div>
        <div id="statusline"></div>
    </div>
</div>







<script>
$(document).ready(function () {

    $(function(){
        $("#tree").fancytree({
            tooltip: true,
            iconTooltip: function(event, data) {
                return data.typeInfo.iconTooltip;
            },
            source: {url: "/api/v1/computers/{{ $computer->id }}/properties"},


        });
    });




});

</script>

@endsection
