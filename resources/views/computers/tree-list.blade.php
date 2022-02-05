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
* {
  box-sizing: border-box;
}

/*
.column {
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
}
*/




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
                    <div class="column left">
                        <h5>Список компьютеров</h5>
                        <div class="list-computers" id="list-computers"  style="height: 620px; overflow: scroll;"></div>
                    </div>
                    <div class="column right">
                        <h5 id="header-devmng">Диспетчер устройств</h5>
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
var computerName;
var computerId;


    $.ajax({
        type: "GET",
        url: '/api/v1/computers-list',
        success: function (data) {
            // console.log(data);
            var htmlComputerList = '<ul>';

            for (var computer in data) {
                htmlComputerList = htmlComputerList + '<li><span id="computer-id_' + data[computer].id + '">' + data[computer].name + '</span></li>';
            }
            htmlComputerList = htmlComputerList + '</ul>';
            $("#list-computers").html(htmlComputerList);


            $("[id^='computer-id_']").click(function () {
                var objData = $(this);
                computerName = $(this).text();
                // console.log(computerName);
                computerId = (objData[0].id).replace('computer-id_', '');
                // console.log(computerId);
                $('#header-devmng').html('Устройства компьютера: ' + computerName);
                renderComputerTree(computerId);
            });



        },
        error: function (jqXHR, text, error) {
            console.log(error);
        }
    });




    function renderComputerTree(computerName){
        $('#tree').html('');
        // $('#tree').fancytree();
        $('#tree').fancytree({
            tooltip: true,
            iconTooltip: function(event, data) {
                return data.typeInfo.iconTooltip;
            },
            source: {url: '/api/v1/computers/'+computerName+'/hardware'},
        });

        // var newSourceOption = {
        //     tooltip: true,
        //     iconTooltip: function(event, data) {
        //         return data.typeInfo.iconTooltip;
        //     },
        //     source: {url: '/api/v1/computers/'+computerName+'/hardware'},
        // };

        // var tree = $('#tree').fancytree('getTree');
        // tree.reload(newSourceOption);

    }
  



});

</script>

@endsection
