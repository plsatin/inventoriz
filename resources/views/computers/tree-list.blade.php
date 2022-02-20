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
        height: 560px;
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
                    <div class="col-md-8">
                        <h5>Список компьютеров</h5>
                        <div class="list-computers" id="list-computers"></div>
                    </div>
                    <div class="col-md-16">
                        <h5 id="header-devmng"></h5>
                        <div class="computer-tree" id="tree" ></div>
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
            var htmlComputerList = '<div class="entity-list">';

            for (var computer in data) {
                htmlComputerList = htmlComputerList + 
                '<div class="entity-list-item">' +
                    '<div class="item-icon">' +
                        '<span class="glyph glyph-devices"></span>' +
                    '</div>' +
                    '<div class="item-content-primary">' +
                        '<div class="content-text-primary" style="cursor: pointer;">' +
                            '<span id="computer-id_' + data[computer].id + '">' + data[computer].name + '</span>' +
                        '</div>' +
                        '<div class="content-text-secondary"><span class="type-t9">Состояние на: ' + data[computer].last_inventory_start + '</span></div>' +
                    '</div>' +
                '</div>';
            }
            htmlComputerList = htmlComputerList + '</div>';
            $("#list-computers").html(htmlComputerList);


            $("[id^='computer-id_']").click(function () {
                var objData = $(this);
                computerName = $(this).text();
                // console.log(computerName);
                computerId = (objData[0].id).replace('computer-id_', '');
                // console.log(computerId);
                $('#header-devmng').html('Диспетчер устройств: ' + computerName);
                renderComputerTree(computerId);
            });



        },
        error: function (jqXHR, text, error) {
            console.log(error);
        }
    });




    function renderComputerTree(computerId){
        $('#tree').fancytree({
            source: {url: '/api/v1/computers/'+computerId+'/properties'},
            tooltip: true,
            iconTooltip: function(event, data) {
                return data.typeInfo.iconTooltip;
            },
            activate: function(event, data){
                var node = data.node;
                node.icon = '/assets/img/icons/' + node.icon;
                node.renderTitle();
            },

        });
    }
  



});

</script>

@endsection
