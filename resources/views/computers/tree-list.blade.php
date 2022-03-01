@extends('layouts.app')
@section('title')

@endsection
@section('content')


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
                                '<div class="col-md-8">' +
                                    '<h5>Список компьютеров</h5>' +
                                    '<div class="list-computers" id="list-computers"></div>' +
                                '</div>' +
                                '<div class="col-md-16">' +
                                    '<h5 id="header-devmng"></h5>' +
                                    '<div class="computer-tree" id="tree" ></div>' +
                                    '<div id="statusline"></div>' +
                                '</div>' +
                            '</div>' +
                        '</section>' +
                    '</div>' +
                '</div>';

            $('#main-container').html(main_container_html);




            var computerName;
            var computerId;

            var treeFirstRender = true;


            $.ajax({
                type: "GET",
                url: '/api/v1/computers-list',
                beforeSend: function (xhr) {
                    if (localStorage.token) {
                        xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.token);
                    }
                },
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
                        console.log(computerName);
                        computerId = (objData[0].id).replace('computer-id_', '');
                        console.log(computerId);
                        // $('#tree').html('');
                        $('#header-devmng').html('Диспетчер устройств: ' + computerName);
                        renderComputerTree(computerId);
                    });



                },
                error: function (jqXHR, text, error) {
                    console.log(error);
                }
            });

        } else {

            localStorage.clear();

            alerts_msg = '' +
                '<div class="alert alert-warning alert-dismissible fade in" role="alert">' +
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

        if (treeFirstRender) {
            
        } else {
            $("#tree").fancytree("destroy");
        }
        

        treeUrl = '/api/v1/computers/' + computerId + '/hardware';
        console.log(treeUrl);

        $('#tree').fancytree({
            ajax: { type: 'GET',
                beforeSend: function (xhr) {
                    if (localStorage.token) {
                        xhr.setRequestHeader('Authorization', 'Bearer ' + localStorage.token);
                    }
                }
            },
            source: { url: treeUrl, cache: false },
            tooltip: true,
            iconTooltip: function(event, data) {
                return data.typeInfo.iconTooltip;
            },
            icon: function(event, data) {
                if (data.node.icon) {
                    return '/assets/img/icons/' + data.node.icon;
                }
            },
            postProcess: function(event, data)
            {
                console.log(data);
            },

        });

        treeFirstRender = false;
    }


</script>

@endsection
