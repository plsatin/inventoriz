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
        height: 600px;
        overflow-x: hidden;
        overflow-y: scroll;
    }
</style>


<div class="container" id="main-container"></div>





<script>
    $(document).ready(function () {

        if (localStorage.token) {

            /* Получаем объект пользователь из localStorage */
            objUser = JSON.parse(localStorage.getItem('objUser'));
            role_id = objUser.role_id;


            main_container_html = '' +
                '<div class="row">' +
                    '<div class="col-xs-24">' +
                        '<section class="section">' +
                            '<header class="section-header">' +
                                '<h1 class="section-title">' +
                                    'Инвентаризация' +
                                '</h1>' +
                            '</header>' +
                            '<div class="row">' +
                                '<div class="col-md-24">' +
                                    '<div class="computer-tree" id="tree"></div>' +
                                    '<div id="statusline"></div>' +
                                '</div>' +
                            '</div>' +
                        '</section>' +
                    '</div>' +
                '</div>';
            $('#main-container').html(main_container_html);



            var computerName;
            var computerId;



            var computerName = "{{ $computer->name }}";
            var computerId = "{{ $computer->id }}";

            renderComputerTree(computerId);


        } else {

            localStorage.clear();

            alerts_msg = '<div class="alert alert-warning alert-dismissible fade in" role="alert">' +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true"><i class="glyph glyph-cancel"></i></span>' +
                '</button>' +
                '<div class="container">' +
                    '<div class="row">' +
                        '<div class="col-md-20">' +
                            '<p>' +
                                'Вы не авторизованы! Пройдите процедуру авторизации!' +
                            '</p>' +
                        '</div>' +
                    '</div>' +
                '</div>' +
            '</div>';

            $('#header-alert-stack').html(alerts_msg);

        } // if token

    });






    function renderComputerTree(computerId){
        $('#tree').fancytree({
            source: {url: '/api/v1/computers/'+computerId+'/hardware'},
            tooltip: true,
            iconTooltip: function(event, data) {
                return data.typeInfo.iconTooltip;
            },
            icon: function(event, data) {
                if (data.node.icon) {
                    return '/assets/img/icons/' + data.node.icon;
                }
            },

        });
    }



</script>

@endsection
