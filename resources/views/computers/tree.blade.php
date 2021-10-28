@extends('layouts.app')
@section('title')
<div class="page-header">
  <h1>{{ $page_title ?? '' }}</h1>
</div>
@endsection
@section('content')



<style>
* {
  box-sizing: border-box;
}

/* .column {
  float: left;
  min-width: 300px;
  padding: 10px;
}

.row:after {
  content: "";
  display: table;
  clear: both;
}

.left {
    width: calc(50% - 100px);
}

.right {
    width: calc(50% - 100px);
}


@media screen and (max-width: 600px) {
  .column {
    width: 100%;
  }
} */




.row {
    width: 100%;
}
.right, .left {
    width:100%;
}
@media (min-width: 48em) {
    .right {
        width: 70%;
        float:left;
    }
    .left {
        width: 30%;
        float:left;
    }

    .row {
        content:"";
        display: table;
        clear: both;
    }
}

.list-computers li span {
    cursor: pointer;
}

</style>



<div class="row">
    <div class="column left">
        <h2>Список компьютеров</h2>
        <div id="list-computers"></div>
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


    $.ajax({
        type: "GET",
        url: '/api/v1/computers',
        success: function (data) {
            console.log(data);
            var htmlComputerList = '<ul>';

            for (var computer in data) {
                htmlComputerList = htmlComputerList + '<li><span id="computer-id_' + data[computer].name + '">' + data[computer].name + '</span></li>';
            }
            htmlComputerList = htmlComputerList + '</ul>';
            $("#list-computers").html(htmlComputerList);

        },
        error: function (jqXHR, text, error) {
            console.log(error);
        }
    });


    $("[id^='computer-id_']").click(function () {
        var objData = $(this);
        console.log(objData);
        computerName = ($(this).id).replace('computer-id_', '');
        console.log(computerName);
        $('#header-devmng').html('Устройства компьютера: ' + computerName);
        renderComputerTree(computerName);
    });



    function renderComputerTree(computerName){
        $('#tree').fancytree({
            tooltip: true,
            iconTooltip: function(event, data) {
                return data.typeInfo.iconTooltip;
            },
            source: {url: '/api/v1/computers/'+computerName+'/properties'},
        });
    }
  



});

</script>

@endsection
